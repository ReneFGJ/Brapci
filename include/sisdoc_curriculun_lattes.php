<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       19/12/2009 //
///////////////////////////////////////////
if ($mostar_versao == True) {array_push($sis_versao,array("sisDOC (Curriculun Lattes)","0.0a",20091219)); }
  
function lattes_recupera_producao($txt,$tipo)
	{	  
	$prod = array();	
	$xi = strpos($txt,$tipo);
	if ($xi > 0)
		{						   
		$ttt = substr($txt,$xi,strlen($txt));
		$ttt = substr($ttt,0,strpos($ttt,'"agrupadorsub"'));  
		echo $ttt;		
		while (strpos($ttt,'textoProducao') > 0)
			{
			$xi = strpos($ttt,'textoProducao')+15;
			$ttt = substr($ttt,$xi,strlen($ttt));
			$ttp = trim(substr($ttt,0,strpos($ttt,'</td>'))); 
			$ttp = tiralinks($ttp);
			array_push($prod,$ttp);				  
			}
		}						
		for ($kz=0;$kz < count($prod); $kz++)
			{
			$atrs = lattes_autores($prod[$kz]);	
			$titulo = lattes_titulo($prod[$kz],'A');	
			$revista = lattes_pb($prod[$kz]);	 
			echo '<HR>'.$titulo; 	
			echo '<BR><I>autores</I> ';	  
			for ($kr = 0;$kr < count($atrs); $kr++)
				{				  
				if ($kr > 0) { echo '; '; }
				echo $atrs[$kr][0];
				}						   
				echo ' ';
			echo '<B>'.$revista[0].'</B>';
			if (strlen($revista[1]) > 0) { echo ', '.$revista[1]; }
			if (strlen($revista[2]) > 0) { echo ', '.$revista[2]; }
			if (strlen($revista[3]) > 0) { echo ', '.$revista[3]; }
			if (strlen($revista[4]) > 0) { echo ', '.$revista[4]; }
			if (strlen($revista[5]) > 0) { echo ', '.$revista[5]; }
			if (strlen($revista[6]) > 0) { echo ', '.$revista[6]; }
			if (strlen($revista[7]) > 0) { echo ', '.$revista[7]; }
			}	
	}
function lattes_pb($ddx)
	{
	$dda = array('','','','','','','','');
	// Publicação, Local, vol, num, mes, pag, ano, codigo
	$tz = 7;
	$ddx = trim(charConv($ddx));
	$ddx = substr($ddx,0,strlen($ddx)-1); 
	$ddx = troca($ddx,'p. ','p* ');
	$ddx = troca($ddx,'v. ','v* ');
	$ddx = troca($ddx,'n. ','n* ');
	
	while (strpos($ddx,'.') > 0)
		{
		$ddx = substr($ddx,strpos($ddx,'.')+1,strlen($ddx));
		}
	$ddx = troca($ddx,'*','.');
	if (strpos($ddx,'v.') > 0) { $dda[2] = substr($ddx,strpos($ddx,'v.'),strlen($ddx)); $dda[2] = substr($dda[2],0,strpos($dda[2],',')); }										
	if (strpos($ddx,'n.') > 0) { $dda[3] = substr($ddx,strpos($ddx,'n.'),strlen($ddx)); $dda[3] = substr($dda[3],0,strpos($dda[3],',')); }										
	if (strpos($ddx,'p.') > 0) { $dda[5] = substr($ddx,strpos($ddx,'p.'),strlen($ddx)); $dda[5] = substr($dda[5],0,strpos($dda[5],',')); }										
	$revista = substr($ddx,0,strpos($ddx,','));
	$local = substr($ddx,strpos($ddx,',')+1,strlen($ddx));
	$local = substr($local,0,strpos($local,','));
	if (strpos($local,'.') > 0) { $local = substr($local,0,strpos($local,'.')); }
	//////////////////////////		  
	if (substr($revista,0,1) == '.') { $revista = trim(substr($revista,1,strlen($revista))); }
	$dda[0] = trim($revista);
	if (strlen($local) > 4) { $dda[1] = $local; }
	///////////////////////////////
	$sqlf = "select * from gk_journal where jid_nome_asc = '".UpperCaseSql($revista)."' ";
	$rrr = db_query($sqlf);
	if (!($rline = db_read($rrr)))
		{
			$sqli = "insert into gk_journal ";
			$sqli .= "(jid_nome, jid_nome_alt, jid_issn, ";
			$sqli .= "jid_isbn, jid_cidade, jid_tipo, ";
			$sqli .= "jid_oai, jid_http, jid_oai_update, ";
			$sqli .= "jid_ano_ini, jid_ano_fim, jid_ativo, ";
			$sqli .= "jid_periodicidade_atual, jid_nome_abreviado, ";
			$sqli .= "jid_artigo_total, jid_issue, jid_sobre, ";
			$sqli .= "jid_nome_asc, jid_codigo ";
			$sqli .= ") values (";
			$sqli .= "'".$revista."','".$revista."','',";
			$sqli .= "'','','P',";
			$sqli .= "'','',".date("Ymd").",";
			$sqli .= "'','','1',";
			$sqli .= "'','',";
			$sqli .= "0,0,'',";
			$sqli .= "'".UpperCaseSql($revista)."',''";
			$sqli .= ")";
			$rrr = db_query($sqli);

			$sqli = "update gk_journal set jid_codigo = lpad(id_jid,7,0) where jid_codigo = '' ";
			$rrr = db_query($sqli);
			$rrr = db_query($sqlf);
			$rline = db_read($rrr);
		}  
		$codigo = $rline['jid_codigo'];
	/////////////////////////// ano
	while (strpos($ddx,',') > 0)
		{
		$ddx = substr($ddx,strpos($ddx,',')+1,strlen($ddx));
		}
	$dda[6] = $ddx;
	$dda[7] = $codigo;
	return($dda);
	
	} 
	
function lattes_titulo($ddx,$tp)
	{
	if ($tp == 'A')
		{
			$tit = trim(substr($ddx,strpos($ddx,' .')+2,strlen($ddx)));
			$tit = substr($tit,0,strpos($tit,'. '));
		}														 
	return($tit);
	}	
function lattes_autores($aut)
	{	 
	$autores = array();								
	$autor = charConv(trim(substr($aut,0,strpos($aut,' .'))));
	if (strlen($autor) > 0)
		{			   
		$autor .= ';';	 
		$autor = tiralinks($autor);

		while (strpos($autor,';') > 0)
			{									
				$cod = '';
				$at1 = substr($autor,0,strpos($autor,';'));
				$autor = ' '.trim(substr($autor,strpos($autor,';')+1,strlen($autor)));	
				$autor = trim($autor);
			
				if (strlen($at1) > 0)
				{
				$sql = "select * from gk_autores where gka_nome_citacao = '".$at1."' ";			
				$rrr = db_query($sql);
				if ($line = db_read($rrr))
					{ $cod = $line['gka_codigo']; } else 
					{ $sqli = "insert into gk_autores ";
						$sqli .= "(gka_nome_citacao,gka_codigo,gka_docente,gka_nome,gka_use) values ";
						$sqli .= "('".$at1."','','','".$at1."','')";
						$rrr = db_query($sqli);
						$sqli = "update gk_autores set gka_codigo = lpad(id_gka,7,0) where gka_codigo = '' ";
						$rrr = db_query($sqli);
	
						$rrr = db_query($sql);
						$line = db_read($rrr);
						$cod = $line['gka_codigo'];
						if (strlen(trim($line['gka_use'])) > 0)
							{ $cod = $line['gka_use']; }
					}
				array_push($autores,array($at1,$cod));
				}
			}
		}
	return($autores);
	}
?>