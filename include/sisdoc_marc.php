<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0b                       20/05/2008 //
// 0.0a                       12/08/2007 //
///////////////////////////////////////////
if ($mostar_versao == True) {array_push($sis_versao,array("sisDOC (Marc)","0.0a",20080520)); }

global $marc_field, $marc_indicador, $marc_campo;
$marc_field = array();
$marc_indicador = array();
$marc_campo = array();

function cutter($autor)
	{
	$ok=0;
	if (strlen($autor) == 0) { $ok = 1; echo 'X'; }
	$aut = $autor;
	while ($ok == 0)
		{
		$xsql = "select * from apoio_cutter where cutter_nome like '".$aut."%'";
		$rlt = db_query($xsql);
		if ($line = db_read($rlt))
			{ 
			$xmone = trim($line['cutter_nome']);
			$nomes = trim(substr($autor,0,strlen($xmone)));
			if ($nomes >= $xmone) { $ok = 1; }
			}
		$aut = substr($aut,0,strlen($aut)-1);
		}

	$rlt = db_query($xsql);
	$autores = array();
	while ($line = db_read($rlt))
		{ 
		array_push($autores,array($line['cutter_codigo'],trim($line['cutter_nome']))); 
		}
	if (count($autores) == 1)
		{
			$cutter = substr($autor,0,1).$autores[0][0];
		} else {
		for ($k = 0;$k < count($autores);$k++)
			{
			$nomes = trim(substr($autor,0,strlen(trim($autores[$k][1]))));
			if ($nomes >= $autores[$k][1]) 
				{ 
				$cutter = substr($autor,0,1).$autores[$k][0];
				}
			}
		}
		return($cutter);
	}
	
function array_find($ar_fld,$ar_cmp)
	{
	global $dd;
	for ($kk=0;$kk < count($ar_fld);$kk++)
		{
		if ($ar_fld[$kk][1] == $ar_cmp) { return($dd[$kk]); 	}
		}
	return('');
	}

function array_create($ar_fld,$ar_ch)
	{
	$ar_fld = troca($ar_fld,$ar_ch,'¢').'¢';
	$ar_cp = array();
	while (strpos($ar_fld,'¢') > 0)
		{
		$ar_fld_ln = trim(substr($ar_fld,0,strpos($ar_fld,'¢')));
		$ar_fld = substr($ar_fld,strpos($ar_fld,'¢')+1,strlen($ar_fld));
//		echo '<BR>--'.$ar_fld_ln;
		array_push($ar_cp,trim($ar_fld_ln));
		}
	return($ar_cp);
	}

function marc_edit($cpf,$tlt,$fld)
	{
	global $cp,$dd;
	$afld = array_create($fld,chr(13));
	array_push($cp,array('$H8',$dd[0],'ID',False,False,-1));
	array_push($cp,array('$A8',$dd[0],trim($tlt).' ('.trim($cpf).')',False,False,-1));
	
	for ($k=0;$k <= count($afld);$k++)
		{
		$cpt = array_create($afld[$k],';');
		if (strlen($cpt[2]) > 0)
			{
			array_push($cp,array($cpt[2],$cpt[0],$cpt[3],False,True,-1));
			}
		}
	}

function marc_extrai($z1,$z2)
	{
	$pos = strpos($z2, $z1);
	if ($pos === false)
		{ return(''); }
	else
		{
		$z3 = substr($z2,$pos+2,strlen($z2));
		
		if (strpos($z3,'$') > 0)
			{
			$z3 = trim(substr($z3,0,strpos($z3,'$')));
			}
		$z3 = trim($z3);
		if (substr($z3,strlen($z3)-1,1) == '/') {$z3 = trim(substr($z3,0,strlen($z3)-1)); }
		if (substr($z3,strlen($z3)-1,1) == ':') {$z3 = trim(substr($z3,0,strlen($z3)-1)); }
		if (substr($z3,strlen($z3)-1,1) == '.') {$z3 = trim(substr($z3,0,strlen($z3)-1)); }
		if (substr($z3,strlen($z3)-1,1) == ';') {$z3 = trim(substr($z3,0,strlen($z3)-1)); }
		if (substr($z3,strlen($z3)-1,1) == '-') {$z3 = trim(substr($z3,0,strlen($z3)-1)); }
//		echo '<BR>'.$z1."==".$z3.'=='.$pos;
		return(trim($z3));
		}
	}
function marc21($texto)
	{
	global $marc_field, $marc_indicador, $marc_campo;
	$xax = "";
//	echo $texto."<HR>";
	for ($ixx = 0; strlen($texto) > $ixx; $ixx++)
		{
		$ch = substr($texto,$ixx,1);
		if ($ch == chr(10)) { $ch = ''; }
		if ($ch == chr(13))
			{
			if (strlen($xax) > 0)
				{
				$field = substr($xax,0,3);
				if ((substr($field,0,2) != '00') and (substr($field,0,3) != 'LDR'))
					{
					$indic = substr($xax,3,3);
					$campo = substr($xax,5,strlen($xax));
					} else {
					$indic = '__';
					$campo = substr($xax,3,strlen($xax));
					}
				array_push($marc_field,$field);
				array_push($marc_indicador,$indic);
				array_push($marc_campo,$campo);
//				echo "<BR>====".substr($xax,0,3);
				}
			$xax = "";
			} else {
			$xax = $xax . $ch;
			}
		}
	}
?>