<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Empresa_model');
		$this->load->model('Certificado_model');
		$this->load->model('Usuarioinicio_model');
		$this->load->model('Menu_model');
		$this->load->model('Catalogos_model');
	}
	
    public function index()
    {
		//echo($_SERVER['REMOTE_ADDR'])
		//$_SERVER['REMOTE_ADDR']=='127.0.0.1'
		if ($_SERVER['REMOTE_ADDR']=='127.0.0.1')
		{
			$this->load->view('configuracion/configuracion');
		}
    }
	
	public function Cambiar_Configuracion()
	{
		$result['status']=0;
		
		$arr=NULL;

		if ($_SERVER['REMOTE_ADDR']=='127.0.0.1')
		{
			$ruta_formato='application/config/database.php';		
			//$ruta_formato='download/database.php';
			
			$prm_hostname=trim($this->input->post('txt_hostname'));
			$prm_username=trim($this->input->post('txt_username'));
			$prm_password=trim($this->input->post('txt_password'));
			$prm_database=trim($this->input->post('txt_database'));
			$prm_driver=trim($this->input->post('txt_driver'));
			$prm_port=trim($this->input->post('txt_port'));
				
			$arreglo[]=NULL;
			
	
			$F=fopen($ruta_formato,'r');
			while (!feof($F)) 
			{
				$cont=0;
				$fila=fgets($F,4096);
				$buscar="]['hostname']";
				$posicion = strpos($fila,$buscar);
				if($posicion !== FALSE)	
				{
					$filatmp='';
					$filatmp=substr($fila,0,$posicion);
					$arreglo[]=$filatmp.$buscar."='".$prm_hostname."';\n";
					$cont++;
				}
				
				$buscar="]['username']";
				$posicion = strpos($fila,$buscar);
				if($posicion !== FALSE)	
				{
					$filatmp='';
					$filatmp=substr($fila,0,$posicion);
					$arreglo[]=$filatmp.$buscar."='".$prm_username."';\n"; // "<br>"
					$cont++;
				}
				
				$buscar="]['password']";
				$posicion = strpos($fila,$buscar);
				if($posicion !== FALSE)	
				{
					$filatmp='';
					$filatmp=substr($fila,0,$posicion);
					$arreglo[]=$filatmp.$buscar."='".$prm_password."';\n";
					$cont++;
				}
				
				$buscar="]['database']";
				$posicion = strpos($fila,$buscar);
				if($posicion !== FALSE)	
				{
					$filatmp='';
					$filatmp=substr($fila,0,$posicion);
					$arreglo[]=$filatmp.$buscar."='".$prm_database."';\n";
					$cont++;
				}
				
				$buscar="]['dbdriver']";
				$posicion = strpos($fila,$buscar);
				if($posicion !== FALSE)	
				{
					$filatmp='';
					$filatmp=substr($fila,0,$posicion);
					$arreglo[]=$filatmp.$buscar."='".$prm_driver."';\n";
					$cont++;
				}
				
				$buscar="]['port']";
				$posicion = strpos($fila,$buscar);
				if($posicion !== FALSE)	
				{
					$filatmp='';
					$filatmp=substr($fila,0,$posicion);
					$arreglo[]=$filatmp.$buscar."='".$prm_port."';\n";
					$cont++;
					//break;
					
				}
				if ($cont==0)
				{
					$arreglo[]=$fila;
				}
				$cont=0;
			}
			fclose($F); 

			//fwrite($ruta_formato,'asafasfas',9);
			$arreglo=implode ('', $arreglo);
			
			 $fp = fopen($ruta_formato, "w");
			 fputs($fp, $arreglo);
			 fclose($fp);
			 $result['status']=1;
		}
		else
		{
			$result['status']=2;
		}

		
		echo json_encode($result);
		
    }
	
 
}



