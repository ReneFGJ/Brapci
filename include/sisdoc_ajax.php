<?php
/**
* Esta classe é a responsável pela conexão com o banco de dados.
* @author Rene F. Gabriel Junior <rene@sisdoc.com.br>
* @version 0.0a
* @copyright Copyright © 2011, Rene F. Gabriel Junior.
* @access public
* @package BIBLIOTECA
* @subpackage sisdoc_ajax
*/
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       28/01/2008 // Primeiras Rotinas em AJAX
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Ajax)",'0.0a',20090128)); }
global $ajax;
if (strlen($include) == 0) { exit; }
if (strlen($ajax) == 0)
{
$ajax = True;
?>

<style>
.visi { visibility:visible; }
.invi { visibility:hidden; }
</style>

<script>
    var idf,idr;
	var req;
	idf = '';
	if (windows.XMLHttpRequest) 
	{ 
		req = new XMLHttpRequest();
		} 
	else if (windows.ActiveXobject) {
		req = new ActiveXobject("Microsoft.XMLHTTP");
	}

	function callback() {
		if (req.readyState==4) {
			if (req.status == 200) { 
				document.getElementById(idr).innerHTML = req.responseText;
				document.getElementById(idr).className = req.responseText;
			}
		}
	}
	
function cv(ic1,ic2) 
	{
   var ic3 = document.getElementById(ic1);
   var ic4 = document.getElementById(ic1+'a');
   ic3.value = ic2;
   ic4.className = 'invi';
	}
function filtrar(idc,http,idt) {
   var idField = document.getElementById(idc);
   var idv = idField.value;
   var url = http+"?dd0=" + encodeURIComponent(idField.value);
   idf = idc;
   idr = idt;
   if (idv.length >0)
   {
   if (typeof XMLHttpRequest != "undefined") {
       req = new XMLHttpRequest();
   } else if (window.ActiveXObject) {
       req = new ActiveXObject("Microsoft.XMLHTTP");
   }
   req.open("GET", url, true);
   req.onreadystatechange = callback;
   req.send(null);
   } else { document.getElementById(idr).className = 'invi'; }
}	
</script>
<? } ?>

