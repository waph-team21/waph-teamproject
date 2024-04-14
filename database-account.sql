CREATE DATABASE IF NOT EXISTS waph_team21;
DROP USER IF EXISTS 'team21'@'localhost';
CREATE USER 'team21'@'localhost' IDENTIFIED BY 'Pa$$w0rd';
GRANT ALL ON waph_team21.* TO 'team21'@'localhost';
