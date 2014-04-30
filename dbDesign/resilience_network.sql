SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `resilience_network` ;
CREATE SCHEMA IF NOT EXISTS `resilience_network` DEFAULT CHARACTER SET utf8 ;
USE `resilience_network` ;

-- -----------------------------------------------------
-- Table `resilience_network`.`Providers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`Providers` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`Providers` (
  `providerID` INT NOT NULL AUTO_INCREMENT ,
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
  `website_ofc` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`providerID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_network`.`Resources`
-- 
-- Resources like labor, office supplies, equipment, etc
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`Resources` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`Resources` (
  `resourceID` INT NOT NULL AUTO_INCREMENT ,
  `resource_description` VARCHAR(45) NULL DEFAULT NULL ,
  `initial_cost` FLOAT(10,2) NULL DEFAULT NULL ,
  `recurring_cost` FLOAT(10,2) NULL DEFAULT NULL ,
  `additional_cost` FLOAT(10,2) NULL DEFAULT NULL ,
  PRIMARY KEY (`resourceID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_network`.`Provider_Resources`
--
-- Which providers have which resources
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`Provider_Resources` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`Provider_Resources` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `resourceID` INT NULL DEFAULT NULL ,
  `providerID` INT NULL DEFAULT NULL ,
  `available_qty` INT NULL DEFAULT NULL ,
  `needed_qty` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `providerID_provResources_idx` (`providerID` ASC) ,
  INDEX `resourceID_provResources_idx` (`resourceID` ASC) ,
  CONSTRAINT `providerID`
    FOREIGN KEY (`providerID` )
    REFERENCES `resilience_network`.`Providers` (`providerID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `resourceID`
    FOREIGN KEY (`resourceID` )
    REFERENCES `resilience_network`.`Resources` (`resourceID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_network`.`Heat_Maps`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`Heat_Maps` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`Heat_Maps` (
  `heatmapID` INT NOT NULL AUTO_INCREMENT ,
  `lat_heat` FLOAT(10,6) NULL DEFAULT NULL ,
  `long_heat` FLOAT(10,6) NULL DEFAULT NULL ,
  `entry_date` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`heatmapID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_network`.`Resilience_Issues`
--
-- What issues exist, a succint theory of why they exist
-- and what the cost per affected person is by this 
-- particular "resilience" issue
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`Resilience_Issues` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`Resilience_Issues` (
  `issueID` INT NOT NULL AUTO_INCREMENT ,
  `parentID` INT NULL DEFAULT NULL COMMENT 'if this issueID is a child of another issueID, that issueID will appear here, otherwise NULL' ,
  `resilience_issue` VARCHAR(255) NULL DEFAULT NULL ,
  `rootcause_theory` MEDIUMTEXT NULL DEFAULT NULL ,
  `cost_per_person` FLOAT(10,2) NULL DEFAULT NULL ,
  PRIMARY KEY (`issueID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_network`.`Intervention_Types`
--
-- Types of resources/tools available to intervene in 
-- and help resolve a given resilience issue. Cost 
-- requirement per person.
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`Intervention_Types` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`Intervention_Types` (
  `interventionID` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  `description` VARCHAR(45) NULL DEFAULT NULL ,
  `method` VARCHAR(45) NULL DEFAULT NULL ,
  `cost_per_user` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`interventionID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_network`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`users` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`users` (
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
  `title` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) ,
  INDEX `providerID_users_idx` (`providerID_users` ASC) ,
  CONSTRAINT `providerID_users`
    FOREIGN KEY (`providerID_users` )
    REFERENCES `resilience_network`.`Providers` (`providerID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `resilience_network`.`Messages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`Messages` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`Messages` (
  `messageID` INT NOT NULL AUTO_INCREMENT ,
  `userID_messages` INT(11) UNSIGNED NOT NULL ,
  `privatemsg_userID` INT(11) NULL DEFAULT NULL COMMENT 'If this is a private message, the memberID of the recipient will be here.' ,
  `replyTo_messageID` INT NULL DEFAULT NULL COMMENT 'Holds the pre-exisiting messageID that this current messageID is in reply to' ,
  `message` VARCHAR(500) NULL DEFAULT NULL ,
  `importance_counter` INT NULL DEFAULT NULL ,
  `photo_filepath` VARCHAR(100) NULL DEFAULT NULL ,
  `messagedate` TIMESTAMP NULL DEFAULT NOW() ,
  PRIMARY KEY (`messageID`) ,
  INDEX `userID_messages_idx` (`userID_messages` ASC) ,
  CONSTRAINT `userID_messages`
    FOREIGN KEY (`userID_messages` )
    REFERENCES `resilience_network`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_network`.`Provider_Logs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`Provider_Logs` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`Provider_Logs` (
  `logID` INT NOT NULL AUTO_INCREMENT ,
  `userID_providelogs` INT(11) UNSIGNED NOT NULL ,
  `messageID` INT NULL DEFAULT NULL ,
  `heatmapID` INT NULL DEFAULT NULL ,
  `interventionID` INT NULL DEFAULT NULL ,
  `resourceID_needed` INT NULL DEFAULT NULL ,
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
    REFERENCES `resilience_network`.`Heat_Maps` (`heatmapID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `resourceID_needed`
    FOREIGN KEY (`resourceID_needed` )
    REFERENCES `resilience_network`.`Resources` (`resourceID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `interventionID`
    FOREIGN KEY (`interventionID` )
    REFERENCES `resilience_network`.`Intervention_Types` (`interventionID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `messageID`
    FOREIGN KEY (`messageID` )
    REFERENCES `resilience_network`.`Messages` (`messageID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `userID_providerlogs`
    FOREIGN KEY (`userID_providelogs` )
    REFERENCES `resilience_network`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_network`.`Service_Areas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`Service_Areas` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`Service_Areas` (
  `serviceareaID` INT NOT NULL AUTO_INCREMENT ,
  `service_area_name` VARCHAR(45) NULL DEFAULT NULL ,
  `city` VARCHAR(45) NULL DEFAULT NULL ,
  `state` VARCHAR(45) NULL DEFAULT NULL ,
  `zipcode` CHAR(5) NULL DEFAULT NULL ,
  PRIMARY KEY (`serviceareaID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_network`.`provider_specialties`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`provider_specialties` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`provider_specialties` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `providerID_specialties` INT NULL DEFAULT NULL ,
  `issueID_specialties` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `providerID_specialties_idx` (`providerID_specialties` ASC) ,
  INDEX `issueID_specialties_idx` (`issueID_specialties` ASC) ,
  CONSTRAINT `providerID_specialties`
    FOREIGN KEY (`providerID_specialties` )
    REFERENCES `resilience_network`.`Providers` (`providerID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `issueID_specialties`
    FOREIGN KEY (`issueID_specialties` )
    REFERENCES `resilience_network`.`Resilience_Issues` (`issueID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_network`.`groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`groups` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`groups` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) NOT NULL ,
  `description` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `resilience_network`.`users_groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`users_groups` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`users_groups` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) UNSIGNED NOT NULL ,
  `group_id` MEDIUMINT(8) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_users_groups_users1_idx` (`user_id` ASC) ,
  INDEX `fk_users_groups_groups1_idx` (`group_id` ASC) ,
  UNIQUE INDEX `uc_users_groups` (`user_id` ASC, `group_id` ASC) ,
  CONSTRAINT `fk_users_groups_users1`
    FOREIGN KEY (`user_id` )
    REFERENCES `resilience_network`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_groups1`
    FOREIGN KEY (`group_id` )
    REFERENCES `resilience_network`.`groups` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `resilience_network`.`login_attempts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`login_attempts` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`login_attempts` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `ip_address` VARBINARY(16) NOT NULL ,
  `login` VARCHAR(100) NOT NULL ,
  `time` INT(11) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `resilience_network`.`message_concerns`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`message_concerns` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`message_concerns` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `messageID_concerns` INT NULL DEFAULT NULL ,
  `issueID_concerns` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `messageID_concerns_idx` (`messageID_concerns` ASC) ,
  INDEX `issueID_concerns_idx` (`issueID_concerns` ASC) ,
  CONSTRAINT `messageID_concerns`
    FOREIGN KEY (`messageID_concerns` )
    REFERENCES `resilience_network`.`Messages` (`messageID` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `issueID_concerns`
    FOREIGN KEY (`issueID_concerns` )
    REFERENCES `resilience_network`.`Resilience_Issues` (`issueID` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_network`.`provider_service_areas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`provider_service_areas` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`provider_service_areas` (
  `prov_serviceID` INT NOT NULL AUTO_INCREMENT ,
  `providerID_serviceareas` INT NULL DEFAULT NULL ,
  `serviceareaID_serviceareas` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`prov_serviceID`) ,
  INDEX `providerID_serviceareas_idx` (`providerID_serviceareas` ASC) ,
  INDEX `serviceareaID_serviceareas_idx` (`serviceareaID_serviceareas` ASC) ,
  CONSTRAINT `providerID_serviceareas`
    FOREIGN KEY (`providerID_serviceareas` )
    REFERENCES `resilience_network`.`Providers` (`providerID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `serviceareaID_serviceareas`
    FOREIGN KEY (`serviceareaID_serviceareas` )
    REFERENCES `resilience_network`.`Service_Areas` (`serviceareaID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `resilience_network`.`events`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `resilience_network`.`events` ;

CREATE  TABLE IF NOT EXISTS `resilience_network`.`events` (
  `eventID` INT NOT NULL AUTO_INCREMENT ,
  `messageID_events` INT NULL ,
  `googlecalendar_id` VARCHAR(80) NULL ,
  `summary` VARCHAR(45) NULL ,
  `calendar_date` DATETIME NULL ,
  PRIMARY KEY (`eventID`) ,
  INDEX `messageID_events_idx` (`messageID_events` ASC) ,
  CONSTRAINT `messageID_events`
    FOREIGN KEY (`messageID_events` )
    REFERENCES `resilience_network`.`Messages` (`messageID` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

USE `resilience_network` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
