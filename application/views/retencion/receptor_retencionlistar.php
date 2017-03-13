<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Retención</title>
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
				ncsistema.Listar_RetencionesTabla('',0);	
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
				$('#txt_FechaEmisionInicio').datepicker('setDate', 'today -1');
				ncsistema.Buscar_Clientes();
			})
			
			//INICIO NCSISTEMA
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

				Listar_Retenciones:function()
				{
					var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
					var txt_RazonSocialCliente=$.trim($('#txt_RazonSocialCliente').val());
					var txt_DocumentoCliente=$.trim($('#txt_DocumentoCliente').val());
					var txt_serienumeroinicio=$.trim($('#txt_serienumeroinicio').val());
					var txt_serienumerofinal=$.trim($('#txt_serienumerofinal').val());				
					var Cmb_EstadoDocumento='';//$.trim($('#Cmb_EstadoDocumento').val());					
					var txt_FechaEmisionInicio=$.trim($('#txt_FechaEmisionInicio').val());
					var txt_FechaEmisionFinal=$.trim($('#txt_FechaEmisionFinal').val());					
					var Cmb_EstadoDocumentoSunat=$.trim($('#Cmb_EstadoDocumentoSunat').val());
					
					$('#txt_filtrobusqueda').val(txt_RucEmpresa+','+txt_DocumentoCliente+','+txt_RazonSocialCliente+','+txt_serienumeroinicio+','+txt_serienumerofinal+','+Cmb_EstadoDocumento+','+txt_FechaEmisionInicio+','+txt_FechaEmisionFinal+','+Cmb_EstadoDocumentoSunat);					
					$('#txt_botonbusqueda').val('1');

					ncsistema.Listar_RetencionesTabla("");
					
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
					$('#txt_datosseleccionados').val('');
					$('#txt_cantidadseleccionados').val('0');

					$('#txt_documentoemisor').val('');
					$('#txt_documentoemisordocumentos').val('');

					$.ajax({
						url:'<?php echo base_url()?>retencion/Listar_Retenciones',
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
							Cmb_EstadoDocumentoSunat:Cmb_EstadoDocumentoSunat,
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
								ncsistema.Listar_RetencionesTabla(result.data);								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								ncsistema.Listar_RetencionesTabla("");
							}
						}
					});					
				},				
				
				
				Listar_RetencionesTabla:function(data)
				{	
					$('#div_ListadoRetenciones').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaRetenciones">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th width:3%>Opci&oacute;n</td>';						
						newHtml+='<th width:10%>Proveedor</td>';						
						newHtml+='<th width:20%>SerieNúmero</td>';
						newHtml+='<th width:5%>Imp.Total a Pagar</td>';
						newHtml+='<th width:5%>Moneda a Pagar</td>';
						newHtml+='<th width:5%>Imp.Total Retenido</td>';
						newHtml+='<th width:5%>Moneda Retenido</td>';
						newHtml+='<th width:7%>Fec.Emisi&oacute;n</td>';
						newHtml+='<th width:7%>Est.Doc.</td>';
						newHtml+='<th width:8%>Est.SUNAT</td>';
						newHtml+='<th width:25%>Descripci&oacute;n</td>';
						newHtml+='<th width:5%>Visto</td>';
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					contador=0;
					$.each(data,function(key,rs)
					{
						newHtml+='<tr>';										
							newHtml+='<td style="text-align:center;color:#990000;font-weight:bold"><input id="cbox_seleccion_'+key+'" type="checkbox" value="" name="cbox_seleccion_'+key+'" onChange="javascrip:Seleccionar_DatosBusqueda('+key+',\''+rs.tipodocumento+'\',\''+rs.serienumeroretencion+'\',\''+rs.bl_estadoregistro+'\',\''+rs.estadosunat+'\',\''+rs.numerodocumentoemisor+'\')" ></td>';
							newHtml+='<td style="text-align:left">'+rs.razonsocialproveedor+'</td>';		
							newHtml+='<td style="text-align:center">'+rs.serienumeroretencion+'</td>';
							newHtml+='<td style="text-align:right">'+rs.importetotalpagado+'</td>';
							newHtml+='<td style="text-align:center">'+rs.tipomonedapagado+'</td>';	
							newHtml+='<td style="text-align:right">'+rs.importetotalretenido+'</td>';			
							newHtml+='<td style="text-align:center">'+rs.tipomonedaretenido+'</td>';
							newHtml+='<td style="text-align:center">'+rs.fechaemision+'</td>';
							newHtml+='<td style="text-align:left">'+rs.estado_documento+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nombreestadosunat+'</td>';
							newHtml+='<td style="text-align:left">'+rs.obssunat+'</td>';
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
					
					$('#div_ListadoRetenciones').empty().append(newHtml);	

					oTable=$('#Tab_ListaRetenciones').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_ListaRetenciones tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});
				},
				
				Detalle_Retenciones:function()
				{
					var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
					var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());					
					var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());					
					if (txt_cantidadseleccionados==1)
					{

						if(Validar_EmisoresDocumentos()==true)
						{
							var txt_RucEmpresa=$.trim($('#txt_documentoemisordocumentos').val());	
						}
						else
						{
							alert("Los documentos seleccionados no tiene el mismo emisor");
						}

						$.ajax({
							url:'<?php echo base_url()?>retencion/Listar_DetalleDocumento',
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
									ncsistema.Detalle_RetencionesTabla(result.data);
									
								}
								else if (result.status==1000)
								{
									document.location.href= '<?php echo base_url()?>usuario';
									return;
								}
								else
								{
									ncsistema.Detalle_RetencionesTabla("");
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
				
				Detalle_RetencionesTabla:function(data)
				{	
					dialogdetallecomprobante.dialog("open");
					$('#div_documentodetalle').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_DetalleComprobanteTabla">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th width:3%>Item</td>';						
						newHtml+='<th width:5%>Tipo</td>';						
						newHtml+='<th width:12%>Documento</td>';
						newHtml+='<th width:10%>Fec. Emisión</td>';
						newHtml+='<th width:10%>Fec. Pago</td>';
						newHtml+='<th width:5%>Nro. Pago</td>';
						newHtml+='<th width:8%>Moneda Origen</td>';
						newHtml+='<th width:10%>Imp.Oper. Origen</td>';
						newHtml+='<th width:15%>Imp.Pago Sin Retención</td>';
						newHtml+='<th width:12%>Importe Retenido.S/.</td>';
						newHtml+='<th width:10%>Imp.Total Pagar.S/.</td>';
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
							$('#div_param4').empty().append(rs.departamentoemisor+' - '+rs.provinciaemisor+' - '+rs.distritoemisor+rs.urbanizacionemisor);	
							
							$('#div_param5').empty().append(rs.nombre_tipodocumentoemisor);	
							$('#div_param6').empty().append(rs.numerodocumentoemisor);	
							$('#div_param7').empty().append(rs.nombre_tipodocumento+' ELECTRONICA');	
							$('#div_param8').empty().append(rs.serienumero);	
							
							$('#div_param9').empty().append(rs.razonsocialadquiriente);	
							$('#div_param10').empty().append(rs.numerodocumentoadquiriente);	
							$('#div_param11').empty().append(rs.fechaemision);	
							$('#div_param12').empty().append(rs.direccioncliente);	
							$('#div_param13').empty().append(rs.tasaretencion+'%');	
							
							//OcultarFilaPassword('row1',0);
							//OcultarFilaPassword('row2',0);
							
							if (rs.importetotalretenido>0)
							{
								//OcultarFilaPassword('row1',1);
								$('#div_param16').empty().append(rs.tipomonedaretenido);
								$('#div_param17').empty().append(rs.importetotalretenido);
							}
							if (rs.importetotalpagado>0)
							{
								//OcultarFilaPassword('row2',1);
								$('#div_param18').empty().append(rs.tipomonedapagado);
								$('#div_param19').empty().append(rs.importetotalpagado);
							}
						}
						newHtml+='<tr>';															
							newHtml+='<td style="text-align:left">'+rs.numeroordenitem+'</td>';		
							newHtml+='<td style="text-align:left">'+rs.nomb_tipodocumento+'</td>';
							newHtml+='<td style="text-align:left">'+rs.numerodocumentorelacionado+'</td>';
							newHtml+='<td style="text-align:center">'+rs.fechaemisiondocumentorelaciona+'</td>';	
							newHtml+='<td style="text-align:center">'+rs.fechapago+'</td>';			
							newHtml+='<td style="text-align:center">'+rs.numeropago+'</td>';
							newHtml+='<td style="text-align:center">'+rs.tipomonedarelacionado+'</td>';
							newHtml+='<td style="text-align:right">'+rs.importetotaldocumentorelaciona+'</td>';
							newHtml+='<td style="text-align:right">'+rs.importepagosinretencion+'</td>';
							newHtml+='<td style="text-align:right">'+rs.importeretenido+'</td>';
							newHtml+='<td style="text-align:right">'+rs.importetotalpagarneto+'</td>';
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
				
				ExportarExcel_General:function()
				{
					Descargar_ExcelGeneral();
				},
				
			}//FIN NCSISTEMA
			
			//INICIO FUNCIONES
			$(function() 
			{
				function Imprimir_DocumentoSeleccionadoDetalle() 
				{
					var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
					var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());	
					var txt_rucemisor=$.trim($('#txt_RucEmpresa').val());

					if (txt_cantidadseleccionados>0)
					{
						$.ajax({
							url:'<?php echo base_url()?>retencion/Comprobar_DocumentoImprimir',
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
									document.location.href=urlexportardatos+'retencion/Imprimir_DocumentoSeleccionado?param1='+txt_datosseleccionados+'&param2='+txt_rucemisor;
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
								url:'<?php echo base_url()?>retencion/Crear_ArchivosDocumentoSeleccionado',
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
					}
					else
					{
						alert("Debe seleccionar al menos un registro");
					}
				}
			
				dialogdetallecomprobante = $("#dialog-form-detalledocumento").dialog({
					autoOpen: false,
					height: 650,
					width: 950,
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
					}
				});
			});
						
			function Limpiar_Busqueda()
			{
				$('#txt_DocumentoCliente').val('');
				$('#txt_RazonSocialCliente').val('');
				$('#txt_serienumeroinicio').val('');
				$('#txt_serienumerofinal').val('');	
				$('#Cmb_EstadoDocumentoSunat').val('0');
				$('#txt_filtrobusqueda').val('');					
				$('#txt_botonbusqueda').val('0');
				
				$('#txt_FechaEmisionInicio').datepicker('setDate', 'today -1');
				$('#txt_FechaEmisionFinal').datepicker('setDate', new Date());
				
				ncsistema.Listar_RetencionesTabla('',0);	
			}

			function Seleccionar_DatosBusqueda(key,tipodocumento,serienumero,estado_doc,estado_sunat,numerodocumentoemisor)
			{
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
				var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());	
				//var txt_cantidaddocumborrador=$.trim($('#txt_cantidaddocumborrador').val());
				//alert(serienumero);

				var txt_documentoemisor=$.trim($('#txt_documentoemisor').val());

				if ($("#cbox_seleccion_"+key).is(":checked"))
				{	
					if (txt_datosseleccionados=='')
					{
						txt_datosseleccionados=tipodocumento+'-'+serienumero;
						txt_documentoemisor=numerodocumentoemisor;
					}
					else
					{
						txt_datosseleccionados=txt_datosseleccionados+','+tipodocumento+'-'+serienumero;
						txt_documentoemisor=txt_documentoemisor+','+numerodocumentoemisor;
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
						}else
						{
							txt_datosseleccionados_estado=txt_datosseleccionados_estado+','+resumenid;
						}	
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
				}
				$('#txt_datosseleccionados').val($.trim(txt_datosseleccionados));
				$('#txt_cantidadseleccionados').val($.trim(txt_cantidadseleccionados));
				$('#txt_documentoemisor').val(txt_documentoemisor);
				
			}
			
			function Imprimir_DocumentoSeleccionado()
			{
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
				var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());
				var txt_rucemisor=$.trim($('#txt_RucEmpresa').val());
				if (txt_cantidadseleccionados>0)
				{
					$.ajax({
							url:'<?php echo base_url()?>retencion/Comprobar_DocumentoImprimir',
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
									document.location.href=urlexportardatos+'retencion/Imprimir_DocumentoSeleccionado?param1='+txt_datosseleccionados+'&param2='+txt_rucemisor;
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
							url:'<?php echo base_url()?>retencion/Crear_ArchivosDocumentoSeleccionado',
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
									alert("No se encontraron archivos para los documentos seleccionados.");
								}
								else if (result.status==1000)
								{
									document.location.href= '<?php echo base_url()?>usuario';
									return;
								}
								else
								{
									alert("Error al crear los archivos seleccionados.");
								}
							}
						});						
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

				document.location.href=urlexportardatos+'retencion/Descargar_DocumentoSeleccionado?param1='+txt_datosseleccionados+'&param2='+txt_rucemisor;	
			}
			
			function Descargar_ExcelGeneral()
			{
				var Cmb_OpcionesExportarExcel=$.trim($('#Cmb_OpcionesExportarExcel').val());	
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
				var Cmb_EstadoDocumentoSunat=resultado[8];
				//var Cmb_TipoDocumentoSunat=resultado[8];
				//var Cmb_TipoMoneda=resultado[10];
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());
				
				if (Cmb_OpcionesExportarExcel==1)
				{
					txt_datosseleccionados='';
					document.location.href=urlexportardatos+'retencion/Exportar_ExcelGeneral?param1='+txt_RucEmpresa+'&param2='+txt_DocumentoCliente+'&param3='+txt_serienumeroinicio+'&param4='+txt_serienumerofinal+'&param5='+Cmb_EstadoDocumento+'&param6='+txt_FechaEmisionInicio+'&param7='+txt_FechaEmisionFinal+'&param8='+Cmb_EstadoDocumentoSunat+'&param9='+txt_datosseleccionados+'&param10='+txt_RazonSocialCliente;
				}else
				{
					if (txt_datosseleccionados=='')
					{
						alert('No existe datos seleccionados');
					}else
					{
						document.location.href=urlexportardatos+'retencion/Exportar_ExcelGeneral?param1='+txt_RucEmpresa+'&param2='+txt_DocumentoCliente+'&param3='+txt_serienumeroinicio+'&param4='+txt_serienumerofinal+'&param5='+Cmb_EstadoDocumento+'&param6='+txt_FechaEmisionInicio+'&param7='+txt_FechaEmisionFinal+'&param8='+Cmb_EstadoDocumentoSunat+'&param9='+txt_datosseleccionados+'&param10='+txt_RazonSocialCliente;
					}
				}
			}

			function Validar_EmisoresDocumentos() 
			{
				var txt_documentoemisor=$.trim($('#txt_documentoemisor').val());
				var ListaPermisosAsignarAll = txt_documentoemisor.split(",");
				
				var doc_emisor='';
				$('#txt_documentoemisordocumentos').val('');
				
				$.each(ListaPermisosAsignarAll,function(key,rs)
				{
					//alert(rs);
					if (key==0)
					{
						doc_emisor=rs;
					}
					else
					{
						if (doc_emisor!=rs)
						{
							return false;
						}
					}
				});	
				
				$('#txt_documentoemisordocumentos').val(doc_emisor);

				return true
			}
		</script>
		
    </head>   
    <body>
		<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">LISTADO DE RETENCIONES</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">			
				<div id="div_datosempresa" style="width:100%; float:left; margin-top:10px; border: 1px solid #a6c9e2; border-radius:5px;">
					<table border="0" width="70%" style="border-collapse:separate; border-spacing:1px 1px;" cellpadding="3" class="tablaFormulario">
						<tr><td><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right;width:15%" ><label class="columna">RUC :</label></td>
							<td style="text-align:left;width:20%">
								<!--<input type="hidden" id="txt_tipo_confserie"  value="<?php echo $Tipo_confserie;?>" />-->
								<input style="width:95%" type="text" id="txt_RucEmpresa" value="<?php echo trim(utf8_decode($Ruc_Empresa));?>"  disabled="disabled" />
							</td>
							<td style="text-align:right;width:15%"><label class="columna">Raz&oacute;n Social :</label></td>
							<td style="text-align:left;width:48%" colspan="3">
								<input style="width:95%" type="text" id="txt_RazonSocialEmpresa" value="<?php echo trim(utf8_decode($Razon_Social));?>" disabled="disabled" /></td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Emisor :</label></td>
							<td style="text-align:left">
								<input style="width:95%" id="txt_RazonSocialCliente" type="text" value="" placeholder="Buscar Emisor por Raz. Social" />
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
							<td style="text-align:right"><label class="columna">Estado Sunat :</label></td>
							<td style="text-align:left">
								<div id="div_Estadodocumentosunat">
									<select id="Cmb_EstadoDocumentoSunat" style="width:98%;height:25px" >
										<option value="0">TODOS</option>	
											<?php foreach ( $Listar_EstadoSunatRetencion as $v):	?>
										<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(strtoupper(utf8_decode($v['no_largo'])));?> </option>
											<?php  endforeach; ?>																	
									</select>
								</div>
							</td>

							<td style="text-align:right"><label class="columna">Fec. Emisi&oacute;n :</label></td>
							<td style="text-align:left">
								<input style="width:70%; text-align:center" id="txt_FechaEmisionInicio" type="text" value="" disabled="disabled" title="Desde (##/##/####)" />
							</td>
							<td style="text-align:right"><label class="columna">al :</label></td>	
							<td style="text-align:left">
								<input style="width:70%; text-align:center" id="txt_FechaEmisionFinal" type="text" value="" disabled="disabled" title="Hasta (##/##/####)" />
							</td>
						</tr>
						<tr>
							<td></td>
							<td style="text-align:left;width:10%" colspan="5">
								<table width="100%" border="0">
								  <tr>
									<td  width="10%">
										<a href="javascript:ncsistema.Listar_Retenciones()" >
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
					<input id="txt_cantidaddocumborrador" type="hidden" value="0" />
					<input id="txt_cantidaddocum_estado" type="hidden" value="0" />
					
					<input id="txt_datosseleccionados" type="hidden" value="" />
					<input id="txt_cantidadseleccionados" type="hidden" value="0" />

					<input id="txt_documentoemisor" type="hidden" value="" />
					<input id="txt_documentoemisordocumentos" type="hidden" value="" />

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
							<button style="width:125px; height:32px" id="btn_Verdetalle" title="Ver detalle del documento" 
								class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" 
								onClick="javascrip:ncsistema.Detalle_Retenciones()">
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
						</td>	
					</tr>
				</table>
				</div>
				<div id="div_ListadoRetenciones" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:5px">
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
										<td style="width:15%;"><label class="columna">Nombre/Razón Social:</label></td>
										<td style="width:30%"><div id="div_param9"></div></td>
										<td style="width:7%; text-align:right"><label class="columna">RUC :</label></td>
										<td style="width:20%"><div id="div_param10"></div></td>
										<td style="width:13%; text-align:right"><label class="columna">Fecha Emisión :</label></td>
										<td style="width:15%"><div id="div_param11"></div></td>
									</tr>
									<tr>
										<td ><label class="columna">Dirección:</label></td>
										<td><div id="div_param12"></div></td>
										<td style="text-align:right"><label class="columna">Tasa :</label></td>
										<td colspan="3"><div id="div_param13"></div></td>
										
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
								<table width="100%" border="0">
									<tr>
										<td style="width:60%"><div id="div_param15"></div></td>
										<td style="width:40%">										
											<table width="100%" border="1">
												<tr id="row1">
													<td style="width:50%;text-align:right"><label class="columna">Importe Total Retenido :</label></td>
													<td style="width:20%;text-align:center"><div id="div_param16"></div></td>
													<td style="width:30%;text-align:right"><div id="div_param17"></div></td>
												</tr>

												<tr id="row2">
													<td style="width:50%;text-align:right"><label class="columna">Importe Total Pagado :</label></td>
													<td style="width:20%;text-align:center"><div id="div_param18"></div></td>
													<td style="width:30%;text-align:right"><div id="div_param19"></div></td>
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