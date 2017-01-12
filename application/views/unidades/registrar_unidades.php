<!doctype html>
<html>
<head>
		<title>SFE Bizlinks - Unidades</title>
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
				
				ncsistema.Listar_ConfigurationTabla("");	
				ncsistema.Listar_Configuration();
				//$('#txt_RucEmpresa').numeric();
				
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
					var cmb_unidadsunat=$.trim($('#cmb_unidadsunat').val());					
					var cmb_nombreunidadsunat=$("#cmb_unidadsunat :selected").text();
					var txt_nombreequivalente=$.trim($('#txt_nombreequivalente').val());
					var txt_codtipoconfig=$.trim($('#txt_codtipoconfig').val());


					if (txt_cod_empr==0)
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Seleccione el emisor</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;

					}
					if (cmb_unidadsunat=='0')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Seleccione la unidad de SUNAT</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;

					}
					if (txt_nombreequivalente=='')
					{
						$('#div_MensajeValidacionEmpresa').fadeIn(0);
						$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/ncexclamacion.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left; color:#FF0000">Ingrese el nombre equivalente</div>');
						setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
						return;
					}
					
					$.ajax({
						url:'<?php echo base_url()?>unidades/Guardar_UnidadEquivalencia',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_cod_empr:txt_cod_empr,
							cmb_unidadsunat:cmb_unidadsunat,
							cmb_nombreunidadsunat:cmb_nombreunidadsunat,
							txt_nombreequivalente:txt_nombreequivalente,
							txt_codtipoconfig:txt_codtipoconfig						
						},
						beforeSend:function()
						{

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
					var txt_cod_empr=$.trim($('#txt_cod_empr').val());
					var txt_confunid=$.trim($('#txt_confunid').val());
					var txt_conffirma=$.trim($('#txt_conffirma').val());

					if(txt_confunid==1)//UNIDADES DE SUNAT
					{
						$('#txt_codtipoconfig').val(txt_confunid);											
						$('#div_tipoconfiguracion').empty().append('UNIDADES DE SUNAT');
						
						ncsistema.Listar_Unidades(txt_cod_empr,txt_confunid);
						
						return false;
					}
					else if(txt_conffirma==2)//UNIDADES PROPIAS
					{
						$('#txt_codtipoconfig').val(txt_confunid);
						$('#div_tipoconfiguracion').empty().append('UNIDADES COMERCIALES');	
						ncsistema.Listar_Unidades(txt_cod_empr,txt_confunid);										
						return false;
					}			
					/*
					var txt_cod_empr=$.trim($('#txt_cod_empr').val());					

					$.ajax({
						url:'<?php echo base_url()?>empresa/Listar_Empresa',
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
								$.each(result.data,function(key,rs)
								{
									if (rs.cod_empr==txt_cod_empr)
									{	
										if(rs.tipo_confunid==1)//UNIDADES DE SUNAT
										{
											$('#txt_codtipoconfig').val(rs.tipo_confunid);											
											$('#div_tipoconfiguracion').empty().append('UNIDADES DE SUNAT');
											
											ncsistema.Listar_Unidades(txt_cod_empr,rs.tipo_confunid);
											
											return false;
										}
										else if(rs.tipo_conffirma==2)//UNIDADES PROPIAS
										{
											$('#txt_codtipoconfig').val(rs.tipo_confunid);
											$('#div_tipoconfiguracion').empty().append('UNIDADES COMERCIALES');	
											ncsistema.Listar_Unidades(txt_cod_empr,rs.tipo_confunid);										
											return false;
										}										
									}				
								});	
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								Listar_Unidades(cmb_emisorunidad,0);
								$('#div_tipoconfiguracion').empty().append('Ninguno');
							}
						}
					});	*/
				},				
				
				Listar_Unidades:function(txt_cod_empr,txt_codtipoconfig)
				{
					$.ajax({
						url:'<?php echo base_url()?>unidades/Listar_Unidades',
						type: 'post',
						dataType: 'json',
						data:
						{
							txt_cod_empr:txt_cod_empr,
							txt_codtipoconfig:txt_codtipoconfig
							
						},
						beforeSend:function()
						{
						},
						success:function(result)
						{
							if(result.status==1)
							{
								ncsistema.Listar_ConfigurationTabla(result.data,txt_codtipoconfig);
								
							}
							else if (result.status==1000)
							{
								document.location.href= '<?php echo base_url()?>usuario';
								return;
							}
							else
							{
								ncsistema.Listar_ConfigurationTabla("",0);
							}
						}
					});	
				},
				Listar_ConfigurationTabla:function(data,txt_codtipoconfig)
				{	
					$('#div_ListadoEmpresa').empty().append('');

					newHtml='';
					
					if (txt_codtipoconfig==1)//SUNAT
					{
						newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
						newHtml+='<thead>';
						newHtml+='<tr>';						
							newHtml+='<th style="width:3%">Nro.</td>';	
							newHtml+='<th style="width:10%">Tipo Config.</td>';					
							newHtml+='<th style="width:10%">Cod.Unid.Sunat</td>';
							newHtml+='<th style="width:25%">Nomb.Unid.Sunat</td>';
							newHtml+='<th style="width:25%">Unidad Equivalente</td>';		
							newHtml+='<th style="width:25%">Eliminar</td>';						
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
								newHtml+='<td style="text-align:left">'+rs.nomb_tipo_confunid+'</td>';					
								newHtml+='<td style="text-align:left">'+rs.cod_unidmedsunat+'</td>';
								newHtml+='<td style="text-align:left">'+rs.nomb_unidmedsunat+'</td>';
								newHtml+='<td style="text-align:left">'+rs.nomb_unidmedempr+'</td>';	
								newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Unidades('+rs.cod_unimedeq+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';						
							newHtml+='</tr>';						
						});	
						newHtml+='</tbody>';
						newHtml+='</table>';
					}
					else  //COMERCIAL
					{
						newHtml+='<table width="100%"  cellpadding="0" cellspacing="0" class="display" id="Tab_ListaEmpresa">';
						newHtml+='<thead>';
						newHtml+='<tr>';						
							newHtml+='<th style="width:3%">Nro.</td>';	
							newHtml+='<th style="width:10%">Tipo Config.</td>';		
							newHtml+='<th style="width:10%">Cod.Unid.Sunat</td>';
							newHtml+='<th style="width:25%">Nomb.Unid.Sunat</td>';
							newHtml+='<th style="width:25%">Unidad Comercial</td>';	
							newHtml+='<th style="width:25%">Eliminar</td>';	
														
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
								newHtml+='<td style="text-align:left">'+rs.nomb_tipo_confunid+'</td>';	
								newHtml+='<td style="text-align:left">'+rs.nomb_unidmedempr+'</td>';					
								newHtml+='<td style="text-align:left">'+rs.cod_unidmedsunat+'</td>';
								newHtml+='<td style="text-align:left">'+rs.nomb_unidmedsunat+'</td>';
								newHtml+='<td style="text-align:center"><a href="javascript:Eliminar_Unidades('+rs.cod_unimedeq+')" ><img align="center" src="<?php echo base_url();?>application/helpers/image/ico/nceliminar.png" title="Eliminar" width="15"  height="15" border="0" ></a></td>';																
							newHtml+='</tr>';						
						});	
						newHtml+='</tbody>';
						newHtml+='</table>';

					}
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
				$('#cmb_unidadsunat').val('0');				
				$('#txt_nombreequivalente').val('');
			}
			
			function Eliminar_Unidades(cod_unimedeq)
			{
				var txt_cod_empr=$.trim($('#txt_cod_empr').val());
				var txt_codtipoconfig=$.trim($('#txt_codtipoconfig').val());
			
				if(confirm("¿ Esta Seguro de Eliminar la unidad ?"))
				{
					$.ajax
					({
						url:'<?php echo base_url()?>unidades/Eliminar_Unidades',type:'post',dataType:'json',
						data:
						{
							cod_unimedeq:cod_unimedeq,
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/information.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">La eliminaci&oacute;n de la unidad se realiz&oacute; con &eacute;xito</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
								ncsistema.Listar_Unidades(txt_cod_empr,txt_codtipoconfig);
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
								$('#div_MensajeValidacionEmpresa').empty().append('<div style="width:10%;float:left;text-align:right"><img src="<?php echo base_url();?>application/helpers/image/ico/error.png"/></div><div style="margin-left:5px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;font-size:12px;padding-top:3px; width:80%;float:left;text-align:left">Error al eliminar el correlativo</div>');
								setTimeout(function(){ $("#div_MensajeValidacionEmpresa").fadeOut(1500);},3000);
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
		<div id="Div_HeadSistema"><?php $this->load->view('inicio/head',$Listar_UsuarioAccesos,$Listar_Empresas,$pagina_ver); ?></div>
		
		<div id="tabs" style="width:99.7%;float:left;text-align:center;margin-top:5px">
			<ul>
				<li><a href="#tabs-1">REGISTRAR UNIDADES</a></li>
			</ul>
			<div id="tabs-1" style="width:95%;float:left">
			
			<div id="div_datosempresa" style="width:100%;border:solid 1px;float:left;margin-top:10px;border: 1px solid #a6c9e2;border-radius:5px;">
				<input type="hidden" id="txt_codtipoconfig" value="0" />
				<input type="hidden" id="txt_cod_empr"  value="<?php echo $Cod_empr;?>" />
				<input type="hidden" id="txt_confunid"  value="<?php echo $Tipo_confunid;?>" />
				<input type="hidden" id="txt_conffirma"  value="<?php echo $Tipo_conffirma;?>" />
				<table width="40%" style="border-collapse:separate; border-spacing:1px 1px;" cellpadding="3" class="tablaFormulario">
					<tr>
						<td style="text-align:right;width:30%"><label class="columna">Emisor:</label></td>
						<td style="text-align:left;;width:70%" >
							<input style="width:98%" type="text" id="txt_RazonSocialEmpresa" value="<?php echo trim(utf8_encode($Razon_Social));?>"
									disabled="disabled" />
						</td>
					</tr>	
					<tr>
						<td style="text-align:right;"><label class="columna">Tipo de Conf.:</label></td>
						<td style="text-align:left;" >							
							<div id="div_tipoconfiguracion"></div>
						</td>
					</tr>	
					
					<tr>
						<td style="text-align:right;width:30%"><label class="columna">Unidades Sunat:</label></td>
						<td style="text-align:left;;width:70%" >
							<select id="cmb_unidadsunat" style="width:99%;height:22px" >
								<option value="0">[SELECCIONAR]</option>								
								<?php foreach ( $Listar_UnidadSunat as $v):	?>
									<option value="<?php echo trim($v['co_item_tabla']); ?>"><?php echo trim(strtoupper(utf8_decode($v['no_corto']))).' - '.trim($v['co_item_tabla']);?> </option>
								<?php  endforeach; ?>
							</select>
						</td>
					</tr>	

					<tr id="row6">
						<td style="text-align:right;"><label class="columna">Nombre Equivante:</label></td>
						<td style="text-align:left;" >
							<input style="width:98%" type="text" id="txt_nombreequivalente" maxlength="50" /></td>
					</tr>
					<tr>
						<td align="right"><label class="columna"></label></td>
						<td colspan="3" align="center">
							<div style="width:100%;height:20px;border:solid 0px;margin-left:4px;margin-right:20px;margin-top:0px;text-align:center;float:left">
								<div id="div_MensajeValidacionEmpresa" style="width:100%;float:left;font-size:9px; color:#FF0000"></div>
							</div>
						</td>
					</tr>
						
					<tr >
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