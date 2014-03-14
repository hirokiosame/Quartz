<?php

require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz(True, 0);

// Make Sure Not Logged In
if( $Quartz->account ){
	header("Location: home.php");
}

if(
	isset($_POST['email']) && strlen($_POST['email'])>0 &&
	isset($_POST['password']) && strlen($_POST['password'])>0
){

	// Attempt Login
	if( $attempt = $Quartz->account([ 'email'=>$_POST['email'], 'password'=>$_POST['password'] ])->login() ){
		header("Location: /home.php".(isset($_POST['ajax']) ? "?ajax=1" : "" ));
	}

	// Return Message
	$returnMessage =	[
					'errors'	=>	[
						'error1'	=> 'Error logging in... <a href="/forgot.php">Did you forget your password?</a>'
					],
					'inputs'	=> [
						'email'		=> $_POST['email'],
						'password'	=> ''
					]
				];

	
	print( json_encode($returnMessage) );

}else{
	include("_header.php");
	?>

		<div class="wrapper">
			<div class="section">
				<h2>Quartz Login</h2>

				<form method="post" action="/login.php">
				<table class="g40 center">
					<tr class="error error1" style="<?=isset($params['errors'])&&isset($params['errors']['error1'])?'display:table-row':''?>">
						<td colspan="2"><?=isset($params['errors'])&&isset($params['errors']['error1'])?$params['errors']['error1']:''?></td>
					</tr>
					<tr>
						<td colspan="2"><div class="input-append"><input required autofocus type="email" name="email" placeholder="Email" value="<?=isset($params['email'])?htmlentities($params['email'], ENT_QUOTES):''?>"><span class="input-append">@bu.edu</span></div></td>
					</tr>
					<tr>
						<td colspan="2"><input required type="password" name="password" placeholder="Password" pattern=".{6,}" value=""></td>
					</tr>
					<tr>
						<td class="text-left"><label><input type="checkbox" name="remember" value="1"> Remember me</label></td>
						<td width="50%"><input type="submit" value="Login"></td>
					</tr>
					<tr>
					</tr>
				</table>
				</form>
			</div>
		</div>

	<?
	include("_footer.php");
}

?>