CREATE TABLE IF NOT EXISTS `PREFIX_skeleton_example` (
	`skeleton_example_id` INT(11) NOT NULL AUTO_INCREMENT,
	`date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`skeleton_example_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;