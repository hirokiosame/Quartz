<?php

require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz(True, False);

// Make Sure Not Logged In
if( $Quartz->account ){
	header("Location: home.php");
}

if(
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
	include("_header.php");
	?>

		<div class="wrapper">
			<div class="section">
				<h2>Quartz Login</h2>

				<form method="post" action="/login.php">
				<table class="g50 center">
					<tr class="error error1" style="<?=isset($params['errors'])&&isset($params['errors']['error1'])?'display:table-row':''?>">
						<td colspan="2"><?=isset($params['errors'])&&isset($params['errors']['error1'])?$params['errors']['error1']:''?></td>
					</tr>
					<tr class="error email"><td colspan="2"></td></tr>
					<tr>
						<td><input required autofocus type="email" name="email" placeholder="Email" value="<?=isset($params['email'])?htmlentities($params['email'], ENT_QUOTES):''?>">@bu.edu</td>
					</tr>
					<tr class="error password1"><td colspan="2"></td></tr>
					<tr>
						<td><input required type="password" name="password" placeholder="Password" pattern=".{6,}" value=""></td>
					</tr>
					<tr class="error password2"><td colspan="2"></td></tr>
					<tr>
						<td><input type="checkbox" name="remember" value="1"> Keep me logged in</td>
					</tr>
					<tr>
						<td><input type="submit" value="Login"></td>
					</tr>
				</table>
				</form>
			</div>
		</div>

	<?
	include("_footer.php");
}

?>