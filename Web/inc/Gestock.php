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

    /**
     * Constructor of the object. Initialises the PDO object and prepares the SQL statements.
     */
    private function __construct()
    {
        try
        { 
            $this->dbc = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USER, PASSWORD, 
                                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                                    PDO::ATTR_PERSISTENT => true,
                                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

            $this->ps_nbProducts = $this->dbc->prepare("SELECT FOUND_ROWS() AS NB_ROWS");
            $this->ps_nbProducts->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_R_categories = $this->dbc->prepare("SELECT categories.* FROM categories");
            $this->ps_R_categories->setFetchMode(PDO::FETCH_ASSOC);

            $limit = NUMBER_PRODUCTS_SHOWN;
            $this->ps_R_products = $this->dbc->prepare("SELECT SQL_CALC_FOUND_ROWS products.* FROM products LIMIT :limit OFFSET :offset");
            $this->ps_R_products->bindParam(":limit", $limit, PDO::PARAM_INT);
            $this->ps_R_products->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_R_product_by_id = $this->dbc->prepare("SELECT products_with_category.* FROM products_with_category WHERE products_with_category.id = :idProduct");
            $this->ps_R_product_by_id->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_R_product_of_category = $this->dbc->prepare("SELECT SQL_CALC_FOUND_ROWS products.* FROM products WHERE products.idCategory_fk = :idCategory LIMIT :limit OFFSET :offset");
            $this->ps_R_product_of_category->bindParam(":limit", $limit, PDO::PARAM_INT);
            $this->ps_R_product_of_category->setFetchMode(PDO::FETCH_ASSOC);
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
}

?>