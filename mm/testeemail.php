<?
$destinatario = "mjbelli@ufpr.br";
$assunto = "Esta mensagem é um teste";
$corpo = '
<html>
<head>
   <title>Teste de correio</title>
</head>
<body>
<h1>Olá amigos!</h1>
<p>
<b>Bem-vindos ao meu correio electrónico de teste</b>. Estou contente de ter tantos leitores.
</p>
</body>
</html>
'; 

//para o envio em formato HTML
$headers = "MIME-Version: 1.0 ";
$headers .= "Content-type: text/html; charset=iso-8859-1";

//endereço do remitente
$headers .= "From: Mauro <maurojb@uol.com.br>";

//endereço de resposta, se queremos que seja diferente a do remitente
$headers .= "Reply-To: mjbelli@ufpr.br";

//endereços que receberão uma copia $headers .= "Cc: manel@desarrollowe
//endereços que receberão uma copia oculta
$headers .= "Bcc: maurojb@uol.com.br";
//mail($destinatario,$assunto,$corpo,$headers) 
if (mail($destinatario,$assunto,$corpo,$headers)) {
echo "Email enviado com sucesso  Novamente!";

if (mail('rene@sisdoc.com.br',$assunto,$corpo,$headers)) {
echo "Email enviado com sucesso  Novamente!";

} else {
echo "Ocorreu um erro durante o envio do email.";
}

?> 