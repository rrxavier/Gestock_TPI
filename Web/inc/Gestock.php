<?php

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
            $this->ps_R_products = $this->dbc->prepare("SELECT SQL_CALC_FOUND_ROWS products.* FROM products LIMIT :limit OFFSET :offset");
            $this->ps_R_products->bindParam(":limit", $limit, PDO::PARAM_INT);
            $this->ps_R_products->setFetchMode(PDO::FETCH_ASSOC);

            // Selects all the info of a certain product for his details page.
            $this->ps_R_product_by_id = $this->dbc->prepare("SELECT products_with_category.* FROM products_with_category WHERE products_with_category.id = :idProduct");
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
            //$this->ps_R_user_by_email = $this->dbc->prepare("SELECT EXISTS (SELECT * FROM users WHERE users.email = :email AND users.password = :password) AS result");
            $this->ps_R_user_by_email = $this->dbc->prepare("SELECT * FROM users WHERE users.email = :email AND users.password = :password");
            $this->ps_R_user_by_email->setFetchMode(PDO::FETCH_ASSOC);
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
        $this->ps_R_categories->execute();
        return $this->ps_R_categories->fetchAll();
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
     * @return \Exception True if the query succeeded, False if there is an unknown error, and the name of the field if the username OR email already exist in base.
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
     * @return \Exception Array with the user's info if it succeeds, false if it fails.
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
     * @return \Exception Array with the user's info if it succeeds, false if it fails.
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
}

?>