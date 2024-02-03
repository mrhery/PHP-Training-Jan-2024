<?php

include_once(__DIR__ . "/classes/DB.php");

if(isset($_POST["reset_password"], $_GET["token"])){
	$password1 = $_POST["password"];
	$password2 = $_POST["password_confirm"];
	$token = $_GET["token"];
	
	if($password1 == $password2){
		$dateTime = date("Y-m-d H:i:s");
		
		$fp = DB::conn()->query("SELECT * FROM forgot_password WHERE fp_url = '$token' AND fp_status = 0");
	
		if($fp->num_rows() > 0){
			$fp = $fp->results();
			
			$xDateTime = date("Y-m-d H:i:s", strtotime($fp->fp_requestTime . " +7 days"));
			
			if($fp->fp_requestTime < $xDateTime){
				DB::conn()->query("UPDATE users SET u_password = '$password1' WHERE u_email = '$fp->fp_email'");
			
				DB::conn()->query("UPDATE forgot_password SET fp_status = 1 WHERE fp_url = '$token'");
				
				$success = "Password changed successfully.";
			}else{
				$error = "Your url token has been expired.";
			}
		}else{
			$error = "The token is not valid.";
		}
	}else{
		$error = "Your password and confirm password is not match.";
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>PHP Chatting System</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<?php
				if(isset($success)){
				?>
				<div class="alert alert-success">
					<strong>Success!</strong> <?= $success ?>
				</div>
				<?php
				}
				
				if(isset($error)){
				?>
				<div class="alert alert-danger">
					<strong>Error!</strong> <?= $error ?>
				</div>
				<?php
				}
			?>
		
			<div class="card">
				<div class="card-header">
					Create new Password
				</div>	
				
				<div class="card-body">
					<form action="" method="POST">
						New Password:
						<input type="password" class="form-control" placeholder="Password" name="password" /><br />
						
						Confirm Password:
						<input type="password" class="form-control" placeholder="Confirm Password" name="password_confirm" /><br />
						
						<button class="btn btn-primary" name="reset_password">
							Reset new Password
						</button>
						
						<br /><br />
						
						<div class="text-center">
							<a href="index.php">
								Back to Login
							</a>
						</div>
					</form>
				</div>
			</div>	
		</div>
	</div>
</div>
</body>
</html>