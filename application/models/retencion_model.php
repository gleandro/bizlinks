<?php
@session_start();
class Retencion_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();		
	}
	
	function Listar_Retenciones($prm_ruc_empr,$prm_documento_cliente, $prm_serie_numeroinicio,
								$prm_serie_numerofinal,$prm_cod_estdoc, $prm_fec_emisinicio,$prm_fec_emisfinal,
								$prm_estado_documentosunat,$prm_razonsocialcliente)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);
		
		$result['result']=0;		
		$rol_usuario=$_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion'];
		if ($rol_usuario==1) //EMISOR
		{
			$query="";
			$query="select a.tipodocumentoemisor, a.tipodocumento, a.numerodocumentoemisor, a.serienumeroretencion,
						a.numerodocumentoproveedor, a.razonsocialproveedor, a.importetotalpagado, a.tipomonedatotalpagado, 
						(select b.nombre from sgr_multitabla b where b.grupo_nombre='TIPO_MONEDA' and b.activo=1 
								and b.grupo_id=5 and b.valorcadena=a.tipomonedatotalpagado) tipomonedapagado,
						a.importetotalretenido, a.tipomonedatotalretenido, 
						(select b.nombre from sgr_multitabla b where b.grupo_nombre='TIPO_MONEDA' and b.activo=1 
								and b.grupo_id=5 and b.valorcadena=a.tipomonedatotalretenido) tipomonedaretenido,
						a.fechaemision, a.bl_estadoregistro, 
						(select b.no_corto from tm_tabla_multiple b where b.no_tabla='ESTADO_DOCUMENTO_PORTAL' 
							and b.in_habilitado=1 and b.co_item_tabla=a.bl_estadoregistro) estadoregistro,
						a.bl_reintento, b.reintento, b.bl_estadoproceso estadosunat,	
						b.bl_mensaje, b.bl_mensajesunat,
						(case when b.bl_estadoRegistro='L' and b.bl_estadoproceso='SIGNED' then 1 else 0 end) mensajeresponse,
						a.visualizado
					from spe_retention a
						left join spe_retention_response b on
								a.tipodocumentoemisor=b.tipodocumentoemisor 
								and a.numerodocumentoemisor=b.numerodocumentoemisor
								and a.serieNumeroRetencion=b.serieNumeroRetencion
								and a.tipoDocumento=b.tipoDocumento
					";
			$query=$query." where a.tipoDocumentoEmisor='6' and a.tipoDocumento='20' and a.numerodocumentoemisor='".$prm_ruc_empr."' ";
			if ($prm_documento_cliente!='')	
			{
				$query=$query." and a.numerodocumentoproveedor='".$prm_documento_cliente."' ";
			}
			if ($prm_serie_numeroinicio!='' && $prm_serie_numerofinal!='')	
			{
				$query=$query." and a.serieNumeroRetencion>='".$prm_serie_numeroinicio."' and a.serieNumeroRetencion<='".$prm_serie_numerofinal."' ";
			}
			if ($prm_cod_estdoc!='0')	
			{
				$query=$query." and a.bl_estadoregistro='".$prm_cod_estdoc."' ";
			}
			if ($prm_fec_emisinicio!='' && $prm_fec_emisfinal!='')	
			{
				$query=$query." and a.fechaemision>='".$prm_fec_emisinicio."'
							and	a.fechaemision<='".$prm_fec_emisfinal."' "; 
			}
			$query=$query." order by fechaemision;";
			
		}else{
			if ($rol_usuario==2) //RECEPTOR
			{
				$query="";
			}
		}
		//print_r($query);
		$consulta =  $this->db_client->query($query);		
		return $consulta->result_array();
	}
	
	function Listar_ErrorDocumento($prm_numerodocumentoemisor,$prm_tipodocumentoemisor,$prm_tipodedocumento,$prm_serienumero)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";
		$query="select codigoerror,descripcionerror from spe_error_log 
			where 
				tipodocumentoemisor='".$prm_tipodocumentoemisor."'
				and numerodocumentoemisor='".$prm_numerodocumentoemisor."'
				and tipodocumento= '".$prm_tipodedocumento."'
				and serienumero= '".$prm_serienumero."' order by fecharegistro desc";
		$consulta =  $this->db_client->query($query);		
		return $consulta->result_array();
	}
	
	function Actualizar_VistaDocumento($prm_ruc_empr,$prm_tipodocumento,$prm_serienumero)
	{
 		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$query="update spe_retention set visualizado=1 where tipodocumentoemisor='6' and numerodocumentoemisor='".$prm_ruc_empr."' 
						and tipodocumento='".$prm_tipodocumento."' and serienumeroretencion='".$prm_serienumero."' and visualizado=0;";
		
		$this->db_client->query($query);
		$result['result']=1;
		return $result;
	}
	
	function Listar_DetalleDocumento($prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);
		$query="";
		$query="select a.tipodocumentoemisor, a.numerodocumentoemisor, 
					(case 	when a.tipodocumentoemisor='0' then 'Doc.Trib.No.Dom.Sin.Ruc'
							when a.tipodocumentoemisor='1' then 'DNI'
							when a.tipodocumentoemisor='4' then 'Carnet de Extranjeria'
							when a.tipodocumentoemisor='6' then 'RUC'
							when a.tipodocumentoemisor='7' then 'Pasaporte'
							when a.tipodocumentoemisor='A' then 'Ced. Diplomatica de Identidad' end
					)nombre_tipodocumentoemisor,
					a.nombrecomercialemisor, a.razonsocialemisor,
					a.direccionemisor, a.urbanizacionemisor, a.distritoemisor,
					a.provinciaemisor,a.departamentoemisor,
					a.tipodocumento, 'COMPROBANTE DE RETENCION' nombre_tipodocumento,
					a.serienumeroretencion,	a.numerodocumentoproveedor, a.razonsocialproveedor, 
					a.direccionproveedor, a.fechaemision, a.tasaretencion, 
					a.importetotalpagado, a.tipomonedatotalpagado, 
					(select b.nombre from sgr_multitabla b where b.grupo_nombre='TIPO_MONEDA' and b.activo=1 
							and b.grupo_id=5 and b.valorcadena=a.tipomonedatotalpagado) tipomonedapagado,
					a.importetotalretenido, a.tipomonedatotalretenido, 
					(select b.nombre from sgr_multitabla b where b.grupo_nombre='TIPO_MONEDA' and b.activo=1 
							and b.grupo_id=5 and b.valorcadena=a.tipomonedatotalretenido) tipomonedaretenido,
					a.fechaemision, a.bl_estadoregistro, 
					(select b.no_corto from tm_tabla_multiple b where b.no_tabla='ESTADO_DOCUMENTO_PORTAL' 
						and b.in_habilitado=1 and b.co_item_tabla=a.bl_estadoregistro) estadoregistro,
					a.bl_reintento, 
					(select c.no_corto from tm_tabla_multiple c where c.no_tabla='TIPO_DOCUMENTO' and c.in_habilitado =1 and c.co_item_tabla=b.tipoDocumentoRelacionado) tipo_doc ,
					b.tipodocumentorelacionado , b.numerodocumentorelacionado, 
					b.fechaemisiondocumentorelaciona, b.fechapago, b.numeropago, b.tipomonedadocumentorelacionado,
					b.importetotaldocumentorelaciona, b.importepagosinretencion, b.importeretenido, b.importetotalpagarneto
				from spe_retention a
					inner join spe_retention_item b on (a.tipodocumentoemisor=b.tipodocumentoemisor 
							and a.numerodocumentoemisor=b.numerodocumentoemisor
							and a.serieNumeroRetencion=b.serieNumeroRetencion)
				where a.numerodocumentoemisor='".$prm_ruc_empremisor."' and a.tipodocumento='".$prm_tipo_documento."' 
					and a.serienumeroretencion='".$prm_serie_numero."' and a.tipoDocumento='20'
				order by a.tipodocumento,a.serienumeroretencion, b.numeroOrdenItem;";
		$consulta =  $this->db_client->query($query);		
		return $consulta->result_array();
	}
	
	/*
	function Buscar_DetalleCaracteristica($prm_tipodocumentoemisor,$prm_numerodocumentoemisor,$prm_tipodedocumento,$prm_serienumero,$prm_clave)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";
		$query="select valor from spe_einvoiceheader_add 
			where 
				tipodocumentoemisor='".$prm_tipodocumentoemisor."'
				and numerodocumentoemisor='".$prm_numerodocumentoemisor."'
				and tipodocumento= '".$prm_tipodedocumento."'
				and serienumero= '".$prm_serienumero."' 
				and clave= '".$prm_clave."' ";
		$consulta =  $this->db_client->query($query);		
		return $consulta->result_array();
	}*/

}