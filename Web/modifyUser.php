<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            modifyUser.php
# Date :                15.06.17
#--------------------------------------------------------------------------
# Modifies a specified user in the DB with the given data.
#
# Version 1.0 :         15.06.17
#--------------------------------------------------------------------------

require_once 'inc/Gestock.php';
session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']['idRole_fk'] == 1) // If not logged OR logged as user.
    header('Location: login.php');

// Sets all vars to their correct values.
$username = $_POST['username'];
$email = $_POST['userEmail'];
$money = $_POST['userMoney'];
$idUser = $_GET['id'];

if(!$idUser)    // If the users's ID isn't set.
    header('Location: adminUsers.php');


$result = Gestock::getInstance()->modifyUser($idUser, $username, $email, $money);
if(is_bool($result) && $result) // If everything went well.
{
    header('Location: adminUsers.php');
    $_SESSION['user'] = Gestock::getInstance()->getUserInfo($_SESSION['user']['id'])[0];
}
else
{
    echo '<pre>';
    print_r($result);
    echo '</pre>';
    die();
}

?>