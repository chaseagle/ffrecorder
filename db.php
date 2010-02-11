<?php

defined('BUSS_LAYER') || die('不允许直接调用该页面。');

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

function getPageIdByHref($href)
{
	$hash = md5($href);
	$sql = 'select id from pages where hash=:hash limit 1;';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':hash' => $hash));
	if(!$r)
	{
		echo '获取页面IP出错:';
		die(var_dump($sth->errorInfo()));
	}
	$r = $sth->fetchAll();
	$sth->closeCursor();
	if(count($r) == 0)
	{
		return -1;
	}
	return $r[0]['id'];
}

function increPage($title,$host,$href)
{
	$hash = md5($href);
	$sql = 'select amount from pages where hash=:hash limit 1;';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':hash' => $hash));
	if(!$r)
	{
		echo '插入页面统计出错:';
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
		$sql = 'insert into pages(title, host, href, hash) values(:title, :host, :href, :hash)';
		$sth = $c->prepare($sql);
		$r = $sth->execute(array(':title' => htmlspecialchars($title,ENT_QUOTES), ':host' => $host, ':href' => $href, ':hash' => $hash));
		if(!$r)
		{
			echo '插入页面统计错误:';
			die(var_dump($sth->errorInfo()));
		}
	}
	else
	{
		$sql = 'update pages set amount=:amount where hash=:hash';
		$sth = $c->prepare($sql);
		$r = $sth->execute(array(':hash' => $hash, ':amount' => $amount + 1));	
		if(!$r)
		{
			echo '插入页面统计错误:';
			die(var_dump($sth->errorInfo()));
		}
	}	
	return getPageIdByHref($href);
}

function getPages()
{
	$sql = 'select * from pages order by amount desc';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array());
	$r = $sth->fetchAll();
	$sth->closeCursor();
	return $r;

}

function setMine($pageId, $favorite)
{
	$hash = md5($href);
	$sql = 'select id from pages where id=:pageId limit 1;';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':pageId' => $pageId));
	if(!$r)
	{
		echo '设置最爱页面出错:';
		die(var_dump($sth->errorInfo()));
	}
	$r = $sth->fetchAll();
	$sth->closeCursor();
	if(count($r) != 1)
	{
		echo '设置最爱页面不存在:';
		die(var_dump($sth->errorInfo()));
	}
	
	$sql = 'update pages set favorite=:favorite where id=:pageId';
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':pageId' => $pageId, ':favorite'=>$favorite));	
	if(!$r)
	{
		echo '设置最爱页面出错:';
		die(var_dump($sth->errorInfo()));
	}	
}

function getMine()
{
	$sql = 'select * from pages where favorite=true order by amount desc';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array());
	$r = $sth->fetchAll();
	$sth->closeCursor();
	return $r;
}

function createRecord($title, $host, $href, $visit_date='')
{
	$pageId = increPage($title, $host, $href);
	//$pageId = getPageIdByHref($href);
	if('' == $visit_date)
	{
		$visit_date = date('Y-m-d H:i:s');
	}
	if($pageId == -1)
	{
		echo '获取页面ID失败。';
	}
	$sql = 'insert into records(page_id, visit_date)
		values(:pageId, :visit_date)';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':pageId' => $pageId,
			    ':visit_date' => $visit_date
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
	if('' == $d)
	{
		$sql = 'select records.visit_date as visit_date, pages.title as title, pages.id as id, 
			pages.href as href, pages.host as host, pages.favorite as favorite
		       	from records left join pages on(pages.id=records.page_id) order by visit_date desc  ';
		$c = connectDb();
		$sth = $c->prepare($sql);
		$r = $sth->execute(array());
	}
	else
	{
		$sql = 'select records.visit_date as visit_date, pages.title as title, pages.id as id, 
			pages.href as href, pages.host as host, pages.favorite as favorite 
		       	from records left join pages on(pages.id=records.page_id) where records.visit_date between :start and :end order by visit_date desc  ';
		$c = connectDb();
		$sth = $c->prepare($sql);
		$r = $sth->execute(array(':start' => $d . ' 00:00:00', ':end' => $d . ' 23:59:59'));
	}
	
	if(!$r)
	{
		echo '获取数据错误:';
		die(var_dump($sth->errorInfo()));
	}
	$r = array_reverse($sth->fetchAll());
	$sth->closeCursor();
	return $r;
}

function searchPages($txt)
{
	$sql = 'select * from pages where title like :txt order by amount desc  ';
	$c = connectDb();
	$sth = $c->prepare($sql);
	$r = $sth->execute(array(':txt' => '%' . $txt . '%'));
	if(!$r)
	{
		echo '获取数据错误:';
		die(var_dump($sth->errorInfo()));
	}
	$r = $sth->fetchAll();
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
