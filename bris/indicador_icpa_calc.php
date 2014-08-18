<?php
require("cab.php");
echo '<h3>iCPA</h3>';
echo '<font class="lt1">�ndice de concentra��o de produ��o do autor</font>';

require("../_class/_class_article.php");
$ar = new article;

require("../_class/_class_bris.php");
$bris = new bris;

require($include.'_class_form.php');
$form = new form;

$cp = array();
array_push($cp,array('$H8','','',False,True));
array_push($cp,array('$[1972-'.date("Y").']D','','Ano de c�lculo',True,True));

$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
		$ar->verifica_artigo_sem_edicao();
		$sem_ano = $bris->verificar_artigo_sem_ano();
		if ($sem_ano > 0)
			{
				$total = $ar->atualizar_ano_do_artigo(1);
				echo '<HR>';
				echo '<h2>C�lculando ano das publica��es</h2>';
				echo 'Sem identifica��o de ano:'.$sem_ano;
				echo '<BR>Reprocessado :'.$total;
				exit;
			}
		echo $bris->calcula_icpa($dd[1]);
	} else {
		echo $tela;		
	}



require("../foot.php");
?>
