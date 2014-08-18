<?
		$sql = "select * from brapci_edition ";
		$sql .= "where ed_codigo = '".$tp[2]."' ";
		$sql .= " and ed_journal_id = '".$jid."' ";
		$rlt = db_query($sql);
		if (!($line = db_read($rlt)))
			{
				$msg = 'Edição não cadastrada no sistema';
				$msg .= '<BR>';
				$msg .= '<a href="brapci_brapci_journals_issue.php">Cadastre aqui!</a>';
				$msg .= '<BR>v. '.$edicao[0].', n. '.$edicao[1].', '.$edicao[2];
				echo msg_alert($msg);
				echo '<BR>';
				exit;
			} else {
				$edicao_artigo = $line['ed_codigo'];
				require("oai_process_record_article.php");
				require("oai_process_record_author.php");
				require("oai_process_record_suport.php");
				require("oai_process_record_subject.php");
			}
?>