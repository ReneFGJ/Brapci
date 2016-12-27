<?php
// This file is part of the Brapci Software. 
// 
// Copyright 2015, UFPR. All rights reserved. You can redistribute it and/or modify
// Brapci under the terms of the Brapci License as published by UFPR, which
// restricts commercial use of the Software. 
// 
// Brapci is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details. 
// 
// You should have received a copy of the Brapci License along with the Brapci
// Software. If not, see
// https://github.com/ReneFGJ/Brapci/tree/master//LICENSE.txt 
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-12-01
 */
if (!function_exists(('msg')))
	{
		function msg($t)
			{
				$CI = &get_instance();
				if (strlen($CI->lang->line($t)) > 0)
					{
						return($CI->lang->line($t));
					} else {
						return($t);
					}
			}
	}
/* Cited */
$lang['bt_thesauros'] = 'tesauros';
$lang['how_to_cite'] = 'Como citar este trabalho';
$lang['mes_01a'] = 'Jan.';
$lang['mes_02a'] = 'Fev.';
$lang['mes_03a'] = 'Mar.';
$lang['mes_04a'] = 'Maio';
$lang['mes_05a'] = 'Jun.';
$lang['mes_06a'] = 'Jul';
$lang['mes_08a'] = 'Ago.';
$lang['mes_09a'] = 'Set.';
$lang['mes_10a'] = 'Out.';
$lang['mes_11a'] = 'Nov.';
$lang['mes_12a'] = 'Dez.';
/* CAB */
$lang['perfil_coordenador'] = 'Coordenador';
$lang['search'] = 'pesquisar';
$lang['bt_admin'] = 'admin';
$lang['bt_home'] = 'HOME';
$lang['bt_sign_out'] = 'logout';
$lang['bt_sign_in'] = 'fazer login';
$lang['bt_about'] = 'sobre a brapci';
$lang['admin_home'] = 'ADMIN HOME';
$lang['menu_admin'] = 'ADMIN';
$lang['last_update'] = 'Útima atualização';

$lang['to index'] = 'indexando';
$lang['finished'] = 'finalizado';
$lang['revised'] = 'revisado';
$lang['to review'] = 'para revisão';

$lang['pt_BR'] = 'Português';
$lang['en'] = 'Inglês';
$lang['es'] = 'Espanhol';
$lang['fr'] = 'Francês';
	
$lang['oai_journals'] = 'Publicações compatíveis com OAI-PMH';
	
$lang['form_query'] = "Pergunta de Busca";
$lang['form_found'] = "foram localizado(s)";
$lang['form_records'] = 'registro(s)';

$lang['city'] = 'localidade';
$lang['status_article_A'] = 'Em revisão';
$lang['status_article_B'] = '1º revisão';
$lang['status_article_C'] = '2º revisão';
$lang['status_article_D'] = 'revisado';

$lang['issue_new'] = 'novo fascículo';

/* Top Menu */
$lang['sign_in'] = 'ACESSAR';

/* SESSIOn */
$lang['save_session'] = 'Salvar busca >>>';

/* Login */
$lang['login_enter'] = 'Entar';
$lang['login_name'] = 'Informe seu login';
$lang['login_password'] = 'Informe sua senha';
$lang['login_enter'] = 'Entar';
$lang['login_social'] = 'Logue com uma conta existente (recomendado)';
$lang['your_passoword'] = 'sua senha';

$lang['form_year_cut'] = 'delimitação da busca:';

/* Controle de autoridade */
$lang['menu_authority_controle'] = 'Controle de Autoridade';
$lang['menu_authority_person'] = 'Pessoas';
$lang['Search for...'] = 'Busca por ...';
$lang['authority_search'] = 'Busca por autoridades';

/* Tools */
$lang['menu_tools'] = 'Ferramentas';
$lang['change_xls'] = 'Converter CSV para Matrix';
$lang['gerar_list_nomes'] = 'Extrair nomes de uma Matrix';
$lang['gerar_matriz'] = 'Gerar Matrix';
$lang['gerar_pajek'] = 'Gerar arquivo para o Pajek (.pjk)';

$lang['idioma_01'] = 'Idioma preferencial';
$lang['idioma_02'] = 'Idioma alternativo #1';
$lang['idioma_03'] = 'Idioma alternativo #2';

$lang['change_language'] = 'Altera posição dos titulos PRINCIPAL e SECUNDÁRIO';

$lang['select_all'] = 'selecionar todos';
$lang['showing'] = 'mostrando';

?>
