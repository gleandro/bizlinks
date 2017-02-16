<?php 
//session_start();

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Principal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Empresa_model');
		$this->load->model('Menu_model');
		$this->load->model('Usuarioinicio_model');
		
	}

    public function index()
    {
		/*
		print_r(md5('123456'));
		return;
		*/
		
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			
			$this->load->view('usuario/login'); 
			return;
		}		
		
		$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();		
		if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
		{
			$prm_cod_empr=0;
		}
		else
		{
			$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		}

		$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);		
		//print_r(array($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu));
		
		
		$lista_empresamenu=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
		$prm['Listar_Empresas']=$lista_empresamenu;			
		if(!empty($lista_empresamenu))
		{
			if ($_SESSION['SES_MarcoTrabajo'][0]['cod_empr']==0)
			{
				$_SESSION['SES_MarcoTrabajo'][0]['cod_empr']=$lista_empresamenu[0]['cod_empr'];
				$consultaempresa=$this->Empresa_model->Listar_EmpresaIdRoles($lista_empresamenu[0]['cod_empr']); 
				if(!empty($consultaempresa))
				{
					$_SESSION['SES_MarcoTrabajo'][0]['tipo_confserie']=$consultaempresa[0]['tipo_confserie'];	
					$_SESSION['SES_MarcoTrabajo'][0]['tipo_confunid']=$consultaempresa[0]['tipo_confunid'];	
					$_SESSION['SES_MarcoTrabajo'][0]['porc_igv']=$consultaempresa[0]['valor_igv'];
					$_SESSION['SES_MarcoTrabajo'][0]['conf_venta']=$consultaempresa[0]['conf_venta'];
				}
			}
		}
		
		$prm['pagina_ver']='principal';
		$this->load->view('principal/principal',$prm);		
    }

	public function principal()
    {
		$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();		
		if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
		{
			$prm_cod_empr=0;
		}
		else
		{
			$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		}
		$prm['pagina_ver']='principal';
		$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);			
		$this->load->view('principal/principal',$prm);
    }

}







