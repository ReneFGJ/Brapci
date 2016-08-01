<?php
/******* years *************/
$data1 = '';
$data2 = '';
$anof = 1972;
if (isset($_SESSION['anoi'])) {
	$ano1 = $_SESSION['anoi'];
} else {
	$ano1 = $anof;
}
if (isset($_SESSION['anof'])) {
	$ano2 = $_SESSION['anof'];
} else {
	$ano2 = date("Y");
}


 

/******************************************************** Mount Form Select ****************/
$check_dd3 = array('', '', '', '', '', '', '', '', '', '', '');
if (strlen(get("dd3")) > 0) {
	$dd3 = round(get("dd3"));
	$check_dd3[$dd3] = 'checked';
} else {
	$check_dd3[0] = 'checked';
	$dd3 = 0;
}

/**************** classes *********/
 $style_dd4a = 'style="display: none;" ';
 $style_dd4b = 'style="display: none;" ';
 $style_dd4c = 'style="display: none;" ';
 $style_dd4d = 'style="display: none;" ';
 $style_dd4e = 'style="display: none;" ';
 
 switch($dd3)
 	{
 	case '0':
		$style_dd4a = '';
		break;		
 	case '1':
		$style_dd4b = '';
		break;	
 	case '2':
		$style_dd4c = '';
		break;
 	case '3':
		$style_dd4d = '';
		break;
 	case '4':
		$style_dd4e = '';
		break;										
 	}

for ($r = $anof; $r <= (date("Y") + 1); $r++) {
	$chk = '';
	if ($r == round($ano1)) { $chk = 'selected';
	}
	$data1 .= '<option value="' . $r . '" ' . $chk . '>' . $r . '</option>' . cr();
}
for ($r = (date("Y") + 1); $r >= $anof; $r--) {
	$chk = '';
	if ($r == round($ano2)) { $chk = 'selected';
	}
	$data2 .= '<option value="' . $r . '" ' . $chk . '>' . $r . '</option>' . cr();
}



/***************************************************************** Journal Type ****************************/
$sa1 = '<h4>' . msg('publication_type') . '</h4>';
$sql = "select * from brapci_journal_tipo where jtp_ativo = 1 order by jtp_ordem";
$rlt = $this -> db -> query($sql);
$rlt = $rlt -> result_array();
for ($r = 0; $r < count($rlt); $r++) {
	$line = $rlt[$r];
	$id = $line['id_jtp'];
	if (isset($_SESSION['tp_' . $line['id_jtp']])) {
		if ($_SESSION['tp_' . $line['id_jtp']] == 0) {
			$check = '';
		} else {
			$check = 'checked';
		}
	} else {
		$check = 'checked';
	}
	$sa1 .= '<input type="checkbox" ' . $check . '> ';
	$sa1 .= $line['jtp_descricao'] . '<br>' . cr();
}
?>
		<script src="<?php echo base_url('js/jquery.autocomplete.js'); ?>"></script>
		<script src="<?php echo base_url('js/jquery.mockjax.js'); ?>"></script>
		<script src="<?php echo base_url('js/autor_complete.js'); ?>"></script>
		<script src="<?php echo base_url('js/keywords_complete.js'); ?>"></script>
		<script src="<?php echo base_url('js/demo/busca_autor.js'); ?>"></script>
		<link rel="stylesheet" href="<?php echo base_url('css/style_brapci_search_form.css'); ?>">
<style>
	.ui-draggable, .ui-droppable {
		background-position: top;
	}
</style>
<form method="post" id="form_search" action="<?php echo base_url('index.php/home/');?>">
	<div class="container-fluid search" style="min-height: 400px;">
		<div class="row ">
			<div class="row" style="margin-top: 50px;">
				<div class="col-xs-10 col-xs-offset-1">
					<span class="roboto"><font color="white">informe o(s) termo(s) de busca</font></span>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-10 col-xs-offset-1">
					<div class="input-group" style="padding: 5px; ">						
						<input type="text" class="form-control selector" <?php echo $style_dd4a;?> name="dd4a" id="dd4a" placeholder="Busca por..." value="<?php echo get("dd4a"); ?>"/>
						<input type="text" class="form-control selector" <?php echo $style_dd4b;?> name="dd4b" id="dd4b" placeholder="Busca pelo autor..." value="<?php echo get("dd4b"); ?>" />
						<input type="text" class="form-control selector" <?php echo $style_dd4c;?> name="dd4c" id="dd4c" placeholder="Busca no titulo..." value="<?php echo get("dd4c"); ?>" />
						<input type="text" class="form-control selector" <?php echo $style_dd4d;?> name="dd4d" id="dd4d" placeholder="Busca nas palavras-chave..." value="<?php echo get("dd4d"); ?>" />
						<input type="text" class="form-control selector" <?php echo $style_dd4e;?> name="dd4e" id="dd4e" placeholder="Busca nos resumos..." value="<?php echo get("dd4e"); ?>" />
						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" onclick="submit();">
								pesquisar
							</button> </span>
					</div><!-- /input-group -->
				</div><!-- /.col-lg-6 -->
			</div><!-- /.row -->
			<!---- Type ---->
			<div class="row">
				<div class="col-md-4 col-xs-7 col-xs-offset-2 col-md-offset-2" style="color: #ffffff;">
					<input type="radio" name="dd3" value="0" id="dd3_0" <?php echo $check_dd3[0]; ?>>
					Todos os campos
					<div class="visible-xs visible-sm"></div>
					<input type="radio" name="dd3" value="1" id="dd3_1" <?php echo $check_dd3[1]; ?> >
					Autores
					<div class="visible-xs visible-sm"></div>
					<input type="radio" name="dd3" value="2" id="dd3_2" <?php echo $check_dd3[2]; ?> >
					Título
					<div class="visible-xs visible-sm visible-md"></div>
					<input type="radio" name="dd3" value="3" id="dd3_3" <?php echo $check_dd3[3]; ?> >
					Palavras-chave
					<div class="visible-xs visible-sm"></div>
					<input type="radio" name="dd3" value="4" id="dd3_4" <?php echo $check_dd3[4]; ?> >
					Resumo
					<div class="visible-xs visible-sm"></div>
					<br>
				</div>
				<div class="col-md-2 col-xs-8 col-xs-offset-2 text-right" style="color: #ffffff;">
					<!---------------------------------------------------------------------------- YEARS ----------->
					<?php echo msg('form_year_cut'); ?>
					<select style="color: #000000;" name="dd5">
						<?php echo $data1; ?>
					</select>
					-
					<select style="color: #000000;" name="dd6">
						<?php echo $data2; ?>
					</select>
				</div>
			</div>
			<!--- ROW -->
			<input type="hidden" name="acao" value="busca_termo">
		</div>
	</div>
	</div>
</form>

<script>

function submit()
	{
	$("#form_search").submit();
	}
$("#dd3_0").click(function () {
	$("#dd4a").show();
	$("#dd4b").hide();	
	$("#dd4c").hide();
	$("#dd4d").hide();
	$("#dd4e").hide();
});
$("#dd3_1").click(function () {
	$("#dd4b").show();
	$("#dd4a").hide();	
	$("#dd4c").hide();
	$("#dd4d").hide();
	$("#dd4e").hide();
});
$("#dd3_2").click(function () {
	$("#dd4c").show();
	$("#dd4a").hide();	
	$("#dd4b").hide();
	$("#dd4d").hide();
	$("#dd4e").hide();
});
$("#dd3_3").click(function () {
	$("#dd4d").show();
	$("#dd4a").hide();	
	$("#dd4b").hide();
	$("#dd4c").hide();
	$("#dd4e").hide();
});
$("#dd3_4").click(function () {
	$("#dd4e").show();
	$("#dd4a").hide();	
	$("#dd4b").hide();
	$("#dd4c").hide();
	$("#dd4d").hide();
});
</script>
	
<style>


</style>