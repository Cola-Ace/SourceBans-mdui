<?php
	class Database {
		var $conn;
		var $db_info = array(
		"type" => "mysql",
		"host" => "",
		"port" => 3306,
		"user" => "root",
		"pass" => "",
		"db_name" => ""
		);
		function __construct(){
			if ($this->db_info["type"] == "mysql"){
				$this->conn = new mysqli("{$this->db_info['host']}:{$this->db_info['port']}", $this->db_info["user"], $this->db_info["pass"], $this->db_info["db_name"]);
			} else {
				$this->conn = "";
			}
		}
		function getDatabaseInfo(){
			
		}
		function Query($sql){
			if ($this->db_info["type"] == "mysql"){
				return $this->conn->query($sql);
			} else {
				
			}
		}
	}
?>