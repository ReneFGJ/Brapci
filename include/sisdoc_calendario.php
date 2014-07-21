<?php
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       03/01/2010 //
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Calendario)","0.0a",20100103)); }

function agenda($data,$dias)
{
	$ss='';
	$ssc='<TABLE class="lt0" width="300" border=1>';
	$ssc.='<TR><TD bgcolor="#c0c0c0" align="center" colspan="10"><B>AGENDA</B></TD></TR>';
	for ($kk=0;$kk < count($dias);$kk++)
		{
		$xcor = trim($dias[$kk][3]);
		if (strlen($xcor) > 0) { $xcor = ' bgcolor="'.$xcor.'" '; }
		$link = '<A HREF="#" onclick="newxy('."'cryo_calendario_novo.php?dd0=".$dias[$kk][5]."'".',550,200);">';
		$ss.= '<TR '.$xcor.'>';
		$ss.= '<TD>';
		$ss.= $link;
		$ss.= $dias[$kk][0];
		$ss.= '<TD>';
		$ss.= $link;
		$ss.= $dias[$kk][1];
		$ss.= '<TD align="center">';
		$ss.= $dias[$kk][2];
		$ss.= '<TD align="center">';
		$ss.= stodbr($dias[$kk][4]);
		}
	if (strlen($ss) > 0) { $ss = $ssc.$ss.'</TABLE>'; }	
	return($ss);
}

function calendario($data,$dias)
{
	global $xlink,$nucleo;
	//////////////////////////////////// Celandário MES
	if (strlen($data) == 6)
	{
		$mes = substr($data,4,2);
		$ano = substr($data,0,4);
		$ss='<TABLE class="lt0" width="300" border=1>';
		$ss.='<TR><TD bgcolor="#c0c0c0" align="center" colspan="10"><B>'.nomemes(intval($mes)).'/'.$ano.'</B></TD></TR>';
		$ss.='<TR align="center"><TD><B>DOM</B><TD><B>SEG</B><TD><B>TER</B>';
		$ss.='<TD><B>QUA</B><TD><B>QUI</B><TD><B>SEX</B><TD><B>SAB</B></TR>';
		$ddx=1;
		$ydata = stod($ano.$mes.'01');
		$xdata = stod($ano.$mes.'01');
		$ys = Date('w',$ydata);
		$ss.='<TR align="center">';
		if ($ys > 0) { $ss.='<TD colspan="'.$ys.'">&nbsp;'; }
		for ($kk=0;$kk<40;$kk++)
			{
			$bg = "";
			$xcor = '';
			$tt = '';
			for ($ky=0;$ky <= (strlen($dias)+5);$ky++)
				{
//				echo $dias[$ky][0].'='.$ddx.'<BR>';
				if ($dias[$ky][0]==$ddx)
				 { 
					$xcor = trim($dias[$ky][3]);
					if (strlen($xcor) > 0) { $xcor = ' bgcolor="'.$xcor.'" '; }
					 $tt.= $dias[$ky][0].'='.$dias[$ky][1].chr(13).chr(10); $bg = $xcor; 
				 }  
				}
				
			$xdata = stod($ano.$mes.strzero($ddx,2));
			$bold = '';
			if (date("m",$xdata) != date("m",$ydata)) { $kk=99; }
				else
				{
					if (strlen($xlink) ==0) 
						{
							$link = '<A HREF="#" onclick="newxy('."'cryo_calendario_novo.php?dd1=".date("d/m/Y",$xdata)."'".',550,200);">';
						} else {
							$link = '<A HREF="'.$xlink."?dd0=".date("d/m/Y",$xdata)."&dd1=".$nucleo.'" onmouseover="return true;">';
						}
					if (strlen($tt) > 0) { $bold='<B>'; }
					$ss.= '<TD '.$xcor.'>'.$link.$bold.$ddx++.'</TD>'.chr(13).chr(10);
					if (date("w",$xdata) == 6) { $ss.= '<TR align="center">'; }
				}
//			$ss.=$xdata;
			}
		
		$ss.="</TABLE>";
	}
	return($ss);
}
?>