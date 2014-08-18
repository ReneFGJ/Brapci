<TABLE width="<?=$tab_max;?>">
<TR><TD align="center">
<HR>
<?
$stc = "A";
if ($sta=='A')
	{
	$bb1 = "Enviar para Revisão";
	$bb2 = "Excluir trabalho";
		
	
	if ($acao == $bb1)
		{
		$sql = "update brapci_article set ar_status = 'B' where ar_journal_id = '".strzero($jid,7)."' and id_ar= '".$dd[0]."' ";
		$rlt = db_query($sql);
		$stb = 'B';
		require("ic_count_acao.php");
		redirecina($http_site.'article_edition.php');
		}

	if ($acao == $bb2)
		{
		$sql = "update brapci_article set ar_status = 'X', ar_journal_id = 'X".strzero(intval($jid),6)."' where ar_journal_id = '".strzero($jid,7)."' and id_ar= '".$dd[0]."' ";
		$rlt = db_query($sql);
		$stb = 'X';
		require("ic_count_acao.php");
		redirecina($http_site.'article_edition.php');
		}
	?>
	<CENTER>
	<form method="post">
	<input type="hidden" name="dd0" value="<?=$dd[0];?>">
	<input type="submit" name="acao" value="Enviar para Revisão" style="width:200px;height:50px;">
	<input type="submit" name="acao" value="Excluir trabalho" style="width:200px;height:50px;">
	</form>
	<?
	}
	
if ($sta=='B')
	{
	$bb1 = "Devolver para revisão";
	$bb2 = "Enviar para 2º revisão";
	
	if (strlen($acao) > 0)
		{
		if ($acao == $bb1) { $stm = "A"; }
		if ($acao == $bb2) { $stm = "C"; }
		
	require("ic_count_acao.php");
		
		if (strlen($stm) > 0)
			{
			$sql = "update brapci_article set ar_status = '".$stm."' where ar_journal_id = '".strzero($jid,7)."' and id_ar= '".$dd[0]."' ";
			$rlt = db_query($sql);
			$stb = $stm;
			require("ic_count_acao.php");
			redirecina($http_site.'article_edition.php');
			}
		}
	?>
	<CENTER>
	<form method="post">
	<input type="hidden" name="dd0" value="<?=$dd[0];?>">
	<input type="submit" name="acao" value="<?=$bb1;?>" style="width:200px;height:50px;">
	<input type="submit" name="acao" value="<?=$bb2;?>" style="width:200px;height:50px;">
	</form>
	<?
	}	
	
if ($sta=='C')
	{
	$bb1 = "Devolver para revisão";
	$bb2 = "Concluir revisão";
	
	if (strlen($acao) > 0)
		{
		if ($acao == $bb1) { $stm = "A"; }
		if ($acao == $bb2) { $stm = "D"; }
		
	require("ic_count_acao.php");
		
		if (strlen($stm) > 0)
			{
			$sql = "update brapci_article set ar_status = '".$stm."' where ar_journal_id = '".strzero($jid,7)."' and id_ar= '".$dd[0]."' ";
			$rlt = db_query($sql);
			$stb = $stm;
			require("ic_count_acao.php");
			redirecina($http_site.'article_edition.php');
			}
		}
	?>
	<CENTER>
	<form method="post">
	<input type="hidden" name="dd0" value="<?=$dd[0];?>">
	<input type="submit" name="acao" value="<?=$bb1;?>" style="width:200px;height:50px;">
	<input type="submit" name="acao" value="<?=$bb2;?>" style="width:200px;height:50px;">
	</form>
	<?
	}	
	
if ($sta=='D')
	{
	$bb1 = "Devolver para revisão";
	
	if (strlen($acao) > 0)
		{
		if ($acao == $bb1) { $stm = "C"; }
		
	require("ic_count_acao.php");
		
		if (strlen($stm) > 0)
			{
			$sql = "update brapci_article set ar_status = '".$stm."' where ar_journal_id = '".strzero($jid,7)."' and id_ar= '".$dd[0]."' ";
			$rlt = db_query($sql);
			$stb = $stm;
			require("ic_count_acao.php");
			redirecina($http_site.'article_edition.php');
			}
		}
	?>
	<CENTER>
	<form method="post">
	<input type="hidden" name="dd0" value="<?=$dd[0];?>">
	<input type="submit" name="acao" value="<?=$bb1;?>" style="width:200px;height:50px;">
	</form>
	<?
	}			
?>
</TD></TR>
</TABLE>