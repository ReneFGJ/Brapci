<?php
$form = new form;
$cp = array();
array_push($cp,array('$H8','id_t','',False,False));
array_push($cp,array('$S50','t_name',msg('term_name'),True,True));
array_push($cp,array('$Q id_lang:lang_name:select * from language','t_lang',msg('language'),True,True));
$tela = $form->editar($cp,'term');
if ($form->saved > 0)
	{
		redirect(base_url('index.php/skos/terms'));
	}
echo $tela;
?>
