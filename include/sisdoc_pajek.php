<?
/**
 * @autor Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * $txt -> Paramentros enviados para análise
 * estrutura:
 * "NOME1","NOME2","NOME3","NOME4" CRNF
 * "NOME1","NOME4","NOME5"
*/
function pajek_centralidade($txt)
	{
	$txt = troca($txt,'  ',' ').chr(13).'**FIM';
	$ln = splitx(chr(13),$txt);
	
	/* Busca quantidade de vértices da matriz */
	$verices = 1;
	$vp = strpos(UpperCase($txt),'*VERTICES');
	$vp = substr($txt,$vp,100);
	$vp = UpperCase(substr($vp,0,strpos($vp,chr(13))));
	$vp = sonumero($vp);
	
	$mtxl = array();
	$ator = array();
	for ($rp=0;$rp < $vp;$rp++)
		{ array_push($mtxl,0); }
	$mtx = array();
	for ($rp=0;$rp < $vp;$rp++)
		{ array_push($mtx,$mtxl); }
	
	/* Recupeara o Vertices e Edges */
	$at =0; // Edges ativo: Não
	$vz =0; // Atores ativo: Não

	for ($rp=0;$rp < count($ln);$rp++)
		{
		/* Vertices */
		if ($vz == 1)
			{
			$atr = $ln[$rp];
			if (strpos($atr,'"') > 0)
				{
					$atr = substr($atr,strpos($atr,'"')+1,100);
					$atr = substr($atr,0,strpos($atr,'"'));
				} else {
					$atr = trim(substr($atr,strpos($atr,' ')+1,100));
					$atr = substr($atr,0,strpos($atr,' '));
				}
			array_push($ator,$atr);
			}
		/* Edges */
		if ($at == 1)
			{ 
			$ll = $ln[$rp].' ;';
			$ll = splitx(' ',$ll);
			$p1 = round($ll[0])-1;
			$p2 = round($ll[1])-1;
			if (strlen($ll[2]) == 0) { $ll[2] = 1; }
			$mtx[$p1][$p2] = $ll[2]; 
			$mtx[$p2][$p1] = $ll[2]; 
//			echo '<BR>'.$p1.' '.$p2.' '.$ll[2].' '.$mtx[$p1][$p2];
			$po = 1;
			}
		if (substr(uppercase($ln[$rp]),0,5) == '*ARCS')    { $at = 1; $vz = 0; }
		if (uppercase($ln[$rp]) == '*EDGES')    { $at = 1; $vz = 0; }
		if (uppercase(substr($ln[$rp],0,9)) == '*VERTICES') { $at = 0; $vz = 1; }
		}
		
	/* Quantidade de Nós */
	$no1=0;
	$no2=0;
	$no3=0;
	$no4=0;
	for ($rx=0;$rx < $vp;$rx++) {
	for ($ry=0;$ry < $vp;$ry++) {
		$no1 = $no1 + $mtx[$rx][$ry];
		if ($mtx[$rx][$ry] > 0) { $no2++; }
		if ($rx != $ry)
			{
			$no3 = $no3 + $mtx[$rx][$ry];
			if ($mtx[$rx][$ry] > 0) { $no4++; }
			}
	} }
	

	/* Total de possibilidades */
	$totp = ($vp*($vp-1))/2;

	$sr = '';
	$sr .= '<BR><TT>Quantida de Edges total: <B>'.$no1.'</B> com <B>'.$no2.'</B> relações ';
	$sr .= '<BR><TT>Quantida de Edges (diferente da sua): <B>'.$no3.'</B> com <B>'.$no4.'</B> relações ';
	$sr .= '<BR><TT>Total de vértices: <B>'.$vp.'</B>, combinados possibilitam <B>'.$totp.'</B> relações ';
	
	/* Impressão da Matrix */
	
	$sa = '<TABLE>';
	$sa .= '<TR><TH>-</TH>';
	for ($rx=0;$rx < $vp;$rx++)
		{ 
		$sa .= '<TH>'.strzero($rx+1,2).'</TH>';
		$sx .= '<TR>';
		$sx .= '<TH>'.strzero($rx+1,2).'</TH>';
		for ($ry=0;$ry < $vp;$ry++)
			{
			$vlr = $mtx[$rx][$ry];
			if ($vlr == 0) { $vlr = '&nbsp;-&nbsp;'; }
			$sx .= '<TD>'.$vlr.'</TD>';
			}
		}
		
	/* Calculo de Centralidade dos atores */
	$st = '<table width="100%" border="1">';
	$st .= '<TR valign="bottom"><TH colspan="2">Ator</TH>';
	$st .= '<TH colspan="2">Centralidade (Edges)<BR><I>(degree centrality)</I></TH>';
	$st .= '<TH colspan="2">Centralidade <BR><I>total de ligações</I></TH>';

	$st .= '<TH colspan="2">Densisade Total</TH>';
//	$st .= '<TH colspan="2">Densidade (Edges)</TH>';

	$to1=0; $to2=0; $to3=0; $to4=0;
	$to5=0; $to6=0; $to7=0; $to8=0;
	for ($rx=0;$rx < $vp;$rx++) {
	$st .= '<TR><TD>'.($rx+1).'</TD><TD>'.$ator[$rx];
	$ind = 0;
	$ind2 = 0;
	
	for ($ry=0;$ry < $vp;$ry++) {
		if (($rx != $ry) and ($mtx[$rx][$ry] > 0)) { $ind = $ind + $mtx[$rx][$ry]; $ind2++; }
		}
		$st .= '<TD align="center">'.$ind2.'</TD>';
		if ($vp> 0) { $st .= '<TD align="center">'.number_format($ind2/($vp-1)*100,1).'% '; } else { $st .= '<TD align="center">&nbsp;-&nbsp;</TD>'; }

		$st .= '<TD align="center">'.$ind.'</TD>';
		if ($totp > 0) { $st .= '<TD align="center">'.number_format($ind/$totp*100,1).'% ';  } else { $st .= '<TD align="center">&nbsp;-&nbsp;</TD>'; }

		$to1 = $to1 + $ind;
		$to2 = $to2 + $ind2;
		if ($no3 > 0) {$to3 = $to3 + $ind/$no3*100;}
		if ($no4 > 0) {$to4 = $to4 + $ind2/$no4*100;}
		
		/* Densidade */
		$den = 0;
		if ($vp > 0) { $den = ($ind2 / $totp); }
		$st .= '<TD align="center">'.number_format($den,4).'</TD>';
		
	}
	$st .= '<TR><TD colspan="2">Totais</TD>';
	$st .= '<TD align="center" colspan="2">'.$to2.' ('.$to4.'%)</TD>';
	$st .= '<TD align="center" colspan="2">'.$to1.' ('.$to3.'%)</TD>';
	$st .= '</table>';
	
	
	
	echo $st;
	echo '<HR>';
	echo $sr;
	$sx = $sa . $sx;
	$sx .= '</TABLE>';
	echo $sx;
	}

function pajek($txt)
	{
	/* Parametro para quantidade mínima de nós apresentado */
	global $nos;
	if (strlen($nos) == 0) { $nos = 1; }
	$txt = $txt . chr(13);
	$tx = $txt;
	$ln = array();
	$autor = array();
	$autor_p = array();
	$rel = array();
	$rel_p = array();
	while (strpos($tx,chr(13)) > 0)
		{
		$xp = strpos($tx,chr(13));
		$lx = trim(substr($tx,0,$xp));
		$tx = ' '.substr($tx,$xp+1,strlen($tx));
		array_push($ln,$lx);
		}

	/* Tamanho máximo do circulo */
	$max = 10;
	for ($r=0; $r < count($ln);$r++)
		{
			$lx = $ln[$r];
			$xx = splitx(';', $lx);
			$az = array();
			
			for ($k=0;$k < count($xx);$k++)
				{
				$au = $xx[$k];
				
				if (!in_array($au,$autor))
					{
						array_push($autor,$au);
						array_push($autor_p,0);
					}
				$pos = array_search($au,$autor);
				$vlr = ($autor_p[$pos] + 1);
				$autor_p[$pos] = $vlr;
				/* Atualiza valor máximo */
				if ($vlr > $max) { $max = $vlr; }
				array_push($az,$pos);
				}
			if (count($az) > 1)
				{
				for ($x = 0;$x < count($az);$x++)
					{
					for ($y = $x;$y < count($az);$y++)
						{
						if ($y != $x)
							{
							$st = ($az[$x]+1).' '.($az[$y]+1);
							if (!in_array($st,$rel))  
								{
								array_push($rel,$st); 
								array_push($rel_p,0);
								}
							$pos = array_search($st,$rel);
							$rel_p[$pos] = $rel_p[$pos]+1;
							}
						}
					}
				}
		}
	$sz = '';
	$sz .= '*vertices '.count($autor);
	$mult = 1/count($autor);
	for ($x=0;$x < count($autor);$x++)
		{
		$angle = $x * $mult;
		$sz .= chr(13).'<BR>';
		$sz .= ($x+1).' ';
		$sz .= '"'.$autor[$x].'" ';
//		$sz .= number_format(cos($angle),4);
//		$sz .= ' ';
//		$sz .= number_format(sin($angle),4);
		$size = (10*$autor_p[$x]/$max);
		if ($size < 1) { $size = 1; }
		$sz .= 'x_fact ';
		$sz .= ' '.number_format($size,4);
		$sz .= ' ';
		$sz .= 'y_fact ';
		$sz .= ' '.number_format($size,4);
		$sz .= ' ';
		$sz .= 'bc bc Green ic Green lc yellow ';
//		$sz .= ' box ';
		}
	$sz .= chr(13).'<BR>*Edges';
	for ($x=0;$x < count($rel);$x++)
		{
		if ($rel_p[$x] >= $nos)
			{
			$sz .= chr(13).'<BR>'.$rel[$x];
			$sz .= ' '.$rel_p[$x];
			}
		}
	if ($nos > 1)
		{ $sz = pajek_limpa($sz,$nos); }
	echo $sz;
	return(1);	
	}
	
function pajek_limpa($txt,$nos)
	{
	$txt = troca($txt,'<BR>','');
	$txt = troca($txt,'<br>','');
	$ln = splitx(chr(13),$txt);
	
	$ln1 = array();
	$ln2 = array();
	$tp = 0;
	for ($rp=0;$rp < count($ln);$rp++)
		{
		$lnx = substr($ln[$rp],0,4);
		if (($tp == 1) and (substr($lnx,0,1) != '*')) 
			{ array_push($ln1,array($ln[$rp],0,0)); }
		if (($tp == 2) and (substr($lnx,0,1) != '*')) 
			{ array_push($ln2,$ln[$rp]); }
		$lnx = substr($ln[$rp],0,4);
		if ($lnx == '*Edg') { $tp = 2; }
		if ($lnx == '*ver') { $tp = 1; }
		}
	/* Marca os válido */
	for ($rp = 0;$rp < count($ln2);$rp++)
		{
		$ln3 = splitx(' ',$ln2[$rp]);
		$p1 = $ln3[0]-1;
		$p2 = $ln3[1]-1;
		$ln1[$p1][1] = $ln1[$p1][1] +1;
		$ln1[$p2][1] = $ln1[$p2][1] +1;
		}
		
	/* Novo Número */
	$nr=1;
	for ($rp=0;$rp < count($ln1);$rp++)
		{ if ($ln1[$rp][1] > 0) { $ln1[$rp][2] = $nr; $nr++; } }

	/* Projete numeros */
	$sp = '';
	for ($rp=0;$rp < count($ln2);$rp++)
		{ $sp .= chr(13).'<BR>['.troca($ln2[$rp],' ',']['); }
	
	/* Realiza trocas */
	$sn = '';
	for ($rp=0;$rp < count($ln1);$rp++)
		{
		$na = trim($ln1[$rp][0]);
		$na1 = substr($na,strpos($na,'"'),strlen($na));
		$ln1[$rp][0] = $na1;
		$na2 = $ln1[$rp][2];
//		echo '<BR>'.$na.'('.$ln1[$rp][1].')'.'Trocas >>>'.($rp+1).' por '.$na2;
		$sp = troca($sp,'['.($rp+1).']',' '.$na2.' ');
		
		/* troca nomes */
		if ($ln1[$rp][1] > 0)
			{
			$ln1[$rp][0] = $ln1[$rp][2].' '.$ln1[$rp][0];
			$sn .= chr(13).'<BR>'.trim($ln1[$rp][0]);
			}
		}

	$sp = troca($sp,']',' ');
	$sp = troca($sp,'[',' ');
	$sp = troca($sp,'  ',' ');
	$sn = '*vertices '.$nr.chr(13).'<BR>'.$sn;
	
	echo $sn;
	echo chr(13).'<BR>'.'*Edges';
	echo $sp;
	
//	print_r($ln1);
	return($sx);
	}
	
?>
