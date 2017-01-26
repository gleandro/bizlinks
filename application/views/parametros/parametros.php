<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Parámetros</title>
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
				ncsistema.Listar_Parametros();	
				
				
				$('#txt_valorentero').numeric();

			})
			
			ncsistema=
			{
				Nuevo_Parametro:function()
				{
					Limpiar_Parametros();
				},
				Guadar_Portalmultitabla:function()
				{

					var txt_codid=$.trim($('#txt_codid').val());
					
					var cmb_variables=$.trim($('#cmb_variables').val());
					var grupo_nombre=$("#cmb_variables option:selected").text();

					var txt_nombrevariable=$.trim($('#txt_nombrevariable').val());
					var txt_valorentero=$.trim($('#txt_valorentero').val());
					var txt_valorcadena=$.trim($('#txt_valorcadena').val());
					
					if (cmb_variables==0)
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione la variable</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					if (txt_nombrevariable=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el nombre de la variable</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;

					}
					if (txt_valorentero=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el valor numérico</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					//if (txt_valorcadena=='')
					//{
					//	$('#div_MensajeValidacionEmpresa').fadeIn(0);
					//	$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el valor texto</div>');
					//	setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					//	return;
					//}
					
					if (cmb_variables==7)
					{
						if (txt_valorentero>13)
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El valor máximo permitido es 13%.</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
					}
					if (txt_codid==0)
					{
						if (cmb_variables==1 || cmb_variables==2 || cmb_variables==3 || cmb_variables==4 || cmb_variables==7 || cmb_variables==8)
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">No se permite generar más registros de este tipo por ser globales.</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
					}else
					{
						if  (cmb_variables==8)
						{
							if (txt_valorcadena=='Configuracion_NO_Realizada')
							{
								if (txt_valorentero>1)
								{
									$('#div_MensajeValidacionEmpresa').fadeIn(0);
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Solo se permiten valores 0(Cálculo por Valor Venta) y 1 (Cálculo por Precio)</div>');
									setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
									return;
								}else
								{
									txt_valorcadena='Configuracion_SI_Realizada';
								}
								
							}else
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La configuración se realiza por única vez. Comuníquese con Bizlinks!</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}
					}
				
					if (txt_codid==0)//GUARDAR
					{
						$.ajax({
							url:'<?php echo base_url()?>parametros/Guadar_Portalmultitabla',
							type: 'post',
							dataType: 'json',
							data:
							{
								cmb_variables:cmb_variables,
								grupo_nombre:grupo_nombre,
								txt_nombrevariable:txt_nombrevariable,
								txt_valorentero:txt_valorentero,
								txt_valorcadena:txt_valorcadena
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

									Limpiar_Parametros();
									ncsistema.Listar_Parametros();
									return;
								}
								else if (result.status==2)
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionEmpresa').fadeIn(0);
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La variable ya está registrado</div>');
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
							url:'<?php echo base_url()?>parametros/Modificar_Portalmultitabla',
							type: 'post',
							dataType: 'json',
							data:
							{
								cmb_variables:cmb_variables,
								txt_codid:txt_codid,
								grupo_nombre:grupo_nombre,
								txt_nombrevariable:txt_nombrevariable,
								txt_valorentero:txt_valorentero,
								txt_valorcadena:txt_valorcadena
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

									Limpiar_Parametros();
									ncsistema.Listar_Parametros();

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

				Listar_Parametros:function()
				{
					$.ajax({
						url:'<?php echo base_url()?>parametros/Listar_Parametros',
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
								ncsistema.Listar_ParametrosTabla(result.data);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
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
					$('#div_ListadoSeriedocumentos').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th style="width:3%">Nro.</td>';						
						newHtml+='<th style="width:5%">Editar</td>';
						newHtml+='<th style="width:10%">Codigo</td>';
						newHtml+='<th style="width:35%">Nombre</td>';
						newHtml+='<th style="width:25%">Valor Entero</td>';
						newHtml+='<th style="width:25%">Cadena</td>';						
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
							newHtml+='<td style="text-align:center"><a href="javascript:VerDatosParametro_Modificar('+rs.id+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/Editar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
							newHtml+='<td style="text-align:left">'+rs.grupo_nombre+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nombre+'</td>';
							newHtml+='<td style="text-align:left">'+rs.valorentero+'</td>';
							newHtml+='<td style="text-align:left">'+rs.valorcadena+'</td>';							
														
							if (rs.est_reg==0)//ANULADO
							{
								newHtml+='<td style="text-align:left"></td>';								
							}
							else
							{
								newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Portalmultitabla('+rs.id+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
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
				
			}
			
			function Limpiar_Parametros()
			{
				$('#txt_codid').val('0');
				
				$('#cmb_variables').val('0');
				$('#txt_nombrevariable').val('');
				$('#txt_valorentero').val('');		
				$('#txt_valorcadena').val('');
				$('#div_DescripcionValorVenta_Precio').empty().append('');
				
				$("#cmb_variables").prop('disabled', false);
				$("#txt_valorcadena").prop('disabled', false);
				$("#txt_nombrevariable").prop('disabled', false);

				$('#div_Guardar').removeClass('disablediv');
				$("#div_Guardar").addClass("enablediv").on("onclick");	
			}
			



			function VerDatosParametro_Modificar(cod_id)
			{
				$.ajax
				({
					url:'<?php echo base_url()?>parametros/Listar_ParametrosId',type:'post',dataType:'json',
					data:
					{
						cod_id:cod_id
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
									$("#cmb_variables").prop('disabled', true);
									$.trim($('#txt_codid').val(rs.id));									
									$.trim($('#cmb_variables').val(rs.grupo_id));
									$.trim($('#txt_nombrevariable').val(rs.nombre));
									$.trim($('#txt_valorentero').val(rs.valorentero));
									$.trim($('#txt_valorcadena').val(rs.valorcadena));	
									if (rs.grupo_id==8)								
									{
										$("#txt_valorcadena").prop('disabled', true);
										$("#txt_nombrevariable").prop('disabled', true);
										if (rs.valorentero==0)
										{ $('#div_DescripcionValorVenta_Precio').empty().append('Cálculo por Valor Venta');
										}
										else
										{$('#div_DescripcionValorVenta_Precio').empty().append('Cálculo por Precio');
										}
										
									}else
									{
										$("#txt_valorcadena").prop('disabled', false);
										$("#txt_nombrevariable").prop('disabled', false);
										$('#div_DescripcionValorVenta_Precio').empty().append('');
									}
									
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
			
			
			function Eliminar_Portalmultitabla(cod_id)
			{
				if(confirm("¿ Esta Seguro de Eliminar la variable ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>parametros/Eliminar_Portalmultitabla',type:'post',dataType:'json',
						data:
						{
							cod_id:cod_id,
						},
						beforeSend:function()
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:20px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>')
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:20px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminaci&oacute;n de la serie se realiz&oacute; con &eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_Parametros();
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:20px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el correlativo</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
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
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">PARAMETROS</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">
				<div id="div_datosempresa" style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
					<input style="width:15%" type="hidden" id="txt_codid"  value="0" />
					<table border="0" width="40%" style="border-collapse:separate; border-spacing:8px 1px;" cellpadding="3" class="tablaFormulario">
						<tr><td colspan="3"><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right;width:25%"><label class="columna">Variable:</label></td>
							<td style="text-align:left;width:75%" colspan="2">
								<select id="cmb_variables" style="width:100%;height:22px" >
									<option value="0">[SELECCIONAR]</option>								
									<?php foreach ( $Listar_Variables as $v):	?>
										<option value="<?php echo trim($v['codigo']); ?>"><?php echo trim(utf8_decode($v['nombre']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Nombre:</label></td>
							<td style="text-align:left" colspan="2"><input style="width:90%" type="text" id="txt_nombrevariable" maxlength="100" /></td>
						</tr>
						
						<tr>
							<td style="text-align:right;width:25%"><label class="columna">Valor Entero:</label></td>
							<td style="text-align:left;width:15%">
								<input style="width:98%" type="text" id="txt_valorentero" maxlength="5" value="0" /></td>
							<td style="text-align:left;width:60%" align="left">
							<div id="div_DescripcionValorVenta_Precio"></div></td>
							
						</tr>
						
						<tr>
							<td style="text-align:right"><label class="columna">Valor Cadena:</label></td>
							<td style="text-align:left" colspan="2"><input style="width:90%" type="text" id="txt_valorcadena" maxlength="250"  /></td>
						</tr>
						
						<tr style="vertical-align:top" ><!--<td><label class="columna"></label></td>-->
							<td  style="vertical-align:top" colspan="3">
								<div style="width:100%;height:15px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:0px;text-align:center;float:left">
									<div id="div_MensajeValidacionEmpresa" style="width:100%;float:left;font-size:9px; color:#FF0000"></div>
								</div>
							</td></tr>
						
						<tr  style="align:left" >
							<td><label class="columna"></label></td>
							<td style="text-align:left" colspan="2">
								<table style="width:100%" border="0" >
								  <tbody>
									<tr style="align:left">
										<td style="text-align:right; width:50%">
											<a href="javascript:ncsistema.Nuevo_Parametro()" >
												<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px"  type="submit">
														<span class="ui-button-icon-left ui-icon ui-icon-document"></span>
														<span class="ui-button-text">Nuevo</span></button>
											</a>
										</td>
										<td style="text-align:left;width:50%">
											<a href="javascript:ncsistema.Guadar_Portalmultitabla()" >
												<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px"  type="submit">
														<span class="ui-button-icon-left ui-icon ui-icon-disk"></span>
														<span class="ui-button-text">Guardar</span></button>
											</a></td>
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

		</div>

    </body>	
</html>