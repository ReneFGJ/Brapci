<script>
function jnl_updown(xax)
	{
	xyx = document.getElementById(xax);
	xqx = xyx.style.display;
	if (xqx != 'none') 
		{ xqx = 'none'; }
		else
		{ xqx = ''; }
	xyx.style.display = xqx;	
	}
</script>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>

<div id="jnl" style="height: auto; width: 100%;">
<table width="100%" class="lt1">
<TR><TD width="75"><IMG src="img/bt_down_mini.png" onclick="jnl_updown('jnldtlh');"></TD>
	<TD width="95%"><?=$link;?><font class="lt2"><B><?=$titu[0];?></A></B></font></TD>
</TR>
</table>
<div id="jnldtlh" style="display: none;" >
	<table width="100%" class="lt1" border="0">
	<TR><TD align="right">Edições</TD><TD><B><?=$line['jnl_edicoes'];?>
		<TD align="right">Artigos <TD><B><?=($line['jnl_artigos']);?></TD></TR>
	<TR><TD align="right" width="25%">ISSN <TD width="25%"><B><?=$line['jnl_issn_impresso'];?></B>
		<TD align="right" width="25%">eISSN <TD width="25%"><B><?=$line['jnl_issn_eletronico'];?></B></TD></TR>
	<TR><TD align="right">Vigente <TD><B><?=dsp_sn($line['jnl_vinc_vigente']);?>
		<TD align="right">Ano início <TD><B><?=($line['jnl_ano_inicio']);?></TD></TR>
	<TR><TD align="right">Localização</TD><TD><B><?=dsp_sn($line['jnl_cidade']);?>
		<TD align="right">Ano final <TD><B><?=($line['jnl_ano_final']);?></TD></TR>
	<TR><TD align="right">OAI-PMH</TD><TD colspan="1">
		<? if (strlen(trim($line['jnl_url_oai'])) > 0) { ?>
		<a href="brapci_brapci_journal_harvesting.php?dd0=<?=$dd[0];?>">[Harvesting OAI]</a>
		&nbsp;
		<a href="link_coletar.php?dd0=<?=$dd[0];?>">Coletar PDF's</a>
		<? } else {?>
			Não disponível
		<? } ?>
		<TD align="right">Qualis <?=$line['q_ano'];?> <TD><B><?=$line['q_qualis'];?></TD></TR>
	</TABLE>
</div>