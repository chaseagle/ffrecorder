use ffrecords;
drop table  if exists records;
create table records
(
	id int auto_increment not null,
	page_id int not null,
	`visit_date` datetime default '0000-00-00 0:00:00',
	primary key(id)
)
