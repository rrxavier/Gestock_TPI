<?php
require_once 'inc/Gestock.php';
session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']['idRole_fk'] == 1)
    header('Location: login.php');

$idUser = $_GET['id'];
if(!$idUser)
    header('Location: adminUsers.php');

$result = Gestock::getInstance()->deleteUser($idUser);
if(is_bool($result) && $result)
    header('Location: adminUser.php');
else
{
    echo '<pre>';
    print_r($result);
    echo '</pre>';
    die();
}
?>