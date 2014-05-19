<?
require("cab.php");
require('../_class/_class_publications.php');
$pb = new publications;

require($include.'_class_form.php');
$form = new form;

$jnl = trim($dd[0]);
$cp = $pb->cp();

echo '<div class="nav">';
echo '<h1>'.$pb->journal_name($jnl).'</h1>';
echo '
	<A class="link" HREF="publications_details.php?dd0='.$dd[0].'">'.msg('return').'</A>
';
$tela = $form->editar($cp,$pb->tabela);

if ($form->saved > 0)
	{
		$pb->updatex();
		redirecina('publications_details.php?dd0='.$dd[0]);	
		exit;			
	} else {
		echo $tela;
	}

require("../foot.php");
?>
