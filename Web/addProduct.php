<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            addProduct.php
# Date :                15.06.17
#--------------------------------------------------------------------------
# This file is used to add a given product to the database.
# Returns to the product list if it goes well, kills the script and returns the exception if an error occurred.
#
# Version 1.0 :         15.06.17
#--------------------------------------------------------------------------

require_once 'inc/Gestock.php';
session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']['idRole_fk'] == 1)
    header('Location: login.php');

// Sets all vars to the correct values.
$name = $_POST['productName'];
$brand = $_POST['productBrand'];
$price = $_POST['productPrice'];
$idCategory = $_POST['productCategory'];
$quantity = $_POST['productQuantity'];
$image = $_FILES['productImage'];
$idStock = $_POST['productStock'];

$result = Gestock::getInstance()->insertProduct($name, $brand, $price, $idCategory, $quantity, $image['name'], $idStock);

if(is_bool($result) && $result) // If everything went well.
{
    if(move_uploaded_file($image['tmp_name'], getcwd() . "/img/products/" . $image['name']))
        header('Location: adminProducts.php');
}
else
{
    echo '<pre>';
    print_r($result);
    echo '</pre>';
    die();
}

?>