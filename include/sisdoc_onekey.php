<?php
class onkey
	{
		function structure($create='')
			{
			$sql = "CREATE TABLE onekey
				(
  				id_onekey serial NOT NULL,
  				onekey_shortcut character(5),
  				onekey_descricao character(40),
  				onekey_http character(100),
  				onekey_aitvo integer DEFAULT 1,
  				onekey_newwin integer DEFAULT 0
  				);";
			if ($create=='S') { $rlt = db_query($sql); }
			return($sql);
			}
	}
$nkey = new onkey;
//$nkey->structure('');

session_start();
    /**
     * OnKey
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com> (Analista-Desenvolvedor)
	 * @copyright Copyright (c) 2011 - sisDOC.com.br
	 * @access public
     * @version v0.11.29
	 * @package Include
	 * @subpackage Apoio
     */
$onekey_form = '<TABLE><TR><TD><form method="post" action="'.$http_local.'cab.php"></TD><TD><input type="text" name="onekey" size="5" maxlength="5" style="width: 40px; text-transform : lowercase; "></TD><TD></FORM></TD></TR></TABLE>';
if (strlen($include) == 0) { exit; }
$onekey = trim(lowercase(trim($vars['onekey'])));
$red = true;

if (strlen($onekey) > 0)
	{ 
	$op = false;
	if ($onekey == 'calc') 
		{
		$red = false;
		?>
		<script>
			window.open('<?=$include.'sisdoc_calc.php'?>','calc','scrollbars=no,resizable=no,width=230,height=260,top=10,left=10'); 
		</script>
		<?php
		}
	
	$onesql = "select * from onekey where lower(onekey_shortcut) = '".$onekey."' ";
	$orlt = db_query($onesql);
	if ($oline = db_read($orlt))
		{
			$http = trim($oline['onekey_http']);
		} else {
			$http = $_SERVER['HTTP_REFERER'];
		}
//		echo $http;
	if ($red == true) {	redirecina($http); }
	}
/*
CREATE TABLE onekey
(
  id_onekey serial NOT NULL,
  onekey_shortcut character(5),
  onekey_descricao character(40),
  onekey_http character(100),
  onekey_aitvo integer DEFAULT 1,
  onekey_newwin integer DEFAULT 0,
  CONSTRAINT id_onekey PRIMARY KEY (id_onekey)
)
*/
?>
