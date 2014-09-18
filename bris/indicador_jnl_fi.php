<?php
require ("cab.php");
require ("../_class/_class_bris.php");
$br = new bris;

echo '<h1>Publicação - Fator de Impacto - '.$dd[1].'</h1>';
require ($include . '_class_form.php');
$form = new form;
$cp = array();
array_push($cp, array('$H8', '', '', False, False));
array_push($cp, array('$[2012-2013]D', '', 'Ano base', True, True));
array_push($cp, array('$B8', '', 'visualizar >>', False, False));
$tela = $form -> editar($cp, '');

echo '
<script type="text/javascript" src="../_include/js/jquery-latest.js"></script>
<script type="text/javascript" src="../_include/js/jquery.tablesorter.js"></script>
';

if ($form -> saved > 0) {
	$ano = $dd[1];
	echo $br -> journal_fi($ano);
} else {
	echo $tela;
}
?>

<script>
$(function(){
	// Parser para configurar a data para o formato do Brasil
	$('.tabela00').tablesorter();
});
</script>
<style>
	.tabela00 th { cursor:n-resize; }
</style>
