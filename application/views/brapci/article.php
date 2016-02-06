<?php
echo '<div id="ccab">';
echo '<div id="bdoi">BDOI: ' . $ar_bdoi .
		'<BR>DOI: '.$ar_doi. 
		'</div>';
echo '<div id="journal">' . $jnl_nome . ', v.' . $ed_vol . ', n.' . $ed_nr . ', ' . $ed_ano . '.</div>';
echo '</div>';

echo '<div id="pdf_icone">
		<A HREF="'.$link_pdf.' target="new'.$ar_bdoi.'"><img src="'.base_url('img/icone_pdf.png').'" height="64" border=0 title="download pdf" ></A>
		<A HREF="'.$link_pdf.' target="new'.$ar_bdoi.'"><img src="'.base_url('img/icone_link.png').'" height="64" border=0 title="access site do view"></A>
	  </div>';
echo '<h1>' . $ar_titulo_1 . '</h1>';
echo '<h2>' . $ar_titulo_2 . '</h2>';

echo '<div id="authors">';
echo $author;
echo '</div>';

echo '<div id="texto">';
echo '<div id="pdf">
			<div id="pdf_frame">
					<iframe SRC="'.$link_pdf.'" style="width: 99%; height: 95%;"></iframe>
			</div>
			<div style="float: right;">
				<span id="download"  onclick="$(\'#pdf\').toggle();"  class="link">fechar</span> | 
				<a href="'.$link_pdf.'" id="download" class="link" target="new0000010946">download</a>&nbsp;</div>
			</div>';

echo '<div id="rs1">';
echo '<div id="asb1">' . $ar_resumo_1 . '</div>';
echo '<div id="key1">' . $ar_keyw_1 . '</div>';
echo '</div>';
echo '<BR>';
echo '<div id="rs2">';
echo '<div id="asb2">' . $ar_resumo_2 . '</div>';
echo '<div id="key2">' . $ar_keyw_2 . '</div>';
echo '</div>';

//echo $metodologia;

echo '</div>';

echo msg('how_to_cite').':<br>';
echo $reference;

echo '<BR>';
echo '<BR>';
echo '<BR>';
echo '<div id="cited">[Cited by ' . $at_citacoes . ']</div>';

echo '<BR>';
echo '<div id="ref"><B>Referências</B><BR>' . $cited . '</div>';

echo '</div>';

if ($user_nivel > 5)
	{
		echo '<A HREF="'.base_url("admin/article_view/".$id_ar."/".checkpost_link($id_ar)).'">editar</A>';
	}
?>
