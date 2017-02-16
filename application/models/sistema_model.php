<?php
//@session_start();
class Sistema_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

	}
	
	function Listar_Sistema()
	{
		//$conect='ncserver';
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select cod_sist,nom_sist from sgr_sistema where est_reg=1;");
		return $consulta->result_array();
		/*
		$this->load->database('ncserver',TRUE);
		$consulta = $this->$this->db->query("select cod_sist,nom_sist from sgr_sistema where est_reg=1;");
		return 1;//$consulta->result_array();
		*/
	}
}