<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       20/05/2008 //
///////////////////////////////////////////
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

function CEPFormat(fld, e) 
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
		if (i==1) aux2=aux2+'.';
		if (i==4) aux2=aux2+'-';
	}	
	fld.value = aux2;
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
function sonumero()
	{
	if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;
	}
</script>
<?
function gets($c1,$txt,$c3,$c4,$c5,$ce)
	{
	$mvl = abs("0".substr($c3,2,10));
	if ($mvl > 2) { $mvl++; }
	if ($mvl > 7) { $mvl++; }
	if ($mvl > 12) { $mvl++; }
	if ($mvl > 20) { $mvl++; }
	if ($mvl > 30) { $mvl++; }
	$size = $mvl;
	$cmd = strtoupper(substr($c3,1,1));
	$result = "ok";
	$max = 60;

	if (($c5==1) and (strlen($txt)==0)) 
		{
		$ce = $ce . " style='background=#ffbfbf' ";
		}
	
	//echo "=======".$cmd."=========";
	
//************************************************* STRING
	if (substr($c3,1,3)=="CEP")
		{
		$cmd='#';
		$ncol=10;
		$size=11;
		$max=10;
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$c4."</TD>"; }
		$msk='onkeypress="sonumero();CEPFormat(this,event);" onblur="checadata(this);" ';
		$msk=$msk.' onblur="checadata(this);" ';
		$gets = $gets. '<TD><input type="text" name="'.$c1.'" value="'.$txt.'" size="'.$size.'" maxlength="'.$mvl.'" '.$msk.' '.$ce.'>&nbsp;&nbsp;</TD>';
		echo $gets;		
		}
		
//'************************************************* SONUMEOS
	if ($cmd == "D" )
		{
		$ncol=10;
		$size=11;
		$max=10;
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$c4."</TD>"; }
//		$gets = $gets . "<TD>";
//		$gets = $gets . '<input type="text" name="'.$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce."></TD>";
		$msk='onkeypress="sonumero();DataFormat(this,event);" ';
		$msk=$msk.' onblur="checadata(this);" ';
		$gets = $gets. '<TD><input type="text" name="'.$c1.'" value="'.$txt.'" size="'.$size.'" maxlength="'.$mvl.'" '.$msk.' '.$ce.'>&nbsp;&nbsp;</TD>';
		echo $gets;		
		}
//'************************************************* SONUMEOS
	if ($cmd == "N" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$c4."</TD>"; }
//		$gets = $gets . "<TD>";
//		$gets = $gets . "<input type=\"text\" name=\"".$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce."></TD>";
		$msk = 'onkeypress="return(currencyFormat(this,event));sonumero();" style="text-align: right;" '.$ce;
		$gets = $gets. '<TD><input type="text" name="'.$c1.'" value="'.$txt.'" size="'.$size.'" maxlength="'.$mvl.'" '.$msk.'></TD>';
		echo $gets;		
		}
//'************************************************* SONUMEOS
	if ($cmd == "I" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$c4."</TD>"; }
//		$gets = $gets . "<TD>";
//		$gets = $gets . "<input type=\"text\" name=\"".$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce."></TD>";
		$msk = 'onkeypress="sonumero();" style="text-align: right;" '.$ce;
		$gets = $gets. '<TD><input type="text" name="'.$c1.'" value="'.$txt.'" size="'.$size.'" maxlength="'.$mvl.'" '.$msk.'></TD>';
		echo $gets;		
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
			{ $gets = $gets . "<TD align=\"right\">".$c4."</TD>"; }
		$gets = $gets . "<TD>";
		$gets = $gets . "<select name=\"".$c1."\" size=\"1\">";
		
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
		echo $gets;		
		}			
		
//************************************************* RadioBox
	if ($cmd == "O" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$c4."</TD>"; }
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
		echo $gets;		
		}		
	
//************************************************* RadioBox
	if ($cmd == "R" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		$gets = $gets . "<TD colspan=2>";
		$checked = "Checked";
		$gets = $gets . "<input type=\"Radio\" name=\"".$c1."\" value=\"1\" ".$ce." ".$checked.">".$c4."</TD>";
		echo $gets;		
		}		
	
//************************************************* CheckBox
	if ($cmd == "H" )
		{
		$gets = "";
		$gets = $gets . "<input type=\"hidden\" name=\"".$c1."\" value=\"".$txt."\" ".$ce." >";
		echo $gets;		
		}			
	
//************************************************* CheckBox
	if ($cmd == "C" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		$gets = $gets . "<TD colspan=2>";
		$checked = "";
		if ($txt=="1") { $checked = "Checked"; }
		$gets = $gets . "<input type=\"checkbox\" name=\"".$c1."\" value=\"1\" ".$ce." ".$checked.">".$c4."</TD>";
		echo $gets;		
		}		
	
	//************************************************* BOTTON
	if ($cmd == "B" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		$gets = $gets . "<TD align=\"center\" colspan=\"2\">";
		$gets = $gets . "<input type=\"submit\" name=\"".$c1."\" value=\"".$c4."\" size=\"".$max."\" maxlength=\"".$size."\" ".$ce."></TD>";
		echo $gets;		
		}	
	
//************************************************* STRING
	if ($cmd == "S" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$c4."</TD>"; }
		$gets = $gets . "<TD>";
		$gets = $gets . "<input type=\"text\" name=\"".$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce."></TD>";
		echo $gets;		
		}
		
	
//************************************************* STRING
	if ($cmd == "P" )
		{
		if ($size >= $max) {$size = $max;}
		$gets = "";
		if (strlen($c4) > 0) 
			{ $gets = $gets . "<TD align=\"right\">".$c4."</TD>"; }
		$gets = $gets . "<TD>";
		$gets = $gets . "<input type=\"password\" name=\"".$c1."\" value=\"".$txt."\" size=\"".$size."\" maxlength=\"".$mvl."\" ".$ce."></TD>";
		echo $gets;		
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
			{ $gets = $gets . "<TD align=\"right\" valign=\"top\">".$c4."</TD>"; }
		$gets = $gets . "<TD>";
		$gets = $gets . "<textarea wrap=\"on\" rows=".$rows." cols=".$cols." name=\"".$c1."\" ".$ce.">".$txt."</textarea></TD>";
		echo $gets;
		}
	echo chr(13).chr(10);
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
?>