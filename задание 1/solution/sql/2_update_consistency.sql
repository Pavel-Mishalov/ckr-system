ALTER TABLE `groups` ENGINE=InnoDB;
ALTER TABLE `products` ENGINE=InnoDB;

ALTER TABLE `groups` MODIFY COLUMN `id_parent` INT(11) DEFAULT NULL;
ALTER TABLE `products` MODIFY COLUMN `id_group` INT(11) DEFAULT NULL;

UPDATE `groups` SET id_parent = NULL where id_parent = 0;
UPDATE `products` SET id_group = NULL where id_group = 0;

ALTER TABLE `groups`
    ADD CONSTRAINT `parent_relation`
        FOREIGN KEY (id_parent) REFERENCES `groups` (id)
            ON DELETE SET NULL;

ALTER TABLE `products`
    ADD CONSTRAINT `id_group`
        FOREIGN KEY (id_group) REFERENCES `groups` (id)
            ON DELETE SET NULL;