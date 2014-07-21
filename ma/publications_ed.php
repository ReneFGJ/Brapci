<?
require("publications_0.php");
if (strlen($dd[0])==0) { redirecina('publicacoes_1_ed.php'); }

echo '<TD>';
$jl->le($dd[0]);
echo $jl->journals_mostra();
echo '</td></tr>';
echo '</table>';

require("../foot.php");
?>
