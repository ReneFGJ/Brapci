<?php
require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_windows.php");

$d1 = 2010;
$d2 = 2013;


$programa = strzero($dd[0],5);
if (strlen($dd[0]) > 0)
	{
		$sql = "select * from ".$db_diris."programa_pos 
			where pos_ativo = 1 and pos_codigo = '$programa'
			order by pos_mestado
		";
	} else {
		$sql = "select * from ".$db_diris."programa_pos 
			where pos_ativo = 1
			order by pos_mestado
		";
	}
$rlt = db_query($sql);
$pos = array();
$whp = '';
while ($line = db_read($rlt))
	{
		array_push($pos,array($line['pos_codigo'],trim($line['pos_instituicao']).'-'.trim($line['pos_sigla'])	));
		if (strlen($whp) > 0) { $whp .= ' or '; }
		$whp .= " (pdce_programa = '".$line['pos_codigo']."') ";
	}

require("../_class/_class_svg.php");
$gr = new svg;
$x = 1024;
$y = 1024;

/* Grafico */
$gr->create($x,$y);
$gr->texto(utf8_encode('Programas de Pós-Graduação em Ciência da Informação'),10,20,'black',20);

$sql = "select * from ( 
			select pdce_docente, pdce_programa from ".$db_diris."programa_pos_docentes 
			where ($whp) and pdce_ativo = 1
			group by pdce_docente, pdce_programa 
		) as professores 
		inner join ".$db_diris."diris on pdce_docente = dis_codigo
		order by pdce_programa, dis_nome	
		";
$rlt = db_query($sql);

$prof = array();
$profs = array();

$others = array();

$arc = array();
$xprog = "";
while ($line = db_read($rlt))
	{
		$prog = $line['pdce_programa'];
		if ($prog != $xprog)
			{
				$xprog = $prog;
				if (count($prof) > 0) {	array_push($profs,$prof); }
				$prof = array();				
			}
		array_push($prof,array($line['dis_nome'],$line['dis_source'],$line['dis_genero'],$line['pdce_docente'],0,0,0,$line['pdce_programa']));
	}
if (count($prof) > 0) 
	{ array_push($profs,$prof); }
	
/* Imprime nome */
$i=0;
for ($t=0;$t < count($profs);$t++)
	{
	$prof = $profs[$t];
	for ($r=0;$r < count($prof);$r++)
		{
			$nome = 'P'.strzero($r+1,2).' = '.$prof[$r][0];
			$size = 6;
			$gr->texto(utf8_encode($nome),800,10+$i*($size+1),'black',$size);
			$i++;
		}
	}

$totp = count($prof);

$frc = 57.29577951; 

/* Circulo dos professores */
if (strlen($dd[0])==0)
	{
		$inc = 360 / $totp;
		$raio = $totp-12;
		$raio = 10;
		$raio_p = 1;
		$fsize = 7;
		$id = 0;
		$ang = 0;
		$posxi = 150;
		$posyi = 150;
	} else {
		$inc = 360 / $totp;
		$raio = $totp-12;
		$raio = 32;
		$raio_p = 2;
		$fsize = 14;
		$id = 0;
		$ang = 0;
		$posx = ($x/2);
		$posy = ($y/2);		
	}
$lprof = array();
$cols = 3;
for ($t=0;$t < count($profs);$t++)
	{
	$prof = $profs[$t];
	
	if (strlen($dd[0])==0)
		{
		echo '<BR>===>['.$t.']'.intval($t/$cols);
		$irow = intval($t/$cols);
		$post = ($t - $irow*$cols)*250;
		$posx = $posxi + $post;
		 
		$post = ($irow)*160+$posyi;
		$posy = $post;
		}
	
	/* Nome da pos */
	$pos_c = $prof[0][7];
	$nome = '';
	for ($q=0;$q < count($pos);$q++)
		{
			if ($pos_c == $pos[$q][0]) { $nome = $pos[$q][1]; }
		}
	$gr->texto($nome,$posx-100,$posy-80,'black',14);
	$totp = count($prof);
	if ($totp > 0)
	{
	$inc = 360 / $totp;
	for ($r=0;$r < count($prof);$r++)
		{
			$nome = 'P'.strzero($r+1,2); 
			$xc = round(cos($ang/$frc)*$raio*8)+($posx);
			$yc = round(sin($ang/$frc)*$raio*6)+($posy);
			$gr->circle($xc,$yc,$raio_p,'white','grey');
			$gr->texto($nome,$xc-$fsize,$yc-round($fsize/2),'grey',$fsize);
			$ang = $ang + $inc;
			$prof[$r][4] = $xc;
			$prof[$r][5] = $yc;
			array_push($lprof,$prof[$r]);
		}
	}
}


/* Busca produï¿½ï¿½o */
$pr = 16;
$wh = '';
for ($t=0;$t < count($profs);$t++)
{
	$prof = $profs[$t];
	for ($r=0;$r < count($prof);$r++)
		{
			$nprof = trim($prof[$r][1]);
			if (strlen($nprof) > 0)
				{
				if (strlen($wh) > 0) { $wh .= ' or '; }		
				$wh .= "(ae_author = '".$prof[$r][1]."')";
				}
		}
}
$sqls = "
		select ar_codigo from brapci_article_author
				inner join brapci_article on ar_codigo = ae_article
				inner join brapci_edition on ar_edition = ed_codigo
				inner join brapci_section on ar_section = se_codigo
		where ($wh) and (se_tipo = 'B') AND (ar_status <> 'D')
				and ((ed_ano >= $d1) and (ed_ano <= $d2))
		";

		
$sql = "select id_ae, pdce_docente, inst_abreviatura, dis_nome, ae_aluno, ae_mestrado, ae_doutorado, ae_article, autor_codigo, autor_nome from (".$sqls.") as tabela
			inner join brapci_article_author on ae_article = ar_codigo
			inner join brapci_autor on ae_author = autor_codigo
			left join ".$db_diris."diris on ae_author = dis_source 
			left join ".$db_diris."programa_pos_docentes on pdce_docente = dis_codigo
			left join instituicoes on inst_codigo = ae_instituicao
		group by id_ae, pdce_docente, inst_abreviatura, dis_nome, ae_aluno, ae_mestrado, ae_doutorado, ae_article, autor_codigo, autor_nome
		order by ae_article, autor_codigo, autor_nome
		";
				
$rlt = db_query($sql);

$xart = 'x';
$aru = array();
$auts = array();
while ($line = db_read($rlt))
	{
		$dis_nome = trim($line['pdce_docente']);
		$dis_inst = trim($line['inst_abreviatura']);
		$link = '';
		if ((strlen($dis_inst)==0) and (strlen($dis_nome)==0))
			{
			echo '<font color="red">';
			
			$link = '<A HREF="#" onclick="newxy2(\'autor_qualificar.php?dd0='.$line['id_ae'].'\',600,400);">';
			echo '<HR>';
			echo $link.'[ed]</A>';
			echo $line['autor_nome'];

			$linka = '<A HREF="http://www.brapci.inf.br/article.php?dd0='.$line['ae_article'].'" target="_new">';
			echo ' - '.$linka.'ver artigo</A>';
						
			echo '<HR>';
			}
		echo '</font>';
		$art = $line['ae_article'];
		$aut = trim($line['autor_codigo']);
		if ($xart != $art)
			{
				if (count($aru) > 0) { array_push($auts,$aru); }
				$aru = array();	
				$xart = $art;		
			}
		
		echo '<BR>'.$linka.$line['autor_nome'].'</A>'.'--'.$line['ae_article'],'-',$line['autor_codigo'];
		if (strlen($$dis_nome) > 0) { echo '*'; }
		else 
				{
					if (strlen($dis_inst) > 0)
					{
					array_push($others,array($line['autor_nome'],$line['autor_codigo'],$line['dis_genero'],$line['autor_codigo'],0,0,0,$dis_inst));
					}
				}
		
		
		array_push($aru,$aut);
	}
if (count($aru) > 0) { array_push($auts,$aru); }
print_r($others);
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
	
			
/* Finaliza grafico */
$gr->close();
$file = '_pos_'.$dd[0].'.svg';
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
