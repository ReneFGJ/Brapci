<?
/*** Modelo ****/
require("cab.php");
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	$sql = "delete from pibic_bolsa_contempladas where id_pb = 40 ";
	$rlt = db_query($sql);
	
	/* Dados da Classe */
	$sql = "select * from (
			select pb_protocolo, count(*) as total 
				from pibic_bolsa_contempladas
				
				 group by pb_protocolo
			) as tabela 
			where total >= 2
	";
	$rlt = db_query($sql);
	while ($line = db_read($rlt))
		{
			print_r($line);
			echo '<HR>';
		}
	
	
require("../foot.php");		
?> 