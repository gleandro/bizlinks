<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class portalException extends Exception {}

class Comprobante extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Comprobante_model');
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
			$prm['valor_igv']=$this->Usuarioinicio_model->Get_Valor_IGV();	
			$prm['valor_otroscargos']=$this->Usuarioinicio_model->Get_Valor_OtrosCargos();	
			
			$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);
			
			$Listar_Empresa=$this->Empresa_model->Listar_Empresa($this->Usuarioinicio_model->Get_Cod_UsuAdm());
			$prm['Listar_Empresa']=$Listar_Empresa;			
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$prm['Listar_TipodeDocumento']=$this->Catalogos_model->Listar_TipodeDocumento();
			$prm['Config_ValorPrecio']=$_SESSION['SES_MarcoTrabajo'][0]['conf_venta'];			
			
			$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
			
			if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
			{
				$prm['Ruc_Empresa']=$Listar_EmpresaId[0]['ruc_empr'];	
				$prm['Razon_Social']=$Listar_EmpresaId[0]['raz_social'];
				$prm['Tipo_confserie']=$Listar_EmpresaId[0]['tipo_confserie'];
			}
			else
			{
				$prm['Ruc_Empresa']='';	
				$prm['Razon_Social']='';
				$prm['Tipo_confserie']='';
			}
			$prm['pagina_ver']='comprobante';
			
			//BORRAMOS LOS DATOS DEL USUARIO EN LA TABLA TEMPORAL
			$this->Comprobante_model->Eliminar_DocumentosUsuario($prm_cod_empr,$prm_cod_usu);
			
			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
			{
				$prm_tipo_confunidad=0;
			}
			else
			{
				$prm_tipo_confunidad=$this->Usuarioinicio_model->Get_Tipo_ConfUnidad();
			}

			$prm['Listar_Unidades']=$this->Catalogos_model->Datos_Unidades($prm_cod_empr,$prm_tipo_confunidad);
			
			
			if (!isset($_GET['param1'])){	$prm_documentomodificar='';} else{$prm_documentomodificar=$_GET['param1'];}
			
			$prm['documentomodificar']=$prm_documentomodificar;
			$this->load->view('comprobante/registrocomprobante',$prm);
		}		
	}
	
	public function ListaComprobantes()
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
			$prm['Listar_Monedas']=$this->Catalogos_model->Listar_Monedas();
			
			//print_r($prm_cod_empr);
			$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);	
			if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
			{
				$prm['Ruc_Empresa']=$Listar_EmpresaId[0]['ruc_empr'];	
				$prm['Razon_Social']=$Listar_EmpresaId[0]['raz_social'];
				$prm['Tipo_confserie']=$Listar_EmpresaId[0]['tipo_confserie'];
			}
			else
			{
				$prm['Ruc_Empresa']='';	
				$prm['Razon_Social']='';
				$prm['Tipo_confserie']='';
			}

			$prm['pagina_ver']='comprobante/listacomprobantes';
			
			//echo((date("Y-m-d H-i-s")).'.'.substr(microtime(),0,5)*1000);
			
			$this->load->view('comprobante/listacomprobantes',$prm);
		}		
	}
	
	public function ListaComprobantesReceptor()
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
			
			$Listar_Empresa=$this->Empresa_model->Listar_Empresa($this->Usuarioinicio_model->Get_Cod_UsuAdm());
			$prm['Listar_Empresa']=$Listar_Empresa;			
			//$prm['Listar_Empresas']=$this->Empresa_model->Listar_Empresa($prm_cod_usuadm);		
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$prm['Listar_TipodeDocumento']=$this->Catalogos_model->Listar_TipodeDocumento();
			$prm['Listar_EstadoDocumento']=$this->Catalogos_model->Listar_EstadoDocumento();
			//$prm['Listar_EstadoDocumentoSunat']=$this->Catalogos_model->Listar_EstadoDocumentoSunat();
			$prm['Listar_Monedas']=$this->Catalogos_model->Listar_Monedas();
			
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
			
			$prm['pagina_ver']='comprobante/listacomprobantesreceptor';
			$this->load->view('comprobante/listacomprobantesreceptor',$prm);
		}		
	}
	
	public function Modificar_Comprobante()
	{
		$prm_datosseleccionados=trim($this->input->post('txt_datosseleccionados'));
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
		$prm['valor_igv']=$this->Usuarioinicio_model->Get_Valor_IGV();	
		
		$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);
		
		$Listar_Empresa=$this->Empresa_model->Listar_Empresa($this->Usuarioinicio_model->Get_Cod_UsuAdm());
		$prm['Listar_Empresa']=$Listar_Empresa;			
		$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);	
		$prm['Listar_TipodeDocumento']=$this->Catalogos_model->Listar_TipodeDocumento();				
		
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
			$prm['pagina_ver']='comprobante';
			
			
			
			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
			{
				$prm_tipo_confunidad=0;
			}
			else
			{
				$prm_tipo_confunidad=$this->Usuarioinicio_model->Get_Tipo_ConfUnidad();
			}
			$prm['Listar_Unidades']=$this->Catalogos_model->Datos_Unidades($prm_cod_empr,$prm_tipo_confunidad);

			
			$this->load->view('comprobante/registrocomprobante',$prm);
			
		}
		
		public function Guardar_Registroproductos()
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
			
			$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
			$prm_cod_prod=trim($this->input->post('txt_codigoprod'));
			$prm_cant_prod=trim($this->input->post('txt_cantidad'));
			$prm_uni_med=trim($this->input->post('cmb_unidadmedida'));
			$prm_desc_prod=strtoupper(trim($this->input->post('txt_descripcion')));
			$prm_val_unitario=trim($this->input->post('txt_valorunitario'));
			$prm_val_descuento=trim($this->input->post('txt_descuento'));
			$prm_val_isc=trim($this->input->post('txt_isc'));
			$prm_tip_afectacion=trim($this->input->post('cmb_tipoafectacion'));
			$prm_val_igv=trim($this->input->post('txt_igv'));
			$prm_val_total=trim($this->input->post('txt_valortotal'));	
			$prm_val_txt_preciocobro=trim($this->input->post('txt_preciocobro'));
			$prm_val_descuento_inc_igv=trim($this->input->post('txt_descuentoIGV'));
		//print_r($prm_val_descuento);
			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
			{
				$prm_cod_empr=0;
			}
			else
			{
				$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();		
			}
			$prm_ruc_empr=trim($this->input->post('txt_RucEmpresa'));
			$prm_cod_tipregist=trim($this->input->post('cod_tipregist'));	
			
		if ($prm_cod_tipregist==3)//EXPORTACION GRATUITAS
		{
			$prm_val_igv=0;
		}
		if ($prm_val_descuento=='')
		{
			$prm_val_descuento=0;
		}
		if ($prm_val_isc=='')
		{
			$prm_val_isc=0;
		}
		if ($prm_val_txt_preciocobro=='')
		{
			$prm_val_txt_preciocobro=0;
		}
		if ($prm_val_descuento_inc_igv=='')
		{
			$prm_val_descuento_inc_igv=0;
		}
		
		$consulta =$this->Comprobante_model->Guardar_Registroproductos($prm_cod_usu,$prm_cod_prod,$prm_cant_prod,$prm_uni_med,
			$prm_desc_prod,$prm_val_unitario,$prm_val_descuento,$prm_val_isc,$prm_tip_afectacion,$prm_val_igv,
			$prm_val_total,$prm_cod_empr,$prm_ruc_empr,$prm_cod_tipregist, $prm_val_txt_preciocobro, $prm_val_descuento_inc_igv);
		
		if ($consulta['result']==1)
		{
			$result['status']=1;
			//$result['codigo_baja']=$prm_resumenid;	
		}
		else
		{
			$result['status']=0;
			//$result['codigo_baja']='';
		}		
		echo json_encode($result);
	}
	
	
	
	public function Listar_ProductosDocumento()
	{

		$arr=NULL;
		$variable=NULL;
		$Contador=0;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$Config_ValorPrecio=$_SESSION['SES_MarcoTrabajo'][0]['conf_venta'];
		
		$consulta =$this->Comprobante_model->Listar_ProductosDocumento($prm_cod_usu,$prm_cod_empr);
		
		$operaciongravadas=0;	
		$operacioninafectos=0; 	
		$operacionexportacion=0;	
		$operacionexoneradas=0; 	
		$operaciongratuitas=0; 	
		$descuentototal=0;	
		$isctotal=0; 	
		$igvtotal=0;
		$preciocobroTotal_tmp=0;
		$preciocobroTotal=0;
		$otroscargos=0;
		$importetotal=0; 	

		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;
			$arr[$key]['nro_secuencia'] = $Contador;
			$arr[$key]['tmp_prod'] = trim($v['tmp_prod']); 
			$arr[$key]['cod_usu'] =trim($v['cod_usu']); 
			$arr[$key]['cod_prod'] =  trim($v['cod_prod']);
			$arr[$key]['cant_prod'] =  trim($v['cant_prod']); 
			$arr[$key]['uni_med'] =  trim($v['uni_med']);
			$arr[$key]['desc_prod'] =  trim($v['desc_prod']);
				$arr[$key]['val_unitario'] =  trim($v['val_unitario']);//number_format(trim($v['val_unitario']),2,'.',','); 		
				$arr[$key]['val_descuento'] =  trim($v['val_descuento']);
				$arr[$key]['val_isc'] =  trim($v['val_isc']); 
				$arr[$key]['tip_afectacion'] =  trim($v['tip_afectacion']);
				$arr[$key]['val_igv'] = trim($v['val_igv']); //$getTruncatedValue($v['val_igv'],2);//number_format(trim($v['val_igv']),3,'.',','); 
				$arr[$key]['val_total'] =  trim($v['val_total']);
				$arr[$key]['cod_empr'] =  trim($v['cod_empr']);
				$arr[$key]['ruc_empr'] =  trim($v['ruc_empr']);
				
				
				if($v['cod_tipregist']==1) //NORMAL
				{
					if ($v['tip_afectacion']==10)
					{
						$operaciongravadas=$operaciongravadas+ $v['val_total'];
					}
					else if ($v['tip_afectacion']==20)
					{
						$operacionexoneradas=$operacionexoneradas+ $v['val_total'];
					}
					else if ($v['tip_afectacion']==30)
					{
						$operacioninafectos=$operacioninafectos+ $v['val_total'];
					}
				}
				else if($v['cod_tipregist']==2)//EXPORTACION
				{
					if ($v['tip_afectacion']==40)
					{
						$operacionexportacion=$operacionexportacion+ $v['val_total'];
					}
				}
				else if($v['cod_tipregist']==3)//GRATUITAS
				{
					$operaciongratuitas=$operaciongratuitas+ $v['val_total'];
				}
				//+number_format(trim($v['val_igv']),3,'.',',');
				//$importetotal=$importetotal+$v['val_total'];//$getTruncatedValue($v['val_igv'],3)
				$descuentototal=$descuentototal + $v['val_descuento'];
				$igvtotal=$igvtotal + $v['val_igv'];
				$preciocobroTotal_tmp=($v['val_preciocobro'] * $v['cant_prod'])-$v['val_descuento_inc_igv'];
				$preciocobroTotal=$preciocobroTotal + $preciocobroTotal_tmp;

				endforeach;
			}
			
		//$variable['operaciongravadas']=number_format($operaciongravadas,3,'.',',');
		 //$getTruncatedValue(1.123,1), 
		$variable['operaciongravadas']=number_format($operaciongravadas,2,'.',',');//$getTruncatedValue($operaciongravadas,3);
		$variable['operacioninafectos']=number_format($operacioninafectos,2,'.',',');
		$variable['operacionexportacion']=number_format($operacionexportacion,2,'.',',');
		$variable['operacionexoneradas']=number_format($operacionexoneradas,2,'.',',');
		$variable['operaciongratuitas']=number_format($operaciongratuitas,2,'.',','); 
		$variable['descuentototal']=number_format($descuentototal,2,'.',','); 
		$variable['isctotal']=number_format( $isctotal,2,'.',',');
		$variable['igvtotal']=number_format( $igvtotal,2,'.',',');
		
		//Validar el tipo de configuracion valor venta o precio
		//print_r('VER OJO: '.$preciocobroTotal);
		//print_r('VER OJO: '.$operaciongravadas);
		//print_r('VER OJO: '.$igvtotal);
		if ($operaciongravadas==0)
		{
			$otroscargos=0;
		}else
		{
			//$valor_otroscargos=$this->Usuarioinicio_model->Get_Valor_OtrosCargos();
			//No se está volviendo a calcular porque hay casos en se pierden centecimas y/o se aumentan centecimas
			//$otroscargos=$operaciongravadas*($valor_otroscargos/100);
			$otroscargos=$preciocobroTotal-($operaciongravadas+$igvtotal);
		}
		
		$variable['otroscargos']=number_format($otroscargos,2,'.',',');
		//print_r('OJO CARGOS: '.$otroscargos);
		$importetotal=$importetotal+$operaciongravadas+$operacionexoneradas+$operacionexportacion+$operacioninafectos+$igvtotal+$otroscargos;
		//$importetotal=$importetotal+(number_format($operaciongravadas,2,'.',',')+number_format($operacionexoneradas,2,'.',',')+
		//	number_format($operacionexportacion,2,'.',',')+number_format($operacioninafectos,2,'.',',')+number_format( $igvtotal,2,'.',','));
		$variable['importetotal']=number_format($importetotal,2,'.',',');
		
		
		if(sizeof($arr)>0)
		{
			$result['status']=1;
			$result['data']=$arr;
			$result['variable']=$variable;
		}
		else
		{
			$result['status']=0;
			$result['data']="";
			$result['variable']="";
		}
		echo json_encode($result);
	}
	
	
	public function Eliminar_Productotemporal()
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
		$prm_tmp_prod=trim($this->input->post('tmp_prod'));
		$consulta =$this->Comprobante_model->Eliminar_Productotemporal($prm_tmp_prod);
		if ($consulta['result']==1)
		{
			$result['status']=1;	
		}
		else
		{
			$result['status']=0;
		}		
		echo json_encode($result);
	}
	

	public function Datos_Emisor()
	{
		$arr=NULL;

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
			
			$ubigeo =$this->Comprobante_model->Datos_Ubigeo($cod_depa,$cod_prov,$cod_dist);
			
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
		
		public function Guardar_Einvoiceheader()
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
			$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
			{
				$prm_cod_empr=0;
			}
			else
			{
				$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();		
			}		
			
			$prm_documentomodificar=trim($this->input->post('txt_documentomodificar'));				
		$prm_correoemisor=trim($this->input->post('txt_emisorcorreo'));//='yessica.prad@bizlinks.la';
		$prm_correoadquiriente=trim($this->input->post('txt_correocliente'));
		$prm_numerodocumentoemisor=trim($this->input->post('txt_RucEmpresa'));
		$prm_tipodocumentoemisor='6';
		$prm_tipodocumento=trim($this->input->post('cmb_tipodocumentosunat'));
		$prm_razonsocialemisor=trim($this->input->post('txt_RazonSocialEmpresa'));
		$prm_nombrecomercialemisor=trim($this->input->post('txt_RazonSocialEmpresa'));
		$prm_seriedocumento=trim($this->input->post('cmb_seriedocumentosunat'));
		$prm_numerodocumento=trim($this->input->post('txt_numerodocumentosunat'));		

		$prm_fechaemisiontmp=trim($this->input->post('txt_fecemisiondoc'));
		$prm_fechaemisiontmp=explode('/',$prm_fechaemisiontmp);
		$prm_fechaemision=($prm_fechaemisiontmp[2].'-'.$prm_fechaemisiontmp[1].'-'.$prm_fechaemisiontmp[0]);

		$prm_ubigeoemisor=trim($this->input->post('txt_emisorubigeo'));
		$prm_direccionemisor=strtoupper(trim($this->input->post('txt_emisordireccion')));
		$prm_urbanizacion=trim($this->input->post('txt_emisorrubanizacion'));
		$prm_provinciaemisor=trim($this->input->post('txt_emisorprovincia'));
		$prm_departamentoemisor=trim($this->input->post('txt_emisordepartamento'));
		$prm_distritoemisor=trim($this->input->post('txt_emisordistrito'));
		$prm_paisemisor=trim($this->input->post('txt_emisorpaiscodigo'));

		$prm_ubigeoadquiriente='';//trim($this->input->post('txt_clienteubigeo'));
		$prm_direccionadquiriente=trim($this->input->post('txt_clientedireccion'));
		$prm_urbanizacionadquiriente=trim($this->input->post('txt_clienteubanizacion'));
		$prm_provinciaadquiriente=trim($this->input->post('txt_clienteprovincia'));
		$prm_departamentoadquiriente=trim($this->input->post('txt_clientedepartamento'));
		$prm_distritoadquiriente=trim($this->input->post('txt_clientedistrito'));
		$prm_paisadquiriente=trim($this->input->post('txt_clientepaiscodigo'));

		if($prm_paisadquiriente=='000000')
		{
			$prm_ubigeoadquiriente='';
		}
		
		$datosadquiriente='';
		if ($prm_tipodocumento=='03') // ES BOLETA
		{
			if ($prm_direccionadquiriente!='' and $prm_direccionadquiriente!='-')
			{
				if ($datosadquiriente=='')
				{
					$datosadquiriente=$prm_direccionadquiriente;
				}
				else
				{
					$datosadquiriente=$datosadquiriente.'-'.$prm_direccionadquiriente;
				}
			}
			if ($prm_urbanizacionadquiriente!='' and $prm_urbanizacionadquiriente!='-')
			{
				
				if ($datosadquiriente=='')
				{
					$datosadquiriente=$prm_urbanizacionadquiriente;
				}
				else
				{
					$datosadquiriente=$datosadquiriente.'-'.$prm_urbanizacionadquiriente;
				}
			}
			if ($prm_distritoadquiriente!='')
			{
				if ($datosadquiriente=='')
				{
					$datosadquiriente=$prm_distritoadquiriente;
				}
				else
				{
					$datosadquiriente=$datosadquiriente.'-'.$prm_distritoadquiriente;
				}
			}
			if ($prm_provinciaadquiriente!='')
			{
				if ($datosadquiriente=='')
				{
					$datosadquiriente=$prm_provinciaadquiriente;
				}
				else
				{
					$datosadquiriente=$datosadquiriente.'-'.$prm_provinciaadquiriente;
				}
			}
			if ($prm_departamentoadquiriente!='')
			{
				if ($datosadquiriente=='')
				{
					$datosadquiriente=$prm_departamentoadquiriente;
				}
				else
				{
					$datosadquiriente=$datosadquiriente.'-'.$prm_departamentoadquiriente;
				}
			}
		}
		else
		{	
			if ($prm_tipodocumento=='07' or $prm_tipodocumento=='08') //NC O ND
			{
				if (trim($this->input->post('cmb_tipodocumentoreferencia'))=='03')//ES BOLETA
				{
					if ($prm_direccionadquiriente!='' and $prm_direccionadquiriente!='-')
					{
						if ($datosadquiriente=='')
						{
							$datosadquiriente=$prm_direccionadquiriente;
						}
						else
						{
							$datosadquiriente=$datosadquiriente.'-'.$prm_direccionadquiriente;
						}
					}
					if ($prm_urbanizacionadquiriente!='' and $prm_urbanizacionadquiriente!='-')
					{
						
						if ($datosadquiriente=='')
						{
							$datosadquiriente=$prm_urbanizacionadquiriente;
						}
						else
						{
							$datosadquiriente=$datosadquiriente.'-'.$prm_urbanizacionadquiriente;
						}
					}
					if ($prm_distritoadquiriente!='')
					{
						if ($datosadquiriente=='')
						{
							$datosadquiriente=$prm_distritoadquiriente;
						}
						else
						{
							$datosadquiriente=$datosadquiriente.'-'.$prm_distritoadquiriente;
						}
					}
					if ($prm_provinciaadquiriente!='')
					{
						if ($datosadquiriente=='')
						{
							$datosadquiriente=$prm_provinciaadquiriente;
						}
						else
						{
							$datosadquiriente=$datosadquiriente.'-'.$prm_provinciaadquiriente;
						}
					}
					if ($prm_departamentoadquiriente!='')
					{
						if ($datosadquiriente=='')
						{
							$datosadquiriente=$prm_departamentoadquiriente;
						}
						else
						{
							$datosadquiriente=$datosadquiriente.'-'.$prm_departamentoadquiriente;
						}
					}
				}
				else
				{
					$datosadquiriente=array(
						'ubigeoAdquiriente'=>$prm_ubigeoadquiriente,
						'direccionAdquiriente'=>$prm_direccionadquiriente,
						'urbanizacionAdquiriente'=>$prm_urbanizacionadquiriente,
						'provinciaAdquiriente'=>$prm_provinciaadquiriente,
						'departamentoAdquiriente'=>$prm_departamentoadquiriente,
						'distritoAdquiriente'=>$prm_distritoadquiriente,
						'paisAdquiriente'=>$prm_paisadquiriente
						);
				}
			}
			else
			{
				$datosadquiriente=array(
					'ubigeoAdquiriente'=>$prm_ubigeoadquiriente,
					'direccionAdquiriente'=>$prm_direccionadquiriente,
					'urbanizacionAdquiriente'=>$prm_urbanizacionadquiriente,
					'provinciaAdquiriente'=>$prm_provinciaadquiriente,
					'departamentoAdquiriente'=>$prm_departamentoadquiriente,
					'distritoAdquiriente'=>$prm_distritoadquiriente,
					'paisAdquiriente'=>$prm_paisadquiriente
					);
			}
		}

		$prm_numerodocumentoadquiriente=trim($this->input->post('txt_numerodoccliente'));
		$prm_tipodocumentoadquiriente=trim($this->input->post('cmb_tipodocumentocliente'));
		$prm_razonsocialadquiriente=trim($this->input->post('txt_razonsocialcliente'));
		$prm_tipomoneda=trim($this->input->post('cmb_monedadocumento'));	
		
		$prm_totalvalorventanetoopgravadas=trim($this->input->post('txt_operaciongravadas'));
		$prm_totalvalorventanetoopgravadas=str_replace(",","",$prm_totalvalorventanetoopgravadas);		
		$prm_totalvalorventanetoopgravadas=number_format($prm_totalvalorventanetoopgravadas, 2, '.', '');

		$prm_totalvalorventanetoopnogravada=trim($this->input->post('txt_operacioninafectos'));
		$prm_totalvalorventanetoopnogravada=str_replace(",","",$prm_totalvalorventanetoopnogravada);
		$prm_totalvalorventanetoopnogravada=number_format(trim($prm_totalvalorventanetoopnogravada), 2, '.', '');
		
		$prm_totalvalorventanetoopexonerada=trim($this->input->post('txt_operacionexoneradas'));
		$prm_totalvalorventanetoopexonerada=str_replace(",","",$prm_totalvalorventanetoopexonerada);
		$prm_totalvalorventanetoopexonerada=number_format(trim($prm_totalvalorventanetoopexonerada), 2, '.', '');
		
		$prm_opexportacion=trim($this->input->post('txt_operacionexportacion'));
		$prm_opexportacion=str_replace(",","",$prm_opexportacion);
		$prm_opexportacion=number_format(trim($prm_opexportacion), 2, '.', '');

		$prm_opgratuitas=trim($this->input->post('txt_operaciongratuitas'));
		$prm_opgratuitas=str_replace(",","",$prm_opgratuitas);	
		if(number_format($prm_opgratuitas, 2, '.', '')>0)
		{
			$prm_totalvalorventanetoopgratuitas=$prm_opgratuitas;
			$prm_totalvalorventanetoopgratuitas=number_format(trim($prm_totalvalorventanetoopgratuitas), 2, '.', '');
		}
		else
		{
			$prm_totalvalorventanetoopgratuitas='';//NULL;
		}
		$prm_totalvalorventanetoopnogravada=$prm_totalvalorventanetoopnogravada+$prm_opexportacion;
		$prm_totalvalorventanetoopnogravada=number_format(trim($prm_totalvalorventanetoopnogravada), 2, '.', '');
		
		$prm_totaligv=trim($this->input->post('txt_igvtotal'));
		$prm_totaligv=str_replace(",","",$prm_totaligv);	
		$prm_totaligv=number_format(trim($prm_totaligv), 2, '.', '');
		
		$prm_totaldescuentos=trim($this->input->post('txt_descuentototal'));
		$prm_totaldescuentos=str_replace(",","",$prm_totaldescuentos);
		$prm_totaldescuentos=number_format(trim($prm_totaldescuentos), 2, '.', '');
		
		//Req v2: Registrar el importe de otros cargos
		$prm_totalotroscargos=trim($this->input->post('txt_otroscargos'));
		$prm_totalotroscargos=str_replace(",","",$prm_totalotroscargos);
		$prm_totalotroscargos=number_format(trim($prm_totalotroscargos), 2, '.', '');

		$prm_porcentajeotroscargos=trim($this->input->post('txt_porcentajeotroscargos'));
		
		$prm_totalventa=trim($this->input->post('txt_importetotal'));
		$prm_totalventa=str_replace(",","",$prm_totalventa);
		$prm_totalventa=number_format(trim($prm_totalventa), 2, '.', '');
		
		$prm_textoleyenda_1=strtoupper($this->funciones->ConvertirNumeroLetras(str_replace(",","",$prm_totalventa)));	
		
		$prm_monedadocumentonombre=trim($this->input->post('cmb_monedadocumentonombre'));
		$prm_textoleyenda_1=$prm_textoleyenda_1.' '.$prm_monedadocumentonombre;	

		$prm_codigoleyenda_1='1000';

		$prm_totaldetraccion=trim($this->input->post('txt_montodetraccion'));
		if ($prm_totaldetraccion=='')
		{
			$prm_totaldetraccion=0;
		}		
		if (number_format($prm_totaldetraccion, 3, '.', '')<=0)
		{
			$prm_totaldetraccion='';//NULL;
			$prm_valorreferencialdetraccion='';
			$prm_porcentajedetraccion='';
			$prm_descripciondetraccion='';
			$prm_textoleyenda_2='';
			$prm_codigoleyenda_2='';			
		}
		else
		{
			$prm_totaldetraccion=number_format(trim($prm_totaldetraccion), 2, '.', '');
			$prm_valorreferencialdetraccion=trim($this->input->post('txt_montodetraccionreferencial'));
			$prm_valorreferencialdetraccion=number_format(trim($prm_valorreferencialdetraccion), 2, '.', '');
			$prm_porcentajedetraccion=trim($this->input->post('txt_porcentajedetraccion'));
			$prm_porcentajedetraccion=number_format(trim($prm_porcentajedetraccion), 2, '.', '');
			$prm_descripciondetraccion=trim($this->input->post('txt_descripciondetraccion'));
			$prm_textoleyenda_2=trim($this->input->post('txt_leyendadetraccion'));
			$prm_codigoleyenda_2='3001';
		}
		
		$prm_porcentajepercepcion=trim($this->input->post('txt_porcentajepercepcion'));
		if ($prm_porcentajepercepcion=='')
		{
			$prm_porcentajepercepcion=0;
		}		
		if (number_format($prm_porcentajepercepcion, 2, '.', '')<=0)
		{
			$prm_porcentajepercepcion='';//NULL;
			$prm_baseimponiblepercepcion='';
			$prm_totalpercepcion='';
			$prm_totalventaconpercepcion='';			
		}
		else
		{
			$prm_porcentajepercepcion=number_format(trim($prm_porcentajepercepcion),2, '.', '');
			$prm_baseimponiblepercepcion=trim($this->input->post('txt_baseimponiblepercepcion'));
			$prm_baseimponiblepercepcion=number_format(trim($prm_baseimponiblepercepcion), 2, '.', '');
			$prm_totalpercepcion=trim($this->input->post('txt_totalpercepcion'));
			$prm_totalpercepcion=number_format(trim($prm_totalpercepcion), 2, '.', '');
			$prm_totalventaconpercepcion=trim($this->input->post('txt_totalventapercepcion'));
			$prm_totalventaconpercepcion=number_format(trim($prm_totalventaconpercepcion), 2, '.', '');
		}

		$prm_descuentosglobales=trim($this->input->post('txt_descuentoglobal'));		
		if ($prm_descuentosglobales=='')
		{	
			$prm_descuentosglobales='';
		}		
		else
		{
			$prm_descuentosglobales=number_format(trim($prm_descuentosglobales), 2, '.', '');
		}
		
		$prm_cod_tipregistrogeneral=trim($this->input->post('cod_tipregistrogeneral'));
		//$prm_cod_tipregistrogeneral 1: normal, 2: exportacion,3: gratuitas
		if ($prm_cod_tipregistrogeneral==3)
		{
			$prm_textoleyenda_3='TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE';
			$prm_codigoleyenda_3='1002';
		}
		else
		{
			$prm_textoleyenda_3='';//NULL;
			$prm_codigoleyenda_3='';
		}


		//NOTAS DE CREDITO Y DEBITO
		if ($prm_tipodocumento=='07' or $prm_tipodocumento=='08')
		{
			$prm_codigoserienumeroafectado=trim($this->input->post('cmb_tiponotadecredito'));
			$prm_numerodocumentoreferenciaprinc=trim($this->input->post('txt_numerodocumentoreferencia'));
			if ($prm_numerodocumentoreferenciaprinc!='')//SI NO ES VACIO
			{
				$prm_serienumeroafectado=$prm_numerodocumentoreferenciaprinc;			
			}
			else //SI ES VACIO
			{
				$prm_numerodocumentoreferenciaprinc='-';
				$prm_serienumeroafectado='-';
			}			
			$prm_motivodocumento=trim($this->input->post('txt_motivodenotacredito'));
			$prm_tipodocumentoreferenciaprincip=trim($this->input->post('cmb_tipodocumentoreferencia'));
			$prm_tipodocumentoreferenciacorregi='';//PREGUNTAR
			$prm_numerodocumentoreferenciacorre='';//PREGUNTAR
			
			
		}
		else
		{
			$prm_codigoserienumeroafectado='';
			$prm_serienumeroafectado='';
			$prm_motivodocumento='';
			$prm_tipodocumentoreferenciaprincip='';
			$prm_numerodocumentoreferenciaprinc='';
			$prm_tipodocumentoreferenciacorregi='';
			$prm_numerodocumentoreferenciacorre='';	
		}

		
		$prm_tipo_registro=trim($this->input->post('tipo_registro'));
		//Req. V2. Campos Adicionales
		$prm_adicionalCantidad=trim($this->input->post('arr_adicional_Cantidad'));
		$prm_adicionalCodigo=($this->input->post('arr_adicional_Codigo'));
		$prm_adicionalValor=($this->input->post('arr_adicional_Valor'));
		//NO se está considerando el campo 500_5 porque se está reservando
		//para guardar la cantidad de datos adicionales insertados.
		//La cantidad se registra para que sea utilizada al momento de editar un comprobante.
		$prm_adicionalCampos = array("100_3","100_4","100_5","100_6","100_7","100_8","100_9","100_10","40_3","40_4","40_5","40_6",
			"40_7","40_8","40_9","40_10","40_11","40_12","40_13","40_14","40_15","40_16","40_17","40_18",
			"40_19","40_20","500_1","500_2","500_3","500_4","250_1","250_2","250_3","250_4","250_5",
			"250_6","250_7","250_8","250_9","250_10","250_11","250_12","250_13","250_14","250_15","250_16",
			"250_17","250_18","250_19","250_20","250_21","250_22","250_23","250_24","250_25");
		
		//print_r($temp);
		//return;
		$consulta =$this->Comprobante_model->Guardar_Einvoiceheader(
			$prm_cod_empr,
			$prm_cod_usu,
			$prm_correoemisor,
			$prm_correoadquiriente,
			$prm_numerodocumentoemisor,
			$prm_tipodocumentoemisor,
			$prm_tipodocumento,
			$prm_razonsocialemisor,
			$prm_nombrecomercialemisor,
			$prm_seriedocumento,
			$prm_numerodocumento,
			$prm_fechaemision,
			$prm_ubigeoemisor,
			$prm_direccionemisor,
			$prm_urbanizacion,
			$prm_provinciaemisor,
			$prm_departamentoemisor,
			$prm_distritoemisor,
			$prm_paisemisor,
			$prm_numerodocumentoadquiriente,
			$prm_tipodocumentoadquiriente,
			$prm_razonsocialadquiriente,
			$prm_tipomoneda,
			$prm_totalvalorventanetoopgravadas,
			$prm_totalvalorventanetoopnogravada,
			$prm_totalvalorventanetoopexonerada,
			$prm_totaligv,
			$prm_totaldescuentos,
			$prm_totalventa,
			$prm_textoleyenda_1,
			$prm_codigoleyenda_1,
			$prm_totaldetraccion,
			$prm_valorreferencialdetraccion,
			$prm_porcentajedetraccion,
			$prm_descripciondetraccion,
			$prm_textoleyenda_2,
			$prm_codigoleyenda_2,
			$prm_descuentosglobales,
			$prm_textoleyenda_3,
			$prm_codigoleyenda_3,
			
			$prm_porcentajepercepcion,
			$prm_baseimponiblepercepcion,
			$prm_totalpercepcion,
			$prm_totalventaconpercepcion,
			$datosadquiriente,
			$prm_totalvalorventanetoopgratuitas,
			
			$prm_codigoserienumeroafectado,
			$prm_serienumeroafectado,
			$prm_motivodocumento,
			$prm_tipodocumentoreferenciaprincip,
			$prm_numerodocumentoreferenciaprinc,
			$prm_tipodocumentoreferenciacorregi,
			$prm_numerodocumentoreferenciacorre,											
			$prm_tipo_registro,
			$prm_documentomodificar,
			$prm_totalotroscargos,
			$prm_porcentajeotroscargos,
			$prm_adicionalCantidad,
			$prm_adicionalCodigo,
			$prm_adicionalValor,
			$prm_adicionalCampos
			);
		
		//print_r($consulta);
		if ($consulta['result']==1)
		{
			$result['status']=1;
			$result['numero']=$prm_seriedocumento.'-'.$consulta['numero'];
			//$result['codigo_baja']=$prm_resumenid;	
		}
		else if ($consulta['result']==2)//EXISTE EL DOCUMENTO
		{
			$result['status']=2;
			$result['numero']=$prm_seriedocumento.'-'.$consulta['numero'];
			//$result['codigo_baja']=$prm_resumenid;	
		}
		else if ($consulta['result']==3)//EXISTE EL DOCUMENTO
		{
			$result['status']=3;
			$result['numero']='0';
			//$result['codigo_baja']=$prm_resumenid;	
		}
		else
		{
			$result['status']=0;
			$result['numero']=$consulta['numero'];
		}		
		echo json_encode($result);
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
		$prm_tipomoneda=trim($this->input->post('Cmb_TipoMoneda'));

		$prm_razonsocialcliente=trim($this->input->post('txt_RazonSocialCliente'));

		if (substr($prm_documento_cliente,0,1)=='E')
		{
			$prm_documento_cliente='-';
		}

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
			$prm_fec_emisfinal=$prm_fec_emisfinaltmp[2].'-'.$prm_fec_emisfinaltmp[1].'-'.$prm_fec_emisfinaltmp[0];
		}
		$prm_tipo_documentosunat=trim($this->input->post('Cmb_TipoDocumentoSunat'));
		$prm_estado_documentosunat=trim($this->input->post('Cmb_EstadoDocumentoSunat'));
		
		$consulta =$this->Comprobante_model->Listar_Comprobantes($prm_ruc_empr,$prm_documento_cliente,$prm_serie_numeroinicio,
			$prm_serie_numerofinal,$prm_cod_estdoc,$prm_fec_emisinicio,$prm_fec_emisfinal,
			$prm_tipo_documentosunat,$prm_estado_documentosunat,$prm_tipomoneda, $prm_razonsocialcliente);

		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):			
				if ($prm_estado_documentosunat=='0')
				{
					if ($prm_documento_cliente=='-')
					{
						$posicion = strpos($prm_razonsocialcliente,$v['razonsocialadquiriente']);			 
						if($posicion !== FALSE)	
						{
							$Contador=$Contador+1;
							$arr[$key]['nro_secuencia'] = $Contador;
							$arr[$key]['razonsocialadquiriente'] =strtoupper(trim($v['razonsocialadquiriente'])); 
							$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
							$arr[$key]['nomb_tipodocumento'] =trim($v['nomb_tipodocumento']);				
							$arr[$key]['serienumero'] =trim($v['serienumero']);
							$arr[$key]['tipomoneda'] =  trim(strtoupper($v['tipomoneda']));
							$arr[$key]['totalventa'] =  trim($v['totalventa']);
							$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
							$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
							$arr[$key]['estado_documento'] =  trim(strtoupper($v['estado_documento']));					
							$arr[$key]['estadosunat'] =  trim($v['estadosunat']);
							$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
							$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);						
							$arr[$key]['visualizado'] =  trim($v['visualizado']);
							
							
							if ( $v['mensajeresponse']=='1')
							{
								$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
							}
							else
							{
								if ($v['bl_estadoregistro']=='E') //ERROR LOCAL
								{
									//TRAER LOS DATOS DEL ERROR
									$Listar_ErrorDocumento=$this->Comprobante_model->Listar_ErrorDocumento($prm_ruc_empr,$v['tipodocumentoemisor'],$v['tipodocumento'],$v['serienumero']);
									if(!empty($Listar_ErrorDocumento))//SI NO ES NULO O VACIO
									{
										$arr[$key]['obssunat'] =  $Listar_ErrorDocumento[0]['descripcionerror'];
									}
									else
									{
										$arr[$key]['obssunat'] =  '';
									}						
								}
								else
								{
									if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
									{
										if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
										{
											$arr[$key]['obssunat']='';
										}
										else
										{
											$arr[$key]['obssunat']=$v['bl_mensajesunat'];
										}
									}
									else
									{
										$arr[$key]['obssunat']=$v['bl_mensaje'];
									}
								}
							}
							
							$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);					
							if ($v['bl_estadoregistro']=='L')
							{
								$arr[$key]['cant_reintento'] =  trim($v['reintento']);
							}
							else
							{
								$arr[$key]['cant_reintento'] =  trim($v['bl_reintento']);
							}	
						}
					}
					else
					{
						$Contador=$Contador+1;
						$arr[$key]['nro_secuencia'] = $Contador;
						$arr[$key]['razonsocialadquiriente'] =strtoupper(trim($v['razonsocialadquiriente'])); 
						$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
						$arr[$key]['nomb_tipodocumento'] =trim($v['nomb_tipodocumento']);				
						$arr[$key]['serienumero'] =trim($v['serienumero']);
						$arr[$key]['tipomoneda'] =  trim(strtoupper($v['tipomoneda']));
						$arr[$key]['totalventa'] =  trim($v['totalventa']);
						$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
						$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
						$arr[$key]['estado_documento'] =  trim(strtoupper($v['estado_documento']));					
						$arr[$key]['estadosunat'] =  trim($v['estadosunat']);
						$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
						$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);						
						$arr[$key]['visualizado'] =  trim($v['visualizado']);	
						
						if ( $v['mensajeresponse']=='1')
						{
							$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
						}
						else
						{
							if ($v['bl_estadoregistro']=='E') //ERROR LOCAL
							{
								//TRAER LOS DATOS DEL ERROR
								$Listar_ErrorDocumento=$this->Comprobante_model->Listar_ErrorDocumento($prm_ruc_empr,$v['tipodocumentoemisor'],$v['tipodocumento'],$v['serienumero']);
								if(!empty($Listar_ErrorDocumento))//SI NO ES NULO O VACIO
								{
									$arr[$key]['obssunat'] =  $Listar_ErrorDocumento[0]['descripcionerror'];
								}
								else
								{
									$arr[$key]['obssunat'] =  '';
								}						
							}
							else
							{
								if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
								{
									if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
									{
										$arr[$key]['obssunat']='';
									}
									else
									{
										$arr[$key]['obssunat']=$v['bl_mensajesunat'];
									}
								}
								else
								{
									$arr[$key]['obssunat']=$v['bl_mensaje'];
								}
							}
						}
						
						$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);					
						if ($v['bl_estadoregistro']=='L')
						{
							$arr[$key]['cant_reintento'] =  trim($v['reintento']);
						}
						else
						{
							$arr[$key]['cant_reintento'] =  trim($v['bl_reintento']);
						}	
					}				
				}
				else //SIGNIFICA QUE SI SELECCIONO UN TIPO DE ESTADO SUNAT
				{
					
					if ($prm_documento_cliente=='-')
					{
						$posicion = strpos($prm_razonsocialcliente,$v['razonsocialadquiriente']);			 
						if($posicion !== FALSE)	
						{
							$estadodocsunat=$v['estadosunat'];
							$posicion = strpos($estadodocsunat,'/');			 
							if($posicion !== FALSE)	
							{
								$estadodocsunat=substr($estadodocsunat, -5); 
							}
							//echo($estadodocsunat).'-'.$posicion;echo '<br>';
							$posicion = strpos($estadodocsunat,$prm_estado_documentosunat);			 
							if($posicion !== FALSE)	
							{
								$Contador=$Contador+1;
								$arr[$key]['nro_secuencia'] = $Contador;
								$arr[$key]['razonsocialadquiriente'] =strtoupper(trim($v['razonsocialadquiriente'])); 
								$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
								$arr[$key]['nomb_tipodocumento'] =trim($v['nomb_tipodocumento']);				
								$arr[$key]['serienumero'] =trim($v['serienumero']);
								$arr[$key]['tipomoneda'] =  trim($v['tipomoneda']);
								$arr[$key]['totalventa'] =  trim($v['totalventa']);
								$arr[$key]['fechaemision'] = trim($v['fechaemision']);	
								$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
								$arr[$key]['estado_documento'] =  trim(strtoupper($v['estado_documento']));
								$arr[$key]['estadosunat'] =  trim($v['estadosunat']);		
								$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
								$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);		
								$arr[$key]['visualizado'] =  trim($v['visualizado']);
								
								if ( $v['mensajeresponse']=='1')
								{
									$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
								}
								else
								{
									if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
									{
										if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
										{
											$arr[$key]['obssunat']='';
										}
										else
										{
											$arr[$key]['obssunat']=$v['bl_mensajesunat'];
										}
									}
									else
									{
										$arr[$key]['obssunat']=$v['bl_mensaje'];
									}
								}
								
								$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);
								if ($v['bl_estadoregistro']=='L')
								{
									$arr[$key]['cant_reintento'] =  trim($v['reintento']);
								}
								else
								{
									$arr[$key]['cant_reintento'] =  trim($v['bl_reintento']);
								}
							}
						}
					}
					else
					{
						$estadodocsunat=$v['estadosunat'];
						$posicion = strpos($estadodocsunat,'/');			 
						if($posicion !== FALSE)	
						{
							$estadodocsunat=substr($estadodocsunat, -5); 
						}
						//echo($estadodocsunat).'-'.$posicion;echo '<br>';
						$posicion = strpos($estadodocsunat,$prm_estado_documentosunat);			 
						if($posicion !== FALSE)	
						{
							$Contador=$Contador+1;
							$arr[$key]['nro_secuencia'] = $Contador;
							$arr[$key]['razonsocialadquiriente'] =strtoupper(trim($v['razonsocialadquiriente'])); 
							$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
							$arr[$key]['nomb_tipodocumento'] =trim($v['nomb_tipodocumento']);				
							$arr[$key]['serienumero'] =trim($v['serienumero']);
							$arr[$key]['tipomoneda'] =  trim($v['tipomoneda']);
							$arr[$key]['totalventa'] =  trim($v['totalventa']);
							$arr[$key]['fechaemision'] = trim($v['fechaemision']);	
							$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
							$arr[$key]['estado_documento'] =  trim(strtoupper($v['estado_documento']));
							$arr[$key]['estadosunat'] =  trim($v['estadosunat']);		
							$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
							$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);		
							$arr[$key]['visualizado'] =  trim($v['visualizado']);
							
							if ( $v['mensajeresponse']=='1')
							{
								$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
							}
							else
							{
								if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
								{
									if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
									{
										$arr[$key]['obssunat']='';
									}
									else
									{
										$arr[$key]['obssunat']=$v['bl_mensajesunat'];
									}
								}
								else
								{
									$arr[$key]['obssunat']=$v['bl_mensaje'];
								}
							}
							
							$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);
							if ($v['bl_estadoregistro']=='L')
							{
								$arr[$key]['cant_reintento'] =  trim($v['reintento']);
							}
							else
							{
								$arr[$key]['cant_reintento'] =  trim($v['bl_reintento']);
							}
						}
					}
				}
				
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
		//print_r($result);
			echo json_encode($result);
		}	
		
		
		public function Comprobar_DocumentoImprimirAnonimo()
		{
			$result['status']=0;
		/*if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}*/
		$prm_cod_documento=trim($this->input->post('param1'));
		$prm_ruc_emisor=trim($this->input->post('param2'));
		
		$carpetaemisor='6-'.$prm_ruc_emisor;		
		$carpeta = ''; //keys/			
		$rutadescargar=$this->Catalogos_model->Listar_RutaDocumentoDescargar();
		if(!empty($rutadescargar))//SI NO ES NULO O VACIO
		{
			$carpeta=$rutadescargar[0]['valorcadena'];
		}		
		$nombrearchivopdf='';
		$tipofirma='';
		$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaIdRuc($prm_ruc_emisor);
		if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
		{
			$tipofirma=$Listar_EmpresaId[0]['tipo_conffirma'];
		}		
		$lista_documento=explode(',',$prm_cod_documento);
		if(!empty($lista_documento))
		{
			$listadearchivos=NULL;
			$contador=0;
			$cantidad=0;
			foreach($lista_documento as $key=>$v):
				if (strlen($v)>4)
				{
					if ($tipofirma==1)//BIZLIN
					{
						$nombrearchivopdf=$prm_ruc_emisor.'-'.$v;
					}
					else //LOCAL
					{
						$nombrearchivopdf='PDFLOCAL-'.$prm_ruc_emisor.'-'.$v;
					}			
					$src=$carpeta.$carpetaemisor.'/'.$v.'/'.$nombrearchivopdf.'.pdf';					
					$nombre_archivo='';		
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						//$nombre_archivo=$nombrearchivopdf.'.pdf';
						$cantidad++;
						
						if($_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion']==2)//SOLO SI ES RECEPTOR SE ACTUALIZA LOS DATOS
						{
							$codigo_documento=explode('-',$v);
							$this->Comprobante_model->Actualizar_VistaDocumento($prm_ruc_emisor,$codigo_documento[0],($codigo_documento[1].'-'.$codigo_documento[2]));
						}
						
						
					} 
				}			
				endforeach;
				
			}
			if ($cantidad==0)
			{
				$result['status']=2;
			}
			else if ($cantidad>0)
			{
				$result['status']=1;
			}
			echo json_encode($result);
			
		}	
		
		public function Imprimir_DocumentoSeleccionadoAnonimo()
		{
		/*if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$this->load->view('usuario/login');
			exit;
		}*/
		if (!isset($_GET['param1'])){	$prm_cod_documento='';} else{$prm_cod_documento=$_GET['param1'];}

		$prm_cod_documento = basename($_GET['param1']);	
		$prm_ruc_emisor = basename($_GET['param2']);		
		
		$carpetaemisor='6-'.$prm_ruc_emisor;		
		$carpeta = ''; //keys/			
		$rutadescargar=$this->Catalogos_model->Listar_RutaDocumentoDescargar();
		if(!empty($rutadescargar))//SI NO ES NULO O VACIO
		{
			$carpeta=$rutadescargar[0]['valorcadena'];
		}		
		$nombrearchivopdf='';
		$tipofirma='';
		//$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaIdRuc($prm_ruc_emisor);
		
		if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
		{
			$tipofirma=$Listar_EmpresaId[0]['tipo_conffirma'];
		}		
		$lista_documento=explode(',',$prm_cod_documento);
		if(!empty($lista_documento))
		{
			$listadearchivos=NULL;
			$contador=0;
			foreach($lista_documento as $key=>$v):
				if (strlen($v)>4)
				{
					if ($tipofirma==1)//BIZLIN
					{
						$nombrearchivopdf=$prm_ruc_emisor.'-'.$v;
					}
					else //LOCAL
					{
						$nombrearchivopdf='PDFLOCAL-'.$prm_ruc_emisor.'-'.$v;
					}					
					$src=$carpeta.$carpetaemisor.'/'.$v.'/'.$nombrearchivopdf.'.pdf';					
					//print_r($src);					
					$nombre_archivo='';		
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						//$nombre_archivo=$nombrearchivopdf.'.pdf';
						$listadearchivos[$contador]=$src;//$carpetadescarga.'/'.$nombrecarpetadoc;
						$contador++;						
					} 
					/*
					else //El fichero $nombre_fichero no existe
					{
						$codigodocumento='el_archivo_no_existe.pdf';	
						$src='././download/'.$codigodocumento;//base_url().'download/el_archivo_no_existe.pdf';
						$nombrearchivopdf=$nombrearchivopdf.'_NO_EXISTE';
					}*/
					
				}			
				endforeach;
				
			//print_r($listadearchivos);
			//return;			
				$this->load->library('my_pdfconcat'); 
				$pdf = new PDFMerger;
				foreach($listadearchivos as $key1=>$v1):
					$pdf->addPDF($v1, 'all');
				endforeach;
				
			//$fecha_actual = explode('/',date("d/m/Y"));
				$fecha_actual=((date("Y-m-d H-i-s")).'.'.substr(microtime(),0,5)*1000);
				
				if ($contador==1)
				{
					$pdf->merge('download', $prm_ruc_emisor.'-'.$fecha_actual.'.pdf');
				}
				else
				{
				//$pdf->merge('download', $prm_ruc_emisor.'-'.$fecha_actual[2].'-'.$fecha_actual[1].'-'.$fecha_actual[0].'.pdf');
					$pdf->merge('download', $prm_ruc_emisor.'-'.$fecha_actual.'.pdf');
				}
			}
		}	
		
	public function Crear_ArchivosDocumentoSeleccionadoAnonimo()//Crear_ArchivosDocumentoSeleccionado  
	{
		//if (!isset($_GET['param1'])){	$prm_cod_documento='';} else{$prm_cod_documento=$_GET['param1'];}

		//$prm_cod_documento = basename($_GET['param1']);		
		//$prm_ruc_emisor = basename($_GET['param2']);	
		
		$result['status']=0;
		/*if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}*/
		$prm_cod_documento=trim($this->input->post('param1'));
		$prm_ruc_emisor=trim($this->input->post('param2'));
		
		
		$carpetaemisor='6-'.$prm_ruc_emisor;		
		$carpeta = ''; //keys/			
		$rutadescargar=$this->Catalogos_model->Listar_RutaDocumentoDescargar();
		if(!empty($rutadescargar))//SI NO ES NULO O VACIO
		{
			$carpeta=$rutadescargar[0]['valorcadena'];
		}
		
		$tipofirma='';

		$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaIdRuc($prm_ruc_emisor);
		if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
		{
			$tipofirma=$Listar_EmpresaId[0]['tipo_conffirma'];
		}		
		$nombre_carpeta=$carpetaemisor.'/bloquedescargar';
		$carpetadescarga = '././download/'.$nombre_carpeta;

		$lista_documento=explode(',',$prm_cod_documento);	

		if(!empty($lista_documento))
		{	
			$this->load->library('zip'); 			
			if (!file_exists($carpetadescarga)) 
			{
				mkdir($carpetadescarga,0777, true);
			}
			else
			{
				$this->EliminarDirecctorio($carpetadescarga);
				mkdir($carpetadescarga,0777, true);
			}
			$listadearchivos=NULL;
			$contador=0;
			
			$cantidad=0;
			$cantidadbucle=0;
			
			foreach($lista_documento as $key=>$v):
				$nombrearchivopdf='';
			if (strlen($v)>4)
			{
					if ($tipofirma==1)//BIZLIN
					{
						$nombrearchivopdf=$prm_ruc_emisor.'-'.$v;
					}
					else //LOCAL
					{
						$nombrearchivopdf='PDFLOCAL-'.$prm_ruc_emisor.'-'.$v;
					}
					$src=$carpeta.$carpetaemisor.'/'.$v.'/'.$nombrearchivopdf.'.pdf';
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						$this->zip->read_file($src);
						$cantidad++;
						$cantidadbucle++;
					} 
					/*
					else //El fichero $nombre_fichero no existe
					{
						$codigodocumento='el_archivo_no_existe.pdf';	
						$src='././download/'.$codigodocumento;//base_url().'download/el_archivo_no_existe.pdf';
					}*/
					
					//CASO CDR
					$src=$carpeta.$carpetaemisor.'/'.$v.'/XML_CDR.xml';
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						$this->zip->read_file($src);
						$cantidad++;
						$cantidadbucle++;
					} 
					//CASO UBL
					$src=$carpeta.$carpetaemisor.'/'.$v.'/XML_UBL.xml';
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						$this->zip->read_file($src);
						$cantidad++;
						$cantidadbucle++;
					} 
					
					//CASO LOCAL_ CDR
					$src=$carpeta.$carpetaemisor.'/'.$v.'/LOCAL_XML_CDR.xml';
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						$this->zip->read_file($src);
						$cantidad++;
						$cantidadbucle++;
					} 
					//CASO LOCAL_ UBL
					$src=$carpeta.$carpetaemisor.'/'.$v.'/LOCAL_XML_UBL.xml';
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						$this->zip->read_file($src);
						$cantidad++;
						$cantidadbucle++;
					}
					
					if ($cantidadbucle>0)
					{
						$nombrecarpetadoc=$prm_ruc_emisor.'-'.$v;
						$listadearchivos[$contador]=$carpetadescarga.'/'.$nombrecarpetadoc;
						$this->zip->archive($carpetadescarga.'/'.$nombrecarpetadoc.'.zip');
						$this->zip->clear_data();
						$contador++;
						
						
						if($_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion']==2)//SOLO SI ES RECEPTOR SE ACTUALIZA LOS DATOS
						{
							$codigo_documento=explode('-',$v);
							$this->Comprobante_model->Actualizar_VistaDocumento($prm_ruc_emisor,$codigo_documento[0],($codigo_documento[1].'-'.$codigo_documento[2]));
						}					
						
						
					}
					$cantidadbucle=0;
				}
				endforeach;

				if ($cantidad>0)
				{
					$result['status']=1;
				}
				else
				{
					$result['status']=2;
				}
			}
			
		//echo json_encode('1');
			echo json_encode($result);
		}
		
		
		public function Descargar_DocumentoSeleccionadoAnonimo()
		{
		/*if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$this->load->view('usuario/login');
			exit;
		}*/
		if (!isset($_GET['param1'])){	$prm_cod_documento='';} else{$prm_cod_documento=$_GET['param1'];}
		

		$prm_cod_documento = basename($_GET['param1']);		
		$prm_ruc_emisor = basename($_GET['param2']);		
		
		
		//$this->Crear_ArchivosDocumentoSeleccionado($prm_cod_documento,$prm_ruc_emisor);
		
		$carpetaemisor='6-'.$prm_ruc_emisor;		
		$carpeta = ''; //keys/			
		$rutadescargar=$this->Catalogos_model->Listar_RutaDocumentoDescargar();
		if(!empty($rutadescargar))//SI NO ES NULO O VACIO
		{
			$carpeta=$rutadescargar[0]['valorcadena'];
		}		

		$tipofirma='';
		$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaIdRuc($prm_ruc_emisor);
		
		if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
		{
			$tipofirma=$Listar_EmpresaId[0]['tipo_conffirma'];
		}		
		$nombre_carpeta=$carpetaemisor.'/bloquedescargar';
		$carpetadescarga = '././download/'.$nombre_carpeta;
		$lista_documento=explode(',',$prm_cod_documento);
		
		if(!empty($lista_documento))
		{	
			$this->load->library('zip'); 
			$listadearchivos=NULL;
			$contador=0;
			
			foreach($lista_documento as $key=>$v):
				if (strlen($v)>4)
				{
					$nombrecarpetadoc=$prm_ruc_emisor.'-'.$v;
					$listadearchivos[$contador]=$carpetadescarga.'/'.$nombrecarpetadoc;
					$contador++;
				}
				endforeach;

			//$listadearchivos=NULL;
			//$listadearchivos=array ( 	"0"=> "././download/6-20100037689/bloquedescargar/2010003768901-F001-00000001","1"=> "././download/6-20100037689/bloquedescargar/2010003768901-F001-00000004");

				foreach($listadearchivos as $key1=>$v1):
					$this->zip->read_file($v1.'.zip');				
				endforeach;
				
			//$fecha_actual = explode('/',date("d/m/Y"));
				$fecha_actual=((date("Y-m-d H-i-s")).'.'.substr(microtime(),0,5)*1000);
			//$this->zip->download($prm_ruc_emisor.'-'.$fecha_actual[2].'-'.$fecha_actual[1].'-'.$fecha_actual[0].'.zip'); 
				$this->zip->download($prm_ruc_emisor.'-'.$fecha_actual.'.zip'); 
			}
		}
		

		
		public function Comprobar_DocumentoImprimir()
		{
			$result['status']=0;
			if(!$this->Usuarioinicio_model->SessionExiste())
			{
				$result['status']=1000;
				echo json_encode($result);
				exit;
			}
			$prm_cod_documento=trim($this->input->post('param1'));
			$prm_ruc_emisor=trim($this->input->post('param2'));
			
			$carpetaemisor='6-'.$prm_ruc_emisor;		
		$carpeta = ''; //keys/			
		$rutadescargar=$this->Catalogos_model->Listar_RutaDocumentoDescargar();
		if(!empty($rutadescargar))//SI NO ES NULO O VACIO
		{
			$carpeta=$rutadescargar[0]['valorcadena'];
		}		
		$nombrearchivopdf='';
		$tipofirma='';
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
		
		if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
		{
			$tipofirma=$Listar_EmpresaId[0]['tipo_conffirma'];
		}		
		$lista_documento=explode(',',$prm_cod_documento);
		if(!empty($lista_documento))
		{
			$listadearchivos=NULL;
			$contador=0;
			$cantidad=0;
			foreach($lista_documento as $key=>$v):
				if (strlen($v)>4)
				{
					if ($tipofirma==1)//BIZLIN
					{
						$nombrearchivopdf=$prm_ruc_emisor.'-'.$v;
					}
					else //LOCAL
					{
						$nombrearchivopdf='PDFLOCAL-'.$prm_ruc_emisor.'-'.$v;
					}			
					$src=$carpeta.$carpetaemisor.'/'.$v.'/'.$nombrearchivopdf.'.pdf';					
					$nombre_archivo='';		
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						//$nombre_archivo=$nombrearchivopdf.'.pdf';
						$cantidad++;
						
						if($_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion']==2)//SOLO SI ES RECEPTOR SE ACTUALIZA LOS DATOS
						{
							$codigo_documento=explode('-',$v);
							$this->Comprobante_model->Actualizar_VistaDocumento($prm_ruc_emisor,$codigo_documento[0],($codigo_documento[1].'-'.$codigo_documento[2]));
						}
						
						
					} 
					
					/*
					else //El fichero $nombre_fichero no existe
					{
						$codigodocumento='el_archivo_no_existe.pdf';	
						$src='././download/'.$codigodocumento;//base_url().'download/el_archivo_no_existe.pdf';
						$nombrearchivopdf=$nombrearchivopdf.'_NO_EXISTE';
					}
					$listadearchivos[$contador]=$src;//$carpetadescarga.'/'.$nombrecarpetadoc;
					$contador++;	
					*/
				}			
				endforeach;
				
			}
			if ($cantidad==0)
			{
				$result['status']=2;
			}
			else if ($cantidad>0)
			{
				$result['status']=1;
			}
			echo json_encode($result);
		}	
		
		public function Imprimir_DocumentoSeleccionado()
		{
			if(!$this->Usuarioinicio_model->SessionExiste())
			{
				$this->load->view('usuario/login');
				exit;
			}
			if (!isset($_GET['param1'])){	$prm_cod_documento='';} else{$prm_cod_documento=$_GET['param1'];}

			$prm_cod_documento = basename($_GET['param1']);	
			$prm_ruc_emisor = basename($_GET['param2']);		
			
			$carpetaemisor='6-'.$prm_ruc_emisor;		
		$carpeta = ''; //keys/			
		$rutadescargar=$this->Catalogos_model->Listar_RutaDocumentoDescargar();
		if(!empty($rutadescargar))//SI NO ES NULO O VACIO
		{
			$carpeta=$rutadescargar[0]['valorcadena'];
		}		
		$nombrearchivopdf='';
		$tipofirma='';
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
		
		if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
		{
			$tipofirma=$Listar_EmpresaId[0]['tipo_conffirma'];
		}		
		$lista_documento=explode(',',$prm_cod_documento);
		if(!empty($lista_documento))
		{
			$listadearchivos=NULL;
			$contador=0;
			foreach($lista_documento as $key=>$v):
				if (strlen($v)>4)
				{
					if ($tipofirma==1)//BIZLIN
					{
						$nombrearchivopdf=$prm_ruc_emisor.'-'.$v;
					}
					else //LOCAL
					{
						$nombrearchivopdf='PDFLOCAL-'.$prm_ruc_emisor.'-'.$v;
					}					
					$src=$carpeta.$carpetaemisor.'/'.$v.'/'.$nombrearchivopdf.'.pdf';					
					//print_r($src);					
					$nombre_archivo='';		
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						//$nombre_archivo=$nombrearchivopdf.'.pdf';
						$listadearchivos[$contador]=$src;//$carpetadescarga.'/'.$nombrecarpetadoc;
						$contador++;						
					} 
					/*
					else //El fichero $nombre_fichero no existe
					{
						$codigodocumento='el_archivo_no_existe.pdf';	
						$src='././download/'.$codigodocumento;//base_url().'download/el_archivo_no_existe.pdf';
						$nombrearchivopdf=$nombrearchivopdf.'_NO_EXISTE';
					}*/
					
				}			
				endforeach;
				
			//print_r($listadearchivos);
			//return;			
				$this->load->library('my_pdfconcat'); 
				$pdf = new PDFMerger;
				foreach($listadearchivos as $key1=>$v1):
					$pdf->addPDF($v1, 'all');
				endforeach;
				
			//$fecha_actual = explode('/',date("d/m/Y"));
				$fecha_actual=((date("Y-m-d H-i-s")).'.'.substr(microtime(),0,5)*1000);
				
				if ($contador==1)
				{
					$pdf->merge('download', $prm_ruc_emisor.'-'.$fecha_actual.'.pdf');
				}
				else
				{
				//$pdf->merge('download', $prm_ruc_emisor.'-'.$fecha_actual[2].'-'.$fecha_actual[1].'-'.$fecha_actual[0].'.pdf');
					$pdf->merge('download', $prm_ruc_emisor.'-'.$fecha_actual.'.pdf');
				}
			}
		}	
		
	public function Crear_ArchivosDocumentoSeleccionado()//Crear_ArchivosDocumentoSeleccionado  
	{
		//if (!isset($_GET['param1'])){	$prm_cod_documento='';} else{$prm_cod_documento=$_GET['param1'];}

		//$prm_cod_documento = basename($_GET['param1']);		
		//$prm_ruc_emisor = basename($_GET['param2']);	
		
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_documento=trim($this->input->post('param1'));
		$prm_ruc_emisor=trim($this->input->post('param2'));
		
		
		$carpetaemisor='6-'.$prm_ruc_emisor;		
		$carpeta = ''; //keys/			
		$rutadescargar=$this->Catalogos_model->Listar_RutaDocumentoDescargar();
		if(!empty($rutadescargar))//SI NO ES NULO O VACIO
		{
			$carpeta=$rutadescargar[0]['valorcadena'];
		}
		
		$tipofirma='';
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
		if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
		{
			$tipofirma=$Listar_EmpresaId[0]['tipo_conffirma'];
		}		
		$nombre_carpeta=$carpetaemisor.'/bloquedescargar';
		$carpetadescarga = '././download/'.$nombre_carpeta;

		$lista_documento=explode(',',$prm_cod_documento);	

		if(!empty($lista_documento))
		{	
			$this->load->library('zip'); 			
			if (!file_exists($carpetadescarga)) 
			{
				mkdir($carpetadescarga,0777, true);
			}
			else
			{
				$this->EliminarDirecctorio($carpetadescarga);
				mkdir($carpetadescarga,0777, true);
			}
			$listadearchivos=NULL;
			$contador=0;
			
			$cantidad=0;
			$cantidadbucle=0;
			
			foreach($lista_documento as $key=>$v):
				$nombrearchivopdf='';
			if (strlen($v)>4)
			{
					if ($tipofirma==1)//BIZLIN
					{
						$nombrearchivopdf=$prm_ruc_emisor.'-'.$v;
					}
					else //LOCAL
					{
						$nombrearchivopdf='PDFLOCAL-'.$prm_ruc_emisor.'-'.$v;
					}
					$src=$carpeta.$carpetaemisor.'/'.$v.'/'.$nombrearchivopdf.'.pdf';
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						$this->zip->read_file($src);
						$cantidad++;
						$cantidadbucle++;
					} 
					/*
					else //El fichero $nombre_fichero no existe
					{
						$codigodocumento='el_archivo_no_existe.pdf';	
						$src='././download/'.$codigodocumento;//base_url().'download/el_archivo_no_existe.pdf';
					}*/
					
					//CASO CDR
					$src=$carpeta.$carpetaemisor.'/'.$v.'/XML_CDR.xml';
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						$this->zip->read_file($src);
						$cantidad++;
						$cantidadbucle++;
					} 
					//CASO UBL
					$src=$carpeta.$carpetaemisor.'/'.$v.'/XML_UBL.xml';
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						$this->zip->read_file($src);
						$cantidad++;
						$cantidadbucle++;
					} 
					
					//CASO LOCAL_ CDR
					$src=$carpeta.$carpetaemisor.'/'.$v.'/LOCAL_XML_CDR.xml';
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						$this->zip->read_file($src);
						$cantidad++;
						$cantidadbucle++;
					} 
					//CASO LOCAL_ UBL
					$src=$carpeta.$carpetaemisor.'/'.$v.'/LOCAL_XML_UBL.xml';
					if (file_exists($src))  //El fichero $nombre_fichero existe
					{
						$this->zip->read_file($src);
						$cantidad++;
						$cantidadbucle++;
					}
					
					if ($cantidadbucle>0)
					{
						$nombrecarpetadoc=$prm_ruc_emisor.'-'.$v;
						$listadearchivos[$contador]=$carpetadescarga.'/'.$nombrecarpetadoc;
						$this->zip->archive($carpetadescarga.'/'.$nombrecarpetadoc.'.zip');
						$this->zip->clear_data();
						$contador++;
						
						
						if($_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion']==2)//SOLO SI ES RECEPTOR SE ACTUALIZA LOS DATOS
						{
							$codigo_documento=explode('-',$v);
							$this->Comprobante_model->Actualizar_VistaDocumento($prm_ruc_emisor,$codigo_documento[0],($codigo_documento[1].'-'.$codigo_documento[2]));
						}					
						
						
					}
					$cantidadbucle=0;
				}
				endforeach;

				if ($cantidad>0)
				{
					$result['status']=1;
				}
				else
				{
					$result['status']=2;
				}
			}
			
		//echo json_encode('1');
			echo json_encode($result);
		}
		
		
		public function Descargar_DocumentoSeleccionado()
		{
			if(!$this->Usuarioinicio_model->SessionExiste())
			{
				$this->load->view('usuario/login');
				exit;
			}
			if (!isset($_GET['param1'])){	$prm_cod_documento='';} else{$prm_cod_documento=$_GET['param1'];}
			

			$prm_cod_documento = basename($_GET['param1']);		
			$prm_ruc_emisor = basename($_GET['param2']);		
			
			
		//$this->Crear_ArchivosDocumentoSeleccionado($prm_cod_documento,$prm_ruc_emisor);
			
			$carpetaemisor='6-'.$prm_ruc_emisor;		
		$carpeta = ''; //keys/			
		$rutadescargar=$this->Catalogos_model->Listar_RutaDocumentoDescargar();
		if(!empty($rutadescargar))//SI NO ES NULO O VACIO
		{
			$carpeta=$rutadescargar[0]['valorcadena'];
		}		

		$tipofirma='';
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
		if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
		{
			$tipofirma=$Listar_EmpresaId[0]['tipo_conffirma'];
		}		
		$nombre_carpeta=$carpetaemisor.'/bloquedescargar';
		$carpetadescarga = '././download/'.$nombre_carpeta;
		$lista_documento=explode(',',$prm_cod_documento);
		
		if(!empty($lista_documento))
		{	
			$this->load->library('zip'); 
			$listadearchivos=NULL;
			$contador=0;
			
			foreach($lista_documento as $key=>$v):
				if (strlen($v)>4)
				{
					$nombrecarpetadoc=$prm_ruc_emisor.'-'.$v;
					$listadearchivos[$contador]=$carpetadescarga.'/'.$nombrecarpetadoc;
					$contador++;
				}
				endforeach;

			//$listadearchivos=NULL;
			//$listadearchivos=array ( 	"0"=> "././download/6-20100037689/bloquedescargar/2010003768901-F001-00000001","1"=> "././download/6-20100037689/bloquedescargar/2010003768901-F001-00000004");

				foreach($listadearchivos as $key1=>$v1):
					$this->zip->read_file($v1.'.zip');				
				endforeach;
				
			//$fecha_actual = explode('/',date("d/m/Y"));
				$fecha_actual=((date("Y-m-d H-i-s")).'.'.substr(microtime(),0,5)*1000);
			//$this->zip->download($prm_ruc_emisor.'-'.$fecha_actual[2].'-'.$fecha_actual[1].'-'.$fecha_actual[0].'.zip'); 
				$this->zip->download($prm_ruc_emisor.'-'.$fecha_actual.'.zip'); 
			}
		}
		
		
		
		
		function EliminarDirecctorio($carpeta)
		{
			foreach(glob($carpeta . "/*") as $archivos_carpeta)
			{
			//echo $archivos_carpeta;
				
				if (is_dir($archivos_carpeta))
				{
					EliminarDirecctorio($archivos_carpeta);
				}
				else
				{
					unlink($archivos_carpeta);
				}
			}
			rmdir($carpeta);
		}
		
		public function Listar_DetalleDocumento()
		{
		//print_r('INICIA 1');
			$arr=NULL;
			$Contador=0;
			$result['status']=0;
		/*
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}*/
		//print_r('INICIA FIN');
		$prm_ruc_empremisor=trim($this->input->post('txt_RucEmpresa'));
		
		$prm_datosseleccionados=trim($this->input->post('txt_datosseleccionados'));
		$prm_datosseleccionados=(str_replace(",","",$prm_datosseleccionados));		
		$datos_seleccionados=explode('-',$prm_datosseleccionados);
		$prm_tipo_documento=$datos_seleccionados[0];
		$prm_serie_numero=$datos_seleccionados[1].'-'.$datos_seleccionados[2];
		
		$consulta =$this->Comprobante_model->Listar_DetalleDocumento($prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero);
		
		//print_r($consulta);
		//return;
		
		/*BUSCANDO LAS CARACTERISTICAS*/
		/*
		$direccioncliente='';
		$detallecaracteristica=$this->Comprobante_model->Buscar_DetalleCaracteristica('6',$prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero,'direccionAdquiriente');
		if(!empty($detallecaracteristica))//SI NO ES NULO O VACIO
		{
			$direccioncliente =  $detallecaracteristica[0]['valor'];
		}*/
		/*BUSCANDO LAS CARACTERISTICAS DE DIRECCION*/
		$direccioncliente='';
		$opciondireccioncliente=substr($prm_serie_numero,0,1);
		if ($opciondireccioncliente=='F')
		{
			$direccionAdquiriente='';
			$urbanizacionAdquiriente='';
			$distritoAdquiriente='';
			$provinciaAdquiriente='';
			$departamentoAdquiriente='';
			$detallecaracteristica=$this->Comprobante_model->Buscar_DetalleCaracteristica('6',$prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero);
			if(!empty($detallecaracteristica))
			{
				foreach($detallecaracteristica as $key=>$v):
					if (trim($v['valor'])<>'-')
					{
						switch($v['clave'])
						{
							case 'direccionAdquiriente':
							$direccionAdquiriente=trim($v['valor']);
							break;
							case 'urbanizacionAdquiriente':
							$urbanizacionAdquiriente=trim($v['valor']);
							break;
							case 'distritoAdquiriente':
							$distritoAdquiriente=trim($v['valor']);
							break;
							case 'provinciaAdquiriente':
							$provinciaAdquiriente=trim($v['valor']);
							break;
							case 'departamentoAdquiriente':
							$departamentoAdquiriente=trim($v['valor']);
							break;
						}
					}
					endforeach;
					if (trim($direccionAdquiriente)<>''){
						$direccioncliente=$direccionAdquiriente;
					}
					if (trim($urbanizacionAdquiriente)<>''){
						if (trim($direccioncliente)==''){
							$direccioncliente=$urbanizacionAdquiriente;
						}else{
							$direccioncliente=$direccioncliente.' - '.$urbanizacionAdquiriente;}
						}
						if (trim($distritoAdquiriente)<>''){
							if (trim($direccioncliente)==''){
								$direccioncliente=$distritoAdquiriente;}
								else{
									$direccioncliente=$direccioncliente.' - '.$distritoAdquiriente;}
								}
								if (trim($provinciaAdquiriente)<>''){
									if (trim($direccioncliente)==''){
										$direccioncliente=$provinciaAdquiriente;}
										else{
											$direccioncliente=$direccioncliente.' - '.$provinciaAdquiriente;}
										}
										if (trim($departamentoAdquiriente)<>''){
											if (trim($direccioncliente)==''){
												$direccioncliente=$departamentoAdquiriente;}
												else{
													$direccioncliente=$direccioncliente.' - '.$departamentoAdquiriente;}
												}
											}
										}

		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;
			$arr[$key]['razonsocialemisor'] =strtoupper(trim($v['razonsocialemisor'])); 
			$arr[$key]['nombrecomercialemisor'] =trim($v['nombrecomercialemisor']); 
			$arr[$key]['direccionemisor'] =trim($v['direccionemisor']); 
			$arr[$key]['distritoemisor'] =trim($v['distritoemisor']); 
			$arr[$key]['departamentoemisor'] =trim($v['departamentoemisor']); 
			$arr[$key]['provinciaemisor'] =trim($v['provinciaemisor']); 
			$arr[$key]['numerodocumentoemisor'] =trim($v['numerodocumentoemisor']); 
			$arr[$key]['tipodocumentoemisor'] =trim($v['tipodocumentoemisor']); 				
			$arr[$key]['nombre_tipodocumentoemisor'] =trim($v['nombre_tipodocumentoemisor']); 
			
			$arr[$key]['serienumero'] =trim($v['serienumero']); 
			$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
			$arr[$key]['nombre_tipodocumento'] =trim($v['nombre_tipodocumento']);

			$arr[$key]['fechaemision'] = trim($v['fechaemision']);		
			
			$arr[$key]['numerodocumentoadquiriente'] =trim($v['numerodocumentoadquiriente']); 
			$arr[$key]['razonsocialadquiriente'] =trim($v['razonsocialadquiriente']);
			if ($opciondireccioncliente=='F')
			{
				$arr[$key]['direccioncliente'] =$direccioncliente; 
			}else
			{
				if (!$v['lugardestino']){
					if (trim($v['lugardestino'])<>'-'){
						$arr[$key]['direccioncliente'] =trim($v['lugardestino']);}
					}else{
						$arr[$key]['direccioncliente'] ='';
					}
				}
				$arr[$key]['textoleyenda_1'] =trim($v['textoleyenda_1']);			
				if ($v['tipomoneda']=='PEN')
				{
					$arr[$key]['tipomonedacabecera'] =trim($v['tipomoneda']).' - Sol'; 
				}
				else
				{
					$arr[$key]['tipomonedacabecera'] =trim($v['tipomoneda']).' - Dolar';
				}
				$arr[$key]['tipomoneda'] =trim($v['tipomoneda']);
				$arr[$key]['tipomonedadescripcion'] =trim($v['tipomonedadescripcion']);
				$arr[$key]['totalvalorventanetoopgravadas'] =trim($v['totalvalorventanetoopgravadas']); 
				$arr[$key]['totalvalorventanetoopnogravada'] =trim($v['totalvalorventanetoopnogravada']);
				$arr[$key]['totalvalorventanetoopexonerada'] =trim($v['totalvalorventanetoopexonerada']); 	
				$arr[$key]['totalvalorventanetoopgratuitas'] =trim($v['totalvalorventanetoopgratuitas']);							
				$arr[$key]['totaldescuentos'] =trim($v['totaldescuentos']); 
				
				$arr[$key]['totaligv'] =trim($v['totaligv']); 
				$arr[$key]['totalventa'] =trim($v['totalventa']); 

				$arr[$key]['totaldetraccion'] =trim($v['totaldetraccion']); 
				$arr[$key]['totalbonificacion'] =trim($v['totalbonificacion']); 
				
				$arr[$key]['numeroordenitem'] =trim($v['numeroordenitem']); 
				$arr[$key]['codigoproducto'] =trim($v['codigoproducto']); 
				$arr[$key]['descripcion'] =trim($v['descripcion']); 
				$arr[$key]['unidadmedida'] =trim($v['unidadmedida']); 
				$arr[$key]['cantidad'] =trim($v['cantidad']); 
				$arr[$key]['importeunitariosinimpuesto'] =trim($v['importeunitariosinimpuesto']); 
				$arr[$key]['importeunitarioconimpuesto'] =trim($v['importeunitarioconimpuesto']); 				
				$arr[$key]['importedescuento'] =trim($v['importedescuento']); 
				$arr[$key]['importetotalsinimpuesto'] =trim($v['importetotalsinimpuesto']); 
				
				endforeach;
				
			if($_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion']==2)//SOLO SI ES RECEPTOR SE ACTUALIZA LOS DATOS
			{
				$this->Comprobante_model->Actualizar_VistaDocumento($prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero);
			}
			
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
	
	public function Listar_DetalleDocumento_Anonimo()
	{
		$arr=NULL;
		$Contador=0;
		$result['status']=0;
		$prm_ruc_empremisor=trim($this->input->post('txt_RucEmpresa'));
		
		$prm_datosseleccionados=trim($this->input->post('txt_datosseleccionados'));
		$prm_datosseleccionados=(str_replace(",","",$prm_datosseleccionados));		
		$datos_seleccionados=explode('-',$prm_datosseleccionados);
		$prm_tipo_documento=$datos_seleccionados[0];
		$prm_serie_numero=$datos_seleccionados[1].'-'.$datos_seleccionados[2];
		
		$consulta =$this->Comprobante_model->Listar_DetalleDocumento($prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero);
		
		/*BUSCANDO LAS CARACTERISTICAS DE DIRECCION*/
		$direccioncliente='';
		$opciondireccioncliente=substr($prm_serie_numero,0,1);
		if ($opciondireccioncliente=='F')
		{
			$direccionAdquiriente='';
			$urbanizacionAdquiriente='';
			$distritoAdquiriente='';
			$provinciaAdquiriente='';
			$departamentoAdquiriente='';
			$detallecaracteristica=$this->Comprobante_model->Buscar_DetalleCaracteristica('6',$prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero);
			if(!empty($detallecaracteristica))
			{
				foreach($detallecaracteristica as $key=>$v):
					if (trim($v['valor'])<>'-')
					{
						switch($v['clave'])
						{
							case 'direccionAdquiriente':
							$direccionAdquiriente=trim($v['valor']);
							break;
							case 'urbanizacionAdquiriente':
							$urbanizacionAdquiriente=trim($v['valor']);
							break;
							case 'distritoAdquiriente':
							$distritoAdquiriente=trim($v['valor']);
							break;
							case 'provinciaAdquiriente':
							$provinciaAdquiriente=trim($v['valor']);
							break;
							case 'departamentoAdquiriente':
							$departamentoAdquiriente=trim($v['valor']);
							break;
						}
					}
					endforeach;
					if (trim($direccionAdquiriente)<>''){
						$direccioncliente=$direccionAdquiriente;
					}
					if (trim($urbanizacionAdquiriente)<>''){
						if (trim($direccioncliente)==''){
							$direccioncliente=$urbanizacionAdquiriente;
						}else{
							$direccioncliente=$direccioncliente.' - '.$urbanizacionAdquiriente;}
						}
						if (trim($distritoAdquiriente)<>''){
							if (trim($direccioncliente)==''){
								$direccioncliente=$distritoAdquiriente;}
								else{
									$direccioncliente=$direccioncliente.' - '.$distritoAdquiriente;}
								}
								if (trim($provinciaAdquiriente)<>''){
									if (trim($direccioncliente)==''){
										$direccioncliente=$provinciaAdquiriente;}
										else{
											$direccioncliente=$direccioncliente.' - '.$provinciaAdquiriente;}
										}
										if (trim($departamentoAdquiriente)<>''){
											if (trim($direccioncliente)==''){
												$direccioncliente=$departamentoAdquiriente;}
												else{
													$direccioncliente=$direccioncliente.' - '.$departamentoAdquiriente;}
												}
											}
										}

		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;
			$arr[$key]['razonsocialemisor'] =strtoupper(trim($v['razonsocialemisor'])); 
			$arr[$key]['nombrecomercialemisor'] =trim($v['nombrecomercialemisor']); 
			$arr[$key]['direccionemisor'] =trim($v['direccionemisor']); 
			$arr[$key]['distritoemisor'] =trim($v['distritoemisor']); 
			$arr[$key]['departamentoemisor'] =trim($v['departamentoemisor']); 
			$arr[$key]['provinciaemisor'] =trim($v['provinciaemisor']); 
			$arr[$key]['numerodocumentoemisor'] =trim($v['numerodocumentoemisor']); 
			$arr[$key]['tipodocumentoemisor'] =trim($v['tipodocumentoemisor']); 				
			$arr[$key]['nombre_tipodocumentoemisor'] =trim($v['nombre_tipodocumentoemisor']); 
			
			$arr[$key]['serienumero'] =trim($v['serienumero']); 
			$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
			$arr[$key]['nombre_tipodocumento'] =trim($v['nombre_tipodocumento']);

			$arr[$key]['fechaemision'] = trim($v['fechaemision']);		
			
			$arr[$key]['numerodocumentoadquiriente'] =trim($v['numerodocumentoadquiriente']); 
			$arr[$key]['razonsocialadquiriente'] =trim($v['razonsocialadquiriente']); 	
			if ($opciondireccioncliente=='F')
			{
				$arr[$key]['direccioncliente'] =$direccioncliente; 
			}else
			{
				if (!$v['lugardestino']){
					if (trim($v['lugardestino'])<>'-'){
						$arr[$key]['direccioncliente'] =trim($v['lugardestino']);}
					}else{
						$arr[$key]['direccioncliente'] ='';
					}						
				}
				
				$arr[$key]['textoleyenda_1'] =trim($v['textoleyenda_1']);				
				if ($v['tipomoneda']=='PEN')
				{
					$arr[$key]['tipomonedacabecera'] =trim($v['tipomoneda']).' - Sol'; 
				}
				else
				{
					$arr[$key]['tipomonedacabecera'] =trim($v['tipomoneda']).' - Dolar';
				}
				$arr[$key]['tipomoneda'] =trim($v['tipomoneda']);
				$arr[$key]['tipomonedadescripcion'] =trim($v['tipomonedadescripcion']);
				$arr[$key]['totalvalorventanetoopgravadas'] =trim($v['totalvalorventanetoopgravadas']); 
				$arr[$key]['totalvalorventanetoopnogravada'] =trim($v['totalvalorventanetoopnogravada']);
				$arr[$key]['totalvalorventanetoopexonerada'] =trim($v['totalvalorventanetoopexonerada']); 	
				$arr[$key]['totalvalorventanetoopgratuitas'] =trim($v['totalvalorventanetoopgratuitas']);							
				$arr[$key]['totaldescuentos'] =trim($v['totaldescuentos']); 
				
				$arr[$key]['totaligv'] =trim($v['totaligv']); 
				$arr[$key]['totalventa'] =trim($v['totalventa']); 

				$arr[$key]['totaldetraccion'] =trim($v['totaldetraccion']); 
				$arr[$key]['totalbonificacion'] =trim($v['totalbonificacion']); 
				
				$arr[$key]['numeroordenitem'] =trim($v['numeroordenitem']); 
				$arr[$key]['codigoproducto'] =trim($v['codigoproducto']); 
				$arr[$key]['descripcion'] =trim($v['descripcion']); 
				$arr[$key]['unidadmedida'] =trim($v['unidadmedida']); 
				$arr[$key]['cantidad'] =trim($v['cantidad']); 
				$arr[$key]['importeunitariosinimpuesto'] =trim($v['importeunitariosinimpuesto']); 
				$arr[$key]['importeunitarioconimpuesto'] =trim($v['importeunitarioconimpuesto']); 				
				$arr[$key]['importedescuento'] =trim($v['importedescuento']); 
				$arr[$key]['importetotalsinimpuesto'] =trim($v['importetotalsinimpuesto']); 
				endforeach;
			}
			if(sizeof($arr)>0)
			{
				$result['status']=1;
				$result['data']=$arr;
			}else{
				$result['status']=0;
				$result['data']="";
			}
			echo json_encode($result);
		}	


		public function existe_comprobante()
		{
			$arr=NULL;
			$Contador=0;
			$result['status']=0;
			
			$prm_tipodedocumento=trim($this->input->post('cmb_tipodedocumento'));
			$prm_serienumero=trim($this->input->post('txt_serienumero'));
			$prm_montototal=trim($this->input->post('txt_montototal'));		
			$prm_montototal=number_format(trim($prm_montototal), 2, '.', '');
			
			$prm_montototaltmp=explode('.',$prm_montototal);
			
			if (count($prm_montototaltmp)>1)
			{
				if (strlen($prm_montototaltmp['1'])==1)
				{
					$prm_montototal=$prm_montototaltmp['0'].'.'.$prm_montototaltmp['1'].'0';
				}
				else
				{	
					$prm_montototal=$prm_montototaltmp['0'].'.'.$prm_montototaltmp['1'];
				}
			}
			else
			{
				$prm_montototal=$prm_montototaltmp['0'].'.00';
			}

			$prm_fechaemisiontmp=explode('/',trim($this->input->post('txt_fechaemision')));
			$prm_fechaemision=$prm_fechaemisiontmp[2].'-'.$prm_fechaemisiontmp[1].'-'.$prm_fechaemisiontmp[0];		
			$prm_rucproveedor=trim($this->input->post('text_rucproveedor'));

			
			$consulta =$this->Comprobante_model->existe_comprobante($prm_tipodedocumento,$prm_serienumero,$prm_montototal,$prm_fechaemision,$prm_rucproveedor);


		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			$result['status']=$consulta[0]['cantidad'];
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
		if (!isset($_GET['param12'])){	$prm_tipomoneda='';} else{$prm_tipomoneda=$_GET['param12'];}
		
		
		if ($prm_fec_emisiniciotmp=='')
		{
			$prm_fec_emisinicio='';
		}
		else
		{
			$prm_fec_emisiniciotmp=explode('/',$prm_fec_emisiniciotmp);
			$prm_fec_emisinicio=($prm_fec_emisiniciotmp[2].'-'.$prm_fec_emisiniciotmp[1].'-'.$prm_fec_emisiniciotmp[0]);
		}

		//$prm_fec_emisfinaltmp=trim($this->input->post('txt_FechaEmisionFinal'));
		if ($prm_fec_emisfinaltmp=='')
		{
			$prm_fec_emisfinal='';
		}
		else
		{
			$prm_fec_emisfinaltmp=explode('/',$prm_fec_emisfinaltmp);
			$prm_fec_emisfinal=$prm_fec_emisfinaltmp[2].'-'.$prm_fec_emisfinaltmp[1].'-'.$prm_fec_emisfinaltmp[0];
		}

		if (substr($prm_documento_cliente,0,1)=='E')
		{
			$prm_documento_cliente='-';
		}
		
		$arr=NULL;

		$consulta =$this->Comprobante_model->Listar_Comprobantes(
			$prm_ruc_empr,				$prm_documento_cliente,	$prm_serie_numeroinicio,
			$prm_serie_numerofinal,		$prm_cod_estdoc,		$prm_fec_emisinicio,
			$prm_fec_emisfinal,			$prm_tipo_documentosunat,$prm_estado_documentosunat,
			$prm_tipomoneda, $prm_razonsocialcliente);

		$estado_documento='';
		$tipo_documentosunat='';
		$estado_documentosunat='';

		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				
				if ($prm_estado_documentosunat=='0')
				{
					if ($prm_documento_cliente=='-')
					{
						$posicion = strpos($prm_razonsocialcliente,$v['razonsocialadquiriente']);			 
						if($posicion !== FALSE)	
						{
							$arr[$key]['razonsocialadquiriente'] =strtoupper(trim($v['razonsocialadquiriente'])); 
							$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
							$arr[$key]['nomb_tipodocumento'] =trim($v['nomb_tipodocumento']);				
							$arr[$key]['serienumero'] =trim($v['serienumero']);
							$arr[$key]['tipomoneda'] =  trim(strtoupper($v['tipomoneda']));
							$arr[$key]['totalventa'] =  number_format(trim($v['totalventa']),2,'.',','); 
							$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
							$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
							$arr[$key]['estado_documento'] =  trim(strtoupper($v['estado_documento']));					
							$arr[$key]['estadosunat'] =  trim($v['estadosunat']);
							$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
							$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);						
							$arr[$key]['visualizado'] =  trim($v['visualizado']);	
							
							if ( $v['mensajeresponse']=='1')
							{
								$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
							}
							else
							{
								if ($v['bl_estadoregistro']=='E') //ERROR LOCAL
								{
									//TRAER LOS DATOS DEL ERROR
									$Listar_ErrorDocumento=$this->Comprobante_model->Listar_ErrorDocumento($prm_ruc_empr,$v['tipodocumentoemisor'],$v['tipodocumento'],$v['serienumero']);
									if(!empty($Listar_ErrorDocumento))//SI NO ES NULO O VACIO
									{
										$arr[$key]['obssunat'] =  $Listar_ErrorDocumento[0]['descripcionerror'];
									}
									else
									{
										$arr[$key]['obssunat'] =  '';
									}
									
								}
								else
								{
									if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
									{
										if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
										{
											$arr[$key]['obssunat']='';
										}
										else
										{
											$arr[$key]['obssunat']=$v['bl_mensajesunat'];
										}
									}
									else
									{
										$arr[$key]['obssunat']=$v['bl_mensaje'];
									}
								}
							}
							
							$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);					
							if ($v['bl_estadoregistro']=='L')
							{
								$arr[$key]['cant_reintento'] =  trim($v['reintento']);
							}
							else
							{
								$arr[$key]['cant_reintento'] =  trim($v['bl_reintento']);
							}	
							$estado_documento=trim(strtoupper($v['estado_documento']));
							$tipo_documentosunat=trim(strtoupper($v['nomb_tipodocumento']));
							$estado_documentosunat=$arr[$key]['nombreestadosunat'];
						}
					}
					else
					{
						$arr[$key]['razonsocialadquiriente'] =strtoupper(trim($v['razonsocialadquiriente'])); 
						$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
						$arr[$key]['nomb_tipodocumento'] =trim($v['nomb_tipodocumento']);				
						$arr[$key]['serienumero'] =trim($v['serienumero']);
						$arr[$key]['tipomoneda'] =  trim(strtoupper($v['tipomoneda']));
						$arr[$key]['totalventa'] =  number_format(trim($v['totalventa']),2,'.',','); 
						$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
						$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
						$arr[$key]['estado_documento'] =  trim(strtoupper($v['estado_documento']));					
						$arr[$key]['estadosunat'] =  trim($v['estadosunat']);
						$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
						$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);						
						$arr[$key]['visualizado'] =  trim($v['visualizado']);	
						
						if ( $v['mensajeresponse']=='1')
						{
							$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
						}
						else
						{
								if ($v['bl_estadoregistro']=='E') //ERROR LOCAL
								{
									//TRAER LOS DATOS DEL ERROR
									$Listar_ErrorDocumento=$this->Comprobante_model->Listar_ErrorDocumento($prm_ruc_empr,$v['tipodocumentoemisor'],$v['tipodocumento'],$v['serienumero']);
									if(!empty($Listar_ErrorDocumento))//SI NO ES NULO O VACIO
									{
										$arr[$key]['obssunat'] =  $Listar_ErrorDocumento[0]['descripcionerror'];
									}
									else
									{
										$arr[$key]['obssunat'] =  '';
									}
									
								}
								else
								{
									if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
									{
										if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
										{
											$arr[$key]['obssunat']='';
										}
										else
										{
											$arr[$key]['obssunat']=$v['bl_mensajesunat'];
										}
									}
									else
									{
										$arr[$key]['obssunat']=$v['bl_mensaje'];
									}
								}
							}
							
							
							$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);					
							if ($v['bl_estadoregistro']=='L')
							{
								$arr[$key]['cant_reintento'] =  trim($v['reintento']);
							}
							else
							{
								$arr[$key]['cant_reintento'] =  trim($v['bl_reintento']);
							}	
							$estado_documento=trim(strtoupper($v['estado_documento']));
							$tipo_documentosunat=trim(strtoupper($v['nomb_tipodocumento']));
							$estado_documentosunat=$arr[$key]['nombreestadosunat'];
						}
					}
				else //SIGNIFICA QUE SI SELECCIONO UN TIPO DE ESTADO SUNAT
				{
					if ($prm_documento_cliente=='-')
					{
						$posicion = strpos($prm_razonsocialcliente,$v['razonsocialadquiriente']);			 
						if($posicion !== FALSE)	
						{
							$estadodocsunat=$v['estadosunat'];
							$posicion = strpos($estadodocsunat,'/');			 
							if($posicion !== FALSE)	
							{
								$estadodocsunat=substr($estadodocsunat, -5); 
							}
							$posicion = strpos($estadodocsunat,$prm_estado_documentosunat);			 
							if($posicion !== FALSE)	
							{
								$arr[$key]['razonsocialadquiriente'] =strtoupper(trim($v['razonsocialadquiriente'])); 
								$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
								$arr[$key]['nomb_tipodocumento'] =trim($v['nomb_tipodocumento']);				
								$arr[$key]['serienumero'] =trim($v['serienumero']);
								$arr[$key]['tipomoneda'] =  trim($v['tipomoneda']);
								$arr[$key]['totalventa'] = number_format(trim($v['totalventa']),2,'.',','); 
								$arr[$key]['fechaemision'] = trim($v['fechaemision']);	
								$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
								$arr[$key]['estado_documento'] =  trim(strtoupper($v['estado_documento']));
								$arr[$key]['estadosunat'] =  trim($v['estadosunat']);		
								$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
								$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);		
								$arr[$key]['visualizado'] =  trim($v['visualizado']);
								
								if ( $v['mensajeresponse']=='1')
								{
									$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
								}
								else
								{					
									if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
									{
										if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
										{
											$arr[$key]['obssunat']='';
										}
										else
										{
											$arr[$key]['obssunat']=$v['bl_mensajesunat'];
										}
									}
									else
									{
										$arr[$key]['obssunat']=$v['bl_mensaje'];
									}
								}
								
								$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);
								if ($v['bl_estadoregistro']=='L')
								{
									$arr[$key]['cant_reintento'] =  trim($v['reintento']);
								}
								else
								{
									$arr[$key]['cant_reintento'] =  trim($v['bl_reintento']);
								}
								$estado_documento=trim(strtoupper($v['estado_documento']));	
								$tipo_documentosunat=trim(strtoupper($v['nomb_tipodocumento']));
								$estado_documentosunat=$arr[$key]['nombreestadosunat'];
							}				
						}
					}
					else
					{
						$estadodocsunat=$v['estadosunat'];
						$posicion = strpos($estadodocsunat,'/');			 
						if($posicion !== FALSE)	
						{
							$estadodocsunat=substr($estadodocsunat, -5); 
						}
						$posicion = strpos($estadodocsunat,$prm_estado_documentosunat);			 
						if($posicion !== FALSE)	
						{
							$arr[$key]['razonsocialadquiriente'] =strtoupper(trim($v['razonsocialadquiriente'])); 
							$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
							$arr[$key]['nomb_tipodocumento'] =trim($v['nomb_tipodocumento']);				
							$arr[$key]['serienumero'] =trim($v['serienumero']);
							$arr[$key]['tipomoneda'] =  trim($v['tipomoneda']);
							$arr[$key]['totalventa'] =  number_format(trim($v['totalventa']),2,'.',','); 
							$arr[$key]['fechaemision'] = trim($v['fechaemision']);	
							$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
							$arr[$key]['estado_documento'] =  trim(strtoupper($v['estado_documento']));
							$arr[$key]['estadosunat'] =  trim($v['estadosunat']);		
							$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
							$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);		
							$arr[$key]['visualizado'] =  trim($v['visualizado']);	
							
							if ( $v['mensajeresponse']=='1')
							{
								$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
							}
							else
							{					
								if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
								{
									if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
									{
										$arr[$key]['obssunat']='';
									}
									else
									{
										$arr[$key]['obssunat']=$v['bl_mensajesunat'];
									}
								}
								else
								{
									$arr[$key]['obssunat']=$v['bl_mensaje'];
								}
							}
							
							$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);
							if ($v['bl_estadoregistro']=='L')
							{
								$arr[$key]['cant_reintento'] =  trim($v['reintento']);
							}
							else
							{
								$arr[$key]['cant_reintento'] =  trim($v['bl_reintento']);
							}
							
							$estado_documento=trim(strtoupper($v['estado_documento']));	
							$tipo_documentosunat=trim(strtoupper($v['nomb_tipodocumento']));
							$estado_documentosunat=$arr[$key]['nombreestadosunat'];
						}
					}
				}						
				endforeach;
			}

			$prm['lista_datosdocumento']=$arr;
			$prm['param1']=$prm_ruc_empr;
			$prm['param2']=$prm_razonsocialcliente;
			$prm['param3']=$prm_serie_numeroinicio;
			$prm['param4']=$prm_serie_numerofinal;
			if ($prm_cod_estdoc!='0'){ $prm['param5']=$estado_documento;}else{$prm['param5']='';}
			if ($prm_tipo_documentosunat!='0'){$prm['param11']=$tipo_documentosunat;} else{$prm['param11']='';}		
			if ($prm_estado_documentosunat!='0'){$prm['param12']=$estado_documentosunat;}else{$prm['param12']='';}
			
			$prm['param6']=$prm_fec_emisinicio;
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
			$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			$prm['datos_empresa']=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);	
			
			$this->load->view('reportes/comprobantes/comprobantes_listadogeneral',$prm);		
		}


		public function Cambiar_EstadoBorradorAdeclarar()
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
			$prm_tipodocumentoemisor='6';
			$prm_rucproveedor=trim($this->input->post('txt_RucEmpresa'));
			$prm_datosseleccionados=trim($this->input->post('txt_datosseleccionados'));

			$consulta =$this->Comprobante_model->Cambiar_EstadoBorradorAdeclarar($prm_tipodocumentoemisor,$prm_rucproveedor,$prm_datosseleccionados);

			if ($consulta['result']==1)
			{
				$result['status']=1;
			}
			else
			{
				$result['status']=0;
			}	

			echo json_encode($result);
			
		}
		

		public function Listar_DatosDocumentoModificar()
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
			$Config_ValorPrecio=$_SESSION['SES_MarcoTrabajo'][0]['conf_venta'];
			$prm_tipodocumentoemisor=6;
			$prm_numerodocumentoemisor=trim($this->input->post('txt_rucempresa'));		
			
			$txt_documentomodificar=trim($this->input->post('txt_documentomodificar'));
			$txt_documentomodificar=(str_replace(",","",$txt_documentomodificar));
			$datos_seleccionados=explode('-',$txt_documentomodificar);
			$prm_tipodedocumento=$datos_seleccionados[0];
			$prm_serienumero=$datos_seleccionados[1].'-'.$datos_seleccionados[2];
			
			$valor_igv=$this->Usuarioinicio_model->Get_Valor_IGV();	
			$valor_otroscargos=$this->Usuarioinicio_model->Get_Valor_OtrosCargos();	

			$consulta =$this->Comprobante_model->Listar_DatosDocumentoModificar($prm_tipodocumentoemisor,$prm_numerodocumentoemisor,$prm_tipodedocumento,$prm_serienumero);
			
		//print_r($consulta);
		//return;
			$ultima_fecha='';
			$primera_fecha='';
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			
			$arr['tipodocumentoemisor'] =$consulta[0]['tipodocumentoemisor']; 
			$arr['numerodocumentoemisor'] =$consulta[0]['numerodocumentoemisor']; 
			$arr['tipodocumento'] =$consulta[0]['tipodocumento']; 
			$documento=explode('-',$consulta[0]['serienumero']);				
			$arr['seriedocumento'] =$documento[0];
			$arr['numerodocumento'] =$documento[1];
			$arr['correoemisor'] =$consulta[0]['correoemisor'];
			$arr['correoadquiriente'] =$consulta[0]['correoadquiriente'];
			$arr['razonsocialemisor'] =$consulta[0]['razonsocialemisor'];
			$arr['nombrecomercialemisor'] =$consulta[0]['nombrecomercialemisor'];
			

			$prm_fec_emisiniciotmp=explode('-',$consulta[0]['fechaemision']);
			$arr['fechaemision'] =($prm_fec_emisiniciotmp[2].'/'.$prm_fec_emisiniciotmp[1].'/'.$prm_fec_emisiniciotmp[0]);
			//print_r($arr['fechaemision']);
			
			$arr['ubigeoemisor'] =$consulta[0]['ubigeoemisor'];
			$arr['direccionemisor'] =$consulta[0]['direccionemisor'];
			$arr['urbanizacion'] =$consulta[0]['urbanizacion'];
			$arr['provinciaemisor'] =$consulta[0]['provinciaemisor'];
			$arr['departamentoemisor'] =$consulta[0]['departamentoemisor'];
			$arr['distritoemisor'] =$consulta[0]['distritoemisor'];
			$arr['paisemisor'] =$consulta[0]['paisemisor'];
			$arr['numerodocumentoadquiriente'] =$consulta[0]['numerodocumentoadquiriente'];
			$arr['tipodocumentoadquiriente'] =$consulta[0]['tipodocumentoadquiriente'];
			$arr['razonsocialadquiriente'] =$consulta[0]['razonsocialadquiriente'];				
			$arr['tipomoneda'] =$consulta[0]['tipomoneda'];
			
			$arr['totalvalorventanetoopgravadas'] =$consulta[0]['totalvalorventanetoopgravadas'];
			$arr['totalvalorventanetoopnogravada'] =$consulta[0]['totalvalorventanetoopnogravada'];
			$arr['totalvalorventanetoopexonerada'] =$consulta[0]['totalvalorventanetoopexonerada'];
			$arr['totalvalorventanetoopgratuitas'] =$consulta[0]['totalvalorventanetoopgratuitas'];			
			$arr['totaligv'] =$consulta[0]['totaligv'];
			$arr['totaldescuentos'] =$consulta[0]['totaldescuentos'];
			
			//$arr['totalOtrosCargos'] =$consulta[0]['totalOtrosCargos'];
			$arr['totalventa'] =$consulta[0]['totalventa'];
			$arr['textoleyenda_1'] =$consulta[0]['textoleyenda_1'];
			$arr['codigoleyenda_1'] =$consulta[0]['codigoleyenda_1'];
			$arr['bl_estadoregistro'] =$consulta[0]['bl_estadoregistro'];
			
			$arr['totaldetraccion'] =$consulta[0]['totaldetraccion'];
			$arr['valorreferencialdetraccion'] =$consulta[0]['valorreferencialdetraccion'];
			$arr['porcentajedetraccion'] =$consulta[0]['porcentajedetraccion'];
			$arr['descripciondetraccion'] =$consulta[0]['descripciondetraccion'];
			$arr['textoleyenda_2'] =$consulta[0]['textoleyenda_2'];
			$arr['codigoleyenda_2'] =$consulta[0]['codigoleyenda_2'];
			
			$arr['descuentosglobales'] =$consulta[0]['descuentosglobales'];
			$arr['inhabilitado'] =$consulta[0]['inhabilitado'];				
			$arr['textoleyenda_3'] =$consulta[0]['textoleyenda_3'];
			$arr['codigoleyenda_3'] =$consulta[0]['codigoleyenda_3'];
			
			$arr['porcentajepercepcion'] =$consulta[0]['porcentajepercepcion'];
			$arr['baseimponiblepercepcion'] =$consulta[0]['baseimponiblepercepcion'];
			$arr['totalpercepcion'] =$consulta[0]['totalpercepcion'];
			$arr['totalventaconpercepcion'] =$consulta[0]['totalventaconpercepcion'];
			
			$arr['codigoserienumeroafectado'] =$consulta[0]['codigoserienumeroafectado'];
			$arr['serienumeroafectado'] =$consulta[0]['serienumeroafectado'];
			$arr['motivodocumento'] =$consulta[0]['motivodocumento'];
			$arr['tipodocumentoreferenciaprincip'] =$consulta[0]['tipodocumentoreferenciaprincip'];
			$arr['numerodocumentoreferenciaprinc'] =$consulta[0]['numerodocumentoreferenciaprinc'];
			$arr['tipodocumentoreferenciacorregi'] =$consulta[0]['tipodocumentoreferenciacorregi'];
			$arr['numerodocumentoreferenciacorre'] =$consulta[0]['numerodocumentoreferenciacorre'];
			
			$arr['bl_origen'] =$consulta[0]['bl_origen'];			
			
			//Calculo de las restricciones de fechas de emision
			$tmpserie='';
			$tmpnumero=(int)$arr['numerodocumento']; 

			//Obtiene fecha inicial de rango permitido
			$tmpserie='';
			$tmpserie=$arr['seriedocumento'].'-'.str_pad((string)($tmpnumero-1), 8, "0", STR_PAD_LEFT);
			$consulta_fechainicio =$this->Comprobante_model->Buscar_Documento($prm_tipodocumentoemisor,$prm_numerodocumentoemisor,$prm_tipodedocumento,$tmpserie);
			if(!empty($consulta_fechainicio))//SI NO ES NULO O VACIO
			{
				$prm_fec_emisinicio_tmp=explode('-',$consulta_fechainicio[0]['fechaemision']);
				$primera_fecha=($prm_fec_emisinicio_tmp[2].'/'.$prm_fec_emisinicio_tmp[1].'/'.$prm_fec_emisinicio_tmp[0]);
			}else
			{
				$primera_fecha='';
			}
			$arr['primera_fecha'] =$primera_fecha;

			//Obtiene fecha final de rango permitido
			$tmpserie='';
			$tmpserie=$arr['seriedocumento'].'-'.str_pad((string)($tmpnumero+1), 8, "0", STR_PAD_LEFT);
			$consulta_fecha =$this->Comprobante_model->Buscar_Documento($prm_tipodocumentoemisor,$prm_numerodocumentoemisor,$prm_tipodedocumento,$tmpserie);//$prm_serienumero);
			
			if(!empty($consulta_fecha))//SI NO ES NULO O VACIO
			{
				$prm_fec_emisinicio_tmp=explode('-',$consulta_fecha[0]['fechaemision']);
				$ultima_fecha=($prm_fec_emisinicio_tmp[2].'/'.$prm_fec_emisinicio_tmp[1].'/'.$prm_fec_emisinicio_tmp[0]);
			}else
			{
				$ultima_fecha=date("d/m/Y");
			}
			$arr['ultima_fecha'] =$ultima_fecha;
			

			$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
			$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();

			//Inicia: Carga de datos de adicionales
			//$cantidad_adicionales=0;
			//$cantidad_adicionales =$consulta[0]['textoAuxiliar500_5'];
			//print_r('Cantidad de adicionales'.$cantidad_adicionales);
			//INICIA: Registro del detalle en la tabla temporal de productos
			//Eliminamos los registros anteriores de productos para la empresa y el usuario
			$this->Comprobante_model->Eliminar_ProductotemporalRegistros($prm_cod_empr,$prm_cod_usu);		
			$prm_cod_tipregist=$this->Catalogos_model->Tipo_AfectacionModificar($consulta[0]['codigorazonexoneracion']);			
			$arr['cod_tipregist'] =$prm_cod_tipregist;
			
			foreach($consulta as $key=>$v):
				//REGISTRANDO LOS PRODUCTOS
				$prm_cod_prod=trim($v['codigoproducto']);
			$prm_cant_prod=trim($v['cantidad']);
			$prm_uni_med=trim($v['unidadmedida']);
			$prm_desc_prod=trim($v['descripcion']);
			$prm_val_unitario=trim($v['importeunitariosinimpuesto']);
			if ($v['importedescuento']=='')
			{
				$prm_val_descuento='0';
			}
			else
			{
				$prm_val_descuento=trim($v['importedescuento']);
			}
			$prm_val_isc='0.00';
			$prm_tip_afectacion=trim($v['codigorazonexoneracion']); 
			$prm_val_igv=trim($v['importeigv']);
			$prm_val_total=trim($v['importetotalsinimpuesto']);	
				//$valor_igv
				//textoAuxiliar40_2

			$prm_val_txt_preciocobro=trim($v['textoAuxiliar40_2']);	
				//este calculo es cuando valor venta
			$prm_val_descuento_inc_igv=number_format(($prm_val_descuento*(1+$valor_igv/100+$valor_otroscargos/100)),0,'.','');

			$prm_ruc_empr=trim($v['numerodocumentoemisor']);
			
			
				if ($prm_cod_tipregist==3)//EXPORTACION GRATUITAS
				{
					$prm_val_igv=0;
					$prm_val_unitario=trim($v['importereferencial']);
				}
				$this->Comprobante_model->Guardar_Registroproductos($prm_cod_usu,$prm_cod_prod,$prm_cant_prod,$prm_uni_med,
					$prm_desc_prod,$prm_val_unitario,$prm_val_descuento,$prm_val_isc,$prm_tip_afectacion,$prm_val_igv,
					$prm_val_total,$prm_cod_empr,$prm_ruc_empr,
					$prm_cod_tipregist, $prm_val_txt_preciocobro, $prm_val_descuento_inc_igv);
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
		
		public function Reiniciar_Correlativos()
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
			$prm_datosseleccionados_estado=trim($this->input->post('txt_datosseleccionados_estado'));
			$consulta =$this->Comprobante_model->Reiniciar_Correlativos($prm_ruc_empr,$prm_datosseleccionados_estado);
			if ($consulta['result']==1)
			{
				$result['status']=1;	
			}
			else
			{
				$result['status']=0;
			}		
			echo json_encode($result);
		}
		
		public function Listar_DatosAdicionales()
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
			$consulta =$this->Comprobante_model->Listar_DatosAdicionales($prm_ruc_empr);
			
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;
			$arr[$key]['nro_secuencia'] = $Contador;
			$arr[$key]['Codigo'] = trim($v['codigo']); 
			$arr[$key]['Observacion'] = trim($v['observacion']);
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



