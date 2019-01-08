<?php
session_start();
date_default_timezone_set('America/Guayaquil');	

if($_SESSION['LOGIN'] == null) echo "<script>parent.window.location.href='../fun_php/salir.php';</script>";

include("../fun_php/lib.php");
$con = conectar();

$where = '';
$accion = $_REQUEST['action'];

if ($accion <> ""){	
	if ($accion == 'fact_esta'){
		$fact_esta = $_REQUEST['f_est'];//estado
		$fact_come = strtoupper($_REQUEST['come']);//comentario
		$cod_factura = $_REQUEST['cod_factura'];//codigo factura	
		
		echo $accion." ".$fact_esta." ".$fact_come." ".$cod_factura;
			
		if ($fact_esta <> 0){
			$sql = 'update INTER.INVE_DOCUMENTOS_DAT I set fech_inve_aprue = sysdate, CODI_INVE_TIPO_EST = :p_estado, come_inve_est = :p_comentario where I.CODI_INVE_DOCU = :p_cod_fac AND CODI_INVE_TIPO_DOCU IN (\'FACTU\') and CODI_INVE_TIPO_EST > 0';		
			
			$sql_l = 'insert into INTER.INVE_DOCU_est_log (SELECT I.CODI_ADMI_EMPR_FINA, I.CODI_ADMI_PUNT_VENT, I.CODI_INVE_TIPO_DOCU, I.CODI_INVE_DOCU,I.CODI_INVE_TIPO_EST, I.COME_INVE_EST,I.FECH_INVE_APRUE FROM INTER.INVE_DOCUMENTOS_DAT I WHERE I.CODI_INVE_DOCU = :p_cod_fac AND CODI_INVE_TIPO_DOCU IN (\'FACTU\'))';
				
		}else{//cancelar solicitud
		$sql = 'update INTER.INVE_DOCUMENTOS_DAT I set fech_inve_aprue = null, CODI_INVE_TIPO_EST = :p_estado, come_inve_est = null where I.CODI_INVE_DOCU = :p_cod_fac AND CODI_INVE_TIPO_DOCU IN (\'FACTU\') and CODI_INVE_TIPO_EST > 0';					
		
		$sql_l = 'insert into INTER.INVE_DOCU_est_log (SELECT I.CODI_ADMI_EMPR_FINA, I.CODI_ADMI_PUNT_VENT, I.CODI_INVE_TIPO_DOCU, codi_inve_docu, codi_inve_tipo_est , \'SOLICITUD DE NC CANCELADA\' COME_INVE_EST, SYSDATE FECH_INVE_APRUE FROM INTER.INVE_DOCUMENTOS_DAT I WHERE I.CODI_INVE_DOCU = :p_cod_fac AND CODI_INVE_TIPO_DOCU IN (\'FACTU\'))';	
		}
	
		$stid = oci_parse($con, $sql);		
		
							oci_bind_by_name($stid, ':p_estado', $fact_esta);			
						if ($fact_esta <> 0){
							oci_bind_by_name($stid, ':p_comentario', $fact_come);		
						}
							oci_bind_by_name($stid, ':p_cod_fac', $cod_factura);
														
							$r = oci_execute($stid);
		
							if (!$r) {
									$e = oci_error($stid); 
									 $mensaje = "Ocurrió un error al intentar CAMBIAR ESTADO...!";								
								}							
		

		oci_free_statement($stid);	
		$stid = oci_parse($con, $sql_l);	
		
							oci_bind_by_name($stid, ':p_cod_fac', $cod_factura);
														
							$r = oci_execute($stid);
		
							if (!$r) {
									$e = oci_error($stid); 
									 $mensaje = "Ocurrió un error al intentar GRABAR LOG...!";
								}							
							oci_free_statement($stid);		
			echo $mensaje." ".$sql;
		//echo "<script>javascript:limpiarf();</script>";
	}
	
}

/*busqueda por numero de facturas*/
$b_nfacturas = $_REQUEST['b_nfacturas'];
if ($b_nfacturas<>''){
	$where_nf = " and CODI_INVE_DOCU like '%".$b_nfacturas."%'";
}else{$where_nf = '';}
/*********************************/


$where = $where_nf;


$sql = 'SELECT     
count(1) total
FROM INTER.INVE_DOCUMENTOS_DAT I
Where
CODI_ADMI_ESTA = \'O\' 
and CODI_ADMI_EMPR_FINA = \'00001\'
 AND CODI_ADMI_PUNT_VENT=\'101\'  
 AND CODI_INVE_TIPO_DOCU IN (\'FACTU\',\'REFAC\')'.$where;
 //AND CODI_INVE_TIPO_EST IN (1,2,3,4)---- LAS QUE DEBE CARGAR DE FAULT
//echo $sql."<br>";

	$rst = oci_parse($con, $sql);
	$r = oci_execute($rst);

	if (!$r) {
		$e = oci_error($rst); 
		echo "Ocurrió un error al verificar total detalle de solicitud NC...!";
	}

	$row = oci_fetch_array($rst, OCI_ASSOC);	

if($row['TOTAL'] == 0){	
	echo "<script>alert('Su busqueda no tiene resultados, intentelo nuevamente...!');limpiardf();</script>"; 
	//echo "<script>alert('Su busqueda no tiene resultados, intentelo nuevamente...!');</script>"; 	
}else{ 
?>
<style>
	.form-control[readonly] {background-color: #D6DBE0;  opacity: 1 !important;}
	
#wrapper{padding:5px 15px; }
.card
	{margin-bottom: 15px; border-radius:0; box-shadow: 0 3px 5px rgba(0,0,0,.1); background:#EBEBEB;}
.header-top
	{box-shadow: 0 3px 5px rgba(0,0,0,.1)}

	@media(min-width:800px) {
	   #wrapper{padding: 10px 5px 5px 5px; }
}

/*peronalizar paginacion */
.page-item.disabled .page-link { 
  color: #868e96; 
  pointer-events: none; 
  cursor: auto; 
  background-color: #D6DBE0; 
  border-color: #718393; 
}
  
.page-item.active .page-link, .page-link:focus, .page-link:hover { 
  z-index: 1; 
  color: #fff; 
  background-color: #212529; 
  border-color: #718393; 
}	

.page-link { 
  position: relative; 
  display: block; 
  padding: 0.5rem 0.75rem; 
  margin-left: -1px; 
  line-height: 1.25; 
  color: #000; 
  background-color: #EBEBEB; 
  border: 1px solid #718393; 
}	
	/*
	tablas con celdas de tamaño igual 
	table {
        table-layout: fixed;
        word-wrap: break-word;
    }

        table th, table td {
            overflow: hidden;
        }*/
a:link {
	color:#000;
	
}
a:visited {
	color: #000;
}
a:hover {
	color: orange;
	background-color: black;
	text-decoration: underline;
}
a:active {	
	text-decoration: underline;
}

.visited {	
	text-decoration: underline;
}		
</style>
<script>
function max2(txarea)
{
total = 150;
tam = txarea.value.length;
str="";
str=str+tam;
Digitado2.innerHTML = str;
Restante2.innerHTML = total - str;

if (tam > total){
aux = txarea.value;
txarea.value = aux.substring(0,total);
Digitado2.innerHTML = total
Restante2.innerHTML = 0
}
}
</script>

  <div id="wrapper" class="animate">
  <div class="container-fluid">      
      <div class="row justify-content-md-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
				<!--<center><h4><strong>DETALLE FACTURA A EMITIR NOTA DE CRÉDITO</strong></h4></center>-->
<?php 
	
	$sql = 'SELECT I.CODI_INVE_DOCU,
       TO_CHAR(I.FECH_INVE_DOCU,\'dd/mm/yyyy\') FECH_INVE_DOCU,
       I.REFE_INVE_DOC,
       (select  nomb_inve_Clie from inve_clientes_dat where codi_admi_empr_fina = \'00001\' and codi_admi_esta = \'A\' and codi_inve_clie = I.CODI_INVE_CLIE) nomb_inve_Clie,
       (select  iden_inve_clie from inve_clientes_dat where codi_admi_empr_fina = \'00001\' and codi_admi_esta = \'A\' and codi_inve_clie = I.CODI_INVE_CLIE) iden_inve_clie,
       I.COME_INVE_DOCU,
       TO_CHAR(I.IMPO_NETO_INVE_DOCU, \'FM999,999,999.90\') IMPO_NETO_INVE_DOCU,
       TO_CHAR(I.IMPO_IVA_INVE_DOCU, \'FM999,999,999.90\') IMPO_IVA_INVE_DOCU,
       TO_CHAR(I.IMPO_TOTA_INVE_DOCU, \'FM999,999,999.90\') IMPO_TOTA_INVE_DOCU,
       TO_CHAR(I.FECH_INVE_APRUE,\'dd/mm/yyyy\') FECH_INVE_APRUE,
       I.COME_INVE_EST,
	   I.CODI_INVE_TIPO_EST,
       (select  NOMB_INVE_TIPO_EST nomb_esta from INTER.INVE_TIPO_EST_REF WHERE CODI_INVE_TIPO_DOCU = \'FACTU\' AND CODI_INVE_TIPO_EST = I.CODI_INVE_TIPO_EST and est_inve_tipo_est = 1) estado_docu
  FROM INTER.INVE_DOCUMENTOS_DAT I
 WHERE     CODI_ADMI_ESTA = \'O\'
       AND CODI_ADMI_EMPR_FINA = \'00001\'
       AND CODI_ADMI_PUNT_VENT = \'101\'
       AND CODI_INVE_TIPO_DOCU IN (\'FACTU\', \'REFAC\')'.$where;
 //--AND CODI_INVE_TIPO_EST IN (1,2,3,4)---- LAS QUE DEBE CARGAR DE FAULT

	//echo $sql."<br>";
	
	$rst = oci_parse($con, $sql);
	$r = oci_execute($rst);

	if (!$r) {
		$e = oci_error($rst); 
		echo "Ocurrió un error al verificar detalle de solicitud NC...!";
	}

	$row = oci_fetch_array($rst, OCI_ASSOC);	

				
?>								
<!------------------------------------------------------------------------------>
				<center><h4><strong>DETALLE FACTURA QUE SOLICITAN EMITIR NOTA DE CRÉDITO</strong></h4></center>
<form id="form1" name="form1" method="post" autocomplete="off" class="form-horizontal">
<fieldset>
<legend><strong></strong></legend>
	<input name="cod_factura" id="cod_factura" type="hidden" value="<?php echo $row['CODI_INVE_DOCU']; ?>">
<div class="form-row">	
	<div class="form-group col-md-6">
		<label for="n_facturas"><strong>Factura No.</strong></label>
	   <input id="n_facturas" name="n_facturas" type="text" placeholder="Número de Factura" class="form-control" style="text-align: center;" <?php if ($row['REFE_INVE_DOC'] <> ''){ ?> readonly value="<?php echo $row['REFE_INVE_DOC']; ?>" <?php } ?> >    	
	</div>
	<div class="form-group col-md-3">
		<label for="fec_facturas"><strong>Fecha Documento</strong></label>
	   <input id="fec_facturas" name="fec_facturas" type="text" placeholder="Fecha Documento" class="form-control" style="text-align: center;" <?php if ($row['FECH_INVE_DOCU'] <> ''){ ?> readonly value="<?php echo $row['FECH_INVE_DOCU']; ?>" <?php } ?> >    	
	</div>	
	<div class="form-group col-md-3">
		<label for="fec_facturas_ap"><strong>Fecha Aprobación Solicitud</strong></label>
	   <input id="fec_facturas_ap" name="fec_facturas_ap" type="text" placeholder="__/__/____" class="form-control" style="text-align: center;" readonly <?php if ($row['FECH_INVE_APRUE'] <> ''){ ?> value="<?php echo $row['FECH_INVE_APRUE']; ?>" <?php } ?> >    	
	</div>	
</div>	
<div class="form-row">	
	<div class="form-group col-md-9">
		<label for="cliente"><strong>Nombre Cliente</strong></label>
		<input id="cliente" name="cliente" type="text"  placeholder="Nombre de Cliente" class="form-control" <?php if ($row['NOMB_INVE_CLIE']<> ''){ ?> readonly value="<?php echo $row['NOMB_INVE_CLIE']; ?>" <?php } ?>>    			
	</div>		
	<div class="form-group col-md-3">
		<label for="r_cliente"><strong>RUC Cliente</strong></label>
		<input id="r_cliente" name="r_cliente" type="text"  placeholder="RUC Cliente" class="form-control" style="text-align: center;" <?php if ($row['IDEN_INVE_CLIE'] <> ''){ ?> readonly value="<?php echo $row['IDEN_INVE_CLIE']; ?>" <?php } ?>>    	
	</div>
</div>	
	<hr>
<?php 
										switch ($row['CODI_INVE_TIPO_EST']){
											case 1:
												$clase = ' badge  badge-pill badge-danger';
											break;
												
											case 2:
												$clase = ' badge  badge-pill badge-warning';
											break;
												
											case 3:
												$clase = ' badge  badge-pill badge-success"';
											break;
												
											case 4:
												$clase = ' badge  badge-pill badge-dark"';
											break;
												
											case 5:
												$clase = ' badge  badge-pill badge-info"';
											break;	
												
											default:
												$clase = '';
											break;												
										}
	
	?>	
<div class="form-row">	
	 <div class="form-group col-md-8">
		 <label for="come_docu"><strong>Estado Documento</strong></label><br>
		 <h5><strong class="<?php echo $clase; ?>"><?php echo $row['ESTADO_DOCU']; ?></strong></h5>
		<!--<textarea class="form-control <?php echo $clase; ?>" id="est_docu" name="est_docu" rows="2" style="text-align: justify;" <?php //if ($row['ESTADO_DOCU'] <> ''){ ?> readonly<?php //} ?> ><?php //echo $row['ESTADO_DOCU']; ?></textarea>-->
	  </div>
</div>	
<div class="form-row">	
	 <div class="form-group col-md-8">
		 <label for="come_docu"><strong>Comentario Documento</strong></label>
		<textarea class="form-control" id="come_docu" name="come_docu" rows="6" style="text-align: justify;" <?php if ($row['COME_INVE_DOCU'] <> ''){ ?> readonly<?php } ?> ><?php echo $row['COME_INVE_DOCU']; ?></textarea>
	  </div>
	<div class="form-group col-md-1"></div>
	<div class="form-vertical col-md-3">		
		<label for="val_facturas"><strong>Valor Facturado</strong></label>
	   <input id="val_facturas" name="val_facturas" type="text" placeholder="Valor Facturado" class="form-control" style="text-align: right;" <?php if ($row['IMPO_NETO_INVE_DOCU'] <> ''){ ?> readonly value="<?php echo $row['IMPO_NETO_INVE_DOCU']; ?>" <?php } ?> >    		
		   <label for="iva_facturas"><strong>IVA Facturado</strong></label>
	   <input id="iva_facturas" name="iva_facturas" type="text" placeholder="Valor Facturado" class="form-control" style="text-align: right;" <?php if ($row['IMPO_NETO_INVE_DOCU'] <> ''){ ?> readonly value="<?php echo $row['IMPO_IVA_INVE_DOCU']; ?>" <?php } ?> >    		
		   <label for="tot_facturas"><strong>ToTal Facturado</strong></label>
	   <input id="tot_facturas" name="tot_facturas" type="text" placeholder="Valor Facturado" class="form-control" style="text-align: right;" <?php if ($row['IMPO_TOTA_INVE_DOCU'] <> ''){ ?> readonly value="<?php echo $row['IMPO_TOTA_INVE_DOCU']; ?>" <?php } ?> >    				
	</div>	
</div>	
	<hr>
<div class="form-row">		
	<div class="form-group col-md-8">
		 <label for="come_docu"><strong>Comentario Pre-aprobación Documento</strong></label>
		<textarea class="form-control" id="come_apdocu" name="come_apdocu" rows="4" style="text-align: justify;" onKeyUp="max2(this)" onKeyPress="max2(this)" <?php if ($row['COME_INVE_EST'] <> ''){ ?> readonly<?php } ?> ><?php echo $row['COME_INVE_EST']; ?></textarea>
		<div align="center"><strong><font id="Digitado2" color="#429F00">0</font><font color="#444444"> Caracteres digitados / Restan </font><font id="Restante2" color="#429F00">150</font></strong></div>
	  </div>	
 	
	<div class="form-group col-md-1">		  		  
		  <button id="b_back" name="b_back" title="Regresar a Listado de Facturas" class="btn btn-default btn-lg btn-warning" onClick="javascript:limpiarf();">
			  	<i data-feather="arrow-left"></i><script>feather.replace();</script>
			  </button> 			
	</div>
	<?php if (($_SESSION['TIPO_USU'] == 2)||($_SESSION['TIPO_USU'] == 3)){ 
					$mensaje_ne = "Negar Emisión de NC";					
					$oculto2 = '';
					
				if ($_SESSION['TIPO_USU'] == 2){
					if ($row['COME_INVE_EST'] <> ''){ 
						$oculto = 'hidden';
					}else{
						$oculto = '';
					}
					$mensaje_ap = "Pre-Aprobar Emisión de NC";
				}else{
					if ($row['COME_INVE_EST'] == ''){ 
						$oculto = 'hidden';
					}else{
						$oculto = '';
					}					
					$mensaje_ap = "Aprobar Emisión de NC";
					
					if (($row['CODI_INVE_TIPO_EST'] == '3')||($row['CODI_INVE_TIPO_EST'] == '4')||($row['CODI_INVE_TIPO_EST'] == '5')){ //emision de nota de credito negada o preaprobada
						$oculto = 'hidden';
						if ($row['CODI_INVE_TIPO_EST'] == '5')
						{$oculto2 = 'hidden';}
					}
					
				}
	?>
	<div class="form-group col-md-1" <?php echo $oculto; ?>	>	  		  
			  <button id="b_aprobar" name="b_aprobar" title="<?php echo $mensaje_ap; ?>" class="btn btn-default btn-lg btn-warning">
					<i data-feather="check"></i><script>feather.replace();</script>
				  </button> 						  		  
		</div>			
	<div class="form-group col-md-1"<?php echo $oculto; ?>	>	  		  
			  <button id="b_negar" name="b_negar" title="<?php echo $mensaje_ne; ?>" class="btn btn-default btn-lg btn-warning">
					<i data-feather="x"></i><script>feather.replace();</script>
				  </button> 					
		</div>
	<div class="form-group col-md-1"<?php echo $oculto2; ?>	>	  		  
			  <button id="b_cancelar" name="b_cancelar" title="Cancelar Solicitud de NC" class="btn btn-default btn-lg btn-warning">
					<i data-feather="x-circle"></i><script>feather.replace();</script>
				  </button> 					
		</div>				
	
	<?php } ?>
</div>		
	
</fieldset>
</form>					  
<script>
$("#b_aprobar").click(function() {  
	if ($("#come_apdocu").val()!=""){
<?php
if (($_SESSION['TIPO_USU'] == 2)||($_SESSION['TIPO_USU'] == 3)){ 
				if ($_SESSION['TIPO_USU'] == 2){
?>					
			grabrarf(2);	//preaprobada emision de nota de credito
<?php		
				}else{
?>					
			grabrarf(3);	//aprobada emision de nota de credito
<?php		
				}
}
	?>				
	}else{
		alert('Debe escribir su comentario, para Pre-Aprobar la emisión del documento.');
		$("#come_apdocu").focus();
	}	
});	
$("#b_negar").click(function() {  
	if ($("#come_apdocu").val()!=""){
		grabrarf(4);	//emision de nota de credito negada o no preaprobada
	}else{
		alert('Debe escribir su comentario, para Negar la emisión del documento.');
		$("#come_apdocu").focus();
	}	
});
$("#b_cancelar").click(function() {  
	alert('Atención! se cancela la solicitud del documento.');
	grabrarf(0);	//cancelar solicitud de NC
	/*if ($("#come_apdocu").val()!=""){
		grabrarf(0);	//cancelar solicitud de NC
	}else{
		alert('Debe escribir su comentario, para cancelar la solicitud del documento.');
		$("#come_apdocu").focus();
	}*/	
});		
$("#form1").submit(function(e){
    //return false;
	e.preventDefault();
});		
</script>				
<!------------------------------------------------------------------------------>				
<?php 
}
?>