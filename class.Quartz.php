<?php

require_once("config.php");

class Quartz{


	// MySQL Connection
	private $mysqli;



	function __construct() {

		// Start Session
		session_start();

		// Establish MySQL Connection
		$this->MySQL();

		// Get Session

		// Get Traffic
		//print_r($this->getTraffic());

		//print("Hi I'm Quartz");
	}



	private function MySQL(){

		
		// Connect
		$this->mysqli = @new mysqli(MySQL_HOST, MySQL_USER, MySQL_PASSWORD, MySQL_DB) or die("AD");

		// Error Connecting
		if( $this->mysqli->connect_error ){
			//print("<pre>");	
			//print_R($_SERVER);
			header("Location: /install.php");
			print("Go to Installation Wiz to Fix");
		}





		/*
		// Create Database if doesn't exist
		$this->mysqli->query("CREATE DATABASE IF NOT EXISTS `quartz`;") or die();

		// Select Database
		$this->mysqli->select_db("quartz");

		// Create Table if doesn't exist
		$this->mysqli->query("
			CREATE TABLE IF NOT EXISTS `people`	(
													`name` VARCHAR(30) NOT NULL,
													`age` smallint(3) unsigned NOT NULL,
													PRIMARY KEY(`name`)
												) ENGINE=InnoDB;
		") or die();
		*/
	}





	private function getTraffic(){
		//Check if Session is set

		return array(
					"time" => $_SERVER['REQUEST_TIME'],
					"IP" => $_SERVER['REMOTE_ADDR'],
					"UserAgent" => $_SERVER['HTTP_USER_AGENT'],
					"RequestURI" => $_SERVER['REQUEST_URI']
				);
	}

}

?>