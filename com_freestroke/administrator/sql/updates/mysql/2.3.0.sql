ALTER TABLE `#__freestroke_events`
	ADD COLUMN `meetsession_id` INTEGER  AFTER id;
;
ALTER TABLE `#__freestroke_entries`
	ADD COLUMN `meetsession_id` INTEGER  AFTER id;
