<?php
include_once "parameters.php";

require_once('calendar/classes/tc_calendar.php');
echo '<script language="javascript" src="calendar/calendar.js"></script>';
echo '<div class="item wide gradient"><p class="number">9</p>';
print_r($_POST);
echo '</div>';
echo '<div class="item wide gradient"><p class="number">9</p>';

echo '
<form id="form1" name="form1" method="post" action="#">
<input type="hidden" name="showtest" value="Test"/>';
$myCalendar = new tc_calendar("datefilter", true, true);
$myCalendar->setIcon("calendar/images/iconCalendar.gif");
$myCalendar->setPath("calendar/");
$myCalendar->startDate(1);
$myCalendar->setYearInterval(2014, date('Y', time()));
$myCalendar->dateAllow('2014-01-01', '2099-12-31');
$myCalendar->setDateFormat('j F Y');
$myCalendar->writeScript();

echo '<input type="submit" name="Submit" value="Submit" /></form>';

echo date('Y', time());

echo '</div>';
