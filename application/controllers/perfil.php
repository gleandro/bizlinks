<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Usuarioinicio_model');
		$this->load->model('usuario_model');
		$this->load->library('My_PHPMailer');
		$this->load->model('Menu_model');	
		$this->load->model('Empresa_model');
		$this->load->model('Perfil_model');
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
			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
			{
				$prm_cod_empr=0;
			}
			else
			{
				$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			}
	
			$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			
			$prm['Listar_Empresa']=$this->Empresa_model->Listar_Empresa($prm_cod_usuadm);
			$prm['Listar_UsuarioDatos']=$this->usuario_model->Listar_UsuarioDatos($prm_cod_usuadm);
			//$prm['Listar_Roles']=$this->usuario_model->Listar_Roles();
			$prm['Listar_Roles']=$this->Catalogos_model->Listar_Roles();
			$prm['Listar_Perfil']=$this->Perfil_model->Listar_Perfil();		
			
			$prm['pagina_ver']='roles';
			
			$this->load->view('perfil/perfil_listar',$prm);
		}
    }
	
	public function Guardar_Perfil()
	{
		$result['status']=0;		
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_nom_roles=trim($this->input->post('txt_Login'));
		$prm_cod_inter=trim($this->input->post('txt_Orden'));
		$prm_cod_rol=trim($this->input->post('cmb_tipoderoles'));
				
		$consulta=$this->Perfil_model->Guardar_Perfil($prm_nom_roles,$prm_cod_inter,$prm_cod_rol);
		if(($consulta['result'])==1)
		{
			$result['status']=1;
		}
		else
		{
			$result['status']=0;
		}
		echo json_encode($result);
	}
	
	public function Modificar_Perfil()
	{
		$result['status']=0;	
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}	
		$prm_cod_roles=trim($this->input->post('txt_Cod_Usu'));
		$prm_nom_roles=trim($this->input->post('txt_Login'));
		$prm_cod_inter=trim($this->input->post('txt_Orden'));
		$prm_cod_rol=trim($this->input->post('cmb_tipoderoles'));
				
		$consulta=$this->Perfil_model->Modificar_Perfil($prm_cod_roles,$prm_nom_roles,$prm_cod_inter,$prm_cod_rol);
		if(($consulta['result'])==1)
		{
			$result['status']=1;
		}
		else
		{
			$result['status']=0;
		}
		echo json_encode($result);
	}
	
	public function Eliminar_Perfil()
	{
		$result['status']=0;	
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}	
		$prm_cod_roles=trim($this->input->post('cod_roles'));

				
		$consulta=$this->Perfil_model->Eliminar_Perfil($prm_cod_roles);
		if(($consulta['result'])==1)
		{
			$result['status']=1;
		}
		else
		{
			$result['status']=0;
		}
		echo json_encode($result);
	}
	
	public function Listar_Perfil()
	{
        $consulta =$this->Perfil_model->Listar_Perfil();
		$CantProd=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['cod_perfil'] = trim($v['cod_perfil']);
				$arr[$key]['nom_perfil'] =trim($v['nom_perfil']);
				$arr[$key]['cod_inter'] = trim($v['cod_inter']);
				$arr[$key]['nomb_rol'] = trim($v['desc_rol']);
				$arr[$key]['cod_rol'] = trim($v['cod_rol']); 				
			endforeach;
			//$result['status']=1;
		}
		if(sizeof($consulta)>0)
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
	
	
	
	
	
	
	
	
	

	public function Guardar_Perfiles()
	{
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}		
		$prm_cod_men=trim($this->input->post('txt_ListaPermisosAsignar'));
		
		$prm_cod_roles=trim($this->input->post('Cmb_Roles'));		
		$consulta=$this->Perfil_model->Guardar_Perfiles($prm_cod_roles,$prm_cod_men);
		if(($consulta['result'])==1)
		{
			$result['status']=1;
		}
		else
		{
			$result['status']=0;
		}
		echo json_encode($result);
	}

	public function Eliminar_Perfiles()
	{
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_mepr=trim($this->input->post('cod_mepr'));		
		$consulta=$this->Perfil_model->Eliminar_Perfiles($prm_cod_mepr);
		if(($consulta['result'])==1)
		{
			$result['status']=1;
		}
		else
		{
			$result['status']=0;
		}

		echo json_encode($result);
	}

	public function Listar_MenuSistemaPendiente()
	{
		$arr=NULL;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_perfil=trim($this->input->post('Cmb_Roles'));		
		$perfilesid =$this->Perfil_model->Listar_PerfilId($prm_cod_perfil);
		if(!empty($perfilesid))//SI NO ES NULO O VACIO
		{
			$prm_cod_rol=$perfilesid[0]['cod_rol'];
		}

        $consulta =$this->Perfil_model->Listar_MenuSistemaPendiente($prm_cod_perfil,$prm_cod_rol);
		$CantProd=0;
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):			
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['cod_men'] = trim($v['cod_men']);
				
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
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_perfil=trim($this->input->post('Cmb_Roles'));		
		$perfilesid =$this->Perfil_model->Listar_PerfilId($prm_cod_perfil);
		if(!empty($perfilesid))//SI NO ES NULO O VACIO
		{
			$prm_cod_rol=$perfilesid[0]['cod_rol'];
		}
		
		$consulta =$this->Perfil_model->Listar_MenuSistemaAsignado($prm_cod_perfil,$prm_cod_rol);
		$CantProd=0;
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):			
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['cod_men'] = trim($v['cod_men']);
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
				$arr[$key]['cod_mepr'] =  trim($v['cod_mepr']);
				
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



