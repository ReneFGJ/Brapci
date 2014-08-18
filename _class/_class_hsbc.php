<?php
class hsbc
	{
	function gerar_arquivo($vc,$compl='')
		{
			$sql = "select * from pibic_pagamentos
					inner join pibic_aluno on pa_cpf = pg_cpf 
					where pg_vencimento = $vc and pg_complemento = '$compl' 
					order by pg_nrdoc ";
			$rlt = db_query($sql);
			
			$sa = $this->header_rq();
			$sa .= $this->header_rq2();
			$ln = 0;
			$total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0;
			while ($line = db_read($rlt))
			{
				$sa .= $this->req_pagamento_arquivo($line);
			}
			$sa .= $this->req_fim();
			return($sa);			
		}
	function req_pagamento_arquivo($line)
		{
			global $ln,$pg,$total,$total1,$total2,$total3,$total4;
			
			$cpf = $this->validaCPF(trim($line['pa_cpf']));
			$conta  = $this->checadv($line['pa_cc_agencia'],$line['pa_cc_conta'],$line['pa_cc_banco'],$line['pa_cc_mod']);
			echo '---.'.$line['pa_cc_banco'];
			if (!($conta == 'ok') or ($cpf==0))
			{
				echo '<br><TT>'.trim($line['pa_cpf']);
				echo ' '.$line['pa_nome'];
				echo ' ';
				echo ' CPF('.$cpf.') - Conta ('.$conta.')';	
				$total1++;
				$total2 = $total2 + $line['pbt_auxilio'];	
				return('');	
			}
			$data = $line['pg_vencimento'];
			$data_nr = substr($data,6,2).substr($data,4,2).substr($data,0,4);
			if (empty($ln)) { $ln=1; }
			
			$valor = strzero($line['pg_valor']*100,13);
			$total = $total + $line['pg_valor'];
			
			$nome = Substr(UpperCaseSQL(trim($line['pa_nome'])),0,30);
			while (strlen($nome) < 30) { $nome .= ' '; }
			$sx = '399';
			$sx .= strzero($ln+1,4);
			//$sx .= '0002';
			$sx .= '3';
			$sx .= strzero($ln,5);
			//$sx .= '00001';
			$sx .= 'A';
			$sx .= '000';
			$sx .= '   ';
			$sx .= '399';
			//$sx .= '01748';
			$sx .= strzero(substr($line['pa_cc_agencia'],0,4),5);
			$sx .= ' ';
			$sx .= '0';
			$sx .= strzero(substr($line['pa_cc_agencia'],0,4),5);
			//$sx .= '0';
			
			//$sx .= '111450';
			$sx .= strzero($line['pa_cc_conta'],7);
			$sx .= ' ';
			$sx .= $nome;
			       
			$vvv = trim($line['pg_nrdoc']);
			$vvv .= strzero($line['pg_complemento'],2);
			//$sx .= '1       ';
			while (strlen($vvv) < (10+10))
				{ $vvv .= ' '; }
			$sx .= $vvv; /* Nr. DOC */
			//$sx .= '12082011';
			$sx .= $data_nr;
			$sx .= 'R$';
			$sx .= '                  ';
			//$sx .= '0000000036000';
			$sx .= $valor;
			$sx .= 'N';
			$sx .= '                                                                                              ';
			$sx .= '0';
			$sx .= '          ';
			$sx .= chr(13).chr(10);
			
			$ln++; 
			/* LN 2 */
			$sx .= '399';
			$sx .= strzero($ln+1,4);
			$sx .= '3';
			//$sx .= '00002';
			$sx .= strzero($ln,5);
			$sx .= 'B';
			$sx .= '   ';
			$sx .= '1000';
			$sx .= strzero($line['pa_cpf'],11);
			//$sx .= '06196555936'; /* cof */
			$sx .= '                                                                                                                                                                                                                ';
			$sx .= chr(13).chr(10);
			$ln++;
			return($sx);
		}


	function header_rq()
		{
			$sx  = '399';
			$sx .= '00000';
			$sx .= '         ';
			$sx .= '27665982';
			$sx .= '00001510';
			// $sx .= '51462'; Trocado convenio de Salário para Outros
			 
			// Solicitidado pelo Fernando em 17/07/2013
			// $sx .= '90565';
			
			// Solicitidado pelo Fernando em 16/06/2014
			$sx .= '51462'; 
			$sx .= '              ';
			$sx .= '00000';
			$sx .= ' ';
			$sx .= '0000000000000';
			$sx .= ' ';
			$sx .= 'APC PIBIC                     ';
			$sx .= 'HSBC BANK BRASIL S.A.                   ';
			$sx .= '1';
			//$sx .= '02082011';
			$sx .= date("dmY");
			//$sx .= '1446520';
			$sx .= date("His");
			$sx .= '00000002001600';
			$sx .= 'CPGY2K SFW 10.10                                                     ';
			$sx .= chr(13).chr(10);
			return($sx);
		}
	function header_rq2()
		{
			$sx = '399';
			$sx .= '00011C3001020';
			$sx .= ' ';
			$sx .= '2766598200001510';
			//$sx .= '51462';
			$sx .= '90565';
			$sx .= '              ';
			$sx .= '00000';
			$sx .= ' ';
			$sx .= '0000000000000';
			$sx .= ' ';
			$sx .= 'APC PIBIC                                                                                                                                   ';
			$sx .= '00000000';
			$sx .= '                    ';
			$sx .= chr(13).chr(10);                    
			return($sx);
		}
	function req_fim()
		{
			global $ln,$pg,$total;
			$sx = '39906465         ';
			$sx .= '0';
			//$sx .= '00646';
			$sx .= strzero($ln,5);
			$sx .= '   ';
			//$sx .= '000000012880000';
			$sx .= strzero($total*100,15);
			$sx .= '                                                                                                                                                                                                       '.chr(13).chr(10);
			
			$ln++;
			$sx .= '39999999         ';
			$sx .= '000001';
			$sx .= '0';
			$sx .= strzero($ln+1,5);
			//$sx .= '00648';
			$sx .= '                                                                                                                                                                                                                   '.chr(13).chr(10);
			return($sx);			
		}
	function req_pagamento($line,$data=20130305)
		{
			global $ln,$pg,$total,$total1,$total2,$total3,$total4;
			//$sql = "alter table pibic_aluno add column pa_cc_mod char(3)";
			//$rlt = db_query($sql);
			
			$banco = trim($line['pa_cc_banco']);
			$cpf = $this->validaCPF(trim($line['pa_cpf']));
			$mod = trim($line['pa_cc_mod']);
			$conta  = $this->checadv($line['pa_cc_agencia'],$line['pa_cc_conta'],$banco,$mod);
			$agencia = trim($line['pa_cc_agencia']);
			
			if (strlen($banco) == 0) { echo '<BR>Banco inválido'; return(''); }
			if (strlen($agencia) == 0) { echo '<BR>Agência inválida'; return(''); }

			if (!($conta == 'ok') or ($cpf==0))
			{
				echo '<TT>'.sonumero($line['pa_cpf']);
				echo ' '.$line['pa_nome'];
				echo ' ';
				echo ' CPF('.$cpf.') - Banco:'.$banco.' Conta ('.$conta.')';	
				echo '<BR>';	
				$total1++;
				$total2 = $total2 + $line['pbt_auxilio'];	
				return('');	
			}
			
			$data_nr = substr($data,6,2).substr($data,4,2).substr($data,0,4);
			if (empty($ln)) { $ln=1; }
			
			$valor = strzero($line['pbt_auxilio']*100,13);
			$total = $total + $line['pbt_auxilio'];
			
			$nome = Substr(UpperCaseSQL(trim($line['pa_nome'])),0,30);
			while (strlen($nome) < 30) { $nome .= ' '; }
			$sx = '399';
			$sx .= strzero($ln+1,4);
			//$sx .= '0002';
			$sx .= '3';
			$sx .= strzero($ln,5);
			//$sx .= '00001';
			$sx .= 'A';
			$sx .= '000';
			if ($banco == '399')
				{
					$sx .= '   ';
				} else {
					$sx .= '700';
				}
			
			switch ($banco)
				{
				case '399':
					$cc = strz($line['pa_cc_banco'],3);;
					//$sx .= '01748';
					$cc .= strz($line['pa_cc_agencia'],5);
					$cc .= ' ';
					$cc .= '0';
					$ncc = strz($line['pa_cc_agencia'],5);
					$nca = strz($line['pa_cc_conta'],7);
					
					if ($nca == '0000000') { $ncc = '00000'; }
					$cc .= $ncc;
					$cc .= $nca;
					//$cc .= strz($line['pa_cc_conta'],7);
					$cc .= ' ';
					break;					
				default:
					$cc .= strz($line['pa_cc_banco'],3);;
					//$sx .= '01748';
					$cc .= strz(substr(trim($line['pa_cc_agencia']),0,4),5);
					$cc .= ' ';
					$cc .= '0';
					//$cc .= strz(substr($line['pa_cc_agencia'],0,4),5);
					if ($banco == '104')
						{
							echo '<BR>-->'.$line['pa_cc_mod'].'-'.strz($line['pa_cc_conta'],9);
							$cc .= strz($line['pa_cc_mod'],3);
							$cc .= strz($line['pa_cc_conta'],9);
						} else {
							$cc .= strz($line['pa_cc_conta'],12);		
						}
					
					$cc .= ' ';					
					break;
				}
			if (strlen($cc) > 23)
				{
					echo '<BR>Erro nos dados da conta '.$line['pa_nome'];
					echo '<BR><TT>'.$cc.'</TT>-'.strlen($cc);
					return('');					
				}
			$sx .= $cc;
			$sx .= $nome;
			       
			$sx .= strzero($line['id_pbt'],2);
			$vvv = substr($data_nr,2,2).substr($data_nr,6,2);
			//$vvv .= date("Hi");
			$vvv .= '0'.trim($line['pa_cc_banco']);
			//$sx .= '1       ';
			$sx .= $vvv.strzero($ln,4); /* Nr. DOC */
			$sx .= '      ';
			//$sx .= '12082011';
			$sx .= $data_nr;
			$sx .= 'R$';
			$sx .= '                  ';
			//$sx .= '0000000036000';
			$sx .= $valor;
			$sx .= 'N';
			$sx .= '                                                                                              ';
			$sx .= '0';
			$sx .= '          ';
			$sx .= chr(13).chr(10);
			
			$ln++; 
			/* LN 2 */
			$sx .= '399';
			$sx .= strzero($ln+1,4);
			$sx .= '3';
			$sx .= strzero($ln,5);
			$sx .= 'B';
			$sx .= '   ';
			$sx .= '1000';
			$sx .= strzero(sonumero($line['pa_cpf']),11);
			//$sx .= '06196555936'; /* cof */
			$sx .= '                                                                                                                                                                                                                ';
			$sx .= chr(13).chr(10);
			$ln++;
			return($sx);
		}
	function checadv($ag='',$cc='',$banco='',$ccmod='')
		{
			$banco = trim($banco);
//			if ($banco == '104')
//			{ echo '<BR>===3>'.$banco.'-'.$ag.'-'.$cc.'=========[]'.$ccmod.'[]'; }
			if (strlen($banco)!=3)	{ return('<font color="red">ERRO BANCO '.$banco.'</font>'); exit; }
			if ($ccc=='0000000')	{ return('<font color="red">ERRO CC</font>'); exit; }
			if ($ccr=='00000')	{ return('<font color="red">ERRO AG</font>'); exit; }

			//if ($cc == 'PAGAMENTO') { $cc = '0000000'; }
			//if ($ag == 'ORDEM') { $cc = '00000'; }

			$ccx = trim($cc);
			$agx = trim($ag);
			$ag = sonumero($ag);
			$cc = sonumero($cc);
			$bc = sonumero($banco);
			$mod = $ccmod;
			//if ((strlen($ag)==0) or (strlen($cc)==0))
			//	{ return('<font color="red">ERRO(NI)</font>'); }
			switch ($banco)
				{
				case '001': $sx = $this->banco_bb($agx,$ccx); break; /* BB */
				case '033': $sx = $this->banco_santander($ag,$cc); break; /* SANTANDER */ 
				//case '041': $sx = $this->banco_barinsul($ag,$cc); break; /* BARINSUL */
				case '104': $sx = $this->banco_caixa($ag,$cc,$mod); break; /* CAIXA ECONOMICA */
				case '237': $sx = $this->banco_bradesco($ag,$cc); break; /* BRADESCO */ 
				case '341': $sx = $this->banco_itau($ag,$cc); break; /* ITAU */
				//case '356': $sx = $this->banco_real($ag,$cc); break; /* REAL */
				case '399': $sx = $this->banco_hsbc($ag,$cc); break; /* HSBC */
				case '748': $sx = 'ok'; break; /* HSBC */
				//case '745': $sx = $this->banco_city($ag,$cc); break; /* CITYBANK */
				default: $sx = '<font color="green">BANCO??</font>'; break;
				 
				}
			return($sx);		
			
		}

	function banco_bradesco($ag,$cc)
		{
			$cc = trim($cc); $ag = trim($ag);
			$cccc = trim(troca($cc,'-',''));
			$ccag = trim(troca($ag,'-',''));
			$cca = substr($cccc,0,strlen($cccc)-1);
			$ccr = substr($ccag,0,strlen($ccag)-1);
			if ($ccc != '0000000')
				{
					$dva = $this->DVMOD13($ccr);
					$dvc = substr($ccag,strlen($ccag)-1,1);
				} else {
					if ($ccr == '00000') { return('<font color="red">SEM ID AG.(BB)</font>'); }					
					return("ok");
				}

			if ($dva == $dvc)
				{
					while (strlen($cca) < 8) { $cca = '0'.$cca; }
					$dva = $this->DVMOD11($cca);
					$dvc = substr($cc,strlen($cc)-1,1);
					if ($dva == $dvc)
					{
						return("ok");
					} else {
						$this->calc .= '<BR>'.$dva.'--DV:'.$dvc;
						$this->calc .= '<BR>'.$cccc;
						$this->calc = '';
						return('<font color="red">ERRO CC (BB)</font>'.$this->calc);
					} 
				}
			else 
				{
					$this->calc .= '<BR>'.$dva.'--'.$dvc;
					$this->calc .= '<BR>'.$cccc;
					$this->calc = '';
					return('<font color="red">ERRO AG.(BB)'.$this->calc.'</font>'); 
				}
		}
	function banco_caixa($ag,$cc,$mod='')
		{
			//echo '==>'.$mod;
			if (strlen($mod)==0) { return('<font color="red">MOD.</font>'); }
			$ccc = strzero(sonumero($cc),9);
			$ccr = strzero($ag,4);
			$cca = $ccr.$mod.substr($ccc,0,8);
			
			if ($ccc != '000000')
				{
					//echo '---{'.$cca.'}';
					$dv = $this->DV44($cca);
					$dvc = substr($cc,strlen($cc)-1,1);
					if ($dv != $dvc)
						{
							$this->calc = '';
							return('<font color="red">ERR AG./CC.'.$this->calc.'</font>');
						} else {
							return('ok');
						}
				} else {
					if ($ccr == '00000') { return('<font color="red">SEM AG.</font>'); }					
					return("ok");
				}
		}
	function banco_santander($ag,$cc)
		{
			$ccc = strzero($cc,9);
			$ccr = strzero($ag,4);
			$cca = $ccr.'00'.substr($ccc,0,8);
			
			if ($ccc != '000000')
				{
					$dv = $this->DV33($cca);
					$dvc = substr($cc,strlen($cc)-1,1);
					if ($dv != $dvc)
						{
							$this->calc = '';
							return('<font color="red">ERR AG./CC.'.$this->calc.'</font>');
						} else {
							return('ok');
						}
				} else {
					if ($ccr == '00000') { return('<font color="red">SEM AG.</font>'); }					
					return("ok");
				}
		}
	function banco_bb($ag,$cc)
		{
			$cc = trim($cc); $ag = trim($ag);
			$cccc = trim(troca($cc,'-',''));
			$ccag = trim(troca($ag,'-',''));
			$cca = substr($cccc,0,strlen($cccc)-1);
			$ccr = substr($ccag,0,strlen($ccag)-1);
			if ($ccc != '0000000')
				{
					$dva = $this->DVMOD11($ccr);
					$dvc = substr($ccag,strlen($ccag)-1,1);
				} else {
					if ($ccr == '00000') { return('<font color="red">SEM ID AG.(BB)</font>'); }					
					return("ok");
				}

			if ($dva == $dvc)
				{
					while (strlen($cca) < 8) { $cca = '0'.$cca; }
					$dva = $this->DVMOD11($cca);
					$dvc = substr($cc,strlen($cc)-1,1);
					if ($dva == $dvc)
					{
						return("ok");
					} else {
						$this->calc .= '<BR>'.$dva.'--DV:'.$dvc;
						$this->calc .= '<BR>'.$cccc;
						$this->calc = '';
						return('<font color="red">ERRO CC (BB)</font>'.$this->calc);
					} 
				}
			else 
				{
					$this->calc .= '<BR>'.$dva.'--'.$dvc;
					$this->calc .= '<BR>'.$cccc;
					$this->calc = '';
					return('<font color="red">ERRO AG.(BB)'.$this->calc.'</font>'); 
				}
		}		
		
	function banco_hsbc($ag,$cc)
		{
			$ccc = strzero($cc,7);
			$ccr = strzero($ag,5);
			$cca = substr($ccc,0,6);
			if ($ccc != '0000000')
				{
					$dv = $this->DV11($ccr.$cca);
					$dvc = substr($cc,strlen($cc)-1,1);
				} else {
					if ($ccr == '00000') { return('<font color="red">SEM AG.</font>'); }					
					return("ok");
				}

			if ($dv == $dvc)
				{ return("ok"); }
			else 
				{ return('<font color="red">ERRO DV</font>'); }
		}
	function banco_itau($ag,$cc)
		{
			$ccc = strzero($cc,6);
			$ccr = strzero($ag,4);
			$this->calc = '';
			if ($ccc != '0000000')
				{
					$dv = $this->DV22($ccr.substr($ccc,0,5));
					$dvc = substr($ccc,strlen($ccc)-1,1);
				} else {
					if ($ccr == '00000') { return('<font color="red">SEM AG.</font>'); }					
					return("ok");
				}

			if ($dv == $dvc)
				{ return("ok"); }
			else 
				{
					$this->calc = '';
					return('<font color="red">ERRO DV'.$this->calc.'</font>'); 
				}
		}

	function DV44($nr)
		{
			$va = array(8,7,6,5,4,3,2,9,8,7,6,5,4,3,2);			
			$tot = 0;
			$this->calc = '';
			for ($r=0;$r < strlen($nr);$r++)
				{
					$nrn = $va[$r];
					$nrv = round(substr($nr,$r,1));
					$tot = $tot + ($nrv * $nrn);
					$this->calc .= '<BR>'.$nrv.'x'.$nrn.'='.$tot;
				}
			$tot = $tot * 10;
			$tott = $tot;
			$mult = 0;
			
			while ($tot >= 11) { $tot = $tot - 11; $mult++; }
			$tot = $tott - $mult * 11;
			if ($tot == 10) { $tot = 0; }
			return($tot);
		}
	function DV33($nr)
		{
			$va = array(9,7,3,1,0,0,9,7,1,3,1,9,7,3);			
			$tot = 0;
			$this->calc = '';
			for ($r=0;$r < strlen($nr);$r++)
				{
					$nrn = $va[$r];
					$nrv = round(substr($nr,$r,1));
					$tot = $tot + $this->somarvs($nrv * $nrn);
					$this->calc .= '<BR>'.$nrv.'x'.$nrn.'='.$tot;
				}
			while ($tot > 10) { $tot = $tot - 10; }
			$tot = 10 - $tot;
			return($tot);
		}
	function DV22($nr)
		{
			$nrn = 2;
			$tot = 0;
			for ($r=0;$r < strlen($nr);$r++)
				{
					$nrv = round(substr($nr,$r,1));
					$tot = $tot + $this->somarvl($nrv * $nrn);
					$this->calc .= '<BR>'.$nrv.'x'.$nrn.'='.$tot;
					if ($nrn == 2) { $nrn = 1; } else { $nrn = 2; }
				}
			while ($tot > 10) { $tot = $tot - 10; }
			$tot = 10 - $tot;
			return($tot);
		}

	function somarvs($v)
		{
			while ($v > 10) {$v = $v - 10; }
			return($v);
		}

	function somarvl($v)
		{
			$tot = 0;
			for ($r=0;$r < strlen($v);$r++)
				{
					$vv = substr($v,$r,1);
					$tot = $tot + round($vv);
				}
			return($tot);
		}
		
		
	function DVMOD11($nr)
		{
			$nrn = strlen($nr)+1;
			$tot = 0;
			$sa = '';
			$calc = '';
			for ($r=0;$r < strlen($nr);$r++)
				{
					$nrv = round(substr($nr,$r,1));
					$tot = $tot + ($nrv * $nrn);
					$calc .= '<BR>'.$nrv.'x'.$nrn.'='.$tot;
					$nrn--;
					if ($nrn == 0) { $nrn = 9; }
				}
			$ttt = $tot;
			while ($tot > 11) { $tot = $tot - 11; }
			$calc .= '<BR>MOD 11='.$tot;
			$tot = 11 - $tot;
			$calc .= '<BR>SUB 11='.$tot;
			$this->calc = $calc;
			if ($tot == 10) { $dv = 'X'; } else { $dv = $tot; }
			return($dv);
		}
	function DVMOD13($nr)
		{
			$nrn = strlen($nr)+1;
			$tot = 0;
			$sa = '';
			$calc = '';
			for ($r=0;$r < strlen($nr);$r++)
				{
					$nrv = round(substr($nr,$r,1));
					$tot = $tot + ($nrv * $nrn);
					$calc .= '<BR>'.$nrv.'x'.$nrn.'='.$tot;
					$nrn--;
					if ($nrn == 0) { $nrn = 9; }
				}
			$ttt = $tot;
			while ($tot > 11) { $tot = $tot - 11; }
			$calc .= '<BR>MOD 11='.$tot;
			$tot = 11 - $tot;
			$calc .= '<BR>SUB 11='.$tot;
			$this->calc = $calc;
			if ($tot == 10) { $dv = '0'; } else { $dv = $tot; }
			return($dv);
		}
	function DV11($nr)
		{
			$nr = sonumero($nr);
			$nrn = 2;
			$tot = 0;
			for ($r=0;$r < strlen($nr);$r++)
				{
					$nrv = round(substr($nr,(strlen($nr)-$r-1),1));
					$tot = $tot + ($nrv * $nrn);
					//echo '<BR>'.$nrv.'x'.$nrn.'='.$tot;
					
					$nrn++;
					if ($nrn > 9) { $nrn = 2; }
				}
			//echo '<BR>'.$tot.'x10='.$tot*10;
			$tot = $tot * 10;
			$totx = 0;
			while ($tot >= 11)
				{
					$totx++;
					$tot = $tot - 11;
				}
			//echo '<BR>'.$tot.'/11='.$totx;
			$tot = $totx;			
			
			while ($tot > 10) { $tot = $tot - 10; }
			//echo '<BR>Resto: '.$tot;
			$dv = 10 - $tot;
			if ($dv == 10) { $dv = 0; }
			return($dv);
		}
	function validaCPF($cpf)
		{	
			/*
			@autor: Moacir Selínger Fernandes
			@email: hassed@hassed.com
			 */
			 // Verifiva se o número digitado contém todos os digitos
			 
    		$cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
		
			// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    		if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999')
			{
				return(0);
    		}
		else
			{   // Calcula os números para verificar se o CPF é verdadeiro
        	for ($t = 9; $t < 11; $t++) 
        		{
	            	for ($d = 0, $c = 0; $c < $t; $c++) 
	            	{ $d += $cpf{$c} * (($t + 1) - $c); }
	
            		$d = ((10 * $d) % 11) % 10;
	
            		if ($cpf{$c} != $d) { return(0); }
        		}	
        	return(1);
    		}
    	}		
	}
function strz($vz,$tm)
	{
		$vz = trim($vz);
		$vz = troca($vz,'-','');
		$vz = troca($vz,'X','0');		
		$vz = troca($vz,'.','');
		while (strlen($vz) < $tm)
			{
				$vz = '0'.$vz;
			}
		return($vz);
	}
?>
