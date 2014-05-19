<?php
require('cab.php');
$ln = new message;

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	$cl = new message;
	$cp = $cl->cp();
	$tabela = $cl->tabela;
	
	
	$http_edit = 'message_ed.php';
	$http_redirect = '';
	$tit = msg("titulo");

	/** Comandos de Edição */
	echo '<div id="content">';
	echo '<CENTER><font class=lt5>'.msg('titulo').'</font></CENTER>';
	?><TABLE width="<?php echo $tab_max;?>" align="center" bgcolor="<?php echo $tab_color;?>"><TR><TD>
		<?php
	editar();
	?></TD></TR></TABLE><?php	
	
	/** Caso o registro seja validado */
	if ($saved > 0)
		{
			echo 'Salvo';
			$cl->updatex();
			redirecina('message.php');
		}
	echo '</div>';
require("foot.php");
?>

