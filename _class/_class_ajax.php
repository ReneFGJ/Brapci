<style>
#loading
	{
	display: none;
	border: 1px solid black;
	position: absolute;
	top: 50%;
	left: 50%;
	width: 300px;
	height: 60px;
	background: white;
	margin-left: -150px;
	margin-top: -30px;
}
</style>
<div id="loading" ><center>Pocessando busca...<BR><BR><img src="img/icone_ajax_waiting.gif"></center></div>
	
<script>
   $("body").ajaxSend(function()
     {
     	$("#loading").fadeIn();
        $("div#error").remove();   
        $("#conteudo").children().not('#loading').css({'opacity':0.22});
     });
    $("body").ajaxComplete(function()
     {
     	$("#conteudo").children().not('#loading').css({'opacity':1});
        $("#loading").hide();
        
     });
</script>