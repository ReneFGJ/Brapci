<?php
$edit_link1 = '<img src="' . base_url('img/icone_edit.gif') . '" height="16" id="titles">';
$edit_link2 = '<img src="' . base_url('img/icone_edit.gif') . '" height="16" id="authors">';
$edit_link4 = '<img src="' . base_url('img/icone_edit.gif') . '" height="16" id="abstract1">';
$edit_link5 = '<img src="' . base_url('img/icone_edit.gif') . '" height="16" id="abstract2">';
$edit_link6 = '<img src="' . base_url('img/icone_edit.gif') . '" height="16" id="issue">';

/* Barra de progresso */
echo $progress_bar;

echo '<div id="ccab">';

echo '<div id="bdoi">BDOI: ' . $ar_bdoi . '<BR>DOI: ' . $ar_doi . '</div>
'.'<div id="journal" >' . $link_issue . $jnl_nome . ', v.' . $ed_vol . ', n.' . $ed_nr . ', ' . $ed_ano . $pages . '</A> ' . $edit_link6 . '</div>
<div style="float: clean; width: 100%;">&nbsp;</div>
<div style="float: clean;">&nbsp;</div>
<HR>
<div id="section" class="lt4">' . $se_descricao . '</div>

<div id="issue_id" style="display: none;">';
/* FORMULARIO */

$sql = "select * from brapci_edition where ed_journal_id = '" . $jnl_codigo . "' order by ed_ano desc, ed_vol desc, ed_nr";
$rlt = db_query($sql);
$dt = array();
while ($line = db_read($rlt)) {
	$dk = $line['ed_codigo'];
	$ds = trim($line['ed_ano']) . ', v.' . trim($line['ed_vol']) . ', ' . trim($line['ed_nr']) . ' ' . trim($line['ed_tematica_titulo']);
	$dt[$dk] = $ds;
}

/* Section */
$sql = "select * from brapci_section where se_ativo = 1 order by se_descricao";
$rlt = db_query($sql);
$dts = array();
while ($line = db_read($rlt)) {
	$dk = $line['se_codigo'];
	$ds = trim($line['se_descricao']);
	$dts[$dk] = $ds;
}

/* ISSE e PAGES *************************************************************************************************************************************/


/* Open form */
echo form_open('admin/article_view/' . $id_ar . '/' . checkpost_link($id_ar));

/* Hidden */
$data = array('dd8' => 'ISSUE');
echo form_hidden($data);

if ($ar_doi == '<font color="red">empty</font>') { $ar_doi = '';
}
$fld1 = Array("name" => "dd11", "maxsize" => "6", "size" => 6, 'value' => $ar_pg_inicial);
$fld2 = Array("name" => "dd12", "maxsize" => "6", "size" => 6, 'value' => $ar_pg_final);
$fld3 = Array();
$fld4 = Array("name" => "dd14", "maxsize" => "50", "size" => 50, 'value' => $ar_doi);
$fld5 = Array("name" => "dd15", "maxsize" => "50", "size" => 50, 'value' => $ar_section);
echo '<table border="1" class="tabela00 lt1">
		<TR>
		<TD>paginação:</td>
		<td>' . form_input($fld1) . '-' . form_input($fld2) . '</td>
		</tr>
		<TR>
		<TD>fasciculo:</td>
		<td>' . form_dropdown('dd13', $dt, $ar_edition) . '</td>
		</tr>
		<TR>
		<TD>DOI:</td>
		<td>' . form_input($fld4) . '</td>
		</tr>
		<tr>
		<td>Seção:</td>
		<td>' . form_dropdown('dd15', $dts, $ar_section) . '</td>
		</tr>
	  </table>';

/* Submit button */
echo '<BR>';
echo form_submit('acao', 'save >>');

/* Close form */
echo form_close();
echo form_fieldset_close();
echo '</div>';

echo '</div>

<div id="pdf_icone">
		<A HREF="' . $link_pdf . ' target="new' . $ar_bdoi . '"><img src="' . base_url('img/icone_pdf.png') . '" height="64" border=0 title="download pdf" ></A>
		<A HREF="' . $link_pdf . ' target="new' . $ar_bdoi . '"><img src="' . base_url('img/icone_link.png') . '" height="64" border=0 title="access site do view"></A>
	  </div>
';

echo '<table width="100%" class="tabela00" border=1 >
<tr valign="top"><td width="50%">
<h1>' . $ar_titulo_1 . $edit_link1 . '</h1>
<h2>' . $ar_titulo_2 . '</h2>';

/* Form textarea titulo
 *
 * TITULO E IDIOMA
 *
 *
 *
 * */
echo '<div id="titles_id" style="display: none;">';

/* Open form */
echo form_open('admin/article_view/' . $id_ar . '/' . checkpost_link($id_ar));

/* Hidden */
$data = array('dd8' => 'TITLE');
echo form_hidden($data);

/* Fieldset */
$option = array('class' => 's96 ml5 pad5');
echo form_fieldset('TITLE', $option);

/* Original title */
echo form_label('Original title') . '<BR>';
$fld = Array("name" => "dd10", "cols" => "80", "rows" => 5, 'value' => $ar_titulo_1, 'class' => 'fullscreen');
echo form_textarea($fld);
echo '<BR>';
$options = array('' => '::idioma::', 'pt_BR' => 'Portugues', 'en' => 'English', 'es' => 'Spanish', 'fr' => 'French');
echo 'Idioma:' . form_dropdown('dd12', $options, $ar_idioma_1) . ' (' . $ar_idioma_1 . ')';
echo '<BR>';

/* Alternative title */
echo form_label('Alternative title') . '<BR>';
$fld = Array("name" => "dd11", "cols" => "80", "rows" => 5, 'value' => $ar_titulo_2, 'class' => 'fullscreen');
echo form_textarea($fld);
echo '<BR>';
$options = array('' => '::idioma::', 'pt_BR' => 'Portugues', 'en' => 'English', 'es' => 'Spanish', 'fr' => 'French');
echo 'Idioma:' . form_dropdown('dd13', $options, $ar_idioma_2) . ' (' . $ar_idioma_2 . ')';

/* Submit button */
echo '<BR>';
echo form_submit('acao', 'save >>');

/* Close form */
echo form_close();
echo form_fieldset_close();
echo '</div>';

/* Botao de acoes */
echo $botao_acoes;

/* Authors
 *
 * */

echo '<div id="authors">';
echo $author;

echo $edit_link2;
echo '</div>';

/* Form textarea titulo
 *
 * AUTHORS
 *
 *
 *
 * */
echo '<div id="author_id" style="display: none;">';

/* Open form */
echo form_open('admin/article_view/' . $id_ar . '/' . checkpost_link($id_ar));

/* Hidden */
$data = array('dd8' => 'AUTHOR');
echo form_hidden($data);

/* Fieldset */
$option = array('class' => 's96 ml5 pad5');
echo form_fieldset('AUTHOR', $option);

/* Original title */
echo form_label('Authors') . '<BR>';
$fld = Array("name" => "dd10", "cols" => "80", "rows" => 5, 'value' => $authores_row, 'class' => 'fullscreen');
echo form_textarea($fld);
echo '<BR>';

/* Submit button */
echo '<BR>';
echo form_submit('acao', 'save >>');

echo form_close();
echo '</div>';
/* */

echo '<div id="texto">';

echo '<div id="rs1">';
echo '<B>Resumo</B>' . $edit_link4;
echo '<div id="asb1">' . $ar_resumo_1 . '</div>';
echo '<div id="key1"><B>Keywords</B>:' . $ar_keyw_1 . '</div>';

/* Form textarea titulo
 *
 * RESUMO E PALAVRAS-CHAVE
 *
 *
 *
 * */
echo '<div id="abstract_id1" style="display: none;">';

/* Open form */
echo form_open('admin/article_view/' . $id_ar . '/' . checkpost_link($id_ar));

/* Hidden */
$data = array('dd8' => 'ABSTRACT1');
echo form_hidden($data);

/* Fieldset */
$option = array('class' => 's96 ml5 pad5');
echo form_fieldset('ABSTRACT', $option);

/* Original title */
echo form_label('Abstract') . '<BR>';
$fld = Array("name" => "dd10", "cols" => "80", "rows" => 10, 'value' => $ar_resumo_1, 'class' => 'fullscreen');
echo form_textarea($fld);
echo '<BR>';

echo form_label('Keywords') . '<BR>';
$fld = Array("name" => "dd11", "cols" => "80", "rows" => 3, 'value' => $ar_keyw_1, 'class' => 'fullscreen');
echo form_textarea($fld);
echo '<BR>';

/* Submit button */
echo '<BR>';
echo form_submit('acao', 'save >>');

/* Close form */
echo form_close();
echo form_fieldset_close();
echo '</div>';

echo '</div>';
echo '<BR>';
echo '<div id="rs2">';
echo '<B>Resumo</B>' . $edit_link5;
echo '<div id="asb2">' . $ar_resumo_2 . '</div>';
echo '<div id="key2"><B>Keywords</B>:' . $ar_keyw_2 . '</div>';

/* Form textarea titulo
 *
 * RESUMO E PALAVRAS-CHAVE
 *
 *
 *
 * */
echo '<div id="abstract_id2" style="display: none;">';

/* Open form */
echo form_open('admin/article_view/' . $id_ar . '/' . checkpost_link($id_ar));

/* Hidden */
$data = array('dd8' => 'ABSTRACT2');
echo form_hidden($data);

/* Fieldset */
$option = array('class' => 's96 ml5 pad5');
echo form_fieldset('ABSTRACT', $option);

/* Original title */
echo form_label('Abstract') . '<BR>';
$fld = Array("name" => "dd10", "cols" => "80", "rows" => 10, 'value' => $ar_resumo_2, 'class' => 'fullscreen');
echo form_textarea($fld);
echo '<BR>';

echo form_label('Keywords') . '<BR>';
$fld = Array("name" => "dd11", "cols" => "80", "rows" => 3, 'value' => $ar_keyw_2, 'class' => 'fullscreen');
echo form_textarea($fld);
echo '<BR>';

/* Submit button */
echo '<BR>';
echo form_submit('acao', 'save >>');

/* Close form */
echo form_close();
echo form_fieldset_close();
echo '</div>';

echo '</div>';

echo '<BR>';
echo $archives;
echo $this->archives->new_file($id_ar);


echo '<BR>';
echo '<div id="cited">[Cited by ' . $at_citacoes . ']</div>';
echo $citeds;

echo '<BR>';
echo '<div id="ref"><B>Referências</B><BR>' . $cited . '</div>';

echo '</div>';
echo '<td width="50%">
		<div id="pdf2">
			<div id="pdf_frame">
					<iframe SRC="' . $link_pdf . '" style="width: 99%; height: 95%;"></iframe>
			</div>
			<div style="float: right;">
				<span id="download"  onclick="$(\'#pdf\').toggle();"  class="link">fechar</span> | 
				<a href="' . $link_pdf . '" id="download" class="link" target="new0000010946">download</a>&nbsp;</div>
			</div>';
?>
<script>
	$("#issue").click(function() {
		$("#issue_id").toggle();
	});
	$("#titles").click(function() {
		$("#titles_id").toggle();
	});

	$("#abstract1").click(function() {
		$("#abstract_id1").toggle();
	});
	$("#abstract2").click(function() {
		$("#abstract_id2").toggle();
	});
	$("#authors").click(function() {
		$("#author_id").toggle();
	});

</script>
