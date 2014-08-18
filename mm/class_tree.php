<?
$ms1 = 'Árvore de classes';
$ms2 = 'Conceitos';
$mm1 = 'Árvore';
$mm2 = 'Descritor';
$mm3 = 'Busca';
$mm4 = 'Adiciona novo conceito';
if ($idioma == 'en')
	{
	$ms1 = 'Class Tree';
	$ms2 = 'Concept';
	
	$mm1 = 'Tree';
	$mm2 = 'Descriptor';
	$mm3 = 'Search';
	}
?>
<h2><?=$ms1; // class tree ?></h2>
<table border="0" cellpadding="0" cellspacing="0">
	<TR><TD>
	<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
	<TR align="center" bgcolor="#808080">
		<TD><?=$mm1; // tree?></TD>
		<TD><a href="descriptor_add.php"><?=$mm2; // List?></a></TD>
		<TD><?=$mm3; // Search?></TD>
	</TR>
	</table>
	</TD></TR>
<TR><TH><?=$ms2; // conceitos ?></TH></TR>
<TR><TD><a href="concept_add.php"><img src="img/icone_concept_add.png" width="32" height="32" title="<?=$mm4;?>" border="0"></a></TD></TR>
<TR><TD width="250">
<UL>
<?
$sql = "select * from tci_conceito ";
$sql .= " left join tci_conceito_relacionamento on crt_conceito = cnt_codigo ";
$sql .= " left join tci_keyword on crt_termo = kw_codigo ";
$sql .= " where crt_rela = 'PD' ";
$sql .= " and kw_idioma = '".$idioma."' ";
$sql .= " order by kw_word ";
$rlt = db_query($sql);
while ($line = db_read($rlt))
	{
	$mst = trim($line['kw_word']);
	if (strlen($mst) == 0)
		{ $mst = trim($line['cnt_descricao']); }
	$link = '<A HREF="concept.php?dd0='.$line['cnt_codigo'].'">';	
	echo '<LI>';
	echo $link;
	echo trim($line['kw_word']);
	echo '</LI>';
	}
?>
</UL>
</TD></TR>
</table>
