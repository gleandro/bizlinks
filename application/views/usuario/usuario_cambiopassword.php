<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Usuario</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.min.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/plugins/dataTable/css/dataTables-all.css" />

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
		
		<script>var urlexportardatos="<?php echo base_url();?>"</script>	
	
		<script type="text/javascript">
			$(document).ready(function()
			{
				$("#tabs").tabs();
			});			
			ncsistema=
			{
				Nuevo_MarcoTrabajo:function()
				{
					Limpiar_DatosMarcoTrabajo();
				},

				Guardar_MarcoTrabajo:function()
				{
					
					var txt_Contrasena=$.trim($('#txt_Contrasena').val());					
					var txt_ContrasenaValidar=$.trim($('#txt_ContrasenaValidar').val());

					if (txt_Contrasena=='')
					{
						$('#div_MensajeValidacionMarcoTrabajo').fadeIn(0);
						$('#div_MensajeValidacionMarcoTrabajo').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese la Nueva Contrase&ntilde;a</div>');
						setTimeout(function(){ $("#div_MensajeValidacionMarcoTrabajo").fadeOut(1500);},3000);
						return;
					}
					
					if (txt_ContrasenaValidar=='')
					{
						$('#div_MensajeValidacionMarcoTrabajo').fadeIn(0);
						$('#div_MensajeValidacionMarcoTrabajo').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Ingrese la Nueva Contrase&ntilde;a a Validar</div>');
						setTimeout(function(){ $("#div_MensajeValidacionMarcoTrabajo").fadeOut(1500);},3000);
						return;
					}


					if (txt_Contrasena!=txt_ContrasenaValidar)
					{
						$('#div_MensajeValidacionMarcoTrabajo').fadeIn(0);
						$('#div_MensajeValidacionMarcoTrabajo').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Las contrase&ntilde;as ingresadas no son iguales</div>');
						setTimeout(function(){ $("#div_MensajeValidacionMarcoTrabajo").fadeOut(1500);},3000);
						return;
					}


					$.ajax({
						url:'<?php echo base_url()?>usuario/Modificar_PasswordUsuario',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_Contrasena:txt_Contrasena					
						},
						beforeSend:function()
						{
							$('#div_Guardar').removeClass('enablediv');
							$("#div_Guardar").addClass("disablediv").off("onclick");

							$('#div_MensajeValidacionMarcoTrabajo').fadeIn(0);
							$('#div_MensajeValidacionMarcoTrabajo').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/procesando.gif" width="27" height="27"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Procesando, Espere por favor...</div>');						
						
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionMarcoTrabajo').fadeIn(0);
								$('#div_MensajeValidacionMarcoTrabajo').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">El cambio de la contrase&ntilde;a se realiz&oacute; con &eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionMarcoTrabajo").fadeOut(1500);},3000);
								
								Limpiar_DatosMarcoTrabajo();								

							}
							else							
							{
								$('#div_Guardar').removeClass('disablediv');
								$("#div_Guardar").addClass("enablediv").on("onclick");

								$('#div_MensajeValidacionMarcoTrabajo').fadeIn(0);
								$('#div_MensajeValidacionMarcoTrabajo').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al cambiar la contrase&ntilde;a</div>');
								setTimeout(function(){ $("#div_MensajeValidacionMarcoTrabajo").fadeOut(1500);},3000);
								return;
							}
						}
					});
					
				}
				
			}

			function Limpiar_DatosMarcoTrabajo()
			{
				$.trim($('#txt_Contrasena').val(''));
				$.trim($('#txt_ContrasenaValidar').val(''));
				$('#div_Guardar').removeClass('disablediv');
				$("#div_Guardar").addClass("enablediv").on("onclick");

			}

		</script>

</head>   
<body>
<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
	<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">CAMBIO DE CONTRASE&Ntilde;A</a></li>
			</ul>			
			<div id="tabs-1" style="width:97%;float:left">
		
				<div style="width:30%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">					
		
					<table border="0" width="100%" style="border-collapse:separate; border-spacing:8px 1px;" cellpadding="3" class="tablaFormulario">
						<tr><td><label class="columna"></label></td></tr>
						<tr>
							<td style="text-align:right;width:35%"><label class="columna">Nueva Contrase&ntilde;a:</label></td>
							<td style="text-align:left;width:65%">
								<input type="password" style="height:20px; width:100%" id="txt_Contrasena"  /></td>
						</tr>
						<tr>
							<td align="right"><label class="columna">Validar Contrase&ntilde;a:</label></td>
							<td align="left">
								<input type="password" style="height:20px; width:100%" id="txt_ContrasenaValidar"  /></td>
						</tr>
						<tr><!--<td><label class="columna"></label></td>-->
							<td colspan="2" align="center">
								<div style="width:100%;height:20px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:0px;text-align:center;float:left">
									<div id="div_MensajeValidacionMarcoTrabajo" style="width:100%;float:left;font-size:9px;color:#FF0000"></div>
								</div>
							</td></tr>
						<tr>
							<td><label class="columna"></label></td>
							<td style="text-align:left">
								<table style="width:100%" >
								  <tbody>
									<tr>
										<td style="text-align:right; width:50%">
											<a href="javascript:ncsistema.Nuevo_MarcoTrabajo()" >
												<button id="" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-left" style="width:105px; height:32px" type="submit">
														<span class="ui-button-icon-left ui-icon ui-icon-document"></span>
														<span class="ui-button-text">Nuevo</span></button>
											</a>
										</td>
										<td style="text-align:left;width:50%">
											<a href="javascript:ncsistema.Guardar_MarcoTrabajo()" >
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
			</div>
		</div>		
    </body>
	
</html>