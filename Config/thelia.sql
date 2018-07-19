
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- sponsorship
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `sponsorship`;

CREATE TABLE `sponsorship`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Sponsorship Id',
    `sponsor_id` INTEGER NOT NULL,
    `beneficiary_id` INTEGER,
    `code` VARCHAR(45) NOT NULL COMMENT 'The invitation code',
    `beneficiary_email` VARCHAR(128) NOT NULL COMMENT 'Beneficiary E-Mail Address',
    `beneficiary_firstname` VARCHAR(255) COMMENT 'Beneficiary firstname',
    `beneficiary_lastname` VARCHAR(255) COMMENT 'Beneficiary lastname',
    `status` INTEGER NOT NULL COMMENT 'Sponsorship Status',
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `sponsorship_UNIQUE` (`sponsor_id`, `beneficiary_email`),
    UNIQUE INDEX `beneficiary_id_UNIQUE` (`sponsor_id`, `beneficiary_id`),
    UNIQUE INDEX `sponsorship_code_UNIQUE` (`code`),
    INDEX `FI_sponsorship_beneficiary_id` (`beneficiary_id`),
    INDEX `FI_sponsorship_status_id` (`status`),
    CONSTRAINT `fk_sponsorship_beneficiary_id`
        FOREIGN KEY (`beneficiary_id`)
        REFERENCES `customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `fk_sponsorship_customer_id`
        FOREIGN KEY (`sponsor_id`)
        REFERENCES `customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `fk_sponsorship_status_id`
        FOREIGN KEY (`status`)
        REFERENCES `sponsorship_status` (`id`)
) ENGINE=InnoDB COMMENT='Sponsorship Table';

-- ---------------------------------------------------------------------
-- sponsorship_status
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `sponsorship_status`;

CREATE TABLE `sponsorship_status`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT COMMENT 'Sponsorship Status Id',
    `code` VARCHAR(45) NOT NULL COMMENT 'Sponsorship Status Code',
    `color` CHAR(7) COMMENT 'Sponsorship Status Color',
    `position` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `sponsorship_status_code_UNIQUE` (`code`)
) ENGINE=InnoDB COMMENT='Sponsorship Status';

-- ---------------------------------------------------------------------
-- sponsorship_status_i18n
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `sponsorship_status_i18n`;

CREATE TABLE `sponsorship_status_i18n`
(
    `id` INTEGER NOT NULL COMMENT 'Sponsorship Status Id',
    `locale` VARCHAR(5) DEFAULT 'en_US' NOT NULL,
    `title` VARCHAR(255),
    `description` LONGTEXT,
    PRIMARY KEY (`id`,`locale`),
    CONSTRAINT `sponsorship_status_i18n_FK_1`
        FOREIGN KEY (`id`)
        REFERENCES `sponsorship_status` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
