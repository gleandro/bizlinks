<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Configuraci&oacute;n</title>
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
			})
			
			ncsistema=
			{
				Nuevo_Configuration:function()
				{
					Limpiar_DatosConfiguration();
				},
				Guardar_Configuration:function()
				{
					var txt_hostname=$.trim($('#txt_hostname').val());
					var txt_username=$.trim($('#txt_username').val());
					var txt_password=$.trim($('#txt_password').val());
					var txt_database=$.trim($('#txt_database').val());
					var txt_driver=$.trim($('#txt_driver').val());
					var txt_port=$.trim($('#txt_port').val());
					
					if(txt_hostname=='')
					{
						alert('Ingrese el nombre del host');
						return;
					}
					if(txt_username=='')
					{
						alert('Ingrese el nombre del usuario');
						return;
					}
					if(txt_driver=='')
					{
						alert('Ingrese el driver');
						return;
					}
					
					
					$.ajax({
						url:'<?php echo base_url()?>configuracion/Cambiar_Configuracion',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_hostname:txt_hostname,
							txt_username:txt_username,
							txt_password:txt_password,
							txt_database:txt_database,
							txt_driver:txt_driver,
							txt_port:txt_port
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								Limpiar_DatosConfiguration();
								alert('La configuracion de los datos se realizo con éxito...');
								return;
							}
							else if(result.status==2)
							{
								alert('No tiene autorización para cambiar la configuración');
								return;
							}
							else
							{	
								alert('Error...')
								return;
							}
						}
					});
				
				},
			}
			
			function Limpiar_DatosConfiguration()
			{
				$('#txt_hostname').val('');
				$('#txt_username').val('');
				$('#txt_password').val('');
				$('#txt_database').val('');
				$('#txt_driver').val('');
				$('#txt_port').val('');
			}

			
			
		</script>
		
    </head>   
    <body>
		<?php header('Content-Type: text/html; charset=ISO-8859-1');?>

		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			
			
			<div id="div_datosempresa" style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
				<input style="width:99%" type="hidden" id="txt_tipofirmaempresa"  />

				<table width="40%" style="border-collapse:separate; border-spacing:8px 1px;" cellpadding="3" class="tablaFormulario">
			
					<tr id="row6">
						<td style="text-align:right;"><label class="columna">Host Name:</label></td>
						<td style="text-align:left;" >
							<input style="width:99%" type="text" id="txt_hostname" /></td>
					</tr>
					<tr id="row7">
						<td style="text-align:right"><label class="columna">User Name:</label></td>
						<td style="text-align:left">
							<input style="width:99%" type="text" id="txt_username"  /></td>
					</tr>
					<tr id="row8">
						<td style="text-align:right"><label class="columna">Password:</label></td>
						<td style="text-align:left">
							<input style="width:99%" type="text" id="txt_password"  value=""/></td>
					</tr>				
					<tr id="row12">
						<td style="text-align:right"><label class="columna"> Database:</label></td>
						<td style="text-align:left"><input style="width:99%" type="text" id="txt_database"  /></td>
					</tr>
					<tr id="row13">
						<td style="text-align:right"><label class="columna"> Driver:</label></td>
						<td style="text-align:left"><input style="width:99%" type="text" id="txt_driver"  /></td>
					</tr>	
					<tr id="row13">
						<td style="text-align:right"><label class="columna"> Port:</label></td>
						<td style="text-align:left"><input style="width:99%" type="text" id="txt_port"  /></td>
					</tr>

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

		</div>
    </body>	
</html>