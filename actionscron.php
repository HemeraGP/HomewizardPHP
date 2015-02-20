<?php
if(!isset($_POST['actionscron'])) {
include "parameters.php";
	$sql="select variable, value from settings where user like 'default' order by variable asc";
	if(!$result = $db->query($sql)){ echo('There was an error running the query [' . $db->error . ']');}
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

if($actie_brander=='yes') {
	if($switchstatus6>$thermometerte4 || $switchstatus7>$thermometerte6 || $switchstatus8>$thermometerte7 || $switchstatus14>$thermometerte5 || $switchstatus15>$thermometerte5) {
		if($switchstatus12=='off') {schakel(12, 'on', 'Auto', $email_notificatie, 'yes');sleep(2);} 
	} else {
		if($switchstatus12=='on') {schakel(12, 'off', 'Auto', $email_notificatie, 'yes');sleep(2);}
	}
}

if($actie_timer_living=='yes'){
	$tempw = 20;
	$tempk = 14;
	$warm=false;
	if($thermometerte5<$tempw) $voorwarmen = ceil(($tempw-$thermometerte5)*($tempw-$thermometerte1)*30); else $voorwarmen = 0;
	if(in_array(date('N', time()), array(1,2,3,4)) && time()>=(strtotime('18:00')-$voorwarmen) && time()<=strtotime('22:00')) $warm=true;
	else if (in_array(date('N', time()), array(5,6,7)) && time()>=(strtotime('7:00')-$voorwarmen) && time()<=strtotime('23:00')) $warm=true;
	else if(time()>=strtotime('8:00') && time()<=strtotime('22:00') && ($switchstatus14>$tempk || $switchstatus15>$tempk)) $warm=true;

	if($warm==true && $thermometerte1<=20) {
		if($switchstatus14<$tempw) {radiator(14, $tempw, 'Auto', $email_notificatie, 'yes');sleep(2);}
		if($switchstatus14<$tempw) {radiator(15, $tempw, 'Auto', $email_notificatie, 'yes');sleep(2);}
	} else {
		if($switchstatus14>$tempk) {
			$laatsteschakel = laatsteschakeltijd(14,null, null);
			if($laatsteschakel['timestamp']<(time()-7200)) {radiator(14, $tempk, 'Auto', $email_notificatie, 'yes');sleep(2);
			}
		}
		if($switchstatus15>$tempk) {
			$laatsteschakel = laatsteschakeltijd(15,null, null);
			if($laatsteschakel['timestamp']<(time()-7200)) {radiator(15, $tempk, 'Auto', $email_notificatie, 'yes');sleep(2);
			}
		}
	}
}

if($actie_timer_badkamer=='yes'){
	$tempw = 21;
	$tempn = 17;
	$tempk = 10;
	$warm=false;
	$lauw=false;
	if($thermometerte4<$tempw) $voorwarmen = ceil(($tempw-$thermometerte4)*($tempw-$thermometerte1)*30); else $voorwarmen = 0;
	if(in_array(date('N', time()), array(1,2,3,4,5)) && time()>=(strtotime('6:00')-$voorwarmen) && time()<=(strtotime('7:30'))) $warm=true;
	else if(in_array(date('N', time()), array(6,7)) && time()>=(strtotime('7:30')-$voorwarmen) && time()<=(strtotime('9:30'))) $warm=true;
	else if(time()>strtotime('18:00') && time()<strtotime('23:00')) $lauw=true;
	
	if($warm==true && $thermometerte1<=21) {
		if($switchstatus6<$tempw) {radiator(6, $tempw, 'Auto', $email_notificatie, 'yes');sleep(2);}
	} else if($lauw==true && $thermometerte1<18) {
		if($switchstatus6<$tempn) {radiator(6, $tempn, 'Auto', $email_notificatie, 'yes');sleep(2);}
	} else if($warm==false && $lauw==false) {
		if($switchstatus6>$tempk) {
			$laatsteschakel = laatsteschakeltijd(6,null, null);
			if($laatsteschakel['timestamp']<(time()-7200)) {radiator(6, $tempk, 'Auto', $email_notificatie, 'yes');sleep(2);
			}
		}
	}
}

if($actie_timer_slaapkamer=='yes'){
	$tempw = 15;
	$tempk = 5;
	$warm=false;
	if($thermometerte6<$tempw) $voorwarmen = ceil(($tempw-$thermometerte6)*($tempw-$thermometerte1)*30); else $voorwarmen = 0;
	if(in_array(date('N', time()), array(1,2,3,4))) {
		if((time()>=(strtotime('21:00')-$voorwarmen)) && time()<=(strtotime('22:30'))) $warm=true;
	} else if(in_array(date('N', time()), array(5,6,7))) {
		if((time()>=(strtotime('22:00')-$voorwarmen)) && time()<=(strtotime('23:30'))) $warm=true;
	}
	if($warm==true && $thermometerte1<=13) {
		if($switchstatus7<$tempw) {radiator(7, $tempw, 'Auto', $email_notificatie, 'yes');sleep(2);}
	} else {
		if($switchstatus7>$tempk) {
			$laatsteschakel = laatsteschakeltijd(7,null, null);
			if($laatsteschakel['timestamp']<(time()-7200)) {radiator(7, $tempk, 'Auto', $email_notificatie, 'yes');sleep(2);
			}
		}
	}
}

if($actie_timer_slaapkamer_tobi=='yes'){
	$tempw = 15;
	$tempk = 5;
	$warm=false;
	if($thermometerte7<$tempw) $voorwarmen = ceil(($tempw-$thermometerte7)*($tempw-$thermometerte1)*30); else $voorwarmen = 0;
	if(date('W', time()) %2 == 0) {
		if(in_array(date('N', time()), array(3,4,5,6))) {
			if((time()>=(strtotime('20:30')-$voorwarmen)) && time()<=(strtotime('21:30'))) $warm=true;
		}
	} else {
		if(in_array(date('N', time()), array(3,4))) {
			if((time()>=(strtotime('20:30')-$voorwarmen)) && time()<=(strtotime('21:30'))) $warm=true;
		}
	}
	if($warm==true && $thermometerte1<=13) {
		if($switchstatus8<$tempw) {radiator(8, $tempw, 'Auto', $email_notificatie, 'yes');sleep(2);}
	} else {
		if($switchstatus8>$tempk) {
			$laatsteschakel = laatsteschakeltijd(8,null, null);
			if($laatsteschakel['timestamp']<(time()-7200)) {radiator(8, $tempk, 'Auto', $email_notificatie, 'yes');sleep(2);
			}
		}
	}
}

if($actie_lichtgarage=='yes') {
	if($switchstatus1=='on') {
		if(strtotime($sensortimestamp1)>time()) {$sensor1tijd = laatstesensortijd($sensorid1,null);$sensortimestamp1 = strtotime($sensor1tijd['time']);} else {$sensortimestamp1 = strtotime($sensortimestamp1);}
		if(strtotime($sensortimestamp2)>time()) {$sensor2tijd = laatstesensortijd($sensorid2,null);$sensortimestamp2 = strtotime($sensor2tijd['time']);} else {$sensortimestamp2 = strtotime($sensortimestamp2);}
		if($sensortimestamp1<(time()-200) && $sensortimestamp2<(time()-200)) {
			$laatsteschakel = laatsteschakeltijd(1,null, null);
			if($laatsteschakel['timestamp']<(time()-7200) || $laatsteschakel['type']=='off') {
				schakel(1, 'off', 'Auto', $email_notificatie, 'yes');sleep(2);
			}
		} 
	}
}

if($actie_timer_pluto=='yes'){
	$pluto=false;
	if(time()>=(strtotime('11:00')) && time()<=(strtotime('23:00'))) $pluto=true;
	if($pluto==true) {
		if($switchstatus0=='off') {schakel(0, 'on', 'c', $email_notificatie, 'yes');sleep(2);}
	} else {
		if($switchstatus0=='on') {
			$laatsteschakel = laatsteschakeltijd(0,null, null);
			if($laatsteschakel['timestamp']<(time()-7200))  {
				schakel(0, 'off', 'Auto', $email_notificatie, 'yes');sleep(2);
			}
		}
	}
}

if($actie_thuis=='yes'){
	if($actie_notify_poort=='yes') {
		$json = file_get_contents($jsonurl.'nf/edit/1/4/null/0/yes');
		$data = null;
		$data = json_decode($json,true);
		if($data['status']=='ok') setparameter('actie_notify_poort', 'no');
		sleep(2);
	}
	if($actie_notify_garage=='yes') {
		$json = file_get_contents($jsonurl.'nf/edit/2/1/null//yes');
		$data = null;
		$data = json_decode($json,true);
		if($data['status']=='ok') setparameter('actie_notify_garage', 'no');
		sleep(2);
	}
} else {
	if($sensorstatus0=='yes') notificatie($email_notificatie ,'ROOK gedetecteerd op zolder' ,'ROOK gedetecteerd op zolder' );
	if($sensorstatus1=='yes') {
		if($laatste_beweging_garage_mail!=$sensortimestamp2) {
			setparameter('laatste_poort_open_mail', time());
			if($laatste_poort_open_mail<time()-300) notificatie($email_notificatie ,'Poort is geopend' ,'Poort is geopend' );
		}
	}
	if($sensorstatus2=='yes') {
		if($laatste_beweging_garage_mail!=$sensortimestamp2) {
			setparameter('laatste_beweging_garage_mail', time());
			if($laatste_beweging_garage_mail<time()-300) notificatie($email_notificatie ,'Beweging gedetecteerd in garage' ,'Beweging gedetecteerd in garage' );
		}
	}
	if($sensorstatus3=='yes') notificatie($email_notificatie ,'ROOK gedetecteerd in de hall' ,'ROOK gedetecteerd in de hall' );
	if($sensorstatus4=='yes') notificatie($email_notificatie ,'Bel voordeur ingedrukt' ,'Bel voordeur ingedrukt' );
	if($actie_notify_poort=='no') {
		$json = file_get_contents($jsonurl.'nf/edit/1/4/null/0,2/yes');
		$data = null;
		$data = json_decode($json,true);
		if($data['status']=='ok') setparameter('actie_notify_poort', 'yes');
		sleep(2);
	}
	if($actie_notify_garage=='no') {
		$json = file_get_contents($jsonurl.'nf/edit/2/1/null/0,2/yes');
		$data = null;
		$data = json_decode($json,true);
		if($data['status']=='ok') setparameter('actie_notify_garage', 'yes');
		sleep(2);
	}
}
echo '</div>';

if($actie_zonneluifel=='yes'){
	if($rainmeter3h3 > 0) {
		if($Zonneluifellaatstomhoogslechtweer<time()-3600) {
			somfy(13, 'up', 'Regen!', $email_notificatie, 'no');
			setparameter('Zonneluifellaatstomhoogslechtweer', time());
		}
	}
	if($windmeterws2 > 10 ||$windmetergu2 > 10) {
		if($Zonneluifellaatstomhoogslechtweer<time()-3600) {
			somfy(13, 'up', 'Wind!', $email_notificatie, 'no');
			setparameter('Zonneluifellaatstomhoogslechtweer', time());
		}
	}
	if($thermometerte1 > 18) somfy(13, 'down', 'Te Warm', $email_notificatie, 'no');
} 

if($actie_batterij=='yes'){
	if(date('H:i',time())=="16:43" && date('z', time()) %3 == 0) {
		$json = file_get_contents($jsonurl.'get-sensors');
		$data = null;
		$data = json_decode($json,true);
		$thermometers =  $data['response']['thermometers'];
			foreach ($thermometers as $thermometer) {
				if($thermometer['lowBattery']=='yes') notificatie($email_notificatie, "Batterijleeg van ".$thermometer['name']."", "Batterijleeg van thermometer".$thermometer['name']."");
			}
		$windmeters =  $data['response']['windmeters'];	
			foreach ($windmeters as $windmeter) {
				if($windmeter['lowBattery']=='yes') notificatie($email_notificatie, "Batterijleeg van ".$windmeter['name']."", "Batterijleeg van ".$windmeter['name']."");
			}
		$rainmeters =  $data['response']['rainmeters'];
			foreach ($rainmeters as $rainmeter) {
				if($rainmeter['lowBattery']=='yes') notificatie($email_notificatie, "Batterijleeg van ".$rainmeter['name']."", "Batterijleeg van ".$rainmeter['name']."");
			}
	}
} 
if(!isset($_POST['actionscron']) && !isset($_POST['showtest'])) {ob_clean(); $db->close();}
?>