<?
require("cab.php");
require('../_class/_class_section.php');
$pb = new section;

require($include.'_class_form.php');
$form = new form;

$jnl = trim($dd[0]);
$cp = $pb->cp();

echo '<div class="nav">';
echo '<h1>'.msg('sections').'</h1>';
echo '
	<A class="link" HREF="sections.php?dd0='.$dd[0].'">'.msg('back').'</A>
';

$tela = $form->editar($cp,$pb->tabela);

if ($form->saved > 0)
	{
		redirecina('sections.php?dd0='.$dd[0]);	
		exit;			
	} else {
		echo $tela;
	}

require("../foot.php");
?>
