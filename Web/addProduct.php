<?php
require_once 'inc/Gestock.php';
session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']['idRole_fk'] == 1)
    header('Location: login.php');

$name = $_POST['productName'];
$brand = $_POST['productBrand'];
$price = $_POST['productPrice'];
$idCategory = $_POST['productCategory'];
$quantity = $_POST['productQuantity'];
$image = $_FILES['productImage'];
$idStock = $_POST['productStock'];

print_r($image);
$result = Gestock::getInstance()->insertProduct($name, $brand, $price, $idCategory, $quantity, $image['name'], $idStock);

if(is_bool($result) && $result)
{
    if(move_uploaded_file($image['tmp_name'], getcwd() . "/img/products/" . $image['name']))
        header('Location: adminProducts.php');
}
else
{
    echo '<pre>';
    print_r($result);
    echo '</pre>';
    die();
}

?>