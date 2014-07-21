<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0e                       19/09/2010 // segurança
// 0.0d                       12/07/2008 //
// 0.0c                       25/03/2008 //
// 0.0b                       27/10/2008 //
// 0.0a                       20/05/2007 //
///////////////////////////////////////////
//$debug = true;
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (ROW)","0.0b",20080712)); }
if (strlen($include) == 0) { exit; }
global $secu;
?>
<style>
TEXTAREA, INPUT {
	background : #F9F9F9;
	border : 1px solid Gray;
	padding : 1px 1px 1px 1px;
	text-align : left;
	text-decoration : none;
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 12px;
	font-weight : normal;
	letter-spacing : 0px;
}
</style>
<?
if (strlen($http_redirect) > 0) 
	{
	global $dd,$base;

	$pg_cookie = $_SERVER["SCRIPT_NAME"];
	$pg_cookie = troca($pg_cookie,'/','');
	$pg_cookie = troca($pg_cookie,'.php','');

///////////// alterado SN no display em 08/10/2007
$bb1 = " busca ";
global $dd,$base;
/////////////////////////////////////////////////////////////
if (strlen($label) > 0)
	{
	echo '<TABLE width="'.$tab_max.'" cellpadding="2" cellspacing="0">';
	echo '<TR><TD>';
	echo '<font class=lt5>'.$label.'</font>';
	echo '</TD></TR>';
	echo '</TABLE>';
	}

//if (($dd[50] != $bb1) and (strlen($dd[1]) == 0))
if (strlen($dd[1]) == 0)
	{
	$dd[1] = $_COOKIE[$pg_cookie.'_dd1'];
	$dd[2] = $_COOKIE[$pg_cookie.'_dd2'];
	$dd[3] = $_COOKIE[$pg_cookie.'_dd3'];
	if (strlen($dd[50]) == 0) { $dd[4] = $_COOKIE[$pg_cookie.'_dd4']; }
	}	

if (strlen($dd[1]) > 0) 
	{
	if ($base == 'mysql') 
		{ $where = "upper(".$dd[2].") like '%".UpperCaseSQL(trim($dd[1]))."%'"; }
	else	
		{ $where = "upper((".$dd[2].")) like '%".UpperCaseSQL(trim($dd[1]))."%'"; }
	}
	
	
if (strlen($pre_where) > 0) 
	{
	if (strlen($where) > 0)
		{
		$where = "(".$pre_where.") and (".$where.") ";
		} else { $where = $pre_where;}
	}	

/////////////////////////////// TOTAL
$total = 0;
$xsql = "select count(*) as total from ".$tabela." ";
if (strlen($where) > 0) { $xsql = $xsql . ' where '.$where; }
$xrlt = db_query($xsql);

if ($xline = db_read($xrlt)) { $total = $xline['total']; }
for ($k = 0; $k <= intval($total / $offset); $k++)
	{
	$ini = $k * $offset +1;
	$fim = ($k+1) * $offset;
	if ($fim > $total) { $fim = $total; }
	$sel = '';
	if ($ini == ($dd[4]+1)) { $sel = 'selected'; }
	$cp_max = $cp_max . '<option value="'.($ini-1).'" '.$sel.'>'.$ini.'-'.$fim.'</option>';
	}

if (strlen($dd[4]) > 0) 
	{ if (intval($dd[4]) > $total) { $dd[4] ='';} }	

$cp_ed = '';
$sql = "select ";
if ($base == 'mssql') {$sql .= ' top '.$offset.' '; }
if (strlen($dd[3]) ==0) { $dd[3] = $cdf[1]; }
for ($kx = 0; $kx < count($cdf); $kx++)
	{ 
	if ($kx > 0) { $sql = $sql . ', '; }
	$sql = $sql . trim($cdf[$kx]). ' ';
	$sele = '';
	if (TRIM($dd[2]) == trim($cdf[$kx])) { $sele = ' selected '; }
	if ($kx > 0) { $cp_ed = $cp_ed . '<option value="'.trim($cdf[$kx]).'" '.$sele.'>'.trim($cdm[$kx]).'</option>'; }
	}
/////////////////// grava ultima pesquisa
if ($dd[50]==$bb1)
	{
	setcookie($pg_cookie.'_dd1',$dd[1],time()+7200);
	setcookie($pg_cookie.'_dd2',$dd[2],time()+7200);
	setcookie($pg_cookie.'_dd3',$dd[3],time()+7200);
	setcookie($pg_cookie.'_dd4',$dd[4],time()+7200);
	}
$sql = $sql . ' from '.$tabela;
if (strlen($where) > 0) { $sql = $sql . ' where '.$where; }
if (strlen($order) > 0) { $sql = $sql . ' order by '.$order; } else { $sql = $sql . ' order by '.$cdf[1]; }

if ($base != 'mssql') 
	{ 
	$sql = $sql . ' limit '.$offset;
	if (strlen($dd[4]) >0) { $sql = $sql . ' offset '.$dd[4]; }
	}
//////////////////////////// Mostra busca

$rlt = db_query($sql);

if ($busca ==  true)
	{
	?>
	<TABLE width="<?=$tab_max;?>" cellpadding="0" cellspacing="0" class="lt0" border="0">
	<TR valign="top"><TD colspan="12" height="10"><img src="<?=$include;?>/img/bt_ln_a.png" width="100%" height="4" alt="" border="0"></TD>
	<TR valign="top">
	<TD><form method="post" action="<?=$http_redirect.$http_redirect_para;?>" ></TD>
	<TD><NOBR>Pesquisar&nbsp;</TD>
	<TD width="10%">
	<input type="text" name="dd1" value="<?=$dd[1];?>" size="15" maxlength="80" style="width: 130px; height: 22px;">
	<TD><NOBR>&nbsp;em&nbsp;</TD>
	<TD width="10%"><select name="dd2" size="1" class="IMG">
	<?=$cp_ed; ?>
	</select>
	<TD>&nbsp;&nbsp;
	<TD width="10%"><input type="image" src="<?=$include;?>/img/bt_busca.png" name="dd50" border="0" style="border : 0px">
	<TD width="10%">&nbsp;
	<TD><NOBR>&nbsp;&nbsp;&nbsp;Mostrar&nbsp;</form></TD>
	<TD width="10%">
	<?
	// $http_redirect.'?dd1='.$dd[1].'&dd4='.
	?>
	<select name="dd4" size="1" class="IMG"  onChange="location='<?=$http_redirect.'?dd1='.$dd[1].'&dd2='.$dd[2].'&dd50=busca&dd4=';?>'+this.options[this.selectedIndex].value;">
	<?= $cp_max; ?>
	</select>
	<TD width="10%">&nbsp;
	<?
	if (strlen($http_edit_para) > 0) { $http_ch = '?'; }
	if ($editar) 
		{ 
		echo '<TD align="left"><A  title="titulo" HREF="'.$http_edit.$http_ch.$http_edit_para.'" alt="Titulo">'; 
		echo '<IMG SRC="'.$include.'img/bt_novo.png" alt="" border="0" style="border : 0px"></A>';
		}
	?>
	</TR>
	<TR valign="top">
	<TD colspan="12"><img src="<?=$include;?>/img/bt_ln_b.png" width="100%" height="4" alt="" border="0"></TR>
	<TR><TD colspan="12">&nbsp;
	</TABLE>

	<TABLE width="<?=$tab_max;?>" border="0" cellpadding="3" cellspacing="0" class="lt1">
	<TR bgcolor="#c0c0c0" align="center" class="lt0">
	<?
	if ($editar) { array_push($cdm,''); }
		for ($k = 1; $k < count($cdm); $k++) { echo '<TD><B><font color=white>'.$cdm[$k]; }
	echo '</TD></TR>';

	while ($line = db_read($rlt))
		{
		$xcol = coluna();
		$link = '<A HREF="'.$http_edit.'?dd0='.$line[$cdf[0]].$http_edit_para.'&dd90='.checkpost($line[$cdf[0]]).'">';
		$link = '<A HREF="'.$http_edit.'?dd0='.$line[$cdf[0]].$http_edit_para.'">';
		if (strlen($http_ver) > 0) { $linkv = '<A HREF="'.$http_ver.'?dd0='.$line[$cdf[0]].$http_ver_para.'&dd90='.checkpost($line[$cdf[0]]).'" class=lt1 >'; }
		echo chr(13).chr(10).'<TR '.$xcol.' valign=top>';
		for ($kx=1; $kx < count($cdf); $kx++)
			{
			$ncp = $cdf[$kx];
			$aln = '';
			if (substr($masc[$kx],0,1) == '$') { $aln = 'align="right" '; }
			//////////// exceções
			if (substr($masc[$kx],0,2) == 'CB') { $linkv = ''; }
			if (substr($masc[$kx],0,2) == 'H1') { $aln = $aln . ' colspan=10 '; }
			if (substr($masc[$kx],0,1) == '$') { $aln = 'align="right" '; }
			echo '<TD '.$aln.'>'.$linkv;
			echo format_fld($line[$ncp],$masc[$kx]);
			}
		if ($editar) 
			{
			echo '<TD width="20">'.$link.'<img src="img/icone_editar.gif" width="20" height="19" alt="" border="0"></TD>';
			}
	echo '</TR>';
	}
echo '<TR><TD colspan="10"><B>Total de '.$total.'</B></TD></TR>';
echo '</TABLE>';
}
}
////////////////////////////////////////	
function format_fld($zq1,$zq2)
	{
		global $hd;
		$zqr = '';
		if (strlen($zq2) > 0)
			{
			if (substr($zq2,0,1) == '(') 
				{ 
					$zqr = substr($zq2,strpos($zq2,$zq1.':')+2,100); 
//					echo '['.strpos($zq2,$zq1.':').']';
					if (strpos($zqr,'&') > 0) { $zqr = substr($zqr,0,strpos($zqr,'&')); }
					$zqr = $zq1.'-'.$zqr;
				}
				
			
			////////////////////// $
			if ($zq2 == '$') { $zqr =  Number_format($zq1/100,2); }
			////////////////////// $R
			if ($zq2 == '$R') { $zqr =  Number_format($zq1,2); }
			////////////////////// #
			if (substr($zq2,0,1) == '#') 
				{ $zqr =  '<CENTER>';
				$zqr = $zqr . $zq1; }
			////////////////////// @
			if ($zq2 == 'SHORT') 
				{ 
				$zqr = $zqr . SubStr($zq1,0,1).LowerCase(SubStr($zq1,1,strpos($zq1,' '))); 
				}
			if ($zq2 == '@') 
				{ $zqr =  UpperCase(SubStr($zq1,0,1));
				if (substr($zq1,1,1) == ' ') { $zqr = $zqr . '&nbsp;'; }
				$zqr = $zqr . LowerCase(SubStr($zq1,1,200)); }
			////////////////////// Bold
			if ($zq2 == 'B') ////// BOLD
				{$zqr = $zqr .'<B>'.$zq1.'</B>'; }
			////////////////////// CB				
			if ($zq2 == 'CB') 
				{ $varf=$vars[$varf];
				$vvvt = '';
				$vvvt = $vars['chk'.$zq1];
				if ($vvvt=="1") { $vvvt = "checked"; }
				$zqr = $zqr . '<input type="checkbox" name="chk'.$zq1.'" '.$vvvt.' value="1">'; }	
			////////////////////// CEP				
			if ($zq2 == 'CEP') ////// CEP
				{ 
				$xxcep = sonumero($zq1);
				if (strlen($xxcep)==8) { $xxcep=substr($xxcep,0,2).'.'.substr($xxcep,2,3).'-'.substr($xxcep,5,3); }
				$zqr =  $zqr . $xxcep; 
				}
			////////////////////// D
			if ($zq2 == 'D') 
				{ $zqr =  '<CENTER>';
				$dta = trim($zq1);
				if ($dta == '19000101') { $zqr = $zqr . '-'; }
				else { $zqr = $zqr . stodbr($zq1); } }
			////////////////////// DR
			if ($zq2 == 'DR') 
				{ 
				$zqr =  '<CENTER>';
				$dta = trim($zq1);
				if ($dta == '19000101') { $zqr = $zqr . '-'; }
				else { $zqr = $zqr . substr(stodbr($zq1),0,5); }
				}					
			////////////////////// H
			if ((substr($zq2,0,1) == 'H') and ($zq2 != 'H1') and ($zq2 != 'H2'))
				{ 
				$zqr = '';
				if ($hd != trim($zq1))
					{ 
					$zq1v = $zq1;
					if (substr($zq2,1,1) =='D') { $zq1v = stodbr($zq1); }
//					$zqr .= '<TR><TD colspan="15" height="1" bgcolor="#c0c0c0"></TD></TR>';
					$zqr .= '<TR><TD  bgcolor="#FFFFFF" colspan="15" class="lt2" align="left"><HR size="1"><B>'.$zq1v.'</TD></TR>';
					$hd = trim($zq1);
					$zqr = $zqr . '<TR '.coluna().'><TD></TD>';
					}
				} 
			if ($zq2 == 'H1') ////// ENFATIZADO
				{$zqr = $zqr .'<h1>'.$zq1.'</h1>'; }
			if ($zq2 == 'H2') ////// ENFATIZADO
				{$zqr = $zqr .'<h2>'.$zq1.'</h2>'; }				
			////////////////////// Italic
			if ($zq2 == 'I') ////// ITALIC
				{$zqr = $zqr .'<I>'.$zq1.'</I>'; }

			if ($$zq2 == 'NL') ////// Nova Linha
				{ $zqr = $zqr . '<TR '.$xcol.'><TD><TD>'.$linkv.$zq1; }
			if ($zq2 == 'SHORT') 
				{ $zqr = $zqr . LowerCase(SubStr($zq1,1,strpos($zq1,' '))); }
			////////////////////// SN
			if ($zq2 == 'SN') 
				{ 
				$zqr =  '<CENTER>';
				$dta = trim($zq1);
				if (($dta == '1') or ($dta == true) or ($dta=='S')) { $zqr = $zqr . 'SIM'; }
				else { $zqr = $zqr . 'NAO'; }
				}					
			if ($zq2 == 'Z') 
				{ 
				$zqr =  '<CENTER>';
				$zqr = $zqr .  strzero($zq1,'0'.substr($zq2,1,2)); 
				}							
			} else { $zqr =  $zq1; }	
			return($zqr);
	}
?>