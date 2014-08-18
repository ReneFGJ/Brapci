<?
    if(($fl = fopen($flink,'r')) === FALSE) {  die('Failed to open file for Read!<BR>'); $ok = 0; }
	if ($ok == 1)
		{
		$fs = $uploaddir.'/teste.pdf';
		echo '<BR><font color="green">Salvando temporário'.$fs.'</font>';
		$fx = fopen($fs,'w');
		
		$link = array();
		$s = '';
		$t = 1;
		$rl = 0;
		$type = "";
		while ($t > 0)
			{
			$sx = fread($fl,512);
			fwrite($fx,$sx);
			$s .= $sx;
			if ($rl == 0) { $type = substr($sx,0,10); }
			$rl++;
			$t = strlen($sx);
			}
		fclose($fl);
		fclose($fx);
		
		$type = substr($type,1,3); 
		echo '<BR><font color="green">Leitura do tipo do temporário ['.$type.'] </font>';
		}

	if ($type == 'PDF')
		{
		diretorio_checa($uploaddir);
		diretorio_checa($uploaddir.'/'.date("Y"));
		diretorio_checa($uploaddir.'/'.date("Y")."/".date("m"));
	
		$uploaddir .= '/'.date("Y").'/'.date("m").'/';
		$local = '/'.date("Y").'/'.date("m").'/';
		
		$filename="automatico_".date("YmdHi").".pdf";
		$chave = UpperCaseSQL(substr(md5($chave.$article),0,8));
		$ver = 1;
		$xfilename = $article.'-'.strzero($ver,2).'-'.$chave.'-'.$filename;
		
		while (file_exists($uploaddir.$xfilename)) 
			{
			$ver++;
			$xfilename = $article.'-'.strzero($ver,2).'-'.$chave.'-'.$filename;
			}
		echo '<TT><HR>';
		$local .= $xfilename;
		$xfilename = $uploaddir.$xfilename;
		echo '<font color="#ff8040">Movendo<BR><B>'.$fs.'</B><BR>';
		echo 'para <B>'.$xfilename.'</B>';
		echo '</font>';
		if (stream_copy($fs, $xfilename))
			{	
			$sql = "update brapci_article_suporte set ";
			$sql .= " bs_type = '".$type."', ";
			$sql .= " bs_adress = '".$local."', ";
			$sql .= " bs_update = ".date("Ymd")." ";
			$sql .= " where id_bs = ".$id;
			echo '<BR><BR><font color="green"><B>Coleta finalizada com sucesso!</B></font>';
			$xxx = db_query($sql);
			} else {
				echo '<BR><BR>Erro ao copiar';
			}
		}
?>