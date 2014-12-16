<?
require("cab.php");
require('../'.$include.'sisdoc_debug.php');

require("../_class/_class_export.php");
$exp = new export;
$max = 340;

echo '<div id="main">';
echo '<h1>Exportação para base pública</h1>';
echo '<BR>';

if (strlen($dd[0]) == 0)
	{
		//$exp->zera_public();
		echo '<h3>Zerar base de dados pública</h3>';
		
		$exp->exporta_texto();
		echo '<h3>Zerar base de dados pública</h3>';
		
		$ini = 0;
	} else {
		$ini = round($dd[0]);
	}
	
$fim = $exp->total_trabalhos();

echo '<h2>Posição: '.$ini.'/'.$fim.'</h2>';

$sql = $exp->export_public($ini,$max);
if (strlen($sql) > 0)
	{
		$ini = $ini + $max;
		$rlt = db_query($sql); 
		echo '<A HREF="http://www.brapci.ufpr.br/mm/export.php?dd0='.$ini.'">Proxima</a>';  
    	echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL=export_public.php?dd0='.$ini.'">';
	}
	

function qualificacao($x)
	{
	$prof = $x['ae_professor'];
	$estu = $x['ae_aluno'];
	$mest = $x['ae_mestrado'];
	$dout = $x['ae_doutorado'];
	$pros = $x['ae_profissional'];
	$rt = '';
	if ($estu == 1)
		{
		if ($mest == 1)
			{ $r = 'Mestrando '; } else
			{ if ($dout == 1) 
				{ $r = 'Doutorando '; } else { $r = 'Estudante'; }
			}
		} else {
			if ($dout == 1) 
					{ $r = 'Doutor '; } else
					{ if ($mest == 1) 
						{ $r = 'Mestre '; } 
					}
			if ($prof == 1) { $r = 'Prof.(a) '.$r; }						
		}
		return($r);
	}
?>
