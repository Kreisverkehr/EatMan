CREATE TABLE `EatMan`.`tags` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_bin;

CREATE TABLE `EatMan`.`dishes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(500) NULL,
  `description` LONGTEXT NULL,
  `recipe_location` VARCHAR(1000) NULL,
  `cooking_time` DECIMAL(5,1) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_bin;

CREATE TABLE `EatMan`.`dish_tag` (
  `dish` INT NOT NULL,
  `tag` INT NOT NULL,
  PRIMARY KEY (`dish`, `tag`),
  INDEX `fk_tag_idx` (`tag` ASC) VISIBLE,
  CONSTRAINT `fk_dish`
    FOREIGN KEY (`dish`)
    REFERENCES `EatMan`.`dishes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tag`
    FOREIGN KEY (`tag`)
    REFERENCES `EatMan`.`tags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_bin;