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
		case 'DELALL' :
			$keyw -> remove_termo_todos_nao_usados();
			break;
		default :
			echo 'ERRO ' . $acao;
			break;
	}
}

echo $keyw -> remove_keyword_not_used();
echo '<A HREF="'.page().'?dd1=0&dd10=DELALL">delete todos os termos não usados</A>';
?>
