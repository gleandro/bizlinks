<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Certificado extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Empresa_model');
		$this->load->model('Certificado_model');
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
			$prm['pagina_ver']='certificado';
			
			
			$Listar_Parametros=$this->Catalogos_model->Listar_Parametros();
			if(!empty($Listar_Parametros))//SI NO ES NULO O VACIO
			{
				if ($Listar_Parametros[0]['is_inhouse']==1)
				{
					$prm['Valor_Inhouse']=1;
				}
				else
				{
					$prm['Valor_Inhouse']=0;
				}
			}
			else
			{
				$prm['Valor_Inhouse']=0;
			}
			
			$Listar_MiEmpresa=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
			if(!empty($Listar_MiEmpresa))//SI NO ES NULO O VACIO
			{
				$prm['Cod_empr']=$Listar_MiEmpresa[0]['cod_empr'];	
				$prm['Razon_Social']=$Listar_MiEmpresa[0]['raz_social'];
				$prm['Ruc_empr']=$Listar_MiEmpresa[0]['ruc_empr'];
				$prm['Tipo_conffirma']=$Listar_MiEmpresa[0]['tipo_conffirma'];
			}
			else
			{
				$prm['Cod_empr']='';	
				$prm['Razon_Social']='';
				//$prm['Tipo_confunid']='';
				$prm['Tipo_conffirma']='';
			}
			
			$this->load->view('certificado/registrar_certificado',$prm);
		}		
    }
	
	public function Guardar_Configuration()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_id_emisor=trim($this->input->post('txt_cod_empr'));
		$prm_firma_local=trim($this->input->post('cmb_tipofirmadocumento'));
		$prm_alias=trim($this->input->post('txt_aliasfirma'));
		$prm_protection_val=trim($this->input->post('txt_valorproteccionfirma'));
		$prm_protection_key=trim($this->input->post('txt_llaveproteccionfirma'));
		$prm_path_key=trim($this->input->post('txt_nombrearchivo'));
		$prm_aditional=trim($this->input->post('txt_adicionalfirma'));
		$prm_expiry_key=trim($this->input->post('txt_fechavencimiento'));		
		$prm_usuario_sol=trim($this->input->post('txt_usuariosol'));
		$prm_clave_sol=trim($this->input->post('txt_clavesol'));
		
		
		$prm_valorinhouse=trim($this->input->post('txt_valorinhouse'));
		
		$prm_tipofirmaempresa=trim($this->input->post('txt_tipofirmaempresa'));
		

		$resultado =$this->Certificado_model->Guardar_Configuration($prm_id_emisor,$prm_firma_local,$prm_alias,$prm_protection_val,
				$prm_protection_key,$prm_path_key,$prm_aditional,$prm_expiry_key,$prm_tipofirmaempresa,$prm_usuario_sol,$prm_clave_sol,$prm_valorinhouse);
		
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
	
	
	public function Listar_ConfigurationId()
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
		$prm_id_emisor=trim($this->input->post('txt_cod_empr'));
        $consulta =$this->Certificado_model->Listar_ConfigurationId($prm_id_emisor);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;
				$arr[$key]['nro_secuencia'] = $Contador;				
				$ruc_empresa=substr($v['id_emisor'],2);				
				$consultaempresa=$this->Empresa_model->Listar_EmpresaIdRazonSocial($ruc_empresa);		
				$arr[$key]['raz_social'] =$consultaempresa['0']['raz_social'];// trim($v['raz_social']);
				$arr[$key]['id_emisor'] = $ruc_empresa;
				$arr[$key]['firma_local'] =trim($v['firma_local']);
				$arr[$key]['alias'] = trim($v['alias']); 
				$arr[$key]['protection_val'] =  trim($v['protection_val']);					
				$arr[$key]['protection_key'] =  'XXXX';//trim($v['protection_key']);	
				$arr[$key]['path_key'] =  trim($v['path_key']);
				$arr[$key]['aditional'] =  trim($v['aditional']);
				$arr[$key]['expiry_key'] =  trim($v['expiry_key']);
				
	
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
	
	
	
	
	
	
	
	public function Validar_Certificado()
	{

			$result['status']=0;
			$result['mensaje']='';
			if(!$this->Usuarioinicio_model->SessionExiste())
			{
				$result['status']=1000;
				echo json_encode($result);
				exit;
			}
			$nombre = $_FILES['archivo']['name'];
			$tipo = $_FILES['archivo']['type'];			
			$size = $_FILES['archivo']['size'];			
			$ruta_provisional = $_FILES['archivo']['tmp_name'];

			$carpeta = ''; //keys/			
			$rutacertificado=$this->Catalogos_model->Listar_RutaCertificado();
			if(!empty($rutacertificado))//SI NO ES NULO O VACIO
			{
				$carpeta=$rutacertificado[0]['valorcadena'];
			}
			
			//print_r($tipo);
			if ($tipo != 'application/octet-stream') //04/09/2016
			{
			  	$result['status']=0;
				$result['mensaje']='El formato del archivo es diferente a .JKS';
				$result['nombre']='';	
			}
			else if ($carpeta == '') //pfx
			{
			  	$result['status']=0;
				$result['mensaje']='No hay ruta configurado para el certificado';
				$result['nombre']='';	
			}
			
			else
			{
				/*
				$src = $carpeta.$nombre;//$nombre
				//print_r($src);
				//return;
				move_uploaded_file($ruta_provisional, $src);
				*/

				$result['status']=1;
				$result['mensaje']='';	
				$result['nombre']=$nombre;	
			}

			echo json_encode($result);
	//http://blog.eltallerweb.com/como-subir-un-archivo-con-jquery-y-ajax-php/
	
	}
	
	
	public function Subir_Certificado()
	{
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$nombre = $_FILES['archivo']['name'];
		$tipo = $_FILES['archivo']['type'];			
		$size = $_FILES['archivo']['size'];			
		$ruta_provisional = $_FILES['archivo']['tmp_name'];
		//$carpeta = "keys/";
		
		$carpeta = ''; //keys/	
		$rutacertificado=$this->Catalogos_model->Listar_RutaCertificado();
		if(!empty($rutacertificado))//SI NO ES NULO O VACIO
		{
			$carpeta=$rutacertificado[0]['valorcadena'];
		}

		if ($tipo != 'application/octet-stream')//04/09/2016
		{
			$result['status']=0;
		}
		else if ($carpeta == '') //pfx
		{
			$result['status']=0;
		}
		else
		{
			$src = $carpeta.$nombre;//$nombre
			move_uploaded_file($ruta_provisional, $src);
			$result['status']=1;
		}

		echo json_encode($result);
	}
	
	
	
	
	
}



