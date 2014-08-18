<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       13/05/2008 //
///////////////////////////////////////////
//
// Alterado de
// Link original: http://forum.wmonline.com.br/index.php?showtopic=182224
if ($mostar_versao == True) {array_push($sis_versao,array("sisDOC (Tips)","0.0a",20080513)); }

?>
<script language="JavaScript" type="text/JavaScript">
//detectando navegador
sAgent = navigator.userAgent;
bIsIE = sAgent.indexOf("MSIE") > -1;
bIsNav = sAgent.indexOf("Mozilla") > -1 && !bIsIE;
//setando as variaveis de controle de eventos do mouse
var xmouse = 0;
var ymouse = 0;
document.onmousemove = MouseMove;
//funcoes de controle de eventos do mouse:
function MouseMove(e)
	{ if (e) { MousePos(e); } 
	else 
	{ MousePos();}}
function MousePos(e) 
	{ if (bIsNav)
		{  xmouse = e.pageX;  ymouse = e.pageY; }  
		if (bIsIE) 
		{  xmouse = document.body.scrollLeft + event.x;  ymouse = document.body.scrollTop + event.y; }}
//funcao que mostra e esconde o hint
function Hint(objNome, action)
	{ 
	//action = 1 -> Esconder 
	//action = 2 -> Mover  
	if (bIsIE) {  objHint = document.all[objNome];  } 
	if (bIsNav) {  objHint = document.getElementById(objNome);  event = objHint; }  
	switch (action)
	{  	case 1: //Esconder   
			objHint.style.visibility = "hidden";  
			break;  
		case 2: //Mover   
			objHint.style.visibility = "visible";   
			objHint.style.left = xmouse + 15;   
			objHint.style.top = ymouse + 15;   
			objHint.style.width= 400;   
			break; 
	} 
	}
</script>
<!-- <style>
	.tips { background: #E0E0E0; border: solid 2px #303030; margin: 1px; padding: 5px; }
</style>-->
<?
global $tips_obj;
$tips_obj = 0;
function tips($cx1,$cx2)
	{
	global $tips_obj;
	$tips_obj++;
	$csi = "cdint".$tips_obj;
	$cs = '<A HREF="#" ';
	$cs .= ' onMouseMove="Hint('.chr(39).$csi.chr(39).',2)" ';
	$cs .= ' onMouseOut="Hint('.chr(39).$csi.chr(39).',1)">';
	$cs .= $cx1;
	$cs .= '</A>';
	$cs .= '<div id="'.$csi.'" class="tips" style="position:absolute; z-index:1; visibility: hidden; ">';
	$cs .= $cx2;
	$cs .= '</div>';
	return($cs);
	}
?>
