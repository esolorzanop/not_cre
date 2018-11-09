<?php 
session_start(); 
include("lib.php");
date_default_timezone_set('America/Guayaquil');	


$login=strtoupper(isset($_REQUEST["nombre"]) ? trim($_REQUEST["nombre"]):NULL);
$clave=isset($_REQUEST["clave"]) ? $_REQUEST["clave"]:NULL;

$con = conectar();


$stid = oci_parse($con, 'begin SP_CHK_LOGIN_NC(:p_usuario,:p_clave,:p_tipo_usu,:p_mensaje); end;');
		
							oci_bind_by_name($stid, ':p_usuario', $login);	
							oci_bind_by_name($stid, ':p_clave', $clave);
							oci_bind_by_name($stid, ':p_tipo_usu', $tipo_usu);
							oci_bind_by_name($stid, ':p_mensaje', $mensaje,200);
														
							$r = oci_execute($stid);
		
							if (!$r) {
									$e = oci_error($stid); 
									/*print htmlentities($e['message']);
									print "\n<pre>\n";
									print htmlentities($e['sqltext']);
									printf("\n%".($e['offset']+1)."s", "^");
									print  "\n</pre>\n";*/
									 $mensaje = "Ocurrió un error al intentar VERIFICAR USUARIO...!";
								}
							
							oci_free_statement($stid);
							oci_close($conn);		
																					
							//echo "<script>alert('".$mensaje."');".$report."window.location='".$direc."';</script>";
							//echo "<script>alert('".$mensaje."');</script>";


	if($mensaje=='pasa') {
		$URL="../index.php?num=1";
		$_SESSION['LOGIN'] = $login;		
		$_SESSION['TIPO_USU'] = $tipo_usu;
		//$_SESSION['LOGIN'] = $login;		
	}
	else{
		$URL="../index.php?num=4";
		//$_SESSION['UW_ID'] = $row['UW_ID'];
		//$_SESSION['NOMBRE'] = $row['LU_NOMBRE'];		
	}


//var_dump($_SESSION);
header("Location: $URL");
?>