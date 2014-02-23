<?php

class Quartz{

	// MySQL Connection
	private $mysqli;


	function __construct() {

		// Start Session
		session_start();

		// Include config.php
		$this->config = $this->getConfig();

		// If MySQL Connection fails -> Send to Wizard
		if( $this->config['status'] === 0 || $this->config['mysqli']->connect_error || $this->config['mysqli']->tables_error ){
			header("Location: /install.php");
		}

		// Get Session

		// Get Traffic
		//print_r($this->getTraffic());

		//print("Hi I'm Quartz");
	}


	public static function getConfig(){

		$config = array(
			'path' => $_SERVER['DOCUMENT_ROOT'],
			'file' => '/config.php',
		);

		// We want to store it numerically so different files can handle the error differently (eg. Install.php)
		// Not redable -> might as well not exist -> recreate file instead of intimidating terminal chmod command
		$config['status'] = !is_readable($config['path'] . $config['file']) ? 0 : 1;
		
		// Test MySQL Credentials if config exists
		if(
			// If Exists...
			$config['status'] === 1 &&

			// include in the scope of this function for now for validation
			include( $config['path'] . $config['file'] )
		){ if(
			// Make sure each parameter is set
			defined('MySQL_HOST') && strlen(MySQL_HOST)>0			&&
			defined('MySQL_DB') && strlen(MySQL_DB)>0				&&
			defined('MySQL_USER') && strlen(MySQL_USER)>0			&&
			defined('MySQL_PASSWORD') && strlen(MySQL_PASSWORD)>0
		){

			// Validate MySQL Connection Before Passing it to the Global Scope
			$config['mysqli'] = @new mysqli(MySQL_HOST, MySQL_USER, MySQL_PASSWORD, MySQL_DB );

			// Check that there are tables?
			if(
				!$config['mysqli']->connect_error &&
				$config['mysqli']->query("SHOW TABLES FROM `".MySQL_DB."` WHERE `Tables_in_".MySQL_DB."` LIKE 'myTable' OR `Tables_in_".MySQL_DB."` LIKE 'myTable2'")->num_rows !== 3
			){
				$config['mysqli']->tables_error = 'Tables have not been setup yet.';
			}
		} }

		return $config;
	}


	private static function MySQL(){

		
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

require_once("class.Template.php");

?>