<?
$ms1 = "CONCEITOS ESPECÍFICOS";
$sql = "SELECT *, t1.crt_rela as tp_codigo  ";
$sql .= "FROM tci_conceito_relacionamento AS t1 ";
$sql .= "INNER JOIN tci_conceito_relacionamento AS t2 ON t1.crt_termo = t2.crt_conceito ";
$sql .= "INNER JOIN tci_keyword ON t2.crt_termo = kw_codigo ";
$sql .= "WHERE t1.crt_conceito = '".$dd[0]."' ";
$sql .= "AND t1.crt_rela = 'TE' ";
$sql .= "AND t2.crt_rela = 'PD' ";
//$sql .= " and kw_idioma = '".$idioma."' ";
$sql .= " order by kw_word_asc ";

$rlt = db_query($sql);
$sx = '';
while ($line = db_read($rlt))
	{
	$tp = $line['tp_codigo'];
	$link = '<A HREF="'.$nlink.'?dd0='.$line['crt_conceito'].'">';
	if ($tp == 'TE') { array_push($tte,array($link.trim($line['kw_word']).'</A>',$line['kw_idioma'])); }	
	$sx .= '<LI>';
	$sx .= $link;
	$sx .= trim($line['kw_word']);
	$sx .= '</A>';
	$sx .= '</LI>';
	}

if (strlen($sx) > 0) 
	{
	$sx .= '<H3>'.$ms1.'</H3>';	
	$sx .= '<DIV align="justify">';
	$sx .= $nota;	
	$sx .= '</DIV>';
	}
	
if (strlen($img_ext) == 0) { echo $sx; }	
?>
