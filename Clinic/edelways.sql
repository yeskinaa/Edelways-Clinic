-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 24 2022 г., 21:01
-- Версия сервера: 5.6.51
-- Версия PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `edelways`
--

-- --------------------------------------------------------

--
-- Структура таблицы `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', 1592635365),
('doctor', '12', 1653196046),
('doctor', '20', 1653196411),
('doctor', '7', 1653150829),
('patient', '10', 1653195935),
('patient', '11', 1653195983),
('patient', '13', 1653196098),
('patient', '14', 1653196142),
('patient', '15', 1653196185),
('patient', '16', 1653196253),
('patient', '17', 1653196285),
('patient', '18', 1653196315),
('patient', '21', 1653206439),
('patient', '22', 1653206696),
('patient', '23', 1653206786),
('patient', '24', 1653207253),
('patient', '6', 1653149931),
('patient', '9', 1653195836);

-- --------------------------------------------------------

--
-- Структура таблицы `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, 'Адміністратор', NULL, NULL, 1653111553, 1653111553),
('doctor', 1, 'Лікар', NULL, NULL, 1653111553, 1653111553),
('patient', 1, 'Пацієнт', NULL, NULL, 1653111553, 1653111553);

-- --------------------------------------------------------

--
-- Структура таблицы `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('admin', 'patient'),
('doctor', 'patient');

-- --------------------------------------------------------

--
-- Структура таблицы `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `doctor_info`
--

CREATE TABLE `doctor_info` (
  `id` int(11) NOT NULL COMMENT 'id',
  `user_id` int(11) NOT NULL COMMENT 'Користувач',
  `speciality_id` int(11) NOT NULL COMMENT 'Спеціальність',
  `room` varchar(50) NOT NULL COMMENT 'Кабінет',
  `working_days` text NOT NULL COMMENT 'Робочі дні',
  `working_time` varchar(15) NOT NULL COMMENT 'Робочий час'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `doctor_info`
--

INSERT INTO `doctor_info` (`id`, `user_id`, `speciality_id`, `room`, `working_days`, `working_time`) VALUES
(4, 12, 56, 'А-101 (1 поверх)', '1;2;3;4;5', '09:00-18:00'),
(5, 20, 51, 'А-223 (2 поверх)', '1;2;3;4;5', '09:00-18:00'),
(6, 7, 18, 'А-112 (1 поверх)', '1;3;5', '10:00-16:00');

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `patient_id` int(11) NOT NULL COMMENT 'ID пацієнта',
  `patient_name` varchar(255) NOT NULL COMMENT 'ПІБ пацієнта',
  `doctor_id` int(11) NOT NULL COMMENT 'ID лікаря',
  `doctor_name` varchar(255) NOT NULL COMMENT 'ПІБ лікаря',
  `speciality` varchar(255) NOT NULL COMMENT 'Спеціальність',
  `date_time` datetime NOT NULL COMMENT 'Дата та час',
  `room` varchar(50) NOT NULL COMMENT 'Кабінет',
  `description` text NOT NULL COMMENT 'Примітка',
  `status` enum('Новий запис','Підтверджено','Відхилено') NOT NULL DEFAULT 'Новий запис' COMMENT 'Статус',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Створено '
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `patient_id`, `patient_name`, `doctor_id`, `doctor_name`, `speciality`, `date_time`, `room`, `description`, `status`, `created_at`) VALUES
(8, 16, 'Пономарчук Валентина Миколаївна', 20, 'Броварчук Інна Петрівна', 'Терапія', '2022-05-26 14:35:00', 'А-223 (2 поверх)', '<p><strong>Симптоми:</strong></p>\r\n\r\n<ul>\r\n	<li>Болить горло</li>\r\n	<li>Нежить</li>\r\n	<li>Температура 37,2</li>\r\n	<li>Головна біль</li>\r\n</ul>\r\n', 'Підтверджено', '2022-05-24 08:01:37');

-- --------------------------------------------------------

--
-- Структура таблицы `speciality`
--

CREATE TABLE `speciality` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `title` varchar(255) NOT NULL COMMENT 'Назва'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `speciality`
--

INSERT INTO `speciality` (`id`, `title`) VALUES
(1, 'Акушерство та гінекологія'),
(2, 'Алергологія'),
(3, 'Анестезіологія'),
(4, 'Гастроентерологія'),
(5, 'Гематологія'),
(6, 'Генетика медична'),
(7, 'Дерматовенерологія'),
(8, 'Дитяча анестезіологія'),
(9, 'Дитяча неврологія'),
(10, 'Дитяча нефрологія'),
(11, 'Дитяча ортопедія і травматологія'),
(12, 'Дитяча отоларингологія'),
(13, 'Дитяча офтальмологія'),
(14, 'Дитяча психіатрія'),
(15, 'Дитяча стоматологія'),
(16, 'Дитяча хірургія'),
(17, 'Дитячі інфекційні хвороби'),
(18, 'Дієтологія'),
(19, 'Загальна практика - сімейна медицина'),
(21, 'Кардіологія'),
(22, 'Клінічна лабораторна діагностика'),
(23, 'Комбустіологія'),
(24, 'Лікувальна фізкультура і спортивна медицина'),
(25, 'Медицина невідкладних станів'),
(26, 'Неврологія'),
(27, 'Нейрохірургія'),
(28, 'Неонатологія'),
(29, 'Нефрологія'),
(30, 'Онкологія'),
(31, 'Ортодонтія'),
(32, 'Ортопедична стоматологія'),
(33, 'Ортопедія і травматологія'),
(34, 'Отоларингологія'),
(35, 'Офтальмологія'),
(36, 'Патологічна анатомія'),
(37, 'Педіатрія'),
(38, 'Професійна патологія'),
(39, 'Психіатрія'),
(41, 'Психотерапія'),
(42, 'Пульмонологія'),
(43, 'Радіологія'),
(44, 'Рентгенологія'),
(45, 'Ревматологія'),
(46, 'Рефлексотерапія'),
(47, 'Стоматологія'),
(48, 'Судинна хірургія'),
(49, 'Судово-медична експертиза'),
(50, 'Терапевтична стоматологія'),
(51, 'Терапія'),
(52, 'Урологія'),
(53, 'Фтизіатрія'),
(54, 'Функціональна діагностика'),
(55, 'Хірургічна стоматологія'),
(56, 'Хірургія'),
(57, 'Хірургія серця і магістральних судин');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Логін',
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Ключ авторизації',
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Пароль',
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Токен скидування пароля',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'E-mail',
  `status` smallint(6) NOT NULL DEFAULT '10' COMMENT 'Статус'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `name`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`) VALUES
(1, 'Адміністратор', 'wkPHee-TkT8uG_aVuuTrbQ4_BHS01Z8U', '$2y$13$AIcwFgspqmeFmwgq9Z2x2eWB2F1nHJUx9c2PTYACTazzpO/cLFkBK', NULL, 'admin@edelways.com', 10),
(6, 'Шевченко Мирослав Миколайович', 'DyZ3oVmL0lGYIIegLwPl9FhDtnB2Yx9L', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'evasilcuk@fortressfinancial.biz', 10),
(7, 'Гнатюк Ярослав Анатолійович', 'DshnkC82ddea-JEzwgos3Eb9NsJ7ZSui', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'mihajlo.kramare@sharyndoll.com', 10),
(9, 'Пономарчук Йосип Анатолійович', 'n1WCR0dvxrk_QuXbFgIcxePtHkZ1EAFV', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'danilo81@netveplay.com', 10),
(11, 'Боднаренко Станіслав Йосипович', 'GcHOv7rTr6p2C3Zcaxoz3iq0a-kLZ6e3', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'stabo92@nonise.com', 10),
(12, 'Антоненко Геннадій Петрович', 'vJQcme9Uh8JijbyCQv7Bk0fMPytmOpJ6', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'krokodile111@ermtia.com', 10),
(13, 'Васильєва Лариса Валентинівна', 'MA6pDYl92-LZRBZ9WFOdSAhvwfrX8EIy', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'loral.yummi@gmailvn.net', 10),
(14, 'Антоненко Марія Федорівна', 'jHMfqFWgKcPxG6rB0asou-igmBliVdPN', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'sereda@victorysvg.com', 10),
(15, 'Шевчук Інна Йосипівна', 'oA092Z2UUu9K01ysPbTILYK52-CY3l-M', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'sevcuk.inna@cheapnitros.com', 10),
(16, 'Пономарчук Валентина Миколаївна', 'LkxLDLlGmX4hpPRuqtaSEh61ZY7bip30', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'pvm228@luddo.me', 10),
(17, 'Кравченко Володимир Петрович', '5iO_UA90vwfsWLlfNI668P6gLa-UdUuc', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'vkravchenko@gmailwe.com', 10),
(18, 'Панасюк Євген Борисович', 'H7Ixg9qRwCfB29loJDaf8iQwr0iC-14K', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'shiga321@scdhn.com', 10),
(20, 'Броварчук Інна Петрівна', 'bMRektrutH_VXDUZoK2ItGKD0Hx1dnXR', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'inna1995@crpotu.com', 10),
(22, 'Васильєв Іван Сергійович', 'zHbDhrsI5KnKACRrbvzvw8JPuHi2asEP', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'elizaveta.pavlu@tukang.codes', 10),
(24, 'Романченко Юлія Володимирівна', 'adItFdu5dL5daUgdDx5MnZOexc5D155C', '$2y$13$MenplI.6dxyeToLnP7ex6uJFMM7XVCv3ii8aYshU3/SSlg9XqJS7C', NULL, 'romanchenko@gmail.com', 10);

-- --------------------------------------------------------

--
-- Структура таблицы `user_info`
--

CREATE TABLE `user_info` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT 'Користувач',
  `sex` enum('Чоловіча','Жіноча') NOT NULL COMMENT 'Стать',
  `birthday` varchar(10) NOT NULL COMMENT 'День народження',
  `phone` varchar(20) NOT NULL COMMENT 'Телефон',
  `photo` varchar(50) DEFAULT NULL COMMENT 'Фото'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_info`
--

INSERT INTO `user_info` (`id`, `user_id`, `sex`, `birthday`, `phone`, `photo`) VALUES
(1, 24, 'Жіноча', '1989-05-24', '+38 (096) 558-4418', NULL),
(2, 1, 'Чоловіча', '2000-01-01', '+38 (000) 000-0000', '1-user.jpg'),
(4, 12, 'Чоловіча', '1975-05-21', '+38 (096) 453-0110', '12-user.jpg'),
(5, 16, 'Жіноча', '1988-01-22', '+38 (063) 145-8751', '16-user.jpg'),
(6, 20, 'Жіноча', '1988-10-12', '+38 (066) 452-1452', '20-user.jpg');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `idx-auth_assignment-user_id` (`user_id`);

--
-- Индексы таблицы `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Индексы таблицы `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Индексы таблицы `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Индексы таблицы `doctor_info`
--
ALTER TABLE `doctor_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Индексы таблицы `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `speciality`
--
ALTER TABLE `speciality`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`name`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Индексы таблицы `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `doctor_info`
--
ALTER TABLE `doctor_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `speciality`
--
ALTER TABLE `speciality`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
