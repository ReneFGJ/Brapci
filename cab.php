<?php
    /**
     * DB
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com> (Analista-Desenvolvedor)
	 * @copyright Copyright (c) 2014 - sisDOC.com.br
	 * @access public
     * @version v0.14.17
	 * @package Header Page
	 * @subpackage Cab 
    */
    
require("db.php");

/* Cabecalho da pagina */
require("_class/_class_header_bp.php");
$hd = new header;
echo $hd->head();
?>
<body>
	