<?php
require_once("chart/conf.php");
$pc = new C_PhpChartX(array(array(11, 9, 5, 12, 14)),'basic_chart');
$pc->set_animate(true);
$pc->set_title(array('text'=>'Basic Chart with Line Renderer'));
//$pc->set_series_default(array('renderer'=>'plugin::LineRenderer'));
$pc->draw();
?>
