</div>
<div class="footer">
<br/>

<!-- Please do not remove these lines -->
Get the code at <a href="http://homewizard.org/index.php" title="HomeWizard.org" style="color:#CCC" target="_blank">HomeWizard.org</a> <br/><br/>
Created by <a href="http://egregius.be" title="egregius.be" style="color:#CCC" target="_blank">egregius.be</a><br/><br/><br/>
<script type="text/javascript" language="javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" language="javascript" src="js/isotope.pkgd.min.js"></script>
<script language="javascript">
$( function() {
  var $container = $('.isotope'),
      $items = $('.item');
	$('.isotope').isotope({
    itemSelector: '.item',
	layoutMode: 'masonry',
    sortBy : 'number',
	getSortData: {
    number: '.number parseInt',
    }
  });
  $items.click(function(){
    var $this = $(this);
    $container
      .isotope('updateSortData', $this )
      .isotope();
  });
});
</script>
<script language="javascript"> 
function toggle(showHideDiv, switchTextDiv) {
	var ele = document.getElementById(showHideDiv);
	var text = document.getElementById(switchTextDiv);
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		text.innerHTML = "show";
  	}
	else {
		ele.style.display = "block";
		text.innerHTML = "hide";
	}
} 
</script>
<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 3);
$sql="select versie from versie order by id desc limit 0,1";
if(!$resultv = $db->query($sql)){ echo('There was an error running the query ['.$sql.'][' . $db->error . ']');}
while($row = $resultv->fetch_assoc()){$versie = $row['versie'];}
$resultv->free();
$db->close();
echo '<div class="footer handje" onclick="window.location=\'settings.php?t='.time().'\';"><small>Versie '.$versie.'. Opgemaakt in '.$total_time.' seconden op '; echo date("j M Y H:i:s"); echo '</small><br/><br/>'; 
if(isset($_COOKIE["HomewizardPHP"])) echo '<form method="post" action="logout.php"><input type="submit" name="logout" value="Uitloggen" class="abutton settings gradient"/></form>';
?>
</div>
</body>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="cache-control" content="no-store" />
<meta http-equiv="expires" content="-1" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
</head>
</html>