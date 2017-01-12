<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loginanonimo extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('Parametros_model');
		//$this->load->model('Empresa_model');
		//$this->load->model('Usuarioinicio_model');
		//$this->load->model('Menu_model');
		$this->load->model('Catalogos_model');
	}
	
    public function index()
    {
		$prm['Listar_TipodeDocumento']=$this->Catalogos_model->Listar_TipodeDocumento();	
		$this->load->view('loginanonimo/loginanonimo',$prm);
    }
	
	

}



