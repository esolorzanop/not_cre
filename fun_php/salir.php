<?php 
   session_start();
   $URL = "";
   $num = isset($_REQUEST["num"]) ? $_REQUEST["num"]:NULL;

   if (($num == 5) && ($num <> null)){
	   $URL="../index.php?num=5";
   }else{$URL="../index.php?num=50";}	

	$arr=array_keys($_SESSION); 
	$li_count=count($arr);
	  
	for($i=0;$i<$li_count;$i++)
	{
		$col=$arr[$i];
		unset($_SESSION["$col"]);
	}
	
	session_destroy();
	header("Location: $URL");
	exit;  
?>