-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.5.48 - MySQL Community Server (GPL)
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица project.images
DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `images_users_id_fk` (`user_id`),
  CONSTRAINT `images_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы project.images: ~6 rows (приблизительно)
DELETE FROM `images`;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` (`id`, `user_id`, `image`) VALUES
	(1, 1, '1-58383fbe90008-1480081342.jpg'),
	(2, 1, '1-583840d512fc0-1480081621.jpg'),
	(3, 1, '1-583845a11ccf0-1480082849.jpg'),
	(4, 1, '1-58384bda9c490-1480084442.jpg'),
	(5, 2, '2-583852c7643ad-1480086215.jpg'),
	(6, 5, '5-58386a20e80a5-1480092192.jpg');
/*!40000 ALTER TABLE `images` ENABLE KEYS */;


-- Дамп структуры для таблица project.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `age` int(10) unsigned DEFAULT NULL,
  `about` text,
  `avatar` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы project.users: ~5 rows (приблизительно)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `login`, `password`, `name`, `age`, `about`, `avatar`) VALUES
	(1, 'test', 'test', 'Тест', 12, 'Тестовая учетная запись', '1-58384bda9c490-1480084442.jpg'),
	(2, 'steelcat', 'steelcat', NULL, NULL, NULL, '2-583852c7643ad-1480086215.jpg'),
	(3, 'vitaly', 'vitaly', NULL, NULL, NULL, NULL),
	(4, 'velopiter', 'velopiter', NULL, NULL, NULL, NULL),
	(5, 'new', 'new', NULL, NULL, NULL, '5-58386a20e80a5-1480092192.jpg');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
