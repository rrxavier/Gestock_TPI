<?php
require_once 'inc/Gestock.php';
session_start();

$idProduct = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!isset($_SESSION['user']))
    header('Location: login.php');
if ($idProduct)
{
    if(Gestock::getInstance()->deleteProductFromCart($idProduct, $_SESSION['user']['id']))
        header('Location: cart.php');
    else
        header('Location: cart.php?msg=Error, please try again.');
}
else
    header('Location: index.php');


?>