<?
    /**
     * Gerar visualização de pesquisador
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com>
     * @version v0.11.14;
	 * @package DIR-IS
     */
	$eqbp = trim($line['di_eq_bp']);
	$ban = trim($line['dir_nascionalidade']);
	$lattes = trim($line['dis_lattes']);
	$lattes = troca($lattes,'visualizacv.jsp','visualizacv.do'); 
		
	$nome = trim($line['dis_nome']);
	$img = trim($line['dis_foto_url']);
	if (strlen($img) == 0) 
		{ 
		// http://buscatextual.cnpq.br/buscatextual/visualizacv.do?id=K4791249J9
		// http://buscatextual/servletrecuperafoto?id=K4791249J9
		$img = troca($lattes,'visualizacv.jsp','servletrecuperafoto'); 
		$img = troca($lattes,'visualizacv.do','servletrecuperafoto'); 
		}
	
	if (strlen($img) == 0) { $img = $img_path."dir-is/img_photo/".strzero($dd[0],7).'.jpg'; }
	$jpg = trim($line['dis_codigo']);
	$jpg = trim($line['dis_foto_url']);

	$sx .= '<TR valign="top">';
	$sx .= '<TD rowspan="50" width="20%" align="center"><img src="'.$img.'" height="200" alt="" border="1"><BR>(foto)';
	$sx .= '<BR><BR>';
	$sx .= '<font class="lt0">'.$img.'</font>';
	$sx .= '<BR><BR>';
	$sx .= $line['dis_codigo'];
	$sx .= '</TD>';
	$sx .= '<TD colspan="2" class="lt3	">'.$nome.'</TD>';
	$sx .= '<TD rowspan="2"><img src="'.$img_path.'dir-is/flag_'.$ban.'.png" width="32" height="32" alt="" border="0"></TD></TR>';

	$sx .= '<TR>';
	$sx .= '<TD width="20%" align="right">Genero';
	$sx .= '<TD width="60%"><B>'.trim($line['dis_genero']).'</TD></TR>';

	$sx .= '<tr><TH colspan="2"><font class="lt2">Afiliação Institucional</font></TH></tr>';
	$sx .= '<TR>';
	$sx .= '<TD align="right">Instituíção';
	$sx .= '<TD><B>'.trim($line['dis_instituicao']).'</TD></TR>';
	$sx .= '<TR>';
	$sx .= '<TD align="right">Endereço';
	$sx .= '<TD><B>'.trim($line['dis_endereco']).'</TD></TR>';
	$sx .= '<TR>';
	$sx .= '<TD align="right">Bairro';
	$sx .= '<TD><B>'.trim($line['dis_bairro']).'</TD></TR>';
	$sx .= '<TR>';
	$sx .= '<TD align="right">Cidade';
	$sx .= '<TD><B>'.trim($line['dis_cidade']).'-'.trim($line['dir_nascionalidade']).'</TD></TR>';


	$sx .= '<tr><TH colspan="2"><font class="lt2">Formas de contato</font></TH></tr>';
	$sx .= '<TR>';
	$sx .= '<TD align="right">Telefone';
	$sx .= '<TD><B>'.trim($line['dis_telefone']).'</TD></TR>';
	$sx .= '<TR>';
	$sx .= '<TD align="right">e-mail';
	$sx .= '<TD><B>'.trim($line['dis_email']).'</TD></TR>';
	$sx .= '<TR>';
	$sx .= '<TD align="right">e-mail (alternativo)</TD>';
	$sx .= '<TD><B>'.trim($line['dis_email_alt']).'</TD></TR>';
	$sx .= '<TR>';
	$sx .= '<TD align="right">Endereço Skype</TD>';
	$sx .= '<TD><B>'.trim($line['dis_skype']).'</TD></TR>';
	$sx .= '<TR>';
	$sx .= '<TD align="right">Site pessoal</TD>';
	$sx .= '<TD><B>'.trim($line['dis_url']).'</TD></TR>';
	$sx .= '<TR>';
	$sx .= '<TD align="right">Site da instituição</TD>';
	$sx .= '<TD><B>'.trim($line['dis_ulr_inst']).'</TD></TR>';
	
	/** Formação academica **/
	$sf = '';
	$frm = array('Graduação','Mestrado','Doutorado','Pós-Doutorado');
	for ($rn=0;$rn < count($frm);$rn++)
		{
		$tp = trim($line['dis_forma_'.($rn+1)]);
		
		if ((substr($tp,0,1) != '0') and (strlen($tp) > 0))
			{
			$fsql = "select * from ajax_areadoconhecimento where a_cnpq = '".$tp."' ";
			$frlt = db_query($fsql);
			$fline = db_read($frlt);
			$tpf = trim($fline['a_descricao']);
			$sf .= '<TR>';
			$sf .= '<TD align="right"><i>'.$frm[$rn].'</i>';
			$sf .= '<TD><B>'.$tpf.'('.trim($line['dis_forma_'.($rn+1).'_ano']).')</TD></TR>';
			}
		}
	if (strlen($sf) > 0)
		{
		$sx .= '<tr><TH colspan="2"><font class="lt2">Formação Acadêmica</font></TH></tr>';
		$sx .= $sf;
		}
	/** Palavras-chave **/

	$sx .= '<TR>';
	$sx .= '<TD align="right">Palavras-chave:';
	$sx .= '<TD><B>'.trim($line['dis_key_1']).'</TD></TR>';
	$sx .= '<TR>';
	$sx .= '<TD>';
	$sx .= '<TD><B>'.trim($line['dis_key_2']).'</TD></TR>';
	$sx .= '<TR>';
	$sx .= '<TD>';
	$sx .= '<TD><B>'.trim($line['dis_key_3']).'</TD></TR>';
	$sx .= '<TR>';
	$sx .= '<TD>';
	$sx .= '<TD><B>'.trim($line['dis_key_4']).'</TD></TR>';
	$sx .= '<TR>';
	$sx .= '<TD>';
	$sx .= '<TD><B>'.trim($line['dis_key_5']).'</TD></TR>';

	$link = '<A HREF="'.$lattes.'" target="lattes">';
	$sx .= '<TR>';
	$sx .= '<TD align="right">Lattes (CNPq):';
	$sx .= '<TD><B>'.$link.trim($lattes).'</TD></TR>';

	$sx .= '<TR>';
	$sx .= '<TD align="right">Direitos reservados / fonte:</TD>';
	$sx .= '<TD><B>'.trim($line['dis_rights']).'</TD></TR>';

	$sx .= '<TR>';
	$sx .= '<TD align="right">atualizado em';
	$sx .= '<TD>'.stodbr($line['dis_update']).'</TD></TR>';
?>