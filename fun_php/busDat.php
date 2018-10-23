<?php
session_start();

// indicamos que la respuesta es en formato JSON
header("Content-type: application/json");

//defino una clase que voy a utilizar para generar los elementos sugeridos en autocompletar
class ElementoAutocompletar {
   var $label;
   var $value;
   
   function __construct($label, $value){
      $this->label = $label;
      $this->value = $value;
   }
}

// nos conectamos
	include("lib.php");
    $conn = conectar();
	
	$arrayElementos = array();

	/*if((isset($_REQUEST['ci']))&&(isset($_REQUEST['cl']))){
	$_SESSION['ciu'] = $_REQUEST['ci'];
	$_SESSION['cli'] = $_REQUEST['cl'];
	}*/

	$fun = $_REQUEST['f'];
	$term = strtoupper($_REQUEST['q']);

/*
 //busqueda por nombres de atm
if ($fun == 'nom'){	  
	$sql = "SELECT atm_numero || ' :: ' || atm_nombre nom_atm, cod_atm FROM ATM where cod_ciudad = ".$_SESSION['ciu']." and cod_cliente = ". $_SESSION['cli']." and  atm_numero || ' :: ' || atm_nombre LIKE '%$term%' order by atm_nombre asc";

	//echo $sql;

 $rst=oci_parse($conn,$sql);
 oci_execute($rst) or die("Ocurrió un error al ejecutar el query1...");

	while(($row = oci_fetch_array($rst, OCI_ASSOC)))
		{	
  			array_push($arrayElementos, new ElementoAutocompletar(utf8_encode($row["NOM_ATM"]), $row["COD_ATM"]));
		}

}

 //busqueda de tipo de atencion
if ($fun == 'tip'){	   //busqueda de nombres de atm
	$sql = "select tat_descripcion, cod_atm_tipo_atencion cod_atencion from atm_tipo_atencion where tat_descripcion LIKE '%$term%'  order by tat_descripcion";
//	echo $sql;

 $rst=oci_parse($conn,$sql);
 oci_execute($rst) or die("Ocurrió un error al ejecutar el query1...");



while(($row = oci_fetch_array($rst, OCI_ASSOC)))
{	
  array_push($arrayElementos, new ElementoAutocompletar(utf8_encode($row["TAT_DESCRIPCION"]), $row["COD_ATENCION"]));
}

}*/

 //busqueda de cliente
if ($fun == 'cli'){	   //busqueda de nombres de clientes
	$sql = "select DISTINCT(nomb_inve_clie) nomb_inve_clie, codi_inve_clie from inve_clientes_dat where codi_admi_empr_fina = '00001' and codi_admi_esta = 'A' and nomb_inve_clie LIKE '%$term%' order by nomb_inve_clie";
	
		/*"select distinct (cl.cl_nombre),CL.COD_CLIENTE from cliente cl, atm where ATM.COD_cliente = CL.COD_CLIENTE AND atm.cod_cliente IN (SELECT UCLI.COD_CLIENTE FROM umovil.logueo_cliente ucli WHERE cod_servicio = 'ATM' AND uw_id = ".$_SESSION['nID'] .")and CL.CL_NOMBRE LIKE '%$term%' order by CL.CL_NOMBRE";*/
//	echo $sql;

 $rst = oci_parse($conn,$sql);
 oci_execute($rst) or die("Ocurrió un error al buscar clientes...");

while($row = oci_fetch_array($rst, OCI_ASSOC))
{	
  array_push($arrayElementos, new ElementoAutocompletar(utf8_encode($row["NOMB_INVE_CLIE"]), $row["CODI_INVE_CLIE"]));
}

}

/* //busqueda de cliudad
if ($fun == 'ciu'){	   //busqueda de nombres de atm
	$sql = "select ci_nombre, VC.COD_CIUDAD from umovil.LOGUEO_CIUDAD lc, umovil.view_ciudad vc where lc.vc_id = vc.cod_ciudad and lc.UW_ID = ".$_SESSION['nID'] ."and ci_nombre LIKE '%$term%'  order by ci_nombre";
//	echo $sql;

 $rst=oci_parse($conn,$sql);
 oci_execute($rst) or die("Ocurrió un error al ejecutar el query1...");

while(($row = oci_fetch_array($rst, OCI_ASSOC)))
{	
  array_push($arrayElementos, new ElementoAutocompletar(utf8_encode($row["CI_NOMBRE"]), $row["COD_CIUDAD"]));
}

}
*/

// pasamos el array a formato json
echo  $_GET['callback'] . '('. json_encode($arrayElementos, JSON_NUMERIC_CHECK).')';

// cerramos la conexion
 oci_close($con);
?>