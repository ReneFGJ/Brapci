<?
function status_artigo($stst)
	{
	if ($stst=='A') { $stst = '1� cadastro / indexa��o'; }
	if ($stst=='B') { $stst = '1� confer�ncia'; }
	if ($stst=='C') { $stst = '2� confer�ncia'; }
	if ($stst=='P') { $stst = 'Publicado'; }
	return($stst);
	}
?>