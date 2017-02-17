<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Comprobantes</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.min.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/plugins/dataTable/css/dataTables-all.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>application/helpers/jquery/flexigrid/flexigrid/flexigrid.css" />

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
		<script type="text/javascript" src="<?php echo base_url();?>application/helpers/jquery/flexigrid/flexigrid/flexigrid.js"></script>
		
		<script>var urlexportardatos="<?php echo base_url(); ?>"</script>
		
		<script type="text/javascript">	

			$(document).ready(function()
			{
				$.datepicker.setDefaults($.datepicker.regional["es"]);
				$("#tabs").tabs();
				ncsistema.Listar_ProductosDocumento();				
				$('#txt_FechaEmision').datepicker({
					showOn: 'button',					
					buttonImage: "<?php echo base_url()?>application/helpers/image/ico/calendar_icon.gif",
					buttonImageOnly: true,
					dateFormat: 'dd/mm/yy',
					buttonText: "##/##/####",
					maxDate: 'today',
					changeMonth: true ,
					changeYear: true
				});
				$('#txt_FechaEmision').datepicker('setDate', 'today');				
				$('#txt_cantidad, #txt_valorunitario,#txt_descuento, #txt_isc,#txt_descuentoglobal, #txt_porcentajedetraccion, #txt_montodetraccionreferencial, #txt_porcentajepercepcion, #txt_baseimponiblepercepcion').numeric({allow:'.'});
				
				Tipo_Afectacion();
				Datos_Emisor();
				OcultarFilaTabla('row3',0);//MUESTRA FILA
				OcultarFilaTabla('row4',0);//MUESTRA FILA
				ncsistema.Buscar_Clientes();
				OcultarOtrosCargos();
			})
			
			ncsistema=
			{
			
				Buscar_Clientes:function ()
				{
					//var lista_clientes = {};
					$('#txt_razonsocialcliente').attr('readonly', false);
					$('#txt_razonsocialcliente').autocomplete
					({
						minLength: 1,
						delay: 700,
						source: function( request, response ) 
						{
							var lista_clientes = {};
							var term = request.term;
							var cmb_tipodocumentocliente=$.trim($('#cmb_tipodocumentocliente').val());
							
							$('#txt_numerodoccliente').val('');
							Datos_ClienteBusqueda('');
							//alert($('#txt_razonsocialcliente').val());
							if ( term in lista_clientes ) 
							{
								response( lista_clientes[ term ] );
								return;
							}
							//alert(term);
							$.getJSON('<?php echo base_url()?>clientes/listar_clientes_autocompletarComprobante?tipodoc='+cmb_tipodocumentocliente, request, function( data, status, xhr ) {
								//alert(term);
								lista_clientes[ term ] = data;
								response( data );
							});
						},
						focus: function( event, ui ) 
						{
							//$('#t_nombsol').val( ui.item.value );
							return false;
						},
						select: function( event, ui ) 
						{
							$('#txt_razonsocialcliente').val(ui.item.value );
							$('#txt_numerodoccliente').val(ui.item.nro_docum); 
							//alert('HIHI');
							Datos_ClienteBusqueda(ui.item.cod_client);
							return false;
						}
					});
				},
			

				Listar_ProductosDocumento:function()
				{
					$.ajax({
						url:'<?php echo base_url()?>comprobante/Listar_ProductosDocumento',
						type: 'post',
						dataType: 'json',
						data:
						{
							/*tipo_busqueda:tipo_busqueda,
							txt_RucEmpresa:txt_RucEmpresa,
							txt_FechaEmision:txt_FechaEmision*/
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_ProductosDocumentoTabla(result.data,result.variable);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								ncsistema.Listar_ProductosDocumentoTabla("","");
							}
						}
					});					
				},				
				
				
				Listar_ProductosDocumentoTabla:function(data,variable)
				{	
					$('#div_ListadoEmpresa').empty().append('');
					var txt_valorigv=$.trim($('#txt_valorigv').val());
					//$('#txt_tipdocemisor').val('');					
					//$('#txt_datosseleccionados').val('');
					var tipo_registro=0;				
					if ($("#cbox_exportaciondocumento").is(":checked"))//EXPORTACION
					{
						tipo_registro=2;
					}else 
						if ($("#cbox_opergratisdocumento").is(":checked"))//OPERACIONES GRATUITAS
						{
							tipo_registro=3;
						}
						else
						{
							tipo_registro=1;
						}
	
					contador=0;
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th width:3%>Nro.</td>';						
						newHtml+='<th width:10%>Eliminar</td>';					
						newHtml+='<th width:10%>Codigo</td>';
						newHtml+='<th width:20%>Descripcion</td>';
						newHtml+='<th width:20%>Unidad</td>';
						newHtml+='<th width:40%>Cantidad</td>';
						newHtml+='<th width:10%>Valor Unit.</td>';	
						newHtml+='<th width:10%>Impuesto</td>';	
						newHtml+='<th width:10%>Precio Unit.</td>';	
						newHtml+='<th width:10%>Descuento</td>';
						newHtml+='<th width:10%>Valor Total</td>';	
						//$('#txt_valorigv').val()
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					//<input style="height:20px;width:95%" id="txt_login" type="text" value="'+rs.cantidadproducto+'"/>
					contador=0;
					$.each(data,function(key,rs)
					{
						
						newHtml+='<tr>';
							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';
							newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Productotemporal('+rs.tmp_prod+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
							newHtml+='<td style="text-align:left">'+rs.cod_prod+'</td>';
							newHtml+='<td style="text-align:left">'+rs.desc_prod+'</td>';
							newHtml+='<td style="text-align:left">'+rs.uni_med+'</td>';
							newHtml+='<td style="text-align:right">'+rs.cant_prod+'</td>';
							newHtml+='<td style="text-align:right">'+(parseFloat(rs.val_unitario)).toFixed(2)+'</td>';
							newHtml+='<td style="text-align:right">'+rs.val_igv+'</td>';
							if (tipo_registro=='1')
							{
								if (rs.tip_afectacion=='10'){
									newHtml+='<td style="text-align:right">'+(parseFloat(rs.val_unitario)*parseFloat(1+txt_valorigv/100)).toFixed(2);+'</td>';
								}else{
									newHtml+='<td style="text-align:right">'+(parseFloat(rs.val_unitario)).toFixed(2);+'</td>';
									}
							}else
							{
								if (rs.tip_afectacion=='40'){
									newHtml+='<td style="text-align:right">'+(parseFloat(rs.val_unitario)).toFixed(2);+'</td>';
								}else{
									newHtml+='<td style="text-align:right">0.00</td>';
									}
							}
							
							newHtml+='<td style="text-align:right">'+rs.val_descuento+'</td>';
							newHtml+='<td style="text-align:right">'+rs.val_total+'</td>';				
							//$('#txt_tipdocemisor').val(rs.tip_docemisor);							
						newHtml+='</tr>';	
						contador++;					
					});	
					newHtml+='</tbody>';
					newHtml+='</table>';

					$.trim($('#numeroitem').val(contador));
					contador++;
					$.trim($('#txt_numeroitem').val(contador));	
					
					$('#txt_operaciongravadas').val('0.00');
					$('#txt_operacioninafectos').val('0.00');
					$('#txt_operacionexportacion').val('0.00');
					$('#txt_operacionexoneradas').val('0.00');
					$('#txt_operaciongratuitas').val('0.00');
					$('#txt_descuentototal').val('0.00');
					$('#txt_isctotal').val('0.00');
					$('#txt_igvtotal').val('0.00');
					$('#txt_otroscargos').val('0.00');
					$('#txt_importetotal').val('0.00');

					if (variable!="")
					{
						$('#txt_operaciongravadas').val(variable.operaciongravadas);
						$('#txt_operacioninafectos').val(variable.operacioninafectos);
						$('#txt_operacionexportacion').val(variable.operacionexportacion);
						$('#txt_operacionexoneradas').val(variable.operacionexoneradas);
						$('#txt_operaciongratuitas').val(variable.operaciongratuitas);
						$('#txt_descuentototal').val(variable.descuentototal);
						$('#txt_isctotal').val(variable.isctotal);
						$('#txt_igvtotal').val(variable.igvtotal);
						$('#txt_otroscargos').val(variable.otroscargos);
						$('#txt_importetotal').val(variable.importetotal);
					}
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
				
				Filtro_producto_tabla:function(data)
				{	
					$('#div_Busqueda_productofiltro').empty().append('');
					var txt_config_valorprecio=$.trim($('#txt_config_valorprecio').val());
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_FiltroProducto">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th style="width:3%">Nro.</td>';						
						newHtml+='<th style="width:5%">Sel.</td>';
						newHtml+='<th style="width:10%">Código</td>';
						newHtml+='<th style="width:35%">Descripción</td>';
						if (txt_config_valorprecio==0)
						{
							newHtml+='<th style="width:25%">ValorVenta</td>';	
						}else
						{
							newHtml+='<th style="width:25%">PrecioCobro</td>';	
						}				
						newHtml+='<th style="width:10%">U.N.Sunat</td>';
						//a.id, a.cod_producto, a.nom_corto, a.precio, d.cod_unidmedsunat
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
								newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';	//id, cod_producto, nom_corto, precio, cod_unidmedsunat, idmed					
								newHtml+='<td style="text-align:center"><a href="javascript:Seleccionar_Producto('+rs.id+',\''+rs.cod_producto+'\',\''+rs.nom_largo+'\',\''+rs.valor_venta_real+'\',\''+rs.cod_unidmedsunat+'\')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/add.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
								newHtml+='<td style="text-align:left">'+rs.cod_producto+'</td>';
								newHtml+='<td style="text-align:left">'+rs.nom_corto+'</td>';
								newHtml+='<td style="text-align:right">'+rs.valor_venta+'</td>';
								newHtml+='<td style="text-align:left">'+rs.cod_unidmedsunat+'</td>';
							newHtml+='</tr>';						
						});	
					}else
					{
						$.each(data,function(key,rs)
						{
							contador++;
							newHtml+='<tr>';							
								newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';	//id, cod_producto, nom_corto, precio, cod_unidmedsunat, idmed					
								newHtml+='<td style="text-align:center"><a href="javascript:Seleccionar_Producto('+rs.id+',\''+rs.cod_producto+'\',\''+rs.nom_largo+'\',\''+rs.precio_venta_real+'\',\''+rs.cod_unidmedsunat+'\')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/add.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
								newHtml+='<td style="text-align:left">'+rs.cod_producto+'</td>';
								newHtml+='<td style="text-align:left">'+rs.nom_corto+'</td>';
								newHtml+='<td style="text-align:right">'+rs.precio_venta+'</td>';
								newHtml+='<td style="text-align:left">'+rs.cod_unidmedsunat+'</td>';
							newHtml+='</tr>';						
						});	
					}
					
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_Busqueda_productofiltro').empty().append(newHtml);	

					oTable=$('#Tab_FiltroProducto').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_FiltroProducto tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});
				},
				
				Filtro_producto:function()
				{
					var txt_busqueda_codigoproducto=$.trim($('#txt_busqueda_codigoproducto').val());
					var txt_busqueda_descripcionproducto=$.trim($('#txt_busqueda_descripcionproducto').val());
					if (txt_busqueda_codigoproducto=='' & txt_busqueda_descripcionproducto=='')
					{
						//alert('Entro a validación!.');
						return;
					}
					
					$.ajax({
						url:'<?php echo base_url()?>producto/Busqueda_ProductoFiltro',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_busqueda_codigoproducto:txt_busqueda_codigoproducto,
							txt_busqueda_descripcionproducto:txt_busqueda_descripcionproducto
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Filtro_producto_tabla(result.data);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}	
							else
							{
								ncsistema.Filtro_producto_tabla("");
							}
						}
					});					
				},
				
				filtro_datosAdicionales:function()
				{
					var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
					$.ajax({
						url:'<?php echo base_url()?>comprobante/Listar_DatosAdicionales',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_RucEmpresa:txt_RucEmpresa
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.filtro_datosAdicionales_Tabla(result.data);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}	
							else
							{
								ncsistema.filtro_datosAdicionales_Tabla("");
							}
						}
					});	
				},
				
				filtro_datosAdicionales_Tabla:function(data)
				{	
					$('#div_Busqueda_DatosAdicionales').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_FiltroDatosAdicionales">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th style="width:3%">Nro.</td>';						
						newHtml+='<th style="width:5%">Sel.</td>';
						newHtml+='<th style="width:10%">Código</td>';
						newHtml+='<th style="width:35%">Descripción</td>';
						newHtml+='<th style="width:35%">Valor</td>';
					newHtml+='</tr>';
					newHtml+='</thead>';
					newHtml+='<tbody>';
					contador=0;
					$.each(data,function(key,rs)
					{
						contador++;
						newHtml+='<tr>';							
							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';	
							//onChange="javascrip:Seleccionar_DatosBusqueda('+key+',\''+rs.resumenid+'\',\''+rs.bl_estadoregistro+'\',\''+rs.estadosunat+'\')"
							newHtml+='<td style="text-align:center"><input id="cbox_datoadicional_seleccion_'+key+'" type="checkbox" value="" name="cbox_datoadicional_seleccion_'+key+'" onChange="javascrip:Seleccionar_DatoAdicional('+key+')"></td>';
							newHtml+='<td style="text-align:left">'+rs.Codigo+'</td>';
							newHtml+='<td style="text-align:left">'+rs.Observacion+'</td>';
							newHtml+='<td style="text-align:left"><input style="width:95%" type="text" id="txt_datoadicional_valor_'+key+'" name="txt_datoadicional_valor_'+key+'" value="" placeholder="Máximo 40 caracteres" disabled="disabled" maxlength="40" /></td>';
						newHtml+='</tr>';						
					});	
					//datoadicional_Cantidad=contador;
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_Busqueda_DatosAdicionales').empty().append(newHtml);	
	
					oTable=$('#Tab_FiltroDatosAdicionales').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_FiltroDatosAdicionales tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});
				},
								
				Guardar_Einvoiceheader:function(tipo_registro)
				{
					var txt_documentomodificar=$.trim($('#txt_documentomodificar').val());
					
					var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
					var cmb_tipodocumentosunat=$.trim($('#cmb_tipodocumentosunat').val());
					var txt_RazonSocialEmpresa=$.trim($('#txt_RazonSocialEmpresa').val());
					var cmb_seriedocumentosunat=$.trim($('#cmb_seriedocumentosunat').val()).toUpperCase();
					var txt_numerodocumentosunat=$.trim($('#txt_numerodocumentosunat').val());
					var txt_fecemisiondoc=$.trim($('#txt_FechaEmision').val());					
					var txt_numerodoccliente=$.trim($('#txt_numerodoccliente').val());
					var cmb_tipodocumentocliente=$.trim($('#cmb_tipodocumentocliente').val());
					var txt_razonsocialcliente=$.trim($('#txt_razonsocialcliente').val());					
					var txt_correocliente=$.trim($('#txt_correocliente').val());
					var txt_direccioncliente=$.trim($('#txt_direccioncliente').val());
										
					var cmb_monedadocumento=$.trim($('#cmb_monedadocumento').val());
					var cmb_monedadocumentonombre = $("#cmb_monedadocumento option:selected").html();
					var txt_operaciongravadas=$.trim($('#txt_operaciongravadas').val());
					txt_operaciongravadas=txt_operaciongravadas.replace(",","");
					var txt_operacioninafectos=$.trim($('#txt_operacioninafectos').val());
					txt_operacioninafectos=txt_operacioninafectos.replace(",","");
					var txt_operacionexoneradas=$.trim($('#txt_operacionexoneradas').val());
					txt_operacionexoneradas=txt_operacionexoneradas.replace(",","");
					var txt_igvtotal=$.trim($('#txt_igvtotal').val());
					txt_igvtotal=txt_igvtotal.replace(",","");
					var txt_descuentototal=$.trim($('#txt_descuentototal').val());
					txt_descuentototal=txt_descuentototal.replace(",","");
					
					var txt_otroscargos=$.trim($('#txt_otroscargos').val());	
					txt_otroscargos=txt_otroscargos.replace(",","");			
					
					var txt_importetotal=$.trim($('#txt_importetotal').val());	
					txt_importetotal=txt_importetotal.replace(",","");				
					var cantidadproduct=$.trim($('#numeroitem').val());
					
					var txt_operacionexportacion=$.trim($('#txt_operacionexportacion').val());
					txt_operacionexportacion=txt_operacionexportacion.replace(",","");
					var txt_operaciongratuitas=$.trim($('#txt_operaciongratuitas').val());
					txt_operaciongratuitas=txt_operaciongratuitas.replace(",","");
					
					var txt_porcentajedetraccion=$.trim($('#txt_porcentajedetraccion').val());
					var txt_montodetraccion=$.trim($('#txt_montodetraccion').val());
					txt_montodetraccion=txt_montodetraccion.replace(",","");
					var txt_montodetraccionreferencial=$.trim($('#txt_montodetraccionreferencial').val());
					var txt_descripciondetraccion=$.trim($('#txt_descripciondetraccion').val());
					var txt_leyendadetraccion=$.trim($('#txt_leyendadetraccion').val());					
					var txt_descuentoglobal=$.trim($('#txt_descuentoglobal').val()); 					
					
					var txt_emisorcorreo=$.trim($('#txt_emisorcorreo').val()); 
					var txt_emisorubigeo=$.trim($('#txt_emisorubigeo').val()); 
					var txt_emisordireccion=$.trim($('#txt_emisordireccion').val()); 
					var txt_emisorrubanizacion=$.trim($('#txt_emisorrubanizacion').val()); 
					var txt_emisorprovincia=$.trim($('#txt_emisorprovincia').val()); 
					var txt_emisordepartamento=$.trim($('#txt_emisordepartamento').val()); 
					var txt_emisordistrito=$.trim($('#txt_emisordistrito').val()); 
					var txt_emisorpaiscodigo=$.trim($('#txt_emisorpaiscodigo').val()); 
					
					
					var txt_porcentajepercepcion=$.trim($('#txt_porcentajepercepcion').val());
					var txt_baseimponiblepercepcion=$.trim($('#txt_baseimponiblepercepcion').val());
					var txt_totalpercepcion=$.trim($('#txt_totalpercepcion').val());
					txt_totalpercepcion=txt_totalpercepcion.replace(",","");
					var txt_totalventapercepcion=$.trim($('#txt_totalventapercepcion').val());
					txt_totalventapercepcion=txt_totalventapercepcion.replace(",","");
					
					var txt_clienteubigeo=$.trim($('#txt_clienteubigeo').val());
					var txt_clientedireccion=$.trim($('#txt_clientedireccion').val());
					var txt_clienteubanizacion=$.trim($('#txt_clienteubanizacion').val());
					var txt_clienteprovincia=$.trim($('#txt_clienteprovincia').val());
					var txt_clientedepartamento=$.trim($('#txt_clientedepartamento').val());
					var txt_clientedistrito=$.trim($('#txt_clientedistrito').val());
					var txt_clientepaiscodigo=$.trim($('#txt_clientepaiscodigo').val());
					
					
					var cmb_tipodocumentoreferencia=$.trim($('#cmb_tipodocumentoreferencia').val());
					var txt_numerodocumentoreferencia=$.trim($('#txt_numerodocumentoreferencia').val());
					var cmb_tiponotadecredito=$.trim($('#cmb_tiponotadecredito').val());
					var txt_motivodenotacredito=$.trim($('#txt_motivodenotacredito').val());
					
					var txt_porcentajeotroscargos=$.trim($('#txt_valorotroscargos').val());;

					var cod_tipregistrogeneral=0;	

					if ($("#cbox_exportaciondocumento").is(":checked"))//EXPORTACION
					{
						cod_tipregistrogeneral=2;
					}
					else if ($("#cbox_opergratisdocumento").is(":checked"))//OPERACIONES GRATUITAS
					{
						cod_tipregistrogeneral=3;
					}
					else
					{
						cod_tipregistrogeneral=1;
					}

					if (txt_RucEmpresa=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Debe seleccionar una empresa de la lista</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}

					if (cmb_tipodocumentosunat=='' || cmb_tipodocumentosunat=='0')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Debe seleccionar el tipo de documento de SUNAT</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					if (cmb_tipodocumentosunat=='07' || cmb_tipodocumentosunat=='08')
					{
						if (cmb_tipodocumentoreferencia!='-')
						{
							if (txt_numerodocumentoreferencia=='')
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el número de documento de referencia</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}

						if (txt_motivodenotacredito=='')
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el motivo del documento</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
						
						if (cmb_tiponotadecredito=='0')
						{
							if (cmb_tipodocumentosunat=='07')
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el tipo de nota crédito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
							else
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el tipo de nota de débito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}		
						
						if (cmb_tiponotadecredito=='0')
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el tipo de documento</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}					
						
					}					
					
					if (cmb_seriedocumentosunat=='' || cmb_seriedocumentosunat=='0')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Debe seleccionar la serie del documento</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					if (txt_numerodocumentosunat=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Debe ingresar el número para el documento</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					if (cmb_monedadocumento=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccionar el tipo moneda para el documento</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					if (cmb_tipodocumentocliente=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Debe seleccionar el tipo de documento del cliente</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					if (txt_numerodoccliente=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Debe ingresar el documento del cliente</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					if (txt_razonsocialcliente=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El cliente no existe, no tiene razón social</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					/*
					if (txt_direccioncliente=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">ingrese la direccion del cliente</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					*/
					if (txt_correocliente=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el correo del cliente</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					if (cantidadproduct==0)
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">No existe datos en la lista para el registro</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					if (parseFloat(txt_importetotal)>700)
					{					
						
						var mensaje = confirm("Por Resolución de Superintendencia N°183-2004/SUNAT indica que si el importe Total es mayor a \n 700.00 (setecientos y 00/100 Soles), el comprobante deberá estar afecto a Detracción. \n Desea continuar con la Emisión del Comprobante?");
						//Detectamos si el usuario acepto el mensaje
						if (mensaje) 
						{
							
						}
						else
						{
							return;
						}
					}
					
					var validando_detraccion=0;
					if (txt_porcentajedetraccion!='')
					{
						if (parseFloat(txt_porcentajedetraccion)>0)
						{
							validando_detraccion++;
						}
					}
					if (txt_montodetraccionreferencial!='')
					{
						if (parseFloat(txt_montodetraccionreferencial)>0)
						{
							validando_detraccion++;
						}
					}
					if (txt_descripciondetraccion!='')
					{
						validando_detraccion++;
					}
					if (txt_leyendadetraccion!='')
					{
						validando_detraccion++;
					}
					if (validando_detraccion>0 && validando_detraccion<4)
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingresar todos los datos para la detracción</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					var validando_percepcion=0;
					if (txt_porcentajepercepcion!='')
					{
						if (parseFloat(txt_porcentajepercepcion)>0)
						{
							validando_percepcion++;
						}
					}
					if (txt_baseimponiblepercepcion!='')
					{
						if (parseFloat(txt_baseimponiblepercepcion)>0)
						{
							validando_percepcion++;
						}
					}

					if (validando_percepcion>0 && validando_percepcion<2)
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingresar todos los datos para la percepción</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					$.ajax({
						url:'<?php echo base_url()?>comprobante/Guardar_Einvoiceheader',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_RucEmpresa:txt_RucEmpresa,
							cmb_tipodocumentosunat:cmb_tipodocumentosunat,
							txt_RazonSocialEmpresa:txt_RazonSocialEmpresa,
							cmb_seriedocumentosunat:cmb_seriedocumentosunat,
							txt_numerodocumentosunat:txt_numerodocumentosunat,
							txt_fecemisiondoc:txt_fecemisiondoc,			
							txt_numerodoccliente:txt_numerodoccliente,
							cmb_tipodocumentocliente:cmb_tipodocumentocliente,
							txt_razonsocialcliente:txt_razonsocialcliente,
							cmb_monedadocumento:cmb_monedadocumento,
							cmb_monedadocumentonombre:cmb_monedadocumentonombre,
							txt_operaciongravadas:txt_operaciongravadas,
							txt_operacioninafectos:txt_operacioninafectos,
							txt_operacionexoneradas:txt_operacionexoneradas,
							txt_igvtotal:txt_igvtotal,
							txt_descuentototal:txt_descuentototal,
							
							txt_importetotal:txt_importetotal,
							txt_operacionexportacion:txt_operacionexportacion,
							txt_operaciongratuitas:txt_operaciongratuitas,
							
							txt_porcentajedetraccion:txt_porcentajedetraccion,
							txt_montodetraccion:txt_montodetraccion,
							txt_montodetraccionreferencial:txt_montodetraccionreferencial,
							txt_descripciondetraccion:txt_descripciondetraccion,
							txt_leyendadetraccion:txt_leyendadetraccion,
							
							txt_descuentoglobal:txt_descuentoglobal,
							txt_correocliente:txt_correocliente,
														
							txt_emisorcorreo:txt_emisorcorreo,
							txt_emisorubigeo:txt_emisorubigeo,
							txt_emisordireccion:txt_emisordireccion,
							txt_emisorrubanizacion:txt_emisorrubanizacion,
							txt_emisorprovincia:txt_emisorprovincia,
							txt_emisordepartamento:txt_emisordepartamento,
							txt_emisordistrito:txt_emisordistrito,
							txt_emisorpaiscodigo:txt_emisorpaiscodigo,
							cod_tipregistrogeneral:cod_tipregistrogeneral,
							
							txt_porcentajepercepcion:txt_porcentajepercepcion,
							txt_baseimponiblepercepcion:txt_baseimponiblepercepcion,
							txt_totalpercepcion:txt_totalpercepcion,
							txt_totalventapercepcion:txt_totalventapercepcion,
							
							txt_clienteubigeo:txt_clienteubigeo,
							txt_clientedireccion:txt_clientedireccion,
							txt_clienteubanizacion:txt_clienteubanizacion,
							txt_clienteprovincia:txt_clienteprovincia,
							txt_clientedepartamento:txt_clientedepartamento,
							txt_clientedistrito:txt_clientedistrito,
							txt_clientepaiscodigo:txt_clientepaiscodigo,
							
							cmb_tipodocumentoreferencia:cmb_tipodocumentoreferencia,
							txt_numerodocumentoreferencia:txt_numerodocumentoreferencia,
							cmb_tiponotadecredito:cmb_tiponotadecredito,
							txt_motivodenotacredito:txt_motivodenotacredito,
							
							//cmb_tipodocumentoreferencia,
							//txt_numerodocumentoreferencia,
							//cmb_tiponotadecredito,
							//txt_motivodenotacredito,
							
							tipo_registro:tipo_registro,
							txt_documentomodificar:txt_documentomodificar,
							txt_otroscargos:txt_otroscargos,
							txt_porcentajeotroscargos:txt_porcentajeotroscargos,
							arr_adicional_Cantidad:datoadicional_Cantidad,
							arr_adicional_Codigo:datoadicional_Codigo,
							arr_adicional_Valor:datoadicional_Valor
							
						},
						beforeSend:function()
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando. Espere por favor...</div>');
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionEmpresa').empty().append('');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_ProductosDocumento();
								
								
								if (tipo_registro==0)//GUARDADO
								{
									if (cmb_tipodocumentosunat=='01')//FACTURA
									{
										$('#txt_numerodocumento_respuesta').val(result.numero);
										$('#txt_estadodocumento_respuesta').val('BORRADOR');
										$('#txt_descripciondocumento_respuesta').val('PENDIENTE DE DECLARAR');
										dialogregistrofactura.dialog( "open" );
									}
									else if (cmb_tipodocumentosunat=='03')//BOLETA DE VENTA
									{
										$('#txt_numerodocumentoboleta_respuesta').val(result.numero);
										$('#txt_estadoboleta_respuesta').val('BORRADOR');
										$('#txt_mensajeboleta_respuesta').val('PENDIENTE DE PUBLICAR');
										dialogregistroboleta.dialog( "open" );
									}
									else
									{
										if (cmb_tipodocumentosunat=='07')
										{
											$('#div_etiquetencynd').empty().append('Numero de Nota Credito :');
										}
										else
										{
											$('#div_etiquetencynd').empty().append('Numero de Nota Debito :');
										}
										$('#txt_numerodocumentoncynd_respuesta').val(result.numero);										
										$('#txt_estadodocumentoncynd_respuesta').val('BORRADOR');
										if (cmb_tipodocumentoreferencia=='03')//REFERENCIA ES BOLETA
										{
											$('#txt_mensajencynd_respuesta').val('PENDIENTE DE PUBLICAR');
										}
										else //ES FACTURA O -
										{
											$('#txt_mensajencynd_respuesta').val('PENDIENTE DE DECLARAR');
										}										
										dialogregistroncynd.dialog( "open" );
									}
								}
								else //DECLARADO
								{								
									if (cmb_tipodocumentosunat=='01')//FACTURA
									{
										$('#txt_numerodocumento_respuesta').val(result.numero);
										$('#txt_estadodocumento_respuesta').val('POR PROCESAR');
										$('#txt_descripciondocumento_respuesta').val('ENVIADO A DECLARAR');
										dialogregistrofactura.dialog( "open" );
									}
									else if (cmb_tipodocumentosunat=='03')//BOLETA DE VENTA
									{
										$('#txt_numerodocumentoboleta_respuesta').val(result.numero);
										$('#txt_estadoboleta_respuesta').val('POR PROCESAR');
										$('#txt_mensajeboleta_respuesta').val('PENDIENTE DE DECLARAR');
										dialogregistroboleta.dialog( "open" );
									}
									else
									{
										if (cmb_tipodocumentosunat=='07')
										{
											$('#div_etiquetencynd').empty().append('Numero de Nota Credito :');
										}
										else
										{
											$('#div_etiquetencynd').empty().append('Numero de Nota Debito :');
										}
										$('#txt_numerodocumentoncynd_respuesta').val(result.numero);									
										$('#txt_estadodocumentoncynd_respuesta').val('POR PROCESAR');
										if (cmb_tipodocumentoreferencia=='03')//REFERENCIA ES BOLETA
										{
											$('#txt_mensajencynd_respuesta').val('PENDIENTE DE DECLARAR');
										}
										else //ES FACTURA O -
										{
											$('#txt_mensajencynd_respuesta').val('ENVIADO A DECLARAR');
										}
										dialogregistroncynd.dialog( "open" );
									}
								}
								/*
								"01";"FACTURA"
								"03";"BOLETA DE VENTA"
								"07";"NOTA DE CREDITO"
								"08";"NOTA DE DEBITO"
								*/
								Limpiar_DatosRegistroDocumento();

								if ($('#txt_modificarregistro').val()==1)
								{
									setTimeout('Iniciar_PaginaComprobante()',4000)
								}
								
								return;
							}
							else if(result.status==2)
							{
								$('#div_MensajeValidacionEmpresa').empty().append('');
								alert('El documento '+result.numero+' ya esta registrado !.');
								
								//setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
							else if(result.status==3)
							{
								$('#div_MensajeValidacionEmpresa').empty().append('');
								alert('El comprobante no tiene productos en su lista!.');
								ncsistema.Listar_ProductosDocumento();
								//setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								$('#div_MensajeValidacionEmpresa').empty().append('Error al registrar los datos');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}
					});
				},
			}
			
			var datoadicional_Cantidad = 0;
			var datoadicional_Codigo = [];
			var datoadicional_Valor = [];
			function Registrar_DatosAdicionales()
			{
				i=0; 
				datoadicional_Codigo = [];
				datoadicional_Valor = []
				datoadicional_Cantidad = 0
				$('#Tab_FiltroDatosAdicionales tr').each(function () {
					if (i>0){
						if ($("#cbox_datoadicional_seleccion_"+(i-1)).is(":checked"))
						{
							datoadicional_Codigo[datoadicional_Cantidad]=($(this).find("td").eq(2).html());
							datoadicional_Valor[datoadicional_Cantidad]=$("#txt_datoadicional_valor_"+(i-1)).val();
							datoadicional_Cantidad++;
						}
					}
					i++;
				});
				if (datoadicional_Cantidad>56)
				{
					alert("Solo está permitido seleccionar 56 Campos Adicionales!");
					return;
				}
				dialogAdicional.dialog( "close" );
			}
			
			function Seleccionar_DatoAdicional(key)
			{
				if ($("#cbox_datoadicional_seleccion_"+key).is(":checked"))
				{
					$("#txt_datoadicional_valor_"+key).prop('disabled', false);
					//datoadicional_Cantidad++;
				}else
				{
					$("#txt_datoadicional_valor_"+key).prop('disabled', true);
					//datoadicional_Cantidad--;
				}
			}
			
			function Seleccionar_Producto(id, cod_producto, nom_corto, vv_precio, cod_unidmedsunat)
			{
				dialogdatosbusquedaproducto.dialog( "close" );
				var txt_config_valorprecio=$.trim($('#txt_config_valorprecio').val());
				var txt_cero=0;
				$('#txt_idprod').val(id);
				$('#txt_codigoprod').val(cod_producto);
				$('#txt_descripcion').val(nom_corto);
				$('#cmb_unidadmedida').val(cod_unidmedsunat);
				
				if (txt_config_valorprecio==0)
				{
					$('#txt_valorunitario').val(parseFloat(vv_precio).toFixed(2));
					
					$("#txt_valorunitario").prop('disabled', false);
					$("#txt_descuento").prop('disabled', false);
					$("#txt_precio").prop('disabled', true);
					$("#txt_descuentoIGV").prop('disabled', true);
				}else
				{
					$('#txt_precio').val(parseFloat(vv_precio).toFixed(2));
					
					$("#txt_valorunitario").prop('disabled', true);
					$("#txt_descuento").prop('disabled', true);
					$("#txt_precio").prop('disabled', false);
					$("#txt_descuentoIGV").prop('disabled', false);
										
					if ($("#cbox_opergratisdocumento").is(":checked"))
					{				
						$("#txt_descuentoIGV").prop('disabled', true);
					}
					else
					{
						$("#txt_descuentoIGV").prop('disabled', false);
					}
				}
			}
		
			function Iniciar_PaginaComprobante()
			{
				document.location.href= '<?php echo base_url()?>comprobante';
			}
				
			function Eliminar_Productotemporal(tmp_prod)
			{
				if(confirm("¿ Esta Seguro de Eliminar el Producto ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>comprobante/Eliminar_Productotemporal',type:'post',dataType:'json',
						data:
						{
							tmp_prod:tmp_prod,
						},
						beforeSend:function()
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando. Espere por favor...</div>')
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminación del Producto se realizó con éxito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_ProductosDocumento();
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el producto</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}			
					});
				}
			}
			
			$(function() 
			{
				function Registrar_Producto() 
				{
					var valid = true;
	
					var txt_codigoprod=$.trim($('#txt_codigoprod').val());	
					var txt_cantidad=$.trim($('#txt_cantidad').val());	
					var cmb_unidadmedida=$.trim($('#cmb_unidadmedida').val());	
					var txt_descripcion=$.trim($('#txt_descripcion').val());	
					var txt_valorunitario=$.trim($('#txt_valorunitario').val());
					var txt_descuento=$.trim($('#txt_descuento').val());	
					var txt_isc=$.trim($('#txt_isc').val());	
					var cmb_tipoafectacion=$.trim($('#cmb_tipoafectacion').val());	
					var txt_igv=$.trim($('#txt_igv').val());	
					var txt_valortotal=$.trim($('#txt_valortotal').val());
					var txt_preciocobro=$.trim($('#txt_precio').val());
					var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());	
					var txt_descuentoIGV=$.trim($('#txt_descuentoIGV').val());
					
					allFields = $([]).add( $('#txt_codigoprod') ).add( $('#txt_cantidad') ).add( $('#cmb_unidadmedida') ).add( $('#txt_descuento') ).add( $('#txt_descripcion') ).add( $('#txt_valorunitario') ).add( $('#cmb_tipoafectacion') );
					
					allFields.removeClass( "ui-state-error" );
					
					if (txt_codigoprod=="")
					{					
						$('#txt_codigoprod').addClass( "ui-state-error" );
						return;
					}
					
					if (txt_cantidad=="" || isNaN(txt_cantidad))
					{					
						$('#txt_cantidad').addClass( "ui-state-error" );
						
						return;
					}
					if (parseFloat(txt_cantidad)<=0)
					{					
						$('#txt_cantidad').addClass( "ui-state-error" );
						return;
					}
					
					if (cmb_unidadmedida=="")
					{					
						$('#cmb_unidadmedida').addClass( "ui-state-error" );
						return;
					}
					if (txt_descripcion=="")
					{					
						$('#txt_descripcion').addClass( "ui-state-error" );
						return;
					}
					
					if (txt_valorunitario=="" || isNaN(txt_valorunitario))
					{					
						$('#txt_valorunitario').addClass( "ui-state-error" );
						return;
					}
					if (parseFloat(txt_valorunitario)<=0)
					{					
						$('#txt_valorunitario').addClass( "ui-state-error" );
						return;
					}
					if (parseFloat(txt_precio)<=0)
					{					
						$('#txt_precio').addClass( "ui-state-error" );
						return;
					}
					
					if (cmb_tipoafectacion=="")
					{					
						$('#cmb_tipoafectacion').addClass( "ui-state-error" );
						return;
					}
					
					var cod_tipregist=0;					
					if ($("#cbox_exportaciondocumento").is(":checked"))//EXPORTACION
					{
						cod_tipregist=2;
					}
					else if ($("#cbox_opergratisdocumento").is(":checked"))//OPERACIONES GRATUITAS
					{
						cod_tipregist=3;
					}
					else
					{
						cod_tipregist=1;
					}
					
					$.ajax({
						url:'<?php echo base_url()?>comprobante/Guardar_Registroproductos',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_codigoprod:txt_codigoprod,
							txt_cantidad:txt_cantidad,
							cmb_unidadmedida:cmb_unidadmedida,
							txt_descripcion:txt_descripcion,
							txt_valorunitario:txt_valorunitario,
							txt_descuento:txt_descuento,
							txt_isc:txt_isc,
							cmb_tipoafectacion:cmb_tipoafectacion,
							txt_igv:txt_igv,
							txt_valortotal:txt_valortotal,
							txt_RucEmpresa:txt_RucEmpresa,
							cod_tipregist:cod_tipregist,
							txt_preciocobro:txt_preciocobro,
							txt_descuentoIGV:txt_descuentoIGV
						},
						beforeSend:function()
						{
							/*$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');*/
						},
						success:function(result)
						{
							if(result.status==1)
							{
								//alert('El registro de los datos se realizo con exito');
								ncsistema.Listar_ProductosDocumento();
								Limpiar_DatosProducto();
								return;
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								//alert('Error al registrar los datos.');
								return;
							}
						}
					});
					//dialog.dialog( "close" );
					return valid;
				}
				
				//Ventana para registrar nuevos adicionales
				$("#create-aditional" ).button().on("click", function() 
				{
					ncsistema.filtro_datosAdicionales();
					dialogAdicional.dialog( "open" );
				});
				
				dialogAdicional = $("#dialog-form-adicional").dialog({
					autoOpen: false,
					height: 340,
					width: 550,
					modal: true,
					buttons: 
					{
						"Aceptar": Registrar_DatosAdicionales,
						"Cancelar": function() 
							{
								dialogAdicional.dialog( "close" );
							}
					},
					close: function() 
					{
						form[0].reset();
					}
				});
				/*form = dialogAdicional.find( "form" ).on( "submit", function( event ) 
				{
					event.preventDefault();
					Registrar_DatosAdicionales();
				});*/
				
				dialog = $("#dialog-form").dialog({
					autoOpen: false,
					height: 450,
					width: 550,
					modal: true,
					buttons: 
					{
						"Registrar Producto": Registrar_Producto,
						"Cerrar": function() 
						{
							dialog.dialog( "close" );
							ncsistema.Listar_ProductosDocumento();
						}
					},
					close: function() 
					{
						form[ 0 ].reset();
						//allFields.removeClass( "ui-state-error" );
					}
				});
					
				form = dialog.find( "form" ).on( "submit", function( event ) 
				{
					event.preventDefault();
					Registrar_Producto();
				});
				
				//Ventana para registrar nuevos productos
				$( "#create-user" ).button().on( "click", function() 
				{
					var cantidad=$.trim($('#numeroitem').val());

					var cod_tipregistrogeneral=0;					
					if ($("#cbox_exportaciondocumento").is(":checked"))//EXPORTACION
					{
						cod_tipregistrogeneral=2;
					}
					else if ($("#cbox_opergratisdocumento").is(":checked"))//OPERACIONES GRATUITAS
					{
						cod_tipregistrogeneral=3;
					}
					else
					{
						cod_tipregistrogeneral=1;
					}

					if (cantidad==0)
					{
						$('#txt_tipoderegistrodocumento').val(cod_tipregistrogeneral)
					}
					else
					{
						var txt_tipoderegistrodocumento=$.trim($('#txt_tipoderegistrodocumento').val());
						if (txt_tipoderegistrodocumento!=cod_tipregistrogeneral)
						{
							alert('No puede combinar productos para diferentes tipos de registros');
							return;
						}
					}
					
					cantidad++;
					$.trim($('#txt_numeroitem').val(cantidad));						
					
					var txt_config_valorprecio=$.trim($('#txt_config_valorprecio').val());
					if (txt_config_valorprecio==0)
					{
						
						$("#txt_valorunitario").prop('disabled', false);
						$("#txt_precio").prop('disabled', true);
						$("#txt_descuento").prop('disabled', false);
						$("#txt_descuentoIGV").prop('disabled', true);
							
						if ($("#cbox_opergratisdocumento").is(":checked"))
						{
							$("#txt_descuento").prop('disabled', true);
						}else
						{
							if ($("#cbox_exportaciondocumento").is(":checked"))
							{
								$("#txt_descuento").prop('disabled', false);
							}
						}
					}else
					{
						$("#txt_valorunitario").prop('disabled', true);
						$("#txt_precio").prop('disabled', false);
						
						$("#txt_descuento").prop('disabled', true);
						$("#txt_descuentoIGV").prop('disabled', false);
						
						if ($("#cbox_opergratisdocumento").is(":checked"))
						{
							$("#txt_descuentoIGV").prop('disabled', true);
						}else
						{
							if ($("#cbox_exportaciondocumento").is(":checked"))
							{
								$("#txt_descuentoIGV").prop('disabled', false);
							}
						}
					}
					
					dialog.dialog( "open" );
				});
				
				dialogdetraccion = $("#dialog-form-detraccion").dialog({
					autoOpen: false,
					height: 250,
					width: 550,
					modal: true,
					buttons: 
					{
						/*
						"Create an account": Registrar_Producto,*/
						"Aceptar": function() 
						{
							dialogdetraccion.dialog( "close" );							
						}
					},
						
						close: function() 
						{
							form[ 0 ].reset();
							//allFields.removeClass( "ui-state-error" );
						}
					});
					
					$( "#create-detraccion" ).button().on( "click", function() 
					{
						dialogdetraccion.dialog( "open" );
					});
					
				dialogpercepcion = $("#dialog-form-percepcion").dialog({
					autoOpen: false,
					height: 200,
					width: 550,
					modal: true,
					buttons: 
					{
						/*
						"Create an account": Registrar_Producto,*/
						"Aceptar": function() 
						{
							dialogpercepcion.dialog( "close" );							
						}
					},
						
						close: function() 
						{
							form[ 0 ].reset();
						}
					});
					
					$( "#create-percepcion" ).button().on( "click", function() 
					{
						dialogpercepcion.dialog( "open" );
					});
				
				dialogotroscargos = $("#dialog-form-otroscargos").dialog({
					autoOpen: false,
					height: 170,
					width: 230,
					modal: true,
					buttons: 
					{
						/*
						"Create an account": Registrar_Producto,*/
						"Aceptar": function() 
						{
							dialogotroscargos.dialog( "close" );							
						}
					},
						
						close: function() 
						{
							form[ 0 ].reset();
						}
					});
					
					$( "#create-otros-cargos" ).button().on( "click", function() 
					{
						dialogotroscargos.dialog( "open" );
					});
					
				dialogregistrofactura = $("#dialog-form-registrofactura").dialog({
					autoOpen: false,
					height: 180,
					width: 400,
					modal: true,
					buttons: 
					{
						"Aceptar": function() 
						{
							$('#txt_numerodocumento_respuesta').val('');
							$('#txt_estadodocumento_respuesta').val('');
							$('#txt_descripciondocumento_respuesta').val('');
							dialogregistrofactura.dialog( "close" );
							
						}
					},
						
						close: function() 
						{
							$('#txt_numerodocumento_respuesta').val('');
							$('#txt_estadodocumento_respuesta').val('');
							$('#txt_descripciondocumento_respuesta').val('');
							form[ 0 ].reset();
						}
					});
				
					
				dialogregistroboleta = $("#dialog-form-registroboleta").dialog({
					autoOpen: false,
					height: 180,
					width: 400,
					modal: true,
					buttons: 
					{
						"Aceptar": function() 
						{
							$('#txt_numerodocumentoboleta_respuesta').val('');
							$('#txt_mensajeboleta_respuesta').val('');
							dialogregistroboleta.dialog( "close" );
							
						}
					},
						
						close: function() 
						{
							$('#txt_numerodocumentoboleta_respuesta').val('');
							$('#txt_mensajeboleta_respuesta').val('');
							form[ 0 ].reset();
						}
					});
				
				
				dialogregistroncynd = $("#dialog-form-registroncynd").dialog({
					autoOpen: false,
					height: 170,
					width: 400,
					modal: true,
					buttons: 
					{
						"Aceptar": function() 
						{
							$('#txt_numerodocumentoncynd_respuesta').val('');
							$('#txt_mensajencynd_respuesta').val('');
							dialogregistroncynd.dialog( "close" );
							
						}
					},
						
						close: function() 
						{
							$('#txt_numerodocumentoncynd_respuesta').val('');
							$('#txt_mensajencynd_respuesta').val('');
							form[ 0 ].reset();
						}
					});
					
				dialogdatosemisor = $("#dialog-datosemisor").dialog({
					autoOpen: false,
					height: 290,
					width: 400,
					modal: true,
					buttons: 
					{
						"Aceptar": function() 
						{
							//$('#txt_numerodocumento_respuesta').val('');
							//$('#txt_estadodocumento_respuesta').val('');
							//$('#txt_descripciondocumento_respuesta').val('');
							dialogdatosemisor.dialog( "close" );
							
						}
					},
						
						close: function() 
						{
							//$('#txt_numerodocumento_respuesta').val('');
							//$('#txt_estadodocumento_respuesta').val('');
							//$('#txt_descripciondocumento_respuesta').val('');
							form[ 0 ].reset();
						}
					});	
				
				dialogdatoscliente = $("#dialog-datoscliente").dialog({
					autoOpen: false,
					height: 290,
					width: 400,
					modal: true,
					buttons: 
					{
						"Aceptar": function() 
						{
							//$('#txt_numerodocumento_respuesta').val('');
							//$('#txt_estadodocumento_respuesta').val('');
							//$('#txt_descripciondocumento_respuesta').val('');
							dialogdatoscliente.dialog( "close" );
							
						}
					},
						
						close: function() 
						{
							//$('#txt_numerodocumento_respuesta').val('');
							//$('#txt_estadodocumento_respuesta').val('');
							//$('#txt_descripciondocumento_respuesta').val('');
							form[ 0 ].reset();
						}
					});	
					
				dialogdatosbusquedaproducto = $("#dialog-datosbusquedaproducto").dialog({
					autoOpen: false,
					height: 320,
					width: 500,
					modal: true,
					buttons: 
					{
						"Salir": function() 
						{
							dialogdatosbusquedaproducto.dialog( "close" );
						}
					},
						close: function() 
						{
							form[ 0 ].reset();
						}
					});	
			});
			
			function ver_datosemisor()
			{
				Datos_Emisor();
				dialogdatosemisor.dialog( "open" );
			}
			
			function ver_datoscliente()
			{
				dialogdatoscliente.dialog( "open" );
			}
			
			function ver_filtro_producto()
			{
				var txt_codigoprod =  $.trim($('#txt_codigoprod').val());
				if (txt_codigoprod=="-")
				{
					$('#txt_busqueda_codigoproducto').val('');
				}else{
					$('#txt_busqueda_codigoproducto').val(txt_codigoprod);
				}
								
				$('#txt_busqueda_descripcionproducto').val('');
				ncsistema.Filtro_producto_tabla("");
				dialogdatosbusquedaproducto.dialog( "open" );
			}
			
			function ver_filtro_producto_button()
			{
				ncsistema.Filtro_producto();
			}
			
			function Calcular_Montos()
			{
				var txt_config_valorprecio=$.trim($('#txt_config_valorprecio').val());
				
				var txt_cantidad=$.trim($('#txt_cantidad').val());
				var txt_descuento=($.trim($('#txt_descuento').val())).replace(',', '');
				var cod_tipafect=$.trim($('#cmb_tipoafectacion').val());
				var txt_valorigv=$.trim($('#txt_valorigv').val());//captura valor de 18
				var txt_valorotroscargos=$.trim($('#txt_valorotroscargos').val());
				
				//var txt_valorigv=(parseFloat($('#txt_valorigv').val())/100).toFixed(2);//captura valor de 18//0.18
				//var txt_valorotroscargos=(parseFloat($('#txt_valorotroscargos').val())/100).toFixed(2);//0.10
				
				if (txt_valorotroscargos=="" || isNaN(txt_valorotroscargos)){
					txt_valorotroscargos=0;
				}
				
				var tipo_registro=0;				
				if ($("#cbox_exportaciondocumento").is(":checked"))//EXPORTACION
				{
					tipo_registro=2;
				}else 
					if ($("#cbox_opergratisdocumento").is(":checked"))//OPERACIONES GRATUITAS
					{
						tipo_registro=3;
					}
					else{
						tipo_registro=1;
					}
				$('#div_mensajereferencia').empty().append('');
				if (txt_cantidad=="" || isNaN(txt_cantidad)){
					txt_cantidad=0;
				}
				if (txt_descuento=="" || isNaN(txt_descuento)){
					txt_descuento=0;
				}
				
				var txt_igv=0;
				var txt_valortotal=0;
				var txt_precio=0;
				var txt_descuentoIGV=0;
				var txt_preciototal=0;
				var txt_valorunitario=0;
				var txt_preciounitario=0;
				
				if (txt_config_valorprecio==0)//Tipo de configuración es por valor venta
				{
					txt_valorunitario=($.trim($('#txt_valorunitario').val())).replace(',', '');
					if (txt_valorunitario=="" || isNaN(txt_valorunitario)){
						txt_valorunitario=0;
					}
					
					if (tipo_registro==1)//NORMAL
					{
						if (cod_tipafect=='10')//GRAVADO - OPERACION ONEROSA
						{	
							txt_preciounitario=parseFloat(parseFloat(txt_valorunitario)*(1+parseFloat(txt_valorigv)/100)).toFixed(2);
							
							txt_precio=parseFloat(parseFloat(txt_valorunitario)*(1+parseFloat(txt_valorigv)/100).toFixed(2));
							txt_descuentoIGV=parseFloat(parseFloat(txt_descuento)*(1+parseFloat(txt_valorigv)/100)).toFixed(2);
							
							if (txt_cantidad==0){
								txt_igv=0;
								txt_valortotal=0;
								txt_preciototal=0;
							}
							else{
								txt_igv=parseFloat(( (parseFloat(txt_cantidad)*parseFloat(txt_valorunitario)) -parseFloat(txt_descuento)) * parseFloat(txt_valorigv/100)).toFixed(2);
								txt_valortotal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento)).toFixed(2);
								txt_preciototal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_precio))-parseFloat(txt_descuentoIGV)).toFixed(2);
							}
						}else 
							if (cod_tipafect=='20')//EXONERADO - OPERACION ONEROSA
							{	
								txt_preciounitario=parseFloat(txt_valorunitario).toFixed(2);
								txt_precio=parseFloat(txt_valorunitario).toFixed(2);
								txt_descuentoIGV=parseFloat(txt_descuento).toFixed(2);
								
								if (txt_cantidad==0){
									txt_valortotal=0;
									txt_preciototal=0;
								}
								else{
									txt_valortotal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento)).toFixed(2);
									txt_preciototal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_precio))-parseFloat(txt_descuentoIGV)).toFixed(2);
								}
							}else 
								if (cod_tipafect=='30')//INAFECTO - OPERACION ONEROSA
								{				
									txt_preciounitario=parseFloat(txt_valorunitario).toFixed(2);
									txt_precio=parseFloat(txt_valorunitario).toFixed(2);
									txt_descuentoIGV=parseFloat(txt_descuento).toFixed(2);
									
									if (txt_cantidad==0){
										txt_valortotal=0;
										txt_preciototal=0;
									}
									else{
										txt_valortotal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento)).toFixed(2);
										txt_preciototal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_precio))-parseFloat(txt_descuentoIGV)).toFixed(2);
									}
								}
					}
					else 
					{
						if (tipo_registro==2)//EXPORTACION
						{				
							if (cod_tipafect=='40')//EXPORTACION
							{				
								txt_preciounitario=parseFloat(txt_valorunitario).toFixed(2);
								txt_precio=parseFloat(txt_valorunitario).toFixed(2);
								txt_descuentoIGV=parseFloat(txt_descuento).toFixed(2);
								
								if (txt_cantidad==0){
									txt_valortotal=0;
									txt_preciototal=0;
								}
								else{
									txt_valortotal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento)).toFixed(2);
									txt_preciototal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_precio))-parseFloat(txt_descuentoIGV)).toFixed(2);
								}
							}
						}else 
							if (tipo_registro==3)//OPERACIONES GRATUITAS
							{				
								if (cod_tipafect=='11' || cod_tipafect=='12' || cod_tipafect=='13' || cod_tipafect=='14' || cod_tipafect=='15' || cod_tipafect=='16')//
								{				
									txt_descuentoIGV=0;
									txt_descuento=0;
									//VALIDAR EL CALCULO DEL IGV
									txt_igv=parseFloat((( parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento))*parseFloat(txt_valorigv/100)).toFixed(2);
									txt_valortotal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento)).toFixed(2);
									
									txt_precio=0;
									txt_preciounitario=0;
									txt_preciototal=0;
									
									$('#div_mensajereferencia').empty().append('IGV Referencial');
								}else 
									if (cod_tipafect=='21' || cod_tipafect=='31' || cod_tipafect=='32' || cod_tipafect=='33' || cod_tipafect=='34' || cod_tipafect=='35' || cod_tipafect=='36')//EXPORTACION
									{	
										txt_descuentoIGV=0;
										txt_descuento=0;	
										//VALIDAR EL CALCULO DEL IGV
										//txt_igv=(((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento))*parseFloat(txt_valorigv/100));
										txt_valortotal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento)).toFixed(2);
										
										txt_precio=0;
										txt_preciounitario=0;
										txt_preciototal=0;
									}
							}
					}
					$('#txt_igv').val(parseFloat(txt_igv).toFixed(2));
					$('#txt_valortotal').val(parseFloat(txt_valortotal).toFixed(2));
					
					$('#txt_precio').val(parseFloat(txt_precio).toFixed(2));
					$('#txt_preciounitario').val(parseFloat(txt_preciounitario).toFixed(2));
					$('#txt_descuentoIGV').val(parseFloat(txt_descuentoIGV).toFixed(2));
					$('#txt_preciototal').val(parseFloat(txt_preciototal).toFixed(2));
				}
				else//Tipo de configuración es por Precio de Cobro
				{
					txt_precio=($.trim($('#txt_precio').val())).replace(',', '');//es precio por cobro
					txt_descuentoIGV=($.trim($('#txt_descuentoIGV').val())).replace(',', '');
					//txt_valorigv=18
					//txt_valorotroscargos=10=jala de parametros iniciales el %
					if (txt_precio=="" || isNaN(txt_precio)){
						txt_precio=0;
					}
					if (txt_descuentoIGV==""  || isNaN(txt_descuentoIGV)){
						txt_descuentoIGV=0;
					}
					if (tipo_registro==1)//NORMAL
					{
						if (cod_tipafect=='10')//GRAVADO - OPERACION ONEROSA
						{				
							txt_valorunitario=parseFloat(parseFloat(txt_precio)/(1+(parseFloat(txt_valorigv)/100)+(parseFloat(txt_valorotroscargos)/100))).toFixed(2);
							txt_descuento=parseFloat(parseFloat(txt_descuentoIGV)/(1+(parseFloat(txt_valorigv)/100)+(parseFloat(txt_valorotroscargos)/100))).toFixed(2);
							txt_preciounitario=parseFloat(parseFloat(txt_valorunitario)*(1+(parseFloat(txt_valorigv)/100))).toFixed(2);
							
							//txt_precio=parseFloat(txt_valorunitario)*parseFloat(1+txt_valorigv/100+txt_valorotroscargos/100);
							if (txt_cantidad==0)
							{
								txt_igv=0;
								txt_valortotal=0;
								txt_preciototal=0;
							}
							else{
								txt_igv=parseFloat(((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento))*parseFloat(txt_valorigv/100)).toFixed(2);
								txt_valortotal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento)).toFixed(2);
								txt_preciototal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_precio))-parseFloat(txt_descuentoIGV)).toFixed(2);
							}
						}else 
							if (cod_tipafect=='20')//EXONERADO - OPERACION ONEROSA
							{	
								//alert(txt_precio);
								txt_valorunitario=parseFloat(parseFloat(txt_precio)/(1+(parseFloat(txt_valorotroscargos)/100))).toFixed(2);
								txt_preciounitario=parseFloat(txt_valorunitario).toFixed(2);
								txt_descuento=parseFloat(parseFloat(txt_descuentoIGV)/(1+(parseFloat(txt_valorotroscargos)/100))).toFixed(2);	
								if (txt_cantidad==0){
									txt_valortotal=0;
									txt_preciototal=0;
								}
								else{
									txt_valortotal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento)).toFixed(2);
									txt_preciototal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_precio))-parseFloat(txt_descuentoIGV)).toFixed(2);
								}
							}else 
								if (cod_tipafect=='30')//INAFECTO - OPERACION ONEROSA
								{	
									txt_valorunitario=parseFloat(parseFloat(txt_precio)/(1+(parseFloat(txt_valorotroscargos)/100))).toFixed(2);
									txt_preciounitario=parseFloat(txt_valorunitario).toFixed(2);
									txt_descuento=parseFloat(parseFloat(txt_descuentoIGV)/(1+(parseFloat(txt_valorotroscargos)/100))).toFixed(2);
									if (txt_cantidad==0){
										txt_valortotal=0;
										txt_preciototal=0;
									}
									else{
										txt_valortotal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento)).toFixed(2);
										txt_preciototal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_precio))-parseFloat(txt_descuentoIGV)).toFixed(2);
									}
								}
							//alert(txt_valorunitario);
					}else
					{
						if (tipo_registro==2)//EXPORTACION
						{
							if (cod_tipafect=='40')//EXPORTACION
							{				
								txt_valorunitario=parseFloat(txt_precio);//(parseFloat(txt_precio)/(1+(parseFloat(txt_valorigv)/100)+(parseFloat(txt_valorotroscargos)/100)));
								txt_descuento=parseFloat(txt_descuentoIGV);
								txt_preciounitario=parseFloat(txt_valorunitario).toFixed(2);
								
								txt_valortotal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento)).toFixed(2);
								txt_preciototal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_precio))-parseFloat(txt_descuentoIGV)).toFixed(2);
							}
						}else
							if (tipo_registro==3)//OPERACIONES GRATUITAS
							{
								
								if (cod_tipafect=='11' || cod_tipafect=='12' || cod_tipafect=='13' || cod_tipafect=='14' || cod_tipafect=='15' || cod_tipafect=='16')//EXPORTACION
								{				
									txt_valorunitario=parseFloat(parseFloat(txt_precio)/(1+(parseFloat(txt_valorigv)/100)+(parseFloat(txt_valorotroscargos)/100))).toFixed(2);
									txt_descuento=0;
									txt_igv=parseFloat(((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento))*parseFloat(txt_valorigv/100)).toFixed(2);
									txt_valortotal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento)).toFixed(2);
									txt_precio=0;
									txt_descuentoIGV=0;
									txt_preciototal=0;
									$('#div_mensajereferencia').empty().append('IGV Referencial');
								}else 
									if (cod_tipafect=='21' || cod_tipafect=='31' || cod_tipafect=='32' || cod_tipafect=='33' || cod_tipafect=='34' || cod_tipafect=='35' || cod_tipafect=='36')//EXPORTACION
									{		
										txt_valorunitario=parseFloat(parseFloat(txt_precio)/(1+(parseFloat(txt_valorigv)/100)+(parseFloat(txt_valorotroscargos)/100))).toFixed(2);
										txt_descuento=0;
										txt_valortotal=parseFloat((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento)).toFixed(2);
										txt_precio=0;
										txt_descuentoIGV=0;
										txt_preciototal=0;
									}
							}
					}
					$('#txt_igv').val(parseFloat(txt_igv).toFixed(2));
					$('#txt_valortotal').val(parseFloat(txt_valortotal).toFixed(2));
					
					$('#txt_valorunitario').val(parseFloat(txt_valorunitario).toFixed(2));
					$('#txt_descuento').val(parseFloat(txt_descuento).toFixed(2));
					$('#txt_preciounitario').val(parseFloat(txt_preciounitario).toFixed(2));
					$('#txt_preciototal').val(parseFloat(txt_preciototal).toFixed(2));
				}
				
			}
			
			
			function Calcular_Detraccion()
			{
				var txt_importetotal=($.trim($('#txt_importetotal').val())).replace(',', '');
				var txt_porcentajedetraccion=$.trim($('#txt_porcentajedetraccion').val());
				var montodetracciontotal=0;
				var txt_valortotal=0;
				
				if (txt_importetotal=="")
				{
					txt_importetotal=0;
				}
				if (txt_porcentajedetraccion=="")
				{
					txt_porcentajedetraccion=0;
				}
				montodetracciontotal=((parseFloat(txt_importetotal)*parseFloat(txt_porcentajedetraccion))/100.00);
				$('#txt_montodetraccion').val(montodetracciontotal.toFixed(2));
				
			}
			
			function Calcular_Percepcion()
			{
				var txt_porcentajepercepcion=$.trim($('#txt_porcentajepercepcion').val());
				var txt_baseimponiblepercepcion=$.trim($('#txt_baseimponiblepercepcion').val());
				
				var montopercepciontotal=0;
				var txt_valortotalpercepcion=0;
				
				if (txt_porcentajepercepcion=="")
				{
					txt_porcentajepercepcion=0;
				}
				if (txt_baseimponiblepercepcion=="")
				{
					txt_baseimponiblepercepcion=0;
				}
				montopercepciontotal=((parseFloat(txt_porcentajepercepcion)*parseFloat(txt_baseimponiblepercepcion))/100.00);
				$('#txt_totalpercepcion').val(montopercepciontotal.toFixed(2));
				
				txt_valortotalpercepcion=(parseFloat(montopercepciontotal)+parseFloat(txt_baseimponiblepercepcion))
				$('#txt_totalventapercepcion').val(txt_valortotalpercepcion.toFixed(2));
				
			}
			
			function Limpiar_DatosProducto()
			{
				$('#txt_codigoprod').val('-');
				$('#txt_cantidad').val('');
				$('#cmb_unidadmedida').val('');
				$('#txt_descripcion').val('');
				$('#txt_valorunitario').val('');
				$('#txt_descuento').val('');
				$('#txt_isc').val('');
				$('#cmb_tipoafectacion').val('');
				$('#txt_igv').val('');
				$('#txt_valortotal').val('');
				
				$('#txt_precio').val('');
				$('#txt_preciounitario').val('');
				$('#txt_descuentoIGV').val('');
				$('#txt_preciototal').val('');
				
				if ($("#cbox_exportaciondocumento").is(":checked"))//EXPORTACION
				{
					$('#cmb_tipoafectacion').val('40');}
			}
			
			function Limpiar_DatosRegistroDocumento()
			{
				$('#cmb_tipodocumentosunat').val('0');
				$('#cmb_seriedocumentosunat').val('0');
				$('#txt_numerodocumentosunat').val('');
				$('#txt_FechaEmision').datepicker('setDate', 'today');	
				$('#txt_numerodoccliente').val('');
				$('#cmb_tipodocumentocliente').val('');
				$('#txt_razonsocialcliente').val('');
				$('#cmb_monedadocumento').val('PEN');
				$('#txt_descuentototal').val('0.00');
				
				$('#txt_correocliente').val('');
				$('#txt_direccioncliente').val('');

				$('#txt_porcentajepercepcion').val('0.00');
				$('#txt_baseimponiblepercepcion').val('0.00');
				$('#txt_totalpercepcion').val('0.00');
				$('#txt_totalventapercepcion').val('0.00');
				
				$('#txt_porcentajedetraccion').val('0.00');
				$('#txt_montodetraccion').val('0.00');
				$('#txt_montodetraccionreferencial').val('0.00');
				$('#txt_descripciondetraccion').val('');
				$('#txt_leyendadetraccion').val('');
				
				$('#cmb_tipodocumentoreferencia').val('');
				$('#txt_numerodocumentoreferencia').val('');
				$('#cmb_tiponotadecredito').val('');
				$('#txt_motivodenotacredito').val('');
				$('#txt_documentomodificar').val('');
				
				OcultarFilaTabla('row3',0);//OCULTA FILA
				OcultarFilaTabla('row4',0);//OCULTA FILA
			}
			
			function Numero_Documento(cod_seriedoc)
			{
				var cmb_tipodocumentosunat=$.trim($('#cmb_tipodocumentosunat').val());
				$.trim($('#txt_numerodocumentosunat').val('0'));
				$.ajax({
					url:'<?php echo base_url()?>catalogos/Listar_SeriesDocumentos',
					type: 'post',
					dataType: 'json',
					data:
					{
						cod_tipodocumento:cmb_tipodocumentosunat
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{							
						if(result.status==1)
						{
							$.each(result.data,function(key,rs)
							{
								if (rs.ser_doc==cod_seriedoc)
								{
									$.trim($('#txt_numerodocumentosunat').val(rs.num_doc));
								}
							});	
							return;
						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}
						else
						{
							$.trim($('#txt_numerodocumentosunat').val('0'));
							return;
						}
					}
				});
			}
			
			function Serie_Documento(cod_tipodocumento,cod_serie,tipodocreferencia,codserafectado,tipodocumentoadquiriente)
			{
				/*
				"01""FACTURA"
				"03""BOLETA DE VENTA"
				"07""NOTA DE CREDITO"
				"08""NOTA DE DEBITO"
				*/
				$('#div_seriedocumento').empty().append('');
				$('#div_tipodocumentocliente').empty().append('');	

				$('#div_catalogonombcliente').empty().append('Nombre del Cliente:');
				$('#div_titulodocumento').empty().append('Documento Cliente:');
				
				$('#div_nombrebotonguardar').empty().append('Enviar y Declarar');
				
				$('#txt_razonsocialcliente').val('');
				$('#txt_numerodoccliente').val('');
				Datos_ClienteBusqueda('');
				
				$('#txt_numerodocumentosunat').val('');
				
				//$("#cbox_exportaciondocumento").prop('disabled', false);	
				
				//$('#div_etiqueteexportacion').empty().append('Exportacion :');	
				//$('#div_checkexportacion').empty().append('<input id="cbox_exportaciondocumento" type="checkbox" value="" name="cbox_exportaciondocumento" onClick="javascript:Seleccionar_CheckExportacion()" >');	
				
				if(cod_tipodocumento=='07' || cod_tipodocumento=='08')
				{
					//create-detraccion
					//$("#create-detraccion").prop('disabled', true);
					OcultarFilaPassword('row1',0);
				}
				else
				{
					//$("#create-detraccion").prop('disabled', false);
					OcultarFilaPassword('row1',1);
				}
								
				if ($("#cbox_exportaciondocumento").is(":checked"))
				{
					
					if (cod_tipodocumento=='03')
					{
						$('#div_etiqueteexportacion').empty().append('');	
						$('#div_checkexportacion').empty().append('');	
						$('#div_nombrebotonguardar').empty().append('Publicar');
					}
				
					newHtml='';
					newHtml+='<select id="cmb_tipodocumentocliente" style="width:95%;height:25px" disabled="disabled">';			
					newHtml+='<option value="0">Doc.Trib.No.Dom.Sin.Ruc</option>';				
					newHtml+='</select>';	
					$('#div_tipodocumentocliente').empty().append(newHtml);	
					
					if (tipodocumentoadquiriente!='')
					{
						$("#cmb_tipodocumentocliente").val(tipodocumentoadquiriente);
					}
					
					
					Listar_SeriesDocumentos(cod_tipodocumento,cod_serie,tipodocreferencia,codserafectado);
					
					
					//$('#div_titulodocumento').empty().append('Doc. Trib. No. Dom. Sin. RUC:');
					$('#div_catalogonombcliente').empty().append('Nombre del Cliente:');
				}
				else
				{
					if (cod_tipodocumento!='03')
					{
						$('#div_etiqueteexportacion').empty().append('Exportacion :');	
						$('#div_checkexportacion').empty().append('<input id="cbox_exportaciondocumento" type="checkbox" value="" name="cbox_exportaciondocumento" onClick="javascript:Seleccionar_CheckExportacion()" >');
					}
					
					if (cod_tipodocumento=='01')
					{
						newHtml='';
						newHtml+='<select id="cmb_tipodocumentocliente" style="width:95%;height:25px" >';			
						newHtml+='<option value="6">RUC</option>';				
						newHtml+='</select>';	
						$('#div_tipodocumentocliente').empty().append(newHtml);	
						if (tipodocumentoadquiriente!='')
						{
							$("#cmb_tipodocumentocliente").val(tipodocumentoadquiriente);
						}
						
						Listar_SeriesDocumentos(cod_tipodocumento,cod_serie,tipodocreferencia,codserafectado);								
						$('#div_catalogonombcliente').empty().append('Razón Social Cliente:');		
						$('#div_titulodocumento').empty().append('RUC Cliente:');		
					
					}
					else if (cod_tipodocumento=='03')
					{
						$('#div_nombrebotonguardar').empty().append('Publicar');
						
						newHtml='';
						newHtml+='<select id="cmb_tipodocumentocliente" style="width:95%;height:25px" onChange="javascript:Etiquetas_TipoDocumento(this.value)">';			
						newHtml+='<option value="">[Seleccionar]</option>';
						newHtml+='<option value="0">Doc.Trib.No.Dom.Sin.Ruc</option>';	
						newHtml+='<option value="1">DNI</option>';	
						newHtml+='<option value="4">Carnet de Extranjería</option>';	
						newHtml+='<option value="6">RUC</option>';	
						newHtml+='<option value="7">Pasaporte</option>';	
						newHtml+='<option value="A">Ced. Diplomática de Identidad</option>';	
	
						newHtml+='</select>';	
						$('#div_tipodocumentocliente').empty().append(newHtml);	
						if (tipodocumentoadquiriente!='')
						{
							$("#cmb_tipodocumentocliente").val(tipodocumentoadquiriente);
						}
						
						newHtml='';
						newHtml+='<select id="cmb_seriedocumentosunat" style="width:95%;height:25px" onChange="javascript:Numero_Documento(this.value)">';
						newHtml+='<option value="0">[SELECCIONAR]</option>';
						newHtml+='</select>';		
						$('#div_seriedocumento').empty().append(newHtml);	
						//$("#cbox_exportaciondocumento").prop('disabled', true);
						$('#div_etiqueteexportacion').empty().append('');	
						$('#div_checkexportacion').empty().append('');	
						
						
						Listar_SeriesDocumentos(cod_tipodocumento,cod_serie,tipodocreferencia,codserafectado);			
					}
					else 
					{
						newHtml='';
						newHtml+='<select id="cmb_tipodocumentocliente" style="width:95%;height:25px" >';			
						newHtml+='<option value="">NINGUNO</option>';	
						newHtml+='</select>';	
						$('#div_tipodocumentocliente').empty().append(newHtml);	
						if (tipodocumentoadquiriente!='')
						{
							$("#cmb_tipodocumentocliente").val(tipodocumentoadquiriente);
						}
						
						newHtml='';
						newHtml+='<select id="cmb_seriedocumentosunat" style="width:95%;height:25px" onChange="javascript:Numero_Documento(this.value)">';
						newHtml+='<option value="0">[SELECCIONAR]</option>';
						newHtml+='</select>';		
						$('#div_seriedocumento').empty().append(newHtml);	
						Listar_SeriesDocumentos(cod_tipodocumento,cod_serie,tipodocreferencia,codserafectado);		
						$('#div_catalogonombcliente').empty().append('Nombre del Cliente:');
						$('#div_titulodocumento').empty().append('Documento Cliente:');
										
					}
				}
				//Seleccionar_CheckExportacion();
			}
			
			function Serie_DocumentoReferencia(cod_tipodocumento,cod_serie)
			{
				/*
				cod_tipodocumento: FACTURA O BOLETA
				"01""FACTURA"
				"03""BOLETA DE VENTA"
				"07""NOTA DE CREDITO"
				"08""NOTA DE DEBITO"
				*/
				$('#txt_razonsocialcliente').val('');
				$('#txt_numerodoccliente').val('');
				Datos_ClienteBusqueda('');
				
				$('#div_seriedocumento').empty().append('');
				$('#div_tipodocumentocliente').empty().append('');
				
				$('#div_catalogonombcliente').empty().append('Nombre del Cliente:');
				$('#div_titulodocumento').empty().append('Documento Cliente:');
				$('#div_etiquetedocumentoreferencia').empty().append('Documento Referenciada:');
				
				
				//$("#cbox_exportaciondocumento").prop('disabled', false);	
				$('#div_nombrebotonguardar').empty().append('Enviar y Declarar');
				if ($("#cbox_exportaciondocumento").is(":checked"))
				{
				
					if (cod_tipodocumento=='03')
					{
						$('#div_etiqueteexportacion').empty().append('');	
						$('#div_checkexportacion').empty().append('');
						$('#div_nombrebotonguardar').empty().append('Publicar');
					}

					newHtml='';
					newHtml+='<select id="cmb_tipodocumentocliente" style="width:95%;height:25px" disabled="disabled">';			
					newHtml+='<option value="0">Doc.Trib.No.Dom.Sin.Ruc</option>';				
					newHtml+='</select>';	
					$('#div_tipodocumentocliente').empty().append(newHtml);	
					Listar_SeriesDocumentosReferencia(cod_tipodocumento,cod_serie);
					
					
					//$('#div_titulodocumento').empty().append('Doc. Trib. No. Dom. Sin. RUC:');
					$('#div_catalogonombcliente').empty().append('Nombre del Cliente:');
				}
				else
				{
					if (cod_tipodocumento!='03')
					{
						$('#div_etiqueteexportacion').empty().append('Exportacion :');	
						$('#div_checkexportacion').empty().append('<input id="cbox_exportaciondocumento" type="checkbox" value="" name="cbox_exportaciondocumento" onClick="javascript:Seleccionar_CheckExportacion()" >');
					}
				
					if (cod_tipodocumento=='01')
					{
						newHtml='';
						newHtml+='<select id="cmb_tipodocumentocliente" style="width:95%;height:25px" >';			
						newHtml+='<option value="6">RUC</option>';				
						newHtml+='</select>';	
						$('#div_tipodocumentocliente').empty().append(newHtml);	
						Listar_SeriesDocumentosReferencia(cod_tipodocumento,cod_serie);		
						$('#div_catalogonombcliente').empty().append('Razón Social Cliente:');		
						$('#div_titulodocumento').empty().append('RUC Cliente:');		
						$('#div_etiquetedocumentoreferencia').empty().append('Factura Referenciada:');
					
					}
					else if (cod_tipodocumento=='03')
					{
						$('#div_nombrebotonguardar').empty().append('Publicar');
						newHtml='';
						newHtml+='<select id="cmb_tipodocumentocliente" style="width:95%;height:25px" onChange="javascript:Etiquetas_TipoDocumento(this.value)">';			
						newHtml+='<option value="">[Seleccionar]</option>';
						newHtml+='<option value="0">Doc.Trib.No.Dom.Sin.Ruc</option>';	
						newHtml+='<option value="1">DNI</option>';	
						newHtml+='<option value="4">Carnet de Extranjería</option>';	
						newHtml+='<option value="6">RUC</option>';	
						newHtml+='<option value="7">Pasaporte</option>';	
						newHtml+='<option value="A">Ced. Diplomática de Identidad</option>';	
	
						newHtml+='</select>';	
						$('#div_tipodocumentocliente').empty().append(newHtml);	
						
						newHtml='';
						newHtml+='<select id="cmb_seriedocumentosunat" style="width:95%;height:25px" onChange="javascript:Numero_Documento(this.value)">';
						newHtml+='<option value="0">[SELECCIONAR]</option>';
						newHtml+='</select>';		
						$('#div_seriedocumento').empty().append(newHtml);	
						//$("#cbox_exportaciondocumento").prop('disabled', true);
						$('#div_etiqueteexportacion').empty().append('');	
						$('#div_checkexportacion').empty().append('');	
						$('#div_etiquetedocumentoreferencia').empty().append('Boleta Referenciada:');
						
						Listar_SeriesDocumentosReferencia(cod_tipodocumento,cod_serie);			
					}
					else 
					{
						newHtml='';
						newHtml+='<select id="cmb_tipodocumentocliente" style="width:95%;height:25px" >';			
						newHtml+='<option value="">NINGUNO</option>';	
						newHtml+='</select>';	
						$('#div_tipodocumentocliente').empty().append(newHtml);	
						
						newHtml='';
						newHtml+='<select id="cmb_seriedocumentosunat" style="width:95%;height:25px" onChange="javascript:Numero_Documento(this.value)">';
						newHtml+='<option value="0">[SELECCIONAR]</option>';
						newHtml+='</select>';		
						$('#div_seriedocumento').empty().append(newHtml);	
						Listar_SeriesDocumentosReferencia(cod_tipodocumento,cod_serie);		
						$('#div_catalogonombcliente').empty().append('Nombre del Cliente:');
						$('#div_titulodocumento').empty().append('Documento Cliente:');
										
					}
				}
				//Seleccionar_CheckExportacion();

			}

			function Etiquetas_TipoDocumento(cod_tipodocumento)
			{
				if (cod_tipodocumento==6)
				{
					$('#div_catalogonombcliente').empty().append('Razón Social Cliente::');
					$('#div_titulodocumento').empty().append('RUC Cliente:');
				}
				else
				{
					$('#div_catalogonombcliente').empty().append('Nombre del Cliente:');
					$('#div_titulodocumento').empty().append('Documento Cliente:');
				}
				$('#txt_razonsocialcliente').val('');
				$('#txt_numerodoccliente').val('');
				Datos_ClienteBusqueda('');
			}


			function Listar_SeriesDocumentos(cod_tipodocumento,cod_serie,tipodocreferencia,codserafectado)
			{
				$('#div_tipodocumentoreferencia').empty().append('');

				if(cod_tipodocumento=='07' || cod_tipodocumento=='08')//ES NOTA DE CREDITO Y NOTA DE DEBITO
				{
					OcultarFilaTabla('row3',1);//MUESTRA FILA
					OcultarFilaTabla('row4',1);//MUESTRA FILA
					newHtml='';
					newHtml+='<select id="cmb_tipodocumentoreferencia" style="width:95%;height:25px" onChange="javascript:Serie_DocumentoReferencia(this.value,\''+cod_serie+'\')">';
					newHtml+='<option value="0">[SELECCIONAR]</option>';
					newHtml+='<option value="01">FACTURA</option>';
					newHtml+='<option value="03">BOLETA DE VENTA</option>';
					newHtml+='</select>';		
					$('#div_tipodocumentoreferencia').empty().append(newHtml);

					newHtml='';
					newHtml+='<select id="cmb_seriedocumentosunat" style="width:95%;height:25px" onChange="javascript:Numero_Documento(this.value)">';
					newHtml+='<option value="0">[SELECCIONAR]</option>';
					newHtml+='</select>';		
					$('#div_seriedocumento').empty().append(newHtml);

					//alert(tipodocreferencia);
					if (tipodocreferencia!='')
					{
						$('#cmb_tipodocumentoreferencia').val(tipodocreferencia);	
						$("#cmb_tipodocumentoreferencia").prop('disabled', true);
						Serie_DocumentoReferencia(tipodocreferencia,cod_serie);
					}
					if(cod_tipodocumento=='07')
					{
						
						$('#div_etiquetetipodenota').empty().append('Tipo Nota de Crédito:');
						$url='<?php echo base_url()?>catalogos/Tipo_NotaCredito';
					}
					else
					{
						$('#div_etiquetetipodenota').empty().append('Tipo Nota de Débito:');
						$url='<?php echo base_url()?>catalogos/Tipo_NotaDebito';
					}
					
					$('#div_tiponotadecredito').empty().append('');					
					$.ajax({
						url:$url,
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
								newHtml='';
								newHtml+='<select id="cmb_tiponotadecredito" style="width:60%;height:25px" onChange="javascript:Seleccionar_Tiponotadebito(this.value)">';
								newHtml+='<option value="0">[SELECCIONAR]</option>';								
								$.each(result.data,function(key,rs)
								{
									newHtml+='<option value="'+rs.codigo+'">'+rs.nombre+'</option>';
								});	
								newHtml+='</select>';		
								$('#div_tiponotadecredito').empty().append(newHtml);	
								
								if (codserafectado!='')
								{
									$('#cmb_tiponotadecredito').val(codserafectado);
									
									if (codserafectado=='03')//NOTA DE CREDITO
									{
										$("#cmb_tiponotadecredito").prop('disabled', true);
									}
									
								}
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								newHtml='';
								newHtml+='<select id="cmb_tiponotadecredito" style="width:60%;height:25px" onChange="javascript:Seleccionar_Tiponotadebito(this.value)">';
								newHtml+='<option value="0">[SELECCIONAR]</option>';
								newHtml+='</select>';		
								$('#div_tiponotadecredito').empty().append(newHtml);	
							}
						}
					});

				}
				else
				{
					OcultarFilaTabla('row3',0);//OCULTA
					OcultarFilaTabla('row4',0);//OCULTA
					
					newHtml='';
					newHtml+='<select id="cmb_tipodocumentoreferencia" style="width:95%;height:25px" >';
					newHtml+='<option value="0">[SELECCIONAR]</option>';
					newHtml+='</select>';		
					$('#div_tipodocumentoreferencia').empty().append(newHtml);
					
					$.ajax({
						url:'<?php echo base_url()?>catalogos/Listar_SeriesDocumentos',
						type: 'post',
						dataType: 'json',
						data:
						{
							cod_tipodocumento:cod_tipodocumento
						},
						beforeSend:function()
						{
	
						},
						success:function(result)
						{
							if(result.status==1)
							{
								newHtml='';
								newHtml+='<select id="cmb_seriedocumentosunat" style="width:95%;height:25px" onChange="javascript:Numero_Documento(this.value)">';
								newHtml+='<option value="0">[SELECCIONAR]</option>';
								
								$.each(result.data,function(key,rs)
								{
									newHtml+='<option value="'+rs.ser_doc+'">'+rs.ser_doc+'</option>';
								});	
								newHtml+='</select>';		
								$('#div_seriedocumento').empty().append(newHtml);	
								
								if (cod_serie!='')
								{
									$('#cmb_seriedocumentosunat').val(cod_serie);
									$("#cmb_seriedocumentosunat").prop('disabled', true);
								}
								
								//return;
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								newHtml='';
								newHtml+='<select id="cmb_seriedocumentosunat" style="width:95%;height:25px" onChange="javascript:Numero_Documento(this.value)">';
								newHtml+='<option value="0">[SELECCIONAR]</option>';
								newHtml+='</select>';		
								$('#div_seriedocumento').empty().append(newHtml);	
								//return;
							}
						}
					});
				}
			}
			
			function Seleccionar_Tiponotadebito(cod_tipond)
			{
				//$('#div_tipodocumentoreferencia').empty().append('');
				var cmb_tipodocumentosunat=$.trim($('#cmb_tipodocumentosunat').val());
				if (cod_tipond=='03' && cmb_tipodocumentosunat=='08')
				{
					
					$('#txt_numerodocumentoreferencia').val('');

					$("#txt_numerodocumentoreferencia").prop('disabled', true);
					$('#div_tipodocumentoreferencia').empty().append('');
					newHtml='';
					newHtml+='<select id="cmb_tipodocumentoreferencia" style="width:95%;height:25px" >';
					newHtml+='<option value="-">-</option>';
					newHtml+='</select>';		
					$('#div_tipodocumentoreferencia').empty().append(newHtml);
					$("#cmb_tipodocumentoreferencia").prop('disabled', true);	
					Serie_DocumentoReferencia('01','');	
					
				}
				else
				{
				
					if ($('#cmb_tipodocumentoreferencia').val()=='-')
					{
						$("#txt_numerodocumentoreferencia").prop('disabled', false);
						$("#cmb_tipodocumentoreferencia").prop('disabled', false);

						$('#div_tipodocumentoreferencia').empty().append('');
						newHtml='';
						newHtml+='<select id="cmb_tipodocumentoreferencia" style="width:95%;height:25px" onChange="javascript:Serie_DocumentoReferencia(this.value,\'\')"  >';
						newHtml+='<option value="0">[SELECCIONAR]</option>';
						newHtml+='<option value="01">FACTURA</option>';
						newHtml+='<option value="03">BOLETA DE VENTA</option>';
						newHtml+='</select>';	
						
						$('#div_tipodocumentoreferencia').empty().append(newHtml);
						
						
						$('#div_seriedocumento').empty().append('');
						newHtml='';
						newHtml+='<select id="cmb_seriedocumentosunat" style="width:95%;height:25px" onChange="javascript:Numero_Documento(this.value)">';
						newHtml+='<option value="0">[SELECCIONAR]</option>';
						newHtml+='</select>';		
						$('#div_seriedocumento').empty().append(newHtml);
						
						$('#txt_numerodocumentosunat').val('');
					}
					
				}
			}
			
			
			function Listar_SeriesDocumentosReferencia(cod_tipodocumentoreferencia,cod_serie)
			{
				var cod_tipodocumento=$.trim($('#cmb_tipodocumentosunat').val());
							
				$.ajax({
					url:'<?php echo base_url()?>catalogos/Listar_SeriesDocumentos',
					type: 'post',
					dataType: 'json',
					data:
					{
						cod_tipodocumento:cod_tipodocumento
					},
					beforeSend:function()
					{

					},
					success:function(result)
					{
							
						if(result.status==1)
						{
							newHtml='';
							newHtml+='<select id="cmb_seriedocumentosunat" style="width:95%;height:25px" onChange="javascript:Numero_Documento(this.value)">';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							
							$.each(result.data,function(key,rs)
							{
								if (cod_tipodocumentoreferencia=='01')//FACTURA
								{
									if (rs.letra_inicio=='F')
									{
										newHtml+='<option value="'+rs.ser_doc+'">'+rs.ser_doc+'</option>';
									}
								}
								else //CASO BOLETAS
								{
									if (rs.letra_inicio=='B')
									{
										newHtml+='<option value="'+rs.ser_doc+'">'+rs.ser_doc+'</option>';
									}
								}
							});	
							newHtml+='</select>';		
							$('#div_seriedocumento').empty().append(newHtml);	
							
							if (cod_serie!='')
							{
								$('#cmb_seriedocumentosunat').val(cod_serie);
								$("#cmb_seriedocumentosunat").prop('disabled', true);
							}


						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}
						else
						{
							newHtml='';
							newHtml+='<select id="cmb_seriedocumentosunat" style="width:95%;height:25px" onChange="javascript:Numero_Documento(this.value)">';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							newHtml+='</select>';		
							$('#div_seriedocumento').empty().append(newHtml);	

						}
					}
				});
			}
			
			function Datos_ClienteBusqueda(cod_client)
			{
				var txt_numerodoccliente=$.trim($('#txt_numerodoccliente').val());
				
				//$.trim($('#txt_razonsocialcliente').val(''));
				$.trim($('#txt_correocliente').val(''));
				$.trim($('#txt_direccioncliente').val(''));

				if (txt_numerodoccliente=='')
				{
					$('#txt_correocliente').val('');
					$('#txt_direccioncliente').val('');					
					$('#txt_clientenombrecomercial').val('');
					$('#txt_clientecorreo').val('');
					$('#txt_clientepais').val('');
					$('#txt_clientepaiscodigo').val('');
					$('#txt_clientedepartamento').val('');
					$('#txt_clienteprovincia').val('');
					$('#txt_clientedistrito').val('');
					$('#txt_clientedireccion').val('');					
					$('#txt_clienteubanizacion').val('');
					$('#txt_clienteubigeo').val('');
					//return;
				}
				else
				{
					$.ajax({
						url:'<?php echo base_url()?>clientes/Datos_ClienteId',
						type: 'post',
						dataType: 'json',
						data:
						{
							cod_client:cod_client
						},
						beforeSend:function()
						{
	
						},
						success:function(result)
						{
								
							if(result.status==1)
							{
								$.each(result.data,function(key,rs)
								{
									//$.trim($('#txt_razonsocialcliente').val(rs.raz_social));
									
									$.trim($('#txt_correocliente').val(rs.email_cliente));
									$.trim($('#txt_direccioncliente').val(rs.direc_cliente));
									$.trim($('#txt_clientenombrecomercial').val(rs.nom_comercial));
									$.trim($('#txt_clientecorreo').val(rs.email_cliente));
									$.trim($('#txt_clientepais').val(rs.nomb_pais));
									$.trim($('#txt_clientepaiscodigo').val(rs.cod_pais));
									$.trim($('#txt_clientedepartamento').val(rs.nomb_depa));
									$.trim($('#txt_clienteprovincia').val(rs.nomb_prov));
									$.trim($('#txt_clientedistrito').val(rs.nomb_dist));
									$.trim($('#txt_clientedireccion').val(rs.direc_cliente));
									
									$.trim($('#txt_clienteubanizacion').val(rs.urbaniz_cliente));
									$.trim($('#txt_clienteubigeo').val(rs.cod_ubigeo));
									
									
								});	
								//return;
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								//$('#txt_razonsocialcliente').val('');
								$('#txt_correocliente').val('');
								$('#txt_direccioncliente').val('');
								
								$('#txt_clientenombrecomercial').val('');
								$('#txt_clientecorreo').val('');
								$('#txt_clientepais').val('');
								$('#txt_clientepaiscodigo').val('');
								$('#txt_clientedepartamento').val('');
								$('#txt_clienteprovincia').val('');
								$('#txt_clientedistrito').val('');
								$('#txt_clientedireccion').val('');
								
								$('#txt_clienteubanizacion').val('');
								$('#txt_clienteubigeo').val('');
								
							}
						}
					});
				}
			}
			
			
			function Datos_ClienteBusquedaModificar(tipodocumentocliente,txt_numerodoccliente,txt_razonsocialcliente)
			{
				//var txt_numerodoccliente=$.trim($('#txt_numerodoccliente').val());
				
				//$.trim($('#txt_razonsocialcliente').val(''));
				$.trim($('#txt_correocliente').val(''));
				$.trim($('#txt_direccioncliente').val(''));

				if (txt_numerodoccliente=='')
				{
					$('#txt_correocliente').val('');
					$('#txt_direccioncliente').val('');					
					$('#txt_clientenombrecomercial').val('');
					$('#txt_clientecorreo').val('');
					$('#txt_clientepais').val('');
					$('#txt_clientepaiscodigo').val('');
					$('#txt_clientedepartamento').val('');
					$('#txt_clienteprovincia').val('');
					$('#txt_clientedistrito').val('');
					$('#txt_clientedireccion').val('');					
					$('#txt_clienteubanizacion').val('');
					$('#txt_clienteubigeo').val('');
					//return;
				}
				else
				{
					$.ajax({
						url:'<?php echo base_url()?>clientes/Datos_ClienteIdModificar',
						type: 'post',
						dataType: 'json',
						data:
						{
							tipodocumentocliente:tipodocumentocliente,
							txt_numerodoccliente:txt_numerodoccliente,
							txt_razonsocialcliente:txt_razonsocialcliente
						},
						beforeSend:function()
						{
	
						},
						success:function(result)
						{
								
							if(result.status==1)
							{
								$.each(result.data,function(key,rs)
								{
									//$.trim($('#txt_razonsocialcliente').val(rs.raz_social));
									
									$.trim($('#txt_correocliente').val(rs.email_cliente));
									$.trim($('#txt_direccioncliente').val(rs.direc_cliente));
									$.trim($('#txt_clientenombrecomercial').val(rs.nom_comercial));
									$.trim($('#txt_clientecorreo').val(rs.email_cliente));
									$.trim($('#txt_clientepais').val(rs.nomb_pais));
									$.trim($('#txt_clientepaiscodigo').val(rs.cod_pais));
									$.trim($('#txt_clientedepartamento').val(rs.nomb_depa));
									$.trim($('#txt_clienteprovincia').val(rs.nomb_prov));
									$.trim($('#txt_clientedistrito').val(rs.nomb_dist));
									$.trim($('#txt_clientedireccion').val(rs.direc_cliente));
									
									$.trim($('#txt_clienteubanizacion').val(rs.urbaniz_cliente));
									$.trim($('#txt_clienteubigeo').val(rs.cod_ubigeo));
									
									
								});	
								//return;
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								//$('#txt_razonsocialcliente').val('');
								$('#txt_correocliente').val('');
								$('#txt_direccioncliente').val('');
								
								$('#txt_clientenombrecomercial').val('');
								$('#txt_clientecorreo').val('');
								$('#txt_clientepais').val('');
								$('#txt_clientepaiscodigo').val('');
								$('#txt_clientedepartamento').val('');
								$('#txt_clienteprovincia').val('');
								$('#txt_clientedistrito').val('');
								$('#txt_clientedireccion').val('');
								
								$('#txt_clienteubanizacion').val('');
								$('#txt_clienteubigeo').val('');
								
								
								//return;
							}
						}
					});
				}
			}
			
			function Datos_Emisor()
			{

				$.ajax({
					url:'<?php echo base_url()?>empresa/Listar_EmpresaId',
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
							$.each(result.data,function(key,rs)
							{
								$.trim($('#txt_emisornombrecomercial').val(rs.nom_comercial));
								$.trim($('#txt_emisorcorreo').val(rs.correo_usuario));
								$.trim($('#txt_emisorpais').val(rs.nomb_pais));
								$.trim($('#txt_emisorpaiscodigo').val(rs.cod_pais));
								$.trim($('#txt_emisorubigeo').val(rs.cod_ubigeo));
								$.trim($('#txt_emisordepartamento').val(rs.nomb_depa));
								$.trim($('#txt_emisorprovincia').val(rs.nomb_prov));
								$.trim($('#txt_emisordistrito').val(rs.nomb_dist));
								$.trim($('#txt_emisorrubanizacion').val(rs.urbaniz_empresa));
								$.trim($('#txt_emisordireccion').val(rs.direcc_empresa));
		
								
							});	
							//return;
						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}
						else
						{
							$('#txt_emisornombrecomercial').val('');
							$('#txt_emisorcorreo').val('');
							$('#txt_emisorpais').val('');
							$('#txt_emisorpaiscodigo').val('');
							$('#txt_emisorubigeo').val('');
							$('#txt_emisordepartamento').val('');
							$('#txt_emisorprovincia').val('');
							$('#txt_emisordistrito').val('');
							$('#txt_emisorrubanizacion').val('');
							$('#txt_emisordireccion').val('');
							
							//return;
						}
					}
				});

			}
			
			function Tipo_Afectacion()
			{
				$('#div_tipoafectacion').empty().append('');	
				var cmb_tipodocumentosunat=$.trim($('#cmb_tipodocumentosunat').val());
			
				if ($("#cbox_exportaciondocumento").is(":checked"))//EXPORTACION
				{
					newHtml='';
					newHtml+='<select id="cmb_tipoafectacion" disabled="disabled" style="width:95%;height:25px" onChange="javascript:Calcular_Montos()">';
					newHtml+='<option value="40">EXPORTACIÓN</option>';
					newHtml+='</select>';		
					$('#div_tipoafectacion').empty().append(newHtml);	
				}
				else if ($("#cbox_opergratisdocumento").is(":checked"))//OPERACIONES GRATUITAS
				{
					$.ajax({
						url:'<?php echo base_url()?>catalogos/Tipo_Afectacion',
						type: 'post',
						dataType: 'json',
						data:
						{
							cod_tipoconsulta:2
						},
						beforeSend:function()
						{
	
						},
						success:function(result)
						{
								
							if(result.status==1)
							{
								newHtml='';
								newHtml+='<select id="cmb_tipoafectacion" style="width:95%;height:25px" onChange="javascript:Calcular_Montos()">';
								newHtml+='<option value="">[SELECCIONAR]</option>';
								$.each(result.data,function(key,rs)
								{
									newHtml+='<option value="'+rs.co_item_tabla+'">'+rs.no_largo+'</option>';
								});	
								newHtml+='</select>';		
								$('#div_tipoafectacion').empty().append(newHtml);	
								return;
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								newHtml='';
								newHtml+='<select id="cmb_tipoafectacion" style="width:95%;height:25px" onChange="javascript:Calcular_Montos()">';
								newHtml+='<option value="">[SELECCIONAR]</option>';
								newHtml+='</select>';		
								$('#div_tipoafectacion').empty().append(newHtml);	
								return;
							}
						}
					});	
				}
				
				else
				{
					$.ajax({
						url:'<?php echo base_url()?>catalogos/Tipo_Afectacion',
						type: 'post',
						dataType: 'json',
						data:
						{
							cod_tipoconsulta:1
						},
						beforeSend:function()
						{
	
						},
						success:function(result)
						{
								
							if(result.status==1)
							{
								newHtml='';
								newHtml+='<select id="cmb_tipoafectacion" style="width:95%;height:25px" onChange="javascript:Calcular_Montos()">';
								newHtml+='<option value="">[SELECCIONAR]</option>';
								$.each(result.data,function(key,rs)
								{
									newHtml+='<option value="'+rs.co_item_tabla+'">'+rs.no_largo+'</option>';
								});	
								newHtml+='</select>';		
								$('#div_tipoafectacion').empty().append(newHtml);	
								return;
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								newHtml='';
								newHtml+='<select id="cmb_tipoafectacion" style="width:95%;height:25px" onChange="javascript:Calcular_Montos()">';
								newHtml+='<option value="">[SELECCIONAR]</option>';
								newHtml+='</select>';		
								$('#div_tipoafectacion').empty().append(newHtml);	
								return;
							}
						}
					});
				}				
			}
			
			
			function Seleccionar_CheckExportacion()
			{
				$('#txt_razonsocialcliente').val('');
				$('#txt_numerodoccliente').val('');
				Datos_ClienteBusqueda('');
				
				if ($("#cbox_exportaciondocumento").is(":checked"))
				{
				
					if ($("#cbox_exportaciondocumento").is(":checked"))
					{
						
						if ($("#cbox_opergratisdocumento").is(":checked"))
						{
							$("#cbox_opergratisdocumento").prop('checked', false);	
						}
					}
					
				
					var cmb_tipodocumentosunat=$.trim($('#cmb_tipodocumentosunat').val());
					if (cmb_tipodocumentosunat=='0')
					{
						alert("Debe seleccionar un tipo de documento");
						$("#cbox_exportaciondocumento").prop('checked', false);	
						return;
					}
					else if (cmb_tipodocumentosunat=='01' || cmb_tipodocumentosunat=='03')
					{
						if (cmb_tipodocumentosunat=='03')
						{
							alert("Debe seleccionar otro tipo de documento");
							$("#cbox_exportaciondocumento").prop('checked', false);	
							return;
						}
						else
						{
							$('#div_tipodocumentocliente').empty().append('');
							newHtml='';
							newHtml+='<select id="cmb_tipodocumentocliente" style="width:95%;height:25px" disabled="disabled">';			
							newHtml+='<option value="0">Doc.Trib.No.Dom.Sin.Ruc</option>';				
							newHtml+='</select>';	
							
							$('#div_catalogonombcliente').empty().append('Nombre del Cliente:');
							$('#div_titulodocumento').empty().append('Doc. Trib. No. Dom. Sin. RUC:');
							$("#cmb_tipodocumentocliente").prop('disabled', true);	
							//$("#txt_razonsocialcliente").prop('disabled', false);
							//$("#txt_direccioncliente").prop('disabled', false);		
							//$('#div_verdatoscliente').empty().append('');	
							
							$('#div_tipodocumentocliente').empty().append(newHtml);	
						}
					}
					else
					{
						
						var cmb_tipodocumentoreferencia=$.trim($('#cmb_tipodocumentoreferencia').val());						
						if (cmb_tipodocumentoreferencia=='0')
						{
							alert("Debe seleccionar un tipo de documento de referencia");
							$("#cbox_exportaciondocumento").prop('checked', false);	
							return;
						}
						else if (cmb_tipodocumentoreferencia=='03')
						{
							alert("Debe seleccionar otro tipo de documento de referencia");
							$("#cbox_exportaciondocumento").prop('checked', false);	
							return;
						}
						else
						{
							$('#div_titulodocumento').empty().append('Doc. Trib. No. Dom. Sin. RUC:');						
							$("#cmb_tipodocumentocliente").prop('disabled', true);	
							//$("#txt_razonsocialcliente").prop('disabled', false);
							//$("#txt_direccioncliente").prop('disabled', false);		
							//$('#div_verdatoscliente').empty().append('');	
							
							if ($("#cbox_opergratisdocumento").is(":checked"))
							{
								$("#cbox_opergratisdocumento").prop('checked', false);	
							}
							$('#div_tipodocumentocliente').empty().append('');
							newHtml='';
							newHtml+='<select id="cmb_tipodocumentocliente" style="width:95%;height:25px" disabled="disabled">';			
							newHtml+='<option value="0">Doc.Trib.No.Dom.Sin.Ruc</option>';				
							newHtml+='</select>';	
							$('#div_tipodocumentocliente').empty().append(newHtml);	
							$('#div_catalogonombcliente').empty().append('Nombre del Cliente:');
							//$('#div_titulodocumento').empty().append('Documento Cliente:');
							//$('#div_titulodocumento').empty().append('Doc. Trib. No. Dom. Sin. RUC:');
							
						}
					}
					Tipo_Afectacion();
					
					//OcultarFilaTabla('row_cargos',0);
					OcultarOtrosCargos_Checked(0);
				}
				else
				{
					//OcultarFilaTabla('row_cargos',1);
					OcultarOtrosCargos_Checked(1);
					$('#div_titulodocumento').empty().append('Documento Cliente:');
					$("#cmb_tipodocumentocliente").prop('disabled', false);
					//$("#txt_razonsocialcliente").prop('disabled', true);	
					//$("#txt_direccioncliente").prop('disabled', true);
					//$('#div_verdatoscliente').empty().append('<a href="javascript:ver_datoscliente()"><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nc_buscar.png" title="Ver Detalle" width="15"  height="15" border="0" ></a>');	
					

					newHtml='';
					newHtml+='<select id="cmb_tipodocumentocliente" style="width:95%;height:25px" >';			
					newHtml+='<option value="6">RUC</option>';				
					newHtml+='</select>';	
					$('#div_tipodocumentocliente').empty().append(newHtml);
					
					$('#div_catalogonombcliente').empty().append('Razón Social Cliente:');		
					$('#div_titulodocumento').empty().append('RUC Cliente:');
					
					Tipo_Afectacion();	
				}

				
			}
			
			function Seleccionar_Check_opegravada()
			{
				var txt_valorotroscargos=$.trim($('#txt_valorotroscargos').val());
				var txt_operaciongravadas=($('#txt_operaciongravadas').val()).replace(',', '');
				var txt_otroscargos=0;
				var txt_importetotal=0;
				var txt_operacioninafectos=($('#txt_operacioninafectos').val()).replace(',', '');
				var txt_operacionexportacion=($('#txt_operacionexportacion').val()).replace(',', '');
				var txt_operacionexoneradas=($('#txt_operacionexoneradas').val()).replace(',', '');
				var txt_operaciongratuitas=($('#txt_operaciongratuitas').val()).replace(',', '');
				//var txt_descuentototal=$('#txt_descuentototal').val();
				var txt_isctotal=($('#txt_isctotal').val()).replace(',', '');
				var txt_igvtotal=($('#txt_igvtotal').val()).replace(',', '');

				if (txt_valorotroscargos=="")
				{ txt_valorotroscargos=0;
				}
				
				if ($("#chekbox_opegravada").is(":checked"))
				{
					if (txt_operaciongravadas>0)
					{
						txt_otroscargos=parseFloat(txt_operaciongravadas)*(parseFloat(txt_valorotroscargos)/100);
					}else{
						alert('Seleccionar otro cargo de operación!.');
						}						
				}else{
					txt_otroscargos=0;
				}
				
				txt_importetotal=parseFloat(txt_operaciongravadas) + parseFloat(txt_operacioninafectos) + 
					parseFloat(txt_operacioninafectos) + parseFloat(txt_operacionexportacion) + 
					parseFloat(txt_operacionexoneradas) + parseFloat(txt_operaciongratuitas) + 
					parseFloat(txt_isctotal) +  parseFloat(txt_otroscargos) + parseFloat(txt_igvtotal);
					
				$('#txt_otroscargos').val(txt_otroscargos.toFixed(2));
				$('#txt_importetotal').val(txt_importetotal.toFixed(2));
				
				$("#chekbox_opeinafecta").prop('checked', false);	
				$("#chekbox_opeexonerada").prop('checked', false);
			}
			function Seleccionar_Check_opeinafecta()
			{
				var txt_valorotroscargos=$.trim($('#txt_valorotroscargos').val());
				var txt_operacioninafectos=($('#txt_operacioninafectos').val()).replace(',', '');
				var txt_otroscargos=0;
				var txt_importetotal=0;
				var txt_operaciongravadas=($('#txt_operaciongravadas').val()).replace(',', '');
				//var txt_operacioninafectos=$('#txt_operacioninafectos').val();
				var txt_operacionexportacion=($('#txt_operacionexportacion').val()).replace(',', '');
				var txt_operacionexoneradas=($('#txt_operacionexoneradas').val()).replace(',', '');
				var txt_operaciongratuitas=($('#txt_operaciongratuitas').val()).replace(',', '');
				//var txt_descuentototal=$('#txt_descuentototal').val();
				var txt_isctotal=($('#txt_isctotal').val()).replace(',', '');
				var txt_igvtotal=($('#txt_igvtotal').val()).replace(',', '');
				if (txt_valorotroscargos=="")
				{ txt_valorotroscargos=0;
				}
				
				if ($("#chekbox_opeinafecta").is(":checked"))
				{
					if (txt_operacioninafectos>0)
					{
						txt_otroscargos=parseFloat(txt_operacioninafectos)*(parseFloat(txt_valorotroscargos)/100);
					}else{
						alert('Seleccionar otro cargo de operación!.');
						}	
				}else{
					txt_otroscargos=0;
				}
				
				txt_importetotal=parseFloat(txt_operaciongravadas) + parseFloat(txt_operacioninafectos) + 
					parseFloat(txt_operacioninafectos) + parseFloat(txt_operacionexportacion) + 
					parseFloat(txt_operacionexoneradas) + parseFloat(txt_operaciongratuitas) + 
					parseFloat(txt_isctotal) +  parseFloat(txt_otroscargos) + 
					parseFloat(txt_igvtotal);//parseFloat(txt_descuentototal) + 
					
				$('#txt_otroscargos').val(txt_otroscargos.toFixed(2));
				$('#txt_importetotal').val(txt_importetotal.toFixed(2));
				
				$("#chekbox_opegravada").prop('checked', false);	
				$("#chekbox_opeexonerada").prop('checked', false);
			}
			function Seleccionar_Check_opeexonerada()
			{
				var txt_valorotroscargos=$.trim($('#txt_valorotroscargos').val());
				var txt_operacionexoneradas=($('#txt_operacionexoneradas').val()).replace(',', '');
				var txt_otroscargos=0;
				var txt_operaciongravadas=($('#txt_operaciongravadas').val()).replace(',', '');
				var txt_operacioninafectos=($('#txt_operacioninafectos').val()).replace(',', '');
				var txt_operacionexportacion=($('#txt_operacionexportacion').val()).replace(',', '');
				//var txt_operacionexoneradas=$('#txt_operacionexoneradas').val();
				var txt_operaciongratuitas=($('#txt_operaciongratuitas').val()).replace(',', '');
				//var txt_descuentototal=$('#txt_descuentototal').val();
				var txt_isctotal=($('#txt_isctotal').val()).replace(',', '');
				var txt_igvtotal=($('#txt_igvtotal').val()).replace(',', '');
				if (txt_valorotroscargos=="")
				{ txt_valorotroscargos=0;
				}
				
				if ($("#chekbox_opeexonerada").is(":checked"))
				{
					if (txt_operacionexoneradas>0)
					{
						txt_otroscargos=parseFloat(txt_operacionexoneradas)*(parseFloat(txt_valorotroscargos)/100);
					}else{
						alert('Seleccionar otro cargo de operación!.');
						}	
				}else{
					txt_otroscargos=0;
				}
				
				txt_importetotal=parseFloat(txt_operaciongravadas) + parseFloat(txt_operacioninafectos) + 
					parseFloat(txt_operacioninafectos) + parseFloat(txt_operacionexportacion) + 
					parseFloat(txt_operacionexoneradas) + parseFloat(txt_operaciongratuitas) + 
					parseFloat(txt_isctotal) +  parseFloat(txt_otroscargos) + //parseFloat(txt_descuentototal) + 
					parseFloat(txt_igvtotal);
					
				$('#txt_otroscargos').val(txt_otroscargos.toFixed(2));
				$('#txt_importetotal').val(txt_importetotal.toFixed(2));
				
				$("#chekbox_opegravada").prop('checked', false);	
				$("#chekbox_opeinafecta").prop('checked', false);
			}
			
			function Seleccionar_CheckOperacionGratuitas()
			{
				if ($("#cbox_opergratisdocumento").is(":checked"))
				{
					if ($("#cbox_exportaciondocumento").is(":checked"))
					{
						//OcultarFilaTabla('row_cargos',0);
						$("#cbox_exportaciondocumento").prop('checked', false);	
					}
					Tipo_Afectacion();
					//alert('IF !.');
					//OcultarFilaTabla('row_cargos',0);
				}
				Seleccionar_CheckExportacion();
				if ($("#cbox_opergratisdocumento").is(":checked"))
				{
					//OcultarFilaTabla('row_cargos',0);
					OcultarOtrosCargos_Checked(0);
				}else
				{
					//OcultarFilaTabla('row_cargos',1);
					OcultarOtrosCargos_Checked(1);
				}
				
			}
			
			function Seleccionar_TipoDocumento(codigo_tipodoc)
			{
				//if (codigo_tipodoc=='')	{
				//	return;
				//}
			
				if ($("#cbox_exportaciondocumento").is(":checked"))
				{
					if ($("#cbox_opergratisdocumento").is(":checked"))
					{
						$("#cbox_opergratisdocumento").prop('checked', false);	
					}
				}
				var cmb_tipodocumentosunat=$.trim($('#cmb_tipodocumentosunat').val());
			
				if ($("#cbox_exportaciondocumento").is(":checked"))
				{
					$('#div_titulodocumento').empty().append('Doc. Trib. No. Dom. Sin. RUC:');						
					$("#cmb_tipodocumentocliente").prop('disabled', true);	
					//$("#txt_razonsocialcliente").prop('disabled', false);
					//$("#txt_direccioncliente").prop('disabled', false);		
					//$('#div_verdatoscliente').empty().append('');	
					
					Serie_Documento(cmb_tipodocumentosunat,'','','','');	
					Tipo_Afectacion();						 
				}
				else
				{					
					$('#div_titulodocumento').empty().append('Documento Cliente:');
					$("#cmb_tipodocumentocliente").prop('disabled', false);
					//$("#txt_razonsocialcliente").prop('disabled', true);	
					//$("#txt_direccioncliente").prop('disabled', true);
					//$('#div_verdatoscliente').empty().append('<a href="javascript:ver_datoscliente()"><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nc_buscar.png" title="Ver Detalle" width="15"  height="15" border="0" ></a>');	
					
					Serie_Documento(cmb_tipodocumentosunat,'','','','');
					Tipo_Afectacion();		
				}
			}
			
			function OcultarFilaTabla(id,opcion) 
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
			
			function OcultarOtrosCargos()
			{
				var txt_config_valorprecio=$.trim($('#txt_config_valorprecio').val());
				if (txt_config_valorprecio==0)
				{
					OcultarFilaTabla('row_cargos',1);
				}else
				{
					OcultarFilaTabla('row_cargos',0);
				}
			}
			
			function OcultarOtrosCargos_Checked(valor)
			{
				var txt_config_valorprecio=$.trim($('#txt_config_valorprecio').val());
				if (txt_config_valorprecio==1)
				{
					OcultarFilaTabla('row_cargos',0);
				}else
				{
					OcultarFilaTabla('row_cargos',valor);
				}
			}
			
			function Listar_DatosDocumentoModificar()
			{
				var txt_documentomodificar=$.trim($('#txt_documentomodificar').val());
				var txt_rucempresa=$.trim($('#txt_RucEmpresa').val());
				
				
				if (txt_documentomodificar!='')
				{
					$.ajax({
						url:'<?php echo base_url()?>comprobante/Listar_DatosDocumentoModificar',
						type: 'post',
						dataType: 'json',
						data:
						{	
							txt_rucempresa:txt_rucempresa,
							txt_documentomodificar:txt_documentomodificar
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
								
							if(result.status==1)
							{

								
								$("#cmb_tipodocumentosunat").prop('disabled', true);
								
								$('#cmb_tipodocumentosunat').val(result.data['tipodocumento']);	
								
								//alert(result.data['tipodocumentoreferenciaprincip']);
								//alert(result.data['codigoserienumeroafectado']);
								//alert(result.data['tipodocumentoadquiriente']);
								
								var tipodocumentoreferenciaprincip=result.data['tipodocumentoreferenciaprincip']
								if (tipodocumentoreferenciaprincip=='' &&  result.data['tipodocumento']=='08')
								{
									tipodocumentoreferenciaprincip='01';
								}
						
								Serie_Documento(result.data['tipodocumento'],result.data['seriedocumento'],tipodocumentoreferenciaprincip,result.data['codigoserienumeroafectado'],result.data['tipodocumentoadquiriente']);
								//$('#cmb_seriedocumentosunat').val(result.data['seriedocumento']);	
								$('#txt_numerodocumentosunat').val(result.data['numerodocumento']);
								
								if (result.data['tipodocumentoreferenciaprincip']!='')
								{
									$('#txt_numerodocumentoreferencia').val(result.data['numerodocumentoreferenciaprinc']);
									$('#txt_motivodenotacredito').val(result.data['motivodocumento']);
								}
								
								$('#txt_numerodoccliente').val(result.data['numerodocumentoadquiriente']);
								$('#txt_razonsocialcliente').val(result.data['razonsocialadquiriente']);
								$('#txt_correocliente').val(result.data['correoadquiriente']);
								$('#txt_direccioncliente').val('');
								$('#cmb_monedadocumento').val(result.data['tipomoneda']);
								$('#txt_FechaEmision').val(result.data['fechaemision']);
								
								
								//CASO DETRACCION
								if (result.data['totaldetraccion']!='')
								{
									//$('#totaldetraccion').val((result.data['totaldetraccion']);
									$('#txt_porcentajedetraccion').val(result.data['porcentajedetraccion']);
									$('#txt_montodetraccion').val(result.data['totaldetraccion']);
									$('#txt_montodetraccionreferencial').val(result.data['valorreferencialdetraccion']);
									$('#txt_descripciondetraccion').val(result.data['descripciondetraccion']);
									$('#txt_leyendadetraccion').val(result.data['textoleyenda_2']);
									
								}
								
								
								//CASO PERCEPCION
								if (result.data['porcentajepercepcion']!='')
								{

									$('#txt_porcentajepercepcion').val(result.data['porcentajepercepcion']);
									$('#txt_baseimponiblepercepcion').val(result.data['baseimponiblepercepcion']);
									$('#txt_totalpercepcion').val(result.data['totalpercepcion']);
									$('#txt_totalventapercepcion').val(result.data['totalventaconpercepcion']);
								}
								if (result.data['descuentosglobales']!='')
								{
									$('#txt_descuentoglobal').val(result.data['descuentosglobales']);
								}
								else
								{
									$('#txt_descuentoglobal').val('');
								}
								
								$('#txt_tipoderegistrodocumento').val(result.data['cod_tipregist'])
								
								//SE DEBE TRAER EL CODIGO DEL CLIENTE, SINO CAGAO
								//Datos_ClienteBusqueda('');
								Datos_ClienteBusquedaModificar(result.data['tipodocumentoadquiriente'],result.data['numerodocumentoadquiriente'],result.data['razonsocialadquiriente']);
								
								
								ncsistema.Listar_ProductosDocumento();
								
								if (result.data['cod_tipregist']==2)
								{
									document.getElementsByName('cbox_exportaciondocumento')[0].checked=true;
								}
								else if (result.data['cod_tipregist']==3)
								{
									document.getElementsByName('cbox_opergratisdocumento')[0].checked=true;
								}

								if (result.data['codigoserienumeroafectado']=='03' &&  result.data['tipodocumento']=='08')
								{
									$('#div_tipodocumentoreferencia').empty().append('');
									newHtml='';
									newHtml+='<select id="cmb_tipodocumentoreferencia" style="width:95%;height:25px" >';
									newHtml+='<option value="-">-</option>';
									newHtml+='</select>';		
									$('#div_tipodocumentoreferencia').empty().append(newHtml);
									$("#cmb_tipodocumentoreferencia").prop('disabled', true);
									
									$("#txt_numerodocumentoreferencia").prop('disabled', true);
									//$("#cmb_tiponotadecredito").prop('disabled', true);
									
								}

								$('#txt_FechaEmision') .datepicker('option', 'minDate', result.data['primera_fecha']) .datepicker('refresh'); 
								$('#txt_FechaEmision') .datepicker('option', 'maxDate', result.data['ultima_fecha']) .datepicker('refresh'); 
								
								$('#txt_modificarregistro').val(1);
								
								Tipo_Afectacion();	

								return;
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								/*
								$('#txt_emisornombrecomercial').val(rs.nom_comercial);
								$('#txt_emisorcorreo').val(rs.correo_usuario);
								$('#txt_emisorpais').val(rs.nomb_pais);
								$('#txt_emisorpaiscodigo').val(rs.cod_pais);
								$('#txt_emisorubigeo').val(rs.cod_ubigeo);
								$('#txt_emisordepartamento').val(rs.nomb_depa);
								$('#txt_emisorprovincia').val(rs.nomb_prov);
								$('#txt_emisordistrito').val(rs.nomb_dist);
								$('#txt_emisorrubanizacion').val(rs.urbaniz_empresa);
								$('#txt_emisordireccion').val(rs.direcc_empresa);
								*/								
								return;
							}
						}
					});
				}

			}
			
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
							regexp = /[0-9]{2}$/
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
		
		<style>
			body { font-size: 62.5%; }
			/*label, input { display:block; }*/
			input.text { margin-bottom:2px; width:60%; padding: .4em; }
			fieldset { padding:0; border:0; margin-top:25px; }
			h1 { font-size: 1.2em; margin: .6em 0; }
			div#users-contain { width: 350px; margin: 5px 0; }
			div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
			div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
			.ui-dialog .ui-state-error { padding: .3em; }
			.validateTips { border: 1px solid transparent; padding: 0.3em; }
		</style>
		
    </head>   
    <body>
		<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">REGISTRO DE COMPROBANTES</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">
				<div id="div_datosempresa" style="width:100%; float:left; margin-top:10px; border: 1px solid #a6c9e2; border-radius:5px;">
					<input type="hidden" id="txt_documentomodificar" value="<?php echo $documentomodificar;?>"  />
					<input type="hidden" id="txt_tipoderegistrodocumento" value="0"  />					
					<input type="hidden" id="txt_valorigv" value="<?php echo $valor_igv;?>"  />
					<input type="hidden" id="txt_valorotroscargos" value="<?php echo $valor_otroscargos;?>"  />
					<input type="hidden" id="txt_modificarregistro" value="0"  />
					<input type="hidden" id="txt_config_valorprecio" value="<?php echo $Config_ValorPrecio;?>"  />
					
					<table border="0" width="80%" style="border-collapse:separate; border-spacing:1px 1px;" cellpadding="3" class="tablaFormulario">
						<tr><td><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right;width:12%" ><label class="columna">Ruc Proveedor:</label></td>
							<td style="text-align:left;width:15%"><input style="width:90%" type="text" id="txt_RucEmpresa" value="<?php echo trim(utf8_decode($Ruc_Empresa));?>"  disabled="disabled" /></td>
							<td style="text-align:right;width:17%"><label class="columna">Razón Social Proveedor:</label></td>
							<td style="text-align:left;width:46%; vertical-align:top" colspan="2" >
								<input style="width:98%" type="text" id="txt_RazonSocialEmpresa" value="<?php echo trim(utf8_decode($Razon_Social));?>"
									disabled="disabled" />
							</td>
							<!--<td style="text-align:left;width:2%; vertical-align:middle" > 
								<a href="javascript:ver_datosemisor()">
									<img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nc_buscar.png" title="Ver Detalle" width="15"  border="0" >	
								</a>
							</td>-->
							<td style="text-align:left;width:10%; vertical-align:middle">
								<a href="javascript:ver_datosemisor()">
									<img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nc_buscar.png" title="Ver Detalle" width="15"  border="0" >	
								</a>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Tipo Documento:</label></td>
							<td style="text-align:left">
								<select id="cmb_tipodocumentosunat" style="width:95%;height:25px" onChange="javascript:Serie_Documento(this.value,'','','','')" >
									<option value="0">[SELECCIONAR]</option>
									<?php foreach ( $Listar_TipodeDocumento as $v):	?>
										<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(utf8_decode($v['no_corto']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
							<td style="text-align:right"><label class="columna">Serie:</label></td>
							<td style="text-align:left" colspan="3">
								<table width="100%" border="0">
									<tr>
										<td style="width:20%">
											<div id="div_seriedocumento">
												<select id="cmb_seriedocumentosunat" style="height:25px" >
													<option value="0">[SELECCIONAR]</option>
												</select>
											</div>
										</td>
										<td style="text-align:right;width:7%"><label class="columna">Número:</label></td>
										<td style="width:14%">
											<input style="width:100%;text-align:center" type="text" id="txt_numerodocumentosunat" disabled="disabled" value="" >
										</td>
										<td style="text-align:right;width:20%"><label class="columna">Fec. Emisión:</label></td>
										<td style="text-align:left;width:39%">
											<input id="txt_FechaEmision" disabled="disabled" type="text" value="" style="width:70px"/>
										</td>
										
									</tr>
								</table>
							</td>
						</tr>
						<tr id="row3">
							<td style="text-align:right"><label class="columna">Tipo Doc.Refer.:</label></td>
							<td style="text-align:left">
									<div id="div_tipodocumentoreferencia">
										<select id="cmb_tipodocumentoreferencia" style="width:95%;height:25px" >
											<option value="">[SELECCIONAR]</option>
										</select>
									</div>
							</td>
							<td style="text-align:right"><label class="columna"><div id="div_etiquetedocumentoreferencia">Factura Referenciada:</div></label></td>
							<td style="text-align:left" colspan="2">
								<table width="100%" border="0">
									<tr>
										<td style="width:25%">
											<input id="txt_numerodocumentoreferencia" style="text-transform:uppercase" type="text" value="" maxlength="13" style="width:95%;" />
										</td>
										<td style="text-align:right;width:35%" >
											<label class="columna"><div id="div_etiquetetipodenota">Tipo Nota de Crédito:</div></label></td>
										<td style="width:40%;text-align:left"  >
											<div id="div_tiponotadecredito">
												<select id="cmb_tiponotadecredito" style="width:100%;height:25px" 
													onChange="javascript:Seleccionar_Tiponotadebito(this.value)">
													<option value="">[SELECCIONAR]</option>
												</select>											
											</div>
										</td>
										
									</tr>
								</table>
							</td>
						</tr>
						<tr id="row4">
							<td style="text-align:right"><label class="columna">Motivo del Doc.:</label></td>
							<td style="text-align:left" colspan="3">
									<input id="txt_motivodenotacredito" type="text" value="" style="width:80%" >
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Tipo Doc.Cliente:</label></td>
							<td style="text-align:left">
								<div id="div_tipodocumentocliente">
									<select id="cmb_tipodocumentocliente" style="width:95%;height:25px" >
										<option value="">[SELECCIONAR]</option>
										<!--<option value="6">RUC</option>
										<option value="1">DNI</option>-->
									</select>
								</div>
							</td>
							<td style="text-align:right"><label class="columna"><div id="div_catalogonombcliente">Razón Social Cliente:</div></label></td>
							<td style="text-align:left" colspan="2">
								<table width="100%" border="0">
									<tr>
										<td style="width:40%">
											<input id="txt_razonsocialcliente" type="text" value="" style="width:90%;" >
										</td>
										<td align="left" style="text-align:left;width:5%; vertical-align:middle" >
											<div id="div_verdatoscliente">
												<a href="javascript:ver_datoscliente()">
													<img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nc_buscar.png" title="Ver Detalle" width="15"  height="15" border="0" >	
												</a>
											</div>
										</td>
										<td style="text-align:right;width:30%">
											<label class="columna"><div id="div_titulodocumento">Documento Cliente:</div></label></td>
										<td style="width:25%">
											<input id="txt_numerodoccliente" type="text" value="" disabled="disabled" width="95%" >
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Correo Cliente:</label></td>
							<td style="text-align:left">
								<input id="txt_correocliente" type="text" value="" style="width:90%;">
							</td>
							<td style="text-align:right"><label class="columna">Dirección Cliente:</label></td>
							<td style="text-align:left" colspan="2">
								<input id="txt_direccioncliente" type="text" value="" disabled="disabled" style="width:98%;">
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Moneda:</label></td>
							<td style="text-align:left">
								<select id="cmb_monedadocumento" style="width:95%;height:25px" >
									<option value="PEN">SOLES</option>
									<option value="USD">DOLAR</option>
								</select>
							</td>
							<td style="text-align:right"></td><!--<label class="columna">Tipo de Operación:</label>-->
							<td style="text-align:left" colspan="2">
								<table width="100%" border="0">
									<tr>
										<td style="text-align:right;width:40%;vertical-align:middle">
											<label class="columna"><div id="div_etiqueteexportacion">Exportación:</div></label>
										</td>
										<td style="width:10%">
											<div id="div_checkexportacion">
											<input id="cbox_exportaciondocumento" type="checkbox" value="" name="cbox_exportaciondocumento" onClick="javascript:Seleccionar_CheckExportacion()" >
											</div>
										</td>
										<td style="text-align:right;width:40%;vertical-align:middle">
											<label class="columna">Operaciones Gratuitas:</label></td>
										<td style="width:10%">
											<input id="cbox_opergratisdocumento" type="checkbox" value="" name="cbox_opergratisdocumento" onClick="javascript:Seleccionar_CheckOperacionGratuitas()">
										</td>

									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="text-align:right" colspan="2" >
								<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" 
									style="width:160px; height:30px" id="create-aditional" title="" >
									<span class="ui-button-icon-left ui-icon ui-icon-newwin"></span>
									<span class="ui-button-icon-left ui-icon ui-icon-document"></span>Datos Adicionales</button>
							</td>
							<td style="text-align:left" colspan="3">
								<div style="width:100%;height:15px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:0px;text-align:center;float:left">
									<div id="div_MensajeValidacionEmpresa" style="width:100%;float:left;font-size:9px; color:#FF0000"></div>
								</div>
							</td>
						</tr>			
					</table>	
				</div> 
				<div id="div_ListadoEmpresa" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:1px">
				</div>	
				<div id="div_datosdevalidacion" style="width:100%; float:left; margin-top:2px; border: 1px solid #a6c9e2; border-radius:5px;">
					<table border="0" width="100%" class="tablaFormulario" >						
						<tr valign="top">
							<td style="text-align:left;width:70%" valign="top">							
									<table border="0" width="100%" >		
										<tr>
											<td style="text-align:left;width:65%" >							
												<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" 
													style="width:160px; height:30px" id="create-user" title="" >
													<span class="ui-button-icon-left ui-icon ui-icon-document"></span>Nuevo Producto</button>	
											</td>										
										</tr>
										<tr>
											<td style="text-align:left;height:110px" >							
																	
											</td>										
										</tr>
										<tr id="row1">
											<td  style="text-align:left">
												<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" 
													style="width:160px; height:30px" id="create-detraccion" title="" >
													<span class="ui-button-icon-left ui-icon ui-icon-plusthick"></span>Ingresar Detracción</button>
											</td>												
										</tr>
										<tr>
											<td style="text-align:left">
												<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" 
													style="width:160px; height:30px" id="create-percepcion" title="" >
													<span class="ui-button-icon-left ui-icon ui-icon-plusthick"></span>Ingresar Percepción</button>
											</td>												
										</tr>
										<tr id="row_cargos">
											<td style="text-align:left">												
												<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" 
													style="width:160px; height:30px" id="create-otros-cargos" title="" >
													<span class="ui-button-icon-left ui-icon ui-icon-plusthick"></span>Ingresar Otro Cargo</button>
											</td>												
										</tr>
									</table>
													
							</td>
							<td style="text-align:right;width:30%" valign="top">
								<table width="100%" border="0" >
									<tr>							
										<td style="text-align:right;width:20%">Operación Gravadas :</td>
										<td style="text-align:right;width:15%" >
											<input type="text" id="txt_operaciongravadas" value="0.00" disabled="disabled" style="width:80%;text-align:right" >
										</td>			
									</tr>
									<tr>
										<td style="text-align:right">Operación Inafectos :</td>
										<td style="text-align:right" >
											<input type="text" id="txt_operacioninafectos" value="0.00" disabled="disabled" style="width:80%;text-align:right">
										</td>					
									</tr>
									<tr>
										<td style="text-align:right">Operación Exportacion :</td>
										<td style="text-align:right" >
											<input type="text" id="txt_operacionexportacion" value="0.00" disabled="disabled" style="width:80%;text-align:right">
										</td>
									</tr>
									<tr>
										<td style="text-align:right">Operación Exoneradas :</td>
										<td style="text-align:right" >
											<input type="text" id="txt_operacionexoneradas" value="0.00" disabled="disabled" style="width:80%;text-align:right">
										</td>
									</tr>
									<tr>
										<td style="text-align:right">Operación Gratuitas :</td>
										<td style="text-align:right" >
											<input type="text" id="txt_operaciongratuitas" value="0.00" disabled="disabled" style="width:80%;text-align:right">
										</td>
									</tr>
									<tr>
										<td style="text-align:right">Descuentos :</td>
										<td style="text-align:right" >
											<input type="text" id="txt_descuentototal" value="0.00" disabled="disabled" style="width:80%;text-align:right">
										</td>	
									</tr>
									<tr>
										<td style="text-align:right">I.S.C 0% :</td>
										<td style="text-align:right" >
											<input type="text" id="txt_isctotal" value="0.00" disabled="disabled" style="width:80%;text-align:right">
										</td>
									</tr>
									<tr>
										<td style="text-align:right">IGV <?php echo $valor_igv;?>% :</td>
										<td style="text-align:right" >
											<input type="text" id="txt_igvtotal" value="0.00" disabled="disabled" style="width:80%;text-align:right">
										</td>
									</tr>
									<tr>
										<td style="text-align:right">Otros Cargos :</td>
										<td style="text-align:right" >
											<input type="text" id="txt_otroscargos" value="0.00" disabled="disabled" style="width:80%;text-align:right">
										</td>
									</tr>
									<tr>
										<td style="text-align:right">Importe Total :</td>
										<td style="text-align:right" >
											<input type="text" id="txt_importetotal" value="0.00" disabled="disabled" style="width:80%;text-align:right">
										</td>	
									</tr>
									<tr>
										<td style="text-align:right">Descuento Global :</td>
										<td style="text-align:right" >
											<input type="text" id="txt_descuentoglobal" value=""  style="width:80%;text-align:right">
										</td>	
									</tr>
								</table>
							</td>			
						</tr>
					</table>
				</div>
				<div id="div_datosdevalidacion" style="width:100%; float:left; margin-top:2px; border: 1px solid #a6c9e2; border-radius:5px;">
					<table border="0" width="100%" class="tablaFormulario">
						<tr>
							<td style="text-align:left;width:10%">
								<button style="width:155; height:32px" id="btn_RegistrarDatosdelDocumentoGuardar" title=""
									class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" 
									onClick="ncsistema.Guardar_Einvoiceheader(0)">
									<span class="ui-button-icon-left ui-icon ui-icon-disk"></span>
									<span class="ui-button-text">Guardar</span></button>
							</td>
							<td style="text-align:left;width:90%">
								<button style="width:155px; height:32px" id="btn_RegistrarDatosdelDocumento" title="" 
									class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left"
									onClick="ncsistema.Guardar_Einvoiceheader(1)">
									<span class="ui-button-icon-left ui-icon ui-icon-transfer-e-w"></span>
									<span class="ui-button-text"><div id="div_nombrebotonguardar">Enviar y Declarar</div></span></button>
							</td>
						</tr>
					</table>
				</div>
			</div>

		</div>
		<!--
		<div>
			<?php $this->load->view('inicio/footer'); ?> 
		</div>
		-->
		<div id="dialog-form" title="Crear un Nuevo ITEM">
			<!--<p class="validateTips">All form fields are required.</p>-->
			<input type="hidden" id="numeroitem" value="0" >
		 	<form>
			<table width="100%" border="0px">
				<tr>
					<td style="width:20%;font-weight:bold">
						N° Item:
					</td >
					<td style="width:80%" colspan="4">
						<table width="100%" border="0" style="border-collapse:separate; border-spacing:0px 0px;">
							<tr>
								<td width="10%">
									<input type="text" name="txt_numeroitem" id="txt_numeroitem" value="1" disabled="disabled" class="">
								</td>
								<td width="90%">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="width:20%;font-weight:bold">
						Código:
					</td>
					<td style="width:30%">
						<table width="100%" style="border-collapse:separate; border-spacing:0px 0px;">
							<tr>
								<td width="40%">
									<input type="text" name="txt_codigoprod" id="txt_codigoprod" value="-"  width="100%" class="">
									<input type="hidden" name="txt_idprod" id="txt_idprod" disabled="disabled" >
								</td>
								<td style="text-align:left;vertical-align:middle">
									<a href="javascript:ver_filtro_producto()">
										<img align="center" src="<?php echo base_url();?>application/helpers/image/ico/buscar.png" 
											title="Buscar Producto" width="20" height="20"  border="0" >	
									</a>
								</td>
							</tr>
						</table>
					</td>
					<td width="20%" style="font-weight:bold; text-align:right">
						Unid. Medida:
					</td>
					<td width="30%">
						<table width="100%" border="0" style="border-spacing:0px 0px;">
							<tr>
								<td width="100%">
									<select id="cmb_unidadmedida" style="width:97%;height:25px" >
										<option value="">SELECCIONAR</option>							
										<?php foreach ( $Listar_Unidades as $v):	?>
											<option value="<?php echo trim($v['codigo_unidad']); ?>"><?php echo trim(utf8_decode($v['nombre_unidad']));?> </option>
										<?php  endforeach; ?>
									</select>
								</td>
							</tr>
						</table>
					</td>			
				</tr>
				<tr>
					<td style="font-weight:bold; vertical-align:top">
						Descripción:
					</td>
					<td colspan="4">
						<textarea id="txt_descripcion" style="width: 98%; height: 50px;text-transform:uppercase" placeholder="Máximo 250 caracteres" maxlength="250" name="txt_ubicacion"></textarea>
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">
						Tipo Afectación:
					</td>
					<td colspan="4">
						<div id="div_tipoafectacion">
							<select id="cmb_tipoafectacion" style="width:100%;height:25px" onChange="javascript:Calcular_Montos()" >
								<option value="">SELECCIONAR</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">
						Cantidad:
					</td>
					<td>
						<table width="100%" border="0" style="border-spacing:0px 0px;">
							<tr>
								<td width="40%">
									<input style="text-align:right" type="text" name="txt_cantidad" id="txt_cantidad" placeholder="0.00" onkeypress="return NumCheck(event, this);" onBlur="javascript:Calcular_Montos()">
								</td>
								<td >
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td> <label class="columna"></label>
					</td>
					<td> 
					</td>
				</tr>
				<tr>
					<td colspan="2" style="vertical-align:top">
					  <div id="div_sinIGV" style="width:100%; float:left; margin-top:0px; border: 1px solid #a6c9e2; border-radius:3px;">
						<table>
							<tr>
								<td style="font-weight:bold" width="45%">
									Valor Unitario:
								</td>
								<td  width="55%">
									<table width="100%" border="0" style="border-spacing:0px 0px;">
										<tr>
											<td width="40%">
												<input type="text" name="txt_valorunitario" id="txt_valorunitario" placeholder="0.00" onkeypress="return NumCheck(event, this);" style="text-align:right" onBlur="javascript:Calcular_Montos()">
											</td>
											<td >
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr height="100%"><td colspan="2" >
								<input type="text" name="space1" id="space1" style="width:0; visibility:hidden"  ></td>
							</tr>
							<tr>
								<td style="font-weight:bold">
									Descuento:
								</td>
								<td>
									<table width="100%" border="0" style="border-spacing:0px 0px;">
										<tr>
											<td width="40%">
												<input type="text" name="txt_descuento" id="txt_descuento" placeholder="0.00" onkeypress="return NumCheck(event, this);" style="text-align:right" onBlur="javascript:Calcular_Montos()">
											</td>
											<td >
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style="font-weight:bold">
									ISC:
								</td>
								<td>
									<table width="100%" border="0" style=" border-spacing:0px 0px;">
										<tr>
											<td width="40%">
												<input type="text" name="txt_isc" id="txt_isc" placeholder="0.00" disabled="disabled" style="text-align:right">
											</td>
											<td >
											</td>
										</tr>
									</table>						
								</td>
							</tr>
							<tr>
								<td style="font-weight:bold">
									IGV:
								</td>
								<td>
									<table width="100%" border="0" style=" border-spacing:0px 0px;">
										<tr>
											<td width="40%">
												<input type="text" name="txt_igv" id="txt_igv" placeholder="0.00" disabled="disabled" class="" style="text-align:right">
											</td>
											<td ><div id="div_mensajereferencia"></div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style="font-weight:bold">
									Valor Total:
								</td>
								<td>
									<table width="100%" border="0" style=" border-spacing:0px 0px;">
										<tr>
											<td width="40%">
												<input type="text" name="txt_valortotal" id="txt_valortotal" placeholder="0.00" disabled="disabled" class="" style="text-align:right">
											</td>
											<td >
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					  </div>
					</td>
					<td colspan="2" style="vertical-align:top">
					  <div id="div_sinIGV" style="width:100%; float:left; margin-top:0px; border: 1px solid #a6c9e2; border-radius:3px;">
					  	<table border="0px" style="height: 100%;">
							<tr>
								<td style="font-weight:bold" width="45%">
									Precio Unitario:
								</td>
								<td>
									<table width="100%" border="0" style="border-spacing:0px 0px;">
										<tr>
											<td width="40%">
												<input type="text" name="txt_preciounitario" id="txt_preciounitario" placeholder="0.00" disabled="disabled" style="text-align:right">
											</td>
											<td >
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style="font-weight:bold" width="45%">
									Precio de Cobro:
								</td>
								<td width="55%">
									<table width="100%" border="0" style="border-spacing:0px 0px;">
										<tr>
											<td width="40%">
												<input type="text" name="txt_precio" id="txt_precio" placeholder="0.00" onkeypress="return NumCheck(event, this);" disabled="disabled" style="text-align:right" onBlur="javascript:Calcular_Montos()">
											</td>
											<td >
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style="font-weight:bold">
									Dscto. inc. IGV:
								</td>
								<td>
									<table width="100%" border="0" style="border-spacing:0px 0px;">
										<tr>
											<td width="40%">
												<input type="text" name="txt_descuentoIGV" id="txt_descuentoIGV" placeholder="0.00" onkeypress="return NumCheck(event, this);" disabled="disabled" style="text-align:right" onBlur="javascript:Calcular_Montos()">
											</td>
											<td >
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr height="100%"><td colspan="2" >
								<input type="text" name="space11" id="space1" style="width:0; visibility:hidden"  ></td>
							</tr>
							<tr height="100%"><td colspan="2" >
								<input type="text" name="space111" id="space111" style="width:0; visibility:hidden"  ></td>
							</tr>
							<tr>
								<td style="font-weight:bold">
									Precio Total:
								</td>
								<td>
									<table width="100%" border="0" style="border-spacing:0px 0px;">
										<tr>
											<td width="40%">
												<input type="text" name="txt_preciototal" id="txt_preciototal" placeholder="0.00" disabled="disabled" style="text-align:right">
											</td>
											<td >
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					  </div>
					</td>
				</tr>
			</table>
	  
		  </form>
		</div>

		<div id="dialog-form-detraccion" title="Detracción">
		 	<form>
			<table width="100%" border="0px">
				<tr>
					<td style="font-weight:bold;width:30%">% Detracción:</td>
					<td style="width:70%">
						<input type="text" id="txt_porcentajedetraccion" value="0.00" style="width:20%" onBlur="javascript:Calcular_Detraccion()">
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Monto Detracción:</td>
					<td >
						<input type="text" id="txt_montodetraccion" disabled="disabled" value="0.00" style="width:40%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Monto Detracción referencial:</td>
					<td >
						<input type="text"  id="txt_montodetraccionreferencial" value="0.00" style="width:40%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Descripción:</td>
					<td >
						<input type="text" id="txt_descripciondetraccion" value="" style="width:95%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Cuenta del Banco de la Nación:</td>
					<td >
						<input type="text" id="txt_leyendadetraccion" value="" style="width:95%" >
					</td>
				</tr>
				
			</table>
		

		  </form>
		</div>
		
		
		<div id="dialog-form-registrofactura" title="Respuesta de Registro">
		  <form>
			<table width="100%" border="0px">
				<tr>
					<td style="font-weight:bold;width:40%">Número de Factura:</td>
					<td style="width:60%">
						<input type="text" id="txt_numerodocumento_respuesta" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Estado del Documento:</td>
					<td >
						<input type="text" id="txt_estadodocumento_respuesta" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Descripción:</td>
					<td >
						<input type="text"  id="txt_descripciondocumento_respuesta" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
			</table>
		  </form>
		</div>
		
		<div id="dialog-form-registroboleta" title="Respuesta de Registro">
		 	<form>
			<table width="100%" border="0px">
				<tr>
					<td style="font-weight:bold;width:40%">Número de Boleta:</td>
					<td style="width:60%">
						<input type="text" id="txt_numerodocumentoboleta_respuesta" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Estado del Documento:</td>
					<td >
						<input type="text" id="txt_estadoboleta_respuesta" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Descripción:</td>
					<td >
						<input type="text"  id="txt_mensajeboleta_respuesta" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
			</table>
		  </form>
		</div>
		
		<div id="dialog-form-registroncynd" title="Respuesta de Registro">
		 	<form>
			<table width="100%" border="0px">
				<tr>
					<td style="font-weight:bold;width:40%"><div id="div_etiquetencynd"></div></td>
					<td style="width:60%">
						<input type="text" id="txt_numerodocumentoncynd_respuesta" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Estado del Documento:</td>
					<td >
						<input type="text" id="txt_estadodocumentoncynd_respuesta" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Descripción:</td>
					<td >
						<input type="text"  id="txt_mensajencynd_respuesta" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
			</table>
		  </form>
		</div>
		
		
		<div id="dialog-datosemisor" title="Datos del Proveedor">
		 	<form>
			<table width="100%" border="0px">
				
				<tr>
					<td style="font-weight:bold">Nombre Comercial:</td>
					<td >
						<input type="text" id="txt_emisornombrecomercial" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold;width:40%">Correo del Emisor:</td>
					<td style="width:60%">
						<input type="text" id="txt_emisorcorreo" disabled="disabled"  value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">País:</td>
					<td >
						<input type="text" id="txt_emisorpais" disabled="disabled" value="" style="width:90%" >
						<input type="hidden" id="txt_emisorpaiscodigo" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<input type="hidden" id="txt_emisorubigeo" disabled="disabled" value="" style="width:90%" >
					<td style="font-weight:bold">Departamento:</td>
					<td >
						<input type="text" id="txt_emisordepartamento" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Provincia:</td>
					<td >
						<input type="text" id="txt_emisorprovincia" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Distrito:</td>
					<td >
						<input type="text" id="txt_emisordistrito" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Urbanización Emisor:</td>
					<td >
						<input type="text" id="txt_emisorrubanizacion" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Dirección Emisor:</td>
					<td >
						<input type="text" id="txt_emisordireccion" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				
			</table>
		  </form>
		</div>
		
		<div id="dialog-datoscliente" title="Datos del Cliente">
		 	<form>
			<table width="100%" border="0px">
				
				<tr>
					<td style="font-weight:bold">Nombre Comercial:</td>
					<td >
						<input type="text" id="txt_clientenombrecomercial" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold;width:40%">Correo del Cliente:</td>
					<td style="width:60%">
						<input type="text" id="txt_clientecorreo" disabled="disabled"  value="" style="width:90%" >
					</td>
				</tr>				
				<tr>
					<td style="font-weight:bold">País:</td>
					<td >
						<input type="text" id="txt_clientepais" disabled="disabled" value="" style="width:90%" >
						<input type="hidden" id="txt_clientepaiscodigo" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<input type="hidden" id="txt_clienteubigeo" disabled="disabled" value="" style="width:90%" >
					<td style="font-weight:bold">Departamento:</td>
					<td >
						<input type="text" id="txt_clientedepartamento" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Provincia:</td>
					<td >
						<input type="text" id="txt_clienteprovincia" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Distrito:</td>
					<td >
						<input type="text" id="txt_clientedistrito" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Urbanización:</td>
					<td >
						<input type="text" id="txt_clienteubanizacion" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Dirección Cliente:</td>
					<td >
						<input type="text" id="txt_clientedireccion" disabled="disabled" value="" style="width:90%" >
					</td>
				</tr>
				
			</table>
		  </form>
		</div>
		
		<div id="dialog-datosbusquedaproducto" title="Filtro de Productos">
		 	<form>
			<table width="100%" border="0px">				
				<tr>
					<td style="font-weight:bold; width:15%">Código:</td>
					<td style="width:20%">
						<input type="text" id="txt_busqueda_codigoproducto" value="" style="width:95%" >
						<input type="hidden" id="txt_busqueda_idproducto" disabled="disabled" value="" >
					</td>
					<td style="font-weight:bold; width:15%">Descripción:</td>
					<td style="width:45%">
						<input type="text" id="txt_busqueda_descripcionproducto" value="" style="width:95%" >
					</td>
					<td style="width:5%;text-align:left;vertical-align:middle">
						<a href="javascript:ver_filtro_producto_button()">
							<img align="center" src="<?php echo base_url();?>application/helpers/image/ico/buscar.png" 
								title="Filtrar..." width="20" height="20"  border="0" >	
						</a>
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold" colspan="5">
						<div id="div_Busqueda_productofiltro" style="width:100%;border:solid 0px;float:left;
							text-align:center;margin-top:10px">
						</div>
					</td>
				</tr>
			</table>
		  </form>
		</div>
		
		<div id="dialog-form-percepcion" title="Percepción">
		 	<form>
			<table width="100%" border="0px">
				<tr>
					<td style="font-weight:bold;width:30%">% Percepción:</td>
					<td style="width:70%">
						<input type="text" id="txt_porcentajepercepcion" value="0.00" style="width:20%" onBlur="javascript:Calcular_Percepcion()">
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Base Imponible Percepción:</td>
					<td >
						<input type="text" id="txt_baseimponiblepercepcion" value="0.00" style="width:40%" onBlur="javascript:Calcular_Percepcion()">
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Total Percepción:</td>
					<td >
						<input type="text" id="txt_totalpercepcion" disabled="disabled" value="0.00" style="width:40%" >
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Total Venta con Percepción:</td>
					<td >
						<input type="text"  id="txt_totalventapercepcion" disabled="disabled" value="0.00" style="width:40%" >
					</td>
				</tr>
			</table>
		  </form>
		</div>
		<div id="dialog-form-otroscargos" title="Otros Cargos">
		 	<form>
			<div id="div_OtrosCargos" style="width:100%; float:left; margin-top:0px; border: 1px solid #a6c9e2; border-radius:4px;">
			<table width="100%" border="0px">
				<tr>
					<td align="right" >
						<input id="chekbox_opegravada" type="checkbox" value="" name="chekbox_opegravada" onClick="javascript:Seleccionar_Check_opegravada()">
					</td>
					<td style="font-weight:bold; vertical-align: middle" align="left">
						<label class="columna">Operación Gravada</label>
					</td>
				</tr>
				<tr>
					<td align="right" >
						<input id="chekbox_opeinafecta" type="checkbox" value="" name="chekbox_opeinafecta" onClick="javascript:Seleccionar_Check_opeinafecta()">
					</td>
					<td style="font-weight:bold; vertical-align: middle" align="left">
						<label class="columna">Operación Inafecta</label>
					</td>
				</tr>
				<tr>
					<td align="right" >
						<input id="chekbox_opeexonerada" type="checkbox" value="" name="chekbox_opeexonerada" onClick="javascript:Seleccionar_Check_opeexonerada()">
					</td>
					<td style="font-weight:bold; vertical-align: middle" align="left">
						<label class="columna">Operación Exonerada</label>
					</td>
				</tr>
			</table>
			</div>
		  </form>
		</div>
		
		<div id="dialog-form-adicional" title="Datos Adicionales">
		 	<form>
			<table width="100%" border="0px">				
				<tr>
					<td style="font-weight:bold" colspan="5">
						<div id="div_Busqueda_DatosAdicionales" style="width:100%;border:solid 0px;float:left;
							text-align:center;margin-top:10px">
						</div>
					</td>
				</tr>
			</table>
		  </form>
		</div>
		
		<script>
			Listar_DatosDocumentoModificar();
		</script>
		
    </body>	
</html>