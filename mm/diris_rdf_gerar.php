<?
$crnf = chr(13).chr(10);
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<rdf:RDF xmlns:dcterms="http://purl.org/dc/terms /" ';
echo 'xmlns:gtbib="'.$site.'/gtbib/xml" ';
echo 'xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:rft="info:ofi/fmt:xml:xsd:journal" ';
echo 'xmlns:vCard="http://www.w3.org/2001/vcard-rdf/3.0#">'.$crnf;

echo '    <rdf:Description rdf:about="'.$site.'/mm/diris_rdf.php?dd0='.$dd[0].'">'.$crnf;
/////////////////////////////////////////////////////// Dados do autor
echo '        <vCard:A>Babini</vCard:A>'.$crnf;
echo '        <vCard:N>Dominique</vCard:N>'.$crnf;
echo '        <vCard:ORG>Consejo Latinoamericano de Ciencias Sociales (Clacso)</vCard:ORG>'.$crnf;
echo '        <vCard:ADR rdf:parseType="Resource">'.$crnf;
echo '            <rdf:value>Av. Callao, 875</rdf:value>'.$crnf;
echo '            <rdf:type rdf:resource="http://www.w3.org/2001/vcard-rdf/3.0#work" />'.$crnf;
echo '            <rdf:value>C1023AAB</rdf:value>'.$crnf;
echo '            <rdf:value>Buenos Aires</rdf:value>'.$crnf;
echo '            <rdf:value />'.$crnf;
echo '            <rdf:value>AR</rdf:value>'.$crnf;
echo '        </vCard:ADR>'.$crnf;
echo '        <vCard:TEL rdf:parseType="Resource">'.$crnf;
echo '            <rdf:value>+54-11-4811 6588 int.304</rdf:value>'.$crnf;
echo '            <rdf:type rdf:resource="http://www.w3.org/2001/vcard-rdf/3.0#fax" />'.$crnf;
echo '            <rdf:value />'.$crnf;
echo '            <rdf:value>+54-11-4812  8459</rdf:value>'.$crnf;
echo '        </vCard:TEL>'.$crnf;
echo '        <vCard:EMAIL rdf:parseType="Resource">'.$crnf;
echo '            <rdf:value>babini@clacso.edu.ar</rdf:value>'.$crnf;
echo '            <rdf:type rdf:resource="http://www.w3.org/2001/vcard-rdf/3.0#internet" />'.$crnf;
echo '            <rdf:value>dasbabini@gmail.com</rdf:value>'.$crnf;
echo '        </vCard:EMAIL>'.$crnf;
echo '        <vCard:URL rdf:parseType="Resource">'.$crnf;
echo '            <rdf:value>http://www.biblioteca.clacso.edu.ar/staff/dbabini.cv/        </rdf:value>'.$crnf;
echo '            <rdf:type rdf:resource="http://www.w3.org/2001/vcard-rdf/3.0#work" />'.$crnf;
echo '            <rdf:value>http://www.biblioteca.clacso.edu.ar</rdf:value>'.$crnf;
echo '        </vCard:URL>'.$crnf;
echo '    </rdf:Description>'.$crnf;
echo '</rdf:RDF>'.$crnf;
?>