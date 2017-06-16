<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Gestock</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">
	<link href="css/gestockStyle.css" rel="stylesheet">
    <link rel="icon" href="img/favicon.png">
</head><!--/head-->

<body>
	<header id="header"><!--header-->		
		<div class="header-middle"><!--header-middle-->
			<div class="container">
				<div class="row">
					<div class="col-sm-4">
						<div class="logo pull-left">
							<a href="index.php"><img src="img/logoSmall.png" alt="" /></a>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="shop-menu pull-center">
							<ul class="nav navbar-nav">
							<?php
								$menu = '';
								if(isset($_SESSION['user']))
								{
									$menu .= '<li><a href="account.php"><i class="fa fa-user"></i>' . $_SESSION['user']['username'] . '</a></li>';
									$menu .= '<li><a href="cart.php"><i class="fa fa-shopping-cart"></i>Cart</a></li>';
									$menu .= '<li><a href="logout.php"><i class="glyphicon glyphicon-remove"></i>Logout</a></li>';
								}
								else
								{
									$menu .= '<li><a href="cart.php"><i class="fa fa-shopping-cart"></i>Cart</a></li>';
									$menu .= '<li><a href="login.php"><i class="fa fa-lock"></i>Login</a></li>';
								}
								echo $menu;
							?>
							</ul>
						</div>
					</div>
					<!-- <div class="col-sm-2">
						<div class="search_box pull-right">
							<input type="text" placeholder="Search..."/>
						</div>
					</div>-->
				</div>
			</div>
		</div><!--/header-middle-->
	</header><!--/header-->