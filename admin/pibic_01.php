<?php
require("cab.php");

/*
 * $breadcrumbs
 */
$breadcrumbs = array();
array_push($breadcrumbs,array(http.'admin/index.php',msg('principal')));
array_push($breadcrumbs,array(http.'admin/index.php',msg('menu')));
echo '<div id="breadcrumbs">'.breadcrumbs().'</div>';

$sql = "
	select * from (
		select pg_cpf, pg_agencia, pg_conta 
		from pibic_pagamentos group by pg_cpf, pg_agencia, pg_conta
	) as tabela
	left join pibic_aluno on pg_cpf = pa_cpf
";
$rlt = db_query($sql);

$sx = '<table>';
$sx .= '<TR><TH>CPF<TH>CPF<TH>Nome<TH>Ag<TH>Ag';
while ($line = db_read($rlt))
{
	$ag1 = sonumero($line['pg_agencia']);
	$ag2 = sonumero($line['pg_conta']);
	$ag3 = sonumero($line['pa_cc_agencia']);
	$ag4 = sonumero($line['pa_cc_conta']);
	$cpf = trim($line['pa_cpf']);
	
	$sx .= '<TR>';
	$sx .= '<TD class="tabela01">';
	$sx .= $line['pg_cpf'];
	$sx .= '<TD class="tabela01">';
	$sx .= $line['pa_cpf'];
	$sx .= '<TD class="tabela01">';
	$sx .= $line['pa_nome'];
	$sx .= '<TD class="tabela01">';
	$sx .= $ag1;
	$sx .= '<TD class="tabela01">';
	$sx .= $ag3;
	$sx .= '<TD class="tabela01">';
	$sx .= $ag2;
	$sx .= '<TD class="tabela01">';
	$sx .= $ag4;
	
	if (($age4 != $age2) or ($ag1 != $ag3))
		{
			$sx .= '<TD class="tabela01">';
			$sx .= 'Diferente';
			
			$sql = "update pibic_aluno set 
				pa_cc_agencia = '$ag1',
				pa_cc_conta  = '$ag2'
			where 
				pa_cpf = '".$cpf."' ";
			//$rrr = db_query($sql);			
		}
}
$sx .= '</table>';
echo $sx;
require("../foot.php");	
?>