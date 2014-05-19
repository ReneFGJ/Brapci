<?php
require('cab_cip.php');
require('../_class/_class_author.php');

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');
	
	$cl = new author;
	$cp = $cl->cp();
	$tabela = $cl->tabela;
	
	$http_edit = page();
	$http_redirect = '';
	$tit = 'Autoridades';

	/** Comandos de Edição */
	echo '<h1>'.$tit.'</h1>';
	?><TABLE width="99%" align="center" bgcolor="<?=$tab_color;?>"><TR><TD><?
	editar();
	?></TD></TR></TABLE><?	
	
	/** Caso o registro seja validado */
	if ($saved > 0)
		{
			echo 'Salvo';
			//$cl->updatex();
			redirecina('autores_autoridades.php');
		}
require("../foot.php");	
?>

