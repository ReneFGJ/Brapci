<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNЧеS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versуo atual           //    data     //
//---------------------------------------//
// 0.0a                       20/05/2008 //
///////////////////////////////////////////
if ($mostar_versao == True) {array_push($sis_versao,array("sisDOC (Digito Vereficador)","0.0a",20080520)); }

function DV_EAN13($form) {

  $factor = 3;
  $sum = 0;
  for ($index = strlen($form)-1; $index >= 0; $index = $index -1) 
  {
    $sum = $sum + round(substr($form,$index, 1)) * $factor;
    $factor = 4 - $factor;
  }

  $cc = ((1000 - $sum) % 10);
  return($cc);

//    7 (EAN/UCC-8), 11 (UCC-12), 12 (EAN/UCC-13), 13 (EAN/UCC-14) ou 17 (SSCC) dэgitos")
}

function DV_ISBN($form) {

  $factor = 2;
  $sum = 0;
  for ($index = strlen($form)-1; $index >= 0; $index = $index -1) 
  {
    $sum = $sum + round(substr($form,$index, 1)) * $factor;
    $factor = $factor + 1;
	if ($fator > 9) { $fator = 2; }
  }

  $cc = (($sum * 10) % 11);
  if ($cc == 10) { $cc = 'X'; }
//  if ($cc == 0) { $cc = 'X'; }
  return($cc);
}
?>