<?php
include_once "parameters.php";
//Importeren parameters, sensors, schakelaars voor alle gebruikers. 
//if(isset($_POST['updateswitches'])) {
	$sql = "select username from users where username not like 'default';";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	while($row = $result->fetch_assoc()){
		$usertoupdate = $row['username'];
		$sqlu="INSERT INTO `settings` (variable, value, favorite, user) SELECT variable, value, favorite, '$usertoupdate' AS user FROM `settings` WHERE user like 'default' AND variable not in (select variable from `settings` where `user` like '$usertoupdate')";
		if(!$resultu = $db->query($sqlu)){ echo('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
		echo '<div class="item wide gradient"><p class="number">2</p><br/>'.$db->affected_rows.' parameters toegevoegd voor gebruiker '.$usertoupdate.'.</div>';
		$sqlu="INSERT INTO `sensors` (id_sensor, volgorde, name, type, favorite, tempk, tempw, correctie, user) SELECT id_sensor, volgorde, name, type, favorite, tempk, tempw, correctie, '$usertoupdate' AS user FROM `sensors` WHERE user like 'default' AND CONCAT(id_sensor, type) not in (select CONCAT(id_sensor, type) from `sensors` where `user` like '$usertoupdate')";
		if(!$resultu = $db->query($sqlu)){ echo('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
		echo '<div class="item wide gradient"><p class="number">2</p><br/>'.$db->affected_rows.' sensoren toegevoegd voor gebruiker '.$usertoupdate.'.</div>';
		$sqlu="INSERT INTO `switches` (id_switch, name, type, favorite, volgorde, temp, user) SELECT id_switch, name, type, favorite, volgorde, temp, '$usertoupdate' AS user FROM `switches` WHERE user like 'default' AND CONCAT(id_switch, type) not in (select CONCAT(id_switch, type) from `switches` where `user` like '$usertoupdate')";
		if(!$resultu = $db->query($sqlu)){ echo('<div class="item wide gradient"><p class="number">2</p><br/>There was an error running the query '.$sql.'<br/>[' . $db->error . ']</div>');}
		echo '<div class="item wide gradient"><p class="number">2</p><br/>'.$db->affected_rows.' schakelaars toegevoegd voor gebruiker '.$usertoupdate.'.</div>';
	}
	$result->free();
//}

?>