<?php
require_once("../class.Quartz.php");

// Index Page
$Quartz = new Quartz(true, 2);


if(
	validateInputs(
		$_POST,
		['validEmail']
	)
){
	print_r( (new Account($Quartz->mysql, [ 'email' => $_POST['validEmail'] ]))->register(true) );
}


include('../_header.php');
?>
<div class="wrapper">
	<div class="section">
		<h2>Valid Emails</h2>
		<hr>
		
		<table class="g80 center">
			<tr>
				<th></th>
				<th>Email</th>
			</tr>

			<?
			$query = $Quartz->mysql->query("SELECT * FROM `".MySQL_PREFIX."_accounts` WHERE `registered` = 0");

			while( $account = $query->fetchObject("Account", [ &$Quartz->mysql ]) ){
				print( sprintf('<form method="post" action="accounts.php"><tr><td><input type="hidden" name="removeID" value="%s">%s</td><td>%s</td><td><input type="submit" value="Remove"></td></tr></form>', $account->id, $account->id, $account->email) );
			}
			?>
			<tr>
				<td></td>
				<td>Add New</td>
			</tr>
			<tr>
				<td></td>
				<form method="post" action="/admin/accounts.php">
					<td><input type="email" name="validEmail" placeholder="Email"></td>
					<td><input type="submit" value="Add"></td>
				</form>
			</tr>
		</table>
	</div>

	<div class="section">
		<h2>Manage Accounts</h2>
		<hr>
		<table class="g80 center">
			<tr>
				<th>ID</th>
				<th>Email</th>
				<th>Name</th>
				<th>Registered</th>
				<th>Activated</th>
				<th>Last Active</th>
				<th>Type</th>
			</tr>

			<?
			$query = $Quartz->mysql->query("SELECT * FROM `".MySQL_PREFIX."_accounts` WHERE `registered` != 0");

			while( $account = $query->fetchObject("Account", [ &$Quartz->mysql ]) ){
				print( sprintf('<tr><td>%d</td><td>%s</td><td>%s %s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $account->id, $account->email, $account->lname, $account->fname, $account->registered, $account->activated, $account->lastActive, $account->type) );
			}
			?>
		</table>
	</div>
</div>
<?
include('../_footer.php');

?>