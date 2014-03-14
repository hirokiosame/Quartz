<?php
require_once("../class.Quartz.php");

// Index Page
$Quartz = new Quartz(true, 2);





include('../_header.php');
?>
<div class="wrapper">
	<div class="section">
		<h2>Valid Emails</h2>
		<hr>

		<form>
		<input type="">
		</form>
		<table class="g80 center">
			<tr>
				<th>Email</th>
				<th>Name</th>
			</tr>

			<?
			$query = $Quartz->mysql->query("SELECT * FROM `".MySQL_PREFIX."_accounts`");

			while( $account = $query->fetchObject("Account", [ &$Quartz->mysql ]) ){
				print( sprintf('<tr><td>%d</td><td>%s</td><td>%s %s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $account->id, $account->email, $account->lname, $account->fname, $account->registered, $account->activated, $account->lastActive, $account->type) );
			}
			?>
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
			$query = $Quartz->mysql->query("SELECT * FROM `".MySQL_PREFIX."_accounts`");

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