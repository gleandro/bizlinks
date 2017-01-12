<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seriedocumentos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Seriedocumentos_model');
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
			$Listar_EmpresaContacto=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$prm['Listar_Empresas']=$Listar_EmpresaContacto;
			
			$Listar_MiEmpresa=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
			if(!empty($Listar_MiEmpresa))//SI NO ES NULO O VACIO
			{
				$prm['Cod_empr']=$Listar_MiEmpresa[0]['cod_empr'];	
				$prm['Razon_Social']=$Listar_MiEmpresa[0]['raz_social'];
				$prm['Tipo_confunid']=$Listar_MiEmpresa[0]['tipo_confunid'];
				$prm['Tipo_confserie']=$Listar_MiEmpresa[0]['tipo_confserie'];
			}
			else
			{
				$prm['Cod_empr']='';	
				$prm['Razon_Social']='';
				$prm['Tipo_confunid']='';
				$prm['Tipo_confserie']='';
			}
			//$prm['Listar_MiEmpresa']=$Listar_MiEmpresa;	
			
			$Listar_EmpresaPermisosUsuario=NULL;
			if(!empty($Listar_EmpresaContacto))//SI NO ES NULO O VACIO
			{
				$contador=0;
				foreach($Listar_EmpresaContacto as $key=>$v):
					$contador=$contador+1;
					$Listar_EmpresaPermisosUsuario[$key]= trim($v['cod_empr']);				
				endforeach;
			}
			
			$prm['Listar_Departamentos']=$this->Catalogos_model->Listar_Departamentos();
			$prm['Listar_TipodeDocumento']=$this->Catalogos_model->Listar_TipodeDocumento();
			$prm['Listar_EmpresaPermisosUsuario']=$Listar_EmpresaPermisosUsuario;	
			$prm['pagina_ver']='seriedocumentos';
			
			$this->load->view('seriedocumentos/seriedocumentos',$prm);
		}		
    }
	
	
	public function Listar_Seriedocumentos()
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
		$prm_cod_empr=trim($this->input->post('txt_cod_empr'));
        $consulta =$this->Seriedocumentos_model->Listar_Seriedocumentos($prm_cod_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$contador=$contador+1;
				$arr[$key]['nro_secuencia'] = $contador;				
				$arr[$key]['cod_confser'] = trim($v['cod_confser']);
				$arr[$key]['cod_empr'] = trim($v['cod_empr']);
				$arr[$key]['cod_usu'] =trim($v['cod_usu']);				
				$arr[$key]['tip_doc'] =trim($v['tip_doc']);
				$arr[$key]['nomb_tipdoc'] = trim($v['nomb_tipdoc']); 
				$arr[$key]['ser_doc'] =  trim($v['ser_doc']);					
				$arr[$key]['num_doc'] =  trim($v['num_doc']);
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
	
	public function Listar_SeriedocumentosId()
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
		$prm_cod_confser=trim($this->input->post('cod_confser'));		
        $consulta =$this->Seriedocumentos_model->Listar_SeriedocumentosId($prm_cod_confser);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$contador=$contador+1;
				$arr[$key]['nro_secuencia'] = $contador;				
				$arr[$key]['cod_confser'] = trim($v['cod_confser']);
				$arr[$key]['cod_empr'] = trim($v['cod_empr']);
				$arr[$key]['cod_usu'] =trim($v['cod_usu']);				
				$arr[$key]['tip_doc'] =trim($v['tip_doc']);
				$arr[$key]['nomb_tipdoc'] = trim($v['nomb_tipdoc']); 
				$arr[$key]['ser_doc'] =  trim($v['ser_doc']);					
				$arr[$key]['num_doc'] =  trim($v['num_doc']);
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
	
	public function Guadar_SerieNumeracion()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_empr=trim($this->input->post('txt_cod_empr'));
		$prm_cod_usu=trim($this->input->post('cmb_usuariodocumento'));
		$prm_tip_doc=trim($this->input->post('cmb_tipodocumento'));
		$prm_ser_doc=trim($this->input->post('txt_seriedocumento'));
		$prm_num_doc=trim($this->input->post('txt_numerodocumento'));
		$prm_usu_reg=$this->Usuarioinicio_model->Get_Cod_Usu();
		//print_r('serrie');
		//print_r($prm_ser_doc);
		$resultado =$this->Seriedocumentos_model->Guadar_SerieNumeracion($prm_cod_empr,$prm_cod_usu,$prm_tip_doc,$prm_ser_doc,$prm_num_doc,$prm_usu_reg);
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
	
	public function Guadar_SerieNumeracionxUsuario()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_confser=trim($this->input->post('cmb_tipodocumentoseries'));
		$prm_cod_usu=trim($this->input->post('cmb_usuarioempresa'));
		$resultado =$this->Seriedocumentos_model->Guadar_SerieNumeracionxUsuario($prm_cod_confser,$prm_cod_usu);
		if ($resultado['result']==0)
		{
			$result['status']=0;		
		}
		else if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		else if ($resultado['result']==3)
		{
			$result['status']=3;	//YA ESTA REGISTRADO
		}
		else
		{
			$result['status']=2;	
		}
		echo json_encode($result);
	}
	
	
	
	public function Modificar_SerieNumeracion()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_confser=trim($this->input->post('txt_codconfser'));		
		$prm_cod_usu=trim($this->input->post('cmb_usuariodocumento'));
		$prm_num_doc=trim($this->input->post('txt_numerodocumento'));
				
		$resultado =$this->Seriedocumentos_model->Modificar_SerieNumeracion($prm_cod_confser,$prm_cod_usu,$prm_num_doc);
		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}
	

	
	public function Eliminar_SerieNumeracion()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_confser=trim($this->input->post('cod_confser'));
		$resultado =$this->Seriedocumentos_model->Eliminar_SerieNumeracion($prm_cod_confser);		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}
	
	
	
	
	public function Listar_SeriedocumentosxUsuario()
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
		$prm_cod_empr=trim($this->input->post('txt_cod_empr'));
        $consulta =$this->Seriedocumentos_model->Listar_SeriedocumentosxUsuario($prm_cod_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$contador=$contador+1;
				$arr[$key]['nro_secuencia'] = $contador;				
				$arr[$key]['cod_confserusu'] = trim($v['cod_confserusu']);
				$arr[$key]['cod_usu'] =trim($v['cod_usu']);
				$arr[$key]['nomb_tipdoc'] = trim($v['nomb_tipdoc']); 
				$arr[$key]['ser_doc'] =  trim($v['ser_doc']);					
				$arr[$key]['nom_usu'] =strtoupper($v['nom_usu']);
				$arr[$key]['apell_usu'] =strtoupper($v['apell_usu']);
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
	
	public function Eliminar_SerieNumeracionxUsuario()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_confserusu=trim($this->input->post('cod_confserusu'));
		$resultado =$this->Seriedocumentos_model->Eliminar_SerieNumeracionxUsuario($prm_cod_confserusu);		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}
	
		

}



