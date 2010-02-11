<html>
<head>
<meta http-equiv="Cotent-Type" content="text/html;charset=utf-8" />
<title>Firefox 页面记录</title>
</head>
<body>
<?php
define("BUSS_LAYER",1);
require_once('db.php');
$r = getPages();
for($ii=0,$jj=count($r); $ii<$jj; $ii++)
{
	echo 'id:' . $r[$ii]['id']  .'<br>title:' . $r[$ii]['title']  . '<br>href:' . $r[$ii]['href'] . '<br>amount:' . $r[$ii]['amount'] . '<br>hash:' . $r[$ii]['hash'] . '<br><br>';
}
?>
</body>
</html>
