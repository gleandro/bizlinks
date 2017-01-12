<?php
@session_start();
class Usuarioacceso_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
		$this->NombreEntidad="usuarioaccesos";
		$this->ListaEntidad="usuarioacceso";	
	}
	
	//$prm_cod_men,
	function Guardar_Parametros($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_cod_usureg,$prm_cod_roles,$prm_listapermisosasignar,$prm_cod_perfil)
	{
		$result['result']=0;		
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();
		
		$listapermisosasignar=explode(',',$prm_listapermisosasignar);		
		foreach($listapermisosasignar as $key=>$v):			 

			$prm_cod_men=$v;
			$query="select count(cod_usuacc) cantidad from sgr_usuarioacceso 
						where cod_empr='".$prm_cod_empr."' and cod_men='".$prm_cod_men."' and cod_usu='".$prm_cod_usu."' and cod_rol='".$prm_cod_roles."';"	;	
			
			//print_r($query);
			$consulta = $this->db_client->query($query);		
			
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=-1;
				return $result;
			}
			$documento=$consulta->result_array();		
	
			if ($documento[0]['cantidad']==0) //num_doc
			{
				$query="insert into sgr_usuarioacceso
					(
						cod_usuadm,
						cod_empr,
						cod_usu,
						cod_men,
						cod_usureg,
						cod_rol,
						cod_perfil
					)
					values
					(
						'".$prm_cod_usuadm."',
						'".$prm_cod_empr."',
						'".$prm_cod_usu."',
						'".$prm_cod_men."',
						'".$prm_cod_usureg."',
						'".$prm_cod_roles."',
						'".$prm_cod_perfil."'
					);";
				//print_r($query);
				$this->db_client->query($query);
			
			}		
			else
			{
				$query="update sgr_usuarioacceso 
						set est_reg=1
					where cod_empr='".$prm_cod_empr."'
						and cod_men='".$prm_cod_men."'
						and cod_usu='".$prm_cod_usu."'
						and cod_rol='".$prm_cod_roles."'
						;";
				//print_r($query);
				$this->db_client->query($query);
			}
	
	
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				return $result;
			}
		
		endforeach;
		

		$this->db_client->trans_commit();
		$result['result']=1;			
		return $result;

	}
	
	
	function Eliminar_Acceso($prm_cod_usuacc,$prm_cod_usu)
	{
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("update sgr_usuarioacceso set est_reg=0 where cod_usuacc=".$prm_cod_usuacc.";");
		return 1;
	}
	
	function Listar_MenuSistemaPendiente($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_cod_roles,$prm_cod_perfil)
	{
		$this->load->database('ncserver',TRUE);//."','".
		
		$query="select
				aa.cod_men,
				aa.nom_men1,
				aa.nom_men2,
				aa.nom_men3,
				aa.nom_men4,
				aa.nom_men5,
				aa.url_pag,
				aa.cod_nivmen,
				aa.order_men,
				aa.ord_ejec,
				aa.tip_men,
				aa.cod_menpad
			from
			(
				select
					aa.cod_men,
					aa.nom_men1,aa.nom_men2,aa.nom_men3,aa.nom_men4,bb.nom_men nom_men5,
					aa.url_pag,
					aa.cod_nivmen,
					aa.order_men,
					aa.ord_ejec,
					aa.tip_men,
					bb.cod_menpad
				from
				(
					select
						aa.cod_men,
						aa.nom_men1,aa.nom_men2,aa.nom_men3,bb.nom_men nom_men4,
						aa.url_pag,
						aa.cod_nivmen,aa.order_men,aa.ord_ejec,aa.tip_men,
						bb.cod_menpad
					from
					(
						select
							aa.cod_men,aa.nom_men1,aa.nom_men2,bb.nom_men nom_men3,aa.url_pag,
							aa.cod_nivmen,aa.order_men,aa.ord_ejec,aa.tip_men,
							bb.cod_menpad
						from
						(
							select aa.cod_men,aa.nom_men1,bb.nom_men nom_men2,aa.url_pag,
								aa.cod_nivmen,aa.order_men,aa.ord_ejec,aa.tip_men,
								bb.cod_menpad
							from
							(
							  SELECT a.cod_men,a.nom_men nom_men1,a.url_pag,a.cod_menpad,a.cod_nivmen,a.order_men,a.ord_ejec,a.tip_men
							  FROM sgr_menuinicio a 
							  		inner join sgr_perfilmenuroles b on a.cod_men=b.cod_men 
							  where a.tip_men=2 and a.cod_rol=".$prm_cod_roles." 
							  	and b.est_reg=1 
								and a.est_reg=1
								and b.cod_perfil=".$prm_cod_perfil."
							)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men 
						)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
					)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
				)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
			)aa left join sgr_usuarioacceso bb on aa.cod_men=bb.cod_men and bb.cod_usuadm=".$prm_cod_usuadm."
				and bb.cod_empr=".$prm_cod_empr." and bb.est_reg=1 and bb.cod_usu=".$prm_cod_usu." and bb.cod_rol=".$prm_cod_roles."
			where bb.cod_men is null order by aa.ord_ejec;";
		
		//print_r($query);
		$consulta = $this->db->query($query);
			
		return $consulta->result_array();
	}
	
	
	function Listar_MenuSistemaAsignado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_cod_roles,$prm_cod_perfil)
	{
		$this->load->database('ncserver',TRUE);//."','".
		
		$query="
			
			select
				bb.cod_usuacc,
				aa.cod_men,
				aa.nom_men1,
				aa.nom_men2,
				aa.nom_men3,
				aa.nom_men4,
				aa.nom_men5,
				aa.url_pag,
				aa.cod_nivmen,
				aa.order_men,
				aa.ord_ejec,
				aa.tip_men,
				aa.cod_menpad,
				(select cc.desc_rol from sgr_rol cc  where cc.cod_rol=bb.cod_rol) tipousuario,
				(select dd.nom_perfil from sgr_perfil dd where dd.cod_perfil=bb.cod_perfil) nomb_perfil
			from
			(
				select
					aa.cod_men,
					aa.nom_men1,aa.nom_men2,aa.nom_men3,aa.nom_men4,bb.nom_men nom_men5,
					aa.url_pag,
					aa.cod_nivmen,
					aa.order_men,
					aa.ord_ejec,
					aa.tip_men,
					bb.cod_menpad
				from
				(
					select
						aa.cod_men,aa.nom_men1,aa.nom_men2,aa.nom_men3,bb.nom_men nom_men4,aa.url_pag,
						aa.cod_nivmen,aa.order_men,aa.ord_ejec,aa.tip_men,
						bb.cod_menpad
					from
					(
						select
							aa.cod_men,aa.nom_men1,aa.nom_men2,bb.nom_men nom_men3,aa.url_pag,
							aa.cod_nivmen,aa.order_men,aa.ord_ejec,aa.tip_men,
							bb.cod_menpad
						from
						(
							select aa.cod_men,aa.nom_men1,bb.nom_men nom_men2,aa.url_pag,
								aa.cod_nivmen,aa.order_men,aa.ord_ejec,aa.tip_men,
								bb.cod_menpad
							from
							(
							  SELECT cod_men,nom_men nom_men1,url_pag,cod_menpad,cod_nivmen,order_men,ord_ejec,tip_men
							  FROM sgr_menuinicio a where tip_men=2 and a.cod_rol =".$prm_cod_roles."
							
							)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
						)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
					)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
				)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
			)aa inner join sgr_usuarioacceso bb on aa.cod_men=bb.cod_men
			where bb.cod_empr=".$prm_cod_empr." and bb.est_reg=1 and bb.cod_usu=".$prm_cod_usu." 
				and bb.cod_rol =".$prm_cod_roles." order by bb.cod_perfil,aa.ord_ejec;";
		
		//print_r($query);
		
		$consulta = $this->db->query($query);
			
		return $consulta->result_array();
	}
	
	
	
	

	
	function Listar_UsuariosPorEmpresa($prm_cod_usuadm)
	{
		$this->load->database('ncserver',TRUE);//."','".
		
		$sql="
			select 
				aa.nom_usu,
				aa.apell_usu,
				aa.email_usu,
				(select bb.desc_rol from sgr_rol bb  where aa.cod_rol=bb.cod_rol) tipo_usuario,
				aa.ruc_empr,
				aa.raz_social
				
				from
				(
					select a.nom_usu,a.apell_usu,a.email_usu,b.cod_rol,c.ruc_empr,c.raz_social 
					from sgr_usuario a 
						inner join sgr_usuarioacceso b on a.cod_usu=b.cod_usu
						inner join sgr_empresa c on c.cod_empr=b.cod_empr
						inner join sgr_empresa_er d on d.cod_rol=b.cod_rol and d.cod_empr=b.cod_empr and d.est_reg=1
					where a.est_reg=1 and b.est_reg=1 and c.est_reg=1
						and a.cod_usuadm=".$prm_cod_usuadm."
					group by a.nom_usu,a.apell_usu,a.email_usu,b.cod_rol,c.ruc_empr,c.raz_social
					
				)aa order by aa.raz_social,aa.email_usu"; 
		
		//print_r($sql);
		
		$consulta = $this->db->query($sql);
			
		return $consulta->result_array();
	}
	
}