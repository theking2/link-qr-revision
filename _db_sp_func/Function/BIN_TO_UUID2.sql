DROP Function IF EXISTS `BIN_TO_UUID2`;
DELIMITER $$
CREATE DEFINER=`link-qr`@`localhost` FUNCTION `BIN_TO_UUID2`(b binary(16)) RETURNS char(36) CHARSET ascii COLLATE ascii_general_ci
BEGIN
   DECLARE hexStr CHAR(32);
   SET hexStr = HEX(b);
   RETURN LOWER(CONCAT(
        SUBSTR(hexStr, 25, 12), '-',
        SUBSTR(hexStr, 21, 4), '-',
        SUBSTR(hexStr, 13, 4), '-',
        SUBSTR(hexStr, 17, 4), '-',
        SUBSTR(hexStr, 1, 12)
    ));
END$$
DELIMITER ;
