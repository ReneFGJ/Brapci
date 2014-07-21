<?php
session_start();
    /**
     * Calculadora PHP
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com> (Analista-Desenvolvedor)
	 * @copyright Copyright (c) 2011 - sisDOC.com.br
	 * @access public
     * @version v0.11.29
	 * @package Include
	 * @subpackage Apoio
     */?>
<style>
.DISPLAY
	{
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 28px;
	width: 40px;
	height: 25px;
	border : 1px solid;
	padding : 5px 5px 5px 5px;	
	}
.B1
	{
	background-color : #FDF5E6;
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 16px;
	color : Black;
	width: 40px;
	height: 30px;
	}
.B2
	{
	background-color : #d1d1d1;
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 16px;
	color : Black;
	width: 40px;
	height: 30px;
	}
.B3
	{
	background-color : #fdcccc;
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 16px;
	color : Black;
	width: 40px;
	height: 30px;
	}
</style>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<?
session_start();
class calculadora
	{
	var $display;
	var $acao;
	var $memoria;
	
	function saldo()
		{ 
		$this->display = $_SESSION['saldo']; 
		$this->acao = $_SESSION['acao']; 
		$this->memoria = $_SESSION['memoria']; 
		
		return($this->display); 
		}
	function saldo_grava()
		{ 
		$_SESSION['saldo'] = $this->display; 
		$_SESSION['acao'] = $this->acao; 
		$_SESSION['memoria'] = $this->memoria; 
		}
	function calcular()
		{
		$v1 = $this->memoria;
		$v2 = $this->display;
		$op = $this->acao;
		if ($op == '+') { $vr = $v1+$v2; }
		if ($op == '-') { $vr = $v1-$v2; }
		if ($op == 'X') { $vr = $v1*$v2; }
		if ($op == '/') { $vr = $v1/$v2; }
		return($vr);
		}
		
	function botao()
		{
		$num = @$_POST['nr'];
		$vlr = $this->saldo();
		if ((strpos(' 0123456789.',$num) > 0) or ($num=='00')) { $vlr = ($vlr.$num); }
		
		if ($num == 'CE') { $vlr = '0'; $this->acao = ''; $this->memoria = 0; }
		
		if ($num == '+-') { $vlr = $vlr * (-1); }

		if (strpos(' -+/X',$num) > 0) { $funcao = True; } else {$funcao = False; }

		if ($num == '=') { $vlr = $this->calcular(); $this->memoria = $vlr; $this->acao = ''; }
		if (($funcao == true) and (!empty($this->acao))) { $vlr = $this->calcular(); }
		if ($funcao == true) { $this->memoria = $vlr;  $vlr = '0'; $this->acao = $num; }
		$this->display = $vlr;
		$this->saldo_grava();
		}
	function resultado()
		{
		$this->saldo();
		$this->botao();
		$this->saldo_grava();
		return(round($this->display*100)/100);
		}
	}
	
$bts = array(); $btc = array();
array_push ($bts,array('Mc','M-','M+','+-','CE'));
array_push ($bts,array('7','8','9','%','&laquo;'));
array_push ($bts,array('4','5','6','X','/'));
array_push ($bts,array('1','2','3','+','-'));
array_push ($bts,array('0','00','.','='));

array_push ($btc,array('B2','B2','B2','B2','B3'));
array_push ($btc,array('B1','B1','B1','B2','B2'));
array_push ($btc,array('B1','B1','B1','B2','B2'));
array_push ($btc,array('B1','B1','B1','B2','B2'));
array_push ($btc,array('B1','B1','B1','B2'));

$calc = new calculadora();
echo '<TABLE>';
echo '<TR><TD colspan="5"><form method="post"></TD></TR>';
echo '<TR><TD colspan="5" align="right" class="DISPLAY">';
echo $calc->resultado();
echo '</TD></TR>';
for ($rx=0;$rx < count($bts);$rx++) {
echo '<TR>';
for ($ry=0;$ry < count($bts[$rx]);$ry++) {
echo '<TD>';
echo '<input type="submit" name="nr" value="'.$bts[$rx][$ry].'" class="'.$btc[$rx][$ry].'">';
} }
echo '<TR><TD colspan="5"></form></TD></TR>';
echo '</TABLE>';
?>
