<?php

require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz(True, 1);


print(
	isset($_GET['ajax']) ?
		json_encode([
				'jQ' => ['replaceWith' => ['.wrapper'=>Template::home()] ],
		]) :
		Template::htmlWrap("Template::home", [], $Quartz)
);

?>