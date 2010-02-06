create database if not exists `ffrecords` character set = `utf8` collate=`utf8_general_ci`;
use  `ffrecords`;
drop table if exists `hosts`;
create table `hosts`
(
	`id` int auto_increment,
	`amount` int not null default 0,
	`host` varchar(50) not null default '',
	primary key(id)
)
