SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `primary_area`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `primary_area` (
  `primary_id` INT NOT NULL AUTO_INCREMENT ,
  `primary_area` VARCHAR(45) NULL ,
  PRIMARY KEY (`primary_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `providers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `providers` (
  `providerID` INT NOT NULL AUTO_INCREMENT ,
  `primary_id` INT NULL ,
  `name_ofc` VARCHAR(45) NULL DEFAULT NULL ,
  `email_ofc` VARCHAR(320) NULL DEFAULT NULL ,
  `street_ofc` VARCHAR(45) NULL DEFAULT NULL ,
  `city_ofc` VARCHAR(45) NULL DEFAULT NULL ,
  `state_ofc` VARCHAR(45) NULL DEFAULT NULL ,
  `zipcode_ofc` CHAR(5) NULL DEFAULT NULL ,
  `country_ofc` VARCHAR(45) NULL DEFAULT NULL ,
  `phone_ofc` VARCHAR(20) NULL DEFAULT NULL ,
  `philosophy` VARCHAR(1000) NULL DEFAULT NULL ,
  `provider_active` TINYINT(1) NULL DEFAULT NULL ,
  `latitude_ofc` FLOAT(10,6) NULL DEFAULT NULL ,
  `longitude_ofc` FLOAT(10,6) NULL DEFAULT NULL ,
  `website_ofc` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`providerID`) ,
  INDEX `primary_id_providers_idx` (`primary_id` ASC) ,
  CONSTRAINT `primary_id_providers`
    FOREIGN KEY (`primary_id` )
    REFERENCES `primary_area` (`primary_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resources`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `resources` (
  `resourceID` INT NOT NULL AUTO_INCREMENT ,
  `resource_description` VARCHAR(45) NULL DEFAULT NULL ,
  `initial_cost` FLOAT(10,2) NULL DEFAULT NULL ,
  `recurring_cost` FLOAT(10,2) NULL DEFAULT NULL ,
  `additional_cost` FLOAT(10,2) NULL DEFAULT NULL ,
  PRIMARY KEY (`resourceID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `provider_resources`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `provider_resources` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `resourceID` INT NOT NULL ,
  `providerID` INT NOT NULL ,
  `available_qty` INT NULL DEFAULT NULL ,
  `needed_qty` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `providerID_provResources_idx` (`providerID` ASC) ,
  INDEX `resourceID_provResources_idx` (`resourceID` ASC) ,
  CONSTRAINT `providerID`
    FOREIGN KEY (`providerID` )
    REFERENCES `providers` (`providerID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `resourceID`
    FOREIGN KEY (`resourceID` )
    REFERENCES `resources` (`resourceID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `heat_maps`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `heat_maps` (
  `heatmapID` INT NOT NULL AUTO_INCREMENT ,
  `lat_heat` FLOAT(10,6) NULL DEFAULT NULL ,
  `long_heat` FLOAT(10,6) NULL DEFAULT NULL ,
  `entry_date` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`heatmapID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_issues`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `resilience_issues` (
  `issueID` INT NOT NULL AUTO_INCREMENT ,
  `parentID` INT NULL DEFAULT NULL COMMENT 'if this issueID is a child of another issueID, that issueID will appear here, otherwise NULL' ,
  `resilience_issue` VARCHAR(255) NULL DEFAULT NULL ,
  `rootcause_theory` MEDIUMTEXT NULL DEFAULT NULL ,
  `cost_per_person` FLOAT(10,2) NULL DEFAULT NULL ,
  PRIMARY KEY (`issueID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `intervention_types`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `intervention_types` (
  `interventionID` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  `description` VARCHAR(45) NULL DEFAULT NULL ,
  `method` VARCHAR(45) NULL DEFAULT NULL ,
  `cost_per_user` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`interventionID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `ip_address` VARBINARY(16) NOT NULL ,
  `username` VARCHAR(100) NOT NULL ,
  `password` VARCHAR(80) NOT NULL ,
  `salt` VARCHAR(40) NULL DEFAULT NULL ,
  `email` VARCHAR(100) NOT NULL ,
  `activation_code` VARCHAR(40) NULL DEFAULT NULL ,
  `forgotten_password_code` VARCHAR(40) NULL DEFAULT NULL ,
  `forgotten_password_time` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `remember_code` VARCHAR(40) NULL DEFAULT NULL ,
  `created_on` INT(11) UNSIGNED NOT NULL ,
  `last_login` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `active` TINYINT(1) UNSIGNED NULL DEFAULT NULL ,
  `providerID_users` INT NULL DEFAULT NULL ,
  `first_name` VARCHAR(50) NULL DEFAULT NULL ,
  `last_name` VARCHAR(50) NULL DEFAULT NULL ,
  `phone` VARCHAR(20) NULL DEFAULT NULL ,
  `street` VARCHAR(45) NULL DEFAULT NULL ,
  `city` VARCHAR(45) NULL DEFAULT NULL ,
  `state` VARCHAR(45) NULL DEFAULT NULL ,
  `zipcode` CHAR(5) NULL DEFAULT NULL ,
  `has_car` TINYINT(1) NULL DEFAULT NULL ,
  `profilephoto_filepath` VARCHAR(255) NULL DEFAULT NULL ,
  `about_me` VARCHAR(1000) NULL DEFAULT NULL ,
  `lat_user` FLOAT(10,6) NULL DEFAULT NULL ,
  `long_user` FLOAT(10,6) NULL DEFAULT NULL ,
  `title` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `providerID_users_idx` (`providerID_users` ASC) ,
  CONSTRAINT `providerID_users`
    FOREIGN KEY (`providerID_users` )
    REFERENCES `providers` (`providerID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `messages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `messages` (
  `messageID` INT NOT NULL AUTO_INCREMENT ,
  `userID_messages` INT(11) UNSIGNED NOT NULL ,
  `primary_id` INT NULL ,
  `privatemsg_userID` INT(11) NULL DEFAULT NULL COMMENT 'If this is a private message, the memberID of the recipient will be here.' ,
  `replyTo_messageID` INT NULL DEFAULT NULL COMMENT 'Holds the pre-exisiting messageID that this current messageID is in reply to' ,
  `message` VARCHAR(500) NULL DEFAULT NULL ,
  `importance_counter` INT NULL DEFAULT NULL ,
  `photo_filepath` VARCHAR(100) NULL DEFAULT NULL ,
  `messagedate` TIMESTAMP NULL DEFAULT NOW() ,
  PRIMARY KEY (`messageID`) ,
  INDEX `userID_messages_idx` (`userID_messages` ASC) ,
  INDEX `primary_id_messages_idx` (`primary_id` ASC) ,
  CONSTRAINT `userID_messages`
    FOREIGN KEY (`userID_messages` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `primary_id_messages`
    FOREIGN KEY (`primary_id` )
    REFERENCES `primary_area` (`primary_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `provider_logs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `provider_logs` (
  `logID` INT NOT NULL AUTO_INCREMENT ,
  `userID_providelogs` INT(11) UNSIGNED NOT NULL ,
  `messageID` INT NOT NULL ,
  `heatmapID` INT NOT NULL ,
  `interventionID` INT NOT NULL ,
  `resourceID_needed` INT NOT NULL ,
  `resources_needed` INT NULL DEFAULT NULL ,
  `follow_up` TINYINT(1) NULL DEFAULT NULL ,
  `follow_date` DATETIME NULL DEFAULT NULL ,
  `view_access_level` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`logID`) ,
  INDEX `heatmapID_provLog_idx` (`heatmapID` ASC) ,
  INDEX `interventionID_idx` (`interventionID` ASC) ,
  INDEX `messageID_idx` (`messageID` ASC) ,
  INDEX `resourceID_needed_idx` (`resourceID_needed` ASC) ,
  INDEX `userID_providerlogs_idx` (`userID_providelogs` ASC) ,
  CONSTRAINT `heatmapID`
    FOREIGN KEY (`heatmapID` )
    REFERENCES `heat_maps` (`heatmapID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `resourceID_needed`
    FOREIGN KEY (`resourceID_needed` )
    REFERENCES `resources` (`resourceID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `interventionID`
    FOREIGN KEY (`interventionID` )
    REFERENCES `intervention_types` (`interventionID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `messageID`
    FOREIGN KEY (`messageID` )
    REFERENCES `messages` (`messageID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `userID_providerlogs`
    FOREIGN KEY (`userID_providelogs` )
    REFERENCES `users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `service_areas`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `service_areas` (
  `serviceareaID` INT NOT NULL AUTO_INCREMENT ,
  `service_area_name` VARCHAR(45) NULL DEFAULT NULL ,
  `city` VARCHAR(45) NULL DEFAULT NULL ,
  `state` VARCHAR(45) NULL DEFAULT NULL ,
  `zipcode` CHAR(5) NULL DEFAULT NULL ,
  PRIMARY KEY (`serviceareaID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `provider_specialties`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `provider_specialties` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `providerID_specialties` INT NULL DEFAULT NULL ,
  `issueID_specialties` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `providerID_specialties_idx` (`providerID_specialties` ASC) ,
  INDEX `issueID_specialties_idx` (`issueID_specialties` ASC) ,
  CONSTRAINT `providerID_specialties`
    FOREIGN KEY (`providerID_specialties` )
    REFERENCES `providers` (`providerID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `issueID_specialties`
    FOREIGN KEY (`issueID_specialties` )
    REFERENCES `resilience_issues` (`issueID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `groups`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `groups` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) NOT NULL ,
  `description` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `users_groups`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `users_groups` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) UNSIGNED NOT NULL ,
  `group_id` MEDIUMINT(8) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_users_groups_users1_idx` (`user_id` ASC) ,
  INDEX `fk_users_groups_groups1_idx` (`group_id` ASC) ,
  UNIQUE INDEX `uc_users_groups` (`user_id` ASC, `group_id` ASC) ,
  CONSTRAINT `fk_users_groups_users1`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_groups1`
    FOREIGN KEY (`group_id` )
    REFERENCES `groups` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `login_attempts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `login_attempts` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `ip_address` VARBINARY(16) NOT NULL ,
  `login` VARCHAR(100) NOT NULL ,
  `time` INT(11) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `message_concerns`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `message_concerns` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `messageID_concerns` INT NULL DEFAULT NULL ,
  `issueID_concerns` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `messageID_concerns_idx` (`messageID_concerns` ASC) ,
  INDEX `issueID_concerns_idx` (`issueID_concerns` ASC) ,
  CONSTRAINT `messageID_concerns`
    FOREIGN KEY (`messageID_concerns` )
    REFERENCES `messages` (`messageID` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `issueID_concerns`
    FOREIGN KEY (`issueID_concerns` )
    REFERENCES `resilience_issues` (`issueID` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `provider_service_areas`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `provider_service_areas` (
  `prov_serviceID` INT NOT NULL AUTO_INCREMENT ,
  `providerID_serviceareas` INT NULL DEFAULT NULL ,
  `serviceareaID_serviceareas` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`prov_serviceID`) ,
  INDEX `providerID_serviceareas_idx` (`providerID_serviceareas` ASC) ,
  INDEX `serviceareaID_serviceareas_idx` (`serviceareaID_serviceareas` ASC) ,
  CONSTRAINT `providerID_serviceareas`
    FOREIGN KEY (`providerID_serviceareas` )
    REFERENCES `providers` (`providerID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `serviceareaID_serviceareas`
    FOREIGN KEY (`serviceareaID_serviceareas` )
    REFERENCES `service_areas` (`serviceareaID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `events`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `events` (
  `eventID` INT NOT NULL AUTO_INCREMENT ,
  `messageID_events` INT NULL DEFAULT NULL ,
  `googlecalendar_id` VARCHAR(80) NULL DEFAULT NULL ,
  `summary` VARCHAR(45) NULL DEFAULT NULL ,
  `calendar_date` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`eventID`) ,
  INDEX `messageID_events_idx` (`messageID_events` ASC) ,
  CONSTRAINT `messageID_events`
    FOREIGN KEY (`messageID_events` )
    REFERENCES `messages` (`messageID` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tagged_users_msg`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tagged_users_msg` (
  `idtagged_msgs` INT NOT NULL ,
  `messageID_tagged` INT NOT NULL ,
  `userID` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`idtagged_msgs`) ,
  INDEX `messageID_tagged_msgs_idx` (`messageID_tagged` ASC) ,
  INDEX `userID_tagged_msgs_idx` (`userID` ASC) ,
  CONSTRAINT `messageID_tagged_msgs`
    FOREIGN KEY (`messageID_tagged` )
    REFERENCES `messages` (`messageID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `userID_tagged_msgs`
    FOREIGN KEY (`userID` )
    REFERENCES `users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `area_calendars`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `area_calendars` (
  `calendar_id` INT NOT NULL AUTO_INCREMENT ,
  `primary_area_id` INT NOT NULL ,
  `calendar_source` VARCHAR(75) NULL COMMENT 'google calendar address' ,
  PRIMARY KEY (`calendar_id`) ,
  INDEX `primary_area_calendar_idx` (`primary_area_id` ASC) ,
  CONSTRAINT `primary_area_calendar`
    FOREIGN KEY (`primary_area_id` )
    REFERENCES `primary_area` (`primary_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



INSERT INTO `primary_area` (`primary_id`,`primary_area`) VALUES 
(1,'Far North Side'),
(2,'Northwest Side'),
(3,'North Side'),
(4,'West Side'),
(5,'Near North - Loop - Near South'),
(6,'South Side'),
(7,'Southwest Side'),
(8,'Far Southwest Side'),
(9, 'Far Southeast Side')
;

INSERT INTO `area_calendars` (`calendar_id`,`primary_area_id`,`calendar_source`) VALUES 
(1,1,'mdpriaqug0spnrsdg1051vjs64@group.calendar.google.com'),
(2,2,'73i3afic9p9q0l0bv60eihagok@group.calendar.google.com'),
(3,3,'n3m8beuf8doej5sakqr8aphj2s@group.calendar.google.com'),
(4,4,'ngt5nedpe110254snaahn1davg@group.calendar.google.com'),
(5,5,'piclakcm4ppoms7cmhjh39p0tg@group.calendar.google.com'),
(6,6,'krplm6j4k8qmmc2600vg9f1sn4@group.calendar.google.com'),
(7,7,'resilience.chicago@gmail.com'),
(8,8,'sfun75a3gf2kbo9ckc5hgsonko@group.calendar.google.com'),
(9,9,'atsk74ckos16su5bep4h1qae0o@group.calendar.google.com')
;

INSERT INTO `service_areas` (`serviceareaID`, `service_area_name`, `city`, `state`, `zipcode`) VALUES
(1, 'Ashburn', NULL, NULL, '60652'),
(2, 'Avalon Park', NULL, NULL, '60619'),
(3, 'Avondale', NULL, NULL, '60618'),
(4, 'Back of the Yards', NULL, NULL, '60609'),
(5, 'Bridgeport', NULL, NULL, '60608'),
(6, 'Brighton Park', NULL, NULL, '60632'),
(7, 'Bronzeville', NULL, NULL, '60653'),
(8, 'Bucktown', NULL, NULL, '60647'),
(9, 'Burnside', NULL, NULL, '60619'),
(10, 'Cabrini Green', NULL, NULL, '60610'),
(11, 'Calumet Heights', NULL, NULL, '60617'),
(12, 'Chatham', NULL, NULL, '60619'),
(13, 'Chicago Lawn', NULL, NULL, '60629'),
(14, 'Chinatown', NULL, NULL, '60616'),
(15, 'Clearing', NULL, NULL, '60638'),
(16, 'Cottage Grove Heights', NULL, NULL, '60628'),
(17, 'East Chicago', NULL, NULL, '60617'),
(18, 'East Garfield Park', NULL, NULL, '60624'),
(19, 'East Rogers Park', NULL, NULL, '60626'),
(20, 'Englewood', NULL, NULL, '60621'),
(21, 'Ford City', NULL, NULL, '60629'),
(22, 'Gage Park', NULL, NULL, '60632'),
(23, 'Garfield Ridge', NULL, NULL, '60638'),
(24, 'Gresham', NULL, NULL, '60620'),
(25, 'Homan Square', NULL, NULL, '60624'),
(26, 'Humboldt Park', NULL, NULL, '60651'),
(27, 'Hyde Park', NULL, NULL, '60615'),
(28, 'Kenwood', NULL, NULL, '60615'),
(29, 'Logan Square', NULL, NULL, '60647'),
(30, 'McKinley Park', NULL, NULL, '60608'),
(31, 'North Austin', NULL, NULL, '60639'),
(32, 'Pilsen', NULL, NULL, '60608'),
(33, 'South Austin', NULL, NULL, '60644'),
(34, 'South Chicago', NULL, NULL, '60617'),
(35, 'Tri-Taylor', NULL, NULL, '60612'),
(36, 'Ukranian Village', NULL, NULL, '60622'),
(37, 'Union Ridge', NULL, NULL, '60656'),
(38, 'United Center Park', NULL, NULL, '60612'),
(39, 'University Village - Little Italy', NULL, NULL, '60607'),
(40, 'West Englewood', NULL, NULL, '60636'),
(41, 'West Garfield Park', NULL, NULL, '60624'),
(42, 'West Humboldt Park', NULL, NULL, '60651'),
(43, 'Westlawn', NULL, NULL, '60629'),
(44, 'Woodlawn', NULL, NULL, '60637'),
(45, 'Albany Park',NULL, NULL, '60625'),
(46, 'Austin',NULL,NULL,'60644'),
(47, 'Greater Grand Crossing', NULL,NULL,'60637'),
(48, 'North Lawndale', NULL, NULL, '60624'),
(49, 'Roseland', NULL, NULL, '60619'),
(52, 'West Pullman', NULL, NULL, '60628'),
(53, 'Andersonville',NULL,NULL,'60625'),
(54, 'Archer Heights',NULL,NULL,'60632'),
(56, 'Belmont Central',NULL,NULL,'60639'),
(57, 'Beverly',NULL,NULL,'60620'),
(59, 'Cragin',NULL,NULL,'60641'),
(60, 'DePaul',NULL,NULL,'60614'),
(61, 'Douglas',NULL,NULL,'60616'),
(62, 'Edgebrook',NULL,NULL,'60646'),
(63, 'Edgewater',NULL,NULL,'60640'),
(64, 'Edison Park',NULL,NULL,'60631'),
(65, 'Forest Glen',NULL,NULL,'60630'),
(66, 'Galewood',NULL,NULL,'60607'),
(67, 'Gold Coast',NULL,NULL,'60610'),
(68, 'Hegewisch',NULL,NULL,'60633'),
(69, 'Hermosa',NULL,NULL,'60639'),
(70, 'Irving Park',NULL,NULL,'60618'),
(71, 'Jefferson Park',NULL,NULL,'60630'),
(72, 'Kilbourn Park',NULL,NULL,'60641'),
(73, 'Lakeview',NULL,NULL,'60657'),
(74, 'Lawndale',NULL,NULL,'60623'),
(75, 'Le Claire Courts',NULL,NULL,'60639'),
(76, 'Lincoln Park',NULL,NULL,'60614'),
(77, 'Lincoln Square',NULL,NULL,'60625'),
(78, 'Little Village',NULL,NULL,'60623'),
(79, 'Medical District',NULL,NULL,'60612'),
(80, 'Midway',NULL,NULL,'60638'),
(81, 'Mount Greenwood',NULL,NULL,'60655'),
(82, 'Oakland',NULL,NULL,'60653'),
(83, 'Portage Park',NULL,NULL,'60634'),
(84, 'Prarie District',NULL,NULL,'60615'),
(85, 'Pullman District',NULL,NULL,'60628'),
(86, 'Printers Row', NULL,NULL,'60605'),
(87, 'Ravenswood',NULL,NULL,'60640'),
(88, 'Roscoe Village',NULL,NULL,'60618'),
(89, 'Roseland',NULL,NULL,'60628'),
(90, 'Sauganash',NULL,NULL,'60646'),
(91, 'Scottsdale',NULL,NULL,'60652'),
(92, 'South Loop',NULL,NULL,'60605'),
(93, 'South Shore',NULL,NULL,'60649'),
(94, 'Uptown',NULL,NULL,'60640'),
(95, 'Wicker Park', NULL,NULL,'60622'),
(96, 'Wildwood',NULL,NULL,'60646'),
(97, 'Wrightwood',NULL,NULL,'60652'),
(98, 'Wrigleyville',NULL,NULL,'60613'),
(99, 'Morgan Park', NULL,NULL,'60643'),
(100,'Marquette Park',NULL,NULL,'60629');


INSERT INTO `resilience_issues` (`issueID`, `parentID`, `resilience_issue`, `rootcause_theory`, `cost_per_person`) VALUES
(1, NULL, 'Health Services', NULL, NULL),
(2, NULL, 'Jobs & Economy', NULL, NULL),
(3, NULL, 'Place Improvement', NULL, NULL),
(4, NULL, 'Healthy Food', NULL, NULL),
(5, NULL, 'Safety', NULL, NULL),
(6, NULL, 'Education', NULL, NULL),
(8, NULL, 'Skill Training', NULL, NULL),
(9, NULL, 'Youth Services', NULL, NULL)
 ;

INSERT INTO `providers` (`providerID`, `name_ofc`, `email_ofc`, `street_ofc`, `city_ofc`, `state_ofc`, `zipcode_ofc`, `country_ofc`, `phone_ofc`, `philosophy`,provider_active) VALUES
(1, 'Teamwork Englewood', NULL, '815 W. 63rd Street', 'Chicago', 'IL', '60621', NULL, '7734886600', 'To unite the many organizations serving Englewood residents and work toward the common goal of building a stronger community.',1),
(2, 'R.A.G.E','joinrage@gmail.com', '1122-24 W. 61st Street, P.O. Box 21052', 'Chicago', 'IL', '60621', NULL, '8668451032',  'The mission of the Resident Association of Greater Englewood is to mobilize people and resources to force a change in the community by breaking down barriers in communication and promoting positivity through solution-based approaches.',1),
(3, 'Growing Home', NULL, '2732 N. Clark St. (Ste. 310)', 'Chicago', 'IL', '60614', NULL, '7735491336', 'Growing Home\'s mission is to operate, promote, and demonstrate organic agriculture by providing job training, employment, and community development.',1),
(4, 'A Safe Haven', NULL, '2750 W Roosevelt Rd', 'Chicago', 'IL', '60608', NULL, '7734358300', 'A Safe Haven empowers families and individuals experiencing homelessness or crisis to achieve sustainable self-sufficiency.',1),
(5, 'IMAN', 'Info@imancentral.org', '2744 W 63rd St', 'Chicago', 'IL', '60629', NULL, '7734344626', 'The Inner-City Muslim Action Network (IMAN) is a community-based nonprofit that works for social justice, delivers a range of direct services, and cultivates the arts in urban communities.',1),
(6, 'Englewood Food Network', 'dennis.ware@englewoodfoodnetwork.com', '1406 West 64th Street', 'Chicago', 'IL', '60636', NULL, '7737783060', 'To serve the community by feeding the hungry and providing programs and services designed to promote healthy living.',1);

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
     (1,'admin','Administrator'),
     (2,'members','General User'),
     (3,'superuser','SuperUser');


INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `phone`,`profilephoto_filepath`) VALUES
     ('1',0x7f000001,'administrator','59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4','9462e8eee0','a.candelario@gmail.com','',NULL,'1268889823','1268889823','1', 'Web App','Admin','','admin.jpg');


INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
     (1,1,1),
     (2,1,2),
     (3,1,3);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CH