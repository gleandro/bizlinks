<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientes extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Clientes_model');
		$this->load->model('Empresa_model');
		$this->load->model('Usuarioinicio_model');		
		$this->load->model('Catalogos_model');
		$this->load->model('Menu_model');
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
	
			//$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesos($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);
			$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);
			
			$Listar_Empresa=$this->Empresa_model->Listar_Empresa($this->Usuarioinicio_model->Get_Cod_UsuAdm());
			$prm['Listar_Empresa']=$Listar_Empresa;			
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$prm['Listar_Departamentos']=$this->Catalogos_model->Listar_Departamentos();
			$prm['Listar_TipodeDocumentoIdentidad']=$this->Catalogos_model->Listar_TipodeDocumentoIdentidad();
			$prm['Listar_Paises']=$this->Catalogos_model->Listar_Paises();
			
			
			$prm['pagina_ver']='clientes';
			
			
			$this->load->view('clientes/registrar_clientes',$prm);
		}		
    }
	
	public function Guardar_Cliente()
	{	
	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		//print_r('Antes de Guardar en model');
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$prm_tip_documento=trim($this->input->post('cmb_tipodoccliente'));
		$prm_ruc_empr=trim($this->input->post('txt_ruccliente'));
		$prm_raz_social=trim($this->input->post('txt_razonsocialcliente'));
		$prm_cod_actiempr='NULL';
		$prm_rep_legal='';
		$prm_pagi_empr='';
		$prm_fec_creac='NULL';
		$prm_cod_pais=trim($this->input->post('cmb_paiscliente'));
		$prm_cod_ubigeo=str_pad(trim($this->input->post('cmb_departamento')),2,'0',STR_PAD_LEFT).str_pad(trim($this->input->post('cmb_provincia')),2,'0',STR_PAD_LEFT).str_pad(trim($this->input->post('cmb_distrito')),2,'0',STR_PAD_LEFT);
		$prm_url_logoempr='';
		$prm_tipo_confserie=0;
		$prm_tipo_confunid=0;
		$prm_tipo_conffirma=0;
		$prm_nom_comercial=trim($this->input->post('txt_nombrecomercialcliente'));
		$prm_urbaniz_empresa=trim($this->input->post('txt_urbanizacioncliente'));
		$prm_direcc_empresa=trim($this->input->post('txt_direccioncliente'));
		$prm_email_cliente=trim($this->input->post('txt_correocliente'));
		
		//print_r('Guarda OOOOO');
		//return;
		$resultado =$this->Clientes_model->Guardar_Cliente($prm_cod_usu,$prm_cod_usuadm,$prm_ruc_empr,
			$prm_raz_social,$prm_cod_actiempr,$prm_rep_legal,$prm_pagi_empr,$prm_fec_creac,$prm_cod_pais,
			$prm_cod_ubigeo,$prm_url_logoempr,$prm_tip_documento,$prm_nom_comercial,$prm_urbaniz_empresa,
			$prm_direcc_empresa,$prm_tipo_confserie,$prm_tipo_confunid,$prm_tipo_conffirma,$prm_cod_empr,$prm_email_cliente);
		
		if ($resultado['result']==0)
		{
			$result['status']=0;		
		}
		else 
			if ($resultado['result']==1)
			{
				$result['status']=1;	
			}
			else
			{
				$result['status']=2;	
			}
		echo json_encode($result);
	}
	
	
	
	
	public function Modificar_Clientes()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_client=trim($this->input->post('txt_codcliente'));
		$prm_cod_tipdoc=trim($this->input->post('cmb_tipodoccliente'));
		$prm_raz_social=trim($this->input->post('txt_razonsocialcliente'));
		$prm_cod_pais=trim($this->input->post('cmb_paiscliente'));
		$prm_cod_ubigeo=str_pad(trim($this->input->post('cmb_departamento')),2,'0',STR_PAD_LEFT).str_pad(trim($this->input->post('cmb_provincia')),2,'0',STR_PAD_LEFT).str_pad(trim($this->input->post('cmb_distrito')),2,'0',STR_PAD_LEFT);
		$prm_direc_cliente=trim($this->input->post('txt_direccioncliente'));
		$prm_email_cliente=trim($this->input->post('txt_correocliente'));
		$prm_urbaniz_cliente=trim($this->input->post('txt_urbanizacioncliente'));
		$prm_nom_comercial=trim($this->input->post('txt_nombrecomercialcliente'));
		
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		
		$resultado =$this->Clientes_model->Modificar_Clientes(
								$prm_cod_client,
								$prm_cod_tipdoc,
								$prm_raz_social,
								$prm_cod_pais,
								$prm_cod_ubigeo,
								$prm_direc_cliente,
								$prm_email_cliente,
								$prm_urbaniz_cliente,
								$prm_nom_comercial,
								$prm_cod_empr,
								$prm_cod_usu);
		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}
	
	
	
	
	public function Eliminar_Clientes()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_empr=trim($this->input->post('cod_client'));
		$prm_cod_empremisor=$this->Usuarioinicio_model->Get_Cod_Empr();
		$resultado =$this->Clientes_model->Eliminar_Clientes($prm_cod_empr,$prm_cod_empremisor);		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}	
	
	public function Listar_Clientes()
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
		$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		
        $consulta =$this->Clientes_model->Listar_Clientes($prm_cod_usuadm,$prm_cod_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;				
				$arr[$key]['cod_client'] = trim($v['cod_empr']);
				//$arr[$key]['cod_empr'] = trim($v['cod_empr']);
				$arr[$key]['cod_tipdoc'] =trim($v['tip_documento']);				
				$arr[$key]['nro_docum'] =trim($v['ruc_empr']);
				$arr[$key]['raz_social'] = trim($v['raz_social']); 
				$arr[$key]['email_cliente'] =  trim($v['email_empresa']);					
				$arr[$key]['nom_comercial'] =  trim($v['nom_comercial']);
				$arr[$key]['urbaniz_cliente'] =  trim($v['urbaniz_empresa']);
				$arr[$key]['direc_cliente'] =  trim($v['direcc_empresa']);				
				$arr[$key]['cod_pais'] =  trim($v['cod_pais']);
				$arr[$key]['cod_departamento'] =  intval(substr(trim($v['cod_ubigeo']),0,2));
				$arr[$key]['cod_provincia'] =  intval(substr(trim($v['cod_ubigeo']),2,2));
				$arr[$key]['cod_distrito'] =  intval(substr(trim($v['cod_ubigeo']),4,2));
				$arr[$key]['nomb_tipodocumento'] =  trim($v['nomb_tipodocumento']);
				

				
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
	
	
	
	public function Listar_ClienteId()
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
		$prm_cod_empr=trim($this->input->post('cod_client'));
        $consulta =$this->Clientes_model->Listar_ClienteId($prm_cod_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;				
				$arr[$key]['cod_client'] = trim($v['cod_empr']);
				//$arr[$key]['cod_empr'] = trim($v['cod_empr']);
				$arr[$key]['cod_tipdoc'] =trim($v['tip_documento']);				
				$arr[$key]['nro_docum'] =trim($v['ruc_empr']);
				$arr[$key]['raz_social'] = trim($v['raz_social']); 
				$arr[$key]['email_cliente'] =  trim($v['email_empresa']);					
				$arr[$key]['nom_comercial'] =  trim($v['nom_comercial']);
				$arr[$key]['urbaniz_cliente'] =  trim($v['urbaniz_empresa']);
				$arr[$key]['direc_cliente'] =  trim($v['direcc_empresa']);
				$arr[$key]['nomb_tipodocumento'] =  trim($v['nomb_tipodocumento']);
						
				$arr[$key]['cod_pais'] =  trim($v['cod_pais']);
				
				$arr[$key]['cod_ubigeo'] =  trim($v['cod_ubigeo']);
				
				//$cod_depa=number_format(substr($v['cod_ubigeo'],0,2),0);
				//$cod_prov=number_format(substr($v['cod_ubigeo'],2,2),0);
				//$cod_dist=number_format(substr($v['cod_ubigeo'],4,2),0);
				
				//$arr[$key]['cod_departamento'] =  intval(substr(trim($v['cod_ubigeo']),0,2));
				//$arr[$key]['cod_provincia'] =  intval(substr(trim($v['cod_ubigeo']),2,2));
				//$arr[$key]['cod_distrito'] =  intval(substr(trim($v['cod_ubigeo']),4,2));
				
				
				$arr[$key]['cod_departamento'] =  intval(substr(trim($v['cod_ubigeo']),0,2));
				$arr[$key]['cod_provincia'] =  intval(substr(trim($v['cod_ubigeo']),2,2));
				$arr[$key]['cod_distrito'] =  intval(substr(trim($v['cod_ubigeo']),4,2));
				
				//print_r($arr[$key]['cod_distrito']);
				//print_r($arr[$key]['cod_provincia']);
				//print_r($arr[$key]['cod_distrito']);
				
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
	
	
	public function Listar_ClienteDocumento()
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
		$prm_tip_documento=trim($this->input->post('cmb_tipodoccliente'));
		$prm_ruc_empr=trim($this->input->post('txt_ruccliente'));
		
        $consulta =$this->Clientes_model->Listar_ClienteDocumento($prm_tip_documento,$prm_ruc_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$CantProd=$CantProd+1;
				$arr[$key]['nro_secuencia'] = $CantProd;				
				$arr[$key]['cod_client'] = trim($v['cod_empr']);
				//$arr[$key]['cod_empr'] = trim($v['cod_empr']);
				$arr[$key]['cod_tipdoc'] =trim($v['tip_documento']);				
				$arr[$key]['nro_docum'] =trim($v['ruc_empr']);
				$arr[$key]['raz_social'] = trim($v['raz_social']); 
				$arr[$key]['email_cliente'] =  trim($v['email_empresa']);					
				$arr[$key]['nom_comercial'] =  trim($v['nom_comercial']);
				$arr[$key]['urbaniz_cliente'] =  trim($v['urbaniz_empresa']);
				$arr[$key]['direc_cliente'] =  trim($v['direcc_empresa']);	
				
				//$cod_depa=number_format(substr($v['cod_ubigeo'],0,2),0);
				//$cod_prov=number_format(substr($v['cod_ubigeo'],2,2),0);
				//$cod_dist=number_format(substr($v['cod_ubigeo'],4,2),0);
							
				$arr[$key]['cod_pais'] =  trim($v['cod_pais']);
				//$arr[$key]['cod_departamento'] =  intval(substr(trim($v['cod_ubigeo']),0,2));
				//$arr[$key]['cod_provincia'] =  intval(substr(trim($v['cod_ubigeo']),2,2));
				//$arr[$key]['cod_distrito'] =  intval(substr(trim($v['cod_ubigeo']),4,2));
				
				$arr[$key]['cod_departamento'] =  intval(substr(trim($v['cod_ubigeo']),0,2));
				$arr[$key]['cod_provincia'] =  intval(substr(trim($v['cod_ubigeo']),2,2));
				$arr[$key]['cod_distrito'] =  intval(substr(trim($v['cod_ubigeo']),4,2));
				//print_r($arr[$key]['cod_departamento']);
				$arr[$key]['nomb_tipodocumento'] =  trim($v['nomb_tipodocumento']);
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
	
	
	
	public function listar_clientes_autocompletar() 
	{
        $term = trim($this->input->get('term'));
        $max = 10;
		$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
        $resultado =$this->Clientes_model->Listar_ClientesAutocompletarSimple($prm_cod_usuadm,$prm_cod_empr);
		
        if (!empty($resultado)) 
		{        
            $output = array();
            foreach ($resultado as $i => $val) 
			{
				$text1 = strtolower(trim($val['raz_social']));
				$text2 = strtolower(trim($term));
				if (strpos($text1, $text2) !== false) 
				{
					if (trim($val['raz_social'])=='-')
						$text2= 'GENERICO';
					else
						$text2= $val['raz_social'];
					$output[] = array
					(
						'value' => utf8_decode($text2),
						'cod_client' => $val['cod_client'],
						'nro_docum' => $val['nro_docum']
					);
				}
            }
            echo json_encode($output);
            exit;
        }
    }
	
	
	public function listar_clientes_autocompletarComprobante() 
	{
        $term = trim($this->input->get('term'));
        $max = 10;
		
		$tipodoc = trim($this->input->get('tipodoc'));
		$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
        $resultado =$this->Clientes_model->Listar_ClientesAutocompletar($prm_cod_usuadm,$prm_cod_empr,$tipodoc);
		
        if (!empty($resultado)) 
		{        
            $output = array();
            foreach ($resultado as $i => $val) 
			{
				$numero='';
				if (substr($val['nro_docum'],0,1)=='E')
				{
					$numero='-';
				}
				else
				{
					$numero=$val['nro_docum'];
				}
				$text1 = strtolower(trim($val['raz_social']));
				$text2 = strtolower(trim($term));
				if (strpos($text1, $text2) !== false) 
				{
					if (trim($val['raz_social'])=='-')
						$text2= 'GENERICO';
					else
						$text2= $val['raz_social'];
					$output[] = array
					(
						'value' => utf8_decode($text2),
						'cod_client' => $val['cod_empr'],
						'nro_docum' => $numero
					);
				}
            }
            echo json_encode($output);
            exit;
        }
    }
	
	
	public function Datos_ClienteId()
	{
		$arr=NULL;

		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();		
		$prm_cod_client=trim($this->input->post('cod_client'));
		$consulta =$this->Clientes_model->Datos_ClienteId($prm_cod_client);
		
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$arr[$key]['cod_client'] =trim($v['cod_client']); 
				$arr[$key]['nro_docum'] =  trim($v['nro_docum']);
				$arr[$key]['raz_social'] =  trim($v['raz_social']);
				$arr[$key]['direc_cliente'] =  trim($v['direc_cliente']);
				$arr[$key]['email_cliente'] =  trim($v['email_cliente']);
				
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
				
				$arr[$key]['urbaniz_cliente'] =  trim($v['urbaniz_cliente']);
				$arr[$key]['nom_comercial'] =  trim($v['nom_comercial']);
				$arr[$key]['nomb_pais'] =  trim($v['nomb_pais']);
				$arr[$key]['tipo_documento'] =  trim($v['tipo_documento']);
				
				$arr[$key]['cod_pais'] =  trim($v['cod_pais']);
				$arr[$key]['cod_tipdoc'] =  trim($v['cod_tipdoc']);
				

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
	
	
	
	public function Datos_ClienteIdModificar()
	{
		$arr=NULL;

		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();		
		$prm_tipo_doc=trim($this->input->post('tipodocumentocliente'));
		$prm_numer_doc=trim($this->input->post('txt_numerodoccliente'));
		$prm_nombre_razsocial=trim($this->input->post('txt_razonsocialcliente'));

		
		$consulta =$this->Clientes_model->Datos_ClienteIdModificar($prm_cod_empr,$prm_tipo_doc,$prm_numer_doc,$prm_nombre_razsocial);
		
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$arr[$key]['cod_client'] =trim($v['cod_client']); 
				$arr[$key]['nro_docum'] =  trim($v['nro_docum']);
				$arr[$key]['raz_social'] =  trim($v['raz_social']);
				$arr[$key]['direc_cliente'] =  trim($v['direc_cliente']);
				$arr[$key]['email_cliente'] =  trim($v['email_cliente']);
				
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
				
				$arr[$key]['urbaniz_cliente'] =  trim($v['urbaniz_cliente']);
				$arr[$key]['nom_comercial'] =  trim($v['nom_comercial']);
				$arr[$key]['nomb_pais'] =  trim($v['nomb_pais']);
				$arr[$key]['tipo_documento'] =  trim($v['tipo_documento']);
				
				$arr[$key]['cod_pais'] =  trim($v['cod_pais']);
				$arr[$key]['cod_tipdoc'] =  trim($v['cod_tipdoc']);
				

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



