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
	<style type="text/css">
            body {
                font-size: 10px;
            }
        </style>
	<script type="text/javascript"> 
			
			//FORMA MANUAL PARA ACTUALIZAR EL JS <script type="text/javascript" src="genrt_volante.js?o=5219">/
			$(document).ready(function()
			{
				ncsistema.IniciarSesion();
			});
			ncsistema=
			{
				
				Iniciar_Sesion:function()
				{
					
					var txt_loginSes=$.trim($('#txt_loginSes').val());
					var txt_passwordSes=$.trim($('#txt_passwordSes').val());
					
					if (txt_loginSes=='')
					{
						$("#div_errorcorreoSes").fadeIn(0);
						$('#div_errorcorreoSes').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese Login</div>');
						setTimeout(function(){ $("#div_errorcorreoSes").fadeOut(1500);},3000);
						return;
					}
					
					if (validarEmail(txt_loginSes)==false)
					{
						$("#div_errorcorreoSes").fadeIn(0);
						$('#div_errorcorreoSes').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">El Email ingresado no es válido</div>');
						setTimeout(function(){ $("#div_errorcorreoSes").fadeOut(1500);},3000);
						return;
					}
					
					if (txt_passwordSes=='')
					{
						$("#div_errorcorreoSes").fadeIn(0);
						$('#div_errorcorreoSes').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese la Contraseña</div>');
						setTimeout(function(){ $("#div_errorcorreoSes").fadeOut(1500);},3000);
						return;
					}
					
					$("#btn_IniciarSesion").prop('disabled', true);
					
					$.ajax({
						url:'<?php echo base_url()?>usuario/validar_usuario',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_login:txt_loginSes,
							txt_password:txt_passwordSes
						},
						beforeSend:function()
						{
							$('#div_errorIniciarSesion').fadeIn(0);
							$('#div_errorIniciarSesion').empty().append('<div style="width:100%;height:100px;text-align:left;font-weight:bold;">Iniciando, Espere por Favor ...</div>');
						},
						success:function(result)
						{
							if(result.status==1)
							{
								//alert("Bienvenido al sistema  11111111");
								document.location.href= '<?php echo base_url()?>principal'; //facturacion_c  principal
							}
							else if (result.status==2)
							{
								$("#btn_IniciarSesion").prop('disabled', false);
								$('#div_errorIniciarSesion').fadeIn(0);
								$('#div_errorIniciarSesion').empty().append('<div style="width:100%;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Su Licencia a vencido,Comunicarse con Bizlinks S.A.</div>');
								setTimeout(function(){ $("#div_errorIniciarSesion").fadeOut(1500);},1000);
								return;
							}
							else
							{
								$("#btn_IniciarSesion").prop('disabled', false);
								$('#div_errorIniciarSesion').fadeIn(0);
								$('#div_errorIniciarSesion').empty().append('<div style="width:100%;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Los datos del usuario son incorrectos.</div>');
								setTimeout(function(){ $("#div_errorIniciarSesion").fadeOut(1500);},1000);
								return;
							}
						}
					});
				},
				
				Iniciar_SesionEnter:function(event)
				{
				  if ( event.which == 13 ) 
				  {
					 ncsistema.Iniciar_Sesion(); //event.preventDefault();
				  }
				},
				
				Guardar_Usuario:function()
				{
					//alert("Alerta");
					var txt_nombre=$.trim($('#txt_nombre').val());
					var txt_apellidos=$.trim($('#txt_apellidos').val());
					var txt_correo=$.trim($('#txt_correo').val());					
					var txt_login=$.trim($('#txt_login').val());
					var txt_password=$.trim($('#txt_password').val());

					if (txt_nombre=='')
					{
						$("#div_errornombre").fadeIn(0);
						$('#div_errornombre').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese el nombre</div>');
						setTimeout(function(){ $("#div_errornombre").fadeOut(1500);},1000);
						return;
						
						
						
					}
					if (txt_apellidos=='')
					{
						$("#div_errornombre").fadeIn(0);
						$('#div_errornombre').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese el Apellido</div>');
						setTimeout(function(){ $("#div_errornombre").fadeOut(1500);},1000);
						return;
					}
					if (txt_correo=='')
					{
						$("#div_errornombre").fadeIn(0);
						$('#div_errornombre').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese el Email</div>');
						setTimeout(function(){ $("#div_errornombre").fadeOut(1500);},1000);
						return;
					}
					
					if (validarEmail(txt_correo)==false)
					{
						$("#div_errornombre").fadeIn(0);
						$('#div_errornombre').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">El Email ingresado no es válido</div>');
						setTimeout(function(){ $("#div_errornombre").fadeOut(1500);},1000);
						return;
					}
					
					
					if (txt_login=='')
					{
						$("#div_errornombre").fadeIn(0);
						$('#div_errornombre').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese Login</div>');
						setTimeout(function(){ $("#div_errornombre").fadeOut(1500);},1000);
						return;
					}
					if (txt_password=='')
					{
						$("#div_errornombre").fadeIn(0);
						$('#div_errornombre').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese la Contraseña</div>');
						setTimeout(function(){ $("#div_errornombre").fadeOut(1500);},1000);
						return;
					}
					$("#btn_CrearCuenta").prop('disabled', true);
					
					$.ajax({
						url:'<?php echo base_url()?>usuario/guardar_usuario',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_nombre:txt_nombre,
							txt_apellidos:txt_apellidos,
							txt_correo:txt_correo,
							txt_login:txt_login,
							txt_password:txt_password
						},
						beforeSend:function()
						{
							$('#div_errorCrearCuenta').fadeIn(0);
							$('#div_errorCrearCuenta').empty().append('<div style="width:100%;height:100px;text-align:left;">Procesando, Espere por Favor ...</div>');
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$("#btn_CrearCuenta").prop('disabled', false);
								$('#div_errorCrearCuenta').fadeIn(0);
								$('#div_errorCrearCuenta').empty().append('<div style="width:100%;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">El registro se realizó con éxito, ingrese su correo para activar el usuario.</div>');
								setTimeout(function(){ $("#div_errorCrearCuenta").fadeOut(1500);},1000);
								Limpiar_CrearCuenta();
								return;
							}
							else if (result.status==2)
							{
								$("#btn_CrearCuenta").prop('disabled', false);
								$('#div_errorCrearCuenta').fadeIn(0);
								$('#div_errorCrearCuenta').empty().append('<div style="width:100%;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">El email ingresado ya está registrado.</div>');
								setTimeout(function(){ $("#div_errorCrearCuenta").fadeOut(1500);},1000);
								return;
							}					
							else
							{
								$("#btn_CrearCuenta").prop('disabled', false);
								$('#div_errorCrearCuenta').fadeIn(0);
								$('#div_errorCrearCuenta').empty().append('<div style="width:100%;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Error al registrar los datos.</div>');
								setTimeout(function(){ $("#div_errorCrearCuenta").fadeOut(1500);},1000);
								return;
							}
						}
					});
				},
				
				CrearCuenta:function()
				{
					$('#DivMensajeEmail').empty().append('');
					$('#div_datossesion').empty().append('');
					newHtml='';	
					newHtml+='<table style="width:260px; border: solid 0px; float:left; text-align:left;"> <tbody>';
					newHtml+='<tr> <td style="border: solid 0px; width:100%;" colspan=2>';
						newHtml+='<div id="div_mensajes" style="width:100%;  float:left; text-align:left; margin-top:100px; margin-left:10px">';
							newHtml+='<div style="border: solid 0px; float:left; font-size:12px; font:Arial, Helvetica, sans-serif; color: #006699; font-weight: bold;"> Crear una Cuenta</div></div>';
							newHtml+='<div style="width:35%; border:solid 0px; float:left; text-align:right"> o <a href="#" onclick="javascript:ncsistema.IniciarSesion()" style="text-decoration:none;color:#0099CC" >Iniciar sesi&oacute;n</a></div>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:30%; height:25px" colspan=2>'
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:30%;">';
						newHtml+='<label class="columna">Nombres :</label></div>';
					newHtml+='</td>';
					newHtml+='<td style="width:70%;">';
						//newHtml+='<input class="negritaEstandar" style="width:100%" id="txt_correoSes" placeholder="Correo Electrónico" type="text" value="arevalo.ilmer@gmail.com" /></div>';
						newHtml+='<input class="negritaEstandar" style="width:100%" id="txt_nombre" placeholder="Nombres" type="text" size="60" value="" /></div>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:30%;">';
						newHtml+='<label class="columna">Apellidos :</label></div>';
					newHtml+='</td>';
					newHtml+='<td style="width:70%;">';
						newHtml+='<input class="negritaEstandar" style="width:100%" id="txt_apellidos" placeholder="Apellidos" type="text" value="" /></div>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:30%;">';
						newHtml+='<label class="columna">Email :</label></div>';
					newHtml+='</td>';
					newHtml+='<td style="width:70%;">';
						newHtml+='<input class="negritaEstandar" style="width:100%" id="txt_correo" placeholder="Correo Electr&oacute;nico V&aacute;lido" type="text" value="" /></div>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:30%;">';
						newHtml+='<label class="columna">Login :</label></div>';
					newHtml+='</td>';
					newHtml+='<td style="width:70%;">';
						newHtml+='<input class="negritaEstandar" style="width:100%" id="txt_login" placeholder="Login" type="text" value="" /></div>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:30%;">';
						newHtml+='<label class="columna">Contrase&ntilde;a :</label></div>';
					newHtml+='</td>';
					newHtml+='<td style="width:70%;">';
						newHtml+='<input class="negritaEstandar" style="width:100%" id="txt_password" placeholder="Contrase&ntilde;a" type="password" value="" /></div>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:100%;" colspan=2>';
						newHtml+='<div id="div_errorCrearCuenta" style="width:100%; height:20px; border:solid 0px; float:left; text-align:left; margin-top:8px; margin-left:30px"></div>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:100%;" colspan=2 align="right">';
						newHtml+='<button type="submit" id="btn_CrearCuenta" type="button" class="ui-button-1" onclick="ncsistema.Guardar_Usuario()">';
						newHtml+='<span class="ui-button-icon-left ui-icon ui-icon-person"></span><span class="ui-button-text">Crear una cuenta</span>';
					  newHtml+='</button>';
					newHtml+='</td> </tr>';
					newHtml+='</tbody></table>';
					
					
					//newHtml+='<div style="width:60%;height:20px;border:solid 0px;float:left;text-align:left;margin-top:20px;margin-left:80px"><div id="div_errorCrearCuenta"></div></div>';
					
					//newHtml+='<div style="width:60%;float:left;margin-left:80px; padding-top:20px;border:solid 0px;text-align:left">';
					//newHtml+='<button style="height:45px;width:130px" id="btn_CrearCuenta" title="Crear una Cuenta" onclick="ncsistema.Guardar_Usuario()">Crear una Cuenta</button></div>';

					$('#div_datossesion').empty().append(newHtml);
				},
				
				IniciarSesion:function()
				{
					$('#div_datossesion').empty().append('');
					
					newHtml='';	
					newHtml+='<table style="width:260px; border: solid 0px; float:left; text-align:left;"> <tbody>';
					newHtml+='<tr> <td style="border: solid 0px; width:100%;" colspan=2>';
						newHtml+='<div id="div_mensajes" style="width:100%;  float:left; text-align:left; margin-top:100px; margin-left:10px">';
							newHtml+='<div style="border: solid 0px; float:left; font-size:12px; font:Arial, Helvetica, sans-serif; color: #006699; font-weight: bold;"> Acceso de Usuario Registrado</div></div>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:100%;" colspan=2>';
						newHtml+='<div style="width:100%; height:25px; border:solid 0px; float:left; text-align:left; "><div id="div_errorcorreoSes"></div></div>';
					newHtml+='</td> </tr>';

					newHtml+='<tr> <td style="width:30%;">';
						newHtml+='<label class="columna">Usuario :</label></div>';
					newHtml+='</td>';
					newHtml+='<td style="width:70%;">';
						newHtml+='<input class="negritaEstandar" style="width:100%" id="txt_loginSes" placeholder="correo@micorreo.com" type="text" /></div>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:30%;">';
						newHtml+='<label class="columna">Contrase&#241;a :</label></div>';
					newHtml+='</td>';
					newHtml+='<td style="width:70%;">';
						newHtml+='<input class="negritaEstandar" style="width:60%" id="txt_passwordSes" onKeyPress="ncsistema.Iniciar_SesionEnter(event)" placeholder="Clave" type="password"  /></div>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:100%;" colspan=2 align="center">';
						newHtml+='<a href="#" onclick="javascript:ncsistema.CrearCuenta()" style="text-decoration:none;color:#0099CC" ></a>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:100%;" colspan=2 align="right">';
						newHtml+='<button type="submit" id="btn_IniciarSesion" type="button" class="ui-button-Sesion ui-widget ui-button-text-icon-left myHoverButtonSesion" onclick="ncsistema.Iniciar_Sesion()">';
						newHtml+='<span class="ui-button-icon-left ui-icon ui-icon-person"></span><span class="ui-button-text">Iniciar Sesi&oacute;n</span>';
					  newHtml+='</button>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:100%;" colspan=2>';
						newHtml+='<div id="div_errorIniciarSesion" style="width:100%;height:40px; border:solid 0px; float:left;text-align:left; margin-top:3px; margin-left:30px"></div>';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:100%; height:20px; border:solid 0px" colspan=2">';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:100%; border:solid 0px" colspan=2" align="center">';
						newHtml+='&iquest;No tiene un usuario? Puedes consultar la opci&oacute;n <a href="<?php echo base_url()?>loginanonimo" >Acceso An&oacute;nimo</a> para ver tus comprobantes.';
					newHtml+='</td> </tr>';
					newHtml+='<tr> <td style="width:100%; border:solid 0px" colspan=2" align="left">';
						newHtml+='<div class="copy" align="left" style="width:100%">&copy; 2016 Bizlinks todos los derechos reservados. </div>';
					newHtml+='</td> </tr>';
					newHtml+='</tbody></table>';
					
					$('#div_datossesion').empty().append(newHtml);
					
					//newHtml+='<div style="width:35%;border:solid 0px;float:left;text-align:right"> 
					//			<a href="#" onclick="javascript:ncsistema.CrearCuenta()" style="text-decoration:none;color:#0099CC" >Crear una Cuenta</a></div></div>';//Crear una Cuenta
					//newHtml+='<button id="btn_IniciarSesion" title="Iniciar Sesi&oacute;n" onclick="ncsistema.Iniciar_Sesion()">Iniciar Sesi&oacute;n</button></div>';
					//contenedor de email: newHtml+='<div id="" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:20px;margin-left:80px">';
					//newHtml+='<div style="width:60%;height:20px;border:solid 0px;float:left;text-align:left;margin-top:3px;margin-left:80px"><div id="div_errorloginSes"></div></div>';
					//contenedor de usuario: newHtml+='<div id="" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:20px;margin-left:80px">';
					//newHtml+='<div style="width:60%;height:20px;border:solid 0px;float:left;text-align:left;margin-top:3px;margin-left:80px"><div id="div_errorpasswordSes"></div></div>';
					//contenedor de clavenewHtml+='<div id="" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:20px;margin-left:80px">';
					//contenedor error: newHtml+='<div style="width:60%;float:left;text-align:left;margin-top:3px;margin-left:80px"></div>';
					//conetenedor boton: newHtml+='<div style="width:60%;float:left;margin-left:80px; padding-top:10px;border:solid 0px;text-align:left">';
					//height:20px;
				},
			}
			
			function Limpiar_CrearCuenta()
			{
				$('#txt_nombre').val('');
				$('#txt_apellidos').val('');
				$('#txt_correo').val('');
				$('#txt_login').val('');
				$('#txt_password').val('');
			}
			
			function validarEmail(valor) 
			{
				filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				//if (/(\w+)(\.?)(\w*)(\@{1})(\w+)(\.?)(\w*)(\.{1})(\w{2,3})/.test(valor))
				if (filter.test(valor))
				{
					return true;
				} 
				else 
				{				
					return false;				
			  	}
				
			}
			
			
		</script>

		
    </head> 

<body id="login" class="animated fadeInDown ui-layout-container" style="position: relative; height: 100%; overflow: hidden; margin: 0px; padding: 0px; border: medium none; font-size: 10px;">
<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
	<div style="height: 100%; width: 100%; margin: 0px; left: 0px; right: 0px; top: 0px; bottom: 0px;  z-index: 1; display: block; visibility: visible;" id="j_id_1" class="ui-layout-unit ui-widget ui-corner-all ui-layout-center ui-layout-pane ui-layout-pane-center" >
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
					<div id="todo" style="position: relative; border-bottom:#006699 1px solid; height: 100%; width: 100%; margin: 0 auto; 
						visibility: visible;"></div>
				</td>
				<td  width="20%"><span></span></td>
			</tr>
		  </tbody>
		</table>
		</div>
	</div>
</body>
	
</html>