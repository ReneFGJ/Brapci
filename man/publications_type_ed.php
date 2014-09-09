<?php
require("cab.php");
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require("../_class/_class_publications_type.php");
$clx = new publication_type;

echo '<h3>'.msg('type_publications').'</h3>';

$cp = $clx->cp();
$tabela = $clx->tabela;

editar();

if ($saved > 0)
	{
		redirecina("publications_type.php");
	}
	
require("../foot.php");
?>
