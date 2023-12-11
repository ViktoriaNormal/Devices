-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Дек 05 2023 г., 14:36
-- Версия сервера: 5.7.21-20-beget-5.7.21-20-1-log
-- Версия PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `i968351m_rk2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `COMMAND_TABLE`
--
-- Создание: Дек 05 2023 г., 10:37
--

DROP TABLE IF EXISTS `COMMAND_TABLE`;
CREATE TABLE `COMMAND_TABLE` (
                                 `DEVICE_ID` int(11) DEFAULT NULL,
                                 `COMMAND` int(11) DEFAULT NULL,
                                 `DATE_TIME` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `DEVICE_TABLE`
--
-- Создание: Дек 05 2023 г., 10:37
--

DROP TABLE IF EXISTS `DEVICE_TABLE`;
CREATE TABLE `DEVICE_TABLE` (
                                `DEVICE_ID` int(11) NOT NULL,
                                `DEVICE_LOGIN` char(10) NOT NULL,
                                `DEVICE_PASSWORD` char(10) NOT NULL,
                                `NAME` char(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `OUT_STATE_TABLE`
--
-- Создание: Дек 05 2023 г., 10:37
--

DROP TABLE IF EXISTS `OUT_STATE_TABLE`;
CREATE TABLE `OUT_STATE_TABLE` (
                                   `DEVICE_ID` int(11) DEFAULT NULL,
                                   `OUT_STATE` int(11) DEFAULT NULL,
                                   `DATE_TIME` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `TEMPERATURE_TABLE`
--
-- Создание: Дек 05 2023 г., 10:37
--

DROP TABLE IF EXISTS `TEMPERATURE_TABLE`;
CREATE TABLE `TEMPERATURE_TABLE` (
                                     `DEVICE_ID` int(11) DEFAULT NULL,
                                     `TEMPERATURE` int(11) DEFAULT NULL,
                                     `DATE_TIME` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Индексы таблицы `COMMAND_TABLE`
--
ALTER TABLE `COMMAND_TABLE`
    ADD KEY `DEVICE_ID` (`DEVICE_ID`);

--
-- Индексы таблицы `DEVICE_TABLE`
--
ALTER TABLE `DEVICE_TABLE`
    ADD PRIMARY KEY (`DEVICE_ID`);

--
-- Индексы таблицы `OUT_STATE_TABLE`
--
ALTER TABLE `OUT_STATE_TABLE`
    ADD KEY `DEVICE_ID` (`DEVICE_ID`);

--
-- Индексы таблицы `TEMPERATURE_TABLE`
--
ALTER TABLE `TEMPERATURE_TABLE`
    ADD KEY `DEVICE_ID` (`DEVICE_ID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `DEVICE_TABLE`
--
ALTER TABLE `DEVICE_TABLE`
    MODIFY `DEVICE_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `COMMAND_TABLE`
--
ALTER TABLE `COMMAND_TABLE`
    ADD CONSTRAINT `COMMAND_TABLE_ibfk_1` FOREIGN KEY (`DEVICE_ID`) REFERENCES `DEVICE_TABLE` (`DEVICE_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `OUT_STATE_TABLE`
--
ALTER TABLE `OUT_STATE_TABLE`
    ADD CONSTRAINT `OUT_STATE_TABLE_ibfk_1` FOREIGN KEY (`DEVICE_ID`) REFERENCES `DEVICE_TABLE` (`DEVICE_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `TEMPERATURE_TABLE`
--
ALTER TABLE `TEMPERATURE_TABLE`
    ADD CONSTRAINT `TEMPERATURE_TABLE_ibfk_1` FOREIGN KEY (`DEVICE_ID`) REFERENCES `DEVICE_TABLE` (`DEVICE_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE user (
    USER_ID INT PRIMARY KEY AUTO_INCREMENT,
    NAME CHAR(60),
    LOGIN CHAR(40),
    PASSWORD CHAR(40)
);

CREATE TABLE user_device_status (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    USER_ID INT,
    DEVICE_ID INT,
    COMMAND INT,
    DATE_TIME DATETIME,
    CONSTRAINT FOREIGN KEY (USER_ID) REFERENCES user (USER_ID) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (DEVICE_ID) REFERENCES device_table (DEVICE_ID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE user_device (
                                    USER_ID INT,
                                    DEVICE_ID INT,
                                    PRIMARY KEY (USER_ID, DEVICE_ID),
                                    CONSTRAINT FOREIGN KEY (USER_ID) REFERENCES user (USER_ID) ON DELETE CASCADE ON UPDATE CASCADE,
                                    CONSTRAINT FOREIGN KEY (DEVICE_ID) REFERENCES device_table (DEVICE_ID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TRIGGER hash_user_password BEFORE INSERT ON user
    FOR EACH ROW
BEGIN
    SET NEW.PASSWORD = SHA2(CONCAT(NEW.PASSWORD, 'my_unique_salt_for_users'), 256);
END;

CREATE TRIGGER hash_device_password BEFORE INSERT ON device_table
    FOR EACH ROW
BEGIN
    SET NEW.DEVICE_PASSWORD = SHA2(CONCAT(NEW.DEVICE_PASSWORD, 'my_unique_salt_for_devices'), 256);
END;

INSERT INTO device_table (DEVICE_LOGIN, DEVICE_PASSWORD, NAME)
VALUES ('1234', '1234', 'MyObject1'),
       ('0987', '0987', 'MyObject2'),
       ('5678', '5678', 'MyObject3'),
       ('1357', '1357', 'MyObject4'),
       ('0864', '0864', 'MyObject5');

INSERT INTO user (NAME, LOGIN, PASSWORD)
VALUES ('Viktoria Pochtova', '111111', '111111'),
       ('Anastasia Shahno', '222222', '222222'),
       ('Danil Kozlov', '333333', '333333'),
       ('Konopskiy Kirill', '444444', '444444'),
       ('Andrey Dorohin', '555555', '555555');

CREATE TABLE devices_change (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    DEVICE_ID INT,
    TYPE_CHANGE VARCHAR(20),
    DATE_TIME DATETIME DEFAULT NOW(),
    CONSTRAINT FOREIGN KEY (DEVICE_ID) REFERENCES device_table (DEVICE_ID)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE device_block (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    DEVICE_ID INT,
    START_TIME DATETIME DEFAULT (NOW()),
    END_TIME DATETIME DEFAULT (ADDTIME(NOW(), '00:00:30')),
    CONSTRAINT FOREIGN KEY (DEVICE_ID) REFERENCES device_table (DEVICE_ID)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE user_block (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    USER_ID INT,
    DEVICE_ID INT,
    START_TIME DATETIME DEFAULT (NOW()),
    END_TIME DATETIME DEFAULT (ADDTIME(NOW(), '00:00:30')),
    CONSTRAINT FOREIGN KEY (USER_ID) REFERENCES user (USER_ID)
        ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT FOREIGN KEY (DEVICE_ID) REFERENCES device_table (DEVICE_ID)
            ON DELETE CASCADE ON UPDATE CASCADE
);

-- Триггер на блокировку пользователя при слишком частом обращении к устройству
CREATE TRIGGER block_user_insert AFTER INSERT ON user_device_status
    FOR EACH ROW
BEGIN
    DECLARE count INT;
    SET count = (SELECT COUNT(*) FROM user_device_status WHERE USER_ID = NEW.USER_ID
                                                           AND MINUTE(TIMEDIFF(NOW(), DATE_TIME)) < 1
                                                         AND DEVICE_ID = NEW.DEVICE_ID);
    IF(count >= 5 AND
       NOT EXISTS(SELECT * FROM user_block WHERE user_block.USER_ID = NEW.USER_ID
                                         AND user_block.DEVICE_ID = NEW.DEVICE_ID
                                         AND END_TIME > NOW()))

        THEN INSERT INTO user_block (USER_ID, DEVICE_ID) VALUE (NEW.USER_ID, NEW.DEVICE_ID);
    END IF;
END;

-- Триггер на блокировку устройства при слишком частом обращении к серверу
CREATE TRIGGER block_device_insert AFTER INSERT ON devices_change
    FOR EACH ROW
BEGIN
    DECLARE count INT;
    SET count = (SELECT COUNT(*) FROM devices_change WHERE DEVICE_ID = NEW.DEVICE_ID
                                                           AND MINUTE(TIMEDIFF(NOW(), DATE_TIME)) < 1);
    IF(count >= 5 AND
       NOT EXISTS(SELECT * FROM device_block WHERE device_block.DEVICE_ID = NEW.DEVICE_ID
                                             AND END_TIME > NOW()))

    THEN INSERT INTO device_block (DEVICE_ID) VALUE (NEW.DEVICE_ID);
    END IF;
END;

SELECT * FROM USER_BLOCK WHERE USER_ID = 1 AND DEVICE_ID = 7 AND END_TIME > NOW();













