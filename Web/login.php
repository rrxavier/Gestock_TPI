<?php
	require_once 'inc/header.php';
	require_once 'inc/DataToHtml.php';

	if(isset($_SESSION['user']))
		header('Location: account.php');

	$msg = FILTER_INPUT(INPUT_GET, "msg");
?>
	<!-- <div id="msgBox"><span>TEST</span></div> ERROR MSG SLIDDER TO DO-->
		
	<section id="form"><!--form-->
		<div class="container">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-1">
					<div class="login-form"><!--login form-->
						<h2>Login to your account</h2>
						<form action="authentification.php" method="POST">
							<input type="text" name="identifier" placeholder="Username OR Email" />
							<input type="password" name="password" placeholder="Password" />
							<!-- <span>
								<input type="checkbox" class="checkbox"> 
								Keep me signed in
							</span> -->
							<button type="submit" class="btn btn-default">Login</button>
						</form>
					</div><!--/login form-->
				</div>
				<div class="col-sm-2 pagination-centered">
					<h2 class="or pagination-centered">OR</h2>
				</div>
				<div class="col-sm-4">
					<div class="signup-form"><!--sign up form-->
						<h2>New User Signup!</h2>
						<form action="addUser.php" method="POST">
							<input type="text" name="username" placeholder="Username" required/>
							<input type="email" name="userEmail" placeholder="Email Address" required/>
							<input type="password" name="userPassword" placeholder="Password" pattern=".{0}|.{8,}" required title="At least 8 characters"/>
							<input type="password" name="userPasswordConfirm" placeholder="Confirm password" required/>
							<button type="submit" name="signUp" class="btn btn-default">Sign up</button>
						</form>
					</div><!--/sign up form-->
				</div>
			</div>
		</div>
	</section><!--/form-->
	<?php require_once 'inc/footer.php'; ?>
	<?php echo ($msg ? '<script>addPopup("' . $msg . '")</script>' : "") ; ?>
	