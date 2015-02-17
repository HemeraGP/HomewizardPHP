<?php
$username = "dbgebruiksernaam";
$password = "dbwachtwoord";
$host = "localhost";
$dbname = "databasenaam";
$db = new mysqli($host, $username, $password, $dbname);
if($db->connect_errno > 0){ echo('Unable to connect to database [' . $db->connect_error . ']');}
