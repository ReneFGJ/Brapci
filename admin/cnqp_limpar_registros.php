<?
require("cab.php");
require("../_class/_class_lattes.php");
$lt = new lattes;

require($include.'_class_form.php');
$form = new form;

$cp = array();
array_push($cp,array('$H8','','',False,True));
array_push($cp,array('$[2010-'.date("Y").']','','Excluir dados a partir de ',True,True))	;
array_push($cp,array('$O : &S:SIM','','Confirmar Exclusão',True,True));

$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
		echo 'GO!';
		$lt->delete_records_from($dd[1]);
		echo 'FIM';
	} else {
		echo $tela;
	}

?>
