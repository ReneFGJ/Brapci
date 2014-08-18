<?
$dd[10] .= chr(13);
$s = $dd[10];
$s = MAR_preparar($s);
$s = troca($s,chr(9),' ');
$s = troca($s,'  ',' ');
$dd[10] = $s;
?>
<center>
<form action="brapci_marcacao_editar.php" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="3000000">
<textarea cols="70" rows="18" name="dd10"><?=$dd[10];?></textarea>
<BR>
<input type="checkbox" name="dd11" value="1">Salvar&nbsp;&nbsp;&nbsp;
&nbsp;<input type="submit" value="e n v i a r" class="lt2" <?=$estilo?>>
<input type="hidden" name="dd0" value="<?=$dd[0]?>">
<input type="hidden" name="dd1" value="<?=$dd[1]?>">
<input type="hidden" name="dd2" value="<?=$dd[2]?>">
<input type="hidden" name="dd3" value="<?=$dd[3]?>">
</form>
</center>
<table width="640" align="center" border="1">
<?
$refs=array();
$article = $dd[0];
$sql = "delete from mar_works where m_work = '".$article."' ";
if (strlen($article) > 0) { $rlt = db_query($sql); }
$s .= ' '.chr(13);
while (strpos($s,chr(13)) > 0)
	{
	$sa = trim(substr($s,0,strpos($s,chr(13))));
	$s = ' '.substr($s,strpos($s,chr(13))+1,strlen($s));
	if (strlen($sa) > 0)
		{
		$sa = troca($sa,"'",'´');
//		$autores = MAR_autor($sa);
//		$titulo = MAR_titulo($sa,$autores);
//		$part2  = MAR_cuts($sa,$titulo);
		//////////////////////////////////// Autores
		//MAR_autor_grava($autores);
		////////////////////////////////////
		echo '<TR><TD><TT>'.$sa;
//		echo '<BR>Título:' . $titulo;
		if (substr($sa,0,2) == '__')
			{
				if (strlen($__autor) == 0) 
					{
					echo 'ops, erro';
					exit;
					}
				$sa = $__autor.substr($sa,7,strlen($sa));
			} else {
				$__autor = substr($sa,0,strpos($sa,'.')+1);
			}
		if ($dd[11] == '1')
			{
			$sql = "INSERT INTO `mar_works` ( ";
			$sql .= "`m_status` , `m_ref` , `m_title` , ";
			$sql .= "`m_codigo` , `m_journal` , ";
			$sql .= " m_tipo, m_work";
			$sql .= ") VALUES (";
			$sql .= " '@','".$sa."','".$titulo."',";
			$sql .= "'','',";
			$sql .= "'','".$article."'";
			$sql .= ")";
			//echo '<HR>'.$sql.'<HR>';
			$rlt = db_query($sql);
			}
		}
	}
if ($dd[11] == '1')
	{
//	exit;
	redirecina("bracpi_article.php?dd0=".$dd[0]."&dd50=1&dd51=&dd52=");
	}
?>
</table>
<?
function MAR_preparar($x)
	{
	$x = chr(13).chr(10).$x;
	for ($r = 1;$r < 150;$r++)
		{ $x = troca($x,chr(10).$r.'. ',''); }
	for ($r = 1;$r < 150;$r++)
		{ $x = troca($x,chr(10).'['.$r.'] ',''); }
	for ($r = 1;$r < 150;$r++)
		{ $x = troca($x,chr(10).$r.' ',''); }		
	$x = troca($x,'[ Links ]','');
	$x = troca($x,chr(92),'');
	$x = troca($x,chr(13),'§');
	$x = troca($x,chr(10),'');
	$x = troca($x,'§§','§');
	$x = troca($x,'§§','§');

	$x = troca($x,'Mc','MC');
	$x = troca($x,'Ä','A');
	$x = troca($x,'Á','A');
	$x = troca($x,'Â','A');
	$x = troca($x,'Ü','U');
	$x = troca($x,'É','E');
	$x = troca($x,'Ê','E');
	$x = troca($x,'È','E');
	$x = troca($x,'Ë','E');
	$x = troca($x,'Ê','E');
	$x = troca($x,'Í','I');
	$x = troca($x,'Î','I');
	$x = troca($x,'Ó','O');
	$x = troca($x,'Ö','O');
	$x = troca($x,'Ô','O');
	$x = troca($x,'Ü','U');
	$x = troca($x,'Û','U');
	$x = troca($x,'Š','S');
	
	$x = troca($x,'______,','AAAAAA');
	$x = troca($x,'______;','AAAAAA');
	$x = troca($x,'______','AAAAAA');
	$x = troca($x,'______','AAAAAA');
	$x = troca($x,'_____','AAAAAA');
	

	$x = ' '.$x;
	while (strpos($x,'§') > 0) 
		{
		$pos = strpos($x,'§');
		$xs = substr($x,$pos,3);
		$x1 = ord(substr($xs,1,1));
		$x2 = ord(substr($xs,2,1));
		if (($x1 >= 65) and ($x1 <= 90) and ($x2 >= 65) and ($x2 <= 90))
			{
				$x = substr($x,0,$pos).chr(13).chr(13).substr($x,$pos+1,strlen($x));
			} else {
				$x = substr($x,0,$pos).' '.substr($x,$pos+1,strlen($x));
			}
		}
	$x = troca($x,'AAAAAA','______');
	return(trim($x));
	}
////////////////////////////////////////////////// Proposição e Conjunção
function MAR_cuts($pr,$ar)
	{
	$tit = trim(substr($pr,strpos($pr,$ar)+1+strlen($ar),strlen($pr)));
	$tit = substr($tit,0,strpos($tit,'.'));
	
	$result = $tit;
	return($result);
	}
	

////////////////////////////////////////////////// Busca Título
function MAR_titulo($pr,$ar)
	{
	$aut = $ar[count($ar)-1];
	$tit = trim(substr($pr,strpos($pr,$aut)+1+strlen($aut),strlen($pr)));
	$tit = substr($tit,0,strpos($tit,'.'));
	return($tit);
	}
////////////////////////////////////////////////// Proposição e Conjunção
function MAR_preposicao($pr)
	{
		$result = 0;
		// fonte: http://pt.wikipedia.org/wiki/Preposi%C3%A7%C3%A3o
		$prep = array('a','ante','após','apos','com','contra',
				'de','desde','em','e','entre','para','per','perante',
				'por','sem','sob','sobre','trás','tras','da');
		if (in_array($pr, $prep))
			{ return(1); } else { return(0); }
	}
////////////////////////////////////////////////// AUTORES
function MAR_autor_grava($au)
	{
	echo '<HR>';
	print_r($au);
	echo '<HR>';	
	for ($r=0;$r < count($au);$r++)
		{
		$aut = UpperCaseSql($au[$r]);
		$aut = troca($aut,' ','');
		$aut = troca($aut,'.','');
		$aut = troca($aut,',','');
		$aut = troca($aut,';','');
		
		$sql = "select * from mar_autor where a_nome_asc  = '".$aut."' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt))
			{ 
				$cod = $line['a_codigo']; 
			} else {
				$isql = "insert into mar_autor ";
				$isql .= "(a_nome,a_nome_asc,a_abrev,a_codigo,a_use) ";
				$isql .= " values ";
				$isql .= "('".$au[$r]."','".$aut."','".$au[$r]."','','')";
				$rlt = db_query($isql);
				
				$isql = "update mar_autor set a_use=lpad(id_a,'10','0') ,a_codigo=lpad(id_a,'10','0') where (length(a_codigo) < 10)";
				$rlt = db_query($isql);
			}
		}
	}
function MAR_autor($ss)
	{
	$au = array();
	$sx = '';
	$ss = trim($ss);
	// analise
	for ($r=0;$r < strlen($ss);$r++)
		{
		$c = substr($ss,$r,1);
		$ok = 1;
		if (($c == '.') and (substr($ss,$r+2,1) == '.'))
			{
			$ok = 0;
			}
		///////////////////////////////////////////////////////// AUTORES
		if ((($c == '.') or ($c == ';')) and ($ok == 1))
			{
			///// Necessidade de ponto no final
			if ($c == '.') 
				{ 
				$ant = ord(substr($sx,strlen($sx)-1,1));
				if (($ant >= 65) and ($ant <= 90))
					{ $sx .= '.'; $c = ''; }
				}
			$sx = trim(troca($sx,';',''));
			if (strlen($sx) > 0) { array_push($au,$sx); }
			$sx = '';
			}
		////////////////////////////////////////////////////////// FINALIZAR
		/////////////////////////////////////////////// Minuscula na Segunda
		if ($c == ' ')
			{
				$sp = substr($ss,$r+1,50);
				$sp = substr($sp,0,strpos($sp,' '));
				$sp = troca($sp,'.',''); // tirar ponto
				$sp = troca($sp,';',''); // tirar ponto e virgula
				$pre = MAR_preposicao($sp).',';
				if (((ord($sp) < 65) or (ord($sp) > 90)) and ($pre == 0))
					{
					return($au);
					}
			}
		$sx .= $c;
		}
	return($s);
	}
?>