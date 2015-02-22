<?php
include "header.php";
include "functions.php";
if($authenticated == true) {
	if (isset($_POST['schakel'])) {
		if (isset($_POST['dimlevel'])) echo dim($_POST['switch'],$_POST['dimlevel'],$gebruiker,null,null);
		else if (isset($_POST['somfy'])) echo somfy($_POST['switch'],$_POST['somfy'],$gebruiker,null,null);
		else if (isset($_POST['schakel'])) echo schakel($_POST['switch'],$_POST['schakel'],$gebruiker,null,null);
	} 
	if(isset($_POST['radiator']) && isset($_POST['set_temp'])) echo radiator($_POST['radiator'],$_POST['set_temp'],$gebruiker,null,null);
	if (isset($_POST['schakelscene'])) echo scene($_POST['scene'],$_POST['schakelscene'],$gebruiker,null,null);
	if (isset($_POST['updactie'])) {
		$variable = $_POST['variable'];
		if($_POST['updactie']=='off') $value = 'no'; else $value = 'yes';
		$sql="update settings set value = '$value' where variable like '$variable';";
		if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	}
}
if($toon_radiatoren=='yes' || $toon_sensoren=='yes') {
	$sql="SELECT a.id_sensor, te, hu FROM temperature a INNER JOIN ( SELECT MAX(timestamp) timestamp, id_sensor FROM temperature b GROUP BY b.id_sensor ) b ON a.id_sensor = b.id_sensor AND a.timestamp = b.timestamp;";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	while($row = $result->fetch_assoc()){
		${'thermometerlastte'.$row['id_sensor']} = $row['te'];
		${'thermometerlasthu'.$row['id_sensor']} = $row['hu'];
	}
}
	
include "data.php";
echo '<div class="isotope">';
if($authenticated == true) {
	//---SCHAKELAARS---
	if($toon_schakelaars=='yes') {
		echo '<div class="item gradient"><p class="number">'.$positie_schakelaars.'</p>
				<form id="showallswitches" action="#" method="post">
					<input type="hidden" name="showallswitches" value="yes" />
					<a href="#" onclick="document.getElementById(\'showallswitches\').submit();" style="text-decoration:none"><h2 >Schakelaars</h2></a>
				</form>';
		$sql="select id_switch, name, type, favorite, volgorde from switches where type in ('switch', 'dimmer', 'virtual', 'asun') AND volgorde < 20000 AND user like '$gebruiker'";
		if (!isset($_POST['showallswitches'])) $sql.=" AND favorite like 'yes'";
		$sql.=" order by volgorde asc, favorite desc, name asc";
		if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
		if($result->num_rows>0) {
			$group = 0;
			echo '
			<table align="center"><tbody>';
			while($row = $result->fetch_assoc()){
				$switchon = "";
				$tdstyle = '';
				if($group != $row['volgorde']) $tdstyle = 'style="'.$css_td_newgroup.'"';
				$group = $row['volgorde'];
				if($row['type']!='asun') {if(${'switchstatus'.$row['id_switch']}=="on") {$switchon = "off";} else {$switchon = "on";}}
				echo '<tr>
					<td><img id="'.$row['type'].'Icon" src="images/empty.gif" width="1px" height="1px" /></td>
					<td align="right" '.$tdstyle.'>
						<form action="switchhistory.php" method="post" id="'.$row['name'].'">
							<input type="hidden" name="filter" value="'.$row['name'].'">
							<a href="#" onclick="document.getElementById(\''.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
						</form>
					</td>
					<td width="115px" '.$tdstyle.' ><form method="post" action="#"><input type="hidden" name="switch" value="'.$row['id_switch'].'"/><input type="hidden" name="schakel" value="'.$switchon.'"/>';
				if($row['type']=='dimmer') {
					echo '<select name="dimlevel"  class="abutton handje gradient" onChange="this.form.submit()" style="margin-top:4px">
					<option '.${'switchstatus'.$row['id_switch']}.') selected>'.${'switchstatus'.$row['id_switch']}.'</option>
					<option>0</option>
					<option>10</option>
					<option>20</option>
					<option>30</option>
					<option>40</option>
					<option>50</option>
					<option>60</option>
					<option>70</option>
					<option>80</option>
					<option>90</option>
					<option>100</option>
					</select>';
				} else if($row['type']=='asun') {
					echo '
					<input type="hidden" name="switch" value="'.$row['id_switch'].'"/>
					<input type="hidden" name="schakel" value="'.$row['id_switch'].'"/>
					<input type="submit" id="somfydownIcon" name="schakel" value="down" class="abuttonsomfy handje gradient"/>
					<input type="submit" id="somfyupIcon" name="schakel" value="up" class="abuttonsomfy handje gradient"/>
				';
				} else if($row['type']=='virtual') {
					echo '
					<form method="post" action="#"><input type="submit" name="schakel" value="on" class="abutton handje gradient"/><input type="submit" name="schakel" value="off" class="abutton handje gradient"/></form>';
				} else {
					echo '
					<section class="slider">	
					<input type="checkbox" value="switch'.$row['id_switch'].'" id="switch'.$row['id_switch'].'" name="switch'.$row['id_switch'].'" '; if($switchon=="off") {echo 'checked';} echo ' onChange="this.form.submit()"/>
					<label for="switch'.$row['id_switch'].'"></label>
					</section>';
				}
				echo '</td></form></tr>';
			}
			echo "</tbody></table>";
		}
		$result->free();
		echo '<br/><br/></div>';
		if($toon_schakelaars2=='yes') {
			$sql="select id_switch, name, type, favorite, volgorde from switches where type in ('switch', 'dimmer', 'virtual', 'asun') AND volgorde > 19999 AND user like '$gebruiker'";
			if (!isset($_POST['showallswitches2'])) $sql.=" AND favorite like 'yes'";
			$sql.=" order by volgorde asc, favorite desc, name asc";
			if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
			if($result->num_rows>0) {
				$group = 0;
				echo '<div class="item gradient"><p class="number">'.$positie_schakelaars.'</p>
					<form id="showallswitches2" action="#" method="post">
						<input type="hidden" name="showallswitches2" value="yes" />
						<a href="#" onclick="document.getElementById(\'showallswitches2\').submit();" style="text-decoration:none"><h2 >Schakelaars</h2></a>
					</form>
				<table align="center"><tbody>';
				while($row = $result->fetch_assoc()){
					$switchon = "";
					$tdstyle = '';
					if($group != $row['volgorde']) $tdstyle = 'style="'.$css_td_newgroup.'"';
					$group = $row['volgorde'];
					if($row['type']!='asun') {if(${'switchstatus'.$row['id_switch']}=="on") {$switchon = "off";} else {$switchon = "on";}}
					echo '<tr>
						<td><img id="'.$row['type'].'Icon" src="images/empty.gif" width="1px" height="1px" /></td>
						<td align="right" '.$tdstyle.'>
							<form action="switchhistory.php" method="post" id="'.$row['name'].'">
								<input type="hidden" name="filter" value="'.$row['name'].'">
								<a href="#" onclick="document.getElementById(\''.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
							</form>
						</td>
						<td width="115px" '.$tdstyle.' ><form method="post" action="#"><input type="hidden" name="switch" value="'.$row['id_switch'].'"/><input type="hidden" name="schakel" value="'.$switchon.'"/>';
					if($row['type']=='dimmer') {
						echo '<select name="dimlevel"  class="abutton handje gradient" onChange="this.form.submit()" style="margin-top:4px">
						<option '.${'switchstatus'.$row['id_switch']}.') selected>'.${'switchstatus'.$row['id_switch']}.'</option>
						<option>0</option>
						<option>10</option>
						<option>20</option>
						<option>30</option>
						<option>40</option>
						<option>50</option>
						<option>60</option>
						<option>70</option>
						<option>80</option>
						<option>90</option>
						<option>100</option>
						</select>';
					} else if($row['type']=='asun') {
						echo '
						<input type="hidden" name="switch" value="'.$row['id_switch'].'"/>
						<input type="hidden" name="schakel" value="'.$row['id_switch'].'"/>
						<input type="submit" id="somfydownIcon" name="schakel" value="down" class="abuttonsomfy handje gradient"/>
						<input type="submit" id="somfyupIcon" name="schakel" value="up" class="abuttonsomfy handje gradient"/>
					';
					} else if($row['type']=='virtual') {
						echo '
						<form method="post" action="#"><input type="submit" name="schakel" value="on" class="abutton handje gradient"/><input type="submit" name="schakel" value="off" class="abutton handje gradient"/></form>';
					} else {
						echo '
						<section class="slider">	
						<input type="checkbox" value="switch'.$row['id_switch'].'" id="switch'.$row['id_switch'].'" name="switch'.$row['id_switch'].'" '; if($switchon=="off") {echo 'checked';} echo ' onChange="this.form.submit()"/>
						<label for="switch'.$row['id_switch'].'"></label>
						</section>';
					}
					echo '</td></form></tr>';
				}
				echo "</tbody></table>";
				$result->free();
				echo '<br/><br/></div>';
			}
		}
		
	}
	
	/* SCENES */
	if($toon_scenes=='yes') {
		echo '<div class="item gradient"><p class="number">'.$positie_scenes.'</p>
				<form id="showallscenes" action="#" method="post">
					<input type="hidden" name="showallscenes" value="yes" />
					<a href="#" onclick="document.getElementById(\'showallscenes\').submit();" style="text-decoration:none"><h2>Scènes</h2></a>
				</form>';
		$sql="select id_switch, name, type, favorite, volgorde from switches where type in ('scene') AND user like '$gebruiker'";
		if (!isset($_POST['showallscenes'])) $sql.=" AND favorite like 'yes'";
		$sql.=" order by volgorde asc, favorite desc, name asc";
		if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
		if($result->num_rows>0) {
			$group = 0;
			while($row = $result->fetch_assoc()){
				echo '<table width="100%"><thead><tr><th colspan="2">';
				if($detailscenes=='optional') {echo '<a href="#" onclick="toggle_visibility(\'scene'.$row['id_switch'].'\');" style="text-decoration:none">'.$row['name'].'</a>';} else {echo $row['name'];}
				echo '</th>
				<th width="50px"><form method="post" action="#"><input type="hidden" name="scene" value="'.$row['id_switch'].'"/><input type="hidden" name="schakelscene" value="on"/><input type="submit" value="AAN" class="abutton gradient"/></form></th>
				<th width="50px"><form method="post" action="#"><input type="hidden" name="scene" value="'.$row['id_switch'].'"/><input type="hidden" name="schakelscene" value="off"/><input type="submit" value="UIT" class="abutton gradient"/></form></th>
				</tr></thead>';
				if(($detailscenes=='yes') || ($detailscenes=='optional')) {
					if($detailscenes=='optional') {
						echo '<tbody id="scene'.$row['id_switch'].'" style="display:none" class="handje">';
					} else {
						echo '<tbody>';
					}
					$datascene = null;
					$datascenes = null;
					$jsonscene = file_get_contents($jsonurl.'gp/get/'.$row['id_switch']);
					$datascenes = json_decode($jsonscene,true);
					if($debug=='yes') print_r($datascenes);
					if (!$datascenes) {
						echo "No information available...";
					} else {
						foreach($datascenes['response'] as $datascene) {
							echo '<tr><td align="right" width="60px">'.$datascene['type'].'&nbsp;&nbsp;</td><td align="left">&nbsp;'.$datascene['name'].'</td><td>'.$datascene['onstatus'].'</td><td>'.$datascene['offstatus'].'</td></tr>';
						}
					}
				}
			}
			echo '</tbody></table>';
		}
		$result->free();
		echo '</div>';
	}
	
	/* SOMFY */
	if($toon_somfy=='yes') {
		echo '<div class="item gradient"><p class="number">'.$positie_somfy.'</p>
				<form id="showallsomfy" action="#" method="post">
					<input type="hidden" name="showallsomfy" value="yes" />
					<a href="#" onclick="document.getElementById(\'showallsomfy\').submit();" style="text-decoration:none"><h2>Somfy</h2></a>
				</form>';
		$sql="select id_switch, name, volgorde from switches where type like 'somfy' AND user like '$gebruiker'";
		if (!isset($_POST['showallsomfy'])) $sql.=" AND favorite like 'yes'";
		$sql.=" order by volgorde asc, favorite desc, name asc";
		if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
		if($result->num_rows>0) {
			$group = 0;
			echo '<table align="center"><tbody>';
			while($row = $result->fetch_assoc()){
				$tdstyle = '';
				if($group != $row['volgorde']) $tdstyle = 'style="'.$css_td_newgroup.'"';
				$group = $row['volgorde'];
				echo '<tr>
				<td><img id="somfyIcon" src="images/empty.gif" width="1px" height="1px" /></td>
				<td align="right" '.$tdstyle.'>
					<form action="switchhistory.php" method="post" id="'.$row['name'].'">
						<input type="hidden" name="filter" value="'.$row['name'].'">
						<a href="#" onclick="document.getElementById(\''.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
					</form></td>
				<td width="185px" '.$tdstyle.'><form method="post" action="#">
				<input type="hidden" name="switch" value="'.$row['id_switch'].'"/>
				<input type="hidden" name="schakel" value="'.$row['id_switch'].'"/>
				<input type="submit" id="somfydownIcon" name="somfy" value="down" class="abuttonsomfy handje gradient"/>
				<input type="submit" id="somfystopIcon" name="somfy" value="stop" class="abuttonsomfy handje gradient"/>
				<input type="submit" id="somfyupIcon" name="somfy" value="up" class="abuttonsomfy handje gradient"/>
				</form></td></tr>';
			}
			echo "</tbody></table>";
		}
		$result->free();
		echo '</div>';
	}
	
	//---RADIATORS---
	if($toon_radiatoren=='yes') {
		echo '<div class="item gradient"><p class="number">'.$positie_radiatoren.'</p>
				<form id="showallradiators" action="#" method="post">
					<input type="hidden" name="showallradiators" value="yes"/>
					<a href="#" onclick="document.getElementById(\'showallradiators\').submit();" style="text-decoration:none"><h2>Radiatoren</h2></a>
				</form>';
		$sql="select r.id_switch, r.name as rname, r.temp, s.name as sname, r.volgorde from switches r left join sensors s ON r.temp = s.id_sensor AND s.type like 'temp' where r.type like 'radiator' AND r.user like '$gebruiker' AND s.user like '$gebruiker'";
		if (!isset($_POST['showallradiators'])) $sql.=" AND r.favorite like 'yes'";
		$sql.=" order by r.volgorde asc, r.favorite desc, r.name asc";
		if(!$result = $db->query($sql)){ echo ('There was an error running the query [' . $db->error . ']');}
		if($result->num_rows>0) {
			$group = 0;
			echo '<table align="center"><tbody>';
			while($row = $result->fetch_assoc()){
				$tdstyle = '';
				if($group != $row['volgorde']) $tdstyle = 'style="'.$css_td_newgroup.'"';
				$group = $row['volgorde'];
				echo '<tr>
				<td><img id="radiatorIcon" src="images/empty.gif" width="1px" height="1px" /></td>
				<td align="right" '.$tdstyle.'>
					<form action="switchhistory.php" method="post" id="'.$row['rname'].'">
						<input type="hidden" name="filter" value="'.$row['rname'].'">
						<a href="#" onclick="document.getElementById(\''.$row['rname'].'\').submit();" style="text-decoration:none">'.$row['rname'].'</a>
					</form></td>
				<td width="60px" '.$tdstyle.'>
					<form method="post" action="#">
						<input type="hidden" name="radiator" value="'.$row['id_switch'].'"/>
						<select name="set_temp"  class="abutton handje gradient" onChange="this.form.submit()" style="margin-top:4px">
							<option '.${'switchstatus'.$row['id_switch']}.') selected>'.${'switchstatus'.$row['id_switch']}.'</option>
							<option>8</option>
							<option>10</option>
							<option>12</option>
							<option>14</option>
							<option>16</option>
							<option>18</option>
							<option>19</option>
							<option>20</option>
							<option>20.5</option>
							<option>21</option>
							<option>21.5</option>
							<option>22</option>
							<option>22.5</option>
							<option>23</option>
							<option>23.5</option>
							<option>24</option>
						</select>
					</form>
				</td>
				<td width="75px" '.$tdstyle.' class="temp" align="left">';
				if(!empty($row['temp']) || $row['temp']==0) {
					echo '<form action="temp.php" method="post" id="temp'.$row['sname'].'">
						<input type="hidden" name="filter" value="'.$row['sname'].'">
						<a href="#" onclick="document.getElementById(\'temp'.$row['sname'].'\').submit();" ';
						if(${'thermometerte'.$row['temp']}>${'switchstatus'.$row['id_switch']}+1) echo 'style="color:#800">';
						else if(${'thermometerte'.$row['temp']}<${'switchstatus'.$row['id_switch']}-1) echo 'style="color:#008">';
						else echo 'style="color:#050">';
						echo number_format(${'thermometerte'.$row['temp']}, 1, ',', ' ').'°C';
						if(${'thermometerlastte'.$row['temp']}<${'thermometerte'.$row['temp']}-0.2) echo '&#x25B2;';
						else if(${'thermometerlastte'.$row['temp']}>${'thermometerte'.$row['temp']}+0.2) echo '&#x25BC;';
				echo '</a></font>
						</form>';
				}
				echo '</td>
				</tr>';
			}
			echo '</tbody></table>';
		}
		$result->free();
		echo '<br/><br/></div>';
	}
	
	//---SENSORS--
	if($toon_sensoren=='yes') {
		echo '<div class="item gradient"><p class="number">'.$positie_sensoren.'</p>
				<form id="showallsensors" action="#" method="post">
					<input type="hidden" name="showallsensors" value="yes"/>
					<a href="#" onclick="document.getElementById(\'showallsensors\').submit();" style="text-decoration:none"><h2>Sensoren</h2></a>
				</form>';
		$sql="select id_sensor, name, type, volgorde from sensors WHERE type in ('smoke','contact','doorbell','motion','light') AND user like '$gebruiker'";
		if (!isset($_POST['showallsensors'])) $sql.=" AND favorite like 'yes'";
		$sql.=" order by volgorde asc, favorite desc, name asc";
		if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
		if($result->num_rows>0) {
			$group = 0;
			echo '<div ><table align="center" width="100%">';
			while($row = $result->fetch_assoc()){
				echo '<tr>';
				$type = $row['type'];
				echo '<td style="color:#F00; font-weight:bold"><img id="'.$type.'Icon" src="images/empty.gif" width="1px" height="1px" /></td>';
				if($type=="contact") $type = "Magneet";
				if($type=="motion") $type = "Beweging";
				if($type=="doorbell") $type = "Deurbel";
				if($type=="smoke") $type = "Rook";
				if($type=="light") $type = "Licht";
				if(${'sensorstatus'.$row['id_sensor']} == "yes") {
					echo '<td style="color:#F00; font-weight:bold" class="temp">
							<form action="history.php" method="post" id="'.$row['name'].'">
							<input type="hidden" name="filter" value="'.$row['name'].'">
							<a href="#" onclick="document.getElementById(\''.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
							</form>
						</td>';
						} else {
							echo '<td><form action="history.php" method="post" id="'.$row['name'].'">
							<input type="hidden" name="filter" value="'.$row['name'].'">
							<a href="#" onclick="document.getElementById(\''.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
						</form></td>';
						}
				if(${'sensorstatus'.$row['id_sensor']} == "yes") {echo '<td style="color:#A00; font-weight:bold" class="temp">';} else {echo '<td>';}
				switch (${'sensorstatus'.$row['id_sensor']}){
					case "no": switch ($type){
						case "Magneet":echo 'Gesloten';Break;
						case "Licht":echo 'Licht';Break;
						default:Break;
					} Break;
					case "yes": switch ($type){
						case "Magneet":echo 'Open';Break;
						case "Beweging":echo 'Beweging';Break;
						case "Deurbel":echo 'Gebeld';Break;
						case "Rook":echo 'ROOK!!!';Break;
						case "Licht":echo 'Donker';Break;
						default:Break;
					} Break;
					Default: echo ${'sensorstatus'.$row['id_sensor']};Break;
				}
				echo '</td>';
				if(${'sensorstatus'.$row['id_sensor']} == "yes") {echo '<td style="color:#A00; font-weight:bold"><font size="+0.1">'.${'sensortimestamp'.$row['id_sensor']}.'</font></td>';} else {echo '<td><font size="+0.01">'.${'sensortimestamp'.$row['id_sensor']}.'</font></td>';}
				echo '</tr>';
			}
			echo "</table></div>";
		}
		$result->free();
		echo '</div>';
	}
}
//--THERMOMETERS--
if($toon_temperatuur=='yes') {
	 if($authenticated == true) {
		echo '<div class="item gradient"><p class="number">'.$positie_temperatuur.'</p>
				<form id="showalltemps" action="#" method="post">
					<input type="hidden" name="showalltemps" value="yes"/>
					<a href="#" onclick="document.getElementById(\'showalltemps\').submit();" style="text-decoration:none"><h2>Temperatuur</h2></a>
				</form>';
		$sql="select id_sensor, name, volgorde, tempk, tempw from sensors WHERE type in ('temp') AND user like '$gebruiker'";
		if (!isset($_POST['showalltemps'])) $sql.=" AND favorite like 'yes'";
		$sql.=" order by volgorde asc, favorite desc, name asc";
		if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
		if($result->num_rows>0) {	
			echo '<div><table width="100%"><tr><th></th><th colspan="2">temp</th><th>hum</th></tr>';
			while($row = $result->fetch_assoc()){
				echo '<tr>
				<td><form action="temp.php" method="post" id="temp'.$row['name'].'">
							<input type="hidden" name="filter" value="'.$row['name'].'">
							<a href="#" onclick="document.getElementById(\'temp'.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
						</form></td>';
				if(${'thermometerte'.$row['id_sensor']} < $row['tempk']) $tempclass = 'class="blue temp"';
				else if(${'thermometerte'.$row['id_sensor']} > $row['tempw']) $tempclass = 'class="red temp"';
				else $tempclass = 'class="temp"';
				echo '<td '.$tempclass.' align="right">';
				echo number_format(${'thermometerte'.$row['id_sensor']}, 1, ',', ' ').' °C</td><td '.$tempclass.' align="left">';
				if(${'thermometerlastte'.$row['id_sensor']}<${'thermometerte'.$row['id_sensor']}-2) echo '&#x25B2;&#x25B2;';
				else if(${'thermometerlastte'.$row['id_sensor']}<${'thermometerte'.$row['id_sensor']}-0.2) echo '&#x25B2;';
				else if(${'thermometerlastte'.$row['id_sensor']}>${'thermometerte'.$row['id_sensor']}+0.2) echo '&#x25BC;';
				else if(${'thermometerlastte'.$row['id_sensor']}>${'thermometerte'.$row['id_sensor']}+2) echo '&#x25BC;&#x25BC;';
				echo '</font></td>';
				echo '<td class="temp" align="left">'.${'thermometerhu'.$row['id_sensor']}.' %';
				if(${'thermometerlasthu'.$row['id_sensor']}<${'thermometerhu'.$row['id_sensor']}) echo '&#x25B2;';
				else if(${'thermometerlasthu'.$row['id_sensor']}>${'thermometerhu'.$row['id_sensor']}) echo '&#x25BC;';
				echo '</td></tr>';
			}
			echo "</table></div>";
		}
		$result->free();
		echo '</div>';
	 } else {
		 echo '<div class="item gradient"><p class="number">'.$positie_temperatuur.'</p><h2>Temperatuur</h2>';
		$sql="select id_sensor, name, volgorde, tempk, tempw from sensors WHERE type in ('temp') AND id_sensor = $defaultthermometer AND user like 'default' ";
		if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
		if($result->num_rows>0) {	
			echo '<div><table width="100%"><tr><th></th><th colspan="2">temp</th><th>hum</th></tr>';
			while($row = $result->fetch_assoc()){
				echo '<tr>';
				echo '<td><form action="temp.php" method="post" id="temp'.$row['name'].'">
							<input type="hidden" name="filter" value="'.$row['name'].'">
							<a href="#" onclick="document.getElementById(\'temp'.$row['name'].'\').submit();" style="text-decoration:none">'.$row['name'].'</a>
						</form></td>';
				if(${'thermometerte'.$row['id_sensor']} < $row['tempk']) $tempclass = 'class="blue temp"';
				else if(${'thermometerte'.$row['id_sensor']} > $row['tempw']) $tempclass = 'class="red temp"';
				else $tempclass = 'class="temp"';
				echo '<td '.$tempclass.' align="center">';
				echo number_format(${'thermometerte'.$row['id_sensor']}, 1, ',', ' ').' °C</td><td '.$tempclass.' align="left">';
				if(${'thermometerlastte'.$row['id_sensor']}<${'thermometerte'.$row['id_sensor']}-2) echo '&#x25B2;&#x25B2;';
				else if(${'thermometerlastte'.$row['id_sensor']}<${'thermometerte'.$row['id_sensor']}-0.2) echo '&#x25B2;';
				else if(${'thermometerlastte'.$row['id_sensor']}>${'thermometerte'.$row['id_sensor']}+0.2) echo '&#x25BC;';
				else if(${'thermometerlastte'.$row['id_sensor']}>${'thermometerte'.$row['id_sensor']}+2) echo '&#x25BC;&#x25BC;';
				echo '</font></td>';
				echo '<td class="temp" align="center">'.${'thermometerhu'.$row['id_sensor']}.' %';
				if(${'thermometerlasthu'.$row['id_sensor']}<${'thermometerhu'.$row['id_sensor']}) echo '&#x25B2;';
				else if(${'thermometerlasthu'.$row['id_sensor']}>${'thermometerhu'.$row['id_sensor']}) echo '&#x25BC;';
				echo '</td></tr>';
			}
			echo "</table></div>";
		}
		$result->free();
		echo '</div>';
	 }
}

//--RAINMETERS--
if($toon_regen=='yes') {
	if(!empty($rainmeters)) {
		echo '<div class="item handje gradient" onclick="window.location=\'rain.php\';"><p class="number">'.$positie_regen.'</p><h2>Regen</h2><table width="100%"><tr><th></th><th>Vandaag</th><th>Laatste 3u</th></tr>';
		foreach($rainmeters as $rainmeter){
			if($authenticated == true && $debug=='yes') print_r($rainmeter);
			echo '<tr>';
			if(count($rainmeters)>1) {echo '<td>'.$rainmeter['name'].'</td>';} else { echo '<td></td>';}
			echo '<td class="temp">'.$rainmeter['mm'].' mm</td><td class="temp">'.$rainmeter['3h'].' mm</td></tr>';
		}
		echo "</table></div>";
	}
}

//--WINDMETERS--
if($toon_wind=='yes') {
	if(!empty($windmeters)) {
		echo '<div class="item handje gradient" onclick="window.location=\'wind.php\';"><p class="number">'.$positie_wind.'</p><h2>Wind</h2><table width="100%"><tr><th></th><th>ws</th><th>gu</th><th>dir</th></tr>';
		foreach($windmeters as $windmeter){
			if($authenticated == true && $debug=='yes') print_r($windmeter);
			if(isset($windmeter['ws'])) {
				echo '<tr>';
				if(count($windmeters)>1) {echo '<td>'.$windmeter['name'].'</td>';} else { echo '<td></td>';}
				echo '<td class="temp">'.$windmeter['ws'].' km/u</td><td class="temp">'.$windmeter['gu'].' km/u</td><td class="temp">'.$windmeter['dir'].' °</td></tr>';
			}
		}
		echo "</table></div>";
	}
}

//--ENERGYLINKS--
if($toon_energylink=='yes' && $authenticated == true) {
	if(!empty($energylinks)) {
		echo '<div class="item handje gradient"><p class="number">'.$positie_energylink.'</p><h2>Energylink</h2><table width="100%">';
		foreach($energylinks as $energylink){
			if($authenticated == true && $debug=='yes') print_r($energylink);
				echo '<tr><td>S1 PO</td><td>'.$energylink['s1']['po'].'</td></tr>';
				echo '<tr><td>S1 dagtotaal</td><td>'.$energylink['s1']['dayTotal'].'</td></tr>';
				echo '<tr><td>S1 PO+</td><td>'.$energylink['s1']['po+'].'</td></tr>';
				echo '<tr><td>S1 PO+t</td><td>'.$energylink['s1']['po+t'].'</td></tr>';
				echo '<tr><td>S2 PO</td><td>'.$energylink['s2']['po'].'</td></tr>';
				echo '<tr><td>S2 dagtotaal</td><td>'.$energylink['s2']['dayTotal'].'</td></tr>';
				echo '<tr><td>S2 PO+</td><td>'.$energylink['s2']['po+'].'</td></tr>';
				echo '<tr><td>S2 PO+t</td><td>'.$energylink['s2']['po+t'].'</td></tr>';
				echo '<tr><td>aggregate PO</td><td>'.$energylink['aggregate']['po'].'</td></tr>';
				echo '<tr><td>aggregate dagtotaal</td><td>'.$energylink['aggregate']['dayTotal'].'</td></tr>';
				echo '<tr><td>aggregate PO+</td><td>'.$energylink['aggregate']['po+'].'</td></tr>';
				echo '<tr><td>aggregate PO+t</td><td>'.$energylink['aggregate']['po+t'].'</td></tr>';
				echo '<tr><td>used PO</td><td>'.$energylink['used']['po'].'</td></tr>';
				echo '<tr><td>used dagtotaal</td><td>'.$energylink['used']['dayTotal'].'</td></tr>';
				echo '<tr><td>used PO+</td><td>'.$energylink['used']['po+'].'</td></tr>';
				echo '<tr><td>used PO+t</td><td>'.$energylink['used']['po+t'].'</td></tr>';
				echo '<tr><td>gas uur</td><td>'.$energylink['gas']['lastHour'].'</td></tr>';
				echo '<tr><td>gas dag</td><td>'.$energylink['gas']['dayTotal'].'</td></tr>';
		}
		echo "</table></div>";
	}
}

//---ACTIES---
if($toon_acties=='yes' && $authenticated == true) {
	echo '<div class="item gradient"><p class="number">'.$positie_acties.'</p>
			<form id="showallacties" action="#" method="post">
				<input type="hidden" name="showallacties" value="yes" />
				<a href="#" onclick="document.getElementById(\'showallacties\').submit();" style="text-decoration:none"><h2 >Acties</h2></a>
			</form>';
	$sql="select variable, value from settings where variable like 'actie_%' AND user like '$gebruiker'";
	if (!isset($_POST['showallacties'])) $sql.=" AND favorite like 'yes'";
	$sql.=" order by variable";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	if($result->num_rows>0) {
		$group = 0;
 		echo '
		<table align="center"><tbody>';
		while($row = $result->fetch_assoc()){
			$switchon = "";
			$tdstyle = '';
			//if($group != $row['volgorde']) $tdstyle = 'style="'.$css_td_newgroup.'"';
			//$group = $row['volgorde'];
			if($row['value']=="yes") {$switchon = "off";} else {$switchon = "on";}
			echo '<tr>
				<td align="right" '.$tdstyle.'>'.ucwords(str_replace('_', ' ', ltrim($row['variable'],'actie'))).'</td>
				<td width="115px" '.$tdstyle.' ><form method="post" action="#"><input type="hidden" name="updactie" value="'.$switchon.'"/><input type="hidden" name="variable" value="'.$row['variable'].'"/>
				<section class="slider">	
				<input type="checkbox" value="switch'.$row['variable'].'" id="switch'.$row['variable'].'" name="switch'.$row['variable'].'" '; if($switchon=="off") {echo 'checked';} echo ' onChange="this.form.submit()"/>
				<label for="switch'.$row['variable'].'"></label>
				</section>
				</td></form></tr>';
		}
		echo "</tbody></table>";
	}
	$result->free();
	echo '<br/><br/></div>';
}
?>
<script type="text/javascript">
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'inherit')
          e.style.display = 'none';
       else
          e.style.display = 'inherit';
    }
//-->
</script>
<?PHP include "footer.php";?>