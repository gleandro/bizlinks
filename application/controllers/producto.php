<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class producto extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('producto_model');
		$this->load->model('Empresa_model');
		$this->load->model('Usuarioinicio_model');
		$this->load->model('Menu_model');
		$this->load->model('Catalogos_model');
	}
	
	public function index()
	{
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			//echo 'SESSION EXPIRADA, VUELVA A INICIAR!'; 
			//exit(0); 
			$this->load->view('usuario/login'); 
		}
		else
		{
			$prm_cod_usuadm=$this->Usuarioinicio_model->Get_Cod_UsuAdm();
			$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
			$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();		
			if(!$this->Usuarioinicio_model->MarcoTrabajoExiste())
			{
				$prm_cod_empr=0;
				$prm_tipo_confunidad=0;
			}
			else
			{
				$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
				$prm_tipo_confunidad=$this->Usuarioinicio_model->Get_Tipo_ConfUnidad();
			}
			$prm['valor_igv']=$this->Usuarioinicio_model->Get_Valor_IGV();
			$prm['valor_otroscargos']=$this->Usuarioinicio_model->Get_Valor_OtrosCargos();
			$prm['Listar_UsuarioAccesos']=$this->Menu_model->Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu);			
			$Listar_Empresa=$this->Empresa_model->Listar_Empresa($this->Usuarioinicio_model->Get_Cod_UsuAdm());
			$prm['Listar_Empresa']=$Listar_Empresa;			
			$prm['Listar_Empresas']=$this->Empresa_model->Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu);
			//$prm['Listar_Departamentos']=$this->Catalogos_model->Listar_Departamentos();
			//$prm['Listar_TipodeDocumento']=$this->Catalogos_model->Listar_TipodeDocumento();
			$prm['Listar_Categoria']=$this->Catalogos_model->Listar_Categoria();			
			
			$prm['Listar_Unidades']=$this->Catalogos_model->Datos_Unidades_Producto($prm_cod_empr,$prm_tipo_confunidad);
			$prm['Config_ValorPrecio']=$_SESSION['SES_MarcoTrabajo'][0]['conf_venta'];
			$prm['pagina_ver']='parametros';
			
			$this->load->view('producto/producto',$prm);
		}		
	}
	
	public function get_Configuracion_ValorPrecio()
	{
		//$prm['valor_igv']=$this->Usuarioinicio_model->Get_Valor_IGV();
		//Por default irá la configuración VALOR VENTA: 0
		$valor=0;
		$valor=$_SESSION['SES_MarcoTrabajo'][0]['conf_venta'];
		echo json_encode($valor);
	}
	
	public function Listar_Productos()
	{
		$arr=NULL;
		$contador=0;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$consulta =$this->producto_model->Listar_Productos($prm_cod_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$contador=$contador+1;
			$arr[$key]['nro_secuencia'] = $contador;				
			$arr[$key]['id'] = trim($v['id']);
			$arr[$key]['cod_producto'] = trim($v['cod_producto']);
			$arr[$key]['nom_corto'] =trim($v['nom_corto']);				
			$arr[$key]['nom_largo'] =trim($v['nom_largo']);
			$arr[$key]['valor_venta'] = number_format(trim($v['valor_venta']),2,'.',','); 
			$arr[$key]['precio_venta'] = number_format(trim($v['precio_venta']),2,'.',','); 
			$arr[$key]['categoria'] =  trim($v['categoria']);
			$arr[$key]['med'] =  trim($v['med']);
			$arr[$key]['cod_unidmedsunat'] =  trim($v['cod_unidmedsunat']);
			endforeach;
		}
		
		if(sizeof($arr)>0)
		{
			$result['status']=1;
			$result['data']=$arr;
		}
		else
		{
			$result['status']=0;
			$result['data']="";
		}
		echo json_encode($result);
	}
	
	public function Listar_ProductoId()
	{
		$arr=NULL;
		$contador=0;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_id=trim($this->input->post('cod_id'));		
		$consulta =$this->producto_model->Listar_ProductoId($prm_cod_id);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			//id, cod_producto, nom_corto, nom_largo, valor_venta
			//id_categoria, categoria, med, cod_unidmedsunat 
			foreach($consulta as $key=>$v):
				$contador=$contador+1;
			$arr[$key]['nro_secuencia'] = $contador;				
			$arr[$key]['id'] = trim($v['id']);
			$arr[$key]['cod_producto'] = trim($v['cod_producto']);
			$arr[$key]['nom_corto'] =trim($v['nom_corto']);				
			$arr[$key]['nom_largo'] =trim($v['nom_largo']);
			$arr[$key]['valor_venta'] = number_format($v['valor_venta'],2,'.',','); 
			$arr[$key]['precio_venta'] = number_format($v['precio_venta'],2,'.',','); 
			$arr[$key]['id_categoria'] =  trim($v['id_categoria']);					
			$arr[$key]['categoria'] =  trim($v['categoria']);
			$arr[$key]['med'] =  trim($v['med']);
			$arr[$key]['cod_unidmedsunat'] =  trim($v['cod_unidmedsunat']);
			endforeach;
		}
		
		if(sizeof($arr)>0){
			$result['status']=1;
			$result['data']=$arr;
		}else{
			$result['status']=0;
			$result['data']="";
		}
		echo json_encode($result);
	}
	
	public function Busqueda_ProductoFiltro()
	{
		$arr=NULL;
		$contador=0;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$prm_codigo=trim($this->input->post('txt_busqueda_codigoproducto'));	
		$prm_descripcion=trim($this->input->post('txt_busqueda_descripcionproducto'));		
		$consulta =$this->producto_model->Busqueda_ProductoFiltro($prm_cod_empr, $prm_codigo, $prm_descripcion);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$contador=$contador+1;
			$arr[$key]['nro_secuencia'] = $contador;				
			$arr[$key]['id'] = trim($v['id']);
			$arr[$key]['cod_producto'] = trim($v['cod_producto']);
			$arr[$key]['nom_corto'] =trim($v['nom_corto']);
			$arr[$key]['nom_largo'] =trim($v['nom_largo']);
			$arr[$key]['valor_venta'] = number_format($v['valor_venta'],2,'.',','); 
			$arr[$key]['valor_venta_real'] = $v['valor_venta']; 
			$arr[$key]['precio_venta'] = number_format($v['precio_venta'],2,'.',','); 
			$arr[$key]['precio_venta_real'] = $v['precio_venta']; 
			$arr[$key]['cod_unidmedsunat'] =  trim($v['cod_unidmedsunat']);
			$arr[$key]['idmed'] =  trim($v['idmed']);
					//a.id, a.cod_producto, a.nom_corto, a.valor_venta, d.cod_unidmedsunat, idmed
			
			endforeach;
		}
		//print_r($arr[$key]['valor_venta']);
		if(sizeof($arr)>0)
		{
			$result['status']=1;
			$result['data']=$arr;
		}
		else
		{
			$result['status']=0;
			$result['data']="";
		}
		echo json_encode($result);
	}
	
	public function Guadar_Producto()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_codigo=trim($this->input->post('txt_codigo'));
		$prm_nombrecorto=trim($this->input->post('txt_nombrecorto'));
		$prm_nombrelargo=trim($this->input->post('txt_nombrelargo'));
		
		$prm_categoria=trim($this->input->post('cmb_categoria'));
		$prm_medida=trim($this->input->post('cmb_medida'));
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		
		$valor=0;
		$valor=$_SESSION['SES_MarcoTrabajo'][0]['conf_venta'];
		$prm_precio=0;
		$prm_valorentero=0;
		
		if ($valor==0)
			$prm_valorentero=trim($this->input->post('txt_valorentero'));
		else
			$prm_precio=trim($this->input->post('txt_precio'));
		
		$resultado =$this->producto_model->Guardar_Producto($prm_codigo,$prm_nombrecorto,$prm_nombrelargo,$prm_valorentero,
			$prm_precio,$prm_categoria,$prm_medida,$prm_cod_empr);
		if ($resultado['result']==0)
		{
			$result['status']=0;		
		}
		else if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		else
		{
			$result['status']=2;	
		}
		echo json_encode($result);
	}
	
	public function Modificar_Producto()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_id=trim($this->input->post('txt_id'));
		$prm_codigo=trim($this->input->post('txt_codigo'));
		$prm_nombrecorto=trim($this->input->post('txt_nombrecorto'));
		$prm_nombrelargo=trim($this->input->post('txt_nombrelargo'));
		$prm_valorentero=trim($this->input->post('txt_valorentero'));
		$prm_precio=trim($this->input->post('txt_precio'));
		$prm_categoria=trim($this->input->post('cmb_categoria'));
		$prm_medida=trim($this->input->post('cmb_medida'));
		//$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		
		$resultado =$this->producto_model->Modificar_Producto($prm_id,$prm_codigo,$prm_nombrecorto,$prm_nombrelargo,$prm_valorentero,$prm_precio,$prm_categoria,$prm_medida);//,$prm_cod_empr);
		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}
	

	
	public function Eliminar_Producto()
	{	
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_id=trim($this->input->post('cod_id'));
		//print_r($prm_id);
		//return;
		$resultado =$this->producto_model->Eliminar_Producto($prm_id);		
		if ($resultado['result']==1)
		{
			$result['status']=1;	
		}
		echo json_encode($result);
	}
	
	public function Valida_Producto()
	{
		$arr=NULL;
		$contador=0;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_codigo=trim($this->input->post('txt_codigo'));
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();
		$consulta =$this->producto_model->Valida_Producto($prm_codigo, $prm_cod_empr);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			//id
			//cod_producto
			//nom_corto
			//nom_largo
			//valor_venta
			//id_categoria
			//categoria
			//med
			//cod_unidmedsunat 
			foreach($consulta as $key=>$v):
				$contador=$contador+1;
			$arr[$key]['nro_secuencia'] = $contador;				
			$arr[$key]['id'] = trim($v['id']);
			$arr[$key]['cod_producto'] = trim($v['cod_producto']);
			$arr[$key]['nom_corto'] =trim($v['nom_corto']);				
			$arr[$key]['nom_largo'] =trim($v['nom_largo']);
			$arr[$key]['valor_venta'] = trim($v['valor_venta']); 
			$arr[$key]['precio_venta'] = trim($v['precio_venta']); 
			$arr[$key]['id_categoria'] =  trim($v['id_categoria']);					
			$arr[$key]['categoria'] =  trim($v['categoria']);
			$arr[$key]['med'] =  trim($v['med']);
			$arr[$key]['cod_unidmedsunat'] =  trim($v['cod_unidmedsunat']);
			$arr[$key]['est_reg'] =  trim($v['est_reg']);
			endforeach;
		}
		
		//print_r($arr);
		
		if(sizeof($arr)>0)
		{
			$result['status']=1;
			$result['data']=$arr;
		}
		else
		{
			$result['status']=0;
			$result['data']="";
		}
		echo json_encode($result);
	}

}



