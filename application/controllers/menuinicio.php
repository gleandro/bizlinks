<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menuinicio extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Menuinicio_model');
		$this->load->model('Sistema_model');
		
		
	}

    public function index()
    {
		$prm['Listar_Sistema']=$this->Sistema_model->Listar_Sistema();
		//$prm['Listar_TipoLicencia']=$this->Menuinicio_model->Listar_TipoLicencia();	
		$prm['Listar_UsuariosAdministradores']=$this->Menuinicio_model->Listar_UsuariosAdministradores();		
		
		$this->load->view('menuinicio/menuinicio_listar',$prm);
    }
	
	
	
	public function Listar_MenuInicioNivel()
	{
		$arr=NULL;	
		$result['status']=0;

		$prm_cod_nivel=trim($this->input->post('cod_nivel'));
				
        $Listar_MenuInicioNivel = $this->Menuinicio_model->Listar_MenuInicioNivel($prm_cod_nivel);

		if(!empty($Listar_MenuInicioNivel))
		{
			foreach($Listar_MenuInicioNivel as $key=>$v):
				$arr[$key]['cod_men'] =  trim($v['cod_men']);
				$arr[$key]['nom_men'] =  trim($v['nom_men']);
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
	
	
	public function Modificar_Menu()
	{
		$arr=NULL;	
		$result['status']=0;

		$prm_cod_men=trim($this->input->post('txt_Cod_Men'));
		$prm_cod_sist=trim($this->input->post('Cmb_Sistema'));
		$prm_nom_men=trim($this->input->post('txt_Nombre'));
		$prm_desc_men=trim($this->input->post('txt_DescMenu'));
		$prm_url_pag=trim($this->input->post('txt_urlPagina'));
		$prm_cod_menpad=trim($this->input->post('Cmb_MenuPadre'));
		$prm_cod_nivmen=trim($this->input->post('Cmb_Nivel'));		
		$prm_ord_ejec=trim($this->input->post('txt_OrdenEjecucion'));
		$prm_order_men=trim($this->input->post('txt_OrdenMenu'));		
		$prm_tien_hij=trim($this->input->post('cbox_TieneHijos'));
		$prm_conf_inic=trim($this->input->post('cbox_MenuInicio'));
		$prm_tip_men=trim($this->input->post('cbox_EjecPag'));
		

		$Listar_MenuInicioNivel = $this->Menuinicio_model->Modificar_Menu($prm_cod_men,$prm_cod_sist,$prm_nom_men,$prm_desc_men,
															$prm_url_pag,$prm_cod_menpad,$prm_cod_nivmen,
															$prm_ord_ejec,$prm_order_men,$prm_tien_hij,$prm_conf_inic,$prm_tip_men);


		$result['status']=1;
		echo json_encode($result);
	}
	
	public function Insertar_Menu()
	{
		$arr=NULL;	
		$result['status']=0;

		$prm_cod_sist=trim($this->input->post('Cmb_Sistema'));
		$prm_nom_men=trim($this->input->post('txt_Nombre'));
		$prm_desc_men=trim($this->input->post('txt_DescMenu'));
		$prm_url_pag=trim($this->input->post('txt_urlPagina'));
		$prm_cod_menpad=trim($this->input->post('Cmb_MenuPadre'));
		$prm_cod_nivmen=trim($this->input->post('Cmb_Nivel'));		
		$prm_ord_ejec=trim($this->input->post('txt_OrdenEjecucion'));
		$prm_order_men=trim($this->input->post('txt_OrdenMenu'));		
		$prm_tien_hij=trim($this->input->post('cbox_TieneHijos'));
		$prm_conf_inic=trim($this->input->post('cbox_MenuInicio'));
		$prm_tip_men=trim($this->input->post('cbox_EjecPag'));
		

		$Listar_MenuInicioNivel = $this->Menuinicio_model->Insertar_Menu($prm_cod_sist,$prm_nom_men,$prm_desc_men,
															$prm_url_pag,$prm_cod_menpad,$prm_cod_nivmen,
															$prm_ord_ejec,$prm_order_men,$prm_tien_hij,$prm_conf_inic,$prm_tip_men);


		$result['status']=1;
		echo json_encode($result);
	}
	
	public function Eliminar_Menu()
	{
		$arr=NULL;	
		$result['status']=0;

		$prm_cod_men=trim($this->input->post('cod_men'));
		$prm_ord_ejec=trim($this->input->post('ord_ejec'));

		$Listar_MenuInicioNivel = $this->Menuinicio_model->Eliminar_Menu($prm_cod_men,$prm_ord_ejec);


		$result['status']=1;
		echo json_encode($result);
	}
	
	
	
	public function Listar_MenuInicio()
	{
		$arr=NULL;	
		$result['status']=0;
		$CantProd=0;

        $Listar_MenuInicio = $this->Menuinicio_model->Listar_MenuInicio();
		if(!empty($Listar_MenuInicio))
		{
			foreach($Listar_MenuInicio as $key=>$v):
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['cod_men'] =  trim($v['cod_men']);
				$arr[$key]['nom_sist'] =  trim($v['nom_sist']);
				$arr[$key]['nom_men'] =  trim($v['nom_men']);
				$arr[$key]['url_pag'] =  trim($v['url_pag']);
				$arr[$key]['cod_nivmen'] =  trim($v['cod_nivmen']);
				$arr[$key]['tien_hij'] =  trim($v['tien_hij']);
				$arr[$key]['conf_inic'] =  trim($v['conf_inic']);
				
				$arr[$key]['order_men'] =  trim($v['order_men']);
				$arr[$key]['ord_ejec'] =  trim($v['ord_ejec']);
				$arr[$key]['menupadre'] =  trim($v['menupadre']);
				$arr[$key]['tip_men'] =  trim($v['tip_men']);
				
				

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
	
	
	
	public function Listar_MenuInicioId()
	{
		$arr=NULL;	
		$result['status']=0;
		$CantProd=0;
		$prm_cod_men=trim($this->input->post('cod_men'));
		
        $Listar_MenuInicio = $this->Menuinicio_model->Listar_MenuInicioId($prm_cod_men);
		if(!empty($Listar_MenuInicio))
		{
			foreach($Listar_MenuInicio as $key=>$v):
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['cod_men'] =  trim($v['cod_men']);
				$arr[$key]['cod_sist'] =  trim($v['cod_sist']);				
				$arr[$key]['nom_men'] =  trim($v['nom_men']);
				$arr[$key]['desc_men'] =  trim($v['desc_men']);
				$arr[$key]['url_pag'] =  trim($v['url_pag']);
				$arr[$key]['cod_menpad'] =  trim($v['cod_menpad']);
				$arr[$key]['cod_nivmen'] =  trim($v['cod_nivmen']);
				$arr[$key]['order_men'] =  trim($v['order_men']);
				$arr[$key]['ord_ejec'] =  trim($v['ord_ejec']);
				$arr[$key]['tien_hij'] =  trim($v['tien_hij']);
				$arr[$key]['conf_inic'] =  trim($v['conf_inic']);
				$arr[$key]['tip_men'] =  trim($v['tip_men']);
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
	
	
	
	
	public function Listar_TipoLicencia()
	{
		$arr=NULL;	
		$result['status']=0;

        $resultado = $this->Menuinicio_model->Listar_TipoLicencia();
		if(!empty($resultado))
		{
			foreach($resultado as $key=>$v):

				$arr[$key]['cod_tiplic'] =  trim($v['cod_tiplic']);
				$arr[$key]['nom_tiplic'] =  trim($v['nom_tiplic']);
				$arr[$key]['cod_inter'] =  trim($v['cod_inter']);

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
	
	
	
	
	public function Listar_MenuSistemaPendiente()
	{
		$arr=NULL;

		$result['status']=0;
		$prm_cod_plan=trim($this->input->post('Cmb_Planes'));
		
        $consulta =$this->Menuinicio_model->Listar_MenuSistemaPendiente($prm_cod_plan);
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
		$prm_cod_plan=trim($this->input->post('Cmb_Planes'));

		
        $consulta =$this->Menuinicio_model->Listar_MenuSistemaAsignado($prm_cod_plan);
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
	
	public function Guardar_Parametros()
	{	
		$result['status']=0;
		$prm_cod_plan=trim($this->input->post('Cmb_Planes'));
		$prm_cod_men=trim($this->input->post('cod_men'));


		$resultado =$this->Menuinicio_model->Guardar_Parametros($prm_cod_plan,$prm_cod_men);
		
		$result['status']=1;
		echo json_encode($result);
	}
	
	public function Guardar_MenuAdministrador()
	{	
		$result['status']=0;
		$prm_cod_usuadm=trim($this->input->post('Cmb_Administ'));
		$prm_cod_plan=trim($this->input->post('Cmb_PlanAdmi'));

		$this->Menuinicio_model->Guardar_MenuAdministrador($prm_cod_usuadm,$prm_cod_plan);
		
		$result['status']=1;
		echo json_encode($result);
	}	
	
	public function Guardar_MenuAdministradorUnitario()
	{	
		$result['status']=0;
		$prm_cod_usuadm=trim($this->input->post('Cmb_Administ'));
		$prm_cod_men=trim($this->input->post('cod_men'));
		
		$this->Menuinicio_model->Guardar_MenuAdministradorUnitario($prm_cod_usuadm,$prm_cod_men);		
		$result['status']=1;
		echo json_encode($result);		
	}	
	
	
	public function Guardar_SystemLicencia()
	{	
		$result['status']=0;
		
		$prm_cod_usuadm=trim($this->input->post('Cmb_AdminLicencia'));
		$prm_cod_plan=trim($this->input->post('Cmb_PlanAdmi'));
		$prm_cod_tiplic=trim($this->input->post('Cmb_Tipo'));
		$prm_fec_ini=trim($this->input->post('txt_fechainicio'));
		$prm_fec_fin=trim($this->input->post('txt_fechafinal'));
		$prm_obs_reg='';
		
		$this->Menuinicio_model->Guardar_SystemLicencia($prm_cod_usuadm,$prm_cod_plan,$prm_cod_tiplic,$prm_fec_ini,$prm_fec_fin,$prm_obs_reg);		
		$result['status']=1;
		echo json_encode($result);		
	}
	
	public function Eliminar_Acceso()
	{	
		$result['status']=0;
		$prm_cod_usuacc=trim($this->input->post('cod_usuacc'));
		$resultado =$this->Menuinicio_model->Eliminar_Acceso($prm_cod_usuacc);
		if ($resultado==1)
		{
			$result['status']=1;		
		}
		echo json_encode($result);
	}
	
	
	
	public function Eliminar_AccesoAdministrador()
	{	
		$result['status']=0;
		$prm_cod_usuacc=trim($this->input->post('cod_usuacc'));
		$resultado =$this->Menuinicio_model->Eliminar_AccesoAdministrador($prm_cod_usuacc);
		if ($resultado==1)
		{
			$result['status']=1;		
		}
		echo json_encode($result);
	}
	
	
	public function Eliminar_PeriodoLicencia()
	{	
		$result['status']=0;
		$prm_cod_sistlic=trim($this->input->post('cod_sistlic'));
		$resultado =$this->Menuinicio_model->Eliminar_PeriodoLicencia($prm_cod_sistlic);
		if ($resultado==1)
		{
			$result['status']=1;		
		}
		echo json_encode($result);
	}
	
	public function Listar_MenuPendienteAdministrador()
	{
		$arr=NULL;

		$result['status']=0;
		$prm_cod_usuadm=trim($this->input->post('Cmb_Administ'));
		
        $consulta =$this->Menuinicio_model->Listar_MenuPendienteAdministrador($prm_cod_usuadm);
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

	
	public function Listar_MenuAsignadosAdministrador()
	{
		$arr=NULL;

		$result['status']=0;
		$prm_cod_usuadm=trim($this->input->post('Cmb_Administ'));

		
        $consulta =$this->Menuinicio_model->Listar_MenuAsignadosAdministrador($prm_cod_usuadm);
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
				$arr[$key]['cod_menadm'] =  trim($v['cod_menadm']);
				
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
	
	
	
	
	public function Listar_LiceciaAdministrador()
	{
		$arr=NULL;
		$result['status']=0;		
        $consulta =$this->Menuinicio_model->Listar_LiceciaAdministrador();
		$CantProd=0;
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):			
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['cod_sistlic'] = trim($v['cod_sistlic']);
				$arr[$key]['email_usuadm'] = trim($v['email_usuadm']);
				$arr[$key]['fec_ini'] =trim($v['fec_ini']);
				$arr[$key]['fec_fin'] =  trim($v['fec_fin']);				
				$arr[$key]['nro_dias'] =  trim($v['nro_dias']);
				$arr[$key]['est_licencia'] =  trim($v['est_licencia']);
				$arr[$key]['nom_plan'] =  trim($v['nom_plan']);
				$arr[$key]['nom_tiplic'] =  trim($v['nom_tiplic']);
				$arr[$key]['est_activo'] =  trim($v['est_activo']);
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
	

	public function Listar_LiceciaAdministradorUnitario()
	{
		$arr=NULL;
		$result['status']=0;	
		$prm_cod_usuadm=trim($this->input->post('cod_usuadm'));	
        $consulta =$this->Menuinicio_model->Listar_LiceciaAdministradorUnitario($prm_cod_usuadm);
		
		$CantProd=0;
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):			
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['cod_sistlic'] = trim($v['cod_sistlic']);
				$arr[$key]['email_usuadm'] = trim($v['email_usuadm']);
				$arr[$key]['fec_ini'] =trim($v['fec_ini']);
				$arr[$key]['fec_fin'] =  trim($v['fec_fin']);				
				$arr[$key]['nro_dias'] =  trim($v['nro_dias']);
				$arr[$key]['est_licencia'] =  trim($v['est_licencia']);
				$arr[$key]['nom_plan'] =  trim($v['nom_plan']);
				$arr[$key]['nom_tiplic'] =  trim($v['nom_tiplic']);
				$arr[$key]['est_activo'] =  trim($v['est_activo']);
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



