-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-11-2024 a las 19:27:14
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
-- Base de datos: `paradise`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `horario_inicio` time NOT NULL,
  `horario_cierre` time NOT NULL,
  `formato` enum('individual','grupal') NOT NULL,
  `capacidad_turno` tinyint(3) NOT NULL,
  `duracion` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `dia_inicio` varchar(255) NOT NULL,
  `dia_fin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `nombre`, `descripcion`, `horario_inicio`, `horario_cierre`, `formato`, `capacidad_turno`, `duracion`, `imagen`, `dia_inicio`, `dia_fin`) VALUES
(42, 'Masajes', 'Disfruta de un masaje relajante diseñado para aliviar el estrés y las tensiones musculares acumuladas. Nuestro servicio de masajes utiliza técnicas avanzadas para revitalizar cuerpo y mente, proporcionando una experiencia de bienestar completa.', '10:00:00', '19:00:00', 'individual', 1, 60, '../IMAGENES/masaje.png', 'martes', 'sábado'),
(45, 'Spa', 'Nuestros tratamientos de spa ofrecen una experiencia completa de relajación y rejuvenecimiento. Disfruta de faciales, masajes, y más en un ambiente que combina lujo y tranquilidad. Perfecto para grupos pequeños que buscan una escapada de bienestar única.', '10:00:00', '19:00:00', 'grupal', 3, 120, '../IMAGENES/spa.jpg', 'martes', 'sábado'),
(47, 'Excursiones', 'asd', '19:36:00', '23:36:00', 'grupal', 1, 20, '../IMAGENES/excursiones.png.jpg', 'lunes', 'lunes'),
(48, 'Yoga', 'Descripcion de actividad', '18:37:00', '18:37:00', 'grupal', 2, 20, '../IMAGENES/yoga.jpg', 'lunes', 'miércoles'),
(50, 'Peluqueria', 'Disfruta de un servicio exclusivo para tu cabello brindado por los mejores profesionales de la peluquería.', '07:30:00', '20:30:00', 'grupal', 5, 90, '../IMAGENES/peluqueria.jpg', 'lunes', 'sábado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `huespedes`
--

CREATE TABLE `huespedes` (
  `huesped_nombre` varchar(100) NOT NULL,
  `huesped_dni` varchar(8) NOT NULL,
  `huesped_email` varchar(255) NOT NULL,
  `huesped_integrantes` int(2) NOT NULL,
  `huesped_direccion` varchar(255) NOT NULL,
  `huesped_ingreso` date NOT NULL,
  `huesped_egreso` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `huespedes`
--

INSERT INTO `huespedes` (`huesped_nombre`, `huesped_dni`, `huesped_email`, `huesped_integrantes`, `huesped_direccion`, `huesped_ingreso`, `huesped_egreso`) VALUES
('Pedro Sánchez', '11223344', 'pedro.sanchez@example.com', 2, 'Paseo de la Reforma 202', '2024-11-05', '2024-11-09'),
('Juan Pérez', '12345678', 'juan.perez@example.com', 2, 'Calle Falsa 123', '2024-11-01', '2024-11-05'),
('Andrés Blanco', '18765432', 'andres.blanco@example.com', 3, 'Plaza Nueva 1515', '2024-11-18', '2024-11-22'),
('Gabriela Silva', '19764321', 'gabriela.silva@example.com', 2, 'Urbanización Central 1010', '2024-11-13', '2024-11-17'),
('Marta Rojas', '19876543', 'marta.rojas@example.com', 2, 'Parque de la Luz 606', '2024-11-09', '2024-11-13'),
('Laura Martínez', '20334455', 'laura.martinez@example.com', 3, 'Callejón de los Milagros 303', '2024-11-06', '2024-11-10'),
('Natalia Ruiz', '21456789', 'natalia.ruiz@example.com', 2, 'Paseo del Río 1414', '2024-11-17', '2024-11-21'),
('María García', '23456789', 'maria.garcia@example.com', 3, 'Av. Siempre Viva 456', '2024-11-02', '2024-11-06'),
('Luis Moreno', '26789012', 'luis.moreno@example.com', 4, 'Sector Verde 909', '2024-11-12', '2024-11-16'),
('Jorge Ramírez', '27568899', 'jorge.ramirez@example.com', 1, 'Esquina de los Vientos 404', '2024-11-07', '2024-11-11'),
('Rosa Delgado', '31027654', 'rosa.delgado@example.com', 1, 'Cerro Alto 1616', '2024-11-19', '2024-11-23'),
('Sofía Herrera', '31098765', 'sofia.herrera@example.com', 5, 'Lomas del Valle 505', '2024-11-08', '2024-11-12'),
('Elena Vargas', '34251678', 'elena.vargas@example.com', 1, 'Barrio Norte 808', '2024-11-11', '2024-11-15'),
('Clara Jiménez', '34512345', 'clara.jimenez@example.com', 1, 'Puente Largo 1212', '2024-11-15', '2024-11-19'),
('Carlos López', '34567890', 'carlos.lopez@example.com', 1, 'Boulevard Central 789', '2024-11-03', '2024-11-07'),
('Fernando Cruz', '38901234', 'fernando.cruz@example.com', 4, 'Rincón del Mar 1313', '2024-11-16', '2024-11-20'),
('Pablo Ortega', '42319876', 'pablo.ortega@example.com', 3, 'Calle Alta 1111', '2024-11-14', '2024-11-18'),
('Hugo Navarro', '42987654', 'hugo.navarro@example.com', 5, 'Mirador Azul 1717', '2024-11-20', '2024-11-24'),
('Ariel Seijo', '44514161', 'glassito.contacto@gmail.com', 1, 'Avenida falsa 123', '2024-11-01', '2024-11-05'),
('Diego Fernández', '45671234', 'diego.fernandez@example.com', 3, 'Zona Franca 707', '2024-11-10', '2024-11-14'),
('Ana Torres', '45678901', 'ana.torres@example.com', 4, 'Plaza Mayor 101', '2024-11-04', '2024-11-08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reset_token` varchar(6) NOT NULL,
  `token_expiration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` varchar(50) NOT NULL,
  `huesped_dni` varchar(20) DEFAULT NULL,
  `actividad_id` int(11) DEFAULT NULL,
  `cupo_id` int(11) DEFAULT NULL,
  `horario` time DEFAULT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `huesped_dni`, `actividad_id`, `cupo_id`, `horario`, `fecha`) VALUES
('79-1', '11223344', 45, 79, '11:00:00', '2024-09-05'),
('79-2', '20334455', 45, 79, '12:30:00', '2024-09-06'),
('86-1', '42987654', 42, 86, '14:30:00', '2024-09-19'),
('86-2', '12345678', 42, 86, '15:00:00', '2024-09-20'),
('87-1', '23456789', 47, 87, '16:30:00', '2024-09-21'),
('87-2', '34567890', 47, 87, '17:00:00', '2024-09-22'),
('88-1', '45678901', 48, 88, '18:30:00', '2024-09-23'),
('88-2', '11223344', 48, 88, '19:30:00', '2024-09-24'),
('91-1', '34251678', 45, 87, '11:30:00', '2024-09-29'),
('91-2', '26789012', 45, 87, '12:00:00', '2024-09-30'),
('93-1', '44514161', 50, 93, '08:00:00', '2024-11-26'),
('93-2', '12345678', 50, 93, '08:00:00', '2024-11-26'),
('93-3', '34567890', 50, 93, '08:00:00', '2024-11-26'),
('93-4', '23456789', 50, 93, '08:00:00', '2024-11-26'),
('93-5', '45678901', 50, 93, '08:00:00', '2024-11-26'),
('95-1', '31098765', 50, 95, '13:00:00', '2024-11-26'),
('95-2', '26789012', 50, 95, '13:00:00', '2024-11-26'),
('95-3', '27568899', 50, 95, '13:00:00', '2024-11-26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos_horarios`
--

CREATE TABLE `turnos_horarios` (
  `id` int(11) NOT NULL,
  `actividad_id` int(11) NOT NULL,
  `horario` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos_horarios`
--

INSERT INTO `turnos_horarios` (`id`, `actividad_id`, `horario`) VALUES
(77, 42, '11:00:00'),
(78, 42, '12:00:00'),
(79, 42, '13:00:00'),
(86, 45, '14:00:00'),
(87, 45, '11:04:00'),
(88, 45, '16:00:00'),
(93, 50, '08:00:00'),
(95, 50, '13:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `contraseña`, `isAdmin`, `fecha_creacion`) VALUES
(19, 'admin@ejemplo.com', '$2y$10$gSHemHPvZ4Hxzs0b0ZZhEO1mX4j1XyzlZTfT4N4u9xcwwknRkqoHS', 1, '2024-11-02 18:32:24'),
(20, 'recepcionista@ejemplo.com', '$2y$10$k8aAgMo8srscLJ4P9ks92OQAwpVZlMVLmbYMsYnnzdpH6NjolRNlO', 0, '2024-11-02 18:38:42');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `huespedes`
--
ALTER TABLE `huespedes`
  ADD PRIMARY KEY (`huesped_dni`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`,`fecha`),
  ADD KEY `fk_huesped` (`huesped_dni`),
  ADD KEY `fk_actividad` (`actividad_id`),
  ADD KEY `fk_cupo` (`cupo_id`);

--
-- Indices de la tabla `turnos_horarios`
--
ALTER TABLE `turnos_horarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actividad_id` (`actividad_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `turnos_horarios`
--
ALTER TABLE `turnos_horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_actividad` FOREIGN KEY (`actividad_id`) REFERENCES `actividades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cupo` FOREIGN KEY (`cupo_id`) REFERENCES `turnos_horarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_huesped` FOREIGN KEY (`huesped_dni`) REFERENCES `huespedes` (`huesped_dni`) ON DELETE CASCADE;

--
-- Filtros para la tabla `turnos_horarios`
--
ALTER TABLE `turnos_horarios`
  ADD CONSTRAINT `turnos_horarios_ibfk_1` FOREIGN KEY (`actividad_id`) REFERENCES `actividades` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
