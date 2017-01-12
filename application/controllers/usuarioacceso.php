<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarioacceso extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Usuarioacceso_model');
		$this->load->model('Usuarioinicio_model');
		$this->load->model('Menu_model');
		$this->load->model('Empresa_model');
		$this->load->model('Usuario_model');
		
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
			
			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
			{
				$prm_cod_empr=0;
			}
			else
			{
				$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			}
			
			$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);

			$prm['Listar_Empresa']=$this->Empresa_model->Listar_Empresa($prm_cod_usuadm);
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$prm['Listar_UsuarioDatos']=$this->Usuario_model->Listar_UsuarioDatos($prm_cod_usuadm);
			$prm['pagina_ver']='usuarioacceso';

			$this->load->view('usuarioacceso/accesouser_guardar',$prm);
		}		
    }


	public function Guardar_Parametros()
	{	
		$result['status']=0;
		
		$prm_cod_usuadm= $this->Usuarioinicio_model->Get_Cod_UsuAdm();
		$prm_cod_usureg= $this->Usuarioinicio_model->Get_Cod_Usu();
		
		$prm_cod_empr=trim($this->input->post('Cmb_Empresa'));
		$prm_cod_usu=trim($this->input->post('Cmb_Usuario'));
		$prm_cod_roles=trim($this->input->post('Cmb_Roles'));
		$prm_cod_perfil=trim($this->input->post('Cmb_Perfiles'));
		
		$prm_listapermisosasignar=trim($this->input->post('txt_ListaPermisosAsignar'));
		
		$resultado =$this->Usuarioacceso_model->Guardar_Parametros($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,
				$prm_cod_usureg,$prm_cod_roles,$prm_listapermisosasignar,$prm_cod_perfil);
		if ($resultado['result']>0)
		{
			$result['status']=1;
		}
		echo json_encode($result);
	}
	
	public function Eliminar_Acceso()
	{	
		$result['status']=0;
		

		$prm_cod_usu= $this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_cod_usuacc=trim($this->input->post('cod_usuacc'));

		$resultado =$this->Usuarioacceso_model->Eliminar_Acceso($prm_cod_usuacc,$prm_cod_usu);
		if ($resultado==1)
		{
			$result['status']=1;		
		}
		echo json_encode($result);
	}
	
	
	
	public function Listar_MenuSistemaPendiente()
	{
		$arr=NULL;

		$result['status']=0;
		$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
		$prm_cod_empr=trim($this->input->post('Cmb_Empresa'));
		$prm_cod_usu=trim($this->input->post('Cmb_Usuario'));
		$prm_cod_roles=trim($this->input->post('Cmb_Roles'));
		$prm_cod_perfil=trim($this->input->post('Cmb_Perfiles'));  
		
        $consulta =$this->Usuarioacceso_model->Listar_MenuSistemaPendiente($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_cod_roles,$prm_cod_perfil);
		$CantProd=0;
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):			
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['cod_men'] = trim($v['cod_men']);
				//$arr[$key]['nom_men'] =trim($v['nom_men']);
				$menu='';
				if (trim($v['nom_men5'])!='')
				{
					$menu=$menu.trim($v['nom_men5']).'-->';
				} 
				if (trim($v['nom_men4'])!='')
				{
					$menu=$menu.trim($v['nom_men4']).'-->';
				}
				if (trim($v['nom_men3'])!='')
				{
					$menu=$menu.trim($v['nom_men3']).'-->';
				}
				if (trim($v['nom_men2'])!='')
				{
					$menu=$menu.trim($v['nom_men2']).'-->';
				}
				if (trim($v['nom_men1'])!='')
				{
					$menu=$menu.trim($v['nom_men1']);
				}				
				$arr[$key]['nom_men'] =$menu;
				$arr[$key]['url_pag'] =  trim($v['url_pag']);				
				$arr[$key]['cod_nivmen'] =  trim($v['cod_nivmen']);
				$arr[$key]['order_men'] =  trim($v['order_men']);
				$arr[$key]['tip_men'] =  trim($v['tip_men']);
				$arr[$key]['cod_menpad'] =  trim($v['cod_menpad']);
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

	
	public function Listar_MenuSistemaAsignado()
	{
		$arr=NULL;

		$result['status']=0;
		$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
		//$prm_cod_empr= 1;//$this->Usuarioinicio_model->Get_Cod_Empr();
		$prm_cod_empr=trim($this->input->post('Cmb_Empresa'));
		$prm_cod_usu=trim($this->input->post('Cmb_Usuario'));
		$prm_cod_roles=trim($this->input->post('Cmb_Roles'));
		$prm_cod_perfil=trim($this->input->post('Cmb_Perfiles'));   
		
        $consulta =$this->Usuarioacceso_model->Listar_MenuSistemaAsignado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_cod_roles,$prm_cod_perfil);
		$CantProd=0;
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):			
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['cod_men'] = trim($v['cod_men']);
				//$arr[$key]['nom_men'] =trim($v['nom_men']);
				$menu='';
				if (trim($v['nom_men5'])!='')
				{
					$menu=$menu.trim($v['nom_men5']).'-->';
				} 
				if (trim($v['nom_men4'])!='')
				{
					$menu=$menu.trim($v['nom_men4']).'-->';
				}
				if (trim($v['nom_men3'])!='')
				{
					$menu=$menu.trim($v['nom_men3']).'-->';
				}
				if (trim($v['nom_men2'])!='')
				{
					$menu=$menu.trim($v['nom_men2']).'-->';
				}
				if (trim($v['nom_men1'])!='')
				{
					$menu=$menu.trim($v['nom_men1']);
				}				
				$arr[$key]['nom_men'] =$menu;
				$arr[$key]['url_pag'] =  trim($v['url_pag']);				
				$arr[$key]['cod_nivmen'] =  trim($v['cod_nivmen']);
				$arr[$key]['order_men'] =  trim($v['order_men']);
				$arr[$key]['tip_men'] =  trim($v['tip_men']);
				$arr[$key]['cod_menpad'] =  trim($v['cod_menpad']);
				$arr[$key]['cod_usuacc'] =  trim($v['cod_usuacc']);
				$arr[$key]['tipousuario'] =  trim($v['tipousuario']);	
				$arr[$key]['nomb_perfil'] =  trim($v['nomb_perfil']);	
							
				
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
	
	public function Listar_UsuariosPorEmpresa()
	{
		$arr=NULL;

		$result['status']=0;
		$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
        $consulta =$this->Usuarioacceso_model->Listar_UsuariosPorEmpresa($prm_cod_usuadm);
		$CantProd=0;
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):			
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['nom_usu'] = trim($v['nom_usu']);
				$arr[$key]['apell_usu'] =trim($v['apell_usu']);
				$arr[$key]['email_usu'] =  trim($v['email_usu']);				
				$arr[$key]['tipo_usuario'] =  trim($v['tipo_usuario']);
				$arr[$key]['ruc_empr'] =  trim($v['ruc_empr']);
				$arr[$key]['raz_social'] =  trim($v['raz_social']);
				//$arr[$key]['tipo_empresa'] =  trim($v['tipo_empresa']);
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
	
	
}



