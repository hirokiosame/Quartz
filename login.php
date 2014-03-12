<?php

require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz(True, False);

// Make Sure Not Logged In
if( $Quartz->account ){
	header("Location: home.php");
}

if(
	isset($_POST['login']) &&
	isset($_POST['email']) && strlen($_POST['email'])>0 &&
	isset($_POST['password']) && strlen($_POST['password'])>0
){


	if( $Quartz->account([ 'email'=>$_POST['email'], 'password'=>$_POST['password'] ])->login() ){
		header("Location: home.php");
	}
	/*
	print_r( $Quartz->MySQLselect("accounts", ) );

	*/


}else{
	?>
	<form method="post">
		<input type="email" name="email" placeholder="email">
		<input type="password" name="password" placeholder="password">
		<input type="submit" name="login" value="Login">
	</form>
	<?
}

?>