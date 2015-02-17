<?php
require("common.php");
if(!empty($_POST))
    {
        if(empty($_POST['username']))
        {
            die("Please enter a username.");
        }
        if(empty($_POST['password']))
        {
            die("Please enter a password.");
        }
        $query = "SELECT 1 FROM users WHERE username = :username";
        $query_params = array(':username' => $_POST['username']);
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }
        $row = $stmt->fetch();
        if($row)
        {
            die("This username is already in use");
        }
        
       $query = "INSERT INTO users (username,password,salt) VALUES (:username,:password,:salt)";
       $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
       $password = hash('sha256', $_POST['password'] . $salt);
       for($round = 0; $round < 65536; $round++)
        {
            $password = hash('sha256', $password . $salt);
        }
        $query_params = array(
            ':username' => $_POST['username'],
            ':password' => $password,
            ':salt' => $salt
		);
        
        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
            //die("Failed to run query: " . $ex->getMessage());
        }
        header("Location: login.php");
        die("Redirecting to login.php");
    }
    
?>
<h1>Register</h1>
<form action="register.php" method="post">
    Username:<br />
    <input type="text" name="username" value="" />
    <br /><br />
    Password:<br />
    <input type="password" name="password" value="" />
    <br /><br />
    <input type="submit" value="Register" />
</form>
