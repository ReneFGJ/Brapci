<?php
require('_include/sisdoc_debug.php');
require("db.php");
require($include.'sisdoc_debug.php');
require("_class/_class_header_bp.php");
$hd = new header;

require($include.'_class_form.php');
$form = new form;


echo $hd->head();
echo $hd->cab_popup();

$cp = array();

array_push($cp,array('$A','','Tipo do erro',False,True));
array_push($cp,array('$R1:1&2:2&3:3','','Tipo do erro',False,False));

array_push($cp,array('$T60:4','','Comentário',False,True));
$tela = $form->editar($cp,'');

echo $tela;
?>
