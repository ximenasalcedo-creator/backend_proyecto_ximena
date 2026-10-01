-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-10-2026 a las 03:08:38
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Cabello', 'Productos para el cuidado del cabello'),
(2, 'Uñas', 'Productos para manicure y pedicure'),
(3, 'Facial', 'Productos para el cuidado facial'),
(4, 'Maquillaje', 'Productos de maquillaje'),
(5, 'Velas', 'Velas y productos aromáticos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `servicio` varchar(100) NOT NULL,
  `descripcion` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `fecha`, `nombre`, `telefono`, `servicio`, `descripcion`) VALUES
(22, '2026-09-04 12:56:00', 'Alison Salcedo', '3192328968', 'Uñas acrílicas', 'acrílicas ojo de gato'),
(23, '2026-10-09 17:30:00', 'luz Maria', '3192328968', 'Manicure', ''),
(24, '2026-10-09 17:35:00', 'Lorena Sanchez', '319232878', 'Uñas acrílicas', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `telefono`, `email`, `fecha_registro`) VALUES
(1, 'Laura Gómez', '3001234567', 'laura@gmail.com', '2026-04-05 22:04:40'),
(2, 'Camila Torres Garcia', '3019876543', 'camila@gmail.com', '2026-04-05 22:04:40'),
(3, 'Valentina Ríos Salcedo', '3024567890', 'valentina@gmail.com', '2026-04-05 22:04:40'),
(4, 'Sofía Martínez', '3007654321', 'sofia@gmail.com', '2026-04-05 22:04:40'),
(5, 'Daniela López', '3041122334', 'daniela@gmail.com', '2026-04-05 22:04:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `venta_id`, `producto_id`, `cantidad`, `subtotal`) VALUES
(4, 9, 1, 5, 50000.00),
(5, 9, 8, 8, 80000.00),
(6, 10, 4, 1, 12000.00),
(7, 10, 7, 1, 37000.00),
(8, 10, 5, 1, 30000.00),
(9, 10, 8, 1, 10000.00),
(10, 11, 7, 7, 259000.00),
(11, 12, 6, 11, 110000.00),
(12, 12, 5, 9, 270000.00),
(13, 13, 7, 7, 259000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `especialidad`, `telefono`) VALUES
(1, 'Ana Pérez', 'Manicure', '3101112233'),
(2, 'Luisa Fernández', 'Pedicure', '3112223344'),
(3, 'María Rodríguez', 'Peinados', '3123334455'),
(4, 'Carolina Sánchez', 'Maquillaje', '3134445566');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `cita_id` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `metodo` enum('efectivo','tarjeta','transferencia') DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `stock`, `precio`) VALUES
(1, 'Esmalte rojo', 20, 10000.00),
(2, 'Kit manicure', 10, 30000.00),
(3, 'Removedor de esmalte', 15, 8000.00),
(4, 'Base fortalecedora', 12, 12000.00),
(5, 'Esmalte verde', 30, 30000.00),
(6, 'Esmalte blanco', 15, 10000.00),
(7, 'Decoraciones', 7, 37000.00),
(8, 'Monomero', 55, 10000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `duracion` int(11) DEFAULT NULL COMMENT 'Duración en minutos'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `precio`, `duracion`) VALUES
(1, 'Manicure tradicional', 25000.00, 60),
(2, 'Pedicure spa', 40000.00, 90),
(3, 'Uñas acrílicas', 80000.00, 120),
(4, 'Maquillaje profesional', 70000.00, 60),
(5, 'Peinado elegante', 60000.00, 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `identificacion` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `celular` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `rol` varchar(100) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `identificacion`, `nombre`, `direccion`, `celular`, `email`, `rol`, `clave`) VALUES
(2, 1012345678, 'Ana Torres', 'Guarne, Antioquia', '3001234567', 'ana@alanbeauty.com', 'Vendedor', '123456'),
(3, 1023456789, 'Laura Gómez', 'Medellín, Antioquia', '3012345678', 'laura@alanbeauty.com', 'Cliente', '654321'),
(4, 1034567890, 'Camila Rojas', 'Rionegro, Antioquia', '3023456789', 'camila@alanbeauty.com', 'Cliente', '111111'),
(5, 1045678901, 'Valentina Pérez', 'Marinilla, Antioquia', '3034567890', 'valentina@alanbeauty.com', 'Cliente', '222222'),
(6, 1056789012, 'Sofía Ramírez', 'La Ceja, Antioquia', '3045678901', 'sofia@alanbeauty.com', 'Cliente', '333333'),
(7, 1019993508, 'Alison Salcedo', 'Cll 41B #47-35', '3192328968', 'asalcedog@unal.edu.co', 'Admin', 'Alana12*'),
(8, 1019993508, 'Luz Andrea Garcia', 'Guarne, Antioquia', '3115934846', 'Lgarciahoyo@uniminuto.edu.co', 'Supervisor', '1234'),
(9, 1022349752, 'Jhon Edison', 'Mosquera', '3102637690', 'Jhonsalcedo160@gmail.com', 'Supervisor', '0000'),
(10, 2147483647, 'Salome Zapata', 'Rio negro, Antioquia', '3124567654', 'SalomeZapa@gmail.com', 'Cliente', 'salome'),
(12, 1066543276, 'Alexander Espinosa', 'Mosquera, Cundinamarca', '3173452796', 'Alexandersalcedo@gmail.com', 'Vendedor', 'Alex1234'),
(13, 1019976543, 'Stella Espinosa', 'Ceja, Antioquia', '3145673423', 'Stellae@gmail.com', 'Vendedor', '567483'),
(14, 1097654348, 'Mariana Lopez Velasquez', 'Rio negro, Antioquia', '3215643217', 'LopezV@gmial.com', 'Cliente', '12345678');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `fo_cliente` int(11) NOT NULL,
  `productos` text DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL,
  `fo_vendedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `fo_cliente`, `productos`, `subtotal`, `fecha`, `total`, `fo_vendedor`) VALUES
(3, 3, 'Esmalte morado, Brillo UV', 42000.00, '2026-08-27 19:20:00', 42000.00, 2),
(4, 4, 'Base rubber, Esmalte rosado', 65000.00, '2026-08-28 16:45:00', 65000.00, 2),
(9, 1, '[{\"producto_id\":1,\"cantidad\":5,\"precio\":10000,\"subtotal\":50000,\"nombre\":\"Esmalte rojo\"},{\"producto_id\":8,\"cantidad\":8,\"precio\":10000,\"subtotal\":80000,\"nombre\":\"Monomero\"}]', 130000.00, '2026-09-15 05:00:00', 130000.00, 12),
(10, 5, '[{\"producto_id\":4,\"cantidad\":1,\"precio\":12000,\"subtotal\":12000,\"nombre\":\"Base fortalecedora\"},{\"producto_id\":7,\"cantidad\":1,\"precio\":37000,\"subtotal\":37000,\"nombre\":\"Decoraciones\"},{\"producto_id\":5,\"cantidad\":1,\"precio\":30000,\"subtotal\":30000,\"nombre\":\"Esmalte verde\"},{\"producto_id\":8,\"cantidad\":1,\"precio\":10000,\"subtotal\":10000,\"nombre\":\"Monomero\"}]', 89000.00, '2026-09-15 05:00:00', 89000.00, 12),
(11, 1, '[{\"producto_id\":7,\"cantidad\":7,\"precio\":37000,\"subtotal\":259000,\"nombre\":\"Decoraciones\"}]', 259000.00, '2026-09-29 05:00:00', 259000.00, 2),
(12, 1, '[{\"producto_id\":6,\"cantidad\":11,\"precio\":10000,\"subtotal\":110000,\"nombre\":\"Esmalte blanco\"},{\"producto_id\":5,\"cantidad\":9,\"precio\":30000,\"subtotal\":270000,\"nombre\":\"Esmalte verde\"}]', 380000.00, '2026-09-29 05:00:00', 380000.00, 12),
(13, 1, '[{\"producto_id\":7,\"cantidad\":7,\"precio\":37000,\"subtotal\":259000,\"nombre\":\"Decoraciones\"}]', 259000.00, '2026-09-29 05:00:00', 259000.00, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cita_id` (`cita_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `cliente_id` (`fo_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id_venta`),
  ADD CONSTRAINT `detalle_ventas_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`fo_cliente`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
