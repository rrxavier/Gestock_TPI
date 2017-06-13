<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            deleteProductFromCart.php
# Date :                12.06.17
#--------------------------------------------------------------------------
# Used to delete a product from the cart of the connected user.
#
# Version 1.0 :         12.06.17
#--------------------------------------------------------------------------

require_once 'inc/Gestock.php';
session_start();

$idProduct = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!isset($_SESSION['user']))  // If the user isn't connected
    header('Location: login.php');
if ($idProduct)     // If the product ID is set.
{
    if(Gestock::getInstance()->deleteProductFromCart($idProduct, $_SESSION['user']['id']))  // If the query successfully executed.
        header('Location: cart.php');
    else    // If an error occurred. 
        header('Location: cart.php?msg=Error, please try again.');
}
else    // If the product ID isn't set.
    header('Location: index.php');


?>