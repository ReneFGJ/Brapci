<?php
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       20/05/2008 //
///////////////////////////////////////////
?>
<XML>
<TITLE><?=$titulo?></TITLE>
<BOTAO>
<? for ($tt=0; $tt < count($botao); $tt++) {?>
<BOTAO_<?=$tt?> NOME="<?=$botao[$tt][0]?>" LINK="<?=$botao[$tt][1]?>" TYPE="<?=$botao[$tt][2]?>" />	
<? } ?>
<? if (strlen(trim($botao_voltar[0])) > 0) { ?>
<BOTAO_7 NOME="<?=$botao_voltar[0]?>" LINK="<?=$botao_voltar[1]?>" TYPE="<?=$botao_voltar[2]?>" />	
<? } ?>
</BOTAO>	
</XML>
	