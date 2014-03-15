<?php

//namespace Quartz;


class Account{

	function __construct(&$mysql, $account = false){
		$this->mysql = $mysql;
		$this->account = $account;
	}



	public function login(){

		// No Credentials to Login With
		if( count($this->account)==0 ){ return false; }

		// Don't Select By Password
		if( isset($this->account['password']) ){
			$password = $this->account['password'];
			unset($this->account['password']);
		}

		// Prepare Query
		$query =	sprintf(
						"SELECT * FROM `%s_%s` WHERE %s LIMIT 1",
						MySQL_PREFIX,
						"accounts",
						implode(
							' AND ',
							array_map(function($key){
								return sprintf("`%s`=:%s", $key, $key);
							}, array_keys($this->account))
						)
					);

		// Throw in Error Handlers
		try{
			// Prepare & Execute
			$stmt = $this->mysql->prepare($query);
			$stmt->execute($this->account);

			// If No Result
			if( !$this->account = $stmt->fetch(PDO::FETCH_ASSOC) ){
				return false;
			}
		}catch(PDOException $e){
			// Store in a Log File Instead...
			//print_r($e->getMessage());
			return false;
		}

		// If Passwords Don't Match
		if( isset($password) && !password_verify($password, $this->account['password']) ){
			return false;
		}

		// Update Last Active
		$this->mysql->query("UPDATE `".MySQL_PREFIX."_accounts` SET `lastActive` = CURRENT_TIMESTAMP WHERE `id` = ".$this->account['id']);

		// Set to Session
		$_SESSION['id'] = $this->account['id'];

		// Close
		$stmt->closeCursor();

		return $this->account;

	}
	public function register(){
	
		$query = sprintf(
					"INSERT INTO `%s_%s` (`%s`) VALUES (:%s)",
					MySQL_PREFIX,
					"accounts",
					implode( "`, `", array_keys($this->account) ),
					implode(", :", array_keys($this->account))
				);

		try{
			// Prepare & Execute
			$stmt = $this->mysql->prepare($query);
			$stmt->execute($this->account);
			$stmt->closeCursor();
		}catch(PDOException $e){
			// Store in a Log File Instead...
			//print_r($e->getMessage());
			return false;
		}

		// Set to Session
		$_SESSION['id'] = $this->mysql->lastInsertId();

		return $this->account;
	}
	
}


class Quartz{

	function __construct($checkInstall = True, $priviliges = 0){

		// Version Check
		if( version_compare(PHP_VERSION, '5.3.1', '<') ){
			die('Your host needs to use PHP 5.3.1 or higher to run this version of Quartz!');
		}

		// Start Session
		session_start();

		// Include config.php
		$this->getConfig();

		// If MySQL Connection fails -> Send to Installation Wizard
		if( $checkInstall && ($this->config['status'] === 0 || !isset($this->mysql) || isset($this->mysql->tables_error) ) ){
			header("Location: /install.php");
		}
		//session_destroy();
		//print_r($_SESSION);

		// Check if Logged In
		$this->account = (new Account($this->mysql, $_SESSION))->login();


		// If Page Requires you to be Logged out, but you're logged in
		if( $priviliges === 0 && $this->account ){
			header("Location: /home.php");
		}

		// If You're not logged in, and page requires you to be logged in
		if( $priviliges === 1 && !$this->account ){
			header("Location: /login.php");
		}

		// You must be an admin
		if( $priviliges === 2 && $this->account && $this->account['type'] !== 'admin' ){
			header("Location: /home.php");
		}

		// Get Traffic
		//print_r($this->getTraffic());
	}



	public function getConfig(){

		$this->config = array(
			'path' => $_SERVER['DOCUMENT_ROOT'],
			'file' => '/config.php'
		);

		// We want to store it numerically so different files can handle the error differently (eg. Install.php)
		// Not redable -> might as well not exist -> recreate file instead of intimidating terminal chmod command
		$this->config['status'] = !is_readable($this->config['path'] . $this->config['file']) ? 0 : 1;
		
		// Test MySQL Credentials if config exists
		if(
			// If Exists...
			$this->config['status'] === 1 &&

			// include in the scope of this function for validation
			include( $this->config['path'] . $this->config['file'] )
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
				$this->mysql = new PDO(sprintf("mysql:host=%s;dbname=%s", MySQL_HOST, MySQL_DB), MySQL_USER, MySQL_PASSWORD, array(
					
					// Persistent Connections
					PDO::ATTR_PERSISTENT => true,

					// Throw Errors
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				));

			} catch (PDOException $e){
				print($e);
			}


			// Check that there are tables?
			if(
				isset($this->mysql) &&
				$this->mysql->query("SHOW TABLES WHERE `Tables_in_".MySQL_DB."` LIKE '".MySQL_PREFIX."_accounts' OR `Tables_in_".MySQL_DB."` LIKE '".MySQL_PREFIX."_accounts'")->rowCount() !== 1
			){
				$this->mysql->tables_error = 'Tables have not been setup yet.';
			}

			/*
			$this->config['mysqli'] = @new mysqli( MySQL_HOST, MySQL_USER, MySQL_PASSWORD, MySQL_DB );

			// Check that there are tables?
			if(
				!$this->config['mysqli']->connect_error &&
				$this->config['mysqli']->query("SHOW TABLES FROM `".MySQL_DB."` WHERE `Tables_in_".MySQL_DB."` LIKE '".MySQL_PREFIX."_accounts' OR `Tables_in_".MySQL_DB."` LIKE '".MySQL_PREFIX."_accounts'")->num_rows !== 1
			){
				$this->config['mysqli']->tables_error = 'Tables have not been setup yet.';
			}
			*/
		} }
	}

	public function account($attr = false){
		return $this->account = new Account($this->mysql, $attr);
	}


	public function MySQLinsert($table, $params){
		$query = sprintf("INSERT INTO `%s_%s` (`%s`) VALUES (:%s)", MySQL_PREFIX, $table, implode( "`, `", array_keys($params) ), implode(", :", array_keys($params)));
		$stmt = $this->mysql->prepare($query);
		return $stmt->execute($params) && $stmt->closeCursor();
	}


	public function MySQLselect($table, $params, $returnAll=True){
		$query = sprintf("SELECT * FROM `%s_%s` WHERE %s", MySQL_PREFIX, $table, implode(' AND ', array_map(function($key){ return sprintf("`%s`=:%s", $key, $key); }, array_keys($params))));

		// Throw in Error Handlers
		$stmt = $this->mysql->prepare($query);
		$stmt->execute($params);

		if( !$returnAll ){ return $stmt; } // Maybe return a Reference instead?
		$return = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();

		return $return;
	}




	/*
	private function getTraffic(){
		//Check if Session is set

		return array(
					"time" => $_SERVER['REQUEST_TIME'],
					"IP" => $_SERVER['REMOTE_ADDR'],
					"UserAgent" => $_SERVER['HTTP_USER_AGENT'],
					"RequestURI" => $_SERVER['REQUEST_URI']
				);
	}
	*/

}

require_once("class.Template.php");

?>