<?php
require_once 'inc/header.php'; 
require_once 'inc/DataToHtml.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['idRole_fk'] == 1)
    header('Location: login.php');

$user = Gestock::getInstance()->getUserInfo($_GET['id']);
if(count($user) == 0)
    header('Location: adminUsers.php');
$user = $user[0];
?>
<section id="cart_items">
    <div class="container">
        <div class="table-responsive cart_info m-t-5">
        <div class="text-center"><h1>Modify user</h1></div>
        <form action="modifyUser.php?id=<?php echo $user['id']; ?>" method="POST">
        <table class="table table-condensed">
            <thead>
                <tr class="cart_menu">
                    <td class="col-sm-3 text-center">ID</td>
                    <td class="col-sm-3 text-center">Username</td>
                    <td class="col-sm-3 text-center">Email</td>
                    <td class="col-sm-3 text-center">Money</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        <?php echo $user['id']; ?>
                    </td>
                    <td class="text-center">
                        <input required type="text" name="username" class="text-center" value="<?php echo $user['username']; ?>">
                    </td>
                    <td class="text-center">
                        <input required type="text" name="userEmail" class="text-center" value="<?php echo $user['email']; ?>">
                    </td>
                    <td class="text-center">
                        <input required type="text" name="userMoney" class="text-center" value="<?php echo $user['money']; ?>">
                    </td>
                </tr>
                <tr><td colspan="4" class="text-center"><input type="submit" value="Modify !"></td></tr>
            </tbody>
        </table>
        </form>
            
        </div>
    </div>
</section>
<?php require_once 'inc/footer.php'; ?>