DROP Function IF EXISTS `BIN_TO_UUID`;
DELIMITER $$
CREATE DEFINER=`link-qr`@`localhost` FUNCTION `BIN_TO_UUID`(b BINARY(16)) RETURNS char(36) CHARSET utf8mb4 COLLATE utf8mb4_unicode_520_ci
BEGIN
   DECLARE hexStr CHAR(32);
   SET hexStr = HEX(b);
   RETURN LOWER(CONCAT(
        SUBSTR(hexStr, 9, 8), '-',
        SUBSTR(hexStr, 5, 4), '-',
        SUBSTR(hexStr, 13, 4), '-',
        SUBSTR(hexStr, 17, 4), '-',
        SUBSTR(hexStr, 21)
    ));
END$$
DELIMITER ;
