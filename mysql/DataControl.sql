CREATE USER 'Speed_Rider_User'@'localhost' IDENTIFIED BY 'speeqhyw_Speed_Rider';

CREATE USER 'speeqhyw_who'@'localhost' IDENTIFIED BY 'speeqhyw_Speed_Rider';

SET PASSWORD FOR 'speeqhyw_who'@'localhost' = PASSWORD('Blah!ThisSUck@#123');

GRANT SELECT ON speeqhyw_Speed_Rider.* TO 'Speed_Rider_User'@'localhost';
GRANT EXECUTE ON speeqhyw_Speed_Rider.* TO 'Speed_Rider_User'@'localhost';
GRANT INSERT ON speeqhyw_Speed_Rider.* TO 'Speed_Rider_User'@'localhost';
GRANT UPDATE ON speeqhyw_Speed_Rider.* TO 'Speed_Rider_User'@'localhost';

GRANT SELECT ON speeqhyw_Speed_Rider.* TO 'speeqhyw_who'@'localhost';
GRANT EXECUTE ON speeqhyw_Speed_Rider.* TO 'speeqhyw_who'@'localhost';
GRANT INSERT ON speeqhyw_Speed_Rider.* TO 'speeqhyw_who'@'localhost';
GRANT UPDATE ON speeqhyw_Speed_Rider.* TO 'speeqhyw_who'@'localhost';

-- Add Stored Procedures Permissions here
-- GRANT EXECUTE ON PROCEDURE Speed_Rider.[INSERT SP HERE] TO 'Speed_Rider_User'@'localhost';-- 

FLUSH PRIVILEGES;

SET SQL_SAFE_UPDATES = 0;