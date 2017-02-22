<?php
//@session_start();
class Catalogos_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}

	function Listar_Departamentos()
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select co_departamento,de_departamento from sgr_ubigeo group by co_departamento,de_departamento order by 1;");
		//print_r("select co_departamento,de_departamento from sgr_ubigeo group by co_departamento,de_departamento order by 1;");
		return $consulta->result_array();
	}
	
	function Listar_Provincias($prm_cod_depa)
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select co_provincia,de_provincia from sgr_ubigeo where co_departamento=".$prm_cod_depa." group by co_provincia,de_provincia order by 1;");		
		//print_r("select co_provincia,de_provincia from sgr_ubigeo where co_departamento=".$prm_cod_depa." group by co_provincia,de_provincia order by 1;");
		return $consulta->result_array();
	}
	
	function Listar_Distritos($prm_cod_depa,$prm_cod_provin)
	{
		//print_r("select co_distrito,de_distrito from sgr_ubigeo 
		//	where co_departamento=".$prm_cod_depa." and co_provincia=".$prm_cod_provin." group by co_distrito,de_distrito order by 1;");
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select co_distrito,de_distrito from sgr_ubigeo 
			where co_departamento=".$prm_cod_depa." and co_provincia=".$prm_cod_provin." group by co_distrito,de_distrito order by 1;");		
		
		return $consulta->result_array();
	}

	function Listar_TipodeDocumento_Retencion()
	{

		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla,no_corto from tm_tabla_multiple where no_tabla='TIPO_DOCUMENTO' and in_habilitado=1 and co_item_tabla in('01','07','08');";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_TipodeDocumento($codigo = 0)
	{

		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla,no_corto from tm_tabla_multiple where no_tabla='TIPO_DOCUMENTO' and in_habilitado=1 and co_item_tabla in('01','03','07','08'";

		if ($codigo == 1) {
			$query=$query.",'20'";
		}

		$query=$query.");";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_TipodeDocumento_Todos()
	{

		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla,no_corto from tm_tabla_multiple where no_tabla='TIPO_DOCUMENTO' and in_habilitado=1 and co_item_tabla in('01','03','07','08','20');";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_TipodeDocumentoIdentidad()
	{

		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla,no_corto from tm_tabla_multiple where no_tabla='TIPO_DOCUMENTO_IDENTIDAD' and in_habilitado=1;";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_SeriesDocumentos($prm_tip_doc,$prm_cod_empr)
	{

		$this->load->database('ncserver',TRUE);
		$query="select 1 tip_conf,a.cod_usu,a.ser_doc,(a.num_doc) num_doc 
		from sgr_configuracionseries a 			
		where a.est_reg=1 and a.tip_doc='".$prm_tip_doc."' and a.cod_empr=".$prm_cod_empr.";";

		$consulta=$this->db->query($query);
		//print_r($consulta->result_array());
		//return;
		return $consulta->result_array();	
	}
	
	function Listar_SeriesDocumentosUsuario($prm_tip_doc,$prm_cod_empr,$prm_cod_usu)
	{

		$this->load->database('ncserver',TRUE);
		$query="select 2 tip_conf,a.cod_usu,a.ser_doc,(a.num_doc) num_doc 
		from sgr_configuracionseries a 	
		inner join	sgr_configuracionseriesusuario b on a.cod_confser=b.cod_confser
		where a.est_reg=1 
		and a.tip_doc='".$prm_tip_doc."' 
		and a.cod_empr=".$prm_cod_empr."
		and b.cod_usu=".$prm_cod_usu."
		and b.est_reg=1	;";

		$consulta=$this->db->query($query);
		//return;
		return $consulta->result_array();	
	}
	
	
	function Tipo_Afectacion($cod_tipoconsulta)
	{

		$this->load->database('ncserver',TRUE);
		
		if ($cod_tipoconsulta==1)//sin selecccionar nada
		{
			$query="select co_item_tabla,no_largo from tm_tabla_multiple where no_tabla='TM_CODIGO_IGV' and co_item_tabla in('10','20','30');";
		}
		else if ($cod_tipoconsulta==2)
		{
			$query="select co_item_tabla,no_largo from tm_tabla_multiple where no_tabla='TM_CODIGO_IGV' and co_item_tabla in('11','12','13','14','15','16','21','31','32','33','34','35','36');";
		}

		
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Tipo_AfectacionModificar($cod_afectacion)
	{
		$tipoafectacion=0;
		if ($cod_afectacion=='40')//EXPORTACION
		{
			$tipoafectacion=2;
		}
		else if ($cod_afectacion=='10' or $cod_afectacion=='20' or $cod_afectacion=='30')
		{
			$tipoafectacion=1;
		}
		else
		{
			$tipoafectacion=3;
		}
		return $tipoafectacion;
	}
	
	function Datos_Ubigeo($prm_cod_depa,$prm_cod_prov,$prm_cod_dist)
	{
		$this->load->database('ncserver',TRUE);
		$query="select 
		de_departamento,
		de_provincia,
		de_distrito		
		from sgr_ubigeo 
		where co_departamento=".$prm_cod_depa." and co_provincia=".$prm_cod_prov." and co_distrito=".$prm_cod_dist.";";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	
	function Tipo_NotaCredito()
	{
		//$this->load->database('ncserver',TRUE);
		//$lista=array()
		$lista=array ( 
			"0"  => array ( "codigo"=> "01","nombre"=> "Anulación de la operación"),
			"1"  => array ( "codigo"=>  "02","nombre" => "Anulación por error en el RUC"),
			"2"  => array ( "codigo"=>  "03","nombre" => "Corrección por error en la descripción"),
			"3"  => array ( "codigo"=>  "04","nombre" => "Descuento global"),
			"4"  => array ( "codigo"=>  "05","nombre" => "Descuento por ítem"),
			"5"  => array ( "codigo"=>  "06","nombre" => "Devolución total"),
			"6"  => array ( "codigo"=>  "07","nombre" => "Devolución por ítem"),
			"7"  => array ( "codigo"=>  "08","nombre" => "Bonificación"),
			"8"  => array ( "codigo"=>  "09","nombre" => "Disminución en el valor"),
			"9"  => array ( "codigo"=>  "10","nombre" => "Otros Conceptos"),
			"10"  => array ( "codigo"=>  "11","nombre" => "Por eventos posteriores del traslado de los bienes (aplicable para factura que sustenta traslado de bienes)")
			);
		return $lista;	
	}
	
	function Tipo_NotaDebito()
	{

		//$this->load->database('ncserver',TRUE);
		//$lista=array()
		
		$lista=array ( 
			"0"  => array ( "codigo"=> "01","nombre"=> "Intereses por mora"),
			"1"  => array ( "codigo"=>  "02","nombre" => "Aumento en el valor"),
			"2"  => array ( "codigo"=>  "03","nombre" => "Penalidades/Otros Conceptos")					
			);

		return $lista;	
	}
	
	
	function Listar_Variables()
	{

		//$this->load->database('ncserver',TRUE);
		//$lista=array()
		
		$lista=array ( 
			"0"  => array ( "codigo"=> "1","nombre"=> "RUTA_CERTIFICADO"),
			"1"  => array ( "codigo"=> "2","nombre"=> "CORRELATIVO_CLIENTEEXTRANJERO"),
			"2"  => array ( "codigo"=> "3","nombre"=> "VALOR_IGV"),
			"3"  => array ( "codigo"=> "4","nombre"=> "RUTA_DOCUMENTOS"),
			"4"  => array ( "codigo"=> "5","nombre"=> "TIPO_MONEDA"),
						//"5"  => array ( "codigo"=> "6","nombre"=> "CATEGORIA_PRODUCTO"),
			"6"  => array ( "codigo"=> "7","nombre"=> "OTROS_CARGOS"),
			"7"  => array ( "codigo"=> "8","nombre"=> "CONFIGURACION_VENTA"),
			"8"  => array ( "codigo"=> "9","nombre"=> "RUTA_LOGO")
			);

		return $lista;	
	}
	
	

	function Datos_Unidades($prm_cod_empr,$prm_tipo_confunid)
	{
		$this->load->database('ncserver',TRUE);
		if ($prm_tipo_confunid==1)//UNIDADES DE SUNAT
		{
			$query="select cod_unidmedsunat codigo_unidad,nomb_unidmedempr nombre_unidad
			from sgr_unidadmedidaequivalencia 
			where tipo_confunid=".$prm_tipo_confunid." and cod_empr=".$prm_cod_empr." and est_reg=1;";
		}
		else //UNIDAD DEL PROVEEDOR
		{
			$query="select cod_unidmedsunat codigo_unidad,nomb_unidmedempr nombre_unidad
			from sgr_unidadmedidaequivalencia 
			where tipo_confunid=".$prm_tipo_confunid." and cod_empr=".$prm_cod_empr." and est_reg=1;";
		}
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Datos_Unidades_Producto($prm_cod_empr,$prm_tipo_confunid)
	{
		$this->load->database('ncserver',TRUE);
		if ($prm_tipo_confunid==1)//UNIDADES DE SUNAT
		{
			$query="select cod_unimedeq codigo_unidad, nomb_unidmedempr nombre_unidad
			from sgr_unidadmedidaequivalencia 
			where tipo_confunid=".$prm_tipo_confunid." and cod_empr=".$prm_cod_empr." and est_reg=1;";
		}
		else //UNIDAD DEL PROVEEDOR
		{
			$query="select cod_unimedeq codigo_unidad, nomb_unidmedempr nombre_unidad
			from sgr_unidadmedidaequivalencia 
			where tipo_confunid=".$prm_tipo_confunid." and cod_empr=".$prm_cod_empr." and est_reg=1;";
		}
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	
	function Listar_EstadoDocumento()
	{

		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla,no_corto from tm_tabla_multiple 
		where no_tabla='ESTADO_DOCUMENTO_PORTAL' and in_habilitado=1;";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	//Nuevo Req. No se considera estado Borrador para las listas de resumenes
	function Listar_EstadoDocumentoResumenes()
	{

		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla,no_corto from tm_tabla_multiple 
		where no_tabla='ESTADO_DOCUMENTO_PORTAL' and in_habilitado=1 and co_item_tabla<>'B';";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_RutaCertificado()
	{

		$this->load->database('ncserver',TRUE);
		$query="select valorcadena from sgr_multitabla where grupo_nombre='RUTA_CERTIFICADO' and activo=1;";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_RutaDocumentoDescargar()
	{

		$this->load->database('ncserver',TRUE);
		$query="select valorcadena from sgr_multitabla where grupo_nombre='RUTA_DOCUMENTOS' and activo=1;";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	
	function Correlativo_ClienteExtranjero($prm_cod_empr)
	{

		$this->load->database('ncserver',TRUE);
		$query="select valorentero from sgr_multitabla where grupo_nombre='CORRELATIVO_CLIENTEEXTRANJERO' and activo=1 and cod_empr='".$prm_cod_empr."';";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	
	function Listar_EstadoDocumentoSunat($prm_tipodocumento)
	{
		$this->load->database('ncserver',TRUE);
		if ($prm_tipodocumento=='01')
		{
			$query="select co_item_tabla,no_corto,no_largo from tm_tabla_multiple 
			where no_tabla='ESTADO_DOCUMENTOSUNATFACTURA_PORTAL' and in_habilitado=1 and co_item_tabla<>'PE_02';";
		}
		else {
			if ($prm_tipodocumento=='03')
			{
				$query="select co_item_tabla,no_corto,no_largo from tm_tabla_multiple 
				where no_tabla='ESTADO_DOCUMENTOSUNATBOLETA_PORTAL' and in_habilitado=1;";
			}
			else
			{
				$query="
				select co_item_tabla,no_corto,no_largo 
				from(
				select co_item_tabla,no_corto,no_largo from tm_tabla_multiple 
				where no_tabla='ESTADO_DOCUMENTOSUNATFACTURA_PORTAL' and in_habilitado=1 and co_item_tabla<>'PE_02' 
				union all
				select co_item_tabla,no_corto,no_largo from tm_tabla_multiple 
				where no_tabla='ESTADO_DOCUMENTOSUNATBOLETA_PORTAL' and in_habilitado=1  
				) a 
				group by co_item_tabla,no_corto,no_largo;";
			}
		}
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_EstadoDocumentoSunat_NoIn($prm_tipodocumento)
	{
		$this->load->database('ncserver',TRUE);
		if ($prm_tipodocumento=='01')
		{
			$query="select co_item_tabla,no_corto,no_largo from tm_tabla_multiple 
			where no_tabla='ESTADO_DOCUMENTOSUNATFACTURA_PORTAL' and in_habilitado=1;";
		}
		else {
			if ($prm_tipodocumento=='03')
			{
				$query="select co_item_tabla,no_corto,no_largo from tm_tabla_multiple 
				where no_tabla='ESTADO_DOCUMENTOSUNATBOLETA_PORTAL' and in_habilitado=1;";
			}
			else
			{
				$query="
				select co_item_tabla,no_corto,no_largo 
				from (
				select co_item_tabla,no_corto,no_largo from tm_tabla_multiple 
				where no_tabla='ESTADO_DOCUMENTOSUNATFACTURA_PORTAL' and in_habilitado=1
				union all
				select co_item_tabla,no_corto,no_largo from tm_tabla_multiple 
				where no_tabla='ESTADO_DOCUMENTOSUNATBOLETA_PORTAL' and in_habilitado=1
				) a 
				group by co_item_tabla,no_corto,no_largo;";
			}
		}
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_EstadoSunatResumen()
	{
		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla,no_corto,no_largo from tm_tabla_multiple 
		where no_tabla='ESTADO_RESUMENDOCUMENTO_PORTAL' and in_habilitado=1 and co_item_tabla<>'PE_02';";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	function Listar_EstadoSunatResumen_NoIn()
	{
		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla,no_corto,no_largo from tm_tabla_multiple 
		where no_tabla='ESTADO_RESUMENDOCUMENTO_PORTAL' and in_habilitado=1;";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_EstadoSunatRetencion()
	{
		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla, no_corto, no_largo from tm_tabla_multiple 
		where no_tabla='ESTADO_DOCUMENTOSUNATFACTURA_PORTAL' and in_habilitado=1 and co_item_tabla<>'PE_02';";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	function Listar_EstadoSunatRetencion_NoIn()
	{
		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla, no_corto, no_largo from tm_tabla_multiple 
		where no_tabla='ESTADO_DOCUMENTOSUNATFACTURA_PORTAL' and in_habilitado=1;";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	function Listar_EstadoSunatRetencion_Receptor()
	{
		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla, no_corto, no_largo from tm_tabla_multiple 
		where no_tabla='ESTADO_DOCUMENTOSUNATFACTURA_PORTAL' and in_habilitado=1 
		and co_item_tabla<>'PE_02' and co_item_tabla<>'SIGNE';";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	function Listar_EstadoSunatRetencion_NoIn_Receptor()
	{
		$this->load->database('ncserver',TRUE);
		$query="select co_item_tabla, no_corto, no_largo from tm_tabla_multiple 
		where no_tabla='ESTADO_DOCUMENTOSUNATFACTURA_PORTAL' and in_habilitado=1 and co_item_tabla<>'SIGNE';";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_Roles()
	{

		$this->load->database('ncserver',TRUE);
		$query="select cod_rol,desc_rol from sgr_rol where est_reg=1;";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_Monedas()
	{
		$this->load->database('ncserver',TRUE);
		$query="select valorcadena codigo,nombre from sgr_multitabla where grupo_nombre='TIPO_MONEDA' and grupo_id='5';";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_Paises()
	{
		$this->load->database('ncserver',TRUE);
		$query="select id,nombre from sgr_pais;";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_Parametros()
	{
		$this->load->database('ncserver',TRUE);
		$query="select is_inhouse from bl_parametros;";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	function Listar_Categoria()
	{
		$this->load->database('ncserver',TRUE);
		$query="select id,nombre,activo from sgr_multitabla where grupo_nombre='CATEGORIA_PRODUCTO' and activo=1;";
		$consulta=$this->db->query($query);
		return $consulta->result_array();	
	}
	
	
	
}