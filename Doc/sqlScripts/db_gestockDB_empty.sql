-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema GestockDB
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema GestockDB
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `GestockDB` DEFAULT CHARACTER SET utf8 ;
USE `GestockDB` ;

-- -----------------------------------------------------
-- Table `GestockDB`.`categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestockDB`.`categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `GestockDB`.`products`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestockDB`.`products` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `brand` VARCHAR(30) NOT NULL,
  `price` DOUBLE NOT NULL,
  `alertQuantity` INT NOT NULL,
  `imgName` VARCHAR(50) NOT NULL,
  `idCategory_fk` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idProduct_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `idCategory_products_idx` (`idCategory_fk` ASC),
  CONSTRAINT `idCategory_products`
    FOREIGN KEY (`idCategory_fk`)
    REFERENCES `GestockDB`.`categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `GestockDB`.`stocks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestockDB`.`stocks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `building` VARCHAR(20) NOT NULL,
  `shelf` CHAR(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `GestockDB`.`stocks_has_products`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestockDB`.`stocks_has_products` (
  `idStock_fk` INT NOT NULL,
  `idProduct_fk` INT NOT NULL,
  `quantity` INT NOT NULL,
  PRIMARY KEY (`idStock_fk`, `idProduct_fk`),
  INDEX `idProduct_stock_has_products_idx` (`idProduct_fk` ASC),
  CONSTRAINT `idStock_stock_has_products`
    FOREIGN KEY (`idStock_fk`)
    REFERENCES `GestockDB`.`stocks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `idProduct_stock_has_products`
    FOREIGN KEY (`idProduct_fk`)
    REFERENCES `GestockDB`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `GestockDB`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestockDB`.`roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `GestockDB`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestockDB`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` CHAR(40) NOT NULL,
  `money` DOUBLE NOT NULL,
  `idRole_fk` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  INDEX `idRole_users_idx` (`idRole_fk` ASC),
  CONSTRAINT `idRole_users`
    FOREIGN KEY (`idRole_fk`)
    REFERENCES `GestockDB`.`roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `GestockDB`.`carts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestockDB`.`carts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `idUser_fk` INT NOT NULL,
  `dateOrder` DATE NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `idUser_carts_idx` (`idUser_fk` ASC),
  CONSTRAINT `idUser_carts`
    FOREIGN KEY (`idUser_fk`)
    REFERENCES `GestockDB`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `GestockDB`.`carts_has_products`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GestockDB`.`carts_has_products` (
  `idCart_fk` INT NOT NULL,
  `idProduct_fk` INT NOT NULL,
  `quantity` INT NOT NULL,
  PRIMARY KEY (`idCart_fk`, `idProduct_fk`),
  INDEX `idProduct_carts_has_products_idx` (`idProduct_fk` ASC),
  CONSTRAINT `idCart_carts_has_products`
    FOREIGN KEY (`idCart_fk`)
    REFERENCES `GestockDB`.`carts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `idProduct_carts_has_products`
    FOREIGN KEY (`idProduct_fk`)
    REFERENCES `GestockDB`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE USER 'gestockAdminDB'@'127.0.0.1' IDENTIFIED BY 'gestockTPI2017';
GRANT ALL PRIVILEGES ON `GestockDB`.* TO 'gestockAdminDB';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
