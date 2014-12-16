<?php
require("cab.php");
echo '<h1>Sobre a Brapci</h1>';

$periodicos = 39;
$periodicos_descontinuados = 9;

echo '
<P>A Base de Dados Referenciais de Artigos de Periódicos em Ciência da Informação (Brapci) é o produto de informação do projeto de pesquisa "Opções metodológicas em pesquisa: a contribuição da área da informação para a produção de saberes no ensino superior", cujo objetivo é subsidiar estudos e propostas na área de Ciência da Informação, fundamentando-se em atividades planejadas institucionalmente. Com esse propósito, foram identificados os títulos de periódicos da área de Ciência da Informação (CI) e indexados seus artigos, constituindo-se a base de dados referenciais. Atualmente disponibiliza referências e resumos de 8303 textos publicados em 37 periódicos nacionais impressos e eletrônicos da área de CI. Dos periódicos disponíveis $periodicos estão ativos e $periodicos_descontinuados históricos (descontinuados).</P> 
<BR>
<P>A construção da Brapci está contribuindo para estudos analíticos e descritivos sobre a produção editorial de uma área em desenvolvimento, ao subsidiar com uma ferramenta dinâmica os alunos, professores e pesquisadores da área.</P>
<BR>
<P>A Brapci amplia o espaço documentário permitido ao pesquisador, facilita a visão de conjunto da produção na área, ao mesmo tempo, que revela especificidades do domínio científico. Os saberes e as pesquisas publicados e organizados para fácil recuperação clarificam as posições teóricas dos pesquisadores.Projeto financiado pelo Conselho Nacional de Pesquisa e Desenvolvimento.</P>
<BR>
<P>Grupo de pesquisa E3PI</P>';

echo '<BR><BR>';

echo '<h1>Política de Indexação</h1>';
echo '<A HREF="__documentos/POLITICA DE INDEXACAO - v0.14.34.pdf">Documento v0.14.34</A>

';

echo '<BR><BR>';

require("about_pontos.php");

require("foot.php");
?>
