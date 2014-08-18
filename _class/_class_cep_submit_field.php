<?php
class cep_submit_field
{
	var $id_sub;
	var $sub_pos;
	var $sub_field;
	var $sub_css;
	var $sub_descricao;
	var $sub_ativo;
	var $sub_codigo;
	var $sub_pag;
	var $sub_obrigatorio;
	var $sub_editavel;
	var $sub_informacao;
	var $sub_projeto_tipo;
	var $sub_ordem;
	var $sub_pdf_title;
	var $sub_pdf_mostra;
	var $sub_pdf_align;
	var $sub_pdf_font_size;
	var $sub_pdf_space;
	var $sub_limite;
	var $sub_caption;
	var $sub_id;
	
	var $tabela = 'cep_submit_field';

	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_sub','',False,True));
			array_push($cp,array('$[1-9]','sub_pag',msg('pag'),False,True));
			array_push($cp,array('$[1-50]','sub_ordem',msg('ord'),False,True));
			array_push($cp,array('$[1-40]','sub_pos',msg('position'),False,True));
			array_push($cp,array('$T60:4','sub_field',msg('field'),False,True));

			array_push($cp,array('$S20','sub_descricao',msg('name'),False,True));

			array_push($cp,array('$H8','sub_css','',False,True));
			array_push($cp,array('$O 1:'.msg('YES').'&0:'.msg('NO'),'sub_ativo',msg('active'),False,True));
			array_push($cp,array('$H8','sub_codigo','',False,True));
			array_push($cp,array('$O 1:'.msg('YES').'&0:'.msg('NO'),'sub_obrigatorio',msg('obrigatorio'),False,True));
			array_push($cp,array('$O 1:'.msg('YES').'&0:'.msg('NO'),'sub_editavel',msg('edit'),False,True));
			//array_push($cp,array('$T40:4','sub_informacao',msg('information'),False,True));
			
			$sq = 'select * from cep_submit_tipo where sp_ativo=1 and sp_codigo like '.chr(39).'%'.$this->sub_projeto_tipo.'%'.chr(39);
			
			array_push($cp,array('$Q sp_descricao:sp_codigo:'.$sq,'sub_projeto_tipo',msg('type'),False,True));
			
			array_push($cp,array('${','',' '.msg('pdf').' ',False,True));
			array_push($cp,array('$O 1:'.msg('YES').'&0:'.msg('NO'),'sub_pdf_mostra',msg('pdf_screen'),False,True));
			array_push($cp,array('$S50','sub_pdf_title',msg('title_pdg'),False,True));
			array_push($cp,array('$O left:'.msg('left').'&right:'.msg('right').'&justify:'.msg('justify'),'sub_pdf_align',msg('align'),False,True));
			array_push($cp,array('$[8-20]','sub_pdf_font_size',msg('font_size'),False,True));
			array_push($cp,array('$[2-10]','sub_pdf_space',msg('font_space'),False,True));
			array_push($cp,array('$S10','sub_limite',msg('limit'),False,True));
			array_push($cp,array('$S40','sub_caption',msg('caption'),False,True));
			array_push($cp,array('$}','','',False,True));
			array_push($cp,array('$S7','sub_id',msg('id'),False,True));
			return($cp);
		}
	function fields($id='',$cp,$page,$prot='')
		{
			global $protocol;
			if (strlen($id) > 0)
				{ $this->sub_projeto_tipo = $id; }
				
			$cps = 'sub_field, sub_descricao, sub_codigo, sub_obrigatorio, sub_editavel, sub_informacao, sdd_content, sub_id';
			$sql = 'select '.$cps.' from cep_submit_field ';
			$sql .= " left join cep_submit_documento_dados on (sdd_field = sub_codigo) and (sdd_protocol='".$protocol."') ";
			$sql .= " where sub_projeto_tipo='".$this->sub_projeto_tipo."'";
			$sql .= " and sub_pag = ".round($page);
			$sql .= " order by sub_pag, sub_ordem, sub_pos ";
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					$obrig = $line['sub_obrigatorio'];
					$edita = $line['sub_editavel'];
					$conte = $line['sdd_content'];
					$ref   = uppercasesql($line['sub_id']);
					
					if (strlen($conte)==0) { $conte = ''; }
					array_push($cp, array(
						trim($line['sub_field']),
						trim($line['sub_codigo']),
						trim($line['sub_descricao']),
						trim($line['sub_informacao']),
						$obrig,
						$edita,
						$conte,
						$ref
					));
				}
				return($cp);
		}
	function updatex()
		{
			$sql = "update ".$this->tabela;
			$sql .= " set sub_codigo = lpad(id_sub,5,0) where sub_codigo = '' ";
			$rlt = db_query($sql);
			return(1);
		}
}
?>
