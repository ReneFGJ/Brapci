<?
//**** PAJEK
//** EDGE
//** 1 2 12 (ligaçao de 1 com 2, 12 vezes)
//** ARC - Define a direção das ligações
//** ic - Cor Interna
//** bc - Cor de contorno
//** x_fact lagura dos vértices
//** y-fact    "    "      "
//
//
require("db.php");
$sql = "SELECT baa2.ae_author as autor, baa1.ae_article as ae_article, autor_nome_abrev ";
$sql .= "FROM brapci_article_author AS baa1 ";
$sql .= "inner join brapci_article on ar_codigo = baa1.ae_article and ar_status <> 'X' ";
$sql .= "INNER JOIN brapci_article_author AS baa2 ON baa1.ae_article = baa2.ae_article ";
$sql .= "inner join brapci_autor on autor_codigo = baa2.ae_author ";
$sql .= "WHERE baa1.ae_journal_id = '".$dd[0]."' ";
$sql .= "ORDER BY baa2.ae_article,baa2.ae_author ";
$rlt = db_query($sql);

$autor = array();
$codigo = array();
$producao = array();
$rel = array();
$rela = array();
$relt = array();
$artigo = array();
$art = "XX";
$aut = "XX";

while ($line = db_read($rlt))
	{
	$cod = $line['autor'];
	$nome = $line['autor_nome_abrev'];
	$arti = $line['ae_article'];
	
	///////////////////////////////////////////////// AUTORES
	if (in_array($cod,$codigo))
		{
		} else {
				array_push($codigo,$cod);
				array_push($autor,$nome);
				array_push($producao,0);
		}
	///// Relações
	if ($arti != $art)
		{
			$aut = "XX";
			$a = array();
			array_push($artigo,array($arti,$a));
			$art = $arti;
		} 
		$p = (count($artigo)-1);
		if ($nome != $aut)
			{ array_push($a,$nome); $aut = $nome; }
		$artigo[$p][1] = $a;
	}
/////////////////////////////////////// CRIAR RELACOES

for ($k1 = 0;$k1 < count($autor);$k1++)
	{
	for ($k2 = 0;$k2 < count($autor);$k2++)
		{
			$r = array(($k1+1),($k2+1));
			array_push($rela,$r);
			array_push($relt,0);
		}
	}

///////////////////////////////////////// POPULACIONA VARIAVEIS
for ($r=0;$r < count($artigo);$r++)
	{
	$ra = $artigo[$r][1];
	if (count($ra) == 1)
		{
		$nome = $ra[0];
		$ai = array_search($nome,$autor)+1;
		$va = array(round($ai),round($ai));
		$ab = array_search($va,$rela);
		$relt[$ab] = $relt[$ab] + 1;
		$producao[$ai-1] = $producao[$ai-1] + 1;
		}
		
	//////////////////////////////////////////////////// DOIS AUTORES
	if (count($ra) > 1)
		{
		for ($xx=0;$xx < (count($ra)-1);$xx=$xx+1)
			{
			for ($yy=$xx;$yy < (count($ra));$yy=$yy+1)
				{
				$n1 = $ra[$yy];
				$n2 = $ra[$xx];
				$ai1 = array_search($n1,$autor)+1;
				$ai2 = array_search($n2,$autor)+1;
				$va = array(round($ai1),round($ai2));
				$ab = array_search($va,$rela);
				if (strlen($ab) == 0) 
					{
					$va = array(round($ai2),round($ai1));
					$ab = array_search($va,$rela);
					}
				if (strlen($ab) == 0) { echo 'ops '; exit; }

				$relt[$ab] = $relt[$ab] + 1;
				$producao[$ai1-1] = $producao[$ai1-1] + 1;
				$producao[$ai2-1] = $producao[$ai2-1] + 1;				
				}
			}
		}	
	}
	
//////////////////////////////////////////////////////////////////////// IMPRESSAO
echo '<TT>';	
echo '*vertices '.count($autor);
$angle = 0;
$t = (360 / count($autor));
$mult = 0.1;
$multi = 0.5 * 0.85;
$desc = 0.08;
$cr = chr(13).chr(10).'<BR>';

for ($r=0;$r < count($autor);$r++)
	{
	$x = (cos(deg2rad($angle))+1)*$multi+$desc;
	$y = (sin(deg2rad($angle))+1)*$multi+$desc;
	$angle = round($angle + $t);

	echo $cr.($r+1).' "'.$autor[$r].'"';
	echo '                      ';
	echo number_format($y,4).' '.number_format($x,4).' '.number_format($producao[$r]*$mult+0.4,4);
	$y = $y + 0.5;
	$x = $x + 0.2;
	}
echo $cr;
echo '*Edges';
for ($rx=0;$rx < count($rela);$rx++)
	{
	if (($relt[$rx] > 0) )
		{
		echo $cr;
		echo ' ';
		echo $rela[$rx][0];
		echo ' ';
		echo $rela[$rx][1];
		echo '     ';
		echo $relt[$rx];
		}
	}
	
?>

