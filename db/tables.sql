create database if not exists small_trade_manager character set 'utf8' collate 'utf8_general_ci';
use small_trade_manager;
set collation_connection = 'utf8_general_ci';
set sql_mode = 'no_auto_value_on_zero';
set time_zone = '-03:00';

-- DADOS DO SISTEMA
create table if not exists budgets (
	id int unsigned auto_increment not null primary key,
	employee int unsigned not null,
	day datetime not null,
	form_of_payment varchar(32) character set 'utf8' not null,
	discount float unsigned not null,
	total decimal(8,2) not null,
	foreign key(employee) references employees(id)
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists clients (
	id int unsigned auto_increment not null primary key,
	name varchar(32) character set 'utf8' not null,
	surname varchar(32) character set 'utf8' not null,
	rg varchar(9) character set 'utf8' null unique,
	cpf char(11) character set 'utf8' not null unique,
	birthday date not null,
	postal_code char(8) character set 'utf8' null,
	district varchar(32) character set 'utf8' null,
	city varchar(64) character set 'utf8' not null,
	state char(2) character set 'utf8' not null,
	address varchar(64) character set 'utf8' not null,
	number int unsigned not null,
	complement varchar(32) character set 'utf8' null,
	email varchar(32) character set 'utf8' null,
	phone char(10) character set 'utf8' null,
	cell_phone char(11) character set 'utf8' null,
	sex char(1) character set 'utf8' not null,
	note varchar(512) character set 'utf8' null
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists employees (
	id int unsigned auto_increment not null primary key,
	name varchar(32) character set 'utf8' not null,
	surname varchar(32) character set 'utf8' not null,
	alias varchar(32) character set 'utf8' not null unique,
	password varchar(32) character set 'utf8' not null,
	rg char(9) character set 'utf8' null unique,
	cpf char(11) character set 'utf8' not null unique,
	birthday date not null,
	postal_code char(8) character set 'utf8' null,
	district varchar(32) character set 'utf8' null,
	city varchar(64) character set 'utf8' not null,
	state char(2) character set 'utf8' not null,
	address varchar(64) character set 'utf8' not null,
	number int unsigned not null,
	complement varchar(32) character set 'utf8' null,
	email varchar(32) character set 'utf8' not null unique,
	phone char(10) character set 'utf8' null,
	cell_phone char(11) character set 'utf8' null unique,
	sex char(1) character set 'utf8' not null,
	note varchar(512) character set 'utf8' null,
	permission int unsigned not null unique,
	foreign key(permission) references permissions(id)
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists permissions (
	id int unsigned auto_increment not null primary key,
	budget tinyint(1) unsigned not null,
	client tinyint(1) unsigned not null,
	employee tinyint(1) unsigned not null,
	product tinyint(1) unsigned not null,
	provider tinyint(1) unsigned not null,
	purchase tinyint(1) unsigned not null,
	record tinyint(1) unsigned not null,
	report tinyint(1) unsigned not null,
	sale tinyint(1) unsigned not null,
	setting tinyint(1) unsigned not null,
	service tinyint(1) unsigned not null
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists products (
	id int unsigned auto_increment not null primary key,
	code int unsigned not null unique,
	name varchar(32) character set 'utf8' not null,
	provider int unsigned not null,
	unity varchar(4) character set 'utf8' not null,
	gross_price decimal(6,2) not null,
	net_price decimal(6,2) not null,
	minimum_stock int unsigned not null,
	maximum_stock int unsigned not null,
	amount int unsigned not null,
	weigth float unsigned not null,
	situation tinyint(1) unsigned not null,
	source tinyint(1) unsigned not null,
	foreign key(provider) references providers(id)
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists providers (
	id int unsigned auto_increment not null primary key,
	company_name varchar(32) character set 'utf8' not null,
	fantasy_name varchar(64) character set 'utf8' not null,
	state_registration char(12) character set 'utf8' null unique,
	cnpj char(14) character set 'utf8' not null unique,
	foundation_date date not null,
	postal_code char(8) character set 'utf8' null,
	district varchar(32) character set 'utf8' null,
	city varchar(64) character set 'utf8' not null,
	state char(2) character set 'utf8' not null,
	address varchar(64) character set 'utf8' not null,
	number int unsigned not null,
	complement varchar(32) character set 'utf8' null,
	email varchar(32) character set 'utf8' not null,
	phone char(10) character set 'utf8' not null,
	cell_phone char(11) character set 'utf8' null,
	note varchar(512) character set 'utf8' null
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists purchases (
	id int unsigned auto_increment not null primary key,
	provider int unsigned not null,
	employee int unsigned not null,
	day datetime not null,
	form_of_payment varchar(32) character set 'utf8' not null,
	discount float unsigned not null,
	total decimal(8,2) not null,
	foreign key(provider) references providers(id),
	foreign key(employee) references employees(id)
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists records (
	id int unsigned auto_increment not null primary key,
	reference int unsigned not null,
	action varchar(8) character set 'utf8' not null,
	entity varchar(16) character set 'utf8' not null,
	employee int unsigned not null,
	description longtext character set 'utf8' null,
	day datetime not null,
	foreign key(employee) references employees(id)
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists sales (
	id int unsigned auto_increment not null primary key,
	client int unsigned not null,
	employee int unsigned not null,
	day datetime not null,
	form_of_payment varchar(32) character set 'utf8' not null,
	discount float unsigned not null,
	total decimal(8,2) not null,
	foreign key(client) references clients(id),
	foreign key(employee) references employees(id)
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists services (
	id int unsigned auto_increment not null primary key,
	code int unsigned not null,
	name varchar(32) character set 'utf8' not null,
	type varchar(32) character set 'utf8' not null,
	price decimal(6,2) not null,
	workload time null
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists settings (
	id int unsigned auto_increment not null primary key,
	company_name varchar(32) character set 'utf8' not null,
	fantasy_name varchar(64) character set 'utf8' not null,
	cnpj char(14) character set 'utf8' not null unique,
	postal_code char(8) character set 'utf8' null,
	district varchar(32) character set 'utf8' null,
	city varchar(64) character set 'utf8' not null,
	state char(2) character set 'utf8' not null,
	address varchar(64) character set 'utf8' not null,
	number int unsigned not null,
	email varchar(32) character set 'utf8' null,
	phone char(10) character set 'utf8' not null,
	website varchar(32) character set 'utf8' null
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';
-- !DADOS DO SISTEMA

-- VETORES DO SISTEMA
create table if not exists product_budget (
	id int unsigned auto_increment not null primary key,
	product int unsigned not null,
	budget int unsigned not null,
	quantity int unsigned not null,
	unit_price decimal(6,2) not null,
	foreign key(product) references products(id),
	foreign key(budget) references budgets(id)
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists product_purchase (
	id int unsigned auto_increment not null primary key,
	product int unsigned not null,
	purchase int unsigned not null,
	quantity int unsigned not null,
	unit_price decimal(6,2) not null,
	foreign key(product) references products(id),
	foreign key(purchase) references purchases(id)
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists product_sale (
	id int unsigned auto_increment not null primary key,
	product int unsigned not null,
	sale int unsigned not null,
	quantity int unsigned not null,
	unit_price decimal(6,2) not null,
	foreign key(product) references products(id),
	foreign key(sale) references sales(id)
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists service_budget (
	id int unsigned auto_increment not null primary key,
	service int unsigned not null,
	budget int unsigned not null,
	quantity int unsigned not null,
	unit_price decimal(6,2) not null,
	foreign key(service) references services(id),
	foreign key(budget) references budgets(id)
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';

create table if not exists service_sale (
	id int unsigned auto_increment not null primary key,
	service int unsigned not null,
	sale int unsigned not null,
	quantity int unsigned not null,
	unit_price decimal(6,2) not null,
	foreign key(service) references services(id),
	foreign key(sale) references sales(id)
) engine='InnoDB' default charset='utf8' collate='utf8_general_ci';
-- !VETORES DO SISTEMA
