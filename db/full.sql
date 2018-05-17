CREATE TABLE IF NOT EXISTS `urls` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `short_name` varchar(32) NOT NULL,
  `full_url` text NOT NULL,
  `used` int(11) NOT NULL DEFAULT '0'
) DEFAULT CHARSET=utf8;

ALTER TABLE `urls` ADD PRIMARY KEY (`id`);
ALTER TABLE `urls`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;