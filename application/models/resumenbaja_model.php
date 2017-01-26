<?php
@session_start();
class Resumenbaja_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();		
	}
	
	
	function Guardar_DocumentosdeBaja($prm_cod_usu,$prm_tip_doc,$prm_ser_doc,$prm_num_doc,$prm_tip_reg,$prm_mot_baja,$prm_cod_empr,$prm_fec_doc,$prm_tip_docemisor)
	{
 
		$result['result']=0;		
		$this->load->database('ncserver',TRUE);
		
		$query="
			insert into sgr_documentosdebaja_temp
			(
			  cod_usu,
			  ser_doc,
			  num_doc,
			  tip_reg,
			  mot_baja,
			  cod_empr,
			  tip_doc,
			  fec_emision,
			  tip_docemisor
			)
			values
			(
				'".$prm_cod_usu."',
				'".$prm_ser_doc."',
				'".$prm_num_doc."',
				'".$prm_tip_reg."',
				'".$prm_mot_baja."',
				'".$prm_cod_empr."',
				'".$prm_tip_doc."',
				'".$prm_fec_doc."' ,
				'".$prm_tip_docemisor."' 
			 );";
		
		//print_r($query);		
		$this->db->query($query);
		$result['result']=1;			

		return $result;
	}	
	
	
	function Validar_DocumentosdeBaja($prm_tip_doc,$prm_num_doc,$prm_ruc_empr,$prm_cod_empr)
	{
 
		$result['result']=0;
		$result['fechadoc']='';		
		$result['tipodocumentoemisor']='';	
		$this->load->database('ncserver',TRUE);
		
		$query="select a.tipodocumento,upper(a.bl_estadoproceso) bl_estadoproceso,a.bl_fecharespuestasunat,a.tipodocumentoemisor,b.fechaemision
			from spe_einvoice_response a
				inner join spe_einvoiceheader b on a.numerodocumentoemisor=b.numerodocumentoemisor 
					and a.tipodocumentoemisor=b.tipodocumentoemisor
					and a.tipodocumento=b.tipodocumento
					and a.serienumero=b.serienumero
			where a.numerodocumentoemisor='".$prm_ruc_empr."' and a.tipodocumento='".$prm_tip_doc."' and a.serienumero='".$prm_num_doc."' ;";

		//print_r($query);
		$consulta = $this->db->query($query);		
		$resultado=$consulta->result_array();	
		if(!empty($resultado))//SI NO ES NULO O VACIO
		{
			
			
			/*
			print_r($fecha_actual);
			return;		
			*/
			$estado = substr($resultado[0]['bl_estadoproceso'], -5);	
			if ($estado!='AC_03')
			{
				$result['result']=2; //EL DOCUMENTO NO TIENE EL ESTADO ACEPTADO
				$result['fechadoc']='';	
				$result['tipodocumentoemisor']='';
				return $result;
			}
			
			$consulta = $this->db->query("select count(cod_doctmp) cantidad from sgr_documentosdebaja_temp where num_doc='".$prm_num_doc."' 
						and tip_doc='".$prm_tip_doc."' and cod_empr=".$prm_cod_empr." ");
			
			$existedocumento=$consulta->result_array();
			if ($existedocumento[0]['cantidad']>0) //no existe registrado
			{
				$result['result']=3; //EL DOCUMENTO YA ESTA EN LA LISTA
				$result['fechadoc']='';	
				$result['tipodocumentoemisor']='';
				return $result;
			}
			
			$fechadocumentotmp=substr($resultado[0]['bl_fecharespuestasunat'], 0, 10);
			$fechadocumento=explode('-',$fechadocumentotmp); 
			$fecha_actual = strtotime('now');
			$fecha_entrada = strtotime($fechadocumento[0].'/'.$fechadocumento[1].'/'.$fechadocumento[2]);

			$dias	= (( $fecha_actual-$fecha_entrada)/86400);
			$dias 	= abs($dias); 
			$dias = floor($dias);	
			
			$consulta = $this->db->query("
										select count(fec_emision) cantidad from
										(
											select b.fec_emision from
											(							
												select fec_emision from sgr_documentosdebaja_temp where cod_empr=".$prm_cod_empr."
												union all 
												select '".$resultado[0]['fechaemision']."' fec_emision 
											)b group by  b.fec_emision
										)aa"			);
			
			//print_r($consulta);
			//return;
			
			$existedocumento=$consulta->result_array();
			if ($existedocumento[0]['cantidad']>1) //no existe registrado
			{
				$result['result']=4; //EL DOCUMENTO TIENE FECHA DIFERENTE
				$result['fechadoc']='';	
				$result['tipodocumentoemisor']='';
				return $result;
			}
			
			if ($dias>7)
			{
				$result['result']=1; //LA FECHA ES SUPERIO A 7 DIAS
				//$result['fechadoc']='';	
				//$result['tipodocumentoemisor']='';
				$result['fechadoc']=$resultado[0]['fechaemision'];	
				$result['tipodocumentoemisor']=$resultado[0]['tipodocumentoemisor'];
				return $result;
			}
			
			$result['result']=5; //ACTO PARA REGISTRAR
			$result['fechadoc']=$resultado[0]['fechaemision'];	
			$result['tipodocumentoemisor']=$resultado[0]['tipodocumentoemisor'];
			return $result;
			
			
		}
		else
		{
			$result['result']=0;//NO EXISTE EL DOCUMENTO
			$result['fechadoc']='';	
			return $result;
		}
		
	}	
	
	
	function Listar_DocumentosdeBaja($prm_cod_usu,$prm_cod_empr,$cod_tipbusqueda,$prm_ruc_empr,$prm_fechabusqueda,$prm_tip_doc)
	{
	
		$this->load->database('ncserver',TRUE);
		$query="
			select cod_doctmp,
			  ser_doc,
			  num_doc,
			  tip_reg,
			  mot_baja,
			  cod_empr,
			  (select b.no_corto from tm_tabla_multiple b where b.no_tabla='TIPO_DOCUMENTO' and b.in_habilitado=1 and b.co_item_tabla=a.tip_doc) tip_doc,
			  fec_emision bl_fecharespuestasunat,
			  tip_docemisor,
			  fec_emision fechaemision
			from sgr_documentosdebaja_temp a
			where cod_usu='".$prm_cod_usu."'
				and cod_empr='".$prm_cod_empr."' ";
		
		if ($cod_tipbusqueda==1)
		{
			$query=$query."union all 

			select 
				0 cod_doctmp,
			  	'' ser_doc,
				a.serienumero num_doc,
			  	0 tip_reg,
			 	'' mot_baja,
			 	0 cod_empr,
				(select b.no_corto from tm_tabla_multiple b where b.no_tabla='TIPO_DOCUMENTO' and b.in_habilitado=1 and b.co_item_tabla=a.tipodocumento) tip_doc ,
				a.bl_fecharespuestasunat,
				a.tipodocumentoemisor tip_docemisor,
				b.fechaemision		
			from spe_einvoice_response a
				inner join spe_einvoiceheader b on a.numerodocumentoemisor=b.numerodocumentoemisor 
					and a.tipodocumentoemisor=b.tipodocumentoemisor
					and a.tipodocumento=b.tipodocumento
					and a.serienumero=b.serienumero
			where a.numerodocumentoemisor='".$prm_ruc_empr."'
				and upper(a.bl_estadoproceso)  like '%AC_03%'
				and a.serienumero like 'F%'
				and a.tipodocumento in ('01','07','08')
				and b.fechaemision='".$prm_fechabusqueda."'
				and a.tipodocumento='".$prm_tip_doc."' 
				
				and not exists(select cod_doctmp from sgr_documentosdebaja_temp aa where aa.num_doc=a.serienumero 
					and aa.tip_doc=a.tipodocumento 
					and aa.cod_empr='".$prm_cod_empr."')
				
				";
		}
		$query=$query.";";
		//print_r($query);

		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}	
	
	
	function Guardar_Specancelheader
	(
		$prm_numerodocumentoemisor ,
		$prm_resumenid,
		$prm_tipodocumentoemisor,
		$prm_correoemisor,
		$prm_fechaemisioncomprobante,
		$prm_fechageneracionresumen,
		$prm_inhabilitado,
		$prm_razonsocialemisor,
		$prm_resumentipo,
		$prm_bl_estadoregistro,
		$prm_bl_reintento,
		$prm_cod_empr,
		$prm_cod_usu
	)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);

		$this->db_client->trans_begin();	
		
		$query="
			insert into spe_cancelheader
			(
			 	numerodocumentoemisor ,
				resumenid,
				tipodocumentoemisor,
				correoemisor,
				fechaemisioncomprobante,
				fechageneracionresumen,
				inhabilitado,
				razonsocialemisor,
				resumentipo,
				bl_estadoregistro,
				bl_reintento,
				bl_origen
			)
			values
			(
				'".$prm_numerodocumentoemisor."',
				'".$prm_resumenid."',
				'".$prm_tipodocumentoemisor."',
				'".$prm_correoemisor."',
				'".$prm_fechaemisioncomprobante."',
				'".$prm_fechageneracionresumen."',
				'".$prm_inhabilitado."',
				'".$prm_razonsocialemisor."',
				'".$prm_resumentipo."',
				'".$prm_bl_estadoregistro."',
				'".$prm_bl_reintento."',
				'P'				 
			 );";
			 		
		//print_r($query);		
		$this->db_client->query($query);		
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}		
		$consulta = $this->db_client->query("select cod_doctmp,mot_baja,num_doc,tip_doc from sgr_documentosdebaja_temp where cod_empr='".$prm_cod_empr."';");				
				
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}
		$detalledocumento=$consulta->result_array();
		$contador=1;
		foreach($detalledocumento as $key=>$v):		
		
			$documento=explode('-',trim($v['num_doc']));
				
			$query="insert into spe_canceldetail
			(
				numerodocumentoemisor,
				tipodocumentoemisor,
				resumenid,
				numerofila,
				motivobaja,
				numerodocumentobaja,
				seriedocumentobaja,
				tipodocumento
			)
			values
			(
				'".$prm_numerodocumentoemisor."',
				'".$prm_tipodocumentoemisor."',
				'".$prm_resumenid."',
				'".$contador."',
				'".trim($v['mot_baja'])."',
				'".$documento[1]."',
				'".$documento[0]."',
				'".trim($v['tip_doc'])."'
			);";
			//print_r($query);
			$this->db_client->query($query);		
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				return $result;
			}				
			$contador++;				
		endforeach;
	
		$query="delete from sgr_documentosdebaja_temp where cod_empr='".$prm_cod_empr."'  and cod_usu ='".$prm_cod_usu."';";
		$this->db_client->query($query);		
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}
		
		$nuevocodigo=explode('-',$prm_resumenid);
		if (number_format($nuevocodigo[2], 2, '.', '')==1)
		{
			$query="insert into sgr_correlativoresumen(cod_empr,tip_resum,fec_resum,num_corre)values(".$prm_cod_empr.",1,'".$prm_fechageneracionresumen."',1);";
			$this->db_client->query($query);		
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				return $result;
			}
		}
		else
		{
			$query="update sgr_correlativoresumen set num_corre=num_corre+1  
					where cod_empr=".$prm_cod_empr." and tip_resum=1 and fec_resum='".$prm_fechageneracionresumen."' and est_reg=1;";
			$this->db_client->query($query);		
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				return $result;
			}
		}
		$this->db_client->trans_commit();
		$result['result']=1;			
		return $result;
	}
	
	
	
	function Eliminar_Documentosdebajatemporal($prm_cod_doctmp)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();	
		
		$query="delete from sgr_documentosdebaja_temp where cod_doctmp='".$prm_cod_doctmp."';";
		$this->db_client->query($query);		
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}

		$this->db_client->trans_commit();
		$result['result']=1;			
		return $result;
	}
	
	function Buscar_CorrelativoDocumento($prm_cod_empr,$prm_fecha)
	{
	
		$this->load->database('ncserver',TRUE);
		$query="select (num_corre+1) valorentero from sgr_correlativoresumen 
				where cod_empr=".$prm_cod_empr." and tip_resum=1 and fec_resum='".$prm_fecha."' and est_reg=1;";
				
		//print_r($query);		
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}

	function Eliminar_DocumentosdebajatemporalUsuario($prm_cod_empr,$prm_cod_usu)
	{
	
		$this->load->database('ncserver',TRUE);
		$query="delete from sgr_documentosdebaja_temp where cod_empr='".$prm_cod_empr."' and cod_usu= '".$prm_cod_usu."';";
		$this->db->query($query);
		return 1;	
	}


	function Listar_Specancelheader($prm_ruc_empr,$prm_cod_resum,$prm_fec_geninicio,
				$prm_fec_genfinal,$prm_cod_estdoc,$prm_fec_emisinicio,$prm_fec_emisfinal,$prm_bl_estadoproceso)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";
		$query="
				select
				  a.numerodocumentoemisor,
				  a.resumenid,
				  a.tipodocumentoemisor,
				  a.correoemisor,
				  a.fechaemisioncomprobante,
				  a.fechageneracionresumen,
				  a.inhabilitado,
				  a.razonsocialemisor,
				  a.resumentipo,
				  a.bl_estadoregistro,
				  (select b.no_corto 
							from tm_tabla_multiple b where b.no_tabla='ESTADO_DOCUMENTO_PORTAL' 
								and b.in_habilitado=1 
								and b.co_item_tabla=a.bl_estadoregistro) estadoregistro,
				  a.bl_reintento,
				  b.reintento,
				  b.bl_estadoproceso estadosunat,
				  b.bl_mensaje,
				  b.bl_mensajesunat,
				 b.bl_fecharespuestasunat,
				 (case when b.bl_estadoRegistro='L' and b.bl_estadoproceso='SIGNED' then 1 else 0 end) mensajeresponse
				from spe_cancelheader a
					left join spe_cancel_response b on 
									a.tipodocumentoemisor=b.tipodocumentoemisor 
									and a.numerodocumentoemisor=b.numerodocumentoemisor
									and a.resumenid=b.resumenid ";
		
		$query=$query." where a.numerodocumentoemisor='".$prm_ruc_empr."' ";
		
		if ($prm_cod_resum!='')	
		{
			$query=$query." and a.resumenid='".$prm_cod_resum."' ";
		}
		
		if ($prm_cod_estdoc!='0')	
		{
			$query=$query." and a.bl_estadoregistro='".$prm_cod_estdoc."' ";
		}
		
		if ($prm_fec_geninicio!='' && $prm_fec_genfinal!='')	
		{
			$query=$query." 
				and a.fechageneracionresumen>='".$prm_fec_geninicio."'
						and	a.fechageneracionresumen<='".$prm_fec_genfinal."' "; //+ '1 days'
		}
		
		if ($prm_fec_emisinicio!='' && $prm_fec_emisfinal!='')	
		{
			$query=$query." 
				and a.fechaemisioncomprobante>='".$prm_fec_emisinicio."'
						and	a.fechaemisioncomprobante<='".$prm_fec_emisfinal."' "; //+ '1 days'
		}
		/*
		if ($prm_bl_estadoproceso!='0')	
		{
			$query=$query." and b.bl_estadoproceso like '%".$prm_bl_estadoproceso."%' ";
		}
		*/
		
		$query=$query." order by 2;";
		
		//print_r($query);
	
		$consulta =  $this->db_client->query($query);		
		return $consulta->result_array();

	}
	
	
	function Listar_SpecancelheaderDetalle($prm_ruc_empr,$prm_resumenid)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";
		$query="
				select
					a.numerodocumentoemisor,
					a.tipodocumentoemisor,
					a.resumenid,
					a.numerofila,
					a.motivobaja,
					a.numerodocumentobaja,
					a.seriedocumentobaja,
					(select b.no_corto from tm_tabla_multiple b where b.no_tabla='TIPO_DOCUMENTO' 
						and b.in_habilitado=1 and b.co_item_tabla=a.tipodocumento) tipodocumento,
					b.fechaemisioncomprobante
				from spe_canceldetail a inner join spe_cancelheader b on a.numerodocumentoemisor=b.numerodocumentoemisor 
						and a.tipodocumentoemisor=b.tipodocumentoemisor 
						and a.resumenid=b.resumenid";
		
		$query=$query." where a.numerodocumentoemisor='".$prm_ruc_empr."' and a.resumenid='".$prm_resumenid."' ";
		$query=$query." order by a.numerofila;";
		
		//print_r($query);
		
		
		$consulta =  $this->db_client->query($query);		
		return $consulta->result_array();

	}
	
	
	function Reiniciar_Correlativos($prm_ruc_empr,$prm_documento)
	{
 		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();
		
		$lista_documento=explode(',',$prm_documento);	

		foreach($lista_documento as $key=>$v):		
		
			if (strlen($v)>3)//AVECES LLEGA VACIO
			{			 
				 $detalle_documento=explode('-',$v);
				
				//RA-20160519-4-A
				if ($detalle_documento[3]=='SIGNED')
				{
					$query="update spe_cancel_response set reintento=0 where tipodocumentoemisor='6' and numerodocumentoemisor='".$prm_ruc_empr."' 
						and resumenid='".$detalle_documento[0].'-'.$detalle_documento[1].'-'.$detalle_documento[2]."';";
				}
				else
				{
					$query="update spe_cancelheader set bl_reintento=0 where tipodocumentoemisor='6' and numerodocumentoemisor='".$prm_ruc_empr."'  
						and resumenid='".$detalle_documento[0].'-'.$detalle_documento[1].'-'.$detalle_documento[2]."';";
				}
	
				//print_r($query);	
				//return;	
				
				$this->db_client->query($query);
				if ($this->db_client->trans_status() === FALSE)
				{
					$this->db_client->trans_rollback();
					$result['result']=0;
					return $result;
				}
			}
			 		
		endforeach;
		
		$this->db_client->trans_commit();
		$result['result']=1;
		
		
		return $result;
	}
	
	function Listar_EstadoDocumento($prm_estado)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);
		
		$query="";
		$query="select no_corto from tm_tabla_multiple where no_tabla='ESTADO_RESUMENDOCUMENTO_PORTAL' and in_habilitado=1 and no_largo='".$prm_estado."';";
		
		
		$consulta =  $this->db_client->query($query);	
		$listaestado=$consulta->result_array();
		$estadodocumento='';
		
		//print_r($query);
		
		if(!empty($listaestado))//SI NO ES NULO O VACIO
		{
			$estadodocumento=$listaestado[0]['no_corto'];
		}

		return $estadodocumento;//$consulta->result_array();

	}
	
	function Listar_DetalleDocumento($prm_ruc_empremisor,$prm_serie_numero)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);
		

		$query="";
		$query="select 
										
					a.numerodocumentoemisor,
					a.resumenid,
					a.tipodocumentoemisor,
					a.correoemisor,
					a.fechaemisioncomprobante,
					a.fechageneracionresumen,
					a.inhabilitado,
					a.razonsocialemisor,
					a.resumentipo,
					a.bl_estadoregistro,
					a.bl_reintento,
					a.bl_origen,
					a.bl_hasfileresponse,
					a.bl_reintentojob,
					a.bl_sourcefile,
					a.visualizado,

					b.numerofila,
					b.tipodocumento,
					b.motivobaja,
					b.numerodocumentobaja,
					b.seriedocumentobaja,
					
					(select aa.no_corto from tm_tabla_multiple aa
						where aa.no_tabla='TIPO_DOCUMENTO' and aa.in_habilitado=1 
							and aa.co_item_tabla in('01','03','07','08') and b.tipodocumento=aa.co_item_tabla ) nombre_tipodocumento,

					c.bl_estadoproceso estadosunat

					from spe_cancelheader a 
						inner join spe_canceldetail b on 
							a.tipodocumentoemisor=b.tipodocumentoemisor and
							a.numerodocumentoemisor=b.numerodocumentoemisor and 
							a.resumenid=b.resumenid 
						left join spe_cancel_response c on
							a.tipodocumentoemisor=c.tipodocumentoemisor and
							a.numerodocumentoemisor=c.numerodocumentoemisor and 
							a.resumenid=c.resumenid 
					where a.tipodocumentoemisor='6' and a.numerodocumentoemisor='".$prm_ruc_empremisor."' and a.resumenid='".$prm_serie_numero."'
					order by a.resumenid,b.numerofila;";
					
		//print_r($query);
		//return;
					
		$consulta =  $this->db_client->query($query);		
		return $consulta->result_array();

	}
	
	function Listar_ErrorDocumento($prm_numerodocumentoemisor,$prm_tipodocumentoemisor,$prm_resumenid)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";
		$query="select codigoerror,descripcionerror from spe_error_log 
			where 
				tipodocumentoemisor='".$prm_tipodocumentoemisor."'
				and numerodocumentoemisor='".$prm_numerodocumentoemisor."'
				and resumenid= '".$prm_resumenid."' order by fecharegistro desc";

		//print_r($query);
		//return;					
		$consulta =  $this->db_client->query($query);		
		return $consulta->result_array();
	}	
	
}