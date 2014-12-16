<?php
require("cab.php");
echo '<h1>Sobre a Brapci</h1>';

$periodicos = 39;
$periodicos_descontinuados = 9;

echo '
<P>A Base de Dados Referenciais de Artigos de Peri�dicos em Ci�ncia da Informa��o (Brapci) � o produto de informa��o do projeto de pesquisa "Op��es metodol�gicas em pesquisa: a contribui��o da �rea da informa��o para a produ��o de saberes no ensino superior", cujo objetivo � subsidiar estudos e propostas na �rea de Ci�ncia da Informa��o, fundamentando-se em atividades planejadas institucionalmente. Com esse prop�sito, foram identificados os t�tulos de peri�dicos da �rea de Ci�ncia da Informa��o (CI) e indexados seus artigos, constituindo-se a base de dados referenciais. Atualmente disponibiliza refer�ncias e resumos de 8303 textos publicados em 37 peri�dicos nacionais impressos e eletr�nicos da �rea de CI. Dos peri�dicos dispon�veis $periodicos est�o ativos e $periodicos_descontinuados hist�ricos (descontinuados).</P> 
<BR>
<P>A constru��o da Brapci est� contribuindo para estudos anal�ticos e descritivos sobre a produ��o editorial de uma �rea em desenvolvimento, ao subsidiar com uma ferramenta din�mica os alunos, professores e pesquisadores da �rea.</P>
<BR>
<P>A Brapci amplia o espa�o document�rio permitido ao pesquisador, facilita a vis�o de conjunto da produ��o na �rea, ao mesmo tempo, que revela especificidades do dom�nio cient�fico. Os saberes e as pesquisas publicados e organizados para f�cil recupera��o clarificam as posi��es te�ricas dos pesquisadores.Projeto financiado pelo Conselho Nacional de Pesquisa e Desenvolvimento.</P>
<BR>
<P>Grupo de pesquisa E3PI</P>';

echo '<BR><BR>';

echo '<h1>Pol�tica de Indexa��o</h1>';
echo '<A HREF="__documentos/POLITICA DE INDEXACAO - v0.14.34.pdf">Documento v0.14.34</A>

';

echo '<BR><BR>';

require("about_pontos.php");

require("foot.php");
?>
