-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 20, 2015 at 03:15 PM
-- Server version: 5.6.20-log
-- PHP Version: 5.4.31

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
-- Table structure for table `metodologia`
--

CREATE TABLE IF NOT EXISTS `metodologia` (
`id_met` bigint(20) unsigned NOT NULL,
  `met_objetivo` text NOT NULL,
  `met_fonte` text NOT NULL,
  `met_article` char(10) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `metodologia`
--

INSERT INTO `metodologia` (`id_met`, `met_objetivo`, `met_fonte`, `met_article`) VALUES
(1, 'elaborar um conjunto de indicadores sobre o uso do acervo', '119.720 registros referentes à circulação (empréstimo, renovação e reservas) do acervo', '0000016245');

-- --------------------------------------------------------

--
-- Table structure for table `metodologia_artigo_instrumento`
--

CREATE TABLE IF NOT EXISTS `metodologia_artigo_instrumento` (
`id_mai` bigint(20) unsigned NOT NULL,
  `mai_article` char(10) NOT NULL,
  `mai_instrumento` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `metodologia_artigo_instrumento`
--

INSERT INTO `metodologia_artigo_instrumento` (`id_mai`, `mai_article`, `mai_instrumento`) VALUES
(1, '0000016245', 1);

-- --------------------------------------------------------

--
-- Table structure for table `metodologia_artigo_tipo`
--

CREATE TABLE IF NOT EXISTS `metodologia_artigo_tipo` (
`id_mat` bigint(20) unsigned NOT NULL,
  `mat_artigo` char(10) NOT NULL,
  `mat_tipo` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `metodologia_artigo_tipo`
--

INSERT INTO `metodologia_artigo_tipo` (`id_mat`, `mat_artigo`, `mat_tipo`) VALUES
(1, '0000016245', 1),
(2, '0000016245', 2);

-- --------------------------------------------------------

--
-- Table structure for table `metodologia_instrumentos`
--

CREATE TABLE IF NOT EXISTS `metodologia_instrumentos` (
`id_mi` bigint(20) unsigned NOT NULL,
  `mi_nome` char(250) NOT NULL,
  `mi_descricao` text NOT NULL,
  `mi_ativo` tinytext NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `metodologia_instrumentos`
--

INSERT INTO `metodologia_instrumentos` (`id_mi`, `mi_nome`, `mi_descricao`, `mi_ativo`) VALUES
(1, 'VantagePoint®', '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `metodologia_tipo`
--

CREATE TABLE IF NOT EXISTS `metodologia_tipo` (
`id_mett` bigint(20) unsigned NOT NULL,
  `mett_descricao` char(250) NOT NULL,
  `mett_definicao` text NOT NULL,
  `mett_ativo` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `metodologia_tipo`
--

INSERT INTO `metodologia_tipo` (`id_mett`, `mett_descricao`, `mett_definicao`, `mett_ativo`) VALUES
(1, 'Análise de Redes sociais', '', 1),
(2, 'Bibliometria', '', 1),
(3, 'Informetria / Infometria', '', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `metodologia`
--
ALTER TABLE `metodologia`
 ADD UNIQUE KEY `id_met` (`id_met`);

--
-- Indexes for table `metodologia_artigo_instrumento`
--
ALTER TABLE `metodologia_artigo_instrumento`
 ADD UNIQUE KEY `id_mai` (`id_mai`);

--
-- Indexes for table `metodologia_artigo_tipo`
--
ALTER TABLE `metodologia_artigo_tipo`
 ADD UNIQUE KEY `id_mat` (`id_mat`);

--
-- Indexes for table `metodologia_instrumentos`
--
ALTER TABLE `metodologia_instrumentos`
 ADD UNIQUE KEY `id_mi` (`id_mi`);

--
-- Indexes for table `metodologia_tipo`
--
ALTER TABLE `metodologia_tipo`
 ADD UNIQUE KEY `id_mett` (`id_mett`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `metodologia`
--
ALTER TABLE `metodologia`
MODIFY `id_met` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `metodologia_artigo_instrumento`
--
ALTER TABLE `metodologia_artigo_instrumento`
MODIFY `id_mai` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `metodologia_artigo_tipo`
--
ALTER TABLE `metodologia_artigo_tipo`
MODIFY `id_mat` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `metodologia_instrumentos`
--
ALTER TABLE `metodologia_instrumentos`
MODIFY `id_mi` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `metodologia_tipo`
--
ALTER TABLE `metodologia_tipo`
MODIFY `id_mett` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
