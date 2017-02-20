<?php

class Seriedocumentos_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}
	
	
	function Listar_Seriedocumentos($prm_cod_empr)
	{

		$this->load->database('ncserver',TRUE);
		
		$info = $this->db->query("select 
			a.cod_confserusu,
			a.cod_usu,
			(select aa.no_corto from tm_tabla_multiple aa 
			where aa.no_tabla='TIPO_DOCUMENTO' and aa.in_habilitado=1 and aa.co_item_tabla=b.tip_doc) nomb_tipdoc,
			b.ser_doc,
			c.nom_usu,
			c.apell_usu					

			from sgr_configuracionseriesusuario a 
			inner join sgr_configuracionseries b on a.cod_confser=b.cod_confser
			inner join sgr_usuario c on c.cod_usu=a.cod_usu
			where b.cod_empr=".$prm_cod_empr." and a.est_reg=1;");
		$data = $info->result_array();

		$query = "select 
		cod_confser,
		cod_empr,
		cod_usu,
		tip_doc,
		(select aa.no_corto from tm_tabla_multiple aa 
		where aa.no_tabla='TIPO_DOCUMENTO' and aa.in_habilitado=1 and aa.co_item_tabla=b.tip_doc) nomb_tipdoc,
		ser_doc,
		num_doc
		from sgr_configuracionseries b where cod_empr=".$prm_cod_empr." and est_reg=1 ";

		foreach($data as $key=>$v):	

			$query=$query."and ser_doc != '".trim($v['ser_doc'])."' ";

		endforeach;

		$query=$query."order by 5;";

		$consulta = $this->db->query($query);		
		return $consulta->result_array();
	}
	
	function Listar_SeriedocumentosId($prm_cod_confser)
	{

		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select 
			cod_confser,
			cod_empr,
			cod_usu,
			tip_doc,
			(select aa.no_corto from tm_tabla_multiple aa 
			where aa.no_tabla='TIPO_DOCUMENTO' and aa.in_habilitado=1 and aa.co_item_tabla=b.tip_doc) nomb_tipdoc,
			ser_doc,
			num_doc
			from sgr_configuracionseries b where cod_confser=".$prm_cod_confser." and est_reg=1;");
		
		return $consulta->result_array();
	}
	
	
	
	
	function Guadar_SerieNumeracion($prm_cod_empr,$prm_cod_usu,$prm_tip_doc,$prm_ser_doc,$prm_num_doc,$prm_usu_reg)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();
		
		$consulta = $this->db_client->query("select count(cod_confser) cantidad from sgr_configuracionseries 
			where cod_empr='".$prm_cod_empr."' and est_reg=1 and tip_doc='".$prm_tip_doc."' and ser_doc='".$prm_ser_doc."';");		
		
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
			insert into sgr_configuracionseries
			(
			cod_empr,cod_usu,tip_doc,ser_doc,num_doc,usu_reg
			)
			values
			(
			'".$prm_cod_empr."',
			'".$prm_cod_usu."',
			'".$prm_tip_doc."',
			'".$prm_ser_doc."',
			'".$prm_num_doc."',
			'".$prm_usu_reg."'
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
			$result['result']=1;			
		}
		else
		{
			$result['result']=2;
		}
		$this->db_client->trans_commit();

		return $result;
	}

	function Modificar_SerieNumeracion($prm_cod_confser,$prm_cod_usu,$prm_num_doc)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;
		$consulta = $this->db->query("		
			update sgr_configuracionseries
			set
			cod_usu='".$prm_cod_usu."',			
			num_doc='". $prm_num_doc."'
			where cod_confser='".$prm_cod_confser."';");
		$result['result']=1;
		return $result;
	}

	function Eliminar_SerieNumeracion($prm_cod_confser)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;
		$consulta = $this->db->query("update sgr_configuracionseries
			set
			est_reg=0
			where cod_confser='".$prm_cod_confser."';");
		$result['result']=1;
		return $result;
	}
	
	
	function Guadar_SerieNumeracionxUsuario($prm_cod_confser,$prm_cod_usu)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();
		
		//LA SERIE YA ESTA RELACIONADA CON UN USUARIO
		$consulta = $this->db_client->query("select count(cod_confserusu) cantidad from sgr_configuracionseriesusuario 
			where cod_confser='".$prm_cod_confser."' and est_reg=1;");		
		
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}
		$resultado=$consulta->result_array();	

		if ($resultado[0]['cantidad']>0) //YA ESTA REGISTRADO
		{
			$this->db_client->trans_commit();
			$result['result']=3;
			return $result;
		}
		
		$consulta = $this->db_client->query("select count(cod_confserusu) cantidad from sgr_configuracionseriesusuario 
			where cod_confser='".$prm_cod_confser."' and est_reg=1 and cod_usu='".$prm_cod_usu."';");		
		
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
			insert into sgr_configuracionseriesusuario
			(
			cod_confser,cod_usu
			)
			values
			(
			'".$prm_cod_confser."',
			'".$prm_cod_usu."'
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
			$result['result']=1;			
		}
		else
		{
			$result['result']=2;
		}
		$this->db_client->trans_commit();

		return $result;
	}
	
	function Listar_SeriedocumentosxUsuario($prm_cod_empr)
	{

		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select 
			a.cod_confserusu,
			a.cod_usu,
			(select aa.no_corto from tm_tabla_multiple aa 
			where aa.no_tabla='TIPO_DOCUMENTO' and aa.in_habilitado=1 and aa.co_item_tabla=b.tip_doc) nomb_tipdoc,
			b.ser_doc,
			c.nom_usu,
			c.apell_usu					

			from sgr_configuracionseriesusuario a 
			inner join sgr_configuracionseries b on a.cod_confser=b.cod_confser
			inner join sgr_usuario c on c.cod_usu=a.cod_usu
			where b.cod_empr=".$prm_cod_empr." and a.est_reg=1;");
		
		return $consulta->result_array();
	}
	
	
	function Eliminar_SerieNumeracionxUsuario($prm_cod_confserusu)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;
		$consulta = $this->db->query("update sgr_configuracionseriesusuario
			set
			est_reg=0
			where cod_confserusu='".$prm_cod_confserusu."';");
		$result['result']=1;
		return $result;
	}
	
	
	
}