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
				ncsistema.Listar_Productos();
				$('#txt_valorentero').numeric({allow:'.'});
				$("#tabs").tabs();
				ncsistema.get_Configuracion_ValorPrecio();
			})
			
			$(function() 
			{
				$("#tabs").tabs({ 
					activate: function(event ,ui)
						{
							//alert( ui.newTab.attr('li',"innerHTML")[0].getElementsByTagName("a")[0].innerHTML);
							if (( ui.newTab.attr('li',"innerHTML")[0].getElementsByTagName("a")[0].innerHTML)=='CATEGORÍA')
							{
								ListarConfiguracion_Tab2();}
						} });
			});
			
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
								$('#txt_valorentero').attr('placeholder','0.0000000000');
								$('#txt_precio').attr('placeholder','0.0000000000');
								obj1.style.display="none"
							}else
							{
								obj2 = document.getElementById('lbl_referencia_precio');
								$("#txt_valorentero").prop('disabled', true);
								$('#txt_valorentero').attr('placeholder','0.00');
								$('#txt_precio').attr('placeholder','0.00');
								obj2.style.display="none"
							}
						}
					});	
				},
				
				Guadar_Categoria:function()
				{
					var txt_id_cat=$.trim($('#txt_id_cat').val());
					var txt_cat_codigo=$.trim($('#txt_cat_codigo').val());
					var txt_cat_descripcion=$.trim($('#txt_cat_descripcion').val());
					var txt_value_cat=$.trim($('#txt_value_cat').val());
					
					if (txt_cat_codigo=='')
					{
						$('#div_MensajeValidacionCategoria').fadeIn(0);
						$('#div_MensajeValidacionCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese código de la categoría.</div>');
						setTimeout(function(){ $("#div_MensajeValidacionCategoria").fadeOut(1500);},3000);
						return;
					}
					if (txt_cat_descripcion=='')
					{
						$('#div_MensajeValidacionCategoria').fadeIn(0);
						$('#div_MensajeValidacionCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese descripción de la categoría.</div>');
						setTimeout(function(){ $("#div_MensajeValidacionCategoria").fadeOut(1500);},3000);
						return;
					}
					if (txt_id_cat==0)//GUARDAR
					{
						$.ajax({
							url:'<?php echo base_url()?>producto/Guadar_Categoria',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_cat_codigo:txt_cat_codigo,
								txt_cat_descripcion:txt_cat_descripcion,
								txt_value_cat:txt_value_cat
							},
							beforeSend:function()
							{
								$('#div_Guardar').removeClass('enablediv');
								$("#div_Guardar").addClass("disablediv").off("onclick");

								$('#div_MensajeValidacionCategoria').fadeIn(0);
								$('#div_MensajeValidacionCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
							},
							success:function(result)
							{
								if(result.status==1)
								{
								
									$('#div_MensajeValidacionCategoria').fadeIn(0);
									$('#div_MensajeValidacionCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El registro de los datos se realiz&oacute; con &eacute;xito</div>');
									setTimeout(function(){ $("#div_MensajeValidacionCategoria").fadeOut(1500);},3000);

									Limpiar_Categorias();
									ncsistema.Listar_Categorias();
									return;
								}
								else if (result.status==2)
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionCategoria').fadeIn(0);
									$('#div_MensajeValidacionCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La categoría ya está registrado</div>');
									setTimeout(function(){ $("#div_MensajeValidacionCategoria").fadeOut(1500);},3000);
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
									
									$('#div_MensajeValidacionCategoria').fadeIn(0);
									$('#div_MensajeValidacionCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al registrar los datos</div>');
									setTimeout(function(){ $("#div_MensajeValidacionCategoria").fadeOut(1500);},3000);
									return;
								}
							}
						});
					}else
					{
						$.ajax({
							url:'<?php echo base_url()?>producto/Modificar_Categoria',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_id_cat:txt_id_cat,
								txt_cat_codigo:txt_cat_codigo,
								txt_cat_descripcion:txt_cat_descripcion,
								txt_value_cat:txt_value_cat
							},
							beforeSend:function()
							{
								$('#div_Guardar').removeClass('enablediv');
								$("#div_Guardar").addClass("disablediv").off("onclick");
								
								$('#div_MensajeValidacionCategoria').fadeIn(0);
								$('#div_MensajeValidacionCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
							},
							success:function(result)
							{
								if(result.status==1)
								{
									$('#div_MensajeValidacionCategoria').fadeIn(0);
									$('#div_MensajeValidacionCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La Modificaci&oacute;n de los datos se realiz&oacute; con &eacute;xito</div>');
									setTimeout(function(){ $("#div_MensajeValidacionCategoria").fadeOut(1500);},3000);

									Limpiar_Categorias();
									ncsistema.Listar_Categorias();
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
									
									$('#div_MensajeValidacionCategoria').fadeIn(0);
									$('#div_MensajeValidacionCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al modificar los datos</div>');
									setTimeout(function(){ $("#div_MensajeValidacionCategoria").fadeOut(1500);},3000);
									return;
								}
							}
						});
					}
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
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione categoría del producto.</div>');
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
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El producto ya está registrado</div>');
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
				
				Nuevo_Producto:function()
				{
					Limpiar_Parametros();
				},
				
				Nuevo_Categoria:function()
				{
					Limpiar_Categorias();
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
						newHtml+='<th style="width:5%">Código</td>';
						newHtml+='<th style="width:10%">Nombre</td>';
						newHtml+='<th style="width:30%">Descripción</td>';
						if (txt_config_valorprecio==0)
						{
							newHtml+='<th style="width:5%">ValorVenta</td>';	
						}else
						{
							newHtml+='<th style="width:5%">PrecioCobro</td>';
						}
						newHtml+='<th style="width:10%">Categoría</td>';
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
				
				Listar_Categorias:function()
				{
					$.ajax({
						url:'<?php echo base_url()?>producto/Listar_Categorias',
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
								ncsistema.Listar_CategoriasTabla(result.data);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								ncsistema.Listar_CategoriasTabla("");
							}
						}
					});					
				},
				
				Listar_CategoriasTabla:function(data)
				{	
					$('#div_ListadoCategoria').empty().append('');
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaCategorias">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th style="width:5%">Nro.</td>';						
						newHtml+='<th style="width:5%">Editar</td>';
						newHtml+='<th style="width:20%">Código</td>';
						newHtml+='<th style="width:65%">Descripción</td>';
						newHtml+='<th style="width:5%">Eliminar</td>';	
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';

					contador=0;
					$.each(data,function(key,rs)
					{
						contador++;
						
						newHtml+='<tr>';							
							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';							
							newHtml+='<td style="text-align:center"><a href="javascript:VerDatosCategoria_Modificar('+rs.id+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/Editar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
							newHtml+='<td style="text-align:left">'+rs.valorcadena+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nombre+'</td>';
							if (rs.activo==0)//ANULADO
							{
								newHtml+='<td style="text-align:left"></td>';								
							}
							else
							{
								newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Categoria('+rs.id+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
							}
							
						newHtml+='</tr>';						
					  });	
					
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_ListadoCategoria').empty().append(newHtml);	

					oTable=$('#Tab_ListaCategorias').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_ListaCategorias tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});
				},
				
			}
			
			function ListarConfiguracion_Tab2()
			{
				ncsistema.Listar_Categorias();
				
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
				$("#txt_codigo").prop('disabled', false);
				$('#div_Guardar').removeClass('disablediv');
				$("#div_Guardar").addClass("enablediv").on("onclick");	
			}
			
			function Limpiar_Categorias()
			{
				$('#txt_id_cat').val('0');
				$('#txt_cat_codigo').val('');
				$('#txt_cat_descripcion').val('');
				$("#txt_cat_codigo").prop('disabled', false);
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
									
									$('#txt_id').val(rs.id);
									$('#txt_codigo').val(rs.cod_producto);
									$("#txt_codigo").prop('disabled', true);
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
			
			function VerDatosCategoria_Modificar(cod_id)
			{
				$.ajax
				({
					url:'<?php echo base_url()?>producto/Listar_CategoriaId',type:'post',dataType:'json',
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
									$('#txt_id_cat').val(rs.id);
									$('#txt_cat_codigo').val(rs.valorcadena);
									$("#txt_cat_codigo").prop('disabled', true);
									$('#txt_cat_descripcion').val(rs.nombre);
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
				$('#txt_valorentero').val(txt_valorentero.toFixed(2));
			}
			
			function Eliminar_Categoria(cod_id)
			{
				if(confirm("¿ Está Seguro de Eliminar la Categoría ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>producto/Eliminar_Categoria',type:'post',dataType:'json',
						data:
						{
							cod_id:cod_id
						},
						beforeSend:function()
						{
							$('#div_MensajeValidacionCategoria').fadeIn(0);
							$('#div_MensajeValidacionCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:20px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>')
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionCategoria').fadeIn(0);
								$('#div_MensajeValidacionCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:20px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminaci&oacute;n de la categoría se realiz&oacute; con &eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionCategoria").fadeOut(1500);},3000);
								ncsistema.Listar_Categorias();
								return;							
								
	
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								$('#div_MensajeValidacionCategoria').fadeIn(0);
								$('#div_MensajeValidacionCategoria').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:20px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el correlativo</div>');
								setTimeout(function(){ $("#div_MensajeValidacionCategoria").fadeOut(1500);},3000);
								return;
							}
						}			
					});
				}
			}
			
			function Eliminar_Producto(cod_id)
			{
				if(confirm("¿ Está Seguro de Eliminar el Producto ?"))
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:20px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminaci&oacute;n del producto se realiz&oacute; con &eacute;xito</div>');
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:20px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el producto</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}			
					});
				}
			}
			
			function validar_Categoria()
			{
				var txt_cat_codigo=$('#txt_cat_codigo').val();
				//var txt_cat_descripcion=$('#txt_cat_descripcion').val();
				if ($.trim(txt_cat_codigo)=='' && $.trim(txt_cat_descripcion)==''){
					return;
					}
				$.ajax
				({
					url:'<?php echo base_url()?>producto/Valida_Categoria',type:'post',dataType:'json',
					data:
					{
						txt_cat_codigo:txt_cat_codigo
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
								if (rs.activo=='0')
								{
									alert('Categoría registrado y deshabilitado. Se habilitará el registro con los nuevos datos a actualizar.');
								}
								$('#txt_id_cat').val(rs.id);
								$('#txt_cat_codigo').val(rs.valorcadena);
								$('#txt_cat_descripcion').val(rs.nombre);
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
			
			function VerDatos_Validar()
			{
				//var txt_id=$('#txt_id').val();
				var txt_codigo=$('#txt_codigo').val();
				if ($.trim(txt_codigo)==''){
					return;
					}
				$.ajax
				({
					url:'<?php echo base_url()?>producto/Valida_Producto',type:'post',dataType:'json',
					data:
					{
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
								if (rs.est_reg=='0')
								{
									alert('Producto registrado y deshabilitado. Se habilitará el registro con los nuevos datos a actualizar.');
								}
								$('#txt_id').val(rs.id);
								$('#txt_codigo').val(rs.cod_producto);
								$('#txt_nombrecorto').val(rs.nom_corto);
								$('#txt_nombrelargo').val(rs.nom_largo);
								$('#cmb_categoria').val(rs.id_categoria);
								$('#cmb_medida').val(rs.med);
								if (txt_config_valorprecio==0){
									$('#txt_valorentero').val(rs.valor_venta);
									Calcular_Montos_VV();
								}else{
									$('#txt_precio').val(rs.precio_venta);
									Calcular_Montos_P();
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
			
			function NumCheck(e, field) {
				
				key = e.keyCode ? e.keyCode : e.which
				// backspace
				if (key == 8 || key == 9 || key == 35 || key == 36 || key == 37 || key == 39) return true
			 	//if (key == 9) return true
				// 0-9 a partir del .decimal  
				if (field.value != "") {
					if ((field.value.indexOf(".")) > 0) {
						//si tiene un punto valida dos digitos en la parte decimal
						if (key > 47 && key < 58) {
							if (field.value == "") return true
							//regexp = /[0-9]{1,10}[\.][0-9]{1,3}$/
							var txt_config_valorprecio=$.trim($('#txt_config_valorprecio').val());
							if (txt_config_valorprecio==0){
								regexp = /[0-9]{10}$/
							}else{
								regexp = /[0-9]{2}$/
							}
							
							return !(regexp.test(field.value))
						}
					}
				}
				// 0-9 
				if (key > 47 && key < 58) {
					if (field.value == "") return true
					regexp = /[0-9]{10}/
					return !(regexp.test(field.value))
				}
				// .
				if (key == 46) {
					if (field.value == "") return false
					regexp = /^[0-9]+$/
					return regexp.test(field.value)
				}
				// other key
				return false
			  }

		</script>
    </head>   
    <body>
<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">PRODUCTOS</a></li>
				<li><a href="#tabs-2">CATEGORÍA</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">
				<div id="div_datosempresa" style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
					<input style="width:15%" type="hidden" id="txt_id"  value="0" />
					<input type="hidden" id="txt_config_valorprecio" value="<?php echo $Config_ValorPrecio;?>"  />
					<table border="0" width="55%" style="border-collapse:separate; border-spacing:2px 1px;" cellpadding="3" 
						class="tablaFormulario">
						<tr><td colspan="4"><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right; width:18%"><label class="columna">Código:</label></td>
							<td style="text-align:left; width:32%">
								<input type="text" id="txt_codigo" maxlength="10" style="text-transform:uppercase" onBlur="javascrip:VerDatos_Validar()" /></td>
								
							<td style="text-align:right; width:18%"><label class="columna">Nombre Corto:</label></td>
							<td style="text-align:left; width:32%">
								<input  style="width:100%" type="text" id="txt_nombrecorto" maxlength="50" /></td>
						</tr>
						
						<tr>
							<td style="text-align:right"><label class="columna">Nombre Largo:</label></td>
							<td style="text-align:left" colspan="3">
								<input style="width:100%" type="text" id="txt_nombrelargo" maxlength="250" placeholder="Máximo 250 caracteres"/></td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Valor Venta:</label></td>
							<td style="text-align:left">
								<input type="hidden" id="txt_valorigv" value="<?php echo $valor_igv;?>" />
								<input type="hidden" id="txt_valorotroscargos" value="<?php echo $valor_otroscargos;?>" />
								<input  type="text" id="txt_valorentero" maxlength="23" onKeyPress="return NumCheck(event, this);"  onBlur="javascript:Calcular_Montos_VV()" style="text-align:right"/>
								<label id="lbl_referencia_valor" style="font-size:10px; color:#0000CC; font-weight:bold">(Referencial)</label></td>
							<td style="text-align:right"><label class="columna">Precio de Cobro:</label></td>
							<td style="text-align:left">
								<input  type="text" id="txt_precio" maxlength="23" placeholder="0.00" onKeyPress="return NumCheck(event, this);"  onBlur="javascript:Calcular_Montos_P()" style="text-align:right"/> 
									<label id="lbl_referencia_precio" style="font-size:10px; color:#0000CC; font-weight:bold">(Referencial)</label></td>
						</tr>
						<tr>
							<td style="text-align:right;"><label class="columna">Categoría:</label></td>
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
			
			<div id="tabs-2" style="width:95%;float:left">
				<div id="div_datoscategoria" style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
					<input type="hidden" id="txt_id_cat"  value="0" />
					<input type="hidden" id="txt_value_cat" value="CATEGORIA_PRODUCTO"/>
					<table border="0" width="40%" style="border-collapse:separate; border-spacing:2px 1px;" cellpadding="3" 
						class="tablaFormulario">
						<tr><td colspan="4"><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right; width:20%"><label class="columna">Código: </label></td>
							<td style="text-align:left; width:80%">
								<input type="text" id="txt_cat_codigo" maxlength="5" style="text-transform:uppercase" onBlur="javascrip:validar_Categoria()" /></td>
						</tr>
						<tr>
							<td style="text-align:right;" ><label class="columna">Descripción: </label></td>
							<td style="text-align:left;">
								<input  style="width:100%" type="text" id="txt_cat_descripcion" maxlength="50" /></td>
						</tr>
						<tr style="vertical-align:top" >
							<td  style="vertical-align:top" colspan="4">
								<div style="width:100%;height:15px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:0px;text-align:center;float:left">
									<div id="div_MensajeValidacionCategoria" style="width:100%;float:left;font-size:9px; color:#FF0000"></div>
								</div>
							</td>
						</tr>
						<tr  style="align:left" >
							<td><label class="columna"></label></td>
							<td style="text-align:left" colspan="3">
								<table style="width:100%" border="0" >
								  <tbody>
									<tr style="align:left">
										<td style="text-align:right; width:50%">
											<a href="javascript:ncsistema.Nuevo_Categoria()" >
												<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px"  type="submit">
														<span class="ui-button-icon-left ui-icon ui-icon-document"></span>
														<span class="ui-button-text">Nuevo</span></button>
											</a>
										</td>
										<td style="text-align:left;width:50%">
											<a href="javascript:ncsistema.Guadar_Categoria()" >
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
				
				<div id="div_ListadoCategoria" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:10px">
				</div>	
			</div>

		</div>

    </body>	
</html>