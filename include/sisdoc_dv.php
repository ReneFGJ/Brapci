<?
/**
* Esta classe й a responsбvel pelo tratamento de mensagens do sistema.
* @author Rene F. Gabriel Junior <rene@sisdoc.com.br>
* @version 0.0a
* @copyright Copyright © 2011, Rene F. Gabriel Junior.
* @access public
* @package BIBLIOTECA
* @subpackage digito_verificador
*/
///////////////////////////////////////////
// Versгo atual           //    data     //
//---------------------------------------//
// 0.0a                       20/05/2008 //
///////////////////////////////////////////
if ($mostar_versao == True) {array_push($sis_versao,array("sisDOC (Digito Vereficador)","0.0a",20080520)); }
/**
 * Funзгo para calcular o digito verificador do EAN13.
 * @access public
 * @param [String] $xnum Nъmero para calculo do DV para EAN13.
 * @return [Integer] Nъmero do dнgito DV
*/
function DV_EAN13($xnum) {

  $factor = 3;
  $sum = 0;
  for ($index = strlen($xnum)-1; $index >= 0; $index = $index -1) 
  {
    $sum = $sum + round(substr($xnum,$index, 1)) * $factor;
    $factor = 4 - $factor;
  }

  $cc = ((1000 - $sum) % 10);
  return($cc);

//    7 (EAN/UCC-8), 11 (UCC-12), 12 (EAN/UCC-13), 13 (EAN/UCC-14) ou 17 (SSCC) dнgitos")
}
/**
 * Funзгo para calcular o digito o ISBN de um livro.
 * @access public
 * @param [String] $xnum Nъmero para calculo do DV para ISBN.
 * @return [Integer] Nъmero do dнgito ISBN
*/
function DV_ISBN($xnum) {

  $factor = 2;
  $sum = 0;
  for ($index = strlen($xnum)-1; $index >= 0; $index = $index -1) 
  {
    $sum = $sum + round(substr($xnum,$index, 1)) * $factor;
    $factor = $factor + 1;
	if ($fator > 9) { $fator = 2; }
  }

  $cc = (($sum * 10) % 11);
  if ($cc == 10) { $cc = 'X'; }
//  if ($cc == 0) { $cc = 'X'; }
  return($cc);
}
?>