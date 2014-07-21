<?
/**
* Esta classe é a responsável pela conexão com o banco de dados.
* @author Rene F. Gabriel Junior <rene@sisdoc.com.br>
* @version 1.0m
* @copyright Copyright © 2011, Rene F. Gabriel Junior.
* @access public
* @package BIBLIOTECA
* @subpackage sisdoc_form2
*/
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 1.0n                       21/06/2011 // Entrada da data por calendario
// 1.0m                       29/10/2010 // Cor de fundo nos campos do FireFox
// 1.0l                       01/10/2010 // Inclusão da ${ $} FieldSet e Legend
// 1.0j                       01/10/2010 // Inclusão da avaliasão SIM/NAO $SN
// 1.0i                       17/03/2009 // Não deixa carregar duas vezes
// 1.0h                       17/03/2009 // Declaracao
// 1.0h                       28/01/2009 // Implantação de Rotinas do AJAX
// 1.0g                       20/01/2009 // COrrecao do Chechar CPF  e Implantado HV (Hidden com valor)
// 1.0f                       06/07/2008 // funcao Checar CPF 
// 1.0e                       06/07/2008 // funcao [de-ate] e [Data=8]
// 1.0d                       24/06/2008 //
// 1.0c                       15/05/2008 //
// 1.0b                       21/04/2008 //
// 1.0a                       20/05/2008 //
///////////////////////////////////////////

/* Java para sistema de janelas */
//require("sisdoc_form2_js.php");
///////////////////////////////////////////

if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Form2)",'0.0h',20090128)); }
global $form2;
if (strlen($include) == 0) { exit; }
if (strlen($form2) != True)
{
$form2 = 1;

?>
<script language="JavaScript">
function checadata(vDateName) 
{
	var strSeperator = '/';
	var mDay = vDateName.value.substr(0,2);
	var mMonth = vDateName.value.substr(3,2);
	var mYear = vDateName.value.substr(6,4)
	var ok = 1;
    var iDay   = parseInt(mDay, 10)
    var iMonth = parseInt(mMonth, 10)
    var iYear  = parseInt(mYear, 10)
    if ((iMonth < 1) || (iMonth > 12)) 
		{ alert("Mes Inválido");ok=0;}
    if (((iYear < 1900) || (iYear > 2050)) && (ok==1))
		{ alert("Ano Inválido");ok=0;}
    if (((iDay < 1 ) || (iDay > 31))  && (ok==1))
		{ alert("Dia Inválido");ok=0;}
    if ((iDay==31 ) && (ok==1))
		{
		if ((iMonth==2) || (iMonth==4) || (iMonth==6) || (iMonth==9) || (iMonth==11))
			{alert("FEV/ABR/JUN/SET e NOV não tem 31 dias ");ok=0;}
		}
	if ((iDay > 29) && (iMonth==2))
		{	alert('Fevereiro tem somente 28/29 dias');
			ok=0; 	}

	if ((iDay > 28) && (iMonth==2))
		{	if (iYear != 2004 && iYear != 2008 && iYear != 2000 && iYear != 2012 && iYear != 2016 && iYear != 2020)
			{ alert('Este ano nao eh Bi-Sexto!');ok=0; }			
		}

	if (ok==0)
		{vDateName.value='';vDateName.focus();	vDateName.select();	}
		
	return false;	
}

function edCnpjFormat(fld, e)
	{
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? e.which : e.keyCode;

	if (whichCode == 13 || whichCode == 8 || whichCode==0 ) return true;  // Enter
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
	aux = '';
	aux3 = document.getElementById(fld).value;
	aux3 = aux3 + key;

	len = aux3.length;
	for(i=0; i < len; i++)
	if (strCheck.indexOf(aux3.charAt(i))!=-1) aux += aux3.charAt(i);

	len = aux.length;
	aux2 ='';
	tp=2;
	if (len <= 12) { tp=1; }
	for(i=0; i < len; i++)
	{
		aux2=aux2+aux.charAt(i);
		if (tp == 1)
			{
			if (i==2) aux2=aux2+'.';
			if (i==5) aux2=aux2+'.';
			if (i==8) aux2=aux2+'-';
			} else
			{
			if (i==1) aux2=aux2+'.';
			if (i==4) aux2=aux2+'.';
			if (i==7) aux2=aux2+'/';			
			if (i==11) aux2=aux2+'-';
			}
	}	
	document.getElementById(fld).value = aux2;
	return false;	
	}

function edCepFormat(fld, e)
	{
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? e.which : e.keyCode;

	if (whichCode == 13 || whichCode == 8 || whichCode==0 ) return true;  // Enter
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
	aux = '';
	aux3 = document.getElementById(fld).value;
	aux3 = aux3 + key;

	len = aux3.length;
	for(i=0; i < len; i++)
	if (strCheck.indexOf(aux3.charAt(i))!=-1) aux += aux3.charAt(i);

	len = aux.length;
	aux2 ='';
	if (len > 8) { len = 8; }
	for(i=0; i < len; i++)
	{
		aux2=aux2+aux.charAt(i);
		if (i==1) aux2=aux2+'.';
		if (i==4) aux2=aux2+'-';
	}	
	document.getElementById(fld).value = aux2;
	return false;	
	}

function DataFormat(fld, e) 
{
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? e.which : e.keyCode;
	if (whichCode == 13) return true;  // Enter
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
	len = fld.value.length;
	aux = '';
	for(i=0; i < len; i++)
	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
	len = aux.length;
	aux2 ='';
	for(i=0; i < len; i++)
	{
		aux2=aux2+aux.charAt(i);
		if (i==1 || i==3) aux2=aux2+'/';
	}	
	fld.value = aux2;
	return false;
}

function currencyFormat(fld, e) 
{
	var milSep='';
	var decSep='.';
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? e.which : e.keyCode;
	if (whichCode == 0) return true;  // Tab
	if (whichCode == 46) return true;  // Del		
	if (whichCode == 8) return true;  // BACKsPACE	
	if (whichCode == 13) return true;  // Enter
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
	len = fld.value.length;
	for(i = 0; i < len; i++)
	if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
	aux = '';
	for(; i < len; i++)
	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
	aux += key;
	len = aux.length;
	if (len == 0) fld.value = '';
	if (len == 1) fld.value = '0'+ decSep + '0' + aux;
	if (len == 2) fld.value = '0'+ decSep + aux;
	if (len > 2) {
	aux2 = '';
	for (j = 0, i = len - 3; i >= 0; i--) {
	if (j == 3) {
	aux2 += milSep;
	j = 0;
	}
	aux2 += aux.charAt(i);
	j++;
	}
	fld.value = '';
	len2 = aux2.length;
	for (i = len2 - 1; i >= 0; i--)
	fld.value += aux2.charAt(i);
	fld.value += decSep + aux.substr(len - 2, len);
	}
	return false;
}

function sonumero(e,id)
	{
	var value=document.getElementById(id).value;
	e = e?e:event;
	var keyCode =e.keyCode?e.keyCode:e.charCode;
	return (( keyCode >= 48 && keyCode <= 57 )||keyCode ==9||keyCode ==0||keyCode ==8||(keyCode ==46 && value.indexOf('.')==-1)||(keyCode >= 37 && keyCode <= 40));
	}

function edDataFormat(fld, e)
	{
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? e.which : e.keyCode;

	if (whichCode == 13 || whichCode == 8 || whichCode==0 ) return true;  // Enter
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
	aux = '';
	aux3 = document.getElementById(fld).value;
	aux3 = aux3 + key;

	len = aux3.length;
	for(i=0; i < len; i++)
	if (strCheck.indexOf(aux3.charAt(i))!=-1) aux += aux3.charAt(i);

	len = aux.length;
	aux2 ='';
	for(i=0; i < len; i++)
	{
		aux2=aux2+aux.charAt(i);
		if (i==1 || i==3) aux2=aux2+'/';
	}	
	document.getElementById(fld).value = aux2;
	return false;	
	}

</script>
<?
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
	return(gets($tt1,$tt1a,$tt2,'',$tt3,''));
	}
	
function gets($c1,$txt,$c3,$c4,$c5,$c6)
	{
	global $estilo,$acao,$editor,$ed_nr_id,$script,$vcol;
	
//	echo '<BR>c1=='.$c1;
//	echo '<BR>c2=='.$txt;
//	echo '<BR>c3=='.$c3;
//	echo '<BR>c4=='.$c4;
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
			$gets .= '<fieldset>';
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
		$gets = $gets . "<select name=\"".$c1."\" size=\"1\">";
		
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
		$ssql = troca($ssql,'´',chr(39));
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
				$gets = "";
				$gets = $gets . "<TD align=\"center\" colspan=\"2\">";
				$gets = $gets . '<input type="submit" name="acao" value="'.$c4.'" '.$ce.' '.$ed_editor.' >';
			} else {
				$gets = "";
				$gets = $gets . "<TD align=\"center\" colspan=\"2\">";
				$gets = $gets . '<input type="submit" name="acao" value="gravar" '.$ce.' '.$ed_editor.' >';
				$gets = $gets . '&nbsp;&nbsp;';
				$gets = $gets . '<input type="reset" value="limpar dados" '.$ce.'>';
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
		$gets .= '<TD><input type="text" name="'.$c1.'" id="'.$c1.'"  value="'.$txt.'" size="'.$size.'" maxlength="'.$mvl.'" '.$msk.' '.$ce.'>&nbsp;';
//		$gets .= '<img src="img/icone_calender.gif" id="f_trigger_c" style="cursor: pointer; border: 1px solid red;" title="Date selector" ';
//      	$gets .= ' onmouseover="this.style.background='red';" onmouseout="this.style.background=''" />';
//		$gets .= '<script type="text/javascript"> Calendar.setup({';
//        $gets .= 'inputField  : "'.$c1.'", ';
//        $gets .= 'ifFormat       :    "dd/mm/y", ';
//        $gets .= 'button         :    "'.$c1.'", ';
//        $gets .= 'align          :    "Tl", ';
//        $gets .= 'singleClick    :    true ';
//		$gets .= '</Script>';
    });
</script>';
		$gets .= '</TD>';
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
		$gets .= '&nbsp; Li e estou de acordo com a declaração acima.';
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
		$gets = $gets . "<select name=\"".$c1."\" size=\"1\">";
		
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
		$gets = $gets . "<input type=\"password\" name=\"".$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce."></TD>";
		}		

//************************************************* SQL
	if ($cmd == "Q" )
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
		$ssql = troca($ssql,'´',chr(39));
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

//************************************************* RadioBox
	if ($cmd == "R" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		$gets = $gets . "<TD colspan=2>";
		$checked = "Checked";
		$gets = $gets . "<input type=\"Radio\" name=\"".$c1."\" value=\"1\" ".$ce." ".$checked.">".$cf.$c4."</TD>";
		}		
	
	
//************************************************* STRING
	if ($cmd == "S" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$cf.$c4."</TD>"; }
		$gets = $gets . "<TD>";
		$gets = $gets . "<input type=\"text\" name=\"".$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce." ";
		if ($c6!=1)
			{ $gets .= ' READONLY '; } /// Somente Leitura
		$gets .= "></TD>";
		}
		

//************************************************* SimNão
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
		$gets .= '<input type="radio" name="'.$c1.'" value="0" '.$chk1.'><font color="#cc3300">NÃO</font>';
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
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\" valign=\"top\">".$cf.$c4."</TD>"; }
		$gets = $gets . "<TD>";
		$gets = $gets . "<textarea wrap=\"on\" rows=".$rows." cols=".$cols." name=\"".$c1."\" ".$ce.">".$txt."</textarea></TD>";
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
		$ufs = array("AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MG", "MS", "MT", "PA", "PB", "PE", "PI", "PR", "RJ", "RN", "RR", "RS", "SC", "SE", "SP", "TO");
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