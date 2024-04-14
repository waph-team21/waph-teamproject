DROP TABLE IF EXISTS users;

CREATE TABLE users (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(100) NOT NULL
);

LOCK TABLES `users` WRITE;
INSERT INTO users(username,password) VALUES ('team21',md5('Pa$$w0rd'));
UNLOCK TABLES;
