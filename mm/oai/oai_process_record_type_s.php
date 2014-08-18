<?
	$edok = 0;
		/////////////////////////////////// sistema troca & por &amp;
		$fonte3 = troca($fonte,'&amp;','E');
		$fonte2 = substr($fonte3,0,strlen($fonte3)-9);
		
		$sql = "select * from brapci_edition ";
		$sql .= "where ((ed_ano = '".$edicao[2]."' ";
		$sql .= " and ed_vol = '".$edicao[0]."' ";
		$sql .= " and ed_nr = '".$edicao[1]."') or (ed_oai_issue like '%".trim(UpperCaseSql($fonte2))."%')) ";
		$sql .= " and ed_journal_id = '".$jid."' ";
		$rlt = db_query($sql);
		if (!($line = db_read($rlt)))
			{
				$msg = 'Edição não cadastrada no sistema ';
				$msg .= '<BR>';
				$msg .= '<a href="brapci_brapci_journals_issue.php">Cadastre aqui!</a>';
				$msg .= '<BR>v. '.$edicao[0].', n. '.$edicao[1].', '.$edicao[2];
			} else {
				$edok = 1;
			}	
			
		if ((strlen($fonte) > 5) and ($edok == 0))
			{
			$sql = "select * from brapci_edition ";
			$sql .= "where ed_oai_issue = '".trim(UpperCaseSql($fonte3))."' ";
			$sql .= " and ed_journal_id = '".$jid."' ";
			$sql .= " order by ed_vol desc, ed_nr desc ";
			echo $sql;
			$rlt = db_query($sql);
			if (!($line = db_read($rlt)))
				{
					$msg .= '<BR>ISSUE OAI <B>'.trim(UpperCaseSql($fonte3)).'</B>';
				} else {
					$edok = 2;
				}			
			} else {
				$msg .= '<BR>não localizado ISSUE ';
			}
///////////////////////////////////////////////////////////////////////////		
	if ($edok == 1)
			{
				$edicao_artigo = $line['ed_codigo'];
				require("oai_process_record_article.php");
				require("oai_process_record_author.php");
				require("oai_process_record_suport.php");
				require("oai_process_record_subject.php");
			} else {
				echo '<BR>';
				echo msg_alert(date("d/m/Y H:i")."<BR>".$msg);
				echo '<BR>';
				exit;
			}
			
?>