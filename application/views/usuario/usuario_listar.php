<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Usuario</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		

		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.min.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/plugins/dataTable/css/dataTables-all.css" />

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
		
		
		<script>var urlexportardatos="<?php echo base_url(); ?>"</script>	


		<script type="text/javascript">
			
			$(document).ready(function()
			{
				ncsistema.Listar_UsuarioSistema();
				ncsistema.Listar_ParametrosTabla('');
				ncsistema.Listar_PermisosAsignadosTabla('');
				ncsistema.Listar_UsuarioPorEmpresaTabla('');
				
				//ncsistema.Listar_UsuarioPorEmpresa();
				$("#tabs").tabs();
				OcultarFilaPassword('rowslect',0);//OCULTA	
			});			
			ncsistema=
			{
				
				
				Listar_UsuarioSistema :function()
				{
					$.ajax({
						url:'<?php echo base_url()?>usuario/Listar_Usuario',
						type: 'post',
						dataType: 'json',
						data:
						{
						},
						beforeSend:function()
						{
							/*$('#div_procesarbuscar').empty().append('<button style="width:30px">  <img src="<?php echo base_url();?>application/helpers/image/ico/nc_loading.gif"/> </button>');*/
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_UsuarioSistemaTabla(result.data);
								
							}
							else
							{
								ncsistema.Listar_UsuarioSistemaTabla("");
							}
						}
					});
					
				},
				
				Listar_UsuarioSistemaTabla:function(data)
				{	
					$('#div_listausuarios').empty().append('');
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaUsuarios">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th style="width:3%">Nro.</td>';						
						newHtml+='<th style="width:3%">Editar</td>';
						newHtml+='<th style="width:28%">Usuario</td>';
						//newHtml+='<th style="width:3%">ADMINISTRADOR</td>';
						newHtml+='<th style="width:20%">Tipo</td>';
						//newHtml+='<th style="width:10%">LOGIN</td>';						
						newHtml+='<th style="width:20%">Nombres</td>';
						newHtml+='<th style="width:20%">Apellidos</td>';						
						newHtml+='<th style="width:3%">Estado</td>';
						newHtml+='<th style="width:3%">Eliminar</td>';	

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
							newHtml+='<td style="text-align:center"><a href="javascript:VerDatos_ModificarUsuario('+rs.cod_usu+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/Editar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
							newHtml+='<td style="text-align:left">'+rs.email_usu+'</td>';
							//newHtml+='<td style="text-align:left">'+rs.Email_UsuAdm+'</td>';
							newHtml+='<td style="text-align:left">'+rs.tipousuario+'</td>';
							//newHtml+='<td style="text-align:left">'+rs.login_usu+'</td>';							
							newHtml+='<td style="text-align:left">'+rs.nom_usu+'</td>';
							newHtml+='<td style="text-align:left">'+rs.apell_usu+'</td>';							
							
							if (rs.est_reg==0)//PENDIENTE
							{
								newHtml+='<td style="text-align:left"><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="ANULADO" ></td>';								
							}
							else
							{
								newHtml+='<td style="text-align:center"><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/ncactivo.png" title="ACTIVO" ></td>';
							}
							
							if (rs.est_reg==0)//PENDIENTE
							{
								newHtml+='<td style="text-align:left"></td>';								
							}
							else
							{
								newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Usuario('+rs.cod_usu+','+rs.cod_tipusu+',\''+rs.email_usu+'\')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
							}
							
						newHtml+='</tr>';						
					});	
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_listausuarios').empty().append(newHtml);	

					oTable=$('#Tab_ListaUsuarios').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_ListaUsuarios tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});
									
				
				},	
					
				Nuevo_Usuario:function()
				{
					Limpiar_DatosUsuario();
				},

				Guardar_Usuario:function()
				{
					
					var txt_Cod_Usu=$.trim($('#txt_Cod_Usu').val());
					
					var txt_Login='';
					var txt_Contrasena=$.trim($('#txt_Contrasena').val());
					
					var txt_ContrasenaValidar=$.trim($('#txt_ContrasenaValidar').val());
					var txt_NombUsuario=$.trim($('#txt_NombUsuario').val());
					var txt_ApelUsuario=$.trim($('#txt_ApelUsuario').val());
					var txt_Email=$.trim($('#txt_Email').val());

					var cbox_Eliminar=1;
					
					if (txt_Email=='')
					{
						$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
						$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese email del usuario</div>');
						setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
						return;
					}
					
					if (validarEmail(txt_Email)==false)
					{
						$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
						$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El Email ingresado no es válido</div>');
						setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
						return;
					}
					
					
					if (txt_Cod_Usu=='0')
					{
						if (txt_Contrasena=='')
						{
							$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
							$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese contraseña del usuario</div>');
							setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
							return;
						}
						
						if (txt_Contrasena!=txt_ContrasenaValidar)
						{
							$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
							$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Las credenciales del usuario son incorrectos</div>');
							setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
							return;
						}
					}
					else
					{
						//SI ES EDITAR Y SI ESTA HABILITADO EL CHECK
						if ($("#cbox_cambiacontasena").is(":checked"))
						{
							if (txt_Contrasena=='')
							{
								$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
								$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese contraseña del usuario</div>');
								setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
								return;
							}
							
							if (txt_Contrasena!=txt_ContrasenaValidar)
							{
								$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
								$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Las credenciales del usuario son incorrectos</div>');
								setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
								return;
							}
						}
					}

					if (txt_NombUsuario=='')
					{
						$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
						$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el nombre del usuario</div>');
						setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
						return;
					}
					if (txt_ApelUsuario=='')
					{
						$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
						$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el apellido del usuario</div>');
						setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
						return;
					}

					if (txt_Cod_Usu==0)
					{
						$.ajax({
							url:'<?php echo base_url()?>usuario/Guardar_UsuarioInterno',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_Cod_Usu:txt_Cod_Usu,
								txt_Login:txt_Login,
								cbox_Eliminar:cbox_Eliminar,
								txt_Contrasena:txt_Contrasena,
								txt_NombUsuario:txt_NombUsuario,
								txt_ApelUsuario:txt_ApelUsuario,
								txt_Email:txt_Email					
							},
							beforeSend:function()
							{
								$('#div_Guardar').removeClass('enablediv');
								$("#div_Guardar").addClass("disablediv").off("onclick");

								$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
								$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
							},
							success:function(result)
							{
								if(result.status==1)
								{
									$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
									$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El registro de los datos se realiz&oacute; con &eacute;xito</div>');
									setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
									ncsistema.Listar_UsuarioSistema();
									Listar_Usuarioasignarpermisos();
									Limpiar_DatosUsuario();
									return;	
								}
								else if(result.status==2)
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");

									$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
									$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El login del usuario está registrado</div>');
									setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
									return;	
								}
								else if(result.status==3)
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
									$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El email del usuario está registrado</div>');
									setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
									return;	
								}
								
								else
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
									$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al registrar los datos</div>');
									setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
									return;
								}
							}
						});
					}
					else
					{
						$.ajax({
							url:'<?php echo base_url()?>usuario/Modificar_UsuarioInterno',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_Cod_Usu:txt_Cod_Usu,
								txt_NombUsuario:txt_NombUsuario,
								txt_ApelUsuario:txt_ApelUsuario,
								txt_Email:txt_Email,
								txt_Contrasena:txt_Contrasena						
							},
							beforeSend:function()
							{
								$('#div_Guardar').removeClass('enablediv');
								$("#div_Guardar").addClass("disablediv").off("onclick");

								$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
								$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
							},
							success:function(result)
							{
								if(result.status==1)
								{
									$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
									$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La modificaci&oacute;n de los datos se realiz&oacute; con &eacute;xito</div>');
									setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
									ncsistema.Listar_UsuarioSistema();
									Limpiar_DatosUsuario();
	
								}else
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
									$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al modificar los datos</div>');
									setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
									return;
								}
							}
						});
					}

				},
				
				Listar_Parametros:function ()
				{
					var Cmb_Empresa=$.trim($('#Cmb_Empresa').val());
					var Cmb_Usuario=$.trim($('#Cmb_Usuario').val());
					var Cmb_Roles=$.trim($('#Cmb_Roles').val());
					var Cmb_Perfiles=$.trim($('#Cmb_Perfiles').val());
					
					
					if (Cmb_Empresa=='')
					{
						return;
					}
					if (Cmb_Usuario=='')
					{
						return;
					}
					
					if (Cmb_Roles==0)
					{
						return;
					}
					
					$.ajax({
						url:'<?php echo base_url()?>usuarioacceso/Listar_MenuSistemaPendiente',
						type: 'post',
						dataType: 'json',
						data:
						{
							Cmb_Empresa:Cmb_Empresa,
							Cmb_Usuario:Cmb_Usuario,
							Cmb_Roles:Cmb_Roles,
							Cmb_Perfiles:Cmb_Perfiles
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_ParametrosTabla(result.data);
							}
							else
							{
								ncsistema.Listar_ParametrosTabla("");
							}
						}
					});					
				},			
				
				
				Listar_ParametrosTabla:function(data)
				{
					txt_ListaPermisosAsignarAll
					//var Cmb_Perfiles=$.trim($('#Cmb_Perfiles').val());
					$('#txt_ListaPermisosAsignarAll').val('');
					
					var cadenamenu='';
					
					
					$('#div_ListadoParametros').empty().append('');
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaAccesosPendientes">';
					newHtml+='<thead>';
					newHtml+='<tr>';
					
						newHtml+='<th style="width:10%">Nro.</td>';
						newHtml+='<th style="width:60%">Nombre</td>';
						newHtml+='<th style="width:10%"><input style="text-align:center" id="txt_variable_all" name="txt_variable_all" onchange="javascrip:Seleccionar_PermisosAsignarAll()" type="checkbox" value="" /></td> ';

					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					$.each(data,function(key,rs)
					{
						newHtml+='<tr class="modo1">';

							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nom_men+'</td>';
							newHtml+='<td style="text-align:center"><input style="text-align:center" id="txt_variable_'+rs.cod_men+'" name="txt_variable_'+rs.cod_men+'" onchange="javascrip:Seleccionar_DatosBusqueda('+rs.cod_men+')" type="checkbox" value="" /></td>';						
							
							if (cadenamenu=='')
							{
								cadenamenu=rs.cod_men;
							}
							else
							{
								cadenamenu=cadenamenu+','+rs.cod_men;
							}
							
						newHtml+='</tr>';	
					});	
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_ListadoParametros').empty().append(newHtml);	
					$('#txt_ListaPermisosAsignarAll').val(cadenamenu);
					
					oTable=$('#Tab_ListaAccesosPendientes').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_ListaAccesosPendientes tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});

				
				},
				
				
				Listar_PermisosAsignados:function ()
				{
					var Cmb_Empresa=$.trim($('#Cmb_Empresa').val());
					var Cmb_Usuario=$.trim($('#Cmb_Usuario').val());
					var Cmb_Roles=$.trim($('#Cmb_Roles').val());
					
					if (Cmb_Empresa=='')
					{
						return;
					}
					if (Cmb_Usuario=='')
					{
						return;
					}
					
				
					
					$.ajax({
						url:'<?php echo base_url()?>usuarioacceso/Listar_MenuSistemaAsignado',
						type: 'post',
						dataType: 'json',
						data:
						{
							Cmb_Empresa:Cmb_Empresa,
							Cmb_Usuario:Cmb_Usuario,
							Cmb_Roles:Cmb_Roles
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_PermisosAsignadosTabla(result.data);
							}
							else
							{
								ncsistema.Listar_PermisosAsignadosTabla("");
							}
						}
					});					
				},			
				
				
				Listar_PermisosAsignadosTabla:function(data)
				{
					$('#div_ListadoPermisosAsignados').empty().append('');

					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListadoPermisosAsignados">';
					newHtml+='<thead>';
					newHtml+='<tr>';
						newHtml+='<th style="width:10%">Nro.</td>';						
						newHtml+='<th style="width:60%">Perfil</td>';
						newHtml+='<th style="width:60%">Rol</td>';
						newHtml+='<th style="width:60%">Nombre</td>';
						newHtml+='<th style="width:10%">OP</td> ';
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';

					$.each(data,function(key,rs)
					{
						newHtml+='<tr class="modo1">';
							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nomb_perfil+'</td>';
							newHtml+='<td style="text-align:left">'+rs.tipousuario+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nom_men+'</td>';
							newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Acceso('+rs.cod_usuacc+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
							
								
						newHtml+='</tr>';	
					});	
					
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_ListadoPermisosAsignados').empty().append(newHtml);	

					oTable=$('#Tab_ListadoPermisosAsignados').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_ListadoPermisosAsignados tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});
					
					
				},
				
				Buscar_Permisos:function()
				{
					
					var Cmb_Empresa=$.trim($('#Cmb_Empresa').val());
					var Cmb_Usuario=$.trim($('#Cmb_Usuario').val());
					var Cmb_Perfiles=$.trim($('#Cmb_Perfiles').val());

					if (Cmb_Empresa=='')
					{
						ncsistema.Listar_ParametrosTabla('');
						ncsistema.Listar_PermisosAsignadosTabla('');
						return;
					}
					if (Cmb_Usuario=='')
					{
						ncsistema.Listar_ParametrosTabla('');
						ncsistema.Listar_PermisosAsignadosTabla('');
						return;
					}
					
					
					if (Cmb_Perfiles==0)
					{
						ncsistema.Listar_ParametrosTabla('');
						ncsistema.Listar_PermisosAsignadosTabla('');
						return;
					}
					
					ncsistema.Listar_Parametros();
					ncsistema.Listar_PermisosAsignados();

				},
				
				Guardar_Parametros:function ()//cod_men
				{
					var Cmb_Empresa=$.trim($('#Cmb_Empresa').val());
					var Cmb_Usuario=$.trim($('#Cmb_Usuario').val());
					var Cmb_Perfiles=$.trim($('#Cmb_Perfiles').val());
					var Cmb_Roles=$.trim($('#Cmb_Roles').val());
					
					
					var txt_ListaPermisosAsignar=$.trim($('#txt_ListaPermisosAsignar').val());
					

					if (Cmb_Empresa=='')
					{
						return;
					}
					if (Cmb_Usuario=='')
					{
						return;
					}
					if (Cmb_Perfiles==0)
					{
						return;
					}
					//cod_men:cod_men,
					$.ajax({
						url:'<?php echo base_url()?>usuarioacceso/Guardar_Parametros',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_ListaPermisosAsignar:txt_ListaPermisosAsignar,
							Cmb_Empresa:Cmb_Empresa,
							Cmb_Usuario:Cmb_Usuario,
							Cmb_Perfiles:Cmb_Perfiles,
							Cmb_Roles:Cmb_Roles
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								
								$('#txt_ListaPermisosAsignar').val('');
								/*
								$('#div_MensajeValidacionCliente').fadeIn(0);
								$('#div_MensajeValidacionCliente').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La actualización de los datos se realizó con éxito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionCliente").fadeOut(1500);},3000);		
								
								*/
								$('#div_MensajeValidacionCliente').fadeIn(0);
								$('#div_MensajeValidacionCliente').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Actualización finalizado. Para visualizar los cambios es necesario refrescar la página.</div>');
								setTimeout(function(){ $("#div_MensajeValidacionCliente").fadeOut(1500);},3000);	
								
														
								ncsistema.Buscar_Permisos();
								//ncsistema.Listar_UsuarioPorEmpresa();
								return;
							}
						}
					});					
				},
				
				Listar_UsuarioPorEmpresa :function()
				{
					$.ajax({
						url:'<?php echo base_url()?>usuarioacceso/Listar_UsuariosPorEmpresa',
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
								ncsistema.Listar_UsuarioPorEmpresaTabla(result.data);								
							}
							else
							{
								ncsistema.Listar_UsuarioPorEmpresaTabla("");
							}
						}
					});
					
				},
				
				Listar_UsuarioPorEmpresaTabla:function(data)
				{	
					$('#div_usuarioporempresa').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_UsuarioPorEmpresa">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th style="width:5%; text-align:center" >Nro.</th>';		
						newHtml+='<th style="width:20%; text-align:left">Email</td>';
						newHtml+='<th style="width:20%; text-align:left">Nombres</td>';
						newHtml+='<th style="width:20%; text-align:left">Apellidos</td>';
						newHtml+='<th style="width:20%; text-align:left">Roles</td>';							
						newHtml+='<th style="width:8%; text-align:left">RUC</td>';
						newHtml+='<th style="width:40%; text-align:left">Razón Social</td>';						
						//newHtml+='<th >Tipo Emp.</td>';				
											
						
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';

					//contador=0;
					$.each(data,function(key,rs)
					{
						//contador++;
						newHtml+='<tr class="modo1">';							
							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';		
							newHtml+='<td style="text-align:left">'+rs.email_usu+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nom_usu+'</td>';
							newHtml+='<td style="text-align:left">'+rs.apell_usu+'</td>';							
							newHtml+='<td style="text-align:left">'+rs.tipo_usuario+'</td>';		
							newHtml+='<td style="text-align:left">'+rs.ruc_empr+'</td>';							
							newHtml+='<td style="text-align:left">'+rs.raz_social+'</td>';
							//newHtml+='<td style="text-align:left">'+rs.tipo_empresa+'</td>';				
						newHtml+='</tr>';						
					});	
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_usuarioporempresa').empty().append(newHtml);	

					oTable=$('#Tab_UsuarioPorEmpresa').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_UsuarioPorEmpresa tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});
									
				
				},
				
				
			}

			function Limpiar_DatosUsuario()
			{
				//$("#txt_Login").prop('disabled', false);
				$("#txt_Contrasena").prop('disabled', false);
				$("#txt_ContrasenaValidar").prop('disabled', false);
									
				$('#txt_Cod_Usu').val('0');
				$('#txt_Login').val('');
				
				$('#txt_Contrasena').val('');
				$('#txt_ContrasenaValidar').val('');
				$('#txt_NombUsuario').val('');
				$('#txt_ApelUsuario').val('');
				$('#txt_Email').val('');				
				OcultarFilaPassword('row3',1);//OCULTA
				$("#cbox_cambiacontasena").prop('checked', false);	
				OcultarFilaPassword('rowslect',0);//OCULTA	
				
				$('#div_Guardar').removeClass('disablediv');
				$("#div_Guardar").addClass("enablediv").on("onclick");
				
			
			}
			
			function VerDatos_ModificarUsuario(cod_usu)
			{
				OcultarFilaPassword('rowslect',0);//OCULTA	
				$.ajax
				({
					url:'<?php echo base_url()?>usuario/Listar_UsuarioDatos',type:'post',dataType:'json',
					data:
					{
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
								if (rs.cod_usu==cod_usu)
								{
									OcultarFilaPassword('row3',0);//OCULTA								
									//$("#txt_Login").prop('disabled', true);									
									//$("#txt_Contrasena").prop('disabled', true);
									//$("#txt_ContrasenaValidar").prop('disabled', true);								
									$('#txt_Cod_Usu').val(rs.cod_usu);
									$('#txt_Login').val(rs.login_usu);									
									//$('#txt_Contrasena').val('');
									//$('#txt_ContrasenaValidar').val('');									
									$('#txt_NombUsuario').val(rs.nom_usu);
									$('#txt_ApelUsuario').val(rs.apell_usu);
									$('#txt_Email').val(rs.email_usu);									
									//alert(rs.cod_tipusuconect);
									$("#cbox_cambiacontasena").prop('checked', false);
									if (rs.cod_tipusuconect==1)//SOLO SI ES ADMINISTRADOR
									{
										OcultarFilaPassword('rowslect',1);//OCULTA	
									}
			
								}
							});
			
						}

					}
			
				});
			}	
			//Dejar en Blanco si no se quiere Modificar
			
			
			function OcultarFilaPassword(id,opcion) 
			{
				if (!document.getElementById) return false;
				fila = document.getElementById(id);
				
				if (opcion==0)//OCULTA LA FILA
				{
					if (fila.style.display != "none") 
					{
						fila.style.display = "none"; //ocultar fila
					}
				}
				else
				{
					if (fila.style.display == "none") 
					{
						fila.style.display = ""; //mostrar fila
					}
				}
			}

			function Eliminar_Usuario(cod_usu,cod_tipusu, email_usu)
			{
				var txt_Cod_Usu_login=$.trim($('#txt_Cod_Usu_login').val());	
				if (cod_usu==txt_Cod_Usu_login)
				{
					$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
					$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">No es posible eliminar! Usuario Logueado.</div>');
					setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
					return;
				}
				if (cod_tipusu==2) //0 INVITADO Y 1 ADMINISTRADOR
				{
					if(confirm("¿ Está Seguro de Eliminar el usuario: " + email_usu + " ?"))
					{
						$.ajax
						({
							url:'<?php echo base_url()?>usuario/Eliminar_UsuarioInterno',type:'post',dataType:'json',
							data:
							{
								cod_usu:cod_usu,
							},
							beforeSend:function()
							{
								$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
								$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
							},
							success:function(result)
							{
								if(result.status==1)
								{
									$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
									$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminaci&oacute;n del usuario se realiz&oacute; con &eacute;xito</div>');
									setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
									ncsistema.Listar_UsuarioSistema();
									Limpiar_DatosUsuario();
		
								}else
								{
									$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
									$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el usuario</div>');
									setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
									return;
								}
							}			
						});
					}
				}
				else
				{
					$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
					$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">¡No se puede eliminar un usuario ADMINISTRADOR!</div>');
					setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
					return;
				}
			}
			
			function validarEmail(valor) 
			{
				filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				//if (/(\w+)(\.?)(\w*)(\@{1})(\w+)(\.?)(\w*)(\.{1})(\w{2,3})/.test(valor))
				if (filter.test(valor))
				{
					return true;
				} 
				else 
				{				
					return false;				
			  	}
			}
			
			function Buscar_TipoEmpresa(cod_tipempresa)
			{
				ncsistema.Listar_ParametrosTabla('');
				ncsistema.Listar_PermisosAsignadosTabla('');
				
				$('#div_listaempresas').empty().append('');
				$('#div_listaperfiles').empty().append('');
				
				$.ajax
				({
					url:'<?php echo base_url()?>empresa/Listar_EmpresaPermisosUsuarioReceptor',type:'post',dataType:'json',
					data:
					{
						cod_tipempresa:cod_tipempresa
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(parseInt(result.status)==1)
						{						
							newHtml='';
							newHtml+='<select id="Cmb_Empresa" style="width:100%;height:25px" onChange="javascrip:ncsistema.Buscar_Permisos()">';
							newHtml+='<option value="">[SELECCIONAR]</option>';
							$.each(result.data,function(key,rs)
							{
							  if (rs.sel_empresa==rs.cod_empr)
								{
									newHtml+='<option value="'+rs.cod_empr+'" selected="selected">'+rs.raz_social+'</option>';
								}
							  else
							    {
									newHtml+='<option value="'+rs.cod_empr+'" >'+rs.raz_social+'</option>';
								}
								
							});
							newHtml+='</select>';
							
							$('#div_listaempresas').empty().append(newHtml);
						}
						else
						{
							newHtml='';
							newHtml+='<select id="Cmb_Empresa" style="width:100%;height:25px" >';
							newHtml+='<option value="">[SELECCIONAR]</option>';
							newHtml+='</select>';
							
							$('#div_listaempresas').empty().append(newHtml);
						}
					}
			
				});
				
				
				$.ajax
				({
					url:'<?php echo base_url()?>perfil/Listar_Perfil',type:'post',dataType:'json',
					data:
					{
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(parseInt(result.status)==1)
						{
						
							newHtml='';
							newHtml+='<select id="Cmb_Perfiles" style="width:100%;height:25px" onChange="javascrip:ncsistema.Buscar_Permisos()">';
							newHtml+='<option value="">[SELECCIONAR]</option>';
							$.each(result.data,function(key,rs)
							{
								if (rs.cod_rol==cod_tipempresa)
								{
									newHtml+='<option value="'+rs.cod_perfil+'">'+rs.nom_perfil+'</option>';
								}
							});
							
							newHtml+='</select>';
							
							$('#div_listaperfiles').empty().append(newHtml);
						}
						else
						{
							newHtml='';
							newHtml+='<select id="Cmb_Perfiles" style="width:100%;height:25px" >';
							newHtml+='<option value="">[SELECCIONAR]</option>';
							newHtml+='</select>';
							
							$('#div_listaperfiles').empty().append(newHtml);
						}
					}
			
				});
				
				//div_listaperfiles
				
			}
			
			
			function Eliminar_Acceso(cod_usuacc)
			{
				if(confirm("¿ Esta Seguro de Eliminar El Accesso ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>usuarioacceso/Eliminar_Acceso',type:'post',dataType:'json',
						data:
						{
							cod_usuacc:cod_usuacc,
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionCliente').fadeIn(0);
								$('#div_MensajeValidacionCliente').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminaci&oacute;n de los datos se realiz&oacute; con &eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionCliente").fadeOut(1500);},3000);
								ncsistema.Buscar_Permisos();
								return;
	
							}else
							{
								$('#div_MensajeValidacionCliente').fadeIn(0);
								$('#div_MensajeValidacionCliente').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:10px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el accesos </div>');
								setTimeout(function(){ $("#div_MensajeValidacionCliente").fadeOut(1500);},3000);
								return;
							}
						}			
					});
				}
			}
			
			
			function Seleccionar_DatosBusqueda(key)
			{
				var txt_ListaPermisosAsignar=$.trim($('#txt_ListaPermisosAsignar').val());
				if ($("#txt_variable_"+key).is(":checked"))
				{
					if (txt_ListaPermisosAsignar=='')
					{
						txt_ListaPermisosAsignar=key;
					}
					else
					{
						txt_ListaPermisosAsignar=txt_ListaPermisosAsignar+','+key;
					}
				}
				else
				{
					txt_ListaPermisosAsignar=txt_ListaPermisosAsignar.replace(","+key, ""); 
					txt_ListaPermisosAsignar=txt_ListaPermisosAsignar.replace(key, ""); 
				}
				$('#txt_ListaPermisosAsignar').val($.trim(txt_ListaPermisosAsignar));
			}
			
			
			
			
			
			function Seleccionar_PermisosAsignarAll()
			{
				var txt_ListaPermisosAsignarAll=$.trim($('#txt_ListaPermisosAsignarAll').val());				
				var ListaPermisosAsignarAll = txt_ListaPermisosAsignarAll.split(",");
				var txt_ListaPermisosAsignar=$.trim($('#txt_ListaPermisosAsignar').val());

				if ($("#txt_variable_all").is(":checked"))
				{
					txt_ListaPermisosAsignar='';
					$.each(ListaPermisosAsignarAll,function(key,rs)
					{
						$("#txt_variable_"+ListaPermisosAsignarAll[key]).prop('checked', true);	
						
						if (txt_ListaPermisosAsignar=='')
						{
							txt_ListaPermisosAsignar=ListaPermisosAsignarAll[key];
						}
						else
						{
							txt_ListaPermisosAsignar=txt_ListaPermisosAsignar+','+ListaPermisosAsignarAll[key];
						}
						
						
					});
					
				}
				else
				{
					$.each(ListaPermisosAsignarAll,function(key,rs)
					{
						$("#txt_variable_"+ListaPermisosAsignarAll[key]).prop('checked', false);	
					});
					txt_ListaPermisosAsignar='';
				}
				$('#txt_ListaPermisosAsignar').val($.trim(txt_ListaPermisosAsignar));

			}
			
			
			
			function Listar_Usuarioasignarpermisos()
			{
				$('#div_usuarioasignarpermisos').empty().append('');
				
				$.ajax
				({
					url:'<?php echo base_url()?>usuario/Listar_UsuarioDatos',type:'post',dataType:'json',
					data:
					{
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(parseInt(result.status)==1)
						{
						
							newHtml='';
							newHtml+='<select id="Cmb_Usuario" style="width:100%;height:25px" onChange="javascrip:ncsistema.Buscar_Permisos()">';
							newHtml+='<option value="">[SELECCIONAR]</option>';
							$.each(result.data,function(key,rs)
							{
								if (rs.cod_tipusu==2)
								{
									newHtml+='<option value="'+rs.cod_usu+'">'+rs.apell_usu+' '+rs.nom_usu+' ['+rs.email_usu+']</option>';
								}
							});
							
							newHtml+='</select>';
							
							$('#div_usuarioasignarpermisos').empty().append(newHtml);
						}
						else
						{
							newHtml='';
							newHtml+='<select id="Cmb_Usuario" style="width:100%;height:25px" onChange="javascrip:ncsistema.Buscar_Permisos()">';
							newHtml+='<option value="">[SELECCIONAR]</option>';
							newHtml+='</select>';
							
							$('#div_usuarioasignarpermisos').empty().append(newHtml);
						}
					}
			
				});
			}
			
			
			function Seleccionar_CheckCambioContrasena()
			{
				if ($("#cbox_cambiacontasena").is(":checked"))
				{
					OcultarFilaPassword('row3',1);//OCULTA					
				}
				else
				{
					OcultarFilaPassword('row3',0);//OCULTA
				}
			}

		</script>



</head>   
<body>
	<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
	<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">USUARIOS</a></li>				
				<li><a href="#tabs-2">ASIGNACIÓN DE PERMISOS</a></li>
				<li><a href="#tabs-3">USUARIOS POR EMPRESA</a></li>
			</ul>			
			<div id="tabs-1" style="width:97%;float:left">
				<div style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">	
					<input id="txt_Cod_Usu" type="hidden" value="0" />
					<input id="txt_Cod_Usu_login" type="hidden" value="<?php echo $Listar_login;?>" />	
					<input type="hidden" id="txt_Login"  />
					<table border="0" width="40%" style="border-collapse:separate; border-spacing:1px 1px;" cellpadding="3" class="tablaFormulario">
						<tr><td><label class="columna"></label></td></tr>
						<tr>
							<td align="right" style="width:20%"><label class="columna">Usuario:</label></td>
							<td colspan="3" align="left" style="width:80%">
								<input style="width:100%" type="text" id="txt_Email"  /></td>
						</tr>
						
						<tr id="rowslect">
							<td align="right"></td>
							<td align="left" >
								<table style="text-align:left; width:100%" border="0" >
								  <tbody>
									<tr>
										<td style="text-align:left; width:65%;font-weight:bold">
											¡Habilitar para cambiar la contraseña del Usuario!
										</td>
										<td style="text-align:left;width:35%"><input id="cbox_cambiacontasena" type="checkbox" value="" name="cbox_cambiacontasena" onClick="javascript:Seleccionar_CheckCambioContrasena()" ></td>
									</tr>
								  </tbody>
								</table>
							</td>
						</tr>
						
						<tr id="row3">
							<td align="right"><label class="columna">Password:</label></td>
							<td align="left" >
								<table style="text-align:left; width:100%" >
								  <tbody>
									<tr>
										<td style="text-align:left; width:35%">
											<input type="password" style="width:100%" id="txt_Contrasena"  />
										</td>
										<td style="text-align:right;width:30%"><label class="columna">Validar Password:</label></td>
										<td style="text-align:left;width:35%">
											<input type="password" style="width:100%" id="txt_ContrasenaValidar"  />
										</td>
									</tr>
								  </tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td align="right"><label class="columna">Nombres:</label></td>
							<td colspan="3" align="left"><input style="width:100%" type="text" id="txt_NombUsuario"  /></td>
						</tr>
						<tr>
							<td align="right"><label class="columna">Apellidos:</label></td>
							<td colspan="3" align="left"><input style="width:100%" type="text" id="txt_ApelUsuario"  /></td>
						</tr>
						<tr>
							<td align="right"><label class="columna"></label></td>
							<td colspan="3" align="center">
								<div style="width:100%;height:20px;border:solid 0px;text-align:center;float:center">
									<div id="div_MensajeValidacionProductoCategoria" style="width:100%;float:left;font-size:9px; color:#FF0000"></div>
								</div>
							</td></tr>
						<tr>
							<td><label class="columna"></label></td>
							<td colspan="3" >
								<table style="width:100%" >
								  <tbody>
									<tr>
										<td style="text-align:right; width:50%">
											<a href="javascript:ncsistema.Nuevo_Usuario()" >
												<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
													<span class="ui-button-icon-left ui-icon ui-icon-document"></span>
													<span class="ui-button-text">Nuevo</span></button>
											</a>
										</td>
										<td style="text-align:left;width:50%">
											<a href="javascript:ncsistema.Guardar_Usuario()" >
												<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
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
				<div id="div_listausuarios" style="width:100%;float:left;text-align:center;margin-top:7px"> 
				</div>
			</div>
			
			<div id="tabs-2" style="width:97%;float:left">
				<div style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">		
					<input type="hidden" value="" id="txt_ListaPermisosAsignar"  />		
					<input type="hidden" value="" id="txt_ListaPermisosAsignarAll"  />		
					<table width="50%" border="0px" style="border-collapse:separate; border-spacing:8px 1px;" cellpadding="3" class="tablaFormulario">
						<tr><td><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right;width:15%"><label class="columna">Roles:</label></td>
							<td style="text-align:left;width:30%">
								<select id="Cmb_Roles" style="width:100%;height:25px" onChange="javascrip:Buscar_TipoEmpresa(this.value)">
									<option value="0">[SELECCIONAR]</option>
									<?php foreach ( $Listar_Roles as $v):	?>
										<option value="<?php echo trim($v['cod_rol']); ?>">
										<?php echo trim(utf8_decode($v['desc_rol']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
							<td style="text-align:right;width:10%" ><label class="columna">Empresa:</label></td>
							<td style="text-align:left;width:45%" >
								<div id="div_listaempresas">
									<select id="Cmb_Empresa" style="width:100%;height:25px" onChange="javascrip:ncsistema.Buscar_Permisos()">
										<option value="">[SELECCIONAR]</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td style="text-align:right;"><label class="columna">Usuario:</label></td>
							<td style="text-align:left;">	
								<div id="div_usuarioasignarpermisos">						
									<select id="Cmb_Usuario" style="width:100%;height:25px" onChange="javascrip:ncsistema.Buscar_Permisos()">
										<option value="">[SELECCIONAR]</option>
										<?php foreach ( $Listar_UsuarioDatos as $v):	
											if ($v['cod_tipusu']==2)
												{	
											?>
											<option value="<?php echo trim($v['cod_usu']); ?>">
											<?php echo trim(utf8_decode($v['apell_usu'])).' '.trim(utf8_decode($v['nom_usu'])).'  [ '.utf8_decode($v['email_usu']).' ]';?> </option>
										<?php }  endforeach; ?>
									</select>
								</div>
							</td>
							<td style="text-align:right;"><label class="columna">Perfiles:</label></td>
							<td style="text-align:left;">
								<div id="div_listaperfiles">
								<select id="Cmb_Perfiles" style="width:100%;height:25px" onChange="javascrip:ncsistema.Buscar_Permisos()">
									<option value="0">[SELECCIONAR]</option>									
								</select>
								</div>
							</td>
						</tr>
					</table>					
				</div>
				<div style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
					<div style="width:60%;height:30px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:6px;text-align:center;float:left">
						<div id="div_MensajeValidacionCliente" style="width:100%;float:left;font-size:9px"></div>
					</div>
				</div>
				<div style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
					<table width="100%" border="0" >
						<tr >
							<td style="width:48%;font-weight:bold; vertical-align:bottom">PERMISOS PENDIENTES DE ASIGNAR</td>
							<td style="width:4%;font-weight:bold"></td>
							<td style="width:48%;font-weight:bold; vertical-align:bottom">PERMISOS ASIGNADOS</td>
						</tr>
						<tr >
							<td style="vertical-align:top">
								<div id="div_ListadoParametros" style="width:97%;border:solid 0px;float:left;text-align:center;margin-top:3px;margin-left:5px;margin-bottom:5px">
								</div>
							</td>
							<td  style="vertical-align:middle">
								<a href="javascript:ncsistema.Guardar_Parametros()" >
									<button id="btn_PasarPermisos" class="ui-button ui-widget ui-state-default ui-corner-all " style="width:40px; height:32px" type="submit">
											<span class="ui-button-icon-center ui-icon ui-icon-shuffle"></span></button></a>
							</td>
							<td style="vertical-align:top">
								<div id="div_ListadoPermisosAsignados" style="width:97%;border:solid 0px;float:left;text-align:center;margin-top:3px;margin-left:5px;margin-bottom:5px">
								</div>
							</td>
						</tr>
					</table>					
				</div>	
			</div>
			<div id="tabs-3" style="width:97%;float:left">
			
				<table width="100%" border="0" >
					<tr align="left" >
						<td style="width:100%;font-weight:bold;">
							<a href="javascript:ncsistema.Listar_UsuarioPorEmpresa()" >
								<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="height:32px" type="submit">
										<span class="ui-button-icon-left ui-icon ui-icon-refresh"></span>
										<span class="ui-button-text">Mostrar Listado</span></button>
							</a>
						</td>
					</tr>
					<tr >
						<td style="vertical-align:top">
							<div style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
								<div id="div_usuarioporempresa" style="width:97%;border:solid 0px;float:left;text-align:center;margin-top:3px;margin-left:5px;margin-bottom:5px"> 
								</div>					
							</div>
					</td></tr>
				</table>
			</div>
		</div>
		
    </body>
	
	
</html>
