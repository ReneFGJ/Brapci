<?php
class rdfs extends CI_model
	{
	function rdf_export_namespace_fabio()
		{
			$sx = '@prefix fabio: <http://purl.org/spar/fabio/> .'.cr();
			$sx .= '@prefix c4o: <http://purl.org/spar/c4o/> .'.cr();
			$sx .= '@prefix dc: <http://purl.org/dc/elements/1.1/> .'.cr();
			$sx .= '@prefix dcterms: <http://purl.org/dc/terms/> .'.cr();
			$sx .= '@prefix foaf: <http://xmlns.com/foaf/0.1/> .'.cr();
			$sx .= '@prefix owl: <http://www.w3.org/2002/07/owl#> .'.cr();
			$sx .= '@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .'.cr();
			$sx .= '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .'.cr();
			$sx .= '@prefix frbr: <http://purl.org/vocab/frbr/core#> .'.cr();
			$sx .= '@prefix prism: <http://prismstandard.org/namespaces/basic/2.0/> .'.cr();
			return($sx);
		}	
	function rdf_export_namespace_frbr()
		{
			$sx  = '@prefix bibo: <http://purl.org/ontology/bibo/> .'.cr();
			$sx .= '@prefix dc: <http://purl.org/dc/elements/1.1/> .'.cr();
			$sx .= '@prefix dcterms: <http://purl.org/dc/terms/> .'.cr();
			$sx .= '@prefix foaf: <http://xmlns.com/foaf/0.1/> .'.cr();
			$sx .= '@prefix owl: <http://www.w3.org/2002/07/owl#> .'.cr();
			$sx .= '@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .'.cr();
			$sx .= '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .'.cr();
			$sx .= '@prefix frbr: <http://purl.org/vocab/frbr/core#> .'.cr();
			$sx .= '@prefix prism: <http://prismstandard.org/namespaces/basic/2.0/> .'.cr();
			return($sx);
		}
		
	function rdf_namespace_brapci()
		{
			$sx = '@prefix lattes: <http://www.inf.puc-rio.br/ontology/lattes#> .'.cr();
			$sx .= '@prefix dc: <http://purl.org/dc/terms/> .'.cr();
			$sx .= '@prefix bibo: <http://purl.org/ontology/bibo/> .'.cr();
			$sx .= '@prefix foaf: <http://xmlns.com/foaf/0.1/> .'.cr();
			$sx .= '@prefix skos: <http://www.w3.org/2004/02/skos/core#> .'.cr();
			$sx .= '@prefix event: <http://purl.org/NET/c4dm/event.owl#> .'.cr();
			$sx .= '@prefix po: <http://purl.org/ontology/po/> .'.cr();
			$sx .= '@prefix bm: <http://www.w3.org/2002/01/bookmark#> .'.cr();
			$sx .= '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .'.cr();
			$sx .= '@prefix bio: <http://purl.org/vocab/bio/0.1/> .'.cr();
			$sx .= '@prefix address: <http://schemas.talis.com/2005/address/schema#> .'.cr();
			$sx .= '@prefix sioct: <http://rdfs.org/sioc/types#> .'.cr();
			$sx .= '@prefix rs: <http://vocab.org/resourcelist/schema#> .'.cr();
			$sx .= '@prefix time: <http://www.w3.org/2006/time#> .'.cr();
			$sx .= '@prefix dcterms: <http://purl.org/dc/terms/> . '.cr();
			$sx .= '@prefix brapci: <'.base_url('index.php/rdf/schema/brapci').'/> . '.cr();
			$sx .= cr();
			return($sx);
		}

	function rdf_methodology()
		{
			$sx = '';
			$sql = "select * from brapci_metodologias where bmt_ativo = 1 order by bmt_codigo ";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			for ($r=0;$r < count($rlt);$r++)
				{
					$data = $rlt[$r];
					$sx .= '<'.base_url('index.php/rdf/metodology/'.$data['bmt_codigo']).'>'.cr();
					$sx .= '	a brapci:metodology;'.cr();
					$sx .= '	brapci:name "'.$data['bmt_descricao'].'";'.cr();
					$desc = $data['bmt_content'];
					$desc = troca($desc,chr(13),' ');
					$desc = troca($desc,'"','´');
					$desc = troca($desc,chr(10),'<br>');
					$sx .= '	brapci:content "'.$desc.'";'.cr();
					$sx .= '	brapci:type "'.$data['bmt_tipo'].'".'.cr();						
					$sx .= cr();
				}
			$sx .= cr();
			return($sx);
		}

	function rdf_author($data)
		{
			$sx = '';
			$last_name = substr($data['autor_nome'],0,strpos($data['autor_nome'],','));
			$first_name = trim(substr($data['autor_nome'],strpos($data['autor_nome'],',')+1,200));
			$sx .= '<'.base_url('index.php/rdf/author/'.$data['autor_alias']).'>'.cr();
			$sx .= '	a foaf:Person;'.cr();
			$sx .= '	foaf:name "'.$data['autor_nome'].'";'.cr();
			$sx .= '	foaf:familyName "'.$last_name.'";'.cr();
			$sx .= '	foaf:givenname "'.$first_name.'".'.cr();
			$sx .= cr();
			return($sx);
		}
	function rdf_dc_terms($data)
		{
			/*
				dcterms:Agent
				    dcterms:description "Examples of Agent include person, organization, and software agent."@en ;
				    dcterms:hasVersion <http://dublincore.org/usage/terms/history/#Agent-001> ;
				    dcterms:issued "2008-01-14"^^<http://www.w3.org/2001/XMLSchema#date> ;
				    a dcterms:AgentClass, rdfs:Class ;
				    rdfs:comment "A resource that acts or has the power to act."@en ;
				    rdfs:isDefinedBy <http://purl.org/dc/terms/> ;
				    rdfs:label "Agent"@en .		
			 */	
		}
	function rdf_article($data)
		{
			$ar_title = $data['ar_titulo_1'];
			$ar_title = troca($ar_title,'"','');
			$ar_title = troca($ar_title,chr(13),' ');
			$ar_title = troca($ar_title,chr(10),'');
			$sx = '';
			$sx .= '<'.base_url('index.php/rdf/article/'.$data['ar_codigo']).'>'.cr();
			$sx .= '	a bibo:AcademicArticle;'.cr();
			$sx .= '	dc:title "'.$ar_title.'";'.cr();
			$sx .= '	bibo:volume "'.$data['ed_vol'].'";'.cr();
			if (strlen($data['ed_nr']) > 0) { $sx .= '	bibo:number "'.$data['ed_nr'].'" ;'.cr(); }
			$sx .= '	bibo:years "'.$data['ed_ano'].'";'.cr();
			$sx .= '	bibo:status <http://purl.org/ontology/bibo/status/published> ;'.cr();
			//$sx .= '	lattes:media "ONLINE" ;'.cr();
			$doi = $data['ar_doi'];
			$doi = troca($doi,'<font color="red">empty</font>','');
			if (strlen($doi) > 0) { $sx .= '	bibo:doi "'.$doi.'";'.cr(); }
			for ($rx=0;$rx < count($data['authors']);$rx++)
				{
					$line = $data['authors'][$rx];
					$sx .= '	dc:creator <'.base_url('index.php/rdf/author/'.$line['autor_alias']).'> ;'.cr();
				}
			//$sx .= '	bibo:authorList [ a rdf:Seq ;'.cr();
//			$sx .= '                      rdf:_1 <'.base_url('index.php/rdf/author/'.$data['autor_alias']).'> ;'.cr();
//			$sx .= '					  rdf:_2 <http://www.inf.puc-rio.br/people/daniel_schwabe> ;'.cr();
//			$sx .= '					  rdf:_3 <http://www.inf.puc-rio.br/people/bruno_feijo>'.cr();
			//$sx .= '	                ];'.cr();
			$sx .= '	bibo:isPartOf <http://www.inf.puc-rio.br/periodicals/lecture_notes_in_computer_science> ;'.cr();
			$sx .= '	bibo:pageStart "'.$data['ar_pg_inicial'].'" ;'.cr();
			$sx .= '	bibo:pageEnd "'.$data['ar_pg_final'].'" ;'.cr();

			$keywords = $data['keywords'];
			for ($r=0;$r < count($keywords);$r++)
				{
					$key = $keywords[$r]['kw_word'];
					$key = troca($key,'"','');
					$sx .= '	dc:term "'.$key.'"@'.troca($keywords[$r]['kw_idioma'],'_','-').' ;'.cr();
				}
//			$sx .= '	bibo:uri "[http://www.springerlink.com/(0ovzk455jqgeeazsaue2cl45)/app/home/contribution.asp?referrer=parent&backto=issue,16,31;journal,337,3947;linkingpublicationresults,1:105633,1][doi:10.1007/11568322_16]";'.cr();
			
			$sx .= '	dc:language "'.msg($data['ar_idioma_1']).'"@'.troca($data['ar_idioma_1'],'_','-').';'.cr();
			$sx .= '	dc:language "'.msg($data['ar_idioma_2']).'"@'.troca($data['ar_idioma_2'],'_','-').';'.cr();
			$sx .= '	brapci:metodology <'.base_url('index.php/rdf/metodology/'.$data['at_metodo_1']).'> ;'.cr();
			$sx .= '	brapci:metodology <'.base_url('index.php/rdf/metodology/'.$data['at_metodo_2']).'> ;'.cr();
			$sx .= '	brapci:metodology <'.base_url('index.php/rdf/metodology/'.$data['at_tecnica_1']).'> ;'.cr();
			$sx .= '	brapci:metodology <'.base_url('index.php/rdf/metodology/'.$data['at_tecnica_2']).'> ;'.cr();
			$sx .= '	brapci:metodology <'.base_url('index.php/rdf/metodology/'.$data['at_tecnica_3']).'> ;'.cr();
			$sx .= '	brapci:metodology <'.base_url('index.php/rdf/metodology/'.$data['at_analise_1']).'> ;'.cr();
			$sx .= '	brapci:metodology <'.base_url('index.php/rdf/metodology/'.$data['at_analise_2']).'> ;'.cr();
			$sx .= '	time:year "'.$data['ed_ano'].'" .'.cr();
			$sx .= cr();
			return($sx);
		}		
	}
?>
