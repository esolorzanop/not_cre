<?php
session_start();
include("../fun_php/lib.php");
$con=conectar();
$con1=conectar1();
date_default_timezone_set('America/Guayaquil');	

if($_SESSION['LOGIN'] == null) echo "<script>parent.window.location.href='../fun_php/salir.php';</script>";
$usuario = $_SESSION['UW_ID'];

?>
<style>
#wrapper{padding:5px 15px; }
.card{margin-bottom: 15px; border-radius:0; box-shadow: 0 3px 5px rgba(0,0,0,.1); background:#fff;}
.header-top{box-shadow: 0 3px 5px rgba(0,0,0,.1)}
@media(min-width:992px) {
#wrapper{padding: 5px 15px 15px 15px; }
}
.animate{-webkit-transition:all .3s ease-in-out;-moz-transition:all .3s ease-in-out;-o-transition:all .3s ease-in-out;-ms-transition:all .3s ease-in-out;transition:all .3s ease-in-out}
</style>
<link href="../css/shadowbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../fun_php/shadowbox.js"></script>
<script type="text/javascript"> Shadowbox.init({ language: "es", players:  ['img', 'html', 'iframe', 'qt', 'wmp', 'swf', 'flv'] }); </script>

  <div id="wrapper" class="animate">
    <div class="container-fluid">
      <center><h4>COMPROBANTES DE DEPÓSITOS DIGITALIZADOS</h4></center>
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">FECHA</th> 
                    <th scope="col">CLIENTE</th>
                    <th scope="col">USUARIO</th>
                    <th scope="col">CIUDAD</th>                  
                  </tr>
                </thead>
<?php
	$sql = 'select  ROWNUM row_number, pi_cod_compuesto codigo, pi.cod_cliente, cl.cl_nombre ncliente, us.cod_usuario, us.us_nombre nusuario,pi.cod_ciudad, ci.ci_nombre nciudad, to_char(pi_fecha_papeleta, \'DD/MM/YYYY\') fecha_pap  from papeletas_imagen pi, cliente cl, usuario us, ciudad ci where pi_estado <> 0 and pi_imagen = 1 and pi.cod_cliente = cl.cod_cliente and PI.COD_USUARIO = US.COD_USUARIO and US.COD_CLIENTE = CL.COD_CLIENTE and PI.COD_CIUDAD = CI.COD_CIUDAD and CL.COD_CLIENTE =  1028 and us.cod_usuario = 1028002 and CI.COD_CIUDAD = 20';
	$rst = oci_parse($con1, $sql);
/*	oci_bind_by_name($rst, ':login', $login);
	oci_bind_by_name($rst, ':clave', $clave);	*/
	$r = oci_execute($rst);
	
	if (!$r) {
		$e = oci_error($rst); 
/*	    print htmlentities($e['message']);
		print "\n<pre>\n";
		print htmlentities($e['sqltext']);
		printf("\n%".($e['offset']+1)."s", "^");
		print  "\n</pre>\n";*/
		echo "Ocurrió un error al verificar papeletas...!";
	}
	
	$productos="";
	while($row = oci_fetch_array($rst, OCI_ASSOC+OCI_RETURN_NULLS))
	{
		$productos[$row['ROW_NUMBER']]['CODIGO'] = $row['CODIGO'];						
		$productos[$row['ROW_NUMBER']]['FECHA_PAP'] = $row['FECHA_PAP'];																
		$productos[$row['ROW_NUMBER']]['NCLIENTE'] = $row['NCLIENTE'];						
		$productos[$row['ROW_NUMBER']]['NUSUARIO'] = $row['NUSUARIO'];						
		$productos[$row['ROW_NUMBER']]['NCIUDAD'] = $row['NCIUDAD'];						
		}		
?>                
                <tbody>
<?php
			foreach ($productos as $v1) {
					echo '<tr>';									
					foreach ($v1 as $v2=>$value) {
 																				
							switch ($v2) {															
							
								case "CODIGO":
										$codigo = "";
										$codigo = $value;
								break;																						

								case "FECHA_PAP":
								echo "<th scope'row'><a href='http://192.168.0.150:8010/guiad/".$codigo.".jpg' rel='shadowbox'>".$value."</th>";
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