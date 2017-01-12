<?php
@session_start();
class Usuarioinicio_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	
	/*DATOS INCINIALES DEL USUARIO*/
	function SessionExiste() 
	{
        if(!empty($_SESSION['SES_InicioSystem']))
		{	
		
			//print_r($_SERVER['REMOTE_ADDR']);
				
			if(!empty($_SESSION['SES_MarcoTrabajo']))
			{
				$val1=$_SESSION['SES_MarcoTrabajo'][0]['fecha_session'];
				$val2 = date('Y-m-d h:i:s');  //strtotime('now');//'2016-10-18 00:38:09.940';
				
				$datetime1 = new DateTime($val1);
				$datetime2 = new DateTime($val2);
				
				$diferencia=($datetime1->diff($datetime2));
				
				$totalminutos=($diferencia->d*24*60)+($diferencia->h*60)+($diferencia->i);
				//print_r($totalminutos);
				//echo($totalminutos);
				
				if ($totalminutos>30)//30
				{
					if (!empty($_SESSION['SES_InicioSystem'])) 
					{
						$_SESSION['SES_InicioSystem']=NULL;
					}
					if (!empty($_SESSION['SES_MarcoTrabajo'])) 
					{
						$_SESSION['SES_MarcoTrabajo']=NULL;
					}
					return false;
				}
				else
				{
					$_SESSION['SES_MarcoTrabajo'][0]['fecha_session']=date('Y-m-d h:i:s');
				}
				
			}

            return true;
        }
        return false;
    }	
	function Get_Cod_UsuAdm(){
        $arr=$_SESSION['SES_InicioSystem'];
        return $arr[0]['cod_usuadm'];
    }
	
	function Get_Email_UsuAdm()
	{
        $arr=$_SESSION['SES_InicioSystem'];
        return $arr[0]['email_usuadm'];
    }
    function Get_Cod_Usu(){
        $arr=$_SESSION['SES_InicioSystem'];
        return $arr[0]['cod_usu'];
    }
    
    function Get_Login_Usu(){
        $arr=$_SESSION['SES_InicioSystem'];
        return $arr[0]['login_usu'];
    }
    
    function Get_Nom_Usu(){
        $arr=$_SESSION['SES_InicioSystem'];
        return $arr[0]['nom_usu'];
    }
    
    function Get_Apell_Usu(){
        $arr=$_SESSION['SES_InicioSystem'];
        return $arr[0]['apell_usu'];
    }
	
	function Get_Email_Usu(){
        $arr=$_SESSION['SES_InicioSystem'];
        return $arr[0]['email_usu'];
    }

	function Get_UsuarioLogin(){
        $arr=$_SESSION['SES_InicioSystem'];
        return $arr[0]['nom_usu'].' '.$arr[0]['apell_usu'].'  ['.$arr[0]['login_usu'].' ]';
    }
	
	function Get_Tip_Usu(){
        $arr=$_SESSION['SES_InicioSystem'];
        return $arr[0]['cod_tipusu'];
    }

	function Get_Caden_Conect(){
        $arr=$_SESSION['SES_InicioSystem'];
        return $arr[0]['caden_conect'];
    }
	
	function MarcoTrabajoExiste() 
	{
        if(!empty($_SESSION['SES_MarcoTrabajo'])){
            return true;
        }
        return false;
    }
	
	function Get_Cod_Empr() 
	{
        $arr=$_SESSION['SES_MarcoTrabajo'];
        return $arr[0]['cod_empr'];
    }
	
	function Get_Cod_TipUsu() 
	{
        $arr=$_SESSION['SES_MarcoTrabajo'];
        return $arr[0]['cod_tipusu'];
    }
	
	function Get_Tipo_ConfSerie() 
	{
        $arr=$_SESSION['SES_MarcoTrabajo'];
        return $arr[0]['tipo_confserie'];
    }
	
	function Get_Tipo_ConfUnidad() 
	{
        $arr=$_SESSION['SES_MarcoTrabajo'];
        return $arr[0]['tipo_confunid'];
    }
	
	function Get_Valor_IGV() 
	{
        $arr=$_SESSION['SES_MarcoTrabajo'];
        return $arr[0]['porc_igv'];
    }
	
	function Get_Conf_ValorPrecio() 
	{
        $arr=$_SESSION['SES_MarcoTrabajo'];
        return $arr[0]['conf_venta'];
    }
	
	function Get_Valor_OtrosCargos() 
	{
        $arr=$_SESSION['SES_MarcoTrabajo'];
        return $arr[0]['porc_otroscargos'];
    }

	/*DATOS GENERALES*/
	 function Get_FechaDia()
	 {
       
        return date("d/m/Y"); //date("d-m-Y H:i:s");
    }
}