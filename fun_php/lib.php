<?php
function conectar()
{
	$username = "UMOVIL";
	$passwd = "UMOVIL";
	
    $db = "(DESCRIPTION = (ADDRESS_LIST = (FAILOVER = on)(LOAD_BALANCE = on)(SOURCE_ROUTE = off)(ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.1.17)(PORT = 1521))(ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.1.18)(PORT = 1521)))(CONNECT_DATA = (SERVER = dedicated)(SERVICE_NAME = GLTEVRAC)))";

	$conn = oci_connect($username,$passwd,$db,'WE8ISO8859P15');
	$err = oci_error();

	if ($err){
//		echo 'Error de comunicaci&oacute;n con la BD '.$err['code'].' '.$err['message'].' '.$err['sqltext'];
		echo 'Error de comunicaci&oacute;n intente nuevamente.';
	}
         
	return $conn;
}

function conectar1()
{
	$username = "ATRANSVAL";
	$passwd = "ATRANSVAL";
	
    $db = "(DESCRIPTION = (ADDRESS_LIST = (FAILOVER = on)(LOAD_BALANCE = on)(SOURCE_ROUTE = off)(ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.1.116)(PORT = 1522)))(CONNECT_DATA = (SERVER = dedicated)(SERVICE_NAME = GLTEVRAC)))";

	$conn = oci_connect($username,$passwd,$db,'WE8ISO8859P15');
	$err = oci_error();

	if ($err){
//		echo 'Error de comunicaci&oacute;n con la BD '.$err['code'].' '.$err['message'].' '.$err['sqltext'];
		echo 'Error de comunicaci&oacute;n intente nuevamente.';
	}
         
	return $conn;
}