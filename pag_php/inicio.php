<?php session_start(); 
if($_SESSION['LOGIN'] == null) echo "<script>parent.window.location.href='../fun_php/salir.php';</script>";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<title>TEVCOL - Comprobantes de Depósito Digital</title>  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">	
<style>
@charset "UTF-8";

html, body { 
	margin: 0; 
	padding: 0; 
	border: 0; 
	font-weight: inherit; 
	font-style: inherit; 
	font-size: 13px; 
	vertical-align: baseline; 
	font-family: Tahoma, Verdana;
}	
	
/*personalizar menu */
.navbar-dark .navbar-nav .nav-link {
color: orange !important;
}

.navbar-dark .navbar-nav .active>.nav-link  {
 color: orange !important;
 text-decoration: underline;
}

	
	
</style>

<!--<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>-->	
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>  	
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="../js/feather.min.js"></script>
<script src="../js/jquery.datetimepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css"/>	
<script>
		$(document).ready(function(){
			cargarproductos(1);			
		});
	
		function cargarproductos(limite){
			var url="compfact.php";
			$.post(url,{limite: limite},function (responseText){
				$("#productos").html(responseText);
			});
		}

		function cargarproductos1(limite){
			var url="compnotc.php";
			$.post(url,{limite: limite},function (responseText){
				$("#productos").html(responseText);
			});
		}	

		function cargarusuarios(){
			var url="creausu.php";
			$.post(url,function (responseText){
				$("#productos").html(responseText);
			});
		}	
</script>
</head>
<body style="background:url(../img/fondo.gif)">	
<center><img src="../img/logo.png" class="img-fluid" alt="TEVCOL CIA LTDA."></center>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #212529;">  
	<a class="navbar-brand" href="#"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menuPrin" aria-controls="menuPrin" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>  		
	<div class="collapse navbar-collapse" id="menuPrin">		
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
		  <a class="nav-link" href="javascript: void(0);" onClick="javascript:cargarproductos(1);"><strong>ADMINISTRACIÓN DE FACTURAS</strong></a>
	  </li>
      <li class="nav-item">
		  <a class="nav-link" href="javascript: void(0);" onClick="javascript:cargarproductos1(1);"><strong>ADMINISTRACIÓN DE NOTAS DE CRÉDITO</strong></a>
	  </li>
      <li class="nav-item">
		  <a class="nav-link" href="javascript: void(0);" onClick="javascript:window.location.href='../fun_php/salir.php'"><strong>SALIR</strong></a>
	  </li>
    </ul>  
  </div>
</nav>
<script>
	$('li.nav-item').click(function(){
    $('li.nav-item').removeClass("active");
    $(this).addClass("active");
});
</script>	
<!--<form id="form1" name="form1" method="post" autocomplete="off">-->

<div id="productos" class="container-fluid">
</div>

<!--</form>-->
</body>
</html>