<?php
//@session_start();
class Clientes_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}


	function Listar_Clientes($prm_cod_usuadm,$prm_cod_empr)
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
					a.direcc_empresa,
					a.email_empresa,
					(select aa.no_corto
						from tm_tabla_multiple aa where aa.no_tabla='TIPO_DOCUMENTO_IDENTIDAD'
						and aa.in_habilitado=1 and aa.co_item_tabla=a.tip_documento) nomb_tipodocumento
		from sgr_empresa a
				inner join sgr_empresa_er b on a.cod_empr=b.cod_empr
		 where a.cod_usuadm=".$prm_cod_usuadm."
		 		and cod_empr_emisor='".$prm_cod_empr."'
		 		and a.est_reg=1
				and b.est_reg=1
				and b.cod_rol=2;");

		return $consulta->result_array();

	}

	function Listar_ClienteId($prm_cod_empr)
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
				a.direcc_empresa,
				a.email_empresa,
				(select aa.no_corto
					from tm_tabla_multiple aa where aa.no_tabla='TIPO_DOCUMENTO_IDENTIDAD'
					and aa.in_habilitado=1 and aa.co_item_tabla=a.tip_documento) nomb_tipodocumento
		from sgr_empresa a where cod_empr=".$prm_cod_empr." and est_reg=1;");
		//print_r()
		return $consulta->result_array();
	}

	function Listar_ClienteDocumento($prm_tip_documento,$prm_ruc_empr)
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
				a.direcc_empresa,
				a.email_empresa,
				(select aa.no_corto
					from tm_tabla_multiple aa where aa.no_tabla='TIPO_DOCUMENTO_IDENTIDAD'
					and aa.in_habilitado=1 and aa.co_item_tabla=a.tip_documento) nomb_tipodocumento
		from sgr_empresa a where ruc_empr='".$prm_ruc_empr."' and tip_documento='".$prm_tip_documento."' and est_reg=1;");
		//print_r($consulta);
		return $consulta->result_array();
	}



	function Guardar_Cliente($prm_cod_usu,$prm_cod_usuadm,$prm_ruc_empr,$prm_raz_social,$prm_cod_actiempr,$prm_rep_legal,$prm_pagi_empr,$prm_fec_creac,$prm_cod_pais,$prm_cod_ubigeo,$prm_url_logoempr,$prm_tip_documento,$prm_nom_comercial,$prm_urbaniz_empresa,$prm_direcc_empresa,$prm_tipo_confserie,$prm_tipo_confunid,$prm_tipo_conffirma,$prm_cod_empr,$prm_email_cliente)
	{


		$result['result']=0;
		$this->db_client =$this->load->database('ncserver',TRUE);
		$this->db_client->trans_begin();

		if ($prm_tip_documento=='0' and $prm_ruc_empr=='-')
		{

			$query="select (valorentero+1) valorentero from sgr_multitabla
				where grupo_nombre='CORRELATIVO_CLIENTEEXTRANJERO' and activo=1 and cod_empr='".$prm_cod_empr."';";

			$consulta=$this->db_client->query($query);
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				return $result;
			}

			$codigocliente=$consulta->result_array();
			$prm_codigocliente=0;
			if(!empty($codigocliente))//SI NO ES NULO O VACIO
			{
				$prm_codigocliente=$codigocliente[0]['valorentero'];
			}
			else
			{
				$prm_codigocliente=1;
				$query="insert into sgr_multitabla
				(
				  grupo_id,
				  grupo_nombre,
				  nombre,
				  valorentero,
				  valorcadena,
				  cod_empr)
				values
				(
					'2',
					'CORRELATIVO_CLIENTEEXTRANJERO',
					'Correlativo del Cliente Extranjero',
					0,
					'-',
					'".$prm_cod_empr."'
				);";
				$this->db_client->query($query);
				if ($this->db_client->trans_status() === FALSE)
				{
					$this->db_client->trans_rollback();
					$result['result']=0;
					return $result;
				}
			}

			$prm_ruc_empr='E'.$prm_cod_empr.'-'.$prm_codigocliente;
		}

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

			$query="insert into sgr_empresa
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
					tipo_conffirma,
					email_empresa
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
					'".$prm_tipo_conffirma."',
					'".$prm_email_cliente."'
				 );";



			$this->db_client->query($query);
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				return $result;
			}

			if($this->db_client->dbdriver == 'oci8')
			{
				$cod_empresa=$this->db_client->insert_id_by_table('SGR_EMPRESA');
			}
			else
				$cod_empresa=$this->db_client->insert_id();



			$query="insert into sgr_empresa_er(cod_rol,cod_empr,cod_empr_emisor,usu_reg)
						values(2,'".$cod_empresa."','".$prm_cod_empr."','".$prm_cod_usu."');";


			$this->db_client->query($query);
			if ($this->db_client->trans_status() === FALSE)
			{
				$this->db_client->trans_rollback();
				$result['result']=0;
				return $result;
			}



			$query="update sgr_multitabla set valorentero=valorentero+1
				where cod_empr='".$prm_cod_empr."' and grupo_nombre='CORRELATIVO_CLIENTEEXTRANJERO' and grupo_id=2 and activo=1;";
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



	function Modificar_Clientes($prm_cod_client,
								$prm_cod_tipdoc,
								$prm_raz_social,
								$prm_cod_pais,
								$prm_cod_ubigeo,
								$prm_direc_cliente,
								$prm_email_cliente,
								$prm_urbaniz_cliente,
								$prm_nom_comercial,
								$prm_cod_empr,
								$prm_cod_usu)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;

		$query="
		update sgr_empresa
		set
			tip_documento='".$prm_cod_tipdoc."',
			raz_social='". $prm_raz_social."',
			cod_pais='".$prm_cod_pais."',
			cod_ubigeo='".$prm_cod_ubigeo."',
			direcc_empresa='".$prm_direc_cliente."',
			email_empresa='".$prm_email_cliente."',
			nom_comercial='".$prm_nom_comercial."',
			urbaniz_empresa='".$prm_urbaniz_cliente."'
		where cod_empr='".$prm_cod_client."';";

		//print_r($query);
		$this->db->query($query);

		$consulta = $this->db->query("select est_reg from sgr_empresa_er
				where cod_rol=2 and cod_empr='".$prm_cod_client."' and cod_empr_emisor='".$prm_cod_empr."' ;");

		$resultado=$consulta->result_array();

		if(!empty($resultado))//SI NO ES NULO O VACIO
		{
			if ($resultado[0]['est_reg']==0) //no existe registrado
			{
				$query="update sgr_empresa_er set est_reg=1
					where cod_rol=2
						and cod_empr='".$prm_cod_client."'
						and cod_empr_emisor='".$prm_cod_empr."'
						and est_reg=0;";
				$this->db->query($query);
			}
		}
		else
		{
			$query="insert into sgr_empresa_er(cod_rol,cod_empr,cod_empr_emisor,usu_reg)
						values(2,'".$prm_cod_client."','".$prm_cod_empr."','".$prm_cod_usu."');";
			$this->db->query($query);

		}


		$result['result']=1;
		return $result;
	}

	function Eliminar_Clientes($prm_cod_empr,$prm_cod_empremisor)
	{
		$this->load->database('ncserver',TRUE);
		$result['result']=0;
		$query="update  sgr_empresa_er set est_reg=0
				where cod_empr=".$prm_cod_empr."
					and cod_empr_emisor=".$prm_cod_empremisor."
					and cod_rol=2 and est_reg=1;";
		//print_r($query);
		$consulta = $this->db->query($query);
		$result['result']=1;
		return $result;
	}


	function Listar_ClientesAutocompletar($prm_cod_usuadm,$prm_cod_empr,$prm_cod_tipdoc)
	{

		$this->load->database('ncserver',TRUE);

		$query="select
					a.cod_empr,
					a.tip_documento cod_tipdoc,
					a.ruc_empr nro_docum,
					a.raz_social,
					a.cod_pais,
					a.cod_ubigeo,
					a.direcc_empresa direc_cliente,
					a.email_empresa email_cliente,
					a.urbaniz_empresa urbaniz_cliente,
					a.nom_comercial

		from sgr_empresa a
			inner join sgr_empresa_er b on a.cod_empr=b.cod_empr
		 where a.cod_usuadm=".$prm_cod_usuadm."
		 		and b.cod_empr_emisor='".$prm_cod_empr."'
				and a.tip_documento='".$prm_cod_tipdoc."'
				and b.est_reg=1
				and b.cod_rol=2
				and a.est_reg=1;";

		//print_r($query);
		//return;
		$consulta = $this->db->query($query);

		return $consulta->result_array();
	}

	function Datos_ClienteId($prm_cod_empr)
	{

		$this->load->database('ncserver',TRUE);
		$query="select
			cod_empr cod_client,
			ruc_empr nro_docum,
			raz_social,
			direcc_empresa direc_cliente,
			email_empresa email_cliente,
			cod_ubigeo,
			urbaniz_empresa urbaniz_cliente,
			nom_comercial,
			cod_pais,
			(select sp.nombre from sgr_pais sp where sp.id=cod_pais) nomb_pais,
			tip_documento cod_tipdoc,
			(select aa.no_corto
						from tm_tabla_multiple aa where aa.no_tabla='TIPO_DOCUMENTO_IDENTIDAD'
						and aa.in_habilitado=1 and aa.co_item_tabla=a.tip_documento) tipo_documento
		from sgr_empresa a
		where est_reg=1 and cod_empr=".$prm_cod_empr.";";
		//print_r($query);
		$consulta=$this->db->query($query);
		return $consulta->result_array();


	}

	function Datos_ClienteIdModificar($prm_cod_empr,$prm_tipo_doc,$prm_numer_doc,$prm_nombre_razsocial)
	{

		$this->load->database('ncserver',TRUE);

		$query="select
			cod_empr cod_client,
			ruc_empr nro_docum,
			raz_social,
			direcc_empresa direc_cliente,
			email_empresa email_cliente,
			cod_ubigeo,
			urbaniz_empresa urbaniz_cliente,
			nom_comercial,
			cod_pais,
			(case when cod_pais='PE' then 'PERU' else 'OTROS' end) nomb_pais,
			tip_documento cod_tipdoc,
			(select aa.no_corto
						from tm_tabla_multiple aa where aa.no_tabla='TIPO_DOCUMENTO_IDENTIDAD'
						and aa.in_habilitado=1 and aa.co_item_tabla=a.tip_documento) tipo_documento
		from sgr_empresa a
		where a.est_reg=1 and a.tip_documento='".$prm_tipo_doc."' ";

		if ($prm_tipo_doc=='0')
		{
			$query=$query." and raz_social= '".$prm_nombre_razsocial."'";
		}
		else
		{
			$query=$query." and ruc_empr= '".$prm_numer_doc."'";
		}
		// and nro_docum='".$prm_numer_doc."'
		//print_r($query);

		$consulta=$this->db->query($query);
		return $consulta->result_array();
	}


	function Listar_ClientesAutocompletarSimple($prm_cod_usuadm,$prm_cod_empr)
	{

		$this->load->database('ncserver',TRUE);

		$cod_rolempresa=$_SESSION['SES_MarcoTrabajo'][0]['cod_rolseleccion'];

		if ($cod_rolempresa==1)// emisor
		{
			$query="select
						a.cod_empr cod_client,
						a.tip_documento cod_tipdoc,
						a.ruc_empr nro_docum,
						a.raz_social,
						a.cod_pais,
						a.cod_ubigeo,
						a.direcc_empresa direc_cliente,
						a.email_empresa email_cliente,
						a.urbaniz_empresa urbaniz_cliente,
						a.nom_comercial

			from sgr_empresa a
				inner join sgr_empresa_er b on a.cod_empr=b.cod_empr
			 where a.cod_usuadm=".$prm_cod_usuadm."
					and b.cod_empr_emisor='".$prm_cod_empr."'
					and b.est_reg=1
					and b.cod_rol=2
					and a.est_reg=1;";
		}
		else
		{
			$query="select
						a.cod_empr cod_client,
						a.tip_documento cod_tipdoc,
						a.ruc_empr nro_docum,
						a.raz_social,
						a.cod_pais,
						a.cod_ubigeo,
						a.direcc_empresa direc_cliente,
						a.email_empresa email_cliente,
						a.urbaniz_empresa urbaniz_cliente,
						a.nom_comercial

			from sgr_empresa a
				inner join sgr_empresa_er b on a.cod_empr=b.cod_empr_emisor
			 where a.cod_usuadm=".$prm_cod_usuadm."
					and b.cod_empr='".$prm_cod_empr."'
					and b.est_reg=1
					and b.cod_rol=2
					and a.est_reg=1;";
		}

		//print_r($query);

		$consulta = $this->db->query($query);

		return $consulta->result_array();
	}



}
