CREATE TABLE IF NOT EXISTS `huge`.`todos` (
  `todo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `todo_text` text NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`todo_id`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_unicode_ci
COMMENT='user todo list';