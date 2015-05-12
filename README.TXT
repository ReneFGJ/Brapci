ALTER TABLE `brapci_autor` ADD `autor_nacionalidade` VARCHAR(5) NOT NULL ;
ALTER TABLE `brapci_autor` ADD `autor_genero` VARCHAR(1) NOT NULL ;

CREATE TABLE IF NOT EXISTS `ajax_nacionalidade` (
`id_nasc` bigint(20) unsigned NOT NULL,
  `nasc_codigo` char(5) NOT NULL,
  `nasc_descricao` char(50) NOT NULL,
  `nasc_ativo` int(11) NOT NULL
) ;

ALTER TABLE `ajax_nacionalidade`
 ADD UNIQUE KEY `id_nasc` (`id_nasc`), ADD UNIQUE KEY `nasc_codigo` (`nasc_codigo`);
 
 INSERT INTO `brapci_base`.`ajax_nacionalidade` (`id_nasc`, `nasc_codigo`, `nasc_descricao`, `nasc_ativo`) VALUES (NULL, 'bra', 'Brasileiro', '1'), (NULL, 'esp', 'Espanhol', '1');

