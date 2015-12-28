<?php
if (isset($_SESSION['bp_session']))
{
	$ssid = $_SESSION['bp_session'];
} else {
	$ssid = '';
}
?>
</div>
<BR>
<BR>
<div id="conteudo_foot">
	<table width="100%" align="center" bgcolor="white" border=0>
		<TR valign="top">
			<TD width="300"><div id="fb-root"></div>
			<script>
				( function(d, s, id) {
						var js,
						    fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id))
							return;
						js = d.createElement(s);
						js.id = id;
						js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=284206068404926";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
			</script><div class="fb-like-box" data-href="https://www.facebook.com/brapci.ci" data-width="100%" data-height="240"  data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div></TD>
			<TD class="lt0" align="right">Session: <?php echo $ssid;?></TD>
		</TR>
	</table>
</div>
