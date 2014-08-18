<?php
require ("cab.php");
require ("../_class/_class_bris.php");
$bris = new bris;
$ano = '2013';

echo $bris->fi_journal($ano,0);
echo $bris->fi_journal($ano,2);
echo $bris->fi_journal($ano,3);
echo $bris->fi_journal($ano,5);

$ft = 2;
/* produção de indicador */
$journal = '0000039';
$sql = "select * from brapci_journal ";
$rrr = db_query($sql);
while ($line = db_read($rrr)) {
	$journal = $line['jnl_codigo'];
	echo '<BR>'.$line['jnl_nome'];
	/* Citacoes por ano */
	$art = array(0, 0, 0, 0, 0, 0);
	$di = array(0, 2, 3, 5);
	for ($r = 0; $r < count($di); $r++) {
		$ft = $di[$r];

		$ano1 = $ano - $ft;
		$ano2 = $ano - 1;
		if ($ft == 0) { $ano1 = $ano;
			$ano2 = $ano;
		}

		$sql = "select count(*) as total, ar_journal_id from mar_works 
					inner join brapci_article on m_bdoi = ar_bdoi
					INNER JOIN brapci_edition on ed_codigo = ar_edition
					where ar_journal_id = '$journal' and m_bdoi <> ''
					and (ed_ano >= $ano1 and ed_ano <= $ano2 )
					group by ar_journal_id
				
		";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$tot = $line['total'];
			$art[$ft] = $tot;
		}
	}
	$bris -> rank_atualiza($journal, $ano, $art[0], '10');
	$bris -> rank_atualiza($journal, $ano, $art[1], '11');
	$bris -> rank_atualiza($journal, $ano, $art[2], '12');
	$bris -> rank_atualiza($journal, $ano, $art[3], '13');
	$bris -> rank_atualiza($journal, $ano, $art[4], '14');
	$bris -> rank_atualiza($journal, $ano, $art[5], '15');
	
	$mv = $bris->calcula_meia_vida($ano,$journal);
	$bris -> rank_atualiza($journal, $ano, $mv, '20');
	
	$bris->calcular_indice_h_revista($journal);
	$ih = $bris->idh;
	$bris -> rank_atualiza($journal, $ano, $ih, '21');
	
}
$bris->calcula_fator_impacto_revista();

?>
