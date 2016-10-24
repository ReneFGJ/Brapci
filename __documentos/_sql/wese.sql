-- phpMyAdmin SQL Dump
-- version 4.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 23, 2016 at 11:58 AM
-- Server version: 5.5.49-0+deb8u1
-- PHP Version: 5.6.22-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `brapci_base`
--

-- --------------------------------------------------------

--
-- Table structure for table `wese_concept`
--

CREATE TABLE IF NOT EXISTS `wese_concept` (
  `id_c` bigint(20) unsigned NOT NULL,
  `c_id` char(50) NOT NULL,
  `c_scheme` int(11) NOT NULL,
  `c_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=154 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wese_concept`
--

INSERT INTO `wese_concept` (`id_c`, `c_id`, `c_scheme`, `c_time`) VALUES
(1, 'estudos_metricos_de_informacao', 1, '2016-03-07 00:14:33'),
(2, 'cientometria', 1, '2016-03-07 00:14:33'),
(3, 'lei_de_zipf', 1, '2016-03-07 00:14:33'),
(4, 'lei_de_lotka', 1, '2016-03-07 00:14:33'),
(5, 'lei_de_garfield', 1, '2016-03-07 00:14:33'),
(6, 'leis_bibliometricas', 1, '2016-03-07 00:14:33'),
(7, 'lei_de_bradford', 1, '2016-03-07 00:14:33'),
(8, 'lei_de_barton_e_klever', 1, '2016-03-07 00:14:33'),
(9, 'lei_de_goffman', 1, '2016-03-07 00:14:33'),
(10, 'bibliometria', 1, '2016-03-07 00:14:33'),
(11, 'indicador_de_producao', 1, '2016-03-07 00:14:33'),
(12, 'indicador_de_ligacao', 1, '2016-03-07 00:14:33'),
(13, 'indicador_de_citacao', 1, '2016-03-07 00:14:33'),
(14, 'fator_de_impacto', 1, '2016-03-07 00:14:33'),
(15, 'analise_de_redes_sociais', 1, '2016-03-07 00:14:33'),
(16, 'altmetria', 1, '2016-03-07 00:14:33'),
(17, 'webometria', 1, '2016-03-07 00:14:33'),
(18, 'indice_h', 1, '2016-03-07 00:14:33'),
(19, 'informetria', 1, '2016-03-07 00:14:33'),
(20, 'autocitacao', 1, '2016-03-07 00:14:33'),
(21, 'perifericos', 2, '2016-03-07 00:14:33'),
(22, 'unidade_de_entrada', 2, '2016-03-07 00:14:33'),
(23, 'unidade_de_armazenamento_secundario', 2, '2016-03-07 00:14:33'),
(24, 'teclado_de_computador', 2, '2016-03-07 00:14:33'),
(25, 'camera_digital', 2, '2016-03-07 00:14:33'),
(26, 'dispositivo_de_comando_de_voz', 2, '2016-03-07 00:14:33'),
(27, 'scanner', 2, '2016-03-07 00:14:33'),
(28, 'mesa_digitalizadora', 2, '2016-03-07 00:14:33'),
(29, 'webcam', 2, '2016-03-07 00:14:33'),
(30, 'mouse', 2, '2016-03-07 00:14:33'),
(31, 'memoria_ram', 2, '2016-03-07 00:14:33'),
(32, 'memoria_flash', 2, '2016-03-07 00:14:33'),
(33, 'memoria_cache', 2, '2016-03-07 00:14:33'),
(34, 'unidade_de_memoria', 2, '2016-03-07 00:14:33'),
(35, 'unidade_central_de_processamento', 2, '2016-03-07 00:14:33'),
(36, 'hardware', 2, '2016-03-07 00:14:33'),
(37, 'monitor_de_video', 2, '2016-03-07 00:14:33'),
(38, 'video_graphics_array', 2, '2016-03-07 00:14:33'),
(39, 'enhanced_graphics_adapter', 2, '2016-03-07 00:14:33'),
(40, 'color_graphics_adapter', 2, '2016-03-07 00:14:33'),
(41, 'monochrome_display_adapter', 2, '2016-03-07 00:14:33'),
(42, 'disco_rigido_sata', 2, '2016-03-07 00:14:33'),
(43, 'disco_rigido_sata_ii', 2, '2016-03-07 00:14:33'),
(44, 'disco_rigido', 2, '2016-03-07 00:14:33'),
(45, 'controlador_de_video', 2, '2016-03-07 00:14:33'),
(46, 'colaboracao_cientifica', 1, '2016-03-07 00:14:33'),
(47, 'coautoria', 1, '2016-03-07 00:14:33'),
(48, 'ensino_religioso', 3, '2016-03-07 00:14:33'),
(49, 'formacao_de_professor', 3, '2016-03-07 00:14:33'),
(50, 'escola_publica', 3, '2016-03-07 00:14:33'),
(51, 'educacao_infantil', 3, '2016-03-07 00:14:33'),
(52, 'educacao_basica', 3, '2016-03-07 00:14:33'),
(53, 'educacao', 3, '2016-03-07 00:14:33'),
(54, 'ensino_a_distancia', 3, '2016-03-07 00:14:33'),
(55, 'epistemologia', 3, '2016-03-07 00:14:33'),
(56, 'adolescencia', 3, '2016-03-07 00:14:33'),
(57, 'catolicentrismo', 3, '2016-03-07 00:14:33'),
(58, 'direitos_humanos', 3, '2016-03-07 00:14:33'),
(59, 'educacao_confessional', 3, '2016-03-07 00:14:33'),
(60, 'historia_da_educacao', 3, '2016-03-07 00:14:33'),
(61, 'historia_do_ensino_religioso', 3, '2016-03-07 00:14:33'),
(62, 'legislacao', 3, '2016-03-07 00:14:33'),
(63, 'legislacao_educacional', 3, '2016-03-07 00:14:33'),
(64, 'escolas', 3, '2016-03-07 00:14:33'),
(65, 'escola_particular', 3, '2016-03-07 00:14:33'),
(66, 'cidadania', 3, '2016-03-07 00:14:33'),
(67, 'arquivometria', 1, '2016-03-07 00:14:33'),
(68, 'ranking', 1, '2016-03-07 00:14:33'),
(69, 'museometria', 1, '2016-03-07 00:14:33'),
(70, 'analise_de_citacao', 1, '2016-03-07 00:14:33'),
(71, 'coeficiente_de_pearson', 1, '2016-03-07 00:14:33'),
(72, 'sience_citation_index', 1, '2016-03-07 00:14:33'),
(73, 'santa_catarina', 1, '2016-03-07 00:14:33'),
(74, 'web_of_science', 1, '2016-03-07 00:14:33'),
(75, 'fator_de_impacto_web', 1, '2016-03-07 00:14:33'),
(76, 'analise_de_cocitacao', 1, '2016-03-07 00:14:33'),
(77, 'bases_de_dados', 1, '2016-03-07 00:14:33'),
(78, 'lisa', 1, '2016-03-07 00:14:33'),
(79, 'brapci', 1, '2016-03-07 00:14:33'),
(80, 'scopus', 1, '2016-03-07 00:14:33'),
(81, 'outlier', 1, '2016-03-07 00:14:33'),
(82, 'mineracao_de_dados', 1, '2016-03-07 00:14:33'),
(83, 'curriculo_lattes', 1, '2016-03-07 00:14:33'),
(84, 'teorema_de_bayes', 1, '2016-03-07 00:14:33'),
(85, 'indice_de_jaccard', 1, '2016-03-07 00:14:33'),
(86, 'meia_vida', 1, '2016-03-07 00:14:33'),
(87, 'acoplamento_bibliografico', 1, '2016-03-07 00:14:33'),
(88, 'obsolescencia_da_literatura', 1, '2016-03-07 00:14:33'),
(89, 'analise_da_producao_cientifica', 1, '2016-03-07 00:14:33'),
(90, 'ranking_de_periodicos', 1, '2016-03-07 00:14:33'),
(91, 'scielo', 1, '2016-03-07 00:14:33'),
(92, 'coeficiente_de_agrupamento', 1, '2016-03-07 00:14:33'),
(93, 'densidade', 1, '2016-03-07 00:14:33'),
(94, 'centralidade', 1, '2016-03-07 00:14:33'),
(95, 'coesao', 1, '2016-03-07 00:14:33'),
(96, 'dublin_core', 2, '2016-03-07 00:14:33'),
(97, 'dublin_core_application_profile_(dcap)', 2, '2016-03-07 00:14:33'),
(98, 'midias_sociais', 1, '2016-03-07 00:14:33'),
(99, 'resource_description_and_access', 4, '2016-03-07 01:53:38'),
(100, 'simple_knowledge_organization_system', 4, '2016-03-07 01:55:22'),
(101, 'simple_knowledge_organization_systemmachine-readab', 4, '2016-03-07 01:56:41'),
(102, 'machine-readable_cataloging', 4, '2016-03-07 01:57:58'),
(103, 'web_semantica', 4, '2016-03-07 01:59:23'),
(104, 'international_standard_bibliographic_description', 4, '2016-03-07 02:01:04'),
(105, 'recuperacao_da_informacao', 4, '2016-03-07 02:07:40'),
(106, 'catalogacao_cooperativa', 4, '2016-03-07 02:09:38'),
(107, 'catalogacao', 4, '2016-03-08 02:31:23'),
(108, 'catalogacao_na_fonte', 4, '2016-03-08 02:32:26'),
(109, 'catalogos_coletivos', 4, '2016-03-08 02:34:20'),
(110, 'listas_de_autoridades', 4, '2016-03-08 02:35:18'),
(111, 'cd-rom', 2, '2016-03-08 02:37:12'),
(112, 'discos_oticos', 2, '2016-03-08 02:38:49'),
(113, 'classificacao_decimal_de_dewey', 4, '2016-03-08 02:40:17'),
(114, 'classificacao_decimal_universal', 4, '2016-03-08 02:40:29'),
(115, 'centros_de_analise_de_informacao', 5, '2016-03-08 02:43:22'),
(116, 'bibliotecas', 5, '2016-03-08 02:44:20'),
(117, 'bibliotecas_especializadas', 5, '2016-03-08 02:45:10'),
(118, 'bibliotecas_universitarias', 5, '2016-03-08 02:45:24'),
(119, 'centros_de_multimeios', 5, '2016-03-08 02:45:53'),
(120, 'internet', 2, '2016-03-08 02:48:00'),
(121, 'cinematecas', 5, '2016-03-08 02:50:01'),
(122, 'classificacao_facetada', 4, '2016-03-08 02:51:25'),
(123, 'classificacao_de_bliss', 4, '2016-03-08 02:52:02'),
(124, 'classificacao_dos_dois_pontos', 4, '2016-03-08 02:52:44'),
(125, 'classificacao_hierarquico-enumerativa', 4, '2016-03-08 02:53:02'),
(126, 'autoria_coletiva', 4, '2016-03-08 02:55:28'),
(127, 'cocitacao', 1, '2016-03-08 02:56:51'),
(128, 'anglo_american_cataloging_rules', 4, '2016-03-08 03:00:06'),
(129, 'computadores', 2, '2016-03-09 01:03:08'),
(130, 'sinopses', 4, '2016-03-09 01:04:58'),
(131, 'linguagens_documentarias', 4, '2016-03-09 01:09:32'),
(132, 'listas_de_cabecalhos_de_assunto', 4, '2016-03-09 01:10:55'),
(133, 'library_of_congress_subject_headings', 4, '2016-03-09 01:11:11'),
(134, 'representacao_descritiva', 4, '2016-03-18 00:12:03'),
(135, 'frbr', 4, '2016-03-18 00:17:11'),
(136, 'metadados', 4, '2016-03-18 00:18:36'),
(137, 'vocabulario_controlado', 4, '2016-03-18 00:27:04'),
(138, 'controle_bibliografico_universal', 4, '2016-03-18 00:30:42'),
(139, 'deposito_legal', 4, '2016-03-18 00:33:52'),
(140, 'isbn', 4, '2016-03-18 00:35:04'),
(141, 'issn', 4, '2016-03-18 00:35:26'),
(142, 'bibliotecas_digitais', 4, '2016-03-18 00:38:02'),
(143, 'frad', 4, '2016-03-19 23:09:50'),
(144, 'frsad', 4, '2016-03-19 23:10:04'),
(146, 'sparql', 2, '2016-03-19 23:17:34'),
(147, 'uri', 2, '2016-03-19 23:18:17'),
(148, 'url', 2, '2016-03-19 23:19:18'),
(149, 'urn', 2, '2016-03-19 23:20:01'),
(150, 'rdf', 2, '2016-03-19 23:20:57'),
(151, 'tecnologias_de_informacao_e_comunicacao', 2, '2016-03-20 21:22:04'),
(152, 'servico_de_extensao', 5, '2016-07-12 13:41:08'),
(153, 'biblioteca', 5, '2016-10-20 12:04:37');

-- --------------------------------------------------------

--
-- Table structure for table `wese_concept_tg`
--

CREATE TABLE IF NOT EXISTS `wese_concept_tg` (
  `id_tg` bigint(20) unsigned NOT NULL,
  `tg_conceito_1` int(11) NOT NULL,
  `tg_conceito_2` int(11) NOT NULL,
  `tg_scheme` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wese_concept_tg`
--

INSERT INTO `wese_concept_tg` (`id_tg`, `tg_conceito_1`, `tg_conceito_2`, `tg_scheme`) VALUES
(2, 6, 3, 1),
(3, 6, 4, 1),
(4, 6, 9, 1),
(5, 6, 8, 1),
(6, 6, 7, 1),
(7, 6, 5, 1),
(8, 10, 6, 1),
(9, 13, 14, 1),
(10, 13, 18, 1),
(11, 1, 16, 1),
(12, 1, 10, 1),
(13, 1, 2, 1),
(14, 1, 17, 1),
(15, 1, 19, 1),
(16, 10, 11, 1),
(17, 10, 12, 1),
(18, 10, 13, 1),
(19, 13, 20, 1),
(20, 12, 15, 1),
(21, 21, 22, 2),
(22, 21, 23, 2),
(23, 22, 25, 2),
(24, 22, 24, 2),
(25, 22, 26, 2),
(26, 22, 28, 2),
(27, 22, 27, 2),
(28, 36, 35, 2),
(29, 35, 34, 2),
(30, 34, 33, 2),
(31, 34, 32, 2),
(32, 34, 31, 2),
(33, 22, 30, 2),
(34, 22, 29, 2),
(35, 35, 21, 2),
(36, 35, 37, 2),
(37, 37, 40, 2),
(38, 37, 39, 2),
(39, 37, 41, 2),
(40, 37, 38, 2),
(41, 23, 44, 2),
(42, 44, 42, 2),
(43, 44, 43, 2),
(44, 35, 45, 2),
(45, 11, 47, 1),
(46, 11, 46, 1),
(47, 53, 52, 3),
(48, 53, 51, 3),
(49, 53, 49, 3),
(50, 53, 55, 3),
(51, 62, 63, 3),
(52, 55, 60, 3),
(53, 55, 61, 3),
(54, 64, 50, 3),
(55, 64, 65, 3),
(56, 48, 57, 3),
(57, 58, 66, 3),
(58, 53, 54, 3),
(59, 1, 67, 1),
(60, 1, 69, 1),
(61, 17, 75, 1),
(77, 74, 72, 1),
(63, 77, 78, 1),
(64, 77, 80, 1),
(65, 77, 74, 1),
(66, 77, 79, 1),
(67, 77, 83, 1),
(68, 13, 70, 1),
(69, 13, 76, 1),
(70, 13, 86, 1),
(71, 70, 87, 1),
(72, 13, 88, 1),
(73, 11, 89, 1),
(74, 10, 68, 1),
(75, 1, 77, 1),
(76, 77, 91, 1),
(78, 68, 90, 1),
(79, 15, 94, 1),
(80, 15, 92, 1),
(81, 15, 93, 1),
(82, 15, 95, 1),
(83, 96, 97, 2),
(84, 98, 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wese_label`
--

CREATE TABLE IF NOT EXISTS `wese_label` (
  `id_l` bigint(20) unsigned NOT NULL,
  `l_concept_id` int(11) NOT NULL,
  `l_term` int(11) NOT NULL,
  `l_type` char(10) NOT NULL,
  `l_update` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=MyISAM AUTO_INCREMENT=278 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wese_label`
--

INSERT INTO `wese_label` (`id_l`, `l_concept_id`, `l_term`, `l_type`, `l_update`) VALUES
(1, 1, 1, 'PREF', '2015-10-12'),
(2, 2, 14, 'PREF', '2015-10-12'),
(3, 3, 24, 'PREF', '2015-10-12'),
(4, 4, 20, 'PREF', '2015-10-12'),
(5, 5, 32, 'PREF', '2015-10-12'),
(6, 6, 25, 'PREF', '2015-10-12'),
(7, 7, 23, 'PREF', '2015-10-12'),
(8, 8, 33, 'PREF', '2015-10-12'),
(9, 9, 34, 'PREF', '2015-10-12'),
(10, 10, 11, 'PREF', '2015-10-13'),
(11, 1, 5, 'ALT', '2015-10-13'),
(12, 11, 29, 'PREF', '2015-10-13'),
(13, 12, 31, 'PREF', '2015-10-13'),
(14, 13, 30, 'PREF', '2015-10-13'),
(15, 14, 28, 'PREF', '2015-10-13'),
(16, 10, 6, 'ALT', '2015-10-13'),
(17, 10, 18, 'HIDDEN', '2015-10-13'),
(18, 15, 22, 'PREF', '2015-10-13'),
(19, 16, 21, 'PREF', '2015-10-13'),
(20, 17, 16, 'PREF', '2015-10-13'),
(21, 2, 26, 'ALT', '2015-10-13'),
(22, 18, 27, 'PREF', '2015-10-13'),
(23, 19, 15, 'PREF', '2015-10-13'),
(24, 2, 8, 'HIDDEN', '2015-10-13'),
(25, 2, 3, 'HIDDEN', '2015-10-13'),
(26, 10, 2, 'HIDDEN', '2015-10-13'),
(27, 17, 7, 'HIDDEN', '2015-10-13'),
(28, 10, 4, 'HIDDEN', '2015-10-13'),
(29, 10, 19, 'ALT', '2015-10-13'),
(30, 10, 9, 'HIDDEN', '2015-10-13'),
(31, 2, 13, 'ALT', '2015-10-13'),
(32, 20, 35, 'PREF', '2015-10-13'),
(33, 21, 36, 'PREF', '2015-10-13'),
(34, 22, 38, 'PREF', '2015-10-13'),
(35, 23, 37, 'PREF', '2015-10-13'),
(36, 24, 39, 'PREF', '2015-10-13'),
(37, 25, 40, 'PREF', '2015-10-13'),
(38, 26, 41, 'PREF', '2015-10-13'),
(39, 27, 42, 'PREF', '2015-10-13'),
(40, 28, 43, 'PREF', '2015-10-13'),
(41, 29, 45, 'PREF', '2015-10-13'),
(42, 30, 44, 'PREF', '2015-10-13'),
(43, 31, 48, 'PREF', '2015-10-13'),
(44, 32, 49, 'PREF', '2015-10-13'),
(45, 33, 47, 'PREF', '2015-10-13'),
(46, 34, 46, 'PREF', '2015-10-13'),
(47, 35, 50, 'PREF', '2015-10-13'),
(48, 36, 53, 'PREF', '2015-10-13'),
(49, 37, 54, 'PREF', '2015-10-13'),
(50, 38, 55, 'PREF', '2015-10-13'),
(51, 39, 56, 'PREF', '2015-10-13'),
(52, 40, 57, 'PREF', '2015-10-13'),
(53, 41, 61, 'PREF', '2015-10-13'),
(54, 40, 58, 'ALT', '2015-10-13'),
(55, 39, 60, 'ALT', '2015-10-13'),
(56, 41, 62, 'ALT', '2015-10-13'),
(57, 29, 59, 'ALT', '2015-10-13'),
(58, 35, 52, 'ALT', '2015-10-13'),
(59, 35, 51, 'ALT', '2015-10-13'),
(60, 42, 63, 'PREF', '2015-10-13'),
(61, 43, 64, 'PREF', '2015-10-13'),
(62, 44, 67, 'PREF', '2015-10-13'),
(63, 44, 65, 'ALT', '2015-10-13'),
(64, 44, 66, 'ALT', '2015-10-13'),
(65, 23, 68, 'ALT', '2015-10-13'),
(66, 45, 69, 'PREF', '2015-10-13'),
(67, 46, 70, 'PREF', '2015-10-13'),
(68, 47, 71, 'PREF', '2015-10-13'),
(69, 48, 72, 'PREF', '2015-10-13'),
(70, 49, 73, 'PREF', '2015-10-13'),
(71, 49, 80, 'ALT', '2015-10-13'),
(72, 50, 86, 'PREF', '2015-10-13'),
(73, 50, 101, 'HIDDEN', '2015-10-13'),
(74, 51, 102, 'PREF', '2015-10-13'),
(75, 52, 91, 'PREF', '2015-10-13'),
(76, 53, 81, 'PREF', '2015-10-13'),
(77, 49, 76, 'ALT', '2015-10-13'),
(78, 49, 109, 'ALT', '2015-10-13'),
(79, 54, 113, 'PREF', '2015-10-13'),
(80, 54, 114, 'ALT', '2015-10-13'),
(81, 55, 88, 'PREF', '2015-10-13'),
(82, 56, 99, 'PREF', '2015-10-13'),
(83, 57, 103, 'PREF', '2015-10-13'),
(84, 58, 122, 'PREF', '2015-10-13'),
(85, 59, 105, 'PREF', '2015-10-13'),
(86, 59, 92, 'ALT', '2015-10-13'),
(87, 49, 85, 'ALT', '2015-10-13'),
(88, 1, 10, 'ALT', '2015-10-13'),
(89, 10, 17, 'ALT', '2015-10-13'),
(90, 60, 82, 'PREF', '2015-10-13'),
(91, 61, 119, 'PREF', '2015-10-13'),
(92, 62, 94, 'PREF', '2015-10-13'),
(93, 63, 108, 'PREF', '2015-10-13'),
(94, 64, 124, 'PREF', '2015-10-13'),
(95, 65, 125, 'PREF', '2015-10-13'),
(96, 66, 111, 'PREF', '2015-10-13'),
(97, 67, 126, 'PREF', '2015-11-08'),
(98, 68, 127, 'PREF', '2015-11-08'),
(99, 17, 128, 'ALT', '2015-11-08'),
(100, 69, 129, 'PREF', '2015-11-08'),
(101, 19, 130, 'ALT', '2015-11-08'),
(102, 2, 142, 'ALT', '2015-11-08'),
(103, 1, 150, 'ALT', '2015-11-08'),
(104, 70, 132, 'PREF', '2015-11-08'),
(105, 71, 151, 'PREF', '2015-11-08'),
(106, 71, 152, 'ALT', '2015-11-08'),
(107, 71, 153, 'ALT', '2015-11-08'),
(108, 71, 154, 'ALT', '2015-11-08'),
(109, 71, 155, 'ALT', '2015-11-08'),
(110, 72, 157, 'PREF', '2015-11-08'),
(111, 73, 120, 'PREF', '2015-11-08'),
(112, 74, 156, 'PREF', '2015-11-08'),
(113, 75, 138, 'PREF', '2015-11-08'),
(114, 76, 131, 'PREF', '2015-11-08'),
(115, 77, 143, 'PREF', '2015-11-08'),
(116, 9, 160, 'ALT', '2015-11-08'),
(117, 9, 162, 'ALT', '2015-11-08'),
(118, 7, 163, 'ALT', '2015-11-08'),
(119, 7, 164, 'ALT', '2015-11-08'),
(120, 1, 165, 'ALT', '2015-11-08'),
(121, 78, 166, 'PREF', '2015-11-08'),
(122, 79, 158, 'PREF', '2015-11-08'),
(123, 9, 140, 'ALT', '2015-11-08'),
(124, 80, 159, 'PREF', '2015-11-08'),
(125, 81, 149, 'PREF', '2015-11-08'),
(126, 82, 148, 'PREF', '2015-11-08'),
(127, 83, 144, 'PREF', '2015-11-08'),
(128, 84, 167, 'PREF', '2015-11-08'),
(129, 84, 168, 'ALT', '2015-11-08'),
(130, 85, 135, 'PREF', '2015-11-08'),
(131, 10, 169, 'ALT', '2015-11-08'),
(132, 83, 171, 'ALT', '2015-11-08'),
(133, 1, 172, 'ALT', '2015-11-08'),
(134, 1, 174, 'ALT', '2015-11-08'),
(135, 15, 141, 'ALT', '2015-11-08'),
(136, 86, 176, 'PREF', '2015-11-08'),
(137, 86, 175, 'ALT', '2015-11-08'),
(138, 17, 177, 'ALT', '2015-11-08'),
(139, 87, 178, 'PREF', '2015-11-08'),
(140, 88, 179, 'PREF', '2015-11-08'),
(141, 89, 180, 'PREF', '2015-11-08'),
(142, 89, 170, 'ALT', '2015-11-08'),
(143, 89, 181, 'ALT', '2015-11-08'),
(144, 90, 182, 'PREF', '2015-11-08'),
(145, 91, 183, 'PREF', '2015-11-08'),
(146, 92, 186, 'PREF', '2015-11-08'),
(147, 93, 185, 'PREF', '2015-11-08'),
(148, 94, 184, 'PREF', '2015-11-08'),
(149, 94, 187, 'ALT', '2015-11-08'),
(150, 95, 188, 'PREF', '2015-11-08'),
(151, 96, 189, 'PREF', '2016-02-10'),
(152, 97, 190, 'PREF', '2016-02-10'),
(153, 98, 191, 'PREF', '2016-03-06'),
(165, 100, 196, 'PREF', '2016-03-06'),
(164, 99, 195, 'ALT', '2016-03-06'),
(163, 99, 194, 'PREF', '2016-03-06'),
(161, 17, 192, 'ALT', '2016-03-06'),
(162, 17, 193, 'HIDDEN', '2016-03-06'),
(166, 100, 197, 'ALT', '2016-03-06'),
(167, 0, 198, 'PREF', '2016-03-06'),
(168, 102, 199, 'PREF', '2016-03-06'),
(169, 102, 200, 'ALT', '2016-03-06'),
(170, 102, 201, 'ALT', '2016-03-06'),
(171, 103, 202, 'PREF', '2016-03-06'),
(172, 103, 203, 'ALT', '2016-03-06'),
(173, 104, 204, 'PREF', '2016-03-06'),
(174, 104, 205, 'ALT', '2016-03-06'),
(175, 105, 206, 'PREF', '2016-03-06'),
(176, 106, 207, 'PREF', '2016-03-06'),
(177, 107, 208, 'PREF', '2016-03-07'),
(178, 108, 209, 'PREF', '2016-03-07'),
(179, 106, 210, 'ALT', '2016-03-07'),
(180, 108, 211, 'ALT', '2016-03-07'),
(181, 109, 212, 'PREF', '2016-03-07'),
(182, 110, 213, 'PREF', '2016-03-07'),
(183, 110, 214, 'ALT', '2016-03-07'),
(184, 110, 215, 'ALT', '2016-03-07'),
(185, 111, 216, 'PREF', '2016-03-07'),
(186, 112, 217, 'PREF', '2016-03-07'),
(187, 112, 218, 'ALT', '2016-03-07'),
(188, 112, 219, 'ALT', '2016-03-07'),
(189, 112, 220, 'ALT', '2016-03-07'),
(190, 113, 221, 'PREF', '2016-03-07'),
(191, 114, 222, 'PREF', '2016-03-07'),
(192, 113, 223, 'ALT', '2016-03-07'),
(193, 114, 224, 'ALT', '2016-03-07'),
(194, 115, 225, 'PREF', '2016-03-07'),
(195, 115, 226, 'ALT', '2016-03-07'),
(196, 116, 227, 'PREF', '2016-03-07'),
(197, 116, 228, 'ALT', '2016-03-07'),
(198, 117, 229, 'PREF', '2016-03-07'),
(199, 118, 230, 'PREF', '2016-03-07'),
(200, 119, 231, 'PREF', '2016-03-07'),
(201, 119, 232, 'ALT', '2016-03-07'),
(202, 119, 233, 'ALT', '2016-03-07'),
(203, 119, 234, 'ALT', '2016-03-07'),
(204, 119, 235, 'ALT', '2016-03-07'),
(205, 119, 236, 'ALT', '2016-03-07'),
(206, 119, 237, 'ALT', '2016-03-07'),
(207, 120, 238, 'PREF', '2016-03-07'),
(208, 121, 239, 'PREF', '2016-03-07'),
(209, 121, 240, 'ALT', '2016-03-07'),
(210, 121, 241, 'ALT', '2016-03-07'),
(211, 121, 242, 'ALT', '2016-03-07'),
(212, 122, 243, 'PREF', '2016-03-07'),
(213, 122, 244, 'ALT', '2016-03-07'),
(214, 123, 245, 'PREF', '2016-03-07'),
(215, 124, 246, 'PREF', '2016-03-07'),
(216, 125, 247, 'PREF', '2016-03-07'),
(217, 122, 248, 'ALT', '2016-03-07'),
(218, 125, 249, 'ALT', '2016-03-07'),
(219, 125, 250, 'ALT', '2016-03-07'),
(220, 126, 251, 'PREF', '2016-03-07'),
(221, 127, 252, 'PREF', '2016-03-07'),
(222, 127, 253, 'ALT', '2016-03-07'),
(223, 127, 254, 'ALT', '2016-03-07'),
(224, 128, 255, 'PREF', '2016-03-08'),
(225, 128, 256, 'ALT', '2016-03-08'),
(226, 129, 257, 'PREF', '2016-03-08'),
(227, 130, 258, 'PREF', '2016-03-08'),
(228, 130, 259, 'ALT', '2016-03-08'),
(229, 131, 260, 'PREF', '2016-03-08'),
(230, 132, 261, 'PREF', '2016-03-08'),
(231, 133, 262, 'PREF', '2016-03-08'),
(232, 78, 263, 'ALT', '2016-03-08'),
(233, 134, 264, 'PREF', '2016-03-17'),
(234, 134, 265, 'ALT', '2016-03-17'),
(235, 134, 266, 'ALT', '2016-03-17'),
(236, 134, 267, 'HIDDEN', '2016-03-17'),
(237, 134, 268, 'HIDDEN', '2016-03-17'),
(238, 134, 269, 'ALT', '2016-03-17'),
(239, 134, 270, 'ALT', '2016-03-17'),
(240, 134, 271, 'ALT', '2016-03-17'),
(241, 134, 272, 'ALT', '2016-03-17'),
(242, 135, 273, 'PREF', '2016-03-17'),
(243, 136, 274, 'PREF', '2016-03-17'),
(244, 134, 275, 'HIDDEN', '2016-03-17'),
(245, 97, 276, 'ALT', '2016-03-17'),
(246, 137, 277, 'PREF', '2016-03-17'),
(247, 138, 278, 'PREF', '2016-03-17'),
(248, 138, 279, 'ALT', '2016-03-17'),
(249, 139, 280, 'PREF', '2016-03-17'),
(250, 140, 281, 'PREF', '2016-03-17'),
(251, 141, 282, 'PREF', '2016-03-17'),
(252, 134, 283, 'ALT', '2016-03-17'),
(253, 142, 284, 'PREF', '2016-03-17'),
(254, 44, 285, 'ALT', '2016-03-18'),
(255, 135, 286, 'ALT', '2016-03-19'),
(256, 135, 287, 'ALT', '2016-03-19'),
(257, 143, 288, 'PREF', '2016-03-19'),
(258, 144, 289, 'PREF', '2016-03-19'),
(259, 143, 290, 'ALT', '2016-03-19'),
(260, 144, 291, 'ALT', '2016-03-19'),
(262, 146, 292, 'PREF', '2016-03-19'),
(263, 146, 293, 'ALT', '2016-03-19'),
(264, 147, 294, 'PREF', '2016-03-19'),
(265, 147, 295, 'ALT', '2016-03-19'),
(266, 148, 296, 'PREF', '2016-03-19'),
(267, 148, 297, 'ALT', '2016-03-19'),
(268, 149, 298, 'PREF', '2016-03-19'),
(269, 149, 299, 'ALT', '2016-03-19'),
(270, 150, 300, 'PREF', '2016-03-19'),
(271, 150, 301, 'ALT', '2016-03-19'),
(272, 151, 302, 'PREF', '2016-03-20'),
(273, 151, 303, 'ALT', '2016-03-20'),
(274, 151, 304, 'ALT', '2016-03-20'),
(275, 77, 305, 'ALT', '2016-03-24'),
(276, 152, 306, 'PREF', '2016-07-12'),
(277, 153, 307, 'PREF', '2016-10-20');

-- --------------------------------------------------------

--
-- Table structure for table `wese_language`
--

CREATE TABLE IF NOT EXISTS `wese_language` (
  `id_lg` bigint(20) unsigned NOT NULL,
  `id_lang` char(5) NOT NULL,
  `lang_name` char(100) NOT NULL,
  `lang_ordem` int(11) NOT NULL DEFAULT '9'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wese_language`
--

INSERT INTO `wese_language` (`id_lg`, `id_lang`, `lang_name`, `lang_ordem`) VALUES
(1, 'en_US', 'English', 1),
(2, 'pt_BR', 'Português Brasil', 9);

-- --------------------------------------------------------

--
-- Table structure for table `wese_note`
--

CREATE TABLE IF NOT EXISTS `wese_note` (
  `id_wn` bigint(20) unsigned NOT NULL,
  `wn_id_c` int(11) NOT NULL,
  `wn_note` text NOT NULL,
  `wn_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wese_note`
--

INSERT INTO `wese_note` (`id_wn`, `wn_id_c`, `wn_note`, `wn_update`) VALUES
(1, 111, 'Sigla para: Compact Disc Read-Only Memory. O termo compacto deve-se ao seu pequeno tamanho para os padrões vigentes, quando do seu lançamento, e memória apenas para leitura deve-se ao fato do seu conteúdo poder apenas ser lido e nunca alterado, o termo foi herdado da memória ROM, que contrasta com tipos de memória RW como memória flash. A gravação é feita pelo seu fabricante. Existem outros tipos desses discos, como o CD-R e o CD-RW, que permitem ao utilizador normal fazer a suas próprias gravações uma, ou várias vezes, respectivamente, caso possua o hardware e software necessários. (WIKI, 2016)', '2016-03-08 23:43:45'),
(2, 78, 'Library and Information Science Abstracts - ProQuest', '2016-03-09 01:18:13'),
(3, 102, 'Formato MARC21 é baseados no MARC de vários países, citando Estados Unidos – USMARC, na Inglaterra – UKMARC, na França – InterMARC e no Canadá – CanMARC', '2016-03-19 12:46:06'),
(4, 147, 'Uma URI é a junção de uma URL e ou uma URN', '2016-03-19 23:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `wese_pa`
--

CREATE TABLE IF NOT EXISTS `wese_pa` (
  `id_we` bigint(20) unsigned NOT NULL,
  `we_prefix` char(20) NOT NULL,
  `we_elemente` char(80) NOT NULL,
  `we_cardinality` int(11) NOT NULL DEFAULT '0',
  `we_description` text NOT NULL,
  `we_mandatory` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wese_scheme`
--

CREATE TABLE IF NOT EXISTS `wese_scheme` (
  `id_sh` bigint(20) unsigned NOT NULL,
  `sh_name` char(150) NOT NULL,
  `sh_initials` char(20) NOT NULL,
  `sh_content` text NOT NULL,
  `sh_own` int(11) NOT NULL,
  `sh_lasupte` date NOT NULL DEFAULT '0000-00-00',
  `sh_active` int(11) NOT NULL DEFAULT '1',
  `sh_link` char(150) NOT NULL,
  `sh_icone` char(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wese_scheme`
--

INSERT INTO `wese_scheme` (`id_sh`, `sh_name`, `sh_initials`, `sh_content`, `sh_own`, `sh_lasupte`, `sh_active`, `sh_link`, `sh_icone`) VALUES
(1, 'Tesauro de Estudos Métricos', 'TEM', '', 1, '0000-00-00', 1, '', ''),
(2, 'Tecnologia da Informação e Comunicação', 'TIC', '', 1, '0000-00-00', 1, '', ''),
(3, 'Estudos da Religião', 'ER', '', 1, '0000-00-00', 1, '', ''),
(4, 'Organização da Informação', 'OI', '', 0, '0000-00-00', 1, '', ''),
(5, 'Gestão de bibliotecas e Recursos de Informação', 'GBRI', 'Gestão de Biblioteca e Recursos de Informação', 0, '0000-00-00', 1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `wese_term`
--

CREATE TABLE IF NOT EXISTS `wese_term` (
  `id_t` bigint(20) unsigned NOT NULL,
  `t_name` char(100) NOT NULL,
  `t_lang` char(5) NOT NULL,
  `t_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=308 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wese_term`
--

INSERT INTO `wese_term` (`id_t`, `t_name`, `t_lang`, `t_time`) VALUES
(1, 'Estudos métricos de informação', 'pt_BR', '2016-03-07 00:33:35'),
(2, 'Indicadores bibliométricos', 'pt_BR', '2016-03-07 00:33:35'),
(3, 'Métodos cientométricos', 'pt_BR', '2016-03-07 00:33:35'),
(4, 'Comportamento bibliométrico', 'pt_BR', '2016-03-07 00:33:35'),
(5, 'Estudos bibliométricos', 'pt_BR', '2016-03-07 00:33:35'),
(6, 'Estudo bibliométrico', 'pt_BR', '2016-03-07 00:33:35'),
(7, 'Indicadores webométricos', 'pt_BR', '2016-03-07 00:33:35'),
(8, 'Leis e indicadores cientométricos', 'pt_BR', '2016-03-07 00:33:35'),
(9, 'Análise bibliométrico', 'pt_BR', '2016-03-07 00:33:35'),
(10, 'Estudos métricos no Brasil', 'pt_BR', '2016-03-07 00:33:35'),
(11, 'Bibliometria', 'pt_BR', '2016-03-07 00:33:35'),
(12, 'Sociometria', 'pt_BR', '2016-03-07 00:33:35'),
(13, 'Cienciometria', 'pt_BR', '2016-03-07 00:33:35'),
(14, 'Cientometria', 'pt_BR', '2016-03-07 00:33:35'),
(15, 'Informetria', 'pt_BR', '2016-03-07 00:33:35'),
(16, 'Webometria', 'pt_BR', '2016-03-07 00:33:35'),
(17, 'Bibliometria cubana', 'pt_BR', '2016-03-07 00:33:35'),
(18, 'Biblio-metria', 'pt_BR', '2016-03-07 00:33:35'),
(19, 'Análise Bibliométria', 'pt_BR', '2016-03-07 00:33:35'),
(20, 'Lei de Lotka', 'pt_BR', '2016-03-07 00:33:35'),
(21, 'Altmetria', 'pt_BR', '2016-03-07 00:33:35'),
(22, 'Análise de redes sociais', 'pt_BR', '2016-03-07 00:33:35'),
(23, 'Lei de Bradford', 'pt_BR', '2016-03-07 00:33:35'),
(24, 'Lei de Zipf', 'pt_BR', '2016-03-07 00:33:35'),
(25, 'Leis bibliometricas', 'pt_BR', '2016-03-07 00:33:35'),
(26, 'Scientometrics', 'en_US', '2016-03-07 00:33:35'),
(27, 'Índice h', 'pt_BR', '2016-03-07 00:33:35'),
(28, 'Fator de Impacto', 'pt_BR', '2016-03-07 00:33:35'),
(29, 'Indicador de produção', 'pt_BR', '2016-03-07 00:33:35'),
(30, 'Indicador de citação', 'pt_BR', '2016-03-07 00:33:35'),
(31, 'Indicador de ligação', 'pt_BR', '2016-03-07 00:33:35'),
(32, 'Lei de Garfield', 'pt_BR', '2016-03-07 00:33:35'),
(33, 'Lei de Barton e Klever', 'pt_BR', '2016-03-07 00:33:35'),
(34, 'Lei de Goffman', 'pt_BR', '2016-03-07 00:33:35'),
(35, 'Autocitação', 'pt_BR', '2016-03-07 00:33:35'),
(36, 'Periféricos', 'pt_BR', '2016-03-07 00:33:35'),
(37, 'Unidade de armazenamento secundário', 'pt_BR', '2016-03-07 00:33:35'),
(38, 'Unidade de entrada', 'pt_BR', '2016-03-07 00:33:35'),
(39, 'Teclado de computador', 'pt_BR', '2016-03-07 00:33:35'),
(40, 'Camera digital', 'pt_BR', '2016-03-07 00:33:35'),
(41, 'Dispositivo de comando de voz', 'pt_BR', '2016-03-07 00:33:35'),
(42, 'Scanner', 'pt_BR', '2016-03-07 00:33:35'),
(43, 'Mesa digitalizadora', 'pt_BR', '2016-03-07 00:33:35'),
(44, 'Mouse', 'pt_BR', '2016-03-07 00:33:35'),
(45, 'Webcam', 'pt_BR', '2016-03-07 00:33:35'),
(46, 'Unidade de memória', 'pt_BR', '2016-03-07 00:33:35'),
(47, 'Memória caché', 'pt_BR', '2016-03-07 00:33:35'),
(48, 'Memória RAM', 'pt_BR', '2016-03-07 00:33:35'),
(49, 'Memória flash', 'pt_BR', '2016-03-07 00:33:35'),
(50, 'Unidade Central de Processamento', 'pt_BR', '2016-03-07 00:33:35'),
(51, 'CPU', 'pt_BR', '2016-03-07 00:33:35'),
(52, 'UCP', 'pt_BR', '2016-03-07 00:33:35'),
(53, 'Hardware', 'pt_BR', '2016-03-07 00:33:35'),
(54, 'Monitor de video', 'pt_BR', '2016-03-07 00:33:35'),
(55, 'Video Graphics Array', 'pt_BR', '2016-03-07 00:33:35'),
(56, 'Enhanced Graphics Adapter', 'pt_BR', '2016-03-07 00:33:35'),
(57, 'Color Graphics Adapter', 'pt_BR', '2016-03-07 00:33:35'),
(58, 'CGA', 'pt_BR', '2016-03-07 00:33:35'),
(59, 'VGA', 'pt_BR', '2016-03-07 00:33:35'),
(60, 'EGA', 'pt_BR', '2016-03-07 00:33:35'),
(61, 'Monochrome Display Adapter', 'en_US', '2016-03-07 00:33:35'),
(62, 'MDA', 'en_US', '2016-03-07 00:33:35'),
(63, 'Disco rígido SATA', 'pt_BR', '2016-03-07 00:33:35'),
(64, 'Disco rígido SATA II', 'pt_BR', '2016-03-07 00:33:35'),
(65, 'Hard disk drive', 'en_US', '2016-03-07 00:33:35'),
(66, 'HDD', 'en_US', '2016-03-07 00:33:35'),
(67, 'Disco rigido', 'pt_BR', '2016-03-07 00:33:35'),
(68, 'Memoria de massa', 'pt_BR', '2016-03-07 00:33:35'),
(69, 'Controlador de video', 'pt_BR', '2016-03-07 00:33:35'),
(70, 'Colaboração científica', 'pt_BR', '2016-03-07 00:33:35'),
(71, 'Coautoria', 'pt_BR', '2016-03-07 00:33:35'),
(72, 'Ensino Religioso', 'pt_BR', '2016-03-07 00:33:35'),
(73, 'Formação de Professor', 'pt_BR', '2016-03-07 00:33:35'),
(74, 'Ciências da Religião', 'pt_BR', '2016-03-07 00:33:35'),
(75, 'Laicidade', 'pt_BR', '2016-03-07 00:33:35'),
(76, 'Formação docente', 'pt_BR', '2016-03-07 00:33:35'),
(77, 'Ciência da Religião', 'pt_BR', '2016-03-07 00:33:35'),
(78, 'desenvolvimento da fé', 'pt_BR', '2016-03-07 00:33:35'),
(79, 'Diversidade', 'pt_BR', '2016-03-07 00:33:35'),
(80, 'Formação de professores', 'pt_BR', '2016-03-07 00:33:35'),
(81, 'Educação', 'pt_BR', '2016-03-07 00:33:35'),
(82, 'História da Educação', 'pt_BR', '2016-03-07 00:33:35'),
(83, 'Pluralismo', 'pt_BR', '2016-03-07 00:33:35'),
(84, 'Relações raciais', 'pt_BR', '2016-03-07 00:33:35'),
(85, 'Saberes docentes', 'pt_BR', '2016-03-07 00:33:35'),
(86, 'Escola pública', 'pt_BR', '2016-03-07 00:33:35'),
(87, 'Livro Didático', 'pt_BR', '2016-03-07 00:33:35'),
(88, 'Epistemologia', 'pt_BR', '2016-03-07 00:33:35'),
(89, 'Diálogo', 'pt_BR', '2016-03-07 00:33:35'),
(90, 'Didática', 'pt_BR', '2016-03-07 00:33:35'),
(91, 'Educação Básica', 'pt_BR', '2016-03-07 00:33:35'),
(92, 'Ensino Confessional', 'pt_BR', '2016-03-07 00:33:35'),
(93, 'Fenômeno religioso', 'pt_BR', '2016-03-07 00:33:35'),
(94, 'Legislação', 'pt_BR', '2016-03-07 00:33:35'),
(95, 'Práxis educativa', 'pt_BR', '2016-03-07 00:33:35'),
(96, 'Sagrado', 'pt_BR', '2016-03-07 00:33:35'),
(97, 'Sala de aula', 'pt_BR', '2016-03-07 00:33:35'),
(98, 'Transposição Didática', 'pt_BR', '2016-03-07 00:33:35'),
(99, 'Adolescência', 'pt_BR', '2016-03-07 00:33:35'),
(100, 'Metodologia', 'pt_BR', '2016-03-07 00:33:35'),
(101, 'escolas públicas', 'pt_BR', '2016-03-07 00:33:35'),
(102, 'Educação Infantil', 'pt_BR', '2016-03-07 00:33:35'),
(103, 'Catolicentrismo', 'pt_BR', '2016-03-07 00:33:35'),
(104, 'Confessional', 'pt_BR', '2016-03-07 00:33:35'),
(105, 'Educação confessional', 'pt_BR', '2016-03-07 00:33:35'),
(106, 'Espiritualidade', 'pt_BR', '2016-03-07 00:33:35'),
(107, 'Formação Integral', 'pt_BR', '2016-03-07 00:33:35'),
(108, 'Legislação educacional', 'pt_BR', '2016-03-07 00:33:35'),
(109, 'Professor', 'pt_BR', '2016-03-07 00:33:35'),
(110, 'Sistema de ensino', 'pt_BR', '2016-03-07 00:33:35'),
(111, 'Cidadania', 'pt_BR', '2016-03-07 00:33:35'),
(112, 'Diretrizes Curriculares', 'pt_BR', '2016-03-07 00:33:35'),
(113, 'Ensino a Distância', 'pt_BR', '2016-03-07 00:33:35'),
(114, 'EAD', 'pt_BR', '2016-03-07 00:33:35'),
(115, 'Proposta pedagógica', 'pt_BR', '2016-03-07 00:33:35'),
(116, 'Identidade pedagógica', 'pt_BR', '2016-03-07 00:33:35'),
(117, 'LDB', 'pt_BR', '2016-03-07 00:33:35'),
(118, 'Literatura', 'pt_BR', '2016-03-07 00:33:35'),
(119, 'História do Ensino Religioso', 'pt_BR', '2016-03-07 00:33:35'),
(120, 'Santa Catarina', 'pt_BR', '2016-03-07 00:33:35'),
(121, 'Estado laico', 'pt_BR', '2016-03-07 00:33:35'),
(122, 'Direitos Humanos', 'pt_BR', '2016-03-07 00:33:35'),
(123, 'Mato Grosso do Sul', 'pt_BR', '2016-03-07 00:33:35'),
(124, 'Escolas', 'pt_BR', '2016-03-07 00:33:35'),
(125, 'Escola particular', 'pt_BR', '2016-03-07 00:33:35'),
(126, 'Arquivometria', 'pt_BR', '2016-03-07 00:33:35'),
(127, 'Ranking', 'pt_BR', '2016-03-07 00:33:35'),
(128, 'Cibermetria', 'pt_BR', '2016-03-07 00:33:35'),
(129, 'Museometria', 'pt_BR', '2016-03-07 00:33:35'),
(130, 'Infometria', 'pt_BR', '2016-03-07 00:33:35'),
(131, 'Análise de cocitação', 'pt_BR', '2016-03-07 00:33:35'),
(132, 'Análise de citação', 'pt_BR', '2016-03-07 00:33:35'),
(133, 'Indicadores de proximidade', 'pt_BR', '2016-03-07 00:33:35'),
(134, 'Cosseno de Salton', 'pt_BR', '2016-03-07 00:33:35'),
(135, 'Índice de Jaccard', 'pt_BR', '2016-03-07 00:33:35'),
(136, 'Indicadores de produção científica', 'pt_BR', '2016-03-07 00:33:35'),
(137, 'Impacto da produção científica', 'pt_BR', '2016-03-07 00:33:35'),
(138, 'Fator de Impacto Web', 'pt_BR', '2016-03-07 00:33:35'),
(139, 'Contagem de palavras', 'pt_BR', '2016-03-07 00:33:35'),
(140, 'Ponto T de Goffman', 'pt_BR', '2016-03-07 00:33:35'),
(141, 'Redes de coautoria', 'pt_BR', '2016-03-07 00:33:35'),
(142, 'Estudo cientométrico', 'pt_BR', '2016-03-07 00:33:35'),
(143, 'Bases de dados', 'pt_BR', '2016-03-07 00:33:35'),
(144, 'Currículo lattes', 'pt_BR', '2016-03-07 00:33:35'),
(145, 'Análise multivariada', 'pt_BR', '2016-03-07 00:33:35'),
(146, 'Estudos quantitativos', 'pt_BR', '2016-03-07 00:33:35'),
(147, 'Estudos qualitativos', 'pt_BR', '2016-03-07 00:33:35'),
(148, 'Mineração de dados', 'pt_BR', '2016-03-07 00:33:35'),
(149, 'Outlier', 'pt_BR', '2016-03-07 00:33:35'),
(150, 'Métrica científica', 'pt_BR', '2016-03-07 00:33:35'),
(151, 'Correlação de Pearson', 'pt_BR', '2016-03-07 00:33:35'),
(152, 'coeficiente de pearson', 'pt_BR', '2016-03-07 00:33:35'),
(153, 'Coeficiente de correlação de Pearson', 'pt_BR', '2016-03-07 00:33:35'),
(154, 'Coeficiente de correlação', 'pt_BR', '2016-03-07 00:33:35'),
(155, 'Pearson', 'pt_BR', '2016-03-07 00:33:35'),
(156, 'Web of science', 'pt_BR', '2016-03-07 00:33:35'),
(157, 'Sience Citation Index (SCI)', 'pt_BR', '2016-03-07 00:33:35'),
(158, 'Brapci', 'pt_BR', '2016-03-07 00:33:35'),
(159, 'Scopus', 'pt_BR', '2016-03-07 00:33:35'),
(160, 'Ponto de Transição de Goffman', 'pt_BR', '2016-03-07 00:33:35'),
(161, 'Freqüência de palavras', 'pt_BR', '2016-03-07 00:33:35'),
(162, 'Região de transição de goffman', 'pt_BR', '2016-03-07 00:33:35'),
(163, 'Dispersão bibliográfica', 'pt_BR', '2016-03-07 00:33:35'),
(164, 'Lei de dispersão bibliográfica', 'pt_BR', '2016-03-07 00:33:35'),
(165, 'Metrias de informação', 'pt_BR', '2016-03-07 00:33:35'),
(166, 'Library and Information Science Abstracts', 'en_US', '2016-03-07 00:33:35'),
(167, 'Teorema de Bayes', 'pt_BR', '2016-03-07 00:33:35'),
(168, 'Inferência bayesiana', 'pt_BR', '2016-03-07 00:33:35'),
(169, 'Bibliografia estatística', 'pt_BR', '2016-03-07 00:33:35'),
(170, 'Produção científica', 'pt_BR', '2016-03-07 00:33:35'),
(171, 'Base Lattes', 'pt_BR', '2016-03-07 00:33:35'),
(172, 'Estudo métrico', 'pt_BR', '2016-03-07 00:33:35'),
(173, 'Mineração de textos', 'pt_BR', '2016-03-07 00:33:35'),
(174, 'Estudos Métricos', 'pt_BR', '2016-03-07 00:33:35'),
(175, 'Vida média', 'pt_BR', '2016-03-07 00:33:35'),
(176, 'Meia Vida', 'pt_BR', '2016-03-07 00:33:35'),
(177, 'Bibliometria na web', 'pt_BR', '2016-03-07 00:33:35'),
(178, 'Acoplamento bibliográfico', 'pt_BR', '2016-03-07 00:33:35'),
(179, 'Obsolescência da literatura', 'pt_BR', '2016-03-07 00:33:35'),
(180, 'Análise da produção científica', 'pt_BR', '2016-03-07 00:33:35'),
(181, 'Produção acadêmica', 'pt_BR', '2016-03-07 00:33:35'),
(182, 'Ranking de Periódicos', 'pt_BR', '2016-03-07 00:33:35'),
(183, 'Scielo', 'pt_BR', '2016-03-07 00:33:35'),
(184, 'Centralidade', 'pt_BR', '2016-03-07 00:33:35'),
(185, 'Densidade', 'pt_BR', '2016-03-07 00:33:35'),
(186, 'Coeficiente de Agrupamento', 'pt_BR', '2016-03-07 00:33:35'),
(187, 'Centralidade do ator', 'pt_BR', '2016-03-07 00:33:35'),
(188, 'Coesão', 'pt_BR', '2016-03-07 00:33:35'),
(189, 'Dublin Core', 'en_US', '2016-03-07 00:33:35'),
(190, 'Dublin Core Application Profile (DCAP)', 'en_US', '2016-03-07 00:33:35'),
(191, 'Midias sociais', 'pt_BR', '2016-03-07 00:33:35'),
(192, 'Webmetria', 'pt_BR', '2016-03-07 00:41:39'),
(193, 'Webomertia', 'pt_BR', '2016-03-07 01:12:17'),
(194, 'Resource description and access', 'en_US', '2016-03-07 01:53:15'),
(195, 'Rda', 'en_US', '2016-03-07 01:54:03'),
(196, 'Simple knowledge organization system', 'en_US', '2016-03-07 01:55:22'),
(197, 'Skos', 'en_US', '2016-03-07 01:55:44'),
(198, 'Simple knowledge organization systemmachine-readable cataloging', 'en_US', '2016-03-07 01:56:41'),
(199, 'Machine-readable cataloging', 'en_US', '2016-03-07 01:57:58'),
(200, 'Marc', 'en_US', '2016-03-07 01:58:19'),
(201, 'Marc21', 'en_US', '2016-03-07 01:58:28'),
(202, 'Web semântica', 'pt_BR', '2016-03-07 01:59:23'),
(203, 'Semantic web', 'en_US', '2016-03-07 02:00:19'),
(204, 'International standard bibliographic description', 'en_US', '2016-03-07 02:01:04'),
(205, 'Isbd', 'en_US', '2016-03-07 02:01:28'),
(206, 'Recuperação da informação', 'pt_BR', '2016-03-07 02:07:40'),
(207, 'Catalogação cooperativa', 'en_US', '2016-03-07 02:09:38'),
(208, 'Catalogação', 'pt_BR', '2016-03-08 02:31:23'),
(209, 'Catalogação na fonte', 'pt_BR', '2016-03-08 02:32:26'),
(210, 'Catalogação compartilhada', 'pt_BR', '2016-03-08 02:33:11'),
(211, 'Catalogação na publicação', 'pt_BR', '2016-03-08 02:33:50'),
(212, 'Catálogos coletivos', 'pt_BR', '2016-03-08 02:34:20'),
(213, 'Listas de autoridades', 'pt_BR', '2016-03-08 02:35:18'),
(214, 'Catálogos de autoridades', 'pt_BR', '2016-03-08 02:35:41'),
(215, 'Catálogos de cabeçalhos autorizados', 'pt_BR', '2016-03-08 02:36:03'),
(216, 'Cd-rom', 'en_US', '2016-03-08 02:37:12'),
(217, 'Discos óticos', 'pt_BR', '2016-03-08 02:38:49'),
(218, 'Cd', 'en_US', '2016-03-08 02:39:33'),
(219, 'Cd-r', 'en_US', '2016-03-08 02:39:43'),
(220, 'Cd-rw', 'en_US', '2016-03-08 02:39:53'),
(221, 'Classificação decimal de dewey', 'en_US', '2016-03-08 02:40:17'),
(222, 'Classificação decimal universal', 'en_US', '2016-03-08 02:40:29'),
(223, 'Cdd', 'en_US', '2016-03-08 02:40:43'),
(224, 'Cdu', 'en_US', '2016-03-08 02:40:56'),
(225, 'Centros de análise de informação', 'pt_BR', '2016-03-08 02:43:22'),
(226, 'Centrais de informação', 'pt_BR', '2016-03-08 02:43:49'),
(227, 'Bibliotecas', 'pt_BR', '2016-03-08 02:44:20'),
(228, 'Centros de documentação', 'pt_BR', '2016-03-08 02:44:47'),
(229, 'Bibliotecas especializadas', 'pt_BR', '2016-03-08 02:45:10'),
(230, 'Bibliotecas universitárias', 'pt_BR', '2016-03-08 02:45:24'),
(231, 'Centros de multimeios', 'en_US', '2016-03-08 02:45:53'),
(232, 'Bibliotecas multimídia', 'pt_BR', '2016-03-08 02:46:11'),
(233, 'Centros de meios audiovisuais', 'pt_BR', '2016-03-08 02:46:21'),
(234, 'Centros de mídia', 'pt_BR', '2016-03-08 02:46:31'),
(235, 'Centros de recursos audiovisuais', 'en_US', '2016-03-08 02:46:39'),
(236, 'Centros de recursos pedagógicos', 'pt_BR', '2016-03-08 02:46:50'),
(237, 'Midiatecas', 'pt_BR', '2016-03-08 02:46:59'),
(238, 'Internet', 'pt_BR', '2016-03-08 02:48:00'),
(239, 'Cinematecas', 'pt_BR', '2016-03-08 02:50:01'),
(240, 'Bibliotecas de filmes', 'pt_BR', '2016-03-08 02:50:21'),
(241, 'Coleções de filmes', 'pt_BR', '2016-03-08 02:50:30'),
(242, 'Filmotecas', 'pt_BR', '2016-03-08 02:50:40'),
(243, 'Classificação facetada', 'pt_BR', '2016-03-08 02:51:25'),
(244, 'Classificação analítico-sistemática', 'pt_BR', '2016-03-08 02:51:40'),
(245, 'Classificação de bliss', 'pt_BR', '2016-03-08 02:52:02'),
(246, 'Classificação dos dois pontos', 'pt_BR', '2016-03-08 02:52:44'),
(247, 'Classificação hierárquico-enumerativa', 'pt_BR', '2016-03-08 02:53:02'),
(248, 'Classificação por facetas', 'pt_BR', '2016-03-08 02:53:26'),
(249, 'Classificação enumerativa', 'pt_BR', '2016-03-08 02:54:34'),
(250, 'Classificação hierárquica', 'pt_BR', '2016-03-08 02:54:50'),
(251, 'Autoria coletiva', 'pt_BR', '2016-03-08 02:55:28'),
(252, 'Cocitação', 'pt_BR', '2016-03-08 02:56:51'),
(253, 'Análise de cocitação2', 'pt_BR', '2016-03-08 02:57:14'),
(254, 'Estudos de cocitação', 'pt_BR', '2016-03-08 02:57:24'),
(255, 'Anglo american cataloging rules', 'en_US', '2016-03-08 03:00:06'),
(256, 'AACR2', 'pt_BR', '2016-03-08 03:00:29'),
(257, 'Computadores', 'pt_BR', '2016-03-09 01:03:08'),
(258, 'Sinopses', 'pt_BR', '2016-03-09 01:04:58'),
(259, 'Condensações', 'en_US', '2016-03-09 01:05:14'),
(260, 'Linguagens documentárias', 'pt_BR', '2016-03-09 01:09:32'),
(261, 'Listas de cabeçalhos de assunto', 'pt_BR', '2016-03-09 01:10:55'),
(262, 'Library of congress subject headings', 'en_US', '2016-03-09 01:11:11'),
(263, 'Lisa', 'en_US', '2016-03-09 01:19:34'),
(264, 'Representação descritiva', 'pt_BR', '2016-03-18 00:12:03'),
(265, 'Representação de informação', 'pt_BR', '2016-03-18 00:13:04'),
(266, 'Representação do conhecimento', 'pt_BR', '2016-03-18 00:13:30'),
(267, 'Representação do conhecimento - rc', 'pt_BR', '2016-03-18 00:13:59'),
(268, 'Representação do conhecimento registrado', 'pt_BR', '2016-03-18 00:14:22'),
(269, 'Representação documental', 'pt_BR', '2016-03-18 00:14:45'),
(270, 'Representação documentária', 'pt_BR', '2016-03-18 00:15:02'),
(271, 'Representação informacional', 'pt_BR', '2016-03-18 00:15:45'),
(272, 'Representação temática', 'pt_BR', '2016-03-18 00:16:11'),
(273, 'Frbr', 'pt_BR', '2016-03-18 00:17:11'),
(274, 'Metadados', 'pt_BR', '2016-03-18 00:18:35'),
(275, 'Descrição de recursos', 'pt_BR', '2016-03-18 00:24:54'),
(276, 'Perfil de aplicação de metadados', 'pt_BR', '2016-03-18 00:26:00'),
(277, 'Vocabulário controlado', 'pt_BR', '2016-03-18 00:27:04'),
(278, 'Controle bibliográfico universal', 'pt_BR', '2016-03-18 00:30:42'),
(279, 'Cbu', 'pt_BR', '2016-03-18 00:30:59'),
(280, 'Depósito legal', 'pt_BR', '2016-03-18 00:33:52'),
(281, 'Isbn', 'pt_BR', '2016-03-18 00:35:04'),
(282, 'ISSN', 'pt_BR', '2016-03-18 00:35:26'),
(283, 'RepresentaÇão descritiva da informação', 'pt_BR', '2016-03-18 00:36:39'),
(284, 'Bibliotecas digitais', 'pt_BR', '2016-03-18 00:38:02'),
(285, 'Winchester', 'en_US', '2016-03-18 10:16:20'),
(286, 'Functional requirements for bibliographic records', 'en_US', '2016-03-19 23:07:32'),
(287, 'Requisitos funcionais para registros bibliográficos', 'pt_BR', '2016-03-19 23:07:45'),
(288, 'FRAD', 'en_US', '2016-03-19 23:09:50'),
(289, 'FRSAD', 'en_US', '2016-03-19 23:10:04'),
(290, 'Functional requirements for authority data', 'en_US', '2016-03-19 23:10:23'),
(291, 'Functional requirements for subject authority data', 'en_US', '2016-03-19 23:10:59'),
(292, 'Sparql', 'en_US', '2016-03-19 23:17:34'),
(293, 'Protocol and rdf query language', 'en_US', '2016-03-19 23:17:53'),
(294, 'Uri', 'en_US', '2016-03-19 23:18:17'),
(295, 'Uniform resource identifiers', 'en_US', '2016-03-19 23:18:32'),
(296, 'Url', 'en_US', '2016-03-19 23:19:18'),
(297, 'Uniform resource locator', 'en_US', '2016-03-19 23:19:29'),
(298, 'Urn', 'en_US', '2016-03-19 23:20:01'),
(299, 'Uniform resource name', 'en_US', '2016-03-19 23:20:10'),
(300, 'Rdf', 'en_US', '2016-03-19 23:20:57'),
(301, 'Resource description framework', 'en_US', '2016-03-19 23:21:15'),
(302, 'Tecnologias de informação e comunicação', 'pt_BR', '2016-03-20 21:22:04'),
(303, 'Tics', 'pt_BR', '2016-03-20 21:22:26'),
(304, 'Tic', 'pt_BR', '2016-03-20 21:22:34'),
(305, 'Business intelligence', 'pt_BR', '2016-03-24 22:26:47'),
(306, 'Serviço de extensao', 'pt_BR', '2016-07-12 13:41:08'),
(307, 'Biblioteca', 'pt_BR', '2016-10-20 12:04:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wese_concept`
--
ALTER TABLE `wese_concept`
  ADD UNIQUE KEY `id_c` (`id_c`);

--
-- Indexes for table `wese_concept_tg`
--
ALTER TABLE `wese_concept_tg`
  ADD UNIQUE KEY `id_tg` (`id_tg`);

--
-- Indexes for table `wese_label`
--
ALTER TABLE `wese_label`
  ADD UNIQUE KEY `id_l` (`id_l`);

--
-- Indexes for table `wese_language`
--
ALTER TABLE `wese_language`
  ADD UNIQUE KEY `id_lg` (`id_lg`);

--
-- Indexes for table `wese_note`
--
ALTER TABLE `wese_note`
  ADD UNIQUE KEY `id_wn` (`id_wn`);

--
-- Indexes for table `wese_pa`
--
ALTER TABLE `wese_pa`
  ADD UNIQUE KEY `id_we` (`id_we`);

--
-- Indexes for table `wese_scheme`
--
ALTER TABLE `wese_scheme`
  ADD UNIQUE KEY `id_sh` (`id_sh`);

--
-- Indexes for table `wese_term`
--
ALTER TABLE `wese_term`
  ADD UNIQUE KEY `id_t` (`id_t`),
  ADD UNIQUE KEY `term_label` (`t_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wese_concept`
--
ALTER TABLE `wese_concept`
  MODIFY `id_c` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=154;
--
-- AUTO_INCREMENT for table `wese_concept_tg`
--
ALTER TABLE `wese_concept_tg`
  MODIFY `id_tg` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=85;
--
-- AUTO_INCREMENT for table `wese_label`
--
ALTER TABLE `wese_label`
  MODIFY `id_l` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=278;
--
-- AUTO_INCREMENT for table `wese_language`
--
ALTER TABLE `wese_language`
  MODIFY `id_lg` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `wese_note`
--
ALTER TABLE `wese_note`
  MODIFY `id_wn` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `wese_pa`
--
ALTER TABLE `wese_pa`
  MODIFY `id_we` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wese_scheme`
--
ALTER TABLE `wese_scheme`
  MODIFY `id_sh` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `wese_term`
--
ALTER TABLE `wese_term`
  MODIFY `id_t` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=308;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
