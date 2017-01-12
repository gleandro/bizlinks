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
				$('#txt_NumInicioProducto').numeric();//$('#txt_NumeroDocumento').numeric({allow:'.'});		
				$('#txt_NumeroCerosIzquierda').numeric();
				
				//Ver_EjemploCodigoProducto();
				ncsistema.Listar_ParametrosTabla('');
				ncsistema.Listar_PermisosAsignadosTabla('');

			});			
			ncsistema=
			{

				Guardar_Parametros:function (cod_men)
				{
					var Cmb_Empresa=$.trim($('#Cmb_Empresa').val());
					var Cmb_Usuario=$.trim($('#Cmb_Usuario').val());
					
					if (Cmb_Empresa=='')
					{
						return;
					}
					if (Cmb_Usuario=='')
					{
						return;
					}

					$.ajax({
						url:'<?php echo base_url()?>usuarioacceso/Guardar_Parametros',
						type: 'post',
						dataType: 'json',
						data:
						{
							cod_men:cod_men,
							Cmb_Empresa:Cmb_Empresa,
							Cmb_Usuario:Cmb_Usuario		
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionCliente').fadeIn(0);
								$('#div_MensajeValidacionCliente').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La actualización de los datos se realizó con éxito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionCliente").fadeOut(1500);},3000);
								ncsistema.Buscar_Permisos();
								return;
							}
						}
					});					
				},				
				
				Listar_Parametros:function ()
				{
					var Cmb_Empresa=$.trim($('#Cmb_Empresa').val());
					var Cmb_Usuario=$.trim($('#Cmb_Usuario').val());
					
					if (Cmb_Empresa=='')
					{
						return;
					}
					if (Cmb_Usuario=='')
					{
						return;
					}
					
					$.ajax({
						url:'<?php echo base_url()?>usuarioacceso/Listar_MenuSistemaPendiente',
						type: 'post',
						dataType: 'json',
						data:
						{
							Cmb_Empresa:Cmb_Empresa,
							Cmb_Usuario:Cmb_Usuario
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_ParametrosTabla(result.data);
							}
							else
							{
								ncsistema.Listar_ParametrosTabla("");
							}
						}
					});					
				},			
				
				
				Listar_ParametrosTabla:function(data)
				{

					$('#div_ListadoParametros').empty().append('');
					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaAccesosPendientes">';
					newHtml+='<thead>';
					newHtml+='<tr>';
					
						newHtml+='<th style="width:10%">Nro.</td>';
						newHtml+='<th style="width:60%">Nombre</td>';
						newHtml+='<th style="width:10%">Asignar</td> ';

					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';
					$.each(data,function(key,rs)
					{
						newHtml+='<tr class="modo1">';

							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nom_men+'</td>';
							newHtml+='<td style="text-align:center"><input style="text-align:center" id="txt_variable_'+rs.cod_men+'" name="txt_variable_'+rs.cod_men+'" onchange="javascrip:ncsistema.Guardar_Parametros('+rs.cod_men+')" type="checkbox" value="" /></td>';						
							
							
						newHtml+='</tr>';	
					});	
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_ListadoParametros').empty().append(newHtml);	

					oTable=$('#Tab_ListaAccesosPendientes').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_ListaAccesosPendientes tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});

				
				},
				
				
				Listar_PermisosAsignados:function ()
				{
					var Cmb_Empresa=$.trim($('#Cmb_Empresa').val());
					var Cmb_Usuario=$.trim($('#Cmb_Usuario').val());
					
					if (Cmb_Empresa=='')
					{
						return;
					}
					if (Cmb_Usuario=='')
					{
						return;
					}
					
					$.ajax({
						url:'<?php echo base_url()?>usuarioacceso/Listar_MenuSistemaAsignado',
						type: 'post',
						dataType: 'json',
						data:
						{
							Cmb_Empresa:Cmb_Empresa,
							Cmb_Usuario:Cmb_Usuario
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_PermisosAsignadosTabla(result.data);
							}
							else
							{
								ncsistema.Listar_PermisosAsignadosTabla("");
							}
						}
					});					
				},			
				
				
				Listar_PermisosAsignadosTabla:function(data)
				{
					$('#div_ListadoPermisosAsignados').empty().append('');

					newHtml='';
					newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListadoPermisosAsignados">';
					newHtml+='<thead>';
					newHtml+='<tr>';
						newHtml+='<th style="width:10%">Nro.</td>';
						newHtml+='<th style="width:60%">Nombre</td>';
						newHtml+='<th style="width:10%">OP</td> ';
					newHtml+='</tr>';
					newHtml+='</thead>';
        			newHtml+='<tbody>';

					$.each(data,function(key,rs)
					{
						newHtml+='<tr class="modo1">';
							newHtml+='<td style="text-align:center">'+rs.nro_secuencia+'</td>';
							newHtml+='<td style="text-align:left">'+rs.nom_men+'</td>';
							newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Acceso('+rs.cod_usuacc+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Imprimir" width="15"  height="15" border="0" ></a></td>';
							
								
						newHtml+='</tr>';	
					});	
					
					newHtml+='</tbody>';
					newHtml+='</table>';
					
					$('#div_ListadoPermisosAsignados').empty().append(newHtml);	

					oTable=$('#Tab_ListadoPermisosAsignados').dataTable({
						"bPaginate": true,
						"sScrollX": "100%",
						"sScrollXInner": "100%",
						"bScrollCollapse": true,
						"bJQueryUI": true
					});
				 
					$("#Tab_ListadoPermisosAsignados tbody").click(function(event) 
					{
						$(oTable.fnSettings().aoData).each(function (){
							$(this.nTr).removeClass('row_selected');
						});
						$(event.target.parentNode).addClass('row_selected');
					});
					
					
				},
				
				Buscar_Permisos:function()
				{
					var Cmb_Empresa=$.trim($('#Cmb_Empresa').val());
					var Cmb_Usuario=$.trim($('#Cmb_Usuario').val());

					if (Cmb_Empresa=='')
					{
						ncsistema.Listar_ParametrosTabla('');
						ncsistema.Listar_PermisosAsignadosTabla('');
						return;
					}
					if (Cmb_Usuario=='')
					{
						ncsistema.Listar_ParametrosTabla('');
						ncsistema.Listar_PermisosAsignadosTabla('');
						return;
					}
					
					ncsistema.Listar_Parametros();
					ncsistema.Listar_PermisosAsignados();
				}
			}
			
			
			
			function Eliminar_Acceso(cod_usuacc)
			{
				if(confirm("¿ Esta Seguro de Eliminar El Accesso ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>usuarioacceso/Eliminar_Acceso',type:'post',dataType:'json',
						data:
						{
							cod_usuacc:cod_usuacc,
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								$('#div_MensajeValidacionCliente').fadeIn(0);
								$('#div_MensajeValidacionCliente').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminación de los datos se realizó con éxito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionCliente").fadeOut(1500);},3000);
								ncsistema.Buscar_Permisos();
								return;
	
							}else
							{
								$('#div_MensajeValidacionCliente').fadeIn(0);
								$('#div_MensajeValidacionCliente').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el accesos </div>');
								setTimeout(function(){ $("#div_MensajeValidacionCliente").fadeOut(1500);},3000);
								return;
							}
						}			
					});
				}
			}

			
		</script>
		
    </head>   
    <body>
<?php header('Content-Type: text/html; charset=ISO-8859-1');?>
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas); ?></div>
		
		<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:5px">
			<div style="width:60%;height:30px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:6px;text-align:center;float:left">
				<div id="div_MensajeValidacionCliente" style="width:100%;float:left;font-size:9px"></div>
			</div>
		</div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">ASIGNACION DE PERMISOS</a></li>
			</ul>			
			<div id="tabs-1" style="width:97%;float:left">
		
				<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:10px">
					
					<table width="100%" border="0px" class="tablaFormulario">
						<tr>
							<td style="text-align:right;width:10%" >Empresa:</td>
							<td style="text-align:left;width:80%" >
								<select id="Cmb_Empresa" style="width:50%;height:30px" onChange="javascrip:ncsistema.Buscar_Permisos(this.value)">
									<option value="">[SELECCIONAR]</option>
									<?php foreach ( $Listar_Empresa as $v):	?>
										<option value="<?php echo trim($v['cod_empr']); ?>"><?php echo trim(utf8_encode($v['raz_social']));?> </option>
									<?php  endforeach; ?>
								</select>
							</td>
						</tr>
							
						<tr>
							<td style="text-align:right;width:10%">Usuario:</td>
							<td style="text-align:left;width:80%">							
								<select id="Cmb_Usuario" style="width:50%;height:30px" onChange="javascrip:ncsistema.Buscar_Permisos(this.value)">
									<option value="">[SELECCIONAR]</option>
									<?php foreach ( $Listar_UsuarioDatos as $v):	
										if ($v['cod_tipusu']==0)
											{	
										?>
										<option value="<?php echo trim($v['cod_usu']); ?>"><?php echo trim(utf8_encode($v['apell_usu'])).', '.trim(utf8_encode($v['nom_usu'])).'  [ '.trim(utf8_encode($v['login_usu'])).' ]';?> </option>
									<?php }  endforeach; ?>
								</select>
							</td>
						</tr>
					</table>
					
				</div>
				
				<div style="width:100%;border:solid 1px;float:left;text-align:center;margin-top:10px">
				
					<div id="div_ListadoParametros" style="width:47%;border:solid 0px;float:left;text-align:center;margin-top:7px;margin-left:5px;margin-bottom:5px">
					</div>
					
					<div id="div_ListadoPermisosAsignados" style="width:48%;border:solid 0px;float:left;text-align:center;margin-top:7px;margin-left:5px;margin-bottom:5px">
					</div>
					
				</div>	
			</div>
		</div>
		<div>
			<?php $this->load->view('inicio/footer'); ?> 
		</div>
    </body>
	
</html>