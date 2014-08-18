<BR><BR><HR><BR><BR>
<form method="post" action="brapci_brapci_ontologia_ver.php">
<input type="hidden" name="dd0" value="<?=$dd[0];?>">

<input type="text" name="dd1" size="100" maxlength="100" value="<?=$dd[1];?>">
<input type="submit" name="acao" value="procurar >>">
</form>

<?
$tema = "tci";
if (strlen($dd[1]) > 0)
	{
	$sql = "select * from tci_keyword where ";
	$sql .= " kw_word_asc like '%".uppercasesql($dd[1])."%' ";
	$sql .= " or kw_word like '%".uppercasesql($dd[1])."%' ";
	echo $sql;
	$rlt = db_query($sql);
	echo '<TT><UL>';	
	while ($line = db_read($rlt))
		{
		$link = '<a href="brapci_brapci_ontologia_ver.php?dd0='.$dd[0].'&dd2='.$line['id_kw'].'">';
		$termo = trim($line['kw_word']);
		echo '<LI>'.$link.$termo.'</A></LI>';
		}
	echo '</UL>';
	}

if (strlen($dd[2]) > 0)
	{
	$sql = "select * from tci_keyword where id_kw = ".$dd[2];
	$rlt = db_query($sql);
	if ($line = db_read($rlt))
		{
		echo '<TT><B>'.$line['kw_word'].'</B>';
		echo '&nbsp;'.strzero($line['id_kw'],7);
		$termoc = strzero($line['id_kw'],7);
		if (strlen($dd[3]) ==0)
			{
			?>
			<form method="post" action="brapci_brapci_ontologia_ver.php">
			<input type="hidden" name="dd0" value="<?=$dd[0];?>">
			<input type="hidden" name="dd2" value="<?=$dd[2];?>">
			<select name="dd3" size="1">
				<option value="">::Tipo de relação::</option>
				<option value="TE">Termo específico</option>
			</select>
			<input type="submit" name="acao" value="procurar >>">
			</form>
			<?
			} else {
				$termo = lowercasesql($line['kw_word']);
				$termo = '¢'.troca($termo,' ','');
				echo '['.$termo.']';
				
				$sql = "select * from tci_conceito where cnt_descricao = '".$termo."' ";
				$trlt = db_query($sql);
				if ($tline = db_read($trlt))
					{
					} else {
						$isql = "insert into tci_conceito ";
						$isql .= "(cnt_descricao,cnt_codigo) values ('".$termo."','');";
						$irlt = db_query($isql);
						
						$isql = "update tci_conceito set cnt_codigo = lpad(id_cnt,7,0) where length(cnt_codigo) = 0 ";
						$irlt = db_query($isql);
						
						$trlt = db_query($sql);
						$tline = db_read($trlt);
					}
				$codigo = $tline['cnt_codigo'];
				//////////////////////////////////// ASSOCIA TERMO PREFERENCIAL
				$conceito = strzero($dd[0],7);
								
				$sql = "select * from tci_conceito_relacionamento where crt_conceito = '".$codigo."' and crt_termo = '".$termoc."' ";
				$sql .= " and crt_rela = 'PD' and crt_tema = '$tema' ";
				$rltx = db_query($sql);
				if (!($xline = db_read($rltx)))
					{
						$sql = "insert into tci_conceito_relacionamento ";
						$sql .= "(crt_conceito,crt_termo,crt_rela,crt_tema) ";
						$sql .= " values ";
						$sql .= "('$codigo','$termoc','PD','$tema');";
						$xrlt = db_query($sql);
					}
				

				$subconceito = $codigo;
				$sql = "select * from tci_conceito_relacionamento where crt_conceito = '$conceito' and crt_termo = '$codigo'";
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
					echo '<BR>Já existe uma relação';
					} else {
						$sql = "insert into tci_conceito_relacionamento ";
						$sql .= "(crt_conceito,crt_termo,crt_rela,crt_tema) ";
						$sql .= " values ";
						$sql .= "('$conceito','$subconceito','".$dd[3]."','$tema');";
						$rlt = db_query($sql);
						redirecina('brapci_brapci_ontologia_ver.php?dd0='.$dd[0]);
					}
			}
		}
	}	