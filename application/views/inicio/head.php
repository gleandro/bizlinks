<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/site.css"/>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/css/tabla_documento.css"/>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>application/helpers/jquery/jquery-ui-1.11.2.custom/jquery-ui.css"/>
<script type="text/javascript"> 			
	Menuinicio=
	{
		
	}
	function Cambiar_MarcodeTrabajoEmpresa()
	{
		var Cmb_EmpresaSeleccion=$.trim($('#Cmb_EmpresaSeleccion').val());
		var txt_rolseleccion=$.trim($('#txt_rolseleccion').val());	
		var txt_paginaver=$.trim($('#txt_paginaver').val());
		if (Cmb_EmpresaSeleccion==0)
		{
			return;
		}
		$('#div_tipousuarioselect').empty().append('');
		$.ajax
		({
			url:'<?php echo base_url()?>usuario/Cambiar_MarcodeTrabajoEmpresa',type:'post',dataType:'json',
			data:
			{
				cod_empr:Cmb_EmpresaSeleccion
			},
			beforeSend:function()
			{
			},
			success:function(result)
			{
			
				document.location.href= '<?php echo base_url()?>principal';
				/*
				if(parseInt(result.status)==1)
				{
					newHtml='';		
					newHtml+='<select id="Cmb_RolSeleccion" style="width:98%;height:25px" onChange="javascrip:Cambiar_MarcodeTrabajoTipo()">';
					newHtml+='<option value="0" selected="selected">[SELECCIONAR]</option>';
						
					$.each(result.data,function(key,rs)
					{
							newHtml+='<option value="'+rs.cod_tipusu+'">'+rs.nomb_tipusu+'</option>';							
					});					
					newHtml+='</select>';
					$('#div_tipousuarioselect').empty().append(newHtml);
					$('#Cmb_RolSeleccion').val(result.cod_rolseleccion);
				}
				else
				{
					newHtml='';		
					newHtml+='<select id="Cmb_RolSeleccion" style="width:98%;height:25px" onChange="javascrip:Cambiar_MarcodeTrabajoTipo()">';
					newHtml+='<option value="0" selected="selected">[SELECCIONAR]</option>';
					newHtml+='</select>';
					$('#div_tipousuarioselect').empty().append(newHtml);
				}*/

			}		
		});
		
	}
	
	function Cambiar_MarcodeTrabajoTipo()
	{
		var Cmb_EmpresaSeleccion=$.trim($('#Cmb_EmpresaSeleccion').val());
		var Cmb_RolSeleccion=$.trim($('#Cmb_RolSeleccion').val());

		if (Cmb_RolSeleccion==0)
		{
			return;
		}
		if (Cmb_EmpresaSeleccion==0)
		{
			return;
		}		
		$.ajax
		({
			url:'<?php echo base_url()?>usuario/Cambiar_MarcodeTrabajo',type:'post',dataType:'json',
			data:
			{
				cod_empr:Cmb_EmpresaSeleccion,
				Cmb_RolSeleccion:Cmb_RolSeleccion					
			},
			beforeSend:function()
			{
			},
			success:function(result)
			{
				if(parseInt(result.status)==1)
				{
					document.location.href= '<?php echo base_url()?>principal';
				}
			}		
		});
	}
	
	
	function Buscar_RolesUsuarioEmpresa()
	{
		var Cmb_EmpresaSeleccion=$.trim($('#Cmb_EmpresaSeleccion').val());
		var txt_rolseleccion=$.trim($('#txt_rolseleccion').val());	
		
		if (txt_rolseleccion==0)
		{
			$('#div_tipousuarioselect').empty().append('');
			$.ajax
			({
				url:'<?php echo base_url()?>usuario/Cambiar_MarcodeTrabajoEmpresa',type:'post',dataType:'json',
				data:
				{
					cod_empr:Cmb_EmpresaSeleccion
				},
				beforeSend:function()
				{
				},
				success:function(result)
				{
					if(parseInt(result.status)==1)
					{
						newHtml='';		
						newHtml+='<select id="Cmb_RolSeleccion" style="width:98%;height:25px" onChange="javascrip:Cambiar_MarcodeTrabajoTipo()">';
						newHtml+='<option value="0" selected="selected">[SELECCIONAR]</option>';
							
						$.each(result.data,function(key,rs)
						{
								newHtml+='<option value="'+rs.cod_tipusu+'">'+rs.nomb_tipusu+'</option>';							
						});					
						newHtml+='</select>';
						$('#div_tipousuarioselect').empty().append(newHtml);
						$('#Cmb_RolSeleccion').val(result.cod_rolseleccion);
					}
					else
					{
						newHtml='';		
						newHtml+='<select id="Cmb_RolSeleccion" style="width:98%;height:25px" onChange="javascrip:Cambiar_MarcodeTrabajoTipo()">';
						newHtml+='<option value="0" selected="selected">[SELECCIONAR]</option>';
						newHtml+='</select>';
						$('#div_tipousuarioselect').empty().append(newHtml);
					}
	
				}		
			});
		}
		else
		{
			if (Cmb_EmpresaSeleccion!=0)
			{
				$('#div_tipousuarioselect').empty().append('');
				$.ajax
				({
					url:'<?php echo base_url()?>usuario/Cambiar_MarcodeTrabajoEmpresaInicio',type:'post',dataType:'json',
					data:
					{
						cod_empr:Cmb_EmpresaSeleccion
					},
					beforeSend:function()
					{
					},
					success:function(result)
					{
	
							if(parseInt(result.status)==1)
							{
								newHtml='';		
								newHtml+='<select id="Cmb_RolSeleccion" style="width:98%;height:25px" onChange="javascrip:Cambiar_MarcodeTrabajoTipo()">';
								newHtml+='<option value="0" selected="selected">[SELECCIONAR]</option>';
									
								$.each(result.data,function(key,rs)
								{
										newHtml+='<option value="'+rs.cod_tipusu+'">'+rs.nomb_tipusu+'</option>';							
								});					
								newHtml+='</select>';
								$('#div_tipousuarioselect').empty().append(newHtml);
								
								$('#Cmb_RolSeleccion').val(result.cod_rolseleccion);
								
								/*
								if (Cmb_EmpresaSeleccion>0)
								{
									Cambiar_MarcodeTrabajoTipo();
								}*/					
								//Cambiar_MarcodeTrabajoTipo();					
							}
							else
							{
								newHtml='';		
								newHtml+='<select id="Cmb_RolSeleccion" style="width:98%;height:25px" onChange="javascrip:Cambiar_MarcodeTrabajoTipo()">';
								newHtml+='<option value="0" selected="selected">[SELECCIONAR]</option>';
								newHtml+='</select>';
								$('#div_tipousuarioselect').empty().append(newHtml);
							}
	
	
					}		
				});
			}
		}
		
	}
	
	
	
</script>

<div id="div_MenuSistema" style="width:100%; border-radius:3px; border: 1px solid #79b7e7; float:left; " >
	<div class="ui-widget-header" >
	<!--ui-widget-header_mainmenu   ui-accordion-header ui-corner-top ui-state-default ui-accordion-icons ui-accordion-header-collapsed ui-corner-all-->
	<ul class="menu" style='color: #ffffff; font-family: "Lucida Sans Unicode","Trebuchet Unicode MS",
		"Lucida Grande",sans-serif; font-size: 13px; font-weight: bold; line-height: 34px;'>			
		<?php 
			$num_nivant=0;
			$niv_1=0;
			$niv_2=0;
			$niv_3=0;
			foreach ( $Listar_UsuarioAccesos as $v):	
				if($v['cod_nivmen']==1)
				{	
					if ($num_nivant==0)
					{
		?>
						<li class="top" class="ui-menu-item">
							<a class="top_link" href="<?php if ($v['url_pag']==''){echo "#";}else{echo base_url().trim($v['url_pag']);} ?>">
								<span class="down"><?php echo trim(utf8_decode($v['nom_men'])); ?></span>
							</a>
					<?php 
					}
					else 
						if ($num_nivant==1)
						  {
					?>
						</li>
							<li class="top">
								<a class="top_link" href="<?php if ($v['url_pag']==''){echo "#";}else{echo base_url().trim($v['url_pag']);} ?>">
									<span class="down"><?php echo trim(utf8_decode($v['nom_men'])); ?></span>
								</a>
					<?php }	
						else 
							if ($num_nivant==2)
							{
					?>
								</ul>
							</li>
						<li class="top"><a class="top_link" href="<?php if ($v['url_pag']==''){echo "#";}else{echo base_url().trim($v['url_pag']);} ?>"><span class="down"><?php echo trim(utf8_decode($v['nom_men'])); ?></span></a>
				<?php }					
					else if ($num_nivant==3)
					{							
						?>
											</ul>
										</li>		
									</ul>
								</li>
							<li class="top"><a class="top_link" href="<?php if ($v['url_pag']==''){echo "#";}else{echo base_url().trim($v['url_pag']);} ?>"><span class="down"><?php echo trim(utf8_decode($v['nom_men'])); ?></span></a>
					<?php }
											
					$niv_2=0;
					$niv_3=0;
				}				
				else if ($v['cod_nivmen']==2)
				{
					$niv_3=0;
					if ($niv_2==0)
					{
						?>
						<ul class="sub">
					<?php } 

					if ($num_nivant==1 or $num_nivant==2 or $num_nivant==3)
					{
						if ($num_nivant==3)
						{
							?>
								</ul>
							</li>
						<?php
						}							
						if ($v['tien_hij']==1)
						{
							?>
							<li><a class="fly" href="<?php echo base_url().trim($v['url_pag']); ?>"><?php echo trim(utf8_decode($v['nom_men'])); ?></a>
						<?php
						}
						else
						{
						?>
							<li><a href="<?php echo base_url().trim($v['url_pag']); ?>"><?php echo trim(utf8_decode($v['nom_men'])); ?></a></li>
						<?php
						} 
					}						
					$niv_2=1;						
				}					
				else if ($v['cod_nivmen']==3)
				{
					if ($niv_3==0)
					{
						?>
						<ul>
					<?php } 

					if ($num_nivant==2 or $num_nivant==3)
					{
						?>
							<li><a href="<?php echo base_url().trim($v['url_pag']); ?>"><?php echo trim(utf8_decode($v['nom_men'])); ?></a></li>
						<?php
					} 						
					$niv_3=1;						
				}					
				$num_nivant=$v['cod_nivmen'];
			endforeach; 
		
			if ($num_nivant==1)
			{
				?>
				</li>
			<?php } 
		
			else if ($num_nivant==2)
			{
				?>
					</ul>
				</li>
			<?php } 
			else if ($num_nivant==3)
			{
				?>
								</ul>
							</li>		
						</ul>
					</li>
			<?php }			
		?>			
	</ul>	
	</div>
</div>


<div id="div_SubMenu1"  style="width:100%; border-radius:3px; float:left; margin-top:2px; border: 1px solid #79b7e7; ">
	<div  class="ui-widget-header-submenu">
		<input type="hidden" id="txt_paginaver" value="<?php echo $pagina_ver; ?>"  />
		<table border="0" width="100%" style='color: #2e6e9e; font-family: "Lucida Sans Unicode","Trebuchet Unicode MS",
		"Lucida Grande",sans-serif; font-weight: bold; line-height: 14px;' >
		  <tbody>
			<tr>
				<td style="width:1%; vertical-align:middle ;">
					<a href="/" >
						<img border="0" src="<?php echo base_url();?>application/helpers/image/ico/home_brown16.ico" width="16" height="16" />
					</a>
				</td>
				<td style="width:4%;text-align:right;font-weight:bold; font-size: 12px; " ><label class="columna">Tipo:</label></td>
				<td style="width:8%;text-align:left; font-size: 12px; ">
					<?php 
					if(!empty($_SESSION['SES_InicioSystem'])) 
					{ 
						if ($_SESSION['SES_InicioSystem'][0]['cod_tipusu']==1)
						{ 
							echo 'Administrador' ;
						} 
						else
						{
							echo 'Invitado' ;
						}    
					} 
					else
					{
						echo "...";
					} ?>
				</td>
			
				<td style="width:5%;text-align:right;font-weight:bold; font-size: 12px; " >Usuario:</td>
				<td style="width:22%;text-align:left; font-size: 12px; "><?php if(!empty($_SESSION['SES_InicioSystem'])) { echo utf8_decode($_SESSION['SES_InicioSystem'][0]['nom_usu']).' '.utf8_decode($_SESSION['SES_InicioSystem'][0]['apell_usu']).'  ['.utf8_decode($_SESSION['SES_InicioSystem'][0]['login_usu']).']'; } else {echo "...";} ?></td>
				
				<td style="width:5%;text-align:right;font-weight:bold; font-size: 12px; " >Empresa:</td>
				<td style="width:23%;text-align:left; font-size: 12px; ">
					<?php 
						if(!empty($_SESSION['SES_MarcoTrabajo'])) 
						{ 
							$cod_empresa=$_SESSION['SES_MarcoTrabajo'][0]['cod_empr'];
						}
						else
						{
							$cod_empresa=0;
						}
					?>
					<select id="Cmb_EmpresaSeleccion" style="width:98%;height:25px" onChange="javascrip:Cambiar_MarcodeTrabajoEmpresa()">
						<?php if ($cod_empresa==0) 
						{
						?>
							<option value="0" selected="selected">[SELECCIONAR]</option>
						<?php 
						}
						else
						{
						?>
							<option value="0">[SELECCIONAR]</option>
						<?php 
						}

						 	foreach ( $Listar_Empresas as $v):	
							if ($cod_empresa==trim($v['cod_empr']))
							{
								?>							
									<option value="<?php echo trim($v['cod_empr']); ?>" selected="selected"><?php echo trim(utf8_decode($v['raz_social']));?> </option>
								<?php  
							}
							else
							{
								?>							
								<option value="<?php echo trim($v['cod_empr']); ?>"><?php echo trim(utf8_decode($v['raz_social']));?> </option>
								<?php  
							}
							endforeach; ?>
					</select>
				</td>
				<td style="width:3%;text-align:right;font-weight:bold; font-size: 12px; " >Roles:</td>
				<td style="width:15%;text-align:left; font-size: 12px; ">	
					<input type="hidden" id="txt_rolseleccion" value="<?php 
						if(!empty($_SESSION['SES_MarcoTrabajo'])) 
						{ 
							echo $_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion'];
						}
						else
						{
							echo '0';
						}
						?>"  
					/>	

					<div id="div_tipousuarioselect">
						<select id="Cmb_RolSeleccion" style="width:98%;height:25px" >
							<option value="0" >[SELECCIONAR]</option>					
						</select>
					</div>
				</td>	
				<!--
				<td style="width:4%;text-align:right;font-weight:bold">Fecha :</td>
				<td style="width:8%;text-align:left"><?php echo date("d/m/Y") ?> </td>
				-->
			</tr>						
		  </tbody>
		</table>
	</div>

</div>	

<script>
	Buscar_RolesUsuarioEmpresa();
</script>