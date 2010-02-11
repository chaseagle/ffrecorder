<?php
define("BUSS_LAYER",1);
require_once('db.php');
if($_GET['a'] == 'count')
{
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
}
else if($_GET['a'] == 'star')
{
	$pid = $_GET['pid'];
	$v = $_GET['v'];
	setMine($pid, $v);
	echo $pid . ':' . $v;
}
else
{
	echo '不认识的命令。';
}
?>
