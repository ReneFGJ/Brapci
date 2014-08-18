<?php
class tesauro
	{
		var $tabela = 'tesauro_editorial';
		function cp()
			{
				global $dd;
				if ((strlen($dd[30]) > 0) and (strlen($dd[2]) == 0))
					{
						$dd[2] = $dd[30]; 
						$dd[3] = $dd[30];
					}
				
				$cp = array();
				array_push($cp,array('$H8','id_ts','',False,True));
				array_push($cp,array('$H8','ts_codigo','',False,True));
				array_push($cp,array('$S40','ts_termo','Termo',True,True));
				array_push($cp,array('$S50','ts_termo_autorizado','Autorizado',True,True));
				array_push($cp,array('$O 1:SIM&0:NÃO','ts_ativo','Ativo',True,True));
				array_push($cp,array('$HV','ts_composto','1',False,True));
				array_push($cp,array('$C1','ts_italico','Itálico',False,True));
				array_push($cp,array('$U8','ts_update','',False,True));
				$dd[8] = trim(LowerCase($dd[2]));
				array_push($cp,array('$HV','ts_termo_asc',$dd[8],True,True));
				return($cp);
			}
			
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_ts','ts_codigo','ts_termo','ts_termo_autorizado','ts_termo_asc','ts_composto','ts_italico');
				$cdm = array('cod',msg('codigo'),msg('nome'),msg('autorizado'));
				$masc = array('','','','','','');
				return(1);				
			}
						
		function gera_arquivo_bibliioteca()
			{
				$sss = fopen('../messages/_tesauro_editorial.php','w');
				
				/* Substituição Automática */
				$sql = "select * from ".$this->tabela." 
					where ts_composto <> '1' and ts_italico <> '1'
					order by ts_termo_asc ";
				$rlt = db_query($sql);
				fwrite($sss,'<?php'.chr(13).chr(10));
				fwrite($sss,'$temo = array('.chr(13).chr(10));
				$it = 0;
				while ($line = db_read($rlt))
				{
					if ($it > 0) { fwrite($sss,', '.chr(13).chr(10)); }
					fwrite($sss,"'".trim($line['ts_termo_asc'])."'=>'".trim($line['ts_termo_autorizado'])."'");
					$it++;	
				}
				fwrite($sss,');'.chr(13).chr(10));
				
				/* Substituição de termo composto */
				$sql = "select * from ".$this->tabela." 
					where ts_composto = '1' and ts_italico <> '1'
					order by ts_termo_asc ";
				$rlt = db_query($sql);
				
				$s1 = '$termo = array('.chr(13).chr(10);
				$s2 = '$termoi = array('.chr(13).chr(10);
				$it = 0;
				while ($line = db_read($rlt))
				{
					if ($it > 0) { $s1 .= ', '.chr(13).chr(10); }
					if ($it > 0) { $s2 .= ', '.chr(13).chr(10); }
					
					$s1 .= "'".LowerCase(trim($line['ts_termo_asc']))."'";
					$s2 .= "'".trim($line['ts_termo_autorizado'])."'";
					$it++;	
				}
				$s1 .= ');'.chr(13).chr(10);
				$s2 .= ');'.chr(13).chr(10);
				
				/* Substituição de termo italico */
				$sql = "select * from ".$this->tabela." 
					where ts_italico = '1'
					order by ts_termo_asc ";
				$rlt = db_query($sql);
				
				$s1 .= '$termi = array('.chr(13).chr(10);
				$s2 .= '$termii = array('.chr(13).chr(10);
				$it = 0;
				while ($line = db_read($rlt))
				{
					if ($it > 0) { $s1 .= ', '.chr(13).chr(10); }
					if ($it > 0) { $s2 .= ', '.chr(13).chr(10); }
					
					$s1 .= "'".LowerCase(trim($line['ts_termo_asc']))."'";
					$s2 .= "'".trim($line['ts_termo_autorizado'])."'";
					$it++;	
				}
				$s1 .= ');'.chr(13).chr(10);
				$s2 .= ');'.chr(13).chr(10);				
								
				fwrite($sss,$s1.$s2);


				
				fwrite($sss,'?>'.chr(13).chr(10));
				fclose($sss);
				echo 'Gerado '.$it.' termos';
			}
		function padroniza_titulo($tit,$tp)
			{
				//$this->structure();
				$this->gera_arquivo_bibliioteca();
				
				$tit = trim($tit); 
				$tit = lowercase($tit);
				$tit = uppercase(substr($tit,0,1)).substr($tit,1,strlen($tit));
				$tit .= ' ';
				$wd = array();
				$ws = '';
				$sp = 0;
				for ($rq=0;$rq < strlen($tit);$rq++)
					{
						$ch = substr($tit,$rq,1);
						if (($ch == ' ') or ($ch == ','))
							{
								if (strlen($ws) > 0) { array_push($wd,$ws); }
								if ($ch == ',') { array_push($wd,','); }
								$ws = '';
							} else {
								$ws .= $ch;
							}
					}
				$tit = '';
				$titl = '';
				require("../messages/_tesauro_editorial.php");
				for ($rq=0;$rq < count($wd);$rq++)
					{
						$tem = $wd[$rq];
						$tem1 = trim($temo[UpperCaseSql($tem)]);
						//echo '<BR>'.$tem.'=='.$tem1;
						if (strlen($tem1) > 0)
							{ $tem = $tem1; }
						$tit .= $tem.' ';
						$titl .= '<A HREF="#" onclick="newwin2(\'tesauro_pop_ed.php?dd30='.trim($wd[$rq]).'\',500,600);">';
						$titl .= $wd[$rq].'</A> ';
						
					}
				echo '<BR>';
				$tit = trim($tit);
				echo $titl.'<BR>';

				/* TROCA TERMOS COMPOSTOS */
				for ($rq=0;$rq < count($termo);$rq++)
					{
						$t1 = trim($termo[$rq]);
						$t2 = trim($termoi[$rq]);
						//echo '<BR>==>'.$t1;
						//echo '<BR>==>'.$tit;
						//echo '<BR>-->'.strpos($tit,$t1);
						$tit = troca($tit,$t1,$t2);
					}
					
				/* TROCA TERMOS ITALICOS */
				for ($rq=0;$rq < count($termo);$rq++)
					{
						$t1 = trim($termi[$rq]);
						$t2 = '<I>'.trim($termii[$rq]).'</I>';
						$tit = troca($tit,$t1,$t2);
					}					
				$tit = troca($tit,' ,',',');
				echo '<HR>'.$tit.'<BR>';
				return($tit);
			}
		
		function structure()
			{
				$sql = "DROP TABLE tesauro_editorial";
				$rlt = db_query($sql);
				
				$sql = "
					CREATE TABLE tesauro_editorial
						(
							id_ts serial NOT NULL,
							ts_codigo char(7),
							ts_termo char(40),
							ts_termo_asc char(40),
							ts_termo_autorizado char(50),
							ts_ativo char(1),
							ts_composto char(1),
							ts_italico char(1),
							ts_update integer
						);
					";
				$rlt = db_query($sql);
				
			}
		function updatex()
			{
				global $base;
				$c = 'ts';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}
	}
?>
