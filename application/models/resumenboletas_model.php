<?php
@session_start();
class Resumenboletas_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}


	function Guardar_ResumenBoletas($prm_cod_usu,$prm_cod_empr,$prm_documento,$prm_ruc_empr,$prm_est_declarar,$prm_fec_doc,$prm_tip_docemisor)
	{
 		$result['result']=0;
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();

		$lista_documento=explode(',',$prm_documento);

		foreach($lista_documento as $key=>$v):
			 $detalle_documento=explode('-',$v);

			 $query="
				insert into sgr_resumenboletas_temp
				(
				  cod_usu,
				  ser_doc,
				  num_doc,
				  tip_reg,
				  cod_empr,
				  tip_doc,
				  fec_emision,
				  tip_docemisor,
				  est_declarar
				)
				values
				(
					'".$prm_cod_usu."',
					'".$detalle_documento[1]."',
					'".$detalle_documento[1].'-'.$detalle_documento[2]."',
					1,
					'".$prm_cod_empr."',
					'".$detalle_documento[0]."',
					'".$prm_fec_doc."',
					'".$prm_tip_docemisor."',
					'".$prm_est_declarar."'
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


		endforeach;

		$this->db_client->trans_commit();
		$result['result']=1;


		return $result;
	}


	function Listar_DocumentosdeResumen($prm_cod_usu,$prm_cod_empr,$prm_ruc_empr,$prm_fechabusqueda)
	{

		$this->load->database('ncserver',TRUE);
		$query="
			select tmp_reg,
			  ser_doc,
			  num_doc,
			  tip_reg,
			  cod_empr,
			  (select b.no_corto from tm_tabla_multiple b where b.no_tabla='TIPO_DOCUMENTO' and b.in_habilitado=1 and b.co_item_tabla=a.tip_doc) tip_doc,
			  fec_emision,
			  tip_docemisor,
			  '' bl_estadoproceso,
			  '0' cod_tipdoc,
			  (case when est_declarar=1 then 'ADICIONAR' when est_declarar=2 then 'ANULAR' when est_declarar=3 then 'DAR DE BAJA' end ) est_declarar,

				(case when b.tipomoneda='PEN' then 'Soles' else 'Otros' end) moneda,
				b.totalvalorventanetoopgravadas op_gravado,
				b.totaligv igv,
				b.totalvalorventanetoopexonerada op_exonerado,
				b.totalvalorventanetoopnogravada op_nogravado,
				b.totalvalorventanetoopgratuitas op_gratis,
				b.totalventa imp_total

			from sgr_resumenboletas_temp a
				inner join spe_einvoiceheader b on b.numerodocumentoemisor='".$prm_ruc_empr."'
							and b.serienumero=a.num_doc and b.tipodocumento=a.tip_doc
			where cod_usu='".$prm_cod_usu."'
				and cod_empr='".$prm_cod_empr."' ";

		$query=$query."union all

		select
			0 tmp_reg,
			'' ser_doc,
			a.serienumero num_doc,
			1 tip_reg,
			0 cod_empr,
			(select b.no_corto from tm_tabla_multiple b where b.no_tabla='TIPO_DOCUMENTO' and b.in_habilitado=1 and b.co_item_tabla=a.tipodocumento) tip_doc ,
			b.fechaEmision fec_emision,
			a.tipodocumentoemisor tip_docemisor,
			upper(a.bl_estadoproceso) bl_estadoproceso,
			a.tipodocumento cod_tipdoc,
			'' est_declarar,

			(case when b.tipomoneda='PEN' then 'Soles' else 'Otros' end) moneda,
			b.totalvalorventanetoopgravadas op_gravado,
			b.totaligv igv,
			b.totalvalorventanetoopexonerada op_exonerado,
			b.totalvalorventanetoopnogravada op_nogravado,
			b.totalvalorventanetoopgratuitas op_gratis,
			b.totalventa imp_total

		from spe_einvoice_response a
			inner join spe_einvoiceheader b on b.numerodocumentoemisor=a.numerodocumentoemisor
							and b.serienumero=a.serienumero and b.tipodocumento=a.tipodocumento
		where a.numerodocumentoemisor='".$prm_ruc_empr."'
			and a.serienumero like 'B%'
				
			and a.tipodocumento in ('03','07','08')
			and b.fechaEmision='".$prm_fechabusqueda."'

			and not exists(select tmp_reg from sgr_resumenboletas_temp aa where aa.num_doc=a.serienumero
				and aa.tip_doc=a.tipodocumento
				and aa.cod_empr='".$prm_cod_empr."')

			";

		$query=$query.";";
		//print_r($query);

		$consulta=$this->db->query($query);

		return $consulta->result_array();
	}

	function Listar_TipodeDocumento()
	{

		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla,no_corto from tm_tabla_multiple where no_tabla='TIPO_DOCUMENTO' and in_habilitado=1 and co_item_tabla in('01','03','07','08');";
		$consulta=$this->db->query($query);
		return $consulta->result_array();
	}

	function Guardar_SummaryHeader
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
		$prm_cod_usu,
		$prm_ruc_empr
	)
	{
		$result['result']=0;
		$this->db_client =$this->load->database('ncserver',TRUE);

		$this->db_client->trans_begin();

		$query="
			insert into spe_summaryheader
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
				bl_reintento
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
				'".$prm_bl_reintento."'
			 );";

		//print_r($query);
		$this->db_client->query($query);
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}

$consulta = $this->db_client->query("select 	
								a.bl_estadoProceso,							
								b.serieNumero 'ser_doc',
								b.tipoDocumento 'tip_doc',
								b.tipodocumentoadquiriente,
								SUBSTRING(b.serieNumero,1,4) 'ser_gru',
								SUBSTRING(b.serieNumero,6,8) 'num_doc',
								b.numerodocumentoadquiriente ,
								b.tipomoneda,
								b.totalvalorventanetoopgravadas,
								b.totaligv,
								b.totalisc,
								b.totalotroscargos,
								b.totalotrostributos,
								b.totalvalorventanetoopexonerada,
								b.totalvalorventanetoopgratuitas,
								b.totalvalorventanetoopgravadas,
								b.totalvalorventanetoopnogravada,
								b.totalventa

								 from SPE_EINVOICEHEADER b join SPE_EINVOICE_RESPONSE a on b.serieNumero = a.serieNumero
								 where b.numeroDocumentoEmisor = '".$prm_ruc_empr."'
								 and b.tipoDocumento = '03' 
								 and b.fechaEmision = '".$prm_fechaemisioncomprobante."' ;");
/*		
		$consulta = $this->db_client->query("select
								a.tmp_reg,
								a.ser_doc,
								a.tip_doc,
								a.num_doc,
								b.tipodocumentoadquiriente,
								b.numerodocumentoadquiriente,
								b.tipomoneda,
								b.totalvalorventanetoopgravadas,
								b.totaligv,
								b.totalisc,
								b.totalotroscargos,
								b.totalotrostributos,
								b.totalvalorventanetoopexonerada,
								b.totalvalorventanetoopgratuitas,
								b.totalvalorventanetoopgravadas,
								b.totalvalorventanetoopnogravada,
								b.totalventa

							from sgr_resumenboletas_temp a
								inner join spe_einvoiceheader b on b.numerodocumentoemisor='".$prm_ruc_empr."'
											and b.serienumero=a.num_doc and b.tipodocumento=a.tip_doc
							where a.cod_usu='".$prm_cod_usu."'
								and a.cod_empr='".$prm_cod_empr."' ;");
*/
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}
		$detalledocumento=$consulta->result_array();
		$contador=1;
		foreach($detalledocumento as $key=>$v):

				if($v['bl_estadoProceso'] == 'SIGNED' || !$v['bl_estadoProceso']){
					$this->db_client->trans_rollback();
					$result['result']=2;
					return $result;
				}

				$aux_totaligv = (trim($v['totaligv']) == '') ? '0.00' : trim($v['totaligv']);
				$aux_totalisc = (trim($v['totalisc']) == '') ? '0.00' : trim($v['totalisc']);
				$aux_totalotroscargos = (trim($v['totalotroscargos']) == '') ? '0.00' : trim($v['totalotroscargos']);
				$aux_totalotrostributos = (trim($v['totalotrostributos']) == '') ? '0.00' : trim($v['totalotrostributos']);
				$aux_totalvalorventanetoopexonerada = (trim($v['totalvalorventanetoopexonerada']) == '') ? '0.00' : trim($v['totalvalorventanetoopexonerada']);
				$aux_totalvalorventanetoopgratuitas = (trim($v['totalvalorventanetoopgratuitas']) == '') ? '0.00' : trim($v['totalvalorventanetoopgratuitas']);
				$aux_totalvalorventanetoopgravadas = (trim($v['totalvalorventanetoopgravadas']) == '') ? '0.00' : trim($v['totalvalorventanetoopgravadas']);
				$aux_totalvalorventanetoopnogravada = (trim($v['totalvalorventanetoopnogravada']) == '') ? '0.00' : trim($v['totalvalorventanetoopnogravada']);
				$aux_totalventa = (trim($v['totalventa']) == '') ? '0.00' : trim($v['totalventa']);


			$query="insert into SPE_SUMMARYDETAIL
			(
			numeroDocumentoEmisor
           ,tipoDocumentoEmisor
           ,resumenId
           ,numeroFila
           ,numeroCorrelativoFin
           ,numeroCorrelativoInicio
           ,serieGrupoDocumento
           ,tipoDocumento
           ,tipoMoneda
           ,totalIgv
           ,totalIsc
           ,totalOtrosCargos
           ,totalOtrosTributos
           ,totalValorVentaOpExoneradasIgv
           ,totalValorVentaOpGratuitas
           ,totalValorVentaOpGravadaConIgv
           ,totalValorVentaOpInafectasIgv
           ,totalVenta
			)
			values
			(
				'".$prm_numerodocumentoemisor."',
				'".$prm_tipodocumentoemisor."',
					'".$prm_resumenid."',
					'".$contador."',
					'".trim($v['num_doc'])."',
					'".trim($v['num_doc'])."',
					'".trim($v['ser_gru'])."',
				'".trim($v['tipodocumentoadquiriente'])."',
				'".trim($v['tipomoneda'])."',
				'".$aux_totaligv."',
				'".$aux_totalisc."',
				'".$aux_totalotroscargos."',
				'".$aux_totalotrostributos."',
				'".$aux_totalvalorventanetoopexonerada."',
				'".$aux_totalvalorventanetoopgratuitas."',
				'".$aux_totalvalorventanetoopgravadas."',
				'".$aux_totalvalorventanetoopnogravada."',
				'".$aux_totalventa."'
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



/*
		$query="delete from sgr_resumenboletas_temp where cod_empr='".$prm_cod_empr."' and cod_usu ='".$prm_cod_usu."';";
		$this->db_client->query($query);
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}
*/
		$nuevocodigo=explode('-',$prm_resumenid);
		if (number_format($nuevocodigo[2], 2, '.', '')==1)
		{
			$query="insert into sgr_correlativoresumen(cod_empr,tip_resum,fec_resum,num_corre)values(".$prm_cod_empr.",2,'".$prm_fechageneracionresumen."',1);";
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
					where cod_empr=".$prm_cod_empr." and tip_resum=2 and fec_resum='".$prm_fechageneracionresumen."' and est_reg=1;";
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



	function Eliminar_DocumentoBoletaBaja($prm_tmp_reg)
	{
		$result['result']=0;
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();
		$query="delete from sgr_resumenboletas_temp where tmp_reg='".$prm_tmp_reg."';";
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
				where cod_empr=".$prm_cod_empr." and tip_resum=2 and fec_resum='".$prm_fecha."' and est_reg=1;";
		$consulta=$this->db->query($query);
		//print_r($prm_fecha);
		return $consulta->result_array();
	}

	function Eliminar_Resumenboletas($prm_cod_empr,$prm_cod_usu)
	{

		$this->load->database('ncserver',TRUE);
		$query="delete from sgr_resumenboletas_temp where cod_empr='".$prm_cod_empr."' and cod_usu= '".$prm_cod_usu."';";
		$this->db->query($query);
		return 1;
	}

	function Listar_SummaryHeader($prm_ruc_empr,$prm_cod_resum,$prm_fec_geninicio,
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
				 b.bl_fechaenviosunat,
				 (case when b.bl_estadoRegistro='L' and b.bl_estadoproceso='SIGNED' then '1' else '0' end) mensajeresponse
				from spe_summaryheader a
					left join spe_summary_response b on
									a.tipodocumentoemisor=b.tipodocumentoemisor
									and a.numerodocumentoemisor=b.numerodocumentoemisor
									and a.resumenid=b.resumenid";

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

		//echo($query);


		$consulta =  $this->db_client->query($query);
		return $consulta->result_array();

	}


	function Listar_SummaryHeaderDetalle($prm_ruc_empr,$prm_resumenid)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);

		$query="";//spe_summary_item
		/*
		$query="
				select
					a.numerodocumentoemisor,
					a.tipodocumentoemisor,
					a.resumenid,
					a.numerofila,
					a.numerocorrelativoinicio numerocorrelativo,
					(select b.no_corto from tm_tabla_multiple b where b.no_tabla='TIPO_DOCUMENTO'
						and b.in_habilitado=1 and b.co_item_tabla=a.tipodocumento) tipodocumento,
					b.fechaemisioncomprobante
				from spe_summarydetail a
					inner join spe_summaryheader b on a.numerodocumentoemisor=b.numerodocumentoemisor
						and a.tipodocumentoemisor=b.tipodocumentoemisor
						and a.resumenid=b.resumenid";

		$query=$query." where a.numerodocumentoemisor='".$prm_ruc_empr."' and a.resumenid='".$prm_resumenid."' ";
		$query=$query." order by a.numerofila;";
		*/


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
					b.numerocorrelativofin,
					b.numerocorrelativoinicio,
					b.seriegrupodocumento,
					b.tipodocumento,
					b.tipomoneda,
					b.totaligv,
					b.totalisc,
					b.totalotroscargos,
					b.totalotrostributos,
					b.totalvalorventaopexoneradasigv,
					b.totalvalorventaopgratuitas,
					b.totalvalorventaopgravadaconigv,
					b.totalvalorventaopinafectasigv,
					b.totalventa,
					(select aa.no_corto from tm_tabla_multiple aa
						where aa.no_tabla='TIPO_DOCUMENTO' and aa.in_habilitado=1
							and aa.co_item_tabla in('01','03','07','08') and b.tipodocumento=aa.co_item_tabla ) nombre_tipodocumento,
					c.bl_estadoproceso estadosunat

					from spe_summaryheader a
						inner join spe_summarydetail b on
							a.tipodocumentoemisor=b.tipodocumentoemisor and
							a.numerodocumentoemisor=b.numerodocumentoemisor and
							a.resumenid=b.resumenid
						left join spe_summary_response c on
							a.tipodocumentoemisor=c.tipodocumentoemisor and
							a.numerodocumentoemisor=c.numerodocumentoemisor and
							a.resumenid=c.resumenid
					where a.tipodocumentoemisor='6' and a.numerodocumentoemisor='".$prm_ruc_empr."' and a.resumenid='".$prm_resumenid."'
					order by b.numerofila;";

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
					$query="update spe_summary_response set reintento=0 where tipodocumentoemisor='6' and numerodocumentoemisor='".$prm_ruc_empr."'
						and resumenid='".$detalle_documento[0].'-'.$detalle_documento[1].'-'.$detalle_documento[2]."';";
				}
				else
				{
					$query="update spe_summaryheader set bl_reintento=0 where tipodocumentoemisor='6' and numerodocumentoemisor='".$prm_ruc_empr."'
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
					b.numerocorrelativofin,
					b.numerocorrelativoinicio,
					b.seriegrupodocumento,
					b.tipodocumento,
					b.tipomoneda,
					b.totaligv,
					b.totalisc,
					b.totalotroscargos,
					b.totalotrostributos,
					b.totalvalorventaopexoneradasigv,
					b.totalvalorventaopgratuitas,
					b.totalvalorventaopgravadaconigv,
					b.totalvalorventaopinafectasigv,
					b.totalventa,
					(select aa.no_corto from tm_tabla_multiple aa
						where aa.no_tabla='TIPO_DOCUMENTO' and aa.in_habilitado=1
							and aa.co_item_tabla in('01','03','07','08') and b.tipodocumento=aa.co_item_tabla ) nombre_tipodocumento,
					c.bl_estadoproceso estadosunat

					from spe_summaryheader a
						inner join spe_summarydetail b on
							a.tipodocumentoemisor=b.tipodocumentoemisor and
							a.numerodocumentoemisor=b.numerodocumentoemisor and
							a.resumenid=b.resumenid
						left join spe_summary_response c on
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

	function Declarar_Comprobante($prm_ruc,$prm_comprobante,$prm_tipo_doc)
	{
		$result['result']=0;
		//return $result;
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();
		$query="delete from SPE_EINVOICE_RESPONSE
					where serieNumero = '".$prm_comprobante."' and 
					numerodocumentoemisor = '".$prm_ruc."' and
					tipoDocumento = '".$prm_tipo_doc."'	";
		$this->db_client->query($query);

		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}

		$query="update SPE_EINVOICEHEADER set bl_estadoRegistro = 'A', bl_reintento = '0'
					where serieNumero = '".$prm_comprobante."' and 
					numerodocumentoemisor = '".$prm_ruc."' and
					tipoDocumento = '".$prm_tipo_doc."'	";
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

}
