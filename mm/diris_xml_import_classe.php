<?
/*
   Extrai os dados do extrato fornecido pelo banco no formato xml
     e alimenta a tabela banco_extrato da base FGHI.
*/

//Inicializando a classe com valores default
global $elemento_corrente;
global $include;

//Encontra os tokens do arquivo xml
function parser($arquivo){    

	global $db, $conn; //Torna-se visível para a função
	global $tab_max;
	$key_bco_nrbanco='';
	$key_bco_nome='';
	
	$reg_import=0;
	$reg_lidos=0;
	$cabecalho=0;
	$cont_historico=0;
	$reg_cadastrado=0;

  	if(!($fp=fopen($arquivo, "r"))){
		echo msg_erro('O arquivo '.$arquivo.' não foi encontrado.');
		return(0);
	 	exit;
  	}
  
    //Percorre o arquivo
	$linha='';

	$id = 0;
	$hd = array();
	while (!feof($fp))
		{
		$linha=fgets($fp);
	  	$tag=retorna_tag($linha);
	  	switch($tag){
	  	case "apellidos":
	  		$nome=UpperCase(ps(UTF8_decode(trim(retorna_valor($linha)))));
			break;
		case "nombre":
	  		$nome .= ', '.(ps(UTF8_decode(trim(retorna_valor($linha)))));
	  		$nome_asc .= UpperCaseSql($nome);
			if (strpos($nome_asc,',') > 0)
				{ $nome_asc = trim(substr($nome_asc,strpos($nome_asc,',')+1,strlen($nome_asc))).' '.trim(substr($nome_asc,0,strpos($nome_asc,','))); }
			echo '<PRE>'.$nome_asc.'</PRE>';
			break;
		case "sexo":
	  		$genero .= ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "institucion":
			$inst = ps(UTF8_decode(retorna_valor($linha)));
			$inst = troca($inst,'&lt;br&gt;','');
			break;
		case "direccion":
			$endereco = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "codigo_postal":
			$cep = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "direccion":
			$endereco = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "ciudad":
			$cidade = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "pais":
			$pais = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "telefono":
			$telefone = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "fax":
			$fax = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "mail":
			$email = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
			
		case "ocultar_correos":
			$hidden = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "mail2":
			$email2 = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "skype":
			$skype = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "url":
			$url = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "url_institucion":
			$url2 = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "researcherid":
			$pesq = ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "descriptor":
			$descritor=ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "materia":
			$hd = array();
			break;
		case "valor":
			$vlr=ps(UTF8_decode(trim(retorna_valor($linha))));
			array_push($hd,$vlr);
			break;
		case "fotografia":
			$foto=ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		case "dc.publisher":
			$publisher=ps(UTF8_decode(trim(retorna_valor($linha))));
			break;
		  	}
	}
	
	$sql = "select * from diris where dis_nome_as = '".$nome_asc."' ";
	$rlt = db_query($sql);
	if (!($line = db_read($rlt)))
		{ 
		$xsql = "insert into diris (dis_nome,dis_nome_as,dis_codigo) values ('".$nome."','".$nome_asc."','')";
		echo '<TT><PRE>'.$xsql.'</PRE>';

		$xrlt = db_query($xsql);
		
		$xsql = "update diris set dis_codigo=lpad(id_dis,'7','0') where (length(dis_codigo) < 7 );";
		$xrlt = db_query($xsql);

		$rlt = db_query($sql);
		$line = db_read($rlt);
		}
	$cod = $line['dis_codigo'];
	echo '[[[['.$cod.']]]]';
	
	$sql = "update diris set ";
	$sql .= " dis_instituicao  ='".$inst."', ";
	$sql .= " dis_endereco  ='".$endereco."', ";
	$sql .= " dis_bairro  ='".$bairro."', ";
	$sql .= " dis_cidade  ='".$cidade."', ";
	$sql .= " dis_telefone  ='".$telefone."', ";
	$sql .= " dir_nascionalidade  ='".$pais."', ";
	$sql .= " dis_email  ='".$email."', ";
	$sql .= " dis_email_alt  ='".$email2."', ";
	$sql .= " dis_fonte  ='', ";
	$sql .= " dis_fonte_link  ='', ";
	$sql .= " dis_fonte_coleta  ='', ";
	$sql .= " dis_forma  ='URL', ";
	$sql .= " dis_lattes  ='".$lattes."', ";
	$sql .= " dis_oculto  ='".$hidden."', ";
	$sql .= " dis_genero  ='".$genero."', ";
	$sql .= " dis_skype  ='".$skype."', ";
	$sql .= " dis_url  ='".$url."', ";
	$sql .= " dis_ulr_inst  ='".$url2."', ";
	$sql .= " dis_pesq  ='".$pesq."', ";
	$sql .= " dis_descritor  ='".$descritor."', ";
	$sql .= " dis_foto_url  ='".$foto."', ";
	$sql .= " dis_key_1  ='".$hd[0]."', ";
	$sql .= " dis_key_2  ='".$hd[1]."', ";
	$sql .= " dis_key_3  ='".$hd[2]."', ";
	$sql .= " dis_key_4  ='".$hd[3]."', ";
	$sql .= " dis_key_5  ='".$hd[4]."', ";
	$sql .= " dis_update  ='".date("Ymd")."', ";
	$sql .= " dis_rights  ='".$publisher."' ";
	$sql .= " where dis_codigo = '".$cod."' ";
	$rlt = db_query($sql);

	echo '<BR><B>'.$nome.'</B>';
	echo '<BR><B>'.$nome_asc.'</B>';
	echo '<BR>'.$inst;

	echo '<tr>';
	echo '<td colspan="5" class="rodapetotal">'.$reg_lidos.' ítens</td>';
	echo '</tr>';
	echo '</TABLE>';
	
	fclose($fp);
	return (1);
}//function

function ps($xx)
	{
	$xp = strpos($xx,'<');
	if ($xp > 0) 
		{ $xx = substr($xx,0,$xp); }
	$xx = troca($xx,"'","´");
	return($xx);
	}

function retorna_tag($_linha){
	$size=strlen($_linha);
	$inicio=false;
	$fim=false;
	$ignorar=false;
	$tag='';

	for($i=0; $i<$size; $i++){
	   if ($_linha[$i]=='<'){
	      $inicio=true;
	   }
   	   else if ($_linha[$i]=='>'){
	      $fim=true;
	   }
	   else if ($_linha[$i] == '/' && $inicio == true){
	      $ignorar=true;
	   }
	   
	   if ($ignorar==true){
	      return '';
	   }

	   if ($inicio==true && $fim==false){
	      $tag.=$_linha[$i+1];
	   }		  

	   if ($fim==true){
	      $tag=substr($tag, 0, -1); //remove o ultimo caracter '>'
		  return $tag;
	   }
	}

}

function retorna_valor($_linha){
   $inicio=false;
   $fim=false;
   $valor='';

   for($i=0;$i< strlen($_linha);$i++){
      if ($_linha[$i]=='>'){
	     $inicio=true;
	  }
	  else if ((strlen($_linha)-1)==$i){
	     $fim=true;
	  }

	  if ($inicio==true && $fim==false){
	     $valor.=$_linha[$i+1];
	  }
	  
	  if ($fim==true){
    	 $valor=substr($valor, 0, -1); //remove o ultimo caracter ''
		 return $valor;
	  }  
   }   
}

function left_zero($valor, $qt_zero){
	$novo_valor='';
	$zeros=0;

	$zeros=$qt_zero-(strlen($valor)); //não contar o caracter \n no final da string
	//echo "zeros = $zeros";
	if ($zeros>0){	  
		for($i=0;$i<$zeros;$i++){
			$novo_valor.="0";
		}
		$novo_valor.=$valor;
		return $novo_valor;
	}
	else{
		return $valor;
	}
}
?>
