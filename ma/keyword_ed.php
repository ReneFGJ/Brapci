<? 
require("cab.php");
require($include.'sisdoc_debug.php'); 

require('../_class/_class_keyword.php');
$clx = new keyword;

$cp = $clx->cp();
$tabela = $clx->tabela;
$sql = "select * from brapci_keyword where id_kw = ".$dd[0];
$rlt = db_query($sql);
$line = db_read($rlt);
print_r($line);

require('../include/sisdoc_colunas.php');
require($include.'_class_form.php');
$form = new form;
$tela = $form->editar($cp,$tabela);

if ($form->saved > 0)
	{
		redirecina('keywords_row.php');
	} else {
		echo $tela;
	}

?>