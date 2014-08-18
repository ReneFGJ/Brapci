<?
require("db.php");
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');

$tabela = "mar_works";

$sql = "select * from ".$tabela." where id_m = ".$dd[0];
$rlt = db_query($sql);

$line = db_read($rlt);

$ref = $line['m_ref'];
if (strpos($ref,'Dispon') > 0)
	{
	$ref = substr($ref,0,strpos($ref,'Dispon')); 
	$sql = "update ".$tabela." set m_ref = '".$ref."', m_status = 'B' where id_m = ".$dd[0];
	$rlt = db_query($sql);
	?>
	<script>window.close();</script>
	<?
	} else {
		echo $ref;
	}

?>