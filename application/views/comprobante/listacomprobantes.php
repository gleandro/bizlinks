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
				$.datepicker.setDefaults($.datepicker.regional["es"]);
				$("#tabs").tabs();
				ncsistema.Listar_ComprobantesTabla('',0);	
				//var currentDate = new Date();
				$('#txt_FechaEmisionInicio').datepicker({
					//defaultDate: new Date().getDay-5,
					showOn: 'button',					
					buttonImage: "<?php echo base_url()?>application/helpers/image/ico/calendar_icon.gif",
					buttonImageOnly: true,
					dateFormat: 'dd/mm/yy',
					buttonText: "Desde (##/##/####)",
					maxDate: 'today',
					changeMonth: true ,
					changeYear: true 
					
				});
				$('#txt_FechaEmisionInicio').datepicker('setDate', 'today -1');
				
				$('#txt_FechaEmisionFinal').datepicker({
					showOn: 'button',					
					buttonImage: "<?php echo base_url()?>application/helpers/image/ico/calendar_icon.gif",
					buttonImageOnly: true,
					dateFormat: 'dd/mm/yy',
					buttonText: "Hasta (##/##/####)",
					maxDate: 'today',
					changeMonth: true ,
					changeYear: true
				});
				$('#txt_FechaEmisionFinal').datepicker('setDate', new Date());
				//$('#txt_FechaEmisionInicio').datepicker('option',{minDate: '01/07/2016'});	
				//$('#txt_FechaEmisionFinal').datepicker('option',{minDate: '01/07/2016'});			
				ncsistema.Buscar_Clientes();
			})
			
			ncsistema=
			{
				Buscar_Clientes:function ()
				{
					var lista_clientes = {};
					$('#txt_RazonSocialCliente').attr('readonly', false);
					$('#txt_RazonSocialCliente').autocomplete
					({
						minLength: 1,
						delay: 700,
						source: function( request, response ) 
						{
							var term = request.term;
							
							$('#txt_DocumentoCliente').val('');
							
							if ( term in lista_clientes ) {
								response( lista_clientes[ term ] );
								return;
							}
							$.getJSON('<?php echo base_url()?>clientes/listar_clientes_autocompletar', request, function( data, status, xhr ) {
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
							$('#txt_RazonSocialCliente').val(ui.item.value );
							$('#txt_DocumentoCliente').val(ui.item.nro_docum);

							return false;
						}
					});
				},




				Listar_Comprobantes:function()
				{
					var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
					var txt_DocumentoCliente=$.trim($('#txt_DocumentoCliente').val());					
					var txt_RazonSocialCliente=$.trim($('#txt_RazonSocialCliente').val());					
					var txt_serienumeroinicio=$.trim($('#txt_serienumeroinicio').val());
					var txt_serienumerofinal=$.trim($('#txt_serienumerofinal').val());					
					var Cmb_EstadoDocumento=$.trim($('#Cmb_EstadoDocumento').val());					
					var txt_FechaEmisionInicio=$.trim($('#txt_FechaEmisionInicio').val());
					var txt_FechaEmisionFinal=$.trim($('#txt_FechaEmisionFinal').val());					
					var Cmb_TipoDocumentoSunat=$.trim($('#Cmb_TipoDocumentoSunat').val());
					var Cmb_EstadoDocumentoSunat=$.trim($('#Cmb_EstadoDocumentoSunat').val());
					var Cmb_TipoMoneda=$.trim($('#Cmb_TipoMoneda').val());					
					//alert("HOLA");
					$('#txt_filtrobusqueda').val(txt_RucEmpresa+','+txt_DocumentoCliente+','+txt_RazonSocialCliente+','+txt_serienumeroinicio+','+txt_serienumerofinal+','+Cmb_EstadoDocumento+','+txt_FechaEmisionInicio+','+txt_FechaEmisionFinal+','+Cmb_TipoDocumentoSunat+','+Cmb_EstadoDocumentoSunat+','+Cmb_TipoMoneda);					
					$('#txt_botonbusqueda').val('1');

					ncsistema.Listar_ComprobantesTabla("");
					
					if (txt_RazonSocialCliente=='')
					{
						txt_DocumentoCliente='';
						$('#txt_DocumentoCliente').val('');
					}else
					{
						if (txt_DocumentoCliente=='')
						{
							alert('El cliente ingresado no tiene documento o no esta registrado');
							return;
						}
					}
					if (txt_FechaEmisionInicio!='' && txt_FechaEmisionFinal=='')
					{
						alert('Es necesaro indicar la fecha final de emisión');
						return;
					}
					if (txt_FechaEmisionFinal!='' && txt_FechaEmisionInicio=='')
					{
						alert('Es necesaro indicar la fecha inicial de emisión');
						return;
					}
					if (txt_serienumeroinicio!='' && txt_serienumerofinal=='')
					{
						alert('Es necesaro indicar la fecha final de emisión');
						return;
					}
					if (txt_serienumerofinal!='' && txt_serienumeroinicio=='')
					{
						alert('Es necesaro indicar la fecha inicial de emisión');
						return;
					}
					if (txt_RazonSocialCliente=='GENERICO')
						txt_RazonSocialCliente='-';
					$('#txt_datosseleccionados').val('');
					$('#txt_cantidadseleccionados').val('0');
					$('#txt_cantidaddocumborrador').val('0');
					
					$('#txt_datosseleccionados_estado').val('');
					$('#txt_cantidaddocum_estado').val('0');
					
					$.ajax({
						url:'<?php echo base_url()?>comprobante/Listar_Comprobantes',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_RucEmpresa:txt_RucEmpresa,
							txt_DocumentoCliente:txt_DocumentoCliente,
							txt_serienumeroinicio:txt_serienumeroinicio,
							txt_serienumerofinal:txt_serienumerofinal,							
							Cmb_EstadoDocumento:Cmb_EstadoDocumento,						
							txt_FechaEmisionInicio:txt_FechaEmisionInicio,
							txt_FechaEmisionFinal:txt_FechaEmisionFinal,
							Cmb_TipoDocumentoSunat:Cmb_TipoDocumentoSunat,
							Cmb_EstadoDocumentoSunat:Cmb_EstadoDocumentoSunat,
							Cmb_TipoMoneda:Cmb_TipoMoneda,
							txt_RazonSocialCliente:txt_RazonSocialCliente
						},
						beforeSend:function()
						{
							$('#div_procesarbuscar').empty().append('<img src="<?php echo base_url();?>application/helpers/image/ico/loading.gif" width="35" height="35"/>');
						},
						success:function(result)
						{
							$('#div_procesarbuscar').empty().append('');
							if(result.status==1)
							{
								ncsistema.Listar_ComprobantesTabla(result.data);								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								ncsistema.Listar_ComprobantesTabla("");
							}
						}
					});					
				},				
				
				
				Listar_ComprobantesTabla:function(data,tipodocumento)
				{	
					$('#div_ListadoEmpresa').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th width:3%>Opci&oacute;n</td>';						
						newHtml+='<th width:10%>Cliente</td>';						
						newHtml+='<th width:5%>Tipo Doc.</td>';
						newHtml+='<th width:20%>SerieNúmero</td>';
						newHtml+='<th width:5%>Moneda</td>';
						newHtml+='<th width:5%>Imp.Total</td>';
						newHtml+='<th width:7%>Fec.Emisi&oacute;n</td>';
						newHtml+='<th width:7%>Est.Doc.</td>';
						newHtml+='<th width:8%>Est.Sunat</td>';
						newHtml+='<th width:25%>Descripci&oacute;n</td>';
						//newHtml+='<th width:50%>Intentos</td>';
						newHtml+='<th width:5%>Visto</td>';
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					//<input style="height:20px;width:95%" id="txt_login" type="text" value="'+rs.cantidadproducto+'"/>
					contador=0;
					$.each(data,function(key,rs)
					{
						newHtml+='<tr>';															

							newHtml+='<td style="text-align:center;color:#990000;font-weight:bold"><input id="cbox_seleccion_'+key+'" type="checkbox" value="" name="cbox_seleccion_'+key+'" onChange="javascrip:Seleccionar_DatosBusqueda('+key+',\''+rs.tipodocumento+'\',\''+rs.serienumero+'\',\''+rs.bl_estadoregistro+'\',\''+rs.estadosunat+'\')" ></td>';
							newHtml+='<td style="text-align:left">'+rs.razonsocialadquiriente+'</td>';		
							newHtml+='<td style="text-align:left">'+rs.nomb_tipodocumento+'</td>';
							newHtml+='<td style="text-align:left">'+rs.serienumero+'</td>';
							newHtml+='<td style="text-align:left">'+rs.tipomoneda+'</td>';	
							newHtml+='<td style="text-align:right">'+rs.totalventa+'</td>';			
							newHtml+='<td style="text-align:center">'+rs.fechaemision+'</td>';
							newHtml+='<td style="text-align:left">'+rs.estado_documento+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nombreestadosunat+'</td>';
							newHtml+='<td style="text-align:left">'+rs.obssunat+'</td>';
							//newHtml+='<td style="text-align:center">'+rs.cant_reintento+'</td>';
							
							if (rs.visualizado==0)//NO VISTO
							{
								newHtml+='<td style="text-align:left"><img align="left" src="<?php echo base_url();?>application/helpers/image/ico/ncinactivo.png" title="Enviado, recepcionado, no visualizado" ></td>';								
							}
							else
							{
								newHtml+='<td style="text-align:center"><img align="left" src="<?php echo base_url();?>application/helpers/image/ico/ncactivo.png" title="Enviado, recepcionado, visualizado" ></td>';
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
				ExportarExcel_General:function()
				{
					Descargar_ExcelGeneral();
				},
				

				Detalle_Comprobante:function()
				{
					var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
					var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());					
					var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());					
					if (txt_cantidadseleccionados==1)
					{
						$.ajax({
							url:'<?php echo base_url()?>comprobante/Listar_DetalleDocumento',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_RucEmpresa:txt_RucEmpresa,
								txt_datosseleccionados:txt_datosseleccionados
							},
							beforeSend:function()
							{
							},
							success:function(result)
							{
								if(result.status==1)
								{
									ncsistema.Detalle_ComprobanteTabla(result.data);
									
								}
								else if (result.status==1000)
								{
									document.location.href= '<?php echo base_url()?>usuario';
									return;
								}
								else
								{
									ncsistema.Detalle_ComprobanteTabla("");
								}
							}
						});						
					}
					else if (txt_cantidadseleccionados==0)
					{
						alert("Debe seleccionar un documento de la lista");
					}		
					else
					{
						alert("Solo se puede ver el detalle de un documento");
					}		
				},				
				
				
				Detalle_ComprobanteTabla:function(data)
				{	
					dialogdetallecomprobante.dialog("open");
					$('#div_documentodetalle').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_DetalleComprobanteTabla">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th width:3%>Item</td>';						
						newHtml+='<th width:5%>Codigo</td>';						
						newHtml+='<th width:10%>Descripcion</td>';
						newHtml+='<th width:10%>Und.</td>';
						newHtml+='<th width:20%>Cantidad</td>';
						newHtml+='<th width:50%>V.Unitario</td>';
						newHtml+='<th width:50%>P.Unitario</td>';
						newHtml+='<th width:50%>Descuento</td>';
						newHtml+='<th width:50%>Valor Total</td>';
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					
					$.each(data,function(key,rs)
					{					
						if (key==0)
						{							
							$('#div_param1').empty().append(rs.razonsocialemisor);	
							$('#div_param2').empty().append(rs.nombrecomercialemisor);	
							$('#div_param3').empty().append(rs.direccionemisor);	
							$('#div_param4').empty().append(rs.departamentoemisor+' - '+rs.provinciaemisor+' - '+rs.distritoemisor);	
							
							$('#div_param5').empty().append(rs.nombre_tipodocumentoemisor);	
							$('#div_param6').empty().append(rs.numerodocumentoemisor);	
							$('#div_param7').empty().append(rs.nombre_tipodocumento+' ELECTRONICA');	
							$('#div_param8').empty().append(rs.serienumero);	
							
							$('#div_param9').empty().append(rs.razonsocialadquiriente);	
							$('#div_param10').empty().append(rs.numerodocumentoadquiriente);	
							$('#div_param11').empty().append(rs.fechaemision);	
							$('#div_param12').empty().append(rs.direccioncliente);	
							$('#div_param13').empty().append(rs.tipomonedacabecera);	
							$('#div_param14').empty().append('');							
							$('#div_param15').empty().append(rs.textoleyenda_1);							
							
							OcultarFilaPassword('row1',0);
							OcultarFilaPassword('row2',0);
							OcultarFilaPassword('row3',0);
							OcultarFilaPassword('row4',0);
							OcultarFilaPassword('row5',0);
							OcultarFilaPassword('row6',0);//NO HAY
							OcultarFilaPassword('row7',0);
							OcultarFilaPassword('row8',0);
							OcultarFilaPassword('row9',0);/**/
							
							if (rs.totalvalorventanetoopgravadas>0)
							{
								OcultarFilaPassword('row1',1);
								$('#div_param16').empty().append(rs.tipomonedadescripcion);
								$('#div_param17').empty().append(rs.totalvalorventanetoopgravadas);
							}
							if (rs.totalvalorventanetoopnogravada>0)
							{
								OcultarFilaPassword('row2',1);
								$('#div_param18').empty().append(rs.tipomonedadescripcion);
								$('#div_param19').empty().append(rs.totalvalorventanetoopnogravada);
							}
							if (rs.totalvalorventanetoopexonerada>0)
							{
								OcultarFilaPassword('row3',1);
								$('#div_param20').empty().append(rs.tipomonedadescripcion);
								$('#div_param21').empty().append(rs.totalvalorventanetoopexonerada);
							}
							if (rs.totalvalorventanetoopgratuitas>0)
							{
								OcultarFilaPassword('row4',1);
								$('#div_param22').empty().append(rs.tipomonedadescripcion);
								$('#div_param23').empty().append(rs.totalvalorventanetoopgratuitas);
							}
							if (rs.totaldescuentos>0)
							{
								OcultarFilaPassword('row5',1);
								$('#div_param24').empty().append(rs.tipomonedadescripcion);
								$('#div_param25').empty().append(rs.totaldescuentos);
							}
							//falta ics
							if (rs.totaligv>0)
							{
								OcultarFilaPassword('row7',1);
								$('#div_param28').empty().append(rs.tipomonedadescripcion);
								$('#div_param29').empty().append(rs.totaligv);
							}
							//otros cargos no hay
							if (rs.totalventa>0)
							{
								OcultarFilaPassword('row9',1);
								$('#div_param32').empty().append(rs.tipomonedadescripcion);
								$('#div_param33').empty().append(rs.totalventa);
							}
						}
						
						newHtml+='<tr>';															

							newHtml+='<td style="text-align:left">'+rs.numeroordenitem+'</td>';		
							newHtml+='<td style="text-align:left">'+rs.codigoproducto+'</td>';
							newHtml+='<td style="text-align:left">'+rs.descripcion+'</td>';
							newHtml+='<td style="text-align:left">'+rs.unidadmedida+'</td>';	
							newHtml+='<td style="text-align:right">'+rs.cantidad+'</td>';			
							newHtml+='<td style="text-align:right">'+rs.importeunitariosinimpuesto+'</td>';
							newHtml+='<td style="text-align:right">'+rs.importeunitarioconimpuesto+'</td>';
							newHtml+='<td style="text-align:right">'+rs.importedescuento+'</td>';
							newHtml+='<td style="text-align:right">'+rs.importetotalsinimpuesto+'</td>';

						newHtml+='</tr>';						
					});	
					
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_documentodetalle').empty().append(newHtml);	

					oTable=$('#Tab_DetalleComprobanteTabla').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_DetalleComprobanteTabla tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});
					
					
					
				},

			}
			
			
			
			$(function() 
			{
				function Imprimir_DocumentoSeleccionadoDetalle() 
				{
					var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
					var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());	
					var txt_rucemisor=$.trim($('#txt_RucEmpresa').val());

					if (txt_cantidadseleccionados>0)
					{
						//alert("Prueba jj");
						
						$.ajax({
							url:'<?php echo base_url()?>comprobante/Comprobar_DocumentoImprimir',
							type: 'post',
							dataType: 'json',
							data:
							{
								param1:txt_datosseleccionados,
								param2:txt_rucemisor
							},
							beforeSend:function()
							{								
							},
							success:function(result)
							{
								if(result.status==1)
								{
									
									document.location.href=urlexportardatos+'comprobante/Imprimir_DocumentoSeleccionado?param1='+txt_datosseleccionados+'&param2='+txt_rucemisor;
								}
								else if(result.status==2)
								{
									alert(urlexportardatos);
									alert("No existen los archivos seleccionados");
								}
								else if (result.status==1000)
								{
									document.location.href= '<?php echo base_url()?>usuario';
									return;
								}
								else
								{
									alert("Error al crear los archivos seleccionados");
								}
							}
						});	

						//document.location.href=urlexportardatos+'comprobante/Imprimir_DocumentoSeleccionado?param1='+txt_datosseleccionados+'&param2='+txt_rucemisor;
					}
					else
					{
						alert("Debe seleccionar al menos un registro");
					}
				}
				function Descargar_DocumentoSeleccionadoDetalle() 
				{
					var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
					var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());	
					var txt_rucemisor=$.trim($('#txt_RucEmpresa').val());
					
					if (txt_cantidadseleccionados>0)
					{
					
						$.ajax({
								url:'<?php echo base_url()?>comprobante/Crear_ArchivosDocumentoSeleccionado',
								type: 'post',
								dataType: 'json',
								data:
								{
									param1:txt_datosseleccionados,
									param2:txt_rucemisor
								},
								beforeSend:function()
								{
									
								},
								success:function(result)
								{
									if(result.status==1)
									{
										Descargar_DocumentoSeleccionadoArchivos();
									}
									else if(result.status==2)
									{
										alert("No se encontraron archivos para los documentos seleccionados");
									}
									else if (result.status==1000)
									{
										document.location.href= '<?php echo base_url()?>usuario';
										return;
									}
									else
									{
										alert("Error al crear los archivos seleccionados");
									}
								}
							});
						 //setTimeout('Descargar_DocumentoSeleccionadoArchivos()',4000)
					}
					else
					{
						alert("Debe seleccionar al menos un registro");
					}
				}
			
				dialogdetallecomprobante = $("#dialog-form-detalledocumento").dialog({
					autoOpen: false,
					height: 650,
					width: 850,
					modal: true,
					buttons: 
					{
						
						"Imprimir": Imprimir_DocumentoSeleccionadoDetalle,
						"Descargar": Descargar_DocumentoSeleccionadoDetalle,
						"Salir": function() 
						{
							dialogdetallecomprobante.dialog( "close" );							
						}
					},						
					close: function() 
					{
						//form[ 0 ].reset();
						//allFields.removeClass( "ui-state-error" );
					}
				});
			
			});
						
			function Limpiar_Busqueda()
			{
				$('#txt_DocumentoCliente').val('');
				$('#txt_RazonSocialCliente').val('');
				$('#txt_serienumeroinicio').val('');
				$('#txt_serienumerofinal').val('');				
				$('#Cmb_EstadoDocumento').val('0');
				$('#txt_FechaEmisionInicio').val('');
				$('#txt_FechaEmisionFinal').val('');				
				$('#Cmb_TipoDocumentoSunat').val('0');	
				$('#Cmb_EstadoDocumentoSunat').val('0');	
				$('#Cmb_TipoMoneda').val('0');
				
				$('#txt_filtrobusqueda').val('');					
				$('#txt_botonbusqueda').val('0');
				
				ncsistema.Listar_ComprobantesTabla('',0);	
			}
			
			
			function Descargar_ExcelGeneral()
			{
				var Cmb_OpcionesExportarExcel=$.trim($('#Cmb_OpcionesExportarExcel').val());	
				/*
				var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
				var txt_DocumentoCliente=$.trim($('#txt_DocumentoCliente').val());					
				var txt_RazonSocialCliente=$.trim($('#txt_RazonSocialCliente').val());				
				var txt_serienumeroinicio=$.trim($('#txt_serienumeroinicio').val());
				var txt_serienumerofinal=$.trim($('#txt_serienumerofinal').val());				
				var Cmb_EstadoDocumento=$.trim($('#Cmb_EstadoDocumento').val());				
				var txt_FechaEmisionInicio=$.trim($('#txt_FechaEmisionInicio').val());
				var txt_FechaEmisionFinal=$.trim($('#txt_FechaEmisionFinal').val());				
				var Cmb_TipoDocumentoSunat=$.trim($('#Cmb_TipoDocumentoSunat').val());
				var Cmb_EstadoDocumentoSunat=$.trim($('#Cmb_EstadoDocumentoSunat').val());
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());
				var Cmb_TipoMoneda=$.trim($('#Cmb_TipoMoneda').val());
				*/
				var txt_botonbusqueda=$.trim($('#txt_botonbusqueda').val());
				if (txt_botonbusqueda==0)
				{
					alert('No ha realizado ninguna búsqueda');
					return;
				}	
				var str = $('#txt_filtrobusqueda').val();
				var resultado = str.split(','); 
				
				var txt_RucEmpresa=resultado[0];
				var txt_DocumentoCliente=resultado[1];			
				var txt_RazonSocialCliente=resultado[2];				
				var txt_serienumeroinicio=resultado[3];
				var txt_serienumerofinal=resultado[4];			
				var Cmb_EstadoDocumento=resultado[5];		
				var txt_FechaEmisionInicio=resultado[6];
				var txt_FechaEmisionFinal=resultado[7];				
				var Cmb_TipoDocumentoSunat=resultado[8];
				var Cmb_EstadoDocumentoSunat=resultado[9];
				var Cmb_TipoMoneda=resultado[10];
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());
				
				if (Cmb_OpcionesExportarExcel==1)
				{
					txt_datosseleccionados='';
					document.location.href=urlexportardatos+'comprobante/Exportar_ExcelGeneral?param1='+txt_RucEmpresa+'&param2='+txt_DocumentoCliente+'&param3='+txt_serienumeroinicio+'&param4='+txt_serienumerofinal+'&param5='+Cmb_EstadoDocumento+'&param6='+txt_FechaEmisionInicio+'&param7='+txt_FechaEmisionFinal+'&param8='+Cmb_TipoDocumentoSunat+'&param9='+Cmb_EstadoDocumentoSunat+'&param10='+txt_datosseleccionados+'&param11='+txt_RazonSocialCliente+'&param12='+Cmb_TipoMoneda;
				}
				else
				{
					if (txt_datosseleccionados=='')
					{
						alert('No existe datos seleccionados');
					}
					else
					{
						document.location.href=urlexportardatos+'comprobante/Exportar_ExcelGeneral?param1='+txt_RucEmpresa+'&param2='+txt_DocumentoCliente+'&param3='+txt_serienumeroinicio+'&param4='+txt_serienumerofinal+'&param5='+Cmb_EstadoDocumento+'&param6='+txt_FechaEmisionInicio+'&param7='+txt_FechaEmisionFinal+'&param8='+Cmb_TipoDocumentoSunat+'&param9='+Cmb_EstadoDocumentoSunat+'&param10='+txt_datosseleccionados+'&param11='+txt_RazonSocialCliente+'&param12='+Cmb_TipoMoneda;
					}
				}
			}

			function Seleccionar_DatosBusqueda(key,tipodocumento,serienumero,estado_doc,estado_sunat)
			{
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
				var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());	
				var txt_cantidaddocumborrador=$.trim($('#txt_cantidaddocumborrador').val());
				
				//YA ESTA CREADO PARA EL CONTROL DE LOS REINICIOS
				var txt_datosseleccionados_estado=$.trim($('#txt_datosseleccionados_estado').val());	
				var txt_cantidaddocum_estado=$.trim($('#txt_cantidaddocum_estado').val());	

				
				if ($("#cbox_seleccion_"+key).is(":checked"))
				{	
					if (txt_datosseleccionados=='')
					{
						txt_datosseleccionados=tipodocumento+'-'+serienumero;
					}
					else
					{
						txt_datosseleccionados=txt_datosseleccionados+','+tipodocumento+'-'+serienumero;
					}
					txt_cantidadseleccionados++;
					
					var temporal=0;
					var resumenid='';
					

					if (estado_doc!='L')// || estado_sunat=='SIGNED'
					{
						resumenid=tipodocumento+'-'+serienumero+'-'+estado_doc;
						temporal=1;
					}
					else if (estado_sunat=='SIGNED')
					{
						resumenid=tipodocumento+'-'+serienumero+'-'+estado_sunat;
						temporal=1;
					}

					if (temporal==1)
					{
						txt_cantidaddocum_estado++;
						if (txt_datosseleccionados_estado=='')
						{
							txt_datosseleccionados_estado=resumenid;
						}
						else
						{
							txt_datosseleccionados_estado=txt_datosseleccionados_estado+','+resumenid;
						}	
					}	
					
					if (estado_doc=='B' || estado_doc=='E' || estado_sunat=='SIGNED')//PARA EL CONTROL DE EDITAR
					{
						txt_cantidaddocumborrador++;
					}
					
					
								
				}	
				else
				{
					txt_datosseleccionados=txt_datosseleccionados.replace(","+tipodocumento+'-'+serienumero, ""); 
					txt_datosseleccionados=txt_datosseleccionados.replace(tipodocumento+'-'+serienumero, ""); 
					txt_cantidadseleccionados--;
					var temporal=0;
					var resumenid='';
					if (estado_doc!='L')// || estado_sunat=='SIGNED'
					{
						resumenid=tipodocumento+'-'+serienumero+'-'+estado_doc;
						temporal=1;
					}
					else if (estado_sunat=='SIGNED')
					{
						resumenid=tipodocumento+'-'+serienumero+'-'+estado_sunat;
						temporal=1;
					}
					
					if (temporal==1)
					{
						txt_cantidaddocum_estado--;
						txt_datosseleccionados_estado=txt_datosseleccionados_estado.replace(","+resumenid, ""); 
						txt_datosseleccionados_estado=txt_datosseleccionados_estado.replace(resumenid, ""); 
					}
					
					if (estado_doc=='B' || estado_doc=='E' || estado_sunat=='SIGNED')//PARA EL CONTROL DE EDITAR
					{
						txt_cantidaddocumborrador--;
					}
				}
				$('#txt_datosseleccionados').val($.trim(txt_datosseleccionados));
				$('#txt_cantidadseleccionados').val($.trim(txt_cantidadseleccionados));
				$('#txt_cantidaddocumborrador').val($.trim(txt_cantidaddocumborrador));
				
				$('#txt_datosseleccionados_estado').val($.trim(txt_datosseleccionados_estado));
				$('#txt_cantidaddocum_estado').val(txt_cantidaddocum_estado);
				
			}


			
			function Imprimir_DocumentoSeleccionado()
			{
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
				var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());
				var txt_rucemisor=$.trim($('#txt_RucEmpresa').val());
				if (txt_cantidadseleccionados>0)
				{
					$.ajax({
							url:'<?php echo base_url()?>comprobante/Comprobar_DocumentoImprimir',
							type: 'post',
							dataType: 'json',
							data:
							{
								param1:txt_datosseleccionados,
								param2:txt_rucemisor
							},
							beforeSend:function()
							{								
							},
							success:function(result)
							{
								if(result.status==1)
								{
									document.location.href=urlexportardatos+'comprobante/Imprimir_DocumentoSeleccionado?param1='+txt_datosseleccionados+'&param2='+txt_rucemisor;
								}
								else if(result.status==2)
								{
									alert("No existen los archivos seleccionados");
								}
								else if (result.status==1000)
								{
									document.location.href= '<?php echo base_url()?>usuario';
									return;
								}
								else
								{
									alert("Error al crear los archivos seleccionados");
								}
							}
						});	
					
					
				}
				else
				{
					alert("Debe seleccionar al menos un registro");
				}
			}
			
			function Descargar_DocumentoSeleccionado()
			{
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
				var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());	
				var txt_rucemisor=$.trim($('#txt_RucEmpresa').val());
				
				if (txt_cantidadseleccionados>0)
				{
				
					$.ajax({
							url:'<?php echo base_url()?>comprobante/Crear_ArchivosDocumentoSeleccionado',
							type: 'post',
							dataType: 'json',
							data:
							{
								param1:txt_datosseleccionados,
								param2:txt_rucemisor
							},
							beforeSend:function()
							{
								
							},
							success:function(result)
							{
								if(result.status==1)
								{
									Descargar_DocumentoSeleccionadoArchivos();
								}
								else if(result.status==2)
								{
									alert("No se encontraron archivos para los documentos seleccionados");
								}
								else if (result.status==1000)
								{
									document.location.href= '<?php echo base_url()?>usuario';
									return;
								}
								else
								{
									alert("Error al crear los archivos seleccionados");
								}
							}
						});						
					 //setTimeout('Descargar_DocumentoSeleccionadoArchivos()',4000)
				}
				else
				{
					alert("Debe seleccionar al menos un registro");
				}
			}
			
			function Descargar_DocumentoSeleccionadoArchivos()
			{
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
				var txt_rucemisor=$.trim($('#txt_RucEmpresa').val());

				document.location.href=urlexportardatos+'comprobante/Descargar_DocumentoSeleccionado?param1='+txt_datosseleccionados+'&param2='+txt_rucemisor;	
			}
			
			function Modificar_DocumentoSeleccionado()
			{
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
				var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());
				var txt_cantidaddocumborrador=$.trim($('#txt_cantidaddocumborrador').val());
				var txt_tipo_confserie=$('#txt_tipo_confserie').val();
				
				if (txt_cantidadseleccionados>1)
				{
					//document.location.href=urlexportardatos+'/comprobante/Descargar_DocumentoSeleccionado?param1='+txt_datosseleccionados;
					alert("Existe más de un documento seleccionado");
					return;
				}
				else 
				  if (txt_cantidadseleccionados==0)
				  {
					alert("No Existe documento seleccionado");
					return;
				  }
				  else 
				    if (txt_cantidadseleccionados==1)
					{
						if (txt_cantidaddocumborrador==txt_cantidadseleccionados)
						{
							txt_datosseleccionados = txt_datosseleccionados.replace(",", ""); 
							var cod_tipodocumento='';
							cod_tipodocumento=txt_datosseleccionados.substring(0, 2); 
							
							var serie_doc=txt_datosseleccionados.substring(3, 7);
							
							
							if (txt_tipo_confserie==1)
							{
								document.location.href=urlexportardatos+'comprobante?param1='+txt_datosseleccionados;
								return;
							}else
							{
								contador=0;
								//tipo_config=0;
								$.ajax({
									url:'<?php echo base_url()?>catalogos/Listar_SeriesDocumentos',
									type: 'post',
									dataType: 'json',
									data:
									{
										cod_tipodocumento:cod_tipodocumento
									},
									beforeSend:function()
									{	},
									success:function(result)
									{
										if(result.status==1)
										{
											$.each(result.data,function(key,rs)
											{
													if (serie_doc==rs.ser_doc)
													{
														contador=contador+1;
													}
											});	
											if (contador==0)
											{
												alert('CASO1: No tienes permiso a editar este comprobante');
												return;
											}else
											{
												document.location.href=urlexportardatos+'comprobante?param1='+txt_datosseleccionados;
												return;
											}
										}
										else if (result.status==1000)
										{
											document.location.href= '<?php echo base_url()?>usuario';
											return;
										}
										else
										{
											alert('No tienes permiso a editar este comprobante.');
											return;
										}
									}
								});
							}
							
							//alert(txt_datosseleccionados);						
							//document.location.href=urlexportardatos+'comprobante?param1='+txt_datosseleccionados;
						}
						else
						{
							alert("No es posible editar el documento seleccionado.");
						}					
					}
			}
			
			function DeclararEnviar_DocumentoSeleccionado()
			{
				var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
				var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());	
				var txt_cantidaddocumborrador=$.trim($('#txt_cantidaddocumborrador').val());
				
				if (txt_cantidadseleccionados>0)
				{
					//document.location.href=urlexportardatos+'/comprobante/Descargar_DocumentoSeleccionado?param1='+txt_datosseleccionados;
					if (txt_cantidaddocumborrador==txt_cantidadseleccionados)
					{
						if(confirm("¿ Esta Seguro de enviar a declarar a  "+txt_cantidaddocumborrador+" Documentos ?"))
						{
							$.ajax({
								url:'<?php echo base_url()?>comprobante/Cambiar_EstadoBorradorAdeclarar',
								type: 'post',
								dataType: 'json',
								data:
								{
									txt_RucEmpresa:txt_RucEmpresa,
									txt_datosseleccionados:txt_datosseleccionados
								},
								beforeSend:function()
								{
								},
								success:function(result)
								{
									if(result.status==1)
									{
										ncsistema.Listar_Comprobantes();
										alert("La actualizacion de los estado se realizo con éxito");									
									}
									else if (result.status==1000)
									{
										document.location.href= '<?php echo base_url()?>usuario';
										return;
									}
									else
									{
										//ncsistema.Detalle_ComprobanteTabla("");
										alert("Error en el procesos");
									}
								}
							});	
						}	
					}
					else
					{
						alert("Los documentos seleccionados no tienen estado BORRADOR");
					}
				}
				else
				{
					alert("Debe seleccionar al menos un Documento");
				}
			}
			
			function ReiniciarCorrelativo_DocumentoSeleccionado()
			{
				var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
				var txt_datosseleccionados_estado=$.trim($('#txt_datosseleccionados_estado').val());	
				var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());	
				var txt_cantidaddocum_estado=$.trim($('#txt_cantidaddocum_estado').val());
				
				if (txt_cantidadseleccionados>0)
				{
					if (txt_cantidadseleccionados==txt_cantidaddocum_estado)
					{
						if(confirm("¿ Esta Seguro de reiniciar correlativos a  "+txt_cantidaddocum_estado+" Documentos ?"))
						{
							$.ajax({
								url:'<?php echo base_url()?>comprobante/Reiniciar_Correlativos',
								type: 'post',
								dataType: 'json',
								data:
								{
									txt_RucEmpresa:txt_RucEmpresa,
									txt_datosseleccionados_estado:txt_datosseleccionados_estado
								},
								beforeSend:function()
								{
								},
								success:function(result)
								{
									if(result.status==1)
									{
										ncsistema.Listar_Comprobantes();
										alert("La actualizacion de los datos se realizo con éxito");									
									}
									else if (result.status==1000)
									{
										document.location.href= '<?php echo base_url()?>usuario';
										return;
									}
									else
									{
										//ncsistema.Detalle_ComprobanteTabla("");
										alert("Error en el procesos");
									}
								}
							});	
						}	
					}
					else
					{
						alert("Los documentos seleccionados no tiene estados Semejantes");
					}
				}
				else
				{
					alert("Debe seleccionar al menos un Documento");
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
			
			
			function Listar_DocumentoSunat(cod_tipodocumento)
			{
				$('#div_Estadodocumentosunat').empty().append('');
				if (cod_tipodocumento=='0')
				{
					newHtml='';
					newHtml+='<select id="Cmb_EstadoDocumentoSunat" style="width:98%;height:25px" >';
					newHtml+='<option value="0">TODOS</option>';
					newHtml+='</select>';		
					$('#div_Estadodocumentosunat').empty().append(newHtml);	
				}
				else
				{
					$.ajax({
						url:'<?php echo base_url()?>catalogos/Listar_EstadoDocumentoSunat',
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
								newHtml+='<select id="Cmb_EstadoDocumentoSunat" style="width:98%;height:25px" >';
								newHtml+='<option value="0">TODOS</option>';								
								$.each(result.data,function(key,rs)
								{
									newHtml+='<option value="'+rs.co_item_tabla+'">'+rs.no_largo+'</option>';
								});	
								newHtml+='</select>';		
								$('#div_Estadodocumentosunat').empty().append(newHtml);	
	
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								newHtml='';
								newHtml+='<select id="Cmb_EstadoDocumentoSunat" style="width:98%;height:25px" >';
								newHtml+='<option value="0">TODOS</option>';
								newHtml+='</select>';		
								$('#div_Estadodocumentosunat').empty().append(newHtml);	
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
				<li><a href="#tabs-1">LISTADO DE COMPROBANTES</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">			
				<div id="div_datosempresa" style="width:100%; float:left; margin-top:10px; border: 1px solid #a6c9e2; border-radius:5px;">
					<table border="0" width="70%" style="border-collapse:separate; border-spacing:1px 1px;" cellpadding="3" class="tablaFormulario">
						<tr><td><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right;width:15%" ><label class="columna">RUC :</label></td>
							<td style="text-align:left;width:20%">
								<input type="hidden" id="txt_tipo_confserie"  value="<?php echo $Tipo_confserie;?>" />
								<input style="width:95%" type="text" id="txt_RucEmpresa" value="<?php echo trim(utf8_decode($Ruc_Empresa));?>"  disabled="disabled" />
							</td>
							<td style="text-align:right;width:15%"><label class="columna">Raz&oacute;n Social :</label></td>
							<td style="text-align:left;width:48%" colspan="3">
								<input style="width:95%" type="text" id="txt_RazonSocialEmpresa" value="<?php echo trim(utf8_decode($Razon_Social));?>" disabled="disabled" /></td>
													
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Cliente :</label></td>
							<td style="text-align:left">
								<input style="width:95%" id="txt_RazonSocialCliente" type="text" value="" placeholder="Buscar Cliente por Raz. Social" />
								<input style="width:95%" id="txt_DocumentoCliente" type="hidden" value="" />
							</td>							
							<td style="text-align:right"><label class="columna">Serie-Num. :</label></td>
							<td style="text-align:left">
								<input style="width:70%" id="txt_serienumeroinicio" type="text" value="" maxlength="13"/>
							</td>
							<td style="text-align:right"><label class="columna">al :</label></td>							
							<td style="text-align:left">
								<input style="width:70%" id="txt_serienumerofinal" type="text" value="" maxlength="13" />
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Tipo Documento :</label></td>
							<td style="text-align:left">
								<select id="Cmb_TipoDocumentoSunat" style="width:98%;height:25px" onChange="javascript:Listar_DocumentoSunat(this.value)" >
									<option value="0">TODOS</option>
									<?php foreach ( $Listar_TipodeDocumento as $v):	?>
										<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(utf8_decode($v['no_corto']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>

							<td style="text-align:right"><label class="columna">Fec.Emisi&oacute;n :</label></td>
							<td style="text-align:left">
								<input style="width:70%; text-align:center" id="txt_FechaEmisionInicio" type="text" value="" disabled="disabled" title="Desde (##/##/####)" />
							</td>
							<td style="text-align:right"><label class="columna">al :</label></td>	
							<td style="text-align:left">
								<input style="width:70%; text-align:center" id="txt_FechaEmisionFinal" type="text" value="" disabled="disabled" title="Hasta (##/##/####)" />
							</td>
						</tr>
						<tr>

							<td style="text-align:right"><label class="columna">Estado Doc. :</label></td>
							<td style="text-align:left">
								<select id="Cmb_EstadoDocumento" style="width:98%;height:25px" >
									<option value="0">TODOS</option>
									<?php foreach ( $Listar_EstadoDocumento as $v):	?>
										<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(utf8_decode(strtoupper($v['no_corto'])));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
							<td style="text-align:right"><label class="columna">Estado Sunat :</label></td>
							<td style="text-align:left">
								<div id="div_Estadodocumentosunat">
									<select id="Cmb_EstadoDocumentoSunat" style="width:98%;height:25px" >
										<option value="0">TODOS</option>																		
									</select>
								</div>
							</td>
							<td style="text-align:right"><label class="columna">Moneda :</label></td>	
							<td style="text-align:left">
								<select id="Cmb_TipoMoneda" style="width:98%;height:25px">
									<option value="0">TODOS</option>
									<?php foreach ( $Listar_Monedas as $v):	?>
										<option value="<?php echo trim($v['codigo']); ?>"><?php echo strtoupper(utf8_decode($v['nombre']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td style="text-align:left;width:10%" colspan="5">
								<table width="100%" border="0">
								  <tr>
									<td  width="10%">
										<a href="javascript:ncsistema.Listar_Comprobantes()" >
											<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
												<span class="ui-button-icon-left ui-icon ui-icon-search"></span>
												<span class="ui-button-text">Buscar</span></button>
										</a>
									</td>
									<td>
										<a href="javascript:Limpiar_Busqueda()" >
												<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
													<span class="ui-button-icon-left ui-icon ui-icon-document-b"></span>
													<span class="ui-button-text">Limpiar</span></button>
											</a>
									</td>
									<td width="80%">
										<div style="width:40px; height:15px" id="div_procesarbuscar">  </div>
									</td>
								  </tr>
								</table>
							</td>
						</tr>
					</table>
				</div>	
			<div style="width:100%;border:solid 1px;float:left;margin-top:10px; border: 1px solid #a6c9e2;border-radius:5px;">	
				<table width="100%"  border="0px" class="ui-widget-header">
					<input id="txt_datosseleccionados_estado" type="hidden" value="" />
					<input id="txt_datosseleccionados" type="hidden" value="" />
					<input id="txt_cantidadseleccionados" type="hidden" value="0" />
					<input id="txt_cantidaddocumborrador" type="hidden" value="0" />
					<input id="txt_cantidaddocum_estado" type="hidden" value="0" />
					<input id="txt_filtrobusqueda" type="hidden" value="" />
					<input id="txt_botonbusqueda" type="hidden" value="0" />
					<tr">
						<td style="text-align:center; vertical-align:middle ;width:5%">
							<a href="javascript:ncsistema.ExportarExcel_General()" >
								<img src="<?php echo base_url();?>application/helpers/image/iconos/exportarexcel.jpg" width="30" height="30" />
							</a>
						</td>
						<td style="text-align:left; width:35%; vertical-align:middle">
							<select id="Cmb_OpcionesExportarExcel" style="height:25px" >
								<option value="1">LISTADO COMPLETO</option>
								<option value="2">LISTADO SELECCIONADO</option>
							</select>
						</td>
						<td align="right" style="width:60%; vertical-align:middle">
								<!--<div id="div_OpcionesBotones">
									<a href="javascript:Limpiar_Busqueda()" >
										<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
											<!--<span class="ui-button-icon-left ui-icon ui-icon-search"></span>
											<span class="ui-button-text">Limpiar</span></button>
									</a>
								</div>-->
							<button style="width:125px; height:32px" id="btn_Verdetalle" title="Ver detalle del documento" 
								class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" 
								onClick="javascrip:ncsistema.Detalle_Comprobante()">
								<span class="ui-button-icon-left ui-icon ui-icon-newwin"></span>
								<span class="ui-button-text">Ver Detalle</span></button>
							<button style="width:120px; height:32px" id="btn_ImprimirPrincipal" title="Imprimir" 
								class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left"
								onClick="javascrip:Imprimir_DocumentoSeleccionado()">
								<span class="ui-button-icon-left ui-icon ui-icon-print"></span>
								<span class="ui-button-text">Imprimir</span></button>
							<button style="width:120px; height:32px"  id="btn_DescargarPrincipal" title="Descargar" 
								class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left"
								onClick="javascrip:Descargar_DocumentoSeleccionado()">
								<span class="ui-button-icon-left ui-icon ui-icon-disk"></span>
								<span class="ui-button-text">Descargar</span></button>
							<button style="width:120px; height:32px" id="btn_ModificarDatosComprobante" title="Editar"
								class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" 
								onClick="javascrip:Modificar_DocumentoSeleccionado()">
								<span class="ui-button-icon-left ui-icon ui-icon-pencil"></span>
								<span class="ui-button-text">Editar</span></button>
							<button style="width:155px; height:32px" id="btn_EnviarDeclarar" title="Enviar y Declarar" 
								class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left"
								onClick="javascrip:DeclararEnviar_DocumentoSeleccionado()">
								<span class="ui-button-icon-left ui-icon ui-icon-transfer-e-w"></span>
								<span class="ui-button-text">Enviar y Declarar</span></button>
						</td>	
						
						<!--<td style="text-align:left; vertical-align:middle">
							
							<button style="width:120px; height:32px" id="btn_EnviarDeclarar" title="Iniciar Sesión" 
								class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left"
								onClick="javascrip:ReiniciarCorrelativo_DocumentoSeleccionado()">
								<span class="ui-button-icon-left ui-icon ui-icon-transfer-e-w"></span>
								<span class="ui-button-text">Reiniciar</span></button>
							
						</td>-->

					</tr>
				</table>
				</div>
				<div id="div_ListadoEmpresa" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:5px">
				</div>
			</div>
		</div>
		
		<div id="dialog-form-detalledocumento" title="Detalle del Comprobante">
		 	<form>
			<div> 
				<div>
					<table width="100%" border="1">
						<tr>
							<td style="width:70%">
								<table width="100%" border="0">
									<tr>
										<td><label class="columna"><div id="div_param1"></div></label></td>
									</tr>
									<tr>
										<td><div id="div_param2"></div></td>
									</tr>
									<tr>
										<td><div id="div_param3"></div></td>
									</tr>
									<tr>
										<td><div id="div_param4"></div></td>
									</tr>
								</table>
							</td>
							
							<td style="width:30%;text-align:center">
								<table width="100%" border="0">
									<tr>
										<td><label class="columna"><div id="div_param5"></div></label></td>
									</tr>
									<tr>
										<td><div id="div_param6"></div></td>
									</tr>
									<tr>
										<td><label class="columna"><div id="div_param7"></div></label></td>
									</tr>
									<tr>
										<td><div id="div_param8"></div></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<table width="100%" border="0">
									<tr>
										<td style="width:15%"><label class="columna">Nombre/Razón Social:</label></td>
										<td style="width:30%"><div id="div_param9"></div></td>
										<td style="width:7%"><label class="columna">RUC:</label></td>
										<td style="width:20%"><div id="div_param10"></div></td>
										<td style="width:13%"><label class="columna">Fecha Emisión:</label></td>
										<td style="width:15%"><div id="div_param11"></div></td>
									</tr>
									<tr>
										<td><label class="columna">Dirección:</label></td>
										<td><div id="div_param12"></div></td>
										<td><label class="columna">Moneda:</label></td>
										<td><div id="div_param13"></div></td>
										<td><label class="columna">Orden de Compra:</label></td>
										<td><div id="div_param14"></div></td>
									</tr>
								</table>
							</td>
						</tr>
						
						
					</table>
				</div>
				<div> 
					<div id="div_documentodetalle"></div>
				</div>
				<div> 
					<table width="100%">
						<tr>
							<td colspan="2">
								<table width="100%" border="0" >
									<tr>
										<td style="width:60%"><div id="div_param15"></div></td>
										<td style="width:40%">										
											<table width="100%" border="1">
												<tr id="row1">
													<td style="width:40%;text-align:right"><label class="columna">Operacion Gravadas :</label></td>
													<td style="width:20%;text-align:center"><div id="div_param16"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param17"></div></td>
												</tr>

												<tr id="row2">
													<td style="width:40%;text-align:right"><label class="columna">Operacion Inafectos :</label></td>
													<td style="width:20%;text-align:center"><div id="div_param18"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param19"></div></td>
												</tr>
												<tr id="row3">
													<td style="width:40%;text-align:right"><label class="columna">Operacion Exoneradas : </label></td>
													<td style="width:20%;text-align:center"><div id="div_param20"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param21"></div></td>
												</tr>
												
												<tr id="row4">
													<td style="width:40%;text-align:right"><label class="columna">Operacion Gratuitas : 	 </label></td>
													<td style="width:20%;text-align:center"><div id="div_param22"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param23"></div></td>
												</tr>
												<tr id="row5">
													<td style="width:40%;text-align:right"><label class="columna">Descuentos : </label></td>
													<td style="width:20%;text-align:center"><div id="div_param24"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param25"></div></td>
												</tr>
												<tr id="row6">
													<td style="width:40%;text-align:right"><label class="columna">I.S.C 0% : </label></td>
													<td style="width:20%;text-align:center"><div id="div_param26"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param27"></div></td>
												</tr>
												<tr id="row7">
													<td style="width:40%;text-align:right"><label class="columna">IGV 18% :  </label></td>
													<td style="width:20%;text-align:center"><div id="div_param28"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param29"></div></td>
												</tr>
												
												<tr id="row8">
													<td style="width:40%;text-align:right"><label class="columna">Otros Cargos :</label></td>
													<td style="width:20%;text-align:center"><div id="div_param30"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param31"></div></td>
												</tr>
												<tr id="row9">
													<td style="width:40%;text-align:right"><label class="columna">Importe Total :</label></td>
													<td style="width:20%;text-align:center"><div id="div_param32"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param33"></div></td>
												</tr>
											</table>
										
										</td>
									</tr>	
								</table>
							</td>
						</tr>
						
						
						<tr>
							<td colspan="2">
								<!-- Representación Impresa de la Factura Electrónica, consulte en https://sfe.bizlinks.com.pe -->
							</td>
						</tr>
					</table>
				</div>
			</div>
		  </form>
		</div>		
		
    </body>	
</html>