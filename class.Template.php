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
				<h2>Quartz Setup Wizard | Configuration</h2>

				<form method="post" action="/install.php">
				<table class="g50 center">
					<tr>
						<td colspan="2">You have either not setup a <b>config.php</b> file or the MySQL connection failed to establish. Fill in the form to either install Quartz or to fix the connecion!</td>
					</tr>
					<tr class="error error1" style="<?=isset($params['errors'])&&isset($params['errors']['error1'])?'display:table-row':''?>">
						<td colspan="2"><?=isset($params['errors'])&&isset($params['errors']['error1'])?$params['errors']['error1']:''?></td>
					</tr>
					<tr>
						<td><input required autofocus type="text" name="host" placeholder="Host" value="<?=isset($params['host'])?htmlentities($params['host'], ENT_QUOTES):'localhost'?>"></td>
						<td>The host on which your MySQL server is located. <i>eg. localhost</i></td>
					</tr>
					<tr>
						<td><input required type="text" name="database" placeholder="Database Name" value="<?=isset($params['database'])?htmlentities($params['database'], ENT_QUOTES):'quartz'?>"></td>
						<td>The name of your MySQL database. <i>eg. quartz</i></td>
					</tr>
					<tr>
						<td><input required type="text" name="prefix" placeholder="Table Prefix" value="<?=isset($params['prefix'])?htmlentities($params['prefix'], ENT_QUOTES):'qz'?>"></td>
						<td>Prefix for MySQL Quartz tables for shared databases. <i>eg. qz</i></td>
					</tr>
					<tr>
						<td><input required type="text" name="username" placeholder="Username" value="<?=isset($params['username'])?htmlentities($params['username'], ENT_QUOTES):'username'?>"></td>
						<td>Username for your database. <i>eg. root</i></td>
					</tr>
					<tr>
						<td><input required type="text" name="password" placeholder="Password" value="<?=isset($params['password'])?htmlentities($params['password'], ENT_QUOTES):'password'?>"></td>
						<td>Password for your username. <i>eg. root</i></td>
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
	define('MySQL_HOST', '<?=isset($params['host'])?addslashes($params['host']):'localhost'?>');

	// MySQL DB
	define('MySQL_DB', '<?=isset($params['database'])?addslashes($params['database']):'quartz'?>');

	// MySQL PREFIX
	define('MySQL_PREFIX', '<?=isset($params['prefix'])?addslashes($params['prefix']):'qz'?>');

	// MySQL User
	define('MySQL_USER', '<?=isset($params['username'])?addslashes($params['username']):'username'?>');

	// Password for MySQL User
	define('MySQL_PASSWORD', '<?=isset($params['password'])?addslashes($params['password']):'password'?>');

<?		return "<?\n".ob_get_clean()."?>";
	}


	public static function install_s1_denied( $params = array() ){
		ob_start(); ?>
		<div class="wrapper">
			<div class="section install">
				<h2>Quartz Setup Wizard | Connection Established </h2>
				<div class="g60 center">
					<p>Successfully connected to MySQL! However, permission was denied trying to create <b>config.php</b> in the Quartz directory. Copy and paste the code below into a plain text editor and save it as <b>config.php</b> in the Quartz folder.</p>
					<br>
					<?=$params['html']?>
					<form class="step2" method="post" action="/install.php">
						<input type="hidden" name="configReady" value="1">
						<input type="submit" value="Config File is Made">
					</form>
				</div>
			</div>
		</div>
<?		return ob_get_clean();
	}

	public static function install_s2( $params = array() ){
		ob_start(); ?>
		<div class="wrapper">
			<div class="section install">
				<h2>Quartz Setup Wizard | Registration</h2>

				<form method="post" action="/install.php">
				<table class="g50 center">
					<tr>
						<td colspan="2">You have successfully created a configuration file! Create your admin account to get started!</td>
					</tr>
					<tr class="error error1" style="<?=isset($params['errors'])&&isset($params['errors']['error1'])?'display:table-row':''?>">
						<td colspan="2"><?=isset($params['errors'])&&isset($params['errors']['error1'])?$params['errors']['error1']:''?></td>
					</tr>
					<tr class="error email"><td colspan="2"></td></tr>
					<tr>
						<td><input required autofocus type="email" name="email" placeholder="Email" value="<?=isset($params['email'])?htmlentities($params['email'], ENT_QUOTES):''?>"></td>
						<td>Enter your login email address.</td>
					</tr>
					<tr class="error password1"><td colspan="2"></td></tr>
					<tr>
						<td><input required type="password" name="password1" placeholder="Password" pattern=".{6,}" value=""></td>
						<td>Choose a secure password of at least 6 characters.</td>
					</tr>
					<tr class="error password2"><td colspan="2"></td></tr>
					<tr>
						<td><input required type="password" name="password2" placeholder="Confirm Password" pattern=".{6,}" value=""></td>
						<td>Confirm your password.</td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Register Admin Account"></td>
					</tr>
				</table>
				</form>
			</div>
		</div>
<?		return ob_get_clean();
	}

	public static function install_done( $params = array() ){
		ob_start(); ?>
		<div class="wrapper">
			<div class="section install">
				<h2>Installation Completed</h2>
				<div class="g60 center">
					Your admin account has been successfully registered! You will be logged in shortly...
				</div>
			</div>
		</div>
<?		return ob_get_clean();
	}




}