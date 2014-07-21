<?
if (strlen($include) == 0) { exit; }
function word_busca($word,$cpo)
	{
	$ps = '0001';
	///////// Phase I  - Corte
	$wdr1 = word_ph1($word,$cpo);	
	///////// Phase II - Inserção
	$wdr2 = word_ph2($word,$cpo);	
	///////// Phase III- Substituição
	$wdr3 = word_ph3($word,$cpo);	
	///////// Phase IV - Troca de posição
	$wdr4 = word_ph4($word,$cpo);	

	return($wdr1 . ' or '. $wdr2.' or '.$wdr3.' or '.$wdr4);
	}

////////////////////// TROCA
function word_ph4($wd,$cp)
	{
	$wcut = array();
	$wdp = word_pt($wd);
	$rs4 = '';
	for ($w1 = 0;$w1 < count($wdp);$w1++)
		{
		$wcutr = array();
		$word = $wdp[$w1];
		$rs1 = '';
		for ($w2=0;$w2 < strlen($word);$w2++)
				{
				for ($w3=0;$w3 < strlen($word);$w3++)
					{
					if (strlen($rs4) > 0) { $rs4 .= ' or '; }
					$rs4 .= ' ('.$cp." = '";
					$rs4 .= substr($word,0,$w2);
					$rs4 .= substr($word,$w3,1);
					$rs4 .= substr($word,$w2+1,strlen($word));
					$rs4 .= "'";
					$rs4 .= ') ';
					}
				}
		}
		return($rs4);
	}
////////////////////// SUBSTITUICAO
function word_ph3($wd,$cp)
	{
	$wcut = array();
	$wdp = word_pt($wd);
	$ALF = 'ABCDEFGHIJKLMNOPQRSTUVWXYZÇ';
	$rs = '';
	$rs2 = '';
	for ($w1 = 0;$w1 < count($wdp);$w1++)
		{
		$wcutr = array();
		$word = $wdp[$w1];
		$rs1 = '';
		for ($w2=0;$w2 < strlen($word);$w2++)
			{
			for ($w3 = 0;$w3 < strlen($ALF);$w3++)
				{
				if (strlen($rs1) > 0) { $rs1 .= ' or '; }
				$rs1 .= ' ('.$cp." = '";
				$rs1 .= substr($word,0,$w2);
				$rs1 .= substr($ALF,$w3,1);
				$rs1 .= substr($word,$w2+1,strlen($word));
				$rs1 .= "'";
				$rs1 .= ') ';
				}
			}	
		if (strlen($rs1) > 0) 
			{
			if (strlen($rs2) > 0) { $rs2 .= ' and '; }
			$rs2 .= '('.$rs1.')';
			}
		}
	return($rs2);
	}
////////////////////// inserção
function word_ph2($wd,$cp)
	{
	$wcut = array();
	$wdp = word_pt($wd);
	$ALF = 'ABCDEFGHIJKLMNOPQRSTUVWXYZÇ';
	$rs = '';
	$rs2 = '';
	for ($w1 = 0;$w1 < count($wdp);$w1++)
		{
		$wcutr = array();
		$word = $wdp[$w1];
		$rs1 = '';
		for ($w2=0;$w2 < strlen($word);$w2++)
			{
			for ($w3 = 0;$w3 < strlen($ALF);$w3++)
				{
				if (strlen($rs1) > 0) { $rs1 .= ' or '; }
				$rs1 .= ' ('.$cp." = '";
				$rs1 .= substr($word,0,$w2);
				$rs1 .= substr($ALF,$w3,1);
				$rs1 .= substr($word,$w2,strlen($word));
				$rs1 .= "'";
				$rs1 .= ') ';
				}
			}	
		if (strlen($rs1) > 0) 
			{
			if (strlen($rs2) > 0) { $rs2 .= ' and '; }
			$rs2 .= '('.$rs1.')';
			}
		}
	return($rs2);
	}
////////////////////// CORTE
function word_ph1($wd,$cp)
	{
	$wcut = array();
	$wdp = word_pt($wd);
	$rs = '';
	$rsf = '';
	for ($w1 = 0;$w1 < count($wdp);$w1++)
		{
		$wcutr = array();
		for ($w2=0;$w2 < strlen($wdp[$w1]);$w2++)
			{
			$wd = substr($wdp[$w1],0,$w2);
			$wd .= substr($wdp[$w1],$w2+1,strlen($wdp[$w1]));
			array_push($wcutr,$wd);
			}
		if (count($wcutr) > 0)
			{
			echo $wcutr[$w3].' ';
			if (strlen($rs) > 0) { $rs .= 'and '; }
			$rs .= '(';
			$rs2 = '';
			for ($w3=0;$w3 < count($wcutr);$w3++)
				{
				if (strlen($rs2) > 0) { $rs2 .= ' or '; }
				$rs2 .= '('.$cp." like '%".$wcutr[$w3]."%') ";
				}
			$rs .= $rs2;
			$rs .= ') ';
			}
			if ($w1 > 0) { $rsf .= ' and '; }
			$rsf = $rs;
		}
	return($rsf);
	}	

function word_pt($wdd)
	{
	$wdd = UpperCaseSQL($wdd).' ';
	$wv=array();
	$ws='';
	for ($k=0;$k < strlen($wdd);$k++)
		{
		$ch = substr($wdd,$k,1);
		if ($ch == ' ')
			{
			if (strlen(trim($ws)) > 0)
				{ array_push($wv,$ws); $ws = ''; }
			} else { $ws .= $ch; }
		}
	return($wv);
	}
?>	
