<?php 
if(isset($_POST['importall'])) include('history_to_sql.php');
include "header.php"; 
require_once('calendar/classes/tc_calendar.php');
echo '<script language="javascript" src="calendar/calendar.js"></script>';
if(isset($_POST['datefilter_day'])) $day = $_POST['datefilter_day']; else $day = date('d', time());
if(isset($_POST['datefilter_month'])) $month = $_POST['datefilter_month']; else $month = date('m', time());
if(isset($_POST['datefilter_year'])) $year = $_POST['datefilter_year']; else $year = date('Y', time());
$timefilter = $year;
if($month>0) $timefilter .= '-'.$month;
if($month>0 && $day>0) $timefilter .= '-'.$day;
echo '<div class="twocolumn"><div class="item wide gradient"><br/><br/>
<form method="post" name="filter" id="filter">';
$myCalendar = new tc_calendar("datefilter", true, true);
$myCalendar->setIcon("calendar/images/iconCalendar.gif");
$myCalendar->setPath("calendar/");
$myCalendar->startDate(1);
$myCalendar->setDate($day, $month, $year);
$myCalendar->setYearInterval(2014, date('Y', time()));
$myCalendar->dateAllow('2014-01-01', '2099-12-31');
$myCalendar->setDateFormat('j F Y');
$myCalendar->writeScript();
echo '<select name="filter" class="abutton abuttonhistory gradient" ><option ';if(isset($_POST['filter'])) { if($_POST['filter']=='all') echo 'selected';} echo '>All</option>';
$sql = "SELECT name FROM sensors WHERE type not like 'temp' AND user like '$gebruiker' ORDER BY name ASC";
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
while($row = $result->fetch_assoc()){
	echo '<option ';if(isset($_POST['filter'])) { if($_POST['filter']==$row['name']) echo 'selected';} echo '>'.$row['name'].'</option>';
}
$result->free();
echo '</select>
<input type="submit" name="Submit" value="Submit" class="abutton gradient" /></form>';

if(isset($_POST['update'])) include_once('history_to_sql.php');
$sql = "SELECT h.id_sensor, h.time, s.name, t.omschrijving FROM history h LEFT JOIN statusses t ON h.status=t.status LEFT JOIN sensors s ON h.id_sensor=s.id_sensor WHERE s.type not like 'temp' and s.user like '$gebruiker'";
if(isset($_POST['filter'])) {
	$filter = $_POST['filter'];
	if($filter != "All") $sql .= " AND s.name like '$filter'";
}
if($authenticated==true) {
	$sql .= " AND h.time like '$timefilter%' order by h.time DESC";
} else {
	echo "<br/><p class='error'>History shows 20 oldest events when not logged in</p>";
	$sql .= " ORDER BY h.time ASC LIMIT 0,20";
}
if(!$result = $db->query($sql)){ die('There was an error running the query [' . $db->error . ']');}
echo '<table id="table" align="center"><thead><tr><th>Tijd</th><th>Sensor</th><th>Status</th></tr></thead><tbody>';
while($row = $result->fetch_assoc()){
	echo '<tr>
	<td width="120px" align="right">'.strftime("%a %e %b %H:%M",strtotime($row['time'])).'&nbsp;</td>
	<td>&nbsp;'.$row['name'].'&nbsp;</td>
	<td>&nbsp;'.$row['omschrijving'].'</td>
	</tr>';
}
echo '</tbody></table><br/><br/><form method="post"><input type="hidden" name="filter" value="'.$filter.'"><input type="submit" name="importall" value="Historiek updaten" class="abutton settings gradient"/></form></div></div>';
$result->free();
include "footer.php";
?>