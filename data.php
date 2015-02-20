<?php
if ($developermode == 'yes') {
	print '<div class="error gradient">Developer mode</div>';
	$json = $developerjson;
} else {
	$json = file_get_contents($jsonurl.'get-status');
}
$data = null;
$data = json_decode($json,true);
if($authenticated == true && $debug=='yes') {
	echo '<div class="error gradient">'.$json.'<hr>POST = ';
	print_r($_POST);
	echo '</div>';}
if (!$data) {
	echo '<div class="error gradient">ERROR OPENING HomeWizard. Check jsonurl and connection!</div>';
} else {
	$switches =  $data['response']['switches'];
	foreach($switches as $switch) {
		${'switchid'.$switch['id']} = $switch['id'];
		${'switchtype'.$switch['id']} = $switch['type'];
		if($switch['type']=='radiator') { 
			${'switchstatus'.$switch['id']} = $switch['tte']; 
		} else if($switch['type']=='dimmer') {
			${'switchstatus'.$switch['id']} = $switch['dimlevel'];
		} else if($switch['type']=='asun') {
			${'switchstatus'.$switch['id']} = $switch['mode'];
		} else if($switch['type']=='somfy') {
		} else if($switch['type']=='virtual') {
			if(isset($switch['status'])) {${'switchstatus'.$switch['id']} = $switch['status'];} else {${'switchstatus'.$switch['id']} = 'off';};
		} else {
			${'switchstatus'.$switch['id']} = $switch['status'];
		}
	}
	$sensors =  $data['response']['kakusensors'];
	foreach($sensors as $sensor) {
		${'sensorid'.$sensor['id']} = $sensor['id'];
		${'sensorstatus'.$sensor['id']} = $sensor['status'];
		${'sensortimestamp'.$sensor['id']} = $sensor['timestamp'];
	}
	$thermometers =  $data['response']['thermometers'];
	foreach($thermometers as $thermometer) {
		${'thermometerid'.$thermometer['id']} = $thermometer['id'];
		${'thermometerte'.$thermometer['id']} = $thermometer['te'];
		${'thermometerhu'.$thermometer['id']} = $thermometer['hu'];
	}
	$sql = "select id_sensor, correctie from sensors where type like 'temp'";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
	while($row = $result->fetch_assoc()){
		${'thermometerte'.$row['id_sensor']} = ${'thermometerte'.$row['id_sensor']} + $row['correctie'];
	}
	$result->free();
	
	$rainmeters =  $data['response']['rainmeters'];
	foreach($rainmeters as $rainmeter) {
		${'rainmeter'.$rainmeter['id']} = $rainmeter['id'];
		${'rainmetermm'.$rainmeter['mm']} = $rainmeter['mm'];
		${'rainmeter3h'.$rainmeter['id']} = $rainmeter['3h'];
	}
	$windmeters =  $data['response']['windmeters'];	
	foreach($windmeters as $windmeter) {
		${'windmeterid'.$windmeter['id']} = $windmeter['id'];
		if(!empty($windmeter['ws'])) ${'windmeterws'.$windmeter['id']} = $windmeter['ws'];
		if(!empty($windmeter['gu'])) ${'windmetergu'.$windmeter['id']} = $windmeter['gu'];
	}
	$energylinks = $data['response']['energylinks'];
}
?>