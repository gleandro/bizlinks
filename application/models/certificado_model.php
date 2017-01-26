<?php
@session_start();
class Certificado_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}
	
	function Listar_ConfigurationId($prm_id_emisor)
	{

		$this->load->database('ncserver',TRUE);
		
		$prm_id_emisor='6-'.$prm_id_emisor;
		
		$consulta = $this->db->query("select 
			a.id_emisor,
			a.firma_local,			  
			a.alias,			  
			a.protection_val,			  
			a.protection_key,			  
			a.path_key,
			a.aditional,
			a.expiry_key			
		from bl_configuration a 
			where id_emisor='".$prm_id_emisor."';");
		
		return $consulta->result_array(); //inner join sgr_empresa b on a.id_emisor=b.ruc_empr 
	}

	function Guardar_Configuration($prm_id_emisor,$prm_firma_local,$prm_alias,$prm_protection_val,$prm_protection_key,
			$prm_path_key,$prm_aditional,$prm_expiry_key,$prm_tipofirmaempresa,$prm_usuario_sol,$prm_clave_sol,$prm_valorinhouse)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();
		
		$prm_firma=1; //SIEMPRE SERA 1, XQ AQUI SOLO ENTRARA CUANDO LA CONF DE EMPRESA SEA LOCAL
		
		if ($prm_firma_local==1) //CERTIFICADO DE BIZLINKS, SE EXTRAE DE LA TABLA DONDE ESTA LOS DATOS
		{
			$consulta = $this->db_client->query("select firma_local,alias,protection_val,protection_key,path_key,expiry_key,usuario_sol,clave_sol  from bl_configuration_provider ;");		//where id_emisor='6-".$prm_id_emisor."'
		
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				return $result;
			}

			if($consulta->num_rows()>0)//SI NO ES NULO O VACIO
			{
				$resultado=$consulta->result_array();
				$prm_alias=$resultado[0]['alias'];
				$prm_protection_val=$resultado[0]['protection_val'];
				$prm_protection_key=$resultado[0]['protection_key'];
				$prm_path_key=$resultado[0]['path_key'];
				$prm_aditional='';
				$prm_expiry_key=$resultado[0]['expiry_key'];
				$prm_usuario_sol=$resultado[0]['usuario_sol'];
				$prm_clave_sol=$resultado[0]['clave_sol'];
			}
			else
			{
				$prm_alias='';
				$prm_protection_val='';
				$prm_protection_key='';
				$prm_path_key='';
				$prm_aditional='';
				$prm_expiry_key='';
				$prm_usuario_sol='';
				$prm_clave_sol='';
			}
			}
		else
		{
			$prm_protection_val=(trim($prm_protection_val));
			$prm_protection_key=(trim($prm_protection_key));
		}
		
		$this->db_client->query("delete from bl_configuration where id_emisor='6-".$prm_id_emisor."';");//ELIMINAMOS LOS DATOS		
		$consulta = $this->db_client->query("select count(id_emisor) cantidad from bl_configuration where id_emisor='6-".$prm_id_emisor."';");		
		
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}
		$resultado=$consulta->result_array();	
		$prm_aditional='PDF_LOCAL=1';
		if ($resultado[0]['cantidad']==0) //no existe registrado
		{
			if ($prm_valorinhouse==0)
			{
				$query="
					insert into bl_configuration
					(
						id_emisor,firma_local,alias,protection_val,protection_key,path_key,aditional,expiry_key
					)
					values
					(
						'6-".$prm_id_emisor."',
						'".$prm_firma."',
						'".$prm_alias."',
						'".$prm_protection_val."',
						'".$prm_protection_key."',
						'".$prm_path_key."',
						'".$prm_aditional."',
						'".$prm_expiry_key."'	
					 );";

			}
			else
			{
				$query="
					insert into bl_configuration
					(
						id_emisor,firma_local,alias,protection_val,protection_key,path_key,aditional,expiry_key,  usuario_sol,clave_sol
					)
					values
					(
						'6-".$prm_id_emisor."',
						'".$prm_firma."',
						'".$prm_alias."',
						'".$prm_protection_val."',
						'".$prm_protection_key."',
						'".$prm_path_key."',
						'".$prm_aditional."',
						'".$prm_expiry_key."',					
						'".$prm_usuario_sol."',
						'".$prm_clave_sol."'	
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
			$query="
				update bl_configuration
				set
					firma_local='".$prm_firma."',
					alias='".$prm_alias."',
					protection_val='".$prm_protection_val."',
					protection_key='".$prm_protection_key."',
					path_key='".$prm_path_key."',
					aditional='".$prm_aditional."',
					expiry_key='".$prm_expiry_key."',					
					usuario_sol='".$prm_usuario_sol."',
					clave_sol='".$prm_clave_sol."'
									
				where id_emisor='6-".$prm_id_emisor."' ;";
			
			//print_r($query);
			
			$this->db_client->query($query);
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				return $result;
			}		
			$result['result']=1;		
		}
		$this->db_client->trans_commit();	
		return $result;
	}
	

	
}