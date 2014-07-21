<?
if (strlen($include) == 0) { exit; }
function nbr_vancouver($refe)
	{
	///////////////////////////////////
	$ref = array();
	$s = $refe.chr(13);
	$ini = 0;
	while (strpos($s,chr(13)) > 0)
		{
		$ini++;
		$ln = substr($s,0,strpos($s,chr(13)));
		if (strlen($ln) > 0)
			{ array_push($ref,$ln); }
		$s = substr($s,strpos($s,chr(13))+1,strlen($s));			
		if ($ini > 20) { $s = ''; }
		}
	for ($i=0;$i < count($ref);$i++)
		{
		echo '<HR>';
		echo $ref[$i];
		echo '<BR>Autores';
		echo '==>';
		print_r(vancouver_autor($ref[$i]));
		echo '<BR>Vol, Nr.';
		echo '==>';
		echo vancouver_vn($ref[$i]);
		echo '<BR>Periodico';
		echo '==>';
		echo vancouver_periodico($ref[$i]);
		echo '<BR>Titulo';
		echo '==>';
		echo vancouver_title($ref[$i]);
		}
	}
function vancouver_title($auto)
	{
	$s = $auto;
	$auto = substr($auto,5,strlen($auto));
	$auto = substr($auto,strpos($auto,'.')+1,strlen($auto));
	$auto = substr($auto,0,strpos($auto,'.'));
	return($auto);
	}
function vancouver_periodico($auto)
	{
	$s = $auto;
	while (strpos($auto,';') > 6)
	{ $auto = substr($auto,strpos($auto,';')-5,strlen($auto)); }
	$publ = substr($s,0,strpos($s,$auto)-1);
	$s = $publ;
	while (strpos($s,'.') > 0)
		{ $s = substr($s,strpos($s,'.')+1,strlen($s)); }
	return($s);

	}
function vancouver_vn($auto)
	{
	while (strpos($auto,';') > 6)
		{
		$auto = substr($auto,strpos($auto,';')-5,strlen($auto));
		}
	$v = array('','','','','');
	$vano = substr($auto,0,strpos($auto,';'));
	$vnum = substr($auto,strpos($auto,';')+1,strlen($auto));
	if (strpos($vnum,':') > 0) { $vnum = substr($vnum,0,strpos($vnum,':')); }
	$vpag = substr($auto,strpos($auto,':')+1,strlen($auto));
	if (substr($vpag,strlen($vpag)-1,1) == '.') { echo '--'; $vpag = substr($vpag,0,strlen($vpag)-1); }
	$v[0] = $vano;
	$v[1] = $vnum;
	$v[3] = $vpag;
	print_r($v);
	}

function vancouver_autor($auto)
	{
	$autores = array();
	$aut = substr($auto,0,strpos($auto,'.'));
	if (sonumero(trim($aut)) == trim($aut))
		{ $auto = trim(substr($auto,strpos($auto,'.')+1,strlen($auto))); }
	if (strpos($auto,'.') > 0)
		{ $aut = substr($auto,0,strpos($auto,'.')); }
	///////////////// se existe autor separa-los
	if (strlen($aut) > 0)
		{$aut .= ',';}
	while (strpos($aut,',') > 0)
		{
		$auto = trim(substr($aut,0,strpos($aut,',')));
		if (strlen($auto) > 0) { array_push($autores,$auto); }
		$aut = trim(substr($aut,strpos($aut,',')+1,strlen($aut)));
		}
	return($autores);
	}
?>