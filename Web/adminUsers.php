<?php

#--------------------------------------------------------------------------
# TPI 2017 - Author :   Oliveira Ricardo
# Filename :            adminUsers.php
# Date :                15.06.17
#--------------------------------------------------------------------------
# This file shows all users in the DB to manage them.
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
        <div class="text-center"><h1>All users</h1></div>
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="col-sm-3 text-center">Username</td>
                        <td class="col-sm-3">Email</td>
                        <td class="col-sm-3 text-center">Money</td>
                        <td class="col-sm-3 text-center">Action</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                    echo DataToHtml::AdminUsers();
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once 'inc/footer.php'; ?>