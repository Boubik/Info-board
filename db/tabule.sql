-- MySQL Script generated by MySQL Workbench
-- Wed Apr  3 17:02:18 2019
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema tabule
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema tabule
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `tabule` DEFAULT CHARACTER SET utf8 ;
USE `tabule` ;

-- -----------------------------------------------------
-- Table `tabule`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tabule`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `usr` VARCHAR(50) NOT NULL,
  `pass` VARCHAR(250) NOT NULL,
  `created` DATE NOT NULL,
  `lastlogin` DATE NOT NULL,
  `active` TINYINT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;