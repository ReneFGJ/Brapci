<?
function status_artigo($stst)
	{
	if ($stst=='A') { $stst = '1º cadastro / indexação'; }
	if ($stst=='B') { $stst = '1º conferência'; }
	if ($stst=='C') { $stst = '2º conferência'; }
	if ($stst=='P') { $stst = 'Publicado'; }
	return($stst);
	}
?>