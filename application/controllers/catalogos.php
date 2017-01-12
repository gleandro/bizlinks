<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Catalogos_model');
		$this->load->model('Usuarioinicio_model');
	}
	
    public function index()
    {
			
    }
	public function Listar_Departamentos()
	{
		$arr=NULL;
		$CantProd=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
        $consulta =$this->Catalogos_model->Listar_Departamentos();
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):			
				$arr[$key]['co_departamento'] = trim($v['co_departamento']);
				$arr[$key]['de_departamento'] =trim($v['de_departamento']);

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
	public function Listar_Provincias()
	{
		$arr=NULL;
		$CantProd=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_depa=trim($this->input->post('cod_depart'));
        $consulta =$this->Catalogos_model->Listar_Provincias($prm_cod_depa);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):			
				$arr[$key]['co_provincia'] = trim($v['co_provincia']);
				$arr[$key]['de_provincia'] =trim($v['de_provincia']);

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
	
	public function Listar_Distritos()
	{
		$arr=NULL;
		$CantProd=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_depa=trim($this->input->post('cod_depart'));
		$prm_cod_provin=trim($this->input->post('cod_provincia'));
		
		//print_r($prm_cod_depa);
		//print_r(', ASAS');
		//print_r($prm_cod_provin);
		
        $consulta =$this->Catalogos_model->Listar_Distritos($prm_cod_depa,$prm_cod_provin);
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):			
				$arr[$key]['co_distrito'] = trim($v['co_distrito']);
				$arr[$key]['de_distrito'] =trim($v['de_distrito']);

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
	
	public function Listar_SeriesDocumentos()
	{
		$arr=NULL;

		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_usu=$this->Usuarioinicio_model->Get_Cod_Usu();
		$prm_cod_empr=$this->Usuarioinicio_model->Get_Cod_Empr();		
		$prm_tip_doc=trim($this->input->post('cod_tipodocumento'));
		$prm_tipo_confserie=$this->Usuarioinicio_model->Get_Tipo_ConfSerie();	
		
		$prm_tipo_usuario=$this->Usuarioinicio_model->Get_Tip_Usu();
		//print_r($prm_tipo_confserie);
		
		if ($prm_tipo_usuario==1)//administrador
		{
			$consulta =$this->Catalogos_model->Listar_SeriesDocumentos($prm_tip_doc,$prm_cod_empr);
		}
		else
		{
		
			if ($prm_tipo_confserie==1)//POR EMPRESA
			{
				$consulta =$this->Catalogos_model->Listar_SeriesDocumentos($prm_tip_doc,$prm_cod_empr);
			}
			else //POR USUARIO
			{
			
				$consulta =$this->Catalogos_model->Listar_SeriesDocumentosUsuario($prm_tip_doc,$prm_cod_empr,$prm_cod_usu);
			}
		}
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			$cod_tipconfig=0;
			
			$cod_tipconfig=$consulta[0]['tip_conf'];

			foreach($consulta as $key=>$v):
				$arr[$key]['tip_conf'] =trim($v['tip_conf']); 
				$arr[$key]['cod_usu'] =  trim($v['cod_usu']);
				$arr[$key]['ser_doc'] =  trim($v['ser_doc']);
				$arr[$key]['num_doc'] =  str_pad(trim($v['num_doc']),8, "0", STR_PAD_LEFT); 
				$arr[$key]['letra_inicio'] =  substr(trim($v['ser_doc']),0,1);
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
	
	
	public function Tipo_Afectacion()
	{
		$arr=NULL;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_cod_tipoconsulta=trim($this->input->post('cod_tipoconsulta'));
		$consulta =$this->Catalogos_model->Tipo_Afectacion($prm_cod_tipoconsulta);
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):
				$arr[$key]['co_item_tabla'] =trim($v['co_item_tabla']); 
				$arr[$key]['no_largo'] =  trim($v['no_largo']);

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
	
	
	public function Tipo_NotaCredito()
	{
		$arr=NULL;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$consulta =$this->Catalogos_model->Tipo_NotaCredito();
		
		if(!empty($consulta))
		{
			foreach($consulta as $key=>$v):
				$arr[$key]['codigo'] =trim($v['codigo']); 
				$arr[$key]['nombre'] =  utf8_encode(strtoupper(trim($v['nombre'])));
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
	
	public function Tipo_NotaDebito()
	{
		$arr=NULL;
		$result['status']=0;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$consulta =$this->Catalogos_model->Tipo_NotaDebito();
		
		if(!empty($consulta))
		{
			foreach($consulta as $key=>$v):
				$arr[$key]['codigo'] =trim($v['codigo']); 
				$arr[$key]['nombre'] =  utf8_encode(strtoupper(trim($v['nombre'])));
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
	
	public function Listar_EstadoDocumentoSunat()
	{
		$arr=NULL;
		if(!$this->Usuarioinicio_model->SessionExiste())
		{
			$result['status']=1000;
			echo json_encode($result);
			exit;
		}
		$prm_tipodocumento=trim($this->input->post('cod_tipodocumento'));
		
		$Listar_Parametros=$this->Catalogos_model->Listar_Parametros();
		//Inicio Requerimiento 3 (NoHouse-InHouse)
			if(!empty($Listar_Parametros))//SI NO ES NULO O VACIO
			{
				$prm['Valor_Inhouse']=$Listar_Parametros[0]['is_inhouse'];
			}
			else{
				$prm['Valor_Inhouse']=0;
			}
			if ($prm['Valor_Inhouse']==0)//NO InHouse
			{
				$consulta =$this->Catalogos_model->Listar_EstadoDocumentoSunat_NoIn($prm_tipodocumento);
			}
			else{//SI InHouse
				$consulta =$this->Catalogos_model->Listar_EstadoDocumentoSunat($prm_tipodocumento);
			}
		//Fin Requerimiento
		
		if(!empty($consulta))//SI NO ES NULO O VACIO
		{
			foreach($consulta as $key=>$v):			
				$arr[$key]['co_item_tabla'] = trim($v['co_item_tabla']);
				$arr[$key]['no_corto'] =strtoupper($v['no_corto']);
				$arr[$key]['no_largo'] =strtoupper($v['no_largo']);

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

	
}



