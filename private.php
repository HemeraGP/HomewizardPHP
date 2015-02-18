<?php
require("common.php");
if(empty($_COOKIE["HomewizardPHP"])) {
	header("Location: login.php");
	die("Redirecting to login.php");
}    
?>
Hello <?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?>, secret content!<br />

De inhoud van de cookie = <?php echo $_COOKIE["HomewizardPHP"]; ?><br/>
<a href="edit_account.php">Edit Account</a><br />
<a href="logout.php">Logout</a>