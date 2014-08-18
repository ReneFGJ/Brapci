<?php
    /**
     * Caixa Central
	 * @author Rene Faustino Gabriel Junior <monitoramento@sisdoc.com.br>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.13.231
	 * @package Excel Support
	 * @subpackage classe
    */
    
class excel
	{
		function header($filename='')
			{
				if (strlen($filename)==0)
					{
						$filename= "report_".date("Ymd_His").".xls";
					}  
			    header('Pragma: public');   
    			header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT'); 
    			header('Cache-Control: no-store, no-cache, must-revalidate');     // HTTP/1.1 
    			header('Cache-Control: pre-check=0, post-check=0, max-age=0');    // HTTP/1.1 
    			header ("Pragma: no-cache"); 
    			header("Expires: 0"); 
    			header('Content-Transfer-Encoding: none'); 
    			header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera 
    			header("Content-type: application/x-msexcel");                    // This should work for the rest 
    			header('Content-Disposition: attachment; filename="'.basename($filename).'"');
				return(1);
			}
	}
?>
