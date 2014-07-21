<TABLE width="<?=$tab_max;?>" align="center" class="lt1"><TR><TD>
<?
	///////////////////////////// habilita busca por termos
		$sql = "select count(*) as total from autoridade where ";
		$cp_nome = 'au_nome_asc';
		$sql_1 .= buscatextual($dd[1]);
		$rlt = db_query($sql.$sql_1);
		if ($line = db_read($rlt))
			{
				$total = $line['total'];
				$sql = "select * from autoridade where ";
				$cp_nome = 'au_nome_asc';
				$rlt = db_query($sql.$sql_1);
			}

	/////////////// PHASE II
	if ((strlen($dd[1]) > 0) and ($total <= 1))
		{
		$rst = word_busca($dd[1],'au_nome_asc');
		$sql = "select * from autoridade where ";
		$sql .= $rst;
//		echo $rst;
		$rlt = db_query($sql);
		}
		$cdos = array();
		
	////////////////////// PHASE III
		{
		$bsc = array();
		$wh = '';
		while ($line = db_read($rlt))
			{
			$cod = $line['au_codigo'];
			$use = trim($line['au_use']);
			if (strlen($use) > 0) { $cod = $use; }
			if (strlen($wh) > 0) { $wh .= ' or '; }
			$wh .= " (aa_autoridade = '".$cod."') ";
//			echo $cod.' -> '.NBR_autor($line['te_termo_asc'],7);
			array_push($bsc,$cod);
			}
		}
	//////////////////////// PHASE IV
		////////////////////// ordem de aparecencia	
		$sql = "select count(*) as total, au_nome_asc, au_nome, au_codigo ";
		$sql .= " from acervo_autoridade ";
		$sql .= " inner join autoridade on au_codigo = aa_autoridade ";
		$sql .= " where ";
		$sql .= $wh;
		$sql .= " group by au_nome_asc, au_nome, au_codigo ";
		$sql .= " order by total desc, au_nome_asc ";
		$sql .= " limit 120 ";
		if (strlen($wh) > 0)
		{
			$rlt = db_query($sql);
			$sr = '';
			while ($line = db_read($rlt))
			{
			$link = '<A HREF="busca_resultado_autoridade.php?dd0='.trim($line['au_codigo']).'&dd1=2">';
			if (strlen($sr) > 0) { $sr .= '<BR>'; }
			$st = $line['total'];
			$sr .= $link;
			$sr .= trim($line['au_nome']);
			$sr .= '</A>';
			$sr .= ' ('.$st.')';
			}
			$sr = '<DIV class="lt2" align="left"><b>Autores Localizados (obras)</B><BR>'.$sr.'</DIV>';

		////////////////////// ordem de aparecencia	
		$sql = "select count(*) as total, au_nome_asc, au_nome, au_codigo ";
		$sql .= " from acervo_autoridade ";
		$sql .= " inner join autoridade on au_codigo = aa_autoridade ";
		$sql .= " where ";
		$sql .= $wh;
		$sql .= " group by au_nome_asc, au_nome, au_codigo ";
		$sql .= " order by au_nome ";
		$sql .= " limit 120 ";
		if (strlen($wh) > 0)
		{
			$rlt = db_query($sql);
			$so = '';
			while ($line = db_read($rlt))
			{
			$link = '<A HREF="busca_resultado_autoridade.php?dd0='.trim($line['au_codigo']).'&dd1=2">';
			if (strlen($so) > 0) { $so .= '<BR>'; }
			$st = $line['total'];
			$so .= $link;
			$so .= trim($line['au_nome']);
			$so .= '</A>';
			$so .= ' ('.$st.')';
			}
			$so = '<DIV class="lt2" align="left"><b>Autores Localizados (alfabética)</B><BR>'.$so.'</DIV>';

		echo '<TABLE width="100%"><TR valign="top"><TD>'.$sr.'</TD><TD>'.$so.'</TD></TR></TABLE>';
		}
		
		////////////////////// ordem alfabética
		} else { echo '<BR>Nome não localizado'; }
?>
</TD></TR></TABLE>