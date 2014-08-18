<?
    if(($fl = fopen($flink,'r')) === FALSE) {  die('Failed to open file for Read!<BR>'); $ok = 0; }
	if ($ok == 1)
		{
		$link = array();
		$s = '';
		$t = 1;
		$rl = 0;
		$type = "";
		while ($t > 0)
			{
			$sx = fread($fl,512);
			$s .= $sx;
			if ($rl == 0) { $type = substr($sx,0,10); }
			$rl++;
			$t = strlen($sx);
			}
		fclose($fl);
		}
	$type = '';
	if (strpos($s,'<meta name="citation_pdf_url"') > 0) 
		{
		$type = 'URL';
		$xp = strpos($s,'<meta name="citation_pdf_url"');
		$link = substr($s,$xp,400);
		$link = substr($link,strpos($link,'content="')+9,400);
		$link = substr($link,0,strpos($link,'"'));
		$link = troca($link,'/view/','/download/');
		echo '<BR><font color="#ff8040"><B>Novo Link: &lt;'.$link.'&gt;</B></font><BR><BR>';
		$local = $link;
		}
	if ((strpos($s,'Texto Completo:') > 0) and ($type ==''))
		{
		$type = 'URL';
		$xp = strpos($s,'Texto Completo:');
		$link = substr($s,$xp,800);
		$xp = strpos($link,'http');
		$link = substr($link,$xp,800);

		$link = substr($link,0,strpos($link,'"'));
		$link = troca($link,'/view/','/download/');
		echo '<BR><font color="#ff8040"><B>Novo Link: &lt;'.$link.'&gt;</B></font><BR><BR>';
		$local = $link;
		}
	
	if ($type == 'URL')
		{
		$sql = "update brapci_article_suporte set ";
		$sql .= " bs_type = '".$type."', ";
		$sql .= " bs_adress = '".$local."', ";
		$sql .= " bs_status = '@', ";
		$sql .= " bs_update = ".date("Ymd")." ";
		$sql .= " where id_bs = ".$id;
//		echo $sql;
		$xxx = db_query($sql);
		}		
		echo '<BR>>>('.$type.') '.$link;
?>