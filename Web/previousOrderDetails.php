<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            previousOrderDetails.php
# Date :                15.06.17
#--------------------------------------------------------------------------
# Shows all products bought in a previous order.
#
# Version 1.0 :         15.06.17
#--------------------------------------------------------------------------

require_once 'inc/header.php'; 
require_once 'inc/DataToHtml.php';

if(!isset($_SESSION['user']))
    header('Location: login.php');
?>
<section id="cart_items">
    <div class="container">
        <div class="table-responsive cart_info m-t-5">
        <div class="text-center"><h1>Products bought in the order of 14.06.17</h1></div>
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="">Item</td>
                        <td class="">Name</td>
                        <td class=""></td>
                        <td class="">Price</td>
                        <td class="">Quantity</td>
                        <td class="text-center">Total</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                echo DataToHtml::PreviousOrderProductsToHTML($_SESSION['user']['id']);
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once 'inc/footer.php'; ?>