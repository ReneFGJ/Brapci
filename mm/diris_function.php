<?
/**
 * Sistema de Importa��o de PEsquisadores
 * 
 *
 *
*/ 

require($include.'sisdoc_autor.php');

/**
 * Fun��o que busca autor e se n�o existe cadastra
 *
 *
*/
function diris_autore($nome_autor)
	{
	$nome_autor = nbr_autor($nome_autor,1);
	
	$sql = "select * from diris where dis_nome = '".$nome_autor."'";
	$rlt = db_query($sql);
	
	if (!($line = db_read($rlt)))
		{
		echo '<HR>';
		echo 'Autor n�o encontrado no DiR-IS<BR>';
		echo $nome_autor;
		echo '<HR>';
		$sql = "insert into diris ";
		$sql .= " (dis_nome,dis_nome_as,dis_update,dis_rights) ";
		$sql .= " values ";
		$sql .= " ('".$nome_autor."','".UpperCaseSql($nome_autor)."',".date("Ymd").",'Brapci - DiR-IS');";
		$rlt = db_query($sql);
		}
	}
?>