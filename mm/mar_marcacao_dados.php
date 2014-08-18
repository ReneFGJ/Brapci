<BR><BR>
<table width="90%" border="1" cellpadding="6" cellspacing="0" align="center">
<TR valign="top">
<TD rowspan="11" width="225"><img src="img/logo_cited.png" width="215" height="138" alt="" border="0">
<center><?
			$work = $line['m_work'];
			$link = '<A href="http://www.brapci.ufpr.br/mm/brapci_article_select.php?dd1='.$work.'" target="new">';
			echo '<BR><BR><BR>';
			echo $link.'Editar lista de referências</a>';
			
$link = '<A HREF="#" onclick="newxy2('.chr(39).'mar_marcacao_editar.php?dd0='.$line['id_m'].chr(39).',680,250);">[EDITAR]</A>';
			
?>
<br><br><br>
<?=$complement;?>
</center>
</TD>
<TD colspan="2" height="80"><TT><?=$line['m_ref'];?>
<BR><?=$link;?>
</TD></TR>
<TR><TD width="5%" align="right"><TT>Ano:<TD><TT><B><?=$line['m_ano'];?></TD>
<TR valign="top"><TD><TT>Tipo</TD>
				 <TD><TT>&nbsp;<B><?=trim($line['m_tipo']);?></TD>
<TR valign="top"><TD><TT>Editor(a):</TD>
				 <TD><TT>&nbsp;<B><?=$line['m_journal'];?></TD>
<TR valign="top"><TD rowspan="6" colspan="2"><TT>