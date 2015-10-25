<!--- CSS Complement -->
<link rel="stylesheet" href="<?php echo base_url('css/style_brapci_search_form.css');?>">
<link rel="stylesheet" href="<?php echo base_url('css/jquery-ui-1.7.1.custom.css');?>">

<!--- JS Complement -->
<script src="<?php echo base_url('js/jquery-ui.js');?>"></script>
<script src="<?php echo base_url('js/jquery-ui-slider-pips.js');?>"></script>

<form id="formWrapper" method="get">
	<div class="formFiled clearfix">
		<!-- Formulário de busca -->
		Informe o termo de busca
		<BR>
		<input type="text" name="dd1" required="" placeholder="" class="search" value="<?php echo $dd1;?>">
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
		<br><br>
		
		<!-- input complementar -->
		<table width="200" class="lt1 tabela00">
			<!-- Range -->
			<tr>
				<td width="200">
				<label for="amount">Recorte:</label>
				<input type="text" name="dd3" value="<?php echo $dd3;?>" id="amount" readonly style="border:0; font-size: 16px; font-weight:bold; background-color: #efefef; width: 100px; ">
				
				</td>
			</tr><tr>
				<td width="200">
					<div id="slider-range" class="slider"></div>			
				</td>
			</tr>
		</table>
		

	</div>
</form>



<script>
	$(function() {
		$("#slider-range").slider({
			range : true,
			min : <?php echo $ano_min;?>,
			max : <?php echo $ano_max;?>,
			values : [<?php echo $anoi.', '.$anof;?>],
			slide : function(event, ui) {
				$("#amount").val("" + ui.values[0] + " - " + ui.values[1]);
			}
		});
		$("#amount").val("" + $("#slider-range").slider("values", 0) + " - " + $("#slider-range").slider("values", 1));
	}); 
</script>


	<?php
	echo '<table class="tabela01" width="100%">';
	echo '<tr valign="top">';
	echo '<td width="60%">';

	/* Opcoes */
	echo '<td width="20%" class="tabela01">';
	echo form_label('delimitação da busca');
	echo '<BR>';
	/* revistas */
	$data = array('name' => 'dd10', 'checked' => 'checked', 'value' => 1);
	echo '<BR>' . form_checkbox($data);
	echo form_label('Revistas científicas');

	/* revistas */
	$data = array('name' => 'dd11', 'checked' => 'checked', 'value' => 1);
	echo '<BR>' . form_checkbox($data);
	echo form_label('Eventos científicos');

	/* revistas */
	$data = array('name' => 'dd12', 'checked' => 'checked', 'value' => 1);
	echo '<BR>' . form_checkbox($data);
	echo form_label('Teses');

	/* revistas */
	$data = array('name' => 'dd13', 'checked' => 'checked', 'value' => 1);
	echo '<BR>' . form_checkbox($data);
	echo form_label('Dissertações');

	echo '</table>';
	?>
	

