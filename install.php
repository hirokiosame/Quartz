<?php
require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz(False);


// Step 1 - There is no config file or there is an error with it and must be recreated
if(
	$Quartz->config['status'] === 0 	||	// Not Created
	!isset($Quartz->config['mysql'])		// No MySQl Data
){

	// Step 1a - Config File Creation
	if(
		// Check If Input Exists
		isset( $_POST['host'] ) && strlen( $_POST['host'] )>0			&&
		isset( $_POST['database'] ) && strlen( $_POST['database'] )>0	&&
		isset( $_POST['prefix'] ) && strlen( $_POST['prefix'] )>0		&&
		isset( $_POST['username'] ) && strlen( $_POST['username'] )>0	&&
		isset( $_POST['password'] ) && strlen( $_POST['password'] )>0
	){

		// Try MySQL Connection
		$mysqli = @new mysqli($_POST['host'], $_POST['username'], $_POST['password'], $_POST['database']);

		// If Error
		if( $mysqli->connect_error ){

			// Error Message
			$error =	[
							'errors'	=>	[
												'error1' => 'Error: Could not establish MySQL connection. Please verify your credentials.'
											],
							'host'		=> htmlentities($_POST['host']),
							'database'	=> htmlentities($_POST['database']),
							'prefix'	=> htmlentities($_POST['prefix']),
							'username'	=> htmlentities($_POST['username']),
							'password'	=> htmlentities($_POST['password'])
						];

			// Return Error
			print( isset($_POST['ajax']) ? json_encode($error) : Template::htmlWrap( "Template::install_s1", [$error] ) );

		}else{

			// Success!

			// Close Connection
			$mysqli->close();

			if (
				// Check If Writable
				( is_writable($Quartz->config['path']) || is_writable( $Quartz->config['path'] . $Quartz->config['file'] ) ) &&

				// Open File
				$Quartz->configFile = fopen( $Quartz->config['path'] . $Quartz->config['file'], "w" )
			){

				// Write to File
				fwrite($Quartz->configFile, Template::configFile( $_POST ));

				// Close File
				fclose($Quartz->configFile);	

				// Refresh Config
				$Quartz->config = Quartz::getConfig();

			}else{
				//Permissions Denied

				$returnMessage = [
					'html' => '<textarea class="code">' . htmlentities( Template::configFile( $_POST ) ) . '</textarea>'
				];

				// Show Step 1 - Permissions Denied
				print( isset($_POST['ajax']) ? json_encode(['jQ' => ['replaceWith' => '.wrapper'], 'html' => Template::install_s1_denied($returnMessage)]) : Template::htmlWrap("Template::install_s1_denied", [$returnMessage]) );
			}

		}

	}

	// No Requests Sent -- Display Form
	else{
		print( Template::htmlWrap( "Template::install_s1" ) );
	}

}

// Step 2 - MySQL Connection
if( isset($Quartz->config['mysql']) ){


	if(
		// If MySql Table Not Created
		 isset($Quartz->config['mysql']->tables_error) &&

		// Attempt Making Table
		!$Quartz->config['mysql']->query("CREATE TABLE IF NOT EXISTS `".MySQL_PREFIX."_accounts`	(
																								`id`	INT UNSIGNED NOT NULL AUTO_INCREMENT,
																								`email` VARCHAR(50) NOT NULL,
																								`fname` VARCHAR(30) NOT NULL,
																								`lname` VARCHAR(30) NOT NULL,
																								`password` CHAR(128) NOT NULL, #  512 bits / 8 bits per byte * 2 hex digits per byte = 128 hex digits

																								`registered` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, # 0 - Unregistered; Timestamp - Date Activated
																								`activated` TIMESTAMP NOT NULL DEFAULT 0, # 0 - Unactivated; Timestamp - Date Activated
																								`lastActive` TIMESTAMP NOT NULL DEFAULT 0, # 0 - Never Logged In; Timestamp - Date Last Active
																								`type` ENUM('normal', 'admin') DEFAULT 'normal', # Normal User or Admin user

																								PRIMARY KEY(`id`, `email`)
																							) ENGINE=MYISAM;")
	){

		// Failed to Create Table -- MySQL Permissions?
		print("Problem Creating Tables");

	}else

	// No Admin Account Created Yet
	if( $Quartz->config['mysql']->query("SELECT COUNT(*) FROM `".MySQL_PREFIX."_accounts` WHERE `type`='admin'")->fetch(PDO::FETCH_ASSOC)['COUNT(*)'] == 0 ){

		if(
			isset( $_POST['email'] ) && strlen( $_POST['email'] )>0					&&
			isset( $_POST['password1'] ) && strlen( $_POST['password1'] )>0			&&
			isset( $_POST['password2'] ) && strlen( $_POST['password2'] )>0
		){

			// Return Message
			$returnMessage =	[
							'errors'	=>	[],
							'email'		=> $_POST['email']
						];

			// Validation

			// Make Sure Email is Valid
			if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
				$returnMessage['errors']['email'] = "Email must be valid!";
			}

			// Make Sure Password Matches
			if( strlen($_POST['password1'])<6 ){
				$returnMessage['errors']['password1'] = "Passwords must be at least 6 characters!";
			}

			// Make Sure Password Matches
			if( $_POST['password1'] !== $_POST['password2'] ){
				$returnMessage['errors']['password2'] = "Passwords must match!";
			}

			// Insert
			$params = [
				'email' => $_POST['email'],
				'password' => hash('sha512', $_POST['password1']),
				'type' => 'admin',
			];

			// Register
			if( 
				count($returnMessage['errors']) === 0 &&	// No errors
				$Quartz->MySQLinsert("accounts", $params)	// Success Registering
			){
				print( isset($_POST['ajax']) ? json_encode([ 'jQ' => ['replaceWith' => '.wrapper'], 'html' => Template::install_done() ]) : Template::htmlWrap("Template::install_done") );
			}else{
				$returnMessage['errors']['registeration'] = "Something went wrong! Please try again later.";
				print( json_encode($returnMessage) );
			}

		}else{
			// Step 3 - Admin Registration
			print( isset($_POST['ajax']) ? json_encode([ 'jQ' => ['replaceWith' => '.wrapper'], 'html' => Template::install_s2() ]) : Template::htmlWrap("Template::install_s2") );
		}
	}else{
		print("Already Installed -- Just Login!");
	}

}

?>