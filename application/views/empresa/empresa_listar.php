<!doctype html>
<html>
<head>
	<title>SFE Bizlinks - Empresa</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.css"/>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.min.css"/>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/plugins/dataTable/css/dataTables-all.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>application/helpers/jquery/flexigrid/flexigrid/flexigrid.css" />

	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/inicio.css"/>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/menusystem.css"/>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/tabla_documento.css"/>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/botones.css"/>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/site.css"/>

	<script type="text/javascript" src="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/external/jquery/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>application/helpers/jquery/plugins/jquery.alphanumeric.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>application/helpers/jquery/plugins/jquery.maskedinput.min.js"></script>		
	<script type="text/javascript" src="<?php echo base_url();?>application/helpers/plugins/dataTable/js/dataTables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>application/helpers/jquery/plugins/jquery.ui.datepicker-es.js"></script> 
	<script type="text/javascript" src="<?php echo base_url();?>application/helpers/jquery/flexigrid/flexigrid/flexigrid.js"></script>

	<script>var urlexportardatos="<?php echo base_url();?>"</script>

	<script type="text/javascript">	

		$(document).ready(function()
		{
			$("#tabs").tabs();
			ncsistema.Listar_Empresa();	
			$('#txt_RucEmpresa').numeric();
		})

		ncsistema=
		{
			Nuevo_Empresa:function()
			{
				Limpiar_DatosEmpresa();
			},
			Guadar_Empresa:function()
			{
				var txt_CodEmpresa=$.trim($('#txt_CodEmpresa').val());
				var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
				var txt_RazonSocialEmpresa=$.trim($('#txt_RazonSocialEmpresa').val());
				var txt_RepLegalEmpresa=$.trim($('#txt_RepLegalEmpresa').val());
				var Cmb_TipoEmpresa=$.trim($('#Cmb_TipoEmpresaSelect').val());
				var Cmb_TipoConfiguracionSerie=$.trim($('#Cmb_TipoConfiguracionSerie').val());
				var Cmb_TipoConfiguracionUnidad=$.trim($('#Cmb_TipoConfiguracionUnidad').val());
				var Cmb_TipoConfiguracionFirma=$.trim($('#Cmb_TipoConfiguracionFirma').val());
				var cmb_tipodocempresa=$.trim($('#cmb_tipodocempresa').val());
				var txt_NombreComercialEmpresa=$.trim($('#txt_NombreComercialEmpresa').val());
				var cmb_paisempresa=$.trim($('#cmb_paisempresa').val());
				var cmb_departamento=$.trim($('#cmb_departamento').val());
				var cmb_provincia=$.trim($('#cmb_provincia').val());
				var cmb_distrito=$.trim($('#cmb_distrito').val());
				var txt_urbanizacionempresa=$.trim($('#txt_urbanizacionempresa').val());
				var txt_direccionempresa=$.trim($('#txt_direccionempresa').val());

				allFields = $([]).add( $('#txt_CodEmpresa')).add( $('#txt_RucEmpresa') ).add( $('#txt_RazonSocialEmpresa') ).add( $('#txt_RepLegalEmpresa') ).add( $('#Cmb_TipoEmpresaSelect') ).add( $('#Cmb_TipoConfiguracionSerie') ).add( $('#Cmb_TipoConfiguracionUnidad') ).add( $('#Cmb_TipoConfiguracionFirma') ).add( $('#cmb_tipodocempresa')).add( $('#txt_NombreComercialEmpresa')).add( $('#cmb_paisempresa')).add( $('#cmb_departamento')).add( $('#cmb_provincia')).add( $('#cmb_distrito')).add( $('#txt_urbanizacionempresa')).add( $('#txt_direccionempresa'));

				allFields.removeClass( "ui-state-error" );

				if (cmb_tipodocempresa==0)
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el tipo de Documento</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#cmb_tipodocempresa").addClass( "ui-state-error" );
					return;
				}
				if (txt_RucEmpresa=='')
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Ingrese el RUC de la Empresa</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#txt_RucEmpresa").addClass( "ui-state-error" );
					return;
				}
				if (txt_RazonSocialEmpresa=='')
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Ingrese la Raz&oacute;n Social de la Empresa</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#txt_RazonSocialEmpresa").addClass( "ui-state-error" );
					return;
				}
				if (Cmb_TipoConfiguracionSerie=='')
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Seleccione el tipo de configuración para la Serie</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#Cmb_TipoConfiguracionSerie").addClass( "ui-state-error" );
					return;
				}
				if (Cmb_TipoConfiguracionUnidad=='')
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Seleccione el tipo de configuración para la Unidad</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#Cmb_TipoConfiguracionUnidad").addClass( "ui-state-error" );
					return;
				}
				if (Cmb_TipoConfiguracionFirma=='')
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Seleccione el tipo de configuración para la Firma</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#Cmb_TipoConfiguracionFirma").addClass( "ui-state-error" );
					return;
				}
				if (Cmb_TipoEmpresa==0)
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Seleccione el tipo de Empresa</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#Cmb_TipoEmpresaSelect").addClass( "ui-state-error" );
					return;
				}
				if (cmb_paisempresa==0)
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Seleccione el pa&iacute;s</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#cmb_paisempresa").addClass( "ui-state-error" );
					return;
				}
				
				if (cmb_departamento==0)
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Seleccione el departamento</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#cmb_departamento").addClass( "ui-state-error" );
					return;
				}
				if (cmb_provincia==0)
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Seleccione la provincia</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#cmb_provincia").addClass( "ui-state-error" );
					return;
				}
				if (cmb_distrito==0)
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Seleccione el distrito</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#cmb_distrito").addClass( "ui-state-error" );
					return;
				}
				if (txt_urbanizacionempresa=='')
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Ingrese la urbanizaci&oacute;n</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#txt_urbanizacionempresa").addClass( "ui-state-error" );
					return;
				}
				if (txt_direccionempresa=='')
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Ingrese la direcci&oacute;n</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					$("#txt_direccionempresa").addClass( "ui-state-error" );
					return;
				}
					if (txt_CodEmpresa==0)//GUARDAR
					{
						$.ajax({
							url:'<?php echo base_url()?>empresa/Guardar_Empresa',
							type: 'post',
							dataType: 'json',
							data:
							{
								cmb_tipodocempresa:cmb_tipodocempresa,
								txt_RucEmpresa:txt_RucEmpresa,
								txt_RazonSocialEmpresa:txt_RazonSocialEmpresa,
								txt_NombreComercialEmpresa:txt_NombreComercialEmpresa,
								txt_RepLegalEmpresa:txt_RepLegalEmpresa,
								Cmb_TipoEmpresa:Cmb_TipoEmpresa,								
								Cmb_TipoConfiguracionSerie:Cmb_TipoConfiguracionSerie,
								Cmb_TipoConfiguracionUnidad:Cmb_TipoConfiguracionUnidad,
								Cmb_TipoConfiguracionFirma:Cmb_TipoConfiguracionFirma,								
								cmb_paisempresa:cmb_paisempresa,
								cmb_departamento:cmb_departamento,
								cmb_provincia:cmb_provincia,
								cmb_distrito:cmb_distrito,
								txt_urbanizacionempresa:txt_urbanizacionempresa,
								txt_direccionempresa:txt_direccionempresa
							},
							beforeSend:function()
							{
								$('#div_Guardar').removeClass('enablediv');
								$("#div_Guardar").addClass("disablediv").off("onclick");

								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
							},
							success:function(result)
							{
								if(result.status==1)
								{
									$('#div_MensajeValidacionEmpresa').fadeIn(0);
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El registro de los datos se realizó con éxito</div>');
									setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
									Limpiar_DatosEmpresa();
									ncsistema.Listar_Empresa();
									return;
								}
								else if (result.status==2)
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionEmpresa').fadeIn(0);
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El RUC ingresado ya está registrado</div>');
									setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
									return;
								}	
								else if (result.status==1000)
								{
									document.location.href= '<?php echo base_url()?>usuario';
									return;
								}				
								else
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionEmpresa').fadeIn(0);
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al registrar los datos</div>');
									setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
									return;
								}
							}
						});
					}
					else //MODIFICAR
					{
						$.ajax({
							url:'<?php echo base_url()?>empresa/Modificar_Empresa',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_CodEmpresa:txt_CodEmpresa,
								cmb_tipodocempresa:cmb_tipodocempresa,
								txt_RazonSocialEmpresa:txt_RazonSocialEmpresa,
								txt_RepLegalEmpresa:txt_RepLegalEmpresa,
								Cmb_TipoEmpresa:Cmb_TipoEmpresa,
								Cmb_TipoConfiguracionSerie:Cmb_TipoConfiguracionSerie,
								Cmb_TipoConfiguracionUnidad:Cmb_TipoConfiguracionUnidad,
								Cmb_TipoConfiguracionFirma:Cmb_TipoConfiguracionFirma,	
								cmb_paisempresa:cmb_paisempresa,
								cmb_departamento:cmb_departamento,
								cmb_provincia:cmb_provincia,
								cmb_distrito:cmb_distrito,
								txt_urbanizacionempresa:txt_urbanizacionempresa,
								txt_direccionempresa:txt_direccionempresa,
								txt_NombreComercialEmpresa:txt_NombreComercialEmpresa,
								txt_RucEmpresa:txt_RucEmpresa
							},
							beforeSend:function()
							{
								$('#div_Guardar').removeClass('enablediv');
								$("#div_Guardar").addClass("disablediv").off("onclick");
								
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
							},
							success:function(result)
							{
								if(result.status==1)
								{
									$('#div_MensajeValidacionEmpresa').fadeIn(0);
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La Modificaci&oacute;n de los datos se realiz&oacute; con &eacute;xito</div>');
									setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);

									Limpiar_DatosEmpresa();
									ncsistema.Listar_Empresa();

									return;
								}
								else if (result.status==1000)
								{
									document.location.href= '<?php echo base_url()?>usuario';
									return;
								}	
								else
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionEmpresa').fadeIn(0);
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al modificar los datos</div>');
									setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
									return;
								}
							}
						});
					}
				},

				Listar_Empresa:function()
				{
					$.ajax({
						url:'<?php echo base_url()?>empresa/Listar_EmpresaGrid',
						type: 'post',
						dataType: 'json',
						data:
						{
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_EmpresaTabla(result.data);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}	
							else
							{
								ncsistema.Listar_EmpresaTabla("");
							}
						}
					});					
				},				
				
				
				Listar_EmpresaTabla:function(data)
				{	
					$('#div_ListadoEmpresa').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
					newHtml+='<th style="width:3%">Nro.</td>';						
					newHtml+='<th style="width:5%">Editar</td>';
					newHtml+='<th style="width:10%">RUC</td>';
					newHtml+='<th style="width:35%">Raz&oacute;n Social</td>';
					newHtml+='<th style="width:25%">Rep.Legal</td>';
					newHtml+='<th style="width:25%">Tipo</td>';						
					newHtml+='<th style="width:10%">Estado</td>';
					newHtml+='<th style="width:10%">Eliminar</td>';	
					newHtml+='</tr>';
					newHtml+='</thead>';
					newHtml+='<tbody>';
					//<input style="height:20px;width:95%" id="txt_login" type="text" value="'+rs.cantidadproducto+'"/>
					contador=0;
					$.each(data,function(key,rs)
					{
						contador++;
						newHtml+='<tr>';							
						newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';							
						newHtml+='<td style="text-align:center"><a href="javascript:VerDatosEmpresa_Modificar('+rs.cod_empr+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/Editar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
						newHtml+='<td style="text-align:left">'+rs.ruc_empr+'</td>';
						newHtml+='<td style="text-align:left">'+rs.raz_social+'</td>';
						newHtml+='<td style="text-align:left">'+rs.rep_legal+'</td>';
						newHtml+='<td style="text-align:left">'+rs.tipoempresa+'</td>';							
							if (rs.est_reg==0)//ANULADO
							{
								newHtml+='<td style="text-align:left"><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="ANULADO" ></td>';								
							}
							else
							{
								newHtml+='<td style="text-align:center"><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/ncactivo.png" title="ACTIVO" ></td>';
							}
							
							if (rs.est_reg==0)//ANULADO
							{
								newHtml+='<td style="text-align:left"></td>';								
							}
							else
							{
								newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Empresa('+rs.cod_empr+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
							}
							
							newHtml+='</tr>';						
						});	
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_ListadoEmpresa').empty().append(newHtml);	

					oTable=$('#Tab_ListaEmpresa').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});

					$("#Tab_ListaEmpresa tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});


				},	
				
			}
			
			function Limpiar_DatosEmpresa()
			{
				$('#txt_CodEmpresa').val('0');
				$('#txt_RucEmpresa').val('');
				$('#txt_RazonSocialEmpresa').val('');
				$('#txt_RepLegalEmpresa').val('');				
				$('#Cmb_TipoEmpresaSelect').val('1');
				
				$('#Cmb_TipoConfiguracionSerie').val('');
				$('#Cmb_TipoConfiguracionUnidad').val('');
				$("#Cmb_TipoConfiguracionUnidad").prop('disabled', false);
				$('#Cmb_TipoConfiguracionFirma').val('2');
				
				$("#txt_RucEmpresa").prop('disabled', false);
				$("#cmb_tipodocempresa").prop('disabled', false);
				
				$('#cmb_tipodocempresa').val('0');
				$('#txt_NombreComercialEmpresa').val('');
				$('#txt_urbanizacionempresa').val('');
				$('#txt_direccionempresa').val('');
				
				$('#cmb_departamento').val('0');
				$('#cmb_provincia').val('0');
				$('#cmb_distrito').val('0');
				$('#cmb_paisempresa').val('0');
				
				$('#div_Guardar').removeClass('disablediv');
				$("#div_Guardar").addClass("enablediv").on("onclick");	
			}

			function VerDatosEmpresa_Modificar(cod_empr)
			{
				$.ajax
				({
					url:'<?php echo base_url()?>empresa/Listar_EmpresaCodigo',type:'post',dataType:'json',
					data:
					{
						cod_empr:cod_empr
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(parseInt(result.status)==1)
						{
							$.each(result.data,function(key,rs)
							{

								$("#txt_RucEmpresa").prop('disabled', true);
								$("#cmb_tipodocempresa").prop('disabled', true);
								$.trim($('#txt_CodEmpresa').val(rs.cod_empr));
								$.trim($('#txt_RucEmpresa').val(rs.ruc_empr));
								$.trim($('#txt_RazonSocialEmpresa').val(rs.raz_social));
								$.trim($('#txt_RepLegalEmpresa').val(rs.rep_legal));
								$.trim($('#Cmb_TipoEmpresaSelect').val(1));
								
								$('#Cmb_TipoConfiguracionSerie').val(rs.tipo_confserie);
								$('#Cmb_TipoConfiguracionUnidad').val(rs.tipo_confunid);
								$("#Cmb_TipoConfiguracionUnidad").prop('disabled', true);
								$('#Cmb_TipoConfiguracionFirma').val(rs.tipo_conffirma);
								
								
								$.trim($('#cmb_paisempresa').val(rs.cod_pais));
								$.trim($('#cmb_tipodocempresa').val(rs.tip_documento));
								$.trim($('#txt_NombreComercialEmpresa').val(rs.nom_comercial));
								$.trim($('#txt_urbanizacionempresa').val(rs.urbaniz_empresa));
								$.trim($('#txt_direccionempresa').val(rs.direcc_empresa));
								
								$.trim($('#cmb_departamento').val(rs.cod_departamento));
								
								Listar_Provincias(rs.cod_departamento,rs.cod_provincia);
								Listar_Distritos(rs.cod_provincia,rs.cod_distrito);

							});

						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}	
					}
				});
			}
			
			function VerDatosEmpresa_Validar()
			{
				var cmb_tipodocempresa=$('#cmb_tipodocempresa').val();
				var txt_rucempresa=$('#txt_RucEmpresa').val();
				$.ajax
				({
					url:'<?php echo base_url()?>empresa/Listar_EmpresaDocumento',type:'post',dataType:'json',
					data:
					{
						cmb_tipodocempresa:cmb_tipodocempresa,
						txt_rucempresa:txt_rucempresa
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(parseInt(result.status)==1)
						{
							$.each(result.data,function(key,rs)
							{

								$("#txt_RucEmpresa").prop('disabled', true);
								$("#cmb_tipodocempresa").prop('disabled', true);
								$.trim($('#txt_CodEmpresa').val(rs.cod_empr));
								$.trim($('#txt_RucEmpresa').val(rs.ruc_empr));
								$.trim($('#txt_RazonSocialEmpresa').val(rs.raz_social));
								$.trim($('#txt_RepLegalEmpresa').val(rs.rep_legal));
								$.trim($('#Cmb_TipoEmpresaSelect').val(1));
								
								$('#Cmb_TipoConfiguracionSerie').val(rs.tipo_confserie);
								$('#Cmb_TipoConfiguracionUnidad').val(rs.tipo_confunid);
								$('#Cmb_TipoConfiguracionFirma').val(rs.tipo_conffirma);
								
								
								$.trim($('#cmb_paisempresa').val(rs.cod_pais));
								$.trim($('#cmb_tipodocempresa').val(rs.tip_documento));
								$.trim($('#txt_NombreComercialEmpresa').val(rs.nom_comercial));
								$.trim($('#txt_urbanizacionempresa').val(rs.urbaniz_empresa));
								$.trim($('#txt_direccionempresa').val(rs.direcc_empresa));
								$.trim($('#cmb_departamento').val(rs.cod_departamento));
								
								Listar_Provincias(rs.cod_departamento,rs.cod_provincia);
								Listar_Distritos(rs.cod_provincia,rs.cod_distrito);
							});
						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}	
					}
				});
			}
			
			function Eliminar_Empresa(cod_empr)
			{
				if(confirm("¿ Está Seguro de Eliminar la Empresa ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>empresa/Eliminar_Empresa',type:'post',dataType:'json',
						data:
						{
							cod_empr:cod_empr,
						},
						beforeSend:function()
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>')
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminacion de la Empresa se realiz&oacute; con &eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_Empresa();
								return;	
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}	
							else
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar la Empresa</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}			
					});
				}
			}
			
			function Listar_Provincias(cod_depart,selectprovincia)
			{
				$('#div_provincia').empty().append('');
				
				$.ajax({
					url:'<?php echo base_url()?>catalogos/Listar_Provincias',
					type: 'post',
					dataType: 'json',
					data:
					{
						cod_depart:cod_depart
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(result.status==1)
						{
							newHtml='';		
							newHtml+='<select id="cmb_provincia" style="width:100%; height:22px" onchange="javascrip:Listar_Distritos(this.value,0)">';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							$.each(result.data,function(key,rs)
							{
								newHtml+='<option value="'+rs.co_provincia+'">'+rs.de_provincia+'</option>';
							});
							newHtml+='</select>';
							$('#div_provincia').empty().append(newHtml);
							
						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}	
						else
						{
							newHtml='';		
							newHtml+='<select id="cmb_provincia" style="width:100%; height:22px" onchange="javascrip:Listar_Distritos(this.value,0)">';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							newHtml+='</select>';
							$('#div_provincia').empty().append(newHtml);
						}
						
						if (selectprovincia>0)
						{
							$('#cmb_provincia').val(selectprovincia);
						}
						
					}
				});
			}
			
			
			function Listar_Distritos(cod_provincia,selectdistrito)
			{
				var cod_depart=$('#cmb_departamento').val();
				$('#div_distrito').empty().append('');
				
				$.ajax({
					url:'<?php echo base_url()?>catalogos/Listar_Distritos',
					type: 'post',
					dataType: 'json',
					data:
					{
						cod_depart:cod_depart,
						cod_provincia:cod_provincia
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(result.status==1)
						{
							newHtml='';		
							newHtml+='<select id="cmb_distrito" style="width:100%; height:22px" >';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							$.each(result.data,function(key,rs)
							{
								newHtml+='<option value="'+rs.co_distrito+'">'+rs.de_distrito+'</option>';
							});
							newHtml+='</select>';
							$('#div_distrito').empty().append(newHtml);
							
						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}	
						else
						{
							newHtml='';		
							newHtml+='<select id="cmb_distrito" style="width:100%; height:22px" >';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							newHtml+='</select>';
							$('#div_distrito').empty().append(newHtml);
						}
						
						if (selectdistrito>0)
						{
							$('#cmb_distrito').val(selectdistrito);
						}
					}
				});
			}
			
			function Bloquear_NumeroDocumento()
			{
				$('#txt_RucEmpresa').val('');
			}
			
			
			
			
		</script>
		
	</head>   
	<body>
		<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">EMPRESAS</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">

				<div id="div_datosempresa" style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
					<input style="width:15%" type="hidden" id="txt_CodEmpresa"  value="0" />
					<table width="50%" style="border-collapse:separate; border-spacing:1px 1px;" cellpadding="3" class="tablaFormulario">
						<tr><td><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right;width:20%"><label class="columna">Tipo Doc.:</label></td>
							<td style="text-align:left; width:30%">
								<select id="cmb_tipodocempresa" style="width:98%;height:22px" onChange="javascript:Bloquear_NumeroDocumento()">
									<option value="0">[SELECCIONAR]</option>
									<option value="6">RUC</option>
								</select>
							</td>
							<td style="text-align:right;width:20%"><label class="columna">N&uacute;mero:</label></td>
							<td style="text-align:left; width:30%">
								<input style="width:100%" type="text" id="txt_RucEmpresa" maxlength="11" onBlur="javascrip:VerDatosEmpresa_Validar()" />
							</td>
						</tr>
						<tr>
							<td style="text-align:right;"><label class="columna">Raz&oacute;n Social:</label></td>
							<td style="text-align:left;" colspan="3">
								<input style="width:100%" type="text" id="txt_RazonSocialEmpresa" />
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Nombre Comercial:</label></td>
							<td style="text-align:left" colspan="3">
								<input style="width:100%" type="text" id="txt_NombreComercialEmpresa"  />
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Rep.Legal:</label></td>
							<td style="text-align:left" colspan="3">
								<input style="width:100%" type="text" id="txt_RepLegalEmpresa" />
							</td>
						</tr>	
						<tr>
							<td style="text-align:right"><label class="columna">Tipo:</label></td>
							<td style="text-align:left;">
								<select id="Cmb_TipoEmpresaSelect" style="width:100%;height:22px" disabled="disabled">
									<option value="1" selected="selected">EMISOR</option>
									<!--<option value="2">RECEPTOP</option>
									<option value="3">EMISOR Y RECEPTOR</option>-->
								</select>
							</td>
							<td style="text-align:right; "><label class="columna">Conf.Serie:</label></td>
							<td style="text-align:left;">
								<select id="Cmb_TipoConfiguracionSerie" style="width:100%;height:22px" >
									<option value="">[SELECCIONAR]</option>
									<option value="1">SERIE POR EMPRESA</option>
									<option value="2">SERIE POR USUARIO</option>
								</select>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Conf.Unidad:</label></td>
							<td style="text-align:left;">
								<select id="Cmb_TipoConfiguracionUnidad" style="width:100%;height:22px" >
									<option value="">[SELECCIONAR]</option>
									<option value="1">UNIDAD DE SUNAT</option>
									<option value="2">UNIDAD COMERCIAL</option>
								</select>
							</td>
							<td style="text-align:right;"><label class="columna">Conf.Firma:</label></td>
							<td style="text-align:left;">
								<select id="Cmb_TipoConfiguracionFirma" style="width:100%;height:22px" >  $prm['Valor_Inhouse']=0;
									<?php if ($Valor_Inhouse==1)
									{	?>
									<option value="2">FIRMA LOCAL</option>
									<?php } else	{ ?>
									<option value="2">FIRMA LOCAL</option>
									<option value="1">FIRMA BIZLINK</option>	
									<?php } ; ?>										
								</select>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Pa&iacute;s:</label></td>
							<td style="text-align:left;">
								<select id="cmb_paisempresa" style="width:100%;height:22px" >
									<option value="0">[SELECCIONAR]</option>
									<option value="PE">PERU</option>													
								</select>
							</td>
							<td style="text-align:right;"><label class="columna">Departamento:</label></td>
							<td style="text-align:left;">
								<select id="cmb_departamento" style="width:100%;height:22px" onChange="javascrip:Listar_Provincias(this.value,0)">
									<option value="0">[SELECCIONAR]</option>
									<?php foreach ( $Listar_Departamentos as $v):	?>
										<option value="<?php echo trim($v['co_departamento']); ?>"><?php echo trim(utf8_decode($v['de_departamento']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Provincia:</label></td>
							<td style="text-align:left;">
								<div id="div_provincia">
									<select id="cmb_provincia" style="width:100%;height:22px" >
										<option value="0">[SELECCIONAR]</option>
									</select>
								</div>
							</td>
							<td style="text-align:right"><label class="columna">Distrito:</label></td>
							<td style="text-align:left;">
								<div id="div_distrito">
									<select id="cmb_distrito" style="width:100%; height:22px" >
										<option value="0">[SELECCIONAR]</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Urbanizaci&oacute;n:</label></td>
							<td style="text-align:left" colspan="3" ><input style="width:100%" type="text" id="txt_urbanizacionempresa"  /></td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna"> Direcci&oacute;n:</label></td>
							<td style="text-align:left" colspan="3"><input style="width:100%" type="text" id="txt_direccionempresa"  /></td>
						</tr>		
						<tr>
							<td align="right"><label class="columna"></label></td>
							<td colspan="3" align="center">
								<div style="width:100%;height:20px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:0px;text-align:center;float:left">
									<div id="div_MensajeValidacionEmpresa" style="width:100%;float:left;font-size:9px; color:#FF0000"></div>
								</div>
							</td>
						</tr>
						<tr>
							<td><label class="columna"></label></td>
							<td colspan="3" >
								<table style="width:100%" >
									<tbody>
										<tr>
											<td style="text-align:right; width:50%">
												<a href="javascript:ncsistema.Nuevo_Empresa()" >
													<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
														<span class="ui-button-icon-left ui-icon ui-icon-document"></span>
														<span class="ui-button-text">Nuevo</span>
													</button>
												</a>
											</td>
											<td style="text-align:left;width:50%">
												<a href="javascript:ncsistema.Guadar_Empresa()" >
													<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
														<span class="ui-button-icon-left ui-icon ui-icon-disk"></span>
														<span class="ui-button-text">Guardar</span>
													</button>
												</a>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</table>

				</div>
				<div id="div_ListadoEmpresa" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:10px">
				</div>	
			</div>

		</div>
	</body>	
	</html>