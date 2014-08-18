<?
require("oai.php");

$sql = "select * from oai_cache where cache_status = 'A' ";
$sql .= " and (cache_journal = '".$jid."' or cache_journal = '".round($jid)."')";
$sql .= " order by id_cache desc ";
$zrlt = db_query($sql);
$coleta = 0;
$utf8 = 'não detectado';
if ($line = db_read($zrlt))
	{
	$identify = $line['cache_oai_id'];
	$id = $line['id_cache'];
	$s = $line['cache_content'];
		
	

	$titulo = oai_content($s,'dc:title');
	$titulo = trim(troca($titulo,'<b>',''));

	if (strpos($titulo,'<p>') > 0)
		{
		echo 'Sub--';
		$titulo_2 = substr($titulo,strpos($titulo,'<p>')+4);
		$titulo_2 = substr($titulo_2,0,strpos($titulo_2,'<'));
		$titulo = substr($titulo,0,strpos($titulo,'<'));
		}
	$resumo = oai_content($s,'dc:description');
	//////////////////////////////////
	
	$autor  = oai_string($s,'<dc:creator>','</dc:creator>');
	$fonte  = oai_content($s,'dc:source');
	$edicao = oai_edicao($fonte);
	$link   = oai_string($s,'<dc:identifier>','</dc:identifier>');
	$tipo   = troca(oai_content($s,'setSpec'),'&','e');
	$s1 = $s;
	$assunto = '';
	?>
	<div style="display: none;">
		<TT><?=$s;?>
	</div>
	<?
	while (strpos($s1,'<dc:subject>') > 0)
		{
		$s3 = substr($s1,strpos($s1,'<dc:subject>')+12,strlen($s1));
		$s3 = substr($s3,0,strpos($s3,'<'));
		$assunto   .= $s3 .';';
		$ter = '<dc:subject>';
		$s1 = substr($s1,strpos($s1,$ter)+strlen($ter),strlen($s1));
		}
	$assunto = trim($assunto);
	$assunto = troca($assunto,'.',';');	
	$assunto = troca($assunto,',',';').';';	
	while (substr($assunto,0,1) == ';')
		{
		$assunto = substr($assunto,1,strlen($assunto));
		}
	/////////////////////////////////////////////////
	$tp = oai_listsets_equal($tipo,$jid);
	echo '<BR>Palavras-Chave:'.$assunto;
	echo '<BR>Tipo: '.$tipo.' ['.$tp[1].','.$tp[2].','.$tp[3].']';
	if ($tp[0] == -1) 
		{
		$grava = 0;
		echo '<BR>-->'.$tp[4];
		}

	$grava = $tp[0];
///////////////////////////////////////////////////////////////
	echo '<BR>Título: <B>'.$titulo.'</B>';
	echo '<BR>Título 2 : <B>'.$titulo_2.'</B>';
	echo '<HR>';
	echo '<BR><div align="justify"><TT>Resumo: '.$resumo.'</TT></div>';
	echo '<BR>Fonte: <B>'.$fonte.'</B>';
//	echo '<BR>Tipo: '.$tp[3];
//	echo '<BR>Edição: '.$fonte;
	echo '<BR><B>Keyword</B>: '.$assunto;
//	print_r($autor);
	echo '<HR>';
//	exit;

///////////////////////////////////////////////////////// Processar Artigo
//////////////////////////////////////////////////////// Tipo Edição
	if (($grava > 0) and ($tp[3] == 'E'))
		{ require("oai_process_record_type_e.php"); }
		
//////////////////////////////////////////////////////// Tipo Seção
	if (($grava > 0) and ($tp[3] == 'S'))
		{ require("oai_process_record_type_s.php"); }

	if ((strlen($titulo) > 0) and ($grava >= 0))
		{
		$sql = "update oai_cache set cache_status = 'B' where id_cache = ".$id;
		$irlt = db_query($sql);
		//redirecina('brapci_brapci_journal_harvesting_proc.php?dd0='.$dd[0]);	
		} else {
		echo '<font color="red">Não gravado</font>'; 
		$err=1;
		}
	}
	
?>