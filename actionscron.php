<?php
if(!isset($_POST['actionscron'])) {
include "parameters.php";
	$sql="select variable, value from settings order by variable asc";
	if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
	$acceptedips = array();
	while($row = $result->fetch_assoc()){
		if (strpos($row['variable'], 'acceptedip') === 0) { 
			array_push($acceptedips, $row['value']);
		} else {
			$$row['variable'] = $row['value'];
		}
	}
	$result->free();
}
$authenticated=true;
include "data.php";
include "functions.php";

if($actie_brander='yes') {
	if($switchstatus12=='off' && ($switchstatus6>$thermometerte4 || $switchstatus6>$thermometerte4)) {schakel(12, 'on', 'c');sleep(2);}
	if($switchstatus12=='on' && $switchstatus6<$thermometerte4 && $switchstatus6<$thermometerte4) {schakel(12, 'off', 'c');sleep(2);}
}
//Timer radiatoren living
$tempw = 20;
$tempk = 17;
if($actie_timer_living='yes'){
	if(time()>(strtotime('18:00')-(($tempw-$thermometerte1)*(($tempw-$thermometerte1)*100))) && (time()<(strtotime('22:00'))) && ($switchstatus14<$tempw || $switchstatus15<$tempw) && in_array(date('N', time()), array(1,2,3,4))) {
		radiator(14, $tempw, 'c');sleep(2);
		radiator(15, $tempw, 'c');sleep(2);
	} else if(time()>(strtotime('19:00')-(($tempw-$thermometerte1)*(($tempw-$thermometerte1)*100))) && (time()<(strtotime('23:00'))) && ($switchstatus14<$tempw || $switchstatus15<$tempw) && in_array(date('N', time()), array(5,6,7))) {
		radiator(14, $tempw, 'c');sleep(2);
		radiator(15, $tempw, 'c');sleep(2);
	} 
}
else {
	if($switchstatus14>$tempk || $switchstatus15>$tempk) {
		$laatsteschakel = laatsteschakeltijd(14,null, 'm');
		$laatsteschakel2 = laatsteschakeltijd(15,null, 'm');
		if($laatsteschakel2>$laatsteschakel) $laatsteschakel = $laatsteschakel2;
		if(($laatsteschakel['timestamp']<(time()-7200) || $laatsteschakel['type']=='off'))  {radiator(14, $tempk, 'c');sleep(2);radiator(15, $tempk, 'c');sleep(2);}
	}
}
//Timer radiator badkamer
$tempw = 22;
$tempk = 17;
if($actie_timer_badkamer='yes'){
	if(time()>(strtotime('6:00')-(($tempw-$thermometerte4)*(($tempw-$thermometerte1)*100))) && (time()<(strtotime('8:00'))) && ($switchstatus6<$tempw) && in_array(date('N', time()), array(1,2,3,4,5))) {
		radiator(6, $tempw, 'c');sleep(2);
	} 	
} else {
	if($switchstatus6>$tempk) {
		$laatsteschakel = laatsteschakeltijd(6,null, 'm');
		if(($laatsteschakel['timestamp']<(time()-7200) || $laatsteschakel['type']=='off')) {radiator(6, $tempk, 'c');sleep(2);}
	}
}
//Timer radiator slaapkamer
if($actie_timer_slaapkamer='yes'){
	$tempw = 18;
	$tempk = 8;
	if(time()>(strtotime('22:50')-(($tempw-$thermometerte4)*(($tempw-$thermometerte1)*100))) && (time()<(strtotime('23:00'))) && ($switchstatus7<$tempw)) {radiator(7, $tempw, 'c');sleep(2);
	} 
} else {
	$laatsteschakel = laatsteschakeltijd(7,null, 'm');
	if(($laatsteschakel['timestamp']<(time()-7200) || $laatsteschakel['type']=='off') && $switchstatus7>$tempk) {radiator(7, $tempk, 'c');sleep(2);}
}
//Timer radiator slaapkamer Tobi
$tempw = 18;
$tempk = 8;
if($actie_timer_slaapkamertobi='yes'){
	if(time()>(strtotime('21:20')-(($tempw-$thermometerte4)*(($tempw-$thermometerte1)*100))) && (time()<(strtotime('21:30'))) && ($switchstatus7<$tempw) && ((in_array(date('N', time()), array(5,6)) && date('W', time()) %2 == 0) || (in_array(date('N', time()), array(3,4))))) {
		radiator(8, $tempw, 'c');sleep(2);
	} 
} else {
	if($switchstatus8>$tempk) {
		$laatsteschakel = laatsteschakeltijd(8,null, 'm');
		if(($laatsteschakel['timestamp']<(time()-7200)|| $laatsteschakel['type']=='off'))  {radiator(8, $tempk, 'c');sleep(2);}
	}
}
if($actie_lichtgarage='yes') {
	if($switchstatus1=='on') {
		if(strtotime($sensortimestamp1)>time()) {$sensor1tijd = laatstesensortijd($sensorid1,null);$sensortimestamp1 = strtotime($sensor1tijd['time']);} else {$sensortimestamp1 = strtotime($sensortimestamp1);}
		if(strtotime($sensortimestamp2)>time()) {$sensor2tijd = laatstesensortijd($sensorid2,null);$sensortimestamp2 = strtotime($sensor2tijd['time']);} else {$sensortimestamp2 = strtotime($sensortimestamp2);}
		if($sensortimestamp1<(time()-200) && $sensortimestamp2<(time()-200)) {
			$laatsteschakel = laatsteschakeltijd(1,null, 'm');
			if($laatsteschakel['timestamp']<(time()-7200) || $laatsteschakel['type']=='off') {schakel(1, 'off', 'c');sleep(2);}
		} 
	}
}

if(!isset($_POST['actionscron'])) $db->close();
?>
