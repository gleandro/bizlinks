<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Resumen de Boletas</title>
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
				ncsistema.Listar_SummaryHeaderTabla('',0);	
				
				$('#txt_FechaGenInicio,#txt_FechaEmisionInicio').datepicker({
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
				
				$('#txt_FechaGenFinal,#txt_FechaEmisionFinal').datepicker({
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
				//$('#txt_FechaGenInicio,#txt_FechaGenFinal').datepicker('setDate', 'today');				
				
			})
			
			ncsistema=
			{

				Listar_SummaryHeader:function()
				{
					var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());					
					var txt_CodigoResumen=$.trim($('#txt_CodigoResumen').val());
					var txt_FechaGenInicio=$.trim($('#txt_FechaGenInicio').val());
					var txt_FechaGenFinal=$.trim($('#txt_FechaGenFinal').val());
					var Cmb_EstadoDocumento=$.trim($('#Cmb_EstadoDocumento').val());
					var txt_FechaEmisionInicio=$.trim($('#txt_FechaEmisionInicio').val());
					var txt_FechaEmisionFinal=$.trim($('#txt_FechaEmisionFinal').val());
					var Cmb_EstadoDocumentoSunat=$.trim($('#Cmb_EstadoDocumentoSunat').val());
					
					$('#txt_filtrobusqueda').val(txt_RucEmpresa+','+txt_CodigoResumen+','+txt_FechaGenInicio+','+txt_FechaGenFinal+','+Cmb_EstadoDocumento+','+txt_FechaEmisionInicio+','+txt_FechaEmisionFinal+','+Cmb_EstadoDocumentoSunat);					
					$('#txt_botonbusqueda').val('1');
					
					$.ajax({
						url:'<?php echo base_url()?>resumenboletas/Listar_SummaryHeader',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_RucEmpresa:txt_RucEmpresa,
							txt_CodigoResumen:txt_CodigoResumen,
							txt_FechaGenInicio:txt_FechaGenInicio,
							txt_FechaGenFinal:txt_FechaGenFinal,							
							Cmb_EstadoDocumento:Cmb_EstadoDocumento,						
							txt_FechaEmisionInicio:txt_FechaEmisionInicio,
							txt_FechaEmisionFinal:txt_FechaEmisionFinal,
							Cmb_EstadoDocumentoSunat:Cmb_EstadoDocumentoSunat
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
								ncsistema.Listar_SummaryHeaderTabla(result.data);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								ncsistema.Listar_SummaryHeaderTabla("");
							}
						}
					});					
				},				
				
				
				Listar_SummaryHeaderTabla:function(data,tipodocumento)
				{	
					$('#div_ListadoEmpresa').empty().append('');
					
					$('#txt_datosseleccionados').val('');
					$('#txt_cantidadseleccionados').val(0);
					
					$('#txt_datosseleccionados_estado').val('');
					$('#txt_cantidaddocum_estado').val(0);
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th width:3%>Opci&oacute;n</td>';						
						newHtml+='<th width:5%>Identificador</td>';						
						newHtml+='<th width:10%>F.Emisi&oacute;n</td>';
						newHtml+='<th width:10%>F.Generaci&oacute;n</td>';
						newHtml+='<th width:20%>Est.Doc.</td>';
						newHtml+='<th width:20%>Est.Sunat</td>';
						newHtml+='<th width:20%>Fec.Env.Sunat</td>';
						newHtml+='<th width:50%>Observaciones</td>';
						//newHtml+='<th width:50%>Reintentos</td>';
						newHtml+='<th width:10%>Detalle</td>';	
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					//<input style="height:20px;width:95%" id="txt_login" type="text" value="'+rs.cantidadproducto+'"/>
					contador=0;
					$.each(data,function(key,rs)
					{						
						newHtml+='<tr>';															

							newHtml+='<td style="text-align:center;color:#990000;font-weight:bold"><input id="cbox_seleccion_'+key+'" type="checkbox" value="" name="cbox_seleccion_'+key+'" onChange="javascrip:Seleccionar_DatosBusqueda('+key+',\''+rs.resumenid+'\',\''+rs.bl_estadoregistro+'\',\''+rs.estadosunat+'\')" ></td>';
							newHtml+='<td style="text-align:left">'+rs.resumenid+'</td>';		
							newHtml+='<td style="text-align:center">'+rs.fechaemisioncomprobante+'</td>';
							newHtml+='<td style="text-align:center">'+rs.fechageneracionresumen+'</td>';
							newHtml+='<td style="text-align:left">'+rs.estadoregistro+'</td>';				
							newHtml+='<td style="text-align:left">'+rs.nombreestadosunat+'</td>';
							newHtml+='<td style="text-align:left">'+rs.bl_fechaenviosunat+'</td>';
							newHtml+='<td style="text-align:left">'+rs.obssunat+'</td>';
							//newHtml+='<td style="text-align:center">'+rs.bl_reintento+'</td>';
							newHtml+='<td style="text-align:center"> <a href="javascript:Descargar_ExcelDetalle(\''+rs.resumenid+'\')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/ncexcel.png" title="Exportar a Excel" width="15"  height="15" border="0" ></a></td>';		

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
							url:'<?php echo base_url()?>resumenboletas/Listar_DetalleDocumento',
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
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_DetalleResumenBoletaTabla">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th width:3%>Tipo de Comprobante</td>';						
						newHtml+='<th width:5%>Serie Número</td>';						
						newHtml+='<th width:10%>Inicio Rango</td>';
						newHtml+='<th width:10%>Fin Rango</td>';
						newHtml+='<th width:20%>Moneda</td>';
						newHtml+='<th width:50%>OP. Gravadas</td>';
						newHtml+='<th width:50%>IGV</td>';
						newHtml+='<th width:50%>Monto Exonerado</td>';
						newHtml+='<th width:50%>Op.No Gravadas</td>';						
						newHtml+='<th width:50%>Op.Gratuita</td>';
						newHtml+='<th width:50%>Importe Total</td>';
						newHtml+='<th width:50%>Fecha Emision</td>';
						
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					$('#txt_codigoresumenvistaprevia').val('');
					
					$.each(data,function(key,rs)
					{		
									
						if (key==0)
						{		
							/*					
							$('#div_param1').empty().append(rs.razonsocialemisor);	
							$('#div_param2').empty().append(rs.nombrecomercialemisor);	
							$('#div_param3').empty().append(rs.direccionemisor);	
							$('#div_param4').empty().append(rs.departamentoemisor+' - '+rs.provinciaemisor+' - '+rs.distritoemisor);							
							$('#div_param5').empty().append(rs.nombre_tipodocumentoemisor);	
							$('#div_param6').empty().append(rs.numerodocumentoemisor);	
							$('#div_param7').empty().append(rs.nombre_tipodocumento+' ELECTRONICA');	
							$('#div_param8').empty().append(rs.serienumero);	
							*/
							$('#div_param9').empty().append(rs.razonsocialemisor);	
							$('#div_param10').empty().append(rs.numerodocumentoemisor);	
							$('#div_param11').empty().append(rs.fechageneracionresumen);
							$('#div_param12').empty().append(rs.resumenid);								
							$('#div_param13').empty().append(rs.fechaemisioncomprobante);	
							$('#div_param14').empty().append(rs.nombreestadosunat);
							/*
							
							$('#div_param15').empty().append(rs.textoleyenda_1);							
							*/		
							
							
							
							$('#txt_codigoresumenvistaprevia').val(rs.resumenid);
											
						}
						
						newHtml+='<tr>';															

							newHtml+='<td style="text-align:left">'+rs.nombre_tipodocumento+'</td>';		
							newHtml+='<td style="text-align:left">'+rs.seriegrupodocumento+'</td>';
							newHtml+='<td style="text-align:left">'+rs.numerocorrelativoinicio+'</td>';
							newHtml+='<td style="text-align:left">'+rs.numerocorrelativofin+'</td>';	
							newHtml+='<td style="text-align:right">'+rs.tipomonedadoc+'</td>';			
							newHtml+='<td style="text-align:center">'+rs.totalvalorventaopgravadaconigv+'</td>';
							newHtml+='<td style="text-align:left">'+rs.totaligv+'</td>';
							newHtml+='<td style="text-align:left">0.00</td>';
							newHtml+='<td style="text-align:left">'+rs.totalvalorventaopexoneradasigv+'</td>';
							newHtml+='<td style="text-align:left">'+rs.totalvalorventaopgratuitas+'</td>';
							newHtml+='<td style="text-align:left">'+rs.totalventa+'</td>';
							newHtml+='<td style="text-align:left">'+rs.fechaemisioncomprobante+'</td>';

						newHtml+='</tr>';						
					});	
					
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_documentodetalle').empty().append(newHtml);	

					oTable=$('#Tab_DetalleResumenBoletaTabla').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						//"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_DetalleResumenBoletaTabla tbody").click(function(event) 
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
						"Descargar": Descargar_ExcelDetalleVistaPrevia,
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
				$('#txt_CodigoResumen').val('');
				$('#txt_FechaGenInicio').val('');
				$('#txt_FechaGenFinal').val('');
				$('#txt_FechaEmisionInicio').val('');
				$('#txt_FechaEmisionFinal').val('');				
				
				$('#Cmb_EstadoDocumento').val('0');	
				$('#Cmb_EstadoDocumentoSunat').val('0');	
				
				$('#txt_filtrobusqueda').val('');					
				$('#txt_botonbusqueda').val('0');
				
				ncsistema.Listar_SummaryHeaderTabla('',0);	
			}
			
			
			
			function Descargar_ExcelGeneral()
			{
				
				var Cmb_OpcionesExportarExcel=$.trim($('#Cmb_OpcionesExportarExcel').val());	
				/*
				var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());					
				var txt_CodigoResumen=$.trim($('#txt_CodigoResumen').val());
				var txt_FechaGenInicio=$.trim($('#txt_FechaGenInicio').val());
				var txt_FechaGenFinal=$.trim($('#txt_FechaGenFinal').val());
				var Cmb_EstadoDocumento=$.trim($('#Cmb_EstadoDocumento').val());
				var txt_FechaEmisionInicio=$.trim($('#txt_FechaEmisionInicio').val());
				var txt_FechaEmisionFinal=$.trim($('#txt_FechaEmisionFinal').val());
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());
				var Cmb_EstadoDocumentoSunat=$.trim($('#Cmb_EstadoDocumentoSunat').val());
				*/
				var txt_botonbusqueda=$.trim($('#txt_botonbusqueda').val());
				if (txt_botonbusqueda==0)
				{
					alert('No ha realizado ninguna busqueda');
					return;
				}				
				var str = $('#txt_filtrobusqueda').val();
				var resultado = str.split(','); 
				
				var txt_RucEmpresa=resultado[0];	
				var txt_CodigoResumen=resultado[1];	
				var txt_FechaGenInicio=resultado[2];	
				var txt_FechaGenFinal=resultado[3];	
				var Cmb_EstadoDocumento=resultado[4];	
				var txt_FechaEmisionInicio=resultado[5];	
				var txt_FechaEmisionFinal=resultado[6];		
				var Cmb_EstadoDocumentoSunat=resultado[7];					
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());
				
				
				if (Cmb_OpcionesExportarExcel==1)
				{
					txt_datosseleccionados='';
					document.location.href=urlexportardatos+'resumenboletas/Exportar_ExcelGeneral?param1='+txt_RucEmpresa+'&param2='+txt_CodigoResumen+'&param3='+txt_FechaGenInicio+'&param4='+txt_FechaGenFinal+'&param5='+Cmb_EstadoDocumento+'&param6='+txt_FechaEmisionInicio+'&param7='+txt_FechaEmisionFinal+'&param8='+txt_datosseleccionados+'&param9='+Cmb_EstadoDocumentoSunat;
				}
				else
				{
					if (txt_datosseleccionados=='')
					{
						alert('No existe datos seleccionados');
					}
					else
					{
						document.location.href=urlexportardatos+'resumenboletas/Exportar_ExcelGeneral?param1='+txt_RucEmpresa+'&param2='+txt_CodigoResumen+'&param3='+txt_FechaGenInicio+'&param4='+txt_FechaGenFinal+'&param5='+Cmb_EstadoDocumento+'&param6='+txt_FechaEmisionInicio+'&param7='+txt_FechaEmisionFinal+'&param8='+txt_datosseleccionados+'&param9='+Cmb_EstadoDocumentoSunat;
					}
				}
			}
			
			function Descargar_ExcelDetalle(resumenid)
			{
				var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());	
				document.location.href=urlexportardatos+'resumenboletas/Descargar_ExcelDetalle?param1='+txt_RucEmpresa+'&param2='+resumenid;				
			}
			
			function Descargar_ExcelDetalleVistaPrevia()
			{
				var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());	
				var txt_codigoresumenvistaprevia=$.trim($('#txt_codigoresumenvistaprevia').val());
				if (txt_codigoresumenvistaprevia!='')
				{
					document.location.href=urlexportardatos+'resumenboletas/Descargar_ExcelDetalle?param1='+txt_RucEmpresa+'&param2='+txt_codigoresumenvistaprevia;				
				}
				else
				{
					alert('No existe documento seleccionado');
				}
			}

			function Seleccionar_DatosBusqueda(key,resumenid,estado_doc,estado_sunat)
			{
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
				var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());
				
				
				var txt_datosseleccionados_estado=$.trim($('#txt_datosseleccionados_estado').val());	
				var txt_cantidaddocum_estado=$.trim($('#txt_cantidaddocum_estado').val());	

				if ($("#cbox_seleccion_"+key).is(":checked"))
				{
					if (txt_datosseleccionados=='')
					{
						txt_datosseleccionados=resumenid;
					}
					else
					{
						txt_datosseleccionados=txt_datosseleccionados+','+resumenid;
					}					
					txt_cantidadseleccionados++;
					var temporal=0;
					if (estado_doc!='L')// || estado_sunat=='SIGNED'
					{
						resumenid=resumenid+'-'+estado_doc;
						temporal=1;
					}
					else if (estado_sunat=='SIGNED')
					{
						resumenid=resumenid+'-'+estado_sunat;
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
				}
				else
				{
					txt_datosseleccionados=txt_datosseleccionados.replace(","+resumenid, ""); 
					txt_datosseleccionados=txt_datosseleccionados.replace(resumenid, ""); 					
					txt_cantidadseleccionados--;
					var temporal=0;
					if (estado_doc!='L')// || estado_sunat=='SIGNED'
					{
						resumenid=resumenid+'-'+estado_doc;
						temporal=1;
					}
					else if (estado_sunat=='SIGNED')
					{
						resumenid=resumenid+'-'+estado_sunat;
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
				$('#txt_cantidadseleccionados').val(txt_cantidadseleccionados);
				
				$('#txt_datosseleccionados_estado').val($.trim(txt_datosseleccionados_estado));
				$('#txt_cantidaddocum_estado').val(txt_cantidaddocum_estado);
				
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
								url:'<?php echo base_url()?>resumenboletas/Reiniciar_Correlativos',
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
										ncsistema.Listar_SummaryHeader();
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
			
			function Descargar_DocumentoSeleccionado()
			{
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());	
				var txt_cantidadseleccionados=$.trim($('#txt_cantidadseleccionados').val());	
				var txt_rucemisor=$.trim($('#txt_RucEmpresa').val());
				
				if (txt_cantidadseleccionados>0)
				{
				
					$.ajax({
							url:'<?php echo base_url()?>resumenboletas/Crear_ArchivosDocumentoSeleccionado',
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
								else if (result.status==2)
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
				document.location.href=urlexportardatos+'resumenboletas/Descargar_DocumentoSeleccionado?param1='+txt_datosseleccionados+'&param2='+txt_rucemisor;	
			}

		</script>
		
    </head>   
    <body>
		<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">LISTA DE RESUMEN DE BOLETAS</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">			
				<div id="div_datosempresa" style="width:100%; float:left; margin-top:10px; border: 1px solid #a6c9e2; border-radius:5px;">
					<table border="0" width="60%" style="border-collapse:separate; border-spacing:1px 1px;" cellpadding="3" class="tablaFormulario">
						<tr><td><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right; width:12%"><label class="columna">RUC:</label></td>
							<td style="text-align:left; width:13%">
								<input style="width:95%" type="text" id="txt_RucEmpresa" value="<?php echo trim(utf8_encode($Ruc_Empresa));?>"  disabled="disabled" />
							</td>
							<td style="text-align:right; width:8%"><label class="columna">Raz&oacute;n Social:</label></td>
							<td style="text-align:left;width:30%" colspan="2">
								<input style="width:95%" type="text" id="txt_RazonSocialEmpresa" value="<?php echo trim(utf8_encode($Razon_Social));?>" disabled="disabled" /></td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">C&oacute;digo Resumen:</label></td>
							<td style="text-align:left">
								<input style="width:95%" id="txt_CodigoResumen" type="text" value="" maxlength="13"/>
							</td>							
							<td style="text-align:right"><label class="columna">Fec.Generaci&oacute;n:</label></td>
							<td style="text-align:left">
								<input style="width:70%" id="txt_FechaGenInicio"  type="text" value="" title="Desde (##/##/####)" />
							</td>
							<td style="text-align:left">
								<input style="width:70%" id="txt_FechaGenFinal" type="text" value="" title="Hasta (##/##/####)" />
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Estado Doc.:</label></td>
							<td style="text-align:left">
								<select id="Cmb_EstadoDocumento" style="width:98%;height:25px" >
									<option value="0">TODOS</option>
									<?php foreach ( $Listar_EstadoDocumento as $v):	?>
										<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(strtoupper(utf8_decode($v['no_corto'])));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
							<td style="text-align:right"><label class="columna">Fec.Emisi&oacute;n:</label></td>
							<td style="text-align:left;">
								<input style="width:70%; text-align:center" id="txt_FechaEmisionInicio" type="text" value="" title="Desde (##/##/####)" />
							</td>
							<td style="text-align:left">
								<input style="width:70%; text-align:center" id="txt_FechaEmisionFinal" type="text" value="" title="Hasta (##/##/####)" />
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Estado Sunat:</label></td>
							<td style="text-align:left">
								<select id="Cmb_EstadoDocumentoSunat" style="width:98%;height:25px" >
									<option value="0">TODOS</option>
									<?php foreach ( $Listar_EstadoSunatResumen as $v):	?>
										<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(strtoupper(utf8_decode($v['no_corto'])));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
							<td style="text-align:right"></td>
							<td style="text-align:left;"></td>
							<td style="text-align:left"></td>
						</tr>
						<tr>
							<td></td>
							<td style="text-align:left;width:10%" colspan="4">
								<table width="100%" border="0">
								  <tr>
									<td  width="10%">
										<a href="javascript:ncsistema.Listar_SummaryHeader()" >
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
				
				<table width="100%" class="ui-widget-header" border="0">
				
					<input id="txt_datosseleccionados" type="hidden" value="" />
					<input id="txt_cantidadseleccionados" type="hidden" value="0" />					
					<input id="txt_datosseleccionados_estado" type="hidden" value="" />
					<input id="txt_cantidaddocum_estado" type="hidden" value="0" />
					
					<input id="txt_filtrobusqueda" type="hidden" value="" />
					<input id="txt_botonbusqueda" type="hidden" value="0" />
	
					<tr >
						<td style="text-align:center; vertical-align:middle; width:5%;" >
							<a href="javascript:ncsistema.ExportarExcel_General()" title="Exportar a Excel" >
								<img src="<?php echo base_url();?>application/helpers/image/iconos/exportarexcel.jpg" width="30" height="30" alt="Exportar Listado"/>
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
								onClick="javascrip:ncsistema.Detalle_Comprobante()">
								<span class="ui-button-icon-left ui-icon ui-icon-newwin"></span>
								<span class="ui-button-text">Ver Detalle</span></button>
							<button style="width:120px; height:32px"  id="btn_DescargarPrincipal" title="Descargar" 
								class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left"
								onClick="javascrip:Descargar_DocumentoSeleccionado()">
								<span class="ui-button-icon-left ui-icon ui-icon-disk"></span>
								<span class="ui-button-text">Descargar</span></button>
						</td>
					</tr>
				</table>
				<div id="div_ListadoEmpresa" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:5px">
				</div>
			</div>
			
			</div>
		</div>
		
		
		
		
		<div id="dialog-form-detalledocumento" title="Detalle de Resumen de Boleta">
		 	<form>
			
			<input id="txt_codigoresumenvistaprevia" type="hidden" value="" />
			<div> 
				<div>
					<table width="100%" border="1">
						<tr>
							<td colspan="2">
								<table width="100%" border="0">
									<tr>
										<td style="width:15%">Nombre/Razón Social:</td>
										<td style="width:30%"><div id="div_param9"></div></td>
										<td style="width:7%">RUC:</td>
										<td style="width:20%"><div id="div_param10"></div></td>
										<td style="width:13%">Fecha Emisión:</td>
										<td style="width:15%"><div id="div_param11"></div></td>
									</tr>
									<tr>
										<td>Identificador de Resumen:</td>
										<td><div id="div_param12"></div></td>
										<td>Fecha Generación Comprobantes:</td>
										<td><div id="div_param13"></div></td>
										<td>Estado SUNAT:</td>
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
				
			</div>

		

		  </form>
		</div>
		
		
		
		
    </body>	
</html>