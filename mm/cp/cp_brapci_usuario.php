<?
$tabela = "brapci_usuario";
$cp = array();
array_push($cp,array('$H4','id_usuario','id_usuario',False,False,''));
/////////////////////
array_push($cp,array('$A','','Dados do usuário',False,True,''));
array_push($cp,array('$S100','usuario_nome','Nome completo',True,True,''));
array_push($cp,array('$Q t_descricao:t_codigo:select * from brapci_titulacao where t_ativo = 1','usuario_titulacao','Titulacao',False,True,''));
array_push($cp,array('$Q perfil_nome:perfil_codigo:select * from brapci_usuario_perfil','usuario_perfil','Perfil',False,True,''));
array_push($cp,array('$S20','usuario_login','Login',True,True,''));
array_push($cp,array('$P20','usuario_senha_md5','Senha',True,True,''));
array_push($cp,array('$S100','usuario_email','e-mail',True,True,''));
array_push($cp,array('$S100','usuario_email_alt','e-mail (alt)',False,True,''));
array_push($cp,array('$S100','usuario_telefone','Telefone',False,True,''));
array_push($cp,array('$S100','usuario_celular','Celular',False,True,''));
array_push($cp,array('$D8','usuario_datanasc','Data nascimento',True,True,''));
array_push($cp,array('$H8','usuario_codigo','codigo',False,True,''));
array_push($cp,array('$O 1:Sim&0:Não','usuario_ativo','Ativo',False,True,''));
array_push($cp,array('$S4','usuario_ano_inicio','Entrou na base em (ano)',False,True,''));
array_push($cp,array('$S4','usuario_ano_fim','Saiu da base em (ano)',False,True,''));

array_push($cp,array('$A','','Lattes',False,True,''));
array_push($cp,array('$S200','usuario_lattes','Link para o lattes',False,True,''));

array_push($cp,array('$A','','Dados adicionais',False,True,''));
array_push($cp,array('$CPF','usuario_cpf','CPF',False,True,''));

array_push($cp,array('$S15','usuario_rg','RG',False,True,''));
array_push($cp,array('$D8','usuario_rg_ex','Data expedição',False,True,''));
array_push($cp,array('$S10','usuario_emissor','Orgão emissor',False,True,''));

array_push($cp,array('$S15','usuario_matricula','Código matricula',False,True,''));
array_push($cp,array('$S20','usuario_departamento','Departamento',False,True,''));
array_push($cp,array('$S20','usuario_setor','Setor',False,True,''));
array_push($cp,array('$S100','usuario_endereco','Endereço',False,True,''));
array_push($cp,array('$S80','usuario_complemento','Complemento',False,True,''));
array_push($cp,array('$S30','usuario_cidade','Cidade',False,True,''));
array_push($cp,array('$CEP','usuario_cep','CEP',False,True,''));
array_push($cp,array('$S20','usuario_bairro','Bairro',False,True,''));
array_push($cp,array('$A','','Conta Corrente',False,True,''));
array_push($cp,array('$S50','usuario_cc_banco','Banco',False,True,''));
array_push($cp,array('$S15','usuario_cc_agencia','N. agência',False,True,''));
array_push($cp,array('$S20','usuario_cc_conta','N. conta corrente',False,True,''));
array_push($cp,array('$O : &1:Poupança&2:Correntista','usuario_cc_tipo','Tipo de conta',False,True,''));
// Gerado pelo sistem "base.php" versao 1.0.2
?>