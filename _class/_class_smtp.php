<?
/**
 * http://scriptbrasil.com.br/forum/index.php?showtopic=125936
 */
class smtp
{
var $conn;
var $usuario_smtp;
var $senha_smtp;
var $debug;

	function smtp($servidor_smtp)
		{
	    $this->conn = fsockopen($servidor_smtp, 25, $errno, $errstr, 30);
	    $this->AdicionaDadosSMTP("EHLO $servidor_smtp");
	    }
	function Autentica()
		{
	    $this->AdicionaDadosSMTP("AUTH LOGIN");
	    $this->AdicionaDadosSMTP(base64_encode($this->user));
	    $this->AdicionaDadosSMTP(base64_encode($this->pass));
		}

	function Send($para, $de, $assunto, $mensagem){
	    $this->Autentica();
	    $this->AdicionaDadosSMTP("MAIL FROM: " . $de);
	    $this->AdicionaDadosSMTP("RCPT TO: " . $para);
	    $this->AdicionaDadosSMTP("DATA");
	    $this->AdicionaDadosSMTP($this->CabecTO($para, $de, $assunto));
	    $this->AdicionaDadosSMTP("\r\n");
	    $this->AdicionaDadosSMTP($mensagem);
	    $this->AdicionaDadosSMTP(".");
	    $this->Close();
	    if(isset($this->conn)){
	    return true;
	    } else {
	        return false;
	    }
	}
	
	function AdicionaDadosSMTP($valor)
		{
	    return fputs($this->conn, $valor . "\r\n");
		}
	
	function CabecTO($para, $de, $assunto)
		{
	    $header = "Message-Id: <". date('YmdHis').".". md5(microtime()).".". strtoupper($de) ."> \r\n";
	    $header .= "From: <" . $de . "> \r\n";
	    $header .= "To: <".$para."> \r\n";
	    $header .= "Subject: ".$assunto." \r\n";
	    $header .= "Date: ". date('D, d M Y H:i:s O') ." \r\n";
	    $header .= "X-MSMail-Priority: High \r\n";
	    $header .= "Content-Type: Text/HTML";
	    return $header;
		}
	
	function Close()
		{
	    $this->AdicionaDadosSMTP("QUIT");
	    if($this->debug == true){
	        while (!feof ($this->conn)) {
	            fgets($this->conn) . "<br>\n";
	        }
	    }
	    return fclose($this->conn);
	}
}