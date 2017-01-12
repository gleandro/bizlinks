<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activarusuario extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('usuario_model');
	}


	public function ActivarUsuario($CodUsuario,$cod_aleatorio)
    {
		$prm['Listar_UsuarioAdministrador']=$this->usuario_model->Listar_UsuarioAdministrador($CodUsuario,$cod_aleatorio);
		$this->load->view('activarusuario/activarusuario',$prm);
    }
	
	
	public function Activar_Usuario()
	{
		$result['status']=0;
		$prm_cod_usuadm=trim($this->input->post('txt_iduser'));
		$prm_correo=trim($this->input->post('txt_correoSes'));
		$this->usuario_model->Activar_Usuario($prm_cod_usuadm,$prm_correo);
		$result['status']=1;
		echo json_encode($result);
	}
	
	
	
}



