<?
$tabela = "ic_inscricao";
$cp = array();
array_push($cp,array('$H5','id_i','',False,True,''));
array_push($cp,array('$H5','i_evento','i_evento',False,True,''));
array_push($cp,array('$U8','i_data','Data',False,True,''));
array_push($cp,array('$H5','i_hora','Hora',False,True,''));
array_push($cp,array('$S100','i_nome_completo','Nome completo',True,True,''));
array_push($cp,array('$S100','i_email','e-mail',True,True,''));
array_push($cp,array('$H40','i_nome_cracha','Nome para o racha',False,True,''));
array_push($cp,array('$S100','i_instituicao','Instituicao',False,True,''));
array_push($cp,array('$O P:Profissional&E:Estudante','i_tipo_inscricao','Tipo inscricao',False,True,''));
array_push($cp,array('$H8','i_vlr_inscricao','Vlr Inscrição',False,True,''));
array_push($cp,array('$T40:4','i_obs','Observação',False,True,''));
array_push($cp,array('$H50','i_senha','Senha',False,True,''));
array_push($cp,array('$H20','i_cpf','CPF',False,True,''));
array_push($cp,array('$H20','i_rg','i_rg',False,True,''));
array_push($cp,array('$H100','i_endereco','Endereco',False,True,''));
array_push($cp,array('$S20','i_cidade','Cidade',False,True,''));
array_push($cp,array('$H20','i_bairro','Bairro',False,True,''));
array_push($cp,array('$S2','i_estado','Estado',False,True,''));
array_push($cp,array('$H20','i_pais','Pais',False,True,''));
array_push($cp,array('$H1','i_status','status',False,True,''));
$dd[3] = date("H:i");
$dd[19] = 'A';

/// Gerado pelo sistem "base.php" versao 1.0.4
?>
