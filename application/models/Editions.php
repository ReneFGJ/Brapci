<?php
class editions extends CI_model
	{
	var $tabela = 'brapci_edition';
	
	function editions($journal='')
		{
			$sql = "select * from ".$this->tabela." 
						where ed_journal_id = '$journal'
						order by ed_ano desc, ed_vol 
			";
			$sx = '<Table class="tabela00 lt2">';
			$query = $this->db->query($sql);
			$rlt = $query->result();
			
			$xano = '';
			$issue = 0;
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="'.base_url('journal/issue/'.$line['id_ed']).'" class="link">';
					$issue++;
					$ano = $line['ed_ano'];
					if ($xano != $ano)
						{
							$sx .= '<TR>';
							$sx .= '<TD>'.$ano;
							$xano = $ano;
						}
					$sx .= '<TD class="tabela01" width="110" align="left">';
					$sx .= $link;
					$sx .= 'v.'.$line['ed_vol'];
					$sx .= ', n.'.$line['ed_nr'];
				}
			$sx .= '<TR><TD colspan=10>Total de <B>'.$issue.'</B> edições';
			$sx .= '</table>';
			return($sx);
		}	
	}
?>
