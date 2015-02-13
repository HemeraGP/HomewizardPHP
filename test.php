<?php
// Copy alles hieronder over naar actionscron.php na testen. 

include "data.php";
include "functions.php";
echo '<div class="item wide gradient"><p class="number">2</p><br/>';
		$laatsteschakel = laatsteschakeltijd(14,null, 'm');
			$laatsteschakel2 = laatsteschakeltijd(15,null, 'm');
			if($laatsteschakel2['timestamp']>$laatsteschakel['timestamp']) $laatsteschakel['timestamp'] = $laatsteschakel2['timestamp'];
			if($laatsteschakel['timestamp']>(time()-7200)) $warm=true;

echo '</div>';

if(!isset($_POST['actionscron']) && !isset($_POST['showtest'])) {ob_clean(); $db->close();}
?>
