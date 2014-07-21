<?
require("publications_0.php");
if (strlen($dd[0])==0) { redirecina('publications_row.php'); }

$cp = $pb->cp_04();
$tela = $form->editar($cp,$tabela);

if ($form->saved > 0)
	{
		$pb->updatex();
		redirecina('publications_ed.php?dd0='.$dd[0]);	
		exit;			
	} else {
		echo $tela;
	}
echo '</td></tr>';
echo '</table>';

require("../foot.php");
?>
