-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 08, 2016 at 03:12 AM
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
-- Table structure for table `mensagem_own`
--

CREATE TABLE IF NOT EXISTS `mensagem_own` (
`id_m` bigint(20) unsigned NOT NULL,
  `m_descricao` char(150) NOT NULL,
  `m_header` char(150) NOT NULL,
  `m_foot` char(150) NOT NULL,
  `m_ativo` tinyint(4) NOT NULL,
  `m_email` char(100) NOT NULL,
  `m_own_cod` char(10) NOT NULL,
  `smtp_host` char(80) NOT NULL,
  `smtp_user` char(80) NOT NULL,
  `smtp_pass` char(80) NOT NULL,
  `smtp_protocol` char(5) NOT NULL,
  `smtp_port` char(3) NOT NULL,
  `mailtype` char(5) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mensagem_own`
--

INSERT INTO `mensagem_own` (`id_m`, `m_descricao`, `m_header`, `m_foot`, `m_ativo`, `m_email`, `m_own_cod`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_protocol`, `smtp_port`, `mailtype`) VALUES
(1, 'Brapci', '', '', 1, 'brapci@brapci.inf.br', '', 'mil.brapci.inf.br', 'brapci@brapci.inf.br', '448545ct', 'smtp', '587', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mensagem_own`
--
ALTER TABLE `mensagem_own`
 ADD UNIQUE KEY `id_m` (`id_m`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mensagem_own`
--
ALTER TABLE `mensagem_own`
MODIFY `id_m` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
