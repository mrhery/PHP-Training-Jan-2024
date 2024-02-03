<?php
session_start();

include_once(__DIR__ . "/classes/DB.php");

if(isset($_POST["action"])){
	switch($_POST["action"]){
		case "login":
			if(isset($_POST["uname"], $_POST["pass"])){
				$username = $_POST["uname"];
				$password = $_POST["pass"];
				
				$login = DB::conn()->query("SELECT * FROM users WHERE (u_email = '$username' OR u_name = '$username') AND u_password = '$password'");
				
				if($login->num_rows() > 0){
					$_SESSION["login"] = $login->results();
					
					echo json_encode([
						"status"	=> "success",
						"message"	=> "Login success!"
					]);
				}else{
					echo json_encode([
						"status"	=> "error",
						"message"	=> "Login failed! Username or password are incorrect."
					]);
				}
			}else{
				echo json_encode([
					"status"	=> "error",
					"message"	=> "Login failed! Insufficient request parameter."
				]);
			}
		break;
		
		case "register":
			
		break;
	}
}