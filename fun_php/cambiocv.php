<?php 
session_start(); 
include("lib.php");
date_default_timezone_set('America/Guayaquil');	

if($_SESSION['LOGIN'] == null) echo "<script>parent.window.location.href='../fun_php/salir.php';</script>";

$login = $_SESSION['LOGIN'];
$clave = isset($_REQUEST["password1"]) ? $_REQUEST["password1"]:NULL;
$clavec = isset($_REQUEST["password2"]) ? $_REQUEST["password2"]:NULL;

$clave = trim($clave);

$con = conectar();

$stid = oci_parse($con, 'ALTER USER '.$login.' IDENTIFIED BY '.$clave);
		
							//oci_bind_by_name($stid, ':p_usuario', $login);	
							//oci_bind_by_name($stid, ':p_clave', $clave);
														
							$r = oci_execute($stid);
							$mensaje = 'pasa';	
		
							if (!$r) {
									$e = oci_error($stid); 
									/*print htmlentities($e['message']);
									print "\n<pre>\n";
									print htmlentities($e['sqltext']);
									printf("\n%".($e['offset']+1)."s", "^");
									print  "\n</pre>\n";*/
									 $mensaje = "No se pudo CAMBIAR  SU CLAVE DE USUARIO, verifique e intente nuevamente...!";
									// echo "<script>alert('".$mensaje."');</script>";
								}
							
							oci_free_statement($stid);
							oci_close($conn);																						
							//echo "<script>alert('".$mensaje."');".$report."window.location='".$direc."';</script>";							


	if($mensaje=='pasa') {
		$URL="salir.php?num=5";//cambio de clave
	}else{		
		$URL="../pag_php/inicio.php";		
		echo "<script>alert('".$mensaje."');</script>";							
	}

//var_dump($_SESSION);
//header("Location: $URL");
echo "<script>window.location='".$URL."';</script>";
?>