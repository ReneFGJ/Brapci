<?
global $ed_editar;
$sc = '';
$sql = "select * from brapci_article_author ";
$sql .= " inner join brapci_autor on autor_codigo = ae_author ";
$sql .= " left join instituicoes on inst_codigo = ae_instituicao ";
$sql .= " where ae_article = '".$cod."' ";
$sql .= " order by ae_pos ";
$arlt = db_query($sql);
$idx = 0;
$sx .= '<B>Produções</B> ';
if ($ed_editar == 1) 
	{ 	
	$link = 'onclick="newxy2('.chr(39).'brapci_autores_ed.php?dd1='.$cod.'&dd2='.$idioma_sel.'&dd3='.$id_sel.'&dd10='.$key.chr(39).',940,540);"';
	$sx .= '<img src="img/icone_editar.gif" width="20" height="19" alt="" '.$link.'>';
	}
$sx .= '<BR>';

while ($aline = db_read($arlt))
	{
	$email = trim($aline['ae_contact']);
	$bio   = trim($aline['ae_bio']);
	$autor = trim($aline['ae_author']);
	$key = $aline['id_ae'];
	$id  = $aline['id_ae'];
	
	$mst = $aline['ae_mestrado'];
	$dra = $aline['ae_doutorado'];
	$ss  = $aline['ae_ss'];
	$est  = $aline['ae_aluno'];
	$prf  = $aline['ae_professor'];	
	
	$instituicao = trim($aline['inst_nome']);
	$inst_abreviatura = trim($aline['inst_abreviatura']);
	if (strlen($inst_abreviatura) > 0) { $instituicao .= ' ('.$inst_abreviatura.')'; }
	
if ($ed_editar == 1) 
	{
	$link = '<A HREF="#" title="editar dados do autor" onclick="newxy2('.chr(39).'brapci_autores_ed.php?dd0='.$id.'&dd1='.$cod.'&dd2='.$idioma_sel.'&dd3='.$autor.'&dd10='.$key.chr(39).',720,450);">';
	$linkd = '<A HREF="#" title="excluir autor" onclick="newxy2('.chr(39).'brapci_autores_del.php?dd0='.$id.'&dd1='.$cod.'&dd2=DEL&dd3='.$autor.'&dd10='.$key.chr(39).',720,450);">';
	}
	
	$ad = '';
	if ($dra == '1') { $ad .= '<img src="img/icone_dr.png" title="Doutorado/Doutorando" alt="Doutorado/Doutorando" border="0">'; }
	if ($mst == '1') { $ad .= '<img src="img/icone_mestre.png" title="Mestrado/Mestrando" alt="Mestrado/Mestrando" border="0">'; }
	if ($est == '1') { $ad .= '<img src="img/icone_estudante.png" title="Estudante/Discente" alt="Estudante/Discente" border="0">'; }
	if ($prf == '1') { $ad .= '<img src="img/icone_professor.png" title="Professor/Docente" alt="Professor/Docente" border="0">'; }
	if ($ss == '1') { $ad .= '<img src="img/icone_ss.png" title="Prograama Stricto Sensus" alt="Prograama Stricto Sensus" border="0">'; }
	
	$sx .= $aline['autor_nome_citacao'];
	$sx .= ' '.$ad;
	$sx .= ' <SUP>['.chr(65+$idx).'</A>]</SUP>';
if ($ed_editar == 1) 
	{	
	$sx .= $link.'<img src="img/icone_editar.gif" width="20" height="19" alt="" border="0"></A>';
	$sx .= $linkd.'<img src="img/icone_del.gif" alt="excluir autor" border="0" ></A>';
	}
	$sx .= '<BR>';
	if (strlen($instituicao) > 0)
		{
		$sx .= '<font class="lt0">'.$instituicao.'</font><BR>';
		}
	/////////////////////////////////////////////////////////////
	if (strlen($bio) > 0)
		{ $sc .= '<BR><SUP>['.chr(65+$idx).']</SUP> '.$bio; }
	if (strlen($email) > 0)
		{ $sc .= ' e-mail '.$email; }
	$idx++;
	}
?>