DROP Function IF EXISTS `set_url`;
DELIMITER $$
CREATE DEFINER=`link-qr`@`localhost` FUNCTION `set_url`(`the_user_id` INT, `the_url` VARCHAR(4096)) RETURNS char(5) CHARSET utf8mb4 COLLATE utf8mb4_unicode_520_ci
    MODIFIES SQL DATA
    DETERMINISTIC
begin
    declare result char(5);
    select `code` into result from `code`
    where url_md5_l=conv(left(md5(the_url),16),16,10)
      and url_md5_r=conv(right(md5(the_url),16),16,10) limit 1;
    if result is null then
        select `code` into result from `code` where url is null limit 1;
		update `code` set user_id=the_user_id, url=the_url where `code`=result;
    end if;
    return result;
end$$
DELIMITER ;
