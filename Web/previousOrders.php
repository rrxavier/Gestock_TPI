<?php
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
                        <td class="col-sm-6 text-center">Date & Hour</td>
                        <td class="col-sm-6 text-center">Action</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                echo DataToHtml::PreviousOrdersToHTML($_SESSION['user']['id']);
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once 'inc/footer.php'; ?>