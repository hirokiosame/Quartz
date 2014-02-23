<?
class Template{

	public static function htmlWrap( $callback, $arguments = array() ){
		ob_start();
		include('_header.php');
		print( call_user_func_array($callback, $arguments) );
		include('_footer.php');
		return ob_get_clean();
	}

	public static function install_s1( $params = array() ){
		ob_start(); ?>
		<div class="wrapper">
			<div class="section install">
				<h2>Quartz Setup Wizard</h2>

				<form method="post" action="/install.php?step=2">
				<table class="g50 center">
					<tr>
						<td colspan="2">You have either not setup a <b>config.php</b> file or the MySQL connection failed to establish. Fill in the form to either install Quartz or to fix the connecion!</td>
					</tr>
					<tr class="error error1" style="<?=isset($params['errors'])&&isset($params['errors']['error1'])?'display:table-row':''?>">
						<td colspan="2"><?=isset($params['errors'])&&isset($params['errors']['error1'])?$params['errors']['error1']:''?></td>
					</tr>
					<tr>
						<td><input type="text" name="host" placeholder="Host" value="<?=isset($params['host'])?$params['host']:'localhost'?>"></td>
						<td>The host on which your MySQL server is located. <i>eg. localhost</i></td>
					</tr>
					<tr>
						<td><input type="text" name="database" placeholder="Database Name" value="<?=isset($params['database'])?$params['database']:'quartz'?>"></td>
						<td>The name of your MySQL database. <i>eg. quartz</i></td>
					</tr>
					<tr>
						<td><input type="text" name="username" placeholder="Username" value="<?=isset($params['username'])?$params['username']:'username'?>"></td>
						<td>The username for your database. <i>eg. root</i></td>
					</tr>
					<tr>
						<td><input type="text" name="password" placeholder="Password" value="<?=isset($params['password'])?$params['password']:'password'?>"></td>
						<td>The password for your username. <i>eg. root</i></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Connect to MySQL"></td>
					</tr>
				</table>
				</form>
			</div>
		</div>
<?		return ob_get_clean();
	}

	public static function configFile( $params = array() ){
		ob_start();
		?>

		// Host MySQL is located on
		define('MySQL_HOST', '<?=addslashes(isset($params['host'])?$params['host']:'localhost')?>');

		// MySQL DB
		define('MySQL_DB', '<?=addslashes(isset($params['database'])?$params['database']:'quartz')?>');

		// MySQL User
		define('MySQL_USER', '<?=addslashes(isset($params['username'])?$params['username']:'username')?>');

		// Password for MySQL User
		define('MySQL_PASSWORD', '<?=addslashes(isset($params['password'])?$params['password']:'password')?>');

<?		return "<?".ob_get_clean()."?>";
	}


	public static function install_s2_denied( $params = array() ){
		ob_start(); ?>
		<div class="wrapper">
			<div class="section install">
				<h2>Quartz Setup Wizard Step 2</h2>
				<div class="g60 center">
					<p>Successfully connected to MySQL! However, permission was denied trying to create <b>config.php</b> in the Quartz directory. Copy and paste the code below into a plain text editor and save it as <b>config.php</b> in the Quartz folder.</p>
					<br>
					<?=$params['html']?>
					<form class="step2" method="post" action="/install.php?step=3">
						<input type="hidden" name="configReady" value="1">
						<input type="submit" value="Config File is Made">
					</form>
				</div>
			</div>
		</div>
<?		return ob_get_clean();
	}
}