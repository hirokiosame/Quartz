<?php

require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz(True, True);


include("_header.php");

?>

<div class="wrapper">
	<div class="section">
		<h2>Settings</h2>
		<hr class="soften">
		<table class="g70 center">
			<tr>
				<th>Name</th><td><input required type="text" placeholder="First" value="<?=$Quartz->account['fname']?>"></td><td><input required type="text" placeholder="Last" value="<?=$Quartz->account['lname']?>"></td>
			</tr>
			<tr>
				<th>Email</th><td><input required type="email" placeholder="Email Address" value="<?=$Quartz->account['email']?>"></td>
			</tr>
		</table>
	</div>
	<div class="section">
		<h2>Change Password</h2>
		<hr class="soften">
		<table class="g70 center">
			<tr> <th>Old Password</th><td><input required type="password" name="password0"></td> </tr>
			<tr> <th>New Password</th><td><input required type="password" name="password1"></td> </tr>
			<tr> <th>Confirm Password</th><td><input required type="password" name="password2"></td> </tr>
		</table>
	</div>
</div>
<?

	include("_footer.php");
?>