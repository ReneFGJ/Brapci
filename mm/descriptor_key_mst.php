<?
$sql = "select * from tci_conceito_relacionamento where crt_termo = '".$dd[0]."' ";
$rlt = db_query($sql);

if ($line = db_read($rlt))
	{
		redirecina('http://www.brapci.ufpr.br/mm/concept.php?dd0='.$line['crt_conceito']);
	} else {
	/////////////////////////////////////////////////////////////////////////////////////////////// KEY
		$sql = "select * from tci_thema";
		$rlt = db_query($sql);
		
		while ($line = db_read($rlt))
			{
			$op .= '<option value="'.$line['tci_codigo'].'">'.$line['tci_descricao'].'</option>';
			}
	
		$sql = "select * from tci_keyword where kw_codigo = '".$dd[0]."'";
		$rlt = db_query($sql);
		$line = db_read($rlt);

		echo '<BR>';
		echo '>>> Conceito não localizado para este termo ';
		$dd[3] = trim($line['kw_word']);
		$dd[5] = trim($line['kw_idioma']);
		
		?>
		<form method="post" action="concept_add.php">
		<input type="hidden" name="dd3" value="<?=$dd[3];?>">
		<input type="hidden" name="dd4" value="<?=$dd[4];?>">
		<input type="hidden" name="dd5" value="<?=$dd[5];?>">
		<BR>>>>><B><?=$dd[3];?></B>
		<BR>>>>><B><?=$dd[5];?></B>
		<BR>
		<select name="dd6" size="1">
		<?=$op;?>
		</select>
		<BR><input type="submit" name="acao" value="criar conceito >>>">
		</form>
		<HR>
		<?
	/////////////////////////////////////////////////////////////////////////////////////////////// USE
		if (strlen($dd[10]) > 0)
			{
			$sql = "SELECT * FROM `tci_conceito_relacionamento` ";
			$sql .= "inner join tci_keyword on `crt_termo` = kw_codigo ";
			$sql .= "where kw_word_asc like '%".UpperCaseSql($dd[10])."%' ";
			$sql .= " and crt_rela = 'PD' ";
			$rlt = db_query($sql);
			$opa = '';
			while ($line = db_read($rlt))
				{
				$opa .= '<option value="'.$line['crt_conceito'].'">'.$line['kw_word'].'</option>';
				}
			}
	
	?>
		<H2>USE</H2>
		<form method="post" action="descriptor_key.php">
		<input type="hidden" name="dd0" value="<?=$dd[0];?>">
		<input type="hidden" name="dd1" value="<?=$dd[1];?>">
		<input type="hidden" name="dd2" value="<?=$dd[2];?>">
		<input type="hidden" name="dd3" value="<?=$dd[3];?>">
		<input type="hidden" name="dd4" value="<?=$dd[4];?>">
		<input type="hidden" name="dd5" value="<?=$dd[5];?>">
		<select name="dd6" size="1">
		<?=$op;?>
		</select>
		<BR>		
		Filtro de palavras
		<BR><input type="text" name="dd10" value="<?=$dd[10];?>" size="50" maxlength="100">
		<BR><select name="dd12" size="1">
		<option value="UP">Usado para</option>
		<option value="HD">Usado para (oculto)</option>
		</select>
		<BR><select name="dd11" size="1">
		<?=$opa;?>
		</select>
		<BR><input type="submit" name="acao" value="Usado para >>>">
		</form>
		<HR>
		<?
		if (strlen($dd[11]) > 0)
			{		
			$sql = "insert into  tci_conceito_relacionamento ";
			$sql .= "(crt_conceito,crt_termo,crt_rela,crt_tema) ";
			$sql .= " values ";
			$sql .= "('".$dd[11]."','".$dd[0]."','".$dd[12]."','".$dd[6]."') ";
			$rlt = db_query($sql);
			
			redirecina('http://www.brapci.ufpr.br/mm/concept.php?dd0='.$dd[11]);
			}
	}
?>