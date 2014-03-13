<?php

require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz(True, True);

// Remove Sessions
session_destroy();

header("Location: /");
?>