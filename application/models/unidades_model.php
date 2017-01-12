<?php
@session_start();
class Unidades_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}
	
	
	function Listar_UnidadesSunat()
	{

		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select 
			co_item_tabla,
			no_corto			
		from tm_tabla_multiple where no_tabla='UNIDAD_MEDIDA_SUNAT' order by no_corto;");
		
		return $consulta->result_array();
	}
	
	function Listar_Unidades($prm_cod_empr,$prm_tipo_confunid)
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select 
			cod_unimedeq,
			cod_unidmedempr,
			nomb_unidmedempr,
			cod_unidmedsunat,
			nomb_unidmedsunat,
			tipo_confunid,
			(case when tipo_confunid=1 then 'UNIDAD SUNAT' else 'UNIDAD LOCAL' end )nomb_tipo_confunid
		from sgr_unidadmedidaequivalencia where est_reg=1 and cod_empr=".$prm_cod_empr." and tipo_confunid=".$prm_tipo_confunid.";");
		
		return $consulta->result_array();
	}

	
	function Listar_ConfigurationId($prm_id_emisor)
	{

		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select 
			b.raz_social,
			a.id_emisor,
			a.firma_local,			  
			a.alias,			  
			a.protection_val,			  
			a.protection_key,			  
			a.path_key,
			a.aditional			
		from bl_configuration a inner join sgr_empresa b on a.id_emisor=b.ruc_empr where id_emisor='".$prm_id_emisor."';");
		
		return $consulta->result_array();
	}

	function Guardar_UnidadEquivalencia($prm_cod_empr,$prm_cod_unidmedempr,$prm_nomb_unidmedempr,$prm_cod_unidmedsunat,
			$prm_nomb_unidmedsunat,$prm_usu_reg,$prm_tipo_confunid)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();
		

		$query="
			insert into sgr_unidadmedidaequivalencia
			(
				cod_empr,cod_unidmedempr,nomb_unidmedempr,cod_unidmedsunat,nomb_unidmedsunat,usu_reg,tipo_confunid
			)
			values
			(
				'".$prm_cod_empr."',
				'".$prm_cod_unidmedempr."',
				'".$prm_nomb_unidmedempr."',
				'".$prm_cod_unidmedsunat."',
				'".$prm_nomb_unidmedsunat."',
				'".$prm_usu_reg."',
				'".$prm_tipo_confunid."'				
			 );";
		$this->db_client->query($query);
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}
		$result['result']=1;
		$this->db_client->trans_commit();	
		return $result;
	}
	
	function Eliminar_Unidades($prm_cod_unimedeq)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;
		$consulta = $this->db->query("update sgr_unidadmedidaequivalencia
		set
			est_reg=0
		where cod_unimedeq='".$prm_cod_unimedeq."';");
		$result['result']=1;
		return $result;
	}
	

	
}