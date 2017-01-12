<?php
@session_start();
class Estadistica_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();		
	}
	
	
	function Listar_Comprobantes($prm_ruc_empr,$prm_documento_cliente,$prm_serie_numeroinicio,
					$prm_serie_numerofinal,$prm_cod_estdoc,$prm_fec_emisinicio,$prm_fec_emisfinal,
					$prm_tipo_documentosunat,$prm_estado_documentosunat,$prm_cod_empr,$prm_cod_usu)
	{
		$prm_conect_db='ncserver';
		$this->db_client = $this->load->database($prm_conect_db, true);
		
		$rol_usuario=$_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion'];

		$this->db_client->query("delete from sgr_estadisticadocumentos where cod_emp=".$prm_cod_empr." and cod_usu=".$prm_cod_usu."");


		if ($rol_usuario==1) //EMISOR
		{//RESUMEN DE FACTURA einvoiceheader
			$query="";
			$query="
					insert into sgr_estadisticadocumentos(cod_emp,cod_usu,tipo_docemis,num_docemis,tipo_docum,nume_docum,est_reglocal,est_regsunat,cont_estadolocal)
					select 
						".$prm_cod_empr.",
						".$prm_cod_usu.",
						a.tipodocumentoemisor,
						a.numerodocumentoemisor,						
						a.tipodocumento,
						a.serienumero,					
						a.bl_estadoregistro,
						b.bl_estadoproceso,
						(case when a.bl_estadoregistro='B' then 7 
							when a.bl_estadoregistro='A' then 8 
							when a.bl_estadoregistro='L' then 9 
							when a.bl_estadoregistro='E' then 10
							else 0 end)
					from spe_einvoiceheader a
						left join spe_einvoice_response b on 
										a.tipodocumentoemisor=b.tipodocumentoemisor 
										and a.numerodocumentoemisor=b.numerodocumentoemisor
										and a.tipodocumento=b.tipodocumento
										and a.serienumero=b.serienumero						
					";
			
			$query=$query." where a.numerodocumentoemisor='".$prm_ruc_empr."' ";
			
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
			if ($prm_estado_documentosunat!='0')	
			{
				$query=$query." and b.bl_estadoproceso like'%".$prm_estado_documentosunat."%' ";
			}
			//print_r($query);
			$this->db_client->query($query);//SE INSERTA 	
			
			
			//RESUMEN DE BOLETA SUMARIHERADER
			$query="";
			$query="
					insert into sgr_estadisticadocumentos(cod_emp,cod_usu,tipo_docemis,num_docemis,tipo_docum,nume_docum,est_reglocal,est_regsunat,cont_estadolocal)
					select 
						".$prm_cod_empr.",
						".$prm_cod_usu.",
						a.tipodocumentoemisor,
						a.numerodocumentoemisor,						
						98,
						a.resumenid,					
						a.bl_estadoregistro,
						b.bl_estadoproceso,
						(case when a.bl_estadoregistro='B' then 7 
							when a.bl_estadoregistro='A' then 8 
							when a.bl_estadoregistro='L' then 9 
							when a.bl_estadoregistro='E' then 10
							else 0 end)
					from spe_summaryheader a
						left join spe_summary_response b on 
										a.tipodocumentoemisor=b.tipodocumentoemisor 
										and a.numerodocumentoemisor=b.numerodocumentoemisor
										and a.resumenid=b.resumenid				
					";
			
			$query=$query." where a.numerodocumentoemisor='".$prm_ruc_empr."' ";
			
	
			if ($prm_fec_emisinicio!='' && $prm_fec_emisfinal!='')	
			{
				$query=$query." and a.fechageneracionresumen>='".$prm_fec_emisinicio."'
							and	a.fechageneracionresumen<='".$prm_fec_emisfinal."' "; //+ '1 days'
			}
			if ($prm_cod_estdoc!='0')	
			{
				$query=$query." and a.bl_estadoregistro='".$prm_cod_estdoc."' ";
			}
			
			if ($prm_estado_documentosunat!='0')	
			{
				$query=$query." and b.bl_estadoproceso like'%".$prm_estado_documentosunat."%' ";
			}
			//print_r($query);
			$this->db_client->query($query);//SE INSERTA 	
			
			//RESUMEN DE BAJAS
			$query="";
			$query="
					insert into sgr_estadisticadocumentos(cod_emp,cod_usu,tipo_docemis,num_docemis,tipo_docum,nume_docum,est_reglocal,est_regsunat,cont_estadolocal)
					select 
						".$prm_cod_empr.",
						".$prm_cod_usu.",
						a.tipodocumentoemisor,
						a.numerodocumentoemisor,						
						99,
						a.resumenid,					
						a.bl_estadoregistro,
						b.bl_estadoproceso,
						(case when a.bl_estadoregistro='B' then 7 
							when a.bl_estadoregistro='A' then 8 
							when a.bl_estadoregistro='L' then 9 
							when a.bl_estadoregistro='E' then 10
							else 0 end)
					from spe_cancelheader a
						left join spe_cancel_response b on 
										a.tipodocumentoemisor=b.tipodocumentoemisor 
										and a.numerodocumentoemisor=b.numerodocumentoemisor
										and a.resumenid=b.resumenid				
					";
			
			$query=$query." where a.numerodocumentoemisor='".$prm_ruc_empr."' ";
			
	
			if ($prm_fec_emisinicio!='' && $prm_fec_emisfinal!='')	
			{
				$query=$query." and a.fechageneracionresumen>='".$prm_fec_emisinicio."'
							and	a.fechageneracionresumen<='".$prm_fec_emisfinal."' "; //+ '1 days'
			}
			if ($prm_cod_estdoc!='0')	
			{
				$query=$query." and a.bl_estadoregistro='".$prm_cod_estdoc."' ";
			}
			if ($prm_estado_documentosunat!='0')	
			{
				$query=$query." and b.bl_estadoproceso like'%".$prm_estado_documentosunat."%' ";
			}
			
			$this->db_client->query($query);//SE INSERTA 
		}
		else if ($rol_usuario==2) //RECEPTOR
		{
			
			$query="";
			$query="
					insert into sgr_estadisticadocumentos(cod_emp,cod_usu,tipo_docemis,num_docemis,tipo_docum,nume_docum,est_reglocal,est_regsunat,cont_estadolocal)
					select 
						".$prm_cod_empr.",
						".$prm_cod_usu.",
						a.tipodocumentoemisor,
						a.numerodocumentoemisor,						
						a.tipodocumento,
						a.serienumero,					
						a.bl_estadoregistro,
						b.bl_estadoproceso,
						(case when a.bl_estadoregistro='B' then 7 
							when a.bl_estadoregistro='A' then 8 
							when a.bl_estadoregistro='L' then 9 
							when a.bl_estadoregistro='E' then 10
							else 0 end)
					from spe_einvoiceheader a
						left join spe_einvoice_response b on 
										a.tipodocumentoemisor=b.tipodocumentoemisor 
										and a.numerodocumentoemisor=b.numerodocumentoemisor
										and a.tipodocumento=b.tipodocumento
										and a.serienumero=b.serienumero						
					";
			
			$query=$query." where a.numerodocumentoadquiriente='".$prm_ruc_empr."' ";
			
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
			if ($prm_estado_documentosunat!='0')	
			{
				$query=$query." and b.bl_estadoproceso like'%".$prm_estado_documentosunat."%' ";
			}
			//print_r($query);
			$this->db_client->query($query);//SE INSERTA 	
			
		}
		else
		{
			$query="";
		}
		//print($query);
		//return;

	
		
		//ELIMINAMOS LOS REGISTROS QUE SON A=AGREGADO
		$this->db_client->query("delete from sgr_estadisticadocumentos where cod_emp=".$prm_cod_empr." and cod_usu=".$prm_cod_usu." and cont_estadolocal=0;");
		
		$this->db_client->query("update sgr_estadisticadocumentos set cont_estadosunat=1 
			where cod_emp=".$prm_cod_empr." and cod_usu=".$prm_cod_usu." 
				and est_regsunat = 'SIGNED' and cont_estadosunat=0;");
			//print($query);
		$this->db_client->query("update sgr_estadisticadocumentos set cont_estadosunat=2 
			where cod_emp=".$prm_cod_empr." and cod_usu=".$prm_cod_usu." and 
				est_regsunat = 'SIGNED/AC_03' and cont_estadosunat=0;");
		
		$this->db_client->query("update sgr_estadisticadocumentos set cont_estadosunat=3 
			where cod_emp=".$prm_cod_empr." and cod_usu=".$prm_cod_usu." and 
				est_regsunat = 'SIGNED/RC_05' and cont_estadosunat=0;");	
		
		$this->db_client->query("update sgr_estadisticadocumentos set cont_estadosunat=4 
			where cod_emp=".$prm_cod_empr." and cod_usu=".$prm_cod_usu." and 
				est_regsunat = 'SIGNED/PE_09' and cont_estadosunat=0;");
		
		$this->db_client->query("update sgr_estadisticadocumentos set cont_estadosunat=5 
			where cod_emp=".$prm_cod_empr." and cod_usu=".$prm_cod_usu." and 
				(est_regsunat like '%PE_02%' or est_regsunat like '%ED_06%') and cont_estadosunat=0;");
		
		$this->db_client->query("update sgr_estadisticadocumentos set cont_estadosunat=6 
			where cod_emp=".$prm_cod_empr." and cod_usu=".$prm_cod_usu." and 
				(est_regsunat like '%AN_04%' or est_regsunat like '%AN_05%') and cont_estadosunat=0;");
		
		$query="select 
					(case 	when tipo_docum='01' then 'FACTURA' 
							when tipo_docum='03' then 'BOLETA DE VENTA'
							when tipo_docum='07' then 'NOTA DE CREDITO'
							when tipo_docum='08' then 'NOTA DE DEBITO'
							when tipo_docum='98' then 'RESUMEN DE BOLETAS'
							when tipo_docum='99' then 'RESUMEN DE BAJAS' end) nomb_tipodocumento,
							
					sum(case when cont_estadosunat=1 then 1 else 0 end) firmado,
					sum(case when cont_estadosunat=2 then 1 else 0 end) aceptado,
					sum(case when cont_estadosunat=3 then 1 else 0 end) rechazado,
					sum(case when cont_estadosunat=4 then 1 else 0 end) pendiente_declaracion,
					sum(case when cont_estadosunat=5 then 1 else 0 end) espera_respuesta,
					sum(case when cont_estadosunat=6 then 1 else 0 end) anulado,
					
					sum(case when cont_estadolocal=7 then 1 else 0 end) borrador,
					sum(case when cont_estadolocal=8 then 1 else 0 end) por_procesar,
					sum(case when cont_estadolocal=9 then 1 else 0 end) procesado,
					sum(case when cont_estadolocal=10 then 1 else 0 end) error
				from sgr_estadisticadocumentos where cod_emp=".$prm_cod_empr." and cod_usu=".$prm_cod_usu." group by tipo_docum";
		$consulta=$this->db_client->query($query);
		
		//print_r($query);
		return $consulta->result_array();

	}

	

}