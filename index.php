<?php
session_start();

include_once(__DIR__ . "/classes/DB.php");
include_once(__DIR__ . "/classes/DataFetch.php");

$df = new DataFetch();

//single data:
echo $df->getNumberOfUser();

// array of objects
foreach($df->getUserList() as $user){
	echo $user->u_name;
}


//DB::conn()->query("INSERT INTO users(u_name, u_email, u_password) VALUE('hery', 'hery@hery', '1234')");
//$q = DB::conn()->query("SELECT * FROM users")->results();

if(isset($_POST["login"])){
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	$login = DB::conn()->query("SELECT * FROM users WHERE (u_email = '$username' OR u_name = '$username') AND u_password = '$password'")->num_rows();
	
	if($login > 0){
		$_SESSION["login"] = true;
		
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
					<form action="" method="POST">
						Username:
						<input type="email" name="username" placeholder="Username" class="form-control" /><br />
						
						Password:
						<input type="password" name="password" placeholder="Password" class="form-control" /><br />
						
						<button class="btn btn-success" name="login">
							Login
						</button><br /><br />
						
						<div class="text-center">
							<a href="">
								Forgot password?
							</a>
						</div>
						
					</form>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					Register
				</div>
				
				<div class="card-body">
					<form action="" method="POST">
						Username:
						<input type="text" name="username" placeholder="Username" class="form-control" /><br />
						
						Email:
						<input type="email" name="email" placeholder="Email" class="form-control" /><br />
						
						Password:
						<input type="password" name="password" placeholder="Password" class="form-control" /><br />
						
						<button class="btn btn-success" name="register">
							Register
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

</body>
</html> 