<?php
if (!isset($dd1)) { $dd1 = '';
}
if (!isset($dd2)) { $dd2 = '';
}
if (strlen($dd3)==0) { $dd3 = $anoi;
}
if (strlen($dd4)==0) { $dd4 = $anof;
}
if (!isset($dd5)) { $dd5 = '';
}
$chk = array('','','','','','');
if (strlen($dd2) > 0)
	{
		$chk[$dd2] = 'checked';
	}
?>
<!--- CSS Complement -->
<link rel="stylesheet" href="<?php echo base_url('css/style_brapci_search_form.css'); ?>">

<form id="formWrapper" method="get" style="min-width: 600px; width: 100%;">
	<div class="formFiled clearfix">
		<table width="100%" class="lt1 tabela00">
			<tr valign="top">
				<td><!-- Formulário de busca --> Informe o termo de busca
				<BR>
				<input type="text" name="dd1" required="" placeholder="" class="search" value="<?php echo $dd1; ?>" style="width: 100%;">
				<br>
				<div>
				<font class="lt1">
					<input type="radio" name="dd2" value="0" <?php echo $chk[0];?> >
					todos os campos&nbsp;&nbsp;&nbsp;
					<input type="radio" name="dd2" value="1" <?php echo $chk[1];?>>
					palavras-chave&nbsp;&nbsp;&nbsp;
					<input type="radio" name="dd2" value="2" <?php echo $chk[2];?>>
					nome dos autores&nbsp;&nbsp;&nbsp; </font>
				</div>					
				<br>
				<br>
				</td><td width="50">
				<input type="submit" class="btn submit" value="pesquisar">
				</td>
			</tr>
			<!-- input complementar -->
			<table width="100%" class="lt1 tabela00">
				<!-- Range -->
				<tr>
					<td style="min-width: 200px;">
					<nobr>
						<label for="amount">Recorte: </label>
						
						<?php echo form_field(array('$['.$ano_min.'-'.$ano_max.']U','','',false,True),$dd3,'dd3',0);?>
						-
						<?php echo form_field(array('$['.$ano_min.'-'.$ano_max.']U','','',false,True),$dd4,'dd4',0);?>
					</td>
					<td width="180">&nbsp;</td>
					<td><label for="amount">Database:</label>
					<select name="dd5">
						<option value="0">:: Todas as bases ::</option>
						<option value="1">Ciência da Informação(*)</option>
						<option value="2">Museologia(*)</option>
						<option value="3">Arquivologia(*)</option>
						<option value="4">Teses e Dissertações (CI)(*)</option>
						<option value="5">Anais de Eventos CI(*)</option>
						<option value="20">Resenhas de Livros(*)</option>
					</select> (*) Em implementação</td>
				</tr>
			</table>

	</div>
</form>
