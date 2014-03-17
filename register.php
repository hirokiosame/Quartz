<?php

require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz(True, 0);



if( validateInputs( $_POST, ['fname', 'lname', 'email', 'username', 'password1', 'password2'] ) ){

	$Quartz->account([
		'fname' => $_POST['fname'],
		'lname' => $_POST['lname'],
		'email' => $_POST['email'],
		'username' => $_POST['username'],
		'password1' => $_POST['password1'],
		'password2' => $_POST['password2']
		])->register();


	// If Errors
	if( count($Quartz->account->errors)>0 ){

		// Return Message
		print( json_encode([
			'errors'	=>	$Quartz->account->errors,

			// Encoded Here incase ajax isn't supported
			'inputs'	=> [
				'fname' => $_POST['fname'],
				'lname' => $_POST['lname'],
				'email' => $_POST['email'],
				'username' => $_POST['username'],
				'password1' => '',	//For Ajax
				'password2' => ''
			]
		]) );

	}else{
		print( isset($_POST['ajax']) ?
			json_encode([
				'jQ' => ['replaceWith' => ['.wrapper' => "Done! Activation mail"]]
			]) :
			"non-ajax"
		);
	}
}else{
	include("_header.php");
	print( Template::register() );
	include("_footer.php");
}

?>