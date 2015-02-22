<?php
include_once "parameters.php";
echo '<div class="item wide gradient"><p class="number">9</p>';
$json = file_get_contents($jsonurl.'get-status');
$data = null;
$data = json_decode($json,true);
$thermometers =  $data['response']['thermometers'];
	foreach($thermometers as $thermometer) {
		${'thermometerid'.$thermometer['id']} = $thermometer['id'];
		${'thermometerte'.$thermometer['id']} = $thermometer['te'];
		${'thermometerhu'.$thermometer['id']} = $thermometer['hu'];
		echo $thermometer['id'].' = '.${'thermometerte'.$thermometer['id']}.'<br/>';
	}
	

echo '</div>';
?>