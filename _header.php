<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Quartz</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Favicon? -->

		<link rel="stylesheet/less" type="text/css" href="/css/style.less" />
		<script type="text/javascript" src="/js/less-1.6.3.min.js"></script>
		<script type="text/javascript" src="/js/jquery-2.1.0.min.js"></script>
		<script type="text/javascript" src=/js/quartz.js></script>
	</head>
	<body>
		<!--[if lt IE 8]>
			<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->

		<div class="header">
			<a class="header-title" href="/">Quartz</a>

			<ul>
			<? if( isset($Quartz->account) && $Quartz->account ){ ?>
				<li><a href="/home.php">Home</a></li>
				<li><a href="/website.php?id=<?=$Quartz->account['id']?>">Website</a></li>
				<li><a href="/settings.php">Settings</a></li>
				<? if($Quartz->account['type'] === 'admin'){ ?><li><a href="/admin/">Admin CP</a></li><? } ?>
				<li><a href="/logout.php">Logout</a></li>
			<? }else{ ?>
				<li><a href="/about.php">About</a></li>
				<li><a class="login" href="#">Login</a></li>
				<li class="login">
					<form action="/install.php?step2" method="post">
						<input type="email" placeholder="Email">
						<input type="password" placeholder="Password">
						<input type="submit" value="&#8594;">
					</form>
				</li>
			<? } ?>
			</ul>
		</div>