-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-07-2025 a las 00:53:32
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `resicontrol1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloque`
--

CREATE TABLE `bloque` (
  `id_casa` int(11) NOT NULL,
  `cantidad_apartamentos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bloque`
--

INSERT INTO `bloque` (`id_casa`, `cantidad_apartamentos`) VALUES
(1, 10),
(2, 10),
(3, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `nit` int(11) NOT NULL,
  `empresa` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`nit`, `empresa`) VALUES
(0, 'condominio filadelfia'),
(800123456, 'Securitas Ltda');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `estado` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `estado`) VALUES
(1, 'Pendiente'),
(2, 'Aprobado'),
(3, 'Rechazado'),
(4, 'Activo'),
(5, 'Inactivo'),
(6, 'Entregado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `id_horario` int(11) NOT NULL,
  `horario` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horario`
--

INSERT INTO `horario` (`id_horario`, `horario`) VALUES
(0, '05:18 - 23:18'),
(1, '08:00 - 10:00'),
(2, '10:00 - 12:00'),
(3, '14:00 - 16:00'),
(4, '16:00 - 18:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencias`
--

CREATE TABLE `licencias` (
  `id_licencia` varchar(10) NOT NULL,
  `codigo_licencia` varchar(20) DEFAULT NULL,
  `id_tipo_licencia` int(11) NOT NULL,
  `fecha_ini` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `nit` int(11) NOT NULL,
  `estado` enum('Activa','Expirada','Inactiva') DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `licencias`
--

INSERT INTO `licencias` (`id_licencia`, `codigo_licencia`, `id_tipo_licencia`, `fecha_ini`, `fecha_fin`, `nit`, `estado`) VALUES
('LIC002', '987654321', 4, '2023-01-01 00:00:00', '2028-01-01 00:00:00', 800123456, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `manzana`
--

CREATE TABLE `manzana` (
  `id_manzana` int(11) NOT NULL,
  `cantidad_casas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `manzana`
--

INSERT INTO `manzana` (`id_manzana`, `cantidad_casas`) VALUES
(1, 10),
(2, 10),
(3, 10),
(4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id_marca` int(11) NOT NULL,
  `marca` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id_marca`, `marca`) VALUES
(1, 'Chevrolet'),
(2, 'Yamaha'),
(3, 'Mazda');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivo_visita`
--

CREATE TABLE `motivo_visita` (
  `id_mot_visi` int(11) NOT NULL,
  `motivo_visita` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `motivo_visita`
--

INSERT INTO `motivo_visita` (`id_mot_visi`, `motivo_visita`) VALUES
(1, 'Familiar'),
(2, 'Técnico'),
(3, 'Amigo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivo_zonas`
--

CREATE TABLE `motivo_zonas` (
  `id_mot_zonas` int(11) NOT NULL,
  `motivo_zonas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `motivo_zonas`
--

INSERT INTO `motivo_zonas` (`id_mot_zonas`, `motivo_zonas`) VALUES
(1, 'Cumpleaños'),
(2, 'Reunión Familiar'),
(3, 'Evento Comunitario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes`
--

CREATE TABLE `paquetes` (
  `id_paquete` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `id_vigilante` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fech_hor_recep` datetime DEFAULT NULL,
  `fech_hor_entre` datetime DEFAULT NULL,
  `id_estado` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paquetes`
--

INSERT INTO `paquetes` (`id_paquete`, `id_usuarios`, `id_vigilante`, `descripcion`, `fech_hor_recep`, `fech_hor_entre`, `id_estado`) VALUES
(12, 987654321, 110562472, 'zfghjk', '2025-07-03 09:55:00', '2025-07-03 17:56:19', 2),
(13, 1012398609, 110562472, 'qwertyuipoiuytrew', '2025-07-04 16:58:00', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reservas` int(11) NOT NULL,
  `id_zonas_comu` int(11) DEFAULT NULL,
  `id_usuarios` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `id_horario` int(11) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `id_administrador` int(11) DEFAULT NULL,
  `fecha_apro` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `id_mot_zonas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reservas`, `id_zonas_comu`, `id_usuarios`, `fecha`, `id_horario`, `id_estado`, `id_administrador`, `fecha_apro`, `observaciones`, `id_mot_zonas`) VALUES
(9, 3, 1012353162, '2025-07-03', 1, 3, NULL, NULL, '', 1),
(10, 4, 1012353162, '2025-07-04', 2, 2, NULL, NULL, '', 1),
(11, 3, 1012398609, '2025-07-04', 1, 2, 1012353163, '2025-07-03', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rol`) VALUES
(1, 'Super Admin'),
(2, 'Administrador'),
(3, 'Residente'),
(4, 'Vigilante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `id_tipo_doc` int(11) NOT NULL,
  `tipo_documento` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`id_tipo_doc`, `tipo_documento`) VALUES
(1, 'C.C'),
(2, 'T.I'),
(3, 'C.E');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_licencia`
--

CREATE TABLE `tipo_licencia` (
  `id_tipo_licencia` int(11) NOT NULL,
  `licencia` varchar(50) NOT NULL,
  `duracion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_licencia`
--

INSERT INTO `tipo_licencia` (`id_tipo_licencia`, `licencia`, `duracion`) VALUES
(1, 'Demo', 3),
(2, 'Freeware', 365),
(3, 'Shareware', 30),
(4, 'Anual', 365),
(5, 'Semestral', 182);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_vehiculos`
--

CREATE TABLE `tipo_vehiculos` (
  `id_tipo_vehi` int(11) NOT NULL,
  `tipo_vehiculos` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_vehiculos`
--

INSERT INTO `tipo_vehiculos` (`id_tipo_vehi`, `tipo_vehiculos`) VALUES
(1, 'Carro'),
(2, 'Moto'),
(3, 'Bicicleta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `documento` int(11) NOT NULL,
  `id_tipo_doc` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `telefono` bigint(11) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `id_estado_usuario` int(11) NOT NULL DEFAULT 4,
  `fecha_registro` datetime DEFAULT NULL,
  `nit` int(11) DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `direccion_casa` varchar(100) DEFAULT NULL,
  `cantidad_personas` int(11) DEFAULT NULL,
  `tiene_animales` varchar(2) DEFAULT NULL,
  `cantidad_animales` int(11) DEFAULT NULL,
  `direccion_residencia` varchar(100) DEFAULT NULL,
  `id_bloque` int(11) DEFAULT NULL,
  `id_manzana` int(11) DEFAULT NULL,
  `codigo_recuperacion` varchar(10) DEFAULT NULL,
  `codigo_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`documento`, `id_tipo_doc`, `nombre`, `apellido`, `telefono`, `correo`, `contrasena`, `id_rol`, `id_estado`, `id_estado_usuario`, `fecha_registro`, `nit`, `empresa`, `direccion_casa`, `cantidad_personas`, `tiene_animales`, `cantidad_animales`, `direccion_residencia`, `id_bloque`, `id_manzana`, `codigo_recuperacion`, `codigo_expira`) VALUES
(110562472, 1, 'brahian', 'morales', 3208044063, 'bgustavom@gmail.com', '$2y$10$0P2bJlht02mU/YPluKpJxe1kQcxCqhMathRRr.OsEXZIomCx3TKRu', 4, 1, 4, NULL, NULL, 'Alkomprar', '', 0, '1', 0, '', NULL, NULL, NULL, NULL),
(987654321, 1, 'pepita', 'perez', 12345678, 'pepitaa@gmail.com', '$2y$10$EApseTXjKg1zUm9qKmalhus4Uev/swrCaOw4k6urDWmcTKeGCJtqu', 3, 1, 4, NULL, NULL, '', 'Manzana L casa 32 Tolima Grande', 2, 'si', 0, '', NULL, NULL, NULL, NULL),
(1012353162, 1, 'sofia', 'enciso', 3022927343, 'encisogarciaelisabetsofia@gmail.com', '$2y$10$VZ/pML5PrhF1V45ApmLM9etfhZndBtf7aZMMo2YjHR3/Pvf4U1y/i', 3, 2, 4, '2025-06-07 10:32:44', 800123456, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '422520', '2025-06-25 06:22:22'),
(1012353163, 1, 'elisabet', 'enciso', 3022927344, 'encisogarciaeli@gmail.com', '$2y$10$24m3LzcRQD45Cx60xmxF9OUGxUxA992B.ZEeGlZOm.MaQxO6Lhp2y', 2, 1, 4, '2025-06-07 10:32:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1012398609, 1, 'jose', 'morales', 302299943, 'josemo@gmail.com', '$2y$10$1qmGczb9VGHU.SxcXe3CTOXjtY4YPi7g4/XJK6arWBzCIrHZFxJYm', 3, 1, 4, NULL, NULL, '', 'Manzana L casa 32 Tolima Grande', 2, 'si', 2, '', NULL, NULL, NULL, NULL),
(1016622262, 1, 'camila', 'camargo', 3208044999, 'camila@gmail.com', '$2y$10$7BhM1gu18T6FLTnMqBFho.nJj3aWoexiHym1NH.734HZug7k2PZ8i', 3, 1, 4, NULL, NULL, '', 'Manzana L casa 32 Tolima Grande', 2, 'no', 0, '', NULL, NULL, NULL, NULL),
(1106226099, 1, 'luisa', 'enciso', 3144644540, 'luisaegar.25@gmail.com', '$2y$10$0bWYmcU/x2SbWJWwcWv/9uJ7MoVwMyxl5xURLIKX71mnNxiw1WFfS', 1, 1, 4, NULL, NULL, '', 'Manzana L casa 32 Tolima Grande', 5, 'si', 2, '', NULL, NULL, NULL, NULL),
(2147483647, 1, 'juan', 'vallejo', 3007467158, 'estebanjv551@gmail.com', '$2y$10$OA/JCDN4N6p7YYn/.4bLF.PakbFEsrEY8xS9AXHlOG.SzC9CXg4se', 3, 1, 4, NULL, NULL, '', 'Manzana B casa 18 Tolima Grande', 2, 'no', 0, '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id_vehiculos` int(11) NOT NULL,
  `id_tipo_vehi` int(11) DEFAULT NULL,
  `id_usuarios` int(11) DEFAULT NULL,
  `placa` varchar(20) DEFAULT NULL,
  `id_marca` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id_vehiculos`, `id_tipo_vehi`, `id_usuarios`, `placa`, `id_marca`) VALUES
(1, 2, 1016622262, '123erfd', 2),
(2, 1, 1106226099, 'WGR352', 1),
(3, 2, 2147483647, 'WER23Q', 2),
(4, 1, 1012398609, 'STT345', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visitas`
--

CREATE TABLE `visitas` (
  `id_visita` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `documento` bigint(20) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `id_mot_visi` int(11) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `hora_ingreso` time NOT NULL,
  `fecha_soli` date DEFAULT curdate(),
  `codigo` varchar(50) DEFAULT NULL,
  `id_estado` int(11) DEFAULT 1,
  `codigo_expira` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `visitas`
--

INSERT INTO `visitas` (`id_visita`, `nombre`, `apellido`, `documento`, `id_usuarios`, `id_mot_visi`, `fecha_ingreso`, `hora_ingreso`, `fecha_soli`, `codigo`, `id_estado`, `codigo_expira`) VALUES
(9, 'eduardo', 'perez', 1012359900, 1012353162, 1, '2025-07-03', '18:20:00', '2025-07-03', NULL, 2, NULL),
(10, 'camila ', 'Enciso', 1234562222, 1012353162, 1, '2025-07-04', '07:08:00', '2025-07-03', NULL, 2, NULL),
(15, 'sara', 'vallejo', 1242483822, 1012353162, 1, '2025-07-04', '09:52:00', '2025-07-03', NULL, 2, NULL),
(16, 'cesar', 'esquivel', 909090, 1012353162, 3, '2025-07-04', '10:51:00', '2025-07-03', NULL, 2, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zonas_comunes`
--

CREATE TABLE `zonas_comunes` (
  `id_zonas_comu` int(11) NOT NULL,
  `nombre_zona` varchar(100) DEFAULT NULL,
  `capacidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `zonas_comunes`
--

INSERT INTO `zonas_comunes` (`id_zonas_comu`, `nombre_zona`, `capacidad`) VALUES
(3, 'salon comunal', 200),
(4, 'zona BBQ', 100),
(5, 'piscina', 50);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bloque`
--
ALTER TABLE `bloque`
  ADD PRIMARY KEY (`id_casa`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`nit`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id_horario`);

--
-- Indices de la tabla `licencias`
--
ALTER TABLE `licencias`
  ADD PRIMARY KEY (`id_licencia`),
  ADD KEY `id_tipo_licencia` (`id_tipo_licencia`),
  ADD KEY `nit` (`nit`);

--
-- Indices de la tabla `manzana`
--
ALTER TABLE `manzana`
  ADD PRIMARY KEY (`id_manzana`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`);

--
-- Indices de la tabla `motivo_visita`
--
ALTER TABLE `motivo_visita`
  ADD PRIMARY KEY (`id_mot_visi`);

--
-- Indices de la tabla `motivo_zonas`
--
ALTER TABLE `motivo_zonas`
  ADD PRIMARY KEY (`id_mot_zonas`);

--
-- Indices de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  ADD PRIMARY KEY (`id_paquete`),
  ADD KEY `id_usuarios` (`id_usuarios`),
  ADD KEY `id_vigilante` (`id_vigilante`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reservas`),
  ADD KEY `id_zonas_comu` (`id_zonas_comu`),
  ADD KEY `id_usuarios` (`id_usuarios`),
  ADD KEY `id_horario` (`id_horario`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_mot_zonas` (`id_mot_zonas`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`id_tipo_doc`);

--
-- Indices de la tabla `tipo_licencia`
--
ALTER TABLE `tipo_licencia`
  ADD PRIMARY KEY (`id_tipo_licencia`);

--
-- Indices de la tabla `tipo_vehiculos`
--
ALTER TABLE `tipo_vehiculos`
  ADD PRIMARY KEY (`id_tipo_vehi`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`documento`),
  ADD KEY `id_tipo_doc` (`id_tipo_doc`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `nit` (`nit`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id_vehiculos`),
  ADD KEY `id_tipo_vehi` (`id_tipo_vehi`),
  ADD KEY `id_usuarios` (`id_usuarios`),
  ADD KEY `id_marca` (`id_marca`);

--
-- Indices de la tabla `visitas`
--
ALTER TABLE `visitas`
  ADD PRIMARY KEY (`id_visita`),
  ADD KEY `id_usuarios` (`id_usuarios`),
  ADD KEY `id_mot_visi` (`id_mot_visi`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `zonas_comunes`
--
ALTER TABLE `zonas_comunes`
  ADD PRIMARY KEY (`id_zonas_comu`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bloque`
--
ALTER TABLE `bloque`
  MODIFY `id_casa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `manzana`
--
ALTER TABLE `manzana`
  MODIFY `id_manzana` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  MODIFY `id_paquete` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reservas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id_vehiculos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `visitas`
--
ALTER TABLE `visitas`
  MODIFY `id_visita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `zonas_comunes`
--
ALTER TABLE `zonas_comunes`
  MODIFY `id_zonas_comu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `licencias`
--
ALTER TABLE `licencias`
  ADD CONSTRAINT `licencias_ibfk_1` FOREIGN KEY (`id_tipo_licencia`) REFERENCES `tipo_licencia` (`id_tipo_licencia`),
  ADD CONSTRAINT `licencias_ibfk_2` FOREIGN KEY (`nit`) REFERENCES `empresa` (`nit`);

--
-- Filtros para la tabla `paquetes`
--
ALTER TABLE `paquetes`
  ADD CONSTRAINT `paquetes_ibfk_1` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`documento`),
  ADD CONSTRAINT `paquetes_ibfk_2` FOREIGN KEY (`id_vigilante`) REFERENCES `usuarios` (`documento`),
  ADD CONSTRAINT `paquetes_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_zonas_comu`) REFERENCES `zonas_comunes` (`id_zonas_comu`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`documento`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`id_horario`) REFERENCES `horario` (`id_horario`),
  ADD CONSTRAINT `reservas_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`),
  ADD CONSTRAINT `reservas_ibfk_5` FOREIGN KEY (`id_mot_zonas`) REFERENCES `motivo_zonas` (`id_mot_zonas`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_tipo_doc`) REFERENCES `tipo_documento` (`id_tipo_doc`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`),
  ADD CONSTRAINT `usuarios_ibfk_5` FOREIGN KEY (`nit`) REFERENCES `empresa` (`nit`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`id_tipo_vehi`) REFERENCES `tipo_vehiculos` (`id_tipo_vehi`),
  ADD CONSTRAINT `vehiculos_ibfk_2` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`documento`),
  ADD CONSTRAINT `vehiculos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`);

--
-- Filtros para la tabla `visitas`
--
ALTER TABLE `visitas`
  ADD CONSTRAINT `visitas_ibfk_1` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`documento`),
  ADD CONSTRAINT `visitas_ibfk_2` FOREIGN KEY (`id_mot_visi`) REFERENCES `motivo_visita` (`id_mot_visi`),
  ADD CONSTRAINT `visitas_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
