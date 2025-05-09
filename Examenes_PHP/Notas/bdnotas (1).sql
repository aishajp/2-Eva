-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-03-2025 a las 11:26:02
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdnotas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellidos` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre`, `apellidos`) VALUES
(1, 'Juan', 'Pérez'),
(2, 'Yolanda', 'López'),
(3, 'María', 'López'),
(4, 'Luis', 'Alvarez'),
(5, 'Teresa', 'Ramírez'),
(6, 'Cristina', 'Álvarez'),
(7, 'Sandra ', 'Menéndez');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `alumno` int(11) NOT NULL,
  `asignatura` varchar(20) NOT NULL,
  `fecha` date NOT NULL,
  `nota` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`alumno`, `asignatura`, `fecha`, `nota`) VALUES
(1, 'Lengua', '2016-11-01', '8.00'),
(1, 'Matematicas', '2019-06-07', '7.00'),
(1, 'Sociales', '2016-11-01', '2.00'),
(2, 'Lengua', '2016-11-01', '7.00'),
(2, 'Matemáticas', '2016-11-10', '3.00'),
(2, 'Sociales', '2019-06-07', '6.00'),
(3, 'Lengua', '2016-11-01', '6.00'),
(3, 'Matemáticas', '2016-11-10', '4.00'),
(3, 'Sociales', '2016-11-01', '4.00'),
(4, 'Lengua', '2016-11-01', '7.00'),
(4, 'Matemáticas', '2016-11-10', '6.00'),
(4, 'Sociales', '0000-00-00', '9.00'),
(5, 'Lengua', '2016-11-01', '6.00'),
(5, 'Matemáticas', '2016-11-10', '9.00'),
(5, 'Sociales', '2016-11-01', '8.00'),
(6, 'Lengua', '2016-11-01', '6.00'),
(6, 'Matemáticas', '2016-11-10', '9.00'),
(6, 'Sociales', '2016-11-01', '5.00'),
(7, 'Lengua', '2016-11-01', '6.00'),
(7, 'Matemáticas', '2016-11-10', '9.00'),
(7, 'Sociales', '2016-11-01', '10.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `password` varchar(10) NOT NULL,
  `rol` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `rol`) VALUES
(1, 'juan', 'juan', 'alumno'),
(2, 'yolanda', 'yolanda', 'alumno'),
(3, 'maria', 'maria', 'alumno'),
(4, 'luis', 'luis', 'alumno'),
(5, 'teresa', 'teresa', 'alumno'),
(6, 'cristina', 'cristina', 'alumno'),
(7, 'sandra', 'sandra', 'director');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`alumno`,`asignatura`,`fecha`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`alumno`) REFERENCES `alumnos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
