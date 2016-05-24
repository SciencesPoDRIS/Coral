-- @annelhote : Add a table to resource's languages

ALTER TABLE `Resource` ADD COLUMN `resourceLanguageID` int(11) default NULL AFTER `resourceAltURL`;



DROP TABLE IF EXISTS `ResourceLanguage`;
CREATE TABLE  `ResourceLanguage` (
  `resourceLanguageID` int(11) NOT NULL auto_increment,
  `shortName` varchar(200) default NULL,
  PRIMARY KEY  USING BTREE (`resourceLanguageID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



ALTER TABLE `ResourceLanguage` ADD INDEX `shortName` ( `shortName` );



-- ISO 639-1 : https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
INSERT INTO `ResourceLanguage` (resourceLanguageID, shortName, fullName) values (1, 'en');
INSERT INTO `ResourceLanguage` (resourceLanguageID, shortName, fullName) values (2, 'fr');
INSERT INTO `ResourceLanguage` (resourceLanguageID, shortName, fullName) values (3, 'sp');