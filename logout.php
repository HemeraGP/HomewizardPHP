 <?php
require("common.php");
unset($_SESSION['user']);
unset($_COOKIE['HomewizardPHP']);
setcookie("HomewizardPHP", null, time()-1);
header("Location: index.php");
die("Redirecting to: index.php"); 
