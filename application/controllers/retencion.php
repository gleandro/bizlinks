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

			$consulta =$this->Retencion_model->Listar_DetalleDocumento($prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero);

			/*BUSCANDO LAS CARACTERISTICAS*/
		/*$direccioncliente='';
		$detallecaracteristica=$this->Retencion_model->Buscar_DetalleCaracteristica('6',$prm_ruc_empremisor,$prm_tipo_documento,$prm_serie_numero,'direccionAdquiriente');
		return;
		if(!empty($detallecaracteristica))//SI NO ES NULO O VACIO
		{
			$direccioncliente =  $detallecaracteristica[0]['valor'];
		}*/
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;
			$arr[$key]['razonsocialemisor'] =strtoupper(trim($v['razonsocialemisor'])); 
			$arr[$key]['nombrecomercialemisor'] =trim($v['nombrecomercialemisor']); 
			$arr[$key]['direccionemisor'] =trim($v['direccionemisor']);
			$arr[$key]['urbanizacionEmisor'] =trim($v['urbanizacionemisor']); 
			$arr[$key]['distritoemisor'] =trim($v['distritoemisor']); 
			$arr[$key]['provinciaemisor'] =trim($v['provinciaemisor']); 
			$arr[$key]['departamentoemisor'] =trim($v['departamentoemisor']); 
			$arr[$key]['numerodocumentoemisor'] =trim($v['numerodocumentoemisor']); 
			$arr[$key]['tipodocumentoemisor'] =trim($v['tipodocumentoemisor']); 
			$arr[$key]['nombre_tipodocumentoemisor'] =trim($v['nombre_tipodocumentoemisor']); 
			$arr[$key]['tasaretencion'] =trim($v['tasaretencion']);

			$arr[$key]['serienumero'] =trim($v['serienumeroretencion']); 
			$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
			$arr[$key]['nombre_tipodocumento'] =trim($v['nombre_tipodocumento']);
			$arr[$key]['fechaemision'] = trim($v['fechaemision']);		
				//me quede en validar AQUI
			$arr[$key]['numerodocumentoadquiriente'] =trim($v['numerodocumentoproveedor']); 
			$arr[$key]['razonsocialadquiriente'] =trim($v['razonsocialproveedor']); 	
				//$arr[$key]['direccioncliente'] =$direccioncliente; //ojo aqui ya se considera directo
			$arr[$key]['direccioncliente'] =trim($v['direccionproveedor']);						
				//$arr[$key]['textoleyenda_1'] =trim($v['textoleyenda_1']);				
			if ($v['tipomonedadocumentorelacionado']=='PEN')
			{
				$arr[$key]['tipomonedacabecera'] =trim($v['tipomonedadocumentorelacionado']).' - Sol'; 
			}
			else
			{
				$arr[$key]['tipomonedacabecera'] =trim($v['tipomonedadocumentorelacionado']).' - Dolar';
			}
			$arr[$key]['tipomoneda'] =trim($v['tipomonedadocumentorelacionado']);

			$arr[$key]['numeroordenitem'] =$Contador; 
			$arr[$key]['tipo_doc'] =trim($v['tipo_doc']); 
			$arr[$key]['numerodocumentorelacionado'] =trim($v['numerodocumentorelacionado']); 
			$arr[$key]['fechaemisiondocumentorelaciona'] =trim($v['fechaemisiondocumentorelaciona']); 
			$arr[$key]['fechapago'] =trim($v['fechapago']); 
			$arr[$key]['numeropago'] =trim($v['numeropago']); 
			$arr[$key]['importetotaldocumentorelaciona'] =trim($v['importetotaldocumentorelaciona']); 
			$arr[$key]['importepagosinretencion'] =trim($v['importepagosinretencion']); 
			$arr[$key]['importeretenido'] =trim($v['importeretenido']); 
			$arr[$key]['importetotalpagarneto'] =trim($v['importetotalpagarneto']); 


				//Pie del detalle
			$arr[$key]['importetotalpagado'] =trim($v['importetotalpagado']); 
			$arr[$key]['importetotalretenido'] =trim($v['importetotalretenido']); 

/*
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
*/		
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
		//if (!isset($_GET['param8'])){	$prm_tipo_documentosunat='';} else{$prm_tipo_documentosunat=$_GET['param8'];}	
		if (!isset($_GET['param9'])){	$prm_estado_documentosunat='';} else{$prm_estado_documentosunat=$_GET['param9'];}		
		if (!isset($_GET['param10'])){	$prm_datosbuscar='';} else{$prm_datosbuscar=$_GET['param10'];}
		if (!isset($_GET['param11'])){	$prm_razonsocialcliente='';} else{$prm_razonsocialcliente=$_GET['param11'];}
		//if (!isset($_GET['param12'])){	$prm_tipomoneda='';} else{$prm_tipomoneda=$_GET['param12'];}
		//se agrgo variable para RETENCIONES
		$prm_estado_doc = 'RETENCIONES';

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
		/*
$consulta =$this->Retencion_model->Listar_Retenciones($prm_ruc_empr,$prm_documento_cliente,$prm_serie_numeroinicio,
			$prm_serie_numerofinal,$prm_cod_estdoc,$prm_fec_emisinicio,$prm_fec_emisfinal,
			$prm_tipo_documentosunat,$prm_estado_documentosunat,$prm_tipomoneda, $prm_razonsocialcliente);
			*/
			$consulta =$this->Retencion_model->Listar_Retenciones($prm_ruc_empr,$prm_documento_cliente,$prm_serie_numeroinicio,
				$prm_serie_numerofinal,$prm_cod_estdoc,$prm_fec_emisinicio,$prm_fec_emisfinal,
				$prm_estado_documentosunat, $prm_razonsocialcliente);

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


						$arr[$key]['razonsocialadquiriente'] =strtoupper(trim($v['razonsocialproveedor'])); 
						$arr[$key]['tipodocumento'] =trim($v['tipodocumento']); 
						$arr[$key]['nomb_tipodocumento'] =trim($prm_estado_doc);				
						$arr[$key]['serienumero'] =trim($v['serienumeroretencion']);
						$arr[$key]['tipomoneda'] =  trim(strtoupper($v['tipomonedapagado']));
						$arr[$key]['totalventa'] =  number_format(trim($v['importetotalretenido']),2,'.',','); 
						$arr[$key]['fechaemision'] = trim($v['fechaemision']);				
						$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
						$arr[$key]['estado_documento'] =  trim(strtoupper($v['estadoregistro']));					
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

							//$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);	

							if ($v['bl_estadoregistro']=='L')
							{
								$arr[$key]['cant_reintento'] =  trim($v['reintento']);
							}
							else
							{
								$arr[$key]['cant_reintento'] =  trim($v['bl_reintento']);
							}	
							$estado_documento=trim(strtoupper($v['estadoregistro']));
							$tipo_documentosunat=trim(strtoupper($prm_estado_doc));
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
			//if ($prm_tipo_documentosunat!='0'){$prm['param11']=$tipo_documentosunat;} else{$prm['param11']='';}		
			//if ($prm_estado_documentosunat!='0'){$prm['param12']=$estado_documentosunat;}else{$prm['param12']='';}

			$prm['param6']=$prm_fec_emisinicio;
			$prm['param7']=$prm_fec_emisfinal;

			//$prm['param8']=date('d/m/Y h:i:s');
			if ($prm_datosbuscar=='')
			{
				$prm['param9']='LISTADO GENERAL DE LAS RETENCIONES';
			}
			else
			{
				$prm['param9']='LISTADO SELECCIONADO DE LAS RETENCIONES';
			}		
			$prm['param10']=$prm_datosbuscar;
			$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			$prm['datos_empresa']=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);	
			$this->load->view('reportes/retenciones/retenciones_listadogeneral',$prm);		
		}

	}



