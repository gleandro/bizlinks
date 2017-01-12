<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Estadística</title>
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
				ncsistema.Listar_ComprobantesTabla('');	
				
				$('#txt_FechaGenInicio,#txt_FechaGenFinal,#txt_FechaEmisionInicio,#txt_FechaEmisionFinal').datepicker({
					showOn: 'button',					
					buttonImage: "<?php echo base_url()?>application/helpers/image/ico/calendar_icon.gif",
					buttonImageOnly: true,
					dateFormat: 'dd/mm/yy',
					buttonText: "Desde (##/##/####)",
					maxDate: 'today',
					changeMonth: true ,
					changeYear: true
				});
				$('#txt_FechaGenInicio,#txt_FechaGenFinal,#txt_FechaEmisionInicio,#txt_FechaEmisionFinal').datepicker({
					showOn: 'button',					
					buttonImage: "<?php echo base_url()?>application/helpers/image/ico/calendar_icon.gif",
					buttonImageOnly: true,
					dateFormat: 'dd/mm/yy',
					buttonText: "Hasta (##/##/####)",
					maxDate: 'today',
					changeMonth: true ,
					changeYear: true
				});
				//$('#txt_FechaGenInicio,#txt_FechaGenFinal').datepicker('setDate', 'today');				
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
					
					if (txt_RazonSocialCliente=='')
					{
						txt_DocumentoCliente='';
					}

					
					$.ajax({
						url:'<?php echo base_url()?>estadistica/Listar_Comprobantes',
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
							Cmb_EstadoDocumentoSunat:Cmb_EstadoDocumentoSunat
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
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
				
				
				Listar_ComprobantesTabla:function(data)
				{	
					$('#div_ListadoEmpresa').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th width:5%>Tipo</td>';						
						newHtml+='<th style="background:#0099CC" width:10%>Borrador</td>';
						newHtml+='<th style="background:#0099CC" width:10%>Leido</td>';
						newHtml+='<th style="background:#0099CC" width:20%>Error Local</td>';
						newHtml+='<th style="background:#993366"  width:50%>Aceptadas</td>';
						newHtml+='<th style="background:#993366" width:50%>Rechazados</td>';
						newHtml+='<th style="background:#993366" width:50%>Pendiente de Declaracion</td>';
						newHtml+='<th style="background:#993366" width:50%>Anulados</td>';
						
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					//<input style="height:20px;width:95%" id="txt_login" type="text" value="'+rs.cantidadproducto+'"/>
					contador=0;
					if (data.length>0)
					{
						total1=0;
						total2=0;
						total3=0;
						total4=0;
						total5=0;
						total6=0;
						total7=0;
						$.each(data,function(key,rs)
						{						
							newHtml+='<tr>';															
								newHtml+='<td style="text-align:left">'+rs.nomb_tipodocumento+'</td>';		
								newHtml+='<td style="text-align:center">'+rs.borrador+'</td>';
								newHtml+='<td style="text-align:center">'+rs.leido+'</td>';
								newHtml+='<td style="text-align:center">'+rs.error_local+'</td>';	
								newHtml+='<td style="text-align:center">'+rs.aceptadas+'</td>';			
								newHtml+='<td style="text-align:center">'+rs.rechazados+'</td>';
								newHtml+='<td style="text-align:center">'+rs.pendiente_declaracion+'</td>';
								newHtml+='<td style="text-align:center">'+rs.anulados+'</td>';
							newHtml+='</tr>';	
							
							total1=total1+parseInt(rs.borrador);
							total2=total2+parseInt(rs.leido);
							total3=total3+parseInt(rs.error_local);
							total4=total4+parseInt(rs.aceptadas);
							total5=total5+parseInt(rs.rechazados);
							total6=total6+parseInt(rs.pendiente_declaracion);
							total7=total7+parseInt(rs.anulados);					
						});	
						
						newHtml+='<tr>';															
							newHtml+='<td style="text-align:left">Total</td>';		
							newHtml+='<td style="text-align:center">'+total1+'</td>';
							newHtml+='<td style="text-align:center">'+total2+'</td>';
							newHtml+='<td style="text-align:center">'+total3+'</td>';	
							newHtml+='<td style="text-align:center">'+total4+'</td>';			
							newHtml+='<td style="text-align:center">'+total5+'</td>';
							newHtml+='<td style="text-align:center">'+total6+'</td>';
							newHtml+='<td style="text-align:center">'+total7+'</td>';
						newHtml+='</tr>';
					
					}
					else
					{
						newHtml+='<tr>';															
							newHtml+='<td style="text-align:left">BOLETA DE VENTA</td>';		
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';	
							newHtml+='<td style="text-align:center">0</td>';			
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
						newHtml+='</tr>';
						
						newHtml+='<tr>';															
							newHtml+='<td style="text-align:left">FACTURA</td>';		
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';	
							newHtml+='<td style="text-align:center">0</td>';			
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
						newHtml+='</tr>';	
						newHtml+='<tr>';															
							newHtml+='<td style="text-align:left">NOTA DE CREDITO</td>';		
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';	
							newHtml+='<td style="text-align:center">0</td>';			
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
						newHtml+='</tr>';	
						newHtml+='<tr>';															
							newHtml+='<td style="text-align:left">NOTA DE DEBITO</td>';		
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';	
							newHtml+='<td style="text-align:center">0</td>';			
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
						newHtml+='</tr>';	
						
						newHtml+='<tr>';															
							newHtml+='<td style="text-align:left">Total</td>';		
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';	
							newHtml+='<td style="text-align:center">0</td>';			
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
							newHtml+='<td style="text-align:center">0</td>';
						newHtml+='</tr>';

					}

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
				


			}
			

			
			
			
			function Descargar_ExcelGeneral()
			{
				
				var Cmb_OpcionesExportarExcel=$.trim($('#Cmb_OpcionesExportarExcel').val());	
				
				
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
				
				
				
				if (Cmb_OpcionesExportarExcel==1)
				{
					document.location.href=urlexportardatos+'estadistica/Exportar_ExcelGeneral?param1='+txt_RucEmpresa+'&param2='+txt_DocumentoCliente+'&param3='+txt_serienumeroinicio+'&param4='+txt_serienumerofinal+'&param5='+Cmb_EstadoDocumento+'&param6='+txt_FechaEmisionInicio+'&param7='+txt_FechaEmisionFinal+'&param8='+Cmb_TipoDocumentoSunat+'&param9='+Cmb_EstadoDocumentoSunat+'&param10='+txt_datosseleccionados+'&param11='+txt_RazonSocialCliente;
				}
				else
				{
					if (txt_datosseleccionados=='')
					{
						alert('No existe datos seleccionados');
					}
					else
					{
						document.location.href=urlexportardatos+'estadistica/Exportar_ExcelGeneral?param1='+txt_RucEmpresa+'&param2='+txt_DocumentoCliente+'&param3='+txt_serienumeroinicio+'&param4='+txt_serienumerofinal+'&param5='+Cmb_EstadoDocumento+'&param6='+txt_FechaEmisionInicio+'&param7='+txt_FechaEmisionFinal+'&param8='+Cmb_TipoDocumentoSunat+'&param9='+Cmb_EstadoDocumentoSunat+'&param10='+txt_datosseleccionados+'&param11='+txt_RazonSocialCliente;
					}
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
								newHtml+='<option value="0">[SELECCIONAR]</option>';								
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
								newHtml+='<option value="0">[SELECCIONAR]</option>';
								newHtml+='</select>';		
								$('#div_Estadodocumentosunat').empty().append(newHtml);	
							}
						}
					});
				}

			}
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

				ncsistema.Listar_ComprobantesTabla('');	
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
					<table border="0" width="70%" style="border-collapse:separate; border-spacing:8px 1px;" cellpadding="3" class="tablaFormulario">
						<tr>
							<td style="text-align:right;width:15%" ><label class="columna">RUC :</label></td>
							<td style="text-align:left;width:20%">
								<input style="width:95%" type="text" id="txt_RucEmpresa" value="<?php echo trim(utf8_encode($Ruc_Empresa));?>"  disabled="disabled" />
							</td>
							<td style="text-align:right;width:15%"><label class="columna">Raz&oacute;n Social :</label></td>
							<td style="text-align:left;width:48%" colspan="2">
								<input style="width:100%" type="text" id="txt_RazonSocialEmpresa" value="<?php echo trim(utf8_encode($Razon_Social));?>" disabled="disabled" /></td>
							
							<td style="text-align:left;width:2%:" valign="bottom">
								<a href="javascript:ncsistema.Listar_Comprobantes()" >
									<img src="<?php echo base_url();?>application/helpers/image/ico/buscar32.png" style="width:20px;height:20px" />
								</a>							
							</td>													
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Emisor :</label></td>
							<td style="text-align:left">
								<input style="width:95%" id="txt_RazonSocialCliente" type="text" value="" placeholder="Buscar Emisor por Raz. Social" />
								<input style="width:95%" id="txt_DocumentoCliente" type="hidden" value="" />
							</td>							
							<td style="text-align:right"><label class="columna">Serie-Num. :</label></td>
							<td style="text-align:left">
								<table border="0" width="100%" cellpadding="0" cellspacing="0">
									<tr><td style="text-align:left;width:90%">
										<input style="width:70%" id="txt_serienumeroinicio" type="text" value=""  maxlength="13"/>
									</td>
									<td style="text-align:left;width:10%"><label class="columna">al</label>
									</td>
									</tr>
								</table>
								
							</td>
							<td style="text-align:left">
								<input style="width:70%" id="txt_serienumerofinal" type="text" value="" maxlength="13"/>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Estado Doc. :</label></td>
							<td style="text-align:left">
								<select id="Cmb_EstadoDocumento" style="width:98%;height:25px" >
									<option value="0">TODOS</option>
									<?php foreach ( $Listar_EstadoDocumento as $v):	?>
										<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(utf8_encode(strtoupper($v['no_corto'])));?> </option>
									<?php  endforeach; ?>
									
									
								</select>
							</td>
							<td style="text-align:right"><label class="columna">Fec.Emisi&oacute;n :</label></td>
							<td style="text-align:left">
								<input style="width:70%" id="txt_FechaEmisionInicio" type="text" value="" disabled="disabled" />
							</td>
							<td style="text-align:left">
								<input style="width:70%" id="txt_FechaEmisionFinal" type="text" value=""  disabled="disabled"/>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Tipo Documento :</label></td>
							<td style="text-align:left">
								<select id="Cmb_TipoDocumentoSunat" style="width:98%;height:25px" onChange="javascript:Listar_DocumentoSunat(this.value)" >
									<option value="0">TODOS</option>
									<?php foreach ( $Listar_TipodeDocumento as $v):	?>
										<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(utf8_encode($v['no_corto']));?> </option>
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
							<td style="text-align:left">

							</td>
						</tr>
						<td style="text-align:left;width:10%" colspan="5">
								<div id="div_OpcionesBotones">
									<a href="javascript:Limpiar_Busqueda()" >
										<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
											<!--<span class="ui-button-icon-left ui-icon ui-icon-search"></span>-->
											<span class="ui-button-text">Limpiar</span></button>
									</a>
								</div>
						</td>				
					</table>	
				<!--
				
				<table width="100%" class="ui-widget-header">
			
					<tr  valign="middle">
						
						<td style="text-align:left; vertical-align:middle">
							<button style="width:90px" id="btn_DescargarPrincipal" title="Iniciar Sesión" 
								class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left"
								>
								<span class="ui-button-icon-left ui-icon ui-icon-disk"></span>
								<span class="ui-button-text">Descargar</span></button>
						</td>
						

					</tr>
				</table>
				-->
				</div>
				<div id="div_ListadoEmpresa" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:5px">
				</div>
				<div id="div_Resumen" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:5px">
					<table cellpadding="0" cellspacing="5" class="display" >
						<tr>						
							<td style="background:#0099CC;width:80px"></td>
							<td style="width:120px;text-align:left">Estado Documento</td>
						</tr>
						<tr>						
							<td style="background:#993366"></td>
							<td style="text-align:left">Estado SUNAT</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		
		
		
		
		
    </body>	
</html>