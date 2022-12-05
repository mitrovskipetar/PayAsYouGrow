 <?php
	include 'common.php';
	//Check if user is logged in, if not redirect to login page
	session_start();
	if (!isset($_SESSION["loggedin"])) {
		header("location: login.php");
		exit;
	}

	$user = $_SESSION['username'];
	$dbh = openCon();

	//Updates the password with the new password provided in "passwordForm" in this file
	function changePassword($new_pass)
	{
		global $user;
		global $dbh;
		$pass_hash = password_hash($new_pass, PASSWORD_DEFAULT);
		$query = "UPDATE users SET password=:new_pass WHERE username=:user";
		$callToDb = $dbh->prepare($query);
		$callToDb->bindParam(":new_pass", $pass_hash);
		$callToDb->bindParam(":user", $user);
		if ($callToDb->execute()) {
			return '<h3 style="text-align:center;">We will get back to you very shortly!</h3>';
		} else {
			return '<h3 style="text-align:center;">Error!</h3>';
		}
	}

	if (isset($_POST['new_pass'])) {
		changePassword($_POST['new_pass']);
		header("Refresh:0; url=index.php");
	}
	?>

 <head>
 	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 	<link rel="stylesheet" href="style.css">
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 	<script src="/js/pass_confirm.js"></script>
 	<title>
 		Password Reset:
 	</title>
 </head>
 <div class="justify-content-center col-4" style="margin-left: 35%; margin-top:5%;">
 	<form id="passwordForm" action="password_change.php" class="form form-custom" method="POST">
 		<div class="card px-1 py-4">
 			<div class="card-body">
 				<h5 class="card-title mb-3">Reset Password</h5>
 				<h6 class="information mt-4">Enter your new password</h6>
 				<div class="row">
 					<div class="col-sm-12">
 						<div class="form-group">
 							<input class="form-control" type="password" id="new_pass" placeholder="Enter passward" name="new_pass">
 							<input class="form-control" type="password" id="new_pass_confirm" placeholder="Confirm Passward" name="new_pass_confirm">
 							<div class="registrationFormAlert" style="color:green;" id="CheckPasswordMatch">
 							</div>
 						</div>
 					</div>
 					<input id="submit-button" class="btn btn-primary" style="float:right" type="submit" name="submit"></input>
 					<input type="button" class="btn btn-secondary ml-2" style="float:right; margin-right:5px;" onclick="location.href = 'settings.php';" value="Back">
 				</div>
 			</div>
 	</form>
 </div>