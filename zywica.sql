-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 25 Wrz 2020, 11:58
-- Wersja serwera: 10.4.14-MariaDB
-- Wersja PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `zywica`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `admins`
--

CREATE TABLE `admins` (
  `ID` int(11) NOT NULL,
  `login` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `admins`
--

INSERT INTO `admins` (`ID`, `login`, `password`) VALUES
(1, 'michasGH30', '$2y$10$7asDmIppMNExBA7CC8mb8.8.PTRvK25dmdW35bxKyDfyUyOAWa9/6');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `chains`
--

CREATE TABLE `chains` (
  `ID` int(11) NOT NULL,
  `long_1` decimal(10,2) NOT NULL,
  `long_2` decimal(10,2) NOT NULL,
  `long_3` decimal(10,2) NOT NULL,
  `long_4` decimal(10,2) NOT NULL,
  `long_5` decimal(10,2) NOT NULL,
  `long_6` decimal(10,2) NOT NULL,
  `long_7` decimal(10,2) NOT NULL,
  `long_8` decimal(10,2) NOT NULL,
  `long_9` decimal(10,2) NOT NULL,
  `long_10` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `chains`
--

INSERT INTO `chains` (`ID`, `long_1`, `long_2`, `long_3`, `long_4`, `long_5`, `long_6`, `long_7`, `long_8`, `long_9`, `long_10`) VALUES
(1, '10.00', '11.00', '12.00', '13.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `colors`
--

CREATE TABLE `colors` (
  `ID` int(11) NOT NULL,
  `color_1` text COLLATE utf8mb4_polish_ci NOT NULL,
  `color_2` text COLLATE utf8mb4_polish_ci NOT NULL,
  `color_3` text COLLATE utf8mb4_polish_ci NOT NULL,
  `color_4` text COLLATE utf8mb4_polish_ci NOT NULL,
  `color_5` text COLLATE utf8mb4_polish_ci NOT NULL,
  `color_6` text COLLATE utf8mb4_polish_ci NOT NULL,
  `color_7` text COLLATE utf8mb4_polish_ci NOT NULL,
  `color_8` text COLLATE utf8mb4_polish_ci NOT NULL,
  `color_9` text COLLATE utf8mb4_polish_ci NOT NULL,
  `color_10` text COLLATE utf8mb4_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `colors`
--

INSERT INTO `colors` (`ID`, `color_1`, `color_2`, `color_3`, `color_4`, `color_5`, `color_6`, `color_7`, `color_8`, `color_9`, `color_10`) VALUES
(1, 'złoty', 'srebrny', 'niebieski', 'none', 'none', 'none', 'none', 'none', 'none', 'none');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `diameters`
--

CREATE TABLE `diameters` (
  `ID` int(11) NOT NULL,
  `diameter_1` int(11) NOT NULL,
  `diameter_2` int(11) NOT NULL,
  `diameter_3` int(11) NOT NULL,
  `diameter_4` int(11) NOT NULL,
  `diameter_5` int(11) NOT NULL,
  `diameter_6` int(11) NOT NULL,
  `diameter_7` int(11) NOT NULL,
  `diameter_8` int(11) NOT NULL,
  `diameter_9` int(11) NOT NULL,
  `diameter_10` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `diameters`
--

INSERT INTO `diameters` (`ID`, `diameter_1`, `diameter_2`, `diameter_3`, `diameter_4`, `diameter_5`, `diameter_6`, `diameter_7`, `diameter_8`, `diameter_9`, `diameter_10`) VALUES
(1, 16, 17, 18, 19, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `images`
--

CREATE TABLE `images` (
  `ID` int(11) NOT NULL,
  `folder` text COLLATE utf8mb4_polish_ci NOT NULL,
  `name` text COLLATE utf8mb4_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `images`
--

INSERT INTO `images` (`ID`, `folder`, `name`) VALUES
(1, 'bracelet', 'BRANSOLETKA-LOVE-ROZOWA-BRANZOLETKA.jpg'),
(2, 'bracelet', 'Meska-Skorzana-bransoletka-grawer-Dzien-Chlopaka-S.jpg'),
(3, 'bracelet', 'img_prod31661_0_o.jpg'),
(4, 'necklace', 'NASZYJNIKI-NASZYJNIK-DLA-ZAKOCHANYCH-POLOWKI-SERCA.jpg'),
(5, 'necklace', 'dots-letter-c-logo-design-260nw-551769190.jpg'),
(6, 'necklace', 'logo-konkurs-773x515.jpg'),
(7, 'others', 'plecak-worek-bialy-worek-na-duperele.jpg'),
(8, 'others', 'plecak-worek-bialy-worek-na-duperele.jpg'),
(9, 'others', 'plecak-worek-bialy-worek-na-duperele.jpg'),
(10, 'earrings', 'YES-Zlote-kolczyki-JX01019-Z0000-000000-000-2.jpg'),
(11, 'earrings', 'YES-Zlote-kolczyki-JX01019-Z0000-000000-000-2.jpg'),
(12, 'earrings', 'YES-Zlote-kolczyki-JX01019-Z0000-000000-000-2.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `ID` int(11) NOT NULL,
  `ID_order` int(11) NOT NULL,
  `ID_product` int(11) NOT NULL,
  `title` text COLLATE utf8mb4_polish_ci NOT NULL,
  `color` text COLLATE utf8mb4_polish_ci NOT NULL,
  `order_long` text COLLATE utf8mb4_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders_details`
--

CREATE TABLE `orders_details` (
  `ID` int(11) NOT NULL,
  `ID_order` int(11) NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `last_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `phone` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `send` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders_personalise`
--

CREATE TABLE `orders_personalise` (
  `ID` int(11) NOT NULL,
  `ID_order` int(11) NOT NULL,
  `jewellery` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `background` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `flower_1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `flower_2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `flower_3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `flower_4` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `flower_5` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `color` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `l/d` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `personalise`
--

CREATE TABLE `personalise` (
  `ID` int(11) NOT NULL,
  `jewellery` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `what_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `what_exactly` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `available` tinyint(1) NOT NULL,
  `number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `personalise`
--

INSERT INTO `personalise` (`ID`, `jewellery`, `what_type`, `what_exactly`, `available`, `number`) VALUES
(1, 'naszyjnik', 'tło', 'czarny.jpg', 1, 1),
(2, 'naszyjnik', 'tło', 'fioletowy.jpg', 1, 1),
(3, 'naszyjnik', 'tło', 'szary.jpg', 1, 1),
(4, 'naszyjnik', 'tło', 'zielony.jpg', 1, 1),
(5, 'naszyjnik', 'kwiaty', 'czerwony.jpg', 1, 5),
(6, 'naszyjnik', 'kwiaty', 'fioletowy.jpg', 1, 4),
(7, 'naszyjnik', 'kwiaty', 'niebieski.jpg', 1, 3),
(8, 'naszyjnik', 'kwiaty', 'różowy.jpg', 1, 2),
(9, 'naszyjnik', 'kwiaty', 'szary.jpg', 1, 1),
(10, 'bransoletka', 'kwiaty', 'czarny.jpg', 1, 5),
(11, 'bransoletka', 'kwiaty', 'czerwony.jpg', 1, 5),
(12, 'bransoletka', 'kwiaty', 'niebieski.jpg', 1, 5),
(13, 'bransoletka', 'kwiaty', 'różowy.jpg', 1, 3),
(14, 'bransoletka', 'kwiaty', 'żółty.jpg', 1, 2),
(15, 'bransoletka', 'tło', 'czarny.jpg', 1, 1),
(16, 'bransoletka', 'tło', 'czerwony.jpg', 1, 1),
(17, 'bransoletka', 'tło', 'niebieski.jpg', 1, 1),
(18, 'bransoletka', 'tło', 'różowy.jpg', 1, 1),
(19, 'bransoletka', 'tło', 'żółty.jpg', 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `personalise_price`
--

CREATE TABLE `personalise_price` (
  `ID` int(11) NOT NULL,
  `bransoletka` decimal(10,2) NOT NULL,
  `kolczyki` decimal(10,2) NOT NULL,
  `spinka` decimal(10,2) NOT NULL,
  `broszka` decimal(10,2) NOT NULL,
  `naszyjnik` decimal(10,2) NOT NULL,
  `pierścionek` decimal(10,2) NOT NULL,
  `inne` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `personalise_price`
--

INSERT INTO `personalise_price` (`ID`, `bransoletka`, `kolczyki`, `spinka`, `broszka`, `naszyjnik`, `pierścionek`, `inne`) VALUES
(1, '10.20', '10.11', '20.20', '30.30', '12.12', '99.00', '56.00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `products`
--

CREATE TABLE `products` (
  `ID` int(11) NOT NULL,
  `category` text COLLATE utf8mb4_polish_ci NOT NULL,
  `title` text COLLATE utf8mb4_polish_ci NOT NULL,
  `description` text COLLATE utf8mb4_polish_ci NOT NULL,
  `available` tinyint(1) NOT NULL,
  `number` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `photo_1` int(11) NOT NULL,
  `photo_2` int(11) NOT NULL,
  `photo_3` int(11) NOT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `products`
--

INSERT INTO `products` (`ID`, `category`, `title`, `description`, `available`, `number`, `price`, `photo_1`, `photo_2`, `photo_3`, `date`) VALUES
(1, 'bracelet', 'Brasoletka', 'Opis xD		 ', 1, 3, '12.20', 1, 2, 3, NULL),
(2, 'necklace', 'Naszyjnik', 'Tu jest opis		 ', 1, 4, '11.10', 4, 5, 6, NULL),
(3, 'others', 'Plecak', 'Tu jest opis		 ', 1, 3, '25.10', 7, 7, 7, NULL),
(4, 'earrings', 'Kolczyki', 'Tu jest opis		 ', 1, 4, '16.20', 10, 10, 10, NULL);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `chains`
--
ALTER TABLE `chains`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `diameters`
--
ALTER TABLE `diameters`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `orders_details`
--
ALTER TABLE `orders_details`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `orders_personalise`
--
ALTER TABLE `orders_personalise`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `personalise`
--
ALTER TABLE `personalise`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `personalise_price`
--
ALTER TABLE `personalise_price`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `admins`
--
ALTER TABLE `admins`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `chains`
--
ALTER TABLE `chains`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `colors`
--
ALTER TABLE `colors`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `diameters`
--
ALTER TABLE `diameters`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `images`
--
ALTER TABLE `images`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT dla tabeli `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `orders_details`
--
ALTER TABLE `orders_details`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `orders_personalise`
--
ALTER TABLE `orders_personalise`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `personalise`
--
ALTER TABLE `personalise`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT dla tabeli `personalise_price`
--
ALTER TABLE `personalise_price`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
