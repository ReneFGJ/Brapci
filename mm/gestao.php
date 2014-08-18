<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('pendências','brapci_rel_pendencia.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Gestão da Base de Dados';

require($include."sisdoc_menus.php");
$estilo_admin = 'style="width: 200; height: 30; background-color: #EEE8AA; font: 13 Verdana, Geneva, Arial, Helvetica, sans-serif;"';
$menu = array();
/////////////////////////////////////////////////// MANAGERS
array_push($menu,array('Concistência da Base','Artigos Duplicados (GQ.1)','brapci_artigos_duplicados.php')); 
array_push($menu,array('Concistência da Base','Artigos sem edição (GQ.2)','brapci_artigos_semedicao.php')); 
array_push($menu,array('Concistência da Base','Artigos sem resumo (GQ.3)','brapci_artigos_semresumo.php')); 
array_push($menu,array('Concistência da Base','Artigos com <I>pagecode</I> inválido no resumo (GQ.4)','brapci_artigos_resumo_pagecode.php')); 
array_push($menu,array('Concistência da Base','Artigos com autores repetidos (GQ.5)','brapci_artigos_autor_repetido.php')); 
array_push($menu,array('Concistência da Base','Artigos sem autores (GQ.6)','brapci_artigos_autor_sem.php')); 
array_push($menu,array('Concistência da Base','Artigos com títulos com caracteres de controle (GQ.7)','brapci_artigos_titulo_caracter.php')); 
array_push($menu,array('Concistência da Base','Palavras-chave sem uso','brapci_keyword_cancelar.php')); 

array_push($menu,array('Concistência da Base','Referências dos autores','brapci_autores_normas.php')); 
array_push($menu,array('Concistência da Base','PDF quebrado','manutencao_pdf_acesso.php')); 
array_push($menu,array('Concistência da Base','Resumo da coleta dos PDF','manutencao_pdf_journal.php')); 

array_push($menu,array('Estado da Indexação','Status dos trabalhos','rel_gestao_status.php')); 

?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?
	$tela = menus($menu,"3");
?>
</DIV>