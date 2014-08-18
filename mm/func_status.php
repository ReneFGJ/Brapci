<?
function status_artigo($stst)
	{
	if ($stst=='A') { $stst = '1К cadastro / indexaчуo'; }
	if ($stst=='B') { $stst = '1К conferъncia'; }
	if ($stst=='C') { $stst = '2К conferъncia'; }
	if ($stst=='P') { $stst = 'Publicado'; }
	return($stst);
	}
?>