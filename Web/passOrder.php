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

print_r($result);
/*if(is_bool($result))
{
    if($result)
        header('Location: index.php?msg=Order successfully passed !');
    else
        header('Location: index.php?msg=Error, please try again.');
}
else
{
    if($result == 'NoItemsInCart')
        header('Location: index.php?msg=Your cart has no products !');
}*/



?>