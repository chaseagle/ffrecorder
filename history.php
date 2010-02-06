<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<title>Firefox 历史记录</title>
<link rel="stylesheet" type="text/css" href="public/css/main.css" />
<link rel="stylesheet" type="text/css" href="public/css/jquery-ui-1.7.2.custom.css" />
<script type="text/javascript" src="public/js/jquery-1.3.2.min.js" ></script>
<script type="text/javascript" src="public/js/jquery-ui-1.7.2.custom.min.js" ></script>
<script type="text/javascript" src="public/js/main.js"></script>
</head>
<body>
<div id="idDivCalendar">
</div>
<div id="idDivSearch">
	<span>搜索历史记录</span>
	<form id="idSearch" name="idSearch" method="post" action="">
	<input type="text" name="searchTxt" value="<?php echo $_POST['searchTxt']?>" />
		<input type="submit" value="搜索" />
	</form>
</div>
<div id="idRecordList">
<?php
require_once('db.php');
if($_SERVER['REQUEST_METHOD'] == 'GET')
{
	if(isset($_GET['d']))
	{
		$d = $_GET['d'];
	}
	else
	{
		$d = date('Y-m-d');
	}
	$r = getRecords($d);
	echo '<div id="idDivDate">' . $d  . '</div>';
	for ($ii = 0, $jj = count($r); $ii < $jj ; $ii++)
	{
		echo '<div class="clsRecord"><div class="clsVisitDate">' . substr($r[$ii]['visit_date'],-8) . '</div><div class="clsTitle"><a href="' . $r[$ii]['href'] . '" target="_blank">' . $r[$ii]['title'] . '</a></div></div>';
	}
}
else
{
	$r = searchRecords($_POST['searchTxt']);
	echo '<div id="idDivDate">搜索结果：</div>';
	for ($ii = 0, $jj = count($r); $ii < $jj ; $ii++)
	{
		echo '<div class="clsRecord"><div class="clsVisitDate">' . $r[$ii]['visit_date'] . '</div><div class="clsTitle"><a href="' . $r[$ii]['href'] . '" target="_blank">' . $r[$ii]['title'] . '</a></div></div>';
	}	
}



?>
</div>
</body>
