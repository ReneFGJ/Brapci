<?
class user
	{
		var $user;
		var $cracha;
		var $nome;
		var $id;
		
		var $erro;
		var $erro_msg;
		
		function loged()
			{
				$this->nome = $_SESSION['nome'];
				$this->cracha = $_SESSION['codigo'];
				$this->user = $_SESSION['id'];
				$this->id = $_SESSION['id'];
				if (strlen($this->nome)==0) { return(0); }
				return(1);
			}
			
		function logout()
			{
				$_SESSION['nome'] = '';
				$_SESSION['codigo'] = '';
				$_SESSION['id'] = '';
				return(1);
			}
		
		function login($codigo,$logon,$senha)
			{
					//echo '<BR>codigo:'.$codigo;
					//echo '<BR>logon:'.$logon;
					//echo '<BR>senha:'.$senha;
				$this->erro = '';
				/** Campos em branco */
				if ((strlen($codigo)==0) or (strlen($logon)==0) or (strlen($senha)==0))
					{ $this->erro = '01'; return(0); }
					
				/* VALIDA DADOS DO CRACHA */
				$pcra = strzero(round($codigo),8); // código do cracha somente em numeros
				if (strlen($pcra) == 12)
					{ $pcra = substr($pcra,3,8); }
				/* TAMANHO IGUAL A OITO */
				
				if (strlen($pcra) != 8)
					{ $this->erro = '02'; return(0); }
				
				//////////////////// Autentica pela PUCPR
				require("pucpr_soap_autenticarUsuario.php");
				$result = 'Autenticado';
				if ($result == 'Autenticado') { $autentica = 1; }
				/** Usuário não autenticado */
				if ($autentica != 1)
					{ $this->erro = '03'; return(0); }
				
				
				/* Busca professor no quadro de professores */
				$sql= "select * from pibic_professor ";
				$sql .= " where pp_cracha = '".$pcra."' ";
				$sql .= " limit 1 ";
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
				{
					$pcra = trim($line['pp_cracha']);
					$nome = trim($line['pp_nome']);
					$id = $line['id_pp'];
				} else {
					echo 'Professor não localizado';
				}
				$_SESSION['nome'] = $nome;
				$_SESSION['codigo'] = $pcra;
				$_SESSION['id'] = $id;
				$_SESSION['user'] = $id;

				$this->user = $line['id_pp'];
				$this->id = $line['id_pp'];
				$this->cracha = $pcra;
				$this->nome = $nome;

				redirecina("main.php");
				return(1);
			}
	}
?>