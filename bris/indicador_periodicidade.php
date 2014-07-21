<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;

require("../_class/_class_journals.php");
$jnl = new journals;
echo $include;
$ano = $dd[0];
echo '<H1>Periodicidade das revistas indexadas na base</h1>';

echo '<h3>Tipo de Periodicidade</h3>';
echo $jnl->periodicidade_publicaoes();

echo '<BR><BR>';
echo '<HR>';

echo '<h3>Periodicidade e títulos das revistas</h3>';
echo $jnl->periodicidade_publicaoes_lista('1');

?>
