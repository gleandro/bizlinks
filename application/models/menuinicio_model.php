<?php
@session_start();
class Menuinicio_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

	}
	
	function Listar_MenuInicioNivel($prm_cod_nivel)
	{

		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("SELECT cod_men,nom_men FROM sgr_menuinicio 
			where cod_nivmen<=".$prm_cod_nivel." and est_reg=1;");
		return $consulta->result_array();
	}
	
	
	function Listar_MenuInicio()
	{
		//b.cod_menpad,
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("SELECT 
				b.cod_men,
				(SELECT a.nom_sist FROM sgr_sistema a where a.cod_sist=b.cod_sist) nom_sist,
				b.nom_men nom_men,
				b.url_pag url_pag,				
				b.cod_nivmen,
				(case when b.tien_hij=1 then 'SI' else 'NO' end) tien_hij,
				(case when b.conf_inic=1 then 'SI' else 'NO' end) conf_inic,
				b.order_men,
				b.ord_ejec,
				(select a.nom_men from sgr_menuinicio a where a.cod_men=b.cod_menpad) menupadre,
				b.tip_men
			FROM sgr_menuinicio b where b.est_reg=1 order by b.ord_ejec;");
		return $consulta->result_array();
		
	}
	
	function Listar_MenuInicioId($prm_cod_men)
	{
		//b.cod_menpad,
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("SELECT 
				b.cod_men,
				b.cod_sist,
				b.nom_men,
				b.desc_men,
				b.url_pag,	
				cod_menpad,			
				b.cod_nivmen,
				b.order_men,
				b.ord_ejec,
				b.tien_hij,				
				b.conf_inic	,
				b.tip_men			
			FROM sgr_menuinicio b where b.est_reg=1 and cod_men=".$prm_cod_men." order by b.ord_ejec;");
		return $consulta->result_array();		
	}
	
	
	function Modificar_Menu($prm_cod_men,$prm_cod_sist,$prm_nom_men,$prm_desc_men,$prm_url_pag,$prm_cod_menpad,$prm_cod_nivmen,
															$prm_ord_ejec,$prm_order_men,$prm_tien_hij,$prm_conf_inic,$prm_tip_men)
	{
		$this->load->database('ncserver',TRUE);//."','".
		
		$consulta = $this->db->query("select cod_men from sgr_menuinicio where cod_men<>".$prm_cod_men."  and 
			ord_ejec>=".$prm_ord_ejec." and est_reg=1 order by ord_ejec;");
		$resultado =$consulta->result_array();

		if (!empty($resultado)) 
		{
			$contador = $prm_ord_ejec;			
			foreach($resultado as $key1=>$v1):					
				$contador++;
				$this->db->query("update sgr_menuinicio set ord_ejec=".$contador." where cod_men='".$v1['cod_men']."';");					
			endforeach;	
		}	
			 			 
		$consulta = $this->db->query("
				update  sgr_menuinicio set
				cod_sist=".$prm_cod_sist.",
				nom_men='".$prm_nom_men."',
				desc_men='".$prm_desc_men."',
				url_pag='".$prm_url_pag."',				
				cod_menpad=".$prm_cod_menpad.",		
				cod_nivmen=".$prm_cod_nivmen.",
				order_men=".$prm_order_men.",
				ord_ejec=".$prm_ord_ejec.",
				tien_hij=".$prm_tien_hij.",				
				conf_inic=".$prm_conf_inic.",
				tip_men=".$prm_tip_men."			
			 where cod_men=".$prm_cod_men.";");
		
		
		return true;
		
	}
	
	function Insertar_Menu($prm_cod_sist,$prm_nom_men,$prm_desc_men,$prm_url_pag,$prm_cod_menpad,$prm_cod_nivmen,
															$prm_ord_ejec,$prm_order_men,$prm_tien_hij,$prm_conf_inic,$prm_tip_men)
	{
		$this->load->database('ncserver',TRUE);//."','".
		
		$consulta = $this->db->query("select cod_men from sgr_menuinicio where ord_ejec>=".$prm_ord_ejec." and est_reg=1 order by ord_ejec;");
		$resultado =$consulta->result_array();

		if (!empty($resultado)) 
		{
			$contador = $prm_ord_ejec;			
			foreach($resultado as $key1=>$v1):					
				$contador++;
				$this->db->query("update sgr_menuinicio set ord_ejec=".$contador." where cod_men='".$v1['cod_men']."';");					
			endforeach;	
		}
		
			 			 
		$consulta = $this->db->query("insert into sgr_menuinicio(cod_sist,nom_men,desc_men,url_pag,cod_menpad,cod_nivmen,
				order_men,ord_ejec,tien_hij,conf_inic,tip_men)
				values(".$prm_cod_sist.",'".$prm_nom_men."','".$prm_desc_men."','".$prm_url_pag."',	".$prm_cod_menpad.",
					".$prm_cod_nivmen.",".$prm_order_men.",".$prm_ord_ejec.",".$prm_tien_hij.",	".$prm_conf_inic.",
					".$prm_tip_men.");");
		
		
		return true;
		
	}
	
	
	function Eliminar_Menu($prm_cod_men,$prm_ord_ejec)
	{
		$this->load->database('ncserver',TRUE);//."','".
		
		$consulta = $this->db->query("select cod_men from sgr_menuinicio where ord_ejec>".$prm_ord_ejec." and est_reg=1 order by ord_ejec;");
		$resultado =$consulta->result_array();

		if (!empty($resultado)) 
		{
			foreach($resultado as $key1=>$v1):					
				$this->db->query("update sgr_menuinicio set ord_ejec=ord_ejec-1 where cod_men='".$v1['cod_men']."';");					
			endforeach;	
		}
			 
		$consulta = $this->db->query("update sgr_menuinicio set est_reg=0 where cod_men=".$prm_cod_men.";");
		return true;
		
	}
	
	
	
	
	
	function Listar_MenuSistemaPendiente($prm_cod_plan)
	{
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("
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
							  SELECT a.cod_men,a.nom_men nom_men1,a.url_pag,a.cod_menpad,a.cod_nivmen,a.order_men,a.ord_ejec,a.tip_men
							  FROM sgr_menuinicio a where a.tip_men=2  and est_reg=1 order by a.ord_ejec
							
							)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
						)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
					)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
				)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
			)aa left join sgr_menuplan bb on aa.cod_men=bb.cod_men and bb.est_reg=1 and bb.cod_plan=".$prm_cod_plan."
			where bb.cod_men is null order by aa.ord_ejec;");
			
		return $consulta->result_array();
	}
	
	
	function Listar_MenuSistemaAsignado($prm_cod_plan)
	{
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("
			
			select
				bb.cod_mepl cod_usuacc,
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
							aa.cod_men,aa.nom_men1,aa.nom_men2,bb.nom_men nom_men3 ,aa.url_pag,
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
							  FROM sgr_menuinicio a  where a.tip_men=2 and est_reg=1 order by a.ord_ejec
							
							)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
						)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
					)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
				)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
			)aa inner join sgr_menuplan bb on aa.cod_men=bb.cod_men
			where bb.est_reg=1 and bb.cod_plan=".$prm_cod_plan." order by aa.ord_ejec;");
			
		return $consulta->result_array();
	}
	
	
	function Guardar_Parametros($prm_cod_plan,$prm_cod_men)
	{
		$this->load->database('ncserver',TRUE);//."','".
		$this->db->query("insert into sgr_menuplan(cod_plan,cod_men) values(".$prm_cod_plan.",".$prm_cod_men.");");

		return 1;//$consulta->result_array();
		//Variables_GenerarXML
	}
	
	
	function Eliminar_Acceso($prm_cod_mepl)
	{
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("update sgr_menuplan set est_reg=0 where cod_mepl=".$prm_cod_mepl.";");
		return 1;
	}
	
	function Eliminar_AccesoAdministrador($prm_cod_menadm)
	{
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("update sgr_menu set est_reg=0 where cod_menadm=".$prm_cod_menadm.";");
		return 1;
	}
	
	function Listar_UsuariosAdministradores()
	{
		//b.cod_menpad,
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("SELECT a.cod_usuadm,a.email_usuadm,b.nom_usu,b.apell_usu 
		FROM sgr_usuarioadmin a
  			inner join sgr_usuario b on a.cod_usuadm=b.cod_usuadm
  		where a.est_reg=1 and b.est_reg=1 and b.cod_tipusu=1 order by a.email_usuadm;;");
		return $consulta->result_array();		
	}
	
	function Listar_MenuPendienteAdministrador($prm_cod_usuadm)
	{
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("
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
						aa.cod_men,aa.nom_men1,aa.nom_men2,aa.nom_men3,bb.nom_men nom_men4,
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
							  FROM sgr_menuinicio a where a.tip_men=2  and a.est_reg=1  order by a.ord_ejec
							
							)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
						)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
					)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
				)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
			)aa left join sgr_menu bb on aa.cod_men=bb.cod_men and bb.est_reg=1 and bb.cod_usuadm=".$prm_cod_usuadm."
			where bb.cod_men is null order by aa.ord_ejec;");
			
		return $consulta->result_array();
	}
	
	
	function Listar_MenuAsignadosAdministrador($prm_cod_usuadm)
	{
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("
			
			select
				bb.cod_menadm,
				aa.nom_men1,
				aa.nom_men2,
				aa.nom_men3,
				aa.nom_men4,
				aa.nom_men5,
				aa.nom_men,
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
						aa.cod_men,aa.nom_men1,aa.nom_men2,aa.nom_men3,bb.nom_men nom_men4,
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
							  FROM sgr_menuinicio a  where a.tip_men=2 and est_reg=1 order by a.ord_ejec
							
							)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
						)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
					)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
				)aa left join sgr_menuinicio bb on aa.cod_menpad=bb.cod_men
			)aa inner join sgr_menu bb on aa.cod_men=bb.cod_men
			where bb.est_reg=1 and bb.cod_usuadm=".$prm_cod_usuadm." order by aa.ord_ejec;");
			
		return $consulta->result_array();
	}
	
	
	
	
	function Guardar_MenuAdministrador($prm_cod_usuadm,$prm_cod_plan)
	{
		$this->load->database('ncserver',TRUE);//."','".
		
		/*ANULAMOS TODOS LOS PERMISOS*/
		$this->db->query("update sgr_menu set est_reg=0 where cod_usuadm=".$prm_cod_usuadm." and est_reg>0;");
		
		/*INSERTAMOS O ATUALIZAMOS TODO LOS MENUS QUE NO SON DE TIPO FINAL*/
		$consulta = $this->db->query("select 
				a.cod_sist,
				a.cod_men,
				a.nom_men,
				a.desc_men,
				a.tip_men,
				a.url_pag,
				a.cod_menpad,
				a.cod_nivmen,
				a.order_men,
				a.ord_ejec,
				a.tien_hij,
				a.conf_inic 
			from sgr_menuinicio a
			where a.est_reg=1 and a.tip_men<>2 order by a.ord_ejec;");
		$resultado =$consulta->result_array();

		if (!empty($resultado)) 
		{
			foreach($resultado as $key1=>$v1):					
				$this->db->query("select result from sgr_guardar_menu (".$prm_cod_usuadm.",
									".$v1['cod_sist'].",
									".$v1['cod_men'].",
									'".$v1['nom_men']."',
									'".$v1['desc_men']."',
									".$v1['tip_men'].",
									'".$v1['url_pag']."',
									".$v1['cod_menpad'].",
									".$v1['cod_nivmen'].",
									".$v1['order_men'].",
									".$v1['ord_ejec'].",
									".$v1['tien_hij'].",
									".$v1['conf_inic'].") ;");					
			endforeach;	
		}

		$consulta = $this->db->query("select 
				a.cod_sist,
				a.cod_men,
				a.nom_men,
				a.desc_men,
				a.tip_men,
				a.url_pag,
				a.cod_menpad,
				a.cod_nivmen,
				a.order_men,
				a.ord_ejec,
				a.tien_hij,
				a.conf_inic 
			from sgr_menuinicio a inner join sgr_menuplan b on a.cod_men=b.cod_men  
			where b.cod_plan=".$prm_cod_plan." and a.est_reg=1 and a.tip_men=2 and b.est_reg=1 order by a.ord_ejec;");
		$resultado =$consulta->result_array();


		if (!empty($resultado)) 
		{
			foreach($resultado as $key1=>$v1):					
				$this->db->query("select result from sgr_guardar_menu (".$prm_cod_usuadm.",
									".$v1['cod_sist'].",
									".$v1['cod_men'].",
									'".$v1['nom_men']."',
									'".$v1['desc_men']."',
									".$v1['tip_men'].",
									'".$v1['url_pag']."',
									".$v1['cod_menpad'].",
									".$v1['cod_nivmen'].",
									".$v1['order_men'].",
									".$v1['ord_ejec'].",
									".$v1['tien_hij'].",
									".$v1['conf_inic'].") ;");					
			endforeach;	
		}
		return true;
	}
	
	
	
	
	
	function Guardar_MenuAdministradorUnitario($prm_cod_usuadm,$prm_cod_men)
	{
		$this->load->database('ncserver',TRUE);//."','".
		
		/*ANULAMOS TODOS LOS PERMISOS*/
		//$this->db->query("update sgr_menu set est_reg=0 where cod_usuadm=".$prm_cod_usuadm.";");
		
		/*INSERTAMOS O ATUALIZAMOS TODO LOS MENUS QUE NO SON DE TIPO FINAL*/
		while ($prm_cod_men>0)
		{
			$consulta = $this->db->query("select 
					a.cod_sist,
					a.cod_men,
					a.nom_men,
					a.desc_men,
					a.tip_men,
					a.url_pag,
					a.cod_menpad,
					a.cod_nivmen,
					a.order_men,
					a.ord_ejec,
					a.tien_hij,
					a.conf_inic 
				from sgr_menuinicio a
				where a.est_reg=1 and a.cod_men=".$prm_cod_men." order by a.ord_ejec;");
			$resultado =$consulta->result_array();
	
			if (!empty($resultado)) 
			{
				$prm_cod_men=$resultado[0]['cod_menpad'];
				foreach($resultado as $key1=>$v1):					
					$this->db->query("select result from sgr_guardar_menu (".$prm_cod_usuadm.",
										".$v1['cod_sist'].",
										".$v1['cod_men'].",
										'".$v1['nom_men']."',
										'".$v1['desc_men']."',
										".$v1['tip_men'].",
										'".$v1['url_pag']."',
										".$v1['cod_menpad'].",
										".$v1['cod_nivmen'].",
										".$v1['order_men'].",
										".$v1['ord_ejec'].",
										".$v1['tien_hij'].",
										".$v1['conf_inic'].") ;");					
				endforeach;	
			}
			else
			{
				$prm_cod_men=0;
			}
		}
		return true;
	}
	
	
	
	
	
	function Listar_LiceciaAdministrador()
	{
		//b.cod_menpad, DATEDIFF(CURRENT_TIMESTAMP,fec_fin)
		$this->load->database('ncserver',TRUE);//."','".
		
		$this->db->query("update sgr_systemlicencia set est_lic=2 where cast(to_char(age(now(),fec_fin),'DD') as int) >0 and est_lic=1;");
		
		$consulta = $this->db->query("SELECT a.cod_sistlic, 
					c.email_usuadm,
					to_char(a.fec_ini,'dd/mm/yyyy') fec_ini,
					to_char(a.fec_fin,'dd/mm/yyyy') fec_fin,
					cast(to_char(age(fec_fin,fec_ini),'DD') as int) nro_dias,
					(case when a.est_lic=0 THEN 'ELIMINADO' when a.est_lic=1 THEN 'ACTIVO' when a.est_lic=2 THEN 'FINALIZADO' end) est_licencia,
					(select b.nom_plan from sgr_planes b where b.cod_plan=a.cod_plan) nom_plan,
					(case when a.cod_tiplic=1 then 'PRUEBA' else 'PAGO' end) nom_tiplic,
					(case when c.est_activ=1 then 'SI' ELSE 'NO' end) est_activo				
				FROM sgr_systemlicencia a 
					inner join sgr_usuarioadmin c on c.cod_usuadm=a.cod_usuadm
				where c.est_reg=1 and a.est_lic=1;");
		return $consulta->result_array();		
	}
	
	function Listar_LiceciaAdministradorUnitario($prm_cod_usuadm)
	{
		//b.cod_menpad,
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("SELECT a.cod_sistlic, 
					c.email_usuadm,
					to_char(a.fec_ini,'dd/mm/yyyy') fec_ini,
					to_char(a.fec_fin,'dd/mm/yyyy') fec_fin,
					cast(to_char(age(fec_fin,fec_ini),'DD') as int) nro_dias,
					(case when a.est_lic=0 THEN 'ELIMINADO' when a.est_lic=1 THEN 'ACTIVO' when a.est_lic=2 THEN 'FINALIZADO' end) est_licencia,
					(select b.nom_plan from sgr_planes b where b.cod_plan=a.cod_plan) nom_plan,
					(case when a.cod_tiplic=1 then 'PRUEBA' else 'PAGO' end) nom_tiplic,
					(case when c.est_activ=1 then 'SI' ELSE 'NO' end) est_activo				
				FROM sgr_systemlicencia a 
					inner join sgr_usuarioadmin c on c.cod_usuadm=a.cod_usuadm
				where c.est_reg=1 and a.cod_usuadm=".$prm_cod_usuadm." order by a.fec_reg desc;");
		return $consulta->result_array();		
	}
	
	function Eliminar_PeriodoLicencia($prm_cod_sistlic)
	{
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("update sgr_systemlicencia set est_lic=0 where cod_sistlic=".$prm_cod_sistlic.";");
		return 1;
	}
	
	
	function Guardar_SystemLicencia($prm_cod_usuadm,$prm_cod_plan,$prm_cod_tiplic,$prm_fec_ini,$prm_fec_fin,$prm_obs_reg)
	{
		$this->load->database('ncserver',TRUE);//."','".
		$consulta = $this->db->query("select result from  sgr_guardar_systemlicencia(".$prm_cod_usuadm.",
					".$prm_cod_plan.",
					".$prm_cod_tiplic.",
					'".$prm_fec_ini."',
					'".$prm_fec_fin."',
					'".$prm_obs_reg."') ;");
		return 1;
	}

}