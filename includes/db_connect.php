<?php
	class Database{   
		private $host = "localhost";
		private $db_name = "idcoffice";
		private $db_user = "root";
		private $db_pass = "MYPASSWORD123";
		public $connect;

		public function dbConnection(){     
			$this->connect = null;    
			try{
				$timezone = "Africa/Lagos";

				$this->connect = new PDO("mysql:host=" . $this->host . "; dbname=" . $this->db_name, $this->db_user, $this->db_pass);
				$this->connect->exec("SET NAMES utf8");
				$this->connect->exec("SET time_zone = '{$timezone}'");
				$this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch(PDOException $exception){
				echo "Connection error: " . $exception->getMessage();
			}         
			return $this->connect;
		}
	}