-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 26 2020 г., 18:52
-- Версия сервера: 5.7.26
-- Версия PHP: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `lab6`
--

-- --------------------------------------------------------

--
-- Структура таблицы `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `msg` text NOT NULL,
  `user` text NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `msg`, `user`, `status`) VALUES
('2cb445103cc34b7854ea80489e46ea4db9d31dc52daffd0f7c36d21f4597d881', 'Sample 2', '[{\"login\":\"User\",\"msg\":\"Sample msg\"},{\"login\":\"User\",\"msg\":\"Another one\"}]', '[]', 0),
('371a0129d6282e7ef672544b92099bf246b362b76527b7c24e7889ca613b52da', 'Sample room', '[]', '[]', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tokens`
--

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `token` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tokens`
--

INSERT INTO `tokens` (`id`, `token`) VALUES
(9, '8e80e80ada1f63ea28855a08496448ff029db11c911376b57b5fcea44f3e2c2f'),
(11, 'f35f8112943b48a517b6167e0d4afaae25bcb193f2c2c194dc67a2569bd9d72d');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `login` varchar(64) NOT NULL,
  `pass_hash` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `q` varchar(64) NOT NULL,
  `a` varchar(64) NOT NULL,
  `status` int(11) NOT NULL,
  `pass_expire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `pass_hash`, `email`, `q`, `a`, `status`, `pass_expire`) VALUES
(9, 'Kora', '$2y$10$OrMTBIAk6CiUrX2qtrXuSOlpk4REPXo4RizEZfNUdNklbsbNoKJTK', 'asd', 'person', 'Anyan', 1, '2020-01-13 16:59:35'),
(11, 'User', '$2y$10$hYLobkhyjyslDnxeuE74cePU0wsKXIzjmfkSu.7APoc/2GkDAReGy', 'user@u.ser', 'user', 'user', 0, '2020-01-13 21:27:19');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
