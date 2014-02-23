<?php
require_once("class.Quartz.php");

// Get Config
$config = Quartz::getConfig();
//print("<pre>"); print_r($config);


// Make sure there is an error with the config file



// Get Step
$step = isset($_GET['step']) && is_numeric($_GET['step']) && $_GET['step']>1 ? intval($_GET['step']) : 1 ;

// Step 1 - There is no config file or there is an error with it and must be recreated
if(
	$config['status'] === 0 				||	// Not Created
	$config['mysqli']->connect_error 			// Connection Failed
){

	// Step 2a - Config File Creation
	if(
		isset($_GET['step']) && is_numeric($_GET['step']) && $_GET['step'] == 2 &&

		// Check If Input Exists
		isset( $_POST['host'] ) && strlen( $_POST['host'] )>0 &&
		isset( $_POST['username'] ) && strlen( $_POST['username'] )>0 &&
		isset( $_POST['password'] ) && strlen( $_POST['password'] )>0 &&
		isset( $_POST['database'] ) && strlen( $_POST['database'] )>0

	){

		// Try MySQL Connection
		$mysqli = @new mysqli($_POST['host'], $_POST['username'], $_POST['password'], $_POST['database']);

		// If Error
		if( $mysqli->connect_error ){

			// Error Message
			$error =	array(
							'errors'	=>	array(
												'error1' => 'Error: Could not establish MySQL connection. Please verify your credentials.'
											),
							'host'		=> htmlentities($_POST['host']),
							'username'	=> htmlentities($_POST['username']),
							'password'	=> htmlentities($_POST['password']),
							'database'	=> htmlentities($_POST['database'])
						);

			// Return Error
			print( isset($_POST['ajax']) ? json_encode($error) : Template::htmlWrap( "Template::install_s1", array($error) ) );

		}else{

			// Success!

			// Check If Writable
			if ( is_writable( $config['path'] . $config['file'] ) ){

				// Create File
				$config = fopen( $config['path'] . $config['file'], "a" );	

				// Flag for Verification
				$verify = 1;

			}else{
				//Permissions Denied

				$return = array(
					'html' => '<textarea class="code">' . htmlentities( Template::configFile() ) . '</textarea>'
				);

				// Show Step 2 - Permissions Denied
				print( isset($_POST['ajax']) ? json_encode(array('jQ' => array('replaceWith' => '.wrapper'), 'html' => Template::install_s2_denied($return))) : Template::htmlWrap("Template::install_s2_denied", array($return)) );
			}

			// When Created - Verify Connection and Make Tables
			if( $_POST['verify'] || $verify === 1 ){


			}
		}
	}else{
		print( Template::htmlWrap( "Template::install_s1" ) );
	}
}else

// Step 3 - Config file created - Tables Not created
if(
	$config['mysqli']->tables_error 		// Tables not created
){

	// Create Table



}


// Step 4 - Admin Registration
?>