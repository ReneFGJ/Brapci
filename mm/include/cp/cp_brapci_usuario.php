<?
$tabela = "brapci_usuario";
$cp = array();
array_push($cp,array('$H4','id_usuario','id_usuario',False,False,''));
/////////////////////
array_push($cp,array('$A','','Dados do usuário',False,True,''));
array_push($cp,array('$S100','usuario_nome','Nome completo',False,True,''));
array_push($cp,array('$S20','usuario_login','Login',False,True,''));
array_push($cp,array('$P20','usuario_senha_md5','Senha',False,True,''));
array_push($cp,array('$S100','usuario_email','e-mail',False,True,''));
array_push($cp,array('$S100','usuario_email_alt','e-mail (alt)',False,True,''));
array_push($cp,array('$S100','usuario_telefone','Telefone',False,True,''));
array_push($cp,array('$S100','usuario_celular','Celular',False,True,''));
array_push($cp,array('$D8','usuario_datanasc','Data nascimento',False,True,''));
// Gerado pelo sistem "base.php" versao 1.0.2
?>