<?php //@session_start();
	
class Empresa_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}

	function Listar_Empresa($prm_cod_usuadm)
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select 
					a.cod_empr,
					a.usu_reg cod_usu,
					a.cod_usuadm,
					a.tip_documento,
					a.ruc_empr,
					a.raz_social,
					a.rep_legal,
					a.pagi_empr,
					a.url_logoempr,
					a.tipo_confserie,		
					a.tipo_confunid,
					a.tipo_conffirma,
					a.cod_pais,
					a.cod_ubigeo,
					a.nom_comercial,
					a.urbaniz_empresa,
					a.direcc_empresa			
		from sgr_empresa a 
				inner join sgr_empresa_er b on a.cod_empr=b.cod_empr
		 where a.cod_usuadm=".$prm_cod_usuadm." and a.est_reg=1 and b.est_reg=1 and b.cod_rol=1;");		
		return $consulta->result_array();
	}
	
	function Listar_EmpresaId($prm_cod_empr)
	{
		$this->load->database('ncserver',TRUE);
		$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();	
		$sql="select 
			cod_empr,
			usu_reg cod_usu,
			cod_usuadm,
			ruc_empr,
			raz_social,
			rep_legal,
			pagi_empr,
			url_logoempr,
			cod_pais,
			cod_ubigeo,
			tip_documento,
			nom_comercial,
			urbaniz_empresa,
			direcc_empresa,
			tipo_confserie,
			(case when cod_pais='PE' then 'PERU' else 'OTROS' end) nomb_pais,
			tipo_confunid,
			tipo_conffirma,
			(select aa.valorentero from sgr_multitabla aa where aa.activo=1 and aa.grupo_nombre='VALOR_IGV' ) valor_igv					
		from sgr_empresa where cod_empr=".$prm_cod_empr." and est_reg=1;";
		$consulta = $this->db->query($sql);
		return $consulta->result_array();
	}
	
	function Listar_EmpresaIdRuc($prm_ruc_empresa)
	{
		$this->load->database('ncserver',TRUE);
		$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();	
		$sql="select 
			cod_empr,
			usu_reg cod_usu,
			cod_usuadm,
			ruc_empr,
			raz_social,
			rep_legal,
			pagi_empr,
			url_logoempr,
			cod_pais,
			cod_ubigeo,
			tip_documento,
			nom_comercial,
			urbaniz_empresa,
			direcc_empresa,
			tipo_confserie,
			(case when cod_pais='PE' then 'PERU' else 'OTROS' end) nomb_pais,
			tipo_confunid,
			tipo_conffirma,
			(select aa.valorentero from sgr_multitabla aa where aa.activo=1 and aa.grupo_nombre='VALOR_IGV' ) valor_igv					
		from sgr_empresa where ruc_empr='".$prm_ruc_empresa."' and est_reg=1 and tip_documento='6';";		
		$consulta = $this->db->query($sql);
		return $consulta->result_array();
	}
	
	
	function Listar_EmpresaDocumento($prm_tip_documento,$prm_ruc_empr)
	{
		$this->load->database('ncserver',TRUE);
		$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();	
		
		$sql="select 
			cod_empr,
			usu_reg cod_usu,
			cod_usuadm,
			ruc_empr,
			raz_social,
			rep_legal,
			pagi_empr,
			url_logoempr,
			cod_pais,
			cod_ubigeo,
			tip_documento,
			nom_comercial,
			urbaniz_empresa,
			direcc_empresa,
			tipo_confserie,
			(case when cod_pais='PE' then 'PERU' else 'OTROS' end) nomb_pais,
			tipo_confunid,
			tipo_conffirma,
			(select aa.valorentero from sgr_multitabla aa where aa.activo=1 and aa.grupo_nombre='VALOR_IGV' ) valor_igv					
		from sgr_empresa where ruc_empr='".$prm_ruc_empr."' and tip_documento='".$prm_tip_documento."' and est_reg=1;";
		
		$consulta = $this->db->query($sql);
		return $consulta->result_array();
	}

	function Listar_EmpresaIdRoles($prm_cod_empr)
	{

		$this->load->database('ncserver',TRUE);
		
		//$prm_cod_rolseleccion=$_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion'];
		//$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();	
		
		$sql="select 
					a.cod_empr,
					a.usu_reg cod_usu,
					a.cod_usuadm,
					a.ruc_empr,
					a.raz_social,
					a.rep_legal,
					a.pagi_empr,
					a.url_logoempr,
					a.cod_pais,
					a.cod_ubigeo,
					a.tip_documento,
					a.nom_comercial,
					a.urbaniz_empresa,
					a.direcc_empresa,
					a.tipo_confserie,
					(case when a.cod_pais='PE' then 'PERU' else 'OTROS' end) nomb_pais,
					a.tipo_confunid,
					a.tipo_conffirma,
					(select aa.valorentero from sgr_multitabla aa where aa.activo=1 and aa.grupo_nombre='VALOR_IGV') valor_igv,
					(select cc.valorentero from sgr_multitabla cc where cc.activo=1 and cc.grupo_nombre='OTROS_CARGOS' and cc.cod_empr=".$prm_cod_empr." ) valor_otroscargos,
					(select bb.valorentero from sgr_multitabla bb where bb.activo=1 and bb.grupo_nombre='CONFIGURACION_VENTA') conf_venta
				from sgr_empresa a 
				where a.cod_empr=".$prm_cod_empr." and a.est_reg=1;";
		
				$consulta = $this->db->query($sql);
		
		return $consulta->result_array();
	}


	function Guardar_Empresa($prm_cod_usu,$prm_cod_usuadm,$prm_ruc_empr,$prm_raz_social,$prm_cod_actiempr,
	$prm_rep_legal,$prm_pagi_empr,$prm_fec_creac,$prm_cod_pais,$prm_cod_ubigeo,$prm_url_logoempr,
	$prm_tip_documento,$prm_nom_comercial,$prm_urbaniz_empresa,$prm_direcc_empresa,$prm_tipo_confserie,$prm_tipo_confunid,$prm_tipo_conffirma)
	{
		$result['result']=0;		

		$this->db_client =$this->load->database('ncserver',TRUE);	
		$this->db_client->trans_begin();
		
		$consulta = $this->db_client->query("select count(cod_empr) cantidad from sgr_empresa 
				where cod_usuadm='".$prm_cod_usuadm."' and ruc_empr='".$prm_ruc_empr."' and est_reg=1 and tip_documento='".$prm_tip_documento."';");		
		
		if ($this->db_client->trans_status() === FALSE)
		{
			$this->db_client->trans_rollback();
			$result['result']=0;
			return $result;
		}
		$resultado=$consulta->result_array();	

		if ($resultado[0]['cantidad']==0) //no existe registrado
		{
			$query="
				insert into sgr_empresa
				(
					usu_reg,
					cod_usuadm,
					ruc_empr,
					raz_social,
					rep_legal,
					pagi_empr,
					fec_creac,
					cod_pais,
					cod_ubigeo,
					url_logoempr,
					tip_documento,
					nom_comercial,
					urbaniz_empresa,
					direcc_empresa,
					tipo_confserie,
					tipo_confunid,
					tipo_conffirma
				)
				values
				(
					".$prm_cod_usu.",
					".$prm_cod_usuadm.",
					'".$prm_ruc_empr."',
					'".$prm_raz_social."',
					'".$prm_rep_legal."',
					'".$prm_pagi_empr."',
					".$prm_fec_creac.",
					'".$prm_cod_pais."',
					'".$prm_cod_ubigeo."',
					'".$prm_url_logoempr."',
					'".$prm_tip_documento."',
					'".$prm_nom_comercial."',
					'".$prm_urbaniz_empresa."',
					'".$prm_direcc_empresa."',
					'".$prm_tipo_confserie."',
					'".$prm_tipo_confunid."',
					'".$prm_tipo_conffirma."'
				 );";
			//print_r($query);
			//return;
			
			$this->db_client->query($query);
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				return $result;
			}		
			//$cod_empresa=$this->db_client->insert_id();
			
			if($this->db_client->dbdriver == 'oci8')
			{
				$cod_empresa=$this->db_client->insert_id_by_table('SGR_EMPRESA');
			}
			else
				$cod_empresa=$this->db_client->insert_id();
			
			//SE REGISTRA SI LA FIRMA ES BIZLINK, SE DEBE REGISTRA EL RUC Y FIRMA LOCAL 0
			if ($prm_tipo_conffirma==1)//FIRMA EN BIZLINK
			{
				$query="insert into bl_configuration(id_emisor,firma_local)
						values('".$prm_tip_documento."-".$prm_ruc_empr."',0);";
				
				//print_r($query);
				
				$this->db_client->query($query);
				if ($this->db_client->trans_status() === FALSE)
				{
					$this->db_client->trans_rollback();
					$result['result']=0;
					return $result;
				}	
			}

			$query="insert into sgr_empresa_er(cod_rol,cod_empr,cod_empr_emisor,usu_reg)
						values(1,'".$cod_empresa."','".$cod_empresa."','".$prm_cod_usu."');";
			$this->db_client->query($query);
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				return $result;
			}	

			$result['result']=1;			
		}
		else
		{
			$result['result']=2;
		}
		$this->db_client->trans_commit();
	
		return $result;
	}
	
	function Modificar_Empresa($prm_cod_empr,$prm_raz_social,$prm_rep_legal,
		$prm_tip_documento,$prm_nom_comercial,$prm_urbaniz_empresa,$prm_direcc_empresa,$prm_cod_pais,$prm_cod_ubigeo,
		$prm_tipo_confserie,$prm_tipo_confunid,$prm_tipo_conffirma,$prm_cod_usu,$prm_ruc_empr)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;
		$consulta = $this->db->query("		
		update sgr_empresa
		set
			raz_social='". $prm_raz_social."',
			rep_legal='".$prm_rep_legal."',
			tip_documento='".$prm_tip_documento."',
			nom_comercial='".$prm_nom_comercial."',
			urbaniz_empresa='".$prm_urbaniz_empresa."',
			direcc_empresa='".$prm_direcc_empresa."',
			cod_pais='".$prm_cod_pais."',
			cod_ubigeo='".$prm_cod_ubigeo."',			
			tipo_confserie='".$prm_tipo_confserie."',
			tipo_confunid='".$prm_tipo_confunid."',
			tipo_conffirma='".$prm_tipo_conffirma."'			
			
		where cod_empr='".$prm_cod_empr."';");
		
		
		$consulta = $this->db->query("select est_reg from sgr_empresa_er
				where cod_rol=1 and cod_empr='".$prm_cod_empr."' and cod_empr_emisor='".$prm_cod_empr."' ;");
		
		$resultado=$consulta->result_array();	
		
		if(!empty($resultado))//SI NO ES NULO O VACIO
		{
			if ($resultado[0]['est_reg']==0) //no existe registrado
			{
				$query="update sgr_empresa_er set est_reg=1 
					where cod_rol=1 
						and cod_empr='".$prm_cod_empr."' 
						and cod_empr_emisor='".$prm_cod_empr."' 
						and est_reg=0;";
				$this->db->query($query);
			}
		}
		else
		{
			$query="insert into sgr_empresa_er(cod_rol,cod_empr,cod_empr_emisor,usu_reg)
						values(1,'".$prm_cod_empr."','".$prm_cod_empr."','".$prm_cod_usu."');";		
			$this->db->query($query);	
						
		}

		if ($prm_tipo_conffirma==1)//FIRMA EN BIZLINK
		{
			//ELIMINAMOS LO Q TIENE EN FIRMA
			$this->db->query("delete from bl_configuration where id_emisor='6-".$prm_ruc_empr."';");
			
			$consulta = $this->db->query("select id_emisor from bl_configuration where id_emisor='6-".$prm_ruc_empr."';");		
			$resultado=$consulta->result_array();			
			if(empty($resultado))//SI ES NULO O VACIO
			{
				$query="insert into bl_configuration(id_emisor,firma_local)
					values('6-".$prm_ruc_empr."',0);";
				$this->db->query($query);
			}
		}
		$_SESSION['SES_MarcoTrabajo'][0]['tipo_confserie']=$prm_tipo_confserie;
		$result['result']=1;
		return $result;
	}

	function Eliminar_Empresa($prm_cod_empr)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;
		$consulta = $this->db->query("update  sgr_empresa_er set est_reg=0 where cod_empr=".$prm_cod_empr." and cod_empr_emisor=".$prm_cod_empr." and cod_rol=1 and est_reg=1;");
		$result['result']=1;
		return $result;
	}
	
	function Listar_EmpresaGrid($prm_cod_usuadm)
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("select 
				a.cod_empr,
				a.ruc_empr,
				a.raz_social,
				a.est_reg,
				a.rep_legal ,
				(case when b.cod_rol=0 then 'NINGUNO'
						 when b.cod_rol=1 then 'EMISOR'
						 when b.cod_rol=2 then 'RECEPTOP' end) tipoempresa
				from sgr_empresa a
					inner join sgr_empresa_er b on a.cod_empr=b.cod_empr and b.est_reg=1
				where a.est_reg=1 and a.cod_usuadm=".$prm_cod_usuadm." and a.est_reg=1 and b.cod_rol=1;");
		return $consulta->result_array();		
    }
	
	function Listar_EmpresaContacto($prm_cod_usuadm,$prm_tip_usu,$prm_cod_usu)
	{
		$this->load->database('ncserver',TRUE);		
		if ($prm_tip_usu==1)//ADMINISTRADOR
		{
			$consulta = $this->db->query("select a.cod_empr, a.raz_social from sgr_empresa a 
  				inner join sgr_empresa_er b on a.cod_empr=b.cod_empr 
			where a.cod_usuadm=".$prm_cod_usuadm." and a.est_reg=1 and b.est_reg=1 and not a.ruc_empr like 'E%'
			group by a.cod_empr, a.raz_social order by 2;");
		}
		else //SI ES INVITADO, SE TIENE Q VER SI ESTA CONF. PARA EMISOR Y RESEPTOR
		{
			$consulta = $this->db->query("
				select a.cod_empr,a.raz_social
				from sgr_empresa a 
					inner join sgr_usuarioacceso b on a.cod_empr=b.cod_empr 
					inner join sgr_empresa_er c on a.cod_empr=c.cod_empr 
				where a.cod_usuadm=".$prm_cod_usuadm." and b.cod_usu=".$prm_cod_usu." 
					and a.est_reg=1 and b.est_reg=1 and c.est_reg=1 and not a.ruc_empr like 'E%'
				group by a.cod_empr,a.raz_social;");
		}		
		return $consulta->result_array();
	}
	
	
	function Listar_EmpresaPermisos($prm_cod_usuadm,$prm_tip_usu,$prm_cod_empr,$prm_cod_tipempresa)
	//($prm_cod_tipempresa,$prm_cod_usuadm,$prm_cod_empr)
	{
		$this->load->database('ncserver',TRUE);
		if ($prm_tip_usu==1)//ADMINISTRADOR
		{
			$query="select 
					a.cod_empr,
					a.raz_social
			from sgr_empresa a 
				inner join sgr_empresa_er b on a.cod_empr=b.cod_empr and b.est_reg=1
			where a.cod_usuadm=".$prm_cod_usuadm." and a.est_reg=1 and b.cod_rol=".$prm_cod_tipempresa.";";
		}else
		{
			$query="select a.cod_empr, a.raz_social
				from sgr_empresa a where a.cod_empr=".$prm_cod_empr.";";
		}
		$consulta = $this->db->query($query);
		
		return $consulta->result_array();
	}
	
	
	function Listar_EmpresaPermisosReceptor($prm_cod_tipempresa,$prm_cod_usuadm,$prm_cod_empr)
	{
		$this->load->database('ncserver',TRUE);
		$query="select a.cod_empr, a.raz_social, a.ruc_empr
		from sgr_empresa a 
			inner join sgr_empresa_er b on a.cod_empr=b.cod_empr and b.est_reg=1
		where a.est_reg=1 and a.cod_usuadm=".$prm_cod_usuadm." and b.cod_rol=".$prm_cod_tipempresa."  
			and cod_empr_emisor=".$prm_cod_empr.";";
		//print_r($query);
		//return;
		
		$consulta = $this->db->query($query);
		
		return $consulta->result_array();
	}
	
	function Listar_EmpresaIdRazonSocial($prm_ruc_empr)
	{

		$this->load->database('ncserver',TRUE);
		
		$prm_tip_usu=$this->Usuarioinicio_model->Get_Tip_Usu();	
		
		$sql="select
			raz_social			
		from sgr_empresa where ruc_empr='".$prm_ruc_empr."' and tip_documento='6' and est_reg=1;";

		$consulta = $this->db->query($sql);
		return $consulta->result_array();
	}
	
	
		
	
}