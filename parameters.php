<?php
$username = "homewizard";
$password = "home!wizard";
$host = "localhost";
$dbname = "homewizard";
$db = new mysqli($host, $username, $password, $dbname);
if($db->connect_errno > 0){ echo('Unable to connect to database [' . $db->connect_error . ']');}
