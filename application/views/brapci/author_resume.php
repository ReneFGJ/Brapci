<table width="100%" align="center" border=0 id="tabela_author" class="tabela01">
	<tr><TD colspan=7><span class="lt4">Author</span></td></tr>
	
	<!-- dados pessoais -->
	<TR class="lt0">
					<Td width="5%"></Th>
					<Td width="10%"></Th>
					<td width="50%"></th>
					<Td width="10%"></Th>
					<td width="20%"></th>
					</tr>
					
	<tr valign="top"><td class="lt0" rowspan=10 width="100" height="30" >
		<img src="<?php echo $autor_foto; ?>" width="150"><BR>
			fonte: <A HREF="http://lattes.cnpq.br" target="_new" class="lt0 link">lattes.br</A>
		<TD align="right" class="lt0">name</td>				
		<TD class="lt2" colspan=1 ><?php echo $autor_nome; ?>
									(<?php echo $autor_titulacao; ?>)
									<?php echo $autor_lattes; ?></td>
		<TD align="right" class="lt0">nacionalidade</td>	
		<TD class="lt2"><?php echo $autor_nacionalidade; ?></td>			
		</tr>
		
	<TR valign="top">
		<TD align="right" class="lt0" width="10%" style="min-height: 200px;">outras forma citadas</td>		
		<TD class="lt2" colspan=1 ><?php echo $autor_nomes; ?></td>
		<TD align="right" class="lt0" width="10%">intituições</td>		
		<TD class="lt2" colspan=1 ><?php echo $autor_instituicoes; ?></td>
		</tr>
	<tr valign="top">
		<td align="right" colspan=4 class="lt0">Código: <?php echo $autor_codigo; ?></td>
	</tr>		
</table>
<?php echo $acao_editar; ?>
