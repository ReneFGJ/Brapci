	<?
$bs = trim($titulo_1);
$bs = troca($bs,' ','%20');

$hg = 'http://www.google.com.br/#hl=pt-BR&q=%22';
$hg .= $bs;
$bg .= '%22';
$hg .= '&btnG=Pesquisa+Google&meta=&aq=f';
$sx .= '<BR><BR>Recuperar este artigo nos mecanismos de busca<BR>';
$sx .= '<A href="'.$hg.'" title="busca este título no google" target="_new"><img src="img/icone_google_mini.png" alt="" border="0"></A>';

$hb = 'http://www.bing.com/search?q=%22';
$hb .= lowercasesql($bs);
$hb .= '%22';
$hb = troca($hb,' ','%20');
$hb .= '&go=&form=QBRE&filt=all';
$sx .= '&nbsp;&nbsp;';
$sx .= '<A href="'.$hb.'" title="busca este título no bing" target="_new"><img src="img/icone_bing_mini.png" alt="" border="0"></A>';
?>