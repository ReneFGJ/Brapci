<?php
require("cab.php");
require("../_class/_class_bris.php");
$br = new bris;

require("_class/_class_autor.php");
$au = new autor;

require("../_class/_class_article.php");
$sh = new article;


$au->le($dd[0]);

echo $au->mostra();



$br->citacoes_por_ano();

$tela = $au->publicoes_dos_autor($au->codigo);
$au->citacoes_meus_artigos($au->codigo);

echo '<table>';
	echo '<TR><TD>';
	echo $au->resumo_producao($au->codigo);
	
	echo $au->grafico();
	echo '<TD align="right">';
	echo $au->grafico_minhas_citacoes();	
echo '<TR valign="top"><TD>';
	echo $au->autor_revistas_publicacoes_cinco_ano($au->codigo);
	echo $au->autor_revistas_publicacoes($au->codigo);
echo '<TD>';
	echo $br->indicador_autor($au->codigo);
echo '</table>';
echo '<BR><BR>';
echo '<h3>Publica��es do autor</h3>';
echo '<BR>';

echo '<font class="lt0">';
echo $tela;

?>