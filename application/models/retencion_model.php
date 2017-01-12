<?php
@session_start();
class Retencion_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();		
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
	
	

}