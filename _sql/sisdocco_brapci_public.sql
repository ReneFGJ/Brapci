-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2013 at 07:21 AM
-- Server version: 5.1.66-cll
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sisdocco_brapci_public`
--

-- --------------------------------------------------------

--
-- Table structure for table `artigos`
--

CREATE TABLE IF NOT EXISTS `artigos` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ar_asc` text NOT NULL,
  `ar_asc_mini` text NOT NULL,
  `Author_Analytic` longtext,
  `Author_Role` longtext,
  `Author_Affiliation` longtext,
  `Article_Title` longtext,
  `Medium_Designator` longtext,
  `Author_2` longtext,
  `Journal_Title` longtext,
  `Title_2` longtext,
  `Reprint_Status` longtext,
  `Date_Publication` longtext,
  `Volume_ID` longtext,
  `Issue_ID` longtext,
  `Pages` longtext,
  `Idioma` longtext,
  `Availability` longtext,
  `URL` longtext,
  `ISSN` longtext,
  `Notes` longtext,
  `Abstract` longtext,
  `Call_Number` longtext,
  `Keywords` longtext,
  `ar_codigo` char(10) NOT NULL,
  `ar_tipo` varchar(5) NOT NULL,
  `ar_doi` varchar(40) NOT NULL,
  `ar_titulo_1` varchar(255) NOT NULL,
  `ar_titulo_2` varchar(255) NOT NULL,
  `ar_resumo_1` text NOT NULL,
  `ar_resumo_2` text NOT NULL,
  `ar_section` char(5) NOT NULL,
  `ar_journal_id` char(7) NOT NULL,
  `ar_keyword_1` char(255) NOT NULL,
  `ar_keyword_2` char(255) NOT NULL,
  `ar_ano` char(10) NOT NULL,
  `ar_nr` char(10) NOT NULL,
  `ar_vol` char(10) NOT NULL,
  `ar_local` char(40) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `ID_2` (`ID`),
  FULLTEXT KEY `index_artigo_text` (`ar_asc`),
  FULLTEXT KEY `index_artigo__mini)text` (`ar_asc_mini`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `brapci_titulacao`
--

CREATE TABLE IF NOT EXISTS `brapci_titulacao` (
  `id_t` int(11) NOT NULL AUTO_INCREMENT,
  `t_descricao` char(30) NOT NULL,
  `t_ordem` int(11) NOT NULL,
  `t_ativo` int(11) NOT NULL,
  `t_codigo` char(3) NOT NULL,
  PRIMARY KEY (`id_t`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `brapci_titulacao`
--

INSERT INTO `brapci_titulacao` (`id_t`, `t_descricao`, `t_ordem`, `t_ativo`, `t_codigo`) VALUES
(1, 'sem titulação', 99, 1, '001'),
(2, 'graduando', 9, 1, '002'),
(3, 'Mestando profissionalizante', 7, 1, '003'),
(4, 'Mestrando acadêmico', 6, 1, '004'),
(5, 'Mestrado', 6, 1, '005'),
(6, 'Doutorando', 5, 1, '006'),
(7, 'Dr.', 4, 1, '007'),
(8, 'Dr.a.', 4, 1, '008'),
(9, 'Pós-Doutor', 2, 1, '009'),
(10, 'Pós-Doutora', 2, 1, '010'),
(11, 'Especialista', 8, 1, '011'),
(12, 'Prof. Dr.', 4, 1, '012'),
(13, 'Prof.a. Dr.a.', 4, 1, '013');

-- --------------------------------------------------------

--
-- Table structure for table `centraldosusuario`
--

CREATE TABLE IF NOT EXISTS `centraldosusuario` (
  `id_cdu` int(11) NOT NULL AUTO_INCREMENT,
  `cdu_nome` varchar(120) NOT NULL,
  `cdu_titulacao` varchar(20) NOT NULL,
  `cdu_instituicao` varchar(7) NOT NULL,
  `cdu_instituicao_nome` varchar(150) NOT NULL,
  `cdu_nasc_ano` varchar(4) NOT NULL,
  `pais_idioma` varchar(5) NOT NULL DEFAULT 'pt_BR',
  `cdu_email` varchar(100) NOT NULL,
  `cdu_email_alt` varchar(100) NOT NULL,
  `cdu_senha` varchar(20) NOT NULL,
  `cdu_ativo` int(11) NOT NULL DEFAULT '1',
  `cdu_codigo` char(7) NOT NULL,
  `cdu_tipo` char(1) NOT NULL DEFAULT 'V',
  `cdu_update` int(11) NOT NULL,
  PRIMARY KEY (`id_cdu`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=235 ;

--
-- Dumping data for table `centraldosusuario`
--

INSERT INTO `centraldosusuario` (`id_cdu`, `cdu_nome`, `cdu_titulacao`, `cdu_instituicao`, `cdu_instituicao_nome`, `cdu_nasc_ano`, `pais_idioma`, `cdu_email`, `cdu_email_alt`, `cdu_senha`, `cdu_ativo`, `cdu_codigo`, `cdu_tipo`, `cdu_update`) VALUES
(13, 'Rene Faustino Gabriel Junior', 'Mestrando acadêmico', '', 'rene', '1969', 'pt_BR', 'rene@sisdoc.com.br', '', '448545', 1, '0000013', 'V', 0),
(2, 'Rene Faustino Gabriel Junior', 'Mestrando', '', 'UFPR', '1969', 'pt_BR', 'renefgj@gmail.com', '', '448545ct', 1, '0000002', 'V', 0),
(3, 'Luciana da Silva Meira', 'sem titulação', '', 'USP', '1981', 'pt_BR', 'luci_meira@yahoo.com.br', 'lucimeira@gmail.com', 'tcc2010', 1, '0000003', 'V', 0),
(4, 'Brunno Sena da Silva Mendes', 'graduando', '', 'Universidade Federal Fluminense', '1990', 'pt_BR', 'sena.brunno@gmail.com', 'brunninho.sena@gmail.com', '260779', 1, '0000004', 'V', 0),
(5, 'tabata nunes tavares', 'graduando', '', 'Fundação Universidade Federal de Rondônia', '1989', 'pt_BR', 'tabata.tnt@gmail.com', 'tnt_meumsn@hotmail.com', 'tabata6368', 1, '0000005', 'V', 0),
(6, 'gabriela previdello orth', 'graduando', '', 'file', '1974', 'pt_BR', 'gabymuseum@gmail.com', 'filearquivo00@gmail.com', 'cienciadainfo', 1, '0000006', 'V', 0),
(7, 'Jeane Carolino Santos', 'graduando', '', 'PUCCAMPINAS', '1969', 'pt_BR', 'karol_santos_br@yahoo.com.br', 'jennsuper@gmail.com', '696969', 0, '0000007', 'V', 0),
(8, 'Melissa Pedroso Fusatto', 'graduando', '', 'Universidade de São Paulo', '1992', 'pt_BR', 'mellfuzatto@rocketmail.com', 'melissa@ffclrp.usp.br', 'melissa', 1, '0000008', 'V', 0),
(9, 'Eduardo da Silva Alentejo', 'Mestre', '', 'UNIRIO', '1966', 'pt_BR', 'alenteju@gmail.com', 'alentejo@oi.com.br', 'lobobobo', 1, '0000009', 'V', 0),
(10, 'Ana Caroline Soares Marcelo', 'graduando', '', 'UNIRIO', '1987', 'pt_BR', 'anacsm@gmail.com', 'scaroline@siqueiracastro.com.br', 'lorac2331', 1, '0000010', 'V', 0),
(11, 'Fabiene Furtado', 'Especialista', '', 'UFMG', '1979', 'pt_BR', 'fabiene.furtado@gmail.com', 'fabiene@ufmg.br', '290903', 1, '0000011', 'V', 0),
(12, 'Ana Paula Lima dos Santos', 'Mestre', '', 'UFF', '1978', 'pt_BR', 'annasorriso@ig.com.br', '', 'borboleta', 1, '0000012', 'V', 0),
(14, 'Rene Faustino Gabriel Junior', 'sem titulação', '', 'UFPR', '1969', 'pt_BR', 'rene@fonzaghi.com.br', '', '448545', 1, '0000014', 'V', 0),
(15, 'Camila Janiski Guesser', 'graduando', '', 'Universidade Federal do Paraná', '1992', 'pt_BR', 'cjguesser@gmail.com', 'camila__jg@hotmail.com', 'hard357', 1, '0000015', 'V', 0),
(16, 'Mariana Ultechak', 'graduando', '', 'UFPR', '1992', 'pt_BR', 'mari_texak@hotmail.com', 'texak@pop.com.br', 'mariana1103', 1, '0000016', 'V', 0),
(17, 'JESSICA CAROLINE DIAS DO NASCIMENTO', 'graduando', '', 'UNIVERSIDADE FEDERAL DO PARANÁ', '1986', 'pt_BR', 'jessicaroline_7@yahoo.com.br', 'jessicaroldias@gmail.com', 'nasepo15', 1, '0000017', 'V', 0),
(18, 'Michele Hasselmann', 'graduando', '', 'UFPR', '1982', 'pt_BR', 'mimisdimell@gmail.com', '', '13061982', 1, '0000018', 'V', 0),
(19, 'Juliana Rodrigues', 'graduando', '', 'Universidade Federal do Paraná', '1993', 'pt_BR', 'jujuli-14@hotmail.com', 'juliana.rodrigues93@bol.com.br', 'juhs2ge', 1, '0000019', 'V', 0),
(20, 'Washington Richel', 'graduando', '', 'UFPR', '1989', 'pt_BR', 'rsaito27@gmail.com', 'richel_saito4@hotmail.com', '1211', 1, '0000020', 'V', 0),
(21, '', '', '', '', '', 'pt_BR', 'paula_hara@hotm', '', '', 1, '0000021', 'T', 0),
(22, 'Izabela Luana Prates', 'graduando', '', 'Universidade Federal do Paraná', '1990', 'pt_BR', 'isabela_luana@hotmail.com', '', 'pipoca', 1, '0000022', 'V', 0),
(23, 'Paula', 'graduando', '', 'UFPR', '1982', 'pt_BR', 'paula_hara@hotmail', 'paula_hara@yahoo.com.br', '160123', 1, '0000023', 'V', 0),
(24, '', '', '', '', '', 'pt_BR', 'carolina.rossato@ufpr.br', '', '', 1, '0000024', 'T', 0),
(25, 'Ana Nilce', 'graduando', '', 'UFPR', '1988', 'pt_BR', 'aguiaflower@hotmail.com', 'anangaldino@gmail.com', 'pettylet', 1, '0000025', 'V', 0),
(26, '', '', '', '', '', 'pt_BR', 'faria.helisane@ufpr.br', '', '', 1, '0000026', 'T', 0),
(27, 'Helisane Faria', 'graduando', '', 'Universidade Federal do Paraná', '1993', 'pt_BR', 'helisanefaria@hotmail.com', 'faria.helisane@ufpr.br', '182620', 1, '0000027', 'V', 0),
(28, 'Paula Hara', 'graduando', '', 'UFPR', '1982', 'pt_BR', 'paula_hara@hotmail.com', 'paula_hara@yahoo.com.br', '160123', 1, '0000028', 'V', 0),
(29, 'Carolina Castro Rossato', 'sem titulação', '', 'Universidade Federal do Paraná', '1992', 'pt_BR', 'carol_rossato@yahoo.com.br', 'carolina.rossato@ufpr.br', '190895', 1, '0000029', 'V', 0),
(30, 'Maria Augusta Melani', 'graduando', '', 'UFPR', '1987', 'pt_BR', 'mariaaugustamelani@hotmail.com', '', '26070', 1, '0000030', 'V', 0),
(31, 'Gustavo Luiz Vitola', 'graduando', '', 'UFPR', '1991', 'pt_BR', 'gustavovitola@hotmail.com', 'gustavovitola@gmail.com', '123456', 1, '0000031', 'V', 0),
(32, 'Rodrigo Goedicke', 'graduando', '', 'UFPR', '1991', 'pt_BR', 'dunkon3@hotmail.com', 'cgoedicke@terra.com.br', 'opv2020', 1, '0000032', 'V', 0),
(33, 'Ana Paula Kachorowski', 'graduando', '', 'Universiodade Federal do Paraná', '1991', 'pt_BR', 'anapk.91@hotmail.com', 'anapk.91@hotmail.com', 'grr20100345', 1, '0000033', 'V', 0),
(34, 'Ana Nilce G. Aguiar', 'graduando', '', 'UFPR', '1988', 'pt_BR', 'aguiarflower@hotmail.com', 'anangaldino@gmail.com', 'pettylet', 1, '0000034', 'V', 0),
(35, '', '', '', '', '', 'pt_BR', 'ale.igarasgi@hotmail.com', '', '', 1, '0000035', 'T', 0),
(36, 'Stephane Macedo', 'graduando', '', 'Universidade Federal do Paraná', '1989', 'pt_BR', 'ste.fpm@hotmail.com', '', '891022', 1, '0000036', 'V', 0),
(37, 'Alessandra Meirelles Igarashi', 'graduando', '', 'UFPR', '1984', 'pt_BR', 'ale.igarashi@hotmail.com', 'igarashi.ale@hotmail.com', '090453', 1, '0000037', 'V', 0),
(38, 'elisangela gomes', 'graduando', '', 'UFRGS', '1988', 'pt_BR', 'zanza18@gmail.com', '', 'zan88fak', 1, '0000038', 'V', 0),
(39, 'Marilia Batista Hirt', 'graduando', '', 'UFRGS', '1968', 'pt_BR', 'marilia.hirt@terra.com.br', '', 'abelha', 1, '0000039', 'V', 0),
(40, 'Valdirene Rodrigues de Carvalho', 'Mestrando acadêmico', '', 'FACULDADE DE EDUCAÇÃO -  UNICAMP', '1971', 'pt_BR', 'valdirene66@yahoo.com.br', 'valdirene.66@hotmail.com', 'SENHABRAPCI', 1, '0000040', 'V', 0),
(41, 'Edwin Giebelen', 'Mestrando acadêmico', '', 'Universidade Federal da Paraíba UFPB', '1967', 'pt_BR', 'edwing@globo.com', '', 'edel24', 1, '0000041', 'V', 0),
(42, 'Ana Paula Cocco', 'Mestrando acadêmico', '', 'UFSC', '1983', 'pt_BR', 'anacocco@gmail.com', '', 'maezinha', 1, '0000042', 'V', 0),
(43, 'Luciana Bergamo Marques', 'graduando', '', 'Universidade Federal de Santa Catarina', '1981', 'pt_BR', 'bergamota.marques@gmail.com', '', '453127', 1, '0000043', 'V', 0),
(44, '', '', '', '', '', 'pt_BR', 'sales_de_souza@hotmail.com', '', '', 1, '0000044', 'T', 0),
(45, '', '', '', '', '', 'pt_BR', 'enaile_f@hotmail.com', '', '', 1, '0000045', 'T', 0),
(46, 'Rafael Costa', 'graduando', '', 'Universidade de Brasília', '1991', 'pt_BR', 'rcosta_0@hotmail.com', '', '260491', 1, '0000046', 'V', 0),
(47, '', '', '', '', '', 'pt_BR', 'maria.portela102@msn.com', '', '', 1, '0000047', 'T', 0),
(48, 'Olivia Zinsly', 'graduando', '', 'UFSCar', '1982', 'pt_BR', 'liv_zi@msn.com', '', 'Olivia28', 1, '0000048', 'V', 0),
(49, 'Eliana Gardini', 'graduando', '', 'UFSCar', '1984', 'pt_BR', 'gardini2005@yahoo.com.br', '', 'Danilo', 1, '0000049', 'V', 0),
(50, '', '', '', '', '', 'pt_BR', 'superju88@hotmail.com', '', '', 1, '0000050', 'T', 0),
(51, 'Eliana Gardini', 'graduando', '', 'UFSCar', '1984', 'pt_BR', 'gardini2005@hotmail.com', '', 'Danilo', 1, '0000051', 'V', 0),
(52, 'jo', 'sem titulação', '', 'federal', '1966', 'pt_BR', 'amaraljo77@hotmail.com', 'amaraljo77@hotmail.com', '789632', 1, '0000052', 'V', 0),
(53, 'ELIAS ROBERTO ALVES', 'graduando', '', 'UNIVERSIDADE FEDERAL DO PARANÁ', '1989', 'pt_BR', 'eliasrober@hotmail.com', 'eliasrober@gmail.com', '685934da', 1, '0000053', 'V', 0),
(54, 'Josanne Assiz', 'graduando', '', 'Universidade Federal do Pará', '1989', 'pt_BR', 'joassiz@yahoo.com.br', '', '110205', 1, '0000054', 'V', 0),
(55, 'Rodrigo Oliveira de Paiva', 'graduando', '', 'UFPA', '1991', 'pt_BR', 'rodrigo.paiva@icsa.ufpa.br', 'r_o_paiva122@hotmail.com', '631991', 1, '0000055', 'V', 0),
(56, 'Kaoane Stival Paula', 'graduando', '', 'Universidade Federal do Paraná', '1990', 'pt_BR', 'kaoane_stival@hotmail.com', '', 'doguhe', 1, '0000056', 'V', 0),
(57, '', '', '', '', '', 'pt_BR', 'dean@thebenjamingroup.com', '', '', 1, '0000057', 'T', 0),
(58, 'Viviana', 'graduando', '', 'UFSC', '1988', 'pt_BR', 'viviana_baldissera@hotmail.com', '', 'vivi0706', 1, '0000058', 'V', 0),
(59, 'Mirian ', 'graduando', '', 'Universidade Federal do Ceará', '1986', 'pt_BR', 'mirian_ufc@hotmail.com', 'mirian_biblioteconomia@yahoo.com.br', '88261565', 1, '0000059', 'V', 0),
(60, 'Vanessa Dias', 'graduando', '', 'Universidade Federal do Pará', '1990', 'pt_BR', 'vanessa.dias@yahoo.com.br', 'vanessa.2009dias@hotmail.com.br', '2712', 1, '0000060', 'V', 0),
(61, 'Maucirene da Silva Ferreira', 'graduando', '', 'UFPA', '1985', 'pt_BR', 'maucyavlis@yahoo.com.br', '', '1821', 1, '0000061', 'V', 0),
(62, '', '', '', '', '', 'pt_BR', 'maucygatynha@hotmail.com', '', '', 1, '0000062', 'T', 0),
(63, 'Aline Curiaki', 'graduando', '', 'Universidade Estadual de Londrina', '1988', 'pt_BR', 'aline.curiaki@gmail.com', 'Aline_Curiaki@hotmail.com', '100196441', 1, '0000063', 'V', 0),
(64, 'Maria Gorete Monteguti Savi', 'Mestrado', '', 'UFSC', '1962', 'pt_BR', 'gorete@bu.ufsc.br', '', '226622', 1, '0000064', 'V', 0),
(65, 'João Galvão', 'sem titulação', '', 'UFSC', '1984', 'pt_BR', 'jrmaderito@hotmail.com', 'jaovitorgalvao@ig.com.br', 'jv4891', 1, '0000065', 'V', 0),
(66, 'Jakeline Martins de Mendonça', 'graduando', '', 'Universidade de Brasília', '1990', 'pt_BR', 'mendonca.jake@gmail.com', 'jakeline-mendonca@hotmail.com', '12122008', 1, '0000066', 'V', 0),
(67, 'Cláudio Moreira Santana', 'Mestrado', '', 'Universidade de São Paulo - FEA/USP', '1971', 'pt_BR', 'cldsantana@unb.br', 'cldsantana.professor@gmail.com', 'd@N1d@N1', 1, '0000067', 'V', 0),
(68, 'NATACHA OLIVEIRA JANES', 'graduando', '', 'UNIVERSIDADE FEDERAL DO AMAZONAS', '1983', 'pt_BR', 'natacha.janes@hotmail.com', 'natacha.janes@hotmail.com', '12345', 1, '0000068', 'V', 0),
(69, 'Helen Ribeiro Nunes', 'sem titulação', '', 'UFRGS', '1993', 'pt_BR', 'helen.r.nunes@hotmail.com', '', 'hrn1103', 1, '0000069', 'V', 0),
(70, '', '', '', '', '', 'pt_BR', 'saeed_as3000@yahoo.com', '', '', 1, '0000070', 'T', 0),
(71, 'Leilah Santiago Bufrem', 'sem titulação', '', 'UFPR', '1910', 'pt_BR', 'leilah@ufpr.br', '', '1aliel', 1, '0000071', 'V', 0),
(72, 'Elaine de Oliveira Lucas', 'Doutorando', '', 'USP', '1975', 'pt_BR', 'lanilucas@gmail.com', 'lani@udesc.br', 'laura', 1, '0000072', 'V', 0),
(73, 'Marilda Lopes Ginez de Lara', 'Dr.a.', '', 'Escola de Comunicações e Artes - USP', '1949', 'pt_BR', 'larama@usp.br', 'marilda.ginezlara@gmail.com', 'marabrapci', 1, '0000073', 'V', 0),
(74, 'Maria cecilia Dias de Miranda', 'Especialista', '', 'Fiocruz', '1967', 'pt_BR', 'mariaceciliamiranda64@gmail.com', 'ceciliamiranda64@hotmail.com', 'miamia64', 1, '0000074', 'V', 0),
(75, 'Hugo da Silva Carlos', 'Especialista', '', 'hugobci@gmail.com', '1983', 'pt_BR', 'hugobci@gmail.com', 'hugo.carlos@ufabc.edu.br', 'qwerty', 1, '0000075', 'V', 0),
(76, 'Paula Regina Dal Evedove', 'Doutorando', '', 'UNESP', '1985', 'pt_BR', 'sud_dove@yahoo.com.br', '', 'doutora2015', 1, '0000076', 'V', 0),
(77, 'antonio', 'graduando', '', 'FORTIUM', '1978', 'pt_BR', 'gnu.linuxaholic@gmail.com', 'viciado.gnu.linux@gmail.com', 'gnulinux', 1, '0000077', 'V', 0),
(78, '', '', '', '', '', 'pt_BR', 'aclaudia.ms@gmail.com', '', '', 1, '0000078', 'T', 0),
(79, 'maria efigenia soares', 'graduando', '', 'FESPSP', '1964', 'pt_BR', 'maefigs@yahoo.com.br', '', '930272', 1, '0000079', 'V', 0),
(80, 'Célia Scucato Minioli', 'Mestrando acadêmico', '', 'UFPR', '1950', 'pt_BR', 'cminioli_90@msn.com', 'cminioli@gmail.com', 'Pitchu', 1, '0000080', 'V', 0),
(81, 'Elaine de Oliveira Lucas', 'Doutorando', '', 'USP', '1975', 'pt_BR', 'lani@usp.br', 'lanilucas@gmail.com', 'e4l15h10', 1, '0000081', 'V', 0),
(82, 'karolzynha rocha', 'graduando', '', 'UNIVERSIDADE FEDERAL DO CEARÁ', '1987', 'pt_BR', 'karolzynha.rocha7@gmail.com', 'karolzynha_jeny@yahoo.com.br', '101060', 1, '0000082', 'V', 0),
(83, 'Beatriz Cunha', 'graduando', '', 'UFF', '1986', 'pt_BR', 'beatrizcunha.uff@gmail.com', 'beatrizcunha@id.uff.br', 'sk8ernina', 1, '0000083', 'V', 0),
(84, 'Aline Elis Arboit', 'Mestrado', '', 'Universidade Federal do Paraná', '1979', 'pt_BR', 'aarboit@yahoo.com.br', '', 'aarboit', 1, '0000084', 'V', 0),
(85, 'Juliana', 'Mestrando acadêmico', '', 'Universidade Federal do Paraná', '1987', 'pt_BR', 'julilazzarotto@gmail.com', 'ju.lazzarotto@yahoo.com.br', '202020', 1, '0000085', 'V', 0),
(86, 'CLEOCI SCHNEIDER', 'graduando', '', 'UFSC', '1977', 'pt_BR', 'cleogbe@gmail.com', 'cleodorafael@gmail.com', 'UFSC123', 1, '0000086', 'V', 0),
(87, 'Elaine Diamantino Oliveira', 'Especialista', '', 'UFMG', '1985', 'pt_BR', 'elained@eci.ufmg.br', 'nanadiamantino@yahoo.com.br', '270385', 1, '0000087', 'V', 0),
(88, 'Joseanne Marques Ferreira', 'graduando', '', 'Universidade Federal de Mato Grosso', '1987', 'pt_BR', 'josibibli@hotmail.com', 'josibiblioteconomia@gmail.com', 'anta100', 1, '0000088', 'V', 0),
(89, 'Gislaine Campos dos Santos ', 'graduando', '', 'Universidade Federal de Mato Grosso', '1990', 'pt_BR', 'gislaine_campos@hotmail.com', 'gislainebibliotecaria@gmail.com', 'juliano', 1, '0000089', 'V', 0),
(90, 'Jônatas Souza de Abreu', 'Mestrando acadêmico', '', 'UFPE', '1989', 'pt_BR', 'jhonynatal7@gmail.com', 'jhonynatal7@yahoo.com.br', '180189', 1, '0000090', 'V', 0),
(91, 'Elizabeth Alves Rodrigues', 'graduando', '', 'UFF', '1962', 'pt_BR', 'elizabethalvesrodrigues@gmail.com', '', '24680202', 1, '0000091', 'V', 0),
(92, 'JÚLIO FERREIRA GOMES', 'graduando', '', 'UNIVERSIDADE FEDERAL DE MINAS GERAIS', '1989', 'pt_BR', 'jf-gomes1989@bol.com.br', 'julioferreira33@gmail.com', 'RIHANNA', 1, '0000092', 'V', 0),
(93, 'Fernando Elias', 'Mestrado', '', 'Universidade Católica Portuguesa', '1974', 'pt_BR', 'fercunhainfdoc@gmail.com', 'fernandoeliasnc@gmail.com', 'infdoc', 1, '0000093', 'V', 0),
(94, 'Ana Paula Morikava', 'graduando', '', 'Universidade Federal do Paraná', '1976', 'pt_BR', 'anapcwb@gmail.com', 'xofelttil@gmail.com', 'runanarun', 1, '0000094', 'V', 0),
(95, 'Débora de Meira Padilha', 'graduando', '', 'Universidade Federal de Santa Catarina', '1989', 'pt_BR', 'debora.m.padilha@gmail.com', 'ddd_rockeira@hotmail.com', 'brapcipesquisador', 1, '0000095', 'V', 0),
(96, 'Luiz Carlos Querino Filho', 'Mestrado', '', 'Centro Universitário Euripides de Marília', '1978', 'pt_BR', 'querino@me.com', '', '160504', 1, '0000096', 'V', 0),
(97, 'Juliana Peres', 'graduando', '', 'UFRGS', '1975', 'pt_BR', 'ju_pc@hotmail.com', '', '13052010', 1, '0000097', 'V', 0),
(98, 'Bayardo Morales', 'Especialista', '', 'UFRGS', '1945', 'pt_BR', 'bmorales@uol.com.br', '', 'mekong17', 1, '0000098', 'V', 0),
(99, '', '', '', '', '', 'pt_BR', 'rene.gabriel@ufpr.br', '', '', 1, '0000099', 'T', 20110426),
(100, 'Helena Nunes', 'Dr.a.', '', 'UFPR', '1956', 'pt_BR', 'helenanunes@ufpr.br', '', 'tham', 1, '0000100', 'V', 20110509),
(101, '', '', '', '', '', 'pt_BR', 'frank.alcantara@gmail.com', '', '', 1, '0000101', 'T', 20110512),
(102, 'Iraci Oliveira Rodrigues', 'graduando', '', 'USP', '1985', 'pt_BR', 'iraci.o.rodrigues@gmail.com', 'iracirodrigues@yahoo.com.br', 'historia', 1, '0000102', 'V', 20110512),
(103, 'creuzilda Vieira', 'graduando', '', 'ftc ead', '1954', 'pt_BR', 'creumontargil@hotmail.com', '', '333645', 1, '0000103', 'V', 20110517),
(104, 'DENIZETE LIMA DE MESQUITA', 'Especialista', '', 'UESPI', '1985', 'pt_BR', 'denilima@hotmail.com', 'dillye@gmail.com', 'DALVINA', 1, '0000104', 'V', 20110518),
(105, 'Hermelinda Martins', 'Especialista', '', 'UAB - Polo Vitória', '1978', 'pt_BR', 'hermelindamartins@yahoo.com.br', 'hermelindamartins@gmail.com', 'ninja008', 1, '0000105', 'V', 20110518),
(106, 'valéria serra da silva', 'graduando', '', 'universidade federal do pará', '1989', 'pt_BR', 'valeria.serra@icsa.ufpa.br', 'valeria-tn08@hotmail.com', '171819', 1, '0000106', 'V', 20110518),
(107, 'Jéssica moreira da silva', 'graduando', '', 'Universidade federal fluminense', '1990', 'pt_BR', 'jessicapuck@oi.com.br', 'jessicamoreirasilva@yahoo.com.br', '03051990', 1, '0000107', 'V', 20110520),
(108, 'Izabel Lima', 'graduando', '', 'UFC', '1990', 'pt_BR', 'ildsb@hotmail.com', '', 'PDHOS117', 1, '0000108', 'V', 20110522),
(109, 'Olivia Zinsly', 'sem titulação', '', 'UFSCar', '1982', 'pt_BR', 'liv_zi@msn.com.br', '', 'Olivia28', 1, '0000109', 'V', 20110523),
(110, '', '', '', '', '', 'pt_BR', 'cjb.saldanha@gmail.com', '', '', 1, '0000110', 'T', 20110526),
(111, 'Débora de Meira Padilha', 'graduando', '', 'UFSC', '1989', 'pt_BR', 'ddd_rockeira@hotmail.com', 'debora.m.padilha@gmail.com', 'laninhasc', 1, '0000111', 'V', 20110531),
(112, 'Lauro', 'graduando', '', 'UFPR', '1986', 'pt_BR', 'laurocesar@ig.com.br', '', '880329', 1, '0000112', 'V', 20110603),
(113, 'Carlos Gustavo Oliveira', 'graduando', '', 'UFPR', '1977', 'pt_BR', 'planetacarlos@uol.com.br', 'carlos-gustavo.oliveira@caixa.gov.br', 'konzax16', 1, '0000113', 'V', 20110604),
(114, 'Geysa Karla Alves Galvão', 'Mestrando acadêmico', '', 'UFPE', '1982', 'pt_BR', 'geysakarlagalvao@hotmail.com', 'geysakarlagalvao@gmail.com', '301183', 1, '0000114', 'V', 20110607),
(115, 'JOHNNY RODRIGUES BARBOSA', 'Mestrando acadêmico', '', 'universidade federal da paraíba', '1985', 'pt_BR', 'johnny_r2@hotmail.com', 'johnnyrodrigues@ufcg.edu.br', '87121566', 1, '0000115', 'V', 20110609),
(116, 'Felipe Salles Silva', 'graduando', '', 'ECA/USP', '1992', 'pt_BR', 'felipessalles@yahoo.com.br', 'felipe.salles.silva@usp.br', 'f25lipe', 1, '0000116', 'V', 20110612),
(117, '', '', '', '', '', 'pt_BR', 'suelenebarroso@hotmail.com', '', '', 1, '0000117', 'T', 20110612),
(118, 'Suelene Barroso Paulino', 'graduando', '', 'Universidade Federal do Ceará', '1988', 'pt_BR', 'ssbp88@gmail.com', 'suelenebarroso@hotmail.com', 'su080705', 1, '0000118', 'V', 20110612),
(119, 'Cristina de Paula Machado', 'Mestrando acadêmico', '', 'UFPR', '1982', 'pt_BR', 'cristinadepaula@gmail.com', 'cristinadepaula@gmail.com', '48163264', 1, '0000119', 'V', 20110622),
(120, 'Bianca Lorrani dos Reis', 'graduando', '', 'Universidade de Brasília', '1992', 'pt_BR', 'biancalorrani@gmail.com', 'biancalorrani@hotmail.com', '039310blr5', 1, '0000120', 'V', 20110626),
(121, 'Pedro Marinho Lopes', 'graduando', '', 'UFPR', '1991', 'pt_BR', 'pedroufpr@hotmail.com', 'harry_pedro@hotmail.com', '112233', 1, '0000121', 'V', 20110627),
(122, 'Bonifácio Muniz', 'graduando', '', 'UFPE', '1985', 'pt_BR', 'boni.muniz@gmail.com', 'bonimuniz@hotmail.com', '200485', 1, '0000122', 'V', 20110704),
(123, '', '', '', '', '', 'pt_BR', 'fc_renato@yahoo.com.br', '', '', 1, '0000123', 'T', 20110704),
(124, 'Renato Fernandes Corrêa', 'Dr.', '', 'UFPE', '1978', 'pt_BR', 'renatocorrea@gmail.com', 'fc_renato@yahoo.com.br', 'rfc0503', 1, '0000124', 'V', 20110704),
(125, 'MARIA DA GLÓRIA', 'Mestando profissiona', '', 'CESGRANRIO', '1962', 'pt_BR', 'glomar177@hotmail.com', 'glomar@coc.fiocruz.br', '909090', 1, '0000125', 'V', 20110801),
(126, 'Eliane Pereira Sales', 'graduando', '', 'USP', '1979', 'pt_BR', 'eliane.sales@uol.com.br', 'punkynha@hotmail.com', 'paixao365', 1, '0000126', 'V', 20110808),
(127, 'amanda vasconcelos de albuquerqeu', 'Mestrando acadêmico', '', 'UFF', '1979', 'pt_BR', 'amanda.albuquerque1979@gmail.com', 'albuquerque_amanda@hotmail.com', 'ehoje1Bom', 1, '0000127', 'V', 20110814),
(128, 'Lidyane Silva Lima', 'graduando', '', 'Unesp', '1911', 'pt_BR', 'lidyane_lima17@hotmail.com', '', 'metricos', 1, '0000128', 'V', 20110822),
(129, 'Elinalda silva de Melo', 'graduando', '', 'FESPSP', '1975', 'pt_BR', 'tianenedele@yahoo.com.br', '', 'emanuel', 1, '0000129', 'V', 20110824),
(130, 'Caio Cunha', 'graduando', '', 'Universidade Federal do Rio Grande do Norte', '1988', 'pt_BR', 'mr.caiocunha@gmail.com', 'mr.caiocunha@hotmail.com', 'dusete77', 1, '0000130', 'V', 20110825),
(131, 'Dalton Martins', 'Doutorando', '', 'Universidade de São Paulo', '1978', 'pt_BR', 'dmartins@gmail.com', '', 'fleming1902', 1, '0000131', 'V', 20110828),
(132, 'Ely Francina Tannuri de Oliveira', 'Dr.a.', '', 'Unesp-Marília', '1944', 'pt_BR', 'etannuri@marilia.unesp.br', '', '300644', 1, '0000132', 'V', 20110829),
(133, 'MARIA EMILIA GANZAROLLI MARTINS', 'Mestrado', '', 'UDESC', '1962', 'pt_BR', 'eganzarolli@gmail.com', '', '4850', 1, '0000133', 'V', 20110829),
(134, 'Lídia Martini Coelho Brandão Salek', 'graduando', '', 'UFF', '1955', 'pt_BR', 'lidiasalek@id.uff.br', 'lidiasalek@uol.com.br', 'mustapha55', 1, '0000134', 'V', 20110830),
(135, 'Kennya Torres Andrade', 'graduando', '', 'Universidade Federal Fluminense', '1987', 'pt_BR', 'kennyat@yahoo.com.br', 'kedu_ta@hotmaul.com', '2sapinhos', 1, '0000135', 'V', 20110901),
(136, 'CARLOS ALEXANDRE FERREIRA GOMES', 'Especialista', '', 'PUC PR', '1985', 'pt_BR', 'carlos.fergomes@gmail.com', '', '290185', 1, '0000136', 'V', 20110902),
(137, 'LEANDR LOPES PEDROSO', 'graduando', '', 'PUC CAMPINAS ', '1985', 'pt_BR', 'leandrolopespuc@uol.com.br', 'lelopeds@gmail.com', 'lean1480', 1, '0000137', 'V', 20110905),
(138, 'Bruno Henrique Alves', 'Mestrando acadêmico', '', 'Unesp-FFC-Marília', '1987', 'pt_BR', 'bruninkmkt@hotmail.com', '', 'hfb123456789', 1, '0000138', 'V', 20110920),
(139, 'Camilla M Caires', 'sem titulação', '', 'UNESP', '1993', 'pt_BR', 'camilla.caires@hotmail.com', '', 'marly1504', 1, '0000139', 'V', 20110921),
(140, '', '', '', '', '', 'pt_BR', 'nascimentoantonio02@hotmail.com', '', '', 1, '0000140', 'T', 20110925),
(141, 'Ana Paula Kachorowski', 'graduando', '', 'Universidade Federal do Paraná', '1991', 'pt_BR', 'anapk@ufpr.br', '', '061291', 1, '0000141', 'V', 20110926),
(142, 'Jéssyca Maria Santos da Silva', 'graduando', '', 'Unesp', '1991', 'pt_BR', 'maria_jessyca@yahoo.com.br', 'maria_jessyca@yahoo.com.br', '9j6e0s1y', 1, '0000142', 'V', 20110927),
(143, 'Lucimara Fernanda Martins Franco', 'Mestrando acadêmico', '', 'Universidade Estadual Paulista', '1984', 'pt_BR', 'lfmara@yahoo.com.br', 'luma_2084@hotmail.com', '131723', 1, '0000143', 'V', 20110930),
(144, 'irisneide de O. S Silva ', 'Doutorando', '', 'UNESP', '1965', 'pt_BR', 'irisneidesilva@yahoo.com.br', '', 'taels1', 1, '0000144', 'V', 20111003),
(145, 'Anna Karolina Silva da Rocha', 'graduando', '', 'UNIVERSIDADE FEDERAL DO CEARÁ', '1987', 'pt_BR', 'karolzynha_jeny@yahoo.com.br', 'karolzynha.rocha7@gmail.com', '101060', 1, '0000145', 'V', 20111003),
(146, 'Iraci Oliveira Rodrigues', 'graduando', '', 'Universidade de São Pualo', '1985', 'pt_BR', 'iracirodrigues@yahoo.com.br', '', 'historia', 1, '0000146', 'V', 20111015),
(147, 'Alexandra Linda Herbst Matos', 'Mestrado', '', 'Universidade de São Paulo', '1976', 'pt_BR', 'linda.alexandra@gmail.com', 'linda_herbst@yahoo.com', 'uberdas', 1, '0000147', 'V', 20111017),
(148, 'Joyce Raphaela Silva de Miranda Souza', 'graduando', '', 'USP', '1989', 'pt_BR', 'lunna_hollopaine@hotmail.com', '', 'floresastrais', 1, '0000148', 'V', 20111023),
(149, '', '', '', '', '', 'pt_BR', 'amanda.pacini.moura@gmail.com', '', '', 1, '0000149', 'T', 20111024),
(150, 'Gracielle Mendonça Rodrigues Gomes', 'Especialista', '', 'Universidade Federal de Minas Gerais', '1983', 'pt_BR', 'graciellemendonca@yahoo.com.br', '', '302897', 1, '0000150', 'V', 20111026),
(151, 'Fabíola Maria Siqueira Rocha', 'graduando', '', 'Universidade Federal de Minas Gerais', '1982', 'pt_BR', 'fabiola.siqueira.9@gmail.com', 'fabiola.siqueira.9@gmail.com', '529001', 1, '0000151', 'V', 20111031),
(152, 'Valéria', 'graduando', '', 'Universidade de São Paulo', '1979', 'pt_BR', 'ferkiilmer@hotmail.com', '', '7tx67491', 1, '0000152', 'V', 20111102),
(153, 'Thaísa Antunes Gonçalves', 'graduando', '', 'Universidade Federal do Rio Grande', '1988', 'pt_BR', 'thaisa.gonc@gmail.com', 'thaisa_ag@yahoo.com.br', 'ollbirtan', 1, '0000153', 'V', 20111102),
(154, 'Amanda', 'graduando', '', 'ECA-USP', '1990', 'pt_BR', 'amanda.pacini.moura@usp.br', 'amanda.pacini.moura@gmail.com', 'josue159', 1, '0000154', 'V', 20111105),
(155, 'Gracenilda Ribeiro da Silva Lacerda', 'graduando', '', 'Universidade Federal do Espírito santo', '1984', 'pt_BR', 'grslacerda@hotmail.com', '', 'filme174', 1, '0000155', 'V', 20111117),
(156, 'Vilma de Fátima Soares', 'Doutorando', '', 'FFLCH - USP', '1955', 'pt_BR', 'soaresvilma@usp.br', 'vilmausp@hotmail.com', 'pyra56', 1, '0000156', 'V', 20111118),
(157, 'lucirene catini lanzi', 'Mestrando acadêmico', '', 'UNESP', '1966', 'pt_BR', 'lu_lanzi@hotmail.com', 'lu_lanzi@hotmail.com', 'lu123456', 1, '0000157', 'V', 20111123),
(158, 'Laiza Rodrigues da Silva', 'graduando', '', 'Unesp', '1991', 'pt_BR', 'laizarodrigues@uol.com.br', 'laizarodrigues26@gmail.com', '415161la', 1, '0000158', 'V', 20111125),
(159, 'ROSICLER RIBEIRO MIRANDA MARQUES', 'graduando', '', 'UNESP MARÍLIA', '1974', 'pt_BR', 'rosi.clairmarques@gmail.com', 'rosi.clermarques@hotmail.com', 'ROSICLER', 1, '0000159', 'V', 20111126),
(160, 'iuli', 'graduando', '', 'Unesp Marília', '1992', 'pt_BR', 'iulirossi@hotmail.com', 'iulirossibiblio@gmail.com', '181731', 1, '0000160', 'V', 20111126),
(161, 'Talita', 'graduando', '', 'UNESP', '1991', 'pt_BR', 'ta.andrade.rodrigues@gmail.com', 'talita.a.rodrigues@hotmail.com', '1269798', 1, '0000161', 'V', 20111129),
(162, 'william', 'graduando', '', 'unesp', '1983', 'pt_BR', 'rihs.william7@gmail.com', 'william_rihs@hotmail.com', '123456', 1, '0000162', 'V', 20111129),
(163, 'dulceneia', 'sem titulação', '', 'unesp', '1989', 'pt_BR', 'dulceneiamana@hotmail.com', 'dulceneiamana@hotmail.com', 'dulceneia', 1, '0000163', 'V', 20111129),
(164, 'luzia aparecida furatdo ferreira', 'graduando', '', 'unesp campus de marilia', '1980', 'pt_BR', 'luziaa80@yahoo.com.br', 'luzia_furtado@hotmail.com', 'mae', 1, '0000164', 'V', 20111130),
(165, 'Isadora Victorino Evangelista', 'graduando', '', 'Universidade Estadual Paulista', '1992', 'pt_BR', 'isadora.biblio@marilia.unesp.br', 'isadorinha_6@hotmail.com', '483543846', 1, '0000165', 'V', 20111130),
(166, 'Victória', 'graduando', '', 'Unesp', '1992', 'pt_BR', 'vick.vicks@hotmail.com', 'salesvick@hotmail.com', 'lindarosa', 1, '0000166', 'V', 20111130),
(167, '', '', '', '', '', 'pt_BR', 'paulatms@hotmail.com', '', '', 1, '0000167', 'T', 20111130),
(168, 'Ramon Ordonhes Adriano Ribeiro', 'graduando', '', 'Unesp, Faculdade de Filosofia e Ciências', '1986', 'pt_BR', 'ramon.ordonhes@gmail.com', 'ramon_ordonhes@hotmail.com', 'tomomaior', 1, '0000168', 'V', 20111130),
(169, 'Aline Ferreira de Oliveira', 'graduando', '', 'Unesp- Marília', '1992', 'pt_BR', 'line.ferroli@gmail.com', 'line_100hp@hotmail.com', 'Linkin07', 1, '0000169', 'V', 20111130),
(170, 'Satie Tagara', 'sem titulação', '', 'Unesp', '1966', 'pt_BR', 'satie@marilia.unesp.br', '', 'letrasd', 1, '0000170', 'V', 20111130),
(171, 'Marlei Solange Vicentini Pereira', 'graduando', '', 'Unesp', '1973', 'pt_BR', 'msv_pereira_@hotmail.com', '', 'panico', 1, '0000171', 'V', 20111130),
(172, 'Daniele Ianni', 'graduando', '', 'UNESP', '1989', 'pt_BR', 'dani.ianni@hotmail.com', 'danidr2@gmail.com', 'osama37', 1, '0000172', 'V', 20111130),
(173, 'Juliete', 'graduando', '', 'Unesp - Marília', '1991', 'pt_BR', 'jully_susann@yahoo.com.br', '', '2206', 1, '0000173', 'V', 20111130),
(174, 'ana maria gomes de almeida', 'graduando', '', 'UNESP', '1968', 'pt_BR', 'amgare@hotmail.com', '', 'NOTAS10', 1, '0000174', 'V', 20111130),
(175, 'Ana Maria Gomes de Almeida', 'graduando', '', 'UNESP', '1968', 'pt_BR', 'ana.algom@yahoo.com', 'amgare@hotmail.com', 'NOTAS1010', 1, '0000175', 'V', 20111130),
(176, 'Danielly Nunes de Souza', 'graduando', '', 'Unesp', '1989', 'pt_BR', 'danielly_nunes@yahoo.com.br', 'lauradan26@hotmail.com', 'andressa', 1, '0000176', 'V', 20111201),
(177, 'Lais Campos Luz', 'graduando', '', 'Unesp', '1992', 'pt_BR', 'sial_tribal_fusion@hotmail.com', '', 'jesusnacruz', 1, '0000177', 'V', 20111205),
(178, 'ana almeida', 'graduando', '', 'unesp', '1968', 'pt_BR', 'hanagari@gmail.com', 'amgare@hotmail.com', '21052008', 1, '0000178', 'V', 20111206),
(179, 'Carolina Ferreira Soares', 'graduando', '', 'Faculdade de Filosofia e Ciencias Júlio de Mesquita "campus" marilia', '1990', 'pt_BR', 'kahfsoares_@hotmail.com', '', '97956935', 1, '0000179', 'V', 20111216),
(180, 'Tomàs Baiget', 'Especialista', '', 'El Profesional de la Información (EPI)', '1944', 'es', 'baiget@sarenet.es', 'baiget@gmail.com', 'pratblau', 1, '0000180', 'V', 20120101),
(181, 'Daniele Masterson Tavares Pereira Ferreira', 'Especialista', '', 'Universidade Federal do Rio de Janeiro', '1978', 'pt_BR', 'danimasterson@yahoo.com.br', 'danimasterson@hotmail.com', '131080', 1, '0000181', 'V', 20120106),
(182, 'Eliane Santos da Ressureicao', 'graduando', '', 'Universidade Federal do Estado do Rio de Janeiro', '1968', 'pt_BR', 'elianeressureicao@cnc.org.br', '', 'pesquisa12', 1, '0000182', 'V', 20120110),
(183, 'luana de oliveira faria', 'graduando', '', 'UNiversidade de brasilia', '1985', 'pt_BR', 'luanaof@gmail.com', '', '714092', 1, '0000183', 'V', 20120113),
(184, 'Carlos Alberto Souza do Nascimento Junior', 'graduando', '', 'Universidade Federal do Pará', '1992', 'pt_BR', 'carlos.junior@icsa.ufpa.br', 'carlosjr@hotmail.com.br', 'aminhasenha', 1, '0000184', 'V', 20120115),
(185, 'Fernando Cruz', 'Especialista', '', 'FESPSP', '1977', 'pt_BR', 'fcruz@anhembimorumbi.edu.br', '', '147NANDO', 1, '0000185', 'V', 20120116),
(186, 'Fernando Cruz', 'Especialista', '', 'FESPSP', '1977', 'pt_BR', 'fcruz@uninove.br', '', '147NANDO', 1, '0000186', 'V', 20120116),
(187, 'FABIO SAMPAIO ROSAS', 'Mestrando acadêmico', '', 'UNESP ', '1977', 'pt_BR', 'fabiosrosas@hotmail.com', '', 'FILIPEROSAS', 1, '0000187', 'V', 20120119),
(188, 'Mariana ', 'graduando', '', 'Universidade Federal do Rio de Janeiro', '1991', 'pt_BR', 'mariana_dias13@hotmail.com', 'diasmfc@hotmail.com', 'biblioteca', 1, '0000188', 'V', 20120127),
(189, 'jackelien Braga de Britto pereira', 'sem titulação', '', 'UFRN', '1964', 'pt_BR', 'jackelinecox@yahoo.com.br', '', '262315', 1, '0000189', 'V', 20120202),
(190, 'thabyta giraldelli marsulo', 'graduando', '', 'Unesp Marilia', '1992', 'pt_BR', 'thabytagm@hotmail.com', 'thabytagm@gmail.com', 'thabytagm', 1, '0000190', 'V', 20120203),
(191, 'Ligia Café', 'Prof.a. Dr.a.', '', 'UFSC', '1963', 'pt_BR', 'ligia@cin.ufsc.br', '', 'brapcilig', 1, '0000191', 'V', 20120216),
(192, 'Tais Bushatsky Mathias', 'graduando', '', 'Fundação Escola de Sociologia e Política', '1988', 'pt_BR', 'taisbmathias@gmail.com', 'taisbmathias@gmail.com', 'satrapi88', 1, '0000192', 'V', 20120221),
(193, 'Leticia Lizondo', 'graduando', '', 'Universidad Nacional de Mar del Plata', '1975', 'pt_BR', 'leticia@lizondo.com.ar', 'lalizondo@mdp.edu.ar', 'imparable2012', 1, '0000193', 'V', 20120303),
(194, 'julio cesar ', 'graduando', '', 'uff', '1978', 'pt_BR', 'r.ribero@hotmail.com', 'jucearte@yahoo.com', 'sabado22', 1, '0000194', 'V', 20120308),
(195, 'Hamilton Rodrigues Tabosa', 'Mestando profissiona', '', 'Universidade Federal do Ceará', '1979', 'pt_BR', 'hatabosa@yahoo.com.br', 'hrtabosa@gmail.com', '9184hata', 1, '0000195', 'V', 20120312),
(196, 'Greissi Gomes Oliveira', 'Mestrando acadêmico', '', 'Universidade Federal de São Carlos', '1979', 'pt_BR', 'greissigomes@gmail.com', 'greissig@ig.com.br', 'brapci1303', 1, '0000196', 'V', 20120314),
(197, 'Priscilla Raquel', 'graduando', '', 'Anhanguera Educacional de Anápolis', '1987', 'pt_BR', 'priscillaraquel@hotmail.com.br', 'priscillaraquel_top@hotmail.com', '\\\\@49a', 1, '0000197', 'V', 20120318),
(198, 'José Lopes', 'Mestrando acadêmico', '', 'Universidade Aberta (Portugal)', '1967', 'pt_BR', 'jlopes.uab@gmail.com', 'jose.pais.lopes@gmail.com', 'JAPL2858', 1, '0000198', 'V', 20120318),
(199, 'Gislene Joana pogetti', 'graduando', '', 'Fespsp', '1961', 'pt_BR', 'gislenepogetti@hotmail.com', '', 'gjp0722', 1, '0000199', 'V', 20120320),
(200, 'veronica lima da silva ', 'sem titulação', '', 'universidade federal do estado do rio de janeiro', '1985', 'pt_BR', 'veronica.limma@globomail.com', 'veronicaewalin@yahoo.com.br', '2609', 1, '0000200', 'V', 20120321),
(201, 'Maria Claudia Cabrini Gracio', 'Dr.a.', '', 'UNESP', '1965', 'pt_BR', 'cabrini@marilia.unesp.br', '', 'vidabe00', 1, '0000201', 'V', 20120322),
(202, 'Leidieice de Jesus Santos', 'graduando', '', 'Fundação Escola de Sociologia e Política de São Paulo', '1989', 'pt_BR', 'dieice.santos@gmail.com', '', 'Poliusp1.', 1, '0000202', 'V', 20120327),
(203, 'Josias Santana', 'graduando', '', 'Universidade Federal do ABC', '1956', 'pt_BR', 'jozias.santana@gmail.com', 'jozias.santana@hotmail.com', 'driver100674', 1, '0000203', 'V', 20120329),
(204, 'Tatiana da silva oliveira maciel', 'graduando', '', 'unirio', '1981', 'pt_BR', 'tatibacana.28@bol.com.br', 'tsilva1@ig.com.br', 'oliv2012', 1, '0000204', 'V', 20120329),
(205, '', '', '', '', '', 'pt_BR', 'cendoc@bcasas.org.pe', '', '', 1, '0000205', 'T', 20120331),
(206, 'Julio Francisco Santillan Aldana', 'graduando', '', 'Universidad Nacional Mayor de San Marcos', '1972', 'es', 'julio.santillan@gmail.com', '', '199801', 1, '0000206', 'V', 20120331),
(207, 'Dinalva Souza dos Santpos', 'graduando', '', 'Universidade Federal do Amanzonas', '1982', 'pt_BR', 'dinalva.ssantos@hotmail.com', '', 'didi1982', 1, '0000207', 'V', 20120401),
(208, 'Adriana Bogliolo Sirihal Duarte', 'Prof.a. Dr.a.', '', 'UFMG', '1970', 'pt_BR', 'bogliolo@eci.ufmg.br', '', 'luegigio', 1, '0000208', 'V', 20120402),
(209, 'Ana Eliza', 'graduando', '', 'FESPSP', '1989', 'pt_BR', 'anaelizagr@gmail.com', '', '945147', 1, '0000209', 'V', 20120402),
(210, 'Renata Nunes', 'Especialista', '', 'Câmara dos Deputados', '1912', 'pt_BR', 'renatanp@gmail.com', 'renata.nunes@camara.gov.br', 'bdci2012', 1, '0000210', 'V', 20120404),
(211, 'Carla Campos Pereira', 'Mestrando acadêmico', '', 'UFSC', '1983', 'pt_BR', 'carlapcpereira@gmail.com', '', 'mozinho74', 1, '0000211', 'V', 20120411),
(212, 'Cinthia Mª da Conceição Bezerra Pinheiro', 'Mestando profissiona', '', 'UFPB', '1978', 'pt_BR', 'cinthiamaria02@gmail.com', 'cinthiamaria02@hotmail.com', 'cmbs1978', 1, '0000212', 'V', 20120412),
(213, 'Danielly Oliveira Inomata', 'Mestrado', '', 'Universidade Federal de Santa Catarina', '1983', 'pt_BR', 'inomata.danielly@gmail.com', 'inomata2004@hotmail.com', 'inomata7', 1, '0000213', 'V', 20120413),
(214, 'Marcia Dietrich', 'graduando', '', 'UFSC', '1960', 'pt_BR', 'dietrich@bu.ufsc.br', '', '868686', 1, '0000214', 'V', 20120418),
(215, 'Gisele Amorim', 'graduando', '', 'Faculdade de Comunicação e BIblioteconomia - UFG', '1987', 'pt_BR', 'giamorim@biblio.grad.ufg.br', 'gisele.mat@gmail.com', '712309dm', 1, '0000215', 'V', 20120419),
(216, 'Patricía de Freitas Wagner', 'graduando', '', 'furg', '1977', 'pt_BR', 'pdefwagner@yahoo.com.br', 'pdefwagner@yahoo.com.br', 'livinha', 1, '0000216', 'V', 20120421),
(217, 'Raimundo Nonato Ribeiro dos Santos', 'Mestrando acadêmico', '', 'Universidade Federal da Paraíba', '1985', 'pt_BR', 'nonatobiblio@gmail.com', '', '071985', 1, '0000217', 'V', 20120422),
(218, 'Diana de Souza Santa Barbara', 'graduando', '', 'CPFL Paulista', '1984', 'pt_BR', 'dbarbara@cpfl.com.br', '', 'gataemily', 1, '0000218', 'V', 20120423),
(219, 'Luiz Augusto Pereira Fernandes', 'graduando', '', 'FESPSP', '1985', 'pt_BR', 'lugusto@gmail.com', '', 'modore1', 1, '0000219', 'V', 20120428),
(220, 'Ana Letícia Nascimento de Coimbra', 'sem titulação', '', 'Universidade Federal de Pernambuco', '1986', 'pt_BR', 'leticiadecoimbra@gmail.com', 'dudialnc@hotmail.com', '27271616', 1, '0000220', 'V', 20120502),
(221, 'Elizabeth Licke da Luz', 'Especialista', '', 'UFPR', '1978', 'pt_BR', 'beth.luz@ufpr.br', 'bethlicke@gmail.com', 'BIBLIOUFPR', 1, '0000221', 'V', 20120502),
(222, 'Nanci Elizabeth Oddone', 'Pós-Doutora', '', 'Universidade Federal do Estado do Rio de Janeiro', '1953', 'pt_BR', 'neoddone@gmail.com', 'neoddone@unirio.br', 'rupagrad', 1, '0000222', 'V', 20120504),
(223, 'Elizabeth Maria Freire de Jesus', 'Mestrando acadêmico', '', 'Universidade Federal Fluminense', '1962', 'pt_BR', 'beth@nce.ufrj.br', '', 'afrodite', 1, '0000223', 'V', 20120508),
(224, 'Rosemeire Felipe', 'sem titulação', '', 'FESP SP', '1968', 'pt_BR', 'rrcfelipe@gmail.com', 'rosemeireby@hotmail.com', 'RCFELIPE', 1, '0000224', 'V', 20120511),
(225, 'Cynthia Maria Kiyonaga Suenaga', 'graduando', '', 'Universidade Estadual de Londrina', '1971', 'pt_BR', 'cyndymak@gmail.com', '', 'leticia1', 1, '0000225', 'V', 20120515),
(226, 'Geisiely da Cruz Gomes', 'graduando', '', 'Universidade Federal de Goiás', '1992', 'pt_BR', 'geisielysemog@hotmail.com', 'geisielysemog@hotmail.com', '241277', 1, '0000226', 'V', 20120516),
(227, 'Lucileide Andrade de Lima do Nascimento', 'Doutorando', '', 'UFES', '1967', 'pt_BR', 'lucileidealn@gmail.com', 'lucileidelima@gmail.com', 'SOUDEJESUS', 1, '0000227', 'V', 20120517),
(228, 'GABRIELA PINHEIRO ANHAIA', 'graduando', '', 'UNIVERSIDADE FEDERAL DO RIO GRANDE DO SUL', '1989', 'pt_BR', 'gabrielaanhaia@hotmail.com', 'gabynha_cb@hotmail.com', 'cb110289', 1, '0000228', 'V', 20120520),
(229, 'PAULO JOSÉ DE OLIVEIRA DIAS', 'graduando', '', 'INSTITUTO DE CIENCIA DA INFORMAÇÃO DA UFBA', '1984', 'pt_BR', 'paulodias_ufba@yahoo.com.br', 'pjnegrou@gmail.com', '160305', 1, '0000229', 'V', 20120520),
(230, 'Iara Vidal Pereira de Souza', 'Mestrando acadêmico', '', 'Universidade Federal Fluminense', '1982', 'pt_BR', 'iaravidalps@gmail.com', 'iara.vidal@yahoo.com', '2c7r0l2', 1, '0000230', 'V', 20120521),
(231, 'Andrea Pacheco Silva Hespanha', 'Especialista', '', 'Sociedade Brasileira de Pesquisa Odontológica - SBPqO', '1967', 'pt_BR', 'andreahespanha@yahoo.com.br', 'andreahespanha@uol.com.br', '9a70ss', 1, '0000231', 'V', 20120527),
(232, '', '', '', '', '', 'pt_BR', 'alinereismelo@gmail.com', '', '', 1, '0000232', 'T', 20120529),
(233, 'Roger de Miranda Guedes', 'Mestrado', '', 'Universidade Federal de Minas Gerais', '1985', 'pt_BR', 'rogerotoni@gmail.com', '', 'rb275105', 1, '0000233', 'V', 20120604),
(234, 'Vera Aparecida Lui Guimarães', 'Mestrado', '', 'UFSCar - Universidade Federal de São Carlos', '1953', 'pt_BR', 'veralui@nit.ufscar.br', 'veralui@ufscar.br', 'vera53', 1, '0000234', 'V', 20120606);

-- --------------------------------------------------------

--
-- Table structure for table `consulta`
--

CREATE TABLE IF NOT EXISTS `consulta` (
  `id_c` int(11) NOT NULL AUTO_INCREMENT,
  `c_texto` text NOT NULL,
  `c_data` int(11) NOT NULL,
  `c_hora` char(5) NOT NULL,
  `c_ip` char(15) NOT NULL,
  `c_tipo` char(1) NOT NULL,
  `c_usuario` char(7) NOT NULL,
  `c_sessao` char(10) NOT NULL,
  `c_tempo` double NOT NULL,
  PRIMARY KEY (`id_c`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=986651 ;

--
-- Dumping data for table `consulta`
--

INSERT INTO `consulta` (`id_c`, `c_texto`, `c_data`, `c_hora`, `c_ip`, `c_tipo`, `c_usuario`, `c_sessao`, `c_tempo`) VALUES
(986650, 'REPOSITORIO INSTITUCIONAL OU REDE SOCIAL DE APRENDIZAGEM? ', 20120608, '00:29', '189.24.63.170', '0', '', '769d583c63', 0.2219);

-- --------------------------------------------------------

--
-- Table structure for table `download`
--

CREATE TABLE IF NOT EXISTS `download` (
  `id_down` int(11) NOT NULL AUTO_INCREMENT,
  `down_data` int(11) NOT NULL,
  `down_hora` varchar(5) NOT NULL,
  `down_arquivo` varchar(10) NOT NULL,
  `down_ip` varchar(15) NOT NULL,
  `down_status` char(1) NOT NULL,
  PRIMARY KEY (`id_down`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gc_producoes`
--

CREATE TABLE IF NOT EXISTS `gc_producoes` (
  `id_gcp` int(11) NOT NULL AUTO_INCREMENT,
  `gcp_link` text NOT NULL,
  `gcp_titulo` text NOT NULL,
  `gcp_ano` char(4) NOT NULL,
  `gcp_autor` text NOT NULL,
  `gcp_volnp` char(30) NOT NULL,
  `gcp_complemento` char(120) NOT NULL,
  `gcp_evento` text NOT NULL,
  `gcp_tipo` char(3) NOT NULL,
  PRIMARY KEY (`id_gcp`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `gc_producoes`
--

INSERT INTO `gc_producoes` (`id_gcp`, `gcp_link`, `gcp_titulo`, `gcp_ano`, `gcp_autor`, `gcp_volnp`, `gcp_complemento`, `gcp_evento`, `gcp_tipo`) VALUES
(1, 'http://www.periodicos.ufsc.br/index.php/eb/article/download/411/403', 'Revista Educação Temática Digital: aproximação entre educação e ciência da informação', '2007', 'BUFREM, Leilah Santiago ; BREDA, Sônia Maria ; SORRIBAS, Tidra Viana.', 'v. 23, p. 195-215', '', 'Encontros Bibli (UFSC)', '001'),
(2, 'dialnet.unirioja.es/servlet/fichero_articulo?codigo=2533313&orden=0', 'The presence of logic in the domain of knowledge organization: interdisciplinary aspects of college curricula. In: VIII CONGRESSO ISKO-ESPAÑA - UNIVERSIDAD DE LEÓN. (ORG.).', '2007', 'BUFREM, Leilah Santiago ; BREDA, Sônia Maria ; SORRIBAS, Tidra Viana', 'v. 00, p. 179-185', ' La interdisciplinariedad y la transdisciplinariedad en la organización del conocimiento científico. 00 ed. León: Blanca', '', '002'),
(5, '', 'Delimitação epistemológica do campo da Ciência da Informação:', '2007', 'FREITAS, Juliana Lazzarotto ; BUFREM, Leilah Santiago.', 'v. 00. p. 279-285.', '', ' uma análise da literatura periódica científica. In: 15º EVINCI - EVENTO DE INICIAÇÃO CIENTÍFICA, 2007, Curitiba - PR. Livro de Resumos - 15º EVINCI. Curitiba - PR : Universidade Federal do Paraná, ', '004'),
(6, '', 'Produção de informação sobre a epistemologia na literatura científica periódica em Ciência da Informação no Brasil', '2007', 'BUFREM, Leilah Santiago.', '2007. v. CD. p. 01-23.', '', 'CD-ROM. In: VIII ENCONTRO NACIONAL DE PESQUISA EM CIÊNCIA DA INFORMAÇÃO (ENANCIB), 2007, Salvador-BA. Anais do VIII Encontro Nacional de Pesquisa em Ciência da Informação. Salvador-BA, ', '003'),
(7, 'http://www.eci.ufmg.br/pcionline/index.php/pci/article/view/177/536', 'Política editorial universitária: por uma crítica à prática. ', '2009', 'BUFREM, Leilah Santiago.', 'v. 14, p. 23-36,', '', 'Perspectivas em Ciência da Informação', '001'),
(8, '', 'Produção científica em Ciência da Informação: análise temática em artigos de revistas brasileiras.', '2007', 'BUFREM, Leilah Santiago ; SILVA, Helena de Fátima Nunes ; FABIAN, Cecília Lícia Silveira Ramos e Medina ; SORRIBAS, Tidra Viana.', 'v. 12, p. 38-49', '', 'Perspectivas em Ciência da Informação', '001'),
(9, '', 'Informação para negócios: aspectos da literatura científica nacional em revistas da área de Ciência da Informação.', '2007', 'ARAÚJO, A. C. A. ; BUFREM, Leilah Santiago.', 'v. 00. p. 279-283.', 'In: 15º EVINCI - EVENTO DE INICIAÇÃO CIENTÍFICA, 2007, Curitiba - PR. Livro de Resumos - 15º EVINCI. Curitiba - PR : Uni', '', '004'),
(10, '', 'Produção científica em ciência e tecnologia:', '2007', 'SILVA, Viviane da ; BUFREM, Leilah Santiago.', 'v. 00. p. 279-285.', '', 'análise temática dos artigos da Revista Brasileira de Inovação. In: 15º EVINCI - EVENTO DE INICIAÇÃO CIENTÍFICA, 2007, Curitiba - PR. Livro de Resumos - 15º EVINCI. Curitiba - PR : Universidade Federal do Paraná,', '004'),
(11, '', 'The presence of logic in the domain of knowledge organization:', '2007', 'BUFREM, Leilah Santiago ; BREDA, Sônia Maria ; SORRIBAS, Tidra Viana.', 'p. 179-185.', '', 'interdisciplinary aspects of college curricula. In: VIII CONGRESSO ISKO-ESPAÑA, 2007, LEÓN. LA INTERDISCIPLINARIEDAD Y LA TRANSDISCIPLINARIEDAD EN LA ORGANIZACIÓN DEL CONOCIMIENTO CIENTÍFICO. León: Universidad de León,', '003'),
(12, '', 'Contribuição da base de dados referenciais BRAPCI à análise de autoria na produção científica periódica em Ciência da Informação no Brasil (1970-2006)', '2007', 'SORRIBAS, Tidra Viana ; BUFREM, Leilah Santiago.', 'p. 280-280.', '', 'In: 15º EVINCI - EVENTO DE INICIAÇÃO CIENTÍFICA, 2007, Curitiba. Livro de Resumos.... Curitiba : Ed. UFPR,', '004'),
(13, '', 'Opções metodológicas em pesquisa:', '2004', 'BUFREM, Leilah Santiago ; MATOS, Vanessa Borges de ; BREDA, Sônia Maria.', 'p. 133-133.', '', 'a contribuição da área da informação. In: EVINCI 2004 - EVENTO DE INICIAÇÃO CIENTÍFICA, 12., 2004, Curitiba. Anais. Curitiba : Editora Universidade Federal do Paraná,', '004'),
(14, '', 'Base BRES:', '2005', 'MIECOANSKI, Ellen Cristina ; BUFREM, Leilah Santiago ; SORRIBAS, Tidra Viana.', 'v. 0. p. 278-278.', '', 'uma prática de organização de dados referenciais. In: XII EVINCI EVENTO DE INICIAÇÃO CIENTÍFICA, 2005, Curitiba. Livro de Resumos. 13. Evento de Iniciação Científica. Curitiba: Editora da UFPR,', '004'),
(15, '', 'Base manbras:', '2005', 'MAGNERE, Mikie Alexandra Okumura ; BUFREM, Leilah Santiago ; SCHMIDT, Maria Auxiliadora ; GARCIA, Tânia Maria Figueiredo Braga.', 'p. 1-15.', '', 'manuais destinados à formação de professores no Brasil. In: XXI CONGRESSO BRASILEIRO DE BIBLIOTECONOMIA, DOCUMENTAÇÃO E CIÊNCIA DA INFORMAÇÃO - CBBD, 2005, Curitiba. XXI Congresso Brasileiro de Biblioteconomia, Documentação e Ciência da Informação - CBBD,', '004'),
(16, '', 'Perfil do Gestor da Informação a partir da literatura periódica brasileira..', '2005', 'ADAMI, Anderson ; BUFREM, Leilah Santiago ; SORRIBAS, Tidra Viana.', 'v. 0. p. 335-335.', '', 'In: XIII EVINCI EVENTO DE INICIAÇÃO CIENTÍFICA, 2005, Curitiba. Livro de Resumos. 13. Evento de Iniciação Científica. Curitiba : Editora da UFPR,', ''),
(17, '', 'Opções metodológicas em pesquisa:', '2005', 'OLIVEIRA, Sonia Mara Ferraz de ; BUFREM, Leilah Santiago.', 'v. 0. p. 338-338.', '', 'a contribuição da área da informação para a produção de saberes no ensino superior.. In: XIII EVINCI EVENTO DE INICIAÇÃO CIENTÍFICA, 2005, Curitiba. Livro de Resumos. 13. Evento de Iniciação Científica. Curitiba : Editora da UFPR,', '004'),
(18, '', 'Revista científica: saberes no campo de Ciência da Informação. In: DINAH AGUIAR POBLACION; GERALDINA PORTO WITTER; JOSÉ FERNANDO MODESTO DA SILVA. (ORG.).', '2006', 'BUFREM, Leilah Santiago.', 'v. 0, p. 193-214.', '', 'Comunicação e produção científica: contexto, indicadores, avaliação. São Paulo: Angellara,', '002'),
(19, '', 'BASE BRAPCI:', '2006', 'SORRIBAS, Tidra Viana ; BUFREM, Leilah Santiago ; SILVA, Helena de Fátima Nunes.', 'v. 1. p. 311-311.', '', 'base de dados referenciais de artigos de periódicos da área de Ciência da Informação. In: EVINCI 2006 - EVENTO DE INICIA�