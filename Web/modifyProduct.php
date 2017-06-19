<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            modifyProduct.php
# Date :                15.06.17
#--------------------------------------------------------------------------
# Modifies a specified product in the DB with the given data.
#
# Version 1.0 :         15.06.17
#--------------------------------------------------------------------------

require_once 'inc/Gestock.php';
session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']['idRole_fk'] == 1) // If not logged OR logged as user.
    header('Location: login.php');

// Set all vars to their correct values.
$name = $_POST['productName'];
$brand = $_POST['productBrand'];
$price = $_POST['productPrice'];
$idCategory = $_POST['productCategory'];
$quantity = $_POST['productQuantity'];
$image = $_FILES['productImage'];
$idStock = $_POST['productStock'];
$idProduct = $_GET['id'];

if(!$idProduct)     // If the product's ID isn't set.
    header('Location: adminProducts.php');

$result = Gestock::getInstance()->modifyProduct($name, $brand, $price, $idCategory, $quantity, $image['name'], $idStock, $idProduct);

if(is_bool($result) && $result) // If everything went well.
{
    if($image['size'] > 0)
        if(move_uploaded_file($image['tmp_name'], getcwd() . "/img/products/" . $image['name']))
            header('Location: adminProducts.php');
        else
        {
            echo 'Problem when updating the photo.';
            exit();
        }
    else
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