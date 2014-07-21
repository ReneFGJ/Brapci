<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       20/05/2008 //
///////////////////////////////////////////
?>
<link rel="stylesheet" href="ic_letras.css" type="text/css" />
<?
/* Rene F. Gabriel 
Versão: 2.2.0a
Atualizado em 13/04/2008
*/
function ic_nts($ini,$fim,$secao,$limit)
	{
	$nts = array();
	$sql = "select * from ic_noticia ";
	$sql = $sql . "inner join ic_secao on nw_secao = id_s ";
	$sql = $sql . "where ";
//	$sql = $sql . " (s_ativo = 'S') ";
//	$sql = $sql . "and ((nw_dt_ate >= ".date("Ymd").') and (nw_dt_de <= '.date("Ymd").' )) ';
	$sql = $sql . "  nw_secao = ".$secao." ";
	$sql = $sql . " order by nw_dt_de desc ";
	$sql .= " limit ".$limit;
	$rlt = db_query($sql);
	$ss="";
	while ($line = db_read($rlt))
		{
		$titulo = trim($line['nw_titulo']);
		$descricao = $line['nw_descricao'];
		if (strpos($descricao,'.') > 0)
			{ $descricao = substr($descricao,0,strpos($descricao,'.')); }
			
		$data = $line['nw_dt_de'];
		array_push($nts,array($data,$titulo,$descricao,$line['id_nw']));
		}
	return($nts);
	}

function ic_pagina($id_secao)
	{
	$sql = "select * from ic_noticia ";
	$sql = $sql . " left join ic_imagem on id_nw = img_evento ";
	$sql = $sql . " where (nw_ativo=1) and (nw_secao=".$id_secao.") ";
	$sql = $sql . "and (nw_dt_de <= ". date("Ymd") . ") ";
	$sql = $sql . "and (nw_dt_ate >= ". date("Ymd") . ") ";

	$ini=0;
	$rlt = db_query($sql);
	$rr = "";
	$idx = -1;
	while ($line = db_read($rlt))
		{
		$link = '<A HREF="noticia.php?dd0='.$line['id_nw'].'">';
		$id = $line['id_nw'];
		if ($idx != $id) 
			{
			$idx = $id;
			$img = trim($line['img_arquivo']);
			if (strlen($img) > 0)
				{
				$img = substr($img,0,strlen($img)-4).'_mini.jpeg';
				$img = '<img src="/img/ic/'.$img.'">';
				}
			$rr = $rr . '<H2>'.$line['nw_titulo'].chr(13).chr(10).'</H4><BR>';
			$rr = $rr . '<P>'.mst($line['nw_descricao']).chr(13).chr(10).'</P><BR>';
			$rr = $rr . $img;
			}
		}
	return($rr);
	}
	
function ic_titulo($ini,$fim)
	{
	global $ic_cab;
	$sql = "select * from ic_noticia ";
	$sql = $sql . "inner join ic_secao on nw_secao = id_s ";
	$sql = $sql . "where (s_ativo >= ".$ini.' and s_ativo <= '.$fim.') ';
	$sql = $sql . "and ((nw_dt_ate >= ".date("Ymd").') and (nw_dt_de <= '.date("Ymd").' )) ';
	$sql = $sql . " order by s_ativo, nw_dt_de desc ";
	$rlt = db_query($sql);
	$ss="<font class=lt0 >";
	$sc=-1;
	while ($line = db_read($rlt))
		{
		$link = '<A HREF="noticia.php?dd0='.$line['id_nw'].'">';
		$seccao = $line['id_s'];
		if ($seccao != $sc)
			{
			$ss = $ss . $ic_cab.' <font class="lt1"><B>'.$line['s_titulo'].'</B></font><BR>';
			$sc = $seccao;
			}
		$titulo = trim($line['nw_titulo']);
		if ($line['s_ativo'] >= 20)
			{
			$data = '&nbsp;';
			} else {
			if ($line['s_ativo'] >= 10)
				{
				$data = '<font color="#808080">'.substr(stodbr($line['nw_dt_ate']),0,5).'</font>&nbsp;';
				} else {
				$data = '<font color="#808080">'.substr(stodbr($line['nw_dt_de']),0,5).'</font>&nbsp;';
				}
			}
		$ss=$ss. $data.$link.'<font class=lt0 >'.$titulo.'</A></font>';
		$ss=$ss.'<BR>';
		}
	return($ss);
	}
	
function ic_destaque($ini,$fim)
	{
	$ss='';
	$sql = "select * from ic_noticia ";
	$sql = $sql . " inner join ic_imagem on img_evento = id_nw ";
	$sql = $sql . " where (nw_dt_de <= ".date("Ymd").') and (nw_dt_ate >= '.date("Ymd").' )';
	$sql = $sql . " order by nw_dt_ate ";
	$sql = $sql . " limit 1 ";
	$rlt = db_query($sql);
	if ($line = db_read($rlt))
		{
		$link = trim($line['nw_link']);
		if (strlen($link) > 0)
			{
			$link = '<A HREF="'.$link.'" target="new">';
			}
		$img = trim($line['img_arquivo']);
		$img = substr($img,0,strlen($img)-4).'_mini.jpeg';
		$img_title = $line['img_titulo'];
		$ss= $ss . '<TR valign="top">';
		$ss= $ss .  '<TD>'.$link.'<img src="img/ic/'.$img.'" width="200" alt="" border="0"></TD>';
		$ss= $ss .  '<TR  bgcolor="#C0C0C0"><TD height="10"></TD>';
		$ss= $ss .  '<TR  bgcolor="#C0C0C0">';
		$ss= $ss .  '<TD align="center">'.$link.$img_title.'</TD></TR>';
		$ss= $ss .  '<TR bgcolor="#C0C0C0"><TD height="10"></TD>';
		}
	return($ss);
	}
	
function ic_resumo($ini,$fim)
	{
	$sql = "select * from ic_noticia ";
	$sql = $sql . "inner join ic_secao on nw_secao = id_s ";
	$sql = $sql . "where (s_ativo >= ".$ini.' and s_ativo <= '.$fim.') ';
	$sql = $sql . "and ((nw_dt_ate >= ".date("Ymd").') and (nw_dt_de <= '.date("Ymd").' )) ';
	$sql = $sql . " order by nw_dt_de desc ";
	$rlt = db_query($sql);
	$ss="";
	while ($line = db_read($rlt))
		{
		$titulo = trim($line['nw_titulo']);
		$descricao = $line['nw_descricao'];
		$data = $line['nw_dt_de'];
		$ss=$ss. '<font class="h1">'.$titulo.'</font><BR>';
		$ss=$ss .'<font class=lt0><I>'.stodbr($data).'</I></font><P>';
		$ss=$ss .'<div align="justify" class="lt0">';
		$ss=$ss.mst($descricao);
		$ss=$ss.'</div>';
		$ss=$ss.'<P>';
		}
	return($ss);
	}

function ic_menu($ini,$fim)
	{
	$sql = "select * from ic_secao where ";
	$sql = $sql . "s_ativo >= ".$ini.' and s_ativo <= '.$fim.' order by s_ativo';
	$menu = array();
	$rlt = db_query($sql);
	while ($line = db_read($rlt))
		{
		array_push($menu,array(trim($line['s_titulo']),$line['id_s'],$line['s_ativo']));
		}
	return($menu);
	}
function ic_news($id_secao)
	{
	$sql = "select * from ic_noticia ";
	$sql = $sql . " left join ic_imagem on id_nw = img_evento ";
	$sql = $sql . " where (nw_ativo=1) and (nw_secao=".$id_secao.") ";
	$sql = $sql . "and (nw_dt_de <= ". date("Ymd") . ") ";
	$sql = $sql . "and (nw_dt_ate >= ". date("Ymd") . ") ";

	$ini=0;
	$rlt = db_query($sql);
	$rr = "";
	$idx = -1;
	while ($line = db_read($rlt))
		{
		$link = '<A HREF="noticia.php?dd0='.$line['id_nw'].'">';
		$id = $line['id_nw'];
		if ($idx != $id) 
			{
			$idx = $id;
			$img = trim($line['img_arquivo']);
			if (strlen($img) > 0)
				{
				$img = substr($img,0,strlen($img)-4).'_mini.jpeg';
				$img = '<img src="/img/ic/'.$img.'">';
				}
			$rr = $rr . $link.$line['nw_titulo'].chr(13).chr(10).'<BR>';
			$rr = $rr . $img;
			$rr = $rr . '<HR>';
			}
		}
	return($rr);
	}
	
function ic_mst($id)
	{
	$sql = "select * from ic_noticia where id_nw = ".$id;
	$rlt = db_query($sql);
	
	if ($line = db_read($rlt))
		{
		$titulo = $line['nw_titulo'];
		$descricao = $line['nw_descricao'];
		$thema     = $line['nw_thema'];
		$data     = $line['nw_dt_de'];
		$link     = trim($line['nw_link']);
		$fonte     = trim($line['nw_fonte']);
		}
		
	IF (strlen($thema) > 0)
		{
		$sql = "select * from ic_evento_tema where thema_codigo='".$thema."'";
		$rlt = db_query($sql);
		
		if ($line = db_read($rlt))
			{
			$thema_cab 			= $line['thema_cab'];
			$thema_foot			= $line['thema_foot'];
			$thema_img_top		= $line['thema_img_top'];
			$thema_img_botton	= $line['thema_img_botton'];
			$thema_table_start	= $line['thema_table_start'];
			$thema_table_end	= $line['thema_table_end'];
			$thema_table_tr		= $line['thema_table_tr'];
			$thema_ativo		= $line['thema_ativo'];
			$thema_img_col		= $line['thema_img_col'];
			}
		}
	$sql = "select * from ic_imagem where img_evento = ".$id;
	$rlt = db_query($sql);
	$rcol = 99;
	$rst = "";
	while ($line = db_read($rlt))
		{
		if ($rcol > $thema_img_col)
			{
			$rst = $rst . $thema_table_tr;
			$rcol = 0;
			}
		$rst = $rst . $thema_table_td;
		$img = trim($line['img_arquivo']);
		if (strlen($img) > 0)
			{
			$img = substr($img,0,strlen($img)-4).'_mini.jpeg';
			$img = '<img src="/img/ic/'.$img.'" align="left" class="img2">';
			}
		$rst = $rst . $img;
		}
	$rst = $rst . '<font class="h1">'.$titulo.'</font>';
	$rst = $rst . '<BR><font class="lt0"><i>'.stodbr($data).'</i></font>';
	if (strlen($fonte) > 0)
		{ $rst = $rst . '&nbsp;&nbsp;<font class="lt0"><font color="#ff5300">fonte: '.$fonte.'</font></font><BR>'; }
	$rst = $rst . '<BR><div align="justify">'.mst($descricao).'</div>';
	if (strlen($link) > 0)
		{
		$rst = $rst . '<P>Link: <A HREF="'.$link.'" target="new">'.$link.'</A>';
		}
	return($rst);
	}
?>