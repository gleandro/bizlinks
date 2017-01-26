<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Usuarioinicio_model');
		$this->load->model('usuario_model');
		$this->load->library('My_PHPMailer');
		$this->load->model('Menu_model');	
		$this->load->model('Empresa_model');
		$this->load->model('Catalogos_model');
		$this->load->model('Perfil_model');		
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
			}else
			{ 
				$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			}
			$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);
			$Listar_Empresa=$this->Empresa_model->Listar_Empresa($this->Usuarioinicio_model->Get_Cod_UsuAdm());
			$prm['Listar_Empresa']=$Listar_Empresa;			
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			
			$prm['pagina_ver']='usuario';
			
			$this->load->view('usuario/usuario_listar',$prm);
		}
        
    }
	
	public function CambiarPassword()
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
			
			$prm['pagina_ver']='usuario/cambiarpassword';
			
			$this->load->view('usuario/usuario_cambiopassword',$prm);
		}
    }
	
	
	public function NuevoUsuario()
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
			$prm['Listar_login']=$prm_cod_usu;
			$prm['pagina_ver']='usuario/nuevousuario';
			
			$this->load->view('usuario/usuario_listar',$prm);
		}		
    }
	
	public function CerrarSession()
    {
		if (!empty($_SESSION['SES_InicioSystem'])) 
		{
			$_SESSION['SES_InicioSystem']=NULL;
		}
		if (!empty($_SESSION['SES_MarcoTrabajo'])) 
		{
			$_SESSION['SES_MarcoTrabajo']=NULL;
		}
		
        $this->load->view('usuario/login'); 
    }
	
	public function CerrarSessionInterno()
    {
		if (!empty($_SESSION['SES_InicioSystem'])) 
		{
			$_SESSION['SES_InicioSystem']=NULL;
		}
		if (!empty($_SESSION['SES_MarcoTrabajo'])) 
		{
			$_SESSION['SES_MarcoTrabajo']=NULL;
		}
    }
	
	/*
	public function Validar_SesiondelUsuario()
    {
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$this->load->view('usuario/login'); 
			$result['status']=0;
			return;
		}
		$result['status']=1;
    }
	*/
	
	public function validar_usuario()
	{
		$result['status']=0;
		$this->CerrarSessionInterno();		
		//$prm_correo=trim($this->input->post('txt_correo'));//'iarevalo@neocompra.com';
		$prm_login=trim($this->input->post('txt_login'));//'iarevalo';
		$prm_password=md5(trim($this->input->post('txt_password')));	//md5('123456');

		$usuarioadmnin=$this->usuario_model->Validar_Usuario($prm_login,$prm_password);
		
		//print_r($usuarioadmnin);
		//echo('ilmer');
		//return(0);	
		

		if (!empty($usuarioadmnin)) 
		{
			if (strtoupper($usuarioadmnin[0]['email_usu'])==strtoupper($prm_login))
			{
				//DATOS INICIALES PARA LA SESION		
				$_SESSION['SES_InicioSystem']=$usuarioadmnin;
				$_SESSION['SES_MarcoTrabajo'][0]['fecha_session']=date('Y-m-d h:i:s');
				
				
				$_SESSION['SES_MarcoTrabajo'][0]['cod_empr']=0;	
				//$_SESSION['SES_MarcoTrabajo'][0]['ruc_emprconect']='';
				$_SESSION['SES_MarcoTrabajo'][0]['tipo_confserie']=0;	
				$_SESSION['SES_MarcoTrabajo'][0]['tipo_confunid']=0;
				$_SESSION['SES_MarcoTrabajo'][0]['porc_igv']=0;			
				//$_SESSION['SES_MarcoTrabajo'][0]['tipo_rolusuario']=0;			
				$_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion']=0;	
				
				
				$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
				$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
				$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();		
				$prm_cod_empr=0;
					
				//GENERAMOS SU MENU										
				$this->Menu_model->Listar_UsuarioAccesos($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu,1);					
				$result['status']=1;
			}
		}		
		echo json_encode($result);
	}	 
	
	
	public function guardar_usuario()
	{

		$result['status']=0;

		//$prm_cod_usuad=$this->Usuarioinicio_model->getCodUsuAd();
		$prm_nombre=trim($this->input->post('txt_nombre'));
		$prm_apellidos=trim($this->input->post('txt_apellidos'));
		$prm_correo=trim($this->input->post('txt_correo'));
		$prm_login=trim($this->input->post('txt_login'));
		$prm_password=md5(trim($this->input->post('txt_password')));
		
		//$this->EnviarCorreo($prm_correo);
		//$result['status']=1;
		
		$usuarioadmnin=$this->usuario_model->Guardar_UsuarioAdmin($prm_correo);
	
		//print_r($usuarioadmnin);
		//return;	
	
		if ($usuarioadmnin['result']>0)
		{
			
			$consulta=$this->usuario_model->Guardar_Usuario($usuarioadmnin['result'],1,
						$prm_login,$prm_password,$prm_nombre,$prm_apellidos,0,$prm_correo);			
			if($consulta['result']==1)
			{
			
				//GENERAMOS EL MENU PARA EL USUARIO ADMINISTRADOR QUE CREO LA CUENTA
				//$this->Menu_model->Guardar_MenudeUsuarioAdmin($usuarioadmnin[0]['cod_usuadm']);
				
				//SE ENVIA EL CORREO DEL REGISTRO DE LA CUENTA
				/*$enviocorreo=$this->EnviarCorreo_NuevaCuenta($prm_correo,$prm_login,
						trim($this->input->post('txt_password')),($prm_nombre.' '.$prm_apellidos), 
						$usuarioadmnin[0]['cod_usuadm'],$usuarioadmnin[0]['cod_aleatorio']);*/
			
			
				$result['status']=1;
			}
			else
			{
				$result['status']=0;
			}	
	
		}
		else
		{
			$result['status']=2;
		}
		echo json_encode($result);
	}
	
	
	public function Guardar_UsuarioInterno()
	{

		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}		
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();		
		$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
		$prm_cod_tipusu=2;
		$prm_login_usu=trim($this->input->post('txt_Login'));
		$prm_pass_usu=md5(trim($this->input->post('txt_Contrasena')));
		$prm_nom_usu=trim($this->input->post('txt_NombUsuario'));
		$prm_apell_usu=trim($this->input->post('txt_ApelUsuario'));
		$prm_cod_usupad=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_email_usu=trim($this->input->post('txt_Email'));
		
		$prm_opcion=1;//SI ES AL REGISTRAR
		
		$existeusu=$this->usuario_model->Buscar_UsuarioValidarExistencia($prm_opcion,$prm_email_usu);
		
		if(($existeusu['result'])==2)//EL LOGIN EXISTE
		{
			$result['status']=2;
		}
		else if(($existeusu['result'])==3)//el email existe
		{
			$result['status']=3;
		}	
		
		
		else if(($existeusu['result'])==1)//esta correcto
		{
			$consulta=$this->usuario_model->Guardar_Usuario($prm_cod_usuadm,$prm_cod_tipusu,$prm_login_usu,$prm_pass_usu,
						$prm_nom_usu,$prm_apell_usu,$prm_cod_usupad,$prm_email_usu);
			if(($consulta['result'])==1)
			{
				$result['status']=1;				
			}
			else
			{
				$result['status']=0;
			}
		}
		echo json_encode($result);
	}
	
	public function Modificar_UsuarioInterno()
	{

		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		
		$prm_cod_usu=trim($this->input->post('txt_Cod_Usu'));
		$prm_nom_usu=trim($this->input->post('txt_NombUsuario'));
		$prm_apell_usu=trim($this->input->post('txt_ApelUsuario'));
		$prm_email_usu=trim($this->input->post('txt_Email'));
		$prm_pass_usu=$this->input->post('txt_Contrasena');
		if ($prm_pass_usu!='')
		{
			$prm_pass_usu=md5(trim($prm_pass_usu));
		}
				
		$consulta=$this->usuario_model->Modificar_Usuario($prm_cod_usu,$prm_nom_usu,$prm_apell_usu,$prm_email_usu,$prm_pass_usu);
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
	
	
	public function Modificar_PasswordUsuario()
	{

		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}		
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_contrasena=md5(trim($this->input->post('txt_Contrasena')));
		
		$consulta=$this->usuario_model->Modificar_PasswordUsuario($prm_cod_usu,$prm_contrasena);
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

	
	public function Eliminar_UsuarioInterno()
	{
		$result['status']=0;	
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}	
		$prm_cod_usuelim=$this->Usuarioinicio_model->Get_Cod_Usu();		
		$prm_cod_usu=trim($this->input->post('cod_usu'));		
		$consulta=$this->usuario_model->Eliminar_Usuario($prm_cod_usu,$prm_cod_usuelim);
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
	
	
	
	public function Listar_UsuarioDatos()
	{
		$arr=NULL;
		$CantProd=0;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_usuadm= $this->Usuarioinicio_model->Get_Cod_UsuAdm();
        $consulta =$this->usuario_model->Listar_UsuarioDatos($prm_cod_usuadm);
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['cod_usu'] = trim($v['cod_usu']);
				$arr[$key]['cod_usuadm'] =trim($v['cod_usuadm']);
				//$arr[$key]['login_usu'] = trim($v['login_usu']); 
				$arr[$key]['nom_usu'] =  trim($v['nom_usu']);					
				$arr[$key]['apell_usu'] =  trim($v['apell_usu']);		
				$arr[$key]['email_usu'] =  trim($v['email_usu']);
				$arr[$key]['cod_tipusu'] =  trim($v['cod_tipusu']);				
				$arr[$key]['cod_tipusuconect'] = $_SESSION['SES_InicioSystem'][0]['cod_tipusu'];
						
			endforeach;
			//$result['status']=1;
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
	
	public function Listar_Usuario()
	{
		$Prm_Cod_UsuAdm= $this->Usuarioinicio_model->Get_Cod_UsuAdm();
        $consulta =$this->usuario_model->Listar_Usuario($Prm_Cod_UsuAdm);
		$Prm_Email_UsuAdm= $this->Usuarioinicio_model->Get_Email_UsuAdm();		
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
				$arr[$key]['cod_usu'] = trim($v['cod_usu']);
				$arr[$key]['tipousuario'] =trim($v['tipousuario']);
				$arr[$key]['cod_tipusu'] = trim($v['cod_tipusu']); 
				$arr[$key]['cod_usuadm'] = trim($v['cod_usuadm']);
				//$arr[$key]['login_usu'] = trim($v['login_usu']);				
				$arr[$key]['nom_usu'] =  trim($v['nom_usu']);					
				$arr[$key]['apell_usu'] =  trim($v['apell_usu']);		
				$arr[$key]['email_usu'] =  trim($v['email_usu']);
				$arr[$key]['est_reg'] =  trim($v['est_reg']);
				$arr[$key]['Email_UsuAdm'] =  $Prm_Email_UsuAdm;				
				
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
	
	
	public function Cambiar_MarcodeTrabajoEmpresa()
	{
		$result['status']=0;
		$prm_cod_emprtmp=trim($this->input->post('cod_empr'));
		$_SESSION['SES_MarcoTrabajo'][0]['cod_empr']=$prm_cod_emprtmp;
		$_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion']='0';
		
		$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();		
		$consulta=$this->Menu_model->Listar_RolesEmpresa($prm_tip_usu,$prm_cod_emprtmp);		
		if(!empty($consulta))
		{
			foreach($consulta as $key=>$v):

				$arr[$key]['cod_tipusu'] = trim($v['cod_rol']);
				$arr[$key]['nomb_tipusu'] =trim($v['nom_rol']);
			endforeach;
			
			
		}
		$consultaempresa=$this->Empresa_model->Listar_EmpresaIdRoles($prm_cod_emprtmp); 
		if(!empty($consultaempresa))
		{
			//print_r($consultaempresa);
			$_SESSION['SES_MarcoTrabajo'][0]['tipo_confserie']=$consultaempresa[0]['tipo_confserie'];	
			$_SESSION['SES_MarcoTrabajo'][0]['tipo_confunid']=$consultaempresa[0]['tipo_confunid'];	
			$_SESSION['SES_MarcoTrabajo'][0]['porc_igv']=$consultaempresa[0]['valor_igv'];
			$_SESSION['SES_MarcoTrabajo'][0]['conf_venta']=$consultaempresa[0]['conf_venta'];
			$_SESSION['SES_MarcoTrabajo'][0]['porc_otroscargos']=$consultaempresa[0]['valor_otroscargos'];
			//$_SESSION['SES_MarcoTrabajo'][0]['cod_empr']=$consultaempresa[0]['cod_empr'];
		}
		
		$prm_cod_rolseleccion='0';				

		$this->Menu_model->Listar_UsuarioAccesos($prm_cod_usuadm,$prm_cod_emprtmp,$prm_cod_usu,$prm_tip_usu,1,$prm_cod_rolseleccion);
		
		
		
		
		if(sizeof($consulta)>0)
		{
			$result['status']=1;
			$result['data']=$arr;
			$result['cod_rolseleccion']='0';
		}
		else
		{
			$result['status']=0;
			$result['data']="";
		}
		echo json_encode($result);		
    }
	
	
	public function Cambiar_MarcodeTrabajoEmpresaInicio()
	{
		$result['status']=0;
		$prm_cod_emprtmp=trim($this->input->post('cod_empr'));
		$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();	
		//$_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion']='0'; //SE AGREGO IAR	
		$consulta=$this->Menu_model->Listar_RolesEmpresa($prm_tip_usu,$prm_cod_emprtmp);		
		if(!empty($consulta))
		{
			foreach($consulta as $key=>$v):

				$arr[$key]['cod_tipusu'] = trim($v['cod_rol']);
				$arr[$key]['nomb_tipusu'] =trim($v['nom_rol']);
			endforeach;
		}
		if(sizeof($consulta)>0)
		{
			$result['status']=1;
			$result['data']=$arr;
			$result['cod_rolseleccion']=$_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion'];
		}
		else
		{
			$result['status']=0;
			$result['data']="";
		}
		
		//print_r($result);
		
		echo json_encode($result);		
    }
	
	
	
	
	public function Cambiar_MarcodeTrabajo()
	{
		$result['status']=0;		
		$prm_cod_emprtmp=trim($this->input->post('cod_empr'));
		$_SESSION['SES_MarcoTrabajo'][0]['cod_empr']=$prm_cod_emprtmp;
		
		//$_SESSION['SES_MarcoTrabajo'][0]['ruc_emprconect']=$prm_cod_emprtmp;
		$prm_cod_rolseleccion=trim($this->input->post('Cmb_RolSeleccion'));				
		//
		$_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion']=$prm_cod_rolseleccion;
		$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();		
		//print_r($prm_tip_usu);			

		$this->Menu_model->Listar_UsuarioAccesos($prm_cod_usuadm,$prm_cod_emprtmp,$prm_cod_usu,$prm_tip_usu,1,$prm_cod_rolseleccion);
		
		$result['status']=1;		
		echo json_encode($result);		
    } 	
	
	
	public function Listar_UsuarioxEmpresa()
	{
		$arr=NULL;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_empr=trim($this->input->post('txt_cod_empr'));
        $consulta =$this->usuario_model->Listar_UsuarioxEmpresa($prm_cod_empr);
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$arr[$key]['cod_usu'] = trim($v['cod_usu']);
				$arr[$key]['nom_usu'] =  trim($v['nom_usu']);					
				$arr[$key]['apell_usu'] =  trim($v['apell_usu']);
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



