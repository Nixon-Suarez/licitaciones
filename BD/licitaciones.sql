-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2026 at 04:25 PM
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
  `usuario_id` int(11) NOT NULL,
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

INSERT INTO `actividades` (`id`, `usuario_id`, `codigo_segmento`, `segmento`, `codigo_familia`, `familia`, `codigo_clase`, `clase`, `codigo_producto`, `producto`) VALUES
(1, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101501, 'Gatos'),
(2, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101502, 'Perros'),
(4, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101505, 'Ratas'),
(5, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101506, 'Caballos'),
(6, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101507, 'Ovejas'),
(7, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101508, 'Cabras'),
(9, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101510, 'Ratones'),
(10, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101511, 'Cerdos'),
(11, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101512, 'Conejos'),
(13, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101514, 'Primates'),
(14, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101515, 'Armadillos'),
(15, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101516, 'Ganado vacuno'),
(16, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101015, 'Animales de granja', 10101517, 'Camellos'),
(20, 11, 10, 'Material Vivo Vegetal y Animal, Accesorios y Suministros', 1010, 'Animales vivos', 101016, 'Pájaros y aves de corral', 10101604, 'Gansos vivos'),
(49026, 11, 123123, 'PruebaP1', 123123, 'PruebaP1', 123123, 'PruebaP1', 123123, 'PruebaP1'),
(49027, 3, 123123, 'PruebaP2', 123213, 'PruebaP2', 123213, 'PruebaP2', 213213, 'PruebaP2');

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

--
-- Dumping data for table `api_tokens`
--

INSERT INTO `api_tokens` (`id`, `user_id`, `token`, `token_hash`, `created_at`, `expires_at`, `revoked`) VALUES
(1, 3, '2b855110bc13065590eef67380bd282e006037cfd1dfd1945983e06f29dac5f1', '29a6fda9258a40d5f41d2cc2e590bd1d06efdbf01e9f073ad098bf6c520d6e23', '2026-03-18 19:42:51', '2026-03-19 19:42:51', 0),
(2, 3, '8f19e60cc96c3fc71433a80c38e3fa610cd159cf3121dd35630eb235366dfcae', 'ae7bf9730f79d3939768d1e518f69b4b75e65887eb07cedd883cc7b7a8cb519b', '2026-03-20 19:37:04', '2026-03-21 19:37:03', 0),
(3, 3, '2a39e50616fd9f71a1c4225c1139222088bc10f9ef42cb9f4b4903b75fe81bc1', 'dfe0a0166eba1c1132b84f68e757856204e4a5004bb66b6a6cbfe5185d807d86', '2026-03-21 15:34:17', '2026-03-22 15:34:15', 0),
(4, 3, 'c3675ffdcae217a5072e2536e666b65ee0b8f1200e7bdd87625757b3ea70d715', '3c434691926668ab48066b5d4f7c24229620d9b6e267c4dd5a00f372874ef5bc', '2026-03-21 15:45:53', '2026-03-22 15:45:53', 0),
(5, 3, '356747f60f7f1744c74a6595b6a48ee74d8810c4a3dc97da049093ba9fef3ff2', '7b2d570ccdf6c4039fcb5929bd643629f9c8d140362f17b4cfc64ff0f383cfc6', '2026-03-21 15:56:50', '2026-03-22 15:56:50', 0),
(6, 3, '0ee399a98176bd98b56c3b4db074360792f71d0dfce455daab96fb5169db998a', '1f30357ca0fb13be0f5718f59770f0a9f298e34d9936e873d728441a1c5ca19d', '2026-03-21 16:02:00', '2026-03-22 16:02:00', 0),
(7, 3, '00efe5e262864a8894e4c0d23bcb4c19c4be89f7cb00eb8d4f2bb21f3e697bcf', '5b6c5ca546d9859b30af772b7147001c5fc34097a436d28f659d9bb844551f3f', '2026-03-21 16:02:46', '2026-03-22 16:02:46', 0),
(8, 3, '26ef3f65adf335e63c268e3ca04eaa861724a5bca928a9ff8a2c29edce195352', '7759485e7af885f61b43e3fc8e05216318e87c3982ac185b4d4a5a8c8c77054e', '2026-03-21 16:03:10', '2026-03-22 16:03:10', 0),
(9, 3, '37ac67e864396e43b05bc924df149d8e77798911ad2d2efe33c5b5c2c6bb38ae', 'c3ddbf39474659ff79e8e64ed5e2ee466450f871c3c8708106928bd714dcd218', '2026-03-22 11:15:32', '2026-03-23 11:15:31', 0),
(10, 3, '21c891609fb465168bab036029b09bc81f0c7d1a1c612aaf828e6bff79299a71', '0eed61cd73cd7c102dbddc7067dad7d3db8756b8142fad2d8c10c00694ea549b', '2026-03-22 11:25:06', '2026-03-23 11:25:06', 0),
(11, 3, 'd3e35be1816224d091d6cedd66a4d04c3b2b198ea27ade6c909713fba53089b6', 'ea7c57ba971d589b8b031bb76d4771b3409af640f5e970144fb65a6aadf2afdd', '2026-03-23 10:59:26', '2026-03-24 10:59:25', 0),
(12, 3, '3dcbfdec0217f1634263b8897f705eabde78050556b6247e5922bac9cf1060de', 'ab8d82a57f2b044e9c208f6a0b6ff46070295b9fc0efb290a4e3e4a4f8d18f1f', '2026-03-23 11:00:00', '2026-03-24 11:00:00', 0),
(13, 3, 'f7f627324586a8a4f797da948d28b047dde25ff76eab54ea52d279167d9bd6c6', '98897402ea20aedd1abf160285ae7301f6d14c73bc1316df1ca81420ea482cf9', '2026-03-23 11:31:22', '2026-03-24 11:31:22', 0),
(14, 3, '8272681d27f9cbf13fe07af521b71b8843a55e62b40c2f0ee529327187cdd4ba', '5672dee4584d5bf4756b53589f0e270740883a979a214626fd295308d22d10c9', '2026-03-28 18:26:40', '2026-03-29 18:26:39', 0),
(15, 3, 'a7cb78d10d8e349f572f1f7a1ed931b5d75660809515f8357970cb88e4cc3bef', 'f50b58cfb2ada30d2348c17b09699699e01b37cf07d2d519665a53023e244518', '2026-03-28 18:48:31', '2026-03-29 18:48:31', 0),
(16, 3, '1460111e5610ec1370d4ab9241637e49a0cf51ab809ea6ecad44021e87d94cc0', '50393d6d0fa5fabe562b0f7ffea54573d5568f295fc396bff8d2654ac59137bc', '2026-03-30 19:14:30', '2026-03-31 19:14:30', 0),
(17, 3, '5fab58fab1f13a1d2492459a186336505f31fd116f7abefacdc71ffbd79a35db', 'e4d558a02eb4013aee0ad728942439f758ea6e301abdbedd9bf29bda480d0af3', '2026-03-31 19:49:45', '2026-04-01 19:49:44', 0),
(18, 3, '158f7e7791d645012b968844720b448fb0803ae7ae477f6f7065fe10ab2dd0d4', '66566253c9078863a34c98c86921a72677d173a28e073f61d6283ebc2e226a4d', '2026-04-02 14:13:15', '2026-04-03 14:13:15', 0),
(19, 3, '1c4ab8b74d4143a203822c51e23f57ba3299e5885d7f21aeecff542473a6edca', '1fbc3c375cba7de606a82b659e49bef016c2a4d8510ce0fa980161b257cf1767', '2026-04-03 16:15:51', '2026-04-04 16:15:50', 0),
(20, 3, '19d6cbc8dabb04c3a7c03fcc4e4f82974f6c8d0b3a85d8259016bd89a3c693c9', '64ccbc0729424514e5a8a0d9060cd6256b5ba507e6e9bb37d9fb03e3383c5d8b', '2026-04-04 12:39:40', '2026-04-05 12:39:39', 0),
(21, 3, '7847119557583286505cb38cb7462674e4f590e665da0511914033fd8c7c9720', '661fec4d81794bc67ce2a4fefb468a1318c6cd98ed3b93a6831f62d6a5c20a76', '2026-04-04 12:39:40', '2026-04-05 12:39:39', 0),
(22, 3, '98756d1f6fe637300cf407acb8cfd6d2eaca94fd0e54565cc63b0e6e452a79da', 'a794376ec1a6c16e52b17960b010c7ead6a4a96e44661b7698c717741535f4c9', '2026-04-05 11:49:27', '2026-04-06 11:49:26', 0),
(23, 3, '068a3e159291a93600526db1640e51e7db6d08b729e77ef7dc69757c901792e9', 'e170ea178b9fffb1ddfc85a4a2fdee0820efd7b9e40c40594ac9a6aaa64aa405', '2026-04-05 12:08:05', '2026-04-06 12:08:05', 0),
(24, 3, 'fe5ef6a8e231ca9976f511134f1b37560b7a9358f07f96d7192c28484c26e4b5', '2397cd1b37f622c142a2980e7e586d0d7bb5425bdd163112a5f6921d5e3fdba0', '2026-04-06 18:20:41', '2026-04-07 18:20:40', 0),
(25, 3, 'abd227906f55b7b60637bd9a4b0af6f0a4dfa24010dda332d32f2247e52c074d', '7b6f34aef9ab9f2f068786aaff3dc0163a9f8d3dad25e004fa4c57e4679e6c07', '2026-04-07 18:45:11', '2026-04-08 18:45:10', 0),
(26, 3, 'ad7056a4dc7e4ae7a2a157bfe3c31484c82455d2a9542b2fe59cf08409e248c3', '697563a93e1c73f10011a5c971f8241c30f1967f7399d31c43d6d7d8e150bda3', '2026-04-07 19:26:14', '2026-04-08 19:26:14', 0),
(27, 3, '1210137cf47eff128ce3c413492814ab8a8f920c1b390ef83f6028a0ec0cf4df', '01212983b16ab3cba6a1db51caa1ad8e118ee5e13f04164b277821abf0a998ef', '2026-04-18 11:21:09', '2026-04-19 11:21:09', 0),
(28, 3, '8d371680c2a58029e66e019137cc5b33ec78f689722832af35ae60407bab069a', '38097258cfee6a328e13aad289623c7fce9697dda9e8940106b295c6f00897a3', '2026-10-11 11:25:42', '2026-10-12 11:25:42', 0),
(29, 6, '3723b8930684bc4668f952ab5b00410661315fdec26fdae000b3577ea2be7d56', 'db62acb7249a964a43a229c3050714bfe4b74d8646a88c2a4cf059325f803844', '2026-10-24 15:28:39', '2026-10-25 15:28:38', 0),
(30, 6, 'd5867825840d3f97b547a8d57f365de272018eae205781343073b7e03ceb66d6', 'fe2beef60029f751e26ae20a4aaba270c3a71726c10ebd526596a3160f7589b0', '2026-10-24 15:28:39', '2026-10-25 15:28:37', 0),
(31, 6, 'b7fda3d8906e6838d2f7645701c5cd52e260da3b500976128c824898dac93d3d', '7dbb6136ab5dc1737ff3cc72a0f038974037c1c19a2b05ebe8b8f9eac02e324e', '2026-10-24 16:00:34', '2026-10-25 16:00:34', 0),
(32, 6, '3168ed72a0db13feb15a89caa1ee99d74d060e8da7289be4fc2aa62fe5a509fa', 'f1e4554bed3117c5eda3733b6506b19d5e26df4d1ab95cbf06ee0efc9b20d7da', '2026-10-24 17:45:50', '2026-10-25 17:45:49', 0),
(33, 6, '6fff820a7c3d0cfc3b578cc5c6e059174ea952e7016d92fb363e9b12796c35ec', '83f75d23ef575f40a3537fceb7f677f8946242ad6abd4e1a2690eca8a5e40fb7', '2026-10-24 22:18:29', '2026-10-25 22:18:29', 0),
(34, 6, '95d925ec2d6b3f96e33a6cffa960a4dcbabd844b010cb9d8d14ef71e0b9404d3', '5c296b0bb706d1dfe439be2fd55b9fa163056ebffbdaccd3b0160ca75d27c33b', '2026-10-25 11:10:54', '2026-10-26 11:10:52', 0),
(35, 8, '5f226b7f224d09bf1733e7518b9a9befb13ffdce92ceaebf4b3ba832e53f5510', '085105fecabfae33a79726e1bfa9e16390cebf639ca288d0c9d4bdc3fe7bf9f0', '2026-10-25 15:00:43', '2026-10-26 15:00:43', 0),
(36, 11, '1edb8411531fae301409783d5bcc7adf008da3e2df325c2ccc6f8b36a7ffc82b', '13718c29c0964ba3f39ab3aa7906578ea13bc4dbe942f263a894bb53ec2d8edb', '2026-10-25 15:38:01', '2026-10-26 15:38:01', 0),
(37, 11, '85ffc50861d193acd472bff5ac81ce5d410f45c7d9ab23e17812054d25d905c3', '3b3b171e5ebbaff50811625868505f8790b93c2cd3d4cca8b198e649be295e0f', '2026-10-25 15:45:56', '2026-10-26 15:45:56', 0),
(38, 11, 'a48322d1cab3f54cd2df69adc4ff7cd9b38bc2def932a898e0a8603a6fde6cef', '7df7f5fd0c9c6e1248e1eb85daab303eed811d4d2f500ed7f1153d224a39b4a0', '2026-10-25 16:31:41', '2026-10-26 16:31:41', 0),
(39, 6, 'bcfb44741828df2c1306240cbd4fe73f6e3308344b54e5638cc4a1677a4d8c22', '906e7f5be45525b5ff2bd6156e63184133453bf96ed86dd93b3c3443ec15d4a4', '2026-10-25 16:37:44', '2026-10-26 16:37:44', 0),
(40, 3, '3d82cb6859c1b05a1db61a0150d8a8ed61c1ba8b71c5a3dc1d278b2be76d95a3', '637fe93b055233ca00f9fb709f8b8c3bc6d0f512871dd390f9018bdbe506a2b9', '2026-10-25 16:40:04', '2026-10-26 16:40:04', 0),
(41, 6, '3244540779cdfef840ce2cf56d0e0ef1e7f3ee72ac750d6812345ecc1f68e87f', '150db9a53b4c48e8c93a47a570d2fdeeeeb4d57b5ac42d8a2252a136d894a93f', '2026-10-25 16:51:49', '2026-10-26 16:51:49', 0),
(42, 11, '26ff6916a257b19cc79c386533ecf0f8b5b6eb0f9a37aff799652bfff4686ea7', '5974c56e6b2e2a95e19db1ad0f786902733bd996291525655b72c2a8245dac82', '2026-10-25 17:28:36', '2026-10-26 17:28:36', 0),
(43, 11, 'ff20cadae7c02e82668553130f501f23c3e6141330d3a215c920e18a18b1c2ee', 'bda48a1d5f4bf0bffe47daced72f3c3f1f9427b9f18fbf0b4e0006bc41f48896', '2026-10-25 20:35:02', '2026-10-26 20:35:02', 0),
(44, 11, '8e33f2b774a9b30b25f5e6a1d7fc7833f1d06e08f5f745af7367480ec629cd25', '6ecefaa9aa9637d75e1d3e87c9d1cccef5d109814412e7a57590f48e0c120d82', '2026-10-26 08:49:02', '2026-10-27 08:49:02', 0),
(45, 11, 'bdd10b981f58422853d88a4c9f3555ddf85acb9b0e959562a1a81e3b69acd328', '9fd60229c985c141752d31da22c5daf087b4307f66b76df0785ae1ba7daac602', '2026-10-26 09:37:30', '2026-10-27 09:37:30', 0),
(46, 11, '8093b28037b2d7ccd7ebf30498fa945854f574fb7507bb20fd86f8bdb5d60c77', '35a2517b3f313c60679747a59ec1c54d3b30f6e7342ef0f66c2cbe8e8a508fcc', '2026-10-26 09:47:46', '2026-10-27 09:47:46', 0),
(47, 11, 'd2bbe6d7806b64fc2e642b8c2ff2900c7c7a6d776d1ec5ea486ce698e780e96d', 'cf5ebab4776e3f0e1116db37557ba034c673d7aae5deab99af0ca80b9be84e5a', '2026-10-26 09:47:56', '2026-10-27 09:47:56', 0);

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
  `usuario_id` int(11) NOT NULL,
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

INSERT INTO `ofertas` (`id`, `consecutivo`, `objeto`, `descripcion`, `moneda`, `presupuesto`, `actividad_id`, `usuario_id`, `fecha_inicio`, `hora_inicio`, `fecha_cierre`, `hora_cierre`, `estado`, `creado_en`, `actualizado_en`) VALUES
(1, 'O-0001-26', 'Objeto1', 'Descripción1', 'COP', 11111.00, 1, 11, '2026-01-01', '17:11:00', '2026-01-03', '17:11:00', 'creacion', '2026-01-07 17:11:19', '2026-10-25 16:13:37'),
(2, 'PO-0002-26', 'Objeto1', 'Descripción1', 'USD', 111123213.00, 1, 11, '2026-01-08', '23:11:00', '2026-01-09', '20:09:00', 'creacion', '2026-01-07 19:10:06', '2026-10-25 16:13:37'),
(3, 'PO-0004-26', 'Pruebas', 'Pruebas', 'COP', 1232132.00, 1, 11, '2026-01-09', '10:13:00', '2026-01-09', '11:13:00', 'creacion', '2026-01-08 08:13:14', '2026-10-25 16:13:37'),
(6, 'PO-0005-26', 'Objeto', 'Objeto', 'USD', 1232131.00, 1, 11, '2026-01-08', '18:07:00', '2026-01-09', '18:07:00', 'creacion', '2026-01-08 16:11:21', '2026-10-25 16:13:37'),
(7, 'PO-0006-26', '1232132', '1323213', 'EUR', 123213.00, 1, 11, '2026-01-08', '17:13:00', '2026-01-09', '17:13:00', 'creacion', '2026-01-08 16:13:37', '2026-10-25 16:13:37'),
(8, 'PO-0007-26', '123', '123', 'COP', 123.00, 11, 11, '2026-01-08', '21:53:00', '2026-01-09', '22:53:00', 'creacion', '2026-01-08 21:53:50', '2026-10-25 16:13:37'),
(9, 'PO-0008-26', '123213', '123213', 'USD', 123213.00, 13, 11, '2026-01-09', '11:32:00', '2026-01-10', '12:31:00', 'creacion', '2026-01-09 10:31:12', '2026-10-25 16:13:37'),
(10, 'PO-0009-26', 'Prueba1', 'Prueba1', 'USD', 12312.12, 15, 11, '2026-01-21', '12:18:00', '2026-01-23', '11:18:00', 'creacion', '2026-01-21 11:18:41', '2026-10-25 16:13:37'),
(11, 'PO-0010-26', 'Prueba1Vue', 'Prueba1Vue', 'USD', 12321312.00, 15, 11, '2026-03-03', '20:17:00', '2026-03-31', '20:18:00', 'creacion', '2026-03-30 20:15:13', '2026-10-25 16:13:37'),
(12, 'PO-0011-26', 'Prueba1Vue', 'Prueba1Vue', 'USD', 12321312.00, 15, 11, '2026-03-03', '20:17:00', '2026-03-31', '20:18:00', 'creacion', '2026-03-30 20:15:22', '2026-10-25 16:13:37'),
(13, 'PO-0012-26', 'Prueba1Vue', 'Prueba1Vue', 'USD', 12321312.00, 15, 11, '2026-03-03', '20:17:00', '2026-03-31', '20:18:00', 'creacion', '2026-03-30 20:16:29', '2026-10-25 16:13:37'),
(14, 'PO-0013-26', 'Prueba1Vue', 'Prueba1Vue', 'USD', 12321312.00, 15, 11, '2026-03-03', '20:17:00', '2026-03-31', '20:18:00', 'creacion', '2026-03-30 20:16:40', '2026-10-25 16:13:37'),
(15, 'PO-0014-26', 'Prueba1Vue', 'Prueba1Vue', 'USD', 12321312.00, 15, 11, '2026-03-03', '20:17:00', '2026-03-31', '20:18:00', 'creacion', '2026-03-30 20:19:07', '2026-10-25 16:13:37'),
(16, 'PO-0015-26', 'Prueba1Vue', 'Prueba1Vue', 'USD', 12321312.00, 15, 11, '2026-03-03', '20:17:00', '2026-03-31', '20:18:00', 'creacion', '2026-03-30 20:19:16', '2026-10-25 16:13:37'),
(17, 'PO-0016-26', 'Prueba3Vue', 'Prueba3Vue', 'USD', 312321.00, 2, 11, '2026-03-30', '21:29:00', '2026-03-31', '23:29:00', 'creacion', '2026-03-30 20:29:27', '2026-10-25 16:13:37'),
(18, 'PO-0017-26', 'Prueba2vUE', 'Prueba2vUE', 'COP', 123213.00, 15, 11, '2026-03-15', '23:35:00', '2026-03-19', '23:32:00', 'creacion', '2026-03-30 20:32:06', '2026-10-25 16:13:37'),
(19, 'PO-0018-26', 'Prueba2vUE', '2342314', 'COP', 21342134.00, 14, 11, '2026-03-30', '20:33:00', '2026-03-30', '22:33:00', 'creacion', '2026-03-30 20:33:54', '2026-10-25 16:13:37'),
(20, 'PO-0019-26', 'Prueba', 'Prueba', 'USD', 12321.33, 10, 11, '2026-04-04', '15:33:00', '2026-04-05', '19:30:00', 'creacion', '2026-04-04 15:30:24', '2026-10-25 16:13:37'),
(21, 'PO-0020-26', 'Prueba324', '234234', 'USD', 234234.00, 16, 11, '2026-04-04', '16:14:00', '2026-04-05', '16:15:00', 'creacion', '2026-04-04 16:17:26', '2026-10-25 16:13:37'),
(22, 'PO-0021-26', '123123', '123213', 'COP', 123213.00, 20, 11, '2026-10-12', '11:11:00', '2026-10-13', '11:01:00', 'creacion', '2026-10-11 14:29:05', '2026-10-25 16:13:37'),
(23, 'PO-0022-26', '214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213', '214213423 214213423 214213423 214213423 214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  214213423  21421342', 'COP', 121212312.12, 20, 11, '2026-10-11', '14:53:00', '2026-10-12', '14:53:00', 'creacion', '2026-10-11 14:53:58', '2026-10-25 16:13:37'),
(24, 'PO-023-26', 'fgfhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh', 'fgfhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh', 'COP', 1221123.00, 20, 11, '2026-10-24', '15:30:00', '2026-10-25', '15:30:00', 'creacion', '2026-10-24 15:31:15', '2026-10-25 16:13:37'),
(25, 'PO-024-26', '3213213', '123123', 'COP', 213.00, 20, 11, '2026-10-24', '19:57:00', '2026-10-25', '19:58:00', 'creacion', '2026-10-24 19:58:21', '2026-10-25 16:13:37'),
(26, 'PO-025-26', 'Prueba', 'Prueba', 'COP', 213.12, 20, 11, '2026-10-24', '20:56:00', '2026-10-25', '20:56:00', 'creacion', '2026-10-24 20:56:07', '2026-10-25 16:13:37'),
(27, 'PO-026-26', 'Prueba Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba', 'Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba  Prueba', 'COP', 12312312.12, 15, 11, '2026-10-25', '11:13:00', '2026-10-25', '11:14:00', 'creacion', '2026-10-25 11:13:29', '2026-10-25 16:13:37'),
(28, 'PO-027-26', '21321', '312321321', 'USD', 123123.00, 20, 11, '2026-10-25', '14:29:00', '2026-11-01', '14:09:00', 'creacion', '2026-10-25 14:30:09', '2026-10-25 16:13:37'),
(29, 'PO-028-26', 'PruebaP1', 'PruebaP1', 'COP', 120000.00, 20, 11, '2026-10-25', '16:32:00', '2026-10-26', '16:32:00', 'creacion', '2026-10-25 16:32:49', '2026-10-25 16:33:08');

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
  `nombre_original` varchar(255) NOT NULL DEFAULT '',
  `ruta_archivo` varchar(255) NOT NULL,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ofertas_documentos`
--

INSERT INTO `ofertas_documentos` (`id`, `licitacion_id`, `titulo`, `descripcion`, `archivo`, `nombre_original`, `ruta_archivo`, `creado_en`) VALUES
(2, 3, '213', '123', 'Prueba_de_desarrollo_FullStack_2025_7045_1767895588.pdf', 'Prueba de desarrollo FullStack 2025.pdf', '../views/docs/uploads/ofertas/', '2026-01-08 13:06:29'),
(3, 3, '123213', '213213', 'Prueba_de_desarrollo_FullStack_2025_9041_1767895668.pdf', 'Prueba de desarrollo FullStack 2025.pdf', '../views/docs/uploads/ofertas/', '2026-01-08 13:07:48'),
(7, 10, '123', '123', 'Prueba_de_desarrollo_FullStack_2025_7551_1769012469.pdf', 'Prueba de desarrollo FullStack 2025.pdf', '../views/docs/uploads/ofertas/', '2026-01-21 11:21:09'),
(15, 21, 'Prueba', 'Prueba', 'TORIA_DE_LA_INFORMACION_TRANSFERENCIA_8611_1775610399.pdf', 'Prueba de desarrollo FullStack 2025.pdf', 'public/documentos_ofertas/', '2026-04-07 20:06:39'),
(19, 24, 'Prueba', 'Descripción', 'Parcial_Nº_1_2375_1792890393.pdf', 'Prueba de desarrollo FullStack 2025.pdf', 'public/documentos_ofertas/', '2026-10-24 20:06:33'),
(24, 25, '123', '33', 'Nixon_Yamid_Suarez_Mejia_Certificate_of_Completion_(1)_2052_1792892904.pdf', 'Nixon_Yamid_Suarez_Mejia_Certificate_of_Completion (1).pdf', 'public/documentos_ofertas/', '2026-10-24 20:48:24'),
(27, 27, 'Prueba', 'Descripción', 'Nixon_Yamid_Suarez_Mejia_Certificate_of_Completion_(2)_8754_1792945142.pdf', 'Nixon_Yamid_Suarez_Mejia_Certificate_of_Completion (2).pdf', 'public/documentos_ofertas/', '2026-10-25 11:19:02'),
(28, 28, 'Prueba', '123123', 'Nixon_Yamid_Suarez_Mejia_Certificate_of_Completion_(3)_7098_1792961132.pdf', 'Nixon_Yamid_Suarez_Mejia_Certificate_of_Completion (3).pdf', 'public/documentos_ofertas/', '2026-10-25 15:45:32'),
(29, 29, 'PruebaP1', 'PruebaP1', 'Nixon_Yamid_Suarez_Mejia_Certificate_of_Completion_(3)_6592_1792963985.pdf', 'Nixon_Yamid_Suarez_Mejia_Certificate_of_Completion (3).pdf', 'public/documentos_ofertas/', '2026-10-25 16:33:05');

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
  `usuario_actualizado` timestamp NULL DEFAULT NULL,
  `clave_no_cript` varchar(150) NOT NULL,
  `perfil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id`, `usuario_nombre`, `usuario_apellido`, `usuario_usuario`, `usuario_clave`, `usuario_creado`, `usuario_actualizado`, `clave_no_cript`, `perfil`) VALUES
(1, '', '', 'nixon', '', '2026-01-07 21:17:31', NULL, '1234567', 3),
(2, '', '', 'nixonS', '', '2026-01-07 21:49:19', NULL, '1234567', 3),
(3, 'nixon', 'suarez', 'nixonSM', '$2y$10$AwEwbzgceNTaRH6mL.HTvOBzD5IlGF0n3qvPI3gO1775QpNl6OShC', '2026-01-07 21:54:56', NULL, '1234567', 3),
(4, 'Pepito', 'Pepito', 'Pepito', '$2y$10$I7iuIQu9hc0YELnur8/.qOQ.7doPKs5LJG0i2vc4wLPsoI0nX266e', '2026-01-21 16:14:54', '2026-01-21 16:14:54', '1234567', 3),
(5, 'nixonPruabaAPI', 'nixonPruabaAPI', 'nixonPruabaAPI', '$2y$10$GR.URDRbQ6y/l3am3ChdROcthJUU50paMlpyvz/RyDSuBoFf0yHKm', '2026-03-19 01:01:29', '2026-03-19 01:01:29', '1234567', 3),
(6, 'Prueba Registro', 'Prueba Registro', 'PruebaRegistro', '$2y$10$0dd5lJrOK2b1hasBa2owdeVJiOUzBCcq3cjyHXXHBDQ9s8cd47NP.', '2026-03-22 19:09:28', '2026-03-22 19:09:28', '1234567', 2),
(7, 'Prueba', 'Prueba', 'Prueba', '$2y$10$G1K8/Dwdui5Yw7kAYB392eUHn1EeLqGnTqduFLYg.GDL3KGozzv1G', '2026-10-11 16:22:48', '2026-10-11 16:22:48', '1234567', 3),
(8, 'Nixon', 'Suarez', 'NixonAdmin', '$2y$10$WQbX2UYcHAVVKgFgw0jQ.eiVLb7pB0PUifl53dMhMLtNWuxKXYmp6', '2026-10-25 20:00:28', '2026-10-25 20:00:28', '1234567', 3),
(11, 'NixonA', 'SuarezA', 'nixonsuarez', '$2y$10$Ew4i5FLqvZxARTBNEraIsea01v2ewYhbTUJOakSulNDE6igHCJGv6', '2026-10-25 20:29:32', '2026-10-26 15:19:39', 'PruebaDeRegistroV2', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_actividades_usuario_id` (`usuario_id`);

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
  ADD KEY `fk_ofertas_actividad` (`actividad_id`),
  ADD KEY `idx_ofertas_usuario_id` (`usuario_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49029;

--
-- AUTO_INCREMENT for table `api_tokens`
--
ALTER TABLE `api_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `ofertas`
--
ALTER TABLE `ofertas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `ofertas_documentos`
--
ALTER TABLE `ofertas_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
