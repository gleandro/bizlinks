<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adicionales extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Adicionales_model');
		$this->load->model('Empresa_model');
		$this->load->model('Usuarioinicio_model');
		$this->load->model('Menu_model');
		//$this->load->model('Catalogos_model');
	}
	
    public function index()
    {
		//print_r("Index: Controller");
		//return;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			//echo 'SESSION EXPIRADA, VUELVA A INICIAR!'; 
			//exit(0); 
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
			
			$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
			if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
			{
				$prm['Ruc_Empresa']=$Listar_EmpresaId[0]['ruc_empr'];	
				$prm['Razon_Social']=$Listar_EmpresaId[0]['raz_social'];
			}else{
				$prm['Ruc_Empresa']='';	
				$prm['Razon_Social']='';
			}
			
			//$Listar_Empresa=$this->Empresa_model->Listar_Empresa($this->Usuarioinicio_model->Get_Cod_UsuAdm());
			//$prm['Listar_Empresa']=$Listar_Empresa;			
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			
			$prm['pagina_ver']='adicionales';
			$this->load->view('adicionales/adicionales_registrar',$prm);
		}		
    }
	
	public function Listar_AdicionalesGrid()
	{
		$arr=NULL;
		$Contador=0;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_ruc_empr=trim($this->input->post('txt_RucEmpresa'));
        $consulta =$this->Adicionales_model->Listar_AdicionalGrid($prm_ruc_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;
				$arr[$key]['nro_secuencia'] = $Contador;
				$arr[$key]['codigo'] = trim($v['codigo']);
				$arr[$key]['observacion'] =trim($v['observacion']);
				$arr[$key]['orden'] = trim($v['orden']); 
			endforeach;
		}
		if(sizeof($arr)>0)
		{
			$result['status']=1;
			$result['data']=$arr;
		}
		else
		{
			$result['status']=0;
			$result['data']="";
		}
		echo json_encode($result);		
    }
	
	public function Guardar_Adicional()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}

		$prm_ruc_empr=trim($this->input->post('txt_RucEmpresa'));
		$prm_codigo=trim($this->input->post('txt_Codigo'));
		$prm_observacion=trim($this->input->post('txt_Observacion'));
		$prm_orden=trim($this->input->post('txt_Orden'));

		$resultado =$this->Adicionales_model->Guardar_Adicional($prm_ruc_empr,$prm_codigo,$prm_observacion,$prm_orden);
		
		if ($resultado['result']==0)
		{
			$result['status']=0;		
		}
		else if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		else
		{
			$result['status']=2;	
		}
		echo json_encode($result);
	}
	
	public function Obtiene_Adicional()
	{
		$arr=NULL;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
	
		$prm_ruc_empr=trim($this->input->post('txt_rucempresa'));
		$prm_codigo=trim($this->input->post('txt_codigo'));

        $consulta =$this->Adicionales_model->Obtiene_Adicional($prm_ruc_empr, $prm_codigo);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$arr[$key]['codigo'] = trim($v['codigo']);
				$arr[$key]['observacion'] =trim($v['observacion']);
				$arr[$key]['orden'] = trim($v['orden']); 
			endforeach;
		}
		
		if(sizeof($arr)>0)
		{
			$result['status']=1;
			$result['data']=$arr;
		}
		else
		{
			$result['status']=0;
			$result['data']="";
		}
		echo json_encode($result);
	}
	
	public function Modificar_Adicional()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_ruc_empresa=trim($this->input->post('txt_RucEmpresa'));
		$prm_codigo=trim($this->input->post('txt_Codigo'));
		$prm_observacion=trim($this->input->post('txt_Observacion'));
		$prm_orden=trim($this->input->post('txt_Orden'));
		
		$resultado =$this->Adicionales_model->Modificar_Adicional($prm_ruc_empresa,$prm_codigo,$prm_observacion,$prm_orden);
		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}
	
	public function Eliminar_Adicional()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_ruc_empresa=trim($this->input->post('txt_rucempresa'));
		$prm_codigo=trim($this->input->post('txt_codigo'));
		
		$resultado =$this->Adicionales_model->Eliminar_Adicional($prm_ruc_empresa, $prm_codigo);		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}	
	
	
}



