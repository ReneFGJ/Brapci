<?
global $ed_editar;
$sx .= '<B>SUPORTES</B>';
if ($ed_editar == 1) 
	{ 
	$linke = 'onclick="newxy2('.chr(39).'brapci_suporte_ed.php?dd1='.$cod.'&dd2=&dd3='.$journal.'&dd0=&dd10='.$key.chr(39).',640,150);"';
	$sa1 = '<img src="img/icone_editar.gif" width="20" height="19" alt="Novo Suporte" border="0" '.$linke.' >';
	$sx .= tips($sa1,'Inserir novo endereço http para este artigo');
	
	$chksun = md5($cod.'448545');
	$linke = 'onclick="newxy2('.chr(39).'brapci_suporte_up.php?dd0='.$cod.'&dd3='.$journal.'&dd2='.$chksun.chr(39).',640,250);"';
	$sa1 = '<img src="img/icone_upload.png" width="20" height="19" title="Fazer upload de arquivo para BRAPCI" alt="Novo Suporte" border="0" '.$linke.' >';
	$sx .= tips($sa1,'Enviar um arquivo para PDF para a base BRAPCI vinculado a este artigo');
	$x .= 'UPLOAD';
	}
$sx .= '<BR>';

$sql = "select * from brapci_article_suporte where bs_article = '".$cod."' and bs_status <> 'X' ";
$frlt = db_query($sql);
$sx .= '<TABLE width="100¨%">';
while ($fline = db_read($frlt))
	{
	$id_sel = $fline['id_bs'];
	$link = '';
	$adress = trim($fline['bs_adress']);
	$adtype = $fline['bs_type'];
	$adstat = $fline['bs_status'];
	$update = $fline['bs_update'];
	if ($adtype == 'URL')
		{
		$link = '<A HREF="'.$adress.'" target="_new'.date("Ymdhis").'">';
		}

	if ($adtype == 'PDF')
		{
		$link = '<A HREF="javascript:newwin2('.chr(39).'download.php?dd0='.$id_sel.chr(39).',100,100);" >';
		}

	$linke = 'onclick="newxy2('.chr(39).'brapci_suporte_ed.php?dd1='.$cod.'&dd2='.$idioma_sel.'&dd3='.$journal.'&dd0='.$id_sel.'&dd10='.$key.chr(39).',640,150);"';
	$linkd = 'onclick="newxy2('.chr(39).'brapci_suporte_ed.php?dd1='.$cod.'&dd2='.$idioma_sel.'&dd3='.$journal.'&dd0='.$id_sel.'&dd10='.$key.chr(39).',640,150);"';
	
	$sx .= '<TR><TD>';
	$sx .= $adtype;
	$sx .= '<TD>';
	if ($ed_editar == 1) 
	{ 
		$sa1 = '<img src="img/icone_editar.gif" width="20" height="19" alt="" border="0" '.$linke.' >';
		$sx .= tips($sa1,'Alterar o conteúdo deste link');
		$sa1 = '<img src="img/icone_del.gif" alt="" border="0" '.$linkd.' >';
		$sx .= tips($sa1,'Excluir este link');
	}
	$sx .= '<TD>';
	if (strlen(trim($adress)) > 50)
		{
		$adress = substr($adress,0,25).'...'.substr($adress,strlen($adress)-25,25);
		}
	$sx .= $link.$adress;
	$sx .= '</A>';
	$sx .= '<TD align="center">';
	$sx .= stodbr($update);
	$sx .= '</TD></TR>';
	}
$sx .= '</TABLE>';
$sx .= '<BR><BR>';
?>