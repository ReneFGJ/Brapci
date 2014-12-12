<?php
require("cab.php");

/*
 * $breadcrumbs
 */
$breadcrumbs = array();

//$sql = "ALTER TABLE pibic_pagamentos
//   ALTER COLUMN pg_nrdoc TYPE character(18);";
//$rlt = db_query($sql);
array_push($breadcrumbs,array(http.'admin/index.php',msg('principal')));
array_push($breadcrumbs,array(http.'admin/index.php',msg('menu')));
echo '<div id="breadcrumbs">'.breadcrumbs().'</div>';

			$sx .= '<form id="upload" action="'.$page.'" method="post" enctype="multipart/form-data">
					<fieldset><legend>'.msg('file_tipo').'</legend> 
    				<span id="post"><input type="file" name="arquivo" id="arquivo" /></span>
    				<input type="submit" value="enviar arquivo" name="acao" id="idbotao" />
    				<BR>';
				$sx .= '</fieldset></form>';
require("../_class/_class_pibic_pagamento.php");
$fl = new pagamentos;
echo $sx;
					    $nome = lowercasesql($_FILES['arquivo']['name']);
    					$temp = $_FILES['arquivo']['tmp_name'];
						$size = $_FILES['arquivo']['size'];
						
						if (strlen($acao) > 0)
							{

								$fl->inport_file($temp);
							} else {
								
							}
echo '==>'.$fl->processa_seq();						
	
require("../foot.php");	
?>