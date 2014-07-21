<?php
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       25/10/2008 //
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Abreviatura)","0.0a",20081025)); }

function AbreviaturaPeriodico($tx)
	{
		$tx = ' '.UpperCaseSql($tx).' ';
		//////////// artigos, preposição
		$elm = array(
		' Da ',' De ',' Do ',' com ',
		' das ',' dos ',' em ',
		' as ', ' e '
		);
	/////////////// Ação de eliminação
	for ($k = 0;$k < count($elm);$k++)
		{ $tx = troca($tx,UpperCaseSql($elm[$k]),' '); }

		///////////// Eliminas palavras
		$elm = array('Revista','Caderno','Repositório',
		'Magazine','Acervo','Anais','Seminário','Anuário',
		'Archives','Arquivos','Boletim','Journal',
		'Cadernos','Colloquium','Debate','Debates',
		'Resumo','Resumos','Abstract','Resume'
		);		
	/////////////// Ação de eliminação
	for ($k = 0;$k < count($elm);$k++)
		{ $tx = troca($tx,UpperCaseSql($elm[$k]),''); }
		
	////////////// Abrevia Termos
	$tx = troca($tx,'ACTA','A');
	$tx = troca($tx,'CIENCIAS','C');
	$tx = troca($tx,'CIENCIA','C');
	$tx = troca($tx,'UNIVERSITAS','U');
	
	$tx1 = str_word_count($tx,1);
	$tx2 = $tx1;
	
	$ok = 0;
	$lp = 0;
	$max = 3;
	while (($ok == 0) and ($lp < 5))
		{
		$lp++; // aumenta sair do looping
		$ABR = '';;
		for ($k=0;$k < count($tx1);$k++)
			{ $tx2[$k] = substr(trim($tx1[$k]),0,$max); $ABR .= trim($tx2[$k]); }
		if (strlen($ABR) > 5) { $ok = 1; } /// Se nome for mais, pula
		$max++;
		}
	$ABR = substr($ABR,0,5);
	while (strlen($ABR) < 5) { $ABR .= '0'; }
	return($ABR);
	}
?>