<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Cliente</title>
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
				ncsistema.Listar_Empresa();	
				
			})
			
			ncsistema=
			{
				Nuevo_Empresa:function()
				{
					Limpiar_DatosEmpresa();
				},
				Guadar_Empresa:function()
				{

					var txt_codcliente=$.trim($('#txt_codcliente').val());
					
					var txt_ruccliente=$.trim($('#txt_ruccliente').val());
					var txt_razonsocialcliente=$.trim($('#txt_razonsocialcliente').val());
					var txt_correocliente=$.trim($('#txt_correocliente').val());
					
					var cmb_tipodoccliente=$.trim($('#cmb_tipodoccliente').val());
					var txt_nombrecomercialcliente=$.trim($('#txt_nombrecomercialcliente').val());
					var cmb_paiscliente=$.trim($('#cmb_paiscliente').val());
					var cmb_departamento=$.trim($('#cmb_departamento').val());
					var cmb_provincia=$.trim($('#cmb_provincia').val());
					var cmb_distrito=$.trim($('#cmb_distrito').val());
					var txt_urbanizacioncliente=$.trim($('#txt_urbanizacioncliente').val());
					var txt_direccioncliente=$.trim($('#txt_direccioncliente').val());

					
					if (cmb_tipodoccliente=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el tipo de Documento</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;

					}

					if (txt_ruccliente=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese el RUC de la Empresa</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;

					}
					
					
					if (cmb_tipodoccliente=='1' )//DNI
					{
						if (txt_ruccliente.length!=8 )//DNI
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El DNI debe tener 8 números</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
					}

					
					if (cmb_tipodoccliente=='6')//RUC
					{
						
						if (txt_ruccliente.length!=11 )
						{
							$('#div_MensajeValidacionEmpresa').fadeIn(0);
							$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El RUC debe tener 11 números</div>');
							setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
							return;
						}
					}
					
					if (txt_razonsocialcliente=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese la Razón Social de la Empresa</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}

					var contador=0;
					if(cmb_tipodoccliente=='6')
					{
						
						if (cmb_paiscliente!='')
						{
							contador++;
						}
						if (cmb_departamento!='')
						{
							contador++;
						}
						if (cmb_provincia!='')
						{
							contador++;
						}
						if (cmb_distrito!='')
						{
							contador++;
						}
						if (txt_urbanizacioncliente!='')
						{
							contador++;
						}
						if (txt_direccioncliente!='')
						{
							contador++;
						}
					
						if (contador>0)
						{
							if (cmb_paiscliente=='')
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el pa&iacute;s</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
							if (cmb_departamento=='')
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el departamento</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
							if (cmb_provincia=='')
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione la provincia</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
							if (cmb_distrito=='')
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el distrito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
							
							if (txt_urbanizacioncliente=='')
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese la urbanizaci&oacute;n</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
							if (txt_direccioncliente=='')
							{
								$('#div_MensajeValidacionEmpresa').fadeIn(0);
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese la direcci&oacute;n</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}					
						
					}
				
					if (txt_codcliente==0)//GUARDAR
					{
						$.ajax({
							url:'<?php echo base_url()?>clientes/Guardar_Cliente',
							type: 'post',
							dataType: 'json',
							data:
							{
								cmb_tipodoccliente:cmb_tipodoccliente,
								txt_ruccliente:txt_ruccliente,
								txt_razonsocialcliente:txt_razonsocialcliente,
								txt_nombrecomercialcliente:txt_nombrecomercialcliente,
								txt_correocliente:txt_correocliente,
								cmb_paiscliente:cmb_paiscliente,
								cmb_departamento:cmb_departamento,
								cmb_provincia:cmb_provincia,
								cmb_distrito:cmb_distrito,
								txt_urbanizacioncliente:txt_urbanizacioncliente,
								txt_direccioncliente:txt_direccioncliente
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
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El registro de los datos se realiz&oacute; con &eacute;xito</div>');
									setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);

									Limpiar_DatosEmpresa();
									ncsistema.Listar_Empresa();
									return;
								}
								else if (result.status==2)
								{
									$('#div_Guardar').removeClass('disablediv');
									$("#div_Guardar").addClass("enablediv").on("onclick");
									
									$('#div_MensajeValidacionEmpresa').fadeIn(0);
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El RUC ingresado ya est&aacute; registrado</div>');
									setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
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
					}
					else //MODIFICAR
					{
						$.ajax({
							url:'<?php echo base_url()?>clientes/Modificar_Clientes',
							type: 'post',
							dataType: 'json',
							data:
							{
								txt_codcliente:txt_codcliente,
								cmb_tipodoccliente:cmb_tipodoccliente,
								txt_razonsocialcliente:txt_razonsocialcliente,
								txt_correocliente:txt_correocliente,
								cmb_paiscliente:cmb_paiscliente,
								cmb_departamento:cmb_departamento,
								cmb_provincia:cmb_provincia,
								cmb_distrito:cmb_distrito,
								txt_urbanizacioncliente:txt_urbanizacioncliente,
								txt_direccioncliente:txt_direccioncliente,
								txt_nombrecomercialcliente:txt_nombrecomercialcliente
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
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La Modificaci&oacute;n de los datos se realiz&oacute; con &eacute;xito</div>');
									setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);

									Limpiar_DatosEmpresa();
									ncsistema.Listar_Empresa();

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
									$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al modificar los datos</div>');
									setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
									return;
								}
							}
						});
					}
				},

				Listar_Empresa:function()
				{
					$.ajax({
						url:'<?php echo base_url()?>clientes/Listar_Clientes',
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
								ncsistema.Listar_EmpresaTabla(result.data);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								ncsistema.Listar_EmpresaTabla("");
							}
						}
					});					
				},				
				
				
				Listar_EmpresaTabla:function(data)
				{	
					$('#div_ListadoEmpresa').empty().append('');
					
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
					newHtml+='<thead>';
					newHtml+='<tr>';						
						newHtml+='<th style="width:3%">Nro.</td>';						
						newHtml+='<th style="width:5%">Editar</td>';
						newHtml+='<th style="width:10%">Tip.Documento</td>';
						newHtml+='<th style="width:10%">Numero</td>';
						newHtml+='<th style="width:35%">Raz&oacute;n Social</td>';
						newHtml+='<th style="width:25%">Correo</td>';
						newHtml+='<th style="width:25%">Direcci&oacute;n</td>';						
						newHtml+='<th style="width:10%">Eliminar</td>';	
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
							newHtml+='<td style="text-align:center"><a href="javascript:VerDatosCliente_Modificar('+rs.cod_client+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/Editar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
							newHtml+='<td style="text-align:left">'+rs.nomb_tipodocumento+'</td>';
							//if (rs.cod_tipdoc=='0')
							//{
								//newHtml+='<td style="text-align:left">-</td>';
							//}
							//else
							//{
								newHtml+='<td style="text-align:left">'+rs.nro_docum+'</td>';
							//}
							newHtml+='<td style="text-align:left">'+rs.raz_social+'</td>';
							newHtml+='<td style="text-align:left">'+rs.email_cliente+'</td>';
							newHtml+='<td style="text-align:left">'+rs.direc_cliente+'</td>';							
														
							if (rs.est_reg==0)//ANULADO
							{
								newHtml+='<td style="text-align:left"></td>';								
							}
							else
							{
								newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Clientes('+rs.cod_client+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';
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
				
			}
			
			function Limpiar_DatosEmpresa()
			{
				$('#txt_codcliente').val('0');
				$('#txt_ruccliente').val('');
				$('#txt_razonsocialcliente').val('');
				$('#txt_RepLegalEmpresa').val('');				
				$("#txt_ruccliente").prop('disabled', false);
				$("#cmb_tipodoccliente").prop('disabled', false);
				
				$('#cmb_tipodoccliente').val('');
				$('#txt_nombrecomercialcliente').val('');
				$('#txt_urbanizacioncliente').val('');
				$('#txt_direccioncliente').val('');
				
				$('#txt_correocliente').val('');
				
				$('#cmb_departamento').val('');
				$('#cmb_provincia').val('');
				$('#cmb_distrito').val('');
				$('#cmb_paiscliente').val('');
				
				$('#div_Guardar').removeClass('disablediv');
				$("#div_Guardar").addClass("enablediv").on("onclick");	
			}
			



			function VerDatosCliente_Modificar(cod_client)
			{
				$.ajax
				({
					url:'<?php echo base_url()?>clientes/Listar_ClienteId',type:'post',dataType:'json',
					data:
					{
						cod_client:cod_client
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(parseInt(result.status)==1)
						{
							$.each(result.data,function(key,rs)
							{
								$("#txt_ruccliente").prop('disabled', true);
								$("#cmb_tipodoccliente").prop('disabled', true);
								$.trim($('#txt_codcliente').val(rs.cod_client));
								$.trim($('#txt_ruccliente').val(rs.nro_docum));
								$.trim($('#txt_razonsocialcliente').val(rs.raz_social));
								$.trim($('#txt_correocliente').val(rs.email_cliente));
								
								$.trim($('#cmb_paiscliente').val(rs.cod_pais));
								$.trim($('#cmb_tipodoccliente').val(rs.cod_tipdoc));
								$.trim($('#txt_nombrecomercialcliente').val(rs.nom_comercial));
								$.trim($('#txt_urbanizacioncliente').val(rs.urbaniz_cliente));
								$.trim($('#txt_direccioncliente').val(rs.direc_cliente));
								/*
								Listar_Departamento(rs.cod_pais,rs.cod_departamento);
								Listar_Provincias(rs.cod_departamento,rs.cod_provincia);
								Listar_Distritos(rs.cod_provincia,rs.cod_distrito);
								*/
								
								var pais='';
								var departamento='';
								var provincia='';
								var distrito='';
								if (rs.cod_pais=='')
								{
									$.trim($('#cmb_paiscliente').val(''));
								}
								else
								{
									$.trim($('#cmb_paiscliente').val(rs.cod_pais));
									pais=rs.cod_pais
								}	
								if (rs.cod_departamento=='')
								{
									$.trim($('#cmb_departamento').val(''));
								}
								else
								{
									$.trim($('#cmb_departamento').val(rs.cod_departamento));
									departamento=rs.cod_departamento
								}									
								if (rs.cod_provincia!='')
								{
									provincia=rs.cod_provincia;
								}
								if (rs.cod_distrito!='')
								{
									distrito=rs.cod_distrito;
								}
								Listar_Departamento(pais,departamento);
								Listar_Provincias(departamento,provincia);
								Listar_Distritos(provincia,distrito,departamento);

							});
			
						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}
					}
			
				});
			}
			
			function VerDatosCliente_Validar()
			{
				var cmb_tipodoccliente=$('#cmb_tipodoccliente').val();
				var txt_ruccliente=$('#txt_ruccliente').val();
			
				$.ajax
				({
					url:'<?php echo base_url()?>clientes/Listar_ClienteDocumento',type:'post',dataType:'json',
					data:
					{
						cmb_tipodoccliente:cmb_tipodoccliente,
						txt_ruccliente:txt_ruccliente
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(parseInt(result.status)==1)
						{
							$.each(result.data,function(key,rs)
							{
								$("#txt_ruccliente").prop('disabled', true);
								$("#cmb_tipodoccliente").prop('disabled', true);
								$.trim($('#txt_codcliente').val(rs.cod_client));
								$.trim($('#txt_ruccliente').val(rs.nro_docum));
								$.trim($('#txt_razonsocialcliente').val(rs.raz_social));
								$.trim($('#txt_correocliente').val(rs.email_cliente));
								
								$.trim($('#cmb_paiscliente').val(rs.cod_pais));
								$.trim($('#cmb_tipodoccliente').val(rs.cod_tipdoc));
								$.trim($('#txt_nombrecomercialcliente').val(rs.nom_comercial));
								$.trim($('#txt_urbanizacioncliente').val(rs.urbaniz_cliente));
								$.trim($('#txt_direccioncliente').val(rs.direc_cliente));
								/*
								Listar_Departamento(rs.cod_pais,rs.cod_departamento);
								Listar_Provincias(rs.cod_departamento,rs.cod_provincia);
								Listar_Distritos(rs.cod_provincia,rs.cod_distrito);
								*/
								
								var pais='';
								var departamento='';
								var provincia='';
								var distrito='';
								
								if (rs.cod_pais=='')
								{
									$.trim($('#cmb_paiscliente').val(''));
								}
								else
								{
									$.trim($('#cmb_paiscliente').val(rs.cod_pais));
									pais=rs.cod_pais
								}	
								
								if (rs.cod_departamento=='')
								{
									$.trim($('#cmb_departamento').val(''));
								}
								else
								{
									$.trim($('#cmb_departamento').val(rs.cod_departamento));
									departamento=rs.cod_departamento
								}									
								if (rs.cod_provincia!='')
								{
									provincia=rs.cod_provincia;
								}
								if (rs.cod_distrito!='')
								{
									distrito=rs.cod_distrito;
								}
								Listar_Departamento(pais,departamento);
								Listar_Provincias(departamento,provincia);
								Listar_Distritos(provincia,distrito,departamento);
								

							});
			
						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}
					}
				});
			}
			
			
			
			function Eliminar_Clientes(cod_client)
			{
				if(confirm("¿ Esta Seguro de Eliminar el cliente ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>clientes/Eliminar_Clientes',type:'post',dataType:'json',
						data:
						{
							cod_client:cod_client,
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminaci&oacute;n del cliente se realiz&oacute; con &eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_Empresa();
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el cliente</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								return;
							}
						}			
					});
				}
				
			}
			
			function Listar_Departamento(cod_pais,selectdepartamento)
			{
				
				$('#div_departamento').empty().append('');
				$('#div_provincia').empty().append('');
				$('#div_distrito').empty().append('');
				
				if (cod_pais=='PE')
				{
					newHtml='';		
					newHtml+='<select id="cmb_provincia" style="width:100%;height:22px" >';
					newHtml+='<option value="">[SELECCIONAR]</option>';
					newHtml+='</select>';
					$('#div_provincia').empty().append(newHtml);		
					
					newHtml='';		
					newHtml+='<select id="cmb_distrito" style="width:100% ;height:22px" >';
					newHtml+='<option value="">[SELECCIONAR]</option>';
					newHtml+='</select>';
					$('#div_distrito').empty().append(newHtml);					
					
					$.ajax({
						url:'<?php echo base_url()?>catalogos/Listar_Departamentos',
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
								newHtml+='<select id="cmb_departamento" style="width:100% ;height:22px" onchange="javascrip:Listar_Provincias(this.value,0)">';
								newHtml+='<option value="">[SELECCIONAR]</option>';
								$.each(result.data,function(key,rs)
								{
									newHtml+='<option value="'+rs.co_departamento+'">'+rs.de_departamento+'</option>';
								});
								newHtml+='</select>';
								$('#div_departamento').empty().append(newHtml);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								newHtml='';		
								newHtml+='<select id="cmb_departamento" style="width:100% ;height:22px" onchange="javascrip:Listar_Provincias(this.value,0)">';
								newHtml+='<option value="">[SELECCIONAR]</option>';
								newHtml+='</select>';
								$('#div_departamento').empty().append(newHtml);
							}
							
							if (selectdepartamento>0)
							{
								$('#cmb_departamento').val(selectdepartamento);
							}
							
						}
					});
			

				}
				else
				{
					newHtml='';		
					newHtml+='<select id="cmb_departamento" style="width:100% ;height:22px" >';
					newHtml+='<option value="">[SELECCIONAR]</option>';
					newHtml+='</select>';
					$('#div_departamento').empty().append(newHtml);					
					
					newHtml='';		
					newHtml+='<select id="cmb_provincia" style="width:100%;height:22px" >';
					newHtml+='<option value="">[SELECCIONAR]</option>';
					newHtml+='</select>';
					$('#div_provincia').empty().append(newHtml);		
					
					newHtml='';		
					newHtml+='<select id="cmb_distrito" style="width:100% ;height:22px" >';
					newHtml+='<option value="">[SELECCIONAR]</option>';
					newHtml+='</select>';
					$('#div_distrito').empty().append(newHtml);
					
				}
			}
			
			function Listar_Provincias(cod_depart,selectprovincia)
			{
				$('#div_provincia').empty().append('');
				if (cod_depart=='')
				{
					newHtml='';		
					newHtml+='<select id="cmb_provincia" style="width:100;height:22px" onchange="javascrip:Listar_Distritos(this.value,0,0)">';
					newHtml+='<option value="">[SELECCIONAR]</option>';
					newHtml+='</select>';
					$('#div_provincia').empty().append(newHtml);					
					
					if (selectprovincia>0 || selectprovincia=='')
					{
						$('#cmb_provincia').val(selectprovincia);
					}
				}
				else
				{
					$.ajax({
						url:'<?php echo base_url()?>catalogos/Listar_Provincias',
						type: 'post',
						dataType: 'json',
						data:
						{
							cod_depart:cod_depart
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								newHtml='';		
								newHtml+='<select id="cmb_provincia" style="width:100% ;height:22px" onchange="javascrip:Listar_Distritos(this.value,0,0)">';
								newHtml+='<option value="0">[SELECCIONAR]</option>';
								$.each(result.data,function(key,rs)
								{
									newHtml+='<option value="'+rs.co_provincia+'">'+rs.de_provincia+'</option>';
								});
								newHtml+='</select>';
								$('#div_provincia').empty().append(newHtml);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								newHtml='';		
								newHtml+='<select id="cmb_provincia" style="width:100% ;height:22px" onchange="javascrip:Listar_Distritos(this.value,0,0)">';
								newHtml+='<option value="0">[SELECCIONAR]</option>';
								newHtml+='</select>';
								$('#div_provincia').empty().append(newHtml);
							}
							
							if (selectprovincia>0)
							{
								$('#cmb_provincia').val(selectprovincia);
							}
						}
					});
				}
			}
			
			function Listar_Distritos(cod_provincia,selectdistrito,cod_depart)
			{
				//var cod_depart=$('#cmb_departamento').val();
				if (cod_depart=='')
				{
					var cod_depart=$('#cmb_departamento').val();
				}
				$('#div_distrito').empty().append('');
				if (cod_provincia=='')
				{
					newHtml='';		
					newHtml+='<select id="cmb_distrito" style="width:100% ;height:22px" >';
					newHtml+='<option value="">[SELECCIONAR]</option>';
					newHtml+='</select>';
					$('#div_distrito').empty().append(newHtml);

					if (selectdistrito>0 || selectdistrito=='')
					{
						$('#cmb_distrito').val(selectdistrito);
					}
				}
				else
				{
				$.ajax({
					url:'<?php echo base_url()?>catalogos/Listar_Distritos',
					type: 'post',
					dataType: 'json',
					data:
					{
						cod_depart:cod_depart,
						cod_provincia:cod_provincia
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
						if(result.status==1)
						{
							newHtml='';		
							newHtml+='<select id="cmb_distrito" style="width:100%;height:22px" >';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							$.each(result.data,function(key,rs)
							{
								newHtml+='<option value="'+rs.co_distrito+'">'+rs.de_distrito+'</option>';
							});
							newHtml+='</select>';
							$('#div_distrito').empty().append(newHtml);
							
						}
						else if (result.status==1000)
						{
							document.location.href= '<?php echo base_url()?>usuario';
							return;
						}	
						else
						{
						    newHtml='';		
							newHtml+='<select id="cmb_distrito" style="width:100%;height:22px" >';
							newHtml+='<option value="0">[SELECCIONAR]</option>';
							newHtml+='</select>';
							$('#div_distrito').empty().append(newHtml);
						}
						
						if (selectdistrito>0)
						{
							$('#cmb_distrito').val(selectdistrito);
						}
					}
				});
				}
			}
			
			
			function Bloquear_NumeroDocumento(cod_tipodocumento)
			{
				if (cod_tipodocumento=='0')
				{
					//$("#txt_ruccliente").prop('disabled', true);
					$('#txt_ruccliente').val('-');
				}
				else
				{
					//$("#txt_ruccliente").prop('disabled', false);
					$('#txt_ruccliente').val('');
				}
				
				
				//div_nombrerazonsocial
				if (cod_tipodocumento!='6')//DIFERENTE DE RUC
				{
					newHtml='';		
					newHtml+='Razón Social/Nombre:';
					$('#div_nombrerazonsocial').empty().append(newHtml);
				}
				else
				{
					newHtml='';		
					newHtml+='Razón Social:';
					$('#div_nombrerazonsocial').empty().append(newHtml);
				}
				
				//http://www.jqueryscript.net/form/A-jQuery-Plugin-To-Restrict-Characters-In-Text-Field-Alphanum.html
				//https://github.com/KevinSheedy/jquery.alphanum
				//http://www.anerbarrena.com/jquery-on-off-4767/
								
				if (cod_tipodocumento=='6' || cod_tipodocumento=='1')//IGUAL A RUC Y DNI
				{
					$('#txt_ruccliente').numeric({allow:'-'});
				}
				else
				{
					$("#txt_ruccliente").off();
				}
			}
		</script>
		
    </head>   
    <body>
		<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">CLIENTES</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">
				<div id="div_datosempresa" style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
					<input style="width:15%" type="hidden" id="txt_codcliente"  value="0" />
					<table width="50%" style="border-collapse:separate; border-spacing:1px 1px;" cellpadding="3" class="tablaFormulario">
						<tr><td><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right;width:20%"><label class="columna">Tipo Doc.:</label></td>
							<td style="text-align:left; width:30%">
								<select id="cmb_tipodoccliente" style="width:100%;height:22px" onChange="javascript:Bloquear_NumeroDocumento(this.value)" >
									<option value="">[SELECCIONAR]</option>
									<?php foreach ( $Listar_TipodeDocumentoIdentidad as $v):	?>
										<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(strtoupper(utf8_decode($v['no_corto'])));?> </option>
									<?php  endforeach; ?>
									
								</select>
							</td>
							<td style="text-align:right;width:20%"><label class="columna">N&uacute;mero:</label></td>
							<td style="text-align:left;width:30%">
								<input style="width:100%" type="text" id="txt_ruccliente" maxlength="15" onBlur="javascript:VerDatosCliente_Validar()" />
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna"><div id="div_nombrerazonsocial">Razón Social: </div></label></td>
							<td style="text-align:left" colspan="3">
								<input style="width:100%" type="text" id="txt_razonsocialcliente"  maxlength="150" /></td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna" >Nombre Comercial:</label></td>
							<td style="text-align:left" colspan="3">
								<input style="width:100%" type="text" id="txt_nombrecomercialcliente"   maxlength="80"/></td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Em&#64;il:</label></td>
							<td style="text-align:left" colspan="3">
								<input style="width:100%" type="text" id="txt_correocliente"maxlength="80" /></td>
						</tr>	
						<tr>
							<td style="text-align:right"><label class="columna">Pa&iacute;s:</label></td>
							<td style="text-align:left;">
								<select id="cmb_paiscliente" style="width:100%;height:22px" onChange="javascrip:Listar_Departamento(this.value,0)">
									<option value="">[SELECCIONAR]</option>
									<?php foreach ( $Listar_Paises as $v):	?>
										<option value="<?php echo trim($v['id']); ?>"><?php echo trim(strtoupper(utf8_encode($v['nombre'])));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
							<td style="text-align:right;"><label class="columna">Departamento:</label></td>
							<td style="text-align:left;">
								<div id="div_departamento">
									<select id="cmb_departamento" style="width:100%;height:22px" onChange="javascrip:Listar_Provincias(this.value,0)">
										<option value="">[SELECCIONAR]</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Provincia:</label></td>
							<td style="text-align:left;">
								<div id="div_provincia">
									<select id="cmb_provincia" style="width:100%;height:22px" >
										<option value="">[SELECCIONAR]</option>
									</select>
								</div>
							</td>
							<td style="text-align:right;"><label class="columna">Distrito:</label></td>
							<td style="text-align:left;">
								<div id="div_distrito">
									<select id="cmb_distrito" style="width:100%; height:22px" >
										<option value="">[SELECCIONAR]</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna">Urbanizaci&oacute;n:</label></td>
							<td style="text-align:left" colspan="3" >
								<input style="width:100%" type="text" id="txt_urbanizacioncliente" maxlength="50" placeholder="Ingrese Guión si no conoce la Información" /></td>
						</tr>
						<tr>
							<td style="text-align:right"><label class="columna"> Direcci&oacute;n:</label></td>
							<td style="text-align:left" colspan="3">
								<input style="width:100%" type="text" id="txt_direccioncliente"  maxlength="200" placeholder="Ingrese Guión si no conoce la Información"/> </td>
						</tr>		
						<tr><td><label class="columna"></label></td>
							<td colspan="3" align="center">
								<div style="width:100%;height:15px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:0px;text-align:center;float:left">
									<div id="div_MensajeValidacionEmpresa" style="width:100%;float:left;font-size:9px; color:#FF0000"></div>
								</div>
							</td></tr>
						<tr>
							<td><label class="columna"></label></td>
							<td style="text-align:left">
								<table style="width:100%" >
								  <tbody>
									<tr>
										<td style="text-align:right; width:50%">
											<a href="javascript:ncsistema.Nuevo_Empresa()" >
												<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
														<span class="ui-button-icon-left ui-icon ui-icon-document"></span>
														<span class="ui-button-text">Nuevo</span></button>
											</a>
										</td>
										<td style="text-align:left;width:50%">
											<a href="javascript:ncsistema.Guadar_Empresa()" >
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