-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 06, 2016 at 12:02 PM
-- Server version: 5.6.20-log
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `skos`
--

-- --------------------------------------------------------

--
-- Table structure for table `wese_concept`
--

CREATE TABLE IF NOT EXISTS `wese_concept` (
`id_c` bigint(20) unsigned NOT NULL,
  `c_id` char(50) NOT NULL,
  `c_scheme` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=99 ;

--
-- Dumping data for table `wese_concept`
--

INSERT INTO `wese_concept` (`id_c`, `c_id`, `c_scheme`) VALUES
(1, 'estudos_metricos_de_informacao', 1),
(2, 'cientometria', 1),
(3, 'lei_de_zipf', 1),
(4, 'lei_de_lotka', 1),
(5, 'lei_de_garfield', 1),
(6, 'leis_bibliometricas', 1),
(7, 'lei_de_bradford', 1),
(8, 'lei_de_barton_e_klever', 1),
(9, 'lei_de_goffman', 1),
(10, 'bibliometria', 1),
(11, 'indicador_de_producao', 1),
(12, 'indicador_de_ligacao', 1),
(13, 'indicador_de_citacao', 1),
(14, 'fator_de_impacto', 1),
(15, 'analise_de_redes_sociais', 1),
(16, 'altmetria', 1),
(17, 'webometria', 1),
(18, 'indice_h', 1),
(19, 'informetria', 1),
(20, 'autocitacao', 1),
(21, 'perifericos', 2),
(22, 'unidade_de_entrada', 2),
(23, 'unidade_de_armazenamento_secundario', 2),
(24, 'teclado_de_computador', 2),
(25, 'camera_digital', 2),
(26, 'dispositivo_de_comando_de_voz', 2),
(27, 'scanner', 2),
(28, 'mesa_digitalizadora', 2),
(29, 'webcam', 2),
(30, 'mouse', 2),
(31, 'memoria_ram', 2),
(32, 'memoria_flash', 2),
(33, 'memoria_cache', 2),
(34, 'unidade_de_memoria', 2),
(35, 'unidade_central_de_processamento', 2),
(36, 'hardware', 2),
(37, 'monitor_de_video', 2),
(38, 'video_graphics_array', 2),
(39, 'enhanced_graphics_adapter', 2),
(40, 'color_graphics_adapter', 2),
(41, 'monochrome_display_adapter', 2),
(42, 'disco_rigido_sata', 2),
(43, 'disco_rigido_sata_ii', 2),
(44, 'disco_rigido', 2),
(45, 'controlador_de_video', 2),
(46, 'colaboracao_cientifica', 1),
(47, 'coautoria', 1),
(48, 'ensino_religioso', 3),
(49, 'formacao_de_professor', 3),
(50, 'escola_publica', 3),
(51, 'educacao_infantil', 3),
(52, 'educacao_basica', 3),
(53, 'educacao', 3),
(54, 'ensino_a_distancia', 3),
(55, 'epistemologia', 3),
(56, 'adolescencia', 3),
(57, 'catolicentrismo', 3),
(58, 'direitos_humanos', 3),
(59, 'educacao_confessional', 3),
(60, 'historia_da_educacao', 3),
(61, 'historia_do_ensino_religioso', 3),
(62, 'legislacao', 3),
(63, 'legislacao_educacional', 3),
(64, 'escolas', 3),
(65, 'escola_particular', 3),
(66, 'cidadania', 3),
(67, 'arquivometria', 1),
(68, 'ranking', 1),
(69, 'museometria', 1),
(70, 'analise_de_citacao', 1),
(71, 'coeficiente_de_pearson', 1),
(72, 'sience_citation_index', 1),
(73, 'santa_catarina', 1),
(74, 'web_of_science', 1),
(75, 'fator_de_impacto_web', 1),
(76, 'analise_de_cocitacao', 1),
(77, 'bases_de_dados', 1),
(78, 'lisa', 1),
(79, 'brapci', 1),
(80, 'scopus', 1),
(81, 'outlier', 1),
(82, 'mineracao_de_dados', 1),
(83, 'curriculo_lattes', 1),
(84, 'teorema_de_bayes', 1),
(85, 'indice_de_jaccard', 1),
(86, 'meia_vida', 1),
(87, 'acoplamento_bibliografico', 1),
(88, 'obsolescencia_da_literatura', 1),
(89, 'analise_da_producao_cientifica', 1),
(90, 'ranking_de_periodicos', 1),
(91, 'scielo', 1),
(92, 'coeficiente_de_agrupamento', 1),
(93, 'densidade', 1),
(94, 'centralidade', 1),
(95, 'coesao', 1),
(96, 'dublin_core', 2),
(97, 'dublin_core_application_profile_(dcap)', 2),
(98, 'midias_sociais', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wese_concept_tg`
--

CREATE TABLE IF NOT EXISTS `wese_concept_tg` (
`id_tg` bigint(20) unsigned NOT NULL,
  `tg_conceito_1` int(11) NOT NULL,
  `tg_conceito_2` int(11) NOT NULL,
  `tg_scheme` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=85 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=154 ;

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
(153, 98, 191, 'PREF', '2016-03-06');

-- --------------------------------------------------------

--
-- Table structure for table `wese_language`
--

CREATE TABLE IF NOT EXISTS `wese_language` (
  `id_lang` char(5) NOT NULL,
  `lang_name` char(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wese_language`
--

INSERT INTO `wese_language` (`id_lang`, `lang_name`) VALUES
('en_US', 'English'),
('pt_BR', 'Português Brasil');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `wese_scheme`
--

INSERT INTO `wese_scheme` (`id_sh`, `sh_name`, `sh_initials`, `sh_content`, `sh_own`, `sh_lasupte`, `sh_active`, `sh_link`, `sh_icone`) VALUES
(1, 'Tesauro de Estudos Métricos', 'TEM', '', 1, '0000-00-00', 1, '', ''),
(2, 'Tecnologia da Informação e Comunicação', 'TIC', '', 1, '0000-00-00', 1, '', ''),
(3, 'Estudos da Religião', 'ER', '', 1, '0000-00-00', 1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `wese_term`
--

CREATE TABLE IF NOT EXISTS `wese_term` (
`id_t` bigint(20) unsigned NOT NULL,
  `t_name` char(100) NOT NULL,
  `t_lang` char(5) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=192 ;

--
-- Dumping data for table `wese_term`
--

INSERT INTO `wese_term` (`id_t`, `t_name`, `t_lang`) VALUES
(1, 'Estudos métricos de informação', 'pt_BR'),
(2, 'Indicadores bibliométricos', 'pt_BR'),
(3, 'Métodos cientométricos', 'pt_BR'),
(4, 'Comportamento bibliométrico', 'pt_BR'),
(5, 'Estudos bibliométricos', 'pt_BR'),
(6, 'Estudo bibliométrico', 'pt_BR'),
(7, 'Indicadores webométricos', 'pt_BR'),
(8, 'Leis e indicadores cientométricos', 'pt_BR'),
(9, 'Análise bibliométrico', 'pt_BR'),
(10, 'Estudos métricos no Brasil', 'pt_BR'),
(11, 'Bibliometria', 'pt_BR'),
(12, 'Sociometria', 'pt_BR'),
(13, 'Cienciometria', 'pt_BR'),
(14, 'Cientometria', 'pt_BR'),
(15, 'Informetria', 'pt_BR'),
(16, 'Webometria', 'pt_BR'),
(17, 'Bibliometria cubana', 'pt_BR'),
(18, 'Biblio-metria', 'pt_BR'),
(19, 'Análise Bibliométria', 'pt_BR'),
(20, 'Lei de Lotka', 'pt_BR'),
(21, 'Altmetria', 'pt_BR'),
(22, 'Análise de redes sociais', 'pt_BR'),
(23, 'Lei de Bradford', 'pt_BR'),
(24, 'Lei de Zipf', 'pt_BR'),
(25, 'Leis bibliometricas', 'pt_BR'),
(26, 'Scientometrics', 'en_US'),
(27, 'Índice h', 'pt_BR'),
(28, 'Fator de Impacto', 'pt_BR'),
(29, 'Indicador de produção', 'pt_BR'),
(30, 'Indicador de citação', 'pt_BR'),
(31, 'Indicador de ligação', 'pt_BR'),
(32, 'Lei de Garfield', 'pt_BR'),
(33, 'Lei de Barton e Klever', 'pt_BR'),
(34, 'Lei de Goffman', 'pt_BR'),
(35, 'Autocitação', 'pt_BR'),
(36, 'Periféricos', 'pt_BR'),
(37, 'Unidade de armazenamento secundário', 'pt_BR'),
(38, 'Unidade de entrada', 'pt_BR'),
(39, 'Teclado de computador', 'pt_BR'),
(40, 'Camera digital', 'pt_BR'),
(41, 'Dispositivo de comando de voz', 'pt_BR'),
(42, 'Scanner', 'pt_BR'),
(43, 'Mesa digitalizadora', 'pt_BR'),
(44, 'Mouse', 'pt_BR'),
(45, 'Webcam', 'pt_BR'),
(46, 'Unidade de memória', 'pt_BR'),
(47, 'Memória caché', 'pt_BR'),
(48, 'Memória RAM', 'pt_BR'),
(49, 'Memória flash', 'pt_BR'),
(50, 'Unidade Central de Processamento', 'pt_BR'),
(51, 'CPU', 'pt_BR'),
(52, 'UCP', 'pt_BR'),
(53, 'Hardware', 'pt_BR'),
(54, 'Monitor de video', 'pt_BR'),
(55, 'Video Graphics Array', 'pt_BR'),
(56, 'Enhanced Graphics Adapter', 'pt_BR'),
(57, 'Color Graphics Adapter', 'pt_BR'),
(58, 'CGA', 'pt_BR'),
(59, 'VGA', 'pt_BR'),
(60, 'EGA', 'pt_BR'),
(61, 'Monochrome Display Adapter', 'en_US'),
(62, 'MDA', 'en_US'),
(63, 'Disco rígido SATA', 'pt_BR'),
(64, 'Disco rígido SATA II', 'pt_BR'),
(65, 'Hard disk drive', 'en_US'),
(66, 'HDD', 'en_US'),
(67, 'Disco rigido', 'pt_BR'),
(68, 'Memoria de massa', 'pt_BR'),
(69, 'Controlador de video', 'pt_BR'),
(70, 'Colaboração científica', 'pt_BR'),
(71, 'Coautoria', 'pt_BR'),
(72, 'Ensino Religioso', 'pt_BR'),
(73, 'Formação de Professor', 'pt_BR'),
(74, 'Ciências da Religião', 'pt_BR'),
(75, 'Laicidade', 'pt_BR'),
(76, 'Formação docente', 'pt_BR'),
(77, 'Ciência da Religião', 'pt_BR'),
(78, 'desenvolvimento da fé', 'pt_BR'),
(79, 'Diversidade', 'pt_BR'),
(80, 'Formação de professores', 'pt_BR'),
(81, 'Educação', 'pt_BR'),
(82, 'História da Educação', 'pt_BR'),
(83, 'Pluralismo', 'pt_BR'),
(84, 'Relações raciais', 'pt_BR'),
(85, 'Saberes docentes', 'pt_BR'),
(86, 'Escola pública', 'pt_BR'),
(87, 'Livro Didático', 'pt_BR'),
(88, 'Epistemologia', 'pt_BR'),
(89, 'Diálogo', 'pt_BR'),
(90, 'Didática', 'pt_BR'),
(91, 'Educação Básica', 'pt_BR'),
(92, 'Ensino Confessional', 'pt_BR'),
(93, 'Fenômeno religioso', 'pt_BR'),
(94, 'Legislação', 'pt_BR'),
(95, 'Práxis educativa', 'pt_BR'),
(96, 'Sagrado', 'pt_BR'),
(97, 'Sala de aula', 'pt_BR'),
(98, 'Transposição Didática', 'pt_BR'),
(99, 'Adolescência', 'pt_BR'),
(100, 'Metodologia', 'pt_BR'),
(101, 'escolas públicas', 'pt_BR'),
(102, 'Educação Infantil', 'pt_BR'),
(103, 'Catolicentrismo', 'pt_BR'),
(104, 'Confessional', 'pt_BR'),
(105, 'Educação confessional', 'pt_BR'),
(106, 'Espiritualidade', 'pt_BR'),
(107, 'Formação Integral', 'pt_BR'),
(108, 'Legislação educacional', 'pt_BR'),
(109, 'Professor', 'pt_BR'),
(110, 'Sistema de ensino', 'pt_BR'),
(111, 'Cidadania', 'pt_BR'),
(112, 'Diretrizes Curriculares', 'pt_BR'),
(113, 'Ensino a Distância', 'pt_BR'),
(114, 'EAD', 'pt_BR'),
(115, 'Proposta pedagógica', 'pt_BR'),
(116, 'Identidade pedagógica', 'pt_BR'),
(117, 'LDB', 'pt_BR'),
(118, 'Literatura', 'pt_BR'),
(119, 'História do Ensino Religioso', 'pt_BR'),
(120, 'Santa Catarina', 'pt_BR'),
(121, 'Estado laico', 'pt_BR'),
(122, 'Direitos Humanos', 'pt_BR'),
(123, 'Mato Grosso do Sul', 'pt_BR'),
(124, 'Escolas', 'pt_BR'),
(125, 'Escola particular', 'pt_BR'),
(126, 'Arquivometria', 'pt_BR'),
(127, 'Ranking', 'pt_BR'),
(128, 'Cibermetria', 'pt_BR'),
(129, 'Museometria', 'pt_BR'),
(130, 'Infometria', 'pt_BR'),
(131, 'Análise de cocitação', 'pt_BR'),
(132, 'Análise de citação', 'pt_BR'),
(133, 'Indicadores de proximidade', 'pt_BR'),
(134, 'Cosseno de Salton', 'pt_BR'),
(135, 'Índice de Jaccard', 'pt_BR'),
(136, 'Indicadores de produção científica', 'pt_BR'),
(137, 'Impacto da produção científica', 'pt_BR'),
(138, 'Fator de Impacto Web', 'pt_BR'),
(139, 'Contagem de palavras', 'pt_BR'),
(140, 'Ponto T de Goffman', 'pt_BR'),
(141, 'Redes de coautoria', 'pt_BR'),
(142, 'Estudo cientométrico', 'pt_BR'),
(143, 'Bases de dados', 'pt_BR'),
(144, 'Currículo lattes', 'pt_BR'),
(145, 'Análise multivariada', 'pt_BR'),
(146, 'Estudos quantitativos', 'pt_BR'),
(147, 'Estudos qualitativos', 'pt_BR'),
(148, 'Mineração de dados', 'pt_BR'),
(149, 'Outlier', 'pt_BR'),
(150, 'Métrica científica', 'pt_BR'),
(151, 'Correlação de Pearson', 'pt_BR'),
(152, 'coeficiente de pearson', 'pt_BR'),
(153, 'Coeficiente de correlação de Pearson', 'pt_BR'),
(154, 'Coeficiente de correlação', 'pt_BR'),
(155, 'Pearson', 'pt_BR'),
(156, 'Web of science', 'pt_BR'),
(157, 'Sience Citation Index (SCI)', 'pt_BR'),
(158, 'Brapci', 'pt_BR'),
(159, 'Scopus', 'pt_BR'),
(160, 'Ponto de Transição de Goffman', 'pt_BR'),
(161, 'Freqüência de palavras', 'pt_BR'),
(162, 'Região de transição de goffman', 'pt_BR'),
(163, 'Dispersão bibliográfica', 'pt_BR'),
(164, 'Lei de dispersão bibliográfica', 'pt_BR'),
(165, 'Metrias de informação', 'pt_BR'),
(166, 'LISA', 'pt_BR'),
(167, 'Teorema de Bayes', 'pt_BR'),
(168, 'Inferência bayesiana', 'pt_BR'),
(169, 'Bibliografia estatística', 'pt_BR'),
(170, 'Produção científica', 'pt_BR'),
(171, 'Base Lattes', 'pt_BR'),
(172, 'Estudo métrico', 'pt_BR'),
(173, 'Mineração de textos', 'pt_BR'),
(174, 'Estudos Métricos', 'pt_BR'),
(175, 'Vida média', 'pt_BR'),
(176, 'Meia Vida', 'pt_BR'),
(177, 'Bibliometria na web', 'pt_BR'),
(178, 'Acoplamento bibliográfico', 'pt_BR'),
(179, 'Obsolescência da literatura', 'pt_BR'),
(180, 'Análise da produção científica', 'pt_BR'),
(181, 'Produção acadêmica', 'pt_BR'),
(182, 'Ranking de Periódicos', 'pt_BR'),
(183, 'Scielo', 'pt_BR'),
(184, 'Centralidade', 'pt_BR'),
(185, 'Densidade', 'pt_BR'),
(186, 'Coeficiente de Agrupamento', 'pt_BR'),
(187, 'Centralidade do ator', 'pt_BR'),
(188, 'Coesão', 'pt_BR'),
(189, 'Dublin Core', 'en_US'),
(190, 'Dublin Core Application Profile (DCAP)', 'en_US'),
(191, 'Midias sociais', 'pt_BR');

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
-- Indexes for table `wese_scheme`
--
ALTER TABLE `wese_scheme`
 ADD UNIQUE KEY `id_sh` (`id_sh`);

--
-- Indexes for table `wese_term`
--
ALTER TABLE `wese_term`
 ADD UNIQUE KEY `id_t` (`id_t`), ADD UNIQUE KEY `term_label` (`t_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wese_concept`
--
ALTER TABLE `wese_concept`
MODIFY `id_c` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=99;
--
-- AUTO_INCREMENT for table `wese_concept_tg`
--
ALTER TABLE `wese_concept_tg`
MODIFY `id_tg` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=85;
--
-- AUTO_INCREMENT for table `wese_label`
--
ALTER TABLE `wese_label`
MODIFY `id_l` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=154;
--
-- AUTO_INCREMENT for table `wese_scheme`
--
ALTER TABLE `wese_scheme`
MODIFY `id_sh` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `wese_term`
--
ALTER TABLE `wese_term`
MODIFY `id_t` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=192;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
