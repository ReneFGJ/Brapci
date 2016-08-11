-- phpMyAdmin SQL Dump
-- version 4.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 07, 2016 at 10:54 PM
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
-- Table structure for table `pdf_download`
--

CREATE TABLE IF NOT EXISTS `pdf_download` (
  `id_pdf` bigint(20) unsigned NOT NULL,
  `pdf_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pdf_ip` char(15) NOT NULL,
  `pdf_id` int(11) NOT NULL,
  `pdf_session` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pdf_download`
--
ALTER TABLE `pdf_download`
  ADD UNIQUE KEY `id_pdf` (`id_pdf`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pdf_download`
--
ALTER TABLE `pdf_download`
  MODIFY `id_pdf` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
