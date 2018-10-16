<?php 
	session_start(); 
	include("lib.php");
	date_default_timezone_set('America/Guayaquil');	
	
	$login=strtoupper(isset($_REQUEST["usuario"]) ? trim($_REQUEST["usuario"]):NULL);
	
	$URL=""; 
	$con = conectar();
	
 	$sql='select * from portal.usuario_web where COD_USUARIOP=:login';
	$rst=oci_parse($con,$sql);
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
 
  	$num_filas=0;
	while(($row = oci_fetch_array($rst, OCI_ASSOC)))
  	 {
		 $num_filas++;
		 $cont = $row['UP_RCLAVE']+1;
	 } 

	if($num_filas == 0)
	{    
       $URL="../index.php?num=4";
	}else{
		  $_SESSION['LOGIN'] = $login;	   
		  $login = "$login";	  

		  $sql='UPDATE portal.usuario_web SET up_rclave = :cont WHERE cod_usuariop like :login';				  
		  $rst = oci_parse($con, $sql);
		  oci_bind_by_name($rst, ":cont", $cont);			  
		  oci_bind_by_name($rst, ":login", $login, -1, SQLT_CHR);			  
		  $r = oci_execute($rst);			

		  if (!$r) {
  			$e = oci_error($rst); 
			/*    print htmlentities($e['message']);
				print "\n<pre>\n";
				print htmlentities($e['sqltext']);
				printf("\n%".($e['offset']+1)."s", "^");
				print  "\n</pre>\n";*/
				echo "Ocurrio un error recuperacion clave";
			}  
		  $URL="../index.php?num=8";	  
		}
				
header("Location: $URL");
exit;
?>	