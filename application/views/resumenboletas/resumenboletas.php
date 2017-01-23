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

	<script type="text/javascript">var urlexportardatos="<?php echo base_url();?>"</script>

	<script type="text/javascript">

		$(document).ready(function()
		{
			$.datepicker.setDefaults($.datepicker.regional["es"]);
			$("#tabs").tabs();
			ncsistema.Listar_DocumentosdeResumen();

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


			//Carga los modales al iniciar la vista
			dialogoforPendientes = $("#dialog-for-Pendientes").dialog({
				autoOpen: false,
				height: 400,
				width: 1200,
				modal: true,
				buttons:
				{
					"Aceptar": function()
					{
						dialogoforPendientes.dialog( "close" );
							//$("#dialog-for-Pendientes").parent().hide();
							//$("#modal-base").hide();
						}
					},

					close: function()
					{
						//alert("entro");
						//$("#dialog-for-Pendientes").parent().hide();
						//$("#modal-base").hide();
					}
				});

			dialogforRespuesta = $("#dialog-for-Pendientes-confirm").dialog({
				autoOpen: false,
				height: 150,
				width: 250,
				modal: true,
				buttons:
				{
					"Aceptar": function()
					{
						dialogforRespuesta.dialog( "close" );
							//$("#dialog-for-Pendientes").parent().hide();
							//$("#modal-base").hide();
						}
					},

					close: function()
					{
						//alert("entro");
						//$("#dialog-for-Pendientes").parent().hide();
						//$("#modal-base").hide();
					}
				});

			//Carga los modales al iniciar la vista

		})


		ncsistema=
		{


			Mostrar_Modal:function(){
				dialogoforPendientes.dialog( "open" );
				$( "#dialog-for-Pendientes" ).css("height","auto");
				//$("#dialog-for-Pendientes").parent().show();
				//$("#modal-base").show();
			},


			Guardar_ResumenBoletas:function(tip_evento)
			{
				var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());
				var txt_FechaEmision=$.trim($('#txt_FechaEmision').val());
				var txt_tipdocemisor=$.trim($('#txt_tipdocemisor').val());

				var txt_fecemisiondoc=$.trim($('#txt_fecemisiondoc').val());


				if (txt_datosseleccionados==0)
				{
					$('#div_MensajeValidacionEmpresa').fadeIn(0);
					$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">No existen datos seleccionado</div>');
					setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
					return;
				}


				$.ajax({
					url:'<?php echo base_url()?>resumenboletas/Guardar_ResumenBoletas',
					type: 'post',
					dataType: 'json',
					data:
					{
						tip_evento:tip_evento,
						txt_RucEmpresa:txt_RucEmpresa,
						txt_datosseleccionados:txt_datosseleccionados,
						txt_FechaEmision:txt_FechaEmision,
						txt_tipdocemisor:txt_tipdocemisor,
						txt_fecemisiondoc:txt_fecemisiondoc
					},
					beforeSend:function()
					{

						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
					},
					success:function(result)
					{
						if(result.status==1)
						{

							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El registro de los datos se realiz&oacute; con &eacute;xito</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							ncsistema.Listar_DocumentosdeResumen();
							$('#txt_datosseleccionados').val('');
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
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:lefts"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">'+result.mensaje+'</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
					}
				});
			},



			Listar_DocumentosdeResumen:function(codigo = 0)
			{
					//console.log("ingresa ready2");
					var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
					var txt_FechaEmision=$.trim($('#txt_FechaEmision').val());
					var txt_RazonSocialEmpresa=$.trim($('#txt_RazonSocialEmpresa').val());
					//console.log(txt_RucEmpresa);
					//console.log(txt_FechaEmision);
					//console.log(txt_RazonSocialEmpresa);

					$.ajax({
						url:'<?php echo base_url()?>resumenboletas/Listar_DocumentosdeResumen',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_RucEmpresa:txt_RucEmpresa,
							txt_FechaEmision:txt_FechaEmision,
							codigo:codigo
						},
						beforeSend:function()
						{

						},
						success:function(result)
						{

							if(result.status==1)
							{
								ncsistema.Listar_DocumentosdeResumenTabla(result.data,codigo);

							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								if (result.cod==1) {
									ncsistema.Listar_DocumentosdeResumenTabla("",1);
								}else{
									ncsistema.Listar_DocumentosdeResumenTabla("",'');
								}
							}
						}
					});
				},


				Listar_DocumentosdeResumenTabla:function(data,codigo_tabla)
				{
					//console.log("ingresa");
					if (codigo_tabla == 1) {
						$('#div_ListadoEmpresa_modal').empty().append('');
					}
					else{
						$('#div_ListadoEmpresa').empty().append('');
					}
					$('#txt_fecemisiondoc').val('');
					$('#txt_tipdocemisor').val('');

					$('#txt_datosseleccionados').val('');

					newHtml='';
					if (codigo_tabla == 1) {
						newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa_modal">';
					}
					else{
						newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
					}
					newHtml+='<thead>';
					newHtml+='<tr>';
					newHtml+='<th width:3%>Nro.</td>';
						//newHtml+='<th width:5%>Opcion</td>';
						newHtml+='<th width:10%>T.Doc.</td>';
						newHtml+='<th hidden width:0%>T.Cod.Doc.</td>';
						newHtml+='<th width:20%>Documento</td>';
						newHtml+='<th width:20%>F.Emis.</td>';
						newHtml+='<th width:50%>Mon.</td>';
						newHtml+='<th width:10%>OP.Grav.</td>';
						newHtml+='<th width:10%>IGV</td>';
						newHtml+='<th width:10%>Op.Exonerado</td>';
						newHtml+='<th width:10%>Op.No.Gravadas</td>';
						newHtml+='<th width:10%>Op.Gratuitas</td>';
						newHtml+='<th width:10%>Imp.Total</td>';
						newHtml+='<th width:10%>Est.SUNAT</td>';
						newHtml+='<th width:10%>Est.Declarar</td>';
						if (codigo_tabla == 1) {
							newHtml+='<th width:10%>Declarar</td>';
						}
						newHtml+='<th width:10%>Eliminar</td>';
						newHtml+='</tr>';
						newHtml+='</thead>';
						newHtml+='<tbody>';
					//<input style="height:20px;width:95%" id="txt_login" type="text" value="'+rs.cantidadproducto+'"/>
					contador=0;
					$.each(data,function(key,rs)
					{

						newHtml+='<tr>';
						newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';

						if (rs.tip_reg==1)
						{
								//newHtml+='<td style="text-align:center"></td>';
								contador++;
							}
							/*else
							{
								newHtml+='<td style="text-align:left"><input id="cbox_seleccion_'+key+'" type="checkbox" value="" name="cbox_seleccion_'+key+'" onChange="javascrip:Seleccionar_DatosBusqueda('+key+',\''+rs.cod_tipdoc+'\',\''+rs.numer_doc+'\')"></td>';
							}
							*/
							
							newHtml+='<td style="text-align:left">'+rs.tipo_doc+'</td>';

							if (codigo_tabla == 1) {
								newHtml+='<td hidden id="modal_tipo_doc_'+contador+'" style="text-align:left">'+rs.cod_tipdoc+'</td>';
								newHtml+='<td id="modal_comprobante_'+contador+'" style="text-align:left">'+rs.numer_doc+'</td>';
							}else{
								newHtml+='<td hidden id="tipo_doc_'+contador+'" style="text-align:left">'+rs.cod_tipdoc+'</td>';
								newHtml+='<td id="comprobante_'+contador+'" style="text-align:left">'+rs.numer_doc+'</td>';
							}
							
							newHtml+='<td style="text-align:left">'+rs.fec_emision+'</td>';
							newHtml+='<td style="text-align:left">'+rs.moneda+'</td>';
							newHtml+='<td style="text-align:left">'+rs.op_gravado+'</td>';
							newHtml+='<td style="text-align:left">'+rs.igv+'</td>';
							newHtml+='<td style="text-align:left">'+rs.op_exonerado+'</td>';
							newHtml+='<td style="text-align:left">'+rs.op_nogravado+'</td>';
							newHtml+='<td style="text-align:left">'+rs.op_gratis+'</td>';
							newHtml+='<td style="text-align:left">'+rs.imp_total+'</td>';
							newHtml+='<td style="text-align:left">'+rs.est_sunat+'</td>';
							newHtml+='<td style="text-align:left">'+rs.est_declarar+'</td>';

							if (rs.tip_reg==1)
							{
								if (codigo_tabla == 1) {
									newHtml+='<td style="text-align:center"><a href="javascript:Declarar_Comprobante('+contador+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/actualizar.png" title="Declarar" width="15"  height="15" border="0" ></a></td>';
								}
								newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_DocumentoBoletaResumen('+contador+','+codigo_tabla+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
								$('#txt_fecemisiondoc').val(rs.fec_emision);
							}
							else
							{
								newHtml+='<td style="text-align:center"></td>';
							}


							$('#txt_tipdocemisor').val(rs.tip_docemisor);

							newHtml+='</tr>';
						});
					newHtml+='</tbody>';
					newHtml+='</table>';
					//console.log(newHtml);

					$('#txt_cantidad').val(contador);

					if (codigo_tabla == 1) {
						$('#div_ListadoEmpresa_modal').empty().append(newHtml);

						oTable=$('#Tab_ListaEmpresa_modal').dataTable({
							"bPaginate": true,
							"sScrollX": "110%",
							"sScrollXInner": "100%",
							"bScrollCollapse": true,
							"bJQueryUI": true
						});

						$("#Tab_ListaEmpresa_modal tbody").click(function(event)
						{
							$(oTable.fnSettings().aoData).each(function (){
								$(this.nTr).removeClass('row_selected');
							});
							$(event.target.parentNode).addClass('row_selected');
						});
					}
					else{
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
					}

				},

				Guardar_SummaryHeader:function()
				{

					var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
					var txt_RazonSocialEmpresa=$.trim($('#txt_RazonSocialEmpresa').val());
					var txt_cantidad=$.trim($('#txt_cantidad').val());
					var txt_fecemisiondoc=$.trim($('#txt_fecemisiondoc').val());
					var txt_tipdocemisor=$.trim($('#txt_tipdocemisor').val());


					cbox_mensajeguardar=0;
					if ($("#cbox_mensajeguardar").is(":checked"))
					{
						cbox_mensajeguardar=1;
					}
					if (cbox_mensajeguardar==0)
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">No ha aceptado la responsabilidad de esta declaraci�n</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}

					if (txt_cantidad==0)
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">No existe datos para el registro</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}

					$.ajax({
						url:'<?php echo base_url()?>resumenboletas/Guardar_SummaryHeader',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_RucEmpresa:txt_RucEmpresa,
							txt_RazonSocialEmpresa:txt_RazonSocialEmpresa,
							txt_fecemisiondoc:txt_fecemisiondoc,
							txt_tipdocemisor:txt_tipdocemisor
						},
						beforeSend:function()
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
						},
						success:function(result)
						{
							if(result.status==0)
							{
								ncsistema.Listar_DocumentosdeResumen(1);
								ncsistema.Mostrar_Modal();
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(300);},1000);

								
								return;
							}
							else if(result.status==1)
							{
								/*
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Se gener� el resumen '+result.codigo_baja+'</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								*/
								alert('Se generó el '+result.codigo_resumen);

								Limpiar_DatosRegistroDocBajas();
								//ncsistema.Listar_DocumentosdeResumen();
								ncsistema.Listar_DocumentosdeResumenTabla("",'');
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:lefts"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error con el registro de los datos</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}
					});
				},
			}

			function Limpiar_DatosRegistroDocBajas()
			{
				//$('#Cmb_TipoDocumento').val('0');
				$('#txt_Motivo').val('');
				document.getElementsByName('cbox_mensajeguardar')[0].checked=false;
			}

			function Declarar_Comprobante(codigo)
			{
				var var_comprobante = $("[id='modal_comprobante_"+codigo+"']").text();
				var var_tipo_doc = $("[id='modal_tipo_doc_"+codigo+"']").text();
				var var_ruc=$.trim($('#txt_RucEmpresa').val());

				$.ajax
				({
					url:'<?php echo base_url()?>resumenboletas/Declarar_Comprobante',type:'post',dataType:'json',
					data:
					{
						var_ruc:var_ruc,
						var_tipo_doc:var_tipo_doc,
						var_comprobante:var_comprobante
					},
					beforeSend:function()
					{

						/*
						$("#span_respuesta").html('El comprobante N°"'+var_comprobante+'" esta siendo procesado.');
						dialogforRespuesta.dialog( "open" );
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>')
						*/

					},
					success:function(result)
					{
						$("#span_respuesta").html('El comprobante N°"'+var_comprobante+'" fue procesado.');
						dialogforRespuesta.dialog( "open" );
						ncsistema.Listar_DocumentosdeResumen(1);
							/*
							if(result.status==1)
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminaci&oacute;n del Documento se realiz&oacute; con &eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_DocumentosdeResumen();
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el documento</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
							*/
						}
					});
			}

			function Eliminar_DocumentoBoletaResumen(codigo,tabla)
			{	
				if (tabla == 0) {
					var var_comprobante = $("[id='comprobante_"+codigo+"']").text();
					var var_tipo_doc = $("[id='tipo_doc_"+codigo+"']").text();
					var var_ruc=$.trim($('#txt_RucEmpresa').val());
				}
				else{
					var var_comprobante = $("[id='modal_comprobante_"+codigo+"']").text();
					var var_tipo_doc = $("[id='modal_tipo_doc_"+codigo+"']").text();
					var var_ruc=$.trim($('#txt_RucEmpresa').val());
				}
				if(confirm("� Esta Seguro de Eliminar el documento ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>resumenboletas/Eliminar_DocumentoBoletaResumen',type:'post',dataType:'json',
						data:
						{
							var_ruc:var_ruc,
							var_tipo_doc:var_tipo_doc,
							var_comprobante:var_comprobante
						},
						beforeSend:function()
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>')
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminaci&oacute;n del Documento se realiz&oacute; con &eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_DocumentosdeResumen(1);
								ncsistema.Listar_DocumentosdeResumen();
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el documento</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}
					});
				}

			}

			function Seleccionar_DatosBusqueda(key,cod_tipdoc,documento)
			{
				var txt_datosseleccionados=$.trim($('#txt_datosseleccionados').val());


				if ($("#cbox_seleccion_"+key).is(":checked"))
				{
					if (txt_datosseleccionados=='')
					{
						txt_datosseleccionados=(cod_tipdoc+'-'+documento);
					}
					else
					{
						txt_datosseleccionados=txt_datosseleccionados+','+(cod_tipdoc+'-'+documento);
					}
				}
				else
				{
					txt_datosseleccionados=txt_datosseleccionados.replace(","+cod_tipdoc+'-'+documento, "");
					txt_datosseleccionados=txt_datosseleccionados.replace(cod_tipdoc+'-'+documento, "");
				}
				$('#txt_datosseleccionados').val($.trim(txt_datosseleccionados));
			}

		</script>

	</head>
	<body>
		<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>

		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">RESUMEN DE BOLETAS</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">

				<div id="div_datosempresa" style="width:100%; float:left; margin-top:10px; border: 1px solid #a6c9e2;border-radius:5px;">
					<input style="width:15%" type="hidden" id="txt_cantidad"  value="0" />
					<input style="width:15%" type="hidden" id="txt_fecemisiondoc"  value="" />
					<input style="width:15%" type="hidden" id="txt_tipdocemisor"  value="" />

					<input id="txt_datosseleccionados" type="hidden" value="" />

					<table border="0" width="65%" style="border-collapse:separate; border-spacing:8px 1px;" cellpadding="3" class="tablaFormulario">
						<tbody>
							<tr><td><label class="columna"></label></td>
								<tr>
									<td style="text-align:right;width:10%" ><label class="columna">RUC:</label></td>
									<td style="text-align:left;width:15%"><input style="width:90%" type="text" id="txt_RucEmpresa" value="<?php echo trim(utf8_encode($Ruc_Empresa));?>"  disabled="disabled" /></td>
									<td style="text-align:right;width:8%"><label class="columna">Raz&oacute;n Social:</label></td>
									<td style="text-align:left;width:30%"><input style="width:95%" type="text" id="txt_RazonSocialEmpresa" value="<?php echo trim(utf8_encode($Razon_Social));?>" disabled="disabled" /></td>
								</tr>
								<tr>
									<td style="text-align:right"><label class="columna">Fecha Emision:</label></td>
									<td style="text-align:left">
										<input style="width:70%" id="txt_FechaEmision" disabled="disabled" type="text" value="" />
									</td>
							<!--
							<td style="text-align:right"><label class="columna">Opciones:</label></td>
							<td style="text-align:left">
								<button style="width:70; height:32px" id="btn_IniciarSesion" title="Adicionar"
									class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left"
									onClick="ncsistema.Guardar_ResumenBoletas(1)">
									<span class="ui-button-icon-left ui-icon ui-icon-circle-plus"></span>
									<span class="ui-button-text">Adicionar</span></button>
								<button style="width:70; height:32px" id="btn_IniciarSesion" title="Anular"
									class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left"
									onClick="ncsistema.Guardar_ResumenBoletas(2)">
									<span class="ui-button-icon-left ui-icon ui-icon-circle-close"></span>
									<span class="ui-button-text">Anular</span></button>
								<button style="width:70; height:32px" id="btn_IniciarSesion" title="Dar de baja"
									class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left"
									onClick="ncsistema.Guardar_ResumenBoletas(3)">
									<span class="ui-button-icon-left ui-icon ui-icon-circle-triangle-s"></span>
									<span class="ui-button-text">Dar de Baja</span></button>


								Esto estuvo comentado
								<button style="height:35px;width:80px" id="btn_IniciarSesion" title="Iniciar Sesi�n"
									onClick="ncsistema.Guardar_ResumenBoletas(1)">Adicionar</button>
								<button style="height:35px;width:80px" id="btn_IniciarSesion" title="Iniciar Sesi�n"
									onClick="ncsistema.Guardar_ResumenBoletas(2)">Anular</button>
								<button style="height:35px;width:90px" id="btn_IniciarSesion" title="Iniciar Sesi�n"
									onClick="ncsistema.Guardar_ResumenBoletas(3)">Dar de Baja</button>
							</td>
						-->
					</tr>
					<tr>
						<td style="text-align:right;" ><label class="columna"></label></td>
						<td style="text-align:left; border: solid 0px" colspan="3" width="100%">
							<a href="javascript:ncsistema.Listar_DocumentosdeResumen()">
								<!--<img src="<?php echo base_url();?>application/helpers/image/ico/buscar32.png" width="35" height="35"/>-->
								<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:120px; height:32px" type="submit">
									<span class="ui-button-icon-left ui-icon ui-icon-search"></span>
									<span class="ui-button-text">Buscar</span></button>
								</a>
							</td>
						</tr>
						<tr>
							<td style="text-align:right" ></td>
							<td style="text-align:left" colspan="3">
								<div style="width:100%;height:15px;border:solid 0px;margin-left:4px;margin-right:4px;margin-top:0px;text-align:center;float:left">
									<div id="div_MensajeValidacionEmpresa" style="width:100%;float:left;font-size:9px;"></div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>

			</div>

			<div id="dialog-for-Pendientes" title="Boletas pendientes">
				<form>
					<div id="div_Modal_Pendientes" style="width:100%; float:left; margin-top:0px; border: 1px solid #a6c9e2; border-radius:4px;">
						<div id="div_ListadoEmpresa_modal" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:1px">
						</div>
					</div>
				</form>
			</div>

			<div id="dialog-for-Pendientes-confirm" title="titulo">
				<form>
					<div id="div_Modal_Respuesta" style="width:100%; float:left; margin-top:0px; border: 1px solid #a6c9e2; border-radius:4px;">
						<span id="span_respuesta"></span>
					</div>
				</form>
			</div>

			<!-- <div id="modal-base" class="ui-widget-overlay ui-front" style="z-index: 100;display: none;"></div>	-->
			<div id="div_ListadoEmpresa" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:1px">
			</div>

			<div id="div_datosdevalidacion" style="width:100%; float:left;margin-top:10px; border: 1px solid #a6c9e2; border-radius:5px;">
				<table border="0" width="100%" class="tablaFormulario">
					<tr>
						<td style="text-align:right;width:2%" >
							<input id="cbox_mensajeguardar" type="checkbox" value="" name="cbox_mensajeguardar" >
						</td>
						<td style="text-align:left;width:30%">
							<label class="columna" style="color: #a6c9e2;">Acepto bajo mi responsabilidad que la informacion es correcta</label></td>
							<td style="text-align:left;width:68%">
								<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="height:40px; width:160px" id="btn_IniciarSesion"
								title="Iniciar Sesi�n" onClick="ncsistema.Guardar_SummaryHeader()">
								<span class="ui-button-icon-left ui-icon ui-icon-check"></span>
								<span class="ui-button-text">Firmar y Declarar</span></button></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</body>
		</html>
