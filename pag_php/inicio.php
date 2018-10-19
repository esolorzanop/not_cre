<?php session_start(); 
if($_SESSION['LOGIN'] == null) echo "<script>parent.window.location.href='../fun_php/salir.php';</script>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
<title>TEVCOL - Comprobantes de Depósito Digital</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!--<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>  
<script>
		$(document).ready(function(){
			cargarproductos();
		})

		$(function() {
		  var menues = $(".nav li"); 
			 menues.click(function(e) {
		     	menues.removeClass("active");
		     	$(this).addClass("active");
				e.preventDefault();
			  });
		});	

		function cargarproductos(){
			var url="compfact.php";
			$.post(url,function (responseText){
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
		  <a class="nav-link" href="javascript: void(0);" onClick="javascript:cargarproductos();"><strong>Administración de Facturas</strong></a>
	  </li>
      <li class="nav-item">
		  <a class="nav-link" href="javascript: void(0);" onClick="javascript:cargarusuarios();"><strong>Administración de Notas de Crédito</strong></a>
	  </li>
      <li class="nav-item">
		  <a class="nav-link" href="javascript: void(0);" onClick="javascript:window.location.href='../fun_php/salir.php'"><strong>Salir</strong></a>
	  </li>
    </ul>  
  </div>
</nav>
	
<!--<form id="form1" name="form1" method="post" autocomplete="off">-->
<div id="productos" class="container-fluid">
</div>
<!--</form>-->
</body>
</html>