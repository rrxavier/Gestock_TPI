<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            deleteUser.php
# Date :                15.06.17
#--------------------------------------------------------------------------
# This script deletes a user from the DB.
# Returns to the user listing page if it works, kills the script if an error occured.
#
# Version 1.0 :         15.06.17
#--------------------------------------------------------------------------

require_once 'inc/Gestock.php';
session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']['idRole_fk'] == 1) // If not logged OR logged as user.
    header('Location: login.php');

$idUser = $_GET['id'];
if(!$idUser)    // If the user's ID isn't set.
    header('Location: adminUsers.php');

$result = Gestock::getInstance()->deleteUser($idUser);
if(is_bool($result) && $result)     // If everything went well.
    header('Location: adminUser.php');
else
{
    echo '<pre>';
    print_r($result);
    echo '</pre>';
    die();
}
?>