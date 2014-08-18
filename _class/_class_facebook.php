<?php
class facebook
	{
		function share()
			{
			$sx = '
			<script>
			function fbs_click() {u=location.href;t=document.title;window.open(\'http://www.facebook.com/sharer.php?u=\'+encodeURIComponent(u)+\'&t=\'+encodeURIComponent(t),\'sharer\',\'toolbar=0,status=0,width=626,height=436\');
			return false;
			}</script>
			<a rel="nofollow" href="http://www.facebook.com/share.php?u=www.sisdoc.com.br" onclick="return fbs_click()" target="_blank"><img src="/reol/img/icone_share_facebook.png" border=0></a> 
			';	
			return($sx);			
			}
	}
