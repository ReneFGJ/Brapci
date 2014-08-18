<?
/** Referências **/
echo '<h2>Referências</h2>';
$totr = 0;
if (strlen($op3) > 0)
	{
	require('db/db_pesquisador.php');
	$sql = "select * from artigos where ".$op3;
	$sql .= " order by ar_ano desc, Author_Analytic  ";
	$prlt = db_query($sql);
	echo '<UL>';
	$xano = 0;
	while ($line = db_read($prlt))
		{
		$totr++;
		$ref = '$autores. $titulo. <B>$publicacao</B>, $local, v. $vol, n. $num, $pag, [$data]';
		require('referencia_mst.php');
		if ($ano != $xano) 
			{
			echo '<LI><font class="lt3">'.$ano.'</font></LI>';
			$xano = $ano;
			}
		echo '<UL><LI>'.$ref.'</LI></UL>';
		}
	echo '</UL>';
	}
if (($totr == 0) and ($ban == 'bra'))
	{
	$sql = "update diris set dis_ativo = 0 where id_dis = ".$dd[0];
	$rlt = db_query($sql);
	echo 'Desativado por falta de produção';
	}
?>