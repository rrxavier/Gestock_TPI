<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            adminLowQuantity.php
# Date :                15.06.17
#--------------------------------------------------------------------------
# This file shows the list of almost out of stock/out of stock products. 
#
# Version 1.0 :         15.06.17
#--------------------------------------------------------------------------

require_once 'inc/header.php'; 
require_once 'inc/DataToHtml.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['idRole_fk'] == 1)
    header('Location: login.php');
?>
<section id="cart_items">
    <div class="container">
        <div class="table-responsive cart_info m-t-5">
            <div class="text-center"><h1>Low stock products</h1></div>
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="col-sm-2 text-center">Item</td>
                        <td class="col-sm-4">Name</td>
                        <td class="col-sm-2 text-center">Quantity</td>
                        <td class="col-sm-2 text-center">Alert quantity</td>
                        <td class="col-sm-2 text-center">Action</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                    echo DataToHtml::AdminLowStockProducts();
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once 'inc/footer.php'; ?>