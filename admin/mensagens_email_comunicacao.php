<?
require("cab.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_email.php");
require($include."cp2_gravar.php");

		$tabela = '';
		$cp = array();
		array_push($cp,array('$S200','','Título do e-mail',True,True,''));
		array_push($cp,array('$T60:10','','Texto para enviar',True,True,''));
		array_push($cp,array('$O : &TST:e-mail de teste para pibucpr@pucpr.br (limite 10)&VID:Somente no video&SIM:SIM Confirmar envio de email','','Com dados detalhados',True,True,''));
		array_push($cp,array('$T60:10','','Lista de e-mail (separado por ponto e virgula (;)',True,True,''));
		array_push($cp,array('$B8','','Enviar mensagem',false,True,''));

		echo '<TABLE width="'.$tab_max.'">';
		echo '<TR><TD>';
		editar();
		echo '<TR><TD colspan="2">';
		echo '</TD></TR>';
		echo '</TABLE>';	
		
if ($saved > 0)
	{
		$email_producao = $dd[2];
		$total = 0;
		$usnome = 'X';
		$e4 = mst($dd[1]);
		$e3 = $dd[0];
		
		if (($email_producao == 'VID') and ($tot < 11))
			{ echo '<HR>'.$e4; }			
		
		if (($email_producao == 'TST') and ($tot < 11))
			{
			$e1 = 'pibicpr@pucpr.br';
//			enviaremail($e1,$e2,$e3,$e4);
			$e1 = 'rene@sisdoc.com.br';
			enviaremail($e1,$e2,'TST-'.$e3,$e4);
			}
			
		if ($email_producao == 'SIM')
			{
			$dx = ' '.$dd[3].';';
			while (strpos($dx,';') > 0)
				{
				$e1 = trim(substr($dx,0,strpos($dx,';')));
				$dx = ' '.substr($dx,strpos($dx,';')+1,strlen($dx));
				$email_2 = $e1;
				
				echo '<BR>enviado ';
				if (strlen($email_2) > 0) { enviaremail($email_2,$e2,$e3,$e4); echo $email_2.'<BR>'; }
				}
			}
	echo "<center>Total de ".$tot." comunicados enviados</center>";
	}

require("foot.php");	
?>