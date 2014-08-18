<?
require("cab.php");

$sql = "select count(*) as total, crt_rela from tci_conceito_relacionamento group by crt_rela ";
$rlt = db_query($sql);

while ($line = db_read($rlt))
	{
	$tp = $line['crt_rela'];
	if ($tp == 'PD') { $rela = 'Conceitos'; }
	if ($tp == 'TE') { $rela = 'Termos Gerais'; }
	if ($tp == 'TR') { $rela = 'Termos Relacionados'; }
	
	echo $line['total'];
	echo ' '.$rela;
	echo '<BR>';
	}
?>
