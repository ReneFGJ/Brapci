<?php
class protocolo_ss
	{
	function form_001()
		{
			global $dd,$cp;
			$cp = array();
			if (strlen($dd[3])==1)
			array_push($cp,array('$H8','','',False,True));
			array_push($cp,array('$S11','','Informe o código do aluno',True,True));
			array_push($cp,array('$HV','','',True,True));
			return($cp);		
		}	
	}
?>
