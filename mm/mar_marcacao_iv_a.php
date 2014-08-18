<?
echo '<table width="95%">';
echo '<TR><TH>Fase</TH><TH>Descricao</TH></TR>';

////////////////////////////////////////////// Fase I - Separar as palavras
echo '<TR><TD align="center">1</TD><TD>Separar as palavras</TD></TR>';
$wd = array();
$s = uppercasesql($s);
$s = troca($s,'"',' ');
$s = troca($s,',',' ');
$s = troca($s,':',' ');
$s = troca($s,';',' ');
$s = troca($s,'.',' ');
$s = troca($s,'?','');
$s = troca($s,' E ',' ');
$s = troca($s,' A ',' ');
$s = troca($s,'?',' ');
$s = troca($s,'?',' ');
$s = troca($s,'0',' ');
$s = troca($s,'1',' ');
$s = troca($s,'2',' ');
$s = troca($s,'3',' ');
$s = troca($s,'4',' ');
$s = troca($s,'5',' ');
$s = troca($s,'6',' ');
$s = troca($s,'7',' ');
$s = troca($s,'8',' ');
$s = troca($s,'9',' ');
$s = troca($s,'-',' ');
$s = troca($s,'/',' ');
$s = troca($s,'“',' ');
$s = troca($s,'”',' ');
$s = troca($s,'!',' ');

$wd = split(' ',$s);
echo '<TR><TD class="lt0">Original:</TD><TD colspan="2"><TT>'.$sx.'</TD></TR>';
echo '<TR><TD class="lt0">Convertido:</TD><TD colspan="2"><TT>'.$s.'</TD></TR>';

$wmax = 4; $ini = 1;
require("brapci_mar_journal_busca_limitador.php");

if (count($ln) == 0)
	{
	$wmax = 4; $ini = 2;
	require("brapci_mar_journal_busca_limitador.php");
	}

if (count($ln) == 0)
	{
	$wmax = 4; $ini = 3;
	require("brapci_mar_journal_busca_limitador.php");
	}

if (count($ln) == 0)
	{
	$wmax = 4; $ini = 4;
	require("brapci_mar_journal_busca_limitador.php");
	}

if (count($ln) > 1)
	{
	$wmax = 4; $ini = 0;
	require("brapci_mar_journal_busca_limitador.php");
	}
	
if (count($ln) > 1)
	{
	$wmax = 5; $ini = 1;
	require("brapci_mar_journal_busca_limitador.php");
	}

if (count($ln) > 1)
	{
	$wmax = 5; $ini = 0;
	require("brapci_mar_journal_busca_limitador.php");
	}

if (count($ln) > 1)
	{
	$wmax = 6; $ini = 1;
	require("brapci_mar_journal_busca_limitador.php");
	}
	
if (count($ln) > 1)
	{
	$wmax = 6; $ini = 0;
	require("brapci_mar_journal_busca_limitador.php");
	}
	
if (count($ln) > 1)
	{
	$wmax = 7; $ini = 1;
	require("brapci_mar_journal_busca_limitador.php");
	}
	
$wmax = 7; $ini = 0;
while ((count($ln) > 1) and ($wmax < 20))
	{
	$wmax++;
	require("brapci_mar_journal_busca_limitador.php");
	}


$kk = 0;
	
if (count($ln) == 1)
	{
		require("../db/db_brapci.php");
		$sql = "update mar_works set m_bdoi = '".$ln[0]."', m_status = 'F' where id_m = ".sonumero($dd[0])." ";
		$rlt = db_query($sql);
		echo '<TR><TD align="center">1</TD><TD><font color=Green>Dados salvos com sucesso '.$ln[0].'</TD></TR>';
		$kk = 1;
	} else {
		require("../db/db_brapci.php");
		$sql = "update mar_works set m_status = 'H' where id_m = ".sonumero($dd[0])." ";
		$rlt = db_query($sql);
		echo '<TR><TD align="center">1</TD><TD><font color=Red>Localizado '.count($ln).' trabalhos</TD></TR>';
	}

	
echo '</table>';
	
?></DIV>