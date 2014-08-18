<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<style>
#topBar { border-bottom : 1px solid Black; }
A {	font-size: 13px; color: black; text-decoration : none; }
A:hover{ font-size: 13px; color: #D2691E; text-decoration : none; }
INPUT{ border: 1px solid black; }
A.buttom{ font-size: 13px; color: gray; text-decoration : none;	font-family: Arial, Helvetica, sans-serif; }
#output INPUT{ border: 0px solid red; }
#header { border-bottom:  1px solid #999999; font-family: Arial, Helvetica, sans-serif;	font-size: 20px;
	color: white; background-color: #008080; margin: 0px; padding: 5px; font-weight : bold;	}
#header A{ color: white; }
#header A:hover{ color: #D2691E; }
#header #title{ display: inline; width: 70%; font-weight: normal; }
#header #language{ font-size: 14px; text-align: right; display: inline;	width: 30%;	}
#main_group { margin-left: 10px; margin-right: 10px; border: 1px solid #999999; height : 100%; }
#left{ margin-left: 10px; margin-right: 10px; }
#right{ margin-right: 10px;	}
#group{ border: 1px solid #999999; margin-top: 10px; margin-bottom: 10px; height : 100%; }
#output{ 	}
#group_title{ border-bottom: 1px solid #999999;  background-color: #e3f0ea; font-family: Arial, Helvetica, sans-serif;
	font-size: 16px; padding: 5px; color: #666666; font-weight : bold; }
#group_content{ font-family: Arial, Helvetica, sans-serif; padding: 5px; }
#menu_item{ font-family: Arial, Helvetica, sans-serif; font-size: 16px;	padding: 5px; width: 100%; }
</style>
<head>
	<title>OAI REol Harvesting Interface</title>
	<link rel="stylesheet" type="text/css" href="layout.css">	
</head>
<script Language="JavaScript">

	function MM_findObj(n, d) 
	{
				 var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
				  d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);
				 }
				 if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
				 for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
				 if(!x && document.getElementById) x=document.getElementById(n); return x;
	}
	
	function MM_showhideLayers() 
	{
				 var i,p,v,obj,args=MM_showhideLayers.arguments; 
				 for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) {
				  v=args[i+2]; z=args[i+3]; 
				  if (!z) {
				   if (obj.style) { obj=obj.style; v=(v=='show')?'block':(v=='hide')?'none':v; } 
				   obj.display=v; 
				  } else {
				   if (obj.style) { obj=obj.style; v=(v=='show')?z:(v=='hide')?'none':v; } 
				   obj.display=v; 
				  }
				 }
	} 
	
	function showVerb(verb)
	{
		MM_showhideLayers('Identify','','hide');
		MM_showhideLayers('ListMetadataFormats','','hide');
		MM_showhideLayers('ListIdentifiers','','hide');
		MM_showhideLayers('ListSets','','hide');
		MM_showhideLayers('ListRecords','','hide');
		MM_showhideLayers('GetRecord','','hide');

		MM_showhideLayers(verb,'','show');		
	}

	function submitForm()
	{
		URL = document.sendQuery.oaiURL.value;
		verb = document.sendQuery.verb.value;
		MetadataPrefix = document.sendQuery.MetadataPrefix.value;
		from = document.sendQuery.from.value;
		until = document.sendQuery.until.value;
		set = document.sendQuery.set.value;
		identifier = document.sendQuery.identifier.value;
		resumptionToken = document.sendQuery.resumptionToken.value;
		formStatus = true;
		if (!URL)
		{
			alert("Is necessary to fill the URL field");
			formStatus = false;
		}
		if (verb == "Identify")
		{
			URL = URL+"?verb="+verb;
		}
		else if (verb == "ListMetadataFormats")
		{
			URL = URL+"?verb="+verb;
		}
		else if (verb == "ListIdentifiers")
		{
			URL = URL+"?verb="+verb;
			if (MetadataPrefix) 
			{URL = URL+"&metadataPrefix="+MetadataPrefix;}
			else 
			{ 
			alert("parameter matadataPrefix is necessary");
			formStatus = false;
			}
			if (from) URL = URL+"&from="+from;
			if (until) URL = URL+"&until="+until;
			if (set) URL = URL+"&set="+set;
			if (resumptionToken) URL = URL+"&resumptionToken="+resumptionToken;
		}
		else if (verb == "ListSets")
		{
			URL = URL+"?verb="+verb;
			if (resumptionToken) URL = URL+"&resumptionToken="+resumptionToken;			
		}
		else if (verb == "ListRecords")
		{
			URL = URL+"?verb="+verb;
			if (MetadataPrefix) 
			{URL = URL+"&metadataPrefix="+MetadataPrefix;}
			else 
			{ 
			alert("parameter matadataPrefix is necessary");
			formStatus = false;
			}
			if (from) URL = URL+"&from="+from;
			if (until) URL = URL+"&until="+until;
			if (set) URL = URL+"&set="+set;			
			if (resumptionToken) URL = URL+"&resumptionToken="+resumptionToken;	
		}
		else if (verb == "GetRecord")
		{
			URL = URL+"?verb="+verb;
			if (MetadataPrefix) 
			{URL = URL+"&metadataPrefix="+MetadataPrefix;}
			else 
			{ 
			alert("parameter matadataPrefix is necessary");
			formStatus = false;
			}
			if (identifier) 
			{URL = URL+"&identifier="+identifier;}
			else 
			{ 
			alert("parameter identifier is necessary");
			formStatus = false;
			}						
		}
		else
		{
			alert("parameter verb is necessary");
			formStatus = false;
		}
		if (formStatus)
		{
			document.sendQuery.outputURL.value = URL;
			window.open(URL,'oai_output');
		}
	}
	function setForm(verb)
	{
		for (i=0 ; i<document.sendQuery.elements.length ; i++)
		{	
			if (document.sendQuery.elements[i].name != "oaiURL")
				document.sendQuery.elements[i].disabled = true;
		}
		
		if (verb == "Identify")
		{
			document.sendQuery.verb.value = "Identify";
		}
		else if (verb == "ListMetadataFormats")
		{
			document.sendQuery.verb.value = "ListMetadataFormats";
		}
		else if (verb == "ListIdentifiers")
		{
			document.sendQuery.verb.value = "ListIdentifiers";
			document.sendQuery.MetadataPrefix.disabled = false;
			document.sendQuery.from.disabled = false;
			document.sendQuery.until.disabled = false;
			document.sendQuery.set.disabled = false;
			document.sendQuery.resumptionToken.disabled = false;										
		}
		else if (verb == "ListSets")
		{
			document.sendQuery.verb.value = "ListSets";
			document.sendQuery.resumptionToken.disabled = false;		
		}
		else if (verb == "ListRecords")
		{
			document.sendQuery.verb.value = "ListRecords";
			document.sendQuery.MetadataPrefix.disabled = false;
			document.sendQuery.from.disabled = false;
			document.sendQuery.until.disabled = false;
			document.sendQuery.set.disabled = false;
			document.sendQuery.resumptionToken.disabled = false;
		}
		else if (verb == "GetRecord")
		{
			document.sendQuery.verb.value = "GetRecord";
			document.sendQuery.MetadataPrefix.disabled = false;			
			document.sendQuery.identifier.disabled = false;			
		}		
	}
</script>
<body marginheight="0" topmargin="0" leftmargin="0">
<div id="header">
	<div id="title">
		OAI RE<SUP>2</SUP>ol Harvesting Interface
	</div>
	<div id="language">
		<a href="oai.php">pt_BR</a>
	</div>
</div>
<FORM name="sendQuery" method="post" action="#">
<div id="main_group">
	<div id="group_title">Endereço URL para coleta de metadados OAI</div>
	<div id="group_content">URL: <input type="text" name="oaiURL" size="50" maxlength="350"/> &nbsp;ex:&nbsp;http://www2.pucpr.br/reol/index.php/bs/oai</div>			
</div>	
<div>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="30%">
			<div id="left">			
				<div id="group">
					<div id="group_title">verbos</div>
					<div id="group_content">
						<div id="menu_item"><a href="javascript: setForm('Identify'); showVerb('Identify');">Identify</a></div>
						<div id="menu_item"><a href="javascript: setForm('ListMetadataFormats'); showVerb('ListMetadataFormats');">ListMetadataFormats</a></div>
						<div id="menu_item"><a href="javascript: setForm('ListIdentifiers'); showVerb('ListIdentifiers');">ListIdentifiers</a></div>
						<div id="menu_item"><a href="javascript: setForm('ListSets'); showVerb('ListSets');">ListSets</a></div>
						<div id="menu_item"><a href="javascript: setForm('ListRecords'); showVerb('ListRecords');">ListRecords</a></div>
						<div id="menu_item"><a href="javascript: setForm('GetRecord'); showVerb('GetRecord');">GetRecord</a></div>																								
					</div>			
				</div>
				<div id="group">
					<div id="group_title">descrição dos verbos</div>
					<div id="group_content">
						<div id="Identify" name="Identify" style="display: none;">
							<p>Este verb retorna as informações principais do protocolo.</p>
							<p>
							<b>Parâmetros Obrigatórios</b>
							<ul><li>verb</li></ul>
							</p>
						</div>
						<div id="ListMetadataFormats" name="ListMetadataFormats" style="display: none;">
							<p>Lista quais são os formatos de metadados utilizados no protocolo. (oai_dc, marc_dc, etc...).</p>
							<p>
							<b>Parâmetros Obrigatórios</b>
							<ul>
								<li>verb</li>
								</ul>
							</p>							
						</div>
						<div id="ListIdentifiers" name="ListIdentifiers" style="display: none;">
							<p>Recupera uma lista com o identificador único de cada artigo publicado.</p>
							<p>
							<b>Parâmetros Obrigatórios</b>
							<ul>
								<li>verb</li>
								<li>metadataPrefix</li>								
							</ul>
							</p>
							<p>
							<b>Parâmetros Opcionais</b>
							<ul>
								<li>from</li>
								<li>until</li>
								<li>set</li>								
							</ul>
							<p>
							<b>Parâmetros Exclusivos</b>
							<ul>
								<li>resumptionToken</li>
							</ul>
							</p>									
						</div>
						<div id="ListSets" name="ListSets" style="display: none;">
							<p>Recupera um lista com todas revistas/volumes publicadas de cada uma.</p>
							<p>
							<b>Parâmetros Obrigatórios</b>
							<ul>
								<li>verb</li>
							</ul>
							</p>	
							<p>
							<b>Parâmetros Exclusivos</b>
							<ul>
								<li>resumptionToken</li>
							</ul>
							</p>									
													
						</div>
						<div id="ListRecords" name="ListRecords" style="display: none;">
							<p>Recupera o resumo dos artigos.</p>
							<p>
							<b>Parâmetros Obrigatórios</b>
							<ul>
								<li>verb</li>
								<li>metadataPrefix</li>								
							</ul>
							</p>
							<p>
							<b>Parâmetros Opcionais</b>
							<ul>
								<li>from</li>
								<li>until</li>
								<li>set</li>								
							</ul>
							<p>
							<b>Parâmetros Exclusivos</b>
							<ul>
								<li>resumptionToken</li>
							</ul>
							</p>															
						</div>
						<div id="GetRecord" name="GetRecord" style="display: none;">
							<p>Recupera o texto completo dos artigos .</p>
							<p>
							<b>Parâmetros Obrigatórios</b>
							<ul>
								<li>verb</li>
								<li>metadataPrefix</li>						
								<li>identifier</li>								
							</ul>
							</p>							
						</div>																														
					</div>			
				</div>		
			</div>
			</td>
			<td width="70%">
			<div id="right">
				<div id="group">
					<div id="group_title">configuração dos parâmetros</div>
					<div id="group_content">
							<table>
								<tr>
									<td>verb</td>
									<td><input type="text" name="verb" disabled="disabled"/></td>
									<td></td>
								</tr>							
								<tr>
									<td>MetadataPrefix</td>
									<td><input type="text" name="MetadataPrefix" maxlength="15"/></td>
									<td>oai_dc</td>
								</tr>
								<tr>
									<td>set</td>
									<td><input type="text" name="set" size="9" maxlength="9"/></td>
									<td>ISSN</td>
								</tr>	
								<tr>
									<td>identifier</td>
									<td><input type="text" name="identifier" size="34" maxlength="150"/></td>
									<td></td>
								</tr>	
								<tr>
									<td>from</td>
									<td><input type="text" name="from" size="10" maxlength="10"/></td>
									<td>YYYY-MM-DD</td>
								</tr>		
								<tr>
									<td>until</td>
									<td><input type="text" name="until" size="10" maxlength="10"/></td>
									<td>YYYY-MM-DD</td>									
								</tr>	
								<tr>
									<td>resumptionToken</td>
									<td><input type="text" name="resumptionToken" size="34"/></td>
									<td></td>									
								</tr>	
							</table>
							<p align="right">
							<a href="javascript: submitForm();">fazer pesquisa</a>
							</p>		
					</div>			
				</div>
				<div id="group">
					<div id="group_title">output</div>
					<div id="group_content">
						<div id="output">
							URL Pesquisada:</br>
							<input type="text" name="outputURL" size="100"/></br>
							Resultado:</br>
							<iframe name="oai_output" height="300" width="99%"/>
						</div>
					</div>			
				</div>	
			</div>
			</td>
		</tr>
	</table>	
</div>
</FORM>
<P>Vesão orginal da tela de consulta : http://www.scielo.br/oai/index_pt.html
<BR>Alterado por : <A HREF="mailto:rene@sisdoc.com.br">Rene F. Gabriel </A>
</body>
</html>
