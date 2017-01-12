<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retencion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Retencion_model');
		$this->load->model('Empresa_model');
		$this->load->model('Usuarioinicio_model');
		$this->load->model('Menu_model');
		$this->load->model('excel_model');
		$this->load->model('Catalogos_model');
	}

    public function index()
    {
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$this->load->view('usuario/login');
		}
		else
		{
			$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
			$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
			$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();
			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste()){
				$prm_cod_empr=0;
			}else{
				$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			}
			$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
			if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
			{
				$prm['Ruc_Empresa']=$Listar_EmpresaId[0]['ruc_empr'];
				$prm['Razon_Social']=$Listar_EmpresaId[0]['raz_social'];
				$prm['Tipo_confserie']=$Listar_EmpresaId[0]['tipo_confserie'];
			}else{
				$prm['Ruc_Empresa']='';
				$prm['Razon_Social']='';
				$prm['Tipo_confserie']='';
			}

			$prm['Listar_EstadoDocumento']=$this->Catalogos_model->Listar_EstadoDocumento();


			//$prm['valor_igv']=$this->Usuarioinicio_model->Get_Valor_IGV();
			//$prm['valor_otroscargos']=$this->Usuarioinicio_model->Get_Valor_OtrosCargos();

			//$Listar_Empresa=$this->Empresa_model->Listar_Empresa($this->Usuarioinicio_model->Get_Cod_UsuAdm());
			//$prm['Listar_Empresa']=$Listar_Empresa;

			//$prm['Listar_TipodeDocumento']=$this->Catalogos_model->Listar_TipodeDocumento();
			//$prm['Config_ValorPrecio']=$_SESSION['SES_MarcoTrabajo'][0]['conf_venta'];

			//$prm['Listar_Unidades']=$this->Catalogos_model->Datos_Unidades($prm_cod_empr,$prm_tipo_confunidad);

			//if (!isset($_GET['param1'])){	$prm_documentomodificar='';} else{$prm_documentomodificar=$_GET['param1'];}
			//$prm['documentomodificar']=$prm_documentomodificar;

			$prm['pagina_ver']='retencion';
			$this->load->view('retencion/retencion_listar',$prm);
		}
    }

	public function ListaComprobantes()
    {

    }

	public function Datos_Emisor()
	{

    }


}
