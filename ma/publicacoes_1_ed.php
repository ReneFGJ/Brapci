<?
require("publications_0.php");
if (strlen($dd[0])==0) 
	{ echo '<h3><font color="red">** NOVO REGISTRO **</h3>'; }

$cp = $pb->cp();
$tela = $form->editar($cp,$tabela);

if ($form->saved > 0)
	{
		$pb->updatex();
		if ($dd[0] == '')
			{
				redirecina('publications_row.php');
			} else {
				redirecina('publications_ed.php?dd0='.$dd[0]);		
			}
			
		exit;			
	} else {
		echo $tela;
	}
echo '</td></tr>';
echo '</table>';

require("../foot.php");
?>
