<?
$sm = '';
//if ($user_id == '00001')
	{
	$link3 = '<A HREF="brapci_marcacao_editar.php?dd0='.$dd[0].'" class="lt2">Inserir referências</A>';
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	$link2 = '<span onclick="newxy2('.chr(39).'mar_marcacao_editar.php?dd3='.$dd[0].chr(39).',600,200);"><IMG src="img/icone_editar.gif"></span>';
	echo $link2.'novo</A>';
	
	$link4 = '<span onclick="newxy2('.chr(39).'mar_marcacao_excluir.php?dd3='.$dd[0].chr(39).',600,200);"><IMG src="img/icone_editar.gif"></span>';
	echo ' | ';
	echo $link4.'excluir tudo</A>';
	
	echo '<fieldset><legend><font class="lt3">Linguagem de marcação (citações)</legend>';
	echo '<font class="lt2">';
	
	$sql = "select * from mar_works ";
	$sql .= " where (m_work = '".$dd[0]."' ";
	$sql .= " or m_work = '".round($dd[0])."') ";
	$sql .= " and m_status <> 'X' ";
//	$sql .= " order by m_ref ";

	$rlt = db_query($sql);
	
	$sr = '';
	while ($line = db_read($rlt))
		{
		//////////////////// BDOI
		$bdoi = $line["m_bdoi"];
		
		/////////////////// 
		if (substr($bdoi,5,1) == '-')
			{ $bdoi = '(BDOI: '.$bdoi.')'; }
			
		if ((substr($bdoi,5,1) == '-') and (strlen($bdoi) > 0))
			{ $bdoi = '(DOI: '.$bdoi.')'; }
		
		$sta = $line['m_status'];
		$link = '<span onclick="newxy2('.chr(39).'mar_marcacao_editar.php?dd0='.$line['id_m'].chr(39).',600,200);"><IMG src="img/icone_editar.gif"></span>';
		$rf = $line['m_ref'];
		$rf = troca($rf,'<','&lt;');
		$rf = troca($rf,'>','&gt;');
		if ($sta == 'Z') { $sr .= '<font color="red">'; } else { $sr .= '<font color="black">'; }
		$sr .= $rf.' '.'<font color="blue">'.$bdoi.'</font>';
		$sr .= '</font>';
		$sr .= $link;
		$sr .= '<BR><BR>';
		}
//	if (strlen($sr) ==0)
		{
		echo $link3.'<BR><BR>';
//		} else {
		echo $sr;
		}
	echo '</fieldset>';
	echo '</table>';
	}
?>
<CENTER>