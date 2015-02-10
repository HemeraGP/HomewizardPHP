<?php
// Copy alles hieronder over naar actionscron.php na testen. 

include "data.php";
include "functions.php";
echo '<div class="item wide gradient"><p class="number">2</p><br/>';
		$json = file_get_contents($jsonurl.'nf/edit/2/1/null/0,1,2/yes');
		$data = null;
		$data = json_decode($json,true);
		if($data['status']=='ok') {
			setparameter('actie_notify_poort', 'no');
		}
		print_r($data);

echo '</div>';

if(!isset($_POST['actionscron']) && !isset($_POST['showtest'])) {ob_clean(); $db->close();}
?>
