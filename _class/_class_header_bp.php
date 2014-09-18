<?php
class header
	{
	//var $charcod = "UTF-8";
	var $charcod = "ISO-8859-1"; 
	
	var $title = 'Brapci';
	var $google_id = 'UA-12803182-4';
	var $login_api = '';
	
	function head()
		{
		global $LANG, $http, $style_add;
		$cr = chr(13).chr(10);
		$pth = $this->path;
		
		header ('Content-type: text/html; charset='.$this->charcod);
		//$sx .= ''.$cr;
		$sx .= '<head>'.$cr;
    	//$sx .= '<META HTTP-EQUIV=Refresh CONTENT="3600; URL='.$http.'logout.php">'.$cr;
		$sx .= '<meta http-equiv="Content-Type" content="text/html; charset='.$this->charcod.'" />'.$cr;
		$sx .= '<meta name="google-site-verification" content="VZpzNVBfl5kOEtr9Upjmed96smfsO9p4N79DZT38toA" />'.$cr;		
        $sx .= '<meta name="description" content="">'.$cr;
        $sx .= '<link rel="shortcut icon" type="image/x-icon" href="'.$http.'favicon.ico" />'.$cr;
		$sx .= '<link rel="stylesheet" href="'.$http.'css/style_cabecalho.css">'.$cr;

		/* Style */
		$style = array('style.css','style_form.css','style_menu.css','style_roboto.css');
		for ($r=0;$r < count($style);$r++)
				{ $sx .= '<link rel="STYLESHEET" type="text/css" href="'.$http.'css/'.$style[$r].'">'.$cr; }
		/* Style Adicional */
		if (isset($style_add)) {		
		for ($r=0;$r < count($style_add);$r++)
				{ $sx .= '<link rel="STYLESHEET" type="text/css" href="'.$http.'css/'.$style_add[$r].'">'.$cr; } }

		/* Java script */
		$js = array('jquery.js'); 
		for ($r=0;$r < count($js);$r++)
			{ $sx .= '<script type="text/javascript" src="'.$http.'js/'.$js[$r].'"></script>'.$cr; }


		/* Icone */
		$sx .= '<link rel="shortcut icon" href="http://www.brapci.inf.br/favicon.png" />'.$cr;
			
    	$sx .= '<title>'.$this->title.'</title>'.$cr;
		$sx .= '</head>'.$cr;
		$sx .= '<body>'.$cr;
		
		$sx .= "		
		<script>
  			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			  ga('create', '".$this->google_id."', 'brapci.inf.br');
  			ga('send', 'pageview');
		</script>		
			";
		
		$LANG='pt_BR';
		return($sx);
		}
	function cab()
		{
			$sx = $this->head();
			$sx .= $this->api_google;
			return($sx);
		}
	function cab_popup()
		{
			global $user_name;
			$sx = '
			<div class="cab"><div class="geral">';
			if (strlen($user_name) > 0) { echo $user_name.' ('.$user_email.')'; } 
			$sx .= '<BR><BR></div></div>'.chr(13).chr(10);
			return($sx);
			
		}
	}
?>