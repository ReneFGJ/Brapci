<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
require("updatex_article_asc.php");
require($include."sisdoc_menus.php");


$titu[0] = 'Relat�rios Quantitativos da Base BRAPCI'; 
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?
$path = "brapci_rel_quanti.php";
$novo = '&nbsp;<font color="#ff8040"><B>(novo)</B></font>';
$menu = array();
/////////////////////////////////////////////////// MANAGERS 
array_push($menu,array('Publica��es','Artigos / Revista','brapci_rel_quanti_1.php')); 
array_push($menu,array('Publica��es','Artigos / Ano','base_rel_quanti_2.php')); 
array_push($menu,array('Publica��es','Artigos / Autores','brapci_rel_quanti_3.php')); 
array_push($menu,array('Publica��es','Artigos / Autores / Ano','base_rel_quanti_4.php')); 
array_push($menu,array('Publica��es','Tipos de artigos','brapci_rel_quanti_6.php')); 
array_push($menu,array('Publica��es','N� Revistas publicadas/ano','rel_quanti_5.php')); 
array_push($menu,array('Publica��es','Descritores mais encontrados','brapci_rel_quanti_10.php')); 
array_push($menu,array('Publica��es','Autores mais encontrados','brapci_rel_quanti_11.php')); 
array_push($menu,array('Publica��es','WebQualis (CAPES) / Peri�dico','brapci_rel_quanti_12.php')); 
array_push($menu,array('Publica��es','Links referencial / PDF coletados','brapci_rel_quanti_13.php')); 

array_push($menu,array('M�todos e T�cnicas de pesquisa','Pesquisa quanto aos fins','brapci_rel_mt.php?dd0=MF')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','Pesquisa quanto aos meios','brapci_rel_mt.php?dd0=MO')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','Enfoques quantitativos','brapci_rel_mt.php?dd0=ME')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','Enfoques qualitativos','brapci_rel_mt.php?dd0=MQ')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','T�cnicas de pesquisa','brapci_rel_mt.php?dd0=T')); 

array_push($menu,array('M�todos e T�cnicas de pesquisa','Relat�rio para an�lise','')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','__Quanto aos fins','rel_metodologia_1.php?dd0=MF')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','__Fins X Meios','rel_metodologia_2.php')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','__Meios X T�cnicas','rel_metodologia_3.php')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','__T�cnicas de pesquisa','rel_metodologia_4.php')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','__Tipos de an�lise','rel_metodologia_5.php')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','__Refer�ncias da amostra','rel_metodologia_ref.php')); 

array_push($menu,array('M�todos e T�cnicas de pesquisa','Relat�rio Novos','')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','__Sem Nome 1','rl_met_1.php')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','__Sem Nome 2','rl_met_2.php')); 
array_push($menu,array('M�todos e T�cnicas de pesquisa','__Palavras-Chave 3','rl_met_3.php')); 


array_push($menu,array('Palavras-chave','Resumo das palavras-chave'.$novo,'brapci_rel_quanti_14.php')); 

array_push($menu,array('Usu�rios','Resposta Enquete','brapci_rel_enquete_1.php')); 

$tela = menus($menu,"3");
?>
</DIV>
