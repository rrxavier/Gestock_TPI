<?php 
require_once 'inc/header.php'; 
require_once 'inc/DataToHtml.php';

if(!isset($_SESSION['user']))
    header('Location: login.php');
$msg = FILTER_INPUT(INPUT_GET, "msg");
?>
<section id="cart_items">
    <div class="container">
        <div class="table-responsive cart_info m-t-5">
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="image">Item</td>
                        <td class="description">Name</td>
                        <td class="price">Price</td>
                        <td class="quantity">Quantity</td>
                        <td class="total">Total</td>
                        <td></td>
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