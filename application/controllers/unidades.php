<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unidades extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Empresa_model');
		$this->load->model('Unidades_model');
		$this->load->model('Usuarioinicio_model');
		$this->load->model('Menu_model');
		$this->load->model('Catalogos_model');
	}
	
    public function index()
    {
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
			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
			{
				$prm_cod_empr=0;
			}
			else
			{
				$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			}
	
			$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);
			
			$Listar_Empresa=$this->Empresa_model->Listar_Empresa($this->Usuarioinicio_model->Get_Cod_UsuAdm());
			$prm['Listar_Empresa']=$Listar_Empresa;			
			$Listar_EmpresaContacto=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$prm['Listar_Empresas']=$Listar_EmpresaContacto;
			$Listar_EmpresaPermisosUsuario=NULL;
			if(!empty($Listar_EmpresaContacto))//SI NO ES NULO O VACIO
			{
				$contador=0;
				foreach($Listar_EmpresaContacto as $key=>$v):
					$contador=$contador+1;
					$Listar_EmpresaPermisosUsuario[$key]= trim($v['cod_empr']);				
				endforeach;
			}
			$prm['Listar_EmpresaPermisosUsuario']=$Listar_EmpresaPermisosUsuario;
			$prm['Listar_Departamentos']=$this->Catalogos_model->Listar_Departamentos();
			$prm['Listar_UnidadSunat']=$this->Unidades_model->Listar_UnidadesSunat();
			
			$Listar_MiEmpresa=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
			if(!empty($Listar_MiEmpresa))//SI NO ES NULO O VACIO
			{
				$prm['Cod_empr']=$Listar_MiEmpresa[0]['cod_empr'];	
				$prm['Razon_Social']=$Listar_MiEmpresa[0]['raz_social'];
				$prm['Tipo_confunid']=$Listar_MiEmpresa[0]['tipo_confunid'];
				$prm['Tipo_conffirma']=$Listar_MiEmpresa[0]['tipo_conffirma'];
			}
			else
			{
				$prm['Cod_empr']='';	
				$prm['Razon_Social']='';
				$prm['Tipo_confunid']='';
				$prm['Tipo_conffirma']='';
			}
			
			$prm['pagina_ver']='unidades';
			
			
			$this->load->view('unidades/registrar_unidades',$prm);
		}		
    }
	
	public function Guardar_UnidadEquivalencia()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_empr=trim($this->input->post('txt_cod_empr'));
		$prm_cod_unidmedempr='';
		$prm_nomb_unidmedempr=trim($this->input->post('txt_nombreequivalente'));
		$prm_cod_unidmedsunat=trim($this->input->post('cmb_unidadsunat'));
		$prm_nomb_unidmedsunat=trim($this->input->post('cmb_nombreunidadsunat'));
		$prm_usu_reg=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_tipo_confunid=trim($this->input->post('txt_codtipoconfig'));
		
		$resultado =$this->Unidades_model->Guardar_UnidadEquivalencia($prm_cod_empr,$prm_cod_unidmedempr,$prm_nomb_unidmedempr,$prm_cod_unidmedsunat,
			$prm_nomb_unidmedsunat,$prm_usu_reg,$prm_tipo_confunid);
		
		if ($resultado['result']==0)
		{
			$result['status']=0;		
		}
		else if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}
	
	
	public function Listar_Unidades()
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
		$prm_cod_empr=trim($this->input->post('txt_cod_empr'));		
		$prm_tipo_confunid=trim($this->input->post('txt_codtipoconfig'));
        $consulta =$this->Unidades_model->Listar_Unidades($prm_cod_empr,$prm_tipo_confunid);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;
				$arr[$key]['nro_secuencia'] = $Contador;				
				$arr[$key]['cod_unimedeq'] = trim($v['cod_unimedeq']);
				$arr[$key]['cod_unidmedempr'] = trim($v['cod_unidmedempr']);
				$arr[$key]['nomb_unidmedempr'] =trim($v['nomb_unidmedempr']);
				$arr[$key]['cod_unidmedsunat'] = trim($v['cod_unidmedsunat']); 
				$arr[$key]['nomb_unidmedsunat'] =  trim($v['nomb_unidmedsunat']);					
				$arr[$key]['tipo_confunid'] =  trim($v['tipo_confunid']);	
				$arr[$key]['nomb_tipo_confunid'] =  trim($v['nomb_tipo_confunid']);

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
	
	
	public function Eliminar_Unidades()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_unimedeq=trim($this->input->post('cod_unimedeq'));
		$resultado =$this->Unidades_model->Eliminar_Unidades($prm_cod_unimedeq);		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}	
	
	
	
	
	
}



