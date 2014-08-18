
<?
$tabela = "diris";
$cp = array();
array_push($cp,array('$H8','id_dis','id_dis',False,True,''));
array_push($cp,array('$S100','dis_nome','Nome',False,True,''));
array_push($cp,array('$H8','dis_nome_as','Nome Asc',False,True,''));
array_push($cp,array('$H7','dis_codigo','Codigo',False,True,''));
array_push($cp,array('$S7','di_eq_bp','Codigo Eq. Autor',False,True,''));
array_push($cp,array('$S7','dis_source','Codigo Eq. Autor (v2)',False,True,''));
array_push($cp,array('$O : &M:Masculino&F:Feminino','dis_genero','Genero',False,True,''));

array_push($cp,array('$S80','dis_instituicao','Instituição',False,True,''));
array_push($cp,array('$S80','dis_lattes','Link para Lattes',False,True,''));

$qs = "a_descricao:a_cnpq:select CONCAT_WS(' ',a_cnpq,a_descricao) as a_descricao,a_cnpq from ".$db_apoio."ajax_areadoconhecimento order by a_cnpq ";
array_push($cp,array('$S4','dis_nasc','Ano nascimento',False,True,''));
array_push($cp,array('$S4','dis_fale','Ano falecimento',False,True,''));
array_push($cp,array('$S40','dis_foto','Cod.Foto',False,True,''));
array_push($cp,array('$S80','dis_email','e-mail',False,True,''));
array_push($cp,array('$S80','dis_email_alt','e-mail (alternativo)',False,True,''));
array_push($cp,array('$S80','dis_url','Página pessoal (URL)',False,True,''));

array_push($cp,array('$T60:7','dis_biografia','Biografia',False,True,''));

array_push($cp,array('$Q '.$qs,'dis_forma_1','Formação (Graduação)',False,True,''));
array_push($cp,array('$S4','dis_forma_1_ano','Ano de formação (Graduação)',False,True,''));

array_push($cp,array('$Q '.$qs,'dis_forma_2','Formação (Mestrado)',False,True,''));
array_push($cp,array('$S4','dis_forma_2_ano','Ano de formação (Mestrado)',False,True,''));

array_push($cp,array('$Q '.$qs,'dis_forma_3','Formação (Doutorado)',False,True,''));
array_push($cp,array('$S4','dis_forma_3_ano','Ano de formação (Doutorado)',False,True,''));

array_push($cp,array('$Q '.$qs,'dis_forma_4','Formação (Pós-Doutorado)',False,True,''));
array_push($cp,array('$S4','dis_forma_4_ano','Ano de formação (Pós-Doutorado)',False,True,''));

array_push($cp,array('$O 1:SIM&0:Não','dis_ativo','Ativo',False,True,''));
array_push($cp,array('$U8','dis_update','',False,True,''));


array_push($cp,array('$Q pais_nome:pais_sigla3:select * from '.$db_apoio.'ajax_pais order by pais_nome','dir_nascionalidade','Nascionalidade',False,True,''));

/// Gerado pelo sistem "base.php" versao 1.0.5
?>