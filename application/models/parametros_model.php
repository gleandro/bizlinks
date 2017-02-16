<?php
//@session_start();
class Parametros_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}
	
	
	function Listar_Parametros($prm_cod_empr)
	{

		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select id, grupo_id, grupo_nombre, nombre,
										valorentero, valorcadena,activo, cod_empr
									from sgr_multitabla where activo=1 and (cod_empr is null OR cod_empr=".$prm_cod_empr.")
									order by grupo_id;");
		
		return $consulta->result_array();
	}
	
	function Listar_ParametrosId($prm_cod_id)
	{

		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select 
					id,
					grupo_id,
					grupo_nombre,
					nombre,
					valorentero,
					valorcadena,
					activo
		from sgr_multitabla b where id=".$prm_cod_id." and activo=1;");
		
		return $consulta->result_array();
	}
	

	
	function Guadar_Portalmultitabla($prm_grupo_id,$prm_grupo_nombre,$prm_nombre,$prm_valorentero,$prm_valorcadena,$prm_cod_empr)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();
		
		if ($prm_grupo_id==2)
		{
			$consulta = $this->db_client->query("select count(id) cantidad from sgr_multitabla 
				where activo=1 and grupo_id='".$prm_grupo_id."' and cod_empr='".$prm_cod_empr."';");		
		}
		else
		{
			$consulta = $this->db_client->query("select count(id) cantidad from sgr_multitabla 
				where activo=1 and grupo_id='".$prm_grupo_id."' and nombre='".$prm_nombre."' ;");
		}
		
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}
		
		$resultado=$consulta->result_array();	

		if ($resultado[0]['cantidad']==0) //no existe registrado
		{
			if ($prm_grupo_id==2 || $prm_grupo_id==6 || $prm_grupo_id==7)
			{
				$query="
					insert into sgr_multitabla
					(
						grupo_id,grupo_nombre,nombre,valorentero,valorcadena,cod_empr
					)
					values
					(
						'".$prm_grupo_id."',
						'".$prm_grupo_nombre."',
						'".$prm_nombre."',
						'".$prm_valorentero."',
						'".$prm_valorcadena."',
						'".$prm_cod_empr."'
					 );";
			}
			else
			{
				$query="
					insert into sgr_multitabla
					(
						grupo_id,grupo_nombre,nombre,valorentero,valorcadena
					)
					values
					(
						'".$prm_grupo_id."',
						'".$prm_grupo_nombre."',
						'".$prm_nombre."',
						'".$prm_valorentero."',
						'".$prm_valorcadena."'
					 );";
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
			$result['result']=1;			
		}
		else
		{
			$result['result']=2;
		}
		$this->db_client->trans_commit();
	
		return $result;
	}

	function Modificar_Portalmultitabla($prm_grupo_id,$prm_id,$prm_nombre,$prm_valorentero,$prm_valorcadena,$prm_cod_empr)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();
		
		if ($prm_grupo_id==2 || $prm_grupo_id==6 || $prm_grupo_id==7)
		{
			$query="update sgr_multitabla
			set
				nombre='".$prm_nombre."',			
				valorentero='". $prm_valorentero."',
				valorcadena='". $prm_valorcadena."',
				cod_empr='". $prm_cod_empr."'
			where id='".$prm_id."';";
		}
		else
		{
			$query="update sgr_multitabla
			set
				nombre='".$prm_nombre."',			
				valorentero='". $prm_valorentero."',
				valorcadena='". $prm_valorcadena."'
			where id='".$prm_id."';";
		}

		
		
		//print_r($query);
		//return;

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




	function Eliminar_Portalmultitabla($prm_cod_id)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;
		$consulta = $this->db->query("update sgr_multitabla
		set
			activo=0
		where id='".$prm_cod_id."';");
		$result['result']=1;
		return $result;
	}
	
}