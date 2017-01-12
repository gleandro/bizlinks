<?php
@session_start();
class Menu_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function Listar_UsuarioAccesos($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu,$prm_buscar_menu=0,$prm_cod_rolseleccion=0)
	{

		if ($prm_buscar_menu==1) 
		{
			$this->db->query("delete from sgr_generandomenu where cod_usuadm='".$prm_cod_usuadm."' and	cod_usu='".$prm_cod_usu."';");		

			if ($prm_tip_usu==1) //ADMINISTRADOR
			{
				$this->db->query("insert into sgr_generandomenu
					(
					cod_usuadm,cod_empr,cod_usu,cod_men,nom_men,url_pag,
					cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
					)
					SELECT ".$prm_cod_usuadm.",".$prm_cod_empr.",".$prm_cod_usu.",cod_men,nom_men,url_pag,
					cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
					FROM sgr_menuinicio
					where est_reg=1 
						and cod_rol=".$prm_cod_rolseleccion."
						and tip_men=2
					order by ord_ejec;");	
					
					//Adicionamos las opciones basicas
					$this->db->query("insert into sgr_generandomenu
					(
					cod_usuadm,cod_empr,cod_usu,cod_men,nom_men,url_pag,
					cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
					)
					SELECT
						".$prm_cod_usuadm.",".$prm_cod_empr.",".$prm_cod_usu.",cod_men,nom_men,url_pag,
						cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
					FROM sgr_menuinicio
					where est_reg=1
					and conf_inic=1
					and tip_men=2
					order by ord_ejec;");
					
					$this->db->query("insert into sgr_generandomenu
					(
					cod_usuadm,cod_empr,cod_usu,cod_men,nom_men,url_pag,
					cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
					)
					SELECT
						".$prm_cod_usuadm.",".$prm_cod_empr.",".$prm_cod_usu.",cod_men,nom_men,url_pag,
						cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
					FROM sgr_menuinicio
					where est_reg=1
					and cod_men in(2,3,4)
					order by ord_ejec;");
						
					
			}
			else
			{
				if ($prm_cod_empr==0) /*Configuracion Basica*/
				{
					$this->db->query("insert into sgr_generandomenu
					(
					cod_usuadm,cod_empr,cod_usu,cod_men,nom_men,url_pag,
					cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
					)
					SELECT
						".$prm_cod_usuadm.",".$prm_cod_empr.",".$prm_cod_usu.",cod_men,nom_men,url_pag,
						cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
					FROM sgr_menuinicio
					where est_reg=1 and conf_inic=1 and tip_men=2
					order by ord_ejec;");
				
				}
				else /*Busca la configuracion del usuario para la empresa*/
				{
					//inner join sgr_perfilmenuroles c on c.cod_rol=b.cod_rol and c.cod_men=b.cod_men and c.est_reg=1
					$this->db->query("insert into sgr_generandomenu
					(
					cod_usuadm,cod_empr,cod_usu,cod_men,nom_men,url_pag,
					cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
					)
					SELECT
						".$prm_cod_usuadm.",".$prm_cod_empr.",".$prm_cod_usu.",a.cod_men,a.nom_men,a.url_pag,
						a.cod_menpad,a.cod_nivmen,a.order_men,a.tien_hij,a.ord_ejec
					FROM sgr_menuinicio a
						inner join sgr_usuarioacceso b on a.cod_men=b.cod_men						
					where a.est_reg=1
					and b.est_reg=1
					and a.tip_men=2
					and b.cod_usu=".$prm_cod_usu."
					and b.cod_empr=".$prm_cod_empr."
					and b.cod_rol=".$prm_cod_rolseleccion."
					order by ord_ejec;");
					
					
					
					/*Adicionamos las opciones basicas*/
					$this->db->query("insert into sgr_generandomenu
					(
					cod_usuadm,cod_empr,cod_usu,cod_men,nom_men,url_pag,
					cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
					)
					SELECT
						".$prm_cod_usuadm.",".$prm_cod_empr.",".$prm_cod_usu.",cod_men,nom_men,url_pag,
						cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
					FROM sgr_menuinicio
					where est_reg=1
					and conf_inic=1
					and tip_men=2
					order by ord_ejec;");					
				}
			}
			/*SACAMOS LOS PADRES DE LOS HIJOS*/		
			$vcod_nivmen=5;
			while ($vcod_nivmen > 0) 
			{
	
				$this->db->query("insert into sgr_generandomenu
				(
				cod_usuadm,cod_empr,cod_usu,cod_men,nom_men,url_pag,
				cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
				)
				SELECT
				".$prm_cod_usuadm.",".$prm_cod_empr.",".$prm_cod_usu.",a.cod_men,a.nom_men,a.url_pag,
				a.cod_menpad,a.cod_nivmen,a.order_men,a.tien_hij,a.ord_ejec
				FROM sgr_menuinicio a inner join
				  (select cod_menpad from sgr_generandomenu
					where cod_nivmen=".$vcod_nivmen."
					and cod_empr=".$prm_cod_empr."
					and cod_usu=".$prm_cod_usu."
					group by cod_menpad
				  )b on a.cod_men=b.cod_menpad
				where a.est_reg=1
				order by a.ord_ejec;");
				
				
				
				$vcod_nivmen = $vcod_nivmen - 1;
	
			}			
			
		}
		

		return true;//$consulta->result_array();
		
		
	}
	
	
	
	
	function Listar_UsuarioAccesosInvitado($prm_cod_usuadm,$prm_cod_empr,$prm_cod_usu,$prm_tip_usu)
	{
	
	
		
		
		$sql="";
		
		if ($prm_tip_usu==1) /*ADMINISTRADOR*/
		{	
			$sql="select
					cod_men,nom_men,url_pag,cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
				from sgr_generandomenu
				where
				cod_usuadm=".$prm_cod_usuadm." 
				and	cod_usu=".$prm_cod_usu."
				group by cod_men,nom_men,url_pag,cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
				order by ord_ejec;";
				//and	cod_empr=".$prm_cod_empr." 
		}
		else
		{
			/*
			if($_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion']==0)
			{
				$sql="delete from sgr_generandomenu
					where
					cod_usuadm=".$prm_cod_usuadm." 
					and	cod_empr=".$prm_cod_empr." 
					and	cod_usu=".$prm_cod_usu.";";
				$this->db->query($sql);
			}
			*/
			$sql="select
					cod_men,nom_men,url_pag,cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
				from sgr_generandomenu
				where
				cod_usuadm=".$prm_cod_usuadm."  
				and	cod_empr=".$prm_cod_empr." 
				and	cod_usu=".$prm_cod_usu."
				group by cod_men,nom_men,url_pag,cod_menpad,cod_nivmen,order_men,tien_hij,ord_ejec
				order by ord_ejec;";
			
			

		}
		//print($consulta->result_array());
		//return;
		//print_r($sql);
		$consulta = $this->db->query($sql);
		return $consulta->result_array();
		
		
	}
	
	
	
	function Listar_RolesEmpresa($prm_tip_usu,$prm_cod_empr)
	{
		$this->load->database('ncserver',TRUE);
		
		//print_r($prm_tip_usu);
		
		$query="
			select b.cod_rol,b.desc_rol nom_rol from
			(
			select cod_rol from sgr_empresa_er where cod_empr=".$prm_cod_empr." 
				and est_reg=1 group by cod_rol
			)a inner join sgr_rol b on a.cod_rol=b.cod_rol ;";
			
			//print_r($query);
			$consulta = $this->db->query($query);
		
		/*
		$query='';
		if ($prm_tip_usu==1)//ADMINISTRADOR
		{
		
			$query="
			select cod_rol, 
			desc_rol nom_rol from sgr_rol where est_reg=1;";
			//print_r($query);
			$consulta = $this->db->query($query);
		}
		else
		{
			$query="
			select b.cod_rol,b.desc_rol nom_rol from
			(
			select cod_rol from sgr_empresa_er where cod_empr=".$prm_cod_empr." 
				and est_reg=1 group by cod_rol
			)a inner join sgr_rol b on a.cod_rol=b.cod_rol ;";
			
			//print_r($query);
			$consulta = $this->db->query($query);
		
			
		}*/
		
		return $consulta->result_array();	
	}
	
	
	
	
	
	
	
}