<?php
    require("common.php");
    $submitted_username = '';
    if(!empty($_POST))
    {
        $query = "SELECT id, username, password, salt FROM users WHERE username = :username";
        $query_params = array(':username' => $_POST['username']);
        try
        {
            $stmt = $dbpdo->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex)
        {
           die("Failed to run query: " . $ex->getMessage());
        }
        
        $login_ok = false;
        $row = $stmt->fetch();
        if($row)
        {
            $check_password = hash('sha256', $_POST['password'] . $row['salt']);
            for($round = 0; $round < 65536; $round++)
            {
                $check_password = hash('sha256', $check_password . $row['salt']);
            }
            
            if($check_password === $row['password'])
            {
                $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
				$expirytime = time()+$_POST['expiry'];
				setcookie("HomewizardPHP", $submitted_username, $expirytime);
				$login_ok = true;
            }
        }
        if($login_ok)
        {
            unset($row['salt']);
            unset($row['password']);
            //$_SESSION['user'] = $row;
            header("Location: index.php");
            die("Redirecting to: index.php");
        }
        else
        {
            print("Login Failed.");
            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
			header("Location: settings.php");
            die("Redirecting to: settings.php");
        }
    }
    
?>
<h1>Login</h1>
<form action="login.php" method="post">
    Username:<br />
    <input type="text" name="username" value="<?php echo $submitted_username; ?>" />
    <br /><br />
    Password:<br />
    <input type="password" name="password" value="" />
    <br /><br />
	<select name="expiry">
        <option value="31536000">1 Jaar</option>
        <option value="2678400">1 Maand</option>
		<option value="604800">1 Week</option>
		<option value="172800">2 Dagen</option>
        <option value="86400">1 Dag</option>
        <option value="3600">1 Uur</option>
        <option value="900">15 Minuten</option>
        <option value="300">5 Minuten</option>
    </select>    
    <input type="submit" value="Login" />
</form>
