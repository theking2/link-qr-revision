DROP Function IF EXISTS `UUID_TO_BIN2`;
DELIMITER $$
CREATE DEFINER=`link-qr`@`localhost` FUNCTION `UUID_TO_BIN2`(uuid char(36)) RETURNS binary(16)
BEGIN
	RETURN UNHEX( CONCAT( 
		SUBSTRING(uuid, 25, 12),
        SUBSTRING(uuid, 20, 4),
        SUBSTRING(uuid, 15, 4),
        SUBSTRING(uuid, 10, 4),
        SUBSTRING(uuid,  1, 8)
        )
	);
END$$
DELIMITER ;
