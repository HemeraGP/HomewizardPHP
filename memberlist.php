<?php
require("common.php");
if(!isset($gebruiker)) {
	header("Location: index.php");
	die("Redirecting to index.php"); 
} else {
	
$query = "SELECT username FROM users";
try
    {
        $stmt = $dbpdo->prepare($query);
        $stmt->execute();
    }
    catch(PDOException $ex)
    {
        die("Failed to run query: " . $ex->getMessage());
    }
    $rows = $stmt->fetchAll();
?>
<h2>Gebruikers</h2>
<table align="center">
    <?php foreach($rows as $row): ?>
        <tr>
            <td><?php echo htmlentities($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><form action="#" method="post">
            	<input type="hidden" name="gebruikers" value="Gebruikers"/>
                <input type="hidden" name="gebruikersnaam" value="<?php echo $row['username']; ?>"/>
                <input type="submit" name="verwijdergebruiker" value="Wissen" class="abutton gradient">
                </form></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
}
