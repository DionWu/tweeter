CREATE TABLE IF NOT EXISTS login_info (
id int unsigned not null AUTO_INCREMENT,
firstname varchar(255) not null,
lastname varchar(255) not null,
username varchar(13) not null UNIQUE,
password nvarchar(128) not null,
email varchar(255) not null UNIQUE, 
timezone varchar(255) not null,
PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS tweets (
id int unsigned not null AUTO_INCREMENT PRIMARY KEY,
user_id int NOT NULL, 
username varchar(255) NOT NULL,
tweet varchar(140),
time DATETIME NOT NULL,
unixtime int unsigned NOT NULL
);

CREATE TABLE IF NOT EXISTS followers (
id int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
user_id int NOT NULL,
following_id int NOT NULL
);
