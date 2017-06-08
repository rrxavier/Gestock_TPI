<?php
	require_once 'inc/header.php';
	require_once 'inc/DataToHtml.php';

	$msg = FILTER_INPUT(INPUT_GET, "msg");
?>
	<!-- <div id="msgBox"><span>TEST</span></div> ERROR MSG SLIDDER TO DO-->
		
	<section id="form"><!--form-->
		<div class="container">
		<h4 id="alert" class="text-danger text-center"><?php echo ($msg ? $msg : "") ?></h4>
			<div class="row" style="background: black;">
				<div class="col-sm-4 col-sm-offset-1">
					<div class="login-form"><!--login form-->
						<h2>Login to your account</h2>
						<form action="#">
							<input type="text" placeholder="Name" />
							<input type="email" placeholder="Email Address" />
							<span>
								<input type="checkbox" class="checkbox"> 
								Keep me signed in
							</span>
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
	<script>
		$(document).ready(function(){
				$("#msgBox").hide();
				$("#msgBox").show("slide", { direction: "up" }, 1000);
			});

		$("#msgBox").click(function(){
      		$(this).hide("slide", { direction: "up" }, 1000); 
		});
	</script>
	