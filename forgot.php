<?php

include_once(__DIR__ . "/classes/DB.php");

if(isset($_POST["send_mail"])){
	$email = $_POST["email"];
	
	$u = DB::conn()->query("SELECT * FROM users WHERE u_email = '$email'");
	
	if($u->num_rows() > 0){
		$url = hash("sha256", uniqid() . time());
		$dateTime = date("Y-m-d H:i:s");
		
		DB::conn()->query("INSERT INTO forgot_password(fp_email, fp_url, fp_requestTime) VALUES('$email', '$url', '$dateTime')");
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <no-reply@herytech.com>' . "\r\n";
		
		$subject = "Forgot Password - PHPChatSystem";
		
		$message = file_get_contents("forgot_password_template.html");
		
		$message = str_replace("{{EMAIL}}", $email, $message);
		
		$url = "http://localhost/PHPChatsystem/reset.php?token=" . $url;
		$message = str_replace("{{URL}}", $url, $message);
		
		file_put_contents("email-forgot.html", $message);
		
		//mail($email, $subject, $message, $headers);
		
		$success = "Reset password URL has been sent to your email.";
	}else{
		$error = "This email has not been registered in this system.";
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
					Forgot
				</div>	
				
				<div class="card-body">
					<form action="" method="POST">
						Email:
						<input type="email" class="form-control" placeholder="Email" name="email" /><br />
						
						<button class="btn btn-primary" name="send_mail">
							Send Mail
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