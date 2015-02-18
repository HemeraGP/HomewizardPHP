<?php
require("common.php");
if(!isset($gebruiker)) {
	header("Location: index.php");
	die("Redirecting to index.php"); 
} else {

    if(!empty($_POST['password']))
    {
        if(!empty($_POST['password']))
        {
            $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
            $password = hash('sha256', $_POST['password'] . $salt);
            for($round = 0; $round < 65536; $round++)
            {
                $password = hash('sha256', $password . $salt);
            }
        }
        else
        {
            $password = null;
            $salt = null;
        }
        $query_params = array(
            ':username' => $_POST['username'],
        );
        if($password !== null)
        {
            $query_params[':password'] = $password;
            $query_params[':salt'] = $salt;
        }
        $query = "
            UPDATE users
            SET
                
        ";
        if($password !== null)
        {
            $query .= "
                password = :password
                , salt = :salt
            ";
        }
        $query .= "
            WHERE
                username LIKE :username
        ";
        
        try
        {
            $stmt = $dbpdo->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            echo("Failed to run query: " . $ex->getMessage());
        }
    } 

?>
<h2>Wijzig wachtwoord</h2>
<form action="#" method="post">
    Gebruikersnaam:<br /><br/>
    <b><input type="text" name="username" value="<?php echo htmlentities($_COOKIE["HomewizardPHP"], ENT_QUOTES, 'UTF-8'); ?>" /></b>
    
    <br /><br />
    Wachtwoord:<br />
    <input type="password" name="password" value="" /><br />
    
    <br /><br />
    <input type="hidden" name="gebruikers" value="Gebruikers"/>
    <input type="submit" value="Update gebruiker" />
</form>
<?php
}