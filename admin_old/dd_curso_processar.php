<?php
require("cab.php");
require('../_class/_class_discentes.php');
$dis = new discentes;
require('../_class/_class_curso.php');
$cur = new curso;
$tab_max = "98%";
?>
<TABLE width="98%" align="center" border="0">
<TR>
<?

$sql = "select * from pibic_aluno where (pa_curso_cod = '' or pa_curso_cod isnull) limit 1";
echo $sql;
$rlt = db_query($sql);
while ($line = db_read($rlt))
{
	$curso = trim($line['pa_curso']);
	echo '==>'.$cur->curso_busca($curso);
	print_r($line);
	echo '<HR>';
	print_r($cur);
	$curso_cod = trim($cur->curso_codigo);
	if (strlen($curso_cod))
	{
		$sql = "update pibic_aluno set pa_curso_cod = '".$curso_cod."' 
			where pa_curso = '".$line['pa_curso']."' "; 
		$rlt = db_query($sql);
		echo '<meta HTTP-EQUIV = "Refresh" CONTENT = "1; URL = '.page().'">';		
	}
	echo '<HR>'.$sql;
}
?>
</TABLE>
<? require("../foot.php");	?>