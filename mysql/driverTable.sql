CREATE TABLE driverTable ( 
userID int(9) NOT NULL auto_increment, 
firstName VARCHAR(50) NOT NULL, 
lastName VARCHAR(50) NOT NULL, 
userName VARCHAR(40) NOT NULL, 
email VARCHAR(40) NOT NULL, 
PrimPhone CHAR(10) NOT NULL,
driverLicense VARCHAR(20) NOT NULL,
SecPhone CHAR(10),
pass VARCHAR(40) NOT NULL, 
PRIMARY KEY(userID) 
);