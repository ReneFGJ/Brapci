<?php
class ris extends CI_model
	{
		
	function export_selection()
		{
			$sql = "select ar_codigo from brapci_article where ar_status <> 'X' and ar_journal_id = '0000012' limit 10";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$sx = '';
			for ($y=0;$y < count($rlt);$y++)
				{
					$ln = $rlt[$y];
					$data = $this->articles->le($ln['ar_codigo']);
					$sx .= $this->ris->export_ris($data);
				}
			echo '<pre>'.$sx.'</pre>';				
		}
				
	function export_ris($line)
		{
			$sx = '';
			$sx .= 'TY  - JOUR'.cr();
			$sx .= 'TI  - '.$line['ar_titulo_1'].cr();
			$sx .= 'ST  - '.$line['ar_titulo_2'].cr();
			$sx .= 'T2  - '.$line['jnl_nome'].cr();
			$sx .= 'J2  - '.$line['jnl_nome_abrev'].cr();
			$sx .= 'VL  - '.$line['ed_vol'].cr();
			$sx .= 'IS  - '.$line['ed_nr'].cr();
			$sx .= 'SP  - '.trim($line['ar_pg_inicial']).cr();
			$sx .= 'EP  - '.trim($line['ar_pg_final']).cr();
			$sx .= 'PY  - '.$line['ar_ano'].cr();
			$sx .= 'DO  - '.$line['ar_doi'].cr();
			$sx .= 'SN  - '.substr(troca($line['jnl_issn_impresso'].$line['jnl_issn_eletronico'],'-',''),0,8).' (ISSN) '.cr();
			for ($r=0;$r < count($line['authors']);$r++)
				{
					$ln = $line['authors'][$r]['autor_nome'];
					$sx .= 'AU  - '.$ln.cr();
				}
			//$sx .= 'AD  - Universidad de Extremadura, Facultad de Ciencias de la Documentación y la Comunicación, Departamento de Información y Comunicación, Plazuela de Ibn Marwan, s/n., Badajoz, Spain'.cr();
			//$sx .= 'AD  - Universidad Central del Ecuador, Facultad de Comunicación Social, Carrera Comunicación Social, Quito, Ecuador'.cr();
			//$sx .= 'AB  - '.$line[''].cr();
			for ($r=0;$r < count($line['keywords']);$r++)
				{
					$ln = $line['keywords'][$r]['kw_word'];
					$sx .= 'KW  - '.$ln.cr();
				}
			//$sx = 'PB  - Pontificia Universidade Catolica de Campinas'.cr();
			$sx .= 'N1  - Export Date: '.date("d").' '.date("m").' '.date("Y").cr();
			$sx .= 'M3  - Article'.cr();
			$sx .= 'DB  - Brapci'.cr(); /* ok */
			$sx .= 'LA  - Spanish'.cr();
			//$sx = 'N1  - Correspondence Address: Caldera-Serrano, J.; Universidad de Extremadura, Facultad de Ciencias de la Documentación y la Comunicación, Departamento de Información y Comunicación, Plazuela de Ibn Marwan, s/n., Spain; email: jcalser@alcazaba.unex.es'.cr();
			//$sx = 'N1  - References: (2012) Os Arquivos Audiovisuis em Portugal: Un Diagnóstico, , http://www.bad.pt/publicacoes/diagnostico_patrimonio_audiovisual_nacional.pdf, Associaçao Portuguesa de Bibliotecários, Arquivistas e Documentalistas, Lisboa: Associaçao Portuguesa de Bibliotecários, Arquivistas e Documentalistas, Disponível em:, Acesso em: 16 fev. 2015;'.cr(); 
			//$sx = 'Buchanan, S., Gibb, F., The information audit: An integrated strategic approach (1998) International Journal of Information Management, 18 (1), pp. 29-47; '.cr();
			//$sx = 'Caldera-Serrano, J., La red de información juvenil de la Comunidad Autónoma de Extremadura (1997) Jornadas Andaluzas de Documentación, 1., 1997, pp. 111-117. , Sevilla. Anales... Sevilla: Jadoc; '.cr();
			//$sx = 'Caldera-Serrano, J., Changes in the management of information in audio-visual archives following digitization: Current and future outlook (2008) Journal of Librarianship and Information Science, 40 (1), pp. 13-20;'.cr(); 
			//$sx = 'Caldera-Serrano, J., Arranz-Escacha, P., (2012) Documentación Audiovisual en Televisión, , Barcelona: EPI-UOC;'.cr(); 
			//$sx = 'Caldera-Serrano, J., Zapico-Alonso, F., La fórmula de comunicación de Lasswell como método para implementar bases de datos documentales en los medios audiovisuales (2004) Investigación Bibliotecológica, 18 (37), pp. 110-131;'.cr(); 
			//$sx = 'Edmondson, R., (2004) Filosofía y Principios de los Archivo Audiovisuales, , http://unesdoc.unesco.org/images/0013/001364/136477s.pdf, París: Unesco, Disponible en:, Acceso en: 16 feb. 2015;'.cr(); 
			//$sx = 'González-Ruiz, D., Térmens, M., Ribera, M., Aspectos técnicos de la digitalización de fondos documentales (2012) El Profesional de la Información, 21 (5), pp. 62-70;'.cr(); 
			//$sx = 'Gutiérrez Garzón, L., La auditoría de información como herramienta de evaluación y mejoramiento de la gestión de documentos (2003) Biblios, (16), pp. 14-22; '.cr();
			//$sx = 'Hidalgo Goyanes, P., Prevenir la amnesia colectiva: El acceso público a los archivos de televisión (2013) Documentación de las Ciencias de la Información, 36, pp. 143-166; '.cr();
			//$sx = 'Nuño Moral, M.V., Caldera-Serrano, J., Etapas del tratamiento documental de imagen en movimiento para televisión (2000) Revista General de Información y Documentación, 12 (2), pp. 375-392;'.cr(); 
			//$sx = 'Saavedra Bendito, P., (2011) Los Documentos Audiovisuales, ¿qué Son y Cómo Se Tratan?, , Gijón: Trea; '.cr();
			//$sx = 'Salvador Benítez, A., Políticas de salvaguardia y acceso en los archivos audiovisuales de televisión: Marco jurídico y nuevos servicios interactivos en la televisión digital (2010) Derecom, (2), pp. 1-18'.cr();
			//$sx .= 'UR  - https://www.scopus.com/inward/record.uri?eid=2-s2.0-84999634032&doi=10.1590%2f2318-08892016000300004&partnerID=40&md5=c157b24e59174ba8c1edb484dd3d030a'.cr();
			$sx .= 'ER  - '.cr();
			$sx .= ''.cr(); 
			$sx .= ''.cr();  
			return($sx);
		}	
	}
?>
