<?php

require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz(True, True);


print( isset($_GET['ajax']) ? json_encode([ 'jQ' => ['replaceWith' => '.wrapper'], 'html' => Template::home() ]) : Template::htmlWrap("Template::home", [], $Quartz) );

?>