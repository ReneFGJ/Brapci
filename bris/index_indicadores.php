<?php
require ("cab.php");
require ("../_class/_class_bris.php");
$br = new bris;

if (strlen($ano) == 0) { $ano = (date("Y") - 1);
}

echo '<H1>Indicadores de Produ��o</h1>';
echo '<UL>';
echo '<LI><a href="indicador_producao_ano.php?dd0=' . $ano . '">Produ��o acumulada anual</A></LI>';
echo '<LI><a href="indicador_colaboracao.php?dd0=' . $ano . '">Indicador do Colabora��o (iIC, iDC e iCC)</A></LI>';
echo '<LI><a href="indicador_icpa.php?dd0=' . $ano . '">Indice de Concentra��o de Produ��o por Autor (iCPA)</A></LI>';
echo '<LI><a href="indicador_iap.php?dd0=' . $ano . '">Indice de Autores mais Produtivos (iAP)</A></LI>';

echo '<LI><a href="indicador_periodicidade.php">Periodicidade das publica��es</A></LI>';
echo '</UL>';
echo '<BR><BR>';
echo '<H1>Indicadores de Cita��o</h1>';
echo '<UL>';
echo '<LI><a href="indicador_tipologia.php?dd0=' . $ano . '">Tipologia das fontes</A></LI>';
echo '<LI><a href="indicador_imv.php">Meia vida</A></LI>';
echo '<LI><a href="indicador_fi_journal.php">Journal Ranking</A></LI>';
echo '<LI><a href="indicador_cited_article.php">Artigo mais citados</A></LI>';
echo '<LI><a href="indicador_fi.php">Fator de Impacto (FI)</A></LI>';
echo '<LI><a href="indicador_h_autor.php">�ndice h - autores</A></LI>';
echo '<LI><a href="indicador_h_revista.php">�ndice h - revista</A></LI>';
echo '<LI><a href="indicador_net.php">Rede das revistas</A></LI>';
echo '</UL>';


?>
<A HREF="index_about_journal.php?dd1=1">Processar</A>
