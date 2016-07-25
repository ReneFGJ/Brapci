<?php
$edit_link1 = '<img src="' . base_url('img/icone_edit.gif') . '" height="16" id="titles">';
$edit_link2 = '<img src="' . base_url('img/icone_edit.gif') . '" height="16" id="authors">';
$edit_link4 = '<img src="' . base_url('img/icone_edit.gif') . '" height="16" id="abstract1">';
$edit_link5 = '<img src="' . base_url('img/icone_edit.gif') . '" height="16" id="abstract2">';
$edit_link6 = '<img src="' . base_url('img/icone_edit.gif') . '" height="16" id="issue">';

/* Authors */
$authors = array('');
$authores_row = troca($authores_row,chr(13),';');
$authors = splitx(';',$authores_row);
?>

<div class="container">
	<div class="row">
		<div class="col-md-8">
			<h3><?php echo $jnl_nome; ?></h3>
			<span class="middle">v. <?php echo $ed_vol . ', n.' . $ed_nr . ', ' . $ed_ano . $pages; ?></span>
		</div>
		<div class="col-md-4">
			<br>
			BDOI: <?php echo $ar_bdoi; ?><BR>
			DOI: <?php echo $ar_doi; ?>
		</div>
	</div>
</div>
	
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<table class="table" width="100%">
				<tr valign="top">
					<th width="40">field</th>
					<th width="20">##</th>
					<th>content</th>
				</tr>
				
				<!---- 100 --->
				<tr valign="top">
					<td align="center">
						<tt>100</tt>
					</td>
					<td>
						<tt>1_</tt>
					</td>
					<td>
						<tt><?php echo $authors[0];?></tt>
					</td>
				</tr>
				
				<!---- 245 --->
				<tr valign="top">
					<td align="center">
						<tt>245</tt>
					</td>
					<td>
						<tt>10</tt>
					</td>
					<td>
						<tt><?php echo $ar_titulo_1;?> |6 <?php echo msg('idioma'). ' '. msg($ar_idioma_1);?></tt>
					</td>
				</tr>
				
				<!---- 246 --->
				<tr valign="top">
					<td align="center">
						<tt>246</tt>
					</td>
					<td>
						<tt>10</tt>
					</td>
					<td>
						<tt><?php echo $ar_titulo_2;?> |6 <?php echo msg('idioma'). ' '. msg($ar_idioma_2);?></tt>
					</td>
				</tr>
				
				<!---- 300 --->
				<tr valign="top">
					<td align="center">
						<tt>300</tt>
					</td>
					<td>
						<tt>10</tt>
					</td>
					<td>
						<tt><?php echo '|a v. '.$ed_vol . ', n. ' . $ed_nr . ', ' . $ed_ano . $pages;?></tt>
					</td>
				</tr>
				
				<!---- 520 --->
				<tr valign="top">
					<td align="center">
						<tt>520</tt>
					</td>
					<td>
						<tt>3#</tt>
					</td>
					<td>
						<tt>|a <?php echo $ar_resumo_1;?> |6 <?php echo msg('idioma'). ' '. msg($ar_idioma_1);?></tt>
					</td>
				</tr>
				
				<tr valign="top">
					<td align="center">
						<tt>520</tt>
					</td>
					<td>
						<tt>3#</tt>
					</td>
					<td>
						<tt>|a <?php echo $ar_resumo_2;?> |6 <?php echo msg('idioma'). ' '. msg($ar_idioma_2);?></tt>
					</td>
				</tr>
				
				<!---- 650 --->
				<?php
				$ar_keyw_1 = splitx(';',$ar_keyw_2.';');
				?>
				<?php for ($r=1;$r < count($ar_keyw_1);$r++) { 
					$link = '<a href="'.base_url('index.php/').'">'.base_url('index.php/t/').'</a>';
					?>
				<tr valign="top">
					<td align="center">
						<tt>650</tt>
					</td>
					<td>
						<tt>1_</tt>
					</td>
					<td>
						<tt>|a <?php echo $ar_keyw_1[$r];?> |? <?php echo $ar_idioma_1;?> |6 <?php echo $link;?></tt>
					</td>
				</tr>
				<? } ?>
				
				<?php
				$ar_keyw_2 = splitx(';',$ar_keyw_2.';');
				?>
				<?php for ($r=1;$r < count($ar_keyw_2);$r++) { ?>
				<tr valign="top">
					<td align="center">
						<tt>650</tt>
					</td>
					<td>
						<tt>1_</tt>
					</td>
					<td>
						<tt>|a <?php echo $ar_keyw_2[$r];?> |? <?php echo $ar_idioma_2;?></tt>
					</td>
				</tr>
				<? } ?>
				
				<!---- 700 --->
				<?php for ($r=1;$r < count($authors);$r++) { ?>
				<tr valign="top">
					<td align="center">
						<tt>700</tt>
					</td>
					<td>
						<tt>1_</tt>
					</td>
					<td>
						<tt><?php echo $authors[$r];?></tt>
					</td>
				</tr>
				<? } ?>
				
				<!---- 773 --->
				<tr valign="top">
					<td align="center">
						<tt>773</tt>
					</td>
					<td>
						<tt>0#</tt>
					</td>
					<td>
						<tt><?php echo '|a '.$cidade_nome.' |t '.$jnl_nome.' $x '.$jnl_issn_impresso;?></tt>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<?
/* Barra de progresso */
echo $progress_bar;

echo '<div id="ccab">';

echo '<div id="bdoi">BDOI: ' . $ar_bdoi . '<BR>DOI: ' . $ar_doi . '</div>
' . '<div id="journal" >' . $link_issue . $jnl_nome . ', v.' . $ed_vol . ', n.' . $ed_nr . ', ' . $ed_ano . $pages . '</A> ' . $edit_link6 . '</div>
<div style="float: clean; width: 100%;">&nbsp;</div>
<div style="float: clean;">&nbsp;</div>
<HR>
<div id="section" class="lt4">' . $se_descricao . '</div>';

/* Formulário */
if (isset($jnl_scielo)) {
	if ($jnl_scielo == 1) {
		$link = '<a href="' . base_url('index.php/admin/scielo_harvesting/' . $id_ar . '/' . checkpost_link($id_ar)) . '" class="link" title="Scielo Harvesting">';
		echo $link;
		echo '<img src="' . base_url('img/logo/logo_scielo.png') . '" height="32" border=0></a>';
	}
}
echo '<div id="issue_id" style="display: none;">';
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
$fld1 = Array("name" => "dd11", "maxsize" => "15", "size" => 6, 'value' => $ar_pg_inicial);
$fld2 = Array("name" => "dd12", "maxsize" => "6", "size" => 6, 'value' => $ar_pg_final);
$fld3 = Array();
$fld4 = Array("name" => "dd14", "maxsize" => "50", "size" => 50, 'value' => $ar_doi);
$fld5 = Array("name" => "dd15", "maxsize" => "50", "size" => 50, 'value' => $ar_section);
echo '<table border="1" class="tabela00 lt1">
		<tr valign="top">
		<TD>paginação:</td>
		<td>' . form_input($fld1) . '-' . form_input($fld2) . '</td>
		</tr>
		<tr valign="top">
		<TD>fasciculo:</td>
		<td>' . form_dropdown('dd13', $dt, $ar_edition) . '</td>
		</tr>
		<tr valign="top">
		<TD>DOI:</td>
		<td>' . form_input($fld4) . '</td>
		</tr>
		<tr valign="top">
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
echo $this -> archives -> new_file($id_ar);

//echo $metodologia;
echo $metodologias;

echo '<BR>';
echo '<div id="cited">[Cited by ' . $at_citacoes . ']</div>';
echo $citeds;

echo '<BR>';
echo '<div id="ref"><B>Referências</B><BR>' . $cited . '</div>';

echo '</div>';
echo '<td width="50%">
		<div id="pdf2">
			<div id="pdf_frame">
					<iframe SRC="' . $link_pdf . '" style="width: 99%; height: 95%; min-height: 800px;"></iframe>
			</div>
			<div style="float: right;">
				<span id="download"  onclick="$(\'#pdf\').toggle();"  class="link">fechar</span> | 
				<a href="' . $link_pdf . '" id="download" class="link" target="new0000010946">download</a>&nbsp;</div>
			</div>';
?>
</div>
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
