<?php 
//@session_start();
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
				$query=$query." and a.serienumero>='".$prm_serie_numeroinicio."' and a.serienumero<='".$prm_serie_numerofinal."' ";
			}
			if ($prm_cod_estdoc!='0')	
			{
				$query=$query." and a.bl_estadoregistro='".$prm_cod_estdoc."' ";
			}
			if ($prm_estado_documentosunat!='0')	
			{
				$query=$query." and upper(b.bl_estadoProceso) like '%".$prm_estado_documentosunat."%' ";
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
				$query=$query." where a.tipoDocumentoEmisor='6' and a.tipoDocumento='20' and a.numeroDocumentoProveedor='".$prm_ruc_empr."' ";
				if ($prm_documento_cliente!='')	
				{
					$query=$query." and a.numerodocumentoproveedor='".$prm_documento_cliente."' ";
				}
				if ($prm_serie_numeroinicio!='' && $prm_serie_numerofinal!='')	
				{
					$query=$query." and a.serienumero>='".$prm_serie_numeroinicio."' and a.serienumero<='".$prm_serie_numerofinal."' ";
				}
				if ($prm_estado_documentosunat!='0')	
				{
					$query=$query." and upper(b.bl_estadoProceso) like '%".$prm_estado_documentosunat."%' ";
				}
				if ($prm_fec_emisinicio!='' && $prm_fec_emisfinal!='')	
				{
					$query=$query." and a.fechaemision>='".$prm_fec_emisinicio."'
					and	a.fechaemision<='".$prm_fec_emisfinal."' "; 
				}
				$query=$query." order by fechaemision;";
			//print_r($query);
			}
		}
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
		b.numeroordenitem, b.tipodocumentorelacionado,  
		(select c.no_corto 
		from tm_tabla_multiple c where c.no_tabla='TIPO_DOCUMENTO' and c.in_habilitado=1 
		and c.co_item_tabla in('01','03','07','08')
		and c.co_item_tabla=b.tipodocumentorelacionado) nomb_tipodocumento,	
		b.numerodocumentorelacionado, b.fechaemisiondocumentorelaciona, 
		b.fechapago, b.numeropago, b.tipomonedadocumentorelacionado, 
		(select c.nombre from sgr_multitabla c where c.grupo_nombre='TIPO_MONEDA' and c.activo=1 
		and c.grupo_id=5 and c.valorcadena=b.tipomonedadocumentorelacionado) tipomonedarelacionado,
		b.importetotaldocumentorelaciona, 
		b.importepagosinretencion, b.importeretenido, b.importetotalpagarneto,
		
		a.bl_estadoregistro, 
		(select b.no_corto from tm_tabla_multiple b where b.no_tabla='ESTADO_DOCUMENTO_PORTAL' 
		and b.in_habilitado=1 and b.co_item_tabla=a.bl_estadoregistro) estadoregistro
		from spe_retention a
		inner join spe_retention_item b on (a.tipodocumentoemisor=b.tipodocumentoemisor 
		and a.numerodocumentoemisor=b.numerodocumentoemisor
		and a.serieNumeroRetencion=b.serieNumeroRetencion)
		where a.numerodocumentoemisor='".$prm_ruc_empremisor."' and a.tipodocumento='".$prm_tipo_documento."' 
		and a.serienumeroretencion='".$prm_serie_numero."' and a.tipoDocumento='20'
		order by a.tipodocumento,a.serienumeroretencion, b.numeroOrdenItem;";
		//print_r($query);
		//return;
		$consulta =  $this->db_client->query($query);		
		return $consulta->result_array();
	}
	
	function existe_comprobante($prm_tipodedocumento,$prm_serienumero,$prm_montototal,$prm_fechaemision,$prm_rucproveedor)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";
		$query="select count(a.serienumeroretencion) cantidad
		from spe_retention a 
		inner join spe_retention_response b on a.tipodocumentoemisor=b.tipodocumentoemisor
		and a.numerodocumentoemisor=b.numerodocumentoemisor
		and a.serienumeroretencion=b.serienumeroretencion
		and a.tipoDocumento=b.tipoDocumento
		where a.serienumeroretencion='".$prm_serienumero."'
		and a.tipodocumento='".$prm_tipodedocumento."'
		and a.importetotalretenido='".$prm_montototal."'
		and a.fechaemision='".$prm_fechaemision."'
		and a.numeroDocumentoEmisor='".$prm_rucproveedor."'
		and b.bl_estadoproceso<>'SIGNED';";

		$consulta =  $this->db_client->query($query);		
		return $consulta->result_array();
	}

	function Guardar_Registroretenciones($prm_cod_usu,$prm_cod_empr,$prm_cod_doc,$prm_tipo_doc,$prm_num_doc,$prm_fec_emision,$prm_fec_pago,$prm_num_pago,$prm_moneda_origen,$prm_imp_origen,$prm_imp_pago_sin_ret,$prm_imp_retenido,$prm_imp_total_pagar)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();

		$query="
		insert into sgr_registroretenciones_temp
		(
		cod_usu,
		cod_empr,
		cod_doc,
		tipo_doc,
		num_doc,
		fec_emision,
		fec_pago,
		num_pago,
		moneda_origen,
		imp_origen,
		imp_pago_sin_ret,
		imp_retenido,
		imp_total_pagar
		)
		values
		(
		'".$prm_cod_usu."',
		'".$prm_cod_empr."',
		'".$prm_cod_doc."',
		'".$prm_tipo_doc."',
		'".$prm_num_doc."',
		'".$prm_fec_emision."',
		'".$prm_fec_pago."',
		'".$prm_num_pago."',
		'".$prm_moneda_origen."',
		'".$prm_imp_origen."',					
		'".$prm_imp_pago_sin_ret."',
		'".$prm_imp_retenido."',
		'".$prm_imp_total_pagar."'
		);";

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

	function Listar_RetencionesDocumento($prm_cod_usu,$prm_cod_empr)
	{

		$this->load->database('ncserver',TRUE);
		$query="
		select 
		tmp_ret,
		cod_usu,
		cod_empr,
		cod_doc,
		tipo_doc,
		num_doc,
		fec_emision,
		fec_pago,
		num_pago,
		moneda_origen,
		imp_origen,
		imp_pago_sin_ret,
		imp_retenido,
		imp_total_pagar
		from sgr_registroretenciones_temp a where cod_usu='".$prm_cod_usu."' and cod_empr='".$prm_cod_empr."' ";

		$query=$query.";";
		//print_r($query);

		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}	

	function Eliminar_Retenciontemporal($prm_tmp_ret)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();		
		$query="delete from sgr_registroretenciones_temp where tmp_ret='".$prm_tmp_ret."';";
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


	function Eliminar_RetenciontemporalRegistros($prm_cod_empr,$prm_cod_usu)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();		
		$query="delete from sgr_registroretenciones_temp where cod_empr='".$prm_cod_empr."' and cod_usu='".$prm_cod_usu."';";
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

	function Guardar_RetentionHeader(
		$prm_cod_empr,
		$prm_cod_usu,
		$prm_correoemisor,
		$prm_correoadquiriente,
		$prm_numerodocumentoemisor,
		$prm_tipodocumentoemisor,
		$prm_tipodocumento,
		$prm_razonsocialemisor,
		$prm_nombrecomercialemisor,
		$prm_seriedocumento,
		$prm_correlativodocumento,
		$prm_fechaemision,
		$prm_ubigeoemisor,
		$prm_direccionemisor,
		$prm_urbanizacion,
		$prm_provinciaemisor,
		$prm_departamentoemisor,
		$prm_distritoemisor,
		$prm_paisemisor,
		$prm_numerodocumentoproveedor,
		$prm_tipodocumentoproveedor,
		$prm_nombrecomercialproveedor,
		$prm_ubigeoproveedor,
		$prm_direccionproveedor,
		$prm_urbanizacionproveedor,
		$prm_provinciaproveedor,
		$prm_departamentoproveedor,
		$prm_distritoproveedor,
		$prm_codigopaisproveedor,
		$prm_razonsocialproveedor,
		$prm_total_retenido,
		$prm_total_pagar,
		$datosproveedor,
		$prm_observacion,
		$prm_tipo_registro,
		$prm_moneda,
		$prm_adicionalCantidad,
		$prm_adicionalCodigo,
		$prm_adicionalValor,
		$prm_adicionalCampos
		)
	{

		$result['result']=0;		
		$result['numero']=0;
		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();

		if ($prm_tipo_registro==0)
		{
			$prm_blestadoregistro='B';
		}else
		{
			$prm_blestadoregistro='A';
		}				
			//$valorigv=$this->Usuarioinicio_model->Get_Valor_IGV();
			//$valorigv_original=$valorigv;
			//$valorigv=round(($valorigv/100),2);


		$query="select (num_doc) num_documento from sgr_configuracionseries where cod_empr='".$prm_cod_empr."' 
		and tip_doc='".$prm_tipodocumento."' and ser_doc='".$prm_seriedocumento."';";	

		$consulta=$this->db_client->query($query);		
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			$result['numero']=-13;
			return $result;
		}
		$numerodocumento=$consulta->result_array();
		$prm_correlativodocumento=0;
			if(!empty($numerodocumento))//SI NO ES NULO O VACIO
			{
				$prm_correlativodocumento=str_pad($numerodocumento[0]['num_documento'],8, "0", STR_PAD_LEFT);
			}else
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				$result['numero']=-1;
				return $result;
			}
			
			//VALIDAMOS QUE EL NUMERO EN EJECUCION YA EXISTE REGISTRADO, SIEMPRE EN CUANDO SE INGRESO DE OTRA FUENTE O MANUAL
			$query="select serieNumeroRetencion from spe_retention 
			where 
			tipodocumentoemisor='".$prm_tipodocumentoemisor."' 
			and numerodocumentoemisor= '".$prm_numerodocumentoemisor."' 
			and tipodocumento= '".$prm_tipodocumento."' 
			and serieNumeroRetencion='".$prm_seriedocumento.'-'.$prm_correlativodocumento."' ;";		
			
			$consulta=$this->db_client->query($query);		
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				$result['numero']=-10;
				return $result;
			}
			if($consulta->num_rows()>0)//SI NO ES NULO O VACIO, SIGNIFICA QUE YA ESTA REGISTRADO EL CODIGO
			{
				$this->db_client->trans_rollback();
				$result['result']=2;
				$result['numero']=$prm_seriedocumento.'-'.$prm_correlativodocumento;
				return $result;
			}

/*			
			//Requerimiento 4 de la Versión 2: PARAMETRO DE MODELO TICKET
			//Si es valor 3: es un modelo ticket
			$valor_Aditional=0;
			$get_Aditional = $this->db_client->query("select aditional from bl_configuration
				where id_emisor='".$prm_tipodocumentoemisor."-".$prm_numerodocumentoemisor."';");	
			$res_get_Aditional=$get_Aditional->result_array();

			if(!empty($res_get_Aditional))//SI NO ES NULO O VACIO
			{
				$valor_Aditional=substr(($res_get_Aditional[0]['aditional']), 10, 1);
			}
*/					
			$query_colum='';
			$query_valores='';


			//INICIO DE INSERCCION CABECERA SPE_RETENTION
			$query_colum="insert into SPE_RETENTION(tipoDocumentoEmisor,";	$query_valores=" values('".$prm_tipodocumentoemisor."',";
			$query_colum=$query_colum."numeroDocumentoEmisor,";				$query_valores=$query_valores."'".$prm_numerodocumentoemisor."',";
			$query_colum=$query_colum."serieNumeroRetencion,";			$query_valores=$query_valores."'".$prm_seriedocumento.'-'.$prm_correlativodocumento."',";
			$query_colum=$query_colum."tipoDocumento,";			$query_valores=$query_valores."'".$prm_tipodocumento."',";
			$query_colum=$query_colum."bl_estadoRegistro,";					$query_valores=$query_valores."'".$prm_blestadoregistro."',";
			$query_colum=$query_colum."correoEmisor,";				$query_valores=$query_valores."'".$prm_correoemisor."',";
			$query_colum=$query_colum."correoAdquiriente,";				$query_valores=$query_valores."'".$prm_correoadquiriente."',";
			$query_colum=$query_colum."fechaEmision,";					$query_valores=$query_valores."'".$prm_fechaemision."',";

			if ($prm_nombrecomercialemisor!=''){
				$query_colum=$query_colum."nombreComercialEmisor,";		$query_valores=$query_valores."'".$prm_nombrecomercialemisor."',";
			}
			if ($prm_ubigeoemisor!=''){
				$query_colum=$query_colum."ubigeoEmisor,";				$query_valores=$query_valores."'".$prm_ubigeoemisor."',";
			}
			if ($prm_direccionemisor!=''){
				$query_colum=$query_colum."direccionEmisor,";			$query_valores=$query_valores."'".$prm_direccionemisor."',";
			}
			if ($prm_urbanizacion!=''){
				$query_colum=$query_colum."urbanizacionEmisor,";				$query_valores=$query_valores."'".$prm_urbanizacion."',";
			}
			if ($prm_provinciaemisor!=''){
				$query_colum=$query_colum."provinciaEmisor,";			$query_valores=$query_valores."'".$prm_provinciaemisor."',";
			}
			if ($prm_departamentoemisor!=''){
				$query_colum=$query_colum."departamentoEmisor,";		$query_valores=$query_valores."'".$prm_departamentoemisor."',";
			}
			if ($prm_distritoemisor!=''){
				$query_colum=$query_colum."distritoEmisor,";			$query_valores=$query_valores."'".$prm_distritoemisor."',";
			}
			if ($prm_paisemisor!=''){
				$query_colum=$query_colum."codigoPaisEmisor,";				$query_valores=$query_valores."'".$prm_paisemisor."',";
			}

			if ($prm_razonsocialemisor!=''){
				if ($prm_razonsocialemisor=='GENERICO')
					$prm_razonsocialemisor='-';
				$query_colum=$query_colum."razonSocialEmisor,";	$query_valores=$query_valores."'".$prm_razonsocialemisor."',";
			}
			if ($prm_numerodocumentoproveedor!=''){
				$query_colum=$query_colum."numeroDocumentoProveedor,";$query_valores=$query_valores."'".$prm_numerodocumentoproveedor."',";
			}
			if ($prm_tipodocumentoproveedor!=''){
				$query_colum=$query_colum."tipoDocumentoProveedor,";	$query_valores=$query_valores."'".$prm_tipodocumentoproveedor."',";
			}
			if ($prm_nombrecomercialproveedor!=''){
				$query_colum=$query_colum."nombreComercialProveedor,";	$query_valores=$query_valores."'".$prm_nombrecomercialproveedor."',";
			}
			if ($prm_ubigeoproveedor!=''){
				$query_colum=$query_colum."ubigeoProveedor,";				$query_valores=$query_valores."'".$prm_ubigeoproveedor."',";
			}
			if ($prm_direccionproveedor!=''){
				$query_colum=$query_colum."direccionProveedor,";			$query_valores=$query_valores."'".$prm_direccionproveedor."',";
			}
			if ($prm_urbanizacionproveedor!=''){
				$query_colum=$query_colum."urbanizacionProveedor,";				$query_valores=$query_valores."'".$prm_urbanizacionproveedor."',";
			}
			if ($prm_provinciaproveedor!=''){
				$query_colum=$query_colum."provinciaProveedor,";			$query_valores=$query_valores."'".$prm_provinciaproveedor."',";
			}
			if ($prm_departamentoproveedor!=''){
				$query_colum=$query_colum."departamentoProveedor,";		$query_valores=$query_valores."'".$prm_departamentoproveedor."',";
			}
			if ($prm_distritoproveedor!=''){
				$query_colum=$query_colum."distritoProveedor,";			$query_valores=$query_valores."'".$prm_distritoproveedor."',";
			}
			if ($prm_codigopaisproveedor!=''){
				$query_colum=$query_colum."codigoPaisProveedor,";				$query_valores=$query_valores."'".$prm_codigopaisproveedor."',";
			}
			if ($prm_razonsocialproveedor!=''){
				if ($prm_razonsocialproveedor=='GENERICO')
					$prm_razonsocialproveedor='-';
				$query_colum=$query_colum."razonSocialProveedor,";	$query_valores=$query_valores."'".$prm_razonsocialproveedor."',";
			}

			$query_colum=$query_colum."regimenRetencion,";	 $query_valores=$query_valores."'01',";
			$query_colum=$query_colum."tasaRetencion,";	 $query_valores=$query_valores."'3.00',";

			if ($prm_observacion!=''){
				$query_colum=$query_colum."observaciones,";	 $query_valores=$query_valores."'".$prm_observacion."',";
			}
			if ($prm_total_retenido!=''){
				$query_colum=$query_colum."importeTotalRetenido,";	 $query_valores=$query_valores."'".$prm_total_retenido."',";
			}
			if ($prm_total_pagar!=''){
				$query_colum=$query_colum."importeTotalPagado,";					$query_valores=$query_valores."'".$prm_total_pagar."',";
			}
			if ($prm_moneda!=''){
				$query_colum=$query_colum."tipoMonedaTotalPagado,";					$query_valores=$query_valores."'".$prm_moneda."',";
				$query_colum=$query_colum."tipoMonedaTotalRetenido,";					$query_valores=$query_valores."'".$prm_moneda."',";
			}
/*
			//Requerimiento 4: Insertar de datos de usuario de login siempre y cuando se modelo ticket
			if ($valor_Aditional==3){
				$txt_user='';
				$txt_user=$_SESSION['SES_InicioSystem'][0]['nom_usu'].', '.$_SESSION['SES_InicioSystem'][0]['apell_usu'];
				$query_colum=$query_colum."codigoAuxiliar100_1,"; 				$query_valores=$query_valores."'9371',";
				$query_colum=$query_colum."textoAuxiliar100_1,"; 				$query_valores=$query_valores."'".$txt_user."',";
			}
			//Fin Requerimiento

			//Requerimiento 4-version 2: Campos Adicionales
			//Inicio cantidad adicional: Se esta reservando estos dos campos para guardar la cantidad de adicionales insertados
			//$query_colum=$query_colum."codigoAuxiliar500_5,"; 				$query_valores=$query_valores."'ADIC',";
			//$query_colum=$query_colum."textoAuxiliar500_5,"; 				$query_valores=$query_valores."'".$prm_adicionalCantidad."',";
			//Fin cantidad adicional
*/

			//Fin Requerimiento

			$query_colum=$query_colum."bl_origen)";							$query_valores=$query_valores."'P');";

			$query=$query_colum.$query_valores;

			$this->db_client->query($query);
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				$result['numero']=-2;
				return $result;
			}
		//FIN INSERCCION SPE_RETENTION: Inicia en linea 332

		//INICIO PARA INSERCCION DEL DETALLE DEL COMPROBANTE: SPE_EINVOICEDETAIL
			$consulta = $this->db_client->query("select 
				tmp_ret,
				cod_usu,
				cod_empr,
				cod_doc,
				tipo_doc,
				num_doc,
				fec_emision,
				fec_pago,
				num_pago,
				moneda_origen,
				imp_origen,
				imp_pago_sin_ret,
				imp_retenido,
				imp_total_pagar
				from sgr_registroretenciones_temp where cod_empr='".$prm_cod_empr."' and cod_usu='".$prm_cod_usu."';");				

			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				$result['numero']=-4;
				return $result;
			}
			$detalledocumento=$consulta->result_array();
		//Valida si existe detalle
		if(count($detalledocumento)<1)//SI NO HAY NINGUN REGISTRO
		{
			$this->db_client->trans_rollback();
			$result['result']=3;
			$result['numero']=-40;
			return $result;
		}
		
		$contador=1;

		foreach($detalledocumento as $key=>$v):	

			$query_colum='';
		$query_valores='';
		//INICIO INSERCCION DETALLE: SPE_RETENTION_ITEM
		$query_colum="insert into SPE_RETENTION_ITEM(tipoDocumentoEmisor,";	$query_valores=" values('".$prm_tipodocumentoemisor."',";
		$query_colum=$query_colum."numeroDocumentoEmisor,";					$query_valores=$query_valores."'".$prm_numerodocumentoemisor."',";			
		$query_colum=$query_colum."serieNumeroRetencion,";					$query_valores=$query_valores."'".$prm_seriedocumento.'-'.$prm_correlativodocumento."',";	
		$query_colum=$query_colum."tipoDocumento,";							$query_valores=$query_valores."'20',";			
		$query_colum=$query_colum."numeroordenitem,";						$query_valores=$query_valores."'".$contador."',";			
		$query_colum=$query_colum."tipoDocumentoRelacionado,";				$query_valores=$query_valores."'".trim($v['cod_doc'])."',";			
		$query_colum=$query_colum."numeroDocumentoRelacionado,";			$query_valores=$query_valores."'".trim($v['num_doc'])."',";
		$query_colum=$query_colum."fechaEmisionDocumentoRelaciona,";		$query_valores=$query_valores."'".trim($v['fec_emision'])."',";

		$query_colum=$query_colum."importeTotalDocumentoRelaciona,";		$query_valores=$query_valores."'".number_format(trim($v['imp_origen']),2,'.',',')."',";

		$query_colum=$query_colum."tipoMonedaDocumentoRelacionado,";		$query_valores=$query_valores."'".trim($v['moneda_origen'])."',";
		$query_colum=$query_colum."fechaPago,";								$query_valores=$query_valores."'".trim($v['fec_pago'])."',";
		$query_colum=$query_colum."numeroPago,";							$query_valores=$query_valores."'".trim($v['num_pago'])."',";

		$query_colum=$query_colum."importePagoSinRetencion,";				$query_valores=$query_valores."'".number_format(trim($v['imp_pago_sin_ret']),2,'.',',')."',";

		$query_colum=$query_colum."monedaPago,";							$query_valores=$query_valores."'".trim($v['moneda_origen'])."',";

		$query_colum=$query_colum."importeRetenido,";						$query_valores=$query_valores."'".number_format(trim($v['imp_retenido']),2,'.',',')."',";

		$query_colum=$query_colum."monedaImporteRetenido,";					$query_valores=$query_valores."'".trim($v['moneda_origen'])."',";
		$query_colum=$query_colum."fechaRetencion,";						$query_valores=$query_valores."'".trim($prm_fechaemision)."',";
		$query_colum=$query_colum."importeTotalPagarNeto,";					$query_valores=$query_valores."'".number_format(trim($v['imp_total_pagar']),2,'.',',')."',";
		$query_colum=$query_colum."monedaMontoNetoPagado,";					$query_valores=$query_valores."'".trim($v['moneda_origen'])."',";
		$query_colum=$query_colum."monedaReferenciaTipoCambio,";			$query_valores=$query_valores."NULL,";
		$query_colum=$query_colum."monedaObjetivoTasaCambio,";				$query_valores=$query_valores."NULL,";
		$query_colum=$query_colum."factorTipoCambioMoneda,";				$query_valores=$query_valores."NULL,";
		$query_colum=$query_colum."fechaCambio)";							$query_valores=$query_valores."NULL);";

		$query=$query_colum.$query_valores;

		$this->db_client->query($query);		
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			$result['numero']=-5;
			return $result;
		}				
		$contador++;				
		endforeach;
		//FIN INSERCCION: SPE_RETENTION_ITEM

		//INICIO INSERCION: SPE_RETENTION_AUXILIAR
		if ($prm_adicionalCantidad>0)
		{
			while($prm_adicionalCantidad>0)
			{

				$query="insert into SPE_RETENTION_AUXILIAR (TIPODOCUMENTOEMISOR,NUMERODOCUMENTOEMISOR,TIPODOCUMENTO,SERIENUMERO,CLAVE,VALOR) values (6,'".$prm_numerodocumentoemisor."','20','".$prm_seriedocumento.'-'.$prm_correlativodocumento."','"."codigoAuxiliar".$prm_adicionalCampos[$prm_adicionalCantidad-1]."','".$prm_adicionalCodigo[$prm_adicionalCantidad-1]."');";

				$query=$query."insert into SPE_RETENTION_AUXILIAR (TIPODOCUMENTOEMISOR,NUMERODOCUMENTOEMISOR,TIPODOCUMENTO,SERIENUMERO,CLAVE,VALOR) values (6,'".$prm_numerodocumentoemisor."','20','".$prm_seriedocumento.'-'.$prm_correlativodocumento."','"."textoAuxiliar".$prm_adicionalCampos[$prm_adicionalCantidad-1]."','".$prm_adicionalValor[$prm_adicionalCantidad-1]."');";

				$this->db_client->query($query);		
				if ($this->db_client->trans_status() === FALSE)
				{
					$this->db_client->trans_rollback();
					$result['result']=0;
					$result['numero']=-6;
					return $result;
				}

				$prm_adicionalCantidad--;
			}
		}
		//FIN INSERCION: SPE_RETENTION_AUXILIAR

		$query="delete from sgr_registroretenciones_temp where cod_empr='".$prm_cod_empr."' and cod_usu='".$prm_cod_usu."';";
		$this->db_client->query($query);		
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			$result['numero']=-6;
			return $result;
		}
		

		$query="update sgr_configuracionseries set num_doc=num_doc+1  
		where cod_empr='".$prm_cod_empr."' and tip_doc='".$prm_tipodocumento."' and ser_doc='".$prm_seriedocumento."';";
		$this->db_client->query($query);		
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			$result['numero']=-7;
			return $result;
		}

		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			$result['numero']=-7;
			return $result;
		}

		
		$this->db_client->trans_commit();
		$result['result']=1;
		$result['numero']=$prm_seriedocumento.'-'.$prm_correlativodocumento;

		return $result;
	}	


}