<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Perfil</title>
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
		
		
		<script>var urlexportardatos="<?php echo base_url();?>"</script>	


		<script type="text/javascript">
			
			$(document).ready(function()
			{
				ncsistema.Listar_UsuarioSistema();
				ncsistema.Listar_ParametrosTabla('');
				ncsistema.Listar_PermisosAsignadosTabla('');
				$("#tabs").tabs();
			});			
			ncsistema=
			{
			
				Listar_UsuarioSistema :function()
				{
					$.ajax({
						url:'<?php echo base_url()?>perfil/Listar_Perfil',
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
								ncsistema.Listar_UsuarioSistemaTabla(result.data);
								
							}
							else
							{
								ncsistema.Listar_UsuarioSistemaTabla("");
							}
							//$('#div_procesarbuscar').empty().append('');
						}
					});
					
				},
				
				Listar_UsuarioSistemaTabla:function(data)
				{	
				
					//class="tabla"  border="0" cellpadding="0" cellspacing="0"  class="display" id="Tablst_ListaClientesPorVendedor"
					$('#div_listausuarios').empty().append('');
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaUsuarios">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th style="width:3%">Nro.</td>';						
						newHtml+='<th style="width:3%">Editar</td>';
						newHtml+='<th style="width:3%">Roles</td>';
						newHtml+='<th style="width:9%">Orden</td>';
						newHtml+='<th style="width:9%">Rol</td>';
						newHtml+='<th style="width:6%">Eliminar</td>';	

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
							newHtml+='<td style="text-align:center"><a href="javascript:VerDatos_ModificarUsuario('+rs.cod_perfil+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/Editar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
							newHtml+='<td style="text-align:left">'+rs.nom_perfil+'</td>';
							newHtml+='<td style="text-align:left">'+rs.cod_inter+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nomb_rol+'</td>';
							newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Usuario('+rs.cod_perfil+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';

							
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
					var txt_Login=$.trim($('#txt_Login').val());
					var txt_Orden=$.trim($('#txt_Orden').val());
					var cmb_tipoderoles=$.trim($('#cmb_tipoderoles').val());
					


					if (txt_Login=='')
					{
						$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
						$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el rol del usuario</div>');
						setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
						return;
					}

					if (txt_Orden=='')
					{
						$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
						$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el orden de los registros</div>');
						setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
						return;
					}
					
					if (cmb_tipoderoles=='')
					{
						$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
						$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el rol del perfil</div>');
						setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
						return;
					}					

					if (txt_Cod_Usu==0)
					{
						$.ajax({
							url:'<?php echo base_url()?>perfil/Guardar_Perfil',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_Cod_Usu:txt_Cod_Usu,
								txt_Login:txt_Login,
								txt_Orden:txt_Orden,
								cmb_tipoderoles:cmb_tipoderoles	
							},
							beforeSend:function()
							{
								$('#div_Guardar').removeClass('enablediv');
								$("#div_Guardar").addClass("disablediv").off("onclick");

								$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
								$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
							},
							success:function(result)
							{
								if(result.status==1)
								{
									$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
									$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El registro de los datos se realiz&oacute; con &Eacute;xito</div>');
									setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
									ncsistema.Listar_UsuarioSistema();
									Limpiar_DatosUsuario();
									return;	
								}							
								else
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
									$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al registrar los datos</div>');
									setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
									return;
								}
							}
						});
					}
					else
					{
						$.ajax({
							url:'<?php echo base_url()?>perfil/Modificar_Perfil',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_Cod_Usu:txt_Cod_Usu,
								txt_Login:txt_Login,
								txt_Orden:txt_Orden	,
								cmb_tipoderoles:cmb_tipoderoles				
							},
							beforeSend:function()
							{
								$('#div_Guardar').removeClass('enablediv');
								$("#div_Guardar").addClass("disablediv").off("onclick");

								$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
								$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
							},
							success:function(result)
							{
								if(result.status==1)
								{
									$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
									$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La modificaci&oacute;n de los datos se realiz&oacute; con &Eacute;xito</div>');
									setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
									ncsistema.Listar_UsuarioSistema();
									Limpiar_DatosUsuario();
	
								}else
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
									$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al modificar los datos</div>');
									setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
									return;
								}
							}
						});
					}

				},
				
				Listar_Parametros:function ()
				{
					var Cmb_Roles=$.trim($('#Cmb_Roles').val());

					if (Cmb_Roles==0)
					{
						return;
					}
					
					$.ajax({
						url:'<?php echo base_url()?>perfil/Listar_MenuSistemaPendiente',
						type: 'post',
						dataType: 'json',
						data:
						{
							Cmb_Roles:Cmb_Roles
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
					$('#txt_ListaPermisosAsignarAll').val('');
					var cadenamenu='';
					
					$('#div_ListadoParametros').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaAccesosPendientes">';
					newHtml+='<thead>';
					newHtml+='<tr>';
					
						newHtml+='<th style="width:10%">Nro.</td>';
						newHtml+='<th style="width:60%">Nombre</td>';
						//newHtml+='<th style="width:10%">Asignar</td> ';
						newHtml+='<th style="width:10%"><input style="text-align:center" id="txt_variable_all" name="txt_variable_all" onchange="javascrip:Seleccionar_PermisosAsignarAll()" type="checkbox" value="" /></td> ';

					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					$.each(data,function(key,rs)
					{
						newHtml+='<tr class="modo1">';

							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nom_men+'</td>';
							//<input style="text-align:center" id="txt_variable_'+rs.cod_men+'" name="txt_variable_'+rs.cod_men+'" onchange="javascrip:ncsistema.Guardar_Parametros('+rs.cod_men+')" type="checkbox" value="" /></td>';
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
					var Cmb_Roles=$.trim($('#Cmb_Roles').val());
					
					if (Cmb_Roles==0)
					{
						return;
					}
					
					$.ajax({
						url:'<?php echo base_url()?>perfil/Listar_MenuSistemaAsignado',
						type: 'post',
						dataType: 'json',
						data:
						{
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
						newHtml+='<th style="width:60%">Nombre</td>';
						newHtml+='<th style="width:10%">OP</td> ';
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';

					$.each(data,function(key,rs)
					{
						newHtml+='<tr class="modo1">';
							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nom_men+'</td>';
							newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Acceso('+rs.cod_mepr+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
							
								
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
					var Cmb_Roles=$.trim($('#Cmb_Roles').val());
					if (Cmb_Roles==0)
					{
						ncsistema.Listar_ParametrosTabla('');
						ncsistema.Listar_PermisosAsignadosTabla('');
						return;
					}					
					ncsistema.Listar_Parametros();
					ncsistema.Listar_PermisosAsignados();
				},
				
				Guardar_Parametros:function ()
				{
					var Cmb_Roles=$.trim($('#Cmb_Roles').val());
					var txt_ListaPermisosAsignar=$.trim($('#txt_ListaPermisosAsignar').val());

					if (Cmb_Roles=='')
					{
						return;
					}
					$.ajax({
						url:'<?php echo base_url()?>perfil/Guardar_Perfiles',
						type: 'post',
						dataType: 'json',
						data:
						{
							//cod_men:cod_men,
							txt_ListaPermisosAsignar:txt_ListaPermisosAsignar,
							Cmb_Roles:Cmb_Roles
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionCliente').fadeIn(0);
								$('#div_MensajeValidacionCliente').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La actualizaci&oacute;n de los datos se realiz&oacute; con &Eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionCliente").fadeOut(1500);},3000);
								ncsistema.Buscar_Permisos();
								return;
							}
						}
					});					
				},
			}

			function Limpiar_DatosUsuario()
			{				
				$('#txt_Cod_Usu').val('0');
				$('#txt_Login').val('');				
				$('#txt_Orden').val('');						
				$('#cmb_tipoderoles').val('');		
				$('#div_Guardar').removeClass('disablediv');
				$("#div_Guardar").addClass("enablediv").on("onclick");			
			}
			
			function VerDatos_ModificarUsuario(cod_roles)
			{
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
							$.each(result.data,function(key,rs)
							{
								if (rs.cod_perfil==cod_roles)
								{
									$('#txt_Cod_Usu').val(rs.cod_perfil);
									$('#txt_Login').val(rs.nom_perfil);									
									$('#txt_Orden').val(rs.cod_inter);
									$('#cmb_tipoderoles').val(rs.cod_rol);
								}
							});
			
						}

					}
			
				});
			}	


			function Eliminar_Usuario(cod_roles)
			{

				if(confirm("¿ Esta Seguro de Eliminar el Rol ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>perfil/Eliminar_Perfil',type:'post',dataType:'json',
						data:
						{
							cod_roles:cod_roles,
						},
						beforeSend:function()
						{
							$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
							$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
								$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminaci&oacute;n del usuario se realiz&oacute; con &Eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
								ncsistema.Listar_UsuarioSistema();
								Limpiar_DatosUsuario();
	
							}else
							{
								$('#div_MensajeValidacionProductoCategoria').fadeIn(0);
								$('#div_MensajeValidacionProductoCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el usuario</div>');
								setTimeout(function(){ $("#div_MensajeValidacionProductoCategoria").fadeOut(1500);},3000);
								return;
							}
						}			
					});
				}
			}

			
			
			
			function Eliminar_Acceso(cod_mepr)
			{
				if(confirm("¿ Esta Seguro de Eliminar El Accesso ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>perfil/Eliminar_Perfiles',type:'post',dataType:'json',
						data:
						{
							cod_mepr:cod_mepr,
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionCliente').fadeIn(0);
								$('#div_MensajeValidacionCliente').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminación de los datos se realiz&oacute; con &Eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionCliente").fadeOut(1500);},3000);
								ncsistema.Buscar_Permisos();
								return;
	
							}else
							{
								$('#div_MensajeValidacionCliente').fadeIn(0);
								$('#div_MensajeValidacionCliente').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el accesos </div>');
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
			
		</script>
		
		

</head>   
<body>
<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
	<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">PERFILES</a></li>
				<li><a href="#tabs-2">ASIGNACIÓN DE PERMISOS</a></li>
			</ul>			
			
			<div id="tabs-1" style="width:97%;float:left">
				<div style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">	
					<input id="txt_Cod_Usu" type="hidden" value="0" />				
					<table border="0" width="40%" style="border-collapse:separate; border-spacing:8px 1px;" cellpadding="3" class="tablaFormulario">
						<tr>
							<td style="text-align:right;width:20%"><label class="columna">Nombre Perfil :</label></td>
							<td style="text-align:left;width:25%"><input style="width:90%" type="text" id="txt_Login"  /></td>
						</tr>
						<tr>
							<td style="text-align:right;width:15%"><label class="columna">Orden :</label></td>
							<td style="text-align:left;width:40%">
								<input style="width:10%" type="text" id="txt_Orden"  /></td>	
						</tr>
						<tr>
							<td style="text-align:right;width:15%"><label class="columna">Roles :</label></td>
							<td style="text-align:left;width:40%">
								<select id="cmb_tipoderoles" style="width:95%;height:22px" >
									<option value="">[SELECCIONAR]</option>
									<?php foreach ( $Listar_Roles as $v):	?>
										<option value="<?php echo trim($v['cod_rol']); ?>">
											<?php echo trim(utf8_decode($v['desc_rol']));?> 
										</option>
									<?php  endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right"><label class="columna"></label></td>
						<tr>
							<td><label class="columna"></label></td>
							<td colspan="3" >
								<table style="width:50%" >
								  <tbody>
									<tr>
										<td style="text-align:right; width:50%">
											<a href="javascript:ncsistema.Nuevo_Usuario()" >
												<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px"  type="submit">
													<span class="ui-button-icon-left ui-icon ui-icon-document"></span>
													<span class="ui-button-text">Nuevo</span></button>
											</a>
										</td>
										<td style="text-align:left;width:50%">
											<a href="javascript:ncsistema.Guardar_Usuario()" >
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
				<div style="width:60%;height:20px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:6px;text-align:center;float:left">
					<div id="div_MensajeValidacionProductoCategoria" style="width:100%;float:left;font-size:9px"; color:"#FF0000"></div>
				</div>
				<div id="div_listausuarios" style="width:100%;float:left;text-align:center;margin-top:7px"> </div>
			</div>

			<div id="tabs-2" style="width:97%;float:left">
				
				<div style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">					
					<input type="hidden" value="" id="txt_ListaPermisosAsignar"  />		
					<input type="hidden" value="" id="txt_ListaPermisosAsignarAll"  />	
					<table width="100%" border="0px" style="border-collapse:separate; border-spacing:8px 1px;" cellpadding="3" class="tablaFormulario">
						<tr>
							<td style="text-align:right;width:10%" ><label class="columna">Perfiles :</label></td>
							<td style="text-align:left;width:90%" >
								<select id="Cmb_Roles" style="width:20%;height:22px" onChange="javascrip:ncsistema.Buscar_Permisos(this.value)">
									<option value="">[SELECCIONAR]</option>
									<?php foreach ( $Listar_Perfil as $v):	?>
										<option value="<?php echo trim($v['cod_perfil']); ?>"><?php echo trim(utf8_encode($v['nom_perfil']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
						</tr>
						
					</table>					
				</div>
				<div style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
					<div style="width:60%;height:20px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:0px;text-align:center;float:left">
						<div id="div_MensajeValidacionCliente" style="width:100%;float:left;font-size:9px;color:#FF0000"></div>
					</div>
				</div>
				
				<div style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
					<table width="100%" border="0">
						<tr>
							<td style="width:50%;font-weight:bold">PERMISOS PENDIENTES DE ASIGNAR</td>´
							<td style="width:4%;font-weight:bold"></td>
							<td style="width:50%;font-weight:bold">PERMISOS ASIGNADOS</td>
						</tr>
						<tr>
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
			
		</div>

    </body>
	
</html>