<?php

function nwin($link='',$w=200,$h=50,$resize=1,$scroll=0)
	{
		$sx = ' onclick="NewWindow=window.open(\'.$link.\',\'newwin\',\'scrollbars=no,resizable=no,width='+$w+',height='+$h+',top=10,left=10\'); NewWindow.focus(); void(0); alert(\'1\');" ';
		//$sx = ' onclick="alert(\'ola\');" ';
		return($sx);
	}
	
class email
	{
	var $email = 'brapci@brapci.inf.br';
	var $email_name = 'Brapci';
	
	function send_to_email($article,$size=32)
		{
			global $user_email;
			$sx = '';
			if (strlen($user_email) > 0)
				{
				$link = nwin('article_send_email.php?dd0='.$cod.'&dd99='.checkpost($cod),200,200);
				$sx = '<img src="img/icone_send_to_email_off.png" 
							border=0 title="'.msg('send_to_email').'" 
							height="'.$size.'"
							onmouseover="this.src=\'img/icone_send_to_email.png\'"
							onmouseout="this.src=\'img/icone_send_to_email_off.png\'"
							'.$link.'
							>';
				}
			return($sx);
		}
	
	function mail_authentic($email,$subject,$text)
		{
			
		}
	
	function header()
		{
			
		}
	function send($email,$subject,$text)
		{
			return(1);
		} 	
	}
?>
