<div class="container">
<?php
$link = '<img src="'.base_url('img/icone_url.png').'" title="Site da publicação" height="40">';
$link = '<A HREF="'.$jnl_url.'" target=_new_'.$jnl_codigo.'">'.$link.'</A>';

$linko = '<img src="'.base_url('img/icone_oai.png').'" title="Harvesting" height="40">';
$linko = '<A HREF="'.base_url('index.php/oai/Identify/'.$jnl_codigo).'" target=_new_'.$jnl_codigo.'">'.$linko.'</A>';


echo "
<div style=\"float: right\"> $linko $link </div>
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
?>
</div>