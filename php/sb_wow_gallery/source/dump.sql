-- 
-- Структура таблицы `users`
-- 

CREATE TABLE `users` (
  `id` int(11),
  `gender` tinyint(3),
  `photos_count` smallint(8) NOT NULL DEFAULT '0',
  `photo` varchar(10) DEFAULT NULL,
  `photo_last`  varchar(10) DEFAULT NULL,
  `char_name` varchar(12),
  `char_race` tinyint(3),
  `char_class` tinyint(3),
  `char_level` tinyint(3),
    UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  CHARACTER SET utf8 COLLATE utf8_general_ci;