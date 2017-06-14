<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            addToCart.php
# Date :                12.06.17
#--------------------------------------------------------------------------
# This file is used to add a selected product in the cart of the connected user.
# Executed from an AJAX call.
#
# Version 1.0 :         12.06.17
#--------------------------------------------------------------------------

require_once 'inc/Gestock.php';
session_start();

if(!isset($_SESSION['user']))   // If the user isn't connected
    echo "NotConnected";
else    // If he's connected.
{
    $idProduct = filter_input(INPUT_POST, "idProduct");
    $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
    // echo $idProduct, ' ', $quantity;
    if($idProduct && $quantity)     // If both these values are set and correct.
        echo Gestock::getInstance()->insertProductIntoCart($idProduct, $quantity, $_SESSION['user']['id']);
    else    // There is no product OR no quantity.
        echo "NoProduct";
}
?>