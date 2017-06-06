<?php

define("HOST", "127.0.0.1");
define("DBNAME", "gestockDB");
define("USER", "gestockAdminDB");
define("PASSWORD", "gestockTPI2017");
define("NUMBER_PRODUCTS_SHOWN", 9);

class Gestock
{
    private $dbc = null;
    private $ps_nbProducts;
    private $ps_R_categories;
    private $ps_R_products;

    public function __construct()
    {
        try
        {
            $this->dbc = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USER, PASSWORD, 
                                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                                    PDO::ATTR_PERSISTENT => true,
                                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

            $this->ps_nbProducts = $this->dbc->prepare("SELECT FOUND_ROWS()AS NB_ROWS");
            $this->ps_nbProducts->setFetchMode(PDO::FETCH_ASSOC);

            $this->ps_R_categories = $this->dbc->prepare("SELECT categories.name FROM categories");
            $this->ps_R_categories->setFetchMode(PDO::FETCH_ASSOC);

            $limit = NUMBER_PRODUCTS_SHOWN;
            $this->ps_R_products = $this->dbc->prepare("SELECT SQL_CALC_FOUND_ROWS products.* FROM products LIMIT :limit OFFSET :offset");
            $this->ps_R_products->bindParam(":limit", $limit, PDO::PARAM_INT);
            $this->ps_R_products->setFetchMode(PDO::FETCH_ASSOC);
        }
        catch (Exception $e)
        {
            print "Error ! " . $e->getMessage() . "<br/>";
            error_log("Erreur !  " . $e->getMessage());
            die();
        }
    }

    public function getNbProducts()
    {
        $this->ps_nbProducts->execute();
        return $this->ps_nbProducts->fetchAll();
    }

    public function getCategories()
    {
        $this->ps_R_categories->execute();
        return $this->ps_R_categories->fetchAll();
    }

    public function getProducts($offset)
    {
        $this->ps_R_products->bindParam(":offset", $offset, PDO::PARAM_INT);
        $this->ps_R_products->execute();
        return $this->ps_R_products->fetchAll();
    }
}

?>