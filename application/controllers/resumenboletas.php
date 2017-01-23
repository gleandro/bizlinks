<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resumenboletas extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Resumenboletas_model');
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
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			$prm['Listar_TipodeDocumento']=$this->Resumenboletas_model->Listar_TipodeDocumento();

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
			//BORRAMOS LOS DATOS DEL USUARIO EN LA TABLA TEMPORAL
			$this->Resumenboletas_model->Eliminar_Resumenboletas($prm_cod_empr,$prm_cod_usu);
			$prm['pagina_ver']='resumenboletas';

			$this->load->view('resumenboletas/resumenboletas',$prm);
		}
	}


	public function ListaResumenBoletas()
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
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);

			//Obtener estados sunat bajo la condicion su es inHouse o no
			$Listar_Parametros=$this->Catalogos_model->Listar_Parametros();
			if(!empty($Listar_Parametros))//SI NO ES NULO O VACIO
			{
				$prm['Valor_Inhouse']=$Listar_Parametros[0]['is_inhouse'];
			}
			else{
				$prm['Valor_Inhouse']=0;
			}
			if ($prm['Valor_Inhouse']==0)
			{
				$prm['Listar_EstadoSunatResumen']=$this->Catalogos_model->Listar_EstadoSunatResumen_NoIn();
			}
			else{
				$prm['Listar_EstadoSunatResumen']=$this->Catalogos_model->Listar_EstadoSunatResumen();
			}

			//$prm['Listar_EstadoDocumento']=$this->Catalogos_model->Listar_EstadoDocumento();
			//Nuevo Req. No se considera estado Borrador para las listas de resumenes
			$prm['Listar_EstadoDocumento']=$this->Catalogos_model->Listar_EstadoDocumentoResumenes();

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
			$prm['pagina_ver']='resumenboletas/listaresumenboletas';

			$this->load->view('resumenboletas/listaresumenboletas',$prm);
		}
	}



	public function Listar_DocumentosdeResumen()
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

		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		//tipo de busqueda 0 - 1
		$prm_ruc_empr=trim($this->input->post('txt_RucEmpresa'));
		$prm_fechabusquedatmp=trim($this->input->post('txt_FechaEmision'));
		$codigo_temp=trim($this->input->post('codigo'));

		if ($prm_fechabusquedatmp=='')
		{
			$prm_fechabusqueda='';
		}
		else
		{
			$prm_fechabusquedatmp=explode('/',$prm_fechabusquedatmp);
			$prm_fechabusqueda=$prm_fechabusquedatmp[2].'-'.$prm_fechabusquedatmp[1].'-'.$prm_fechabusquedatmp[0];
		}

		$consulta =$this->Resumenboletas_model->Listar_DocumentosdeResumen($prm_cod_usu,$prm_cod_empr,$prm_ruc_empr,$prm_fechabusqueda);

		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				
				if($codigo_temp == 1){

					if($v['bl_estadoproceso'] == 'SIGNED' || !$v['bl_estadoproceso']){

						$Contador=$Contador+1;
						$arr[$key]['nro_secuencia'] = $Contador;
						$arr[$key]['tmp_reg'] = trim($v['tmp_reg']);
						$arr[$key]['tipo_doc'] =trim($v['tip_doc']);
						$arr[$key]['numer_doc'] =  trim($v['num_doc']);

						$arr[$key]['fec_emision'] =  trim($v['fec_emision']);
						$arr[$key]['tip_docemisor'] =  trim($v['tip_docemisor']);
						$arr[$key]['tip_reg'] =  trim($v['tip_reg']);
						$arr[$key]['bl_estadoproceso'] =  trim($v['bl_estadoproceso']);

						$arr[$key]['moneda'] =  trim($v['fec_emision']);
						$valor=0;if(trim($v['op_gravado'])==''){ $valor=0; }else{$valor=$v['op_gravado'];}
						$arr[$key]['op_gravado'] =  number_format( $valor ,2,'.',',');
						$valor=0;if(trim($v['igv'])==''){ $valor=0; }else{$valor=$v['igv'];}
						$arr[$key]['igv'] =   number_format($valor,2,'.',',');
						$valor=0;if(trim($v['op_exonerado'])==''){ $valor=0; }else{$valor=$v['op_exonerado'];}
						$arr[$key]['op_exonerado'] = number_format($valor,2,'.',',');
						$valor=0;if(trim($v['op_nogravado'])==''){ $valor=0; }else{$valor=$v['op_nogravado'];}
						$arr[$key]['op_nogravado'] =  number_format($valor,2,'.',',');
						$valor=0;if(trim($v['op_gratis'])==''){ $valor=0; }else{$valor=$v['op_gratis'];}
						$arr[$key]['op_gratis'] =   number_format($valor,2,'.',',');
						$valor=0;if(trim($v['imp_total'])==''){ $valor=0; }else{$valor=$v['imp_total'];}
						$arr[$key]['imp_total'] =  number_format($valor,2,'.',',');
						$arr[$key]['est_sunat'] =  '';
						$arr[$key]['est_declarar'] =  trim($v['est_declarar']);
						$arr[$key]['cod_tipdoc'] =  trim($v['cod_tipdoc']);
						$arr[$key]['ruc_emisor'] = trim($prm_ruc_empr);
					}
				}
				else{

					$Contador=$Contador+1;
					$arr[$key]['nro_secuencia'] = $Contador;
					$arr[$key]['tmp_reg'] = trim($v['tmp_reg']);
					$arr[$key]['tipo_doc'] =trim($v['tip_doc']);
					$arr[$key]['numer_doc'] =  trim($v['num_doc']);

					$arr[$key]['fec_emision'] =  trim($v['fec_emision']);
					$arr[$key]['tip_docemisor'] =  trim($v['tip_docemisor']);
					$arr[$key]['tip_reg'] =  trim($v['tip_reg']);
					$arr[$key]['bl_estadoproceso'] =  trim($v['bl_estadoproceso']);

					$arr[$key]['moneda'] =  trim($v['fec_emision']);
					$valor=0;if(trim($v['op_gravado'])==''){ $valor=0; }else{$valor=$v['op_gravado'];}
					$arr[$key]['op_gravado'] =  number_format( $valor ,2,'.',',');
					$valor=0;if(trim($v['igv'])==''){ $valor=0; }else{$valor=$v['igv'];}
					$arr[$key]['igv'] =   number_format($valor,2,'.',',');
					$valor=0;if(trim($v['op_exonerado'])==''){ $valor=0; }else{$valor=$v['op_exonerado'];}
					$arr[$key]['op_exonerado'] = number_format($valor,2,'.',',');
					$valor=0;if(trim($v['op_nogravado'])==''){ $valor=0; }else{$valor=$v['op_nogravado'];}
					$arr[$key]['op_nogravado'] =  number_format($valor,2,'.',',');
					$valor=0;if(trim($v['op_gratis'])==''){ $valor=0; }else{$valor=$v['op_gratis'];}
					$arr[$key]['op_gratis'] =   number_format($valor,2,'.',',');
					$valor=0;if(trim($v['imp_total'])==''){ $valor=0; }else{$valor=$v['imp_total'];}
					$arr[$key]['imp_total'] =  number_format($valor,2,'.',',');
					$arr[$key]['est_sunat'] =  '';
					$arr[$key]['est_declarar'] =  trim($v['est_declarar']);
					$arr[$key]['cod_tipdoc'] =  trim($v['cod_tipdoc']);
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
				if($codigo_temp==1) {
					$result['cod']=1;
				}
			}
			echo json_encode($result);
		}

		public function Guardar_ResumenBoletas()
		{
			$result['status']=0;
			if(!$this->Usuarioinicio_model->SessionExiste())
			{
				$result['status']=1000;
				echo json_encode($result);
				exit;
			}
			$result['mensaje']='';

			$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
			$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			$prm_documento=trim($this->input->post('txt_datosseleccionados'));
			$prm_ruc_empr=trim($this->input->post('txt_RucEmpresa'));
			$prm_est_declarar=trim($this->input->post('tip_evento'));
			$prm_fec_doc=trim($this->input->post('txt_FechaEmision'));
			$prm_tip_docemisor=trim($this->input->post('txt_tipdocemisor'));
			$txt_fecemisiondoc=trim($this->input->post('txt_fecemisiondoc'));

			$fechadocumento=explode('/',$prm_fec_doc);
			$fecha_actual = strtotime('now');
			$fecha_entrada = strtotime($fechadocumento[2].'/'.$fechadocumento[1].'/'.$fechadocumento[0]);

			$dias	= (( $fecha_actual-$fecha_entrada)/86400);
			$dias 	= abs($dias);
			$dias = floor($dias);

			if ($dias>7)
			{
				$result['status']=0;
				$result['mensaje']='La fecha de emisi�n es inferior a 7 dias calendarios';
				echo json_encode($result);
				return;
			}

			if ($txt_fecemisiondoc!='')
			{
				if ($prm_fec_doc!=$txt_fecemisiondoc)
				{
					$result['status']=0;
					$result['mensaje']='No se puede agregar documentos de otra fecha';
					echo json_encode($result);
					return;
				}
			}
		//FALTA AGREGAR LAS CONDICIONES POR CADA EVENTO, SI ES DE BAJA SOLO Q DEBEN TENER UN ESTADO ESPECIFICO


			$consulta =$this->Resumenboletas_model->Guardar_ResumenBoletas($prm_cod_usu,$prm_cod_empr,$prm_documento,$prm_ruc_empr,$prm_est_declarar,$prm_fec_doc,$prm_tip_docemisor);

			if ($consulta['result']==1)
			{
				$result['status']=1;
				$result['mensaje']='';
			}
			echo json_encode($result);
		}

		public function Guardar_SummaryHeader()
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
			$result['codigo_resumen']='';

			$prm_numerodocumentoemisor=trim($this->input->post('txt_RucEmpresa'));
			$fecha_actual = explode('/',date("d/m/Y"));
			$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			$Buscar_CorrelativoDocumento=$this->Resumenboletas_model->Buscar_CorrelativoDocumento($prm_cod_empr,$fecha_actual[2].'-'.$fecha_actual[1].'-'.$fecha_actual[0]);
		//print_r($Buscar_CorrelativoDocumento);
			if (!empty($Buscar_CorrelativoDocumento))
			{
				$correlativo=$Buscar_CorrelativoDocumento[0]['valorentero'];
			}
			else
			{
				$correlativo=1;
			}
			$correlativo=str_pad(trim($correlativo),3, "0", STR_PAD_LEFT);
			$fecha_actual = explode('/',date("d/m/Y"));
			$prm_resumenid='RC-'.$fecha_actual[2].$fecha_actual[1].$fecha_actual[0].'-'.$correlativo;
			$prm_tipodocumentoemisor=trim($this->input->post('txt_tipdocemisor'));
			$prm_correoemisor=$this->Usuarioinicio_model->Get_Email_UsuAdm();
			$prm_fechaemisioncomprobante=trim($this->input->post('txt_fecemisiondoc'));
		//$prm_fechaemisioncomprobantetmp=explode('/',$prm_fechaemisioncomprobante);
		//$prm_fechaemisioncomprobante=($prm_fechaemisioncomprobantetmp[2].'-'.$prm_fechaemisioncomprobantetmp[1].'-'.$prm_fechaemisioncomprobantetmp[0]);

			$prm_fechageneracionresumen=($fecha_actual[2].'-'.$fecha_actual[1].'-'.$fecha_actual[0]);
			$prm_inhabilitado='1';
			$prm_razonsocialemisor=trim($this->input->post('txt_RazonSocialEmpresa'));
			$prm_resumentipo='RC';
			$prm_bl_estadoregistro='A';
			$prm_bl_reintento=0;
			$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
			$prm_ruc_empr=trim($this->input->post('txt_RucEmpresa'));

			$consulta =$this->Resumenboletas_model->Guardar_SummaryHeader(
				$prm_numerodocumentoemisor ,
				$prm_resumenid,
				$prm_tipodocumentoemisor,
				$prm_correoemisor,
				$prm_fechaemisioncomprobante,
				$prm_fechageneracionresumen,
				$prm_inhabilitado,
				$prm_razonsocialemisor,
				$prm_resumentipo,
				$prm_bl_estadoregistro,
				$prm_bl_reintento,
				$prm_cod_empr,
				$prm_cod_usu,
				$prm_ruc_empr
				);
			if ($consulta['result']==2)
			{
				$result['status']=2;
			}
			if ($consulta['result']==1)
			{
				$result['status']=1;
				$result['codigo_resumen']=$prm_resumenid;
			}
			else
			{
				$result['status']=0;
				$result['codigo_resumen']='';
			}
			echo json_encode($result);
		}

		public function Eliminar_DocumentoBoletaResumen()
		{
			$result['status']=0;
			if(!$this->Usuarioinicio_model->SessionExiste())
			{
				$result['status']=1000;
				echo json_encode($result);
				exit;
			}
			$prm_comprobante=trim($this->input->post('var_comprobante'));
			$prm_tipo_doc=trim($this->input->post('var_tipo_doc'));
			$prm_ruc=trim($this->input->post('var_ruc'));

			$consulta =$this->Resumenboletas_model->Eliminar_DocumentoBoletaResumen($prm_ruc,$prm_tipo_doc,$prm_comprobante);
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
			$consulta =$this->Resumenboletas_model->Reiniciar_Correlativos($prm_ruc_empr,$prm_datosseleccionados_estado);
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

		public function Listar_SummaryHeader()
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
			$prm_cod_resum=trim($this->input->post('txt_CodigoResumen'));

			$prm_fec_geniniciotmp=trim($this->input->post('txt_FechaGenInicio'));
			if ($prm_fec_geniniciotmp=='')
			{
				$prm_fec_geninicio='';
			}
			else
			{
				$prm_fec_geniniciotmp=explode('/',$prm_fec_geniniciotmp);
				$prm_fec_geninicio=($prm_fec_geniniciotmp[2].'-'.$prm_fec_geniniciotmp[1].'-'.$prm_fec_geniniciotmp[0]);
			}

			$prm_fec_genfinaltmp=trim($this->input->post('txt_FechaGenFinal'));
			if ($prm_fec_genfinaltmp=='')
			{
				$prm_fec_genfinal='';
			}
			else
			{
				$prm_fec_genfinaltmp=explode('/',$prm_fec_genfinaltmp);
				$prm_fec_genfinal=$prm_fec_genfinaltmp[2].'-'.$prm_fec_genfinaltmp[1].'-'.$prm_fec_genfinaltmp[0];
			}

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

			$prm_cod_estdoc=trim($this->input->post('Cmb_EstadoDocumento'));
			$prm_bl_estadoproceso=trim($this->input->post('Cmb_EstadoDocumentoSunat'));


			$consulta =$this->Resumenboletas_model->Listar_SummaryHeader($prm_ruc_empr,$prm_cod_resum,$prm_fec_geninicio,
				$prm_fec_genfinal,$prm_cod_estdoc,$prm_fec_emisinicio,$prm_fec_emisfinal,$prm_bl_estadoproceso);

		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				if ($prm_bl_estadoproceso=='0')
				{

					$arr[$key]['numerodocumentoemisor'] = trim($v['numerodocumentoemisor']);
					$arr[$key]['resumenid'] =trim($v['resumenid']);
					$arr[$key]['tipodocumentoemisor'] =  trim($v['tipodocumentoemisor']);
					$arr[$key]['correoemisor'] =  trim($v['correoemisor']);
					$arr[$key]['fechaemisioncomprobante'] =  trim($v['fechaemisioncomprobante']);
					$arr[$key]['fechageneracionresumen'] =  trim($v['fechageneracionresumen']);
					$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);
					$arr[$key]['razonsocialemisor'] =  trim($v['razonsocialemisor']);
					$arr[$key]['resumentipo'] =  trim($v['resumentipo']);
					$arr[$key]['estadoregistro'] =  strtoupper(trim($v['estadoregistro']));
					$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
					if ($v['bl_estadoregistro']=='L') //PASO A BIZLINK
					{
						$arr[$key]['bl_reintento'] =  trim($v['reintento']);
					}
					else
					{
						$arr[$key]['bl_reintento'] = trim($v['bl_reintento']);
					}
					$arr[$key]['estadosunat'] =  trim($v['estadosunat']);


					if ( $v['mensajeresponse']=='1')
					{
						$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
					}
					else
					{
						if ($v['bl_estadoregistro']=='E') //ERROR LOCAL
						{
							//TRAER LOS DATOS DEL ERROR
							$Listar_ErrorDocumento=$this->Resumenboletas_model->Listar_ErrorDocumento($prm_ruc_empr,$v['tipodocumentoemisor'],$v['resumenid']);
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

					$arr[$key]['bl_fecharespuestasunat'] =  trim($v['bl_fecharespuestasunat']);
					$arr[$key]['bl_fechaenviosunat'] =  trim($v['bl_fechaenviosunat']);
					$arr[$key]['nombreestadosunat']=strtoupper($this->Resumenboletas_model->Listar_EstadoDocumento(strtoupper($v['estadosunat'])));
				}
				else
				{
					$estadodocsunat=$v['estadosunat'];
					$posicion = strpos($estadodocsunat,'/');
					if($posicion !== FALSE)
					{
						$estadodocsunat=substr($estadodocsunat, -5);
					}
					$posicion = strpos($estadodocsunat,$prm_bl_estadoproceso);
					if($posicion !== FALSE)
					{
						$arr[$key]['numerodocumentoemisor'] = trim($v['numerodocumentoemisor']);
						$arr[$key]['resumenid'] =trim($v['resumenid']);
						$arr[$key]['tipodocumentoemisor'] =  trim($v['tipodocumentoemisor']);
						$arr[$key]['correoemisor'] =  trim($v['correoemisor']);
						$arr[$key]['fechaemisioncomprobante'] =  trim($v['fechaemisioncomprobante']);
						$arr[$key]['fechageneracionresumen'] =  trim($v['fechageneracionresumen']);
						$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);
						$arr[$key]['razonsocialemisor'] =  trim($v['razonsocialemisor']);
						$arr[$key]['resumentipo'] =  trim($v['resumentipo']);
						$arr[$key]['estadoregistro'] =  strtoupper(trim($v['estadoregistro']));
						$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
						if ($v['bl_estadoregistro']=='L') //PASO A BIZLINK
						{
							$arr[$key]['bl_reintento'] =  trim($v['reintento']);
						}
						else
						{
							$arr[$key]['bl_reintento'] = trim($v['bl_reintento']);
						}
						$arr[$key]['estadosunat'] =  trim($v['estadosunat']);

						if ( $v['mensajeresponse']=='1')
						{
							$arr[$key]['obssunat'] ='Pendiente de envio - Programado';
						}
						else
						{
							if ($v['bl_estadoregistro']=='E') //ERROR LOCAL
							{
								//TRAER LOS DATOS DEL ERROR
								$Listar_ErrorDocumento=$this->Resumenboletas_model->Listar_ErrorDocumento($prm_ruc_empr,$v['tipodocumentoemisor'],$v['resumenid']);
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
						$arr[$key]['bl_fecharespuestasunat'] =  trim($v['bl_fecharespuestasunat']);
						$arr[$key]['bl_fechaenviosunat'] =  trim($v['bl_fechaenviosunat']);
						$arr[$key]['nombreestadosunat']=strtoupper($this->Resumenboletas_model->Listar_EstadoDocumento(strtoupper($v['estadosunat'])));
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
			if (!isset($_GET['param2'])){	$prm_cod_resum='';} else{$prm_cod_resum=$_GET['param2'];}
			if (!isset($_GET['param3'])){	$prm_fec_geniniciotmp='';} else{$prm_fec_geniniciotmp=$_GET['param3'];}
			if (!isset($_GET['param4'])){	$prm_fec_genfinaltmp='';} else{$prm_fec_genfinaltmp=$_GET['param4'];}
			if (!isset($_GET['param5'])){	$prm_cod_estdoc=0;} else{$prm_cod_estdoc=$_GET['param5'];}
			if (!isset($_GET['param6'])){	$prm_fec_emisiniciotmp='';} else{$prm_fec_emisiniciotmp=$_GET['param6'];}
			if (!isset($_GET['param7'])){	$prm_fec_emisfinaltmp='';} else{$prm_fec_emisfinaltmp=$_GET['param7'];}
			if (!isset($_GET['param8'])){	$prm_datosbuscar='';} else{$prm_datosbuscar=$_GET['param8'];}
			if (!isset($_GET['param9'])){	$prm_bl_estadoproceso='';} else{$prm_bl_estadoproceso=$_GET['param9'];}



			if ($prm_fec_geniniciotmp=='')
			{
				$prm_fec_geninicio='';
			}
			else
			{
				$prm_fec_geniniciotmp=explode('/',$prm_fec_geniniciotmp);
				$prm_fec_geninicio=($prm_fec_geniniciotmp[2].'-'.$prm_fec_geniniciotmp[1].'-'.$prm_fec_geniniciotmp[0]);
			}
			if ($prm_fec_genfinaltmp=='')
			{
				$prm_fec_genfinal='';
			}
			else
			{
				$prm_fec_genfinaltmp=explode('/',$prm_fec_genfinaltmp);
				$prm_fec_genfinal=$prm_fec_genfinaltmp[2].'-'.$prm_fec_genfinaltmp[1].'-'.$prm_fec_genfinaltmp[0];
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
			if ($prm_fec_emisfinaltmp=='')
			{
				$prm_fec_emisfinal='';
			}
			else
			{
				$prm_fec_emisfinaltmp=explode('/',$prm_fec_emisfinaltmp);
				$prm_fec_emisfinal=$prm_fec_emisfinaltmp[2].'-'.$prm_fec_emisfinaltmp[1].'-'.$prm_fec_emisfinaltmp[0];
			}

		//$arr=NULL;
			$consulta =$this->Resumenboletas_model->Listar_SummaryHeader($prm_ruc_empr,$prm_cod_resum,$prm_fec_geninicio,$prm_fec_genfinal,$prm_cod_estdoc,$prm_fec_emisinicio,$prm_fec_emisfinal,$prm_bl_estadoproceso);


			$estado_documento='';
			$estado_documentosunat='';

			$arr=NULL;
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				if ($prm_bl_estadoproceso=='0')
				{
					$arr[$key]['numerodocumentoemisor'] = trim($v['numerodocumentoemisor']);
					$arr[$key]['resumenid'] =trim($v['resumenid']);
					$arr[$key]['tipodocumentoemisor'] =  trim($v['tipodocumentoemisor']);
					$arr[$key]['correoemisor'] =  trim($v['correoemisor']);
					$arr[$key]['fechaemisioncomprobante'] =  trim($v['fechaemisioncomprobante']);
					$arr[$key]['fechageneracionresumen'] =  trim($v['fechageneracionresumen']);
					$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);
					$arr[$key]['razonsocialemisor'] =  trim($v['razonsocialemisor']);
					$arr[$key]['resumentipo'] =  trim($v['resumentipo']);
					$arr[$key]['estadoregistro'] =  strtoupper(trim($v['estadoregistro']));
					$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
					if ($v['bl_estadoregistro']=='L') //PASO A BIZLINK
					{
						$arr[$key]['bl_reintento'] =  trim($v['reintento']);
					}
					else
					{
						$arr[$key]['bl_reintento'] = trim($v['bl_reintento']);
					}
					$arr[$key]['estadosunat'] =  trim($v['estadosunat']);


					if ($v['bl_estadoregistro']=='E') //ERROR LOCAL
					{
						//TRAER LOS DATOS DEL ERROR
						$Listar_ErrorDocumento=$this->Resumenboletas_model->Listar_ErrorDocumento($prm_ruc_empr,$v['tipodocumentoemisor'],$v['resumenid']);
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


					$arr[$key]['bl_fecharespuestasunat'] =  trim($v['bl_fecharespuestasunat']);
					$arr[$key]['bl_fechaenviosunat'] =  trim($v['bl_fechaenviosunat']);
					$arr[$key]['nombreestadosunat']=strtoupper($this->Resumenboletas_model->Listar_EstadoDocumento(strtoupper($v['estadosunat'])));

					$estado_documento=trim(strtoupper($v['estadoregistro']));
					$estado_documentosunat=$arr[$key]['nombreestadosunat'];

				}
				else
				{
					$estadodocsunat=$v['estadosunat'];
					$posicion = strpos($estadodocsunat,'/');
					if($posicion !== FALSE)
					{
						$estadodocsunat=substr($estadodocsunat, -5);
					}
					$posicion = strpos($estadodocsunat,$prm_bl_estadoproceso);
					if($posicion !== FALSE)
					{
						$arr[$key]['numerodocumentoemisor'] = trim($v['numerodocumentoemisor']);
						$arr[$key]['resumenid'] =trim($v['resumenid']);
						$arr[$key]['tipodocumentoemisor'] =  trim($v['tipodocumentoemisor']);
						$arr[$key]['correoemisor'] =  trim($v['correoemisor']);
						$arr[$key]['fechaemisioncomprobante'] =  trim($v['fechaemisioncomprobante']);
						$arr[$key]['fechageneracionresumen'] =  trim($v['fechageneracionresumen']);
						$arr[$key]['inhabilitado'] =  trim($v['inhabilitado']);
						$arr[$key]['razonsocialemisor'] =  trim($v['razonsocialemisor']);
						$arr[$key]['resumentipo'] =  trim($v['resumentipo']);
						$arr[$key]['estadoregistro'] =  strtoupper(trim($v['estadoregistro']));
						$arr[$key]['bl_estadoregistro'] =  trim($v['bl_estadoregistro']);
						if ($v['bl_estadoregistro']=='L') //PASO A BIZLINK
						{
							$arr[$key]['bl_reintento'] =  trim($v['reintento']);
						}
						else
						{
							$arr[$key]['bl_reintento'] = trim($v['bl_reintento']);
						}
						$arr[$key]['estadosunat'] =  trim($v['estadosunat']);


						if ($v['bl_estadoregistro']=='E') //ERROR LOCAL
						{
							//TRAER LOS DATOS DEL ERROR
							$Listar_ErrorDocumento=$this->Resumenboletas_model->Listar_ErrorDocumento($prm_ruc_empr,$v['tipodocumentoemisor'],$v['resumenid']);
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


						$arr[$key]['bl_fecharespuestasunat'] =  trim($v['bl_fecharespuestasunat']);
						$arr[$key]['bl_fechaenviosunat'] =  trim($v['bl_fechaenviosunat']);
						$arr[$key]['nombreestadosunat']=strtoupper($this->Resumenboletas_model->Listar_EstadoDocumento(strtoupper($v['estadosunat'])));

						$estado_documento=trim(strtoupper($v['estadoregistro']));
						$estado_documentosunat=$arr[$key]['nombreestadosunat'];
					}

				}
				endforeach;
			}


			$prm['lista_datosdocumento']=$arr;
			$prm['param1']=$prm_ruc_empr;
			$prm['param2']=$prm_cod_resum;
			$prm['param3']=$prm_fec_geninicio;
			$prm['param4']=$prm_fec_genfinal;
		//$prm['param5']=$prm_cod_estdoc;

			if ($prm_cod_estdoc!='0'){ $prm['param5']=$estado_documento;}else{$prm['param5']='';}
			if ($prm_bl_estadoproceso!='0'){$prm['param11']=$estado_documentosunat;}else{$prm['param11']='';}

			$prm['param6']=$prm_fec_emisinicio;
			$prm['param7']=$prm_fec_emisfinal;
			$prm['param8']=date('d/m/Y h:i:s');
			if ($prm_datosbuscar=='')
			{
				$prm['param9']='LISTADO GENERAL DE RESUMEN DE BOLETAS';
			}
			else
			{
				$prm['param9']='LISTADO SELECCIONADO DEL RESUMEN DE BOLETAS';
			}
			$prm['param10']=$prm_datosbuscar;



			$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
			$prm['datos_empresa']=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);

			$this->load->view('reportes/resumenboletas/resumenboletas_listadogeneral',$prm);
		}

		public function Descargar_ExcelDetalle()
		{
			if(!$this->Usuarioinicio_model->SessionExiste())
			{
				$this->load->view('usuario/login');
				exit;
			}
			if (!isset($_GET['param1'])){	$prm_ruc_empr='';} else{$prm_ruc_empr=$_GET['param1'];}
			if (!isset($_GET['param2'])){	$prm_resumenid='';} else{$prm_resumenid=$_GET['param2'];}

			$consulta =$this->Resumenboletas_model->Listar_SummaryHeaderDetalle($prm_ruc_empr,$prm_resumenid);

			$arr=NULL;
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$arr[$key]['numerodocumentoemisor'] =trim($v['numerodocumentoemisor']);
			$arr[$key]['resumenid'] =trim($v['resumenid']);
			$arr[$key]['tipodocumentoemisor'] =trim($v['tipodocumentoemisor']);
			$arr[$key]['correoemisor'] =trim($v['correoemisor']);
			$arr[$key]['fechaemisioncomprobante'] =trim($v['fechaemisioncomprobante']);
			$arr[$key]['fechageneracionresumen'] =trim($v['fechageneracionresumen']);
			$arr[$key]['inhabilitado'] =trim($v['inhabilitado']);
			$arr[$key]['razonsocialemisor'] =strtoupper(trim($v['razonsocialemisor']));
			$arr[$key]['resumentipo'] =trim($v['resumentipo']);
			$arr[$key]['bl_estadoregistro'] =trim($v['bl_estadoregistro']);
			$arr[$key]['bl_reintento'] =trim($v['bl_reintento']);
			$arr[$key]['bl_origen'] =trim($v['bl_origen']);
			$arr[$key]['bl_hasfileresponse'] =trim($v['bl_hasfileresponse']);
			$arr[$key]['bl_reintentojob'] =trim($v['bl_reintentojob']);
			$arr[$key]['bl_sourcefile'] =trim($v['bl_sourcefile']);
			$arr[$key]['visualizado'] =trim($v['visualizado']);
			$arr[$key]['inhabilitado'] =trim($v['inhabilitado']);

			$arr[$key]['numerofila'] =trim($v['numerofila']);
			$arr[$key]['numerocorrelativofin'] =trim($v['numerocorrelativofin']);
			$arr[$key]['numerocorrelativoinicio'] =trim($v['numerocorrelativoinicio']);
			$arr[$key]['seriegrupodocumento'] =trim($v['seriegrupodocumento']);
			$arr[$key]['tipodocumento'] =trim($v['tipodocumento']);
			$arr[$key]['tipomoneda'] =trim($v['tipomoneda']);

			$arr[$key]['totaligv'] =number_format(trim($v['totaligv']),2,'.',',');
			$arr[$key]['totalisc'] =number_format(trim($v['totalisc']),2,'.',',');
			$arr[$key]['totalotroscargos'] =number_format(trim($v['totalotroscargos']),2,'.',',');
			$arr[$key]['totalotrostributos'] =trim($v['totalotrostributos']);
			$arr[$key]['totalvalorventaopexoneradasigv'] =number_format(trim($v['totalvalorventaopexoneradasigv']),2,'.',',');
				//$arr[$key]['totalvalorventaopgratuitas'] =trim($v['totalvalorventaopgratuitas']);
			if (is_null($v['totalvalorventaopgratuitas']) or $v['totalvalorventaopgratuitas']=='')
			{
				$arr[$key]['totalvalorventaopgratuitas'] ='0.00';
			}
			else
			{
				$arr[$key]['totalvalorventaopgratuitas'] =number_format(trim($v['totalvalorventaopgratuitas']),2,'.',',');
			}
			$arr[$key]['totalvalorventaopgravadaconigv'] =number_format(trim($v['totalvalorventaopgravadaconigv']),2,'.',',');
			$arr[$key]['totalvalorventaopinafectasigv'] =number_format(trim($v['totalvalorventaopinafectasigv']),2,'.',',');
			$arr[$key]['totalventa'] =number_format(trim($v['totalventa']),2,'.',',');

			$arr[$key]['nombre_tipodocumento'] =trim($v['nombre_tipodocumento']);


			if ($v['tipomoneda']=='PEN')
			{
				$arr[$key]['tipomonedadoc'] ='Sol';
			}
			else
			{
				$arr[$key]['tipomonedadoc'] ='Dolar';
			}
			if ($v['estadosunat']!='')
			{
				$arr[$key]['nombreestadosunat']=strtoupper($this->Resumenboletas_model->Listar_EstadoDocumento(strtoupper($v['estadosunat'])));
			}
			else
			{
				$arr[$key]['nombreestadosunat']='';
			}

			endforeach;


		}

		//print_r($arr);
		//return;
		$prm['lista_datosdocumento']=$arr;
		$prm['param1']=$prm_resumenid;
		$prm['param2']=date('d/m/Y h:i:s');
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$prm['datos_empresa']=$this->Empresa_model->Listar_EmpresaId($prm_cod_empr);

		$this->load->view('reportes/resumenboletas/resumenboletas_detalle',$prm);
	}


	public function Listar_DetalleDocumento()
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

		$prm_ruc_empremisor=trim($this->input->post('txt_RucEmpresa'));

		$prm_datosseleccionados=trim($this->input->post('txt_datosseleccionados'));
		$prm_datosseleccionados=(str_replace(",","",$prm_datosseleccionados));
		//$datos_seleccionados=explode('-',$prm_datosseleccionados);
		//$prm_tipo_documento=$datos_seleccionados[0];
		$prm_serie_numero=$prm_datosseleccionados;//$datos_seleccionados[1].'-'.$datos_seleccionados[2];

		$consulta =$this->Resumenboletas_model->Listar_DetalleDocumento($prm_ruc_empremisor,$prm_serie_numero);


		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$Contador=$Contador+1;


			$arr[$key]['numerodocumentoemisor'] =trim($v['numerodocumentoemisor']);
			$arr[$key]['resumenid'] =trim($v['resumenid']);
			$arr[$key]['tipodocumentoemisor'] =trim($v['tipodocumentoemisor']);
			$arr[$key]['correoemisor'] =trim($v['correoemisor']);
			$arr[$key]['fechaemisioncomprobante'] =trim($v['fechaemisioncomprobante']);
			$arr[$key]['fechageneracionresumen'] =trim($v['fechageneracionresumen']);
			$arr[$key]['inhabilitado'] =trim($v['inhabilitado']);
			$arr[$key]['razonsocialemisor'] =strtoupper(trim($v['razonsocialemisor']));
			$arr[$key]['resumentipo'] =trim($v['resumentipo']);
			$arr[$key]['bl_estadoregistro'] =trim($v['bl_estadoregistro']);
			$arr[$key]['bl_reintento'] =trim($v['bl_reintento']);
			$arr[$key]['bl_origen'] =trim($v['bl_origen']);
			$arr[$key]['bl_hasfileresponse'] =trim($v['bl_hasfileresponse']);
			$arr[$key]['bl_reintentojob'] =trim($v['bl_reintentojob']);
			$arr[$key]['bl_sourcefile'] =trim($v['bl_sourcefile']);
			$arr[$key]['visualizado'] =trim($v['visualizado']);
			$arr[$key]['inhabilitado'] =trim($v['inhabilitado']);

			$arr[$key]['numerofila'] =trim($v['numerofila']);
			$arr[$key]['numerocorrelativofin'] =trim($v['numerocorrelativofin']);
			$arr[$key]['numerocorrelativoinicio'] =trim($v['numerocorrelativoinicio']);
			$arr[$key]['seriegrupodocumento'] =trim($v['seriegrupodocumento']);
			$arr[$key]['tipodocumento'] =trim($v['tipodocumento']);
			$arr[$key]['tipomoneda'] =trim($v['tipomoneda']);
			$arr[$key]['totaligv'] =number_format(trim($v['totaligv']),2,'.',',');
			$arr[$key]['totalisc'] =number_format(trim($v['totalisc']),2,'.',',');
			$arr[$key]['totalotroscargos'] =number_format(trim($v['totalotroscargos']),2,'.',',');
			$arr[$key]['totalotrostributos'] =trim($v['totalotrostributos']);
			$arr[$key]['totalvalorventaopexoneradasigv'] =number_format(trim($v['totalvalorventaopexoneradasigv']),2,'.',',');
			if (is_null($v['totalvalorventaopgratuitas']) or $v['totalvalorventaopgratuitas']=='')
			{
				$arr[$key]['totalvalorventaopgratuitas'] ='0.00';
			}
			else
			{
				$arr[$key]['totalvalorventaopgratuitas'] =number_format(trim($v['totalvalorventaopgratuitas']),2,'.',',');
			}
			$arr[$key]['totalvalorventaopgravadaconigv'] =number_format(trim($v['totalvalorventaopgravadaconigv']),2,'.',',');
			$arr[$key]['totalvalorventaopinafectasigv'] =number_format(trim($v['totalvalorventaopinafectasigv']),2,'.',',');
			$arr[$key]['totalventa'] =number_format(trim($v['totalventa']),2,'.',',');

			$arr[$key]['nombre_tipodocumento'] =trim($v['nombre_tipodocumento']);


			if ($v['tipomoneda']=='PEN')
			{
				$arr[$key]['tipomonedadoc'] ='Sol';
			}
			else
			{
				$arr[$key]['tipomonedadoc'] ='Dolar';
			}
			if ($v['estadosunat']!='')
			{
				$arr[$key]['nombreestadosunat']=strtoupper($this->Resumenboletas_model->Listar_EstadoDocumento(strtoupper($v['estadosunat'])));
			}
			else
			{
				$arr[$key]['nombreestadosunat']='';
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
		echo json_encode($result);
	}

	public function Crear_ArchivosDocumentoSeleccionado()//Crear_ArchivosDocumentoSeleccionado
	{
		//if (!isset($_GET['param1'])){	$prm_cod_documento='';} else{$prm_cod_documento=$_GET['param1'];}

		//$prm_cod_documento = basename($_GET['param1']);
		//$prm_ruc_emisor = basename($_GET['param2']);

		$result['status']=0;

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
					/*
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
					}
					else //El fichero $nombre_fichero no existe
					{
						$codigodocumento='el_archivo_no_existe.pdf';
						$src='././download/'.$codigodocumento;//base_url().'download/el_archivo_no_existe.pdf';
					}
					$this->zip->read_file($src);

					*/


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
						//print_r($listadearchivos);
						//return;
						$this->zip->archive($carpetadescarga.'/'.$nombrecarpetadoc.'.zip');
						$this->zip->clear_data();
						$contador++;
					}
					$cantidadbucle=0;
				}
				endforeach;
			//$this->zip->clear_data();
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

		function Declarar_Comprobante()
		{

			$result['status']=0;
			if(!$this->Usuarioinicio_model->SessionExiste())
			{
				$result['status']=1000;
				echo json_encode($result);
				exit;
			}

			$prm_comprobante=trim($this->input->post('var_comprobante'));
			$prm_tipo_doc=trim($this->input->post('var_tipo_doc'));
			$prm_ruc=trim($this->input->post('var_ruc'));


			$consulta =$this->Resumenboletas_model->Declarar_Comprobante($prm_ruc,$prm_comprobante,$prm_tipo_doc);

			if ($consulta['result']==1)
			{
				$result['status']=1;

			}
			else
			{
				$result['status']=0;
				$result['codigo_resumen']='';
			}
			echo json_encode($result);
		}


	}
