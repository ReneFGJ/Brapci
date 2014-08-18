<?
$ms1 = "NOTAS";
$sql = "SELECT * ";
$sql .= "FROM tci_note ";
$sql .= " where tn_codigo = '".$dd[0]."' ";
$sql .= " and tn_idioma = '".$idioma."' ";

$rlt = db_query($sql);
$sx = '';
while ($line = db_read($rlt))
	{
	$nota .= trim($line['tn_note']);
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
