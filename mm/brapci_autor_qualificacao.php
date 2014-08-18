<?
function qualificacao($x)
	{
	$prof = $x['ae_professor'];
	$estu = $x['ae_aluno'];
	$mest = $x['ae_mestrado'];
	$dout = $x['ae_doutorado'];
	$pros = $x['ae_profissional'];
	$rt = '';
	if ($estu == 1)
		{
		if ($mest == 1)
			{ $r = 'Mestrando '; } else
			{ if ($dout == 1) 
				{ $r = 'Doutorando '; } else { $r = 'Estudante'; }
			}
		} else {
			if ($dout == 1) 
					{ $r = 'Doutor '; } else
					{ if ($mest == 1) 
						{ $r = 'Mestre '; } 
					}
			if ($prof == 1) { $r = 'Prof.(a) '.$r; }						
		}
		return($r);
	}
?>