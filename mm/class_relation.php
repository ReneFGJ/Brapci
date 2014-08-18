<?
$ms1 = "CONCEITOS RELACIONADOS";
$sql = "";
$sql = "select * from (
SELECT `crt_conceito` as te1 
FROM tci_conceito_relacionamento as t1 where crt_rela = 'TR' 
and (crt_termo = '".$dd[0]."' or crt_conceito = '".$dd[0]."')
union
SELECT `crt_termo` as te1 
FROM tci_conceito_relacionamento as t1 where crt_rela = 'TR' 
and (crt_termo = '".$dd[0]."'or crt_conceito = '".$dd[0]."')
) as tabela 
inner join tci_conceito_relacionamento on te1 = crt_conceito
inner join tci_keyword on  kw_codigo = crt_termo
where te1 <> '".$dd[0]."' and crt_rela = 'PD'";
$sql .= " order by kw_word_asc ";
$rlt = db_query($sql);
$sx = '';
while ($line = db_read($rlt))
	{
	$link = '<A HREF="concept.php?dd0='.$line['crt_conceito'].'">';
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
