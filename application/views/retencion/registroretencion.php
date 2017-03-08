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
			$("#txt_numero_relacionado").mask("****-99999999");
			$("#txt_tipo_cambio").mask("9.9999");			
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

			$('#txt_FechaEmision_Relacionado').datepicker({
				showOn: 'button',					
				buttonImage: "<?php echo base_url()?>application/helpers/image/ico/calendar_icon.gif",
				buttonImageOnly: true,
				dateFormat: 'dd/mm/yy',
				buttonText: "##/##/####",
				maxDate: 'today',
				changeMonth: true ,
				changeYear: true
			});	
			$('#txt_FechaEmision_Relacionado').datepicker('setDate', 'today');	

			$('#txt_FechaPago').datepicker({
				showOn: 'button',					
				buttonImage: "<?php echo base_url()?>application/helpers/image/ico/calendar_icon.gif",
				buttonImageOnly: true,
				dateFormat: 'dd/mm/yy',
				buttonText: "##/##/####",
				maxDate: 'today',
				changeMonth: true ,
				changeYear: true
			});	
			$('#txt_FechaPago').datepicker('setDate', 'today');	

			$('#txt_fechaRetencion').datepicker({				
				buttonImage: "<?php echo base_url()?>application/helpers/image/ico/calendar_icon.gif",
				buttonImageOnly: true,
				dateFormat: 'dd/mm/yy',
				buttonText: "##/##/####",
				maxDate: 'today',
				changeMonth: true ,
				changeYear: true
			});	
			$('#txt_fechaRetencion').datepicker('setDate', 'today');

			$('#txt_fechaCambio').datepicker({				
				buttonImage: "<?php echo base_url()?>application/helpers/image/ico/calendar_icon.gif",
				buttonImageOnly: true,
				dateFormat: 'dd/mm/yy',
				buttonText: "##/##/####",
				maxDate: 'today',
				changeMonth: true ,
				changeYear: true
			});	
			$('#txt_fechaCambio').datepicker('setDate', 'today');	

			$("#txt_FechaPago").change(function(event) {
				var dato = $("#txt_FechaPago").val();
				$("#txt_fechaRetencion").val(dato);
				$("#txt_fechaCambio").val(dato);
			});

			$("#txt_numero_relacionado").change(function(e) {
				var upper = $("#txt_numero_relacionado").val().toUpperCase();
				$("#txt_numero_relacionado").val(upper);
				var codigo = $("#cmb_tipodocumentosunat").val();
				var letra = $("#txt_numero_relacionado").val().substring(0,1)

				if (codigo == "01") 
				{
					if (!$("#txt_numero_relacionado").val().match(/^[F0-9]{1}[A-Z0-9]{3}-[0-9]{8}$/) && $("#txt_numero_relacionado").val() != "")
					{
						alert("No esta ingresando el formato correcto para una Factura fisica o electronica.");
						$("#txt_numero_relacionado").val("");
					}
				}
				if (codigo == "07") 
				{
					if (!$("#txt_numero_relacionado").val().match(/^[F0-9]{1}[A-Z0-9]{3}-[0-9]{8}$/) && $("#txt_numero_relacionado").val() != "")
					{
						alert("No esta ingresando el formato correcto para una Nota de Credito fisica o electronica.");
						$("#txt_numero_relacionado").val("");
					}
				}
				if (codigo == "08") 
				{
					if (!$("#txt_numero_relacionado").val().match(/^[F0-9]{1}[A-Z0-9]{3}-[0-9]{8}$/) && $("#txt_numero_relacionado").val() != "")
					{
						alert("No esta ingresando el formato correcto para una Nota de Debito fisica o electronica.");
						$("#txt_numero_relacionado").val("");
					}
				}

			});

			$("#txt_numero_relacionado").focus(function(event) { 
				if($("#cmb_tipodocumentosunat").val() == 0){	$("#cmb_tipodocumentosunat").focus();	}	
			});			

			$("#txt_importe_total_relacionado,#txt_importepago_sin_retencion,#txt_tipo_cambio").keyup(function(event) {
				var factor_cargo = 1;
				var importe_retenido = 0;
				if ($("#cmb_Monedas option:selected")[0].innerText == "US Dollar")	//SI EL TIPO DE MONEDA ES DOLAR
				{
					factor_cargo = $("#txt_tipo_cambio").val();
				}
					var txt_importepago_sin_retencion = $("#txt_importepago_sin_retencion").val() * factor_cargo;
					var importe = ((txt_importepago_sin_retencion /100)*3).toFixed(2);
					var txt_importetotal_pagar = (txt_importepago_sin_retencion - importe).toFixed(2);
					$('#txt_importe_retenido').val(importe);
					$('#txt_importetotal_pagar').val(txt_importetotal_pagar);
			});

			$('#txt_cantidad, #txt_valorunitario,#txt_descuento, #txt_isc,#txt_descuentoglobal, #txt_porcentajedetraccion, #txt_montodetraccionreferencial, #txt_porcentajepercepcion, #txt_baseimponiblepercepcion, #txt_importepago_sin_retencion, #txt_importe_total_relacionado,txt_importe_retenido,#txt_importetotal_pagar,#txt_tipo_cambio').numeric({allow:'.'});
			$('#txt_numero_pago').numeric();

			Datos_Emisor();
			ncsistema.Buscar_Clientes();
			Listar_SeriesDocumentos('20','','','');

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
				//var cmb_tipodocumentocliente=$.trim($('#cmb_tipodocumentocliente').val());
				var cmb_tipodocumentocliente='6';
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
			url:'<?php echo base_url()?>retencion/Listar_ProductosDocumento',
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
		$('#div_ListadoDetalle').empty().append('');

		contador=0;
		newHtml='';
		newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaDetalle">';
		newHtml+='<thead>';
		newHtml+='<tr>';						
		newHtml+='<th width:3%>Nro.</td>';						
		newHtml+='<th width:10%>Eliminar</td>';					
		newHtml+='<th width:8%>Tipo Documento</td>';
		newHtml+='<th width:10%>Numero Documento</td>';
		newHtml+='<th width:20%>Fecha de Emision</td>';
		newHtml+='<th width:40%>Fecha de Pago</td>';
		newHtml+='<th width:10%>Nro de Pago</td>';	
		newHtml+='<th width:10%>Moneda Origen</td>';	
		newHtml+='<th width:10%>Importe Operacion/Origen</td>';	
		newHtml+='<th width:10%>Importe de Pago sin Retencion</td>';
		newHtml+='<th width:10%>Importe Total Retenido S/.</td>';	
		newHtml+='<th width:10%>Importe Total a Pagar S/.</td>';	
		//$('#txt_valorigv').val()
		newHtml+='</tr>';
		newHtml+='</thead>';
		newHtml+='<tbody>';
		//<input style="height:20px;width:95%" id="txt_login" type="text" value="'+rs.cantidadproducto+'"/>

		$.each(data,function(key,rs)
		{
			newHtml+='<tr>';
			newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';
			newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Retenciontemporal('+rs.tmp_ret+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
			newHtml+='<td style="text-align:left">'+rs.tipo_doc+'</td>';
			newHtml+='<td style="text-align:left">'+rs.num_doc+'</td>';
			newHtml+='<td style="text-align:left">'+rs.fec_emision+'</td>';
			newHtml+='<td style="text-align:right">'+rs.fec_pago+'</td>';
			newHtml+='<td style="text-align:right">'+rs.num_pago+'</td>';
			newHtml+='<td style="text-align:right">'+rs.moneda_origen+'</td>';
			newHtml+='<td style="text-align:right">'+(parseFloat(rs.imp_origen)).toFixed(2);+'</td>';
			newHtml+='<td style="text-align:right">'+(parseFloat(rs.imp_pago_sin_ret)).toFixed(2);+'</td>';
			newHtml+='<td style="text-align:right">'+rs.imp_retenido+'</td>';
			newHtml+='<td style="text-align:right">'+rs.imp_total_pagar+'</td>';
			//$('#txt_tipdocemisor').val(rs.tip_docemisor);							
			newHtml+='</tr>';	
			contador++;					
		});	
		newHtml+='</tbody>';
		newHtml+='</table>';

		$.trim($('#numeroitem').val(contador));
		contador++;
		$.trim($('#txt_numeroitem').val(contador));	

		$('#txt_importe_retenido_footer').val('0.00');
		$('#txt_importetotal_pagar_footer').val('0.00');

		if (variable!="")
		{
			$('#txt_importe_retenido_footer').val(variable.importeretenido_footer);
			$('#txt_importetotal_pagar_footer').val(variable.importetotal_footer);
		}
		$('#div_ListadoDetalle').empty().append(newHtml);	
		oTable=$('#Tab_ListaDetalle').dataTable({
			"bPaginate": true,
			"sScrollX": "100%",
			"sScrollXInner": "100%",
			"bScrollCollapse": true,
			"bJQueryUI": true
		});

		$("#Tab_ListaDetalle tbody").click(function(event) 
		{
			$(oTable.fnSettings().aoData).each(function (){
				$(this.nTr).removeClass('row_selected');
			});
			$(event.target.parentNode).addClass('row_selected');
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
					$('#txt_dato_adicional_condicion').val(1);

				}
				else if (result.status==1000)
				{
					document.location.href= '<?php echo base_url()?>usuario';
					return;
				}	
				else
				{
					ncsistema.filtro_datosAdicionales_Tabla("");
					$('#txt_dato_adicional_condicion').val(0);
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
			newHtml+='<td style="text-align:center"><input id="cbox_datoadicional_seleccion_'+key+'" type="checkbox" value="" name="cbox_datoadicional_seleccion_'+key+'" onChange="javascrip:Seleccionar_DatoAdicional('+key+')"></td>';
			newHtml+='<td style="text-align:left">'+rs.Codigo+'</td>';
			newHtml+='<td style="text-align:left">'+rs.Observacion+'</td>';
			newHtml+='<td style="text-align:left"><input style="width:95%" type="text" id="txt_datoadicional_valor_'+key+'" name="txt_datoadicional_valor_'+key+'" value="" placeholder="Máximo 40 caracteres" disabled="disabled" maxlength="40" /></td>';
			newHtml+='</tr>';						
		});	
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
		var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
		var cmb_tipodocumentosunat='20';// codigo retencion
		var txt_RazonSocialEmpresa=$.trim($('#txt_RazonSocialEmpresa').val());
		var txt_seriedocumento=$.trim($("#cmb_seriedocumentosunat").val());
		var txt_correlativodocumento=$.trim($("#txt_correlativodocumento").val());
		var txt_fecemisiondoc=$.trim($('#txt_FechaEmision').val());					
		var txt_numerodoccliente=$.trim($('#txt_numerodoccliente').val());
		var cmb_tipodocumentocliente='6';//RUC
		var txt_razonsocialcliente=$.trim($('#txt_razonsocialcliente').val());					
		var txt_correocliente=$.trim($('#txt_correocliente').val());
		var txt_direccioncliente=$.trim($('#txt_direccioncliente').val());
		var cantidadproduct=$.trim($('#numeroitem').val());

		var txt_importe_retenido_footer=$.trim($('#txt_importe_retenido_footer').val());	
		txt_importe_retenido_footer=txt_importe_retenido_footer.replace(",","");				

		var txt_importetotal_pagar_footer=$.trim($('#txt_importetotal_pagar_footer').val());	
		txt_importetotal_pagar_footer=txt_importetotal_pagar_footer.replace(",","");				

		var txt_emisorcorreo=$.trim($('#txt_emisorcorreo').val()); 
		var txt_emisorubigeo=$.trim($('#txt_emisorubigeo').val()); 
		var txt_emisordireccion=$.trim($('#txt_emisordireccion').val()); 
		var txt_emisorrubanizacion=$.trim($('#txt_emisorrubanizacion').val()); 
		var txt_emisorprovincia=$.trim($('#txt_emisorprovincia').val()); 
		var txt_emisordepartamento=$.trim($('#txt_emisordepartamento').val()); 
		var txt_emisordistrito=$.trim($('#txt_emisordistrito').val()); 
		var txt_emisorpaiscodigo=$.trim($('#txt_emisorpaiscodigo').val()); 

		var txt_clienteubigeo=$.trim($('#txt_clienteubigeo').val());
		var txt_clientedireccion=$.trim($('#txt_clientedireccion').val());
		var txt_clienteurbanizacion=$.trim($('#txt_clienteubanizacion').val());
		var txt_clienteprovincia=$.trim($('#txt_clienteprovincia').val());
		var txt_clientedepartamento=$.trim($('#txt_clientedepartamento').val());
		var txt_clientedistrito=$.trim($('#txt_clientedistrito').val());
		var txt_clientepaiscodigo=$.trim($('#txt_clientepaiscodigo').val());

		var txt_observacion=$.trim($('#txt_observacion').val());
		var txt_moneda_final=$.trim($('#txt_moneda_final').val());

		if (txt_RucEmpresa=='')
		{
			$('#div_MensajeValidacionEmpresa').fadeIn(0);
			$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Debe seleccionar una empresa de la lista</div>');
			setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
			return;
		}

		if (txt_seriedocumento=='0')
		{
			$('#div_MensajeValidacionEmpresa').fadeIn(0);
			$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Debe ingresar el número para el documento</div>');
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

		$.ajax({
			url:'<?php echo base_url()?>retencion/Guardar_RetentionHeader',
			type: 'post',
			dataType: 'json',
			data:
			{
				txt_RucEmpresa:txt_RucEmpresa,
				cmb_tipodocumentosunat:cmb_tipodocumentosunat,
				txt_RazonSocialEmpresa:txt_RazonSocialEmpresa,
				txt_seriedocumento:txt_seriedocumento,
				txt_correlativodocumento:txt_correlativodocumento,
				txt_fecemisiondoc:txt_fecemisiondoc,			
				txt_numerodocumentoproveedor:txt_numerodoccliente,
				cmb_tipodocumentocliente:cmb_tipodocumentocliente,
				txt_razonsocialproveedor:txt_razonsocialcliente,

				txt_importe_retenido_footer:txt_importe_retenido_footer,
				txt_importetotal_pagar_footer:txt_importetotal_pagar_footer,

				txt_correocliente:txt_correocliente,

				txt_emisorcorreo:txt_emisorcorreo,
				txt_emisorubigeo:txt_emisorubigeo,
				txt_emisordireccion:txt_emisordireccion,
				txt_emisorrubanizacion:txt_emisorrubanizacion,
				txt_emisorprovincia:txt_emisorprovincia,
				txt_emisordepartamento:txt_emisordepartamento,
				txt_emisordistrito:txt_emisordistrito,
				txt_emisorpaiscodigo:txt_emisorpaiscodigo, 

				txt_proveedorubigeo:txt_clienteubigeo,
				txt_proveedordireccion:txt_clientedireccion,
				txt_proveedorurbanizacion:txt_clienteurbanizacion,
				txt_proveedorprovincia:txt_clienteprovincia,
				txt_proveedordepartamento:txt_clientedepartamento,
				txt_proveedordistrito:txt_clientedistrito,
				txt_proveedorpaiscodigo:txt_clientepaiscodigo,
				txt_observacion:txt_observacion,

				tipo_registro:tipo_registro,
				txt_moneda_final:txt_moneda_final,

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
						if (cmb_tipodocumentosunat=='20')//RETENCION
						{
							$('#txt_numerodocumento_respuesta').val(result.numero);
							$('#txt_estadodocumento_respuesta').val('BORRADOR');
							$('#txt_descripciondocumento_respuesta').val('PENDIENTE DE DECLARAR');
							dialogregistroretencion.dialog( "open" );
						}
					}
					else //DECLARADO
					{								
						if (cmb_tipodocumentosunat=='20')//RETENCION
						{
							$('#txt_numerodocumento_respuesta').val(result.numero);
							$('#txt_estadodocumento_respuesta').val('POR PROCESAR');
							$('#txt_descripciondocumento_respuesta').val('ENVIADO A DECLARAR');
							dialogregistroretencion.dialog( "open" );
						}
					}
					/*
					"01";"FACTURA"
					"03";"BOLETA DE VENTA"
					"07";"NOTA DE CREDITO"
					"08";"NOTA DE DEBITO"
					*/
					Limpiar_DatosRegistroDocumento(1);
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

var item_boolean = 0;
var datoadicional_Cantidad = 0;
var datoadicional_Codigo = [];
var datoadicional_Valor = [];
var array_numpago = [];
var n=0;
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

function Eliminar_Retenciontemporal(tmp_prod)
{
	if(confirm("¿ Esta Seguro de Eliminar el Producto ?"))
	{
		$.ajax
		({
			url:'<?php echo base_url()?>retencion/Eliminar_Retenciontemporal',type:'post',dataType:'json',
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

	function Registrar_Comprobante() 
	{
		var valid = true;

		var txt_tipo_comprobante=$.trim($('#cmb_tipodocumentosunat option:selected')[0].innerText);
		var txt_codigo_documento=$.trim($('#cmb_tipodocumentosunat option:selected').val());
		var txt_numero_relacionado=$.trim($('#txt_numero_relacionado').val());
		var txt_FechaEmision_Relacionado=$.trim($('#txt_FechaEmision_Relacionado').val());
		var txt_FechaPago=$.trim($('#txt_FechaPago').val());
		var txt_numero_pago=$.trim($('#txt_numero_pago').val());
		var txt_moneda=$.trim($('#txt_moneda_pago').val());
		var txt_moneda_code=$("#cmb_Monedas option:selected").val();
		var txt_importe_origen=$.trim($('#txt_importe_total_relacionado').val());
		var txt_importepago_sin_retencion=$.trim($('#txt_importepago_sin_retencion').val());
		var txt_importe_retenido=$.trim($('#txt_importe_retenido').val());
		var txt_importetotal_pagar=$.trim($('#txt_importetotal_pagar').val());
		var txt_tipo_cambio=$.trim($("#txt_tipo_cambio").val());

		if (txt_codigo_documento == "07") 
		{
			txt_FechaPago='';
			txt_numero_pago='';
			txt_moneda='';
			txt_importepago_sin_retencion='';
			txt_importe_retenido='';
			txt_importetotal_pagar='';
		}

		allFields = $([]).add( $('#cmb_tipodocumentosunat')).add( $('#txt_numero_relacionado') ).add( $('#txt_importe_total_relacionado') ).add( $('#txt_numero_pago') ).add( $('#txt_importepago_sin_retencion') ).add( $('#txt_importe_retenido') ).add( $('#txt_importetotal_pagar') ).add( $('#cmb_Monedas')).add( $('#txt_tipo_cambio') );

		allFields.removeClass( "ui-state-error" );

		if (txt_tipo_comprobante=="[SELECCIONAR]")
		{					
			$('#cmb_tipodocumentosunat').addClass( "ui-state-error" );
			return;
		}

		if (txt_numero_relacionado=="")
		{					
			$('#txt_numero_relacionado').addClass( "ui-state-error" );
			return;
		}

		if (txt_importe_origen=="" || isNaN(txt_importe_origen))
		{					
			$('#txt_importe_total_relacionado').addClass( "ui-state-error" );

			return;
		}

		if (txt_moneda_code == 0) 
		{
			$('#cmb_Monedas').addClass( "ui-state-error" );
			return;
		}

		if (item_boolean == 0) 
		{
			if (txt_moneda=="")
			{					
				$('#txt_moneda_pago').addClass( "ui-state-error" );
				return;
			}
		}

		if (item_boolean == 0) 
		{
			if (txt_importepago_sin_retencion=="" || isNaN(txt_importepago_sin_retencion))
			{					
				$('#txt_importepago_sin_retencion').addClass( "ui-state-error" );

				return;
			}

			if (txt_importe_retenido=="" || isNaN(txt_importe_retenido))
			{					
				$('#txt_importe_retenido').addClass( "ui-state-error" );

				return;
			}
		}

		if (txt_tipo_cambio=="" || isNaN(txt_tipo_cambio))
		{					
			$('#txt_tipo_cambio').addClass( "ui-state-error" );

			return;
		}

		$.ajax({
			url:'<?php echo base_url()?>Retencion/Guardar_Registroretenciones',
			type: 'post',
			dataType: 'json',
			data:
			{
				txt_tipo_comprobante:txt_tipo_comprobante,
				txt_codigo_documento:txt_codigo_documento,
				txt_numero_relacionado:txt_numero_relacionado,
				txt_FechaEmision_Relacionado:txt_FechaEmision_Relacionado,
				txt_FechaPago:txt_FechaPago,
				txt_numero_pago:txt_numero_pago,
				txt_moneda:txt_moneda_code,
				txt_importe_origen:txt_importe_origen,
				txt_importepago_sin_retencion:txt_importepago_sin_retencion,
				txt_importe_retenido:txt_importe_retenido,
				txt_importetotal_pagar:txt_importetotal_pagar,
				item_boolean:item_boolean,
				txt_tipo_cambio:txt_tipo_cambio
			},
			beforeSend:function()
			{
				/*$('#div_MensajeValidacionEmpresa').fadeIn(0);
				$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="">Procesando, Espere por favor...</div>');*/
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

		$("#txt_moneda_final").val(txt_moneda_code);
		//dialog.dialog( "close" );

		if (txt_numero_pago == 1)
		{
			array_numpago[n]=[];
			array_numpago[n]['serie']=txt_numero_relacionado;
			array_numpago[n]['num_pago']=1;
			n++;
		}
		else{
			for (var i = 0; i < array_numpago.length; i++) {
				if (array_numpago[i]['serie'] == txt_numero_relacionado)
				{
					array_numpago[i]['num_pago']=txt_numero_pago;
				}
			}
		}
		$("#txt_numero_pago").val(parseFloat(txt_numero_pago)+1);

		return valid;
	}

	//Ventana para registrar nuevos adicionales
	$("#create-aditional" ).button().on("click", function() 
	{
		var txt_dato_adicional_condicion=$.trim($('#txt_dato_adicional_condicion').val());
		if (txt_dato_adicional_condicion==0)
		{
			ncsistema.filtro_datosAdicionales();
		}
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

	//Ventana para registrar nuevos productos
	$( "#create-user" ).button().on( "click", function() 
	{
		var cantidad=$.trim($('#numeroitem').val());

		cantidad++;
		$.trim($('#txt_numeroitem').val(cantidad));		

		Limpiar_DatosRegistroDocumento();

		dialog.dialog( "open" );
	});

	dialog = $("#dialog-form").dialog({
		autoOpen: false,
		width: 410,
		modal: true,
		buttons: 
		{
			"Registrar Item": Registrar_Comprobante,
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
		Registrar_Comprobante();
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
				dialogdatosemisor.dialog( "close" );

			}
		},

		close: function() 
		{
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
				dialogdatoscliente.dialog( "close" );

			}
		},

		close: function() 
		{
			form[ 0 ].reset();
		}
	});	

	dialogregistroretencion = $("#dialog-form-registroretencion").dialog({
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
				dialogregistroretencion.dialog( "close" );

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
	
});

function Moneda_Relacionado(moneda)
{
	$("#txt_importepago_sin_retencion").keyup();
	var texto_moneda = $("#cmb_Monedas option:selected")[0].innerText;

	if (moneda == 'USD') {
		if ($("#cmb_tipodocumentosunat").val() != "07") 
		{
			$("#txt_moneda_pago").val('Sol');
		}
		$("#txt_moneda_referencia").val(texto_moneda);
		$("#txt_item_hidden").show();	
		
	}
	else if (moneda == 'PEN') {
		if ($("#cmb_tipodocumentosunat").val() != "07") 
		{
			$("#txt_moneda_pago").val(texto_moneda);
		}
		$("#txt_item_hidden").hide();	
	}else
	{
		$("#txt_moneda_pago").val('');
		$("#txt_item_hidden").hide();	
	}
}

function validaMonto(importe_pago_sin_ret)
{
	var importe_total_relacionado = parseFloat($("#txt_importe_total_relacionado").val());

	if (isNaN(importe_total_relacionado))
	{
		$("#txt_importepago_sin_retencion").val('');
		$("#txt_importe_retenido").val('0.00');
		$("#txt_importetotal_pagar").val('0.00');
		$("#txt_importe_total_relacionado").focus();
	}

	else if (importe_total_relacionado < importe_pago_sin_ret && !isNaN(importe_total_relacionado))
	{
		alert("El importe de pago sin retencion no puede ser mayor que el importe total del documento relacionado");		
		$("#txt_importe_retenido").val('0.00');
		$("#txt_importetotal_pagar").val('0.00');
		$("#txt_importepago_sin_retencion").val('');
		$("#txt_importepago_sin_retencion").focus();
	}
	
}

function Numero_Pago_Ret(serienumero)
{
	var existe = 'NO';
	for (var i = 0; i < array_numpago.length; i++) {
		if (array_numpago[i]['serie'] == serienumero)
		{
			existe = 'SI';
			$("#txt_numero_pago").val(parseFloat(array_numpago[i]['num_pago'])+1);
		}
	}
	if (existe == 'NO')
	{
		$("#txt_numero_pago").val('1');
	}
}

function ocultardatos(dato)
{
	if (dato == "07") 
	{
		$(".hide").hide();
		item_boolean = 1;
	}
	else{
		$(".hide").show();
		item_boolean = 0;
	}
}

function ver_datosemisor()
{
	Datos_Emisor();
	dialogdatosemisor.dialog( "open" );
}

function ver_datoscliente()
{
	dialogdatoscliente.dialog( "open" );
}

function Calcular_Montos()
{
	var txt_config_valorprecio=$.trim($('#txt_config_valorprecio').val());

	var txt_cantidad=$.trim($('#txt_cantidad').val());
	var txt_descuento=($.trim($('#txt_descuento').val())).replace(',', '');
	var cod_tipafect=$.trim($('#cmb_tipoafectacion').val());
	var txt_valorigv=$.trim($('#txt_valorigv').val());//captura valor de 18
	var txt_valorotroscargos=$.trim($('#txt_valorotroscargos').val());
	if (txt_valorotroscargos=="" || isNaN(txt_valorotroscargos))
	{
		txt_valorotroscargos=0;
	}

	var tipo_registro=1;	

	if (txt_cantidad=="" || isNaN(txt_cantidad))
	{
		txt_cantidad=0;
	}
	if (txt_descuento=="" || isNaN(txt_descuento))
	{
		txt_descuento=0;
	}

	var txt_igv=0;
	var txt_valortotal=0;
	var txt_precio=0;
	var txt_descuentoIGV=0;
	var txt_preciototal=0;
	var txt_valorunitario=0;


	if (txt_config_valorprecio==0)
	{
		txt_valorunitario=($.trim($('#txt_valorunitario').val())).replace(',', '');
		if (txt_valorunitario=="" || isNaN(txt_valorunitario))
		{
			txt_valorunitario=0;
		}

		if (tipo_registro==1)//NORMAL
		{
			if (cod_tipafect=='10')//GRAVADO - OPERACION ONEROSA
			{				
				txt_igv=(((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento))*parseFloat(txt_valorigv/100));
				txt_valortotal=((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento));
				
				txt_precio=parseFloat(txt_valorunitario)*parseFloat(1+txt_valorigv/100);
				txt_descuentoIGV=parseFloat(txt_descuento)*parseFloat(1+txt_valorigv/100);
				txt_preciototal=(parseFloat(txt_cantidad)*parseFloat(txt_precio))-parseFloat(txt_descuentoIGV);
			}
		}
		$('#txt_igv').val(txt_igv.toFixed(10));
		$('#txt_valortotal').val(txt_valortotal.toFixed(10));

		$('#txt_precio').val(txt_precio.toFixed(10));
		$('#txt_descuentoIGV').val(txt_descuentoIGV.toFixed(10));
		$('#txt_preciototal').val(txt_preciototal.toFixed(10));
	}else
	{
		txt_precio=($.trim($('#txt_precio').val())).replace(',', '');
		txt_descuentoIGV=($.trim($('#txt_descuentoIGV').val())).replace(',', '');
		//txt_valorotroscargos: 10
		//txt_valorigv: 18 (1+igv/100)

		if (txt_precio=="" || isNaN(txt_precio))
		{
			txt_precio=0;
		}
		if (txt_descuentoIGV==""  || isNaN(txt_descuentoIGV))
		{
			txt_descuentoIGV=0;
		}
		if (tipo_registro==1)//NORMAL
		{
			if (cod_tipafect=='10')//GRAVADO - OPERACION ONEROSA
			{				
				txt_valorunitario=(parseFloat(txt_precio)/(1+(parseFloat(txt_valorigv)/100)+(parseFloat(txt_valorotroscargos)/100)));
				txt_descuento=(parseFloat(txt_descuentoIGV)/(1+(parseFloat(txt_valorigv)/100)+(parseFloat(txt_valorotroscargos)/100)));
				if (txt_cantidad==0)
				{
					txt_igv=0;
					txt_valortotal=0;
					txt_preciototal=0;
				}
				else{
					txt_igv=(((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento))*parseFloat(txt_valorigv/100));
					txt_valortotal=((parseFloat(txt_cantidad)*parseFloat(txt_valorunitario))-parseFloat(txt_descuento));
					txt_preciototal=(parseFloat(txt_cantidad)*parseFloat(txt_precio))-parseFloat(txt_descuentoIGV);
				}
			}

		}

		$('#txt_valorunitario').val(txt_valorunitario.toFixed(10));
		$('#txt_descuento').val(txt_descuento.toFixed(10));

		$('#txt_igv').val(txt_igv.toFixed(10));
		$('#txt_valortotal').val(txt_valortotal.toFixed(10));
		$('#txt_preciototal').val(txt_preciototal.toFixed(10));
	}
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
	$('#txt_descuentoIGV').val('');
	$('#txt_preciototal').val('');
}

function Limpiar_DatosRegistroDocumento(codigo = 0)
{
	$('#txt_FechaEmision').datepicker('setDate', 'today');	
	$('#txt_FechaEmision_Relacionado').datepicker('setDate', 'today');	
	$('#txt_FechaPago').datepicker('setDate', 'today');	
	$('#txt_fechaRetencion').datepicker('setDate', 'today');
	$('#txt_fechaCambio').datepicker('setDate', 'today');	

	if (codigo == 1) 
	{
		$('#cmb_seriedocumentosunat').val('0');
		$('#txt_correlativodocumento').val('-');
		$('#txt_FechaEmision').datepicker('setDate', 'today');	
		$('#txt_numerodoccliente').val('');
		$('#txt_correocliente').val('');
		$('#txt_observacion').val('');
		$('#txt_razonsocialcliente').val('');
		$('#txt_direccioncliente').val('');


		$('#txt_clientenombrecomercial').val('');
		$('#txt_clientecorreo').val('');
		$('#txt_clientepais').val('');
		$('#txt_clientepaiscodigo').val('');
		$('#txt_clientedepartamento').val('');
		$('#txt_clienteprovincia').val('');
		$('#txt_clientedistrito').val('');

		$('#txt_clienteubanizacion').val('');
		$('#txt_clientedireccion').val('');
		$('#txt_dato_adicional_condicion').val(0);
	}
}

function Numero_Documento(cod_seriedoc)
{
	if (cod_seriedoc == 0)
	{
		$('#txt_correlativodocumento').val("-");
		return;
	}
	var cmb_tipodocumentosunat='20';
	$('#txt_seriedocumento').val('0');
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
						$('#txt_correlativodocumento').val(rs.num_doc);
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
				$.trim($('#txt_correlativodocumento').val('-'));
				return;
			}
		}
	});
}

function Listar_SeriesDocumentos(cod_tipodocumento,cod_serie,tipodocreferencia,codserafectado)
{
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
				newHtml+='<select id="cmb_seriedocumentosunat" style="width:100%;height:25px" onChange="javascript:Numero_Documento(this.value)">';
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
			}
			else if (result.status==1000)
			{
				document.location.href= '<?php echo base_url()?>usuario';
				return;
			}
			else
			{
				newHtml='';
				newHtml+='<select id="cmb_seriedocumentosunat" style="width:100%;height:25px" onChange="javascript:Numero_Documento(this.value)">';
				newHtml+='<option value="0">[SELECCIONAR]</option>';
				newHtml+='</select>';		
				$('#div_seriedocumento').empty().append(newHtml);	
				//return;
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
				}
				else if (result.status==1000)
				{
					document.location.href= '<?php echo base_url()?>usuario';
					return;
				}
				else
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
			}
		}
	});
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
			<li><a href="#tabs-1">REGISTRO RETENCIONES</a></li>
		</ul>
		<div id="tabs-1" style="width:95%;float:left">
			<div id="div_datosempresa" style="width:100%; float:left; margin-top:10px; border: 1px solid #a6c9e2; border-radius:5px;">
				<input type="hidden" id="txt_tipoderegistrodocumento" value="0"  />					
				<input type="hidden" id="txt_valorigv" value="<?php echo $valor_igv;?>"  />
				<input type="hidden" id="txt_valorotroscargos" value="<?php echo $valor_otroscargos;?>"  />
				<input type="hidden" id="txt_modificarregistro" value="0"  />
				<input type="hidden" id="txt_config_valorprecio" value="<?php echo $Config_ValorPrecio;?>"  />
				<input type="hidden" id="txt_dato_adicional_condicion" value="0"  />

				<table border="0" width="80%" style="border-collapse:separate; border-spacing:1px 1px;" cellpadding="3" class="tablaFormulario">
					<tr><td><label class="columna"></label></td></tr>
					<tr>
						<td style="text-align:right;width:12%" ><label class="columna">Serie:</label></td>
						<td>
							<table>
								<tr>
									<td style="text-align:left;width:15%">
										<div id="div_seriedocumento">
											<select id="cmb_seriedocumentosunat" style="width:100%;height:25px" onChange="javascript:Numero_Documento(this.value)">
												<option value="0">[SELECCIONAR]</option>
											</select>
										</div>
									</td>

									<td style="text-align:right;width:12%" ><label class="columna">Numero:</label></td>
									<td style="text-align:left;width:15%">
										<input style="width:96%" id="txt_correlativodocumento" type="text" value="-" disabled="disabled">
									</td>
								</tr>
							</table>
						</td>
						<td style="text-align:right;width:20%"><label class="columna">Fec. Emisión:</label></td>
						<td style="text-align:left;width:6%">
							<input id="txt_FechaEmision" disabled="disabled" type="text" value="" style="width:70px"/>
						</td>
						<td style="text-align:right;width:10%" >
							<label class="columna">Tasa (%): </label>
							<input style="width:35%" type="text" id="txt_tasa" value="3.00"  disabled="disabled" />
						</td>
					</tr>

					<tr>
						<td style="text-align:right;width:12%" ><label class="columna">Ruc Proveedor:</label></td>
						<td style="text-align:left;width:15%"><input style="width:98%" type="text" id="txt_RucEmpresa" value="<?php echo trim(utf8_decode($Ruc_Empresa));?>"  disabled="disabled" /></td>
						<td style="text-align:right;width:17%"><label class="columna">Razón Social Proveedor:</label></td>
						<td style="text-align:left;width:46%; vertical-align:top" colspan="2" >
							<input style="width:98%" type="text" id="txt_RazonSocialEmpresa" value="<?php echo trim(utf8_decode($Razon_Social));?>"
							disabled="disabled" />
						</td>

						<td style="text-align:left;width:10%; vertical-align:middle">
							<a href="javascript:ver_datosemisor()">
								<img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nc_buscar.png" title="Ver Detalle" width="15"  border="0" >	
							</a>
						</td>
					</tr>
					<tr>
						<td style="text-align:right" class="columna"><div id="div_titulodocumento">Documento Cliente:</div></td>

						<td style="width:25%;text-align:left">
							<input style="width:98%" id="txt_numerodoccliente" type="text" value="" disabled="disabled" width="95%" >
						</td>

						<td style="text-align:right"><label class="columna"><div id="div_catalogonombcliente">Razón Social Cliente:</div></label></td>
						<td style="text-align:left" colspan="2">
							<input id="txt_razonsocialcliente" type="text" value="" style="width:98%;" >
						</td>
						<td align="left" style="text-align:left;width:5%; vertical-align:middle" >
							<div id="div_verdatoscliente">
								<a href="javascript:ver_datoscliente()">
									<img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nc_buscar.png" title="Ver Detalle" width="15"  height="15" border="0" >	
								</a>
							</div>
						</td>
					</tr>
					<tr>
						<td style="text-align:right"><label class="columna">Correo Cliente:</label></td>
						<td style="text-align:left">
							<input id="txt_correocliente" type="text" value="" style="width:98%;">
						</td>
						<td style="text-align:right"><label class="columna">Dirección Cliente:</label></td>
						<td style="text-align:left" colspan="2">
							<input id="txt_direccioncliente" type="text" value="" disabled="disabled" style="width:98%;">
						</td>
					</tr>
					<tr>
						<td style="text-align: right; bottom: 3em; position: relative;"><label class="columna">Observación: </label></td>
						<td style="text-align:left;">
							<textarea id="txt_observacion" type="text" value="" style="width:98%;" ></textarea>
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
			<div id="div_ListadoDetalle" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:1px">
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
										<span class="ui-button-icon-left ui-icon ui-icon-document"></span>Nuevo Item</button>	
									</td>										
								</tr>
								<tr>
									<td style="text-align:left;height:110px" >							

									</td>										
								</tr>
							</table>

						</td>
						<td style="text-align:right;width:30%" valign="top">
							<table width="100%" border="0" >
								<tr>							
									<td style="text-align:right;width:20%">Importe Total Retenido S./</td>
									<td style="text-align:right;width:15%" >
										<input type="text" id="txt_importe_retenido_footer" value="0.00" disabled="disabled" style="width:80%;text-align:right" >
									</td>			
								</tr>
								<tr>
									<td style="text-align:right">Importe Total a Pagar S./</td>
									<td style="text-align:right">
										<input type="text" id="txt_importetotal_pagar_footer" value="0.00" disabled="disabled" style="width:80%;text-align:right">
									</td>					
								</tr>
							</table>
						</td>			
					</tr>
				</table>
			</div>
			<div id="div_footer" style="width:100%; float:left; margin-top:2px; border: 1px solid #a6c9e2; border-radius:5px;">
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

	<div id="dialog-form" title="Crear un Nuevo ITEM">
		<!--<p class="validateTips">All form fields are required.</p>-->
		<input type="hidden" id="numeroitem" value="0" >
		<input type="hidden" id="txt_moneda_final" value="" >
		<form>
			<table width="100%" border="0px">
				<tr>
					<td style="width:50%;font-weight:bold">
						N° Item:
					</td>
					<td style="width:50%" colspan="4">
						<input type="text" name="txt_numeroitem" id="txt_numeroitem" value="1" disabled="disabled" class="">
					</td>
				</tr>
				<tr>
					<td style="width:50%;font-weight:bold">
						Tipo Doc.Relacionado :
					</td >
					<td style="width:50%;" colspan="4">
						<select id="cmb_tipodocumentosunat" style="" onChange="javascript:ocultardatos(this.value)" >
							<option value="0">[SELECCIONAR]</option>
							<?php foreach ( $Listar_TipodeDocumento as $v):	?>
								<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(utf8_decode($v['no_corto']));?> </option>
							<?php  endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td style="width:50%;font-weight:bold">
						Numero Documento Relacionado :
					</td >
					<td style="width:50%" colspan="4">
						<input type="text" name="txt_numero_relacionado" id="txt_numero_relacionado" class="" maxlength="13" onChange="javascript:Numero_Pago_Ret(this.value)" >
					</td>
				</tr>
				<tr>
					<td style="width:50%;font-weight:bold">
						Fecha Emision Doc.Relacionado :
					</td >
					<td style="width:50%" colspan="4">
						<table border="0" style="border-collapse:separate; border-spacing:0px 0px;">
							<tr>
								<td>
									<input id="txt_FechaEmision_Relacionado" type="text" value="" disabled="" />
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="width:50%;font-weight:bold">
						Importe Total Doc.Relacionado :
					</td >
					<td style="width:50%" colspan="4">
						<input type="text" name="txt_importe_total_relacionado" id="txt_importe_total_relacionado" value="" placeholder="0.00" class="">
					</td>
				</tr>
				<tr>
					<td style="width:50%;font-weight:bold">
						Moneda Doc.Relacionado :
					</td >
					<td style="width:50%;" colspan="4">
						<select id="cmb_Monedas" style="" onChange="javascript:Moneda_Relacionado(this.value)" >
							<option value="0">[SELECCIONAR]</option>
							<?php foreach ( $Listar_Monedas as $v):	?>
								<option value="<?php echo trim($v['codigo']); ?>"><?php echo trim(utf8_decode($v['nombre']));?></option>
							<?php  endforeach; ?>
						</select>
					</td>
				</tr>
				<tr class="hide">
					<td style="width:50%;font-weight:bold">
						Fecha De Pago :
					</td >
					<td style="width:50%" colspan="4">
						<table border="0" style="border-collapse:separate; border-spacing:0px 0px;">
							<tr>
								<td>
									<input id="txt_FechaPago" type="text" value="" onChange="" disabled="disabled"/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="hide">
					<td style="width:50%;font-weight:bold">
						Numero de Pago :
					</td >
					<td style="width:50%" colspan="4">
						<input name="txt_numero_pago" id="txt_numero_pago" placeholder="0" type="text" disabled="disabled" value="1">
					</td>
				</tr>
				<tr class="hide">
					<td style="width:50%;font-weight:bold">
						Importe Pago Sin Retencion :
					</td >
					<td style="width:50%" colspan="4">
						<input name="txt_importepago_sin_retencion" id="txt_importepago_sin_retencion" placeholder="0.00" type="text" onchange="javascript:validaMonto(this.value)">
					</td>
				</tr>
				<tr class="hide">
					<td style="width:50%;font-weight:bold">
						Moneda Pago :
					</td >
					<td style="width:50%;" colspan="4">
						<input name="txt_moneda_pago" id="txt_moneda_pago" type="text" disabled="disabled">
					</td>
				</tr>
				<tr class="hide">
					<td style="width:50%;font-weight:bold">
						Importe Total Retenido :
					</td >
					<td style="width:50%" colspan="4">
						<input name="txt_importe_retenido" id="txt_importe_retenido" placeholder="0.00" type="text" disabled="disabled">
					</td>
				</tr>
				<tr>
					<td style="width:50%;font-weight:bold">
						Fecha De Retencion:
					</td >
					<td style="width:50%" colspan="4">
						<table border="0" style="border-collapse:separate; border-spacing:0px 0px;">
							<tr>
								<td>
									<input id="txt_fechaRetencion" type="text" value="" disabled="disabled"/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="hide">
					<td style="width:50%;font-weight:bold">
						Importe Total Pagar Neto:
					</td>
					<td style="width:50%" colspan="4">
						<input name="txt_importetotal_pagar" id="txt_importetotal_pagar" placeholder="0.00" type="text" disabled="disabled">
					</td>
				</tr>
				<tr>
					<table id="txt_item_hidden" style="margin-top:3%;border: 1px solid #a6c9e2;" hidden="">
						<tr>
							<td style="width:50%;font-weight:bold">
								Moneda Referencia Tipo Cambio :
							</td >
							<td style="width:50%" colspan="4">
								<input style="margin-left: 13px" name="txt_moneda_referencia" id="txt_moneda_referencia" placeholder="Sol" type="text" disabled="disabled">
							</td>
						</tr>
						<tr>
							<td style="width:50%;font-weight:bold">
								Factor Tipo Cambio Moneda:
							</td >
							<td style="width:50%" colspan="4">
								<input style="margin-left: 13px" name="txt_tipo_cambio" id="txt_tipo_cambio" placeholder="0.00" value="3.1400" type="text">
							</td>
						</tr>
						<tr>
							<td style="width:50%;font-weight:bold">
								Fecha Cambio:
							</td >
							<td style="width:50%" colspan="4">
								<table border="0" style="border-collapse:separate; border-spacing:0px 0px;">
									<tr>
										<td>
											<input style="margin-left: 13px" id="txt_fechaCambio" type="text" value="" disabled="disabled"/>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</tr>
				<tr>
					<td> <label class="columna"></label>
					</td>
					<td> 
					</td>
				</tr>
			</table>
		</form>
	</div>

	<div id="dialog-form-registroretencion" title="Respuesta de Registro">
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


	<div id="dialog-form-adicional" title="Datos Adicionales">
		<form>
			<table width="100%" border="0px">				
				<tr>
					<td style="font-weight:bold" colspan="5">
						<div id="div_Busqueda_DatosAdicionales" style="width:100%;border:solid 0px;float:left; text-align:center;margin-top:10px">
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>

</body>	
</html>