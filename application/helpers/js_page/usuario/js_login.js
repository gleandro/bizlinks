$(document).ready(function()
{
	//$('#txt_CantComi').numeric({allow:'.'});
	//$('#btn_CrearCuenta').click(ncsistema.Guardar_Usuario);
	//$('#btn_IniciarSesion').click(ncsistema.Iniciar_Sesion);
	//Comisiones.Comisiones_ListaConsolidador();
	
	//onclick="ncsistema.Guardar_Usuario"
	ncsistema.IniciarSesion();
});
ncsistema=
{
	
	Iniciar_Sesion:function()
	{

		var txt_correoSes=$.trim($('#txt_correoSes').val());					
		var txt_loginSes=$.trim($('#txt_loginSes').val());
		var txt_passwordSes=$.trim($('#txt_passwordSes').val());
		
		if (txt_correoSes=='')
		{
			$("#div_errorcorreoSes").fadeIn(0);
			$('#div_errorcorreoSes').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese el Email</div>');
			setTimeout(function(){ $("#div_errorcorreoSes").fadeOut(1500);},3000);
			return;
		}
		
		if (validarEmail(txt_correoSes)==false)
		{
			$("#div_errorcorreoSes").fadeIn(0);
			$('#div_errorcorreoSes').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">El Email ingresado no es valido</div>');
			setTimeout(function(){ $("#div_errorcorreoSes").fadeOut(1500);},3000);
			return;
		}	
		
		if (txt_loginSes=='')
		{
			$("#div_errorcorreoSes").fadeIn(0);
			$('#div_errorcorreoSes').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese una Login</div>');
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
			url:'<?php echo base_url()?>index.php/usuario_c/Validar_Usuario',
			type: 'post',
			dataType: 'json',
			data:
			{
				txt_correo:txt_correoSes,
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
					document.location.href= '<?php echo base_url()?>index.php/principal_c'; //facturacion_c  principal_c
				}
				else if (result.status==2)
				{
					$("#btn_IniciarSesion").prop('disabled', false);
					$('#div_errorIniciarSesion').fadeIn(0);
					$('#div_errorIniciarSesion').empty().append('<div style="width:100%;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Su Licencia a vencido,Comunicarse con NEOCOMPRA PERU.</div>');
					setTimeout(function(){ $("#div_errorIniciarSesion").fadeOut(1500);},3000);
					return;
				}
				else
				{
					$("#btn_IniciarSesion").prop('disabled', false);
					$('#div_errorIniciarSesion').fadeIn(0);
					$('#div_errorIniciarSesion').empty().append('<div style="width:100%;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Los datos del usuario son incorrectos.</div>');
					setTimeout(function(){ $("#div_errorIniciarSesion").fadeOut(1500);},3000);
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
			setTimeout(function(){ $("#div_errornombre").fadeOut(1500);},3000);
			return;
			
			
			
		}
		if (txt_apellidos=='')
		{
			$("#div_errornombre").fadeIn(0);
			$('#div_errornombre').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese el Apellido</div>');
			setTimeout(function(){ $("#div_errornombre").fadeOut(1500);},3000);
			return;
		}
		if (txt_correo=='')
		{
			$("#div_errornombre").fadeIn(0);
			$('#div_errornombre').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese el Email</div>');
			setTimeout(function(){ $("#div_errornombre").fadeOut(1500);},3000);
			return;
		}
		
		if (validarEmail(txt_correo)==false)
		{
			$("#div_errornombre").fadeIn(0);
			$('#div_errornombre').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">El Email ingresado no es valido</div>');
			setTimeout(function(){ $("#div_errornombre").fadeOut(1500);},3000);
			return;
		}
		
		
		if (txt_login=='')
		{
			$("#div_errornombre").fadeIn(0);
			$('#div_errornombre').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese una Login</div>');
			setTimeout(function(){ $("#div_errornombre").fadeOut(1500);},3000);
			return;
		}
		if (txt_password=='')
		{
			$("#div_errornombre").fadeIn(0);
			$('#div_errornombre').empty().append('<div style="width:100%;height:25px;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Ingrese la Contraseña</div>');
			setTimeout(function(){ $("#div_errornombre").fadeOut(1500);},3000);
			return;
		}
		$("#btn_CrearCuenta").prop('disabled', true);
		
		$.ajax({
			url:'<?php echo base_url()?>index.php/usuario_c/Guardar_Usuario',
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
					$('#div_errorCrearCuenta').empty().append('<div style="width:100%;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">El registro se realizo con exito, ingrese a su correo para activar el usuario.</div>');
					setTimeout(function(){ $("#div_errorCrearCuenta").fadeOut(1500);},3000);
					Limpiar_CrearCuenta();
					return;
				}
				else if (result.status==2)
				{
					$("#btn_CrearCuenta").prop('disabled', false);
					$('#div_errorCrearCuenta').fadeIn(0);
					$('#div_errorCrearCuenta').empty().append('<div style="width:100%;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">El email ingresado ya esta registrado.</div>');
					setTimeout(function(){ $("#div_errorCrearCuenta").fadeOut(1500);},3000);
					return;
				}					
				else
				{
					$("#btn_CrearCuenta").prop('disabled', false);
					$('#div_errorCrearCuenta').fadeIn(0);
					$('#div_errorCrearCuenta').empty().append('<div style="width:100%;border:solid 1px; float:left;text-align:left;background:#F5F5F5;font-weight:bold;text-align:center;border-radius:3px;font-size:15px;font-family:"Times New Roman", Times, serif">Error al registrar los datos.</div>');
					setTimeout(function(){ $("#div_errorCrearCuenta").fadeOut(1500);},3000);
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
		newHtml+='<div id="div_mensajes" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:120px;margin-left:80px">';
		newHtml+='<div style="width:50%;float:left;font-size:22px;font:Arial, Helvetica, sans-serif">Crear una Cuenta</div>';
		
		newHtml+='<div style="width:35%;border:solid 0px;float:left;text-align:right"> o <a href="#" onclick="javascript:ncsistema.IniciarSesion()" style="text-decoration:none;color:#0099CC" >Iniciar sesión</a></div></div>';
	
		newHtml+='<div style="width:60%;height:20px;border:solid 0px;float:left;text-align:left;margin-top:15px;margin-left:80px">							<div id="div_errornombre"></div></div>';
		newHtml+='<div id="" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:15px;margin-left:80px">';
		newHtml+='<input style="height:24px;width:100%" id="txt_nombre" placeholder="Nombre" type="text" size="60" value="" /></div>';				
		
		newHtml+='<div id="" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:15px;margin-left:80px">';
		newHtml+='<input style="height:24px;width:100%" id="txt_apellidos" placeholder="Apellidos" type="text" value="" /></div>';	
		
		newHtml+='<div id="" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:15px;margin-left:80px">';
		newHtml+='<input style="height:24px;width:100%" id="txt_correo" placeholder="Correo Electrónico Válido" type="text" value="" /></div>';	
		
		newHtml+='<div id="" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:15px;margin-left:80px">';
		newHtml+='<input style="height:24px;width:100%" id="txt_login" placeholder="Login" type="text" value="" /></div>';				
		
		
		newHtml+='<div id="" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:15px;margin-left:80px">';
		newHtml+='<input style="height:24px;width:100%" id="txt_password" placeholder="Contraseña" type="password" value="" /></div>';				


		newHtml+='<div style="width:60%;height:20px;border:solid 0px;float:left;text-align:left;margin-top:20px;margin-left:80px"><div id="div_errorCrearCuenta"></div></div>';
		
		newHtml+='<div style="width:60%;float:left;margin-left:80px; padding-top:20px;border:solid 0px;text-align:left">';
		newHtml+='<button style="height:45px;width:130px" id="btn_CrearCuenta" title="Crear una Cuenta" onclick="ncsistema.Guardar_Usuario()">Crear una Cuenta</button></div>';

		$('#div_datossesion').empty().append(newHtml);
	},
	
	IniciarSesion:function()
	{
		$('#div_datossesion').empty().append('');
		newHtml='';					
		newHtml+='<div id="div_mensajes" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:120px;margin-left:80px">';
		newHtml+='<div style="width:50%;float:left;font-size:22px;font:Arial, Helvetica, sans-serif">Iniciar sesión</div>';
		newHtml+='<div style="width:35%;border:solid 0px;float:left;text-align:right"> o <a href="#" onclick="javascript:ncsistema.CrearCuenta()" style="text-decoration:none;color:#0099CC" >Crear una Cuenta</a></div></div>';
		
		newHtml+='<div style="width:60%;height:25px;border:solid 0px;float:left;text-align:left;margin-top:10px;margin-left:80px"><div id="div_errorcorreoSes"></div></div>';
		
		newHtml+='<div id="" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:20px;margin-left:80px">';
		newHtml+='<input style="height:24px;width:100%" id="txt_correoSes" placeholder="Correo Electrónico" type="text" value="" /></div>';				
		
		//newHtml+='<div style="width:60%;height:20px;border:solid 0px;float:left;text-align:left;margin-top:3px;margin-left:80px"><div id="div_errorloginSes"></div></div>';
		newHtml+='<div id="" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:20px;margin-left:80px">';
		newHtml+='<input style="height:24px;width:100%" id="txt_loginSes" placeholder="Nombre del Usuario" type="text" value="" /></div>';				
		
		//newHtml+='<div style="width:60%;height:20px;border:solid 0px;float:left;text-align:left;margin-top:3px;margin-left:80px"><div id="div_errorpasswordSes"></div></div>';
		newHtml+='<div id="" style="width:60%;border:solid 0px;float:left;text-align:left;margin-top:20px;margin-left:80px">';
		newHtml+='<input style="height:24px;width:100%" id="txt_passwordSes" onKeyPress="ncsistema.Iniciar_SesionEnter(event)" placeholder="Contraseña" type="password"  value="" /></div>';				
		
		newHtml+='<div style="width:60%;float:left;text-align:left;margin-top:3px;margin-left:80px"></div>';
		newHtml+='<div id="div_errorIniciarSesion" style="width:60%;height:50px;border:solid 0px;float:left;text-align:left;margin-top:3px;margin-left:80px"></div>';
		
		newHtml+='<div style="width:60%;float:left;margin-left:80px; padding-top:10px;border:solid 0px;text-align:left">';
		newHtml+='<button style="height:45px;width:130px" id="btn_IniciarSesion" title="Iniciar Sesión" onclick="ncsistema.Iniciar_Sesion()">Iniciar Sesión</button></div>';
		

		
		$('#div_datossesion').empty().append(newHtml);
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
	if (/(\w+)(\.?)(\w*)(\@{1})(\w+)(\.?)(\w*)(\.{1})(\w{2,3})/.test(valor))
	{
		return true;
	} 
	else 
	{				
		return false;				
	}
	
}