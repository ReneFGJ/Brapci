<div class="container">
	<div class="row">
		<div class="col-md-12">&nbsp;</div>
	</div>
	<div class="row">
		<div class="col-md-2">
			<img src="<?php echo $autor_foto; ?>" width="150"><BR>
			fonte: <A HREF="http://lattes.cnpq.br" target="_new" class="small link">lattes.br</A>
		</div>
		<div class="col-md-8">
			<h3><?php echo $autor_nome; ?></h3>
			<table class="table">
	<TR valign="top">
		<TD align="right" class="small" width="10%" style="min-height: 200px;">outras forma citadas</td>		
		<TD class="lt2" colspan=1 ><?php echo $autor_nomes; ?></td>
		<TD align="right" class="small" width="10%">instituições</td>		
		<TD class="lt2" colspan=1 ><?php echo $autor_instituicoes; ?></td>
		</tr>
	<tr valign="top">
		<td align="left" colspan=2 class="small">Lattes: <?php echo $autor_lattes; ?></td>
		<td align="right" colspan=2 class="small">Código: <?php echo $autor_codigo; ?></td>
	</tr>
			</table>
		</div>
		<div class="col-md-2">
			<span class="small">nacionalidade</span><br>
			<span class="big"><?php echo $autor_nacionalidade; ?></span>
		</div>
	</div>
	
	
<?php echo $acao_editar; ?>
</div>
