<?php
function connectDb()
{
	static $conn = null;
	if(null == $conn)
	{
		$dsn = 'mysql:host=localhost;dbname=ffrecords';
		try
		{
			$conn = new PDO($dsn, 'chasea','mybaby',array(PDO::ATTR_PERSISTENT=>true));			
		}
		catch(PDOException $e)
		{
			die('连接数据库错误:' . $e->getMessage());
		}
	}
	return $conn;
}

function increDay()
{
	$day = date('Y-m-d');
	$sql = 'select amount from days where visit_date=:visit_date limit 1;';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':visit_date' => $day));
	if(!$r)
	{
		echo '插入日期统计出错:';
		die(var_dump($sth->errorInfo()));
	}
	$r = $sth->fetchAll();
	$sth->closeCursor();
	$amount = 0;
	if(count($r) == 1)
	{
		$amount = $r[0]['amount'];
	}
	
	if(0 == $amount)
	{
		$sql = 'insert into days(amount, visit_date) values(:amount, :visit_date)';
	}
	else
	{
		$sql = 'update days set amount=:amount where visit_date=:visit_date';
	}
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':visit_date' => $day, ':amount' => $amount + 1));
	if(!$r)
	{
		echo '插入日期统计错误:';
		die(var_dump($sth->errorInfo()));
	}
}

function increHost($host)
{
	$sql = 'select amount from hosts where host=:host limit 1;';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':host' => $host));
	if(!$r)
	{
		echo '插入主机统计出错:';
		die(var_dump($sth->errorInfo()));
	}
	$r = $sth->fetchAll();
	$sth->closeCursor();
	$amount = 0;
	if(count($r) == 1)
	{
		$amount = $r[0]['amount'];
	}
	if(0 == $amount)
	{
		$sql = 'insert into hosts(amount, host) values(:amount, :host)';
	}
	else
	{
		$sql = 'update hosts set amount=:amount where host=:host';
	}
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':host' => $host, ':amount' => $amount + 1));
	if(!$r)
	{
		echo '插入主机统计错误:';
		die(var_dump($sth->errorInfo()));
	}
}

function createRecord($title, $host, $href)
{
	$sql = 'insert into records(title, host, href, visit_date)
			values(:title, :host, :href, :visit_date)';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':title' => htmlspecialchars($title,ENT_QUOTES),
			    ':host' => $host,
			    ':href' => $href,
			    ':visit_date' => date('Y-m-d H:i:s')
		    ));
	if(!$r)
	{
		echo '插入数据错误:';
		die(var_dump($sth->errorInfo()));
	}
	increDay();
	increHost($host);
}

function getRecords($d)
{
	$sql = 'select * from records where visit_date between :start and :end order by visit_date desc  ';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':start' => $d . ' 00:00:00', ':end' => $d . ' 23:59:59'));
	if(!$r)
	{
		echo '获取数据错误:';
		die(var_dump($sth->errorInfo()));
	}
	$r = array_reverse($sth->fetchAll());
	$sth->closeCursor();
	return $r;
}

function searchRecords($txt)
{
	$sql = 'select * from records where title like :txt order by visit_date desc  ';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':txt' => '%' . $txt . '%'));
	if(!$r)
	{
		echo '获取数据错误:';
		die(var_dump($sth->errorInfo()));
	}
	$r = array_reverse($sth->fetchAll());
	$sth->closeCursor();
	return $r;

}

function getCountByMonth($m)
{
	$sql = 'select * from days where visit_date between :start and :end order by visit_date asc';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':start'=>$m . '-01', ':end' => $m . '-28'));
	if(!$r)
	{
		echo '获取数据错误:';
		die(var_dump($sth->errorInfo()));
	}
	$r = $sth->fetchAll();
	$sth->closeCursor();
	return $r;
}

?>
