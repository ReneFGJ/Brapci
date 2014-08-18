<?php
require("cab.php");
echo '<h3>iCPA</h3>';
echo '<font class="lt1">Índice de concentração de produção do autor</font>';

require("../_class/_class_article.php");
$ar = new article;

require("../_class/_class_bris.php");
$bris = new bris;

require($include.'_class_form.php');
$form = new form;

$cp = array();
array_push($cp,array('$H8','','',False,True));
array_push($cp,array('$[1972-'.date("Y").']D','','Ano de cálculo',True,True));

$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
		$ar->verifica_artigo_sem_edicao();
		$sem_ano = $bris->verificar_artigo_sem_ano();
		if ($sem_ano > 0)
			{
				$total = $ar->atualizar_ano_do_artigo(1);
				echo '<HR>';
				echo '<h2>Cálculando ano das publicações</h2>';
				echo 'Sem identificação de ano:'.$sem_ano;
				echo '<BR>Reprocessado :'.$total;
				exit;
			}
		echo $bris->calcula_icpa($dd[1]);
	} else {
		echo $tela;		
	}



require("../foot.php");
?>
