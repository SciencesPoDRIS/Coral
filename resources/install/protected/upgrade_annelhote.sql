-- @annelhote : Add a table for resource's languages


DROP TABLE IF EXISTS `Language`;
CREATE TABLE  `Language` (
  `languageId` int(11) NOT NULL auto_increment,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY USING BTREE (`languageId`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE `Language` ADD INDEX `shortName` ( `shortName` );


-- ISO 639-1 : https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
INSERT INTO `Language` (languageId, shortName) values (1, 'en');
INSERT INTO `Language` (languageId, shortName) values (2, 'fr');
INSERT INTO `Language` (languageId, shortName) values (3, 'es');
INSERT INTO `Language` (languageId, shortName) values (4, 'pt');
INSERT INTO `Language` (languageId, shortName) values (5, 'de');
INSERT INTO `Language` (languageId, shortName) values (6, 'mul');


DROP TABLE IF EXISTS `ResourceLanguage`;
CREATE TABLE  `ResourceLanguage` (
  `resourceLanguageId` int(11) NOT NULL auto_increment,
  `resourceId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  PRIMARY KEY  USING BTREE (`resourceLanguageId`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- @annelhote : Add a table for resource's status


ALTER TABLE `Resource` ADD COLUMN `resourceStatusID` int(11) default NULL AFTER `resourceAltURL`;


DROP TABLE IF EXISTS `ResourceStatus`;
CREATE TABLE  `ResourceStatus` (
  `resourceStatusID` int(11) NOT NULL auto_increment,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY USING BTREE (`resourceStatusID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE `ResourceStatus` ADD INDEX `shortName` ( `shortName` );


INSERT INTO `ResourceStatus` (resourceStatusID, shortName) values (1, 'test');
INSERT INTO `ResourceStatus` (resourceStatusID, shortName) values (2, 'new');


-- @annelhote : Add a table for resource's tutos


DROP TABLE IF EXISTS `ResourceTuto`;
CREATE TABLE  `ResourceTuto` (
  `resourceTutoID` int(11) NOT NULL auto_increment,
  `resourceID` int(11) NOT NULL,
  `name` varchar(200) default NULL,
  `url` varchar(200) default NULL,
  PRIMARY KEY USING BTREE (`resourceTutoID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- @annelhote : Add a field for resource's logo


ALTER TABLE `Resource` ADD COLUMN `logo` varchar(100) default NULL AFTER `resourceStatusID`;


-- @annelhote : Add a field for resource's accessibility


ALTER TABLE `Resource` ADD COLUMN `accessibility` int(11) default 0 AFTER `logo`;


-- @annelhote : Add a field for resource's publication


ALTER TABLE `Resource` ADD COLUMN `published` int(11) default 0 AFTER `accessibility`;


-- @annelhote : Add a field for resource's publication comment


ALTER TABLE `Resource` ADD COLUMN `publicationComment` text default NULL AFTER `published`;


-- @annelhote : Add a field for resource's publication date


ALTER TABLE `Resource` ADD COLUMN `publicationDate` DATE NULL DEFAULT NULL AFTER `publicationComment`;


-- @annelhote : Add a field for resource's title in french


ALTER TABLE `Resource` ADD COLUMN `titleText_fr` varchar(200) NULL DEFAULT NULL AFTER `publicationDate`;


-- @annelhote : Add a field for resource's description in french


ALTER TABLE `Resource` ADD COLUMN `descriptionText_fr` text NULL DEFAULT NULL AFTER `titleText_fr`;


-- @annelhote : Add a field for note's description in french


ALTER TABLE `ResourceNote` ADD COLUMN `noteTextFr` TEXT NULL DEFAULT NULL AFTER `noteText`;


-- @annelhote : Add a table for resource's types

UPDATE `Resource` SET `resourceTypeID` = NULL;

DROP TABLE IF EXISTS `ResourceTypeLink`;
CREATE TABLE  `ResourceTypeLink` (
  `resourceTypeLinkId` int(11) NOT NULL auto_increment,
  `resourceId` int(11) NOT NULL,
  `resourceTypeId` int(11) NOT NULL,
  PRIMARY KEY  USING BTREE (`resourceTypeLinkId`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;