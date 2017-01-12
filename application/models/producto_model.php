<?php
@session_start();
class producto_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}
		
	function Listar_Productos($prm_cod_empr)
	{
		//se modificó script para listar productos
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select  a.id, a.cod_producto, a.nom_corto, a.nom_largo, a.valor_venta, a.precio_venta, 
				b.nombre categoria,	d.nomb_unidmedempr med, d.cod_unidmedsunat 
			from sgr_producto a
				inner join sgr_multitabla b on a.id_categoria = b.id 
				left join sgr_unidadmedidaequivalencia d on a.cod_unimedeq=d.cod_unimedeq 
				where a.est_reg=1 and a.cod_empr=".$prm_cod_empr." order by a.nom_corto;");
		return $consulta->result_array();
	}
	
	function Listar_ProductoId($prm_cod_id)
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select  a.id, a.cod_producto, a.nom_corto, a.nom_largo, a.valor_venta, a.precio_venta, a.id_categoria, 
				b.nombre categoria, d.cod_unimedeq med, d.cod_unidmedsunat 
			from sgr_producto a
				inner join sgr_multitabla b on a.id_categoria = b.id 
				left join sgr_unidadmedidaequivalencia d on a.cod_unimedeq=d.cod_unimedeq   
			where a.id=".$prm_cod_id."  ;");
		
		return $consulta->result_array();
	}
	
	function Busqueda_ProductoId($prm_cod_id)
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select  a.id, a.cod_producto, a.nom_corto, a.nom_largo, a.valor_venta, a.precio_venta, 
				d.cod_unidmedsunat, b.nombre categoria, d.cod_unimedeq idmed
			from sgr_producto a
				inner join sgr_multitabla b on a.id_categoria = b.id 
				left join sgr_unidadmedidaequivalencia d on a.cod_unimedeq=d.cod_unimedeq     
			where a.id=".$prm_cod_id."  ;");		
		return $consulta->result_array();
	}
	
	function Busqueda_ProductoFiltro($prm_cod_empr, $prm_codigo, $prm_descripcion)
	{
		$this->load->database('ncserver',TRUE);
		//strtoupper
		$consulta = $this->db->query("select a.id, a.cod_producto, a.nom_corto, a.nom_largo, a.valor_venta, a.precio_venta, 
				d.cod_unidmedsunat, d.cod_unimedeq idmed
			from sgr_producto a
				inner join sgr_multitabla b on a.id_categoria = b.id 
				left join sgr_unidadmedidaequivalencia d on a.cod_unimedeq=d.cod_unimedeq     
			where a.cod_empr=".$prm_cod_empr." and upper(a.cod_producto) like '%".strtoupper($prm_codigo)."%' 
				and upper(a.nom_corto) like '%".strtoupper($prm_descripcion)."%' ;");		
		return $consulta->result_array();
	}
	
	function Guardar_Producto($prm_codigo,$prm_nombrecorto,$prm_nombrelargo,$prm_valorentero,$prm_precio,$prm_categoria,$prm_medida,$prm_cod_empr)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();
		
		$consulta = $this->db_client->query("select count(id) cantidad from sgr_producto 
				where nom_corto='".$prm_nombrecorto."' and cod_empr=".$prm_cod_empr." ;");
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
				insert into sgr_producto
				(cod_producto, nom_corto, nom_largo, valor_venta, precio_venta, id_categoria, cod_unimedeq, cod_empr)
				values
				(
					'".$prm_codigo."',
					'".$prm_nombrecorto."',
					'".$prm_nombrelargo."',
					".$prm_valorentero.",
					".$prm_precio.",
					".$prm_categoria.",
					".$prm_medida.",
					'".$prm_cod_empr."'
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

	function Modificar_Producto($prm_id,$prm_codigo,$prm_nombrecorto,$prm_nombrelargo,$prm_valorentero,$prm_precio,$prm_categoria,$prm_medida)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();
//cod_producto='".$prm_nombrecorto."',	
		$query="update sgr_producto
		set	
			cod_producto='".$prm_codigo."',
			nom_corto='".$prm_nombrecorto."',
			nom_largo='".$prm_nombrelargo."',
			valor_venta=".$prm_valorentero.",
			precio_venta=".$prm_precio.",
			id_categoria=".$prm_categoria.", 
			cod_unimedeq=".$prm_medida."
		where id='".$prm_id."';";
		
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




	function Eliminar_Producto($prm_cod_id)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;
		$consulta = $this->db->query("update sgr_producto set est_reg=0 where id='".$prm_cod_id."';");
		$result['result']=1;
		return $result;
	}
	
}