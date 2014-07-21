<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;

if (($dd[1]=='0') or (strlen($dd[1]) == 0)) { exit; }
if ($dd[1]=='1') { $tipo_nome = 'BAIXO'; }
if ($dd[1]=='2') { $tipo_nome = 'MÉDIO'; }
if ($dd[1]=='3') { $tipo_nome = 'ALTO'; }

$ano = $dd[0];
$tipo = $dd[1];

echo '<H1>Indice de Concentração de Produção por Autor (iCPA)</h1>';
echo '<h3>Ano Base: '.$ano.' '.$tipo_nome.'</h3>';

echo $bris->grupos_icpa_mostra($ano,$tipo);

?>
