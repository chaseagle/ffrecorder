create database if not exists `ffrecords` character set = `utf8` collate=`utf8_general_ci`;
use  `ffrecords`;
drop table if exists `records`;
create table `records`
(
	`id` int auto_increment,
	`title` varchar(1000) not null default '',
	`host` varchar(500) not null default '',
	`href` varchar(500) not null default '',
	`visit_date` datetime default '0000-00-00 0:00:00',
	primary key(id)
);
drop table if exists `days`;
create table `days`
(
	`id` int auto_increment,
	`amount` int not null default 0,
	`visit_date` date default '0000-00-00',
	primary key(id)
);
drop table if exists `hosts`;
create table `hosts`
(
	`id` int auto_increment,
	`amount` int not null default 0,
	`host` varchar(50) not null default '',
	primary key(id)
);
