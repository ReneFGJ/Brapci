<?php
echo '<table class="tabela01" width="100%">';
echo '<tr valign="top">';
echo '<td width="60%">';

echo form_open();

/* Label */
echo form_label('Informe os termos ou expressão de busca');
echo '<BR>';

//* textarea */
if (!(isset($dd1))) { $dd1 = ''; }
$options = array('name'=>'dd1','id'=>'dd1','rows'=>'5','cols'=>'80','value'=>$dd1,'class'=>'fullscreen');
echo form_textarea($options);
echo '<BR>';

/* RadioBox */
$opts = array('em todos os campos','nas palavras-chave','no resumo','por autores');

$opts_value = $this->input->post('dd2');
if (strlen($opts_value) == 0) { $opts_value = 0; } 
for ($r=0;$r < count($opts);$r++)
	{
	$radio_is_checked =  $opts_value === $r;
	
	/* Default */
	//if ((!isset($this->input->post('dd2'))) and ($r ==0)) { $radio_is_checked = TRUE; }
	echo form_radio('dd2', $r, $radio_is_checked, 'id=female');
	echo form_label($opts[$r]).'&nbsp;&nbsp;';
	}

echo '<BR>';
/* Submit */
$options = array('name'=>'acao','id'=>'acao','value'=>'realizar pesquisa >>','class'=>'form_submit_big');
echo form_submit($options);

/* Opcoes */
echo '<td width="20%" class="tabela01">';
echo form_label('delimitação da busca');
echo '<BR>';
/* revistas */
$data = array('name'=>'dd10','checked'=>'checked','value'=>1);
echo '<BR>'.form_checkbox($data) ;
echo form_label('Revistas científicas');

/* revistas */
$data = array('name'=>'dd11','checked'=>'checked','value'=>1);
echo '<BR>'.form_checkbox($data) ;
echo form_label('Eventos científicos');

/* revistas */
$data = array('name'=>'dd12','checked'=>'checked','value'=>1);
echo '<BR>'.form_checkbox($data) ;
echo form_label('Teses');

/* revistas */
$data = array('name'=>'dd13','checked'=>'checked','value'=>1);
echo '<BR>'.form_checkbox($data) ;
echo form_label('Dissertações');

/* datas */
$anof = 2015;
$anoi = 1972;
echo '<td width="20%" class="tabela01">';
echo form_label('delimitação da busca');
echo '<BR><BR>';
$opt = array();
for ($r=$anoi;$r <= $anof;$r++) { $opt[$r] = $r; }
echo form_dropdown('dd20',$opt);

echo ' até ';

$opt = array();
for ($r=$anof;$r >= $anoi;$r--) { $opt[$r] = $r; }
echo form_dropdown('dd21',$opt);

echo form_close();
echo '</table>';
?>
