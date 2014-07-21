<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       25/05/2008 //
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Spell)",$security_ver,20080706)); }
if (strlen($include) == 0) { exit; }
function spell_import($spe_texto,$spe_idioma,$spe_tipo)
	{
	$spe_texto = trim($spe_texto);
	$spe_texto = ' '.troca($spe_texto,"%",'');	
	$spe_texto = troca($spe_texto,'.',' ');	
	$spe_texto = troca($spe_texto,'-',' ');		
	$spe_texto = troca($spe_texto,',',' ');	
	$spe_texto = troca($spe_texto,';',' ');	
	$spe_texto = troca($spe_texto,'"',' ');	
	$spe_texto = troca($spe_texto,"'",' ');	
	$spe_texto = troca($spe_texto,"/",' ');	
	$spe_texto = troca($spe_texto,"\\",' ');	
	if ($spe_tipo == 'w') { $spe_tabela  = "assunto_word"; }
	$so = LowerCase($spe_texto);
	$su = UpperCaseSQL($spe_texto);
	
	$se = UpperCaseSQL($spe_texto);
	$se = troca($se,'´',' ');
	$se = troca($se,'"',' ');
	$se = troca($se,"'",' ');
	$se = troca($se,"0",' ');
	$se = troca($se,"1",' ');
	$se = troca($se,"2",' ');
	$se = troca($se,"3",' ');
	$se = troca($se,"4",' ');
	$se = troca($se,"5",' ');
	$se = troca($se,"6",' ');
	$se = troca($se,"7",' ');
	$se = troca($se,"8",' ');
	$se = troca($se,"9",' ');
	$se = troca($se,"!",' ');
	$se = troca($se,"@",' ');
	$se = troca($se,chr(13),' ');
	$se = troca($se,chr(10),' ');
	$se = str_word_count($se,1);
	sort($se);
	$termo = 'xx';
	$isql = '';
	$ti = 0;
	for ($ke=0;$ke < count($se);$ke++)
		{
		if ($termo != $se[$ke])
			{
			$ok = 1;
			$termo = trim($se[$ke]);
			$xt = strpos($su,' '.$termo.' ');
			$termo_x = trim(substr($so,$xt,strlen($termo)+1));
			if (UpperCaseSQL($termo_x) != $termo)
				{
				//echo '<BR>'.$se[$ke].'=='.$xt.'=='.$termo_x.'=='.$xt;
				$ok = 0;
				}
			if (strlen($termo) > 20)
				{ $ok = 0;
				echo $termo_x.', muito longo<BR>';
				}
			if ($ok == 1)
				{
				$sql = "select * from ".$spe_tabela." where aw_termo_asc = '".$termo."' ";
				$rlt = db_query($sql);
				if (!$line = db_read($rlt))
					{
					$isql .= "insert into ".$spe_tabela." (";
					$isql .= "aw_termo,aw_termo_asc,lastupdate,";
					$isql .= "aw_codigo,aw_idioma,aw_ativo,";
					$isql .= "aw_status ";
					$isql .= ") values (";
					$isql .= "'".$termo_x."','".$termo."',".date("Ymd").",";
					$isql .= "'','".$spe_idioma."',1,";
					$isql .= "1";
					$isql .= ');';
					$ti++;
					}
				}
			}
		}
		if (strlen($isql) > 0)
			{
			$rlt = db_query($isql);
			}
		return($ti);
//	print_r($se);
	}
?>