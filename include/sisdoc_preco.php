<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       20/05/2008 //
///////////////////////////////////////////
function preco($pr1,$pr2)
	{
	$preco = Number_Format(($pr1/100),2);
	if ($pr2 > 0) { $preco = '<S>'.$preco.'</S> por <B>'.Number_Format($pr2/100,2).'</B>'; }
	return($preco);
	}
?>