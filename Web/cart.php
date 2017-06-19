<?php 

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            cart.php
# Date :                13.06.17
#--------------------------------------------------------------------------
# Shows all products in the cart of the user.
#
# Version 1.0 :         13.06.17
#--------------------------------------------------------------------------

require_once 'inc/header.php'; 
require_once 'inc/DataToHtml.php';

if(!isset($_SESSION['user']))
    header('Location: login.php?msg=Login first !');
$msg = FILTER_INPUT(INPUT_GET, "msg");
?>
<section id="cart_items">
    <div class="container">
        <div class="table-responsive cart_info m-t-5">
        <div class="text-center"><h1>Cart</h1></div>
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="image col-sm-1">Item</td>
                        <td class="col-sm-3">Name</td>
                        <td class="price col-sm-2">Price</td>
                        <td class="quantity text-center col-sm-3">Quantity</td>
                        <td class="total text-center col-sm-2">Total</td>
                        <td class="col-sm-1"></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                echo DataToHtml::CartProductsToHTML($_SESSION['user']['id']);
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once 'inc/footer.php'; ?>
<?php echo ($msg ? '<script>addPopup("' . $msg . '")</script>' : "") ; ?>