# link-qr-revision
# link-qr revisited
# Config
Create a file `app.conf` with this content
```
base_url = 'http://your-domain.orch/'
default_url = 'https://de.wikipedia.org/'

[db]
server = p:localhost
name = 'schema'
user = 'user'
passwort = 'pass'
```


## Tables and views
```sql
CREATE TABLE IF NOT EXISTS `code` (
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `code` char(5) COLLATE latin1_bin NOT NULL,
  `url` varchar(4096) COLLATE latin1_bin DEFAULT NULL,
  `last_used` datetime NOT NULL DEFAULT current_timestamp(),
  `hits` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `url_md5_l` bigint(20) UNSIGNED GENERATED ALWAYS AS (conv(left(md5(`url`),16),16,10)) STORED,
  `url_md5_r` bigint(20) UNSIGNED GENERATED ALWAYS AS (conv(right(md5(`url`),16),16,10)) STORED,
  PRIMARY KEY (`code`) USING HASH
) ENGINE=Aria DEFAULT CHARSET=latin1 COLLATE=latin1_bin PACK_KEYS=0;

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `username` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `vorname` varchar(30) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `nachname` varchar(30) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `hash` varchar(255) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=Aria DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
  ADD UNIQUE KEY `ix_user_username` (`username`);

CREATE TABLE `user_email` (
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `uuid` char(64) DEFAULT NULL,
  `confirm_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `register_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=Aria DEFAULT CHARSET=utf8;
ALTER TABLE `user_email`
  ADD PRIMARY KEY (`email`) USING BTREE;


CREATE VIEW `used`  AS
  SELECT count(`code`.`url`) AS `used`, count(0) AS `total`
  FROM `code``code`  ;


CREATE VIEW `vw_user_email` AS
  select 
    `username` AS `username`,
    `email` AS `email`
  from `user_email`
  join (
    select 
      `username`,
      max(`confirm_date`) AS `confirm_date`
    from `user_email`
    group by `username`
  ) `_last` using(`username`, `confirm_date`))

DELIMITER $$
CREATE FUNCTION `get_url` (`c` CHAR(5))
  RETURNS VARCHAR(4096)
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

DELIMITER $$
CREATE FUNCTION `set_url` (`the_user_id` INT, `the_url` VARCHAR(4096))
  RETURNS CHAR(5) CHARSET utf8 DETERMINISTIC
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

```
![image](https://github.com/theking2/link-qr-revision/assets/1612152/7bc6d054-c04d-48a2-adb4-e7b5cfd03d4b)


Watch live on [go321.eu](https://go321.eu) or[go321.ch](https://go321.ch)

