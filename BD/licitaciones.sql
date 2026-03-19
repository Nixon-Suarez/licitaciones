-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2026 at 01:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `licitaciones`
--

-- --------------------------------------------------------

--
-- Table structure for table `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `codigo_segmento` int(11) NOT NULL,
  `segmento` varchar(200) NOT NULL,
  `codigo_familia` int(11) NOT NULL,
  `familia` varchar(200) NOT NULL,
  `codigo_clase` int(11) NOT NULL,
  `clase` varchar(200) NOT NULL,
  `codigo_producto` int(11) NOT NULL,
  `producto` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `actividades`
--

INSERT INTO `actividades` (`id`, `codigo_segmento`, `segmento`, `codigo_familia`, `familia`, `codigo_clase`, `clase`, `codigo_producto`, `producto`) VALUES
(1, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101501, 'Gatos'),
(2, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101502, 'Perros'),
(3, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101504, 'Visón'),
(4, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101505, 'Ratas'),
(5, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101506, 'Caballos'),
(6, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101507, 'Ovejas'),
(7, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101508, 'Cabras'),
(8, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101509, 'Asnos'),
(9, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101510, 'Ratones'),
(10, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101511, 'Cerdos'),
(11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101512, 'Conejos'),
(12, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101513, 'Cobayas o conejillos de indias'),
(13, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101514, 'Primates'),
(14, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101515, 'Armadillos'),
(15, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101516, 'Ganado vacuno'),
(16, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101517, 'Camellos'),
(17, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101016, 'Pájaros y aves de corral', 10101601, 'Pollos vivos'),
(18, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101016, 'Pájaros y aves de corral', 10101602, 'Patos vivos'),
(19, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101016, 'Pájaros y aves de corral', 10101603, 'Pavos vivos'),
(20, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101016, 'Pájaros y aves de corral', 10101604, 'Gansos vivos');

-- --------------------------------------------------------

--
-- Table structure for table `api_tokens`
--

CREATE TABLE `api_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `token_hash` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL,
  `revoked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ofertas`
--

CREATE TABLE `ofertas` (
  `id` int(11) NOT NULL,
  `consecutivo` varchar(50) NOT NULL,
  `objeto` varchar(150) NOT NULL,
  `descripcion` varchar(400) DEFAULT NULL,
  `moneda` varchar(3) NOT NULL,
  `presupuesto` decimal(15,2) NOT NULL,
  `actividad_id` int(11) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `fecha_cierre` date DEFAULT NULL,
  `hora_cierre` time DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `creado_en` datetime DEFAULT current_timestamp(),
  `actualizado_en` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ofertas`
--

INSERT INTO `ofertas` (`id`, `consecutivo`, `objeto`, `descripcion`, `moneda`, `presupuesto`, `actividad_id`, `fecha_inicio`, `hora_inicio`, `fecha_cierre`, `hora_cierre`, `estado`, `creado_en`, `actualizado_en`) VALUES
(1, 'O-0001-26', 'Objeto', 'Objeto', 'COP', 123213.00, 1, '2026-01-07', '17:11:00', '2026-01-09', '17:11:00', 'creacion', '2026-01-07 17:11:19', '2026-01-08 08:16:50'),
(2, 'PO-0002-26', 'Objeto1', 'Descripción1', 'USD', 111123213.00, 1, '2026-01-08', '23:11:00', '2026-01-09', '20:09:00', 'creacion', '2026-01-07 19:10:06', '2026-01-08 16:11:15'),
(3, 'PO-0004-26', 'Pruebas', 'Pruebas', 'COP', 1232132.00, 1, '2026-01-09', '10:13:00', '2026-01-09', '11:13:00', 'creacion', '2026-01-08 08:13:14', '2026-01-08 16:11:04'),
(6, 'PO-0005-26', 'Objeto', 'Objeto', 'USD', 1232131.00, 1, '2026-01-08', '18:07:00', '2026-01-09', '18:07:00', 'creacion', '2026-01-08 16:11:21', '2026-01-08 16:13:03'),
(7, 'PO-0006-26', '1232132', '1323213', 'EUR', 123213.00, 1, '2026-01-08', '17:13:00', '2026-01-09', '17:13:00', 'creacion', '2026-01-08 16:13:37', '2026-01-08 16:13:37'),
(8, 'PO-0007-26', '123', '123', 'COP', 123.00, 11, '2026-01-08', '21:53:00', '2026-01-09', '22:53:00', 'creacion', '2026-01-08 21:53:50', '2026-01-08 21:53:50'),
(9, 'PO-0008-26', '123213', '123213', 'USD', 123213.00, 13, '2026-01-09', '11:32:00', '2026-01-10', '12:31:00', 'creacion', '2026-01-09 10:31:12', '2026-01-09 10:31:12'),
(10, 'PO-0009-26', 'Prueba1', 'Prueba1', 'USD', 12312.12, 15, '2026-01-21', '12:18:00', '2026-01-23', '11:18:00', 'creacion', '2026-01-21 11:18:41', '2026-01-21 11:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `ofertas_documentos`
--

CREATE TABLE `ofertas_documentos` (
  `id` int(11) NOT NULL,
  `licitacion_id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `archivo` varchar(255) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ofertas_documentos`
--

INSERT INTO `ofertas_documentos` (`id`, `licitacion_id`, `titulo`, `descripcion`, `archivo`, `ruta_archivo`, `creado_en`) VALUES
(2, 3, '213', '123', 'Prueba_de_desarrollo_FullStack_2025_7045_1767895588.pdf', '../views/docs/uploads/ofertas/', '2026-01-08 13:06:29'),
(3, 3, '123213', '213213', 'Prueba_de_desarrollo_FullStack_2025_9041_1767895668.pdf', '../views/docs/uploads/ofertas/', '2026-01-08 13:07:48'),
(7, 10, '123', '123', 'Prueba_de_desarrollo_FullStack_2025_7551_1769012469.pdf', '../views/docs/uploads/ofertas/', '2026-01-21 11:21:09');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `usuario_nombre` varchar(70) NOT NULL,
  `usuario_apellido` varchar(70) NOT NULL,
  `usuario_usuario` varchar(30) NOT NULL,
  `usuario_clave` varchar(200) NOT NULL,
  `usuario_creado` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_actualizado` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id`, `usuario_nombre`, `usuario_apellido`, `usuario_usuario`, `usuario_clave`, `usuario_creado`, `usuario_actualizado`) VALUES
(1, '', '', 'nixon', '', '2026-01-07 21:17:31', NULL),
(2, '', '', 'nixonS', '', '2026-01-07 21:49:19', NULL),
(3, 'nixon', 'suarez', 'nixonSM', '$2y$10$AwEwbzgceNTaRH6mL.HTvOBzD5IlGF0n3qvPI3gO1775QpNl6OShC', '2026-01-07 21:54:56', NULL),
(4, 'Pepito', 'Pepito', 'Pepito', '$2y$10$I7iuIQu9hc0YELnur8/.qOQ.7doPKs5LJG0i2vc4wLPsoI0nX266e', '2026-01-21 16:14:54', '2026-01-21 16:14:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ofertas`
--
ALTER TABLE `ofertas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `consecutivo` (`consecutivo`),
  ADD KEY `fk_ofertas_actividad` (`actividad_id`);

--
-- Indexes for table `ofertas_documentos`
--
ALTER TABLE `ofertas_documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_documentos_oferta` (`licitacion_id`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_usuario` (`usuario_usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49023;

--
-- AUTO_INCREMENT for table `api_tokens`
--
ALTER TABLE `api_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ofertas`
--
ALTER TABLE `ofertas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ofertas_documentos`
--
ALTER TABLE `ofertas_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ofertas`
--
ALTER TABLE `ofertas`
  ADD CONSTRAINT `fk_ofertas_actividad` FOREIGN KEY (`actividad_id`) REFERENCES `actividades` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `ofertas_documentos`
--
ALTER TABLE `ofertas_documentos`
  ADD CONSTRAINT `fk_documentos_oferta` FOREIGN KEY (`licitacion_id`) REFERENCES `ofertas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
