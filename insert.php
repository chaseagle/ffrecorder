<?php
/*
	function encode($c, $e)
	{
		//return $c;
		//if('gb2312' == $e)
		//{
		//	return $c;
		//}	
		//else
		//{
			return iconv('utf-8','gb2312', $c);
		//}
	}

	function writeTxt($c)
	{
		$f = fopen('ab.txt','a+');
		fwrite($f, $c);
		fclose($f);
	}

	function writeTxt($c)
	{
		$o = file_get_contents('ab.txt');
		if(false === $o)
		{
			$o =  '';
		}
		$f = fopen('ab.txt','w');
		fwrite($f, $o . $c);
		fclose($f);
	}*/
	define("BUSS_LAYER",1);
	require_once('db.php');
	if(isset($_POST['href']) && isset($_POST['title']) && isset($_POST['host']))
	{
		createRecord($_POST['title'], $_POST['host'], $_POST['href']);
		echo "ok";
	}
	else
	{
		echo "fail to get data";
	}
?>
