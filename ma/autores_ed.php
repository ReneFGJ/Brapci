<? 
require("cab.php");
require($include.'sisdoc_debug.php'); 

require('../_class/_class_author.php');
$clx = new author;

$cp = $clx->cp();
$tabela = $clx->tabela;

require('../include/sisdoc_colunas.php');
require($include.'_class_form.php');
$form = new form;
$tela = $form->editar($cp,$tabela);

if ($form->saved > 0)
	{
		redirecina('autores_row.php');
	} else {
		echo $tela;
	}

?>