DROP DATABASE IF EXISTS Speed_Rider;
CREATE DATABASE Speed_Rider;
USE Speed_Rider; 

DROP TABLE IF EXISTS T_CODES;
CREATE TABLE T_CODES(
		CODE_ID CHAR(5) NOT NULL,
        CODE_TYPE VARCHAR(10) NOT NULL,
		CODE_DESCRIPTION VARCHAR(255) NOT NULL,
		CREATED_BY INT,
		CREATED_ON TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

DROP TABLE IF EXISTS T_USER;
CREATE TABLE T_USER
(
        USER_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		USERNAME VARCHAR(32) NOT NULL,
		SALTED_HASH CHAR(64) NOT NULL,
		FNAME VARCHAR(32) NOT NULL,
		LNAME VARCHAR(32) NOT NULL,
		EMAIL VARCHAR(50) NOT NULL,
		PRIM_PHONE CHAR(10) NOT NULL,
		SEC_PHONE CHAR(10),
		USER_TYPE CHAR(5) NOT NULL REFERENCES T_CODES(CODE_ID)
);

DROP TABLE IF EXISTS T_TRANSANCTION;
CREATE TABLE T_TRANSANCTION
(
		TRANS_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		CLIENT_ID INT NOT NULL REFERENCES T_USER(USER_ID),
		DRIVER_ID INT  NOT NULL REFERENCES T_USER(USER_ID),
		PICK_UP_ADDRESS VARCHAR(200) NOT NULL,
		DROP_OFF_ADDRESS VARCHAR(200) NOT NULL,
		COST DECIMAL(65,2) NOT NULL,
		STATUS CHAR(5) NOT NULL REFERENCES T_CODES(CODE_ID),
		CREATED_ON TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
		UPDATED_ON TIMESTAMP
);
ALTER TABLE T_CODES ADD CONSTRAINT FK_USER_TYPE FOREIGN KEY (CREATED_BY) REFERENCES T_USER(USER_ID);

-- DEFAULT DATA BECAUSE LOAD DATA LOCAL INFILE COMMAND IS NOT WORKING
INSERT INTO T_USER (USERNAME, SALTED_HASH, FNAME, LNAME, EMAIL, PRIM_PHONE, USER_TYPE)
	VALUES('ViviTurtle','5E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E01','Vivi','Langga',
		'vivi.langga@gmail.com','4086075657','ADMIN'); 

INSERT INTO T_USER (USERNAME, SALTED_HASH, FNAME, LNAME, EMAIL, PRIM_PHONE, USER_TYPE)
	VALUES('Client1','5E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E02','William','Wallace',
		'braveheart@gmail.com','4086071234','CLIET'); 

INSERT INTO T_USER (USERNAME, SALTED_HASH, FNAME, LNAME, EMAIL, PRIM_PHONE, USER_TYPE)
	VALUES('Driver1','5E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E0C65E03','Clark','Kent',
		'superman@gmail.com','4086074321','DRIVR'); 

INSERT INTO T_CODES VALUES ('ADMIN','USER','Adminstrative Users',1, CURRENT_TIMESTAMP);
INSERT INTO T_CODES VALUES ('DRIVR','USER','Drivers',1, CURRENT_TIMESTAMP);
INSERT INTO T_CODES VALUES ('CLIET','USER','Clients',1, CURRENT_TIMESTAMP);
INSERT INTO T_CODES VALUES ('COMPT','STATUS','Driver has been paid and Client has been dropped off',1, CURRENT_TIMESTAMP);
INSERT INTO T_CODES VALUES ('IPROG','STATUS','Transanction is in-progress',1, CURRENT_TIMESTAMP);
INSERT INTO T_CODES VALUES ('REFUN','STATUS','The transanction has been canceled',1, CURRENT_TIMESTAMP);

INSERT INTO T_TRANSANCTION (CLIENT_ID, DRIVER_ID, PICK_UP_ADDRESS, DROP_OFF_ADDRESS, COST, STATUS)
	VALUES (2,3,'P Sherman 42 Wallaby Way Sydney', 'Smallville', 23400.50, 'IPROG');

INSERT INTO T_TRANSANCTION (CLIENT_ID, DRIVER_ID, PICK_UP_ADDRESS, DROP_OFF_ADDRESS, COST, STATUS)
	VALUES (2,3,'Smallville', 'Krypton', 0, 'IPROG');

INSERT INTO T_TRANSANCTION (CLIENT_ID, DRIVER_ID, PICK_UP_ADDRESS, DROP_OFF_ADDRESS, COST, STATUS)
	VALUES (2,3,'Durry Lane', 'Where the land meets the sky. Where the eagle and the raven fly free. I live under the sun and the moon.', 240.50, 'IPROG');

UPDATE T_TRANSANCTION
	SET STATUS = 'COMPT', UPDATED_ON = CURRENT_TIMESTAMP
	WHERE TRANS_ID = 1;

UPDATE T_TRANSANCTION
	SET STATUS = 'REFUN', UPDATED_ON = CURRENT_TIMESTAMP
	WHERE TRANS_ID = 3;

