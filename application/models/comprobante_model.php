<?php
if(!isset($_SESSION)) { session_start(); }



class Comprobante_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}


	function Guardar_Registroproductos($prm_cod_usu,$prm_cod_prod,$prm_cant_prod,$prm_uni_med,$prm_desc_prod,
		$prm_val_unitario,$prm_val_descuento,$prm_val_isc,$prm_tip_afectacion,$prm_val_igv,$prm_val_total,
		$prm_cod_empr,$prm_ruc_empr,$prm_cod_tipregist,$prm_val_txt_preciocobro, $prm_val_descuento_inc_igv)
	{
		$result['result']=0;
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();

		$query="
		insert into sgr_registroproductos_temp
		(
		cod_usu,
		cod_prod,
		cant_prod,
		uni_med,
		desc_prod,
		val_unitario,
		val_descuento,
		val_isc,
		tip_afectacion,
		val_igv,
		val_total,
		cod_empr,
		ruc_empr,
		cod_tipregist,
		val_preciocobro,
		val_descuento_inc_igv
		)
		values
		(
		'".$prm_cod_usu."',
		'".$prm_cod_prod."',
		'".$prm_cant_prod."',
		'".$prm_uni_med."',
		'".$prm_desc_prod."',
		'".$prm_val_unitario."',
		'".$prm_val_descuento."',
		'".$prm_val_isc."',
		'".$prm_tip_afectacion."',
		'".$prm_val_igv."',
		'".$prm_val_total."',
		'".$prm_cod_empr."',
		'".$prm_ruc_empr."',
		'".$prm_cod_tipregist."',
		'".$prm_val_txt_preciocobro."',
		'".$prm_val_descuento_inc_igv."'
		);";

		//print_r($query);
		//return;


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

	function Listar_ProductosDocumento($prm_cod_usu,$prm_cod_empr)
	{
		$this->load->database('ncserver',TRUE);
		$query="
		select
		tmp_prod,
		cod_usu,
		cod_prod,
		cant_prod,
		uni_med,
		desc_prod,
		val_unitario,
		val_descuento,
		val_isc,
		tip_afectacion,
		val_igv,
		val_total,
		cod_empr,
		ruc_empr,
		cod_tipregist,
		val_preciocobro,
		val_descuento_inc_igv
		from sgr_registroproductos_temp a
		where cod_usu='".$prm_cod_usu."'		and cod_empr='".$prm_cod_empr."' ";

		$query=$query.";";
		//print_r($query);

		$consulta=$this->db->query($query);
		return $consulta->result_array();
	}

	function Eliminar_Productotemporal($prm_tmp_prod)
	{
		$result['result']=0;
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();
		$query="delete from sgr_registroproductos_temp where tmp_prod='".$prm_tmp_prod."';";
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

	function Eliminar_ProductotemporalRegistros($prm_cod_empr,$prm_cod_usu)
	{
		$result['result']=0;
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();
		$query="delete from sgr_registroproductos_temp where cod_empr='".$prm_cod_empr."' and cod_usu='".$prm_cod_usu."';";
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


	function Guardar_Einvoiceheader(		$prm_cod_empr,
		$prm_cod_usu,
		$prm_correoemisor,
		$prm_correoadquiriente,
		$prm_numerodocumentoemisor,
		$prm_tipodocumentoemisor,
		$prm_tipodocumento,
		$prm_razonsocialemisor,
		$prm_nombrecomercialemisor,
		$prm_seriedocumento,
		$prm_numerodocumento,
		$prm_fechaemision,
		$prm_ubigeoemisor,
		$prm_direccionemisor,
		$prm_urbanizacion,
		$prm_provinciaemisor,
		$prm_departamentoemisor,
		$prm_distritoemisor,
		$prm_paisemisor,
		$prm_numerodocumentoadquiriente,
		$prm_tipodocumentoadquiriente,
		$prm_razonsocialadquiriente,
		$prm_tipomoneda,
		$prm_totalvalorventanetoopgravadas,
		$prm_totalvalorventanetoopnogravada,
		$prm_totalvalorventanetoopexonerada,
		$prm_totaligv,
		$prm_totaldescuentos,
		$prm_totalventa,
		$prm_textoleyenda_1,
		$prm_codigoleyenda_1,
		$prm_totaldetraccion,
		$prm_valorreferencialdetraccion,
		$prm_porcentajedetraccion,
		$prm_descripciondetraccion,
		$prm_textoleyenda_2,
		$prm_codigoleyenda_2,
		$prm_descuentosglobales,
		$prm_textoleyenda_3,
		$prm_codigoleyenda_3,

		$prm_porcentajepercepcion,
		$prm_baseimponiblepercepcion,
		$prm_totalpercepcion,
		$prm_totalventaconpercepcion,
		$datosadquiriente,
		$prm_totalvalorventanetoopgratuitas,

		$prm_codigoserienumeroafectado,
		$prm_serienumeroafectado,
		$prm_motivodocumento,
		$prm_tipodocumentoreferenciaprincip,
		$prm_numerodocumentoreferenciaprinc,
		$prm_tipodocumentoreferenciacorregi,
		$prm_numerodocumentoreferenciacorre,
		$prm_tipo_registro,
		$prm_documentomodificar,
		$prm_totalotroscargos,
		$prm_porcentajeotroscargos,
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
		$valorigv=$this->Usuarioinicio_model->Get_Valor_IGV();
		$valorigv_original=$valorigv;
		$valorigv=round(($valorigv/100),2);

		if ($prm_documentomodificar!='')
		{
			$query="delete from spe_einvoiceheader
			where
			tipodocumentoemisor='".$prm_tipodocumentoemisor."'
			and numerodocumentoemisor= '".$prm_numerodocumentoemisor."'
			and tipodocumento= '".$prm_tipodocumento."'
			and serienumero='".$prm_seriedocumento.'-'.$prm_numerodocumento."' ;";
			$consulta=$this->db_client->query($query);
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				$result['numero']=-10;
				return $result;
			}

			$query="delete from spe_einvoicedetail
			where
			tipodocumentoemisor='".$prm_tipodocumentoemisor."'
			and numerodocumentoemisor= '".$prm_numerodocumentoemisor."'
			and tipodocumento= '".$prm_tipodocumento."'
			and serienumero='".$prm_seriedocumento.'-'.$prm_numerodocumento."' ;";
			$consulta=$this->db_client->query($query);
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				$result['numero']=-11;
				return $result;
			}

			$query="delete from spe_einvoiceheader_add
			where
			tipodocumentoemisor='".$prm_tipodocumentoemisor."'
			and numerodocumentoemisor= '".$prm_numerodocumentoemisor."'
			and tipodocumento= '".$prm_tipodocumento."'
			and serienumero='".$prm_seriedocumento.'-'.$prm_numerodocumento."' ;";
			$consulta=$this->db_client->query($query);
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				$result['numero']=-12;
				return $result;
			}
		}//end if

		if ($prm_documentomodificar=='')// NUEVO REGISTRO
		{
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
			$prm_numerodocumento=0;
			if(!empty($numerodocumento))//SI NO ES NULO O VACIO
			{
				$prm_numerodocumento=str_pad($numerodocumento[0]['num_documento'],8, "0", STR_PAD_LEFT);
			}else
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				$result['numero']=-1;
				return $result;
			}

			//VALIDAMOS QUE EL NUMERO EN EJECUCION YA EXISTE REGISTRADO, SIEMPRE EN CUANDO SE INGRESO DE OTRA FUENTE O MANUAL
			$query="select serienumero from spe_einvoiceheader
			where
			tipodocumentoemisor='".$prm_tipodocumentoemisor."'
			and numerodocumentoemisor= '".$prm_numerodocumentoemisor."'
			and tipodocumento= '".$prm_tipodocumento."'
			and serienumero='".$prm_seriedocumento.'-'.$prm_numerodocumento."' ;";

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
				$result['numero']=$prm_numerodocumento;
				return $result;
			}
		}

		//Requerimiento 4 de la Versi�n 2: PARAMETRO DE MODELO TICKET
		//Si es valor 3: es un modelo ticket
		$valor_Aditional=0;
		$get_Aditional = $this->db_client->query("select aditional from bl_configuration
			where id_emisor='".$prm_tipodocumentoemisor."-".$prm_numerodocumentoemisor."';");
		$res_get_Aditional=$get_Aditional->result_array();

		if(!empty($res_get_Aditional))//SI NO ES NULO O VACIO
		{
			$valor_Aditional=substr(($res_get_Aditional[0]['aditional']), 10, 1);
		}

		$query_colum='';
		$query_valores='';

		//INICIO DE INSERCCION CABECERA SPE_EINVOICEHEADER
		$query_colum="insert into spe_einvoiceheader(correoemisor,";	$query_valores=" values('".$prm_correoemisor."',";
		$query_colum=$query_colum."correoadquiriente,";				$query_valores=$query_valores."'".$prm_correoadquiriente."',";
		$query_colum=$query_colum."numerodocumentoemisor,";			$query_valores=$query_valores."'".$prm_numerodocumentoemisor."',";
		$query_colum=$query_colum."tipodocumentoemisor,";			$query_valores=$query_valores."'".$prm_tipodocumentoemisor."',";
		$query_colum=$query_colum."tipodocumento,";					$query_valores=$query_valores."'".$prm_tipodocumento."',";
		$query_colum=$query_colum."razonsocialemisor,";				$query_valores=$query_valores."'".$prm_razonsocialemisor."',";

		if ($prm_nombrecomercialemisor!=''){
			$query_colum=$query_colum."nombrecomercialemisor,";		$query_valores=$query_valores."'".$prm_nombrecomercialemisor."',";
		}
		$query_colum=$query_colum."serienumero,";					$query_valores=$query_valores."'".$prm_seriedocumento.'-'.$prm_numerodocumento."',";
		$query_colum=$query_colum."fechaemision,";					$query_valores=$query_valores."'".$prm_fechaemision."',";

		if ($prm_ubigeoemisor!=''){
			$query_colum=$query_colum."ubigeoemisor,";				$query_valores=$query_valores."'".$prm_ubigeoemisor."',";
		}
		if ($prm_direccionemisor!=''){
			$query_colum=$query_colum."direccionemisor,";			$query_valores=$query_valores."'".$prm_direccionemisor."',";
		}
		if ($prm_urbanizacion!=''){
			$query_colum=$query_colum."urbanizacion,";				$query_valores=$query_valores."'".$prm_urbanizacion."',";
		}
		if ($prm_provinciaemisor!=''){
			$query_colum=$query_colum."provinciaemisor,";			$query_valores=$query_valores."'".$prm_provinciaemisor."',";
		}
		if ($prm_departamentoemisor!=''){
			$query_colum=$query_colum."departamentoemisor,";		$query_valores=$query_valores."'".$prm_departamentoemisor."',";
		}
		if ($prm_distritoemisor!=''){
			$query_colum=$query_colum."distritoemisor,";			$query_valores=$query_valores."'".$prm_distritoemisor."',";
		}
		if ($prm_paisemisor!=''){
			$query_colum=$query_colum."paisemisor,";				$query_valores=$query_valores."'".$prm_paisemisor."',";
		}
		if ($prm_numerodocumentoadquiriente!=''){
			$query_colum=$query_colum."numerodocumentoadquiriente,";$query_valores=$query_valores."'".$prm_numerodocumentoadquiriente."',";
		}
		if ($prm_tipodocumentoadquiriente!=''){
			$query_colum=$query_colum."tipodocumentoadquiriente,";	$query_valores=$query_valores."'".$prm_tipodocumentoadquiriente."',";
		}
		if ($prm_razonsocialadquiriente!=''){
			if ($prm_razonsocialadquiriente=='GENERICO')
				$prm_razonsocialadquiriente='-';
			$query_colum=$query_colum."razonsocialadquiriente,";	$query_valores=$query_valores."'".$prm_razonsocialadquiriente."',";
		}
		if ($prm_tipomoneda!=''){
			$query_colum=$query_colum."tipomoneda,";				$query_valores=$query_valores."'".$prm_tipomoneda."',";
		}
		if ($prm_totalvalorventanetoopgravadas!=''){
			$query_colum=$query_colum."totalvalorventanetoopgravadas,";	 $query_valores=$query_valores."'".$prm_totalvalorventanetoopgravadas."',";
		}
		if ($prm_totalvalorventanetoopnogravada!=''){
			$query_colum=$query_colum."totalvalorventanetoopnogravada,"; $query_valores=$query_valores."'".$prm_totalvalorventanetoopnogravada."',";
		}
		if ($prm_totalvalorventanetoopexonerada!=''){
			$query_colum=$query_colum."totalvalorventanetoopexonerada,"; $query_valores=$query_valores."'".$prm_totalvalorventanetoopexonerada."',";
		}
		if ($prm_totaligv!=''){
			$query_colum=$query_colum."totaligv,";						$query_valores=$query_valores."'".$prm_totaligv."',";
		}
		if ($prm_totaldescuentos!=''){
			$query_colum=$query_colum."totaldescuentos,";				$query_valores=$query_valores."'".$prm_totaldescuentos."',";
		}
		if ($prm_totalventa!=''){
			$query_colum=$query_colum."totalventa,";					$query_valores=$query_valores."'".$prm_totalventa."',";
		}
		if ($prm_textoleyenda_1!=''){
			$query_colum=$query_colum."textoleyenda_1,";				$query_valores=$query_valores."'".$prm_textoleyenda_1."',";
		}
		if ($prm_codigoleyenda_1!=''){
			$query_colum=$query_colum."codigoleyenda_1,";				$query_valores=$query_valores."'".$prm_codigoleyenda_1."',";
		}
		if ($prm_blestadoregistro!=''){
			$query_colum=$query_colum."bl_estadoregistro,";				$query_valores=$query_valores."'".$prm_blestadoregistro."',";
		}
		//Requerimiento de cambio 4: Porcentaje de Otros cargos
		if ($prm_totalotroscargos!='')
		{
			if(floatval($prm_porcentajeotroscargos)>0){
				$query_colum=$query_colum."totalOtrosCargos,";				$query_valores=$query_valores."'".$prm_totalotroscargos."',";
				$query_colum=$query_colum."codigoAuxiliar40_2,"; 			$query_valores=$query_valores."'9370',";
				$query_colum=$query_colum."textoAuxiliar40_2,"; 			$query_valores=$query_valores."'".$prm_porcentajeotroscargos."%',";
			}
		}
		//Fin Requerimiento
		//Requerimiento de cambio 4: Registro del valor de IGV
		$query_colum=$query_colum."codigoAuxiliar40_1,"; 				$query_valores=$query_valores."'9011',";
		$query_colum=$query_colum."textoAuxiliar40_1,"; 				$query_valores=$query_valores."'".$valorigv_original."%',";
		//Fin Requerimiento
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
		if ($prm_adicionalCantidad>0)
		{
			while($prm_adicionalCantidad>0)
			{
				$query_colum=$query_colum."codigoAuxiliar".$prm_adicionalCampos[$prm_adicionalCantidad-1].",";
				$query_valores=$query_valores."'".$prm_adicionalCodigo[$prm_adicionalCantidad-1]."',";

				$query_colum=$query_colum."textoAuxiliar".$prm_adicionalCampos[$prm_adicionalCantidad-1].",";
				$query_valores=$query_valores."'".$prm_adicionalValor[$prm_adicionalCantidad-1]."',";

				$prm_adicionalCantidad--;
			}
		}
		//Fin Requerimiento
		if($prm_tipodocumento=='03')//BOLETA
		{
			$datosadquiriente=substr($datosadquiriente, 0, 100);
			if ($datosadquiriente!=''){
				$query_colum=$query_colum."lugardestino,";					$query_valores=$query_valores."'".$datosadquiriente."',";
			}
		}else
		{
			if ($prm_tipodocumento=='07' || $prm_tipodocumento=='08') //es nc o dn
			{
				if ($prm_tipodocumentoreferenciaprincip=='03')// es boleta
				{
					$datosadquiriente=substr($datosadquiriente, 0, 100);
					if ($datosadquiriente!=''){
						$query_colum=$query_colum."lugardestino,";			$query_valores=$query_valores."'".$datosadquiriente."',";
					}
				}
			}
		}//end if

		if ($prm_totaldetraccion!=''){
			if ($prm_totaldetraccion!=''){
				$query_colum=$query_colum."totaldetraccion,";			$query_valores=$query_valores."'".$prm_totaldetraccion."',";
			}
			if ($prm_valorreferencialdetraccion!=''){
				$query_colum=$query_colum."valorreferencialdetraccion,"; $query_valores=$query_valores."'".$prm_valorreferencialdetraccion."',";
			}
			if ($prm_porcentajedetraccion!=''){
				$query_colum=$query_colum."porcentajedetraccion,";		$query_valores=$query_valores."'".$prm_porcentajedetraccion."',";
			}
			if ($prm_descripciondetraccion!=''){
				$query_colum=$query_colum."descripciondetraccion,";		$query_valores=$query_valores."'".$prm_descripciondetraccion."',";
			}
			if ($prm_textoleyenda_2!=''){
				$query_colum=$query_colum."textoleyenda_2,";			$query_valores=$query_valores."'".$prm_textoleyenda_2."',";
			}
			if ($prm_codigoleyenda_2!=''){
				$query_colum=$query_colum."codigoleyenda_2,";			$query_valores=$query_valores."'".$prm_codigoleyenda_2."',";
			}
		}
		if ($prm_descuentosglobales!=''){
			$query_colum=$query_colum."descuentosglobales,";			$query_valores=$query_valores."'".$prm_descuentosglobales."',";
		}
		$query_colum=$query_colum."inhabilitado,";						$query_valores=$query_valores."'1',";
		if ($prm_textoleyenda_3!=''){
			$query_colum=$query_colum."textoleyenda_3,";				$query_valores=$query_valores."'".$prm_textoleyenda_3."',";
			$query_colum=$query_colum."codigoleyenda_3,";				$query_valores=$query_valores."'".$prm_codigoleyenda_3."',";
		}
		if ($prm_porcentajepercepcion!=''){
			if ($prm_porcentajepercepcion!=''){
				$query_colum=$query_colum."porcentajepercepcion,";		$query_valores=$query_valores."'".$prm_porcentajepercepcion."',";
			}
			if ($prm_baseimponiblepercepcion!=''){
				$query_colum=$query_colum."baseimponiblepercepcion,";	$query_valores=$query_valores."'".$prm_baseimponiblepercepcion."',";
			}
			if ($prm_totalpercepcion!=''){
				$query_colum=$query_colum."totalpercepcion,";			$query_valores=$query_valores."'".$prm_totalpercepcion."',";
			}
			if ($prm_totalventaconpercepcion!=''){
				$query_colum=$query_colum."totalventaconpercepcion,";	$query_valores=$query_valores."'".$prm_totalventaconpercepcion."',";
			}
		}
		if ($prm_totalvalorventanetoopgratuitas!=''){
			$query_colum=$query_colum."totalvalorventanetoopgratuitas,"; $query_valores=$query_valores."'".$prm_totalvalorventanetoopgratuitas."',";
		}

		//NOTAS DE CREDITO Y DEBITO
		if ($prm_tipodocumento=='07' or $prm_tipodocumento=='08')
		{
			if ($prm_codigoserienumeroafectado!=''){
				$query_colum=$query_colum."codigoserienumeroafectado,";	$query_valores=$query_valores."'".$prm_codigoserienumeroafectado."',";
			}
			if ($prm_serienumeroafectado!=''){
				$query_colum=$query_colum."serienumeroafectado,";		$query_valores=$query_valores."'".strtoupper($prm_serienumeroafectado)."',";
			}
			if ($prm_motivodocumento!=''){
				$query_colum=$query_colum."motivodocumento,";			$query_valores=$query_valores."'".$prm_motivodocumento."',";
			}
			if ($prm_tipodocumentoreferenciaprincip!=''){
				$query_colum=$query_colum."tipodocumentoreferenciaprincip,"; 	$query_valores=$query_valores."'".$prm_tipodocumentoreferenciaprincip."',";
			}
			if ($prm_numerodocumentoreferenciaprinc!=''){
				$query_colum=$query_colum."numerodocumentoreferenciaprinc,";	$query_valores=$query_valores."'".strtoupper($prm_numerodocumentoreferenciaprinc)."',";
			}
			if ($prm_tipodocumentoreferenciacorregi!=''){
				$query_colum=$query_colum."tipodocumentoreferenciacorregi,";	$query_valores=$query_valores."'".$prm_tipodocumentoreferenciacorregi."',";
			}
			if ($prm_numerodocumentoreferenciacorre!=''){
				$query_colum=$query_colum."numerodocumentoreferenciacorre,";	$query_valores=$query_valores."'".$prm_numerodocumentoreferenciacorre."',";
			}
		}

		$query_colum=$query_colum."bl_origen)";	$query_valores=$query_valores."'P');";
		$query=$query_colum.$query_valores;

		$this->db_client->query($query);
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			$result['numero']=-2;
			return $result;
		}
		//FIN INSERCCION SPE_EINVOICEHEADER: Inicia en linea 332

		//INICIO INSERCCION SPE_EINVOICEHEADER_ADD
		if($prm_tipodocumento=='01')//FACTURA
		{
			if(!empty($datosadquiriente))//SI NO ES NULO O VACIO
			{
				foreach($datosadquiriente as $key=>$v):
					if ($v!=''){
						$query="insert into spe_einvoiceheader_add
						(
						tipodocumentoemisor,
						numerodocumentoemisor,
						tipodocumento,
						serienumero,
						clave,
						valor
						)
						values
						(
						'".$prm_tipodocumentoemisor."',
						'".$prm_numerodocumentoemisor."',
						'".$prm_tipodocumento."',
						'".$prm_seriedocumento.'-'.$prm_numerodocumento."',
						'".$key."',
						'".$v."'
						);";
						$this->db_client->query($query);
						if ($this->db_client->trans_status() === FALSE)
						{
							$this->db_client->trans_rollback();
							$result['result']=0;
							$result['numero']=-3;
							return $result;
						}
					}
					endforeach;
				}
			}else
			{
			if ($prm_tipodocumento=='07' or $prm_tipodocumento=='08') //es nc o dn
			{
				if ($prm_tipodocumentoreferenciaprincip=='01')// es FACTURA
				{
					if(!empty($datosadquiriente))//SI NO ES NULO O VACIO
					{
						foreach($datosadquiriente as $key=>$v):
							if ($v!=''){
								$query="insert into spe_einvoiceheader_add
								(
								tipodocumentoemisor,
								numerodocumentoemisor,
								tipodocumento,
								serienumero,
								clave,
								valor
								)
								values
								(
								'".$prm_tipodocumentoemisor."',
								'".$prm_numerodocumentoemisor."',
								'".$prm_tipodocumento."',
								'".$prm_seriedocumento.'-'.$prm_numerodocumento."',
								'".$key."',
								'".$v."'
								);";
								$this->db_client->query($query);
								if ($this->db_client->trans_status() === FALSE)
								{
									$this->db_client->trans_rollback();
									$result['result']=0;
									$result['numero']=-3;
									return $result;
								}
							}
							endforeach;
						}
					}
				}
			}
		//FIN INSERCCION SPE_EINVOICEHEADER_ADD

		//INICIO PARA INSERCCION DEL DETALLE DEL COMPROBANTE: SPE_EINVOICEDETAIL
			$consulta = $this->db_client->query("select
				tmp_prod,
				cod_usu,
				cod_prod,
				cant_prod,
				uni_med,
				desc_prod,
				val_unitario,
				val_descuento,
				val_isc,
				tip_afectacion,
				val_igv,
				val_total,
				cod_empr,
				ruc_empr,
				cod_tipregist,
				val_preciocobro,
				val_descuento_inc_igv
				from sgr_registroproductos_temp where cod_empr='".$prm_cod_empr."' and cod_usu='".$prm_cod_usu."';");

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
		$Conf_Tipo_Venta=$_SESSION['SES_MarcoTrabajo'][0]['conf_venta'];
		foreach($detalledocumento as $key=>$v):
			$val_descuento=number_format(trim($v['val_descuento']), 2, '.', '');
		$val_precioconigv=0;
			if ( ($v['val_igv'])>0)//exonerada onerosa  , inafecto onerosa
			{
				$val_precioconigv=number_format(trim(($v['val_unitario']*(1+$valorigv))), 2, '.', '');
			}else
			{
				$val_precioconigv=number_format(trim($v['val_unitario']), 2, '.', '');
			}
			$var_importeunitariosinimpuesto=0;
			$var_importereferencial='';
			$var_codigoimportereferencial='';
			if ($v['cod_tipregist']==3)//CHECK OPERACIONES GRATUITAS
			{
				$var_importeunitariosinimpuesto='0.00';
				$val_precioconigv='0.00';
				$var_importetotal='0.00';
				$var_importereferencial=number_format(trim($v['val_unitario']), 2, '.', '');
				$var_codigoimportereferencial='02';
			}else
			{
				$var_importeunitariosinimpuesto=number_format(trim($v['val_unitario']), 2, '.', '');
				$var_importetotal=number_format(trim($v['val_total']), 2, '.', '');
			}

			$query_colum='';
			$query_valores='';
			//INICIO INSERCCION DETALLE: SPE_EINVOICEDETAIL
			$query_colum="insert into spe_einvoicedetail(tipodocumentoemisor,";	$query_valores=" values('".$prm_tipodocumentoemisor."',";
			$query_colum=$query_colum."numerodocumentoemisor,";		$query_valores=$query_valores."'".$prm_numerodocumentoemisor."',";
			$query_colum=$query_colum."tipodocumento,";				$query_valores=$query_valores."'".$prm_tipodocumento."',";
			$query_colum=$query_colum."serienumero,";				$query_valores=$query_valores."'".$prm_seriedocumento.'-'.$prm_numerodocumento."',";
			$query_colum=$query_colum."numeroordenitem,";			$query_valores=$query_valores."'".$contador."',";
			$query_colum=$query_colum."codigoproducto,";			$query_valores=$query_valores."'".trim($v['cod_prod'])."',";
			$query_colum=$query_colum."descripcion,";				$query_valores=$query_valores."'".trim($v['desc_prod'])."',";
			$query_colum=$query_colum."cantidad,";					$query_valores=$query_valores."'".number_format(trim($v['cant_prod']), 2, '.', '')."',";
			$query_colum=$query_colum."unidadmedida,";				$query_valores=$query_valores."'".trim($v['uni_med'])."',";
			if ($var_importetotal!=''){
				$query_colum=$query_colum."importetotalsinimpuesto,";	 $query_valores=$query_valores."'".$var_importetotal."',";
			}
			if ($var_importeunitariosinimpuesto!=''){
				$query_colum=$query_colum."importeunitariosinimpuesto,"; $query_valores=$query_valores."'".$var_importeunitariosinimpuesto."',";
			}
			if ($val_precioconigv!=''){
				$query_colum=$query_colum."importeunitarioconimpuesto,"; $query_valores=$query_valores."'".$val_precioconigv."',";
			}
			//Requerimiento 4: Insertar Precio total
			if ($valor_Aditional==3){
				//Se condiciona solo para factura y boleta
				if($prm_tipodocumento=='03' || $prm_tipodocumento=='01')
				{
					$PrecioTotal_tmp=(($v['cant_prod'])*($v['val_preciocobro'])) - $v['val_descuento_inc_igv'];//($val_descuento*(1+$valorigv));
					$query_colum=$query_colum."codigoauxiliar40_1,"; 		$query_valores=$query_valores."'9122',";
					$query_colum=$query_colum."textoAuxiliar40_1,"; 		$query_valores=$query_valores."'".number_format(trim($PrecioTotal_tmp), 2, '.', '')."',";
				}
				//Inicio Req. 7: Cuando el registro de venta es del tipo 1 se registra el precio de cobro
				if ($Conf_Tipo_Venta==1){
					$query_colum=$query_colum."codigoauxiliar40_2,"; 		$query_valores=$query_valores."'9123',";
					$query_colum=$query_colum."textoAuxiliar40_2,"; 		$query_valores=$query_valores."'".number_format(trim($v['val_preciocobro']), 2, '.', '')."',";
				}else
				{
					$query_colum=$query_colum."codigoauxiliar40_2,"; 		$query_valores=$query_valores."'9123',";
					$query_colum=$query_colum."textoAuxiliar40_2,"; 		$query_valores=$query_valores."'".number_format(trim($val_precioconigv), 2, '.', '')."',";
				}
				//Fin Requerimiento 7
			}
			//Fin requerimiento 4

			$query_colum=$query_colum."importeigv,";					$query_valores=$query_valores."'".number_format(trim($v['val_igv']), 2, '.', '')."',";
			$query_colum=$query_colum."codigorazonexoneracion,";		$query_valores=$query_valores."'".trim($v['tip_afectacion'])."',";

			if ($val_descuento!=''){
				$query_colum=$query_colum."importedescuento,";			$query_valores=$query_valores."'".number_format(trim($val_descuento), 2, '.', '')."',";
			}
			if ($var_codigoimportereferencial!=''){
				$query_colum=$query_colum."codigoimportereferencial,";	$query_valores=$query_valores."'".$var_codigoimportereferencial."',";
			}
			if ($var_importereferencial!=''){
				$query_colum=$query_colum."importereferencial,";		$query_valores=$query_valores."'".$var_importereferencial."',";
			}
			$query_colum=$query_colum."codigoimporteunitarioconimpues)"; $query_valores=$query_valores."'01');";
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
		//FIN INSERCCION: SPE_EINVOICEDETAIL

			$query="delete from sgr_registroproductos_temp where cod_empr='".$prm_cod_empr."' and cod_usu='".$prm_cod_usu."';";
			$this->db_client->query($query);
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				$result['numero']=-6;
				return $result;
			}

		if ($prm_documentomodificar=='')//SI NO HAY DOCUMENTO A MODIFICAR ENTONCES SE ESTA REGISTRANDO UNO NUEVO
		{
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
		}

		$this->db_client->trans_commit();
		$result['result']=1;
		$result['numero']=$prm_numerodocumento;

		return $result;
	}

	function Eliminar_DocumentosUsuario($prm_cod_empr,$prm_cod_usu)
	{

		$this->load->database('ncserver',TRUE);
		$query="delete from sgr_registroproductos_temp where cod_empr='".$prm_cod_empr."' and cod_usu='".$prm_cod_usu."';";
		$this->db->query($query);
		return 1;
	}


	function Listar_Comprobantes($prm_ruc_empr,			$prm_documento_cliente,	$prm_serie_numeroinicio,
		$prm_serie_numerofinal,	$prm_cod_estdoc,		$prm_fec_emisinicio,
		$prm_fec_emisfinal,		$prm_tipo_documentosunat,$prm_estado_documentosunat,
		$prm_tipomoneda, $prm_razonsocialcliente)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$rol_usuario=$_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion'];

		if ($rol_usuario==1) //EMISOR
		{
			$query="";
			$query="
			select
			a.tipodocumentoemisor,
			a.razonsocialadquiriente,
			a.tipodocumento,
			(select b.no_corto
			from tm_tabla_multiple b where b.no_tabla='TIPO_DOCUMENTO' and b.in_habilitado=1
			and b.co_item_tabla in('01','03','07','08')
			and b.co_item_tabla=a.tipodocumento) nomb_tipodocumento,
			a.serienumero,

			(select b.nombre
			from sgr_multitabla b where b.grupo_nombre='TIPO_MONEDA' and b.activo=1
			and b.grupo_id=5
			and b.valorcadena=a.tipomoneda) tipomoneda,


			a.totalventa,
			a.fechaemision,
			a.bl_estadoregistro,
			(select b.no_corto
			from tm_tabla_multiple b where b.no_tabla='ESTADO_DOCUMENTO_PORTAL'
			and b.in_habilitado=1
			and b.co_item_tabla=a.bl_estadoregistro) estado_documento,
			a.inhabilitado,
			b.bl_estadoproceso estadosunat,

			b.bl_mensaje,
			b.bl_mensajesunat,

			a.bl_reintento,
			b.reintento,
			a.numerodocumentoemisor,
			a.visualizado,
			(case when b.bl_estadoRegistro='L' and b.bl_estadoproceso='SIGNED' then '1' else '0' end) mensajeresponse
			from spe_einvoiceheader a
			left join spe_einvoice_response b on
			a.tipodocumentoemisor=b.tipodocumentoemisor
			and a.numerodocumentoemisor=b.numerodocumentoemisor
			and a.tipodocumento=b.tipodocumento
			and a.serienumero=b.serienumero

			";

			$query=$query." where a.numerodocumentoemisor='".$prm_ruc_empr."' ";
			//print_r(prm_documento_cliente);
			//return;
			/*if (substr($prm_documento_cliente,0,1)=='E')
			{
				$query=$query." and a.razonSocialAdquiriente='".$prm_razonsocialcliente."' ";
			}else
			{
				if ($prm_documento_cliente!='')
				{
					$query=$query." and a.numerodocumentoadquiriente='".$prm_documento_cliente."' ";
				}
			}*/

			if ($prm_documento_cliente!='')
			{
				$query=$query." and a.numerodocumentoadquiriente='".$prm_documento_cliente."' ";
			}


			if ($prm_serie_numeroinicio!='' && $prm_serie_numerofinal!='')
			{
				$query=$query." and a.serienumero>='".$prm_serie_numeroinicio."'
				and a.serienumero<='".$prm_serie_numerofinal."' ";
			}
			if ($prm_fec_emisinicio!='' && $prm_fec_emisfinal!='')
			{
				$query=$query." and a.fechaemision>='".$prm_fec_emisinicio."'
							and	a.fechaemision<='".$prm_fec_emisfinal."' "; //+ '1 days'
						}
						if ($prm_cod_estdoc!='0')
						{
							$query=$query." and a.bl_estadoregistro='".$prm_cod_estdoc."' ";
						}

						if ($prm_tipo_documentosunat!='0')
						{
							$query=$query." and a.tipodocumento='".$prm_tipo_documentosunat."' ";
						}

						if ($prm_tipomoneda!='0')
						{
							$query=$query." and a.tipomoneda='".$prm_tipomoneda."' ";
						}

						$query=$query." order by fechaemision;";
			//print_r($query);
					}
		else if ($rol_usuario==2) //RECEPTOR
		{
			$query="";
			$query="
			select
			a.tipodocumentoemisor,
			a.razonsocialemisor razonsocialadquiriente,
			a.tipodocumento,
			(select b.no_corto
			from tm_tabla_multiple b where b.no_tabla='TIPO_DOCUMENTO' and b.in_habilitado=1
			and b.co_item_tabla in('01','03','07','08')
			and b.co_item_tabla=a.tipodocumento) nomb_tipodocumento,
			a.serienumero,
			(select b.nombre
			from sgr_multitabla b where b.grupo_nombre='TIPO_MONEDA' and b.activo=1
			and b.grupo_id=5
			and b.valorcadena=a.tipomoneda) tipomoneda,
			a.totalventa,
			a.fechaemision,
			a.bl_estadoregistro,
			(select b.no_corto
			from tm_tabla_multiple b where b.no_tabla='ESTADO_DOCUMENTO_PORTAL'
			and b.in_habilitado=1
			and b.co_item_tabla=a.bl_estadoregistro) estado_documento,
			a.inhabilitado,
			b.bl_estadoproceso estadosunat,

			b.bl_mensaje,
			b.bl_mensajesunat,

			a.bl_reintento,
			b.reintento,
			a.numerodocumentoemisor,
			a.visualizado,
			(case when b.bl_estadoRegistro='L' and b.bl_estadoproceso='SIGNED' then '1' else '0' end) mensajeresponse
			from spe_einvoiceheader a
			inner join spe_einvoice_response b on
			a.tipodocumentoemisor=b.tipodocumentoemisor
			and a.numerodocumentoemisor=b.numerodocumentoemisor
			and a.tipodocumento=b.tipodocumento
			and a.serienumero=b.serienumero
			";

			$query=$query." where a.numerodocumentoadquiriente='".$prm_ruc_empr."'  and  b.bl_estadoproceso <>'SIGNED'  ";
			//print_r($prm_documento_cliente);
			//return;
			/*if (substr($prm_documento_cliente,0,1)=='E')
			{
				$query=$query." and a.razonSocialAdquiriente='".$prm_razonsocialcliente."' ";
			}else
			{
				$query=$query." and a.numerodocumentoadquiriente='".$prm_ruc_empr."' ";
			}*/

			if ($prm_documento_cliente!='')
			{
				$query=$query." and a.numerodocumentoemisor='".$prm_documento_cliente."' ";
			}
			if ($prm_serie_numeroinicio!='' && $prm_serie_numerofinal!='')
			{
				$query=$query." and a.serienumero>='".$prm_serie_numeroinicio."'
				and a.serienumero<='".$prm_serie_numerofinal."' ";
			}
			if ($prm_fec_emisinicio!='' && $prm_fec_emisfinal!='')
			{
				$query=$query." and a.fechaemision>='".$prm_fec_emisinicio."'
							and	a.fechaemision<='".$prm_fec_emisfinal."' "; //+ '1 days'
						}
						if ($prm_cod_estdoc!='0')
						{
							$query=$query." and a.bl_estadoregistro='".$prm_cod_estdoc."' ";
						}

						if ($prm_tipo_documentosunat!='0')
						{
							$query=$query." and a.tipodocumento='".$prm_tipo_documentosunat."' ";
						}

						if ($prm_tipomoneda!='0')
						{
							$query=$query." and a.tipomoneda='".$prm_tipomoneda."' ";
						}

						$query=$query." order by fechaemision;";
					}
					else
					{
						$query="";
					}

		//print($query);
		//return;

					$consulta =  $this->db_client->query($query);
					return $consulta->result_array();

				}

				function Listar_EstadoDocumento($prm_tipodocumento,$prm_estado)
				{
					$prm_conect_db='ncserver';
					$this->db_client = $this->load->database($prm_conect_db, true);

					$query="";

					if ($prm_tipodocumento=='01')
					{
						$query="select no_largo from tm_tabla_multiple where no_tabla='ESTADO_DOCUMENTOSUNATFACTURA_PORTAL' and in_habilitado=1 and no_corto='".$prm_estado."';";
					}
					else if ($prm_tipodocumento=='03')
					{
						$query="select no_largo from tm_tabla_multiple where no_tabla='ESTADO_DOCUMENTOSUNATBOLETA_PORTAL' and in_habilitado=1 and no_corto='".$prm_estado."';";
					}
					else
					{
						$query="
						select no_largo from
						(
						select no_largo from tm_tabla_multiple where no_tabla='ESTADO_DOCUMENTOSUNATFACTURA_PORTAL' and in_habilitado=1 and no_corto='".$prm_estado."'
						union all
						select no_largo from tm_tabla_multiple where no_tabla='ESTADO_DOCUMENTOSUNATBOLETA_PORTAL' and in_habilitado=1 and no_corto='".$prm_estado."'
						) a group by no_largo;";
					}

					$consulta =  $this->db_client->query($query);
					$listaestado=$consulta->result_array();
					$estadodocumento='';

		//print_r($query);

		if(!empty($listaestado))//SI NO ES NULO O VACIO
		{
			$estadodocumento=$listaestado[0]['no_largo'];
		}

		return $estadodocumento;//$consulta->result_array();
	}

	function Listar_DetalleDocumento($prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);


		$query="";
		$query="select
		a.razonsocialemisor,
		a.nombrecomercialemisor,
		a.direccionemisor,
		a.distritoemisor,
		a.departamentoemisor,
		a.provinciaemisor,
		a.numerodocumentoemisor,
		a.tipodocumentoemisor,
		(case 	when a.tipodocumentoemisor='0' then 'Doc.Trib.No.Dom.Sin.Ruc'
		when a.tipodocumentoemisor='1' then 'DNI'
		when a.tipodocumentoemisor='4' then 'Carnet de Extranjeria'
		when a.tipodocumentoemisor='6' then 'RUC'
		when a.tipodocumentoemisor='7' then 'Pasaporte'
		when a.tipodocumentoemisor='A' then 'Ced. Diplomatica de Identidad' end
		)nombre_tipodocumentoemisor,
		a.serienumero,
		a.tipodocumento,
		(select aa.no_corto from tm_tabla_multiple aa
		where aa.no_tabla='TIPO_DOCUMENTO' and aa.in_habilitado=1
		and aa.co_item_tabla in('01','03','07','08') and a.tipodocumento=aa.co_item_tabla ) nombre_tipodocumento,
		a.fechaemision,
		a.numerodocumentoadquiriente,
		a.razonsocialadquiriente,
		a.lugardestino,
		a.textoleyenda_1,
		a.tipomoneda,
		(select c.nombre from sgr_multitabla c where c.grupo_nombre='TIPO_MONEDA' and c.activo=1
		and c.grupo_id=5 and c.valorcadena=a.tipomoneda) tipomonedadescripcion,
		a.totalbonificacion,
		a.totaldescuentos,
		a.totaldetraccion,
		a.totaligv,
		a.totalvalorventanetoopexonerada,
		a.totalvalorventanetoopgratuitas,
		a.totalvalorventanetoopgravadas,
		a.totalvalorventanetoopnogravada,
		a.totalventa,
		b.numeroordenitem,
		b.codigoproducto,
		b.descripcion,
		b.unidadmedida,
		b.cantidad,
		b.importeunitariosinimpuesto,
		b.importeunitarioconimpuesto,
		b.importedescuento,
		b.importetotalsinimpuesto
		from spe_einvoiceheader a
		inner join spe_einvoicedetail b on
		a.tipodocumentoemisor=b.tipodocumentoemisor and
		a.numerodocumentoemisor=b.numerodocumentoemisor and
		a.tipodocumento=b.tipodocumento and
		a.serienumero=b.serienumero
		where a.numerodocumentoemisor='".$prm_ruc_empremisor."' and a.tipodocumento='".$prm_tipo_documento."' and a.serienumero='".$prm_serie_numero."'
		order by a.tipodocumento,a.serienumero,b.numeroordenitem;";

		$consulta =  $this->db_client->query($query);
		return $consulta->result_array();

	}


	function existe_comprobante($prm_tipodedocumento,$prm_serienumero,$prm_montototal,$prm_fechaemision,$prm_rucproveedor)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";
		$query="select count(a.numerodocumentoemisor) cantidad
		from spe_einvoiceheader a
		inner join spe_einvoice_response b
		on a.tipodocumentoemisor=b.tipodocumentoemisor
		and a.numerodocumentoemisor=b.numerodocumentoemisor
		and a.serienumero=b.serienumero
		and a.tipodocumento=b.tipodocumento
		where a.serienumero= '".$prm_serienumero."'
		and a.tipodocumento='".$prm_tipodedocumento."'
		and a.totalventa='".$prm_montototal."'
		and a.fechaemision='".$prm_fechaemision."'
		and a.numerodocumentoemisor='".$prm_rucproveedor."'
		and b.bl_estadoproceso<>'SIGNED' ";

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

		//print_r($query);
		//return;
		$consulta =  $this->db_client->query($query);
		return $consulta->result_array();
	}

	function Cambiar_EstadoBorradorAdeclarar($prm_tipodocumentoemisor,$prm_numerodocumentoemisor,$prm_datosseleccionados)
	{
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();

		$datos_seleccionados=explode(',',$prm_datosseleccionados);
		if(!empty($datos_seleccionados))//SI NO ES NULO O VACIO
		{
			foreach($datos_seleccionados as $key=>$v):
				if(strlen($v)>5)//evitamos espacios
			{
				$datos_documento=explode('-',$v);
				$query="";
				$query="update spe_einvoiceheader
				set
				bl_estadoregistro='A'
				where
				tipodocumentoemisor='".$prm_tipodocumentoemisor."'
				and numerodocumentoemisor='".$prm_numerodocumentoemisor."'
				and tipodocumento= '".$datos_documento[0]."'
				and serienumero= '".$datos_documento[1].'-'.$datos_documento[2]."'";

				$this->db_client->query($query);

					//print_r($query);
					//return;

				if ($this->db_client->trans_status() === FALSE)
				{
					$this->db_client->trans_rollback();
					$result['result']=0;
					return $result;
				}
			}
			endforeach;
		}

		$this->db_client->trans_commit();
		$result['result']=1;

		return $result;

	}

	function Listar_DatosDocumentoModificar($prm_tipodocumentoemisor,$prm_numerodocumentoemisor,$prm_tipodedocumento,$prm_serienumero)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";
		$query="select
		a.tipodocumentoemisor,
		a.numerodocumentoemisor,
		a.tipodocumento,
		a.serienumero,
		a.correoemisor,
		a.correoadquiriente,
		a.razonsocialemisor,
		a.nombrecomercialemisor,
		a.fechaemision,
		a.ubigeoemisor,
		a.direccionemisor,
		a.urbanizacion,
		a.provinciaemisor,
		a.departamentoemisor,
		a.distritoemisor,
		a.paisemisor,
		a.numerodocumentoadquiriente,
		a.tipodocumentoadquiriente,
		a.razonsocialadquiriente,
		a.tipomoneda,
		a.totalvalorventanetoopgravadas,
		a.totalvalorventanetoopnogravada,
		a.totalvalorventanetoopexonerada,
		a.totaligv,
		a.totaldescuentos,
		a.totalventa,
		a.textoleyenda_1,
		a.codigoleyenda_1,
		a.bl_estadoregistro,
		a.totaldetraccion,
		a.valorreferencialdetraccion,
		a.porcentajedetraccion,
		a.descripciondetraccion,
		a.textoleyenda_2,
		a.codigoleyenda_2,
		a.descuentosglobales,
		a.inhabilitado,
		a.textoleyenda_3,
		a.codigoleyenda_3,
		a.porcentajepercepcion,
		a.baseimponiblepercepcion,
		a.totalpercepcion,
		a.totalventaconpercepcion,
		a.totalvalorventanetoopgratuitas,
		a.codigoserienumeroafectado,
		a.serienumeroafectado,
		a.motivodocumento,
		a.tipodocumentoreferenciaprincip,
		a.numerodocumentoreferenciaprinc,
		a.tipodocumentoreferenciacorregi,
		a.numerodocumentoreferenciacorre,
		a.bl_origen,

		b.numeroordenitem,
		b.codigoproducto,
		b.descripcion,
		b.cantidad,
		b.unidadmedida,
		b.importetotalsinimpuesto,
		b.importeunitariosinimpuesto,
		b.importeunitarioconimpuesto,
		b.codigoimporteunitarioconimpues,
		b.importeigv,
		b.codigorazonexoneracion,
		b.importedescuento,
		b.importecargo,
		b.importereferencial,
		b.codigoimportereferencial,
		b.textoAuxiliar40_2

		from spe_einvoiceheader a
		inner join spe_einvoicedetail b on
		a.tipodocumentoemisor=b.tipodocumentoemisor
		and a.numerodocumentoemisor=b.numerodocumentoemisor
		and a.tipodocumento=b.tipodocumento
		and a.serienumero=b.serienumero

		where a.tipodocumentoemisor='".$prm_tipodocumentoemisor."'
		and a.numerodocumentoemisor='".$prm_numerodocumentoemisor."'
		and a.tipodocumento='".$prm_tipodedocumento."'
		and a.serienumero='".$prm_serienumero."'
		";
		//print_r($query);
		//return;
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
				$query="update spe_einvoice_response set reintento=0 where tipodocumentoemisor='6' and numerodocumentoemisor='".$prm_ruc_empr."'
				and tipodocumento='".$detalle_documento[0]."' and serienumero='".$detalle_documento[1].'-'.$detalle_documento[2]."';";
			}
			else
			{
				$query="update spe_einvoiceheader set bl_reintento=0 where tipodocumentoemisor='6' and numerodocumentoemisor='".$prm_ruc_empr."'
				and tipodocumento='".$detalle_documento[0]."' and serienumero='".$detalle_documento[1].'-'.$detalle_documento[2]."';";
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


	function Actualizar_VistaDocumento($prm_ruc_empr,$prm_tipodocumento,$prm_serienumero)
	{
		$result['result']=0;
		$this->db_client =$this->load->database('ncserver',TRUE);

		$query="update spe_einvoiceheader set visualizado=1 where tipodocumentoemisor='6' and numerodocumentoemisor='".$prm_ruc_empr."'
		and tipodocumento='".$prm_tipodocumento."' and serienumero='".$prm_serienumero."' and visualizado=0;";

		$this->db_client->query($query);

		$result['result']=1;


		return $result;
	}

	function Buscar_DetalleCaracteristica($prm_tipodocumentoemisor,$prm_numerodocumentoemisor,$prm_tipodedocumento,$prm_serienumero)//,$prm_clave)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";
		$query="select clave, valor from spe_einvoiceheader_add
		where
		tipodocumentoemisor='".$prm_tipodocumentoemisor."'
		and numerodocumentoemisor='".$prm_numerodocumentoemisor."'
		and tipodocumento= '".$prm_tipodedocumento."'
		and serienumero= '".$prm_serienumero."';" ;
				//and clave= '".$prm_clave."' ";
		$consulta =  $this->db_client->query($query);
		return $consulta->result_array();

	}

	function Buscar_Documento($prm_tipodocumentoemisor,$prm_numerodocumentoemisor,$prm_tipodedocumento,$prm_serienumero)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";
		$query="select fechaemision from spe_einvoiceheader
		where
		tipodocumentoemisor='".$prm_tipodocumentoemisor."'
		and numerodocumentoemisor='".$prm_numerodocumentoemisor."'
		and tipodocumento= '".$prm_tipodedocumento."'
		and serienumero='".$prm_serienumero."' ;";
		$consulta =  $this->db_client->query($query);
		return $consulta->result_array();
	}

	function Buscar_UltimaFechaDocumento($prm_tipodocumentoemisor,$prm_numerodocumentoemisor,$prm_tipodedocumento,$prm_serienumero)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";
		$query="select fechaemision from spe_einvoiceheader
		where
		tipodocumentoemisor='".$prm_tipodocumentoemisor."'
		and numerodocumentoemisor='".$prm_numerodocumentoemisor."'
		and tipodocumento= '".$prm_tipodedocumento."'
		and serienumero='".$prm_serienumero."' ;";
		$consulta =  $this->db_client->query($query);
		return $consulta->result_array();
	}

	function Buscar_PrimeraFechaDocumento($prm_tipodocumentoemisor,$prm_numerodocumentoemisor,$prm_tipodedocumento,$prm_serienumero)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";
		$query="select fechaemision from spe_einvoiceheader
		where
		tipodocumentoemisor='".$prm_tipodocumentoemisor."'
		and numerodocumentoemisor='".$prm_numerodocumentoemisor."'
		and tipodocumento= '".$prm_tipodedocumento."'
		and serienumero< '".$prm_serienumero."' order by  serienumero desc;";

		//print_r($query);
		//return;

		$consulta =  $this->db_client->query($query);
		return $consulta->result_array();
	}

	function Listar_DatosAdicionales($prm_tipodocumentoemisor)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);
		$query="";
		$query="select codigo, observacion, numerodocumentoemisor from bl_adicionales_auxiliares
		where numerodocumentoemisor='".$prm_tipodocumentoemisor."' and codigo<>'9371' order by orden;";
		$consulta =  $this->db_client->query($query);
		return $consulta->result_array();
	}

}
