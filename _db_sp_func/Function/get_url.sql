DROP Function IF EXISTS `get_url`;
DELIMITER $$
CREATE DEFINER=`link-qr`@`localhost` FUNCTION `get_url`(`c` CHAR(5)) RETURNS varchar(4096) CHARSET utf8mb4 COLLATE utf8mb4_unicode_520_ci
    DETERMINISTIC
begin
    declare result varchar(4096);
    update code
	set hits = hits + 1,last_used=current_timestamp()
        where code = c;
    return (
	select url
        from code
        where code = c
    );
end$$
DELIMITER ;
