<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<style>
	.form-control[readonly] {background-color: #D6DBE0;  opacity: 1 !important;}
	
#wrapper{padding:5px 15px;}
.card
	{margin-bottom: 15px; border-radius:0; box-shadow: 0 3px 5px rgba(0,0,0,.1); background:#EBEBEB;}
.header-top
	{box-shadow: 0 3px 5px rgba(0,0,0,.1)}

	@media(min-width:800px) {
	   #wrapper{padding: 10px 5px 5px 5px; }
		.pass_show ptxt { 	
			right: 0px; }
}

.pass_show{position: relative} 

.pass_show .ptxt { 
		position: absolute; 
		top: 50%; 
		right: 15px; 
		z-index: 1; 
		color: #00A41E;/*#f36c01; */
		margin-top: -10px; 
		cursor: pointer; 
		transition: .3s ease all; 
} 

.ptxt { 
	font-weight: bold;
	}

.pass_show .ptxt:hover{color: #FF0004;} 	
</style>	

<div id="wrapper" class="animate">
  <div class="container-fluid">      
      <div class="row justify-content-md-center">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
				<center><h4><strong>CAMBIO DE CONTRASE&Ntilde;A</strong></h4></center>								
				<hr>
				<p class="text-center"><strong>Utilice el siguiente formulario para cambiar su contrase&ntilde;a.</strong></p>					
<form id="passwordForm" name="passwordForm" method="post" autocomplete="off" class="form-horizontal">
 <div style="align-items: center;">
	<div class="form-row">
		<div class="form-group col-md-1 center-text">
		</div>
		<div class="form-group col-md-11 pass_show">
		<input type="password" required class="input-md form-control col-md-10" name="password1" id="password1" placeholder="Digite Nueva Contrase&ntilde;a" autocomplete="off">
	</div></div>
</div>	
	<div class="form-row">
		<div class="form-group col-md-2 center-text">
		</div>
		<div class="form-group col-md-5 center-text">
			<i id="8char" class="fa fa-remove fa-lg" style="color:#FF0004;"></i> M&iacute;nimo 5 caracteres<br>
			<i id="ucase" class="fa fa-remove fa-lg" style="color:#FF0004;"></i> Una letra may&uacute;scula
		</div>
		<div class="form-group col-md-5 center-text">					
			<i id="lcase" class="fa fa-remove fa-lg" style="color:#FF0004;"></i> Una letra min&uacute;scula<br>
			<i id="num" class="fa fa-remove fa-lg" style="color:#FF0004;"></i> Un numero
		</div>
	</div>
					
	<div class="form-row">
		<div class="form-group col-md-1 center-text">
		</div>
		<div class="form-group col-md-11 pass_show">		
			<input type="password" required class="input-md form-control col-md-10" name="password2" id="password2" placeholder="Repita Nueva Contrase&ntilde;a" autocomplete="off">
		</div></div>
		
	<div class="form-row">
		<div class="form-group col-md-2 center-text">
		</div>	
		<div class="form-group col-md-10 center-text">					
			<i id="pwmatch" class="fa fa-remove fa-lg" style="color:#FF0004;"></i> Las contrase&ntilde;as deben coincidir<br> 
			<i class="fa fa-info-circle fa-lg" style="color:#286090;"></i> Si ingresa espacios en blanco no ser&aacute;n tomados en cuenta<br>
			<i class="fa fa-warning fa-lg" style="color:#286090;"></i> No se permiten claves que inicien con n&uacute;meros
		</div>	
	</div>	
<hr>
	<div class="form-row">
		<div class="form-group col-md-1 center-text">
		</div>
		<div class="form-group col-md-11 center-text">																			
			<button id="cambioc" name="cambioc" title="CAMBIE CONTRASE&Ntilde;A" class="col-md-5 btn btn-primary btn-load btn-md btn-warning" disabled>
			  	<i class="fa fa-key fa-lg "></i> <strong>CAMBIE CONTRASE&Ntilde;A</strong>
			  </button> 
			<a id="cancelar" name="cancelar" class="col-md-5 btn btn-primary btn-load btn-md btn-warning" href="javascript:null;"><i class="fa fa-remove fa-lg "></i> <strong>CANCELAR</strong></a>	
		</div>
	</div>
</form>		
	
	</div>
			  </div>
			</div>
		  </div>
	  </div>
		</div>	
<script>
var hab = 0;	
$("input[type=password]").keyup(function(){
    var ucase = new RegExp("[A-Z]+");
	var lcase = new RegExp("[a-z]+");
	var num = new RegExp("[0-9]+");	
	
	if($("#password1").val().length >= 5){
		$("#8char").removeClass("fa fa-remove fa-lg");
		$("#8char").addClass("fa fa-check fa-lg");
		$("#8char").css("color","#00A41E");
		hab = 1;
	}else{
		$("#8char").removeClass("fa fa-check fa-lg");
		$("#8char").addClass("fa fa-remove fa-lg");
		$("#8char").css("color","#FF0004");
		hab = 0;
	}
	
	if(ucase.test($("#password1").val())){
		$("#ucase").removeClass("fa fa-remove fa-lg");
		$("#ucase").addClass("fa fa-check fa-lg");
		$("#ucase").css("color","#00A41E");
		hab = 1;
	}else{
		$("#ucase").removeClass("fa fa-check fa-lg");
		$("#ucase").addClass("fa fa-remove fa-lg");
		$("#ucase").css("color","#FF0004");
		hab = 0;
	}
	
	if(lcase.test($("#password1").val())){
		$("#lcase").removeClass("fa fa-remove fa-lg");
		$("#lcase").addClass("fa fa-check fa-lg");
		$("#lcase").css("color","#00A41E");
		hab = 1;
	}else{
		$("#lcase").removeClass("fa fa-check fa-lg");
		$("#lcase").addClass("fa fa-remove fa-lg");
		$("#lcase").css("color","#FF0004");
		hab = 0;
	}
	
	if(num.test($("#password1").val())){
		$("#num").removeClass("fa fa-remove fa-lg");
		$("#num").addClass("fa fa-check fa-lg");
		$("#num").css("color","#00A41E");
		hab = 1;
	}else{
		$("#num").removeClass("fa fa-check fa-lg");
		$("#num").addClass("fa fa-remove fa-lg");
		$("#num").css("color","#FF0004");
		hab = 0;
	}
	
	if ($("#password1").val() != "" || $("#password2").val() != "") {
	if($("#password1").val() == $("#password2").val()){
		$("#pwmatch").removeClass("fa fa-remove fa-lg");
		$("#pwmatch").addClass("fa fa-check fa-lg");
		$("#pwmatch").css("color","#00A41E");
		hab = 1;
	}else{
		$("#pwmatch").removeClass("fa fa-check fa-lg");
		$("#pwmatch").addClass("fa fa-remove fa-lg");
		$("#pwmatch").css("color","#FF0004");
		hab = 0;
	}
	}
if (hab == 0){$("#cambioc").attr('disabled','disabled');}else{$("#cambioc").removeAttr('disabled');}		
});

$(document).ready(function(){
$('.pass_show').append('<span class="ptxt">Mostrar</span>');  
});
  

$(document).on('click','.pass_show .ptxt', function(){ 
	$(this).text($(this).text() == "Mostrar" ? "Ocultar" : "Mostrar"); 
	$(this).prev().attr('type', function(index, attr){return attr == 'password' ? 'text' : 'password'; }); 	
});  
	
$("#passwordForm").submit(function(e){
    //return false;
	e.preventDefault();
});	
	
$("#cancelar").click(function() {cargarcambio();});	
	
$("#cambioc").click(function() {
	document.passwordForm.action="../fun_php/cambiocv.php";
	document.passwordForm.submit();	
});		
</script>