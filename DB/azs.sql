CREATE SCHEMA IF NOT EXISTS `azs` DEFAULT CHARACTER SET utf8 ;
USE `azs` ;
-- -----------------------------------------------------
-- Table `azs`.`branch`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `azs`.`branch` (
  `id_branch` INT(10) UNSIGNED NOT NULL,
  `address_branch` VARCHAR(45) NOT NULL,
  `phone_branch` VARCHAR(45) NOT NULL,
  `quantity_workers_branch` INT(11) NOT NULL,
  `quantity_trk_branch` INT(11) NOT NULL,
  `schedule_branch` DATE NOT NULL,
  `open_branch` INT(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_branch`),
  UNIQUE INDEX `idbrunch_UNIQUE` (`id_branch` ASC) VISIBLE,
  UNIQUE INDEX `address_brunch_UNIQUE` (`address_branch` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
-- -----------------------------------------------------
-- Table `azs`.`supplier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `azs`.`supplier` (
  `id_supplier` INT(10) UNSIGNED NOT NULL,
  `name_supplier` VARCHAR(45) NOT NULL,
  `address_supplier` VARCHAR(50) NOT NULL,
  `phone_supplier` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_supplier`),
  UNIQUE INDEX `id_supplier_UNIQUE` (`id_supplier` ASC) VISIBLE,
  UNIQUE INDEX `phone_supplier_UNIQUE` (`phone_supplier` ASC) VISIBLE,
  UNIQUE INDEX `address_supplier_UNIQUE` (`address_supplier` ASC) VISIBLE,
  UNIQUE INDEX `name_supplier_UNIQUE` (`name_supplier` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
-- -----------------------------------------------------
-- Table `azs`.`fuel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `azs`.`fuel` (
  `id_fuel` INT(10) UNSIGNED NOT NULL,
  `type_fuel` VARCHAR(45) NOT NULL,
  `price_fuel` DECIMAL(5,2) NULL DEFAULT NULL,
  `supplier_fuel` VARCHAR(45) NOT NULL,
  `price_fuel_suppl` DECIMAL(5,2) NOT NULL,
  `margin_fuel` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id_fuel`),
  UNIQUE INDEX `idfuel_UNIQUE` (`id_fuel` ASC) VISIBLE,
  UNIQUE INDEX `type_fuel_UNIQUE` (`type_fuel` ASC) VISIBLE,
  INDEX `fk_supplier_fuel` (`supplier_fuel` ASC) VISIBLE,
  CONSTRAINT `fk_supplier_fuel`
    FOREIGN KEY (`supplier_fuel`)
    REFERENCES `azs`.`supplier` (`name_supplier`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
-- -----------------------------------------------------
-- Table `azs`.`clients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `azs`.`clients` (
  `id_clients` INT(10) UNSIGNED NOT NULL,
  `name_clients` VARCHAR(45) NOT NULL,
  `surname_clients` VARCHAR(45) NOT NULL,
  `middlename_clients` VARCHAR(45) NOT NULL,
  `phone_clients` VARCHAR(45) NULL DEFAULT NULL,
  `pass_clients` VARCHAR(50) NOT NULL,
  `address_clients` VARCHAR(45) NULL DEFAULT NULL,
  `birthdate_clients` DATE NULL DEFAULT NULL,
  `fuel_client` VARCHAR(48) NULL DEFAULT NULL,
  PRIMARY KEY (`id_clients`),
  UNIQUE INDEX `idclients_UNIQUE` (`id_clients` ASC) VISIBLE,
  INDEX `fk_fuel_client_idx` (`fuel_client` ASC) VISIBLE,
  CONSTRAINT `clients_ibfk_1`
    FOREIGN KEY (`fuel_client`)
    REFERENCES `azs`.`fuel` (`type_fuel`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
-- -----------------------------------------------------
-- Table `azs`.`leftover`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `azs`.`leftover` (
  `id_leftover` INT(10) UNSIGNED NOT NULL,
  `branch_leftover` INT(10) UNSIGNED NOT NULL,
  `fuel_leftover` VARCHAR(45) NOT NULL,
  `quantity_leftover` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_leftover`),
  UNIQUE INDEX `idleftover_UNIQUE` (`id_leftover` ASC) VISIBLE,
  INDEX `fk_branch_leftover_idx` (`branch_leftover` ASC) VISIBLE,
  INDEX `fk_fuel_leftover` (`fuel_leftover` ASC) VISIBLE,
  CONSTRAINT `fk_branch_leftover`
    FOREIGN KEY (`branch_leftover`)
    REFERENCES `azs`.`branch` (`id_branch`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_fuel_leftover`
    FOREIGN KEY (`fuel_leftover`)
    REFERENCES `azs`.`fuel` (`type_fuel`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
-- -----------------------------------------------------
-- Table `azs`.`position`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `azs`.`position` (
  `id_position` INT(10) UNSIGNED NOT NULL,
  `name_position` VARCHAR(45) NOT NULL,
  `salary_position` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_position`),
  UNIQUE INDEX `name_position_UNIQUE` (`name_position` ASC) VISIBLE,
  UNIQUE INDEX `id_position_UNIQUE` (`id_position` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
-- -----------------------------------------------------
-- Table `azs`.`sales`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `azs`.`sales` (
  `id_sales` INT(10) UNSIGNED NOT NULL,
  `fuel_sales` VARCHAR(45) NOT NULL,
  `branch_sales` INT(10) UNSIGNED NOT NULL,
  `volume_sales` VARCHAR(45) NOT NULL,
  `date_sales` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_sales`),
  UNIQUE INDEX `id_sales_UNIQUE` (`id_sales` ASC) VISIBLE,
  INDEX `fk_branch_sales_idx` (`branch_sales` ASC) VISIBLE,
  INDEX `fk_fuel_sales` (`fuel_sales` ASC) VISIBLE,
  CONSTRAINT `fk_branch_sales`
    FOREIGN KEY (`branch_sales`)
    REFERENCES `azs`.`branch` (`id_branch`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_fuel_sales`
    FOREIGN KEY (`fuel_sales`)
    REFERENCES `azs`.`fuel` (`type_fuel`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
-- -----------------------------------------------------
-- Table `azs`.`workers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `azs`.`workers` (
  `id_workers` INT(10) UNSIGNED NOT NULL,
  `pass_workers` VARCHAR(50) NOT NULL,
  `name_workers` VARCHAR(45) NOT NULL,
  `surname_workers` VARCHAR(45) NOT NULL,
  `middlename_workers` VARCHAR(45) NOT NULL,
  `address_workers` VARCHAR(45) NOT NULL,
  `phone_workers` VARCHAR(45) NOT NULL,
  `birthday_workers` DATE NOT NULL,
  `branch_workers` INT(10) UNSIGNED NOT NULL,
  `position_workers` VARCHAR(45) NOT NULL,
  `exp_workers` VARCHAR(45) NOT NULL,
  PRIMARY KEY USING BTREE (`id_workers`),
  UNIQUE INDEX `idworkers_UNIQUE` (`id_workers` ASC) VISIBLE,
  UNIQUE INDEX `phone_workers_UNIQUE` (`phone_workers` ASC) VISIBLE,
  INDEX `fk_branch_workers_idx` (`branch_workers` ASC) VISIBLE,
  INDEX `fk_position_workers` (`position_workers` ASC) VISIBLE,
  CONSTRAINT `fk_branch_workers`
    FOREIGN KEY (`branch_workers`)
    REFERENCES `azs`.`branch` (`id_branch`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_position_workers`
    FOREIGN KEY (`position_workers`)
    REFERENCES `azs`.`position` (`name_position`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

