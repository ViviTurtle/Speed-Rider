CREATE USER 'Speed_Rider_User'@'localhost' IDENTIFIED BY 'Speed-Rider';

GRANT SELECT ON Speed_Rider.* TO 'Speed_Rider_User'@'localhost';
GRANT EXECUTE ON Speed_Rider.* TO 'Speed_Rider_User'@'localhost';
GRANT INSERT ON Speed_Rider.* TO 'Speed_Rider_User'@'localhost';
GRANT UPDATE ON Speed_Rider.* TO 'Speed_Rider_User'@'localhost';

-- Add Stored Procedures Permissions here
-- GRANT EXECUTE ON PROCEDURE Speed_Rider.[INSERT SP HERE] TO 'Speed_Rider_User'@'localhost';-- 

FLUSH PRIVILEGES;

SET SQL_SAFE_UPDATES = 0;