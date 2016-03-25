create table city(
id int not null primary key auto_increment,
u_id not null default 0,
city_en varchar(30) not null default '',
city_cn varchar(10) not null default '',
city_thai varchar(30) not null default ''
)engine myisam charset utf8;