<!doctype html>
<html>
<head>
	<title>SFE Bizlinks Facturador Local</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	
	<script type="text/javascript" src="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/external/jquery/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.js"></script>

	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.min.css"/>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.css"/>

	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/inicio.css"/>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/menusystem.css"/>
	<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/site.css"/>
		
	<script type="text/javascript"> 			
		$(document).ready(function()
		{		});
		ncsistema=
		{		}
		//#F0F0F0
	</script>

</head>   
<body>
<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
	<div id="Div_HeadSistema" >
		<?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		
		<div id="Div_Body" style="width:100%; height:530px;  float:left; text-align:center; 
			margin-top:5px; border-radius:3px; border: 1px solid #a6c9e2;">
			
			<table border="0" style="background-color:#FFFEE5; width:100%; height:100%;">
			  <tbody>
				<tr height="95%">
					<td width="20%"></td>
					<td width="60%" height="100%" style="background-image: url(<?php echo base_url() ?>application/helpers/image/logos/eFactMdeioAmbiente.png); 
			background-repeat: no-repeat;" ></td>
					<td width="20%">
					</td>
				</tr>
				<tr height="5%">
					<td width="100%" colspan="3" align="left" >
						<div align="rigth" style="width:100%; float: left; font-size: 11px; color: #4ca20b;">&copy; Bizlinks - Todos los derechos reservados 2016. Versión 2.0
						</div></td>
				</tr>
			  </tbody>
			</table>
			
		</div>
	</div>

</body>
</html>