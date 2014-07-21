<?php
/**
* Esta classe e a responsavel pelos formulários.
* @author Rene F. Gabriel Junior <rene@sisdoc.com.br>
* @version 1.0r
* @copyright Copyright - 2012, Rene F. Gabriel Junior.
* @access public
* @package BIBLIOTECA
* @subpackage sisdoc_form2
*/
///////////////////////////////////////////
// Vers�o atual           //    data     //
//---------------------------------------//
// 1.0r						  29/12/2012 //
// 1.0q						  29/11/2012 //
// 1.0p						  01/06/2011 //
// 1.0a                       20/05/2008 //
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Form2)",'0.0o',20111101)); }
global $form2;
if (strlen($include) == 0) { exit; }
if (strlen($form2) != True)
{
$form2 = 1;
require($include."sisdoc_form2_js.php");
function cpf($cpf)
	{
	$cpf = sonumero($cpf);
	if (strlen($cpf) <> 11) { return(false); } 
	
	$soma1 = ($cpf[0] * 10) + ($cpf[1] * 9) + ($cpf[2] * 8) + ($cpf[3] * 7) + 
			 ($cpf[4] * 6) + ($cpf[5] * 5) + ($cpf[6] * 4) + ($cpf[7] * 3) + 
			 ($cpf[8] * 2); 
	$resto = $soma1 % 11; 
	$digito1 = $resto < 2 ? 0 : 11 - $resto; 
	
	$soma2 = ($cpf[0] * 11) + ($cpf[1] * 10) + ($cpf[2] * 9) + 
			 ($cpf[3] * 8) + ($cpf[4] * 7) + ($cpf[5] * 6) + 
			 ($cpf[6] * 5) + ($cpf[7] * 4) + ($cpf[8] * 3) + 
			 ($cpf[9] * 2); 
			 
	$resto = $soma2 % 11; 
	$digito2 = $resto < 2 ? 0 : 11 - $resto; 
	if (($cpf[9] == $digito1) and ($cpf[10] == $digito2))
		{ return(true); } else
		{ return(false); }
	}

function sget($tt1,$tt2,$tt3)
	{
	global $dd;
	if (strlen($tt3) == true) { $tt3 = 1; }
	$dda = intval("0".substr($tt1,2,3));
	if ($dda >=0 and $dda <=99) { $tt1a = $dd[$dda];}
	return(gets($tt1,$tt1a,$tt2,'',$tt3,1));
	}
	
function gets($c1,$txt,$c3,$c4,$c5,$c6,$tips='',$style='')
	{
	global $estilo,$acao,$editor,$ed_nr_id,$script,$vcol,$max_char,$include;
	if (empty($max_char)) { $max_char = 25; }
	$c4 = trim($c4);
	
//	echo '<BR>c1=='.$c1; - Field
//	echo '<BR>c2=='.$txt;
//	echo '<BR>c3=='.$c3;
//	echo '<BR>c4=='.$c4; - Caption
//	echo '<BR>c5=='.$c5;
//	echo '<BR>c6=='.$c6;
//	echo '<HR>';
	$ce = $estilo;
	$mvl = abs("0".substr($c3,2,10));
	$size = $mvl;
	if ($size > 2) { $size++; }
	if ($size > 7) { $size++; }
	if ($size > 12) { $size++; }
	if ($size > 20) { $size++; }
	if ($size > 30) { $size++; }

	$cmd = strtoupper(substr($c3,1,1));
	$result = "ok";
	$max = 60;
	$cf ='';
	if (($c5==1) and (strlen($txt)==0)) 
		{
		$ce = $ce . ' style="background-color : #ffbfbf" ';
		$cec = " background-color : #ffbfbf; ";
//		$ce = $ce . " style='background=#ffbfbf' ";
		if (strlen($acao) > 0) { $cf = $cf . "<font color=red>"; }
		}
	
	//echo "=======".$cmd."=========";
//************************************************* FieldSet
	if ($cmd == "{" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ 
			$vcol = 0;
			$gets = $gets . '<TD colspan="2">';
			$gets .= '<fieldset '.$style.'>';
			$gets .= '<legend><font class="lt1"><b>'.$cf.$c4.'</b></legend>';
			$gets = $gets . '<table cellpadding="0" cellspacing="0" class="lt2" width="100%">';
			$gets = $gets . '<TR valign="top">';
			}		
		}
//************************************************* Fim do FieldSet
	if ($cmd == "}" )
		{
		$gets = "";
		$gets .= '</table>';
		$gets .= '</fieldset>';
		}
//************************************************* Sequencia
	if ($cmd == "[" )
		{
		$nn1 = substr($c3,2,100);
		$nn2 = substr($nn1,strpos($nn1,'-')+1,100);
		$nn1 = substr($nn1,0,strpos($nn1,'-'));
		$nn2 = substr($nn2,0,strpos($nn2,']'));
		$nn1 = intval("0".$nn1);
		$nn2 = intval("0".$nn2);
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>"; }
		$gets = $gets . "<TD>";
		$gets = $gets . '<select name="'.$c1.'" id="'.$c1.'" size="1">';
		
		for ($nnk=$nn1;$nnk <= $nn2;$nnk++)
			{
			$sel = '';
			if ($nnk==$txt) {$sel="selected";}
			$gets = $gets . "<option value=\"".$nnk."\" ".$sel.">".$nnk."</OPTION>";
			}
		$gets = $gets . "</select>" ;
		}		
//************************************************* STRING
	if ($cmd == "A" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ 
			$gets = $gets . '<TD colspan="2">';
			$gets = $gets . '<table cellpadding="0" cellspacing="0" class="lt2" width="100%">';
			$gets = $gets . '<TR valign="middle">';
			$gets = $gets . '<TD width="25"><HR width="25" size="5" color="#c0c0c0"></TD>';
			$gets = $gets . '<TD><NOBR><B>'.$cf.$c4.'</TD>';
			$gets = $gets . '<TD width="90%"><HR size="5" width="100%" color="#c0c0c0"></TD>';
			$gets = $gets . '</TABLE>';	
			}
		}	
//************************************************* AJAX
	if (substr($c3,1,4)=="AJAX")
		{
		$opc = trim(substr($c3,2,strlen($c3))).":";
		$ssql = "";
		$sfl1 = "";
		$sfl2 = "";
		$iiii = 0;
		while (($pos=strpos($opc, ':'))>0)
			{
			if ($iiii==0) { $sfl1 = substr($opc,0,$pos); }
			if ($iiii==1) { $sfl2 = substr($opc,0,$pos); }
			if ($iiii==2) { $ssql = substr($opc,0,$pos); }
			$opc = substr($opc,$pos+1,strlen($opc));
			$iiii++;
			}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>"; }
		$gets = $gets . "<TD>";
		$gets = $gets . "<select name=\"".$c1."\" size=\"1\">";
		$ssql = troca($ssql,'�',chr(39));
		$rrr = db_query($ssql);
		
		while ($sline = db_read($rrr))
			{
			$it1 = CharE($sline[$sfl1]);
			$it2 = $sline[$sfl2];
			$sel = '';
			if (trim($it2) == trim($txt)) {$sel="selected";}
			$gets = $gets . "<option value=\"".$it2."\" ".$sel.">".$it1."</OPTION>";
			}
		$gets = $gets . "</select>" ;
		}		
	//************************************************* BOTTON
	if ($cmd == "B" )
		{
		if ($editor == true)
			{
			$ed_editor = 'onClick="rtoStore()"';
			}
		if (strlen($c4) > 0)
			{
				$cpop = '';
				$c4 = troca($c4,'<font class="lt0">','');
				$c4 = troca($c4,'</font>','');
				if (substr($c4,0,1) == '#')
					{
						//$c4 = substr($c4,1,strlen($c4));
						//$c4 = msg($c4);
						//$cpop = substr($c4,strpos($c4,'<A'),strlen($c4));
						//if (strpos($c,'<A') > 0) { $c4 = substr($c4,0,strpos($c4,'<A')); }
					}
					
				$gets = "";
				if (strpos(' '.$c4,'<') > 0)
					{
						$c4 = ' '.$c4;
						
						$pos = strpos($c4,'<');
						$cpop = substr($c4,$pos,strlen($c4));
						$c4 = substr($c4,0,$pos);
					}
				$gets = $gets . "<TD align=\"center\" colspan=\"2\">";
				$gets = $gets . '<input type="submit" name="acao" value="'.$c4.'" class="bnt_submit">'.$cpop;
			} else {
				$gets = "";
				$gets = $gets . "<TD align=\"center\" colspan=\"2\">";
				$gets = $gets . '<input type="submit" name="acao" value="gravar" '.$ed_editor.' >';
				$gets = $gets . '&nbsp;&nbsp;';
				$gets = $gets . '<input type="reset" value="limpar dados" >';
			}
		}	
		
//************************************************* CheckBox
	if ($cmd == "C" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		$gets = $gets . "<TD colspan=2>";
		$checked = "";
		if ($txt=="1") { $checked = "Checked"; }
		$gets = $gets . "<input type=\"checkbox\" name=\"".$c1."\" value=\"1\" ".$ce." ".$checked.">".$cf.$c4."</TD>";
		}	
			
//************************************************* STRING
	if (substr($c3,1,3)=="CEP")
		{
		$cmd='#';
		$ncol=10;
		$size=11;
		$max=10;
		$mvl=10;
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$c4."</TD>"; }

		$msk='onkeypress="return edCepFormat(this.id,event); "';
		$gets = $gets. '<TD><input type="text" name="'.$c1.'" id="'.$c1.'" value="'.$txt.'" size="'.$size.'" maxlength="'.$mvl.'" '.$msk.' '.$ce.'>&nbsp;&nbsp;</TD>';
		}
		
//************************************************* STRING
	if ((substr($c3,1,4)=="CNPJ") or (substr($c3,1,3)=="CPF"))
		{
		$cmd='#';
		$ncol=10;
		$size=15;
		$mvl=14;
		if (substr($c3,1,4)=="CNPJ")
			{ $mvl = 18; $size=21; }

		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$c4."</TD>"; }

		$msk='onkeypress="return edCnpjFormat(this.id,event); "';
		$gets = $gets. '<TD><input type="text" name="'.$c1.'" id="'.$c1.'" value="'.$txt.'" size="'.$size.'" maxlength="'.$mvl.'" '.$msk.' '.$ce.'>&nbsp;&nbsp;</TD>';
		}		
//'************************************************* DATA
	if (($cmd == "D" ) and (substr($c3,1,5)!="DECLA"))
		{
		$ncol=10;
		$size=13;
		$max=12;
		$mvl=12;
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>"; }
//		$gets = $gets . "<TD>";
//		$gets = $gets . '<input type="text" name="'.$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce."></TD>";
		$msk='onkeypress="return edDataFormat(this.id,event);" ';
		$msk=$msk.' onblur="checadata(this);" ';
		$gets = $gets. '<TD><input type="text" name="'.$c1.'" id="'.$c1.'"  value="'.$txt.'" size="'.$size.'" maxlength="'.$mvl.'" '.$msk.' '.$ce.'>&nbsp;';
		$gets .= '<img src="'.$include.'/img/icone_calender.gif" border="0" id="'.$c1.'a" style="cursor: pointer; border: 0px solid red;" title="Sele��o de data" onmouseover="this.style.background='.chr(39).'red'.chr(39).';" onmouseout="this.style.background='.chr(39).chr(39).'" >';
		/* SCRIPT */
		$gets .= '<script type="text/javascript">'.chr(13);
		$gets .= 'var '.$c1.' = '.chr(39).chr(39).';';
    	$gets .= 'Calendar.setup({'.chr(13);
        $gets .= 'inputField     :    "'.$c1.'",'.chr(13);
        $gets .= 'ifFormat       :    "dd/mm/y",'.chr(13);
        $gets .= 'button         :    "'.$c1.'a",'.chr(13);
        $gets .= 'align          :    "Tl",'.chr(13);
        $gets .= 'singleClick    :    true'.chr(13);
    	$gets .= '});'.chr(13);
		$gets .= '</script>'.chr(13);
		$gets .= '&nbsp;</TD>';
		}
//'************************************************* DECLARACAO
	if (substr($c3,1,5)=="DECLA")
		{
		$ncol=10;
		$size=13;
		$max=12;
		$mvl=12;
		$gets = "";
//		$gets = $gets . "<TD class="lt1">";
//		$gets = $gets . '<input type="text" name="'.$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce."></TD>";
		$gets = $gets. '<TD>'.$c4;
		$gets .= '<input type="checkbox" name="'.$c1.'" value="1">';
		$gets .= '&nbsp; Li e estou de acordo com a declara��o acima.';
		}		
		
//'************************************************* SONUMEOS
	if ($cmd == "E" )
		{
		$ed_width = 600;
		$ed_height = 300;
		$edit_max = substr($c3,2,100);
		if (strpos($edit_max,':') > 0)
			{
			$ed_width  = substr($edit_max,0,strpos($edit_max,':'));
			$ed_height = substr($edit_max,strpos($edit_max,':')+1,100);
			}
		$ed_height = intval('0'.$ed_height);
		if ($ed_height <= 40) { echo 'meno'; $ed_height = 40; }

		if (intval('0'.$ed_width) < 500) { $ed_width = 500; }

		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>"; }
		$gets = $gets. '<TD>'.chr(13);
		$gets = $gets . '<input type="hidden" name="'.$c1.'" value="#flip#de'.$ed_nr_id.'"  >';
		$gets = $gets . '<textarea name="dd'.$ed_nr_id.'a" style="visibility = hidden; height:1px; ">'.$txt.'</textarea>';		
		$gets .= '<script>'.chr(13);
		$gets .= 'var editor'.$ed_nr_id.' = new EDITOR();'.chr(13);
		$gets .= 'editor'.$ed_nr_id.'.textWidth = '.$ed_width.';'.chr(13);
		$gets .= 'editor'.$ed_nr_id.'.textHeight = '.$ed_height.';'.chr(13);
		$gets .= 'editor'.$ed_nr_id.".fieldName = 'de';".chr(13);
		$gets .= 'editor'.$ed_nr_id.'.create(fl.dd'.$ed_nr_id.'a.value);'.chr(13);
		$gets .= '</script>'.chr(13);

		$ed_nr_id++;
		}

//************************************************* STRING (e-mail)
	if (substr($c3,1,5)=="EMAIL")
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>"; }
		$gets = $gets . "<TD>";
		$gets = $gets . "<input type=\"text\" name=\"".$c1."\" value=\"".$txt."\" size=\"60\" maxlength=\"120\" ".$ce." ";
		if ($c6!=1)
			{ $gets .= ' READONLY '; } /// Somente Leitura
		$gets .= "></TD>";
		}		

//************************************************* Hidden
	if ($cmd == "H" )
		{
		$gets = "";
		$gets = $gets . "<input type=\"hidden\" name=\"".$c1."\" value=\"".$txt."\" ".$ce." >";
		}			
//************************************************* Hidden with value
	if (substr($c3,1,2) == "HV" )
		{
		$gets = "";
		$gets = $gets . "<input type=\"hidden\" name=\"".$c1."\" value=\"".$c4."\" ".$ce." >";
		}		
//'************************************************* SONUMEOS
	if ($cmd == "I" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>"; }
//		$gets = $gets . "<TD>";
//		$gets = $gets . "<input type=\"text\" name=\"".$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce."></TD>";
		$msk = 'onkeypress="return sonumero(event,this.id);" style="text-align: right; '.$cec.'" ';
		$gets = $gets. '<TD><input type="text" name="'.$c1.'"  id="'.$c1.'" value="'.$txt.'" size="'.$size.'" maxlength="'.$mvl.'" '.$msk.'></TD>';
		}	

//************************************************* MOSTRAR TEXTO EXPLICATIVO
	if ($cmd == "M" )
		{
		$gets = "";
		if (substr($c3,1,2)=="M2")
			{
			if (strlen($c4) > 0) { $gets = $gets . "<TD colspan=2><P>".$cf.$c4."</P></TD>"; }
			} else {
			if (strlen($c4) > 0) { $gets = $gets . "<TD>&nbsp;</TD><TD><P>".$cf.$c4."</P></TD>"; }
			}
		}	
				
		
//'************************************************* SONUMEOS
	if ($cmd == "N" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>"; }
//		$gets = $gets . "<TD>";
//		$gets = $gets . "<input type=\"text\" name=\"".$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce."></TD>";
		$msk = 'onkeypress="return(currencyFormat(this,event));return sonumero(event,this.id);" style="text-align: right; '.$cec.'" ';
		$gets = $gets. '<TD><input type="text" name="'.$c1.'" id="'.$c1.'"  value="'.$txt.'" size="'.$size.'" maxlength="'.$mvl.'" '.$msk.'></TD>';
		}

//************************************************* RadioBox
	if ($cmd == "O" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>"; }
		$gets = $gets . "<TD>";
		$gets = $gets . '<select name="'.$c1.'" size="1" id="'.$c1.'" >';
		
		$opc=substr($c3,3,strlen($c3)).'&';
		while (($pos=strpos($opc, '&'))>0)
			{
			$it1=substr($opc,0,$pos);
			$pos2=strpos($it1, ':');
			$it2=substr($it1,0,$pos2);
			$it1=trim(substr($it1,$pos2+1,strlen($it1)));
			$sel="";
			if ($it2==$txt) {$sel="selected";}
			$gets = $gets . "<option value=\"".$it2."\" ".$sel.">".$it1."</OPTION>";
			$opc = substr($opc,$pos+1,strlen($opc));
			}
		$gets = $gets . "</select>" ;
		}		


//************************************************* PASSWORD
	if ($cmd == "P" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>"; }
		$gets = $gets . "<TD>";
		$gets = $gets . "<input type=\"password\" name=\"".$c1."\" id=\"".$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce."></TD>";
		}		

//************************************************* SQL
	if (substr($c3,1,2) == "Q " )
		{
		$opc = trim(substr($c3,2,strlen($c3))).":";
		$ssql = "";
		$sfl1 = "";
		$sfl2 = "";
		$iiii = 0;
		while (($pos=strpos($opc, ':'))>0)
			{
			if ($iiii==0) { $sfl1 = substr($opc,0,$pos); }
			if ($iiii==1) { $sfl2 = substr($opc,0,$pos); }
			if ($iiii==2) { $ssql = substr($opc,0,$pos); }
			$opc = substr($opc,$pos+1,strlen($opc));
			$iiii++;
			}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>"; }
		$gets = $gets . "<TD>";
		$gets = $gets . "<select name=\"".$c1."\" size=\"1\" ".$style." id=\"".$c1."\">";
		$ssql = troca($ssql,'�',chr(39));
		$rrr = db_query($ssql);
		
		while ($sline = db_read($rrr))
			{
			$it1 = CharE($sline[$sfl1]);
			$it2 = $sline[$sfl2];
			$sel = '';
			if (trim($it2) == trim($txt)) {$sel="selected";}
			$gets = $gets . "<option value=\"".$it2."\" ".$sel.">".$it1."</OPTION>";
			$gets .= chr(13).chr(10);	
			}
		$gets = $gets . "</select>" ;
		}			

//************************************************* SQL
	if (substr($c3,1,2) == "QA" )
		{
		$opc = trim(substr($c3,3,strlen($c3))).":";
		$ssql = "";
		$sfl1 = "";
		$sfl2 = "";
		$iiii = 0;
		while (($pos=strpos($opc, ':'))>0)
			{
			if ($iiii==0) { $sfl1 = substr($opc,0,$pos); }
			if ($iiii==1) { $sfl2 = substr($opc,0,$pos); }
			if ($iiii==2) { $ssql = substr($opc,0,$pos); }
			if ($iiii==3) { $ssql = substr($opc,0,$pos); }
			if ($iiii==4) { $ssql = substr($opc,0,$pos); }
			if ($iiii==5) { $ssql = substr($opc,0,$pos); }
			$opc = substr($opc,$pos+1,strlen($opc));
			$iiii++;
			}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>"; }
		$gets .= chr(13).chr(10);	
		$gets = $gets . "<TD>";
		$para = trim(substr($c3,3,strlen($c3)));
		$gets .= chr(13).chr(10);	
		$gets = $gets . '<input type="text" name="filtro" size="5" maxlength="50" onkeyup="exibeModeloSelect(this.value,'.$c1.','.chr(39).$para.chr(39).');">';
		$gets .= chr(13).chr(10);	
		$gets = $gets . '<select id="'.$c1.'" name="'.$c1.'" size="1" id=\"".$c1."\">';
		$gets .= chr(13).chr(10);	
		$gets = $gets . '<option value="">::Selecione na lista::</option>' ;
		$ssql = troca($ssql,'`',"'");
		$rrr = db_query($ssql);
		
		while ($sline = db_read($rrr))
			{
			$it1 = CharE($sline[$sfl1]);
			$it2 = $sline[$sfl2];
			$sel = '';
			if (trim($it2) == trim($txt)) {$sel="selected";}
			$gets = $gets . "<option value=\"".$it2."\" ".$sel.">".$it1."</OPTION>";
			$gets .= chr(13).chr(10);
			}
		
		$gets = $gets . "</select>" ;
		}			


//************************************************* RadioBox
	if ($cmd == "R" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"left\" colspan=2><B>".$cf.$c4."</B></TD>"; }
		$gets = $gets . "<TR><TD colspan=2>";
		
		$opc=substr($c3,3,strlen($c3)).'&';
		while (($pos=strpos($opc, '&'))>0)
			{
			$it1=substr($opc,0,$pos);
			$pos2=strpos($it1, ':');
			$it2=substr($it1,0,$pos2);
			$it1=trim(substr($it1,$pos2+1,strlen($it1)));
			if (substr($it1,0,1) == '#') { $it1 = msg(substr($it1,1,strlen($it1))); }
			$sel="";
			if ($it2==$txt) {$sel="checked";}
			$gets = $gets . "<input type=\"Radio\" name=\"".$c1."\" value=\"".$it2."\" ".$sel.">".$it1;
			$opc = substr($opc,$pos+1,strlen($opc));
			}
		$gets = $gets . "<BR>&nbsp;" ;
		
		}		
	
/*****
 * String Ajax
 * requere o arquivo _ajax_tabela.php
 * 04/jun./2013
 */	
	if (substr($c3,1,2) == "SA" )
	{
		$cmd = 'SA';
		if (strlen($style) > 0) { $sty='class="'.$style.'"'; }
		if ($size >= $max) {$size = $max;}
		if (strlen($c4) > 0) 
			{
				if (strlen($c4) > $max_char)
					{
						$gets = $gets . "<TD colspan=2 align=\"left\">".$cf.$c4."</TD>";
						$gets .= '<TR><TD>';						
					} else {
						$gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>";
					} 
			}
			$gets = $gets . "<TD>".$size;
			$gets = $gets . "<input type=\"text\" name=\"".$c1."\" id=\"".$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce." ";
			$gets .= $sty;

		if ($c6!=1)
			{ $gets .= ' READONLY '; } /// Somente Leitura
		$gets .= "></TD>";
	}
//************************************************* STRING
	if ($cmd == "S" )
		{
		$gets = "";
		if (strlen($style) > 0) { $sty='class="'.$style.'"'; }
		if (substr($c3,1,2)=="SL")
			{
				$size = round(sonumero($c3));
				if ($size >= $max) {$sizev = $max;}
				$gets = $gets . "<TD align=\"left\">".$cf.$c4."</TD>";
				$gets = $gets . "<TR><TD colspan=2>";
				$gets = $gets . "<input type=\"text\" name=\"".$c1."\" id=\"".$c1."\" value=\"".$txt."\" size=\"".$sizev."\" maxlength=\"".$size."\" ".$ce." ";	
				$gets .= $sty;			
			}
		else 
			{
				if ($size >= $max) {$size = $max;}
				if (strlen($c4) > 0) 
					{
						if (strlen($c4) > $max_char)
							{
								$gets = $gets . "<TD colspan=2 align=\"left\">".$cf.$c4."</TD>";
								$gets .= '<TR><TD>';						
							} else {
								$gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>";
							} 
					}
				$gets = $gets . "<TD>";
				$gets = $gets . "<input type=\"text\" name=\"".$c1."\" id=\"".$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce." ";
				$gets .= $sty;
			}
		if ($c6!=1)
			{ $gets .= ' READONLY '; } /// Somente Leitura
		$gets .= "></TD>";
		}
		

//************************************************* SimN�o
	if (substr($c3,1,2)=="SN")
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		$chk1 = '';
		$gets .= '<TD align=\"left\">';
		if (strlen($c4) > 0) 
			{ $gets = $gets .$cf.$c4; }
		$gets .= '<TD align="right">';
		$gets .= '<input type="radio" name="'.$c1.'" value="1" '.$chk1.'><font color="#339900">SIM</font>';
		$gets .= '&nbsp;';
		$gets .= '<input type="radio" name="'.$c1.'" value="0" '.$chk1.'><font color="#cc3300">N�O</font>';
		$gets .= "</TD>";
		}

//************************************************* TEXTO
	if ($cmd == "T" )
		{
		$cols = 70;
		$rows = 6;
		
		$pos=strpos($c3, ':');
		if ($pos > 0)
			{
			$cols=substr($c3,0,$pos);
			$rows=substr($c3,$pos+1,10);
			$cols=substr($cols,2,10);
			}
		
		$gets = "";
				if (strlen($c4) > $max_char)
					{
						$gets = $gets . "<TD colspan=2 align=\"left\">".$cf.$c4."</TD>";
						$gets .= '<TR><TD>';						
					} else {
						$gets = $gets . "<TD align=\"left\" COLSPAN=2>".$cf.$c4."</TD>";
					} 

		$gets = $gets . "<tr><TD COLSPAN=2>";
		$gets = $gets . "<textarea wrap=\"on\" rows=".$rows." cols=".$cols.' id="'.$c1.'" '."name=\"".$c1."\" ".$ce." style=\"width: 100%;\" ".$style." >".$txt."</textarea></TD>";
		}
		
//'************************************************* SONUMEOS
	if ($cmd == "U" )
		{
		$ncol=10;
		$size=11;
		$max=10;
		$gets = "";
		$gets = $gets. '<TD><input type="hidden" name="'.$c1.'" value="'.date("Ymd").'" size="'.$size.'" >';
		}
		
		
//************************************************* STRING
	if (substr($c3,1,2)=="UF")
		{
		$cmd='#';
		$gets = "";
		$ufs = array("AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MG", "MS", "MT", "PA", "PB", "PE", "PI", "PR", "RJ", "RN", "RO", "RR", "RS", "SC", "SE", "SP", "TO");
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\" valign=\"top\">".$cf.$c4."</TD>"; }
		$gets .= "<TD>";
		$gets .= '<select name="'.$c1.'">';
		for ($km = 0;$km < count($ufs);$km++)
			{
			$check = '';
			if ($txt == $ufs[$km]) { $check = 'selected'; }
			$gets .= '<option value="'.$ufs[$km].'" '.$check.' >'.$ufs[$km].'</option>';
			}
		$gets .= '</select>';
		}
				
	return($gets.chr(13).chr(10));
	}	

function ler($table,$cps,$where)
	{
		$sqlx = "select * from ".$table." where ".$where;
		$result = mysql_query($sqlx) or die("Query failed : " . mysql_error());
		if ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			{
			for ($k=1;$k < count($cps);$k++)
				{
				$cps[$k]->cp_valor = $line[$cps[$k]->cp_field];
				}
			}
		return($cps);
	}
function gravar($table,$cps,$where,$novo)
	{
	if ($novo)
		{
		$sqlx = "insert into ".$table." (";
		for ($t=2;$t <= count($cps); $t++)
			{ if ($t > 2) {$sqlx = $sqlx . ", ";}
			$sqlx = $sqlx . $cps[$t]->campo();  }
		$sqlx = $sqlx .	") values (";
		for ($t=2;$t <= count($cps); $t++)
			{ 
			if ($t > 2) {$sqlx = $sqlx . ", ";}
			$sqlx = $sqlx . "'".$cps[$t]->cp_valor."'";  }
			$sqlx = $sqlx .	")";
		}
		else
		{
		$sqlx = 'update '.$table.' set ';
		for ($t=1;$t <= count($cps); $t++)
			{
			if ($t > 1) { $sqlx = $sqlx . ", "; }
			$sqlx = $sqlx . $cps[$t]->campo() . " ='" . $cps[$t]->valor() . "'";
			}
			if (isset($where))
				{ 
				if (strlen($where) > 0) 
					{ $sqlx = $sqlx . " where " . $where; } 
				}
		}
		return $sqlx;
	}
	
function form_mst($txt)
	{
	while	(($pos=strpos($txt,chr(13)))>1)
		{
			$txt = substr($txt,0,$pos).'<BR>'.substr($txt,$pos+2,strlen($txt));
		}
		return $txt;
	}
	
function sn($it)
	{
	$result = "0";
	if (isset($it))
		{
		if ($it=="1") { $result = "1"; }
		}
	return $result;
	}
	
function redirect($pg)
	{
	header("Location: ".$pg);
	echo 'Stoped'; exit;
	}
}
?>