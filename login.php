<?php
require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz();

if( isset($_POST['login']) ){
	print("FUCK ASS");

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