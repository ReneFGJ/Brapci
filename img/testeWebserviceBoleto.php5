<?php

/***************************************PARAMETROS*************************************************/
$cod_tipo_titulo=375;
$condicaorec=300;
$NomePessoa="Rodolfo";
$venc = '12/06/2013';
$Valor= "1";
$mensagemboletoCima="Teste mensagemboletoCimamensagemboletoCima mensagemboletoCima mensagemboletoCima
                     mensagemboletoCima <br>
                     Pagamento asdads
                     ";
$mensagemboletoBaixo=" mensagemboletoBaixo mensagemboletoBaixo mensagemboletoBaixo mensagemboletoBaixo 
                        Teste
                        Pagamento asdads";
/****************************************************************************************************/





$function = "BuscarTituloSemContrato";
$endpoint = "http://jbosshom.pucpr.br:8281/services/manterTitulos?wsdl";
$client = new SoapClient($endpoint);
$arguments= array('empresa'=>1,
                  'pessoasemcadastro'=> $NomePessoa,
                  'cod_tipo_titulo'=>$cod_tipo_titulo,
                  'condicaorec'=>$condicaorec,
                  'data_vencimento'=>$venc,
                  'data_limite_pgto'=>$venc,
                  'numparcelas_par'=>1,
                  'valor'=>$Valor,
                  'mensagemboleto'=>$mensagemboletoBaixo,
                  'flag'=>1
                );
 
try{
   $result = $client->__soapCall($function, array($arguments));
}catch(SoapFault $e){
   header('Content-Type: text/html; charset=utf-8'); 
   echo "<script>alert('Erro ao gerar número título!')</script>"; 
   die;
}


$arrayTemp = (array) $result; 
$array = (array)$arrayTemp["NumeroTitulo"];
$erro = $array["erro"];

if(isset($msg)){
   header('Content-Type: text/html; charset=utf-8');
   die($msg);
}else{
   $numTitulo = $array["num_titulo"];
   $data_vencimento = substr($array["data_vencimento"],3,2)."/".substr($array["data_vencimento"],0,2)."/".substr($array["data_vencimento"],6,4);
   $IPImpressao = getenv("REMOTE_ADDR"); 
}



$function = "gerarBoleto";
$endpoint = "https://jbosshom.pucpr.br:9446/services/gerarBoleto?wsdl";

$client = new SoapClient($endpoint);

$arguments= array(
                        'codEmpresa'   => 1,
                        'numTitulo'      => $numTitulo,
                        'pessoaLogada'      => '89107868',
                        'dataVencimento'      => $data_vencimento,
                        'mensagemBoleto'      => $mensagemboletoCima,
                        'ipImpressao'        => $IPImpressao
                );
 
try{
   $result = $client->__soapCall($function, array($arguments));
}catch(SoapFault $e){
   header('Content-Type: text/html; charset=utf-8'); 
   echo "<script>alert('Erro ao efetuar impressão do boleto!')</script>"; 
   die;
}
$arrayTemp = (array) $result; 
$array = (array)$arrayTemp["return"];
$mensagemErroGeracaoBoleto = $array["mensagemErroGeracaoBoleto"];

if(isset($mensagemErroGeracaoBoleto)){
   header('Content-Type: text/html; charset=utf-8');
   die($mensagemErroGeracaoBoleto);
}else{
   $boletoPDF = $array["boleto"];

   header('Content-Type: application/pdf');
   header('Content-Length: '.strlen($boletoPDF));
   header('Content-Disposition: inline;');
   header('Cache-Control: private, max-age=0, must-revalidate');
   header('Pragma: public');
   print $boletoPDF;
}
?> 