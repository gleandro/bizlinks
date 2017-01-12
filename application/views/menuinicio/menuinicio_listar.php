<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Configuración</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		

		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.min.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/plugins/dataTable/css/dataTables-all.css" />

		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/inicio.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/menusystem.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/tabla_documento.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/botones.css"/>
		
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
				$.datepicker.setDefaults($.datepicker.regional["es"]);
				
				ncsistema.Listar_UsuarioSistema();
				ncsistema.Buscar_Permisos();
				ncsistema.Buscar_PermisosEmpresa();
				ncsistema.Listar_LicenciaUsuario();
				$("#tabs").tabs();
				
				$('#txt_fechainicio,#txt_fechafinal').datepicker({
					showOn: 'button',					
					buttonImage: "<?php echo base_url()?>application/helpers/image/ico/btnfecha.jpg",
					buttonImageOnly: true,
					dateFormat: 'dd/mm/yy',
					buttonText: "##/##/####",					
					changeMonth: true ,
					changeYear: true				
				});	
				
			});			
			ncsistema=
			{
			
				Listar_UsuarioSistema :function()
				{
					$.ajax({
						url:'<?php echo base_url()?>menuinicio/Listar_MenuInicio',
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
						newHtml+='<th style="width:3%">EDITAR</td>';
						//newHtml+='<th style="width:3%">SISTEMA</td>';
						newHtml+='<th style="width:10%">NIVEL 1</td>';
						newHtml+='<th style="width:10%">NIVEL 2</td>';
						newHtml+='<th style="width:10%">NIVEL 3</td>';
						newHtml+='<th style="width:10%">PADRE</td>';
						//newHtml+='<th style="width:10%">O.ME</td>';
						newHtml+='<th style="width:10%">O.EJ</td>';												
						newHtml+='<th style="width:20%">URL</td>';
						newHtml+='<th style="width:10%">T.HIJ</td>';						
						newHtml+='<th style="width:8%">I.CONF.</td>';
						newHtml+='<th style="width:8%">E.PAG</td>';
						newHtml+='<th style="width:8%">OP</td>';
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
							newHtml+='<td style="text-align:center"><a href="javascript:VerDatos_ModificarMenu('+rs.cod_men+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/Editar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
							//newHtml+='<td style="text-align:left">'+rs.nom_sist+'</td>';
							
							if (rs.cod_nivmen==1)
							{
								newHtml+='<td style="text-align:left">'+rs.nom_men+' ('+rs.order_men+')</td>';
								newHtml+='<td style="text-align:left"></td>';
								newHtml+='<td style="text-align:left"></td>';

							}
							else if (rs.cod_nivmen==2)
							{
								newHtml+='<td style="text-align:left"></td>';
								newHtml+='<td style="text-align:left">'+rs.nom_men+' ('+rs.order_men+')</td>';
								newHtml+='<td style="text-align:left"></td>';

							}
							else if (rs.cod_nivmen==3)
							{
								newHtml+='<td style="text-align:left"></td>';
								newHtml+='<td style="text-align:left"></td>';
								newHtml+='<td style="text-align:left">'+rs.nom_men+' ('+rs.order_men+')</td>';
	
							}
							/*
							else if (rs.cod_nivmen==4)
							{
								newHtml+='<td style="text-align:left"></td>';
								newHtml+='<td style="text-align:left"></td>';
								newHtml+='<td style="text-align:left"></td>';
								newHtml+='<td style="text-align:left">'+rs.nom_men+' ('+rs.order_men+')</td>';	
							}*/
							
							newHtml+='<td style="text-align:left">'+rs.menupadre+'</td>';
							//newHtml+='<td style="text-align:left"></td>';
							newHtml+='<td style="text-align:center">'+rs.ord_ejec+'</td>';
							newHtml+='<td style="text-align:left">'+rs.url_pag+'</td>';	
							newHtml+='<td style="text-align:center">'+rs.tien_hij+'</td>';
							newHtml+='<td style="text-align:center">'+rs.conf_inic+'</td>';	
							if (rs.tip_men==1)
							{
								newHtml+='<td style="text-align:center">NO</td>';

							}
							else
							{
								newHtml+='<td style="text-align:center">SI</td>';

							}
							newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Menu('+rs.cod_men+','+rs.ord_ejec+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
							
							
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
				
				
				
				Listar_LicenciaUsuario :function()
				{
					$.ajax({
						url:'<?php echo base_url()?>menuinicio/Listar_LiceciaAdministrador',
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
								ncsistema.Listar_LicenciaUsuarioTabla(result.data);
								
							}
							else
							{
								ncsistema.Listar_LicenciaUsuarioTabla("");
							}
						}
					});
					
				},
				
				Listar_LicenciaUsuarioTabla:function(data)
				{	

					$('#div_ListadoLicenciaUsuario').empty().append('');
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_LicenciaUsuario">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th style="width:3%">Nro.</td>';						
						newHtml+='<th style="width:10%">ADMIN</td>';
						newHtml+='<th style="width:10%">INICIO</td>';
						newHtml+='<th style="width:10%">FINAL</td>';
						newHtml+='<th style="width:10%">DIAS</td>';																	
						newHtml+='<th style="width:20%">PLAN</td>';
						newHtml+='<th style="width:10%">TIPO</td>';						
						newHtml+='<th style="width:8%">ACTIVADO</td>';
						newHtml+='<th style="width:10%">EST.LIC.</td>';	
						newHtml+='<th style="width:8%">OP</td>';
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';

					$.each(data,function(key,rs)
					{
						contador++;
						newHtml+='<tr>';							
							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';							
							newHtml+='<td style="text-align:left">'+rs.email_usuadm+'</td>';
							newHtml+='<td style="text-align:center">'+rs.fec_ini+'</td>';
							newHtml+='<td style="text-align:left">'+rs.fec_fin+'</td>';	
							newHtml+='<td style="text-align:center">'+rs.nro_dias+'</td>';								
							newHtml+='<td style="text-align:center">'+rs.nom_plan+'</td>';	
							newHtml+='<td style="text-align:left">'+rs.nom_tiplic+'</td>';
							newHtml+='<td style="text-align:left">'+rs.est_activo+'</td>';	
							newHtml+='<td style="text-align:left">'+rs.est_licencia+'</td>';
							newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Licencia('+rs.cod_sistlic+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
							
							
						newHtml+='</tr>';						
					});	
					newHtml+='</tbody>';
					newHtml+='</table>';
					
										
					$('#div_ListadoLicenciaUsuario').empty().append(newHtml);

					oTable=$('#Tab_LicenciaUsuario').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_LicenciaUsuario tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});

					
				},	
				

				Buscar_PermisosAdminUnitario :function(cod_usuadm)
				{
					if (cod_usuadm=='')
					{
						cod_usuadm=0;
					}
					$.ajax({
						url:'<?php echo base_url()?>menuinicio/Listar_LiceciaAdministradorUnitario',
						type: 'post',
						dataType: 'json',
						data:
						{
							cod_usuadm:cod_usuadm
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Buscar_PermisosAdminUnitarioTabla(result.data);
								
							}
							else
							{
								ncsistema.Buscar_PermisosAdminUnitarioTabla("");
							}
						}
					});
					
				},
				
				Buscar_PermisosAdminUnitarioTabla:function(data)
				{	

					$('#div_ListadoLicenciaUsuarioUnitario').empty().append('');
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_LicenciaUsuarioUnitario">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th style="width:3%">Nro.</td>';						
						newHtml+='<th style="width:10%">ADMIN</td>';
						newHtml+='<th style="width:10%">INICIO</td>';
						newHtml+='<th style="width:10%">FINAL</td>';
						newHtml+='<th style="width:10%">DIAS</td>';																	
						newHtml+='<th style="width:20%">PLAN</td>';
						newHtml+='<th style="width:10%">TIPO</td>';						
						newHtml+='<th style="width:8%">ACTIVO</td>';
						newHtml+='<th style="width:10%">EST.LIC.</td>';	
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';

					$.each(data,function(key,rs)
					{
						contador++;
						newHtml+='<tr>';							
							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';							
							newHtml+='<td style="text-align:left">'+rs.email_usuadm+'</td>';
							newHtml+='<td style="text-align:center">'+rs.fec_ini+'</td>';
							newHtml+='<td style="text-align:left">'+rs.fec_fin+'</td>';	
							newHtml+='<td style="text-align:center">'+rs.nro_dias+'</td>';							
							newHtml+='<td style="text-align:center">'+rs.nom_plan+'</td>';	
							newHtml+='<td style="text-align:left">'+rs.nom_tiplic+'</td>';
							newHtml+='<td style="text-align:left">'+rs.est_activo+'</td>';
							newHtml+='<td style="text-align:left">'+rs.est_licencia+'</td>';		
						newHtml+='</tr>';						
					});	
					newHtml+='</tbody>';
					newHtml+='</table>';
					
										
					$('#div_ListadoLicenciaUsuarioUnitario').empty().append(newHtml);

					oTable=$('#Tab_LicenciaUsuarioUnitario').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_LicenciaUsuarioUnitario tbody").click(function(event) 
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
					
					var txt_Cod_Men=$.trim($('#txt_Cod_Men').val());
					
					var Cmb_Sistema=$.trim($('#Cmb_Sistema').val());
					var txt_Nombre=$.trim($('#txt_Nombre').val());					
					var txt_DescMenu=$.trim($('#txt_DescMenu').val());
					var txt_urlPagina=$.trim($('#txt_urlPagina').val());
					var Cmb_Nivel=$.trim($('#Cmb_Nivel').val());
					var Cmb_MenuPadre=$.trim($('#Cmb_MenuPadre').val());
					
					var txt_OrdenEjecucion=$.trim($('#txt_OrdenEjecucion').val());
					var txt_OrdenMenu=$.trim($('#txt_OrdenMenu').val());
					
					var cbox_TieneHijos=0;
					if ($("#cbox_TieneHijos").is(":checked"))
					{
						cbox_TieneHijos=1;
					}
					
					var cbox_MenuInicio=0;
					if ($("#cbox_MenuInicio").is(":checked"))
					{
						cbox_MenuInicio=1;
					}
					
					var cbox_EjecPag=1;
					if ($("#cbox_EjecPag").is(":checked"))
					{
						cbox_EjecPag=2;
					}
					
					
					if (txt_Nombre=='' )
					{
						alert("Falta ingresar datos");
						return;
					}

					if (txt_Cod_Men==0)
					{
						$.ajax({
							url:'<?php echo base_url()?>menuinicio/Insertar_Menu',
							type: 'post',
							dataType: 'json',
							data:
							{
								Cmb_Sistema:Cmb_Sistema,
								txt_Nombre:	txt_Nombre,		
								txt_DescMenu:txt_DescMenu,
								txt_urlPagina:txt_urlPagina,
								Cmb_Nivel:Cmb_Nivel,
								Cmb_MenuPadre:Cmb_MenuPadre,						
								txt_OrdenEjecucion:txt_OrdenEjecucion,
								txt_OrdenMenu:txt_OrdenMenu,
								cbox_TieneHijos:cbox_TieneHijos,
								cbox_MenuInicio:cbox_MenuInicio,
								cbox_EjecPag:cbox_EjecPag				
							},
							beforeSend:function()
							{
								$('#div_Guardar').removeClass('enablediv');
								$("#div_Guardar").addClass("disablediv").off("onclick");
							},
							success:function(result)
							{
								if(result.status==1)
								{
									alert("Los datos se ingreso correctamente");
									ncsistema.Listar_UsuarioSistema();
									Limpiar_DatosUsuario();
									return;	
								}

								else
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									alert("error al registrar los datos");
									return;
								}
							}
						});
					}
					else
					{
						$.ajax({
							url:'<?php echo base_url()?>menuinicio/Modificar_Menu',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_Cod_Men:txt_Cod_Men,					
								Cmb_Sistema:Cmb_Sistema,
								txt_Nombre:	txt_Nombre,		
								txt_DescMenu:txt_DescMenu,
								txt_urlPagina:txt_urlPagina,
								Cmb_Nivel:Cmb_Nivel,
								Cmb_MenuPadre:Cmb_MenuPadre,						
								txt_OrdenEjecucion:txt_OrdenEjecucion,
								txt_OrdenMenu:txt_OrdenMenu,
								cbox_TieneHijos:cbox_TieneHijos,
								cbox_MenuInicio:cbox_MenuInicio,
								cbox_EjecPag:cbox_EjecPag
																	
							},
							beforeSend:function()
							{
								$('#div_Guardar').removeClass('enablediv');
								$("#div_Guardar").addClass("disablediv").off("onclick");
							},
							success:function(result)
							{
								if(result.status==1)
								{
									alert("La modificacion de los datos se realizo correctamente");
									ncsistema.Listar_UsuarioSistema();
									Limpiar_DatosUsuario();
	
								}else
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									alert("error al registrar los datos");
									
									return;
								}
							}
						});
					}

				},

				
				Guardar_Parametros:function (cod_men)
				{
					var Cmb_Planes=$.trim($('#Cmb_Planes').val());
					if (Cmb_Planes=='')
					{
						return;
					}
					$.ajax({
						url:'<?php echo base_url()?>menuinicio/Guardar_Parametros',
						type: 'post',
						dataType: 'json',
						data:
						{
							cod_men:cod_men,
							Cmb_Planes:Cmb_Planes	
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Buscar_Permisos();
								return;
							}
						}
					});					
				},
				
				
				Guardar_LicenciaUsuario:function ()
				{
					var Cmb_AdminLicencia=$.trim($('#Cmb_AdminLicencia').val());
					var Cmb_PlanAdmi=$.trim($('#Cmb_PlanAdmiLicen').val());
					var Cmb_Tipo=$.trim($('#Cmb_Tipo').val());
					var txt_fechainicio=$.trim($('#txt_fechainicio').val());
					var txt_fechafinal=$.trim($('#txt_fechafinal').val());					
					
					/*
					if (Cmb_Planes=='')
					{
						return;
					}
					*/
					
					$.ajax({
						url:'<?php echo base_url()?>menuinicio/Guardar_SystemLicencia',
						type: 'post',
						dataType: 'json',
						data:
						{
							Cmb_AdminLicencia:Cmb_AdminLicencia,
							Cmb_PlanAdmi:Cmb_PlanAdmi,
							Cmb_Tipo:Cmb_Tipo,
							txt_fechainicio:txt_fechainicio,	
							txt_fechafinal:txt_fechafinal
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_LicenciaUsuario();	
								ncsistema.Buscar_PermisosAdminUnitario();
								$('#Cmb_AdminLicencia').val('');
								$('#Cmb_PlanAdmiLicen').val('');
								$('#Cmb_Tipo').val('');
								$('#txt_fechainicio').val('');
								$('#txt_fechafinal').val('');
								return;
							}
						}
					});					
				},
				
				Guardar_MenuAdministrador:function (cod_men)
				{
					var Cmb_Administ=$.trim($('#Cmb_Administ').val());
					if (Cmb_Administ=='')
					{
						return;
					}
					$.ajax({
						url:'<?php echo base_url()?>menuinicio/Guardar_MenuAdministradorUnitario',
						type: 'post',
						dataType: 'json',
						data:
						{
							cod_men:cod_men,
							Cmb_Administ:Cmb_Administ	
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Buscar_PermisosEmpresa();
								return;
							}
						}
					});					
				},
				
				Guardar_PermisoAdmin:function (cod_men)
				{
					var Cmb_Administ=$.trim($('#Cmb_Administ').val());
					var Cmb_PlanAdmi=$.trim($('#Cmb_PlanAdmi').val());
					
					if (Cmb_Administ=='')
					{
						return;
					}
					if (Cmb_PlanAdmi=='')
					{
						return;
					}
					if (Cmb_PlanAdmi==0)
					{
						return;
					}
					
					$.ajax({
						url:'<?php echo base_url()?>menuinicio/Guardar_MenuAdministrador',
						type: 'post',
						dataType: 'json',
						data:
						{
							Cmb_Administ:Cmb_Administ,
							Cmb_PlanAdmi:Cmb_PlanAdmi	
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Buscar_PermisosEmpresa();
								return;
							}
						}
					});					
				},
				
				
				Listar_Parametros:function ()
				{
					var Cmb_Planes=$.trim($('#Cmb_Planes').val());
					
					if (Cmb_Planes=='')
					{
						return;
					}
					
					$.ajax({
						url:'<?php echo base_url()?>menuinicio/Listar_MenuSistemaPendiente',
						type: 'post',
						dataType: 'json',
						data:
						{
							Cmb_Planes:Cmb_Planes,
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

					$('#div_ListadoParametros').empty().append('');
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaAccesosPendientes">';
					newHtml+='<thead>';
					newHtml+='<tr>';
					
						newHtml+='<th style="width:10%">Nro.</td>';
						newHtml+='<th style="width:60%">Nombre</td>';
						newHtml+='<th style="width:10%">Asignar</td> ';

					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					$.each(data,function(key,rs)
					{
						newHtml+='<tr class="modo1">';

							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nom_men+'</td>';
							newHtml+='<td style="text-align:center"><input style="text-align:center" id="txt_variable_'+rs.cod_men+'" name="txt_variable_'+rs.cod_vari+'" onchange="javascrip:ncsistema.Guardar_Parametros('+rs.cod_men+')" type="checkbox" value="" /></td>';						
							
							
						newHtml+='</tr>';	
					});	
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_ListadoParametros').empty().append(newHtml);	

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
					var Cmb_Planes=$.trim($('#Cmb_Planes').val());
					
					if (Cmb_Planes=='')
					{
						return;
					}

					$.ajax({
						url:'<?php echo base_url()?>menuinicio/Listar_MenuSistemaAsignado',
						type: 'post',
						dataType: 'json',
						data:
						{
							Cmb_Planes:Cmb_Planes
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
					var Cmb_Planes=$.trim($('#Cmb_Planes').val());					
					
					if (Cmb_Planes=='')
					{
						ncsistema.Listar_ParametrosTabla('');
						ncsistema.Listar_PermisosAsignadosTabla('');
						return;
					}
					ncsistema.Listar_Parametros();
					ncsistema.Listar_PermisosAsignados();
				},
				
				
				
				
				Buscar_PermisosEmpresa:function()
				{
					var Cmb_Administ=$.trim($('#Cmb_Administ').val());
					var Cmb_PlanAdmi=$.trim($('#Cmb_PlanAdmi').val());
					
					if (Cmb_Administ=='')
					{
						ncsistema.Listar_MenuPendienteAdministradorTabla('');
						ncsistema.Listar_MenuAsignadosAdministradorTabla('');
						return;
					}
					
					if (Cmb_PlanAdmi=='0')
					{
						ncsistema.Listar_MenuPendienteAdministrador();
					}
					else
					{
						ncsistema.Listar_MenuPendienteAdministradorTabla('');
					}
					ncsistema.Listar_MenuAsignadosAdministrador();
				},

				Listar_MenuPendienteAdministrador:function ()
				{
					var Cmb_Administ=$.trim($('#Cmb_Administ').val());
					var Cmb_PlanAdmi=$.trim($('#Cmb_PlanAdmi').val());
					
					if (Cmb_Administ=='')
					{
						return;
					}
					if (Cmb_PlanAdmi==0)
					{
						$.ajax({
							url:'<?php echo base_url()?>menuinicio/Listar_MenuPendienteAdministrador',
							type: 'post',
							dataType: 'json',
							data:
							{
								Cmb_Administ:Cmb_Administ,
							},
							beforeSend:function()
							{
							},
							success:function(result)
							{
								if(result.status==1)
								{
									ncsistema.Listar_MenuPendienteAdministradorTabla(result.data);
								}
								else
								{
									ncsistema.Listar_MenuPendienteAdministradorTabla("");
								}
							}
						});	
					}				
				},			
				
				
				Listar_MenuPendienteAdministradorTabla:function(data)
				{

					$('#div_ListadoMenuAdminPendiente').empty().append('');
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaAccesosPendientesAdm">';
					newHtml+='<thead>';
					newHtml+='<tr>';
					
						newHtml+='<th style="width:10%">Nro.</td>';
						newHtml+='<th style="width:60%">Nombre</td>';
						newHtml+='<th style="width:10%">Asignar</td> ';

					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					$.each(data,function(key,rs)
					{
						newHtml+='<tr class="modo1">';

							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nom_men+'</td>';
							newHtml+='<td style="text-align:center"><input style="text-align:center" id="txt_variable_'+rs.cod_men+'" name="txt_variable_'+rs.cod_vari+'" onchange="javascrip:ncsistema.Guardar_MenuAdministrador('+rs.cod_men+')" type="checkbox" value="" /></td>';						
							
							
						newHtml+='</tr>';	
					});	
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_ListadoMenuAdminPendiente').empty().append(newHtml);	

					oTable=$('#Tab_ListaAccesosPendientesAdm').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_ListaAccesosPendientesAdm tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});

				
				},
				
				
				Listar_MenuAsignadosAdministrador:function ()
				{
					var Cmb_Administ=$.trim($('#Cmb_Administ').val());
					
					if (Cmb_Administ=='')
					{
						return;
					}

					$.ajax({
						url:'<?php echo base_url()?>menuinicio/Listar_MenuAsignadosAdministrador',
						type: 'post',
						dataType: 'json',
						data:
						{
							Cmb_Administ:Cmb_Administ
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_MenuAsignadosAdministradorTabla(result.data);
							}
							else
							{
								ncsistema.Listar_MenuAsignadosAdministradorTabla("");
							}
						}
					});					
				},			
				
				
				Listar_MenuAsignadosAdministradorTabla:function(data)
				{
					$('#div_ListadoMenuAdminAsignado').empty().append('');

					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListadoPermisosAsignadosAdm">';
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
							newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_AccesoAdministrador('+rs.cod_menadm+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
							
								
						newHtml+='</tr>';	
					});	
					
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_ListadoMenuAdminAsignado').empty().append(newHtml);	

					oTable=$('#Tab_ListadoPermisosAsignadosAdm').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_ListadoPermisosAsignadosAdm tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});
					
					
				}
				
			}


			function Limpiar_DatosUsuario()
			{									
				$('#txt_Cod_Men').val('0');
				$('#Cmb_Sistema').val('');
				
				$('#txt_Nombre').val('');
				$('#txt_DescMenu').val('');
				$('#txt_urlPagina').val('');
				$('#Cmb_Nivel').val('');
				$('#Cmb_MenuPadre').val('');
				$('#txt_OrdenEjecucion').val('');	
				$('#txt_OrdenMenu').val('');
				
				document.getElementsByName('cbox_TieneHijos')[0].checked=false;
				document.getElementsByName('cbox_MenuInicio')[0].checked=false;		
				document.getElementsByName('cbox_EjecPag')[0].checked=false;	
					
				
				$('#div_Guardar').removeClass('disablediv');
				$("#div_Guardar").addClass("enablediv").on("onclick");				
			
			}
			
			function VerDatos_ModificarMenu(cod_men)
			{
				document.getElementsByName('cbox_TieneHijos')[0].checked=false;
				document.getElementsByName('cbox_MenuInicio')[0].checked=false;		
				document.getElementsByName('cbox_EjecPag')[0].checked=false;		
				$.ajax
				({
					url:'<?php echo base_url()?>menuinicio/Listar_MenuInicioId',type:'post',dataType:'json',
					data:
					{
						cod_men:cod_men
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
								$('#txt_Cod_Men').val(rs.cod_men);
								$('#Cmb_Sistema').val(rs.cod_sist);									
								$('#txt_Nombre').val(rs.nom_men);
								$('#txt_DescMenu').val(rs.desc_men);
								$('#txt_urlPagina').val(rs.url_pag);
								
								$('#Cmb_Nivel').val(rs.cod_nivmen);
								$('#Cmb_MenuPadre').val(rs.cod_menpad);
								$('#txt_OrdenEjecucion').val(rs.ord_ejec);
								
								$('#txt_OrdenMenu').val(rs.order_men);
								if (parseInt(rs.tien_hij)==1)
								{
									document.getElementsByName('cbox_TieneHijos')[0].checked=true;
								}
								if (parseInt(rs.conf_inic)==1)
								{
									document.getElementsByName('cbox_MenuInicio')[0].checked=true;
								}								
								if (parseInt(rs.tip_men)==2)
								{
									document.getElementsByName('cbox_EjecPag')[0].checked=true;
								}
								Listar_MenuInicioNivelPadreSelect(rs.cod_nivmen,rs.cod_menpad);
							});			
						}
					}			
				});
			}	


			function Listar_MenuInicioNivelPadre(cod_nivel)
			{
				$('#div_MenuNivelPadre').empty().append('');
				
				$.ajax({
					url:'<?php echo base_url()?>menuinicio/Listar_MenuInicioNivel',
					type: 'post',
					dataType: 'json',
					data:
					{
						cod_nivel:(cod_nivel-1)
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(result.status==1)
						{
							newHtml='';		
							newHtml+='<select id="Cmb_MenuPadre" style="width:80%">';
							newHtml+='<option value="">[SELECCIONAR]</option>';
							$.each(result.data,function(key,rs)
							{
								newHtml+='<option value="'+rs.cod_men+'">'+rs.nom_men+'</option>';
							});
							newHtml+='</select>';
							$('#div_MenuNivelPadre').empty().append(newHtml);
							
						}else
						{
						    newHtml='';		
							newHtml+='<select id="Cmb_MenuPadre" style="width:80%">';
							newHtml+='<option value="">[SELECCIONAR]</option>';
							newHtml+='<option value="0">NINGUNO</option>';							
							newHtml+='</select>';
							$('#div_MenuNivelPadre').empty().append(newHtml);
						}
					}
				});
			}
			
			
			function Listar_MenuInicioNivelPadreSelect(cod_nivel,cod_padre)
			{
				$('#div_MenuNivelPadre').empty().append('');
				
				$.ajax({
					url:'<?php echo base_url()?>menuinicio/Listar_MenuInicioNivel',
					type: 'post',
					dataType: 'json',
					data:
					{
						cod_nivel:(cod_nivel-1)
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(result.status==1)
						{
							newHtml='';		
							newHtml+='<select id="Cmb_MenuPadre" style="width:80%">';
							newHtml+='<option value="">[SELECCIONAR]</option>';
							$.each(result.data,function(key,rs)
							{
								newHtml+='<option value="'+rs.cod_men+'">'+rs.nom_men+'</option>';
							});
							newHtml+='</select>';
							$('#div_MenuNivelPadre').empty().append(newHtml);
							
							$('#Cmb_MenuPadre').val(cod_padre);
						}else
						{
						    newHtml='';		
							newHtml+='<select id="Cmb_MenuPadre" style="width:80%">';
							newHtml+='<option value="">[SELECCIONAR]</option>';
							newHtml+='<option value="0">NINGUNO</option>';							
							newHtml+='</select>';
							$('#div_MenuNivelPadre').empty().append(newHtml);
							$('#Cmb_MenuPadre').val(cod_padre);
						}
					}
				});
			}
			
			
			
			function Eliminar_Menu(cod_men,ord_ejec)
			{
				if(confirm("¿ Esta Seguro de Eliminar el menu ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>menuinicio/Eliminar_Menu',type:'post',dataType:'json',
						data:
						{
							cod_men:cod_men,
							ord_ejec:ord_ejec
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								alert("La eliminacion de los datos se realizo correctamente");
								ncsistema.Listar_UsuarioSistema();
								Limpiar_DatosUsuario();
								return;							
								
	
							}else
							{
								alert("Error al eliminar los datos");
								return;
							}
						}			
					});
				}
				
			}
			
			
			
			function Eliminar_Acceso(cod_usuacc)
			{
				if(confirm("¿ Esta Seguro de Eliminar El Accesso ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>menuinicio/Eliminar_Acceso',type:'post',dataType:'json',
						data:
						{
							cod_usuacc:cod_usuacc
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Buscar_Permisos();
								return;
	
							}else
							{
								return;
							}
						}			
					});
				}
			}
			
			function Eliminar_AccesoAdministrador(cod_usuacc)
			{
				if(confirm("¿ Esta Seguro de Eliminar El Accesso ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>menuinicio/Eliminar_AccesoAdministrador',type:'post',dataType:'json',
						data:
						{
							cod_usuacc:cod_usuacc
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Buscar_PermisosEmpresa();
								return;
	
							}else
							{
								return;
							}
						}			
					});
				}
			}
			
			
			function Eliminar_Licencia(cod_sistlic)
			{
				if(confirm("¿ Esta Seguro de Eliminar el periodo de Licencia ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>menuinicio/Eliminar_PeriodoLicencia',type:'post',dataType:'json',
						data:
						{
							cod_sistlic:cod_sistlic
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_LicenciaUsuario();
								return;
	
							}else
							{
								return;
							}
						}			
					});
				}
			}
			
		</script>

    </head>   
    <body>
<?php header('Content-Type: text/html; charset=ISO-8859-1');?>

		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">MENU SISTEMA</a></li>
				<li><a href="#tabs-2">MENU PLAN</a></li>
				<li><a href="#tabs-3">LICENCIA POR EMPRESA</a></li>
				<li><a href="#tabs-4">GESTION DE LICENCIA</a></li>
				
			</ul>			
			<div id="tabs-1" style="width:97%;float:left">
				<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:5px">
					<div style="width:10%;border:solid 0px;float:left;text-align:center;margin-left:5px">							
						<div id="div_Nuevo" style="border:solid 0px;float:left;text-align:center">
							<a href="javascript:ncsistema.Nuevo_Usuario()" >
								<img id="img_nuevo_registro" src="<?php echo base_url();?>application/helpers/image/iconos/nuevoregistro.jpg" width="40" height="40" />
							</a>
						</div>
						<div style="height:5px;width:20px;float:left"></div>
						<div id="div_Guardar" style="border:solid 0px;float:left;text-align:center" >
							<a href="javascript:ncsistema.Guardar_Usuario()" >
								<img id="img_guardar_registro" src="<?php echo base_url();?>application/helpers/image/iconos/guardar.jpg" width="40" height="40" />
							</a>								
						</div>
										
					</div>	
					<div style="width:60%;height:30px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:6px;text-align:center;float:left">
						<div id="div_MensajeValidacionmenu" style="width:100%;float:left;font-size:9px"></div>
					</div>
				</div>

				<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:5px">	
					<input id="txt_Cod_Men" type="hidden" value="0" />				
					<table border="0" width="100%" class="tablaFormulario">						
						<tr>
							<td style="text-align:right;width:8%"> Sistema:</td>
							<td style="text-align:left;width:30%">
								<select id="Cmb_Sistema" style="width:80%">
									<option value="">[SELECCIONAR]</option>									
									<?php foreach ( $Listar_Sistema as $v):	
										?>
										<option value="<?php echo trim($v['cod_sist']); ?>"><?php echo $v['nom_sist'];?> </option>
									<?php endforeach; ?>
								</select>
							</td>
							<td style="text-align:right">Orden Menu.:</td>
							<td style="text-align:left"><input style="width:90%" type="text" id="txt_OrdenMenu"  /></td>
							</td>	
						</tr>
		
						<tr>
							<td style="text-align:right"> Nombre:</td>
							<td style="text-align:left"><input style="width:90%" type="text" id="txt_Nombre"  /></td>
							<td style="text-align:right">Desc.menu:</td>
							<td style="text-align:left"><input style="width:90%" type="text" id="txt_DescMenu"  /></td>	
						</tr>
						
						<tr>
							<td style="text-align:right"> Url Pagina:</td>
							<td style="text-align:left"><input style="width:90%" type="text" id="txt_urlPagina"  /></td>
							<td style="text-align:right"> Nivel:</td>
							<td style="text-align:left">
								<select id="Cmb_Nivel" style="width:50%" onChange="javascrip:Listar_MenuInicioNivelPadre(this.value)">
									<option value="">[SELECCIONAR]</option>
									<option value="1">NIVEL 1</option>
									<option value="2">NIVEL 2</option>
									<option value="3">NIVEL 3</option>
									<option value="4">NIVEL 4</option>
								</select>
							</td>
						</tr>						
						<tr>							
							<td style="text-align:right">Menu Padre:</td>
							<td style="text-align:left">
								<div id="div_MenuNivelPadre">
									<select id="Cmb_MenuPadre" style="width:80%">
										<option value="">[SELECCIONAR]</option>
										<option value="0">NINGUNO</option>
									</select>
								</div>
							</td>	
							<td style="text-align:right">Orden Ejec.:</td>
							<td style="text-align:left"><input style="width:90%" type="text" id="txt_OrdenEjecucion"  /></td>	
						</tr>						
						<tr>
							<td style="text-align:right"> Tiene Hijos:</td>
							<td style="text-align:left">
								<input id="cbox_TieneHijos" name="cbox_TieneHijos" type="checkbox" value="" />
								Ejecuta la Pagina
								<input id="cbox_EjecPag" name="cbox_EjecPag" type="checkbox" value="" />
							</td>
							<td style="text-align:right">Conf.Inicio:</td>
							<td style="text-align:left">
								<input id="cbox_MenuInicio" name="cbox_MenuInicio" type="checkbox" value="" />
							</td>	
						</tr>						
					</table>		
							
				</div>
				<div id="div_listausuarios" style="width:100%;float:left;text-align:center;margin-top:7px"> </div>		
			</div>
			
			<div id="tabs-2" style="width:97%;float:left">
				<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:5px">
					<div style="width:10%;border:solid 0px;float:left;text-align:center;margin-left:5px">							
						<div id="div_Nuevo" style="border:solid 0px;float:left;text-align:center">
							<a href="javascript:ncsistema.Nuevo_Usuario()" >
								<img id="img_nuevo_registro" src="<?php echo base_url();?>application/helpers/image/iconos/nuevoregistro.jpg" width="40" height="40" />
							</a>
						</div>
						<div style="height:5px;width:20px;float:left"></div>
						<div id="div_Guardar" style="border:solid 0px;float:left;text-align:center" >
							<a href="javascript:ncsistema.Guardar_Usuario()" >
								<img id="img_guardar_registro" src="<?php echo base_url();?>application/helpers/image/iconos/guardar.jpg" width="40" height="40" />
							</a>								
						</div>
										
					</div>	
					<div style="width:60%;height:30px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:6px;text-align:center;float:left">
						<div id="div_MensajeValidacionmenu" style="width:100%;float:left;font-size:9px"></div>
					</div>
				</div>
				
				<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:10px">
					
					<table width="100%" border="0px" class="tablaFormulario">
						<tr>
							<td style="text-align:right;width:12%" >Nuestros Planes:</td>
							<td style="text-align:left;width:85%" >
								<select id="Cmb_Planes" style="width:40%;height:30px" onChange="javascrip:ncsistema.Buscar_Permisos(this.value)">
									<option value="">[SELECCIONAR]</option>
									<?php foreach ( $Listar_TipoLicencia as $v):	?>
										<option value="<?php echo trim($v['cod_tiplic']); ?>"><?php echo trim(utf8_encode($v['nom_tiplic']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
						</tr>
					</table>
					
				</div>
				
				<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:10px">
				
					<div id="div_ListadoParametros" style="width:47%;border:solid 0px;float:left;text-align:center;margin-top:7px;margin-left:5px;margin-bottom:5px">
					</div>
					
					<div id="div_ListadoPermisosAsignados" style="width:48%;border:solid 0px;float:left;text-align:center;margin-top:7px;margin-left:5px;margin-bottom:5px">
					</div>
					
				</div>	
			</div>
			
			
			
			<div id="tabs-3" style="width:97%;float:left">
				<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:5px">
					<div style="width:10%;border:solid 0px;float:left;text-align:center;margin-left:5px">							
						<div id="div_Nuevo" style="border:solid 0px;float:left;text-align:center">
							<a href="javascript:ncsistema.Nuevo_PermisoAdmin()" >
								<img id="img_nuevo_registro" src="<?php echo base_url();?>application/helpers/image/iconos/nuevoregistro.jpg" width="40" height="40" />
							</a>
						</div>
						<div style="height:5px;width:20px;float:left"></div>
						<div id="div_Guardar" style="border:solid 0px;float:left;text-align:center" >
							<a href="javascript:ncsistema.Guardar_PermisoAdmin()" >
								<img id="img_guardar_registro" src="<?php echo base_url();?>application/helpers/image/iconos/guardar.jpg" width="40" height="40" />
							</a>								
						</div>
										
					</div>	
					<div style="width:60%;height:30px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:6px;text-align:center;float:left">
						<div id="div_MensajeValidacionLicEmp" style="width:100%;float:left;font-size:9px"></div>
					</div>
				</div>
				
				<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:10px">
					
					<table width="100%" border="0px" class="tablaFormulario">
						<tr>
							<td style="text-align:right;width:12%" >Administradores:</td>
							<td style="text-align:left;width:85%" >
								<select id="Cmb_Administ" style="width:80%;height:30px" onChange="javascrip:ncsistema.Buscar_PermisosEmpresa()">
									<option value="">[SELECCIONAR]</option>
									<?php foreach ( $Listar_UsuariosAdministradores as $v):	?>
										<option value="<?php echo trim($v['cod_usuadm']); ?>"><?php echo $v['email_usuadm'].' ('.$v['nom_usu'].' '.$v['apell_usu'].')';?> </option>
									<?php  endforeach; ?>
								</select>
							</td>							
						</tr>
						<tr>
							<td style="text-align:right;width:12%" >Planes:</td>
							<td style="text-align:left;width:85%" >
								<select id="Cmb_PlanAdmi" style="width:20%;height:30px" onChange="javascrip:ncsistema.Buscar_PermisosEmpresa()">
									<option value="">[SELECCIONAR]</option>
										<option value="0">NINGUNO</option>
									<?php foreach ( $Listar_TipoLicencia as $v):	?>
										<option value="<?php echo trim($v['cod_tiplic']); ?>"><?php echo trim(utf8_encode($v['nom_tiplic']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>							
						</tr>
					</table>
					
				</div>
				
				<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:10px">
				
					<div id="div_ListadoMenuAdminPendiente" style="width:47%;border:solid 0px;float:left;text-align:center;margin-top:7px;margin-left:5px;margin-bottom:5px">
					</div>
					
					<div id="div_ListadoMenuAdminAsignado" style="width:48%;border:solid 0px;float:left;text-align:center;margin-top:7px;margin-left:5px;margin-bottom:5px">
					</div>
					
				</div>	
			</div>
			

			<div id="tabs-4" style="width:97%;float:left">
				<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:5px">
					<div style="width:10%;border:solid 0px;float:left;text-align:center;margin-left:5px">							
						<div id="div_Nuevo" style="border:solid 0px;float:left;text-align:center">
							<a href="javascript:ncsistema.Nuevo_PermisoEmp()" >
								<img id="img_nuevo_registro" src="<?php echo base_url();?>application/helpers/image/iconos/nuevoregistro.jpg" width="40" height="40" />
							</a>
						</div>
						<div style="height:5px;width:20px;float:left"></div>
						<div id="div_Guardar" style="border:solid 0px;float:left;text-align:center" >
							<a href="javascript:ncsistema.Guardar_LicenciaUsuario()" >
								<img id="img_guardar_registro" src="<?php echo base_url();?>application/helpers/image/iconos/guardar.jpg" width="40" height="40" />
							</a>								
						</div>
										
					</div>	
					<div style="width:60%;height:30px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:6px;text-align:center;float:left">
						<div id="div_MensajeValidacionLicEmp" style="width:100%;float:left;font-size:9px"></div>
					</div>
				</div>
				
				<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:10px">
					
					<table width="100%" border="0px" class="tablaFormulario">
						<tr>
							<td style="text-align:right;width:12%" >Administradores:</td>
							<td colspan="3" style="text-align:left;width:85%" >
								<select id="Cmb_AdminLicencia" style="width:80%;height:30px" onChange="javascrip:ncsistema.Buscar_PermisosAdminUnitario(this.value)">
									<option value="">[SELECCIONAR]</option>
									<?php foreach ( $Listar_UsuariosAdministradores as $v):	?>
										<option value="<?php echo trim($v['cod_usuadm']); ?>"><?php echo $v['email_usuadm'].' ('.$v['nom_usu'].' '.$v['apell_usu'].')';?> </option>
									<?php  endforeach; ?>
								</select>
							</td>							
						</tr>
						<tr>
							<td style="text-align:right;width:12%" >Planes:</td>
							<td style="text-align:left;width:38%" >
								<select id="Cmb_PlanAdmiLicen" style="width:70%;height:30px" >
									<option value="">[SELECCIONAR]</option>
									<?php foreach ( $Listar_TipoLicencia as $v):	?>
										<option value="<?php echo trim($v['cod_tiplic']); ?>"><?php echo trim(utf8_encode($v['nom_tiplic']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>	
							<td style="text-align:right;width:10%" >Tipo:</td>
							<td style="text-align:left;width:40%" >
								<select id="Cmb_Tipo" style="width:70%;height:30px" >
									<option value="">[SELECCIONAR]</option>
									<option value="1">PRUEBA</option>
									<option value="2">PAGO</option>
								</select>
							</td>							
						</tr>

						<tr>
							<td style="text-align:right">Inicio:</td>
							<td style="text-align:left;width:30%"><input style="width:40%" id="txt_fechainicio" type="text" disabled value="" /></td>										
							<td style="text-align:right">Final:</td>
							<td style="text-align:left;width:30%"><input style="width:40%" id="txt_fechafinal" type="text" disabled value="" /></td>	
						</tr>

					</table>
					
				</div>
				
				<div id="div_ListadoLicenciaUsuario" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:10px"></div>
				
				<div id="div_ListadoLicenciaUsuarioUnitario" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:10px"></div>	
				
			</div>
			
			
		</div>
    </body>
	
	
</html>