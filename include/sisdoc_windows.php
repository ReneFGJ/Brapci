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
if ($mostar_versao == True) {array_push($sis_versao,array("sisDOC (Windws-Java)","0.0a",20080520)); }
if (strlen($include) == 0) { exit; }
?>
<script language="JavaScript">
 function fullwin(url) 	{ NewWindow=window.open(url,'padle','scrollbars=no,resizable=yes,width=1280,height=800,top=0,left=0');  NewWindow.focus(); void(0);}
 function newxy(url,xx,yy) {  NewWindow=window.open(url,'newwin','scrollbars=no,resizable=no,width='+xx+',height='+yy+',top=10,left=10');  NewWindow.focus(); void(0);}
 function newxy2(url,xx,yy) {  NewWindow=window.open(url,'newwin2','scrollbars=yes,resizable=no,width='+xx+',height='+yy+',top=10,left=10'); NewWindow.focus(); void(0);}
 function newxy3(url,xx,yy) {  NewWindow=window.open(url,'newwin3','scrollbars=yes,resizable=yes,width='+xx+',height='+yy+',top=10,left=10');  NewWindow.focus(); void(0);}
 function newwin(url) {  NewWindow=window.open(url,'newwin','scrollbars=no,resizable=yes,width=690,height=450,top=10,left=10');  NewWindow.focus(); void(0);}
 function newwin2(url) {  NewWindow=window.open(url,'newwin','scrollbars=yes,resizable=yes,width=690,height=450,top=10,left=10');  NewWindow.focus(); void(0);}
 function winclose() {  close(); }
</script>

<?
function newwin($link,$szh=50,$szv=50)
	{
		if ($szh < 50) { $szh = 50; }
		if ($szv < 20) { $szv = 20; }
		$sx = '<A HREF="javascript:newxy3(';
		$sx .= "'".$link."',".$szh.",".$szv.");";
		$sx .= '">';
		return($sx);
	}
