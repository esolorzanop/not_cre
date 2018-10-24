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
//header ("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=$nombre;");
header("Pragma: no-cache");
header("Expires: 0");

$usuario = "Edgar";//""$_SESSION['nID'];
$where = "";
?>	
<html lang="es-es">
	<head>
		<meta charset="utf-8">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<link rel="stylesheet" type="text/css" href="../css/style.css" media="screen" />
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
        <td colspan="8" bgcolor="#070707" height="100"><div align="center"><img src="http://webapp.tevcol.com.ec:8010/images/LoginBanner_white1.JPG" alt="TEVCOL - Transportadora Ecuatoriana de Valores" width="450" height="100" /></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="21" colspan="8"><div align="center">
          <p><font color="#000000" size="+2"><strong>PORTAL SERVICIOS EN LINEA<BR />
          FACTURAS A EMITIR NOTAS DE CRÉDITO</strong></font></p>
        </div></td>
      </tr>
    </table>
    <table width="100%" border="0" align="center">
      <tr>
        <td colspan="3"><strong>Usuario Consulta: </strong><?php echo $_SESSION['sUsuario']; ?></td>
        <td colspan="3"><strong>Fecha Reporte:</strong>
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
        <td colspan="3">&nbsp;</td>
        <td colspan="3"><strong>Hora Reporte:</strong>
          <?php $hora=date("H:i:s"); echo $hora; ?>
        </td>
      </tr>
    </table>
</div>
<br/>
<table border="1" align="center" cellpadding="0" cellspacing="0" bgcolor="">
  <tr>
    <td height="25" colspan="8" bgcolor="#444444" style="color: #FFFFFF; font-family: Tahoma; font-weight: bold;"><div align="center">LISTADO DE FACTURAS A EMITIR NOTAS DE CRÉDITO</div></td>
  </tr>
  <tr>
    <td height="25" bgcolor="#626262" class="date1"><div align="center" class="Estilo55"># FACTURA</div></td>
    <td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">FECHA</div></td>
	<td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">CLIENTE</div></td>
    <td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">COMENTARIO</div></td>
    <td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">TOTAL</div></td>	
    <td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">FECHA AUTORIZA NC</div></td>	
<td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">COMENTARIO AUTORIZA NC</div></td>	
	  <td bgcolor="#626262" class="date" style="color: #FFFFFF;"><div align="center">ESTADO DOCUMENTO</div></td>	
  </tr>
  <?php	      			     
	$sql = $_SESSION['sql_rpt'];
		$rst = oci_parse($conn, $sql);
		oci_execute($rst)or die("Ocurrio un error ");
//		echo $sql;	
		while ($row = oci_fetch_array($rst, OCI_ASSOC)) 
		{								
			echo "<tr bgcolor=#FFFFFF> 
			   <td height=25  class=date1 bgcolor=#444444><div align=center style='color:#D0910B'>".$row['REFE_INVE_DOC']."</div></td>";
			   echo "<td bgcolor=#FFFFFF  class=date2><div align=left>".$row['FECH_INVE_DOCU']."</div></td>		  	   
			   <td bgcolor=#FFFFFF  class=date2><div align=center>".$row['CODI_INVE_CLIE']."</div></td>
			   <td bgcolor=#FFFFFF  class=date2><div align=left>".$row['COME_INVE_DOCU']."</div></td>		   
			   <td bgcolor=#FFFFFF  class=date2><div align=center>".$row['IMPO_TOTA_INVE_DOCU']."</div></td>
			   <td bgcolor=#FFFFFF  class=date2><div align=center>".$row['FECH_INVE_APRUE']."</div></td>
			   <td bgcolor=#FFFFFF  class=date2><div align=center>".$row['COME_INVE_EST']."</div></td>
			   <td bgcolor=#FFFFFF  class=date2><div align=center>".$row['CODI_INVE_TIPO_EST']."</div></td>";		   		   		   
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