<?php
if (isset($_SESSION['bp_session'])) {
	$ssid = $_SESSION['bp_session'];
} else {
	$ssid = '';
}

/* Page count */
$pc = page_count();
?>
</div>
<BR>
<BR>
<div id="conteudo_foot">
	<table width="100%" align="center" bgcolor="white" border=0>
		<TR valign="top">
			<td width="50%">
			<div class="fb-page" 
			  data-href="https://www.facebook.com/brapci.ci/"
			  data-width="400" 
			  data-hide-cover="false"
			  data-show-facepile="false" 
			  data-show-posts="false"
			  show_facepile="true"></div>
			</TD>
			<td width="50%" align="right">
			<!--
			<div class="fb-page" data-href="https://www.facebook.com/brapci.ci/" 
				data-tabs="timeline" data-width="1024" 
				data-height="250" 
				data-small-header="false" 
				data-adapt-container-width="false" 
				data-hide-cover="true" 
				data-show-facepile="false">
			</div>
			-->
			</TD>
			<tr>
			<TD class="lt0" align="right" colspan=2><?php echo msg('session') . ':'; ?> <?php echo $ssid; ?></TD>
			</tr>
		</TR>
	</table>
</div>
