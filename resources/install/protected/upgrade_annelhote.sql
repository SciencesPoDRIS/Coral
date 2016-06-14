-- @annelhote : Add a table to resource's languages


DROP TABLE IF EXISTS `Language`;
CREATE TABLE  `Language` (
  `languageId` int(11) NOT NULL auto_increment,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY  USING BTREE (`languageId`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE `Language` ADD INDEX `shortName` ( `shortName` );


-- ISO 639-1 : https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
INSERT INTO `Language` (languageId, shortName) values (1, 'en');
INSERT INTO `Language` (languageId, shortName) values (2, 'fr');


DROP TABLE IF EXISTS `ResourceLanguage`;
CREATE TABLE  `ResourceLanguage` (
  `resourceLanguageId` int(11) NOT NULL auto_increment,
  `resourceId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  PRIMARY KEY  USING BTREE (`resourceLanguageId`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- @annelhote : Add a table to resource's status

ALTER TABLE `Resource` ADD COLUMN `resourceStatusID` int(11) default NULL AFTER `resourceAltURL`;


DROP TABLE IF EXISTS `ResourceStatus`;
CREATE TABLE  `ResourceStatus` (
  `resourceStatusID` int(11) NOT NULL auto_increment,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY  USING BTREE (`resourceStatusID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE `ResourceStatus` ADD INDEX `shortName` ( `shortName` );


INSERT INTO `ResourceStatus` (resourceStatusID, shortName) values (1, 'test');
INSERT INTO `ResourceStatus` (resourceStatusID, shortName) values (2, 'new');


-- @annelhote : Add a field to resource's logo


ALTER TABLE `Resource` ADD COLUMN `logo` varchar(45) default NULL AFTER `resourceStatusID`;


ALTER TABLE `Resource` ADD COLUMN `accessibility` int(11) default 0 AFTER `logo`;