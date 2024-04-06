-- if the table exists, delete it

DROP TABLE IF EXISTS users;

--create a new table 

CREATE TABLE users(
	username varchar(50) PRIMARY KEY,
	password varchar(100) NOT NULL);
	

-- insert data into tables

LOCK TABLES 'users' WRITE;
INSERT INTO users(username,password) VALUES ('team21', md5('Pa$$w0rd'));
UNLOCK TABLES;