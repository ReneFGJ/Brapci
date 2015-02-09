<?php
require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_windows.php");

$pos = array();
$whp = '';

require("_class/_class_svg.php");
$gr = new svg;
$x = 1024;
$y = 1024;

/* Grafico */
$gr->create($x,$y);
$gr->texto(utf8_encode('Autor - Assunto - Tema'),10,20,'black',20);


$ano = array();
$tem = array();
$aut = array();

$arc = array();

/* Salva padroes */
$arc = array();
for ($q=0;$q < count($auts);$q++)
	{
	$aru = $auts[$q];
	for ($x=0;$x < count($aru);$x++)
		{ for ($y=($x+1);$y < count($aru);$y++)
			{ $arc = arcs($aru[$x],$aru[$y],$arc); }
		}
	}

/* mostra relacoes */
for ($r=0;$r < count($arc);$r++)
	{
		$prf1 = $arc[$r][0];
		$prf2 = $arc[$r][1];
		$size = round($arc[$r][2]*0.5*10)/10;
		$size = 0.5+log($arc[$r][2]);
		
		$p1 = recupera_professor_xy($lprof,$prf1);
		$p2 = recupera_professor_xy($lprof,$prf2);
		if (($p1[0]==0) or ($p2[0]==0))
			{
					
			} else {
				$x1 = $p1[0];
				$y1 = $p1[1];
				$x2 = $p2[0]-$x1;
				$y2 = $p2[1]-$y1;
				$cor = 'green';
				$bar1 = 0; $bar2 = 0;
				
				/* Q1 */
				if ($x1 < $posx) {$q1 = 1; }
				if ($x1 >= $posx) {$q1 = 3; }
				if ($y1 >= $posy) {$q1 = $q1 + 1; }
				
				if ($p2[0] < $posx) {$q2 = 1; }
				if ($p2[0] >= $posx) {$q2 = 3; }
				if ($p2[1] >= $posy) {$q2 = $q2 + 1; }
				
				$gr->img = $gr->img . '<path d="M '.$x1.' '.$y1.' q '.$bar2.' '.$bar1.' '.$x2.' '.$y2.'" stroke="'.$cor.'" stroke-width="'.$size.'" fill="none" />'.chr(13);
			}		
	}

for ($r=1900;$r < 2013;$r++)
	{	
	$gr->img .= '<ellipse cy="'.(($r-1900)*15+30).'" cx="40" rx="4.00" ry="4.00" style="fill:rgb(255,255,255);stroke:rgb(0,0,0);stroke-width:0.50;"/>
		<text x="5" y="'.(($r-1900)*15+30).'" style="font-family:MS Sans Serif;font-size:12px;fill:rgb(0,0,0)">'.$r.'</text>';
	}				
/* Finaliza grafico */
$gr->close();
$file = 'rede'.$dd[0].'.svg';
$gr->save($file);
//echo $gr->show($file,800,600);
echo '<img src="'.$file.'" border=1 width="100%>';
require("../foot.php");


function arcs($n1,$n2,$arc)
	{
		$ok = 0;
		if ($n1==$n2) { return($arc); }
		if (strlen(trim($n1))==0) { return($arc); }
		for ($r=0;$r < count($arc);$r++)
			{
				if (($arc[$r][0]==$n1) and ($arc[$r][1]==$n2))
					{
						$arc[$r][2] = $arc[$r][2] + 1;
						return($arc);
					}
				if (($arc[$r][0]==$n2) and ($arc[$r][1]==$n1))
					{
						$arc[$r][2] = $arc[$r][2] + 1;
						return($arc);
					}
			}
		array_push($arc,array($n1,$n2,1));
		return($arc);
	}
	
function recupera_professor_xy($prof,$codigo)
	{
		for ($r=0;$r < count($prof);$r++)
			{
				if ($prof[$r][1]==$codigo) { return(array($prof[$r][4],$prof[$r][5],$prof[$r][0])); }
			}
		return(array(0,0));
	}
?>
