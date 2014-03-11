<?php

//namespace Quartz;

/*
class MySQL{
	private $connection;
	function __construct($host, $database, $username, $password){
		try {
			$this->connection = new PDO(sprintf("mysql:host=%s;dbname=%s", $host, $database), $username, $password, array(
				PDO::ATTR_PERSISTENT => true
			));
		} catch (PDOException $e){}

	}

	// INSERT
	public function insertMySQL($table, $params){


		$query = "INSERT INTO `".MySQL_PREFIX."_".$table."` (`".implode( "`, `", array_keys($params) )."`) VALUES (:".implode(", :", array_keys($params)).")";
		print($query);
		if( !($stmt = $config['mysqli']->prepare($query)) ){
			print("Prepare failed: (" . $config['mysqli']->errno . ") " . $config['mysqli']->error);
			return false;
		}



		
		//return $query;
		foreach( $params as $key => $val ){
			if( !$stmt->bindParam(':'.$key, $val) ){
				print("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
				return false;
			}
			print( gettype($val)."\n" );
		}
		

	}
}
*/

class Quartz{

	// MySQL Connection
	private $mysqli;

	function __construct($checkInstall = True){

		// Version Check
		if( version_compare(PHP_VERSION, '5.3.1', '<') ){
			die('Your host needs to use PHP 5.3.1 or higher to run this version of Quartz!');
		}

		// Start Session
		session_start();

		// Include config.php
		$this->config = $this->getConfig();

		// If MySQL Connection fails -> Send to Installation Wizard
		if( $checkInstall && ($this->config['status'] === 0 || !isset($this->config['mysql']) || isset($this->config['mysql']->tables_error) ) ){
			header("Location: /install.php");
		}

		// Get Session


		// Get Traffic
		//print_r($this->getTraffic());
	}



	public function getConfig(){

		$config = array(
			'path' => $_SERVER['DOCUMENT_ROOT'],
			'file' => '/config.php'
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
			defined('MySQL_PREFIX') && strlen(MySQL_PREFIX)>0		&&
			defined('MySQL_USER') && strlen(MySQL_USER)>0			&&
			defined('MySQL_PASSWORD') && strlen(MySQL_PASSWORD)>0
		){

			// Connect to MySQL
			try {
				$config['mysql'] = new PDO(sprintf("mysql:host=%s;dbname=%s", MySQL_HOST, MySQL_DB), MySQL_USER, MySQL_PASSWORD, array(
					PDO::ATTR_PERSISTENT => true
				));
			} catch (PDOException $e){}


			// Check that there are tables?
			if(
				isset($config['mysql']) &&
				$config['mysql']->query("SHOW TABLES WHERE `Tables_in_".MySQL_DB."` LIKE '".MySQL_PREFIX."_accounts' OR `Tables_in_".MySQL_DB."` LIKE '".MySQL_PREFIX."_accounts'")->rowCount() !== 1
			){
				$config['mysql']->tables_error = 'Tables have not been setup yet.';
			}

			/*
			$config['mysqli'] = @new mysqli( MySQL_HOST, MySQL_USER, MySQL_PASSWORD, MySQL_DB );

			// Check that there are tables?
			if(
				!$config['mysqli']->connect_error &&
				$config['mysqli']->query("SHOW TABLES FROM `".MySQL_DB."` WHERE `Tables_in_".MySQL_DB."` LIKE '".MySQL_PREFIX."_accounts' OR `Tables_in_".MySQL_DB."` LIKE '".MySQL_PREFIX."_accounts'")->num_rows !== 1
			){
				$config['mysqli']->tables_error = 'Tables have not been setup yet.';
			}
			*/
		} }

		return $config;
	}


	public function MySQLinsert($params){
		$stmt = $this->config['mysql']->prepare("INSERT INTO `".MySQL_PREFIX."_accounts` (`".implode( "`, `", array_keys($params) )."`) VALUES (:".implode(", :", array_keys($params)).")");
		return $stmt->execute($params) && $stmt->closeCursor();
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