<style type="text/css">
#fifteenth {position: absolute;width: 150px;border: 2px solid black;padding: 2px;background-color: #ffffbb;visibility: hidden;z-index: 99;filter: progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135);} 
</style>

<div id="fifteenth" class="lt1">aa</div>
<script type="text/javascript">
var sixteenth=-62; var seventeenth=21; var eighteenth=document.all;var nineteenth=document.getElementById && !document.all;var twentieth=false;
if (eighteenth||nineteenth)var first2=document.all? document.all["fifteenth"] : document.getElementById? document.getElementById("fifteenth") : "";
function second2(){return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body;}

function tipsshow(fourth2, sixth2){
	fifth2 = '#ffffbb';
	if (nineteenth||eighteenth){
	if (typeof sixth2!="undefined") first2.style.width=sixth2+"px";
	if (typeof fifth2!="undefined" && fifth2!="") first2.style.backgroundColor=fifth2;first2.innerHTML=fourth2;twentieth=true;return false;}
	}
function fifteenth3(e){
if (twentieth){var sixteenth3=(nineteenth)?e.pageX : event.x+second2().scrollLeft;var seventeenth3=(nineteenth)?e.pageY : event.y+second2().scrollTop;var eighteenth3=eighteenth&&!window.opera? second2().clientWidth-event.clientX-sixteenth : window.innerWidth-e.clientX-sixteenth-20;var nineteenth3=eighteenth&&!window.opera? second2().clientHeight-event.clientY-seventeenth : window.innerHeight-e.clientY-seventeenth-20;var twentieth3=(sixteenth<0)? sixteenth*(-1) : -960;
if (eighteenth3<first2.offsetWidth)first2.style.left=eighteenth? second2().scrollLeft+event.clientX-first2.offsetWidth+"px" : window.pageXOffset+e.clientX-first2.offsetWidth+"px";
else if (sixteenth3<twentieth3)first2.style.left="5px";else first2.style.left=sixteenth3+sixteenth+"px";
if (nineteenth3<first2.offsetHeight)first2.style.top=eighteenth? second2().scrollTop+event.clientY-first2.offsetHeight-seventeenth+"px" : window.pageYOffset+e.clientY-first2.offsetHeight-seventeenth+"px";else first2.style.top=seventeenth3+seventeenth+"px";first2.style.visibility="visible";}}
function first4(){
if (nineteenth||eighteenth){twentieth=false;first2.style.visibility="hidden";first2.style.left="-1000px";first2.style.backgroundColor='';first2.style.width='';}}document.onmousemove=fifteenth3;

 function newwin(url,x,y) {  window.open(url,'newwin','toolsbar=no,scrollbars=no,resizable=no,width='+x+',height='+y+',top=10,left=10'); }

</script>
<?

function isUpper($chc)
	{
	$chc = substr($chc,0,1);
	$chu = 'ZABCDEFGHIJKLMNOPQRSTUVWXYZ';
	if (strpos($chu,$chc))
		{ return(True); } else { return(False); }
	}
function peer_article_mostra($texto,$id,$coment,$para)
	{
	global $dd,$formato_printer;
	$cmt = array();
	$coment = troca($coment,chr(13),'');
	$coment = troca($coment,chr(10),'');
	$coment = troca($coment,chr(15),'');
	$ctm = '--'.$coment.'¢¢';
	$texto = troca($texto,'  ',' ');
	$texto = ' '.troca($texto,'. ','¢¢').'¢¢';
	$tips = '';
	$xln=0;
	$font = '<font class="pn">';
	$div = '';
//	echo '--------------------'.$texto.strpos($texto,'¢¢');
	while (strpos($texto,'¢¢') > 0)
		{
		$xln=$xln+1;
		$pos = strpos($texto,'¢¢');
		$ln = substr($texto,0,$pos);
//		if (substr($ln,strlen($ln),1) == '.') { $ln = substr($ln,0,strlen($ln)-1).'#'; }

		$texto = substr($texto,$pos+2,strlen($texto));
	
		/////////////////////////////////////
		$coment = '';
		$tmk = "#".$para.'#'.$xln.'#';
		$tipo = '';
		if (strpos(' '.$ctm,$tmk) > 0)
			{
			$coment = trim(substr($ctm,strpos($ctm,$tmk)+strlen($tmk),strlen($ctm)));
			$tipo = trim(substr($coment,0,strpos($coment,']')+1));
			$coment = trim(substr($coment,strpos($coment,'#')+1,strlen($coment)));
			if (strpos(' '.$coment,'#') > 0)
				{
				$coment = trim(substr($coment,0,strpos($coment,'#')));
				}
			$comment = utf8_encode($comment);
			}
//		echo '<BR>Coment=='.$coment;
//		echo '<BR>'.$tmk;
//		echo '<BR>==>'.$ln;
//		echo '<HR>';
		//	$coment = substr($coment,5,strlen($coment));
		$tips = '';
		$font = '';
		/////////////////////////////////////
		if (strlen(trim($coment)) > 0)
			{ 
			$coment = troca($coment,'¢¢','');
			$tips = " onMouseover=".chr(34)."tipsshow(' ".$coment."! ',320)".chr(34).'; onMouseout="first4()" '; 
			if ($tipo == '[2]')
				{ $font = '<font class="pz">'; } else {	$font = '<font class="pr">'; }
			if ($formato_printer)
				{ if ($tipo == '[2]')
					{ $font = '<font class="ipz">'; } else { $font = '<font class="ipr">'; } 
					$ln = $ln . '<SUP>'.$xln.'</SUP>';
					$div = $div.'<DIV align="right" align="300"><SUP>'.$xln.'</SUP>'.$coment.'</DIV>';
				}
			}
		$slink = 'onclick="newwin('.chr(39).'peer_comentario.php?dd0='.$id.'&dd1='.$xln.'&dd2='.$para.chr(39).',400,250);" ';
		$link = '<A HREF="#'.$id.'" javascript="winopen('.chr(39).'peer_comentario.php'.chr(39).');" class="pn" '.$slink.'  '.$tips.'>';
		//////////////////////////////////////
		if (strlen($ln) > 0)
			{
			$per = $per . $link. $font. $ln .'</A></font>';
			}
		}
	return($per.$div);
	}
	
function peer_article_import($texto,$id)
	{
	$sql = "select * from peer_article_review where pp_article_pp = ".$id;
	$rlt = db_query($sql);
	if (!($line = db_read($rlt)))
		{
		$paragrafo = 1;
		$texto = $texto . chr(13);
		$ln = array();
		$sql = "";
		while (strpos($texto,chr(13)))
			{
			$pos = strpos($texto,chr(13));
			$sln = trim(substr($texto,0,$pos));
			$texto = substr($texto,$pos+1,strlen($texto));
			if (strlen($sln) > 0)
				{
				echo '<HR>'.$sln;
				$sql = $sql . "insert into peer_article_review";
				$sql = $sql . "(pp_article_pp,pp_texto,pp_comentario,";
				$sql = $sql . "pp_dt_comentario,pp_log_comentario,pp_tipo,";
				$sql = $sql . "pp_paragrafo) values (";
				$sql = $sql . $id.",'".$sln."','',";
				$sql = $sql . "19000101,0,'N',";
				$sql = $sql . $paragrafo++.");";
				}
			}
			if (strlen($sql) > 0)
				{
				$rlt = db_query($sql);
				}
		} else {
			echo "Artigo ja inserido na base";
		}
	}
?>