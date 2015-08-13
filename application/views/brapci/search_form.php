<form id="formWrapper">
	<div class="formFiled clearfix">
		Informe o termo de busca<BR>
		<input type="text" required="" placeholder="" class="search">
		<input type="submit" class="btn submit" value="pesquisar">
	</div>
</form>

<style>
	#formWrapper {
		width: 940px;
		padding: 8px;
		margin: 30px auto;
		overflow: hidden;
		border-width: 1px;
		border-style: solid;
		border-color: #dedede #bababa #aaa #bababa;
		box-shadow: 0 3px 3px rgba(255,255,255,.1), 0 3px 0 #bbb, 0 4px 0 #aaa, 0 5px 5px #444;
		border-radius: 10px;
		background-color: #f6f6f6;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#f6f6f6), to(#eae8e8));
		background-image: -webkit-linear-gradient(top, #f6f6f6, #eae8e8);
		background-image: -moz-linear-gradient(top, #f6f6f6, #eae8e8);
		background-image: -ms-linear-gradient(top, #f6f6f6, #eae8e8);
		background-image: -o-linear-gradient(top, #f6f6f6, #eae8e8);
		background-image: linear-gradient(top, #f6f6f6, #eae8e8);
	}
	#formWrapper .search {
		width: 800px;
		height: 30px;
		padding: 10px 5px;
		float: left;
		font: bold 24px 'lucida sans', 'trebuchet MS', 'Tahoma';
		border: 1px solid #ccc;
		box-shadow: 0 1px 1px #ddd inset, 0 1px 0 #fff;
		border-radius: 3px;
	}
	#formWrapper .search:focus {
		outline: 0;
		border-color: #aaa;
		box-shadow: 0 1px 1px #bbb inset;
	}
	#formWrapper .search::-webkit-input-placeholder, #formWrapper .search:-moz-placeholder, #formWrapper .search:-ms-input-placeholder {
		color: #999;
		font-weight: normal;
	}

	#formWrapper .btn {
		float: right;
		border: 1px solid #00748f;
		height: 42px;
		width: 100px;
		padding: 0;
		cursor: pointer;
		font: bold 15px Arial, Helvetica;
		color: #fafafa;
		text-transform: uppercase;
		background-color: #0483a0;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#31b2c3), to(#0483a0));
		background-image: -webkit-linear-gradient(top, #31b2c3, #0483a0);
		background-image: -moz-linear-gradient(top, #31b2c3, #0483a0);
		background-image: -ms-linear-gradient(top, #31b2c3, #0483a0);
		background-image: -o-linear-gradient(top, #31b2c3, #0483a0);
		background-image: linear-gradient(top, #31b2c3, #0483a0);
		border-radius: 3px;
		text-shadow: 0 1px 0 rgba(0, 0 ,0, .3);
		box-shadow: 0 1px 0 rgba(255, 255, 255, 0.3) inset, 0 1px 0 #fff;
	}
	#formWrapper .btn:hover, #formWrapper .btn:focus {
		background-color: #31b2c3;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#0483a0), to(#31b2c3));
		background-image: -webkit-linear-gradient(top, #0483a0, #31b2c3);
		background-image: -moz-linear-gradient(top, #0483a0, #31b2c3);
		background-image: -ms-linear-gradient(top, #0483a0, #31b2c3);
		background-image: -o-linear-gradient(top, #0483a0, #31b2c3);
		background-image: linear-gradient(top, #0483a0, #31b2c3);
	}
	#formWrapper .btn:active {
		outline: 0;
		box-shadow: 0 1px 4px rgba(0, 0, 0, 0.5) inset;
	}


</style>

<?php
echo '<table class="tabela01" width="100%">';
echo '<tr valign="top">';
echo '<td width="60%">';

echo form_open();

/* Label */
echo form_label('Informe os termos ou expressão de busca');
echo ' | <a href="' . base_url('/home/help') . '" target="_new_help" title="Ajuda sobre a busca">?</A>';
echo '<BR>';

//* textarea */
echo '<textarea name="dd1" cols="80" rows="5" class="fullscreen" style="width: 100%">' . $this -> input -> post('dd1') . '</textarea>';
echo '<BR>';

/* RadioBox */
$opts = array('em todos os campos', 'nas palavras-chave', 'no resumo', 'por autores');

$opts_value = $this -> input -> post('dd2');
if (strlen($opts_value) == 0) { $opts_value = 0;
}
for ($r = 0; $r < count($opts); $r++) {
	$checked = '';
	if ($opts_value == $r) {$checked = 'checked';
	}
	echo '<input type="radio" name="dd2" ' . $checked . ' value="' . $r . '">';
	echo form_label($opts[$r]) . '&nbsp;&nbsp;';
}

echo '<BR>';
/* Submit */
$options = array('name' => 'acao', 'id' => 'acao', 'value' => 'realizar pesquisa >>', 'class' => 'form_submit_big');
echo form_submit($options);

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

/* datas */
$anof = 2015;
$anoi = 1972;
echo '<td width="20%" class="tabela01">';
echo form_label('delimitação da busca');
echo '<BR><BR>';
$opt = array();
for ($r = $anoi; $r <= $anof; $r++) { $opt[$r] = $r;
}
echo form_dropdown('dd20', $opt);

echo ' até ';

$opt = array();
for ($r = $anof; $r >= $anoi; $r--) { $opt[$r] = $r;
}
echo form_dropdown('dd21', $opt);

echo form_close();
echo '</table>';
?>
