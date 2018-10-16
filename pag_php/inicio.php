<?php session_start(); 
if($_SESSION['LOGIN'] == null) echo "<script>parent.window.location.href='../fun_php/salir.php';</script>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
<title>TEVCOL - Comprobantes de Depósito Digital</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!--<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>  
<script>
		$(document).ready(function(){
			cargarproductos(1);
		})

		$(function() {
		  var menues = $(".nav li"); 
			 menues.click(function(e) {
		     	menues.removeClass("active");
		     	$(this).addClass("active");
				e.preventDefault();
			  });
		});	

		function cargarproductos(limite){
			var url="comprod.php";
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
<!--	<center><img src="../img/logo2.png" class="img-fluid" style="height: 100px; width: 450px; display: block;" alt="Responsive image"></center>	-->
<nav class="navbar  navbar-inverse" role="navigation">
<!--<nav class="navbar  navbar-inverse navbar-fixed-top" role="navigation">
  <!-- El logotipo y el icono que despliega el menú se agrupan
       para mostrarlos mejor en los dispositivos móviles -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse"
            data-target=".navbar-ex1-collapse">
      <span class="sr-only">Desplegar navegación</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
  </div>
 
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav">
      <li class="active"><a href="javascript: void(0)" onClick="javascript:cargarproductos(1);"><strong>Comprobantes de Depósitos Digitalizados</strong></a></li>
      <li><a href="javascript: void(0)" onClick="javascript:cargarusuarios();"><strong>Creación Usuarios del Sistema</strong></a></li>
      <li><a href="javascript: void(0)" onClick="javascript:window.location.href='../fun_php/salir.php'"><strong>Salir</strong></a></li>
    </ul>
  </div>
</nav>  
<!--<form id="form1" name="form1" method="post" autocomplete="off">-->
<div id="productos" class="container" style="margin-top:5px; height:100%; width:100%;">
</div>
<!--</form>-->
</body>
</html>