<?
$m1 = "Atributos do conceito";
$ms2 = "Tipo";
$ms3 = "Idioma";
$ms4 = "Valor";
$ms5 = "Inserir relacionamento com outros conceitos";

$ms11 = 'Relacionamentos';
$ms12 = 'Tipo';
$ms13 = 'idioma';
$ms14 = 'Termo';
if($idioma == 'en')
	{
	$ms1 = 'Attributes';
	$ms2 = 'Type';
	$ms3 = 'Language';
	$ms4 = 'Value';
	$ms5 = '';
	}
	/////////////////////////////////////////////////////// VALORES
$sx = '<table width="100%" border="1">';
$sx .= '<TR><TD colspan="3">'.$ms1.'</TD></TR>';
$sx .= '<TR><TH width="35%">'.$ms2.'</TH><TH width="10%">';
$sx .= $ms3.'</TH><TH width="55%">'.$ms4.'</TH></TR>';
///////////////////////////////////////////////////////////

$sql = "select * from tci_conceito_relacionamento ";
$sql .= " inner join tci_keyword on crt_termo = kw_codigo ";
$sql .= " inner join tci_type on tp_codigo = crt_rela ";
$sql .= " where crt_conceito = '".$dd[0]."' and (crt_rela = 'PD' or crt_rela = 'HD' or crt_rela = 'UP' or crt_rela = 'US'  or crt_rela = 'TR')";
$rlt = db_query($sql);
while ($line = db_read($rlt))
	{
	$tp = $line['tp_codigo'];
	if ($tp == 'PD') { array_push($ttt,array(trim($line['kw_word']),$line['kw_idioma'])); }
	if ($tp == 'UP') { array_push($ttu,array(trim($line['kw_word']),$line['kw_idioma'])	); }
	
	$sx .= '<TR>';
	$sx .= '<TD>';
	$sx .= trim($line['tp_descricao']);
	$sx .= ' ('.trim($line['tp_xml']).')';
	$sx .= '<TD align="center">';
	$sx .= trim($line['kw_idioma']);
	$sx .= '<TD>';
	$sx .= trim($line['kw_word']);
	$sx .= '</TD></TR>';
	}
$sx .= '</table>';
if (strlen($img_ext) == 0) { echo $sx; }
?>

<?

require("class_note.php");
require("class_broader.php");
require("class_narrower.php");
require("class_relation.php");


if (strlen($img_ext) == 0)
	{
	if (strlen($dd[1]) > 0)
		{
			if ($dd[1] == '1') { require('class_propriety_ed.php'); }
			if ($dd[1] == '2') { require('class_relation_ed.php'); }
			if ($dd[1] == '3') { require('class_note_ed.php'); }
		} else {
			$link1 = '<A HREF="concept.php?dd0='.$dd[0].'&dd1=1">';
			$link2 = '<A HREF="concept.php?dd0='.$dd[0].'&dd1=2">';
			$link3 = '<A HREF="concept.php?dd0='.$dd[0].'&dd1=3">';
			?>
			<?=$link2;?><img src="img/icone_concept_hiearquia.jpg" width="32" height="32" alt="" border="0" title="<?=$ms5;?>"></A>
			<?=$link3;?><img src="img/icone_note.jpg" width="32" height="32" alt="" border="0" title="<?=$ms5;?>"></A>
			<?
		}
	}
?>