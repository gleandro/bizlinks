<!doctype html>
<html>
<head>
	<title>SFE Bizlinks Facturador Local</title>
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
	
	<style type="text/css">
            body {
                font-size: 10px;
            }
        </style>
	<script type="text/javascript"> 
			
			$(document).ready(function()
			{
				$.datepicker.setDefaults($.datepicker.regional["es"]);

				$('#txt_fechaemision').datepicker({
					showOn: 'button',					
					buttonImage: "<?php echo base_url()?>application/helpers/image/ico/calendar_icon.gif",
					buttonImageOnly: true,
					dateFormat: 'dd/mm/yy',
					buttonText: "##/##/####",
					maxDate: 'today',
					changeMonth: true ,
					changeYear: true
				});
				$('#txt_fechaemision').datepicker('setDate', 'today');
				$('#txt_montototal,#text_rucproveedor').numeric({allow:'.'});
				$('#div_param1').empty().append('Monto Total:');
				$('#div_param2').empty().append('R.U.C. Proveedor:');	
				
			});
			ncsistema=
			{
				Consultar_Documento:function()
				{
					var cmb_tipodedocumento=$.trim($('#cmb_tipodedocumento').val());
					var txt_serienumero=($.trim($('#txt_serienumero').val())).toUpperCase();
					var txt_montototal=$.trim($('#txt_montototal').val());
					var txt_fechaemision=$.trim($('#txt_fechaemision').val());	
					var text_rucproveedor=$.trim($('#text_rucproveedor').val());	
					
					if (cmb_tipodedocumento=='0')
					{
						$("#div_errorcorreoSes").fadeIn(0);
						$('#div_errorcorreoSes').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:12px;font-family:"Times New Roman", Times, serif">Seleccione un tipo de documento</div>');
						setTimeout(function(){ $("#div_errorcorreoSes").fadeOut(1500);},3000);
						return;
					}
					
					if (txt_serienumero=='')
					{
						$("#div_errorcorreoSes").fadeIn(0);
						$('#div_errorcorreoSes').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:12px;font-family:"Times New Roman", Times, serif">Ingrese la serie y n�mero del documento</div>');
						setTimeout(function(){ $("#div_errorcorreoSes").fadeOut(1500);},3000);
						return;
					}
					
					if (txt_montototal=='')
					{
						$("#div_errorcorreoSes").fadeIn(0);
						$('#div_errorcorreoSes').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:12px;font-family:"Times New Roman", Times, serif">Ingrese el monto del documento</div>');
						setTimeout(function(){ $("#div_errorcorreoSes").fadeOut(1500);},3000);
						return;
					}
					
					if (txt_fechaemision=='')
					{
						$("#div_errorcorreoSes").fadeIn(0);
						$('#div_errorcorreoSes').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:12px;font-family:"Times New Roman", Times, serif">Ingrese la fecha del documento</div>');
						setTimeout(function(){ $("#div_errorcorreoSes").fadeOut(1500);},3000);
						return;
					}
					if (text_rucproveedor=='')
					{
						$("#div_errorcorreoSes").fadeIn(0);
						$('#div_errorcorreoSes').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:12px;font-family:"Times New Roman", Times, serif">Ingrese el RUC del Proveedor</div>');
						setTimeout(function(){ $("#div_errorcorreoSes").fadeOut(1500);},3000);
						return;
					}
					
					switch(cmb_tipodedocumento)
					{
						case '20':
							$.ajax({
								url:'<?php echo base_url()?>retencion/existe_comprobante',
								type: 'post',
								dataType: 'json',
								data:
								{
									cmb_tipodedocumento:cmb_tipodedocumento,
									txt_serienumero:txt_serienumero,
									txt_montototal:txt_montototal,
									txt_fechaemision:txt_fechaemision,
									text_rucproveedor:text_rucproveedor
								},
								beforeSend:function()
								{
									$('#div_errorIniciarSesion').fadeIn(0);
									$('#div_errorIniciarSesion').empty().append('<div style="width:100%;height:100px;text-align:left;font-weight:bold;">Consultando, Espere por Favor ...</div>');
								},
								success:function(result)
								{
									if(result.status==1)
									{
										$('#div_errorIniciarSesion').empty().append('');
										//alert('Si existe retenci�n');
										ncsistema.Detalle_Retencion();
									}
									else
									{
										$('#div_errorIniciarSesion').fadeIn(0);
										$('#div_errorIniciarSesion').empty().append('<div style="width:100%;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:12px;font-family:"Times New Roman", Times, serif">Los datos ingresados no existen.</div>');
										setTimeout(function(){ $("#div_errorIniciarSesion").fadeOut(1500);},3000);
										return;
									}
								}
							});
							break;
						default:
							$.ajax({
								url:'<?php echo base_url()?>comprobante/existe_comprobante',
								type: 'post',
								dataType: 'json',
								data:
								{
									cmb_tipodedocumento:cmb_tipodedocumento,
									txt_serienumero:txt_serienumero,
									txt_montototal:txt_montototal,
									txt_fechaemision:txt_fechaemision,
									text_rucproveedor:text_rucproveedor
								},
								beforeSend:function()
								{
									$('#div_errorIniciarSesion').fadeIn(0);
									$('#div_errorIniciarSesion').empty().append('<div style="width:100%;height:100px;text-align:left;font-weight:bold;">Consultando, Espere por Favor ...</div>');
								},
								success:function(result)
								{
									if(result.status==1)
									{
										$('#div_errorIniciarSesion').empty().append('');
										ncsistema.Detalle_Comprobante();
									}
									else
									{
										$('#div_errorIniciarSesion').fadeIn(0);
										$('#div_errorIniciarSesion').empty().append('<div style="width:100%;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:12px;font-family:"Times New Roman", Times, serif">Los datos ingresados no existen.</div>');
										setTimeout(function(){ $("#div_errorIniciarSesion").fadeOut(1500);},3000);
										return;
									}
								}
							});
							break;
					}
				},
				
				Detalle_Retencion:function()
				{
					var txt_datosseleccionados=$.trim($('#cmb_tipodedocumento').val())+'-'+$.trim($('#txt_serienumero').val()).toUpperCase();	
					var txt_RucEmpresa=$.trim($('#text_rucproveedor').val());
					
					$.ajax({
						url:'<?php echo base_url()?>retencion/Listar_DetalleDocumento_Anonimo',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_RucEmpresa:txt_RucEmpresa,
							txt_datosseleccionados:txt_datosseleccionados
						},
						beforeSend:function()
						{	},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Detalle_RetencionesTabla(result.data);
							}else{
								ncsistema.Detalle_RetencionesTabla("");
							}
						}
					});						
				},
				
				Detalle_Comprobante:function()
				{
					var txt_datosseleccionados=$.trim($('#cmb_tipodedocumento').val())+'-'+$.trim($('#txt_serienumero').val()).toUpperCase();	
					var txt_cantidadseleccionados=1;					
					var txt_RucEmpresa=$.trim($('#text_rucproveedor').val());
					
					//alert(txt_datosseleccionados);
										
					if (txt_cantidadseleccionados==1)
					{
						$.ajax({
							url:'<?php echo base_url()?>comprobante/Listar_DetalleDocumento_Anonimo',
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
				
				Detalle_RetencionesTabla:function(data)
				{	
					dialogdetalleretenciones.dialog("open");
					$('#div_documentoretenciones').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_DetalleRetencionesTabla">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th width:3%>Item</td>';						
						newHtml+='<th width:5%>Tipo</td>';						
						newHtml+='<th width:12%>Documento</td>';
						newHtml+='<th width:10%>Fec. Emisi�n</td>';
						newHtml+='<th width:10%>Fec. Pago</td>';
						newHtml+='<th width:5%>Nro. Pago</td>';
						newHtml+='<th width:8%>Moneda Origen</td>';
						newHtml+='<th width:10%>Imp.Oper. Origen</td>';
						newHtml+='<th width:15%>Imp.Pago Sin Retenci�n</td>';
						newHtml+='<th width:12%>Importe Retenido.S/.</td>';
						newHtml+='<th width:10%>Imp.Total Pagar.S/.</td>';
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					
					$.each(data,function(key,rs)
					{					
						if (key==0)
						{							
							$('#div_param1_ret').empty().append(rs.razonsocialemisor);	
							$('#div_param2_ret').empty().append(rs.nombrecomercialemisor);	
							$('#div_param3_ret').empty().append(rs.direccionemisor);	
							$('#div_param4_ret').empty().append(rs.departamentoemisor+' - '+rs.provinciaemisor+' - '+rs.distritoemisor+rs.urbanizacionemisor);	
							
							$('#div_param5_ret').empty().append(rs.nombre_tipodocumentoemisor);	
							$('#div_param6_ret').empty().append(rs.numerodocumentoemisor);	
							$('#div_param7_ret').empty().append(rs.nombre_tipodocumento+' ELECTRONICA');	
							$('#div_param8_ret').empty().append(rs.serienumero);	
							
							$('#div_param9_ret').empty().append(rs.razonsocialadquiriente);	
							$('#div_param10_ret').empty().append(rs.numerodocumentoadquiriente);	
							$('#div_param11_ret').empty().append(rs.fechaemision);	
							$('#div_param12_ret').empty().append(rs.direccioncliente);	
							$('#div_param13_ret').empty().append(rs.tasaretencion+'%');	
							
							if (rs.importetotalretenido>0)
							{
								//OcultarFilaPassword('row1',1);
								$('#div_param16_ret').empty().append(rs.tipomonedaretenido);
								$('#div_param17_ret').empty().append(rs.importetotalretenido);
							}
							if (rs.importetotalpagado>0)
							{
								//OcultarFilaPassword('row2',1);
								$('#div_param18_ret').empty().append(rs.tipomonedapagado);
								$('#div_param19_ret').empty().append(rs.importetotalpagado);
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
					
					$('#div_documentoretenciones').empty().append(newHtml);	

					oTable=$('#Tab_DetalleRetencionesTabla').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_DetalleRetencionesTabla tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});
				},
			}
			
			function Limpiar_Datos()
			{
				$('#cmb_tipodedocumento').val('0');
				$('#txt_serienumero').val('');
				$('#txt_montototal').val('');
				$('#text_rucproveedor').val('');
				$('#txt_fechaemision').datepicker('setDate', 'today');
			}
			
			function Retornar_PaginaInicio()
			{
				document.location.href=urlexportardatos+'usuario';
			}
			
			$(function() 
			{

				function Imprimir_DocumentoSeleccionadoDetalle() 
				{
					var txt_datosseleccionados=$.trim($('#cmb_tipodedocumento').val())+'-'+$.trim($('#txt_serienumero').val());	
					var txt_cantidadseleccionados=1;	
					var txt_rucemisor=$.trim($('#text_rucproveedor').val());

					if (txt_cantidadseleccionados>0)
					{
						$.ajax({
							url:'<?php echo base_url()?>comprobante/Comprobar_DocumentoImprimirAnonimo',
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
									document.location.href=urlexportardatos+'comprobante/Imprimir_DocumentoSeleccionadoAnonimo?param1='+txt_datosseleccionados+'&param2='+txt_rucemisor;
								}
								else if(result.status==2)
								{
									alert("No existen los archivos seleccionados");
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
					var txt_datosseleccionados=$.trim($('#cmb_tipodedocumento').val())+'-'+$.trim($('#txt_serienumero').val());	
					var txt_cantidadseleccionados=1;	
					var txt_rucemisor=$.trim($('#text_rucproveedor').val());
					
					if (txt_cantidadseleccionados>0)
					{
					
						$.ajax({
								url:'<?php echo base_url()?>comprobante/Crear_ArchivosDocumentoSeleccionadoAnonimo',
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
					width: 850,
					modal: true,
					buttons: 
					{
						"Imprimir": function() {Imprimir_DocumentoSeleccionadoDetalle();},
						"Descargar": function() {Descargar_DocumentoSeleccionadoDetalle();},
						"Salir": function() 
						{							
							$('#cmb_tipodedocumento').val('0');							
							$('#txt_serienumero').val('');
							$('#txt_montototal').val('');
							$('#txt_fechaemision').datepicker('setDate', 'today');
							$('#text_rucproveedor').val('');
							dialogdetallecomprobante.dialog( "close" );							
						}
					},						
					close: function() 
					{
					}
				});
				
				dialogdetalleretenciones = $("#dialog-form-detalleretenciones").dialog({
					autoOpen: false,
					height: 650,
					width: 950,
					modal: true,
					buttons: 
					{
						"Imprimir": function() {Imprimir_DocumentoSeleccionadoDetalle();},
						"Descargar": function() {Descargar_DocumentoSeleccionadoDetalle();},
						"Salir": function() 
						{
							$('#cmb_tipodedocumento').val('0');							
							$('#txt_serienumero').val('');
							$('#txt_montototal').val('');
							$('#txt_fechaemision').datepicker('setDate', 'today');
							$('#text_rucproveedor').val('');
							dialogdetalleretenciones.dialog( "close" );							
						}
					},						
					close: function() 
					{
					}
				});
					
			});
			
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
			
			function Descargar_DocumentoSeleccionadoArchivos()
			{
				var txt_datosseleccionados=$.trim($('#cmb_tipodedocumento').val())+'-'+$.trim($('#txt_serienumero').val());	
				var txt_rucemisor=$.trim($('#text_rucproveedor').val());
				document.location.href=urlexportardatos+'comprobante/Descargar_DocumentoSeleccionadoAnonimo?param1='+txt_datosseleccionados+'&param2='+txt_rucemisor;	
			}
			
			function Editar_Etiqueta(tipoDocumento)
			{
				var txt_tipoDocumento=tipoDocumento;
				if (txt_tipoDocumento=='20')
				{
					$('#div_param1').empty().append('Importe Retenido:');
					$('#div_param2').empty().append('R.U.C. Emisor:');
				}else{
					$('#div_param1').empty().append('Monto Total:');
					$('#div_param2').empty().append('R.U.C. Proveedor:');	
				}
			}
			
		</script>

		
    </head> 

<body id="login" class="animated fadeInDown ui-layout-container" style="position: relative; height: 100%; overflow: hidden; margin: 0px; padding: 0px; border: medium none; font-size: 10px;">
<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
	<div style="height: 100%; width: 100%; margin: 0px; left: 0px; right: 0px; top: 0px; bottom: 0px;  z-index: 1; display: block; 
		visibility: visible;" id="j_id_1" class="ui-layout-unit ui-widget ui-corner-all ui-layout-center ui-layout-pane ui-layout-pane-center" >
		<div id="subcapa" style="height: 100%; width: 100%; position: relative; visibility: visible; 
			background-image:url(<?php echo base_url() ?>application/helpers/image/logos/fondo-general.jpg)" 
			class="ui-layout-unit-content " >
		<table style="height: 100%; width: 100%; margin: 0px; left: 0px; right: 0px; top: 0px; bottom: 0px;  z-index: 1; display: block; visibility: visible;" >
		  <tbody>
			<tr>
				<td width="20%"><span></span></td>
				<td  width="60%" background="<?php echo base_url() ?>application/helpers/image/logos/fondo_fe_white.png" >
					<div id="todo" style="position: relative; border-bottom:#006699 1px solid; height: 100%; width: 100%; margin: 0 auto; 
					visibility: visible; "></div>
					<table >
					  <tbody>
						<tr>
							<td width="15%">
								<div id="logo" style="width: 240px; height: 90px; float: left; margin-left: 20px; padding-top: 10px;">
									<a href="http://www.bizlinks.com.pe/" target="_blank">
									<img id="j_id_2" src="<?php echo base_url() ?>application/helpers/image/logos/logo_Empresa.png" alt="" 
										style="width:180px;"></a>		
								</div>
							</td>
							<td width="5%"><span></span></td>
							<td width="80%">
								<div id="menu-dos" style="float: right; margin-left: 180px; font-size: 8px; font-weight: bold; color: #666666; 
									margin-top: 3px; font-size: 8px; color: #006699; font-weight: bold;">
									<h1 align="right">Sistema de Facturaci&oacute;n Electr&oacute;nica</h1>
								</div>
								<div class="redes" style='float: right;'>
									<div align="center"><a href="https://www.facebook.com/pages/Bizlinks-Latin-America/537002013078899" target="_blank">
										<img id="j_id2030916047_790d511f" src="<?php echo base_url() ?>application/helpers/image/logos/facebook.jpg" alt="" height="20" width="20"></a><a href="https://twitter.com/Bizlinks1" target="_blank">
										<img id="j_id2030916047_790d5112" src="<?php echo base_url() ?>application/helpers/image/logos/twitter.jpg" alt="" height="20" width="20"></a><a href="https://www.linkedin.com/company/bizlinks-latin-america?trk=top_nav_home" target="_blank">
										<img id="j_id2030916047_790d5125" src="<?php echo base_url() ?>application/helpers/image/logos/linkledn.jpg" alt="" height="20" width="20"></a>
									</div>
								</div>
							</td>
						</tr>
						<tr >
							<td colspan="3" width="100%">
								<table style="height: 100%; width: 100%; margin: 0px; left: 0px; right: 0px; top: 0px; bottom: 0px;  z-index: 1; display: block; visibility: visible;">
								  <tbody>
								  	<tr>
										<td width="2%" >
											<div id="todo" style="float: right; height: 476px; color: #006699; 
												visibility: visible; border-right: #006699 1px solid;">
												
												</div>
										</td>
										<td width="98%">
											<div style="width:100%; float:left; text-align:left; margin-top:0px; border-radius:3px;">
												<div id="div_datossesion" style="width:48%; height:476px; border:solid 0px; float:left;">												
												
													<table style="width:300px; border: solid 0px; float:left; text-align:left;"> <tbody>
														<tr> 
															<td style="border: solid 0px; width:100%;" colspan=2>
																<div id="div_mensajes" style="width:100%;  float:left; text-align:left; margin-top:100px; margin-left:10px">
																	<div style="border: solid 0px; float:left; font-size:12px; font:Arial, Helvetica, sans-serif; color: #006699; font-weight: bold;"> Datos del Comprobante</div></div>
															</td> 
														</tr>
															<tr> <td style="width:100%;" colspan=2>
																<div style="width:100%; height:25px; border:solid 0px; float:left; text-align:left; "><div id="div_errorcorreoSes"></div></div>
															</td> 
														</tr>									
														<tr> 
															<td style="width:40%;">
																<label class="columna">Tipo de Documento :</label>
															</td>
															<td style="width:60%;">
																<select id="cmb_tipodedocumento" style="width:80%;font-size:10px;height:23px" onChange="javascript:Editar_Etiqueta(this.value)">
																	<option value="0">[SELECCIONAR]</option>
																	<?php foreach ( $Listar_TipodeDocumento as $v):	?>
																		<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(utf8_decode($v['no_corto']));?> </option>
																	<?php  endforeach; ?>
																</select>
																
															</td> 
														</tr>														
														<tr> 
															<td >
																<label class="columna">Serie-N&uacute;mero:</label>
															</td>
															<td >
																<input class="negritaEstandar" style="width:130px; text-transform:uppercase" id="txt_serienumero"  type="text"  value="" maxlength="13"/>
															</td> 
														</tr>														
														<tr> 
															<td >
																<label class="columna"><div id="div_param1"></div></label>
															</td>
															<td >
																<input class="negritaEstandar" style="width:130px" id="txt_montototal" type="text"  placeholder="0.00" />
															</td> 
														</tr>														
														<tr> 
															<td >
																<label class="columna">Fecha Emisi&oacute;n:</label>
															</td>
															<td >
																<input class="negritaEstandar" style="width:130px" id="txt_fechaemision" type="text"  value="" />
															</td> 
														</tr>														
														<tr> 
															<td >
																<label class="columna"><div id="div_param2"></div></label>
															</td>
															<td >
																<input class="negritaEstandar" style="width:130px" id="text_rucproveedor"  type="text"  value="" maxlength="11"/>
															</td> 
														</tr>														

														<tr> 
															<td style="width:100%;" colspan=2>
																<div id="div_errorIniciarSesion" style="width:100%;height:40px; border:solid 0px; float:left;text-align:left; margin-top:3px; margin-left:30px"></div>
															</td> 
														</tr>
														<tr> 
															<td style="width:100%" colspan="2" align="left">
																<table>
																	<tr>
																		<td>
																			<button style="width:110px; height:32px" id="btn_Verdetalle" title="Consulta an�nima." 
																					class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" 
																					onClick="ncsistema.Consultar_Documento()">
																				<span class="ui-button-icon-left ui-icon ui-icon-search"></span>
																				<span class="ui-button-text">Consultar</span>
																			 </button >
																		
																		</td>
																		<td>
																			<button style="width:110px; height:32px" id="btn_Verdetalle" title="Consulta an�nima." 
																					class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" 
																					onclick="Limpiar_Datos()">
																				<span class="ui-button-icon-left ui-icon ui-icon-trash"></span>
																				<span class="ui-button-text">Limpiar</span>
																			 </button>
																		
																		</td>
																		<td>
																			<button style="width:110px; height:32px" id="btn_Verdetalle" title="Consulta an�nima." 
																					class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" 
																					onclick="Retornar_PaginaInicio()">
																				<span class="ui-button-icon-left ui-icon ui-icon-circle-arrow-w"></span>
																				<span class="ui-button-text">Regresar</span>
																			  </button>
																		
																		</td>
																	</tr>
																</table>
															</td> 
														</tr>
														<tr> 
															<td style="width:100%; height:20px; border:solid 0px" colspan="2">
															</td> 
														</tr>
														
														</tbody>
													</table>

												</div>
											</div>
										</td>
									</tr>
								  </tbody>
								</table>
							</td>
						</tr>
					  </tbody>
					</table>
					<!--<div id="todo" style="position: relative; border-bottom:#006699 1px solid; height: 100%; width: 100%; margin: 0 auto; 
						visibility: visible;"></div>-->
				</td>
				<td  width="20%"><span></span></td>
			</tr>
		  </tbody>
		</table>
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
										<td style="width:15%"><label class="columna">Nombre/Raz�n Social:</label></td>
										<td style="width:30%"><div id="div_param9"></div></td>
										<td style="width:7%"><label class="columna">RUC:</label></td>
										<td style="width:20%"><div id="div_param10"></div></td>
										<td style="width:13%"><label class="columna">Fecha Emisi�n:</label></td>
										<td style="width:15%"><div id="div_param11"></div></td>
									</tr>
									<tr>
										<td><label class="columna">Direcci�n:</label></td>
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
								<table width="100%" border="0">
									<tr>
										<td style="width:60%"><label class="columna"><div id="div_param15"></div></label></td>
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
													<td style="width:40%;text-align:right"><label class="columna">Operacion Exoneradas :</label></td>
													<td style="width:20%;text-align:center"><div id="div_param20"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param21"></div></td>
												</tr>
												
												<tr id="row4">
													<td style="width:40%;text-align:right"><label class="columna">Operacion Gratuitas :</label></td>
													<td style="width:20%;text-align:center"><div id="div_param22"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param23"></div></td>
												</tr>
												<tr id="row5">
													<td style="width:40%;text-align:right"><label class="columna">Descuentos :</label></td>
													<td style="width:20%;text-align:center"><div id="div_param24"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param25"></div></td>
												</tr>
												<tr id="row6">
													<td style="width:40%;text-align:right"><label class="columna">I.S.C 0% :</label></td>
													<td style="width:20%;text-align:center"><div id="div_param26"></div></td>
													<td style="width:40%;text-align:right"><div id="div_param27"></div></td>
												</tr>
												<tr id="row7">
													<td style="width:40%;text-align:right"><label class="columna">IGV 18% :</label></td>
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
						<!--
						<tr>
							<td colspan="2">
								<!--Representaci�n Impresa de la Factura Electr�nica, consulte en https://sfe.bizlinks.com.pe
							</td>
						</tr>-->
					</table>
				</div>
			</div>
	  </form>
	</div>
	
	<div id="dialog-form-detalleretenciones" title="Detalle del Comprobante de Retenci�n">
		 <form>
			<div> 
				<div>
					<table width="100%" border="1">
						<tr>
							<td style="width:70%">
								<table width="100%" border="0">
									<tr>
										<td><label class="columna"><div id="div_param1_ret"></div></label></td>
									</tr>
									<tr>
										<td><div id="div_param2_ret"></div></td>
									</tr>
									<tr>
										<td><div id="div_param3_ret"></div></td>
									</tr>
									<tr>
										<td><div id="div_param4_ret"></div></td>
									</tr>
								</table>
							</td>
							
							<td style="width:30%;text-align:center">
								<table width="100%" border="0">
									<tr>
										<td><label class="columna"><div id="div_param5_ret"></div></label></td>
									</tr>
									<tr>
										<td><div id="div_param6_ret"></div></td>
									</tr>
									<tr>
										<td><label class="columna"><div id="div_param7_ret"></div></label></td>
									</tr>
									<tr>
										<td><div id="div_param8_ret"></div></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<table width="100%" border="0">
									<tr>
										<td style="width:15%;"><label class="columna">Nombre/Raz�n Social:</label></td>
										<td style="width:30%"><div id="div_param9_ret"></div></td>
										<td style="width:7%; text-align:right"><label class="columna">RUC :</label></td>
										<td style="width:20%"><div id="div_param10_ret"></div></td>
										<td style="width:13%; text-align:right"><label class="columna">Fecha Emisi�n :</label></td>
										<td style="width:15%"><div id="div_param11_ret"></div></td>
									</tr>
									<tr>
										<td ><label class="columna">Direcci�n:</label></td>
										<td><div id="div_param12_ret"></div></td>
										<td style="text-align:right"><label class="columna">Tasa :</label></td>
										<td colspan="3"><div id="div_param13_ret"></div></td>
										
									</tr>
								</table>
							</td>
						</tr>
						
					</table>
				</div>
				<div> 
					<div id="div_documentoretenciones"></div>
				</div>
				<div> 
					<table width="100%">
						<tr>
							<td colspan="2">
								<table width="100%" border="0">
									<tr>
										<td style="width:60%"><div id="div_param15_ret"></div></td>
										<td style="width:40%">										
											<table width="100%" border="1">
												<tr id="row1">
													<td style="width:50%;text-align:right"><label class="columna">Importe Total Retenido :</label></td>
													<td style="width:20%;text-align:center"><div id="div_param16_ret"></div></td>
													<td style="width:30%;text-align:right"><div id="div_param17_ret"></div></td>
												</tr>

												<tr id="row2">
													<td style="width:50%;text-align:right"><label class="columna">Importe Total Pagado :</label></td>
													<td style="width:20%;text-align:center"><div id="div_param18_ret"></div></td>
													<td style="width:30%;text-align:right"><div id="div_param19_ret"></div></td>
												</tr>
											</table>
										
										</td>
									</tr>	
								</table>
							</td>
						</tr>
						
						
						<tr>
							<td colspan="2">
								<!-- Representaci�n Impresa de la Factura Electr�nica, consulte en https://sfe.bizlinks.com.pe -->
							</td>
						</tr>
					</table>
				</div>
			</div>
		  </form>
		</div>	

</body>
	
</html>