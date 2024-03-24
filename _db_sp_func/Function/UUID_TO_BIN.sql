DROP Function IF EXISTS `UUID_TO_BIN`;
DELIMITER $$
CREATE DEFINER=`link-qr`@`localhost` FUNCTION `UUID_TO_BIN`(uuid CHAR(36)) RETURNS binary(16)
BEGIN
	RETURN UNHEX( CONCAT( 
		SUBSTRING(uuid, 15, 4),
        SUBSTRING(uuid, 10, 4),
        SUBSTRING(uuid,  1, 8),
        SUBSTRING(uuid, 20, 4),
        SUBSTRING(uuid, 25)
        )
	);
END$$
DELIMITER ;
