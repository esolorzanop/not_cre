<?php
session_start();
date_default_timezone_set('America/Guayaquil');	

if($_SESSION['LOGIN'] == null) echo "<script>parent.window.location.href='../fun_php/salir.php';</script>";

include("../fun_php/lib.php");
$con = conectar();

$where = '';
$accion = $_REQUEST['action'];

/*busqueda por numero de facturas*/
$b_nfacturas = $_REQUEST['b_nfacturas'];
if ($b_nfacturas<>''){
	$where_nf = " and REFE_INVE_DOC like '%".$b_nfacturas."%'";
}else{$where_nf = '';}
/*********************************/

/*busqueda por nombre de cliente*/
$cod_cliente = isset($_REQUEST['cod_cliente']) ? $_REQUEST['cod_cliente']:$_SESSION['cod_cliente'];
$b_cliente = isset($_REQUEST['b_cliente']) ? $_REQUEST['b_cliente']:$_SESSION['b_cliente'];
	if ($b_cliente<>''){
		$where_cl = " and codi_inve_clie = ".$cod_cliente;
		$_SESSION['cod_cliente'] = $cod_cliente;
		$_SESSION['b_cliente'] = $b_cliente;
	}else{
		$where_cl = $_SESSION['cod_cliente'] = $_SESSION['b_cliente'] = '';
		 }
/*********************************/

/*busqueda por estado factura*/
$b_estadof = isset($_REQUEST['b_estadof']) ? $_REQUEST['b_estadof']:$_SESSION['b_estadof'];
	if (($b_estadof<>'-1000')&&($b_estadof<>'')){
		$where_ef = " and CODI_INVE_TIPO_EST = ".$b_estadof;
		$_SESSION['b_estadof'] = $b_estadof;
	}else{
		$where_ef = " and CODI_INVE_TIPO_EST in (1,2,3)";
		$b_estadof = $_SESSION['b_estadof'] = '';
		if($b_nfacturas<>'')
		{$where_ef = '';}
		 }
/*********************************/

/*busqueda por fecha*/
$b_dfecha = isset($_REQUEST['b_dfecha']) ? $_REQUEST['b_dfecha']:$_SESSION['b_dfecha'];
$b_hfecha = isset($_REQUEST['b_hfecha']) ? $_REQUEST['b_hfecha']:$_SESSION['b_hfecha'];
	if ((($b_dfecha <> '__/__/____')&&($b_hfecha <> '__/__/____'))&&(($b_dfecha <> '')&&($b_hfecha <> ''))){
		$where_fec = "AND to_date(to_char(FECH_INVE_DOCU, 'dd/mm/yyyy'), 'dd/mm/yyyy')  between to_date('".$b_dfecha."','dd/mm/yyyy') and to_date('".$b_hfecha."','dd/mm/yyyy')";
		$_SESSION['b_dfecha'] = $b_dfecha;
		$_SESSION['b_hfecha'] = $b_hfecha;
	}else{
		$where_fec = $_SESSION['b_dfecha'] = $_SESSION['b_hfecha'] = '';
		 }		
/*********************************/



if ($accion == 'refrescar'){
	$b_nfacturas = $b_cliente = $cod_cliente = $b_estadof = $b_dfecha = $b_hfecha = '';
	$where_nf = $where_cl = $where_fec = '';	
	$_SESSION['cod_cliente'] = $_SESSION['b_cliente'] = $_SESSION['b_estadof'] = $_SESSION['b_dfecha'] = $_SESSION['b_hfecha'] = '';	
	$where_ef = " and CODI_INVE_TIPO_EST in (1,2,3)";
}

$where = $where_nf.$where_cl.$where_ef.$where_fec;


$sql = 'SELECT count(1) total FROM INTER.INVE_DOCUMENTOS_DAT Where CODI_ADMI_ESTA = \'O\' AND CODI_ADMI_EMPR_FINA = \'00001\' AND CODI_ADMI_PUNT_VENT = \'101\' AND CODI_INVE_TIPO_DOCU IN (\'FACTU\',\'REFAC\') AND CODI_INVE_TIPO_EST > 0'.$where;
//echo $sql."<br>";

	$rst = oci_parse($con, $sql);
	$r = oci_execute($rst);

	if (!$r) {
		$e = oci_error($rst); 
		echo "Ocurrió un error al verificar total de solicitud NC...!";
	}

	$row = oci_fetch_array($rst, OCI_ASSOC);	

if($row['TOTAL'] == 0){	
	//echo "<script>alert('Su busqueda no tiene resultados, intentelo nuevamente...!');limpiarf();</script>"; 
	echo "<script>alert('Su busqueda no tiene resultados que mostrar, intentelo nuevamente en pocos minutos o espere notificacion vía e-mail...!');limpiarf();</script>"; 	
}else{ 
	if(isset($_POST['limite'])){
		$limit = $_POST['limite'];
		$_SESSION['limit'] = $_POST['limite'];
	}else{
		$limit = 1;
		$_SESSION['limit'] = 1;
	}
			
	$limite = ceil($row['TOTAL'] / 10);
	$n_page_number = $limit; 
	$n_page_size = 10;
?>
<style>
input,textarea[disabled]{background-color: #fff; opacity: 1 !important;}
	
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

  <div id="wrapper" class="animate">
    <div class="container-fluid">      
      <div class="row justify-content-md-center">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
				<center><h4><strong>LISTADO FACTURAS QUE SOLICITAN EMITIR NOTAS DE CRÉDITO</strong></h4></center>
<!------------------------------------------------------------------------------>
<form id="form1" name="form1" method="post" autocomplete="off" class="form-horizontal">
<fieldset>
<!--<legend>Búsqueda de Facturas</legend>-->
	<input name="action" id="action" type="hidden" value="<?php echo $accion; ?>">
<hr>
<div class="form-row">	
	<div class="form-group col-md-2">
		<label for="b_nfacturas"><strong>Factura</strong></label>
	   <input id="b_nfacturas" name="b_nfacturas" type="text" placeholder="Número de Factura" class="form-control"  data-mask="000-000-000000000" data-mask-clearifnotmatch="true" <?php if ($b_nfacturas <> ''){ ?> readonly value="<?php echo $b_nfacturas; ?>" <?php } ?> >    	
	</div>
	<div class="form-group col-md-2">
		<label for="b_cliente"><strong>Cliente</strong></label>
		<input id="b_cliente" name="b_cliente" type="text"  placeholder="Nombre de Cliente" class="form-control" <?php if ($b_cliente <> ''){ ?> readonly value="<?php echo $b_cliente; ?>" <?php } ?>>    	
		<input name="cod_cliente" type="hidden" id="cod_cliente" <?php if ($cod_cliente <> ''){ ?> readonly value="<?php echo $cod_cliente; ?>" <?php } ?>>
	</div>
	<?php
	$sql_est = 'select CODI_INVE_TIPO_EST cod_esta, NOMB_INVE_TIPO_EST nomb_esta from INTER.INVE_TIPO_EST_REF WHERE CODI_INVE_TIPO_DOCU = \'FACTU\' AND CODI_INVE_TIPO_EST > 0';
//echo $sql_est."<br>";

	$rst_est = oci_parse($con, $sql_est);
	$r = oci_execute($rst_est);

	if (!$r) {
		$e = oci_error($rst_est); 
		echo "Ocurrió un error al verificar estados...!";
	}
	
	?>
	<div class="form-group col-md-2">
		<label for="b_estadof"><strong>Estado</strong></label>
		<select id="b_estadof" name="b_estadof" class="form-control">
		  <option value="-1000">:: Seleccione Estado ::</option>
			<?php
				while($row_est = oci_fetch_array($rst_est, OCI_ASSOC))
						{
							if ($row_est['COD_ESTA'] == $_SESSION['b_estadof']) 
									$valor = "selected='selected'";
								else
									$valor = "";
					
								echo "<option value='".$row_est['COD_ESTA']."' $valor>".htmlentities($row_est['NOMB_ESTA'])."</option>\n";
						}
			?>
		</select>
	</div>
	<div class="form-group col-md-2">
		<label for="b_dfecha"><strong>Fecha Inicial</strong></label>
		  <div class="input-group">
			  <input id="b_dfecha" name="b_dfecha" class="form-control" placeholder="Fecha" type="text" <?php if (($b_dfecha <> '__/__/____')&&($b_dfecha <> '')){ ?> readonly value="<?php echo $b_dfecha; ?>" <?php } ?>>
			  <i data-feather="calendar"></i><script>feather.replace()</script>			  
		  </div>	  
	</div>
	<div class="form-group col-md-2">
		<label for="b_dfecha"><strong>Fecha Final</strong></label>
		  <div class="input-group">		  	
			  <input id="b_hfecha" name="b_hfecha" class="form-control" placeholder="Fecha" type="text"  <?php if (($b_hfecha <> '__/__/____')&&($b_hfecha <> '')){ ?> readonly value="<?php echo $b_hfecha; ?>" <?php } ?>>
			  <i data-feather="calendar"></i><script>feather.replace()</script>			  
		  </div>	  
	</div>	
	<script>
		
 $('#b_dfecha').datetimepicker({
  lang:'es',
 closeOnWithoutClick :true,
 closeOnDateSelect: true,	 
		dayOfWeekStart : 1,
		format:'d/m/Y',
		//format:'d/m/Y H:i',	 
		//formatTime:'H:i',
		formatDate:'d.m.Y',
		mask:'39/19/9999',
	 	//mask:'39/19/9999 29:59',	 
		timepickerScrollbar:false,
		timespan: 8.00-17.00,
		step:5,	  
onShow:function( ct ){
   this.setOptions({
    maxDate:$('#b_dfecha').val()?$('#b_dfecha').val():false
   })
  },		
		timepicker:false

 });
		
 $('#b_hfecha').datetimepicker({
  lang:'es',
 closeOnWithoutClick :true,
 closeOnDateSelect: true, 	 
		dayOfWeekStart : 1,
		format:'d/m/Y',
		//format:'d/m/Y H:i',	 
		//formatTime:'H:i',
		formatDate:'d.m.Y',
		mask:'39/19/9999',
	 	//mask:'39/19/9999 29:59',	 
		timepickerScrollbar:false,
		timespan: 8.00-17.00,
		step:5,	  
  onShow:function( ct ){
   this.setOptions({
    minDate:$('#b_dfecha').val()?$('#b_dfecha').val():false,
	maxDate:new Date()
   })
  },
  timepicker:false
 });
</script>
	
	
	<div class="form-group col-md-2">		  		  
		  <button id="b_busqueda" name="b_busqueda" title="Buscar Datos" class="btn btn-default btn-lg btn-warning">
			  	<i data-feather="search"></i><script>feather.replace();</script>
			  </button> 			
		  <button id="b_earchivo" name="b_earchivo" title="Enviar Datos Hacia Archivo Excel" class="btn btn-default btn-lg btn-warning" onclick="window.location='../fun_php/reporte_001.php'">
			  	<i data-feather="file-text"></i><script>feather.replace();</script>
			  </button> 						  		  
		  <button id="b_limpiar" name="b_limpiar" title="Refrescar Página" class="btn btn-default btn-lg btn-warning">
			  	<i data-feather="refresh-cw"></i><script>feather.replace();</script>
			  </button> 					
	</div>		
</div>	

</fieldset>
</form>			
<span class="clearfix"></span>				
<!------------------------------------------------------------------------------>
			<div class="table-responsive">
              <table class="table table-md table-striped">
                <thead class="table-dark">
                  <tr align="center">                    
					<th style="width: 12%"># FACTURA</th>                     
					<th style="width: 10%">FECHA</th> 					  
					<th style="width: 13%">CLIENTE</th> 
                    <th style="width: 20%">COMENTARIO</th>
					<th style="width: 10%">TOTAL</th>
					<th style="width: 11%">FECHA AUTORIZA NC</th>					  
                    <th style="width: 15%">COMENTARIO AUTORIZA NC</th>
                    <th style="width: 9%">ESTADO DOCUMENTO</th>                  
                  </tr>
                </thead>
<?php
	$sql = 'SELECT * FROM (SELECT ROWNUM AS FILA, CONSULTA.* FROM (SELECT rownum ROW_NUMBER, CODI_INVE_DOCU, FECH_INVE_DOCU, 
         TO_CHAR (FECH_INVE_DOCU, \'DD/MM/YYYY\') FECH_INVE_DOCU1, I.REFE_INVE_DOC, I.CODI_INVE_CLIE, I.COME_INVE_DOCU, TO_CHAR(IMPO_TOTA_INVE_DOCU, \'FM999,999,999.90\') IMPO_TOTA_INVE_DOCU, to_char(FECH_INVE_APRUE, \'DD/MM/YYYY\') FECH_INVE_APRUE, I.COME_INVE_EST, I.CODI_INVE_TIPO_EST FROM INTER.INVE_DOCUMENTOS_DAT I Where CODI_ADMI_ESTA = \'O\' AND CODI_ADMI_EMPR_FINA = \'00001\' AND CODI_ADMI_PUNT_VENT = \'101\' AND CODI_INVE_TIPO_DOCU IN (\'FACTU\',\'REFAC\') AND CODI_INVE_TIPO_EST > 0 '.$where.' ORDER BY FECH_INVE_DOCU DESC) CONSULTA) WHERE FILA >= (((:n_page_number-1) * :n_page_size) + 1) AND FILA < ((:n_page_number * :n_page_size) + 1 )';// AND CODI_INVE_TIPO_EST IN (1,2,3,4) LAS QUE DEBE CARGAR DE FAULT
									
	$_SESSION['sql_rpt'] = "";
	$_SESSION['sql_rpt'] = "SELECT rownum ROW_NUMBER, CODI_INVE_DOCU, FECH_INVE_DOCU, 
         TO_CHAR (FECH_INVE_DOCU, 'DD/MM/YYYY') FECH_INVE_DOCU1, I.REFE_INVE_DOC, I.CODI_INVE_CLIE, I.COME_INVE_DOCU, TO_CHAR(IMPO_TOTA_INVE_DOCU, 'FM999,999,999.90') IMPO_TOTA_INVE_DOCU, to_char(FECH_INVE_APRUE, 'DD/MM/YYYY') FECH_INVE_APRUE, I.COME_INVE_EST, I.CODI_INVE_TIPO_EST FROM INTER.INVE_DOCUMENTOS_DAT I Where CODI_ADMI_ESTA = 'O' AND CODI_ADMI_EMPR_FINA = '00001' AND CODI_ADMI_PUNT_VENT = '101' AND CODI_INVE_TIPO_DOCU IN ('FACTU','REFAC') AND CODI_INVE_TIPO_EST > 0 $where ORDER BY FECH_INVE_DOCU DESC";
	
	$rst = oci_parse($con, $sql);
	oci_bind_by_name($rst, ':n_page_number', $n_page_number);	
	oci_bind_by_name($rst, ':n_page_size', $n_page_size);
	
	//echo $sql;
	//echo $_SESSION['sql_rpt'];
	
	$r = oci_execute($rst);
	
	if (!$r) {
		$e = oci_error($rst); 
/*	    print htmlentities($e['message']);
		print "\n<pre>\n";
		print htmlentities($e['sqltext']);
		printf("\n%".($e['offset']+1)."s", "^");
		print  "\n</pre>\n";*/
		echo "Ocurrió un error al verificar facturas...!";
	}
	
	$productos="";
	while($row = oci_fetch_array($rst, OCI_ASSOC))
	{
		$productos[$row['ROW_NUMBER']]['CODIGO_FAC'] = $row['CODI_INVE_DOCU'];								
		$productos[$row['ROW_NUMBER']]['NUMERO_FAC'] = $row['REFE_INVE_DOC'];	
		$productos[$row['ROW_NUMBER']]['FECHA_FAC'] = $row['FECH_INVE_DOCU1'];	
		
		$sql_cl = 'select nomb_inve_clie from inve_clientes_dat where codi_admi_empr_fina = \'00001\' and codi_admi_esta = \'A\' and codi_inve_clie ='.$row['CODI_INVE_CLIE'];
		//echo $sql_cl;		  

				$rst_cl = oci_parse($con, $sql_cl);
				$r = oci_execute($rst_cl);

				if (!$r) {
					$e = oci_error($rst_cl); 
					echo "Ocurrió un error al verificar cliente...!";
				}
		$row_cl = oci_fetch_array($rst_cl, OCI_ASSOC);
		$productos[$row['ROW_NUMBER']]['CLIENTE'] = $row_cl['NOMB_INVE_CLIE'];
		
		$productos[$row['ROW_NUMBER']]['DETALLE_FAC'] = $row['COME_INVE_DOCU'];			
		$productos[$row['ROW_NUMBER']]['TOTAL_FACTURA'] = $row['IMPO_TOTA_INVE_DOCU'];						
		$productos[$row['ROW_NUMBER']]['FECHA_APNC'] = $row['FECH_INVE_APRUE'];						
		$productos[$row['ROW_NUMBER']]['DETALLE_APNC'] = $row['COME_INVE_EST'];
		$productos[$row['ROW_NUMBER']]['COD_ESTADO'] = $row['CODI_INVE_TIPO_EST'];
		
		$sql_est = 'select NOMB_INVE_TIPO_EST from INTER.INVE_TIPO_EST_REF WHERE CODI_INVE_TIPO_DOCU = \'FACTU\' AND CODI_INVE_TIPO_EST = '.$row['CODI_INVE_TIPO_EST'];
		//echo $sql_cl;		  

				$rst_est = oci_parse($con, $sql_est);
				$r = oci_execute($rst_est);

				if (!$r) {
					$e = oci_error($rst_est); 
					echo "Ocurrió un error al verificar cliente...!";
				}
		$row_est = oci_fetch_array($rst_est, OCI_ASSOC);		
		$productos[$row['ROW_NUMBER']]['ESTADO_APNC'] = $row_est['NOMB_INVE_TIPO_EST'];						
		}		
?>                
                <tbody align="center">
<?php
			foreach ($productos as $v1) {
					echo '<tr>';									
					foreach ($v1 as $v2=>$value) {
 																				
							switch ($v2) {															
							
								case "CODIGO_FAC":
										$codigo_fac = "";
										$codigo_fac = $value;
								break;				

								case "NUMERO_FAC":
								echo "<th scope=\"row\"><a href=\"#\" onclick=\"javascript:funIrpag('$codigo_fac');\">".$value."</a></th>";
								break;																						

								case "COD_ESTADO":
										switch ($value){
											case 1:
												$clase = 'class = "badge  badge-pill badge-danger"';
											break;
												
											case 2:
												$clase = 'class = "badge  badge-pill badge-warning"';
											break;
												
											case 3:
												$clase = 'class = "badge  badge-pill badge-success"';
											break;
												
											case 4:
												$clase = 'class = "badge  badge-pill badge-dark"';
											break;
												
											case 5:
												$clase = 'class = "badge  badge-pill badge-info"';
											break;	
												
											default:
												$clase = '';
											break;												
										}
								break;	

								case "DETALLE_FAC":
								echo "<td align=\"justify\">".$value."</td>";
								break;										

								case "ESTADO_APNC":
								echo "<td align=\"justify\" ".$clase.">".$value."</td>";
								break;																	
									
								default:
								echo "<td>".$value."</td>";
								break;								
							}
					
					}
					echo '</tr>'; 
				}
	
?>                
                </tbody>
              </table>
			  </div>	
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<nav aria-label="">	
  <ul class="pagination pagiantion-sm flex-wrap justify-content-center">
<?php 
$total_paginas = $limite;
  $actual = $n_page_number;
  $maxpags = 16;
  
  $anterior = $actual - 1;
  $posterior = $actual + 1;
  $minimo = $maxpags ? max(1, $actual-ceil($maxpags/2)): 1;
  $maximo = $maxpags ? min($total_paginas, $actual+floor($maxpags/2)): $total_paginas;
   
  if ($actual >1) 
  	{
	  $limit = $n_page_number - 1;
	  echo "<li class=\"page-item\">
      <a class=\"page-link\" href=\"javascript:void(0)\" onclick=\"javascript:cargarproductos(".$limit.")\">
	    <span aria-hidden=\"true\">&laquo; Anterior</span><span class=\"sr-only\">Anterior</span>
      </a>
    </li>";
  }
  else	
  	{
 	 echo "<li class=\"page-item disabled\">
      <a class=\"page-link\" href=\"#\" tabindex=\"-1\">
	    <span aria-hidden=\"true\">&laquo; Anterior</span><span class=\"sr-only\">Anterior</span>
      </a>
    </li>";
	}
  
  if ($minimo!=1){	
	  echo "<li class=\"page-item\"><a class=\"page-link\" href=\"javascript:void(0);\" onclick=\"javascript:cargarproductos(1)\">...</a></li>";
  } 

	for ($i=$minimo; $i<=$actual; $i++)
	if ($i == $actual) {
			echo "<li class=\"page-item active\"><span class=\"page-link\" >".$i."</span></li>";
			}else
  	{
		echo "<li class=\"page-item\"><a class=\"page-link\" href=\"javascript:void(0);\" onclick=\"javascript:cargarproductos(".$i.")\">".$i."</a></li>";
	}
  for ($i=$actual+1; $i<=$maximo; $i++)
	{
	  echo "<li class=\"page-item\"><a class=\"page-link\" href=\"javascript:void(0);\" onclick=\"javascript:cargarproductos(".$i.")\">".$i."</a></li>";
	}
	
	if ($maximo!=$total_paginas){
		 echo "<li class=\"page-item\"><a class=\"page-link\" href=\"javascript:void(0);\" onclick=\"javascript:cargarproductos(".$total_paginas.")\">...</a></li>";
	}
	 
	if($n_page_number < $limite){
				$limit = $n_page_number + 1;
				  echo "<li class=\"page-item\">
				  <a class=\"page-link\" href=\"javascript:void(0)\" onclick=\"javascript:cargarproductos(".$limit.")\">
					<span aria-hidden=\"true\">Siguiente &raquo;</span><span class=\"sr-only\">Siguiente</span>
				  </a>
				</li>";		
			}else{
				 echo "<li class=\"page-item disabled\">
				  <a class=\"page-link\" href=\"#\">
					<span aria-hidden=\"true\">Siguiente &raquo;</span><span class=\"sr-only\">Siguiente</span>
				  </a>
				</li>";				
			}	  
?>	  
  </ul>
</nav>

<link rel="stylesheet" href="../css/jquery.autocomplete.css">
<script src="../js/jquery.autocomplete.js" type="text/javascript"></script>
<script src="../js/jquery.mask.js" type="text/javascript"></script>
<script>
$('#b_cliente').autocomplete({
valueKey:'label',
source:[{
	url:"../fun_php/busDat.php?f=cli&q=%QUERY%",
	type:'remote',
	getValueFromItem:function(item){
		return item;
	},
	ajax:{
		dataType : 'jsonp'	
	}
		}]
});
$('#b_cliente').on('selected.xdsoft', function(event, item){
$("#cod_cliente").val(item.value);
});	
$("#b_cliente").keypress(function(e) {
  if(e.keyCode == 13)
     {
	        e.preventDefault();
  		//	$("#busCiudad").prop('disabled', false);
			//$("#busCiudad").focus();	       
			//$("#b_cliente").prop('disabled', true);
         $(this).autocomplete('close');
     }
});
	
$("#b_nfacturas").keypress(function(e) {
  if(e.keyCode == 13)
     {
	        e.preventDefault();
  		//	$("#busCiudad").prop('disabled', false);
			//$("#busCiudad").focus();	       
			//$("#b_cliente").prop('disabled', true);
         //$(this).autocomplete('close');
     }
});	

$("#b_busqueda").click(function() {  
	//alert('click');
	if (($("#b_nfacturas").val()!="")||($("#cod_cliente").val()!="")||($('#b_estadof').val()!='-1000')||(($("#b_dfecha").val()!="__/__/____")&&($("#b_hfecha").val()!="__/__/____"))){
		//alert('bus');
		buscarf();	
	}else{
		alert('Elegir uno o más criterios de busqueda...');
	}	
});
	
$("#b_limpiar").click(function() {limpiarf();});	

$("#form1").submit(function(e){
    //return false;
	e.preventDefault();
});	
</script>
<?php 
}
?>