-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-08-2018 a las 23:07:52
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dev20_eagle2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roomnumberused`
--

CREATE TABLE `roomnumberused` (
  `roomnumberusedid` int(11) NOT NULL,
  `checkin` date DEFAULT NULL,
  `checkout` date DEFAULT NULL,
  `roomnumber` varchar(45) DEFAULT NULL,
  `hotelid` int(11) DEFAULT NULL,
  `roomid` int(11) DEFAULT NULL,
  `reservationid` int(11) DEFAULT NULL,
  `channelid` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `roomnumberused`
--

INSERT INTO `roomnumberused` (`roomnumberusedid`, `checkin`, `checkout`, `roomnumber`, `hotelid`, `roomid`, `reservationid`, `channelid`, `active`) VALUES
(1, '2018-08-10', '2018-08-11', '1', 1, 1, 1, 0, 1),
(2, '2018-08-08', '2018-08-09', 'Room 1', 25, 17, 2957, 0, 1),
(3, '2018-08-08', '2018-08-09', 'Room 1', 25, 17, 2958, 0, 1),
(4, '2018-08-08', '2018-08-09', 'Room 1', 25, 17, 2959, 0, 1),
(5, '2018-08-08', '2018-08-09', 'Room 2', 25, 17, 2960, 0, 1),
(6, '2018-08-08', '2018-08-09', 'Room 3', 25, 17, 2961, 0, 1),
(7, '2018-08-08', '2018-08-12', 'Room 1 ', 25, 20, 2962, 0, 1),
(8, '2018-08-08', '2018-08-09', 'Room 2', 25, 20, 2963, 0, 1),
(9, '2018-08-08', '2018-08-09', 'Room 3', 25, 20, 2964, 0, 1),
(10, '2018-08-08', '2018-08-09', 'Room 4', 25, 20, 2965, 0, 1),
(11, '2018-08-08', '2018-08-09', 'Room 5', 25, 20, 2966, 0, 1),
(12, '2018-08-08', '2018-08-09', 'Room 6 ', 25, 20, 2967, 0, 1),
(13, '2018-08-08', '2018-08-09', 'Room 7', 25, 20, 2968, 0, 1),
(14, '2018-08-08', '2018-08-12', '505', 21, 13, 2969, 0, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `roomnumberused`
--
ALTER TABLE `roomnumberused`
  ADD PRIMARY KEY (`roomnumberusedid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `roomnumberused`
--
ALTER TABLE `roomnumberused`
  MODIFY `roomnumberusedid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
