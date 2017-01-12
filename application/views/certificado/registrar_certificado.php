<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Certificado</title>
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
				ncsistema.Listar_ConfigurationTabla("");	
				
				
				$('#txt_RucEmpresa').numeric();
				
				$('#txt_fechavencimiento').datepicker({
					showOn: 'button',					
					buttonImage: "<?php echo base_url()?>application/helpers/image/ico/calendar_icon.gif",
					buttonImageOnly: true,
					dateFormat: 'dd/mm/yy',
					buttonText: "##/##/####",
					//maxDate: 'today',
					changeMonth: true ,
					changeYear: true
				});
				//$('#txt_FechaEmision').datepicker('setDate', 'today');
				
				OcultarFilaPassword('row3',0);
				OcultarFilaPassword('row4',0);
				OcultarFilaPassword('row5',0);
				OcultarFilaPassword('row6',0);
				OcultarFilaPassword('row7',0);
				OcultarFilaPassword('row8',0);
				OcultarFilaPassword('row9',0);
				OcultarFilaPassword('row10',0);
				OcultarFilaPassword('row11',0);
				
				OcultarFilaPassword('row12',0);
				OcultarFilaPassword('row13',0);
				
				ncsistema.Listar_Configuration();
			})
			
			ncsistema=
			{
				Nuevo_Configuration:function()
				{
					Limpiar_DatosConfiguration();
				},
				Guardar_Configuration:function()
				{

					var txt_cod_empr=$.trim($('#txt_cod_empr').val());
					var cmb_tipofirmadocumento=$.trim($('#cmb_tipofirmadocumento').val());
					var txt_aliasfirma=$.trim($('#txt_aliasfirma').val());
					var txt_valorproteccionfirma=$.trim($('#txt_valorproteccionfirma').val());
					var txt_llaveproteccionfirma=$.trim($('#txt_llaveproteccionfirma').val());
					var txt_nombrearchivo=$.trim($('#txt_nombrearchivo').val());
					var txt_adicionalfirma=$.trim($('#txt_adicionalfirma').val());					
					var archivoImage=$.trim($('#archivoImage').val());					
					var txt_fechavencimiento=$.trim($('#txt_fechavencimiento').val());
					var txt_usuariosol=$.trim($('#txt_usuariosol').val());
					var txt_clavesol=$.trim($('#txt_clavesol').val());					
					var txt_tipofirmaempresa=$.trim($('#txt_tipofirmaempresa').val());
					
					var txt_valorinhouse=$.trim($('#txt_valorinhouse').val());
					
					if (txt_cod_empr==0)
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el emisor</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;

					}


					if (cmb_tipofirmadocumento=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Seleccione el tipo de firma</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;

					}
					
					if (cmb_tipofirmadocumento==2)// SI ES CERTIFICADO LOCAL
					{					
						if (txt_aliasfirma=='')
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Ingrese el alias</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
						
						if (txt_valorproteccionfirma=='')
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Ingrese el valor de la firma</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
						
						
						if (txt_llaveproteccionfirma=='')
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Ingrese la llave de la firma</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
						
						
						//if (txt_usuariosol=='')
						//{
						//	$('#div_MensajeValidacionEmpresa').fadeIn(0);
						//	$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Ingrese el usuario SOL</div>');
						//	setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						//	return;
						//}
						//if (txt_clavesol=='')
						//{
						//	$('#div_MensajeValidacionEmpresa').fadeIn(0);
						//	$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Ingrese la clave SOL</div>');
						//	setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						//	return;
						//}
						
						if (txt_nombrearchivo=='')
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Seleccione un archivo</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
						
						
						if (txt_fechavencimiento=='')
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Seleccione la fecha de vencimiento</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
						
					}

					$.ajax({
						url:'<?php echo base_url()?>certificado/Guardar_Configuration',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_cod_empr:txt_cod_empr,
							cmb_tipofirmadocumento:cmb_tipofirmadocumento,
							txt_aliasfirma:txt_aliasfirma,
							txt_valorproteccionfirma:txt_valorproteccionfirma,
							txt_llaveproteccionfirma:txt_llaveproteccionfirma,
							txt_nombrearchivo:txt_nombrearchivo,
							txt_adicionalfirma:txt_adicionalfirma,
							txt_fechavencimiento:txt_fechavencimiento,
							txt_tipofirmaempresa:txt_tipofirmaempresa,
							txt_usuariosol:txt_usuariosol,
							txt_clavesol:txt_clavesol,
							txt_valorinhouse:txt_valorinhouse
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:10px;padding-top:3px; width:80%;float:left;text-align:left">Registrado! Es importante RE-Iniciar componente para que los cambios sean considerados.</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);

								if (cmb_tipofirmadocumento==2)// SI ES CERTIFICADO LOCAL
								{
									Subir_Certificado();
								}

								Limpiar_DatosConfiguration();
								
								ncsistema.Listar_Configuration();
	
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
				
				},

				Listar_Configuration:function()
				{
					
					
					//var txt_cod_empr=$.trim($('#txt_cod_empr').val());					
					//TIPO DE CONFIGURACION
					
					var txt_cod_empr=$.trim($('#txt_cod_empr').val());
					var txt_conffirma=$.trim($('#txt_conffirma').val());
					
					$('#cmb_tipofirmadocumento').val('');
					
					$('#txt_tipofirmaempresa').val('');
					
					OcultarFilaPassword('row3',0);
					OcultarFilaPassword('row4',0);
					OcultarFilaPassword('row5',0);
					OcultarFilaPassword('row6',0);
					OcultarFilaPassword('row7',0);
					OcultarFilaPassword('row8',0);
					OcultarFilaPassword('row9',0);
					OcultarFilaPassword('row10',0);
					OcultarFilaPassword('row11',0);
										
					OcultarFilaPassword('row12',0);
					OcultarFilaPassword('row13',0);
					//alert(txt_conffirma);
					if(txt_conffirma==1)//FIRMA EN BIZLINK
					{
						$('#div_tipoconfiguracion').empty().append('FIRMA EN BIZLINK');
						$('#txt_tipofirmaempresa').val(1);
						//return false;
					}
					else if(txt_conffirma==2)//FIRMA EN LOCAL
					{
						$('#div_tipoconfiguracion').empty().append('FIRMA LOCAL');
						$('#txt_tipofirmaempresa').val(2);
						OcultarFilaPassword('row3',1);
						/*OcultarFilaPassword('row4',1);
						OcultarFilaPassword('row5',1);
						OcultarFilaPassword('row6',1);
						OcultarFilaPassword('row7',1);
						OcultarFilaPassword('row8',1);
						OcultarFilaPassword('row9',1);
						OcultarFilaPassword('row10',1);*/
						//return false;
					}		
					
					$.ajax({
						url:'<?php echo base_url()?>certificado/Listar_ConfigurationId',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_cod_empr:txt_cod_empr
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_ConfigurationTabla(result.data);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								ncsistema.Listar_ConfigurationTabla("");
							}
						}
					});				
				},				
				
				
				Listar_ConfigurationTipoFirma:function()
				{
					var cmb_tipofirmadocumento=$.trim($('#cmb_tipofirmadocumento').val());	
					
					var txt_valorinhouse=$.trim($('#txt_valorinhouse').val());	
					
									
					//TIPO DE CONFIGURACION
					//OcultarFilaPassword('row3',0);
					OcultarFilaPassword('row4',0);
					OcultarFilaPassword('row5',0);
					OcultarFilaPassword('row6',0);
					OcultarFilaPassword('row7',0);
					OcultarFilaPassword('row8',0);
					OcultarFilaPassword('row9',0);
					OcultarFilaPassword('row11',0);
					OcultarFilaPassword('row12',0);
					OcultarFilaPassword('row13',0);
					OcultarFilaPassword('row10',1);	
									
					if (cmb_tipofirmadocumento==2 )
					{
						//OcultarFilaPassword('row3',0);
						OcultarFilaPassword('row4',1);
						OcultarFilaPassword('row5',1);
						OcultarFilaPassword('row6',1);
						OcultarFilaPassword('row7',1);
						OcultarFilaPassword('row8',1);
						OcultarFilaPassword('row9',0);
						OcultarFilaPassword('row11',1);
						if (txt_valorinhouse==0)
						{
							OcultarFilaPassword('row12',0);
							OcultarFilaPassword('row13',0);
						}
						else
						{
							OcultarFilaPassword('row12',1);
							OcultarFilaPassword('row13',1);
						}
						//OcultarFilaPassword('row10',1);
					}
					//ncsistema.Listar_Configuration();
				},
				
				
				Listar_ConfigurationTabla:function(data)
				{	
					$('#div_ListadoEmpresa').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th style="width:3%">Nro.</td>';						
						newHtml+='<th style="width:10%">Emisor</td>';
						newHtml+='<th style="width:25%">Alias</td>';
						newHtml+='<th style="width:25%">Nombre</td>';						
						newHtml+='<th style="width:10%">Valor</td>';
						newHtml+='<th style="width:10%">Fec.Vencimiento</td>';
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					//<input style="height:20px;width:95%" id="txt_login" type="text" value="'+rs.cantidadproducto+'"/>
					contador=0;
					$.each(data,function(key,rs)
					{
						contador++;
						newHtml+='<tr>';							
							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';							
							newHtml+='<td style="text-align:left">'+rs.id_emisor+' - '+rs.raz_social+'</td>';
							newHtml+='<td style="text-align:left">'+rs.alias+'</td>';
							newHtml+='<td style="text-align:left">'+rs.path_key+'</td>';
							newHtml+='<td style="text-align:left">'+rs.protection_key+'</td>';
							newHtml+='<td style="text-align:center">'+rs.expiry_key+'</td>';
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
				
			}
			
			function Limpiar_DatosConfiguration()
			{
				//$('#txt_cod_empr').val('0');
				$('#cmb_tipofirmadocumento').val('');
				$('#txt_aliasfirma').val('');				
				$('#txt_valorproteccionfirma').val('');
				
				$('#txt_llaveproteccionfirma').val('');
				
				$('#txt_usuariosol').val('');
				$('#txt_clavesol').val('');

				$('#txt_nombrearchivo').val('');
				$('#txt_adicionalfirma').val('');
				$('#archivoImage').val('');
				$('#txt_fechavencimiento').val('');

				OcultarFilaPassword('row4',0);
					OcultarFilaPassword('row5',0);
					OcultarFilaPassword('row6',0);
					OcultarFilaPassword('row7',0);
					OcultarFilaPassword('row8',0);
					OcultarFilaPassword('row9',0);
					OcultarFilaPassword('row11',0);
					OcultarFilaPassword('row12',0);
					OcultarFilaPassword('row13',0);
					OcultarFilaPassword('row10',1);	
				
				$('#div_Guardar').removeClass('disablediv');
				$("#div_Guardar").addClass("enablediv").on("onclick");	
			}

			function Validar_Certificado()
			{
				if ($('#archivoImage').val()=='')
				{					
				}
				else
				{
					var inputFileImage = document.getElementById('archivoImage');				
					var file = inputFileImage.files[0];				
					var data = new FormData();				
					data.append('archivo',file);	

					var url = '<?php echo base_url()?>certificado/Validar_Certificado';				
					$.ajax(
					{				
						url:url,					
						type:'POST',		
						dataType: 'json',			
						contentType:false,					
						data:data,/*
						{
							data:data,					
							nombrefoto:nombrefoto
						}*/
						processData:false,
						cache:false,
						success:function(result)
						{
							if(result.status==1)
							{
								$('#txt_nombrearchivo').val(result.nombre);
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">'+result.mensaje+'</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);								
								$('#archivoImage').val('');
								$('#txt_nombrearchivo').val('');
							}
						}
					});
				}
			}
			
			
			function Subir_Certificado()
			{
				if ($('#archivoImage').val()=='')
				{
					
				}
				else
				{
					var inputFileImage = document.getElementById('archivoImage');				
					var file = inputFileImage.files[0];				
					var data = new FormData();				
					data.append('archivo',file);	
					//data.append('nombrefoto',nombrefoto);
					//data.append('cod_fot',cod_fot);
		
					var url = '<?php echo base_url()?>certificado/Subir_Certificado';				
					$.ajax(
					{				
						url:url,					
						type:'POST',		
						dataType: 'json',			
						contentType:false,					
						data:data,/*
						{
							data:data,					
							nombrefoto:nombrefoto
						}*/
						processData:false,
						cache:false,
						success:function(result)
						{
							if(result.status==1)
							{
								//$('#txt_nombrearchivo').val(result.nombre);
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								alert('Error al subir el certificado');
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
			
		</script>
		
    </head>   
    <body>
		<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">REGISTRAR CERTIFICADO</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">
			
			<div id="div_datosempresa" style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
				<input style="width:99%" type="hidden" id="txt_tipofirmaempresa"  />
				<input style="width:99%" type="hidden" id="txt_valorinhouse" value="<?php echo $Valor_Inhouse;?> "  />
				<input type="hidden" id="txt_cod_empr"  value="<?php echo $Ruc_empr;?>" />
				<input type="hidden" id="txt_conffirma"  value="<?php echo $Tipo_conffirma;?>" />
				<table width="40%" style="border-collapse:separate; border-spacing:1px 1px;" cellpadding="3" class="tablaFormulario">
					<tr><td><label class="columna"></label></td></tr>
					<tr>
						<td style="text-align:right;width:30%"><label class="columna">Emisor:</label></td>
						<td style="text-align:left;;width:70%" >
							<input style="width:98%" type="text" id="txt_RazonSocialEmpresa" 
								value="<?php echo trim(utf8_encode($Razon_Social));?>" disabled="disabled" />
						</td>
					</tr>	
					<tr>
						<td style="text-align:right;"><label class="columna">Tipo de Conf.:</label></td>
						<td style="text-align:left;" >
							<div id="div_tipoconfiguracion"></div>
						</td>
					</tr>				
					<tr id="row3">
						<td style="text-align:right;"><label class="columna">Tipo de Firma:</label></td>
						<td style="text-align:left;" >
							<select id="cmb_tipofirmadocumento" style="width:90%;height:25px" onChange="ncsistema.Listar_ConfigurationTipoFirma()" >
								<option value="">[SELECCIONAR]</option>
								<option value="1">CERTIFICADO DE BIZLINKS</option>
								<option value="2">CERTIFICADO DEL EMISOR</option>
							</select>
						</td>
					</tr>
					
					<tr id="row4">
						<td style="text-align:right;"><label class="columna">Buscar:</label></td>
						<td style="text-align:left;" >
							<input type="file" name="archivoImage" id="archivoImage" onBlur="Validar_Certificado()" /> 
						</td>
					</tr>
					<tr id="row5">
						<td style="text-align:right"><label class="columna">Nombre Archivo:</label></td>
						<td style="text-align:left" ><input style="width:99%" type="text" id="txt_nombrearchivo" disabled="disabled" /></td>
					</tr>
					<tr id="row6">
						<td style="text-align:right;"><label class="columna">Alias:</label></td>
						<td style="text-align:left;" >
							<input style="width:99%" type="text" id="txt_aliasfirma" /></td>
					</tr>
					<tr id="row7">
						<td style="text-align:right"><label class="columna">Valor de Proteccion:</label></td>
						<td style="text-align:left">
							<input style="width:99%" type="text" id="txt_valorproteccionfirma"  /></td>
					</tr>
					<tr id="row8">
						<td style="text-align:right"><label class="columna">Llave Proteccion:</label></td>
						<td style="text-align:left">
							<input style="width:80%" type="text" id="txt_llaveproteccionfirma"  value="alignetsfe "/></td>
					</tr>	
					<tr id="row11">
						<td style="text-align:right"><label class="columna">Fecha Vencimiento:</label></td>
						<td style="text-align:left">
							<input style="width:50%" disabled="disabled" type="text" id="txt_fechavencimiento"  value=" "/></td>
					</tr>
					
					<tr id="row9">
						<td style="text-align:right"><label class="columna"> Adicional:</label></td>
						<td style="text-align:left"><input style="width:99%" type="text" id="txt_adicionalfirma"  /></td>
					</tr>	
					
					<tr id="row12">
						<td style="text-align:right"><label class="columna"> Usuario SOL:</label></td>
						<td style="text-align:left"><input style="width:99%" type="text" id="txt_usuariosol"  /></td>
					</tr>
					<tr id="row13">
						<td style="text-align:right"><label class="columna"> Clave SOL:</label></td>
						<td style="text-align:left"><input style="width:99%" type="text" id="txt_clavesol"  /></td>
					</tr>	
					
					<tr>
						<td align="right"><label class="columna"></label></td>
						<td colspan="3" align="center">
							<div style="width:100%;height:30px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:0px;text-align:center;float:left">
								<div id="div_MensajeValidacionEmpresa" style="width:100%;float:left;font-size:9px; color:#FF0000"></div>
							</div>
						</td></tr>
					<tr id="row10">
						<td><label class="columna"></label></td>
						<td colspan="3" >
							<table style="width:100%" >
							  <tbody>
								<tr>
									<td style="text-align:right; width:50%">
										<a href="javascript:ncsistema.Nuevo_Configuration()" >
											<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
													<span class="ui-button-icon-left ui-icon ui-icon-document"></span>
													<span class="ui-button-text">Nuevo</span></button>
										</a>
									</td>
									<td style="text-align:left;width:50%">
										<a href="javascript:ncsistema.Guardar_Configuration()" >
											<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
													<span class="ui-button-icon-left ui-icon ui-icon-disk"></span>
													<span class="ui-button-text">Guardar</span></button>
										</a>
									</td>
								</tr>
							  </tbody>
							</table>
						</td>
					</tr>
				</table>
					
				</div>
				<div id="div_ListadoEmpresa" style="width:100%;border:solid 0px;float:left;text-align:center;margin-top:10px">
				</div>	
			</div>

		</div>
    </body>	
</html>