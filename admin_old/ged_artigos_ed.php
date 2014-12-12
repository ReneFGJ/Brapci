<?php
require('cab.php');
global $acao,$dd,$cp,$tabela;

require("../_class/_class_ged.php");

require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');

	$cl = new ged;
	echo '1';
	$cp = $cl->cp_type();
	echo '1';
	$tabela = 'artigo_ged_documento_tipo';
	
	$http_edit = page();
	$http_redirect = '';

	/** Comandos de Edição */
	echo '<CENTER><font class=lt5>'.msg('titulo').'</font></CENTER>';
	?><TABLE width="<?=$tab_max;?>" align="center" bgcolor="<?=$tab_color;?>"><TR><TD><?
	editar();
	?></TD></TR></TABLE><?	
	
	/** Caso o registro seja validado */
	if ($saved > 0)
		{
			echo 'Salvo';
			redirecina(troca(page(),'_ed.php','.php'));
		}
		
?>

