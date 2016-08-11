<?php
class messages
	{
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','','',False,True));
			array_push($cp,array('$S80','m_descricao','',True,True));
			array_push($cp,array('$T80:5','m_header','',False,True));
			array_push($cp,array('$:5','m_foot','',False,True));
			array_push($cp,array('$O 1:SIM&0:NÃO','m_ativo','',True,True));
			array_push($cp,array('$S80','m_email','',True,True));
			array_push($cp,array('$H8','m_own_cod','',False,True));
			array_push($cp,array('$S80','smtp_host','',True,True));
			array_push($cp,array('$S80','smtp_user','',True,True));
			array_push($cp,array('$S80','smtp_pass','',True,True));
			array_push($cp,array('$O smtp:SMTP','smtp_protocol','',True,True));
			array_push($cp,array('$S8','smtp_port','',True,True));
			array_push($cp,array('$HV','mailtype','html',True,True));
			return($sx);
		}	
	}
?>
