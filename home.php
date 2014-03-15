<?php

require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz(True, 1);


print(
	isset($_GET['ajax']) ?
		json_encode([
			'url' => 'home.php',
			'jQ' => [
				'replaceWith' => [
					'.wrapper'=>Template::home()
				],
				'html' => [
					'ul.links'=>Template::headerLinks($Quartz)
				]
			]
		]) :
		Template::htmlWrap("Template::home", [], $Quartz)
);

?>