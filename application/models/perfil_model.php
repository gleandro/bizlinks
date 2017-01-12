<?php
@session_start();
class Perfil_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function Listar_Perfil()
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("
		
		select 
			a.cod_perfil,
			UPPER(a.nom_perfil) nom_perfil,
			a.cod_inter,
			b.desc_rol,
			a.cod_rol
		from sgr_perfil a inner join sgr_rol b on a.cod_rol=b.cod_rol where a.est_reg=1 ;");
		return $consulta->result_array();	
	}
	
	function Listar_PerfilId($prm_cod_perfil)
	{
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("
		
		select 
			cod_perfil,
			nom_perfil,
			cod_inter,
			cod_rol
		from sgr_perfil a where a.est_reg=1 and cod_perfil='".$prm_cod_perfil."' ;");
		return $consulta->result_array();	
	}
	
	function Guardar_Perfil($prm_nom_roles,$prm_cod_inter,$prm_cod_rol)
	{
		$result['result']=0;
		$this->load->database('ncserver',TRUE);

		$this->db->query("insert into sgr_perfil(nom_perfil,cod_inter,cod_rol)
		values('".$prm_nom_roles."','".$prm_cod_inter."','".$prm_cod_rol."') ;");
		$result['result']=1;
		return $result;	
	}

	function Modificar_Perfil($prm_cod_roles,$prm_nom_roles,$prm_cod_inter,$prm_cod_rol)
	{
		$result['result']=0;
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("		
		update sgr_perfil set
		  nom_perfil='".$prm_nom_roles."',
		  cod_inter='".$prm_cod_inter."',
		  cod_rol='".$prm_cod_rol."'
		where cod_perfil='".$prm_cod_roles."';");
		$result['result']=1;
		return $result;	
	}
	
	function Eliminar_Perfil($prm_cod_roles)
	{
		$result['result']=0;	
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("update sgr_perfil set est_reg=0 where cod_perfil=".$prm_cod_roles.";");
		$result['result']=1;
		return $result;	
	}

	function Guardar_Perfiles($prm_cod_roles,$prm_cod_men)
	{
		$result['result']=0;
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();
		
		$listapermisosasignar=explode(',',$prm_cod_men);

		foreach($listapermisosasignar as $key=>$v):			 
			$prm_cod_men_sub=$v;
				$query="insert into sgr_perfilmenuroles(cod_perfil,cod_men)
					values (
						'".$prm_cod_roles."',
						'".$prm_cod_men_sub."'
					);";
				$this->db_client->query($query);
	
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
		
		//$consulta = $this->db->query("insert into sgr_perfilmenuroles(cod_perfil,cod_men)values('".$prm_cod_roles."','".$prm_cod_men."');");
		//$result['result']=1;
		//return $result;
	}
	
	function Eliminar_Perfiles($prm_cod_mepr)
	{
		$result['result']=0;	
		$this->load->database('ncserver',TRUE);
		$consulta = $this->db->query("update sgr_perfilmenuroles set est_reg=0 where cod_mepr=".$prm_cod_mepr.";");
		$result['result']=1;
		return $result;	
	}

	function Listar_MenuSistemaPendiente($prm_cod_perfil,$prm_cod_rol)
	{
		$this->load->database('ncserver',TRUE);//."','".
		
		$query="
			select
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
							aa.cod_men,aa.nom_men1,aa.nom_men2,bb.nom_men nom_men3  ,aa.url_pag,
							aa.cod_nivmen,aa.order_men,aa.ord_ejec,aa.tip_men,
							bb.cod_menpad
						from
						(
							select aa.cod_men,aa.nom_men1,bb.nom_men nom_men2  ,aa.url_pag,
								aa.cod_nivmen,aa.order_men,aa.ord_ejec,aa.tip_men,
								bb.cod_menpad
							from
							(
							  SELECT a.cod_men,a.nom_men nom_men1,a.url_pag,a.cod_menpad,a.cod_nivmen,a.order_men,a.ord_ejec,a.tip_men
							  FROM sgr_menuinicio a 
							  where a.tip_men=2 and a.est_reg=1 and a.cod_rol=".$prm_cod_rol."
							
							)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men 
							
						)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
					)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
				)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
			)aa left join sgr_perfilmenuroles bb on aa.cod_men=bb.cod_men
				and bb.est_reg=1 and bb.cod_perfil=".$prm_cod_perfil."
			where bb.cod_men is null order by aa.ord_ejec;";
		
		//print_r();
		$consulta = $this->db->query($query);
			
		return $consulta->result_array();
	}
	
	
	function Listar_MenuSistemaAsignado($prm_cod_perfil,$prm_cod_rol)
	{
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("
			
			select
				bb.cod_mepr,
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
							aa.cod_men,
							aa.nom_men1,aa.nom_men2,bb.nom_men nom_men3,
							aa.url_pag,
							aa.cod_nivmen,aa.order_men,aa.ord_ejec,aa.tip_men,
							bb.cod_menpad
						from
						(
							select aa.cod_men,
								aa.nom_men1,bb.nom_men nom_men2,
								aa.url_pag,
								aa.cod_nivmen,aa.order_men,aa.ord_ejec,aa.tip_men,
								bb.cod_menpad
							from
							(
							  SELECT cod_men,
							  	nom_men nom_men1,
								url_pag,cod_menpad,cod_nivmen,order_men,ord_ejec,tip_men
							  FROM sgr_menuinicio a where tip_men=2  and a.cod_rol=".$prm_cod_rol."
							
							)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
						)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
					)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
				)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
			)aa inner join sgr_perfilmenuroles bb on aa.cod_men=bb.cod_men
			where bb.est_reg=1 and bb.cod_perfil=".$prm_cod_perfil." order by aa.ord_ejec;");
			
		return $consulta->result_array();
	}

	
}