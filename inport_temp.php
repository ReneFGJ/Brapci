<?php
require ("db.php");
require ($include . 'sisdoc_debug.php');

require ("_class/_class_oai.php");
$oai = new oai;
$dd0 = $dd[0];

if (round($dd0) > 11000) { exit; }

$ID = strzero($dd0, 10);
$secu = 'brapci';

$ln = substr(md5($ID . $secu), 0, 5);
echo $ID;
echo '<BR>' . $lk;
echo '<BR>' . $ln;
echo '<BR>';

$sql = "select count(*) as total, ar_status, ar_codigo
				from  brapci_article
				left join mar_works on ar_codigo = m_work
				where ar_codigo = '$ID'";
$rlt = db_query($sql);
if ($line = db_read($rlt)) {
	$to = $line['total'];
	echo '===>'.$to;
	
	if (strlen($line['ar_codigo'])==0) {
			 $to = 9999;
			 echo $sql.'<BR>'; 
			 echo '<font color="red">Não existe</font><BR>';
	}
	echo '===>'.$to;
	
	if ($to <=1 ) {
		$link = 'http://www.brapci.ufpr.br/documento.php?dd0=' . $ID . '&dd1=' . $ln;

		$s = $oai -> read_link($link);
		$pos = strpos($s, 'REFERÊNCIAS');
		if ($pos > 0) {
			$s = substr($s, $pos, strlen($s));
			$pos = strpos($s, '</table>Total de');
			$s = substr($s, 0, $pos);
			$pos = strpos($s, '<TR><TD>');
			$s = substr($s, $pos, strlen($s));

			$s = troca($s, '<TR><TD>', '¢');

			$sx = splitx('¢', $s);

			for ($r = 0; $r < count($sx); $r++) {
				echo '<BR>' . chr(13) . chr(10);
				$sa = $sx[$r];
				$sa = substr($sa, 0, strpos($sa, '<BR>&nbsp;'));
				$sa = troca($sa, chr(13), '');
				$sa = troca($sa, chr(10), '');

				$sql = "insert into mar_works 
			(
			m_status, m_ref, m_title, 
			m_author, m_codigo, m_journal,
			m_tipo, m_work, m_ano,
			m_doi, m_bdoi, m_first_page,
			m_norma, m_idioma
			) values (
			'@','$sa','',
			'','','',
			'','$ID','',
			'','','',
			'ABNT',''
			);
			";
			$rrr = db_query($sql);
			}
			echo '<PRE>'.$s.'</PRE>';
			//mail('renefgj@gmail.com','Inserido '.$ID,$ID.chr(13).chr(10).'Incorporado '.count($sx).' referencias'.chr(13).chr(10).chr(13).chr(10).$s,'','');		
			echo '<BR>Incorporado '.count($sx).' referencias';
			//exit;			
		} else {
			echo 'REFERENCIAS NAO LOCALIZADAS';
		}
	} else {
		echo '<BR>Já lancado ou não existe registro';
	}
} else {
	echo 'ERRO';
}
echo '<meta http-equiv="refresh" content="2;url=http://www.brapci.inf.br/inport_temp.php?dd0='.(round($ID)+1).'"> '
?>	