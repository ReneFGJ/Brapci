<?php
class sparqls extends CI_model {
	function sample() {
		$db = sparql_connect("http://rdf.ecs.soton.ac.uk/sparql/");
		$sx = '';
		if (!$db) { $sx .=  sparql_errno() . ": " . sparql_error() . "\n";
			exit ;
		}
		sparql_ns("foaf", "http://xmlns.com/foaf/0.1/");

		$sparql = '
					SELECT * WHERE {
						?person a foaf:Person . ?person foaf:name ?nome
						FILTER
							(
								?nome like "%George%"
							)
								
							
					} LIMIT 100';
		$result = sparql_query($sparql);
		if (!$result) { $sx .=  sparql_errno() . ": " . sparql_error() . "\n";
			exit ;
		}

		$fields = sparql_field_array($result);

		$sx .=  "<p>Number of rows: " . sparql_num_rows($result) . " results.</p>";
		$sx .=  "<table class='example_table'>";
		$sx .=  "<tr>";
		foreach ($fields as $field) {
			$sx .=  "<th>$field</th>";
		}
		$sx .=  "</tr>";
		while ($row = sparql_fetch_array($result)) {
			$sx .=  "<tr>";
			foreach ($fields as $field) {
				$sx .=  "<td>$row[$field]</td>";
			}
			$sx .=  "</tr>";
		}
		$sx .=  "</table>";
		return($sx);
	}
	function find_country($name='') {
		$db = sparql_connect("http://rdf.ecs.soton.ac.uk/sparql/");
		$sx = '';
		if (!$db) { $sx .=  sparql_errno() . ": " . sparql_error() . "\n";
			exit ;
		}
		
		//sparql_ns("dbpedia", "http://dbpedia.org/resource/");
		//sparql_ns("rdf", "http://www.w3.org/2000/01/rdf");
		//sparql_ns("rdfs", "http://www.w3.org/2000/01/rdf-schema");
		//sparql_ns("foaf", "http://xmlns.com/foaf/0.1/");
		//sparql_ns("geo", 'http://www.geonames.org/ontology#');
		//sparql_ns("lotico", 'http://www.lotico.com/ontology/');

		$sparql = 'PREFIX lotico: <http://www.lotico.com/ontology/>
					SELECT ?o ?n
						{
						 ?p lotico:countryCode ?o .
						 ?p lotico:city ?n .
						 ?p lotico:countryCode "BR" .
						}
					ORDER BY ?n';
		
		$result = sparql_query($sparql);
		if (!$result) { $sx .=  sparql_errno() . ": " . sparql_error() . "\n";
			exit ;
		}

		$fields = sparql_field_array($result);

		$sx .=  "<p>Number of rows: " . sparql_num_rows($result) . " results.</p>";
		$sx .=  "<table class='example_table'>";
		$sx .=  "<tr>";
		foreach ($fields as $field) {
			$sx .=  "<th>$field</th>";
		}
		$sx .=  "</tr>";
		while ($row = sparql_fetch_array($result)) {
			$sx .=  "<tr>";
			foreach ($fields as $field) {
				$sx .=  "<td>$row[$field]</td>";
			}
			$sx .=  "</tr>";
		}
		$sx .=  "</table>";
		return($sx);
	}
}
?>
