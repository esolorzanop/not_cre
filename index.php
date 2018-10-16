<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>TEVCOL - Comprobantes de Depósito Digital</title>
<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" type="text/css" href="css/index.css">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<script>
function nuevo()
{
	document.ingreso.nombre.focus();
	
	if (document.ingreso.validar.value=="1")
	{ 
		document.ingreso.action="pag_php/inicio.php";
		document.ingreso.submit();	  
	}
	if (document.ingreso.validar.value=="4")
	{    
	   alert("Usuario Incorrecto...!");
	}
	if (document.ingreso.validar.value=="5")
	{   
	   alert("Clave Incorrecta...!");
	   document.ingreso.nombre.value = "<?php echo $_SESSION['LOGIN'];?>";
	   document.ingreso.clave.focus();
	}
	if (document.ingreso.validar.value=="8")
	{    
	    alert("Contraseña enviada por correo.\nSu clave de acceso al sistema se envio a la dirección de correo registrada para este usuario");
 	    document.ingreso.nombre.value = "<?php echo $_SESSION['LOGIN'];?>";
	    document.ingreso.clave.value="";   
	    document.ingreso.clave.focus();		
	}
	if (document.ingreso.validar.value=="50")
	{    
		alert('Usted ha salido del sistema.\nVuelva pronto.');
	} 
}

function sig(e)
{
  tecla = (document.all) ? e.keyCode :e.which;   
  if (tecla==13) 
     {
    document.ingreso.clave.focus();
  }
}
function sig2(e)
{
  tecla = (document.all) ? e.keyCode :e.which;   
  if (tecla==13) 
     {
    document.ingreso.ingresar.focus();
  }
}


function validar1()
{
	if(document.ingreso.nombre.value=="")
	  {  
	   alert("Ingrese su Nombre de usuario "); 
	   document.ingreso.nombre.focus();
	   return;
	  }
	   else{
			if(document.ingreso.clave.value=="")
	  		  {  
			   alert("Ingrese su Clave de Acceso "); 
	   		   document.ingreso.clave.focus();
			   return;
	  		  }	else{
					 document.ingreso.action="fun_php/validarusuario.php";					 
					 document.ingreso.submit();	  
			        }
  	  }
}

function validarrc()
{
	if(document.solicitud.usuario.value=="")
	  {  
	   alert("Atención!\nDebe ingresar su usuario\nPara poder recuperar su clave de acceso al sistema."); 
	   document.solicitud.usuario.focus();
	   return;
	  }
	   else{
      		 document.solicitud.action="fun_php/recuperarc.php";					 
		 document.solicitud.submit();	  
  	  }
}	

</script>
</head>
<body onLoad="nuevo();">
<div class="main">												
	<div class="container">
        <center>
        <div class="middle">
              <div class="logo">       
                  <div class="clearfix"></div>
              </div>      
              <div class="slogan">       
                  <div class="clearfix"></div>
                  "Líderes y Pioneros desde 1969® en Logística Integral de Valores"
              </div>                                 
              <div id="login">
                <form name="ingreso" id="ingreso" autocomplete="off" method="post">
                  <fieldset class="clearfix">
                    <p><span class="fa fa-user"></span><input name="nombre" type="text" required id="nombre"  placeholder="Usuario" onKeyPress="return sig(event);"></p>
                    <p><span class="fa fa-lock"></span><input name="clave" type="password" required id="clave"  placeholder="Clave" onKeyPress="return sig2(event);"></p>
                           <input name="validar" type="hidden" id="validar" value="<?php echo isset($_REQUEST["num"]) ? $_REQUEST["num"]:NULL; ?>">
                     <div>
                        <span style="width:48%; text-align:left;  display: inline-block;"><a class="small-text" href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block'">Olvido su clave? / Recuperela aquí</a></span>
                        <span style="width:50%; text-align:right;  display: inline-block;"><input name="ingresar" id="ingresar" type="button" value="Ingresar" onClick="validar1();"></span>
                     </div>
                  </fieldset>
                <div class="clearfix"></div>
                </form>
              </div> <!-- end login -->
        </div>
        </center>
	</div>
</div>
<div id="fade" class="overlay" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'"></div>
<div id="light" class="modal">
<form class="login" method="post" name="solicitud" id="solicitud" autocomplete="off">
  <table id="recupera" width="398px" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="25" valign="middle">
			<div align="center" style="padding-top:3px;"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#F9BF1D"><strong>Solicitud de envío de clave</strong></font></div></td>
      </tr>
      <tr>
        <td height="25" ><div style="padding:8px;"><font size="1" face="Verdana, Arial, Helvetica, sans-serif" color="#fff" ><strong>Escriba el nombre de usuario, y la contrase&ntilde;a ser&aacute; enviada al correo electr&oacute;nico registrado para ese usuario.</strong></font></div></td>
      </tr>
     <tr>
        <td height="10" >
		</td></tr>      
      <tr>
        <td><div align="center"><input id="usuario" name="usuario" type="text" autofocus onKeyPress="return event.keyCode!=13" placeholder="Usuario" /></div></td>
      </tr>
      <tr>
        <td align="center">
              <input name="grabar1" type="button" id="grabar1" onClick="validarrc();" value="Aceptar" />                
              <input name="cancelar1" type="button" id="cancelar1" value="Cancelar"  onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'" />
		</td>
      </tr>
  </table>   
</form>
</div>

</body>
</html>
