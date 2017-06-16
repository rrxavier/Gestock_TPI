<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            Gestock.php
# Date :                14.06.17
#--------------------------------------------------------------------------
# Main class of the website. It mainly does the SQL requests.
# All the prepare statements are created in the object constructor.
# Then, they're encapsulated in functions that execute them.
#
# Version 1.0 :         14.06.17
#--------------------------------------------------------------------------

define("HOST", "127.0.0.1");
define("DBNAME", "gestockDB");
define("USER", "gestockAdminDB");
define("PASSWORD", "gestockTPI2017");
define("NUMBER_PRODUCTS_SHOWN", 9);

 /**
  * Main class of the website. It mainly does the SQL requests.
  */
class Gestock
{
    private static $objInstance;
    private $dbc = null;
    private $ps_nbProducts;
    private $ps_R_categories;
    private $ps_R_products;
    private $ps_R_product_by_id;
    private $ps_R_product_of_category;
    private $ps_C_user;
    private $ps_R_user_by_username;
    private $ps_R_user_by_email;
    private $ps_R_user_by_id;
    private $ps_U_user_money;
    private $ps_U_stocks_quantity;

    private $ps_R_cart;
    private $ps_C_cart;
    private $ps_C_carts_has_stocks;
    private $ps_R_carts_has_stocks;
    private $ps_R_carts_has_stocks_oneProduct;
    private $ps_U_carts_has_stocks_quantity;
    private $ps_D_carts_has_stocks;
    private $ps_D_carts_has_stocks_oneProduct;
    private $ps_R_carts_has_stocks_limit;
    private $ps_R_carts_has_stocks_totalPrice;
    private $ps_R_carts_has_stocks_notInStock;
    private $ps_U_carts;

    private $ps_R_previousOrders_limit;
    private $ps_R_previousOrder_products;
    private $ps_R_previousOrders;  

    private $ps_R_products_LIKE;
    private $ps_R_stocks;
    private $ps_C_product;
    private $ps_C_stocks_has_product;
    private $ps_D_product;
    private $ps_U_product;

    private $ps_R_users;
    private $ps_U_user;
    private $ps_D_user;

    private $ps_R_products_low;

    /**
     * Constructor of the object. Initialises the PDO object and prepares the SQL statements.
     */
    private function __construct()
    {
        try
        { 
            // PDO initialisation
            $this->dbc = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USER, PASSWORD, 
                                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                                    PDO::ATTR_PERSISTENT => true,
                                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

            // Gets the value processed by the SQL_CALC_FOUND_ROWS. Used to return the total number of items of a given category.
            $this->ps_nbProducts = $this->dbc->prepare("SELECT FOUND_ROWS() AS NB_ROWS");
            $this->ps_nbProducts->setFetchMode(PDO::FETCH_ASSOC);

            // Selects all categories.
            $this->ps_R_categories = $this->dbc->prepare("SELECT categories.* FROM categories");
            $this->ps_R_categories->setFetchMode(PDO::FETCH_ASSOC);

            // Selects the next 9(NUMBER_PRODUCTS_SHOWN) products.
            $limit = NUMBER_PRODUCTS_SHOWN;
            $this->ps_R_products = $this->dbc->prepare("SELECT SQL_CALC_FOUND_ROWS products_with_info.* FROM products_with_info LIMIT :limit OFFSET :offset");
            $this->ps_R_products->bindParam(":limit", $limit, PDO::PARAM_INT);
            $this->ps_R_products->setFetchMode(PDO::FETCH_ASSOC);

            // Selects all the info of a certain product.
            $this->ps_R_product_by_id = $this->dbc->prepare("SELECT products_with_info.*, stocks.id AS idStock, stocks.shelf AS shelf
                                                                FROM products_with_info, stocks_has_product, stocks
                                                                WHERE products_with_info.id = :idProduct
                                                                AND stocks_has_product.idProduct_fk = products_with_info.id
                                                                AND stocks.id = stocks_has_product.idStock_fk");
            $this->ps_R_product_by_id->setFetchMode(PDO::FETCH_ASSOC);

            // Selects the next 9(NUMBER_PRODUCTS_SHOWN) products of a given category.
            $this->ps_R_product_of_category = $this->dbc->prepare("SELECT SQL_CALC_FOUND_ROWS products.* FROM products WHERE products.idCategory_fk = :idCategory LIMIT :limit OFFSET :offset");
            $this->ps_R_product_of_category->bindParam(":limit", $limit, PDO::PARAM_INT);
            $this->ps_R_product_of_category->setFetchMode(PDO::FETCH_ASSOC);

            // Creates a new username.
            $this->ps_C_user = $this->dbc->prepare("INSERT INTO users VALUES (null, :username, :email, :password, 500, 1)");
            $this->ps_C_user->setFetchMode(PDO::FETCH_ASSOC);

            // Checks if the username/password combination exists. Used for authentification.
            $this->ps_R_user_by_username = $this->dbc->prepare("SELECT * FROM users WHERE users.username = :username AND users.password = :password");
            $this->ps_R_user_by_username->setFetchMode(PDO::FETCH_ASSOC);

            // Checks if the email/password combination exists. Used for authentification.
            $this->ps_R_user_by_email = $this->dbc->prepare("SELECT * FROM users WHERE users.email = :email AND users.password = :password");
            $this->ps_R_user_by_email->setFetchMode(PDO::FETCH_ASSOC);

            // Gets the current cart of the selected user. /!\ A cart is only a cart if it doesn't have a date of order. /!\
            $this->ps_R_cart = $this->dbc->prepare("SELECT * FROM carts WHERE carts.idUser_fk = :idUser AND carts.dateOrder IS null");
            $this->ps_R_cart->setFetchMode(PDO::FETCH_ASSOC);

            // Creates a new cart for the selected user.
            $this->ps_C_cart = $this->dbc->prepare("INSERT INTO carts VALUES (null, :idUser, null)");
            $this->ps_C_cart->setFetchMode(PDO::FETCH_ASSOC);

            // Inserts a product in a cart.
            $this->ps_C_carts_has_stocks = $this->dbc->prepare("INSERT INTO carts_has_stocks SELECT null, :idCart, stocks_has_product.id, :quantity FROM stocks_has_product WHERE stocks_has_product.idProduct_fk = :idProduct");
            $this->ps_C_carts_has_stocks->setFetchMode(PDO::FETCH_ASSOC);

            // Gets all the items of the cart of the selected user.
            $this->ps_R_carts_has_stocks = $this->dbc->prepare("SELECT products_with_info.*, carts_has_stocks.quantity AS cartQuantity FROM products_with_info, stocks_has_product, carts_has_stocks, carts 
                                                                WHERE carts.idUser_fk = :idUser 
                                                                AND  carts_has_stocks.idCart_fk = carts.id
                                                                AND stocks_has_product.id = carts_has_stocks.idStock_has_product_fk
                                                                AND products_with_info.id = stocks_has_product.idProduct_fk
                                                                AND carts.dateOrder IS null
                                                                GROUP BY products_with_info.id");
            $this->ps_R_carts_has_stocks->setFetchMode(PDO::FETCH_ASSOC);

            // Gets the given item of the selected user's cart.
            $this->ps_R_carts_has_stocks_oneProduct = $this->dbc->prepare("SELECT products.*, carts_has_stocks.quantity AS cartQuantity FROM products, stocks_has_product, carts_has_stocks, carts 
                                                                WHERE carts.idUser_fk = :idUser 
                                                                AND  carts_has_stocks.idCart_fk = carts.id
                                                                AND stocks_has_product.id = carts_has_stocks.idStock_has_product_fk
                                                                AND products.id = stocks_has_product.idProduct_fk
                                                                AND products.id = :idProduct
                                                                AND carts.dateOrder IS null
                                                                GROUP BY products.id");
            $this->ps_R_carts_has_stocks_oneProduct->setFetchMode(PDO::FETCH_ASSOC);

            // Sets the new quantity of the item in the user cart.
            $this->ps_U_carts_has_stocks_quantity = $this->dbc->prepare("UPDATE carts_has_stocks, products, stocks_has_product, carts 
                                                                SET carts_has_stocks.quantity = :quantity + carts_has_stocks.quantity
                                                                WHERE carts.idUser_fk = :idUser 
                                                                AND  carts_has_stocks.idCart_fk = carts.id
                                                                AND stocks_has_product.id = carts_has_stocks.idStock_has_product_fk
                                                                AND products.id = stocks_has_product.idProduct_fk
                                                                AND carts.dateOrder IS null
                                                                AND products.id = :idProduct");
            $this->ps_U_carts_has_stocks_quantity->setFetchMode(PDO::FETCH_ASSOC);

            // Deletes completely a product from the current cart of the user.
            $this->ps_D_carts_has_stocks = $this->dbc->prepare("DELETE carts_has_stocks FROM carts, carts_has_stocks, stocks_has_product 
                                                                WHERE carts.idUser_fk = :idUser
                                                                AND carts_has_stocks.idCart_fk = carts.id 
                                                                AND carts_has_stocks.idStock_has_product_fk = stocks_has_product.id
                                                                AND stocks_has_product.idProduct_fk = :idProduct
                                                                AND carts.dateOrder IS null");
            $this->ps_D_carts_has_stocks->setFetchMode(PDO::FETCH_ASSOC);

            // Deletes a product from the current cart of the user.
            $this->ps_D_carts_has_stocks = $this->dbc->prepare("DELETE carts_has_stocks FROM carts, carts_has_stocks, stocks_has_product 
                                                                WHERE carts.idUser_fk = :idUser
                                                                AND carts_has_stocks.idCart_fk = carts.id 
                                                                AND carts_has_stocks.idStock_has_product_fk = stocks_has_product.id
                                                                AND stocks_has_product.idProduct_fk = :idProduct
                                                                AND carts.dateOrder IS null");
            $this->ps_D_carts_has_stocks->setFetchMode(PDO::FETCH_ASSOC);

            // Gets the first five most expensive products. Used for the cart preview.
            $this->ps_R_carts_has_stocks_limit = $this->dbc->prepare("SELECT products.*, carts_has_stocks.quantity AS cartQuantity FROM products, stocks_has_product, carts_has_stocks, carts 
                                                                WHERE carts.idUser_fk = :idUser 
                                                                AND  carts_has_stocks.idCart_fk = carts.id
                                                                AND stocks_has_product.id = carts_has_stocks.idStock_has_product_fk
                                                                AND products.id = stocks_has_product.idProduct_fk
                                                                AND carts.dateOrder IS null
                                                                GROUP BY products.id
                                                                ORDER BY products.price DESC
                                                                LIMIT 5 OFFSET 0");
            $this->ps_R_carts_has_stocks_limit->setFetchMode(PDO::FETCH_ASSOC);

            // Gets the total price of the cart of the selected user.
            $this->ps_R_carts_has_stocks_totalPrice = $this->dbc->prepare("SELECT SUM(products.price * carts_has_stocks.quantity) AS totalPrice FROM products, stocks_has_product, carts_has_stocks, carts 
                                                                WHERE carts.idUser_fk = :idUser
                                                                AND  carts_has_stocks.idCart_fk = carts.id
                                                                AND stocks_has_product.id = carts_has_stocks.idStock_has_product_fk
                                                                AND products.id = stocks_has_product.idProduct_fk
                                                                AND carts.dateOrder IS null
                                                                GROUP BY carts.id");
            $this->ps_R_carts_has_stocks_totalPrice->setFetchMode(PDO::FETCH_ASSOC);

            // Gets all the info of the given user.
            $this->ps_R_user_by_id = $this->dbc->prepare("SELECT * FROM users WHERE users.id = :idUser");
            $this->ps_R_user_by_id->setFetchMode(PDO::FETCH_ASSOC);

            // Gets all the items that are in a cart but with not enough in stock.
            $this->ps_R_carts_has_stocks_notInStock = $this->dbc->prepare("SELECT * FROM (SELECT products.*, stocks_has_product.quantity, carts_has_stocks.quantity AS cartQuantity, (stocks_has_product.quantity  - SUM(carts_has_stocks.quantity)) AS stockQuantityResult FROM products, stocks_has_product, carts_has_stocks, carts
                                                                           WHERE carts.idUser_fk = :idUser
                                                                           AND carts_has_stocks.idCart_fk = carts.id
                                                                           AND stocks_has_product.id = carts_has_stocks.idStock_has_product_fk
                                                                           AND products.id = stocks_has_product.idProduct_fk
                                                                           AND carts.dateOrder IS null
                                                                           GROUP BY products.id) as tmpTable
                                                                           WHERE tmpTable.stockQuantityResult < 0");
            $this->ps_R_carts_has_stocks_notInStock->setFetchMode(PDO::FETCH_ASSOC);

            // Sets an order date in a given cart.
            $this->ps_U_cart = $this->dbc->prepare("UPDATE carts SET dateOrder = NOW() WHERE carts.idUser_fk = :idUser AND carts.dateOrder IS null");
            $this->ps_U_cart->setFetchMode(PDO::FETCH_ASSOC);

            // Debits the amout of the order from the user's money pool.
            $this->ps_U_user_money = $this->dbc->prepare("UPDATE users SET users.money = (users.money - :cost) WHERE users.id = :idUser");
            $this->ps_U_user_money->setFetchMode(PDO::FETCH_ASSOC);

            // Substracts the quantity of the order from the quantity in the stock.
            $this->ps_U_stocks_quantity = $this->dbc->prepare("UPDATE stocks_has_product, products_with_info, carts_has_stocks, carts 
                                                                SET stocks_has_product.quantity = stocks_has_product.quantity - carts_has_stocks.quantity
                                                                WHERE carts.idUser_fk = :idUser 
                                                                AND  carts_has_stocks.idCart_fk = carts.id
                                                                AND stocks_has_product.id = carts_has_stocks.idStock_has_product_fk
                                                                AND products_with_info.id = stocks_has_product.idProduct_fk
                                                                AND carts.dateOrder IS null");
            $this->ps_U_stocks_quantity->setFetchMode(PDO::FETCH_ASSOC);

            // Gets the first five most expensive products. Used for the cart preview.
            $this->ps_R_previousOrders_limit = $this->dbc->prepare("SELECT carts.* FROM carts 
                                                                    WHERE carts.idUser_fk = :idUser 
                                                                    AND carts.dateOrder IS NOT null
                                                                    ORDER BY carts.id DESC
                                                                    LIMIT 5 OFFSET 0");
            $this->ps_R_previousOrders_limit->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_R_previousOrder_products = $this->dbc->prepare('SELECT products_with_info.*, carts_has_stocks.quantity AS cartQuantity FROM products_with_info, stocks_has_product, carts_has_stocks, carts 
                                                                WHERE carts.idUser_fk = :idUser 
                                                                AND carts.id = :idCart
                                                                AND carts_has_stocks.idCart_fk = carts.id
                                                                AND stocks_has_product.id = carts_has_stocks.idStock_has_product_fk
                                                                AND products_with_info.id = stocks_has_product.idProduct_fk
                                                                AND carts.dateOrder IS NOT null');
            $this->ps_R_previousOrder_products->setFetchMode(PDO::FETCH_ASSOC);

            // Gets the first five most expensive products. Used for the cart preview.
            $this->ps_R_previousOrders = $this->dbc->prepare("SELECT carts.* FROM carts 
                                                                    WHERE carts.idUser_fk = :idUser 
                                                                    AND carts.dateOrder IS NOT null
                                                                    ORDER BY carts.id DESC");
            $this->ps_R_previousOrders->setFetchMode(PDO::FETCH_ASSOC);
            
            // Selects the next 9(NUMBER_PRODUCTS_SHOWN) products.
            $this->ps_R_products_LIKE = $this->dbc->prepare("SELECT SQL_CALC_FOUND_ROWS products_with_info.* FROM products_with_info WHERE products_with_info.name LIKE :searchToken LIMIT :limit OFFSET :offset");
            $this->ps_R_products_LIKE->bindParam(":limit", $limit, PDO::PARAM_INT);
            $this->ps_R_products_LIKE->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_R_stocks = $this->dbc->prepare("SELECT * FROM stocks");
            $this->ps_R_stocks->setFetchMode(PDO::FETCH_ASSOC);
    
            $this->ps_C_product = $this->dbc->prepare("INSERT INTO products VALUES (null, :name, :brand, :price, 5, :imgName, :idCategory)");
            $this->ps_C_product->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_C_stocks_has_product = $this->dbc->prepare("INSERT INTO stocks_has_product VALUES (null, :idStock, LAST_INSERT_ID(), :quantity)");
            $this->ps_C_stocks_has_product->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_D_product = $this->dbc->prepare("DELETE FROM stocks_has_product WHERE stocks_has_product.idProduct_fk = :idProduct1;
                                                       DELETE FROM products WHERE products.id = :idProduct2;");
            $this->ps_D_product->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_U_product = $this->dbc->prepare('UPDATE stocks_has_product, products
                                                        SET stocks_has_product.idStock_fk = :idStock, 
                                                        stocks_has_product.quantity = :quantity,
                                                        products.name = :name,
                                                        products.brand = :brand,
                                                        products.price = :price,
                                                        products.imgName = 
                                                        CASE
                                                        WHEN :imgName1 != "" THEN :imgName2
                                                        ELSE products.imgName
                                                        END,
                                                        products.idCategory_fk = :idCategory
                                                        WHERE products.id = :idProduct
                                                        AND stocks_has_product.idProduct_fk = products.id');
            $this->ps_U_product->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_R_users = $this->dbc->prepare('SELECT * FROM users');
            $this->ps_R_users->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_U_users = $this->dbc->prepare('UPDATE users 
                                                        SET users.username = :username, 
                                                        users.email = :email, 
                                                        users.money = :money 
                                                        WHERE users.id = :idUser');
            $this->ps_U_users->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_D_users = $this->dbc->prepare('DELETE FROM users WHERE users.id = :idUser');
            $this->ps_D_users->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_R_products_low = $this->dbc->prepare('SELECT * FROM products_with_info AS p WHERE p.stockQuantity <= p.alertQuantity');
            $this->ps_R_products_low->setFetchMode(PDO::FETCH_ASSOC);  
        }
        catch (Exception $e)
        {
            print "Error ! " . $e->getMessage() . "<br/>";
            error_log("Erreur !  " . $e->getMessage());
            die();
        }
    }

    /**
     * Returns the instance of the object.
     * @return Gestock The instance of the object.
     */
    public static function getInstance()
    {
        if (!self::$objInstance) 
            self::$objInstance = new Gestock();
        return self::$objInstance;
    }
    /**
     * Processes the total number of products of the last set of products.
     * @return array 
     */
    public function getNbProducts()
    {
        $this->ps_nbProducts->execute();
        return $this->ps_nbProducts->fetchAll();
    }

    /**
     * Gets all the Categories of the DB.
     * @return array Array containing all the categories and their fields.
     */
    public function getCategories()
    {
        try
        {
            $this->ps_R_categories->execute();
            return $this->ps_R_categories->fetchAll();
        }
        catch (Exception $e)
        {
            error_log($e);
            echo 'Website unavailable, please try again later.';
            die();
        }
    }

    /**
     * Gets all the fields of one selected product.
     * @param int $idProduct The id of the product.
     * @return array Array containing all the fields of the product.
     */
    public function getProductById($idProduct)
    {
        $this->ps_R_product_by_id->bindParam(":idProduct", $idProduct, PDO::PARAM_INT);
        $this->ps_R_product_by_id->execute();
        return $this->ps_R_product_by_id->fetchAll();
    }

    /**
     * Gets the next products, starting at a given offset.
     * @param int $offset Offset of the SQL statement.
     * @return array Array containing the different products, and their fields.
     */
    public function getProducts($offset)
    {
        $this->ps_R_products->bindParam(":offset", $offset, PDO::PARAM_INT);
        $this->ps_R_products->execute();
        return $this->ps_R_products->fetchAll();
    }

    /**
     * Gets all the products of a given category.
     * @param int $idCategory The id of the category.
     * @param int $offset Offset of the SQL statement.
     * @return type
     */
    public function getProductsOfCategory($idCategory, $offset)
    {
        $this->ps_R_product_of_category->bindParam(":offset", $offset, PDO::PARAM_INT);
        $this->ps_R_product_of_category->bindParam(":idCategory", $idCategory, PDO::PARAM_INT);
        $this->ps_R_product_of_category->execute();
        return $this->ps_R_product_of_category->fetchAll();
    }

    /**
     * Inserts a new user in the DB.
     * @param type $username Username of the user.
     * @param type $email Email of the user.
     * @param type $pwd Password of the user. Must be sha1 encrypted.
     * @return \Exception TRUE if the query succeeded, False if there is an unknown error, and the name of the field if the username OR email already exist in base.
     */
    public function insertUser($username, $email, $pwd)
    {
        try
        {
            $this->ps_C_user->bindParam(":username", $username);
            $this->ps_C_user->bindParam(":email", $email);
            $this->ps_C_user->bindParam(":password", $pwd);
            $this->ps_C_user->execute();

            return true;
        }
        catch (Exception $e)
        {
            error_log($e);
            if($e->errorInfo[0] == 23000)  // Username or Email already in use.
                    return explode("_", explode("'", $e->errorInfo[2])[3])[0];
            return false;
        }
    }

    /**
     * Selects the user with the given username and password.
     * @param type $username User's name.
     * @param type $password User's password.
     * @return \Exception Array with the user's info if it succeeds, FALSE if it fails.
     */
    public function authentifyByUsername($username, $password)
    {
        try
        {
            $this->ps_R_user_by_username->bindParam(":username", $username);
            $this->ps_R_user_by_username->bindParam(":password", $password);
            $this->ps_R_user_by_username->execute();

            $result = $this->ps_R_user_by_username->fetchAll();
            if(count($result) == 1)
                return $result;
            else
                return false;
        }
        catch (Exception $e)
        {
            error_log($e);
            return false;
        }
    }

    /**
     * Selects the user with the given email and password.
     * @param type $email User's email.
     * @param type $password User's password.
     * @return \Exception Array with the user's info if it succeeds, FALSE if it fails.
     */
    public function authentifyByEmail($email, $password)
    {
        try
        {
            $this->ps_R_user_by_email->bindParam(":email", $email);
            $this->ps_R_user_by_email->bindParam(":password", $password);
            $this->ps_R_user_by_email->execute();
            
            $result = $this->ps_R_user_by_email->fetchAll();
            if(count($result) == 1)
                return $result;
            else
                return false;
        }
        catch (Exception $e)
        {
            error_log($e);
            return false;
        }
    }

    /**
     * Get the current cart of a given user. If he doesn't have one, creates one.
     * @param type $idUser  The id of the owner of the cart.
     * @return \Exception   The cart if the script successfully executed, FALSE if an error occurred.
     */
    private function getCart($idUser)
    {
         try
        {
            $this->ps_R_cart->bindParam(":idUser", $idUser, PDO::PARAM_INT);
            $this->ps_R_cart->execute();

            $result = $this->ps_R_cart->fetchAll();

            if(count($result) == 1)
                return $result;
            else
            {
                $this->ps_C_cart->bindParam(":idUser", $idUser, PDO::PARAM_INT);
                $this->ps_C_cart->execute();
                $this->ps_R_cart->execute();

                return $this->ps_R_cart->fetchAll();
            }
        }
        catch (Exception $e)
        {
            error_log($e);
            return $e;
        }
    }

    /**
     * Adds the selected product into the cart.
     * @param type $idProduct ID of the product to add.
     * @param type $quantity Quantity to add.
     * @param type $idUser ID of the owner of the cart.
     * @return boolean TRUE if everything goes well, FALSE if an error occurred.
     */
    public function insertProductIntoCart($idProduct, $quantity, $idUser)
    {
        try
        {
            $this->ps_R_carts_has_stocks_oneProduct->bindParam(":idUser", $idUser);
            $this->ps_R_carts_has_stocks_oneProduct->bindParam(":idProduct", $idProduct);
            $this->ps_R_carts_has_stocks_oneProduct->execute();
            $existingLine = $this->ps_R_carts_has_stocks_oneProduct->fetchAll();

            if(count($existingLine) == 1) // IF EXISTS : CHANGE QUANTITY
            {
                if($quantity + $existingLine[0]['cartQuantity'] <= 0)   // IF the user is DELETING and the quantity reaches 0 or less : DELETE THE ROW
                    $this->deleteProductFromCart($idProduct, $idUser);
                else    // ELSE : CHANGE QUANTITY
                {
                    $this->ps_U_carts_has_stocks_quantity->bindParam(":idUser", $idUser);
                    $this->ps_U_carts_has_stocks_quantity->bindParam(":quantity", $quantity);
                    $this->ps_U_carts_has_stocks_quantity->bindParam(":idProduct", $idProduct);
                    $this->ps_U_carts_has_stocks_quantity->execute();
                }
            }
            else    // ELSE : CREATE ROW
            {
                $this->ps_C_carts_has_stocks->bindParam(":idCart", $this->getCart($idUser)[0]['id'], PDO::PARAM_INT);
                $this->ps_C_carts_has_stocks->bindParam(":quantity", $quantity, PDO::PARAM_INT);
                $this->ps_C_carts_has_stocks->bindParam(":idProduct", $idProduct, PDO::PARAM_INT);
                $this->ps_C_carts_has_stocks->execute();
            }
            return true;
        }
        catch (Exception $e)
        {
            error_log($e);
            return false;
        }
    }

    /**
     * Gets all the products of the cart of the selected user.
     * @param type $idUser ID of the owner of the cart.
     * @return boolean Array with the product if everything went well, FALSE if an error occurred.
     */
    public function getCartProducts($idUser)
    {
        try
        {
            $this->ps_R_carts_has_stocks->bindParam(":idUser", $idUser);
            $this->ps_R_carts_has_stocks->execute();

            return $this->ps_R_carts_has_stocks->fetchAll(); 
        }
        catch (Exception $e)
        {
            error_log($e);
            return false;
        }
    }

    /**
     * Delets a given product from the cart of the selected user.
     * @param type $idProduct ID of the product to delete.
     * @param type $idUser ID of the owner of the cart.
     * @return boolean TRUE if everything went well, FALSE if an error occurred.
     */
    public function deleteProductFromCart($idProduct, $idUser)
    {
        try
        {
            $this->ps_D_carts_has_stocks->bindParam(":idProduct", $idProduct, PDO::PARAM_INT);
            $this->ps_D_carts_has_stocks->bindParam(":idUser", $idUser, PDO::PARAM_INT);
            $this->ps_D_carts_has_stocks->execute();

            return true;
        }
        catch (Exception $e)
        {
            error_log($e);
            return false;
        }
    }

    /**
     * Gets the first five products of the user's cart. The products are ordered from the most expensive to the cheapest.
     * @param type $idUser ID of the owner of the cart.
     * @return type Array with the products if everything went well, empty Array if an error occurred.
     */
    public function getFirstCartProducts($idUser)
    {
        try
        {
            $this->ps_R_carts_has_stocks_limit->bindParam(":idUser", $idUser);
            $this->ps_R_carts_has_stocks_limit->execute();

            return $this->ps_R_carts_has_stocks_limit->fetchAll();
        }
        catch (Exception $e)
        {
            error_log($e);
            return Array();
        }
    }

    public function getUserInfo($idUser)
    {
        try
        {
            $this->ps_R_user_by_id->bindParam(":idUser", $idUser);
            $this->ps_R_user_by_id->execute();

            return $this->ps_R_user_by_id->fetchAll();
        }
        catch (Exception $e)
        {
            error_log($e);
            return null;
        }
    }

    public function passOrder($idUser)
    {
        try
        {
            $itemCount = count($this->getCartProducts($idUser)); 

            if($itemCount > 0)
            {
                $this->ps_R_carts_has_stocks_notInStock->bindParam(":idUser", $idUser);
                $this->ps_R_carts_has_stocks_notInStock->execute();
                $notInStockItems = $this->ps_R_carts_has_stocks_notInStock->fetchAll();
                if(count($notInStockItems))
                    return "NotInStock";
                else
                {
                    $this->ps_R_carts_has_stocks_totalPrice->bindParam(":idUser", $idUser);
                    $this->ps_R_carts_has_stocks_totalPrice->execute();
                    $totalPrice = $this->ps_R_carts_has_stocks_totalPrice->fetchAll()[0]['totalPrice'];

                    $user = $this->getUserInfo($idUser)[0];

                    if($totalPrice <= $user['money'])
                    {
                        $this->dbc->beginTransaction();

                        $this->ps_U_stocks_quantity->bindParam(":idUser", $idUser);
                        $this->ps_U_stocks_quantity->execute();

                        $this->ps_U_cart->bindParam(":idUser", $idUser);
                        $this->ps_U_cart->execute();

                        $this->ps_U_user_money->bindParam(":cost", $totalPrice);
                        $this->ps_U_user_money->bindParam(":idUser", $idUser);
                        $this->ps_U_user_money->execute();                       

                        $this->dbc->commit();

                        return true;
                    }
                    else
                        return "NotEnoughMoney";
                }        
            }
            else
                return "NoItemsInCart";
        }
        catch (Exception $e)
        {
            if(PDO::inTransaction())
                $this->dbc->rollback();
            error_log($e);
            return false;
        }
    }

    public function getFirstPreviousOrders($idUser)
    {
        try
        {
            $this->ps_R_previousOrders_limit->bindParam(":idUser", $idUser);
            $this->ps_R_previousOrders_limit->execute();

            return $this->ps_R_previousOrders_limit->fetchAll();
        }
        catch (Exception $e)
        {
            error_log($e);
            return Array();
        }
    }

    public function getPreviousOrderProducts($idUser, $idCart)
    {
        try
        {
            $this->ps_R_previousOrder_products->bindParam(":idUser", $idUser);
            $this->ps_R_previousOrder_products->bindParam(":idCart", $idCart);
            $this->ps_R_previousOrder_products->execute();

            return $this->ps_R_previousOrder_products->fetchAll();
        }
        catch (Exception $e)
        {
            error_log($e);
            return Array();
        }
    }

    public function getPreviousOrders($idUser)
    {
        try
        {
            $this->ps_R_previousOrders->bindParam(":idUser", $idUser);
            $this->ps_R_previousOrders->execute();

            return $this->ps_R_previousOrders->fetchAll();
        }
        catch (Exception $e)
        {
            error_log($e);
            return Array();
        }
    }
    
    public function getProductsLIKE($offset, $searchToken)
    {
        try
        {
            $searchToken = "%" . $searchToken . "%";
            $this->ps_R_products_LIKE->bindParam(":offset", $offset, PDO::PARAM_INT);
            $this->ps_R_products_LIKE->bindParam(":searchToken", $searchToken);
            $this->ps_R_products_LIKE->execute();
            return $this->ps_R_products_LIKE->fetchAll();
        }
        catch (Exception $e)
        {
            error_log($e);
            return Array();
        }
    }

    public function getStocks()
    {
        try
        {
            $this->ps_R_stocks->execute();
            return $this->ps_R_stocks->fetchAll();
        }
        catch (Exception $e)
        {
            error_log($e);
            return Array();
        }
    }
    
    public function insertProduct($name, $brand, $price, $idCategory, $quantity, $imgName, $idStock)
    {
        try
        {
            $this->dbc->beginTransaction();

            $this->ps_C_product->bindParam(':name', $name);
            $this->ps_C_product->bindParam(':brand', $brand);
            $this->ps_C_product->bindParam(':price', $price);
            $this->ps_C_product->bindParam(':imgName', $imgName);
            $this->ps_C_product->bindParam(':idCategory', $idCategory);
            $this->ps_C_product->execute();

            $this->ps_C_stocks_has_product->bindParam(':idStock', $idStock);
            $this->ps_C_stocks_has_product->bindParam(':quantity', $quantity);
            $this->ps_C_stocks_has_product->execute();

            $this->dbc->commit();
            return true;
        }
        catch (Exception $e)
        {
            $this->dbc->rollback();
            error_log($e);
            //echo $name, " ", $brand, " ", $price, " " . $idCategory, " ", $quantity, " ", $imgName, " ", $idStock;
            return $e;
        }
    }

    public function deleteProduct($idProduct)
    {
        try
        {
            $this->ps_D_product->bindParam(':idProduct1', $idProduct);
            $this->ps_D_product->bindParam(':idProduct2', $idProduct);
            $this->ps_D_product->execute();

            return true;
        }
        catch (Exception $e)
        {
            error_log($e);
            return $e;
        }
    }

    public function modifyProduct($name, $brand, $price, $idCategory, $quantity, $imgName = "", $idStock, $idProduct)
    {
        try
        {
            $this->dbc->beginTransaction();

            $this->ps_U_product->bindParam(':idStock', $idStock);
            $this->ps_U_product->bindParam(':quantity', $quantity);
            $this->ps_U_product->bindParam(':name', $name);
            $this->ps_U_product->bindParam(':brand', $brand);
            $this->ps_U_product->bindParam(':price', $price);
            $this->ps_U_product->bindParam(':imgName1', $imgName);
            $this->ps_U_product->bindParam(':imgName2', $imgName);
            $this->ps_U_product->bindParam(':idCategory', $idCategory);
            $this->ps_U_product->bindParam(':idProduct', $idProduct);
            $this->ps_U_product->execute();

            $this->dbc->commit();
            return true;
        }
        catch (Exception $e)
        {
            $this->dbc->rollback();
            error_log($e);
            return $e;
        }
    }

    public function getUsers()
    {
        try
        {
            $this->ps_R_users->execute();
            return $this->ps_R_users->fetchAll();
        }
        catch (Exception $e)
        {
            error_log($e);
            return Array();
        }        
    }

    public function modifyUser($idUser, $username, $email, $money)
    {
        try
        {
            $this->ps_U_users->bindParam('idUser', $idUser);
            $this->ps_U_users->bindParam('username', $username);
            $this->ps_U_users->bindParam('email', $email);
            $this->ps_U_users->bindParam('money', $money);
            $this->ps_U_users->execute();
            return true;
        }
        catch(Excetpion $e)
        {
            error_log($e);
            return $e;
        }
    }

    public function deleteUser($idUser)
    {
       try
        {
            $this->ps_D_users->bindParam('idUser', $idUser);
            $this->ps_D_users->execute();
            return true;
        }
        catch(Excetpion $e)
        {
            error_log($e);
            return $e;
        } 
    }

    public function getLowQuantityProducts()
    {
        try
        {
            $this->ps_R_products_low->execute();
            return $this->ps_R_products_low->fetchAll();
        }
        catch(Excetpion $e)
        {
            error_log($e);
            return $e;
        } 
    }
}

?>