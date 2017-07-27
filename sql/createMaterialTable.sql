CREATE TABLE IF NOT EXISTS `forumzfd_material` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `description` text,
  `is_active` tinyint(3) unsigned DEFAULT NULL,
  `price` decimal(9,2) DEFAULT NULL,
  `material_category_id` varchar(512) DEFAULT NULL,
  `short_description` varchar(512) DEFAULT NULL,
  `subtitle` varchar(256) CHARACTER SET big5 DEFAULT NULL,
  `creation_year` char(4) DEFAULT NULL,
  `language_id` varchar(512) DEFAULT NULL,
  `number_of_pages` int(11) DEFAULT NULL,
  `download_link` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
