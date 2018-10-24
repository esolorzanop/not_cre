<?php
session_start();
date_default_timezone_set('America/Guayaquil');	

if($_SESSION['LOGIN'] == null) echo "<script>parent.window.location.href='../fun_php/salir.php';</script>";

$usuario = $_SESSION['UW_ID'];

include("../fun_php/lib.php");
$con = conectar();

$sql = 'SELECT count(1) total FROM INTER.INVE_DOCUMENTOS_DAT Where CODI_ADMI_ESTA = \'O\' AND CODI_ADMI_EMPR_FINA = \'00001\' AND CODI_ADMI_PUNT_VENT = \'101\' AND CODI_INVE_TIPO_DOCU IN (\'FACTU\',\'REFAC\')';
//echo $sql."<br>";

	$rst = oci_parse($con, $sql);
	$r = oci_execute($rst);

	if (!$r) {
		$e = oci_error($rst); 
		echo "Ocurrió un error al verificar total de solicitud NC...!";
	}

	$row = oci_fetch_array($rst, OCI_ASSOC);	

if($row['TOTAL'] == 0){
	//echo "<script>alert('Su busqueda no tiene resultados, intentelo nuevamente...!');window.location.href='adm_001.php?refresca=1';</script>"; 
	echo "<script>alert('Su busqueda no tiene resultados, intentelo nuevamente...!');</script>"; 
}else{ 
	if(isset($_POST['limite'])){
		$limit = $_POST['limite'];
		$_SESSION['limit'] = $_POST['limite'];
	}else{
		$limit = 0;
		$_SESSION['limit'] = 0;
	}
			
	$limite = ceil($row['TOTAL'] / 10);
	$n_page_number = $limit; 
	$n_page_size = 10;
?>
<style>
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
	text-decoration: underline;
}
a:hover {
	color: orange;
	background-color: black;
	text-decoration: underline;
}
a:active {
	color: #000;
	text-decoration: underline;
}

.visited {
	color: #000;
	text-decoration: underline;
}	
</style>
  <div id="wrapper" class="animate">
    <div class="container-fluid">      
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
				<center><h4><strong>NOTAS DE CRÉDITO</strong></h4></center>
				<button id="b_limpiar" name="b_limpiar" title="Refrescar Página" class="btn btn-default btn-lg" onclick="javaScript:funRefresca();">
			  	<i data-feather="refresh-cw"></i><script>feather.replace()</script>
			  </button> 
			<div class="table-responsive">
              <table class="table table-md table-striped">
                <thead class="table-dark">
                  <tr align="center">
                    <th style="width: 10%">FECHA</th> 
					<th style="width: 12%">FACTURA #</th>                     
					<th style="width: 13%">CLIENTE</th> 
                    <th style="width: 20%">DETALLE</th>
					<th style="width: 10%">TOTAL</th>
					<th style="width: 10%">FECHA AUTORIZA NC</th>					  
                    <th style="width: 15%">DETALLE AUTORIZA NC</th>
                    <th style="width: 10%">ESTADO DOCUMENTO</th>                  
                  </tr>
                </thead>
<?php
	$sql = 'SELECT * FROM (SELECT ROWNUM AS FILA, CONSULTA.* FROM (SELECT rownum ROW_NUMBER, CODI_INVE_DOCU, to_char(FECH_INVE_DOCU, \'DD/MM/YYYY\') FECH_INVE_DOCU, I.REFE_INVE_DOC, I.CODI_INVE_CLIE, I.COME_INVE_DOCU, TO_CHAR(IMPO_TOTA_INVE_DOCU, \'FM999,999,999.90\') IMPO_TOTA_INVE_DOCU, to_char(FECH_INVE_APRUE, \'DD/MM/YYYY\') FECH_INVE_APRUE, I.COME_INVE_EST, I.CODI_INVE_TIPO_EST FROM INTER.INVE_DOCUMENTOS_DAT I Where CODI_ADMI_ESTA = \'O\' AND CODI_ADMI_EMPR_FINA = \'00001\' AND CODI_ADMI_PUNT_VENT = \'101\' AND CODI_INVE_TIPO_DOCU IN (\'FACTU\',\'REFAC\') ORDER BY FECH_INVE_DOCU DESC) CONSULTA) WHERE FILA >= (((:n_page_number-1) * :n_page_size) + 1) AND FILA < ((:n_page_number * :n_page_size) + 1 )';// AND CODI_INVE_TIPO_EST IN (1,2,3,4) LAS QUE DEBE CARGAR DE FAULT
									
	$rst = oci_parse($con, $sql);
	oci_bind_by_name($rst, ':n_page_number', $n_page_number);	
	oci_bind_by_name($rst, ':n_page_size', $n_page_size);
	//echo $sql;
	
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
		$productos[$row['ROW_NUMBER']]['CODIGO_FAC'] = $row['CODI_INVE_DOCU,'];						
		$productos[$row['ROW_NUMBER']]['FECHA_FAC'] = $row['FECH_INVE_DOCU'];	
		$productos[$row['ROW_NUMBER']]['NUMERO_FAC'] = $row['REFE_INVE_DOC'];	
		
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
								echo "<th scope=\"row\"><a href=\"\">".$value."</a></th>";
								break;																						

								case "DETALLE_FAC":
								echo "<td align=\"justify\">".$value."</td>";
								break;	

								case "DETALLE_FAC":
								echo "<td align=\"justify\">".$value."</td>";
								break;										
									
								default:
								echo "<td>$value</td>";
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
<script>
function funRefresca(){
			window.location.reload()
		}

</script>
</script>
<?php 
}
?>