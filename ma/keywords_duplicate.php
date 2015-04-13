<?php
require ("cab.php");

require ("../_class/_class_keyword.php");
echo '<h1>Descritores não utilizados</h1>';

$keyw = new keyword;

/* Remove */
if (strlen($dd[1]) > 0) {
	$nome = $dd[1];
	$lang = $dd[2];
	$total = round($dd[3]);
	$acao = $dd[10];
	switch ($acao) {
		case 'DEL' :
			$keyw -> remove_termo($nome, $lang, $total);
			break;
		case 'AGRUPAR' :
			$keyw -> agrupar_termo($nome, $lang, $total);
			break;			
		default :
			echo 'ERRO '.$acao;
			break;
	}

}

echo $keyw -> remove_duplicates();
?>
