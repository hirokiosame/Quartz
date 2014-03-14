<?php

require_once("class.Quartz.php");

// Get Config
$Quartz = new Quartz(True, 1);

// Remove Sessions
session_destroy();

header("Location: /");
?>