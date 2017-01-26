<strong></strong><!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Resumen de Baja</title>
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
				$.datepicker.setDefaults($.datepicker.regional["es"]);
				//ncsistema.Listar_DocumentosdeBaja(0);	
				ncsistema.Listar_DocumentosdeBajaTabla("",'');
				
			})
			
			ncsistema=
			{
				
				Guardar_DocumentosdeBaja:function()
				{

					var txt_SerieNumero=$.trim($('#txt_SerieNumero').val());					
					var txt_Motivo=$.trim($('#txt_Motivo').val());
					var Cmb_TipoDocumento=$.trim($('#Cmb_TipoDocumento').val());
					var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
					
	

					if (Cmb_TipoDocumento==0)
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el Tipo de Documento</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					if (txt_SerieNumero=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el serie y el número del documento</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					if (txt_Motivo=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el Motivo</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
				

					$.ajax({
						url:'<?php echo base_url()?>resumenbaja/Guardar_DocumentosdeBaja',
						type: 'post',
						dataType: 'json',
						data:
						{
							Cmb_TipoDocumento:Cmb_TipoDocumento,
							txt_SerieNumero:txt_SerieNumero,
							txt_Motivo:txt_Motivo,
							txt_RucEmpresa:txt_RucEmpresa
						},
						beforeSend:function()
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');
						},
						success:function(result)
						{
							if(result.status==1)
							{
							
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El registro de los datos se realizó con éxito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_DocumentosdeBaja(0);
								return;
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else if(result.status==2)
							{
							
								alert(result.mensaje);
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El registro de los datos se realizó con éxito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_DocumentosdeBaja(0);
								return;
							}
							else
							{
								$('#div_Guardar').removeClass('disablediv');
								$("#div_Guardar").addClass("enablediv").on("onclick");
								
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:lefts"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">'+result.mensaje+'</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}
					});
				
				},

				Listar_DocumentosdeBaja:function(tipo_busqueda)
				{
					var txt_RucEmpresa=$.trim($('#txt_RucEmpresa').val());
					var txt_FechaEmision=$.trim($('#txt_FechaEmision').val());
					var Cmb_TipoDocumento=$.trim($('#Cmb_TipoDocumento').val());
					if (tipo_busqueda==1)
					{
						if (Cmb_TipoDocumento==0)
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el Tipo de Documento</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
					}
					
					$.ajax({
						url:'<?php echo base_url()?>resumenbaja/Listar_DocumentosdeBaja',
						type: 'post',
						dataType: 'json',
						data:
						{
							tipo_busqueda:tipo_busqueda,
							txt_RucEmpresa:txt_RucEmpresa,
							txt_FechaEmision:txt_FechaEmision,
							Cmb_TipoDocumento:Cmb_TipoDocumento
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							$('#div_MensajeValidacionEmpresa').empty().append('');
							if(result.status==1)
							{
								ncsistema.Listar_DocumentosdeBajaTabla(result.data,Cmb_TipoDocumento);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								ncsistema.Listar_DocumentosdeBajaTabla("",'');
							}
						}
					});					
				},				
				
				
				Listar_DocumentosdeBajaTabla:function(data,tipodocumento)
				{	
					$('#div_ListadoEmpresa').empty().append('');
					$('#txt_fecemisiondoc').val('');
					$('#txt_tipdocemisor').val('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th width:3%>Nro.</td>';						
						newHtml+='<th width:5%>Incluir</td>';						
						newHtml+='<th width:10%>F.Emisi&oacute;n</td>';
						newHtml+='<th width:10%>Tipo Doc.</td>';
						newHtml+='<th width:20%>N&uacute;mero</td>';
						newHtml+='<th width:50%>Motivo</td>';
						newHtml+='<th width:10%>Agregado</td>';	
						newHtml+='<th width:10%>Eliminar</td>';	
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					//<input style="height:20px;width:95%" id="txt_login" type="text" value="'+rs.cantidadproducto+'"/>
					contador=0;
					$.each(data,function(key,rs)
					{
						
						newHtml+='<tr>';							
								
							if (rs.tip_reg==1)
							{
								newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';
								newHtml+='<td style="text-align:center"></td>';
								contador++;
							}
							else
							{
								newHtml+='<td style="text-align:center;color:#990000;font-weight:bold">'+rs.nro_secuencia+'</td>';
								newHtml+='<td style="text-align:center"><a href="javascript:ncsistema.Agregar_DocumentoLista('+key+',\''+tipodocumento+'\',\''+rs.numer_doc+'\')" ><img src="<?php echo base_url();?>application/helpers/image/ico/nc_guardar.png" style="width: 20px; height: 20px"  /></a></td>';
							}						
							
							if (rs.tip_reg==1)
							{
								newHtml+='<td style="text-align:left">'+rs.fec_emision+'</td>';
								newHtml+='<td style="text-align:left">'+rs.tipo_doc+'</td>';
								newHtml+='<td style="text-align:left">'+rs.numer_doc+'</td>';
							}
							else
							{
								newHtml+='<td style="text-align:left;color:#990000;font-weight:bold">'+rs.fec_emision+'</td>';
								newHtml+='<td style="text-align:left;color:#990000;font-weight:bold">'+rs.tipo_doc+'</td>';
								newHtml+='<td style="text-align:left;color:#990000;font-weight:bold">'+rs.numer_doc+'</td>';
							}	

							if (rs.tip_reg==1)
							{
								newHtml+='<td style="text-align:left">'+rs.mot_baja+'</td>';
								newHtml+='<td style="text-align:center"><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/ncactivo.png" title="ACTIVO" ></td>';		
								newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Documentosdebajatemporal('+rs.cod_doctmp+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
							}
							else
							{
								newHtml+='<td style="text-align:left"><input style="width:95%" type="text" id="txt_motivo_'+key+'" name=id="txt_motivo_'+key+'" value="" placeholder="Máximo 200 caracteres"/></td>';
								newHtml+='<td style="text-align:center"></td>';	
								newHtml+='<td style="text-align:center"></td>';													
							}							
							
							$('#txt_fecemisiondoc').val(rs.fec_emision);
							$('#txt_tipdocemisor').val(rs.tip_docemisor);
							
						newHtml+='</tr>';						
					});	
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					
					$('#txt_cantidad').val(contador);
					
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
				
				
				Agregar_DocumentoLista:function(key,tipodocumento,serienumero)
				{

					var txt_motivo=$("#txt_motivo_"+key).val();
					if (txt_motivo=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el motivo para el Item '+(key+1)+'</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						//document.getElementsByName('cbox_agrupar_'+key)[0].checked=false;
						return;
					}

					var txt_RucEmpresa=$('#txt_RucEmpresa').val();		
					if (tipodocumento==0)
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el Tipo de Documento</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					if (serienumero=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese la serie y el número del documento</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}

					$.ajax({
						url:'<?php echo base_url()?>resumenbaja/Guardar_DocumentosdeBaja',
						type: 'post',
						dataType: 'json',
						data:
						{
							Cmb_TipoDocumento:tipodocumento,
							txt_SerieNumero:serienumero,
							txt_Motivo:txt_motivo,
							txt_RucEmpresa:txt_RucEmpresa
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El registro de los datos se realizó con éxito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_DocumentosdeBaja(1);
								return;
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else if(result.status==2)
							{
							
								alert(result.mensaje);
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El registro de los datos se realizó con éxito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_DocumentosdeBaja(1);
								return;
							}
							else
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:lefts"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">'+result.mensaje+'</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								//document.getElementsByName('cbox_agrupar_'+key)[0].checked=false;
								return;
							}
						}
					});	
	
				},
				
				Guardar_Specancelheader:function()
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
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Debe aceptar las condiciones</div>');
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
						url:'<?php echo base_url()?>resumenbaja/Guardar_Specancelheader',
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
							if(result.status==1)
							{
								/*
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:left"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:18px;padding-top:3px; width:80%;float:left;text-align:left">Se generó el '+result.codigo_baja+'</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								*/
								alert('Se generó el '+result.codigo_baja);
								
								Limpiar_DatosRegistroDocBajas();
								ncsistema.Listar_DocumentosdeBaja(0);
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:4%;float:left;text-align:lefts"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error en el registro de los datos</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}
					});
				
				},
			}
			
			function Limpiar_DatosRegistroDocBajas()
			{
				$('#Cmb_TipoDocumento').val('0');
				$('#txt_Motivo').val('');		
				$('#txt_FechaEmision').val('');	
				$('#txt_SerieNumero').val('');					
						
				document.getElementsByName('cbox_modobusqueda')[0].checked=false;
				Cambiar_BusquedaDocumento();	
				document.getElementsByName('cbox_mensajeguardar')[0].checked=false;
			}
			
			function Limpiar_Busqueda()
			{
				$('#Cmb_TipoDocumento').val('0');
				$('#txt_Motivo').val('');		
				$('#txt_FechaEmision').val('');	
				$('#txt_SerieNumero').val('');		
				document.getElementsByName('cbox_modobusqueda')[0].checked=false;
				Cambiar_BusquedaDocumento();							
				ncsistema.Listar_DocumentosdeBaja(0);
				document.getElementsByName('cbox_mensajeguardar')[0].checked=false;	
				$.ajax
				({
					url:'<?php echo base_url()?>resumenbaja/Eliminar_DocumentosdebajatemporalUsuario',type:'post',dataType:'json',
					data:
					{
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
					}			
				});
				
			}
			
			
			function Eliminar_Documentosdebajatemporal(cod_doctmp)
			{
				if(confirm("¿ Esta Seguro de Eliminar el documento ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>resumenbaja/Eliminar_Documentosdebajatemporal',type:'post',dataType:'json',
						data:
						{
							cod_doctmp:cod_doctmp,
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminación del Documento se realizó con éxito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_DocumentosdeBaja(0);
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar la documento</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}			
					});
				}
				
			}
			
			function Cambiar_BusquedaDocumento()
			{
				$('#div_mensajecontrol').empty().append('');	
				$('#div_mensaje').empty().append('');
				$('#div_OpcionesBotones').empty().append('');				
				
				var cbox_modobusqueda=0;
				if ($("#cbox_modobusqueda").is(":checked"))
				{
					cbox_modobusqueda=1;
				}
				
				if (cbox_modobusqueda==0)
				{
					newHtml='';
					newHtml+='<input style="width:90%" type="text" id="txt_SerieNumero" maxlength="15"/>';
					$('#div_mensajecontrol').empty().append(newHtml);
					$('#div_mensaje').empty().append('<label class="columna">Serie-N&uacute;mero:</label>');
					$('#div_OpcionesBotones').empty().append('<a href="javascript:ncsistema.Guardar_DocumentosdeBaja()"><button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" type="submit"><span class="ui-button-icon-left ui-icon ui-icon-circle-plus"></span><span class="ui-button-text">Agregar Documento</span></button></a>');
					
					//ncsistema.Listar_DocumentosdeBaja(0);
					OcultarFilaTabla('row_0',1);//MUESTRA FILA
				}
				else
				{
					newHtml='';
					newHtml+='<input style="width:70%" id="txt_FechaEmision" disabled="disabled" type="text" value="" />';
					$('#div_mensajecontrol').empty().append(newHtml);					
					$('#txt_FechaEmision').datepicker({
						showOn: 'button',					
						buttonImage: "<?php echo base_url()?>application/helpers/image/ico/calendar_icon.png",
						buttonImageOnly: true,
						dateFormat: 'dd/mm/yy',
						buttonText: "##/##/####",
						maxDate: 'today',
						changeMonth: true ,
						changeYear: true
					});
					$('#txt_FechaEmision').datepicker('setDate', 'today');
					
					$.datepicker.setDefaults($.datepicker.regional["es"]);
					
					$('#div_mensaje').empty().append('<label class="columna">Fec.Emision:</label>');
					
					$('#div_OpcionesBotones').empty().append('<a href="javascript:ncsistema.Listar_DocumentosdeBaja(1)"><button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" type="submit"><span class="ui-button-icon-left ui-icon ui-icon-circle-plus"></span><span class="ui-button-text">Listar B&uacute;squeda</span></button></a>');
					
					OcultarFilaTabla('row_0',0);//MUESTRA FILA
					//ncsistema.Listar_DocumentosdeBaja(1);
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
			
		</script>
		
    </head>   
    <body>
<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">RESUMEN DE BAJA</a></li>
			</ul>
			<div id="tabs-1" style="width:98%;float:left">
			
				<div id="div_datosempresa" style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
					<input style="width:15%" type="hidden" id="txt_cantidad"  value="0" />
					<input style="width:15%" type="hidden" id="txt_fecemisiondoc"  value="" />
					<input style="width:15%" type="hidden" id="txt_tipdocemisor"  value="" />
					<table border="0" width="60%" style="border-collapse:separate; border-spacing:8px 1px;" cellpadding="3" class="tablaFormulario"><!--class="tablaFormulario"-->
					  <tbody>
					    <tr><td><label class="columna"></label></td>
						</tr>
						<tr>
							<td style="text-align:right;width:5%" ><label class="columna">RUC:</label></td>
							<td style="text-align:left;width:15%"><input style="width:90%" type="text" id="txt_RucEmpresa" value="<?php echo trim(utf8_decode($Ruc_Empresa));?>"  disabled="disabled" /></td>
							<td style="text-align:right;width:8%"><label class="columna">Raz&oacute;n Social:</label></td>
							<td style="text-align:left;width:30%"><input style="width:95%" type="text" id="txt_RazonSocialEmpresa" value="<?php echo trim(utf8_decode($Razon_Social));?>" disabled="disabled" /></td>	
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Documento:</label></td>
							<td style="text-align:left">
								<select id="Cmb_TipoDocumento" style="width:92%;height:25px" >
									<option value="0">[SELECCIONAR]</option>
									<?php foreach ( $Listar_TipodeDocumento as $v):	?>
										<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(utf8_decode($v['no_corto']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
							<td style="text-align:right" >
								<div id="div_mensaje"><label class="columna">Serie-N&uacute;mero:</label></div>
							</td>
							<td style="text-align:left">
								<table width="100%" border="0">
									<tr>
										<td style="width:45%">
											<div id="div_mensajecontrol">
												<input style="width:90%" type="text" id="txt_SerieNumero" maxlength="13" /> 									
											</div>
										</td>
										<td style="width:55%">
											<input id="cbox_modobusqueda" type="checkbox" value="" name="cbox_modobusqueda" onChange="javascrip:Cambiar_BusquedaDocumento()" 
												style="vertical-align:middle; font-weight:bold; color:#72b42d;" > Buscar por Fechas de Emisi&oacute;n
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr id="row_0">
							<td style="text-align:right" ><label class="columna">Motivo:</label></td>
							<td style="text-align:left" colspan="3">
								<input style="width:97%" type="text" id="txt_Motivo"  /></td>
						</tr>
						<tr>
							<td style="text-align:right;" ><label class="columna"></label></td>
							<td style="text-align:left; border: solid 0px" colspan="2"  >
								<div id="div_OpcionesBotones" style="width:100%">
									<a href="javascript:ncsistema.Guardar_DocumentosdeBaja()" >
										<!--<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" type="submit">
											<span class="ui-button-icon-left ui-icon ui-icon-plusthick"></span>
											<span class="ui-button-text">Agregar</span></button>-->
										<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:175px; height:32px" type="submit">
											<span class="ui-button-icon-left ui-icon ui-icon-circle-plus"></span>
											<span class="ui-button-text">&#32;&#32;Agregar Documento</span></button>
									</a>
								</div>
							</td>
							<td style="align:center"  >
								<div id="div_OpcionesBotones">
									<a href="javascript:Limpiar_Busqueda()" >
										<!--<img src="<?php echo base_url();?>application/helpers/image/ico/buscar32.png" 
											width="35" height="35"/>-->
										<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
											<span class="ui-button-icon-left ui-icon ui-icon-document-b"></span>
											<span class="ui-button-text">Limpiar</span></button>
									</a>
								</div>
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
				<div id="div_ListadoEmpresa" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:10px">
				</div>	
				<div id="div_datosdevalidacion" style="width:100%; float:left;margin-top:10px; border: 1px solid #a6c9e2; border-radius:5px;">
					<table border="0" width="100%" class="tablaFormulario">
						<tr>
							<td style="text-align:right; width:2%" valign="middle">
							<input id="cbox_mensajeguardar" type="checkbox" value="" name="cbox_mensajeguardar" >
							</td>
							<td style="text-align:left;width:30%" > 
								<label class="columna" style="color: #a6c9e2;">Acepto bajo mi responsabilidad que la informacion es correcta</label></td>
							<td style="text-align:left;width:68%"> 
								<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="height:40px; width:160px" id="btn_IniciarSesion" title="Firmar" onClick="ncsistema.Guardar_Specancelheader()">
									<span class="ui-button-icon-left ui-icon ui-icon-check"></span>
									<span class="ui-button-text">Firmar y Declarar</span></button></td>
													
						</tr>
					</table>
				</div>
			</div>

		</div>
    </body>	
</html>