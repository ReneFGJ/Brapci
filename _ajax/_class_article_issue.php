<?php
class ajax_class
	{
		function refresh($id)
			{
				global $dd,$acao;
				$ar = new article;
				$ar->le($id);
				$dd[3] = $ar->line['ar_edition'];
				
				$issue = $ar->issue;
				$is = new issue;
				
				$sx .= $is->issue_legend($ar->issue);
				$form = new form;
				$form->frame = $dd[90].'_main';
				$form->ajax = 1;
				
				$sql = "select * from brapci_edition 
							where ed_journal_id = '".$ar->journal_id."' 
							and ed_status <> 'X'
							order by ed_ano desc, ed_vol desc, ed_nr desc
							";
				$rlt = db_query($sql);
				$opa = ' : ';
				while ($line = db_read($rlt))
				{
					$vol = 'v. '.trim($line['ed_vol']);
					$vol .= ', n. '.trim($line['ed_nr']);
					$vol .= ', '.trim($line['ed_ano']);
					$opa .= '&'.trim($line['ed_codigo']).':'.trim($vol);
				}
				$cp = array();
				$tabela = "";
				array_push($cp,array('$H8','',$id,False,False));
				array_push($cp,array('$HV','',$dd[1],True,True));
				array_push($cp,array('$HV','',$dd[2],True,True));
				array_push($cp,array('$O '.$opa,'',$dd[2],True,True));
				$tela = $form->editar($cp,$tabela);
				
				$sx = $tela;
				return($sx);
			}
	}

	