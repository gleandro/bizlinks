<?php
@session_start();
class Usuario_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}	

	function Guardar_UsuarioAdmin($prm_correo)
	{
		$result['result']=0;
		$this->load->database('ncserver',TRUE);		
		$cod_alert='';
		$num_aleat1= rand(1,1000);
		$num_aleat2= rand(1,1000);		
		$fecha=date('d/m/Y').''.time('H:M:S');
		$fecha=str_replace("/","",$fecha);
		$fecha=str_replace(":","",$fecha);				
		$cod_alert=$num_aleat1.$fecha.$num_aleat2;		
		$consulta = $this->db->query("select count(cod_usuadm) cantidad from sgr_usuarioadmin where email_usuadm='".$prm_correo."' and est_reg=1;");		
		$documento=$consulta->result_array();
		//$this->db->select('title, content, date');
		//$query = $this->db->get('mytable');
		if ($documento[0]['cantidad']==0) //num_doc
		{
			$this->db->query("insert into sgr_usuarioadmin(email_usuadm,cod_valaleatorio,est_activ) values('".$prm_correo."','".$cod_alert."',1);");
			$consulta = $this->db->query("select cod_usuadm from sgr_usuarioadmin where email_usuadm='".$prm_correo."' and est_reg=1;");
			$documento=$consulta->result_array();
			$result['result']=$documento[0]['cod_usuadm'];			
		}		
		return $result;
	}
	
	function Guardar_Usuario($prm_cod_usuadm,$prm_cod_tipusu,$prm_login_usu,$prm_pass_usu,$prm_nom_usu,$prm_apell_usu,$prm_cod_usupad,$prm_email_usu)
	{
		$result['result']=0;
		$this->load->database('ncserver',TRUE);
		$this->db->query("insert into sgr_usuario(cod_usuadm,cod_tipusu,pass_usu,nom_usu,apell_usu ,usu_reg,email_usu)
		values('".$prm_cod_usuadm."','".$prm_cod_tipusu."','".$prm_pass_usu."','".$prm_nom_usu."',
			'".$prm_apell_usu."','".$prm_cod_usupad."','".$prm_email_usu."') ;");
		$result['result']=1;

		return $result;	
	}

	function Modificar_Usuario($prm_cod_usu,$prm_nom_usu,$prm_apell_usu,$prm_email_usu,$prm_pass_usu)
	{
		$result['result']=0;
		$this->load->database('ncserver',TRUE);
		
		$sql="update sgr_usuario set
		  nom_usu='".$prm_nom_usu."',
		  apell_usu='".$prm_apell_usu."',
		  email_usu='".$prm_email_usu."' ";
		
		if ($prm_pass_usu!='')
		{  
			$sql=$sql." ,pass_usu='".$prm_pass_usu."' ";
		}		
		$sql=$sql." where cod_usu='".$prm_cod_usu."';";
		
		$consulta = $this->db->query($sql);
		$result['result']=1;
		return $result;	
	}
	
	
	function Modificar_PasswordUsuario($prm_cod_usu,$prm_pass_usu)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;		
		$consulta = $this->db->query("
		update sgr_usuario
		set
			pass_usu='".$prm_pass_usu."'
		where cod_usu='".$prm_cod_usu."';");
		$result['result']=1;
		return $result;		
	}

	function Eliminar_Usuario($prm_cod_usu,$prm_cod_usuelim)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;
		$query="update sgr_usuario set est_reg=0 where cod_usu='".$prm_cod_usu."';";
		$consulta = $this->db->query($query);
		//$consulta = $this->db->query("update sgr_usuario set est_reg=0 where cod_usu='".$prm_cod_usu."';");
		
		$result['result']=1;
		return $result;//$consulta->result_array();	
	}

	function Validar_Usuario($prm_login_usu,$prm_pass_usu)
	{
		$this->load->database('ncserver',TRUE);
		$query="select a.cod_usuadm,a.email_usuadm,b.cod_usu,b.email_usu login_usu,b.nom_usu,
			b.apell_usu,b.email_usu,b.cod_tipusu,a.caden_conect
		from sgr_usuarioadmin a
			inner join sgr_usuario b on a.cod_usuadm=b.cod_usuadm
		where upper(b.email_usu)='".strtoupper($prm_login_usu)."'
		and b.pass_usu='".$prm_pass_usu."'
		and a.est_reg=1
		and b.est_reg=1
		and a.est_activ=1 ;";
		
		//print_r($query);
		//return;	
		$consulta = $this->db->query($query);
		return $consulta->result_array();	
	}
	
	function buscar_systemlicencia($prm_cod_usuadm)
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select cod_sistlic,nom_tiplic,dias from sgr_buscar_systemlicencia('".$prm_cod_usuadm."')
		as(cod_sistlic integer,nom_tiplic character varying,dias integer);");
		return $consulta->result_array();	
	}

	
	function Buscar_UsuarioValidarExistencia($prm_opcion,$prm_email_usu)
	{
		$result['result']=0;
		$this->load->database('ncserver',TRUE);
		if ($prm_opcion==1)  /*Registra*/
		{		
			$consulta = $this->db->query("select count(cod_usu) cantidad 
					from sgr_usuario where email_usu = '".$prm_email_usu."' and est_reg=1;");		
			$resultado=$consulta->result_array();
			if(!empty($resultado))//SI NO ES NULO O VACIO
			{
				if ($resultado[0]['cantidad']>0) //num_doc
				{
					$result['result']=3;			
				}
				else
				{
					$result['result']=1;
				}
			}
		}
		else /*Modifica*/
		{
			$result['result']=50;
		}	
		return $result;	
	}
	
	function Listar_UsuarioDatos($prm_cod_usuadm)
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select cod_usu,cod_usuadm,nom_usu,apell_usu,est_reg,email_usu,cod_tipusu
		from sgr_usuario where est_reg=1 and cod_usuadm='".$prm_cod_usuadm."';");
		return $consulta->result_array();	
	}	
	
	function Listar_UsuarioAdministrador($prm_cod_usuadm,$cod_aleatorio)
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select cod_usuadm,email_usuadm from sgr_usuarioadmin 
			where cod_usuadm=".$prm_cod_usuadm." and est_activ=0 and cod_valaleatorio='".$cod_aleatorio."';");
		return $consulta->result_array();
	}
	
	function Activar_Usuario($prm_cod_usuadm,$prm_correo)
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("update sgr_usuarioadmin set est_activ=1 where cod_usuadm=".$prm_cod_usuadm." and email_usuadm='".$prm_correo."';;");
		return true;
	}	
	
	function Listar_Usuario($cod_usuadm)
	{		
		$this->load->database('ncserver',TRUE);
				$consulta = $this->db->query("
				select 
					cod_usu,
					((case cod_tipusu when 1 then 'Administrador' else 'Invitado' end) ) tipousuario,
					cod_tipusu,
					cod_usuadm,
					nom_usu,
					apell_usu,
					est_reg,
					email_usu
				from sgr_usuario where est_reg=1 and cod_usuadm=".$cod_usuadm.";");		
		return $consulta->result_array();
    }	
		
	function Listar_Roles()
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select cod_perfil,upper(nom_perfil) nom_perfil from sgr_perfil 
			where est_reg=1;");
		return $consulta->result_array();
	}	
	
	
	function Listar_UsuarioxEmpresa($cod_empr)
	{		
		$this->load->database('ncserver',TRUE);
				$consulta = $this->db->query("
				select 
					a.cod_usu,
					a.nom_usu,
					a.apell_usu
				from sgr_usuario a 
					inner join sgr_usuarioacceso b on a.cod_usu=b.cod_usu 
				where a.est_reg=1 
					and b.est_reg=1 
					and b.cod_rol=1 
					and b.cod_empr=".$cod_empr."
				group by a.cod_usu,	a.nom_usu,a.apell_usu;"
			);		
		return $consulta->result_array();
    }
	
	
}