-- Delete all demand matches from the database
-- This will completely remove all match records

SET FOREIGN_KEY_CHECKS = 0;

-- Delete all records from demand_matches table
DELETE FROM demand_matches;

-- Reset auto-increment counter
ALTER TABLE demand_matches AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;

-- Show result
SELECT COUNT(*) as remaining_matches FROM demand_matches;
