<?php
require_once 'inc/Gestock.php';
session_start();

if(!isset($_SESSION['user']))
    echo "NotConnected";
else
{
    $idProduct = filter_input(INPUT_POST, "idProduct");
    $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
    echo $quantity;
    if($idProduct && $quantity)
        echo Gestock::getInstance()->insertProductIntoCart($idProduct, $quantity, $_SESSION['user']['id']);
    else
        echo "NoProduct";
}
?>