<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retencion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Retencion_model');
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
			$this->load->view('usuario/login'); 
		}
		else
		{
			$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
			$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
			$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();		
			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste()){
				$prm_cod_empr=0;
			}else{
				$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			}
			$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
			if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
			{
				$prm['Ruc_Empresa']=$Listar_EmpresaId[0]['ruc_empr'];	
				$prm['Razon_Social']=$Listar_EmpresaId[0]['raz_social'];
				$prm['Tipo_confserie']=$Listar_EmpresaId[0]['tipo_confserie'];
			}else{
				$prm['Ruc_Empresa']='';	
				$prm['Razon_Social']='';
				$prm['Tipo_confserie']='';
			}
			
			$prm['Listar_EstadoDocumento']=$this->Catalogos_model->Listar_EstadoDocumento();
			//Obtener estados sunat bajo la condicion si es inHouse o no
			$Listar_Parametros=$this->Catalogos_model->Listar_Parametros();
			if(!empty($Listar_Parametros))//SI NO ES NULO O VACIO
			{
				$prm['Valor_Inhouse']=$Listar_Parametros[0]['is_inhouse'];
			}else{
				$prm['Valor_Inhouse']=0;
			}
			if ($prm['Valor_Inhouse']==0)
			{
				$prm['Listar_EstadoSunatRetencion']=$this->Catalogos_model->Listar_EstadoSunatRetencion_NoIn();
			}
			else{
				$prm['Listar_EstadoSunatRetencion']=$this->Catalogos_model->Listar_EstadoSunatRetencion();
			}
			
			//$Listar_Empresa=$this->Empresa_model->Listar_Empresa($this->Usuarioinicio_model->Get_Cod_UsuAdm());
			//$prm['Listar_Empresa']=$Listar_Empresa;			
			//$prm['Listar_TipodeDocumento']=$this->Catalogos_model->Listar_TipodeDocumento();
			//$prm['Config_ValorPrecio']=$_SESSION['SES_MarcoTrabajo'][0]['conf_venta'];		
			//$prm['Listar_Unidades']=$this->Catalogos_model->Datos_Unidades($prm_cod_empr,$prm_tipo_confunidad);
			//if (!isset($_GET['param1'])){	$prm_documentomodificar='';} else{$prm_documentomodificar=$_GET['param1'];}
			//$prm['documentomodificar']=$prm_documentomodificar;
			
			$prm['pagina_ver']='retencion';
			$this->load->view('retencion/retencion_listar',$prm);
		}		
	}
	
	public function Guardar_RetentionHeader()
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
		$prm_correoemisor=trim($this->input->post('txt_emisorcorreo'));//='yessica.prad@bizlinks.la';
		$prm_correoadquiriente=trim($this->input->post('txt_correocliente'));
		$prm_numerodocumentoemisor=trim($this->input->post('txt_RucEmpresa'));
		$prm_tipodocumentoemisor='6';
		$prm_tipodocumento=trim($this->input->post('cmb_tipodocumentosunat'));
		$prm_razonsocialemisor=trim($this->input->post('txt_RazonSocialEmpresa'));
		$prm_nombrecomercialemisor=trim($this->input->post('txt_RazonSocialEmpresa'));
		$prm_seriedocumento=trim($this->input->post('txt_seriedocumento'));
		$prm_correlativodocumento=trim($this->input->post('txt_correlativodocumento'));


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

		$prm_observacion=trim($this->input->post('txt_observacion'));


		$datosproveedor='';
		//DATOS PROVEEDOR
		
		$prm_numerodocumentoproveedor=trim($this->input->post('txt_numerodocumentoproveedor'));
		$prm_tipodocumentoproveedor='6';
		$prm_nombrecomercialproveedor=trim($this->input->post('txt_razonsocialproveedor'));
		$prm_razonsocialproveedor=trim($this->input->post('txt_razonsocialproveedor'));
		$prm_ubigeoproveedor=trim($this->input->post('txt_proveedorubigeo'));
		$prm_urbanizacionproveedor=trim($this->input->post('txt_proveedorurbanizacion'));
		$prm_provinciaproveedor=trim($this->input->post('txt_proveedorprovincia'));
		$prm_departamentoproveedor=trim($this->input->post('txt_proveedordepartamento'));
		$prm_distritoproveedor=trim($this->input->post('txt_proveedordistrito'));
		$prm_direccionproveedor=trim($this->input->post('txt_proveedordireccion'));

		$prm_codigopaisproveedor=trim($this->input->post('txt_proveedorpaiscodigo'));

		//FIN DATOS PROVEEDOR
		
		$datosproveedor=array(
			'numeroDocumentoProveedor'=>$prm_numerodocumentoproveedor,
			'tipoDocumentoProveedor'=>$prm_tipodocumentoproveedor,
			'nombreComercialProveedor'=>$prm_nombrecomercialproveedor,
			'ubigeoProveedor'=>$prm_ubigeoproveedor,
			'direccionProveedor'=>$prm_direccionproveedor,
			'urbanizacionProveedor'=>$prm_urbanizacionproveedor,
			'provinciaProveedor'=>$prm_provinciaproveedor,
			'departamentoProveedor'=>$prm_departamentoproveedor,
			'distritoProveedor'=>$prm_distritoproveedor,
			'codigoPaisProveedor'=>$prm_codigopaisproveedor,
			'razonSocialProveedor'=>$prm_razonsocialproveedor
			);

		
		
		
		$prm_tipodocumentoadquiriente=trim($this->input->post('cmb_tipodocumentocliente'));
		
		
		$prm_total_retenido=trim($this->input->post('txt_importe_retenido_footer'));
		$prm_total_retenido=number_format(trim($prm_total_retenido), 2, '.', '');

		$prm_total_pagar=trim($this->input->post('txt_importetotal_pagar_footer'));
		$prm_total_pagar=number_format(trim($prm_total_pagar), 2, '.', '');

		$prm_tipo_registro=trim($this->input->post('tipo_registro'));
		$prm_moneda=trim($this->input->post('txt_moneda_final'));
		//Req. V2. Campos Adicionales
		/*
		$prm_adicionalCantidad=trim($this->input->post('arr_adicional_Cantidad'));
		$prm_adicionalCodigo=($this->input->post('arr_adicional_Codigo'));
		$prm_adicionalValor=($this->input->post('arr_adicional_Valor'));
		$prm_adicionalCampos = array("100_3","100_4","100_5","100_6","100_7","100_8","100_9","100_10","40_3","40_4","40_5","40_6",
			"40_7","40_8","40_9","40_10","40_11","40_12","40_13","40_14","40_15","40_16","40_17","40_18",
			"40_19","40_20","500_1","500_2","500_3","500_4","500_5","250_1","250_2","250_3","250_4","250_5",
			"250_6","250_7","250_8","250_9","250_10","250_11","250_12","250_13","250_14","250_15","250_16",
			"250_17","250_18","250_19","250_20","250_21","250_22","250_23","250_24","250_25");
		*/
		//print_r($temp);
		//return;
			$consulta =$this->Retencion_model->Guardar_RetentionHeader(
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
				$prm_correlativodocumento,
				$prm_fechaemision,
				$prm_ubigeoemisor,
				$prm_direccionemisor,
				$prm_urbanizacion,
				$prm_provinciaemisor,
				$prm_departamentoemisor,
				$prm_distritoemisor,
				$prm_paisemisor,
				$prm_numerodocumentoproveedor,
				$prm_tipodocumentoproveedor,
				$prm_nombrecomercialproveedor,
				$prm_ubigeoproveedor,
				$prm_direccionproveedor,
				$prm_urbanizacionproveedor,
				$prm_provinciaproveedor,
				$prm_departamentoproveedor,
				$prm_distritoproveedor,
				$prm_codigopaisproveedor,
				$prm_razonsocialproveedor,
				$prm_total_retenido,
				$prm_total_pagar,
				$datosproveedor,
				$prm_observacion,
				$prm_tipo_registro,
				$prm_moneda
				);

		//print_r($consulta);
			if ($consulta['result']==1)
			{
				$result['status']=1;
				$result['numero']=$consulta['numero'];
			//$result['codigo_baja']=$prm_resumenid;	
			}
		else if ($consulta['result']==2)//EXISTE EL DOCUMENTO
		{
			$result['status']=2;
			$result['numero']=$consulta['numero'];
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

	public function receptor_retencionlistar()
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
			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste()){
				$prm_cod_empr=0;
			}else{
				$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			}
			$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);
			if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
			{
				$prm['Ruc_Empresa']=$Listar_EmpresaId[0]['ruc_empr'];	
				$prm['Razon_Social']=$Listar_EmpresaId[0]['raz_social'];
				$prm['Tipo_confserie']=$Listar_EmpresaId[0]['tipo_confserie'];
			}else{
				$prm['Ruc_Empresa']='';	
				$prm['Razon_Social']='';
				$prm['Tipo_confserie']='';
			}
			
			$prm['Listar_EstadoDocumento']=$this->Catalogos_model->Listar_EstadoDocumento();
			//Obtener estados sunat bajo la condicion si es inHouse o no
			$Listar_Parametros=$this->Catalogos_model->Listar_Parametros();
			if(!empty($Listar_Parametros))//SI NO ES NULO O VACIO
			{
				$prm['Valor_Inhouse']=$Listar_Parametros[0]['is_inhouse'];
			}else{
				$prm['Valor_Inhouse']=0;
			}
			if ($prm['Valor_Inhouse']==0)
			{
				$prm['Listar_EstadoSunatRetencion']=$this->Catalogos_model->Listar_EstadoSunatRetencion_NoIn_Receptor();
			}
			else{
				$prm['Listar_EstadoSunatRetencion']=$this->Catalogos_model->Listar_EstadoSunatRetencion_Receptor();
			}
			
			$prm['pagina_ver']='retencion';
			$this->load->view('retencion/receptor_retencionlistar',$prm);
		}		
	}
	
	public function Listar_Retenciones()
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
		
		$prm_serie_numeroinicio=trim($this->input->post('txt_serienumeroinicio'));
		$prm_serie_numerofinal=trim($this->input->post('txt_serienumerofinal'));
		$prm_cod_estdoc=trim($this->input->post('Cmb_EstadoDocumento'));
		$prm_estado_documentosunat=trim($this->input->post('Cmb_EstadoDocumentoSunat'));
		$prm_razonsocialcliente=trim($this->input->post('txt_RazonSocialCliente'));
		
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
			$prm_fec_emisfinal=$prm_fec_emisfinaltmp[2].'-'.$prm_fec_emisfinaltmp[1].'-'.$prm_fec_emisfinaltmp[0];
		}
		
		$prm_documento_cliente=trim($this->input->post('txt_DocumentoCliente'));
		if (substr($prm_documento_cliente,0,1)=='E')
		{
			$prm_documento_cliente='-';
		}
		
		$consulta =$this->Retencion_model->Listar_Retenciones($prm_ruc_empr,$prm_documento_cliente,$prm_serie_numeroinicio,
			$prm_serie_numerofinal,$prm_cod_estdoc,$prm_fec_emisinicio,$prm_fec_emisfinal,
			$prm_estado_documentosunat,$prm_razonsocialcliente);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				if ($prm_estado_documentosunat=='0')
				{
					if ($prm_documento_cliente=='-')
					{
						$posicion = strpos($prm_razonsocialcliente,$v['razonsocialproveedor']);
						if($posicion !== FALSE)	
						{
							$Contador=$Contador+1;
							$arr[$key]['nro_secuencia'] = $Contador;
							$arr[$key]['razonsocialproveedor'] =strtoupper(trim($v['razonsocialproveedor'])); 
							$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
							$arr[$key]['serienumeroretencion'] =trim($v['serienumeroretencion']); 
							$arr[$key]['importetotalpagado'] =  trim($v['importetotalpagado']);
							$arr[$key]['tipomonedapagado'] =  trim(strtoupper($v['tipomonedapagado']));
							$arr[$key]['importetotalretenido'] =  trim($v['importetotalretenido']);
							$arr[$key]['tipomonedaretenido'] =  trim(strtoupper($v['tipomonedaretenido']));
							$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
							$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
							$arr[$key]['estado_documento'] =  trim(strtoupper($v['estadoregistro']));
							$arr[$key]['estadosunat'] =  trim($v['estadosunat']);
							$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
							$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);						
							$arr[$key]['visualizado'] =  trim($v['visualizado']);
							if ( $v['mensajeresponse']=='1'){
								$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
							}else{
								if ($v['bl_estadoregistro']=='E') //ERROR LOCAL
								{	//TRAER LOS DATOS DEL ERROR
									$Listar_ErrorDocumento=$this->Retencion_model->Listar_ErrorDocumento($prm_ruc_empr,$v['tipodocumentoemisor'],$v['tipodocumento'],$v['serieNumeroRetencion']);
									if(!empty($Listar_ErrorDocumento))//SI NO ES NULO O VACIO
									{
										$arr[$key]['obssunat'] =  $Listar_ErrorDocumento[0]['descripcionerror'];
									}else{
										$arr[$key]['obssunat'] =  '';
									}						
								}else{
									if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
									{
										if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
										{
											$arr[$key]['obssunat']='';
										}else{
											$arr[$key]['obssunat']=$v['bl_mensajesunat'];
										}
									}else{
										$arr[$key]['obssunat']=$v['bl_mensaje'];
									}
								}
							}
						}//fin posicion
					}else//si documento cliente es distinto de -
					{
						$Contador=$Contador+1;
						$arr[$key]['nro_secuencia'] = $Contador;
						$arr[$key]['razonsocialproveedor'] =strtoupper(trim($v['razonsocialproveedor'])); 
						$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
						$arr[$key]['serienumeroretencion'] =trim($v['serienumeroretencion']); 
						$arr[$key]['importetotalpagado'] =  trim($v['importetotalpagado']);
						$arr[$key]['tipomonedapagado'] =  trim(strtoupper($v['tipomonedapagado']));
						$arr[$key]['importetotalretenido'] =  trim($v['importetotalretenido']);
						$arr[$key]['tipomonedaretenido'] =  trim(strtoupper($v['tipomonedaretenido']));
						$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
						$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
						$arr[$key]['estado_documento'] =  trim(strtoupper($v['estadoregistro']));
						$arr[$key]['estadosunat'] =  trim($v['estadosunat']);
						$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
						$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);						
						$arr[$key]['visualizado'] =  trim($v['visualizado']);
						if ( $v['mensajeresponse']=='1'){
							$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
						}else{
							if ($v['bl_estadoregistro']=='E') //ERROR LOCAL
							{	//TRAER LOS DATOS DEL ERROR
								$Listar_ErrorDocumento=$this->Retencion_model->Listar_ErrorDocumento($prm_ruc_empr,$v['tipodocumentoemisor'],$v['tipodocumento'],$v['serienumeroretencion']);
								if(!empty($Listar_ErrorDocumento))//SI NO ES NULO O VACIO
								{
									$arr[$key]['obssunat'] =  $Listar_ErrorDocumento[0]['descripcionerror'];
								}else{
									$arr[$key]['obssunat'] =  '';
								}						
							}else{
								if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
								{
									if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
									{
										$arr[$key]['obssunat']='';
									}else{
										$arr[$key]['obssunat']=$v['bl_mensajesunat'];
									}
								}else{
									$arr[$key]['obssunat']=$v['bl_mensaje'];
								}
							}
						}
					}
				}else //SIGNIFICA QUE SI SELECCIONO UN TIPO DE ESTADO SUNAT
				{
					if ($prm_documento_cliente=='-')
					{
						$posicion = strpos($prm_razonsocialcliente,$v['razonsocialproveedor']);
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
								$Contador=$Contador+1;
								$arr[$key]['nro_secuencia'] = $Contador;
								$arr[$key]['razonsocialproveedor'] =strtoupper(trim($v['razonsocialproveedor'])); 
								$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
								$arr[$key]['serienumeroretencion'] =trim($v['serienumeroretencion']); 
								$arr[$key]['importetotalpagado'] =  trim($v['importetotalpagado']);
								$arr[$key]['tipomonedapagado'] =  trim(strtoupper($v['tipomonedapagado']));
								$arr[$key]['importetotalretenido'] =  trim($v['importetotalretenido']);
								$arr[$key]['tipomonedaretenido'] =  trim(strtoupper($v['tipomonedaretenido']));
								$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
								$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
								$arr[$key]['estado_documento'] =  trim(strtoupper($v['estadoregistro']));
								$arr[$key]['estadosunat'] =  trim($v['estadosunat']);
								$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
								$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);						
								$arr[$key]['visualizado'] =  trim($v['visualizado']);
								if ( $v['mensajeresponse']=='1'){
									$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
								}else{
									if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
									{
										if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
										{
											$arr[$key]['obssunat']='';
										}else{
											$arr[$key]['obssunat']=$v['bl_mensajesunat'];
										}
									}else{
										$arr[$key]['obssunat']=$v['bl_mensaje'];
									}
								}
								if ($v['bl_estadoregistro']=='L')
								{
									$arr[$key]['cant_reintento'] =  trim($v['reintento']);
								}else{
									$arr[$key]['cant_reintento'] =  trim($v['bl_reintento']);
								}
							}
						}//fin posicion razonsocialproveedor
					}else
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
							$Contador=$Contador+1;
							$arr[$key]['nro_secuencia'] = $Contador;
							$arr[$key]['razonsocialproveedor'] =strtoupper(trim($v['razonsocialproveedor'])); 
							$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
							$arr[$key]['serienumeroretencion'] =trim($v['serienumeroretencion']); 
							$arr[$key]['importetotalpagado'] =  trim($v['importetotalpagado']);
							$arr[$key]['tipomonedapagado'] =  trim(strtoupper($v['tipomonedapagado']));
							$arr[$key]['importetotalretenido'] =  trim($v['importetotalretenido']);
							$arr[$key]['tipomonedaretenido'] =  trim(strtoupper($v['tipomonedaretenido']));
							$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
							$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
							$arr[$key]['estado_documento'] =  trim(strtoupper($v['estadoregistro']));
							$arr[$key]['estadosunat'] =  trim($v['estadosunat']);
							$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
							$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);						
							$arr[$key]['visualizado'] =  trim($v['visualizado']);
							if ( $v['mensajeresponse']=='1'){
								$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
							}else{
								if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
								{
									if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
									{
										$arr[$key]['obssunat']='';
									}else{
										$arr[$key]['obssunat']=$v['bl_mensajesunat'];
									}
								}else{
									$arr[$key]['obssunat']=$v['bl_mensaje'];
								}
							}
							if ($v['bl_estadoregistro']=='L')
							{
								$arr[$key]['cant_reintento'] =  trim($v['reintento']);
							}else{
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
			}else{
				$result['status']=0;
				$result['data']="";
			}
			echo json_encode($result);
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
							$this->Retencion_model->Actualizar_VistaDocumento($prm_ruc_emisor,$codigo_documento[0],($codigo_documento[1].'-'.$codigo_documento[2]));
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
				$this->load->library('my_pdfconcat'); 
				$pdf = new PDFMerger;
				foreach($listadearchivos as $key1=>$v1):
					$pdf->addPDF($v1, 'all');
				endforeach;
				$fecha_actual=((date("Y-m-d H-i-s")).'.'.substr(microtime(),0,5)*1000);
				
				if ($contador==1)
				{
					$pdf->merge('download', $prm_ruc_emisor.'-'.$fecha_actual.'.pdf');
				}else
				{
					$pdf->merge('download', $prm_ruc_emisor.'-'.$fecha_actual.'.pdf');
				}
			}
		}
		
		public function Listar_DetalleDocumento()
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
		//print_r('INICIA');
			$consulta =$this->Retencion_model->Listar_DetalleDocumento($prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero);
			
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;
			$arr[$key]['razonsocialemisor'] =strtoupper(trim($v['razonsocialemisor'])); 
			$arr[$key]['nombrecomercialemisor'] =trim($v['nombrecomercialemisor']); 
			$arr[$key]['direccionemisor'] =trim($v['direccionemisor']);
			if (trim($v['urbanizacionemisor'])<>'-')
			{
				$arr[$key]['urbanizacionemisor'] =' - '.trim($v['urbanizacionemisor']); 
			}else{
				$arr[$key]['urbanizacionemisor'] =''; 
			}
			$arr[$key]['distritoemisor'] =trim($v['distritoemisor']); 
			$arr[$key]['provinciaemisor'] =trim($v['provinciaemisor']); 
			$arr[$key]['departamentoemisor'] =trim($v['departamentoemisor']); 
			$arr[$key]['numerodocumentoemisor'] =trim($v['numerodocumentoemisor']); 
			$arr[$key]['tipodocumentoemisor'] =trim($v['tipodocumentoemisor']); 
			$arr[$key]['nombre_tipodocumentoemisor'] =trim($v['nombre_tipodocumentoemisor']); 
			
			$arr[$key]['serienumero'] =trim($v['serienumeroretencion']); 
			$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
			$arr[$key]['nombre_tipodocumento'] =trim($v['nombre_tipodocumento']);
			
			$arr[$key]['fechaemision'] = trim($v['fechaemision']);		
			$arr[$key]['numerodocumentoadquiriente'] =trim($v['numerodocumentoproveedor']); 
			$arr[$key]['razonsocialadquiriente'] =trim($v['razonsocialproveedor']); 	
			$arr[$key]['direccioncliente'] =trim($v['direccionproveedor']); 
			$arr[$key]['tasaretencion'] =trim($v['tasaretencion']); 
			
			$arr[$key]['importetotalretenido'] =trim($v['importetotalretenido']); 
			$arr[$key]['tipomonedaretenido'] =trim($v['tipomonedaretenido']); 
			$arr[$key]['importetotalpagado'] =trim($v['importetotalpagado']);
			$arr[$key]['tipomonedapagado'] =trim($v['tipomonedapagado']); 
			
				//detalle
			$arr[$key]['numeroordenitem'] =trim($v['numeroordenitem']); 
			$arr[$key]['tipodocumentorelacionado'] =trim($v['tipodocumentorelacionado']); 
			$arr[$key]['nomb_tipodocumento'] =trim($v['nomb_tipodocumento']); 
			
			$arr[$key]['numerodocumentorelacionado'] =trim($v['numerodocumentorelacionado']); 
			$arr[$key]['fechaemisiondocumentorelaciona'] =trim($v['fechaemisiondocumentorelaciona']); 
			$arr[$key]['fechapago'] =trim($v['fechapago']); 
			$arr[$key]['numeropago'] =trim($v['numeropago']); 
			$arr[$key]['tipomonedadocumentorelacionado'] =trim($v['tipomonedadocumentorelacionado']); 
			$arr[$key]['tipomonedarelacionado'] =trim($v['tipomonedarelacionado']); 				
			$arr[$key]['importetotaldocumentorelaciona'] =trim($v['importetotaldocumentorelaciona']); 
			$arr[$key]['importepagosinretencion'] =trim($v['importepagosinretencion']); 
			$arr[$key]['importeretenido'] =trim($v['importeretenido']); 
			$arr[$key]['importetotalpagarneto'] =trim($v['importetotalpagarneto']); 
			$arr[$key]['bl_estadoregistro'] =trim($v['bl_estadoregistro']); 
			$arr[$key]['estadoregistro'] =trim($v['estadoregistro']); 
			
			endforeach;
			
			if($_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion']==2)//SOLO SI ES RECEPTOR SE ACTUALIZA LOS DATOS
			{
				$this->Retencion_model->Actualizar_VistaDocumento($prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero);
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

		$consulta =$this->Retencion_model->Listar_RetencionesDocumento($prm_cod_usu,$prm_cod_empr);
		
		$importeretenido_footer=0;	
		$importetotal_footer=0; 	

		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;
			$arr[$key]['nro_secuencia'] = $Contador;
			$arr[$key]['tmp_ret'] = trim($v['tmp_ret']); 
			$arr[$key]['cod_usu'] =trim($v['cod_usu']); 
			$arr[$key]['cod_empr'] =  trim($v['cod_empr']);
			$arr[$key]['tipo_doc'] =  trim($v['tipo_doc']);
			$arr[$key]['num_doc'] =  trim($v['num_doc']);
			$arr[$key]['fec_emision'] =  trim($v['fec_emision']);
			$arr[$key]['fec_pago'] =  trim($v['fec_pago']);
			$arr[$key]['num_pago'] =  trim($v['num_pago']);
			$arr[$key]['moneda_origen'] =  trim($v['moneda_origen']);
			$arr[$key]['imp_origen'] =  number_format(trim($v['imp_origen']),2,'.',','); 
			$arr[$key]['imp_pago_sin_ret'] =  number_format(trim($v['imp_pago_sin_ret']),2,'.',','); 
			$arr[$key]['imp_retenido'] =  number_format(trim($v['imp_retenido']),2,'.',',');
			$arr[$key]['imp_total_pagar'] =  number_format(trim($v['imp_total_pagar']),2,'.',','); 

			$importeretenido_footer=$importeretenido_footer+$v['imp_retenido'];
			$importetotal_footer=$importetotal_footer+$v['imp_total_pagar'];

			endforeach;
		}

		$variable['importeretenido_footer']=number_format($importeretenido_footer,2,'.',',');
		$variable['importetotal_footer']=number_format($importetotal_footer,2,'.',',');

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

	public function registroretencion()
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
			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste()){
				$prm_cod_empr=0;
			}else{
				$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			}
			$prm['valor_igv']=$this->Usuarioinicio_model->Get_Valor_IGV();	
			$prm['valor_otroscargos']=$this->Usuarioinicio_model->Get_Valor_OtrosCargos();	
			$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);
			$Listar_Empresa=$this->Empresa_model->Listar_Empresa($this->Usuarioinicio_model->Get_Cod_UsuAdm());
			$prm['Listar_Empresa']=$Listar_Empresa;			
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$prm['Listar_TipodeDocumento']=$this->Catalogos_model->Listar_TipodeDocumento_Retencion();
			$prm['Listar_Monedas']=$this->Catalogos_model->Listar_Monedas();
			$prm['Config_ValorPrecio']=$_SESSION['SES_MarcoTrabajo'][0]['conf_venta'];			

			$Listar_EmpresaId=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);

			if(!empty($Listar_EmpresaId))//SI NO ES NULO O VACIO
			{
				$prm['Ruc_Empresa']=$Listar_EmpresaId[0]['ruc_empr'];	
				$prm['Razon_Social']=$Listar_EmpresaId[0]['raz_social'];
				$prm['Tipo_confserie']=$Listar_EmpresaId[0]['tipo_confserie'];
			}else{
				$prm['Ruc_Empresa']='';	
				$prm['Razon_Social']='';
				$prm['Tipo_confserie']='';
			}
			
			$prm['pagina_ver']='retencion/registroretencion';

			$this->Retencion_model->Eliminar_RetenciontemporalRegistros($prm_cod_empr,$prm_cod_usu);

			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
			{
				$prm_tipo_confunidad=0;
			}
			else
			{
				$prm_tipo_confunidad=$this->Usuarioinicio_model->Get_Tipo_ConfUnidad();
			}

			$prm['Listar_Unidades']=$this->Catalogos_model->Datos_Unidades($prm_cod_empr,$prm_tipo_confunidad);
			
			$this->load->view('retencion/registroretencion',$prm);
		}
	}

	public function Eliminar_Retenciontemporal()
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
		$consulta =$this->Retencion_model->Eliminar_Retenciontemporal($prm_tmp_prod);
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

	public function Guardar_Registroretenciones()
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
		$prm_cod_doc=trim($this->input->post('txt_codigo_documento'));
		$prm_tipo_doc=trim($this->input->post('txt_tipo_comprobante'));
		$prm_num_doc=trim($this->input->post('txt_numero_relacionado'));

		$prm_fechaemisiontmp=trim($this->input->post('txt_FechaEmision_Relacionado'));
		$prm_fechaemisiontmp=explode('/',$prm_fechaemisiontmp);
		$prm_fec_emision=($prm_fechaemisiontmp[2].'-'.$prm_fechaemisiontmp[1].'-'.$prm_fechaemisiontmp[0]);

		$prm_fechaemisiontmp=trim($this->input->post('txt_FechaPago'));
		$prm_fechaemisiontmp=explode('/',$prm_fechaemisiontmp);
		$prm_fec_pago=($prm_fechaemisiontmp[2].'-'.$prm_fechaemisiontmp[1].'-'.$prm_fechaemisiontmp[0]);

		$prm_num_pago=trim($this->input->post('txt_numero_pago'));
		$prm_moneda_origen=trim($this->input->post('txt_moneda'));
		$prm_imp_origen=trim($this->input->post('txt_importe_origen'));
		$prm_imp_pago_sin_ret=trim($this->input->post('txt_importepago_sin_retencion'));
		$prm_imp_retenido=trim($this->input->post('txt_importe_retenido'));
		$prm_imp_total_pagar=trim($this->input->post('txt_importetotal_pagar'));	

		
		//print_r($prm_val_descuento);
		if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
		{
			$prm_cod_empr=0;
		}
		else
		{
			$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();		
		}	
		$consulta =$this->Retencion_model->Guardar_Registroretenciones($prm_cod_usu,$prm_cod_empr,$prm_cod_doc,$prm_tipo_doc,$prm_num_doc,$prm_fec_emision,$prm_fec_pago,$prm_num_pago,$prm_moneda_origen,$prm_imp_origen,$prm_imp_pago_sin_ret,$prm_imp_retenido,$prm_imp_total_pagar);
		
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
	
	public function Crear_ArchivosDocumentoSeleccionado()
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

				foreach($listadearchivos as $key1=>$v1):
					$this->zip->read_file($v1.'.zip');				
				endforeach;
				
				$fecha_actual=((date("Y-m-d H-i-s")).'.'.substr(microtime(),0,5)*1000);
				$this->zip->download($prm_ruc_emisor.'-'.$fecha_actual.'.zip'); 
			}
		}
		
		public function Exportar_ExcelGeneral()
		{
			if(!$this->Usuarioinicio_model->SessionExiste())
			{
				$this->load->view('usuario/login');
				exit;
			}
			$Contador=0;
			
			if (!isset($_GET['param1'])){	$prm_ruc_empr='';} 				else{$prm_ruc_empr=$_GET['param1'];}
			if (!isset($_GET['param2'])){	$prm_documento_cliente='';} 	else{$prm_documento_cliente=$_GET['param2'];}
			if (!isset($_GET['param3'])){	$prm_serie_numeroinicio='';} 	else{$prm_serie_numeroinicio=$_GET['param3'];}
			if (!isset($_GET['param4'])){	$prm_serie_numerofinal='';} 	else{$prm_serie_numerofinal=$_GET['param4'];}
			if (!isset($_GET['param5'])){	$prm_cod_estdoc=0;} 			else{$prm_cod_estdoc=$_GET['param5'];}
			if (!isset($_GET['param6'])){	$prm_fec_emisiniciotmp='';} 	else{$prm_fec_emisiniciotmp=$_GET['param6'];}
			if (!isset($_GET['param7'])){	$prm_fec_emisfinaltmp='';} 		else{$prm_fec_emisfinaltmp=$_GET['param7'];}	
		//if (!isset($_GET['param8'])){	$prm_tipo_documentosunat='';} 	else{$prm_tipo_documentosunat=$_GET['param8'];}	
			if (!isset($_GET['param9'])){	$prm_estado_documentosunat='';} else{$prm_estado_documentosunat=$_GET['param8'];}		
			if (!isset($_GET['param10'])){	$prm_datosbuscar='';} 			else{$prm_datosbuscar=$_GET['param9'];}
			if (!isset($_GET['param11'])){	$prm_razonsocialcliente='';} 	else{$prm_razonsocialcliente=$_GET['param10'];}
		//if (!isset($_GET['param12'])){	$prm_tipomoneda='';} 		else{$prm_tipomoneda=$_GET['param12'];}
			
			if ($prm_fec_emisiniciotmp=='')
			{
				$prm_fec_emisinicio='';
			}else
			{
				$prm_fec_emisiniciotmp=explode('/',$prm_fec_emisiniciotmp);
				$prm_fec_emisinicio=($prm_fec_emisiniciotmp[2].'-'.$prm_fec_emisiniciotmp[1].'-'.$prm_fec_emisiniciotmp[0]);
			}

			if ($prm_fec_emisfinaltmp=='')
			{
				$prm_fec_emisfinal='';
			}else
			{
				$prm_fec_emisfinaltmp=explode('/',$prm_fec_emisfinaltmp);
				$prm_fec_emisfinal=$prm_fec_emisfinaltmp[2].'-'.$prm_fec_emisfinaltmp[1].'-'.$prm_fec_emisfinaltmp[0];
			}

			if (substr($prm_documento_cliente,0,1)=='E')
			{
				$prm_documento_cliente='-';
			}
			
			$arr=NULL;

			$consulta =$this->Retencion_model->Listar_Retenciones(
				$prm_ruc_empr,				$prm_documento_cliente,	$prm_serie_numeroinicio,
				$prm_serie_numerofinal,		$prm_cod_estdoc,		$prm_fec_emisinicio,
				$prm_fec_emisfinal,			$prm_estado_documentosunat,$prm_razonsocialcliente);

		//$estado_documento='';
		//$tipo_documentosunat='';
		//$estado_documentosunat='';

		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				if ($prm_estado_documentosunat=='0')
				{
					if ($prm_documento_cliente=='-')
					{
						$posicion = strpos($prm_razonsocialcliente,$v['razonsocialproveedor']);
						if($posicion !== FALSE)	
						{
							$Contador=$Contador+1;
							$arr[$key]['nro_secuencia'] = $Contador;
							$arr[$key]['razonsocialproveedor'] =strtoupper(trim($v['razonsocialproveedor'])); 
							$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
							$arr[$key]['serienumeroretencion'] =trim($v['serienumeroretencion']); 
							$arr[$key]['importetotalpagado'] =  trim($v['importetotalpagado']);
							$arr[$key]['tipomonedapagado'] =  trim(strtoupper($v['tipomonedapagado']));
							$arr[$key]['importetotalretenido'] =  trim($v['importetotalretenido']);
							$arr[$key]['tipomonedaretenido'] =  trim(strtoupper($v['tipomonedaretenido']));
							$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
							$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
							$arr[$key]['estado_documento'] =  trim(strtoupper($v['estadoregistro']));
							$arr[$key]['estadosunat'] =  trim($v['estadosunat']);
							$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
							$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);						
							$arr[$key]['visualizado'] =  trim($v['visualizado']);
							if ( $v['mensajeresponse']=='1'){
								$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
							}else{
								if ($v['bl_estadoregistro']=='E') //ERROR LOCAL
								{	//TRAER LOS DATOS DEL ERROR
									$Listar_ErrorDocumento=$this->Retencion_model->Listar_ErrorDocumento($prm_ruc_empr,$v['tipodocumentoemisor'],$v['tipodocumento'],$v['serieNumeroRetencion']);
									if(!empty($Listar_ErrorDocumento))//SI NO ES NULO O VACIO
									{
										$arr[$key]['obssunat'] =  $Listar_ErrorDocumento[0]['descripcionerror'];
									}else{
										$arr[$key]['obssunat'] =  '';
									}						
								}else{
									if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
									{
										if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
										{
											$arr[$key]['obssunat']='';
										}else{
											$arr[$key]['obssunat']=$v['bl_mensajesunat'];
										}
									}else{
										$arr[$key]['obssunat']=$v['bl_mensaje'];
									}
								}
							}
						}//fin posicion
					}else//si documento cliente es distinto de -
					{
						$Contador=$Contador+1;
						$arr[$key]['nro_secuencia'] = $Contador;
						$arr[$key]['razonsocialproveedor'] =strtoupper(trim($v['razonsocialproveedor'])); 
						$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
						$arr[$key]['serienumeroretencion'] =trim($v['serienumeroretencion']); 
						$arr[$key]['importetotalpagado'] =  trim($v['importetotalpagado']);
						$arr[$key]['tipomonedapagado'] =  trim(strtoupper($v['tipomonedapagado']));
						$arr[$key]['importetotalretenido'] =  trim($v['importetotalretenido']);
						$arr[$key]['tipomonedaretenido'] =  trim(strtoupper($v['tipomonedaretenido']));
						$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
						$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
						$arr[$key]['estado_documento'] =  trim(strtoupper($v['estadoregistro']));
						$arr[$key]['estadosunat'] =  trim($v['estadosunat']);
						$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
						$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);						
						$arr[$key]['visualizado'] =  trim($v['visualizado']);
						if ( $v['mensajeresponse']=='1'){
							$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
						}else{
							if ($v['bl_estadoregistro']=='E') //ERROR LOCAL
							{	//TRAER LOS DATOS DEL ERROR
								$Listar_ErrorDocumento=$this->Retencion_model->Listar_ErrorDocumento($prm_ruc_empr,$v['tipodocumentoemisor'],$v['tipodocumento'],$v['serienumeroretencion']);
								if(!empty($Listar_ErrorDocumento))//SI NO ES NULO O VACIO
								{
									$arr[$key]['obssunat'] =  $Listar_ErrorDocumento[0]['descripcionerror'];
								}else{
									$arr[$key]['obssunat'] =  '';
								}						
							}else{
								if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
								{
									if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
									{
										$arr[$key]['obssunat']='';
									}else{
										$arr[$key]['obssunat']=$v['bl_mensajesunat'];
									}
								}else{
									$arr[$key]['obssunat']=$v['bl_mensaje'];
								}
							}
						}
					}
				}else //SIGNIFICA QUE SI SELECCIONO UN TIPO DE ESTADO SUNAT
				{
					if ($prm_documento_cliente=='-')
					{
						$posicion = strpos($prm_razonsocialcliente,$v['razonsocialproveedor']);
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
								$Contador=$Contador+1;
								$arr[$key]['nro_secuencia'] = $Contador;
								$arr[$key]['razonsocialproveedor'] =strtoupper(trim($v['razonsocialproveedor'])); 
								$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
								$arr[$key]['serienumeroretencion'] =trim($v['serienumeroretencion']); 
								$arr[$key]['importetotalpagado'] =  trim($v['importetotalpagado']);
								$arr[$key]['tipomonedapagado'] =  trim(strtoupper($v['tipomonedapagado']));
								$arr[$key]['importetotalretenido'] =  trim($v['importetotalretenido']);
								$arr[$key]['tipomonedaretenido'] =  trim(strtoupper($v['tipomonedaretenido']));
								$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
								$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
								$arr[$key]['estado_documento'] =  trim(strtoupper($v['estadoregistro']));
								$arr[$key]['estadosunat'] =  trim($v['estadosunat']);
								$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
								$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);						
								$arr[$key]['visualizado'] =  trim($v['visualizado']);
								if ( $v['mensajeresponse']=='1'){
									$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
								}else{
									if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
									{
										if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
										{
											$arr[$key]['obssunat']='';
										}else{
											$arr[$key]['obssunat']=$v['bl_mensajesunat'];
										}
									}else{
										$arr[$key]['obssunat']=$v['bl_mensaje'];
									}
								}
								if ($v['bl_estadoregistro']=='L')
								{
									$arr[$key]['cant_reintento'] =  trim($v['reintento']);
								}else{
									$arr[$key]['cant_reintento'] =  trim($v['bl_reintento']);
								}
							}
						}//fin posicion razonsocialproveedor
					}else
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
							$Contador=$Contador+1;
							$arr[$key]['nro_secuencia'] = $Contador;
							$arr[$key]['razonsocialproveedor'] =strtoupper(trim($v['razonsocialproveedor'])); 
							$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
							$arr[$key]['serienumeroretencion'] =trim($v['serienumeroretencion']); 
							$arr[$key]['importetotalpagado'] =  trim($v['importetotalpagado']);
							$arr[$key]['tipomonedapagado'] =  trim(strtoupper($v['tipomonedapagado']));
							$arr[$key]['importetotalretenido'] =  trim($v['importetotalretenido']);
							$arr[$key]['tipomonedaretenido'] =  trim(strtoupper($v['tipomonedaretenido']));
							$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
							$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
							$arr[$key]['estado_documento'] =  trim(strtoupper($v['estadoregistro']));
							$arr[$key]['estadosunat'] =  trim($v['estadosunat']);
							$arr[$key]['nombreestadosunat']=strtoupper($this->Comprobante_model->Listar_EstadoDocumento($v['tipodocumento'],strtoupper($v['estadosunat'])));
							$arr[$key]['numerodocumentoemisor'] =  trim($v['numerodocumentoemisor']);						
							$arr[$key]['visualizado'] =  trim($v['visualizado']);
							if ( $v['mensajeresponse']=='1'){
								$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
							}else{
								if (empty($v['bl_mensaje']) or trim($v['bl_mensaje']==''))
								{
									if (empty($v['bl_mensajesunat']) or trim($v['bl_mensajesunat']==''))
									{
										$arr[$key]['obssunat']='';
									}else{
										$arr[$key]['obssunat']=$v['bl_mensajesunat'];
									}
								}else{
									$arr[$key]['obssunat']=$v['bl_mensaje'];
								}
							}
							if ($v['bl_estadoregistro']=='L')
							{
								$arr[$key]['cant_reintento'] =  trim($v['reintento']);
							}else{
								$arr[$key]['cant_reintento'] =  trim($v['bl_reintento']);
							}
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
			
			
			
		//if ($prm_tipo_documentosunat!='0'){$prm['param11']=$tipo_documentosunat;} else{$prm['param11']='';}		
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
			
			$rol_usuario=$_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion'];
		//print_r($prm['rol']);
		//return;
			if ($rol_usuario==1)
			{
				$prm['nombreArchivo']='FormatoListaRetenciones.xlsx';
				if ($prm_cod_estdoc!='0' ){ $prm['param5']=$estado_documento;}else{$prm['param5']='';}			
			}
			else{
				$prm['nombreArchivo']='FormatoListaRetenciones_Receptor.xlsx';
				$prm['param5']='';
			}
			$this->load->view('reportes/retenciones/retenciones_listadogeneral',$prm);
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
				}else{	
					$prm_montototal=$prm_montototaltmp['0'].'.'.$prm_montototaltmp['1'];
				}
			}else{
				$prm_montototal=$prm_montototaltmp['0'].'.00';
			}

			$prm_fechaemisiontmp=explode('/',trim($this->input->post('txt_fechaemision')));
			$prm_fechaemision=$prm_fechaemisiontmp[2].'-'.$prm_fechaemisiontmp[1].'-'.$prm_fechaemisiontmp[0];		
			$prm_rucproveedor=trim($this->input->post('text_rucproveedor'));
			
			$consulta =$this->Retencion_model->existe_comprobante($prm_tipodedocumento,$prm_serienumero,$prm_montototal,$prm_fechaemision,$prm_rucproveedor);

		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			$result['status']=$consulta[0]['cantidad'];
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

		$consulta =$this->Retencion_model->Listar_DetalleDocumento($prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;
			$arr[$key]['razonsocialemisor'] =strtoupper(trim($v['razonsocialemisor'])); 
			$arr[$key]['nombrecomercialemisor'] =trim($v['nombrecomercialemisor']); 
			$arr[$key]['direccionemisor'] =trim($v['direccionemisor']);
			if (trim($v['urbanizacionemisor'])<>'-')
			{
				$arr[$key]['urbanizacionemisor'] =' - '.trim($v['urbanizacionemisor']); 
			}else{
				$arr[$key]['urbanizacionemisor'] =''; 
			}
			$arr[$key]['distritoemisor'] =trim($v['distritoemisor']); 
			$arr[$key]['provinciaemisor'] =trim($v['provinciaemisor']); 
			$arr[$key]['departamentoemisor'] =trim($v['departamentoemisor']); 
			$arr[$key]['numerodocumentoemisor'] =trim($v['numerodocumentoemisor']); 
			$arr[$key]['tipodocumentoemisor'] =trim($v['tipodocumentoemisor']); 
			$arr[$key]['nombre_tipodocumentoemisor'] =trim($v['nombre_tipodocumentoemisor']); 
			
			$arr[$key]['serienumero'] =trim($v['serienumeroretencion']); 
			$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
			$arr[$key]['nombre_tipodocumento'] =trim($v['nombre_tipodocumento']);
			
			$arr[$key]['fechaemision'] = trim($v['fechaemision']);		
			$arr[$key]['numerodocumentoadquiriente'] =trim($v['numerodocumentoproveedor']); 
			$arr[$key]['razonsocialadquiriente'] =trim($v['razonsocialproveedor']); 	
			$arr[$key]['direccioncliente'] =trim($v['direccionproveedor']); 
			$arr[$key]['tasaretencion'] =trim($v['tasaretencion']); 
			
			$arr[$key]['importetotalretenido'] =trim($v['importetotalretenido']); 
			$arr[$key]['tipomonedaretenido'] =trim($v['tipomonedaretenido']); 
			$arr[$key]['importetotalpagado'] =trim($v['importetotalpagado']);
			$arr[$key]['tipomonedapagado'] =trim($v['tipomonedapagado']); 
			
				//detalle
			$arr[$key]['numeroordenitem'] =trim($v['numeroordenitem']); 
			$arr[$key]['tipodocumentorelacionado'] =trim($v['tipodocumentorelacionado']); 
			$arr[$key]['nomb_tipodocumento'] =trim($v['nomb_tipodocumento']); 
			
			$arr[$key]['numerodocumentorelacionado'] =trim($v['numerodocumentorelacionado']); 
			$arr[$key]['fechaemisiondocumentorelaciona'] =trim($v['fechaemisiondocumentorelaciona']); 
			$arr[$key]['fechapago'] =trim($v['fechapago']); 
			$arr[$key]['numeropago'] =trim($v['numeropago']); 
			$arr[$key]['tipomonedadocumentorelacionado'] =trim($v['tipomonedadocumentorelacionado']); 
			$arr[$key]['tipomonedarelacionado'] =trim($v['tipomonedarelacionado']); 				
			$arr[$key]['importetotaldocumentorelaciona'] =trim($v['importetotaldocumentorelaciona']); 
			$arr[$key]['importepagosinretencion'] =trim($v['importepagosinretencion']); 
			$arr[$key]['importeretenido'] =trim($v['importeretenido']); 
			$arr[$key]['importetotalpagarneto'] =trim($v['importetotalpagarneto']); 
			$arr[$key]['bl_estadoregistro'] =trim($v['bl_estadoregistro']); 
			$arr[$key]['estadoregistro'] =trim($v['estadoregistro']); 
			
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
	
}



