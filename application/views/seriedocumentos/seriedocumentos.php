<!doctype html>
<html>
<head>
	<title>SFE Bizlinks - Serie Documentos</title>
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
			$('#txt_numerodocumento').numeric({allow:'.'});

			ncsistema.Listar_SeriedocumentosTabla('');	
			ncsistema.Listar_SeriedocumentosxEmpresaTabla('');

			$("#tabs").tabs();
			ListarConfiguracion_Tab1();
		})

		$(function() 
		{
			$("#tabs").tabs({ 
				activate: function(event ,ui)
				{
							//alert( ui.newTab.attr('li',"innerHTML")[0].getElementsByTagName("a")[0].innerHTML);
							if (( ui.newTab.attr('li',"innerHTML")[0].getElementsByTagName("a")[0].innerHTML)=='SERIES POR USUARIOS')
							{
								ListarConfiguracion_Tab2();}
							} });
		});

		ncsistema=
		{
			Nuevo_Empresa:function()
			{
				Limpiar_DatosEmpresa();
			},
			Guadar_SerieNumeracion:function()
			{
				var txt_codconfser=$.trim($('#txt_codconfser').val());
				var txt_cod_empr=$.trim($('#txt_cod_empr').val());	
					var cmb_usuariodocumento=0;//$.trim($('#cmb_usuariodocumento').val());
					var cmb_tipodocumento=$.trim($('#cmb_tipodocumento').val());
					var txt_seriedocumento=$.trim($('#txt_seriedocumento').val()).toUpperCase();
					var txt_numerodocumento=$.trim($('#txt_numerodocumento').val());
					
					if (txt_cod_empr=='0'){
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el emisor</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}			
					if (cmb_tipodocumento==0){
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el tipo de Documento</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					if (txt_seriedocumento==''){
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese la serie del documento</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					if (txt_seriedocumento.length!=4 ){
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La serie debe tener 4 caracteres</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					if (txt_numerodocumento==''){
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el número del documento</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					var txt_caracter=txt_seriedocumento.slice(0,1);
					if (cmb_tipodocumento=='01')
					{
						if (txt_caracter!='F')
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La serie debe de iniciar con el caracter F.</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
					}else
					{
						if (cmb_tipodocumento=='03')
						{
							if (txt_caracter!='B')
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La serie debe de iniciar con el caracter B.</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;

							}
						}
						else if (cmb_tipodocumento=='20')
						{
							if (txt_caracter!='R')
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La serie debe de iniciar con el caracter R.</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}
						else
						{
							if (txt_caracter!='F' && txt_caracter!='B')
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La serie debe de iniciar con el caracter F o B.</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}
					}
					if (txt_codconfser==0)//GUARDAR
					{
						$.ajax({
							url:'<?php echo base_url()?>seriedocumentos/Guadar_SerieNumeracion',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_cod_empr:txt_cod_empr,
								cmb_usuariodocumento:cmb_usuariodocumento,
								cmb_tipodocumento:cmb_tipodocumento,
								txt_seriedocumento:txt_seriedocumento,
								txt_numerodocumento:txt_numerodocumento
								
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
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El registro de los datos se realiz&oacute; con &eacute;xito</div>');
									setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);

									Limpiar_DatosEmpresa();
									ncsistema.Listar_Seriedocumentos(txt_cod_empr);
									Listar_TipoDocumentoPorEmisor();
									return;
								}
								else if (result.status==1000)
								{
									document.location.href= '<?php echo base_url()?>usuario';
									return;
								}
								else if (result.status==2)
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionEmpresa').fadeIn(0);
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La serie y el tipo de documento ya está registrado</div>');
									setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
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
							url:'<?php echo base_url()?>seriedocumentos/Modificar_SerieNumeracion',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_codconfser:txt_codconfser,
								cmb_usuariodocumento:cmb_usuariodocumento,
								txt_numerodocumento:txt_numerodocumento
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
									ncsistema.Listar_Seriedocumentos(txt_cod_empr);

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
				
				Guadar_SerieNumeracionxEmpresa:function()
				{
					var txt_cod_empr=$.trim($('#txt_cod_empr').val());
					var cmb_tipodocumentoseries=$.trim($('#cmb_tipodocumentoseries').val());	
					var cmb_usuarioempresa=$.trim($('#cmb_usuarioempresa').val());
					
					if (cmb_tipodocumentoseries=='0')
					{
						$('#div_MensajeValidacion').fadeIn(0);
						$('#div_MensajeValidacion').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el tipo de documento</div>');
						setTimeout(function(){ $("#div_MensajeValidacion").fadeOut(1500);},3000);
						return;
					}
					if (cmb_usuarioempresa=='0')
					{
						$('#div_MensajeValidacion').fadeIn(0);
						$('#div_MensajeValidacion').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el usuario</div>');
						setTimeout(function(){ $("#div_MensajeValidacion").fadeOut(1500);},3000);
						return;
					}
					
					$.ajax({
						url:'<?php echo base_url()?>seriedocumentos/Guadar_SerieNumeracionxUsuario',
						type: 'post',
						dataType: 'json',
						data:
						{
							cmb_tipodocumentoseries:cmb_tipodocumentoseries,
							cmb_usuarioempresa:cmb_usuarioempresa
						},
						beforeSend:function()
						{
							$('#div_MensajeValidacion').fadeIn(0);
							$('#div_MensajeValidacion').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
						},
						success:function(result)
						{
							if(result.status==1)
							{

								$('#div_MensajeValidacion').fadeIn(0);
								$('#div_MensajeValidacion').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El registro de los datos se realiz&oacute; con &eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacion").fadeOut(1500);},3000);
								Nuevo_SeriesUsuarioxEmpresa();
								ncsistema.Listar_SeriedocumentosxEmpresa(txt_cod_empr);
								return;
							}
							else if (result.status==2)
							{
								$('#div_MensajeValidacion').fadeIn(0);
								$('#div_MensajeValidacion').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La serie y el usuario ya está registrado</div>');
								setTimeout(function(){ $("#div_MensajeValidacion").fadeOut(1500);},3000);
								return;
							}	
							else if (result.status==3)
							{
								$('#div_MensajeValidacion').fadeIn(0);
								$('#div_MensajeValidacion').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La serie ya está registrado</div>');
								setTimeout(function(){ $("#div_MensajeValidacion").fadeOut(1500);},3000);
								return;
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}					
							else
							{
								$('#div_MensajeValidacion').fadeIn(0);
								$('#div_MensajeValidacion').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al registrar los datos</div>');
								setTimeout(function(){ $("#div_MensajeValidacion").fadeOut(1500);},3000);
								return;
							}
						}
					});	
				},
				
				
				
				Listar_Seriedocumentos:function(txt_cod_empr)
				{
					$.ajax({
						url:'<?php echo base_url()?>seriedocumentos/Listar_Seriedocumentos',
						type: 'post',
						dataType: 'json',
						data:
						{	
							txt_cod_empr:txt_cod_empr
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_SeriedocumentosTabla(result.data);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								ncsistema.Listar_SeriedocumentosTabla("");
							}
						}
					});					
				},				
				
				
				Listar_SeriedocumentosTabla:function(data)
				{	
					$('#div_ListadoSeriedocumentos').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
					newHtml+='<th style="width:3%">Nro.</td>';						
					newHtml+='<th style="width:5%">Editar</td>';
						//newHtml+='<th style="width:10%">Usuario</td>';
						newHtml+='<th style="width:35%">Tipo Doc.</td>';
						newHtml+='<th style="width:25%">Serie</td>';
						newHtml+='<th style="width:25%">N&uacute;mero</td>';						
						newHtml+='<th style="width:7%">Eliminar</td>';	
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
						newHtml+='<td style="text-align:center"><a href="javascript:VerDatosCliente_Modificar('+rs.cod_confser+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/Editar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
							//newHtml+='<td style="text-align:left">'+rs.cod_usu+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nomb_tipdoc+'</td>';
							newHtml+='<td style="text-align:left">'+rs.ser_doc+'</td>';
							newHtml+='<td style="text-align:left">'+rs.num_doc+'</td>';							

							if (rs.est_reg==0)//ANULADO
							{
								newHtml+='<td style="text-align:left"></td>';								
							}
							else
							{
								newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_SerieDocumentos('+rs.cod_confser+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
							}
							
							newHtml+='</tr>';						
						});	
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_ListadoSeriedocumentos').empty().append(newHtml);	

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
				
				Listar_SeriedocumentosxEmpresa:function(txt_cod_empr)
				{
					$.ajax({
						url:'<?php echo base_url()?>seriedocumentos/Listar_SeriedocumentosxUsuario',
						type: 'post',
						dataType: 'json',
						data:
						{	
							txt_cod_empr:txt_cod_empr
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_SeriedocumentosxEmpresaTabla(result.data);
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								ncsistema.Listar_SeriedocumentosxEmpresaTabla("");
							}
						}
					});					
				},				

				Listar_SeriedocumentosxEmpresaTabla:function(data)
				{	
					$('#div_ListadoSeriedocumentosxEmpresa').empty().append('');
					//alert('hola rellena tabla');
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaSeriesEmpresa">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
					newHtml+='<th style="width:3%">Nro.</td>';						
					newHtml+='<th style="width:20%">Usuario</td>';
					newHtml+='<th style="width:40%">Tipo Doc.</td>';
					newHtml+='<th style="width:17%">Serie</td>';				
					newHtml+='<th style="width:20%">Eliminar</td>';	
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
						newHtml+='<td style="text-align:left">'+rs.apell_usu+' '+rs.nom_usu+'</td>';
						newHtml+='<td style="text-align:left">'+rs.nomb_tipdoc+'</td>';
						newHtml+='<td style="text-align:left">'+rs.ser_doc+'</td>';
							if (rs.est_reg==0)//ANULADO
							{
								newHtml+='<td style="text-align:left"></td>';								
							}
							else
							{
								newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_SerieDocumentosxUsuario('+rs.cod_confserusu+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
							}
							
							newHtml+='</tr>';						
						});	
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_ListadoSeriedocumentosxEmpresa').empty().append(newHtml);	

					oTable=$('#Tab_ListaSeriesEmpresa').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});

					$("#Tab_ListaSeriesEmpresa tbody").click(function(event) 
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
				$('#txt_codconfser').val('0');
				
				//$('#cmb_usuariodocumento').val('');
				$('#cmb_tipodocumento').val('');
				$('#txt_seriedocumento').val('');		
				$('#txt_numerodocumento').val('1');		
				
				$("#cmb_tipodocumento").prop('disabled', false);
				$("#txt_seriedocumento").prop('disabled', false);
				$("#txt_numerodocumento").prop('disabled', true);

				$('#div_Guardar').removeClass('disablediv');
				$("#div_Guardar").addClass("enablediv").on("onclick");	
			}
			
			
			function Nuevo_SeriesUsuarioxEmpresa()
			{
				$('#cmb_tipodocumentoseries').val('0');
				$('#cmb_usuarioempresa').val('0');		
			}

			function VerDatosCliente_Modificar(cod_confser)
			{
				$.ajax
				({
					url:'<?php echo base_url()?>seriedocumentos/Listar_SeriedocumentosId',type:'post',dataType:'json',
					data:
					{
						cod_confser:cod_confser
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
								$("#cmb_tipodocumento").prop('disabled', true);
								$("#txt_seriedocumento").prop('disabled', true);

								$.trim($('#txt_codconfser').val(rs.cod_confser));									
									//$.trim($('#cmb_usuariodocumento').val(rs.cod_usu));
									$.trim($('#cmb_tipodocumento').val(rs.tip_doc));
									$.trim($('#txt_seriedocumento').val(rs.ser_doc));									
									$.trim($('#txt_numerodocumento').val(rs.num_doc));
									
									$("#txt_numerodocumento").prop('disabled', false);
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
			
			
			function Eliminar_SerieDocumentos(cod_confser)
			{
				if(confirm("¿ Esta Seguro de Eliminar el documento ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>seriedocumentos/Eliminar_SerieNumeracion',type:'post',dataType:'json',
						data:
						{
							cod_confser:cod_confser,
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminaci&oacute;n de la serie se realiz&oacute; con &eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								//ncsistema.Listar_Seriedocumentos();
								var txt_cod_empr=$('#txt_cod_empr').val();
								ncsistema.Listar_Seriedocumentos(txt_cod_empr);
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el correlativo</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}			
					});
				}
				
			}
			
			function Eliminar_SerieDocumentosxUsuario(cod_confserusu)
			{
				if(confirm("¿ Esta Seguro de Eliminar el registro ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>seriedocumentos/Eliminar_SerieNumeracionxUsuario',type:'post',dataType:'json',
						data:
						{
							cod_confserusu:cod_confserusu,
						},
						beforeSend:function()
						{
							$('#div_MensajeValidacion').fadeIn(0);
							$('#div_MensajeValidacion').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>')
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacion').fadeIn(0);
								$('#div_MensajeValidacion').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminaci&oacute;n de la serie se realiz&oacute; con &eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacion").fadeOut(1500);},3000);
								//ncsistema.Listar_Seriedocumentos();
								var txt_cod_empr=$('#txt_cod_empr').val();
								ncsistema.Listar_SeriedocumentosxEmpresa(txt_cod_empr);
								return;					
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								$('#div_MensajeValidacion').fadeIn(0);
								$('#div_MensajeValidacion').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el correlativo</div>');
								setTimeout(function(){ $("#div_MensajeValidacion").fadeOut(1500);},3000);
								return;
							}
						}			
					});
				}
				
			}
			
			function Completar_TipoDocumento(item_tabla)
			{
				var item_tabla=$.trim(item_tabla);
				//'01','03','07','08'
				if (item_tabla=='01')
				{
					$('#txt_seriedocumento').val('F');
				}else{
					if (item_tabla=='03')
					{
						$('#txt_seriedocumento').val('B');
					}
					else if (item_tabla=='20')
					{
						$('#txt_seriedocumento').val('R');
					}
					else{
						$('#txt_seriedocumento').val('');
					}
				}
			}

			function Listar_TipoDocumentoPorEmisor()
			{
				var cod_emisor=$.trim($('#txt_cod_EmisorSerieUsuario').val());
				$.ajax({
					url:'<?php echo base_url()?>seriedocumentos/Listar_Seriedocumentos',
					type: 'post',
					dataType: 'json',
					data:
					{	
						txt_cod_empr:cod_emisor
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(result.status==1)
						{
							newHtml='';		
							newHtml+='<select id="cmb_tipodocumentoseries" style="width:100% ;height:22px"';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							$.each(result.data,function(key,rs)
							{
								newHtml+='<option value="'+rs.cod_confser+'">'+rs.nomb_tipdoc+'-'+rs.ser_doc+'</option>';
							});
							newHtml+='</select>';
							$('#div_tipodocumentoseries').empty().append(newHtml);
							
						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}
						else
						{
							newHtml='';		
							newHtml+='<select id="cmb_tipodocumentoseries" style="width:100%;height:22px" >';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							newHtml+='</select>';
							$('#div_tipodocumentoseries').empty().append(newHtml);
						}
					}
				});
			}
			
			function ListarConfiguracion_Tab1()
			{
				var txt_cod_empr=$.trim($('#txt_cod_empr').val());
				var txt_RazonSocialEmpresa=$.trim($('#txt_RazonSocialEmpresa').val());
				var txt_tipo_confserie=$.trim($('#txt_tipo_confserie').val());
				if(txt_tipo_confserie==1)
				{
					$('#div_tipoconfiguracion').empty().append('SERIES POR EMPRESA');
					
				}
				else if(txt_tipo_confserie==2)
				{
					$('#div_tipoconfiguracion').empty().append('SERIES POR USUARIO');	
					
				}
				ncsistema.Listar_Seriedocumentos(txt_cod_empr);	
			}
			function ListarConfiguracion_Tab2()
			{
				var txt_cod_empr=$.trim($('#txt_cod_empr').val());
				var txt_RazonSocialEmpresa=$.trim($('#txt_RazonSocialEmpresa').val());
				var txt_tipo_confserie=$.trim($('#txt_tipo_confserie').val());
				//alert(txt_cod_empr);
				if (txt_tipo_confserie==2)//
				{
					$('#txt_cod_EmisorSerieUsuario').val(txt_cod_empr);
					$('#txt_desc_EmisorSerieUsuario').val(txt_RazonSocialEmpresa);
					//alert('conf 2');
					Listar_Tipodocumentoseries(txt_cod_empr);
				}else
				{
					$('#txt_cod_EmisorSerieUsuario').val('');
					$('#txt_desc_EmisorSerieUsuario').val('');
					//alert('conf else');
					//$('#cmb_tipodocumentoseries').val('0');
					//$('#Cmb_TipoDocumentoSunat').val('0');	
					Listar_Tipodocumentoseries("0");
				}
				
			}
			
			
			
			function Listar_Tipodocumentoseries(cod_emisor)
			{
				$('#div_tipodocumentoseries').empty().append('');
				$('#div_usuarioempresa').empty().append('');
				var txt_cod_emisor=cod_emisor;
				//alert(txt_cod_emisor);
				ncsistema.Listar_SeriedocumentosxEmpresa(txt_cod_emisor);
				$.ajax({
					url:'<?php echo base_url()?>seriedocumentos/Listar_Seriedocumentos',
					type: 'post',
					dataType: 'json',
					data:
					{	
						txt_cod_empr:txt_cod_emisor
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(result.status==1)
						{
							newHtml='';		
							newHtml+='<select id="cmb_tipodocumentoseries" style="width:100%;height:22px" >';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							$.each(result.data,function(key,rs)
							{
								newHtml+='<option value="'+rs.cod_confser+'">'+rs.nomb_tipdoc+'-'+rs.ser_doc+'</option>';
							});
							newHtml+='</select>';
							$('#div_tipodocumentoseries').empty().append(newHtml);
							
						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}
						else
						{
							newHtml='';		
							newHtml+='<select id="cmb_tipodocumentoseries" style="width:100%;height:22px" >';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							newHtml+='</select>';
							$('#div_tipodocumentoseries').empty().append(newHtml);
						}
					}
				});	
				
				$.ajax({
					url:'<?php echo base_url()?>usuario/Listar_UsuarioxEmpresa',
					type: 'post',
					dataType: 'json',
					data:
					{	
						txt_cod_empr:txt_cod_emisor
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(result.status==1)
						{
							newHtml='';		
							newHtml+='<select id="cmb_usuarioempresa" style="width:100%;height:22px" >';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							$.each(result.data,function(key,rs)
							{
								newHtml+='<option value="'+rs.cod_usu+'">'+rs.apell_usu+' '+rs.nom_usu+'</option>';
							});
							newHtml+='</select>';
							$('#div_usuarioempresa').empty().append(newHtml);
							
						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}
						else
						{
							newHtml='';		
							newHtml+='<select id="cmb_usuarioempresa" style="width:100%;height:22px" >';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							newHtml+='</select>';
							$('#div_usuarioempresa').empty().append(newHtml);
						}
					}
				});					
				
			}

		</script>
	</head>   
	<body>
		<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">SERIES Y NUMERACI&Oacute;N</a></li>
				<li><a href="#tabs-2">SERIES POR USUARIOS</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">
				<div id="div_datosempresa" style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
					<input style="width:15%" type="hidden" id="txt_codconfser"  value="0" />
					<input type="hidden" id="txt_cod_empr"  value="<?php echo $Cod_empr;?>" />
					<input type="hidden" id="txt_tipo_confunid"  value="<?php echo $Tipo_confunid;?>" />
					<input type="hidden" id="txt_tipo_confserie"  value="<?php echo $Tipo_confserie;?>" />
					<table border="0" width="40%" style="border-collapse:separate; border-spacing:1px 1px;" cellpadding="3" class="tablaFormulario">
						<tr><td><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right;width:30%"><label class="columna">Emisor:</label></td>
							<td style="text-align:left;;width:70%" >
								<input style="width:98%" type="text" id="txt_RazonSocialEmpresa" value="<?php echo trim(utf8_decode($Razon_Social));?>"
								disabled="disabled" />
							</td>
						</tr>	
						<tr>
							<td style="text-align:right;"><label class="columna">Tipo de Conf.:</label></td>
							<td style="text-align:left;" >							
								<div id="div_tipoconfiguracion"></div>
							</td>
						</tr>	
						
						<tr>
							<td style="text-align:right;"><label class="columna">Tipo Documento:</label></td>
							<td style="text-align:left;">
								<select id="cmb_tipodocumento" style="width:40%;height:22px" onChange="javascrip:Completar_TipoDocumento(this.value)">
									<option value="0">[SELECCIONAR]</option>
									<?php foreach ( $Listar_TipodeDocumento as $v):	?>
										<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(utf8_decode($v['no_corto']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Serie Documento:</label></td>
							<td style="text-align:left"><input style="width:15%; text-transform:uppercase" type="text" id="txt_seriedocumento" maxlength="4" /></td>
						</tr>
						
						<tr>
							<td style="text-align:right"><label class="columna">N&uacute;mero Documento:</label></td>
							<td style="text-align:left"><input style="width:15%" type="text" id="txt_numerodocumento" maxlength="8" value="1" disabled="disabled" /></td>
						</tr>
						<tr><!--<td><label class="columna"></label></td>-->
							<td colspan="2" align="center">
								<div style="width:90%;height:15px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:6px;text-align:center;float:left">
									<div id="div_MensajeValidacionEmpresa" style="width:100%;float:left;font-size:9px;color:#FF0000"></div>
								</div>
							</td></tr>
							<tr>
								<td><label class="columna"></label></td>
								<td style="text-align:left">
									<table style="width:50%" >
										<tbody>
											<tr>
												<td style="text-align:right; width:50%">
													<a href="javascript:ncsistema.Nuevo_Empresa()" >
														<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px"  type="submit">
															<span class="ui-button-icon-left ui-icon ui-icon-document"></span>
															<span class="ui-button-text">Nuevo</span></button>
														</a>
													</td>
													<td style="text-align:left;width:50%">
														<a href="javascript:ncsistema.Guadar_SerieNumeracion()" >
															<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px"  type="submit">
																<span class="ui-button-icon-left ui-icon ui-icon-disk"></span>
																<span class="ui-button-text">Guardar</span></button>
															</a>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>		
								</table>

							</div>
							<div id="div_ListadoSeriedocumentos" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:10px">
							</div>	
						</div>

						<div id="tabs-2" style="width:95%;float:left">
							<div id="div_datosempresa" style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
								<input style="width:15%" type="hidden" id="txt_codconfser"  value="0" />
								<table border="0" width="40%" style="border-collapse:separate; border-spacing:1px 1px;" cellpadding="3" class="tablaFormulario">
									<tr><td><label class="columna"></label></td></tr>

									<tr>
										<td style="text-align:right;width:30%"><label class="columna">Emisor:</label></td>
										<td style="text-align:left;width:70%" >
											<table width="100%" border="0">
												<tr>
													<td>
														<input style="width:98%" type="hidden" id="txt_cod_EmisorSerieUsuario" value="" />
														<input style="width:98%" type="text" id="txt_desc_EmisorSerieUsuario" value=""
														disabled="disabled" />
													</td>
													<td>
														<div>Serie por Usuario</div>
													</td>
												</tr>
											</table>
										</td>
									</tr>

									<tr>
										<td style="text-align:right;"><label class="columna">Tipo Documento:</label></td>
										<td style="text-align:left;">
											<div id="div_tipodocumentoseries">
												<select id="cmb_tipodocumentoseries" style="width:80%;height:22px" >
													<option value="0">[SELECCIONAR]</option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td style="text-align:right;"><label class="columna">Usuario:</label></td>
										<td style="text-align:left;">
											<div id="div_usuarioempresa">
												<select id="cmb_usuarioempresa" style="width:80%;height:22px" >
													<option value="0">[SELECCIONAR]</option>
												</select>
											</div>
										</td>
									</tr>

									<tr><!--<td><label class="columna"></label></td>-->
										<td colspan="2" align="center">
											<div style="width:90%;height:15px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:0px;text-align:center;float:left">
												<div id="div_MensajeValidacion" style="width:100%;float:left;font-size:9px;color:#FF0000"></div>
											</div>
										</td></tr>
										<tr>
											<td><label class="columna"></label></td>
											<td style="text-align:left">
												<table style="width:50%" >
													<tbody>
														<tr>
															<td style="text-align:right; width:50%">
																<a href="javascript:Nuevo_SeriesUsuarioxEmpresa()" >
																	<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px"  type="submit">
																		<span class="ui-button-icon-left ui-icon ui-icon-document"></span>
																		<span class="ui-button-text">Nuevo</span></button>
																	</a>
																</td>
																<td style="text-align:left;width:50%">
																	<a href="javascript:ncsistema.Guadar_SerieNumeracionxEmpresa()" >
																		<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px"  type="submit">
																			<span class="ui-button-icon-left ui-icon ui-icon-disk"></span>
																			<span class="ui-button-text">Guardar</span></button>
																		</a>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>		
											</table>

										</div>
										<div id="div_ListadoSeriedocumentosxEmpresa" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:10px">
										</div>	
									</div>



								</div>

							</body>	
							</html>