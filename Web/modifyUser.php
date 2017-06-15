<?php
require_once 'inc/Gestock.php';
session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']['idRole_fk'] == 1)
    header('Location: login.php');

$username = $_POST['username'];
$email = $_POST['userEmail'];
$money = $_POST['userMoney'];
$idUser = $_GET['id'];

if(!$idUser)
    header('Location: adminUsers.php');


$result = Gestock::getInstance()->modifyUser($idUser, $username, $email, $money);
if(is_bool($result) && $result)
    header('Location: adminUsers.php');
else
{
    echo '<pre>';
    print_r($result);
    echo '</pre>';
    die();
}

?>