<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Productos</title>
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
				ncsistema.Listar_Productos();
				$('#txt_valorentero').numeric({allow:'.'});
				$('#txt_precio').numeric({allow:'.'});
				ncsistema.get_Configuracion_ValorPrecio();
			})
			
			ncsistema=
			{
				
				get_Configuracion_ValorPrecio:function()
				{
					$.ajax({
						url:'<?php echo base_url()?>producto/get_Configuracion_ValorPrecio',
						type: 'post',
						dataType: 'json',
						data:
						{
						},
						beforeSend:function()
						{},
						success:function(valor)
						{
							if (valor==0)
							{
								obj1 = document.getElementById('lbl_referencia_valor');
								$("#txt_precio").prop('disabled', true);
								obj1.style.display="none"
							}else
							{
								obj2 = document.getElementById('lbl_referencia_precio');
								$("#txt_valorentero").prop('disabled', true);
								obj2.style.display="none"
							}
						}
					});	
				},
				
				Nuevo_Producto:function()
				{
					Limpiar_Parametros();
				},
				
				Guadar_Producto:function()
				{ 
					var txt_id=$.trim($('#txt_id').val());
					var txt_codigo=$.trim($('#txt_codigo').val());
					var txt_nombrecorto=$.trim($('#txt_nombrecorto').val());
					var txt_nombrelargo=$.trim($('#txt_nombrelargo').val());
					var txt_valorentero=$.trim($('#txt_valorentero').val());
					var txt_precio=$.trim($('#txt_precio').val());
					var cmb_categoria=$.trim($('#cmb_categoria').val());
					var cmb_medida=$.trim($('#cmb_medida').val());
					var txt_config_valorprecio=$.trim($('#txt_config_valorprecio').val());
					
					if (txt_nombrecorto=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese nombre del producto.</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					if (txt_valorentero=='' || txt_precio=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese valor venta del producto.</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					if (cmb_categoria==0)
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione categor�a del producto.</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}					
					if (cmb_medida==0)
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione la unidad de medida.</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					if (txt_nombrelargo=='')
					{
						txt_nombrelargo=txt_nombrecorto;
					}
					
					if (txt_config_valorprecio==0)
					{
						if (txt_valorentero=='' || isNaN(txt_valorentero))
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese valor venta del producto.</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
					}
					else{
						if (txt_precio=='' || isNaN(txt_precio))
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese precio del producto.</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
					}

					if (txt_id==0)//GUARDAR
					{
						$.ajax({
							url:'<?php echo base_url()?>producto/Guadar_Producto',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_codigo:txt_codigo,
								txt_nombrecorto:txt_nombrecorto,
								txt_nombrelargo:txt_nombrelargo,
								txt_valorentero:txt_valorentero,
								txt_precio:txt_precio,
								cmb_categoria:cmb_categoria,
								cmb_medida:cmb_medida
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
									ncsistema.Listar_Productos();
									return;
								}
								else if (result.status==2)
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionEmpresa').fadeIn(0);
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El producto ya est� registrado</div>');
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
							url:'<?php echo base_url()?>producto/Modificar_Producto',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_id:txt_id,
								txt_codigo:txt_codigo,
								txt_nombrecorto:txt_nombrecorto,
								txt_nombrelargo:txt_nombrelargo,
								txt_valorentero:txt_valorentero,
								txt_precio:txt_precio,
								cmb_categoria:cmb_categoria,
								cmb_medida:cmb_medida
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
									ncsistema.Listar_Productos();

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

				Listar_Productos:function()
				{
					$.ajax({
						url:'<?php echo base_url()?>producto/Listar_Productos',
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
								ncsistema.Listar_ProductosTabla(result.data);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								ncsistema.Listar_ProductosTabla("");
							}
						}
					});					
				},				
				
				
				Listar_ProductosTabla:function(data)
				{	
					$('#div_ListadoProductos').empty().append('');
					var txt_config_valorprecio=$.trim($('#txt_config_valorprecio').val());
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaProductos">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th style="width:3%">Nro.</td>';						
						newHtml+='<th style="width:5%">Editar</td>';
						newHtml+='<th style="width:5%">C�digo</td>';
						newHtml+='<th style="width:10%">Nombre</td>';
						newHtml+='<th style="width:30%">Descripci�n</td>';
						if (txt_config_valorprecio==0)
						{
							newHtml+='<th style="width:5%">ValorVenta</td>';	
						}else
						{
							newHtml+='<th style="width:5%">Precio</td>';
						}
						newHtml+='<th style="width:10%">Categor�a</td>';
						newHtml+='<th style="width:5%">U.M.Com.</td>';
						newHtml+='<th style="width:5%">U.M.Sunat</td>';
						newHtml+='<th style="width:10%">Eliminar</td>';	
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';

					contador=0;
					if (txt_config_valorprecio==0)
					{
						$.each(data,function(key,rs)
						{
							contador++;
							
							newHtml+='<tr>';							
								newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';							
								newHtml+='<td style="text-align:center"><a href="javascript:VerDatosProducto_Modificar('+rs.id+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/Editar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
								newHtml+='<td style="text-align:left">'+rs.cod_producto+'</td>';
								newHtml+='<td style="text-align:left">'+rs.nom_corto+'</td>';
								newHtml+='<td style="text-align:left">'+rs.nom_largo+'</td>';
								newHtml+='<td style="text-align:right">'+rs.valor_venta+'</td>';
								//newHtml+='<td style="text-align:right">'+rs.precio_venta+'</td>';
								newHtml+='<td style="text-align:left">'+rs.categoria+'</td>';
								newHtml+='<td style="text-align:left">'+rs.med+'</td>';
								newHtml+='<td style="text-align:left">'+rs.cod_unidmedsunat+'</td>';
															
								if (rs.est_reg==0)//ANULADO
								{
									newHtml+='<td style="text-align:left"></td>';								
								}
								else
								{
									newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Producto('+rs.id+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
								}
								
							newHtml+='</tr>';						
						  });	
					}
					else
					{
						$.each(data,function(key,rs)
						{
							contador++;
							
							newHtml+='<tr>';							
								newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';							
								newHtml+='<td style="text-align:center"><a href="javascript:VerDatosProducto_Modificar('+rs.id+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/Editar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
								newHtml+='<td style="text-align:left">'+rs.cod_producto+'</td>';
								newHtml+='<td style="text-align:left">'+rs.nom_corto+'</td>';
								newHtml+='<td style="text-align:left">'+rs.nom_largo+'</td>';
								newHtml+='<td style="text-align:right">'+rs.precio_venta+'</td>';
								newHtml+='<td style="text-align:left">'+rs.categoria+'</td>';
								newHtml+='<td style="text-align:left">'+rs.med+'</td>';
								newHtml+='<td style="text-align:left">'+rs.cod_unidmedsunat+'</td>';
															
								if (rs.est_reg==0)//ANULADO
								{
									newHtml+='<td style="text-align:left"></td>';								
								}
								else
								{
									newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Producto('+rs.id+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
								}
								
							newHtml+='</tr>';						
						  });	
					}
					
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_ListadoProductos').empty().append(newHtml);	

					oTable=$('#Tab_ListaProductos').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_ListaProductos tbody").click(function(event) 
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
				$('#txt_id').val('0');
				$('#txt_codigo').val('');
				$('#txt_nombrecorto').val('');
				$('#txt_nombrelargo').val('');
				$('#txt_valorentero').val('');	
				$('#cmb_categoria').val('0');
				$('#cmb_medida').val('0');
				$('#txt_precio').val('');

				$('#div_Guardar').removeClass('disablediv');
				$("#div_Guardar").addClass("enablediv").on("onclick");	
			}
			
			function VerDatosProducto_Modificar(cod_id)
			{
				var txt_config_valorprecio=$.trim($('#txt_config_valorprecio').val());
				$.ajax
				({
					url:'<?php echo base_url()?>producto/Listar_ProductoId',type:'post',dataType:'json',
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
									//$("#cmb_variables").prop('disabled', true);
									//id
									//cod_producto
									//nom_corto
									//nom_largo
									//valor_venta
									//id_categoria
									//categoria
									//med
									//cod_unidmedsunat 
									$('#txt_id').val(rs.id);
									$('#txt_codigo').val(rs.cod_producto);
									$('#txt_nombrecorto').val(rs.nom_corto);
									$('#txt_nombrelargo').val(rs.nom_largo);
									$('#cmb_categoria').val(rs.id_categoria);
									$('#cmb_medida').val(rs.med);
									//alert(txt_config_valorprecio);
									if (txt_config_valorprecio==0){
										$('#txt_valorentero').val(rs.valor_venta);
										Calcular_Montos_VV();
									}else{
										$('#txt_precio').val(rs.precio_venta);
										Calcular_Montos_P();
									}
									//
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
			
			function Calcular_Montos_VV()
			{  
				var txt_valorigv=(parseFloat($('#txt_valorigv').val())/100).toFixed(2);
				var txt_valorotroscargos=(parseFloat($('#txt_valorotroscargos').val())/100).toFixed(2);
				var txt_valorentero=$('#txt_valorentero').val();
				var txt_precio=0;
				if (txt_valorentero=="" || isNaN(txt_valorentero))
				{
					txt_valorentero=0;
				}
				if (txt_valorotroscargos=="" || isNaN(txt_valorotroscargos))
				{
					txt_valorotroscargos=0;
				}
				if (txt_valorigv=="" || isNaN(txt_valorigv))
				{
					txt_valorigv=0;
				}		
				txt_precio=(parseFloat(txt_valorentero)*(1+parseFloat(txt_valorigv)+parseFloat(txt_valorotroscargos)));
				$('#txt_precio').val(txt_precio.toFixed(10));
				
			}
			function Calcular_Montos_P()
			{
				var txt_valorigv=(parseFloat($('#txt_valorigv').val())/100).toFixed(2);
				var txt_valorotroscargos=(parseFloat($('#txt_valorotroscargos').val())/100).toFixed(2);
				var txt_precio=$('#txt_precio').val();
				var txt_valorentero=0;
				if (txt_precio=="" || isNaN(txt_precio))
				{
					txt_precio=0;
				}
				if (txt_valorotroscargos=="" || isNaN(txt_valorotroscargos))
				{
					txt_valorotroscargos=0;
				}
				if (txt_valorigv=="" || isNaN(txt_valorigv))
				{
					txt_valorigv=0;
				}
				txt_valorentero=(parseFloat(txt_precio)/(1+parseFloat(txt_valorigv)+parseFloat(txt_valorotroscargos)));
				$('#txt_valorentero').val(txt_valorentero.toFixed(10));
			}
			
			function Eliminar_Producto(cod_id)
			{
				if(confirm("� Est� Seguro de Eliminar el Producto ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>producto/Eliminar_Producto',type:'post',dataType:'json',
						data:
						{
							cod_id:cod_id
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
								ncsistema.Listar_Productos();
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
			
			function VerDatos_Validar()
			{
				var txt_rucempresa=$('#txt_RucEmpresa').val();
				var txt_codigo=$('#txt_Codigo').val();
				if ($.trim(txt_codigo)==''){
					return;
					}
				$.ajax
				({
					url:'<?php echo base_url()?>producto/valida_producto',type:'post',dataType:'json',
					data:
					{
						txt_rucempresa:txt_rucempresa,
						txt_codigo:txt_codigo
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
								$.trim($('#txt_Codigo').val(rs.codigo));
								$.trim($('#txt_Orden').val(rs.orden));
								$.trim($('#txt_Observacion').val(rs.observacion));
								$.trim($('#txt_id').val(1));
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

		</script>
    </head>   
    <body>
<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">PRODUCTOS</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">
				<div id="div_datosempresa" style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
					<input style="width:15%" type="hidden" id="txt_id"  value="0" />
					<input type="hidden" id="txt_config_valorprecio" value="<?php echo $Config_ValorPrecio;?>"  />
					<table border="0" width="55%" style="border-collapse:separate; border-spacing:2px 1px;" cellpadding="3" 
						class="tablaFormulario">
						<tr><td colspan="4"><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right; width:18%"><label class="columna">C�digo:</label></td>
							<td style="text-align:left; width:32%">
								<input type="text" id="txt_codigo" maxlength="10" style="text-transform:uppercase" onBlur="javascrip:VerDatos_Validar()" /></td>
								
							<td style="text-align:right; width:18%"><label class="columna">Nombre Corto:</label></td>
							<td style="text-align:left; width:32%">
								<input  style="width:100%" type="text" id="txt_nombrecorto" maxlength="50" /></td>
						</tr>
						
						<tr>
							<td style="text-align:right"><label class="columna">Nombre Largo:</label></td>
							<td style="text-align:left" colspan="3">
								<input style="width:100%" type="text" id="txt_nombrelargo" maxlength="250" placeholder="M�ximo 250 caracteres"/></td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Valor Venta:</label></td>
							<td style="text-align:left">
								<input type="hidden" id="txt_valorigv" value="<?php echo $valor_igv;?>" />
								<input type="hidden" id="txt_valorotroscargos" value="<?php echo $valor_otroscargos;?>" />
								<input  type="text" id="txt_valorentero" maxlength="23" placeholder="0.0000000000" onBlur="javascript:Calcular_Montos_VV()" style="text-align:right"/>
								<label id="lbl_referencia_valor" style="font-size:10px; color:#0000CC; font-weight:bold">(Referencial)</label></td>
							<td style="text-align:right"><label class="columna">Precio:</label></td>
							<td style="text-align:left">
								<input  type="text" id="txt_precio" maxlength="23" placeholder="0.0000000000" onBlur="javascript:Calcular_Montos_P()" style="text-align:right"/> 
									<label id="lbl_referencia_precio" style="font-size:10px; color:#0000CC; font-weight:bold">(Referencial)</label></td>
						</tr>
						<tr>
							<td style="text-align:right;"><label class="columna">Categor�a:</label></td>
							<td style="text-align:left;" >
								<select id="cmb_categoria" style="width:100%;height:22px" >
									<option value="0">[SELECCIONAR]</option>								
									<?php foreach ( $Listar_Categoria as $v):	?>
										<option value="<?php echo trim($v['id']); ?>"><?php echo trim(utf8_decode($v['nombre']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
							<td style="text-align:right;"><label class="columna">U.Medida:</label></td>
							<td style="text-align:left;" >
								<select id="cmb_medida" style="width:100%;height:22px" >
									<option value="0">[SELECCIONAR]</option>								
									<?php foreach ( $Listar_Unidades as $v):	?>
										<option value="<?php echo trim($v['codigo_unidad']); ?>"><?php echo trim(utf8_decode($v['nombre_unidad']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
						</tr>
						<tr style="vertical-align:top" >
							<td><label class="columna"></label></td>
							<td  style="vertical-align:top" colspan="3">
								<div style="width:100%;height:15px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:0px;text-align:center;float:left">
									<div id="div_MensajeValidacionEmpresa" style="width:100%;float:left;font-size:9px; color:#FF0000"></div>
								</div>
							</td></tr>
						
						<tr  style="align:left" >
							<td><label class="columna"></label></td>
							<td style="text-align:left" colspan="3">
								<table style="width:100%" border="0" >
								  <tbody>
									<tr style="align:left">
										<td style="text-align:right; width:50%">
											<a href="javascript:ncsistema.Nuevo_Producto()" >
												<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px"  type="submit">
														<span class="ui-button-icon-left ui-icon ui-icon-document"></span>
														<span class="ui-button-text">Nuevo</span></button>
											</a>
										</td>
										<td style="text-align:left;width:50%">
											<a href="javascript:ncsistema.Guadar_Producto()" >
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
				<div id="div_ListadoProductos" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:10px">
				</div>	
			</div>

		</div>

    </body>	
</html>