<h2>Hidden Term</h2>
<?php
$form = new form;
$cp = array();
array_push($cp,array('$H8','','',False,False));
$sql = "select * from label
			inner join term on l_term = id_t 
			where l_type='PREF' ";		
array_push($cp,array('$Q l_concept_id:t_name:'.$sql,'t_lang',msg('term'),True,True));
$tela = $form->editar($cp,'');
echo $tela;
?>
