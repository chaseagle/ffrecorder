use ffrecords;
drop table  if exists keywords;
create table keywords
(
	id int auto_increment not null,
	keyword varchar(100) not null default '',
	`visit_date` date default '0000-00-00',
	`amount` int not null default 0,
	primary key(id)
)
