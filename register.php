<?php
require("common.php");
if(!isset($gebruiker)) {
	header("Location: index.php");
	die("Redirecting to index.php"); 
} else {
if(!empty($_POST['newusername']))
    {
        if(empty($_POST['newusername']))
        {
            die("Please enter a username.");
        }
        if(empty($_POST['newpassword']))
        {
            die("Please enter a password.");
        }
        $query = "SELECT 1 FROM users WHERE username = :username";
        $query_params = array(':username' => $_POST['newusername']);
        try
        {
            $stmt = $dbpdo->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }
        $row = $stmt->fetch();
        if($row)
        {
            echo("<font size='+1'><br/><font color=\"#FF0000\">Deze gebruikersnaam bestaat al.</font></font>");
        }
        
       $query = "INSERT INTO users (username,password,salt) VALUES (:username,:password,:salt)";
       $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
       $password = hash('sha256', $_POST['newpassword'] . $salt);
       for($round = 0; $round < 65536; $round++)
        {
            $password = hash('sha256', $password . $salt);
        }
        $query_params = array(
            ':username' => $_POST['newusername'],
            ':password' => $password,
            ':salt' => $salt
		);
        
        try
        {
            $stmt = $dbpdo->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            //die("Failed to run query: " . $ex->getMessage());
        }
        //header("Location: settings.php");
        //die("Redirecting to settings.php");
    }
    
?>
<h2>Registreer nieuwe gebruiker</h2>
<form action="#" method="post">
    Gebruikersnaam:<br />
    <input type="text" name="newusername" value="" />
    <br /><br />
    Wactwoord:<br />
    <input type="password" name="newpassword" value="" />
    <br /><br />
	<input type="hidden" name="gebruikers" value="Gebruikers" class="abutton settings gradient"/>
    <input type="submit" value="Register" />
</form>
<?php
}