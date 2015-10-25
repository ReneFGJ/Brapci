<?php
$link = '<img src="'.base_url('img/icone_url.png').'" title="Site da publicação" height="40">';
$link = '<A HREF="'.$jnl_url.'" target=_new_'.$jnl_codigo.'">xx'.$link.'</A>';


echo "
<div style=\"float: right\">$link</div>
<h1>$jnl_nome, $cidade_nome</h1>
ISSN: $jnl_issn_impresso, eISSN: $jnl_issn_eletronico
";

/* Sobre os fasciculos */
echo "
<table class=\"bgblue\" width=\"100%\">
<TR align=\"center\">
<TD align=\"center\">$nr EDIÇÕES | $na TRABALHOS | desde $desde | $anos anos
</table>
";

echo '<div style="float: right">'.$logo.'</div>';

echo "





$edicoes

";
?>