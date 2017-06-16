<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            passOrder.php
# Date :                14.06.17
#--------------------------------------------------------------------------
# Used to complete an order by setting an orderDate.
#
# Version 1.0 :         14.06.17
#--------------------------------------------------------------------------

require_once 'inc/Gestock.php';
session_start();

if(!isset($_SESSION['user']))
    header('Location: index.php');


$result = Gestock::getInstance()->passOrder($_SESSION['user']['id']);

if(is_bool($result))
{
    if($result)
    {
        $_SESSION['user'] = Gestock::getInstance()->getUserInfo($_SESSION['user']['id'])[0];
        header('Location: index.php?msg=Order successfully passed !');
    }
    else
        header('Location: index.php?msg=Error, please try again.');
}
else
{
    if($result == 'NoItemsInCart')
        header('Location: cart.php?msg=Your cart has no products !');
    if($result == 'NotInStock')
        header('Location: cart.php?msg=Some items aren\'t in stock !');
    if($result == 'NotEnoughMoney')
        header('Location: cart.php?msg=You do not have enough money !');
}
?>