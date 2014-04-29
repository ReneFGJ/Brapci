<?php
class resume
	{
		function resume_type($tp='')
			{
				$sql = "select * from ";
				$sx = '<table>';
				$sx .= '<TR>';
				$sx . '</table>';
				return($sx);
			}
		function resume()
			{
				$sql = "select count(*) as total, jnl_tipo, jtp_descricao  
						from brapci_journal
						inner join brapci_journal_tipo on jnl_tipo = jtp_codigo
						and jnl_status <> 'X'
						group by jnl_tipo, jtp_descricao ";
				$rlt = db_query($sql);
				$ar1 = array();
				$ar2 = array();
				$ar3 = array();
				while ($line = db_read($rlt))
					{
						array_push($ar1,$line['jnl_tipo']);
						array_push($ar2,$line['total']);
						array_push($ar3,$line['jtp_descricao']);
					}
				if (count($ar1)==0) { return(''); }
				$sx .= '<table class="tabela00" width=100%>';
				$s1='<TR>'; $s2='<TR>'; $s3='<TR>';
				$wd = round(100/count($ar1));
				for ($r=0;$r < count($ar1);$r++)
					{
						$link = '<A href="publications.php?dd0='.$ar1[$r].'">';
						$s1 .= '<TD width="'.$wd.'" align="center">
								'.$link.'
								<img src="../img/icone_pub_type_'.$ar1[$r].'.png" height="50" border=0>
								</A>';
						$s2 .= '<TD align="center" class="lt4">'.$ar2[$r];
						$s3 .= '<TD align="center" class="lt0">'.$ar3[$r];						
					}
				$sx .= $s1.$s2.$s3;
				$sx .= '</table>';
				return($sx);
			}	
	}
?>
