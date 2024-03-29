DELIMITER $$

CREATE TRIGGER `stok_dinamis` 
AFTER UPDATE ON `alur_barang` 
FOR EACH ROW BEGIN IF (new.`status`="Diterima") 
THEN IF (new.`request`="Masuk") 
THEN UPDATE `item` 
SET `item`.`stok` = `item`.`stok` + new.`ubah_stok` 
WHERE `item`.`id_item` = new.`id_item`; 
ELSE UPDATE `item` 
SET `item`.`stok` = `item`.`stok` - new.`ubah_stok` 
WHERE `item`.`id_item` = new.`id_item`; 
END IF; 
END IF; 
END$$

DELIMITER ;