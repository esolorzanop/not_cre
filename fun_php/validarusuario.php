<?php 
session_start(); 
include("lib.php");
date_default_timezone_set('America/Guayaquil');	


$login=strtoupper(isset($_REQUEST["nombre"]) ? trim($_REQUEST["nombre"]):NULL);
$clave=isset($_REQUEST["clave"]) ? $_REQUEST["clave"]:NULL;

$url = ""; 
$con = conectar();

$sql = 'SELECT UW_ID FROM LOGUEO_USER WHERE BLOCK = 0 and LU_ALIAS = :login';
$rst = oci_parse($con, $sql);
oci_bind_by_name($rst, ':login', $login);
$r = oci_execute($rst);

if (!$r) {
    $e = oci_error($rst); 
/*    print htmlentities($e['message']);
    print "\n<pre>\n";
    print htmlentities($e['sqltext']);
    printf("\n%".($e['offset']+1)."s", "^");
    print  "\n</pre>\n";*/
	echo "Ocurrió un error al verificar el usuario...!";
}

$row = oci_fetch_array($rst, OCI_ASSOC+OCI_RETURN_NULLS);

if(empty($row['UW_ID'])){
// usuario no existe
    $URL="../index.php?num=4";
}else{
//usuario existe verifico password
	$sql = 'SELECT UW_ID, upper(LU_NOMBRE), LU_ALIAS, LU_PASSWORD FROM LOGUEO_USER WHERE BLOCK = 0 and LU_ALIAS = :login and lu_password = :clave';
	$rst = oci_parse($con, $sql);
	oci_bind_by_name($rst, ':login', $login);
	oci_bind_by_name($rst, ':clave', $clave);	
	$r = oci_execute($rst);
	
	if (!$r) {
		$e = oci_error($rst); 
	/*    print htmlentities($e['message']);
		print "\n<pre>\n";
		print htmlentities($e['sqltext']);
		printf("\n%".($e['offset']+1)."s", "^");
		print  "\n</pre>\n";*/
		echo "Ocurrió un error al verificar el usuario...!";
	}
	
	$row = oci_fetch_array($rst, OCI_ASSOC+OCI_RETURN_NULLS);
	if(empty($row['LU_PASSWORD'])) {
		$URL="../index.php?num=5";
		$_SESSION['LOGIN'] = $login;		
	}
	else{
		$URL="../index.php?num=1";
		$_SESSION['LOGIN'] = $login;
		$_SESSION['UW_ID'] = $row['UW_ID'];
		$_SESSION['NOMBRE'] = $row['LU_NOMBRE'];		
	}

}
//var_dump($_SESSION);
oci_free_statement($rst);
oci_close($con);	
header("Location: $URL");
?>