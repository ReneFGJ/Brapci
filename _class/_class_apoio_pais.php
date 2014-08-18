<?php
class apoio_pais
{
		var $tabela = 'ajax_pais';
		var $protocol;
	
	function country_iten_del($id,$proto)
		{
			$sql = "update cep_submit_country set ctr_ativo = 0 
				where id_ctr = ".round($id)." and ctr_protocol = '$proto'";
				
			$rlt = db_query($sql);
			return(1);
		}
	function country_iten_insert($protocol,$desc,$target)
		{
			$sql = "select * from cep_submit_country
				where 
				ctr_country = ".$desc."
				and ctr_protocol = '".$protocol."'
				and ctr_ativo = 1 ";
				
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					return(0);
				} else {
					$sql = "insert into cep_submit_country
						(ctr_country, ctr_protocol, ctr_target, ctr_ativo)
						values
						('$desc','$protocol','$target',1);
					";
					$rlt = db_query($sql);
					return(1);
				}
		}		
	function form_country()
			{
			$sx .= '<table width="100%"  class="lt0" border=1>';
			$sx .= '<TR bgcolor="#C0C0C0"><TH width=5%>'.msg('country_item');
			$sx .= '<TH width=60%>'.msg('country_desc');
			$sx .= '<TH>'.msg('country_sample_size');
			
			/* Form */
			$sx .= '
			<style>
				#dd3 { width: 300px; }
				#dd4 { width: 70px; }
				#dd5 { width: 70px; }
			</style>';
			$sx .= '<TR>';
			$sx .= '<TD>';
			$sx .= gets('dd3a',$dd[3],'$Q pais_nome:pais_codigo:select * from ajax_pais where pais_idioma = \'en_US\' and pais_ativo=1 order by pais_nome',$dd[3],0,1,'','form_textarea_full','');
			$sx .= gets('dd4a',$dd[4],'$I4',$dd[4],0,1,'','form_textarea_full','');
			$sx .= '<TD><input type="button" id="country_post" value="'.msg('country_post').'">';
			$sx .= '</table>';
			$sx .= '</div>';
			
			$cr = chr(13).chr(10);
			$sx .= '<script>'.$cr;
			$sx .= '$("#country_post").click(function() 
				{
					var v1 = $(\'#dd3a\').val();
					var v2 = $(\'#dd4a\').val();
					var site = \'submit_ajax_php\';
					var ok = 1;			
					if (v1.length == 0) { ok = 0; alert(\'Descriction is necessary\'); }
					if (ok == 1)
					{ 
			 		$.ajax({
			 				url: "submit_ajax.php",
			 				type: "POST",
			 				data: { dd1: v1, dd2: v2, dd10: "country" ,dd11: "'.$this->protocol.'", dd12: "DEL" }
			 		 }) 
					.fail(function() { alert("error"); })
			 		.success(function(data) { $("#country").html(data); });
					} 
				});
				
			'.$cr;
			$sx .= '</script>'.$cr;
			return($sx);
		}
	function country_list($protocol)
		{
			global $tab_max;
			$sql = "select * from cep_submit_country 
				inner join ".$this->tabela." on ctr_country = pais_codigo
				where ctr_protocol = '".$protocol."' and ctr_ativo = 1
				order by pais_nome";
			$rlt = db_query($sql);
			$it = 0;
			$tot = 0;
			$toti = 0;
			$sx .= '<table width="'.$tab_max.'" class="lt1">';
			$sx .= '<TR>';
			$sx .= '<TH width="5%">'.msg('budget_item');
			$sx .= '<TH>'.msg('country_desc');
			$sx .= '<TH>'.msg('country_sample_size');
			while ($line = db_read($rlt))
				{
					$link = "<A HREF=\"javascript:country_del(".$line['id_ctr'];
					$link .= ",'".checkpost($line['id_ctr'])."');\">";
					$it++;
					$toti = $toti + $line['sorca_unid'];
					$tot = $tot + $line['sorca_unid']*$line['sorca_valor'];
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD align="center">'.$it;
					$sx .= '<TD align="left">'.trim($line['pais_nome']);
					$sx .= '<TD align="center">'.trim($line['ctr_target']);
					$sx .= '<TD align="right" width="10">';
					$sx .= $link;
					$sx .= '<img src="img/icone_remove.png" border=0>';
					$sx .= '</A>';
				}
			$sx .= '</table>';
			
			$s .= chr(13).'<script type="text/javascript">';
			$s .= chr(13).'function country_del(id) {';
			$s .= chr(13).'var $tela = $.ajax({ url: "submit_ajax.php", type: "POST", ';
			$s .= chr(13).'data: { dd0: id, dd10: "country" ,dd12 :"DEL" ,dd11: "'.$protocol.'" }';
			$s .= chr(13).'})';
			$s .= chr(13).'.fail(function() { alert("error"); })';
			$s .= chr(13).'.success(function(data) { $("#country").html(data); });';
			$s .= chr(13).'}';
			$s .= chr(13).'</script>';		
			$sx .= chr(13).$s;		
			return($sx);
		}		
	function cp()
		{
			global $messa,$dd;
			$cp = array();	
			array_push($cp,array('$H8','id_pais','',False,True));
			array_push($cp,array('$S80','pais_nome',msg('country_name'),False,True));
			array_push($cp,array('$H8','pais_use','',False,True));
			array_push($cp,array('$H8','pais_codigo','',False,True));
			array_push($cp,array('$O 1:#YES&0:#NO','pais_ativo',msg('ativo'),False,True));
			array_push($cp,array('$H8','pais_idioma','',False,True));
			return($cp);
		}	
		
	function row()
			{
				global $cdf,$cdm,$masc,$messa;
				$cdf = array('id_pais','pais_nome','pais_idioma');
				$cdm = array('cod',msg('country_name'),msg('idioma'));
				$masc = array('','','','SN','','','');
				return(1);				
			}
	
	
	function strcuture_english()
		{
$sql = "
insert into ajax_pais
(pais_nome,pais_codigo,pais_ativo,pais_idioma,pais_use)
values
('ASCENSION ISLAND ,'',1,'en',''), 
('AFGHANISTAN','',1,'en',''), 
('ALAND','',1,'en',''), 
('ALBANIA','',1,'en',''), 
('ALGERIA','',1,'en',''), 
('ANDORRA','',1,'en',''), 
('ANGOLA','',1,'en',''), 
('ANGUILLA','',1,'en',''), 
('ANTARCTICA','',1,'en',''), 
('ANTIGUA AND BARBUDA','',1,'en',''), 
('ARGENTINA REPUBLIC','',1,'en',''), 
('ARMENIA','',1,'en',''), 
('ARUBA','',1,'en',''), 
('AUSTRALIA','',1,'en',''), 
('AUSTRIA','',1,'en',''), 
('AZERBAIJAN','',1,'en',''), 
('BAHAMAS','',1,'en',''), 
('BAHRAIN','',1,'en',''), 
('BANGLADESH','',1,'en',''), 
('BARBADOS','',1,'en',''), 
('BELARUS','',1,'en',''), 
('BELGIUM','',1,'en',''), 
('BELIZE','',1,'en',''), 
('BENIN','',1,'en',''), 
('BERMUDA','',1,'en',''), 
('BHUTAN','',1,'en',''), 
('BOLIVIA','',1,'en',''), 
('BOSNIA AND HERZEGOVINA','',1,'en',''), 
('BOTSWANA','',1,'en',''), 
('BOUVET ISLAND','',1,'en',''), 
('BRAZIL','',1,'en',''), 
('BRITISH INDIAN OCEAN TERR','',1,'en',''), 
('BRITISH VIRGIN ISLANDS','',1,'en',''), 
('BRUNEI DARUSSALAM','',1,'en',''), 
('BULGARIA','',1,'en',''), 
('BURKINA FASO','',1,'en',''), 
('BURUNDI','',1,'en',''), 
('CAMBODIA','',1,'en',''), 
('CAMEROON','',1,'en',''), 
('CANADA','',1,'en',''), 
('CAPE VERDE','',1,'en',''), 
('CAYMAN ISLANDS','',1,'en',''), 
('CENTRAL AFRICAN REPUBLIC','',1,'en',''), 
('CHAD','',1,'en',''), 
('CHILE','',1,'en',''), 
('PEOPLE’S REPUBLIC OF CHINA','',1,'en',''), 
('CHRISTMAS ISLANDS','',1,'en',''), 
('COCOS ISLANDS','',1,'en',''), 
('COLOMBIA','',1,'en',''), 
('COMORAS','',1,'en',''), 
('CONGO','',1,'en',''), 
('CONGO (DEMOCRATIC REPUBLIC)','',1,'en',''), 
('COOK ISLANDS','',1,'en',''), 
('COSTA RICA','',1,'en',''), 
('COTE D IVOIRE','',1,'en',''), 
('CROATIA','',1,'en',''), 
('CUBA','',1,'en',''), 
('CYPRUS','',1,'en',''), 
('CZECH REPUBLIC','',1,'en',''), 
('DENMARK','',1,'en',''), 
('DJIBOUTI','',1,'en',''), 
('DOMINICA','',1,'en',''), 
('DOMINICAN REPUBLIC','',1,'en',''), 
('EAST TIMOR','',1,'en',''), 
('ECUADOR','',1,'en',''), 
('EGYPT','',1,'en',''), 
('EL SALVADOR','',1,'en',''), 
('EQUATORIAL GUINEA','',1,'en',''), 
('ESTONIA','',1,'en',''), 
('ETHIOPIA','',1,'en',''), 
('FALKLAND ISLANDS','',1,'en',''), 
('FAROE ISLANDS','',1,'en',''), 
('FIJI','',1,'en',''), 
('FINLAND','',1,'en',''), 
('FRANCE','',1,'en',''), 
('FRANCE METROPOLITAN','',1,'en',''), 
('FRENCH GUIANA','',1,'en',''), 
('FRENCH POLYNESIA','',1,'en',''), 
('FRENCH SOUTHERN TERRITORIES','',1,'en',''), 
('GABON','',1,'en',''), 
('GAMBIA','',1,'en',''), 
('GEORGIA','',1,'en',''), 
('GERMANY','',1,'en',''), 
('GHANA','',1,'en',''), 
('GIBRALTER','',1,'en',''), 
('GREECE','',1,'en',''), 
('GREENLAND','',1,'en',''), 
('GRENADA','',1,'en',''), 
('GUADELOUPE','',1,'en',''), 
('GUAM','',1,'en',''), 
('GUATEMALA','',1,'en',''), 
('GUINEA','',1,'en',''), 
('GUINEA-BISSAU','',1,'en',''), 
('GUYANA','',1,'en',''), 
('HAITI','',1,'en',''), 
('HEARD & MCDONALD ISLAND','',1,'en',''), 
('HONDURAS','',1,'en',''), 
('HONG KONG','',1,'en',''), 
('HUNGARY','',1,'en',''), 
('ICELAND','',1,'en',''), 
('INDIA','',1,'en',''), 
('INDONESIA','',1,'en',''), 
('IRAN, ISLAMIC REPUBLIC OF','',1,'en',''), 
('IRAQ','',1,'en',''), 
('IRELAND','',1,'en',''), 
('ISLE OF MAN','',1,'en',''), 
('ISRAEL','',1,'en',''), 
('ITALY','',1,'en',''), 
('JAMAICA','',1,'en',''), 
('JAPAN','',1,'en',''), 
('JORDAN','',1,'en',''), 
('KAZAKHSTAN','',1,'en',''), 
('KENYA','',1,'en',''), 
('KIRIBATI','',1,'en',''), 
('KOREA, DEM. PEOPLES REP OF','',1,'en',''), 
('KOREA, REPUBLIC OF','',1,'en',''), 
('KUWAIT','',1,'en',''), 
('KYRGYZSTAN','',1,'en',''), 
('LAO PEOPLE’S DEM. REPUBLIC','',1,'en',''), 
('LATVIA','',1,'en',''), 
('LEBANON','',1,'en',''), 
('LESOTHO','',1,'en',''), 
('LIBERIA','',1,'en',''), 
('LIBYAN ARAB JAMAHIRIYA','',1,'en',''), 
('LIECHTENSTEIN','',1,'en',''), 
('LITHUANIA','',1,'en',''), 
('LUXEMBOURG','',1,'en',''), 
('MACAO','',1,'en',''), 
('MACEDONIA','',1,'en',''), 
('MADAGASCAR','',1,'en',''), 
('MALAWI','',1,'en',''), 
('MALAYSIA','',1,'en',''), 
('MALDIVES','',1,'en',''), 
('MALI','',1,'en',''), 
('MALTA','',1,'en',''), 
('MARSHALL ISLANDS','',1,'en',''), 
('MARTINIQUE','',1,'en',''), 
('MAURITANIA','',1,'en',''), 
('MAURITIUS','',1,'en',''), 
('MAYOTTE','',1,'en',''), 
('MEXICO','',1,'en',''), 
('MICRONESIA','',1,'en',''), 
('MOLDAVA REPUBLIC OF','',1,'en',''), 
('MONACO','',1,'en',''), 
('MONGOLIA','',1,'en',''), 
('MONTENEGRO','',1,'en',''), 
('MONTSERRAT','',1,'en',''), 
('MOROCCO','',1,'en',''), 
('MOZAMBIQUE','',1,'en',''), 
('MYANMAR','',1,'en',''), 
('NAMIBIA','',1,'en',''), 
('NAURU','',1,'en',''), 
('NEPAL','',1,'en',''), 
('NETHERLANDS ANTILLES','',1,'en',''), 
('NETHERLANDS, THE','',1,'en',''), 
('NEW CALEDONIA','',1,'en',''), 
('NEW ZEALAND','',1,'en',''), 
('NICARAGUA','',1,'en',''), 
('NIGER','',1,'en',''), 
('NIGERIA','',1,'en',''), 
('NIUE','',1,'en',''), 
('NORFOLK ISLAND','',1,'en',''), 
('NORTHERN MARIANA ISLANDS','',1,'en',''), 
('NORWAY','',1,'en',''), 
('OMAN','',1,'en',''), 
('PAKISTAN','',1,'en',''), 
('PALAU','',1,'en',''), 
('PALESTINE','',1,'en',''), 
('PANAMA','',1,'en',''), 
('PAPUA NEW GUINEA','',1,'en',''), 
('PARAGUAY','',1,'en',''), 
('PERU','',1,'en',''), 
('PHILIPPINES (REPUBLIC OF THE)','',1,'en',''), 
('PITCAIRN','',1,'en',''), 
('POLAND','',1,'en',''), 
('PORTUGAL','',1,'en',''), 
('PUERTO RICO','',1,'en',''), 
('QATAR','',1,'en',''), 
('REUNION','',1,'en',''), 
('ROMANIA','',1,'en',''), 
('RUSSIAN FEDERATION','',1,'en',''), 
('RWANDA','',1,'en',''), 
('SAMOA','',1,'en',''), 
('SAN MARINO','',1,'en',''), 
('SAO TOME/PRINCIPE','',1,'en',''), 
('SAUDI ARABIA','',1,'en',''), 
('SCOTLAND','',1,'en',''), 
('SENEGAL','',1,'en',''), 
('SERBIA','',1,'en',''), 
('SEYCHELLES','',1,'en',''), 
('SIERRA LEONE','',1,'en',''), 
('SINGAPORE','',1,'en',''), 
('SLOVAKIA','',1,'en',''), 
('SLOVENIA','',1,'en',''), 
('SOLOMON ISLANDS','',1,'en',''), 
('SOMALIA','',1,'en',''), 
('SOMOA,GILBERT,ELLICE ISLANDS','',1,'en',''), 
('SOUTH AFRICA','',1,'en',''), 
('SOUTH GEORGIA, SOUTH SANDWICH ISLANDS','',1,'en',''), 
('SOVIET UNION','',1,'en',''), 
('SPAIN','',1,'en',''), 
('SRI LANKA','',1,'en',''), 
('ST. HELENA','',1,'en',''), 
('ST. KITTS AND NEVIS','',1,'en',''), 
('ST. LUCIA','',1,'en',''), 
('ST. PIERRE AND MIQUELON','',1,'en',''), 
('ST. VINCENT & THE GRENADINES','',1,'en',''), 
('SUDAN','',1,'en',''), 
('SURINAME','',1,'en',''), 
('SVALBARD AND JAN MAYEN','',1,'en',''), 
('SWAZILAND','',1,'en',''), 
('SWEDEN','',1,'en',''), 
('SWITZERLAND','',1,'en',''), 
('SYRIAN ARAB REPUBLIC','',1,'en',''), 
('TAIWAN','',1,'en',''), 
('TAJIKISTAN','',1,'en',''), 
('TANZANIA, UNITED REPUBLIC OF','',1,'en',''), 
('THAILAND','',1,'en',''), 
('TOGO','',1,'en',''), 
('TOKELAU','',1,'en',''), 
('TONGA','',1,'en',''), 
('TRINIDAD AND TOBAGO','',1,'en',''), 
('TUNISIA','',1,'en',''), 
('TURKEY','',1,'en',''), 
('TURKMENISTAN','',1,'en',''), 
('TURKS AND CALCOS ISLANDS','',1,'en',''), 
('TUVALU','',1,'en',''), 
('UGANDA','',1,'en',''), 
('UKRAINE','',1,'en',''), 
('UNITED ARAB EMIRATES','',1,'en',''), 
('UNITED KINGDOM','',1,'en',''), 
('UNITED STATES','',1,'en',''), 
('UNITED STATES MINOR OUTL.IS.','',1,'en',''), 
('URUGUAY','',1,'en',''), 
('UZBEKISTAN','',1,'en',''), 
('VANUATU','',1,'en',''), 
('VATICAN CITY STATE','',1,'en',''), 
('VENEZUELA','',1,'en',''), 
('VIET NAM','',1,'en',''), 
('VIRGIN ISLANDS (USA)','',1,'en',''), 
('WALLIS AND FUTUNA ISLANDS','',1,'en',''), 
('WESTERN SAHARA','',1,'en',''), 
('YEMEN','',1,'en',''), 
('ZAMBIA','',1,'en',''), 
('ZIMBABWE','',1,'en',''), 

";
		}
	function structure_portugues()
		{
$sql = "
insert into ajax_pais
(pais_nome,pais_codigo,pais_ativo,pais_idioma,pais_use)
values
('Afeganistão','',1,'pt_BR',''), 
('África do Sul','',1,'pt_BR',''), 
('Akrotiri','',1,'pt_BR',''), 
('Albânia','',1,'pt_BR',''), 
('Alemanha','',1,'pt_BR',''), 
('Andorra','',1,'pt_BR',''), 
('Angola','',1,'pt_BR',''), 
('Anguila','',1,'pt_BR',''), 
('Antárctida','',1,'pt_BR',''), 
('Antígua e Barbuda','',1,'pt_BR',''), 
('Antilhas Neerlandesas','',1,'pt_BR',''), 
('Arábia Saudita','',1,'pt_BR',''), 
('Arctic Ocean','',1,'pt_BR',''), 
('Argélia','',1,'pt_BR',''), 
('Argentina','',1,'pt_BR',''), 
('Arménia','',1,'pt_BR',''), 
('Aruba','',1,'pt_BR',''), 
('Ashmore and Cartier Islands','',1,'pt_BR',''), 
('Atlantic Ocean','',1,'pt_BR',''), 
('Austrália','',1,'pt_BR',''), 
('Áustria','',1,'pt_BR',''), 
('Azerbaijão','',1,'pt_BR',''), 
('Baamas','',1,'pt_BR',''), 
('Bangladeche','',1,'pt_BR',''), 
('Barbados','',1,'pt_BR',''), 
('Barém','',1,'pt_BR',''), 
('Bélgica','',1,'pt_BR',''), 
('Belize','',1,'pt_BR',''), 
('Benim','',1,'pt_BR',''), 
('Bermudas','',1,'pt_BR',''), 
('Bielorrússia','',1,'pt_BR',''), 
('Birmânia','',1,'pt_BR',''), 
('Bolívia','',1,'pt_BR',''), 
('Bósnia e Herzegovina','',1,'pt_BR',''), 
('Botsuana','',1,'pt_BR',''), 
('Brasil','',2,'pt_BR',''), 
('Brunei','',1,'pt_BR',''), 
('Bulgária','',1,'pt_BR',''), 
('Burquina Faso','',1,'pt_BR',''), 
('Burúndi','',1,'pt_BR',''), 
('Butão','',1,'pt_BR',''), 
('Cabo Verde','',1,'pt_BR',''), 
('Camarões','',1,'pt_BR',''), 
('Camboja','',1,'pt_BR',''), 
('Canadá','',1,'pt_BR',''), 
('Catar','',1,'pt_BR',''), 
('Cazaquistão','',1,'pt_BR',''), 
('Chade','',1,'pt_BR',''), 
('Chile','',1,'pt_BR',''), 
('China','',1,'pt_BR',''), 
('Chipre','',1,'pt_BR',''), 
('Clipperton Island','',1,'pt_BR',''), 
('Colômbia','',1,'pt_BR',''), 
('Comores','',1,'pt_BR',''), 
('Congo-Brazzaville','',1,'pt_BR',''), 
('Congo-Kinshasa','',1,'pt_BR',''), 
('Coral Sea Islands','',1,'pt_BR',''), 
('Coreia do Norte','',1,'pt_BR',''), 
('Coreia do Sul','',1,'pt_BR',''), 
('Costa do Marfim','',1,'pt_BR',''), 
('Costa Rica','',1,'pt_BR',''), 
('Croácia','',1,'pt_BR',''), 
('Cuba','',1,'pt_BR',''), 
('Dhekelia','',1,'pt_BR',''), 
('Dinamarca','',1,'pt_BR',''), 
('Domínica','',1,'pt_BR',''), 
('Egipto','',1,'pt_BR',''), 
('Emiratos Árabes Unidos','',1,'pt_BR',''), 
('Equador','',1,'pt_BR',''), 
('Eritreia','',1,'pt_BR',''), 
('Eslováquia','',1,'pt_BR',''), 
('Eslovénia','',1,'pt_BR',''), 
('Espanha','',1,'pt_BR',''), 
('Estados Unidos','',1,'pt_BR',''), 
('Estónia','',1,'pt_BR',''), 
('Etiópia','',1,'pt_BR',''), 
('Faroé','',1,'pt_BR',''), 
('Fiji','',1,'pt_BR',''), 
('Filipinas','',1,'pt_BR',''), 
('Finlândia','',1,'pt_BR',''), 
('França','',1,'pt_BR',''), 
('Gabão','',1,'pt_BR',''), 
('Gâmbia','',1,'pt_BR',''), 
('Gana','',1,'pt_BR',''), 
('Gaza Strip','',1,'pt_BR',''), 
('Geórgia','',1,'pt_BR',''), 
('Geórgia do Sul.','',1,'pt_BR',''), 
('Gibraltar','',1,'pt_BR',''), 
('Granada','',1,'pt_BR',''), 
('Grécia','',1,'pt_BR',''), 
('Gronelândia','',1,'pt_BR',''), 
('Guame','',1,'pt_BR',''), 
('Guatemala','',1,'pt_BR',''), 
('Guernsey','',1,'pt_BR',''), 
('Guiana','',1,'pt_BR',''), 
('Guiné','',1,'pt_BR',''), 
('Guiné Equatorial','',1,'pt_BR',''), 
('Guiné-Bissau','',1,'pt_BR',''), 
('Haiti','',1,'pt_BR',''), 
('Honduras','',1,'pt_BR',''), 
('Hong Kong','',1,'pt_BR',''), 
('Hungria','',1,'pt_BR',''), 
('Iémen','',1,'pt_BR',''), 
('Ilha Bouvet','',1,'pt_BR',''), 
('Ilha do Natal','',1,'pt_BR',''), 
('Ilha Norfolk','',1,'pt_BR',''), 
('Ilhas Caimão','',1,'pt_BR',''), 
('Ilhas Cook','',1,'pt_BR',''), 
('Ilhas dos Cocos','',1,'pt_BR',''), 
('Ilhas Falkland','',1,'pt_BR',''), 
('Ilhas Heard e McDonald','',1,'pt_BR',''), 
('Ilhas Marshall','',1,'pt_BR',''), 
('Ilhas Salomão','',1,'pt_BR',''), 
('Ilhas Turcas e Caicos','',1,'pt_BR',''), 
('Ilhas Virgens Americanas','',1,'pt_BR',''), 
('Ilhas Virgens Britânicas','',1,'pt_BR',''), 
('Índia','',1,'pt_BR',''), 
('Indian Ocean','',1,'pt_BR',''), 
('Indonésia','',1,'pt_BR',''), 
('Irão','',1,'pt_BR',''), 
('Iraque','',1,'pt_BR',''), 
('Irlanda','',1,'pt_BR',''), 
('Islândia','',1,'pt_BR',''), 
('Israel','',1,'pt_BR',''), 
('Itália','',1,'pt_BR',''), 
('Jamaica','',1,'pt_BR',''), 
('Jan Mayen','',1,'pt_BR',''), 
('Japão','',1,'pt_BR',''), 
('Jersey','',1,'pt_BR',''), 
('Jibuti','',1,'pt_BR',''), 
('Jordânia','',1,'pt_BR',''), 
('Kuwait','',1,'pt_BR',''), 
('Laos','',1,'pt_BR',''), 
('Lesoto','',1,'pt_BR',''), 
('Letónia','',1,'pt_BR',''), 
('Líbano','',1,'pt_BR',''), 
('Libéria','',1,'pt_BR',''), 
('Líbia','',1,'pt_BR',''), 
('Listenstaine','',1,'pt_BR',''), 
('Lituânia','',1,'pt_BR',''), 
('Luxemburgo','',1,'pt_BR',''), 
('Macau','',1,'pt_BR',''), 
('Macedónia','',1,'pt_BR',''), 
('Madagáscar','',1,'pt_BR',''), 
('Malásia','',1,'pt_BR',''), 
('Malávi','',1,'pt_BR',''), 
('Maldivas','',1,'pt_BR',''), 
('Mali','',1,'pt_BR',''), 
('Malta','',1,'pt_BR',''), 
('Man, Isle of','',1,'pt_BR',''), 
('Marianas do Norte','',1,'pt_BR',''), 
('Marrocos','',1,'pt_BR',''), 
('Maurícia','',1,'pt_BR',''), 
('Mauritânia','',1,'pt_BR',''), 
('Mayotte','',1,'pt_BR',''), 
('México','',1,'pt_BR',''), 
('Micronésia','',1,'pt_BR',''), 
('Moçambique','',1,'pt_BR',''), 
('Moldávia','',1,'pt_BR',''), 
('Mónaco','',1,'pt_BR',''), 
('Mongólia','',1,'pt_BR',''), 
('Monserrate','',1,'pt_BR',''), 
('Montenegro','',1,'pt_BR',''), 
('Mundo','',1,'pt_BR',''), 
('Namíbia','',1,'pt_BR',''), 
('Nauru','',1,'pt_BR',''), 
('Navassa Island','',1,'pt_BR',''), 
('Nepal','',1,'pt_BR',''), 
('Nicarágua','',1,'pt_BR',''), 
('Níger','',1,'pt_BR',''), 
('Nigéria','',1,'pt_BR',''), 
('Niue','',1,'pt_BR',''), 
('Noruega','',1,'pt_BR',''), 
('Nova Caledónia','',1,'pt_BR',''), 
('Nova Zelândia','',1,'pt_BR',''), 
('Omã','',1,'pt_BR',''), 
('Pacific Ocean','',1,'pt_BR',''), 
('Países Baixos','',1,'pt_BR',''), 
('Palau','',1,'pt_BR',''), 
('Panamá','',1,'pt_BR',''), 
('Papua-Nova Guiné','',1,'pt_BR',''), 
('Paquistão','',1,'pt_BR',''), 
('Paracel Islands','',1,'pt_BR',''), 
('Paraguai','',1,'pt_BR',''), 
('Peru','',1,'pt_BR',''), 
('Pitcairn','',1,'pt_BR',''), 
('Polinésia Francesa','',1,'pt_BR',''), 
('Polónia','',1,'pt_BR',''), 
('Porto Rico','',1,'pt_BR',''), 
('Portugal','',1,'pt_BR',''), 
('Quénia','',1,'pt_BR',''), 
('Quirguizistão','',1,'pt_BR',''), 
('Quiribáti','',1,'pt_BR',''), 
('Reino Unido','',1,'pt_BR',''), 
('República Centro-Africana','',1,'pt_BR',''), 
('República Checa','',1,'pt_BR',''), 
('República Dominicana','',1,'pt_BR',''), 
('Roménia','',1,'pt_BR',''), 
('Ruanda','',1,'pt_BR',''), 
('Rússia','',1,'pt_BR',''), 
('Salvador','',1,'pt_BR',''), 
('Samoa','',1,'pt_BR',''), 
('Samoa Americana','',1,'pt_BR',''), 
('Santa Helena','',1,'pt_BR',''), 
('Santa Lúcia','',1,'pt_BR',''), 
('São Cristóvão e Neves','',1,'pt_BR',''), 
('São Marinho','',1,'pt_BR',''), 
('São Pedro e Miquelon','',1,'pt_BR',''), 
('São Tomé e Príncipe','',1,'pt_BR',''), 
('São Vicente e Granadinas','',1,'pt_BR',''), 
('Sara Ocidental','',1,'pt_BR',''), 
('Seicheles','',1,'pt_BR',''), 
('Senegal','',1,'pt_BR',''), 
('Serra Leoa','',1,'pt_BR',''), 
('Sérvia','',1,'pt_BR',''), 
('Singapura','',1,'pt_BR',''), 
('Síria','',1,'pt_BR',''), 
('Somália','',1,'pt_BR',''), 
('Southern Ocean','',1,'pt_BR',''), 
('Spratly Islands','',1,'pt_BR',''), 
('Sri Lanca','',1,'pt_BR',''), 
('Suazilândia','',1,'pt_BR',''), 
('Sudão','',1,'pt_BR',''), 
('Suécia','',1,'pt_BR',''), 
('Suíça','',1,'pt_BR',''), 
('Suriname','',1,'pt_BR',''), 
('Svalbard e Jan Mayen','',1,'pt_BR',''), 
('Tailândia','',1,'pt_BR',''), 
('Taiwan','',1,'pt_BR',''), 
('Tajiquistão','',1,'pt_BR',''), 
('Tanzânia','',1,'pt_BR',''), 
('Terr. Brit. do Oceano Índico','',1,'pt_BR',''), 
('Territórios Austrais Franceses','',1,'pt_BR',''), 
('Timor Leste','',1,'pt_BR',''), 
('Togo','',1,'pt_BR',''), 
('Tokelau','',1,'pt_BR',''), 
('Tonga','',1,'pt_BR',''), 
('Trindade e Tobago','',1,'pt_BR',''), 
('Tunísia','',1,'pt_BR',''), 
('Turquemenistão','',1,'pt_BR',''), 
('Turquia','',1,'pt_BR',''), 
('Tuvalu','',1,'pt_BR',''), 
('Ucrânia','',1,'pt_BR',''), 
('Uganda','',1,'pt_BR',''), 
('União Europeia','',1,'pt_BR',''), 
('Uruguai','',1,'pt_BR',''), 
('Usbequistão','',1,'pt_BR',''), 
('Vanuatu','',1,'pt_BR',''), 
('Vaticano','',1,'pt_BR',''), 
('Venezuela','',1,'pt_BR',''), 
('Vietname','',1,'pt_BR',''), 
('Wake Island','',1,'pt_BR',''), 
('Wallis e Futuna','',1,'pt_BR',''), 
('West Bank','',1,'pt_BR',''), 
('Zâmbia','',1,'pt_BR',''), 
('Zimbabué','',1,'pt_BR','')
";
$rlt = db_query($sql);
}
		function updatex()
			{
				global $base;
				$c = 'pais';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ajax_pais set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ajax_pais set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}
}
