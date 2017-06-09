<?php
require_once 'inc/header.php';

if(!isset($_SESSION['user']))
    header('Location: login.php');
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
                <div class="row">
                    <div clasS="col-sm-5 col-sm-offset-1">
                        <strong class="col-sm-4">Password :</strong>
                        <div class="col-sm-8"><b>CHANGE PASSWORD LINK</b></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5 text-center">
                <div class="accountBlock">
                    <div class="text-center"><h1>Cart</h1></div>
                    
                </div>
            </div>
            <div class="col-sm-5 col-sm-offset-2">
                <div class="accountBlock">
                    <div class="text-center"><h1>Previous orders</h1></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'inc/footer.php'; ?>