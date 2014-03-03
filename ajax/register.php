<?
require_once("../class.Quartz.php");

// Registration
$Quartz = new Quartz();

/*
Account Table
	name
	email
	password

	last_login
	last_active
	registration_date
	online (main page)
	status (0 = unactivated; 1 = activated; 2 = admin)
*/

// Return Message
$returnMessage = array('errors'=>array());

// Only react if there is data sent
if(
	isset($_POST['name']) && strlen($_POST['name'])>0 &&
	isset($_POST['email']) && strlen($_POST['email'])>0 &&
	isset($_POST['password']) && strlen($_POST['password'])>0 &&
	isset($_POST['password2']) && strlen($_POST['password2'])>0
){

	//Validate Information

	// Validate Password
	if( $_POST['pasword'] !== $_POST['password2'] ){
		$returnMessage['errors']['password'] = 'Passwords must match!';
	}

	// Check if Email exists in MySQL Table and is unregistered
	if( $Quartz->validateEmail($_POST['email']) ){
		$returnMessage['errors']['email'] = 'Email is not approved or is already registered!';
	}

	// Register
	if( 
		count($returnMessage['errors']) === 0 && 								// No errors
		$Quartz->register($_POST['name'], $_POST['email'], $_POST['password'])	// Success Registering
	){
		$returnMessage['message'] = "Successfully registered! You will receive an activation email shortly.";
	}else{
		$returnMessage['errors']['registeration'] = "Something went wrong! Please try again later.";
	}
}else{
	$returnMessage['errors']['form'] = "Please fill in every input in the form.";
}

// Display Message
print( json_encode($returnMessage) );
?>