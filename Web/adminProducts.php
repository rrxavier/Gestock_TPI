<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            adminProducts.php
# Date :                15.06.17
#--------------------------------------------------------------------------
# This file shows the list of all products of the DB to manage them.
#
# Version 1.0 :         15.06.17
#--------------------------------------------------------------------------

require_once 'inc/header.php'; 
require_once 'inc/DataToHtml.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['idRole_fk'] == 1)
    header('Location: login.php');
    
if(filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT))
{
    $page = $_GET['page'];
    if($page < 0)
        $page = 0;
}
else
    $page = 0;
?>
<section id="cart_items">
    <div class="container">
        <div class="table-responsive cart_info m-t-5">
        <div class="text-center"><h1>All products</h1></div>
        <div class="row text-center">
            <a href="adminProduct.php?mode=add"><h2>Add a product</h2></a>
        </div>
        <div class="row">
            <form action="adminProducts.php" method="POST" class="form-inline col-sm-12 pull-center"><div class="search_box col-sm-12 input-group input-group-lg"><input class="text-center form-control" name="searchAdmin" type="text" placeholder="SEARCH BY NAME..."></div></form>            
        </div>
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="col-sm-1 text-center">Item</td>
                        <td class="col-sm-4">Name</td>
                        <td class="col-sm-2">Category</td>
                        <td class="col-sm-1">Price</td>
                        <td class="col-sm-1 text-center">Quantity</td>
                        <td class="col-sm-3 text-center">Action</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                echo DataToHtml::AdminProductsToHTML($page, (isset($_POST['searchAdmin']) ? $_POST['searchAdmin'] : ''));
                ?>
                </tbody>
            </table>
            <?php
            echo DataToHtml::PaginationToHtml($page);
            ?>
        </div>
    </div>
</section>
<?php require_once 'inc/footer.php'; ?>