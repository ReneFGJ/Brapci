<?
class produtividade
	{
	var $codigo;
	var $descricao;
	
	function mosta($tp)
		{
		$rs = '';
		if ($tp == '0') { $rs = ''; }
		if ($tp == '2') { $rs = 'Nível 1A'; }
		if ($tp == '3') { $rs = 'Nível 1B'; }
		if ($tp == '4') { $rs = 'Nível 1C'; }
		if ($tp == '5') { $rs = 'Nível 1D'; }
		if ($tp == '6') { $rs = 'Nível 2'; }
		if ($tp == '9') { $rs = '??'; }
		return($rs);
		}
	}
	
/*
<UL>
<LI>0 - Não</LI>
<LI>2 - Nivel 1A</LI>
<LI>3 - Nivel 1B</LI>
<LI>4 - Nível 1C</LI>
<LI>5 - Nível 1D</LI>
<LI>6 - Nível 2</LI>
</UL> 
?*
?>