<?php
session_start();

include("../fun_php/lib.php");
date_default_timezone_set('America/Guayaquil');	

$conn = conectar();

$empresa="TEVCOL_FACT_NC";
$fecha=date("Y-m-d");
date_default_timezone_set('America/Guayaquil');
$nombre = $empresa."_".$fecha.".xls";

header('Content-type: application/vnd.ms-excel; name=excel');
header("Content-Disposition: attachment; filename=$nombre;");
header("Pragma: no-cache");
header("Expires: 0");


$sql_usu = 'SELECT (nom1_segu_usua||\' \'||nom2_segu_usua||\' \'||ape1_segu_usua||\' \'||ape2_segu_usua) nom_ape FROM SEGU_USUARIOS_dAT WHERE CODI_ADMI_ESTA = \'A\' and logi_segu_usua like trim(\''.$_SESSION['LOGIN'].'\')';
		//echo $sql_usu;		  

				$rst_usu = oci_parse($conn, $sql_usu);
				$r = oci_execute($rst_usu);

				if (!$r) {
					$e = oci_error($rst_usu); 
					echo "Ocurrió un error al verificar cliente...!";
				}
		$row_usu = oci_fetch_array($rst_usu, OCI_ASSOC);		
		$usuario = $row_usu['NOM_APE'];

?>	
<html lang="es">
	<head>		
		<meta charset="utf-8">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<!--<link rel="stylesheet" type="text/css" href="../css/style.css" media="screen" />-->
<style type="text/css">
	<!--
	body {
		background-color: #FFFFFF;
	}
	.Estilo55 {color: #FFFFFF}
	a {
		color:#D0910B;
	}
	.Estilo56 {
		color: #FFFFFF;
		font-family: Tahoma;
		font-weight: bold;
	}
	-->
</style>
</head>
<body>
<div align="center">
    <table width="100%" border="0" align="center">
      <tr>
        <td colspan="9" bgcolor="#070707" height="100"><div align="center"><img src="http://webapp.tevcol.com.ec:8010/images/LoginBanner_white1.JPG" alt="TEVCOL - Transportadora Ecuatoriana de Valores" width="450" height="100" /></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="21" colspan="9"><div align="center">
          <p><font color="#000000" size="+2"><strong>PORTAL SERVICIOS EN LINEA<BR />
          FACTURAS A EMITIR NOTAS DE CRÉDITO</strong></font></p>
        </div></td>
      </tr>
    </table>
    <table width="100%" border="0" align="center">
      <tr>
        <td colspan="4"><strong>Usuario Consulta: </strong><?php echo $usuario;//$_SESSION['sUsuario']; ?></td>
        <td colspan="4"><strong>Fecha Reporte:</strong>
        <?php 	
			$meses = array('0' => '','01' => 'Enero','02' => 'Febrero','03' => 'Marzo','04' => 'Abril','05' => 'Mayo','06' => 'Junio','07' => 'Julio','08' => 'Agosto','09' => 'Septiembre','10' => 'Octubre','11' =>'Noviembre','12' => 'Diciembre');
			$anio=date("Y");
			$mes=date("m");
			$dia=date("d");		  
			echo $dia."/".$meses[$mes]."/".$anio;	
		?>
		</td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
        <td colspan="4"><strong>Hora Reporte:</strong>
          <?php $hora=date("H:i:s"); echo $hora; ?>
        </td>
      </tr>
    </table>
</div>
<br/>
<table border="1" align="center" cellpadding="0" cellspacing="0" bgcolor="">
  <tr>
    <td height="25" colspan="9" bgcolor="#444444" style="color: #FFFFFF; font-family: Tahoma; font-weight: bold;"><div align="center">LISTADO NOTAS DE CRÉDITO</div></td>
  </tr>
  <tr>
    <td height="25" bgcolor="#626262" class="date1"><div align="center" class="Estilo55"># DOCUMENTO</div></td>
	  <td height="25" bgcolor="#626262" class="date1"><div align="center" class="Estilo55"># RE-FACTURA</div></td>
    <td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">FECHA</div></td>
	<td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">CLIENTE</div></td>
    <td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">COMENTARIO</div></td>
    <td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">TOTAL</div></td>	
    <td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">FECHA JUSTIFICACIÓN</div></td>	
<td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">COMENTARIO JUSTIFICACIÓN</div></td>	
	  <td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">ESTADO DOCUMENTO</div></td>	
  </tr>
  <?php	      			     
		$sql = $_SESSION['sql_rpt'];
		$rst = oci_parse($conn, $sql);
		oci_execute($rst)or die("Ocurrio un error ");
	//	echo $sql;	
	
		while ($row = oci_fetch_array($rst, OCI_ASSOC)) 
		{								
			$sql_rfact = 'select B.CODI_INVE_DOCU_2 from INTER.BANC_PAGOS_DAT B WHERE B.CODI_ADMI_EMPR_FINA = \'00001\' AND B.CODI_ADMI_PUNT_VENT = \'101\' AND B.CODI_INVE_TIPO_DOCU IN (\'NCCLI\') and B.CODI_INVE_DOCU ='.$row['CODI_INVE_DOCU'];
	//	echo $sql_rfact;		  

				$rst_rfact = oci_parse($conn, $sql_rfact);
				$r = oci_execute($rst_rfact);

				if (!$r) {
					$e = oci_error($rst_rfact); 
					echo "Ocurrió un error al verificar codigo re-factura...!";
				}
		$row_rfact = oci_fetch_array($rst_rfact, OCI_ASSOC);
		
		
		$refact = $row_rfact['CODI_INVE_DOCU_2'];				
			
			$sql_cl = 'select nomb_inve_clie from inve_clientes_dat where codi_admi_empr_fina = \'00001\' and codi_admi_esta = \'A\' and codi_inve_clie ='.$row['CODI_INVE_CLIE'];
		//echo $sql_cl;		  

				$rst_cl = oci_parse($conn, $sql_cl);
				$r = oci_execute($rst_cl);

				if (!$r) {
					$e = oci_error($rst_cl); 
					echo "Ocurrió un error al verificar cliente...!";
				}
		$row_cl = oci_fetch_array($rst_cl, OCI_ASSOC);
		$cliente = $row_cl['NOMB_INVE_CLIE'];
		
		$sql_est = 'select NOMB_INVE_TIPO_EST from INTER.INVE_TIPO_EST_REF WHERE CODI_INVE_TIPO_DOCU = \'NCCLI\' AND CODI_INVE_TIPO_EST = '.$row['CODI_INVE_TIPO_EST'];
		//echo $sql_cl;		  

				$rst_est = oci_parse($conn, $sql_est);
				$r = oci_execute($rst_est);

				if (!$r) {
					$e = oci_error($rst_est); 
					echo "Ocurrió un error al verificar cliente...!";
				}
		$row_est = oci_fetch_array($rst_est, OCI_ASSOC);		
		$estado = $row_est['NOMB_INVE_TIPO_EST'];			
			
			echo "<tr bgcolor=#FFFFFF> 
			   <td height=25  class=date1 bgcolor=#444444><div align=center style='color:#D0910B'>".$row['REFE_INVE_DOC']."</div></td>
			   <td height=25  class=date1 bgcolor=#444444><div align=center style='color:#D0910B'>".$refact."</div></td>";
			   echo "<td bgcolor=#FFFFFF  class=date2><div align=left>".$row['FECH_INVE_DOCU1']."</div></td>		  	   
			   <td bgcolor=#FFFFFF  class=date2><div align=center>".$cliente."</div></td>
			   <td bgcolor=#FFFFFF  class=date2><div align=left>".$row['COME_INVE_DOCU']."</div></td>		   
			   <td bgcolor=#FFFFFF  class=date2><div align=center>".$row['IMPO_TOTA_INVE_DOCU']."</div></td>
			   <td bgcolor=#FFFFFF  class=date2><div align=center>".$row['FECH_INVE_APRUE']."</div></td>
			   <td bgcolor=#FFFFFF  class=date2><div align=center>".$row['COME_INVE_EST']."</div></td>
			   <td bgcolor=#FFFFFF  class=date2><div align=center>".$estado."</div></td>";		   		   		   
			 echo "</tr> \n";
         }										
	 ?>
</table>
<?php 
    oci_free_statement($rst);
	oci_close($conn);
?>
</body>
</html>