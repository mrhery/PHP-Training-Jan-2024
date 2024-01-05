<?php

class DB {
	public static $name = "hery";
	
	private static $_db = null;
	private	$_conn = null, $_q = null;
	
	public function __construct($conn = null){
		$this->_conn = $conn;
	}
	
	public static function conn() {
		if(self::$_db == null){
			$conn = mysqli_connect("127.0.0.1", "root", "", "test");
			
			self::$_db = new DB($conn);
		}
		
		return self::$_db;
	}
	
	public function query($sql = "", $data = []) {
		$this->_q = mysqli_query($this->_conn, $sql);
		
		return $this;
	}
	
	public function results() {
		return mysqli_fetch_object($this->_q);
	}
	
	public function num_rows(){
		return mysqli_num_rows($this->_q);
	}
}