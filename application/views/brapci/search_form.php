<?php
if (!isset($dd1)) { $dd1 = ''; }
if (!isset($dd3)) { $dd3 = ''; }
if (!isset($dd4)) { $dd4 = ''; }
if (!isset($dd5)) { $dd5 = ''; }
?>
<!--- CSS Complement -->
<link rel="stylesheet" href="<?php echo base_url('css/style_brapci_search_form.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('css/jquery-ui-1.7.1.custom.css'); ?>">

<!--- JS Complement -->
<script src="<?php echo base_url('js/jquery-ui.js'); ?>"></script>
<script src="<?php echo base_url('js/jquery-ui-slider-pips.js'); ?>"></script>

<form id="formWrapper" method="get">
	<div class="formFiled clearfix">
		<!-- Formulário de busca -->
		Informe o termo de busca
		<BR>
		<input type="text" name="dd1" required="" placeholder="" class="search" value="<?php echo $dd1; ?>">
		<input type="submit" class="btn submit" value="pesquisar">
		<br>
		<br>
		<font class="lt1">
			<input type="radio" name="dd2" value="0" checked>
			todos os campos&nbsp;&nbsp;&nbsp;
			<input type="radio" name="dd2" value="1">
			palavras-chave&nbsp;&nbsp;&nbsp;
			<input type="radio" name="dd2" value="2">
			nome dos autores&nbsp;&nbsp;&nbsp; </font>
		<br>
		<br>

		<!-- input complementar -->
		<table width="600" class="lt1 tabela00">
			<!-- Range -->
			<tr>
				<td width="160"><nobr>
				<label for="amount">Recorte:</label>
				<input type="text" name="dd3" value="<?php echo $dd3; ?>" id="amount" readonly style="border:0; font-size: 16px; font-weight:bold; background-color: #efefef; width: 100px; ">
				</td>
				<td width="180">&nbsp;</td>
				<td><label for="amount">Database:</label></td>
			</tr>
			<tr>
				<td width="160"><div id="slider-range" class="slider"></div></td>
				<td width="180">&nbsp;</td>
				<td>
				<select name="dd4">
					<option value="0">:: Todas as bases ::</option>
					<option value="1">Ciência da Informação(*)</option>
					<option value="2">Museologia(*)</option>
					<option value="3">Arquivologia(*)</option>
					<option value="4">Teses e Dissertações (CI)(*)</option>
					<option value="5">Anais de Eventos CI(*)</option>
					<option value="20">Resenhas de Livros(*)</option>
				</select>
				(*) Em implementação</td>
			</tr>
		</table>

	</div>
</form>

<script>
$(function() {
$("#slider-range").slider({
range : true,
min : <?php echo $ano_min; ?>
	,
	max :
<?php echo $ano_max; ?>,
values : [<?php echo $anoi . ', ' . $anof; ?>
	], slide : function(event, ui) {
		$("#amount").val("" + ui.values[0] + " - " + ui.values[1]);
	}
	});
	$("#amount").val("" + $("#slider-range").slider("values", 0) + " - " + $("#slider-range").slider("values", 1));
	});
</script>
