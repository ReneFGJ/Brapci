<?
///************************* FUNCOES OAI
/// INTEGRIDADE DO ARQUIVO
//////////////////////////////////////// Processar autor
function oai_autor($nrz)
	{
	$nra = array();
	for ($nr = 0;$nr < count($nrz);$nr++)
		{
		$sa = $nrz[$nr];
		$sb = '';
		if (strpos($sa,';') > 0)
			{
			$sb = substr($sa,strpos($sa,';')+1,strlen($sa));
			$sa = substr($sa,0,strpos($sa,';'));
			}
		if (strlen($sa) > 0)
			{
			array_push($nra,array('',$sa,nbr_autor($sa,5),nbr_autor($sa,1),'','',$sb));
			$sx = substr($sx,strpos($sx,$txt)+1,strlen($sx));	
			}
		}
	//// Processa na base

	for ($nr = 0;$nr < count($nra);$nr++)
		{
		$xsql = "select * from brapci_autor ";
		$xsql .= " where autor_nome_abrev = '".UpperCaseSql($nra[$nr][2])."'";
		$xrlt = db_query($xsql);
		if (!($xline = db_read($xrlt)))
			{
			$xsql = "insert into brapci_autor ";
			$xsql .= "(autor_codigo,autor_nome,autor_nome_asc,";
			$xsql .= "autor_nome_abrev,autor_nome_citacao,autor_nasc,";
			$xsql .= "autor_lattes,autor_alias";
			$xsql .= ") values (";
			$xsql .= "'','".$nra[$nr][3]."','".UpperCaseSql($nra[$nr][3])."',";
			$xsql .= "'".UpperCaseSql($nra[$nr][2])."','".$nra[$nr][3]."','',";
			$xsql .= "'',''";
			$xsql .= ")";
			$xrlt = db_query($xsql);	
			echo '<BR>Cadastrado autor '.$nra[$nr][1].' '.$nra[$nr][2].' '.$nra[$nr][3];
			$xsql = "update brapci_autor set autor_codigo=lpad(id_autor,7,'0') where autor_codigo=''";			
			$xrlt = db_query($xsql);

			$xsql = "select * from brapci_autor ";
			$xsql .= " where autor_nome_abrev = '".UpperCaseSql($nra[$nr][2])."'";
			$xrlt = db_query($xsql);
			$xline = db_read($xrlt);
			}
		$xcod = $xline['autor_codigo'];
		$nra[$nr][0] = $xcod;
		}
	return($nra);
	}

function oai_listsets_equal($oai,$j)
	{
	$oai = troca($oai,'&','e');
	$sql = "select * from oai_listsets ";
	$sql .= " left join brapci_section on se_codigo = ls_equal ";
	$sql .= " where ls_setspec = '".$oai."' and ls_journal='".strzero($j,7)."' ";
	$zrlt = db_query($sql);

	if ($zline = db_read($zrlt))
		{
			$tipo = $zline['ls_tipo'];
			if ($tipo == 'E')
				{	
					$result = array(1,$zline['se_descricao'],$zline['ls_equal_ed'],$zline['ls_tipo'],'');
				} else {
					$equal = trim($zline['ls_equal']);
					if (strlen($equal) > 0)
						{
							$result = array($zline['se_processar'],$zline['se_descricao'],$zline['ls_equal'],trim($zline['ls_tipo']),'');
						} else {
							$slink = '<input type="submit" name="acao" value="associar seção" onclick="newxy('.chr(39).'oai/listsets_equal.php?dd0='.$zline['id_ls'].chr(39).',600,230);">';
							$result = array(-1,'','',0,$slink);
						}
				}
		} else {
			$result = array(-1,'','',0,'ListSets Não existe nesta publicação');
			$sql = "insert into oai_listsets ";
			$sql .= "( ls_setspec, ls_setname, ls_setdescription, ";
			$sql .= "ls_journal, ls_status, ls_data ";
			$sql .= " )";
			$sql .= " values ";
			$sql .= "('".$oai."','".$oai."','$setDescription',";
			$sql .= "'".strzero($j,7)."','A',".date("Ymd");
			$sql .= ")";
			$xrlt = db_query($sql);
		}
		return($result);
	}
function oai_edicao($oai)
	{
	$oai = ' '.LowerCase($oai);
	$edicao = array('','','','','');
	// Volume, Numero, Ano, Temática
	if (strpos($oai,'vol.') > 0)
		{ 
			$vi = strpos($oai,'vol.'); 
			$vol = substr($oai,$vi+4,strlen($oai));
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,'(') > 0) { $vol = substr($vol,0,strpos($vol,'(')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			$v = trim($vol);
		}
	if (strpos($oai,'v.') > 0)
		{ 
			$vi = strpos($oai,'v.'); 
			$vol = substr($oai,$vi+2,strlen($oai));
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,'(') > 0) { $vol = substr($vol,0,strpos($vol,'(')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			if (strpos($vol,' ') > 0) { $vol = substr($vol,0,strpos($vol,' ')); }
			$v = trim($vol);
		}		
	if ((strpos($oai,'vol ') > 0) and (strlen($v) == 0))
		{ 
			$vi = strpos($oai,'vol '); 
			$vol = substr($oai,$vi+4,strlen($oai));
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,'(') > 0) { $vol = substr($vol,0,strpos($vol,'(')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			$v = trim($vol);
		}		
	if (strpos($oai,'no ') > 0)
		{ 
			$vi = strpos($oai,'no '); 
			$vol = substr($oai,$vi+2,strlen($oai));
			echo '['.$vol.']';
//			exit;
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,'(') > 0) { $vol = substr($vol,0,strpos($vol,'(')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			$n = trim($vol);
		}		
	if (strpos($oai,'n.') > 0)
		{ 
			$vi = strpos($oai,'n.'); 
			$vol = substr($oai,$vi+2,strlen($oai));
			echo '['.$vol.']';
//			exit;
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,'(') > 0) { $vol = substr($vol,0,strpos($vol,'(')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			if (strpos($vol,' ') > 0) { $vol = substr($vol,0,strpos($vol,' ')); }
			$n = trim($vol);
		}
	///////////////////////////// ano
	if (strpos($oai,'no') > 0)
		{ 
			$vi = strpos($oai,'('); 
			$vol = substr($oai,$vi+1,strlen($oai));
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,')') > 0) { $vol = substr($vol,0,strpos($vol,')')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			$a = trim($vol);
		}		
	if (strpos($oai,'n.') > 0)
		{ 
			$vi = strpos($oai,'n.'); 
			$vol = substr($oai,$vi+4,strlen($oai));
			if (strpos($vol,',') > 0) { $vol = substr($vol,0,strpos($vol,',')); }
			if (strpos($vol,')') > 0) { $vol = substr($vol,0,strpos($vol,')')); }
			if (strpos($vol,'.') > 0) { $vol = substr($vol,0,strpos($vol,'.')); }
			$a = trim($vol);
		}		

	$edicao[0] = sonumero($v);
	$edicao[1] = sonumero($n);
	$edicao[2] = sonumero($a);
	return($edicao);
	}

function oai_integridade($oai)
	{
	$oai = ' '.$oai;
	$ok = 0;
	if (strpos($oai,'<OAI-PMH') > 0) { $ok++; }
	if (strpos($oai,'</OAI-PMH') > 0) { $ok++; }
	return($ok == 2);
	}
	
function oai_string($oai,$si,$sf)
	{
	$result = array();
	$oai = ' '.$oai;
	while (strpos($oai,$si) > 0)
		{
		$sp = strpos($oai,$si)+strlen($si);
		$se = strpos($oai,$sf);
		$sa = substr($oai,$sp,$se-$sp);
		array_push($result,$sa);
		$oai = ' '.substr($oai,$se+strlen($sf),strlen($oai));
		}
	return($result);
	}
	
function oai_content($oai,$id)
	{
		$si = '<'.$id;
		$sf = '</'.$id;
		$oai = ' '.$oai;
		$sp = strpos($oai,$si);
		$se = strpos($oai,$sf);
		$sa = substr($oai,$sp,$se-$sp);
		$sa = substr($sa,strpos($sa,'>')+1,strlen($sa));
		return($sa);
	}

function oai_identifier($oais)
	{
		return(oai_content($oais,'identifier'));
	}
?>