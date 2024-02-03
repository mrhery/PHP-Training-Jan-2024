<?php
session_start();

include_once(__DIR__ . "/classes/DB.php");
include_once(__DIR__ . "/classes/DataFetch.php");

if(isset($_POST["login"])){
	$username = $_POST["username"];
	$password = hash("sha256", $_POST["password"]);
	
	$login = DB::conn()->query("SELECT * FROM users WHERE (u_email = '$username' OR u_name = '$username') AND u_password = '$password'");
	
	if($login->num_rows() > 0){
		$_SESSION["login"] = $login->results();
		
		header("Location: main.php");
	}else{
		$error = "Login error";
	}
}

if(isset($_POST["register"])){
	$username = $_POST["username"];
	$password = $_POST["password"];
	$email = $_POST["email"];
	
	$check = DB::conn()->query("SELECT * FROM users WHERE u_email = '$email'")->num_rows();
	
	if($check < 1){
		$password = hash("sha256", $password);
		
		DB::conn()->query("INSERT INTO users(u_name, u_email, u_password) VALUES('$username', '$email', '$password')");
	
		$success = "User registered successfully.";
	}else{
		$error = "User email already exists.";
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
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
			
		</div>
		
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					Login
				</div>
				
				<div class="card-body">
						Username:
						<input type="email" name="username" id="username" placeholder="Username" class="form-control" /><br />
						
						Password:
						<input type="password" name="password" id="password" placeholder="Password" class="form-control" /><br />
						
						<button class="btn btn-success" name="login" type="button" id="login">
							Login
						</button><br /><br />
						
						<div class="text-center">
							<a href="forgot.php">
								Forgot password?
							</a>
						</div>
						
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					Register
				</div>
				
				<div class="card-body">
					
						Username:
						<input type="text" name="username" placeholder="Username" class="form-control" /><br />
						
						Email:
						<input type="email" name="email" placeholder="Email" class="form-control" /><br />
						
						Password:
						<input type="password" name="password" placeholder="Password" class="form-control" /><br />
						
						<button class="btn btn-success" name="register" type="button">
							Register
						</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
$("#login").on("click", function(){
	var username = $("#username").val();
	var password = $("#password").val();
	
	if(username.length > 0 && password.length > 0){
		$.ajax({
			url: "server.php",
			method: "POST",
			data: {
				action: "login",
				uname: username,
				pass: password
			},
			dataType: "text"
		}).done(function(responseText){
			console.log(responseText);
			var obj = JSON.parse(responseText);
			
			if(obj.status == "success"){
				window.location = "main.php";
			}else{
				alert(obj.message);
			}
		}).fail(function(error){
			console.log(error);
		});
	}else{
		alert("Username & Password are required.");
	}
});
</script>
</body>
</html> 