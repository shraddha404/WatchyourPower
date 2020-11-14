create index index_filename on datafiles(filename);
create index index_is_processed on datafiles(is_processed);

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `file_path` varchar(350) DEFAULT NULL,
  `description` text,
  `date_published` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

INSERT INTO `user_types` (`type`, `type_alias`) VALUES
('Owner', 'location-owner');

CREATE TABLE IF NOT EXISTS `location_owner_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `location_ids` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
