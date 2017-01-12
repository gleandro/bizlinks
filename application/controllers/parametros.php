<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parametros extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Parametros_model');
		$this->load->model('Empresa_model');
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
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$prm['Listar_Departamentos']=$this->Catalogos_model->Listar_Departamentos();
			$prm['Listar_TipodeDocumento']=$this->Catalogos_model->Listar_TipodeDocumento();
			$prm['Listar_Variables']=$this->Catalogos_model->Listar_Variables();
			$prm['pagina_ver']='parametros';
			
			$this->load->view('parametros/parametros',$prm);
		}		
    }
	
	
	public function Listar_Parametros()
	{
		$arr=NULL;
		$contador=0;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
        $consulta =$this->Parametros_model->Listar_Parametros($prm_cod_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
			
				if ($v['cod_empr']=='')//PARA QUE SOLO MUESTRA LAS VARIABLES GLOBALES
				{
					$contador=$contador+1;
					$arr[$key]['nro_secuencia'] = $contador;				
					$arr[$key]['id'] = trim($v['id']);
					$arr[$key]['grupo_id'] = trim($v['grupo_id']);
					$arr[$key]['grupo_nombre'] =trim($v['grupo_nombre']);				
					$arr[$key]['nombre'] =trim($v['nombre']);
					$arr[$key]['valorentero'] = trim($v['valorentero']); 
					$arr[$key]['valorcadena'] =  trim($v['valorcadena']);					
					$arr[$key]['activo'] =  trim($v['activo']);
				}
				else if  ($v['cod_empr']==$prm_cod_empr)
				{
					$contador=$contador+1;
					$arr[$key]['nro_secuencia'] = $contador;				
					$arr[$key]['id'] = trim($v['id']);
					$arr[$key]['grupo_id'] = trim($v['grupo_id']);
					$arr[$key]['grupo_nombre'] =trim($v['grupo_nombre']);				
					$arr[$key]['nombre'] =trim($v['nombre']);
					$arr[$key]['valorentero'] = trim($v['valorentero']); 
					$arr[$key]['valorcadena'] =  trim($v['valorcadena']);					
					$arr[$key]['activo'] =  trim($v['activo']);
				}
				
				
			endforeach;
		}
		
		//print_r($arr);
		
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
	
	public function Listar_ParametrosId()
	{
		$arr=NULL;
		$contador=0;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_id=trim($this->input->post('cod_id'));		
        $consulta =$this->Parametros_model->Listar_ParametrosId($prm_cod_id);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$contador=$contador+1;
				$arr[$key]['nro_secuencia'] = $contador;				
				$arr[$key]['id'] = trim($v['id']);
				$arr[$key]['grupo_id'] = trim($v['grupo_id']);
				$arr[$key]['grupo_nombre'] =trim($v['grupo_nombre']);				
				$arr[$key]['nombre'] =trim($v['nombre']);
				$arr[$key]['valorentero'] = trim($v['valorentero']); 
				$arr[$key]['valorcadena'] =  trim($v['valorcadena']);					
				$arr[$key]['activo'] =  trim($v['activo']);
			endforeach;
		}
		
		//print_r($arr);
		
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
	
	public function Guadar_Portalmultitabla()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		
		$prm_grupo_id=trim($this->input->post('cmb_variables'));
		$prm_grupo_nombre=trim($this->input->post('grupo_nombre'));
		$prm_nombre=trim($this->input->post('txt_nombrevariable'));
		$prm_valorentero=trim($this->input->post('txt_valorentero'));
		$prm_valorcadena=trim($this->input->post('txt_valorcadena'));
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		
		if ($prm_grupo_nombre=='RUTA_CERTIFICADO' or $prm_grupo_nombre=='RUTA_DOCUMENTOS')
		{
			if ($prm_valorcadena!='')
			{
				$prm_valorcadena=str_replace("\\","/",$prm_valorcadena);
				$ultimo_caracter=substr($prm_valorcadena,-1);
				if ($ultimo_caracter!='/')
				{
					$prm_valorcadena=$prm_valorcadena.'/';
				}
			}
		}

		$resultado =$this->Parametros_model->Guadar_Portalmultitabla($prm_grupo_id,$prm_grupo_nombre,$prm_nombre,$prm_valorentero,$prm_valorcadena,$prm_cod_empr);
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
	
	public function Modificar_Portalmultitabla()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_grupo_id=trim($this->input->post('cmb_variables'));
		$prm_id=trim($this->input->post('txt_codid'));
		$prm_nombre=trim($this->input->post('txt_nombrevariable'));
		$prm_valorentero=trim($this->input->post('txt_valorentero'));
		$prm_valorcadena=trim($this->input->post('txt_valorcadena'));
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		
		$prm_grupo_nombre=trim($this->input->post('grupo_nombre'));
		
		if ($prm_grupo_nombre=='RUTA_CERTIFICADO' or $prm_grupo_nombre=='RUTA_DOCUMENTOS')
		{
			if ($prm_valorcadena!='')
			{
				$prm_valorcadena=str_replace("\\","/",$prm_valorcadena);
				$ultimo_caracter=substr($prm_valorcadena,-1);
				if ($ultimo_caracter!='/')
				{
					$prm_valorcadena=$prm_valorcadena.'/';
				}
			}
		}
	
		$resultado =$this->Parametros_model->Modificar_Portalmultitabla($prm_grupo_id,$prm_id,$prm_nombre,$prm_valorentero,$prm_valorcadena,$prm_cod_empr);
		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}
	

	
	public function Eliminar_Portalmultitabla()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_id=trim($this->input->post('cod_id'));
		$resultado =$this->Parametros_model->Eliminar_Portalmultitabla($prm_id);		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}	

}



