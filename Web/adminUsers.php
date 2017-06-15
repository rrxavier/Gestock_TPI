<?php
require_once 'inc/header.php'; 
require_once 'inc/DataToHtml.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['idRole_fk'] == 1)
    header('Location: login.php');
?>
<section id="cart_items">
    <div class="container">
        <div class="table-responsive cart_info m-t-5">
        <div class="row text-center">
            <a href="adminProduct.php?mode=add"><h2>Ajouter un produit</h2></a>
        </div>
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
                //echo DataToHtml::AdminProductsToHTML($page, (isset($_POST['searchAdmin']) ? $_POST['searchAdmin'] : ''));
                foreach(Gestock::getInstance()->getUsers() as $user)
                    echo '<tr><td class="text-center">' . $user['username'] . '</td><td>' . $user['email'] . '</td><td class="text-center">' . $user['money'] . '.-</td><td class="text-center"><p class="cart_total_price"><div class="col-sm-6"><a href="adminUser.php?id=' . $user['id'] . '">Modify</a></div><div class="col-sm-6"><a href="deleteUser.php?id=' . $user['id'] . '">Delete</a></div></p></td></tr>';
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once 'inc/footer.php'; ?>