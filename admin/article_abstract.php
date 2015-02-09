<?php
require("db.php");
require($include.'_class_form.php');
$form = new form;

require("../_class/_class_keyword.php");
$kw = new keyword;

require("../_class/_class_article.php");
$ar = new article;
$ar->le($dd[0]);

$id = strzero($dd[0],10);

switch($dd[1])
	{
	case 'ABS0':
		$cp = $ar->cp_title();
		break;		
	case 'ABS1':
		$cp = $ar->cp_abs1();
		if (strlen($acao) == 0)
			{
				$sa = $kw->recupera_keyword($id,$ar->idioma_1);
				$ar->atualiza_keyword($id,$sa,1);
			}
		break;
	case 'ABS2':
		$cp = $ar->cp_abs2();
		if (strlen($acao) == 0)
			{
				$sa = $kw->recupera_keyword($id,$ar->idioma_2);
				$ar->atualiza_keyword($id,$sa,2);
			}
		break;
	}
$tabela = $ar->tabela;
$tela = $form->editar($cp,$tabela);



if ($form->saved > 0)
	{
		$key = $dd[3];
		$key = troca($key,'. ','; ');
		$key = troca($key,', ','; ');

		$keys = splitx(';',$key.';');
		$kw->save_keyword_article_v2($id,$keys,$dd[4]);
		echo '
		<script>
			
		</script>
		';
		
	} else {
		echo $tela;
	}
function msg($x) { return($x); }
?>
