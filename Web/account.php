<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            account.php
# Date :                09.06.17
#--------------------------------------------------------------------------
# Shows the user informations, a preview of his current cart and a preview of his past orders.
#
# Version 1.0 :         09.06.17
#--------------------------------------------------------------------------

require_once 'inc/header.php';
require_once 'inc/DataToHtml.php';
require_once 'inc/Gestock.php';

if(!isset($_SESSION['user']))
    header('Location: login.php');

$_SESSION['user'] = Gestock::getInstance()->getUserInfo($_SESSION['user']['id'])[0];
?>

<section>
    <div class="container">
        <div class="row">
            <div class="accountBlock">
                <div class="text-center"><h1>Personnal info</h1></div>
                <div class="row">
                    <div clasS="col-sm-5 col-sm-offset-1">
                        <strong class="col-sm-4">Username :</strong>
                        <div class="col-sm-1"><?php echo $_SESSION['user']['username']; ?></div>
                    </div>
                    <div clasS="col-sm-5 col-sm-offset-1">
                        <strong class="col-sm-4">Money :</strong>
                        <div class="col-sm-8"><?php echo $_SESSION['user']['money']; ?>.-</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5 text-right">
                <div class="accountBlock cartPreview">
                    <div class="text-center"><h1>Cart preview</h1></div>
                    <?php
                    echo DataToHtml::CartPreview($_SESSION['user']['id']);
                    ?>
                </div>
            </div>
            <div class="col-sm-5 col-sm-offset-2">
                <div class="accountBlock">
                    <div class="text-center"><h1>Previous orders</h1></div>
                    <?php
                    echo DataToHtml::PreviousOrdersPreview($_SESSION['user']['id']);
                    ?>
                </div>
            </div>
        </div>
        <?php
            if($_SESSION['user']['idRole_fk'] == 2)
            {
                echo '<div class="row">
                        <div class="accountBlock">
                            <div class="text-center">
                                <h1>Administration</h1>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 text-center">
                                    <a href="adminProducts.php"><h3>Manage Products</h3></a>
                                </div>
                                <div class="col-sm-4 text-center">
                                    <a href="adminUsers.php"><h3>Manage Users</h3></a>
                                </div>
                                <div class="col-sm-4 text-center">
                                    <a href="adminLowQuantity.php"><h3>Low quantity products</h3></a>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
        ?>
    </div>
</section>

<?php require_once 'inc/footer.php'; ?>