<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Empresa_model');
		$this->load->model('Usuarioinicio_model');
		$this->load->model('Menu_model');
		$this->load->model('Catalogos_model');
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
			$prm['Listar_Paises']=$this->Catalogos_model->Listar_Paises();
			
			$Listar_Parametros=$this->Catalogos_model->Listar_Parametros();
			if(!empty($Listar_Parametros))//SI NO ES NULO O VACIO
			{
				$prm['Valor_Inhouse']=$Listar_Parametros[0]['is_inhouse'];
				/*if ($Listar_Parametros[0]['is_inhouse']==1)
				{
					$prm['Valor_Inhouse']=1;
				}
				else
				{
					$prm['Valor_Inhouse']=0;
				}*/
			}
			else
			{
				$prm['Valor_Inhouse']=0;
			}
			
			$prm['pagina_ver']='empresa';
			
			$this->load->view('empresa/empresa_listar',$prm);
		}		
    }
	
	public function Guardar_Empresa()
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
		$prm_ruc_empr=trim($this->input->post('txt_RucEmpresa'));
		$prm_raz_social=trim($this->input->post('txt_RazonSocialEmpresa'));
		$prm_cod_actiempr='NULL';
		$prm_rep_legal=trim($this->input->post('txt_RepLegalEmpresa'));
		$prm_pagi_empr='';
		$prm_fec_creac='NULL';
		$prm_cod_pais=trim($this->input->post('cmb_paisempresa'));
		$prm_cod_ubigeo=str_pad(trim($this->input->post('cmb_departamento')),2,'0',STR_PAD_LEFT).str_pad(trim($this->input->post('cmb_provincia')),2,'0',STR_PAD_LEFT).str_pad(trim($this->input->post('cmb_distrito')),2,'0',STR_PAD_LEFT);
		$prm_url_logoempr='';
		$prm_tipo_confserie=trim($this->input->post('Cmb_TipoConfiguracionSerie'));
		$prm_tipo_confunid=trim($this->input->post('Cmb_TipoConfiguracionUnidad'));
		$prm_tipo_conffirma=trim($this->input->post('Cmb_TipoConfiguracionFirma'));
		
		$prm_tip_documento=trim($this->input->post('cmb_tipodocempresa'));
		$prm_nom_comercial=trim($this->input->post('txt_NombreComercialEmpresa'));
		$prm_urbaniz_empresa=trim($this->input->post('txt_urbanizacionempresa'));
		$prm_direcc_empresa=trim($this->input->post('txt_direccionempresa'));

		$resultado =$this->Empresa_model->Guardar_Empresa($prm_cod_usu,$prm_cod_usuadm,$prm_ruc_empr,
			$prm_raz_social,$prm_cod_actiempr,$prm_rep_legal,$prm_pagi_empr,$prm_fec_creac,$prm_cod_pais,
			$prm_cod_ubigeo,$prm_url_logoempr,$prm_tip_documento,$prm_nom_comercial,$prm_urbaniz_empresa,
			$prm_direcc_empresa,$prm_tipo_confserie,$prm_tipo_confunid,$prm_tipo_conffirma);
		
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
	
	public function Modificar_Empresa()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_empr=trim($this->input->post('txt_CodEmpresa'));
		$prm_raz_social=trim($this->input->post('txt_RazonSocialEmpresa'));
		$prm_rep_legal=trim($this->input->post('txt_RepLegalEmpresa'));
		//$prm_cod_tipempr=trim($this->input->post('Cmb_TipoEmpresa'));
		$prm_tipo_confserie=trim($this->input->post('Cmb_TipoConfiguracionSerie'));
		$prm_tipo_confunid=trim($this->input->post('Cmb_TipoConfiguracionUnidad'));
		$prm_tipo_conffirma=trim($this->input->post('Cmb_TipoConfiguracionFirma'));
		
		$prm_tip_documento=trim($this->input->post('cmb_tipodocempresa'));
		$prm_nom_comercial=trim($this->input->post('txt_NombreComercialEmpresa'));
		$prm_urbaniz_empresa=trim($this->input->post('txt_urbanizacionempresa'));
		$prm_direcc_empresa=trim($this->input->post('txt_direccionempresa'));
		
		$prm_cod_pais=trim($this->input->post('cmb_paisempresa'));
		$prm_cod_ubigeo=str_pad(trim($this->input->post('cmb_departamento')),2,'0',STR_PAD_LEFT).str_pad(trim($this->input->post('cmb_provincia')),2,'0',STR_PAD_LEFT).str_pad(trim($this->input->post('cmb_distrito')),2,'0',STR_PAD_LEFT);
		
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_ruc_empr=trim($this->input->post('txt_RucEmpresa'));
		
		
		$resultado =$this->Empresa_model->Modificar_Empresa($prm_cod_empr,$prm_raz_social,$prm_rep_legal,$prm_tip_documento,
				$prm_nom_comercial,$prm_urbaniz_empresa,$prm_direcc_empresa,$prm_cod_pais,$prm_cod_ubigeo,
				$prm_tipo_confserie,$prm_tipo_confunid,$prm_tipo_conffirma,$prm_cod_usu,$prm_ruc_empr);
		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}

	
	public function Eliminar_Empresa()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		//$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_cod_empr=trim($this->input->post('cod_empr'));		
		$resultado =$this->Empresa_model->Eliminar_Empresa($prm_cod_empr);		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}	
	
	public function Listar_Empresa()
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
        $consulta =$this->Empresa_model->Listar_Empresa($prm_cod_usuadm);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['cod_empr'] = trim($v['cod_empr']);
				$arr[$key]['ruc_empr'] =trim($v['ruc_empr']);
				$arr[$key]['raz_social'] = trim($v['raz_social']); 
				$arr[$key]['rep_legal'] =  trim($v['rep_legal']);

				$arr[$key]['tipo_confserie'] =  trim($v['tipo_confserie']);
				$arr[$key]['tipo_confunid'] =  trim($v['tipo_confunid']);
				$arr[$key]['tipo_conffirma'] =  trim($v['tipo_conffirma']);
				
				$arr[$key]['cod_pais'] =  trim($v['cod_pais']);
				$arr[$key]['tip_documento'] =  trim($v['tip_documento']);
				$arr[$key]['nom_comercial'] =  trim($v['nom_comercial']);
				$arr[$key]['urbaniz_empresa'] =  trim($v['urbaniz_empresa']);
				$arr[$key]['direcc_empresa'] =  trim($v['direcc_empresa']);
				$arr[$key]['cod_departamento'] =  intval(substr(trim($v['cod_ubigeo']),0,2));
				$arr[$key]['cod_provincia'] =  intval(substr(trim($v['cod_ubigeo']),2,2));
				$arr[$key]['cod_distrito'] =  intval(substr(trim($v['cod_ubigeo']),4,2));

				

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
	
	public function Listar_EmpresaId()
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
		if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
		{
			$prm_cod_empr=0;
		}
		else
		{
			$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		}
        $consulta =$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):

				$arr[$key]['cod_empr'] = trim($v['cod_empr']);
				$arr[$key]['ruc_empr'] =trim($v['ruc_empr']);
				$arr[$key]['raz_social'] = trim($v['raz_social']); 
				$arr[$key]['rep_legal'] =  trim($v['rep_legal']);
								
				$arr[$key]['cod_pais'] =  trim($v['cod_pais']);
				$arr[$key]['nomb_pais'] =  trim($v['nomb_pais']);
				$arr[$key]['tip_documento'] =  trim($v['tip_documento']);
				$arr[$key]['nom_comercial'] =  trim($v['nom_comercial']);
				$arr[$key]['urbaniz_empresa'] =  trim($v['urbaniz_empresa']);
				$arr[$key]['direcc_empresa'] =  trim($v['direcc_empresa']);
				$arr[$key]['tipo_confserie'] =  trim($v['tipo_confserie']);				
				$arr[$key]['cod_ubigeo'] =  trim($v['cod_ubigeo']);
				
				$cod_depa=number_format(substr($v['cod_ubigeo'],0,2),0);
				$cod_prov=number_format(substr($v['cod_ubigeo'],2,2),0);
				$cod_dist=number_format(substr($v['cod_ubigeo'],4,2),0);
				
				$ubigeo =$this->Catalogos_model->Datos_Ubigeo($cod_depa,$cod_prov,$cod_dist);
				
				if(!empty($ubigeo))//SI NO ES NULO O VACIO
				{
					$arr[$key]['nomb_depa'] =  $ubigeo[0]['de_departamento'];
					$arr[$key]['nomb_prov'] =  $ubigeo[0]['de_provincia'];
					$arr[$key]['nomb_dist'] =  $ubigeo[0]['de_distrito'];
				}
				else
				{
					$arr[$key]['nomb_depa'] = '';
					$arr[$key]['nomb_prov'] = '';
					$arr[$key]['nomb_dist'] =  '';
				}
				
				$arr[$key]['cod_departamento'] =  intval(substr(trim($v['cod_ubigeo']),0,2));
				$arr[$key]['cod_provincia'] =  intval(substr(trim($v['cod_ubigeo']),2,2));
				$arr[$key]['cod_distrito'] =  intval(substr(trim($v['cod_ubigeo']),4,2));
				$arr[$key]['correo_usuario'] = $this->Usuarioinicio_model->Get_Email_Usu();				
				$arr[$key]['tipo_confunid'] =  trim($v['tipo_confunid']);	
				$arr[$key]['tipo_conffirma'] =  trim($v['tipo_conffirma']);
				
				

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
	


	public function Listar_EmpresaCodigo()
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
		$prm_cod_empr=trim($this->input->post('cod_empr'));			
        $consulta =$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):

				$arr[$key]['cod_empr'] = trim($v['cod_empr']);
				$arr[$key]['ruc_empr'] =trim($v['ruc_empr']);
				$arr[$key]['raz_social'] = trim($v['raz_social']); 
				$arr[$key]['rep_legal'] =  trim($v['rep_legal']);
								
				$arr[$key]['cod_pais'] =  trim($v['cod_pais']);
				$arr[$key]['nomb_pais'] =  trim($v['nomb_pais']);
				$arr[$key]['tip_documento'] =  trim($v['tip_documento']);
				$arr[$key]['nom_comercial'] =  trim($v['nom_comercial']);
				$arr[$key]['urbaniz_empresa'] =  trim($v['urbaniz_empresa']);
				$arr[$key]['direcc_empresa'] =  trim($v['direcc_empresa']);
				$arr[$key]['tipo_confserie'] =  trim($v['tipo_confserie']);				
				$arr[$key]['cod_ubigeo'] =  trim($v['cod_ubigeo']);
				
				$cod_depa=number_format(substr($v['cod_ubigeo'],0,2),0);
				$cod_prov=number_format(substr($v['cod_ubigeo'],2,2),0);
				$cod_dist=number_format(substr($v['cod_ubigeo'],4,2),0);
				
				$ubigeo =$this->Catalogos_model->Datos_Ubigeo($cod_depa,$cod_prov,$cod_dist);
				
				if(!empty($ubigeo))//SI NO ES NULO O VACIO
				{
					$arr[$key]['nomb_depa'] =  $ubigeo[0]['de_departamento'];
					$arr[$key]['nomb_prov'] =  $ubigeo[0]['de_provincia'];
					$arr[$key]['nomb_dist'] =  $ubigeo[0]['de_distrito'];
				}
				else
				{
					$arr[$key]['nomb_depa'] = '';
					$arr[$key]['nomb_prov'] = '';
					$arr[$key]['nomb_dist'] =  '';
				}
				
				$arr[$key]['cod_departamento'] =  intval(substr(trim($v['cod_ubigeo']),0,2));
				$arr[$key]['cod_provincia'] =  intval(substr(trim($v['cod_ubigeo']),2,2));
				$arr[$key]['cod_distrito'] =  intval(substr(trim($v['cod_ubigeo']),4,2));
				
				$arr[$key]['correo_usuario'] = $this->Usuarioinicio_model->Get_Email_Usu();				
				$arr[$key]['tipo_confunid'] =  trim($v['tipo_confunid']);	
				$arr[$key]['tipo_conffirma'] =  trim($v['tipo_conffirma']);
				
				

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
	
	
	public function Listar_EmpresaDocumento()
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
		$prm_tip_documento=trim($this->input->post('cmb_tipodocempresa'));		
		$prm_ruc_empr=trim($this->input->post('txt_rucempresa'));	

			
        $consulta =$this->Empresa_model->Listar_EmpresaDocumento($prm_tip_documento,$prm_ruc_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):

				$arr[$key]['cod_empr'] = trim($v['cod_empr']);
				$arr[$key]['ruc_empr'] =trim($v['ruc_empr']);
				$arr[$key]['raz_social'] = trim($v['raz_social']); 
				$arr[$key]['rep_legal'] =  trim($v['rep_legal']);
								
				$arr[$key]['cod_pais'] =  trim($v['cod_pais']);
				$arr[$key]['nomb_pais'] =  trim($v['nomb_pais']);
				$arr[$key]['tip_documento'] =  trim($v['tip_documento']);
				$arr[$key]['nom_comercial'] =  trim($v['nom_comercial']);
				$arr[$key]['urbaniz_empresa'] =  trim($v['urbaniz_empresa']);
				$arr[$key]['direcc_empresa'] =  trim($v['direcc_empresa']);
				$arr[$key]['tipo_confserie'] =  trim($v['tipo_confserie']);				
				$arr[$key]['cod_ubigeo'] =  trim($v['cod_ubigeo']);
				
				$cod_depa=number_format(substr($v['cod_ubigeo'],0,2),0);
				$cod_prov=number_format(substr($v['cod_ubigeo'],2,2),0);
				$cod_dist=number_format(substr($v['cod_ubigeo'],4,2),0);
				
				$ubigeo =$this->Catalogos_model->Datos_Ubigeo($cod_depa,$cod_prov,$cod_dist);
				
				if(!empty($ubigeo))//SI NO ES NULO O VACIO
				{
					$arr[$key]['nomb_depa'] =  $ubigeo[0]['de_departamento'];
					$arr[$key]['nomb_prov'] =  $ubigeo[0]['de_provincia'];
					$arr[$key]['nomb_dist'] =  $ubigeo[0]['de_distrito'];
				}
				else
				{
					$arr[$key]['nomb_depa'] = '';
					$arr[$key]['nomb_prov'] = '';
					$arr[$key]['nomb_dist'] =  '';
				}
				
				$arr[$key]['cod_departamento'] =  intval(substr(trim($v['cod_ubigeo']),0,2));
				$arr[$key]['cod_provincia'] =  intval(substr(trim($v['cod_ubigeo']),2,2));
				$arr[$key]['cod_distrito'] =  intval(substr(trim($v['cod_ubigeo']),4,2));
				$arr[$key]['correo_usuario'] = $this->Usuarioinicio_model->Get_Email_Usu();				
				$arr[$key]['tipo_confunid'] =  trim($v['tipo_confunid']);	
				$arr[$key]['tipo_conffirma'] =  trim($v['tipo_conffirma']);
				
				

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
	
	
	public function Listar_EmpresaGrid()
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
		$prm_cod_usuadm= $this->Usuarioinicio_model->Get_Cod_UsuAdm();
        $consulta =$this->Empresa_model->Listar_EmpresaGrid($prm_cod_usuadm);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;
				$arr[$key]['nro_secuencia'] = $Contador;
				$arr[$key]['cod_empr'] = trim($v['cod_empr']);
				$arr[$key]['ruc_empr'] =trim($v['ruc_empr']);
				$arr[$key]['raz_social'] = trim($v['raz_social']); 
				$arr[$key]['est_reg'] =  trim($v['est_reg']);					
				$arr[$key]['rep_legal'] =  trim($v['rep_legal']);	
				$arr[$key]['tipoempresa'] =  trim($v['tipoempresa']);
				
					
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
	
	public function Listar_EmpresaPermisos()
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
		$prm_cod_tipempresa=trim($this->input->post('cod_tipempresa'));	
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();	
		$consulta =$this->Empresa_model->Listar_EmpresaPermisos($prm_cod_tipempresa,$prm_cod_usuadm,$prm_cod_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;
				$arr[$key]['cod_empr'] = trim($v['cod_empr']);
				$arr[$key]['raz_social'] = trim($v['raz_social']); 
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
	
	public function Listar_EmpresaPermisosUsuario()
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
		$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();   
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		
		$prm_cod_tipempresa=trim($this->input->post('cod_tipempresa'));	
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();	

		$Listar_EmpresaContacto=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
		$Listar_EmpresaPermisosUsuario=NULL;
		if(!empty($Listar_EmpresaContacto))//SI NO ES NULO O VACIO
		{
			$contador=0;
			foreach($Listar_EmpresaContacto as $key=>$v):
				$contador=$contador+1;
				$Listar_EmpresaPermisosUsuario[$key]= trim($v['cod_empr']);				
			endforeach;
		}
		//$prm['Listar_EmpresaPermisosUsuario']=$Listar_EmpresaPermisosUsuario;
		$consulta =$this->Empresa_model->Listar_EmpresaPermisos($prm_cod_tipempresa,$prm_cod_usuadm,$prm_cod_empr);
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				if (in_array($v['cod_empr'], $Listar_EmpresaPermisosUsuario)) 
				{
					$CantProd=$CantProd+1;
					$arr[$key]['nro_secuencia'] = $CantProd;
					$arr[$key]['cod_empr'] = trim($v['cod_empr']);
					$arr[$key]['raz_social'] = trim($v['raz_social']); 
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
	
	public function Listar_EmpresaPermisosUsuarioReceptor()
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
		$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();   
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		
		$prm_cod_tipempresa=trim($this->input->post('cod_tipempresa'));	
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();	

		if ($prm_cod_tipempresa==1)//EMISOR
		{
			//$Listar_EmpresaContacto=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$Listar_EmpresaContacto=$this->Empresa_model->Listar_EmpresaPermisos($prm_cod_usuadm,$prm_tip_usu,$prm_cod_empr, $prm_cod_tipempresa);
			//$Listar_EmpresaPermisosUsuario=NULL;
			if(!empty($Listar_EmpresaContacto))//SI NO ES NULO O VACIO
			{
				$contador=0;
				foreach($Listar_EmpresaContacto as $key=>$v):
						$CantProd=$CantProd+1;
						$arr[$key]['nro_secuencia'] = $CantProd;
						$arr[$key]['cod_empr'] = trim($v['cod_empr']);
						$arr[$key]['raz_social'] = trim($v['raz_social']); 
						$arr[$key]['sel_empresa'] = $prm_cod_empr; 
				endforeach;
			}
			
		}
		else
		{

			$consulta =$this->Empresa_model->Listar_EmpresaPermisosReceptor($prm_cod_tipempresa,$prm_cod_usuadm,$prm_cod_empr);
			if(!empty($consulta))//SI NO ES NULO O VACIO
			{
				foreach($consulta as $key=>$v):
					if (substr(trim($v['ruc_empr']),0,1)!='E')//substr(trim($v['ruc_empr']),0,1)=='E'
					{	
						$CantProd=$CantProd+1;
						$arr[$key]['nro_secuencia'] = $CantProd;
						$arr[$key]['cod_empr'] = trim($v['cod_empr']);
						$arr[$key]['raz_social'] = trim($v['raz_social']); 
					}
				endforeach;
			}
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
	
	
	
	
	public function Crear_Graficoestadistico()
	{
		$this->load->library('my_pchartgrafico'); 
		// Dataset definition   
		 
		
		 $DataSet = new pData;  
		 $DataSet->AddPoint(array(10,2,3,5,3),"Serie1");  
		 $DataSet->AddPoint(array("aaaaaaaaaaaaaaa","bbbbbbbbbbbbb","ccccccccccc","dddddddddd","eeeeeeeeeeee"),"Serie2");  
		 $DataSet->AddAllSeries();  
		 $DataSet->SetAbsciseLabelSerie("Serie2");  
		  
		 // Initialise the graph  
		 //$Test = new pChart(380,200);
		 //$Test->drawFilledRoundedRectangle(7,7,373,193,5,240,240,240);  
		 //      $Test->drawRoundedRectangle(5,5,375,195,5,230,230,230);  
		  
		 $Test = new pChart(680,400);  
		 $Test->drawFilledRoundedRectangle(7,7,673,393,5,440,440,440);  
		       $Test->drawRoundedRectangle(5,5,675,395,5,430,430,430);  
		  
		  
		 // Draw the pie chart  
		 //$font_folder = $_SERVER['DOCUMENT_ROOT']."/libraries/pChart.1.27/Fonts/"; 
		 //$Test->setFontProperties($font_folder."tahoma.ttf",8);
		 //print_r($_SERVER['DOCUMENT_ROOT']);

		 $Test->setFontProperties("application/libraries/Fonts/tahoma.ttf",8);  //Fonts/tahoma.ttf //application\libraries\Fonts   PIE_PERCENTAGE
		 
		 //$Title = "Average Temperatures during the first months of 2008  ";
 		 //$Test->drawTextBox(0,210,700,230,$Title,0,255,255,255,ALIGN_RIGHT,TRUE,0,0,0,30);
		 
		 $Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),150,90,110,PIE_VALORES,TRUE,50,20,5);  
		 $Test->drawPieLegend(310,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);  
		  
		 $Test->Render("example10.png");  
		 
	}
}



