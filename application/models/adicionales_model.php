<?php

class Adicionales_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}

	function Listar_AdicionalGrid($prm_ruc_empr)
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select codigo, observacion, orden from bl_adicionales_auxiliares
			where numerodocumentoemisor='".$prm_ruc_empr."' and codigo<>'9371' order by codigo asc;");
		return $consulta->result_array();		
	}
	
	function Guardar_Adicional($prm_ruc_empr,$prm_codigo,$prm_observacion,$prm_orden)
	{
		$result['result']=0;		

		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();
		
		$consulta = $this->db_client->query("select count(numerodocumentoemisor) cantidad from bl_adicionales_auxiliares 
			where numerodocumentoemisor='".$prm_ruc_empr."' and codigo='".$prm_codigo."';");		
		
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}
		$resultado=$consulta->result_array();	

		if ($resultado[0]['cantidad']==0) //no existe registrado
		{
			$query="
			insert into bl_adicionales_auxiliares
			(codigo, observacion, orden, numerodocumentoemisor)
			values
			(	".$prm_codigo.",
			'".$prm_observacion."',
			'".$prm_orden."',
			'".$prm_ruc_empr."'
			);";
			
			$this->db_client->query($query);
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				return $result;
			}			
			$result['result']=1;			
		}
		else
		{
			$result['result']=2;
		}
		$this->db_client->trans_commit();
		
		return $result;
	}
	
	function Obtiene_Adicional($prm_ruc_empresa, $prm_codigo)
	{
		$this->load->database('ncserver',TRUE);
		$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();	
		$sql="select codigo, observacion, orden from bl_adicionales_auxiliares
		where numerodocumentoemisor='".$prm_ruc_empresa."' and codigo='".$prm_codigo."';";		
		$consulta = $this->db->query($sql);
		return $consulta->result_array();
	}
	
	function Modificar_Adicional($prm_ruc_empresa,$prm_codigo,$prm_observacion,$prm_orden)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();

		$query="update bl_adicionales_auxiliares
		set	
		observacion='".$prm_observacion."',
		orden='".$prm_orden."'
		where numerodocumentoemisor='".$prm_ruc_empresa."' and codigo='".$prm_codigo."';";
		
		$consulta = $this->db_client->query($query);
		
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

	function Eliminar_Adicional($prm_ruc_empresa,$prm_codigo)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;
		
		$consulta = $this->db->query("delete from bl_adicionales_auxiliares
			where numerodocumentoemisor='".$prm_ruc_empresa."' and codigo='".$prm_codigo."';");
		$result['result']=1;
		return $result;
	}
	
	
}