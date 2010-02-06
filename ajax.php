<?php
require_once('db.php');
$r = array();
for($ii = 1; $ii<= 31 ; $ii++)
{
	$r[$ii] = 0;
}
$records = getCountByMonth($_GET['m']);
for($ii = 0, $jj = count($records); $ii < $jj; $ii++)
{
	$day = $records[$ii]['visit_date'];
	$day = intval(substr($day,-2));
	$r[$day] =  $records[$ii]['amount'];
}
echo implode($r,':');
?>
