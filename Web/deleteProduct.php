<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            deleteProduct.php
# Date :                15.06.17
#--------------------------------------------------------------------------
# This script deletes a product from the DB.
# Returns to the product listing page if it works, kills the script if an error occured.
#
# Version 1.0 :         15.06.17
#--------------------------------------------------------------------------

require_once 'inc/Gestock.php';
session_start();

if(!isset($_SESSION['user']) && $_SESSION['user']['idRole_fk'] == 1)
    header('Location: login.php');

$idProduct = filter_input(INPUT_GET, 'id');
$result = Gestock::getInstance()->deleteProduct($idProduct);

if(is_bool($result) && $result)
    header('Location: adminProducts.php');
else
{
    echo '<pre>';
    print_r($result);
    echo '</pre>';
    die();
} 

?>