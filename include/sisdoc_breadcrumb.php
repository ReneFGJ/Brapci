<?php
    /**
     * BreadCrumbs
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com> (Analista-Desenvolvedor)
	 * @copyright Copyright (c) 2011 - sisDOC.com.br
	 * @access public
     * @version v0.11.29
	 * @package Include
	 * @subpackage Breadcrumb
	 * @category UC0001 - Gerar BradsCrumbs
    */

if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (BreadCrumbs)","0.0a",20110708)); }
if (strlen($crumbs) != 1)
{
	$crumbs = 1;
	function breadcrumbs()
		{
		global $breadcrumbs;
		$rst = '';
		$ch = '&nbsp;&raquo;&nbsp;';
		for ($rb=0;$rb < count($breadcrumbs);$rb++)
			{
			$imenu = $breadcrumbs[$rb][1];	
			$ilink = $breadcrumbs[$rb][0];	
			/* RN 002 - BreadCrumbs sem os nomes não são apresentados */
			if (strlen($imenu) > 0)
				{
				if (strlen($imenu) > 0)
					{ 
					$alink = '';
					if (strlen($ilink) > 0) { $alink = '<A HREF="'.$ilink.'">'; }
					if (strlen($rst) > 0) { $rst .= $ch; }
					$rst .= $alink.$imenu.'</A>';	
					}
				}
			}
		return('&nbsp;'.$rst);
		}

}
?>