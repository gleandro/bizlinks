<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Estadistica extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Estadistica_model');
		$this->load->model('Empresa_model');
		$this->load->model('Usuarioinicio_model');
		$this->load->model('Menu_model');
		$this->load->model('excel_model'); 
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
			//$prm['Listar_Empresas']=$this->Empresa_model->Listar_Empresa($prm_cod_usuadm);		
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$prm['Listar_TipodeDocumento']=$this->Catalogos_model->Listar_TipodeDocumento();
			$prm['Listar_EstadoDocumento']=$this->Catalogos_model->Listar_EstadoDocumento();
			//$prm['Listar_EstadoDocumentoSunat']=$this->Catalogos_model->Listar_EstadoDocumentoSunat();
			
			//print_r($prm_cod_empr);
			$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);	
			if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
			{
				$prm['Ruc_Empresa']=$Listar_EmpresaId[0]['ruc_empr'];	
				$prm['Razon_Social']=$Listar_EmpresaId[0]['raz_social'];
			}
			else
			{
				$prm['Ruc_Empresa']='';	
				$prm['Razon_Social']='';
			}
			
			$prm['pagina_ver']='estadistica';
			$this->load->view('estadistica/estadisticaemisor',$prm);
			//$this->load->view('usuario/login'); 
		}		
    }

	public function Estadisticareceptor()
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
			//$prm['Listar_Empresas']=$this->Empresa_model->Listar_Empresa($prm_cod_usuadm);		
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$prm['Listar_TipodeDocumento']=$this->Catalogos_model->Listar_TipodeDocumento();
			$prm['Listar_EstadoDocumento']=$this->Catalogos_model->Listar_EstadoDocumento();
			//$prm['Listar_EstadoDocumentoSunat']=$this->Catalogos_model->Listar_EstadoDocumentoSunat();
			
			//print_r($prm_cod_empr);
			$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);	
			if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
			{
				$prm['Ruc_Empresa']=$Listar_EmpresaId[0]['ruc_empr'];	
				$prm['Razon_Social']=$Listar_EmpresaId[0]['raz_social'];
			}
			else
			{
				$prm['Ruc_Empresa']='';	
				$prm['Razon_Social']='';
			}
			
			$prm['pagina_ver']='estadistica/estadisticareceptor';
			$this->load->view('estadistica/estadisticareceptor',$prm);
		}		
    }

	public function Listar_Comprobantes()
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
		$prm_ruc_empr=trim($this->input->post('txt_RucEmpresa'));		
		$prm_documento_cliente=trim($this->input->post('txt_DocumentoCliente'));
		$prm_serie_numeroinicio=trim($this->input->post('txt_serienumeroinicio'));
		$prm_serie_numerofinal=trim($this->input->post('txt_serienumerofinal'));
		$prm_cod_estdoc=trim($this->input->post('Cmb_EstadoDocumento'));
		$prm_fec_emisiniciotmp=trim($this->input->post('txt_FechaEmisionInicio'));
		
		if ($prm_fec_emisiniciotmp=='')
		{
			$prm_fec_emisinicio='';
		}
		else
		{
			$prm_fec_emisiniciotmp=explode('/',$prm_fec_emisiniciotmp);
			$prm_fec_emisinicio=($prm_fec_emisiniciotmp[2].'-'.$prm_fec_emisiniciotmp[1].'-'.$prm_fec_emisiniciotmp[0]);
		}
		
		$prm_fec_emisfinaltmp=trim($this->input->post('txt_FechaEmisionFinal'));
		
		if ($prm_fec_emisfinaltmp=='')
		{
			$prm_fec_emisfinal='';
		}
		else
		{
			$prm_fec_emisfinaltmp=explode('/',$prm_fec_emisfinaltmp);
			$prm_fec_emisfinal=($prm_fec_emisfinaltmp[2].'-'.$prm_fec_emisfinaltmp[1].'-'.$prm_fec_emisfinaltmp[0]);
		}
		
		$prm_tipo_documentosunat=trim($this->input->post('Cmb_TipoDocumentoSunat'));
		$prm_estado_documentosunat=trim($this->input->post('Cmb_EstadoDocumentoSunat'));

		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();


		$consulta =$this->Estadistica_model->Listar_Comprobantes($prm_ruc_empr,$prm_documento_cliente,$prm_serie_numeroinicio,
					$prm_serie_numerofinal,$prm_cod_estdoc,$prm_fec_emisinicio,$prm_fec_emisfinal,
					$prm_tipo_documentosunat,$prm_estado_documentosunat,$prm_cod_empr,$prm_cod_usu);

		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):

					$Contador=$Contador+1;
					$arr[$key]['nro_secuencia'] = $Contador;
					$arr[$key]['nomb_tipodocumento'] =trim($v['nomb_tipodocumento']);

					$arr[$key]['firmado'] = trim($v['firmado']); 
					$arr[$key]['aceptado'] =  trim($v['aceptado']); 
					$arr[$key]['rechazado'] =  trim($v['rechazado']); 
					$arr[$key]['pendiente_declaracion'] = trim($v['pendiente_declaracion']); 
					$arr[$key]['espera_respuesta'] = trim($v['espera_respuesta']); 
					$arr[$key]['anulado'] = trim($v['anulado']);
					
					$arr[$key]['borrador'] =trim($v['borrador']); 
					$arr[$key]['por_procesar'] =trim($v['por_procesar']); 
					$arr[$key]['procesado'] =trim($v['procesado']); 
					$arr[$key]['error'] = trim($v['error']); 
					
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
	
	
	

	public function Exportar_ExcelGeneral()
	{
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$this->load->view('usuario/login');
			exit;
		}
		if (!isset($_GET['param1'])){	$prm_ruc_empr='';} else{$prm_ruc_empr=$_GET['param1'];}
		if (!isset($_GET['param2'])){	$prm_documento_cliente='';} else{$prm_documento_cliente=$_GET['param2'];}
		if (!isset($_GET['param3'])){	$prm_serie_numeroinicio='';} else{$prm_serie_numeroinicio=$_GET['param3'];}
		if (!isset($_GET['param4'])){	$prm_serie_numerofinal='';} else{$prm_serie_numerofinal=$_GET['param4'];}
		if (!isset($_GET['param5'])){	$prm_cod_estdoc=0;} else{$prm_cod_estdoc=$_GET['param5'];}
		if (!isset($_GET['param6'])){	$prm_fec_emisiniciotmp='';} else{$prm_fec_emisiniciotmp=$_GET['param6'];}
		if (!isset($_GET['param7'])){	$prm_fec_emisfinaltmp='';} else{$prm_fec_emisfinaltmp=$_GET['param7'];}	
		if (!isset($_GET['param8'])){	$prm_tipo_documentosunat='';} else{$prm_tipo_documentosunat=$_GET['param8'];}	
		if (!isset($_GET['param9'])){	$prm_estado_documentosunat='';} else{$prm_estado_documentosunat=$_GET['param9'];}		
		if (!isset($_GET['param10'])){	$prm_datosbuscar='';} else{$prm_datosbuscar=$_GET['param10'];}
		if (!isset($_GET['param11'])){	$prm_razonsocialcliente='';} else{$prm_razonsocialcliente=$_GET['param11'];}

		$consulta =$this->Estadistica_model->Listar_Comprobantes($prm_ruc_empr,$prm_documento_cliente,$prm_serie_numeroinicio,
					$prm_serie_numerofinal,$prm_cod_estdoc,$prm_fec_emisinicio,$prm_fec_emisfinal,
					$prm_tipo_documentosunat,$prm_estado_documentosunat);
	

		$prm['lista_datosdocumento']=$consulta;
		$prm['param1']=$prm_ruc_empr;
		$prm['param2']=$prm_razonsocialcliente;
		$prm['param3']=$prm_serie_numeroinicio;
		$prm['param4']=$prm_serie_numerofinal;
		$prm['param5']=$prm_cod_estdoc;
		if ($prm_fec_emisiniciotmp=='')
		{
			$prm_fec_emisinicio='';
		}
		else
		{
			$prm_fec_emisiniciotmp=explode('/',$prm_fec_emisiniciotmp);
			$prm_fec_emisinicio=($prm_fec_emisiniciotmp[2].'-'.$prm_fec_emisiniciotmp[1].'-'.$prm_fec_emisiniciotmp[0]);
		}
		$prm['param6']=$prm_fec_emisinicio;
		
		if ($prm_fec_emisfinaltmp=='')
		{
			$prm_fec_emisfinal='';
		}
		else
		{
			$prm_fec_emisfinaltmp=explode('/',$prm_fec_emisfinaltmp);
			$prm_fec_emisfinal=($prm_fec_emisfinaltmp[2].'-'.$prm_fec_emisfinaltmp[1].'-'.$prm_fec_emisfinaltmp[0]);
		}		
		$prm['param7']=$prm_fec_emisfinal;
		$prm['param8']=date('d/m/Y h:i:s');
		if ($prm_datosbuscar=='')
		{
			$prm['param9']='LISTADO GENERAL DE LOS COMPROBANTES';
		}
		else
		{
			$prm['param9']='LISTADO SELECCIONADO DE LOS COMPROBANTES';
		}		
		$prm['param10']=$prm_datosbuscar;
		$this->load->view('reportes/comprobantes/comprobantes_listadogeneral',$prm);		
	}

	
	
}



