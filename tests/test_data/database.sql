DROP TABLE IF EXISTS `table1`;
CREATE TABLE `table1` (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, `name` varchar(32) NULL, `description` varchar(255) NOT NULL, `price` decimal(10, 2) NULL, PRIMARY KEY (`id`), UNIQUE KEY `uniq_name` (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `table2`;
CREATE TABLE `table2` (`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, `name` varchar(32) NULL, `description` varchar(255) NOT NULL, `price` decimal(10, 2) NULL, PRIMARY KEY  (`id`), UNIQUE KEY `uniq_name` (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `table1` VALUES ('1','test1','test test3',0.22);
INSERT INTO `table1` VALUES ('2','test2','test test4',231.99);