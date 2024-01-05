<?php


class DataFetch {
	public function getUser(){
		return (object)[
			"u_name" 		=> "hery", 
			"u_password" 	=> "1234",
			"u_email"		=> "hery@hery"
		];
 	}
	
	public function getUserList(){
		return [
			(object)[
				"u_name" 		=> "hery", 
				"u_password" 	=> "1234",
				"u_email"		=> "hery@hery"
			],
			(object)[
				"u_name" 		=> "hery1", 
				"u_password" 	=> "12345",
				"u_email"		=> "hery2@hery"
			],
			(object)[
				"u_name" 		=> "hery2", 
				"u_password" 	=> "123456",
				"u_email"		=> "hery2@hery"
			]
		];
 	}
	
	public function getNumberOfUser(){
		return 2;
	}
}
