</div><BR>
<div id="content_BOTTON"></div>
<center>
	&copy COPYRIGHT 1996-<?php echo date("Y"); ?>
</center>
	
<?php require("foot_menu.php");?>

<script>
	$.ajax({
  		type: "POST",
  			url: "article_mark.php",
			}).done(function( data ) {
	$("#basket").html(data);
			});	
</script>

<?
$face_liked = $face->liked();
if (strlen($dd[0]) > 0)
	{
		$query = $_SERVER['REQUEST_URI'];
		$page = 'http://'.$_SERVER['SERVER_NAME'].$query;

		$face_comment = $face->comentario($page);
	}
//$face_comment = $face->comment();

echo '
	<div id="conteudo_foot">
	<table width="800" align="center" bgcolor="white">
	<TR valign="top">
		<TD width="100">'.$face_liked.'</TD>
		<TD>'.$face_comment.'</TD>
	</TR>	
	</table>
	</div>';
?>