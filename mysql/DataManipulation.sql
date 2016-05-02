USE Speed_Rider;

-- EXAMPLE TRIGGER
-- DROP TRIGGER IF EXISTS TR_INIT_CUSTOMER;
-- DELIMITER //
-- CREATE TRIGGER TR_CHECK_USER
-- BEFORE INSERT ON T_TRANSANCTION
-- FOR EACH ROW
-- BEGIN
-- INSERT INTO T_ACCOUNT (ACCOUNT_ID, ACCOUNT_TYPE, AMOUNT) VALUES (NEW.ACCOUNT_ID, 1, 0);
-- INSERT INTO T_ACCOUNT (ACCOUNT_ID, ACCOUNT_TYPE, AMOUNT) VALUES (NEW.ACCOUNT_ID, 2, 0);
-- END//
-- DELIMITER ;

DROP PROCEDURE IF EXISTS SP_LOGIN;
DELIMITER //
CREATE PROCEDURE SP_LOGIN(IN
P_USERNAME VARCHAR(32),
P_HASH CHAR(64))
BEGIN
	SELECT USER_ID, USER_TYPE
	FROM T_USER
	WHERE USERNAME = P_USERNAME AND SALTED_HASH = P_HASH;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS SP_GET_SALT;
DELIMITER //
CREATE PROCEDURE SP_GET_SALT(IN
P_USERNAME VARCHAR(32))
BEGIN
	SELECT LEFT(SALTED_HASH , 16)
	FROM T_USER
	WHERE USERNAME = P_USERNAME;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS SP_REGISTER;
DELIMITER //
CREATE PROCEDURE SP_REGISTER(IN
P_USERNAME VARCHAR(32),
P_SALTED_HASH CHAR(64),
P_FNAME VARCHAR(32),
P_LNAME VARCHAR(32),
P_EMAIL VARCHAR(50),
P_PRIM_PHONE CHAR(10),
P_SEC_PHONE CHAR(10),
P_USER_TYPE CHAR(5))
BEGIN
	IF EXISTS(SELECT * FROM T_USER WHERE USERNAME = P_USERNAME) THEN SELECT 0;
	ELSE INSERT INTO T_USER (USERNAME, SALTED_HASH, FNAME, LNAME, EMAIL, PRIM_PHONE, USER_TYPE)
			VALUES(P_USERNAME,P_SALTED_HASH,P_FNAME,P_LNAME,P_EMAIL, P_PRIM_PHONE, P_SEC_PHONE, P_USER_TYPE);
		SELECT 1;
	END IF;
END//
DELIMITER ;


-- Used for password recovery
DROP PROCEDURE IF EXISTS SP_CHECK_EMAIL;
DELIMITER //
CREATE PROCEDURE SP_CHECK_EMAIL(IN
P_EMAIL VARCHAR(50))
BEGIN
	IF EXISTS (SELECT * FROM T_USER WHERE EMAIL = P_EMAIL) 
		THEN SELECT 1 AS EMAIL_FOUND;
		ELSE SELECT 0 AS EMAIL_FOUND;
	END IF;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS SP_PASSWORD_RESET;
DELIMITER //
CREATE PROCEDURE SP_PASSWORD_RESET(IN
P_SALTED_HASH CHAR(64),
P_EMAIL VARCHAR(50))
BEGIN
	UPDATE T_USER
	SET SALTED_HASH = P_SALTED_HASH
	WHERE EMAIL = P_EMAIL;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS SP_GET_DRIV_LFJOB;
DELIMITER //
CREATE PROCEDURE SP_GET_DRIV_LFJOB()
BEGIN
	SELECT USERNAME, FNAME, LNAME, PRIM_PHONE,SEC_PHONE,CURRENT_LONGITUDE,CURRENT_LATITUDE, USER_ID
	FROM V_GET_DRIVERS
	WHERE STATUS_TYPE = 'LFCLT';
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS SP_CHECK_IF_CHOSEN;
DELIMITER //
CREATE PROCEDURE SP_CHECK_IF_CHOSEN(IN
P_USERNAME VARCHAR(32))
BEGIN
	IF EXISTS(SELECT * FROM T_USER WHERE USERNAME = P_USERNAME AND STATUS_TYPE = 'INROT')
	THEN SELECT 1 AS IS_CHOSEN;
	ELSE SELECT 0 AS IS_CHOSEN;
	END IF;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS SP_CHANGE_2_LFCLT;
DELIMITER //
CREATE PROCEDURE SP_CHANGE_2_LFCLT(IN
P_USERNAME VARCHAR(32),
P_CURRENT_LONGITUDE DECIMAL(8,5),
P_CURRENT_LATITUDE DECIMAL(8,5))
BEGIN
	UPDATE T_USER
	SET CURRENT_LONGITUDE = P_CURRENT_LONGITUDE, CURRENT_LATITUDE = P_CURRENT_LATITUDE, STATUS_TYPE = 'LFCLT'
	WHERE USERNAME = P_USERNAME;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS SP_LINK_USER;
DELIMITER //
CREATE PROCEDURE SP_LINK_USER(IN
P_DRIVER_ID INT,
P_CLIENT_ID INT,
P_CLIENT_LONG DECIMAL(8,5),
P_CLIENT_LAT DECIMAL(8,5),
P_DRIVR_LONG DECIMAL(8,5),
P_DRIVR_LAT DECIMAL(8,5))
BEGIN
	UPDATE T_USER
	SET STATUS_TYPE = 'INROT'
	WHERE USER_ID = P_CLIENT_ID;

	UPDATE T_USER
	SET STATUS_TYPE = 'INROT'
	WHERE USER_ID = P_DRIVER_ID;

	INSERT INTO T_TRANSANCTION (CLIENT_ID, DRIVER_ID, PICK_UP_LONGITUDE, PICK_UP_LATITUDE,INIT_DRIVER_LONGITUDE, INIT_DRIVER_LATITUDE, STATUS)
	VALUES (P_CLIENT_ID, P_DRIVER_ID ,P_CLIENT_LONG, P_CLIENT_LAT, P_DRIVR_LONG, P_DRIVR_LAT, 'IPROG');
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS SP_UPDATE_DRIVER_LOC;
DELIMITER //
CREATE PROCEDURE SP_UPDATE_DRIVER_LOC(IN
P_USERNAME VARCHAR(32),
P_DRIVER_LONG DECIMAL(8,5),
P_DRIVER_LAT DECIMAL(8,5))
BEGIN
	UPDATE T_USER
	SET CURRENT_LONGITUDE = P_DRIVER_LONG, CURRENT_LATITUDE = P_DRIVER_LAT
	WHERE USERNAME = P_USERNAME;
END//
DELIMITER ;



-- NEED TO DISGUST HOW TO IMPLMENT FINDN NEAREST DRIVER AND CLIENT AND CREATE STORED PROCEDURE



