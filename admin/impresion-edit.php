<?php

include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <?php    
    $id_orden= $_GET['orderID'];
    $devo =  "
    SELECT *
    FROM tbl_order WHERE orderID='$id_orden'
    ";

    if ($orderResult2 = $sqlconnection->query($devo)) {
                        //if no order
        if ($orderResult2->num_rows == 0) {

            echo "<div class='alert alert-warning alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h5><i class='icon fas fa-exclamation-triangle'></i> Importante!</h5>
            Actualmente no hay pedido en este momento.
            </div>";
        }

        else {
            while($fila2 = $orderResult2->fetch_array(MYSQLI_ASSOC)) {
                $id=$fila2['orderID'];
                $pago=$fila2['pago'];
                $total=$fila2['total'];
                $devolucion=$fila2['devolucion'];
            }
        }
    }

    ?>



    <!-- Content Wrapper. Contains page content -->

    <!-- Content Header (Page header) -->
    <section class="content-header ">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Devolución</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Factura</li>
          </ol>
      </div>
  </div>
</div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card mb-3 border-primary">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb ">
            <li class="breadcrumb-item">
                <a href="index.php">Panel de Control</a>
            </li>
            <li class="breadcrumb-item active">Devuelta</li>
        </ol>

        <!-- Page Content -->

        <h3 class="text-center bg-navy pt-2">INFORMACIÓN DEL PAGO</h3>

        <div class="card-body">
            <a href="impresion_detalle.php?orderID=<?php echo $id; ?>" class="btn btn-sm btn-info"><i class="fas fa-print"></i> Imprimir</a>
            <table class="table table-bordered table-sm text-center">
                <thead class="bg-teal">
                    <tr>
                        <td>Caja</td>
                        <td>Valor Recibido</td>
                        <td>Pago</td>
                        <td>Devolución</td>
                        <td>Fecha</td>

                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $pagosacum =  "
                    SELECT c.id_caja, c.nombre_caja, c.estado_caja,p.id_pago, p.orderID, p.id_caja_fk, p.monto_recibido, p.pago_orden, p.devuelto, p.fecha_pago
                    FROM pagos p
                    INNER JOIN tipo_caja c
                    ON p.id_caja_fk=c.id_caja
                    WHERE p.orderID='{$id_orden}'";
                    if ($orderResultPago = $sqlconnection->query($pagosacum)) {
                        //if no order
                        if ($orderResultPago->num_rows == 0) {

                            echo "ERROR";
                        }

                        else {
                            $acumPago=0;
                            while($orderpago = $orderResultPago->fetch_array(MYSQLI_ASSOC)) {
                                $pago_orden=$orderpago['pago_orden'];
                                $acumPago+=$pago_orden;
                                echo "<tr>

                                <td>".$orderpago['nombre_caja']." </td>
                                <td>$ ".number_format($orderpago['monto_recibido'])." </td>
                                <td>$ ".number_format($orderpago['pago_orden'])." </td>
                                <td>$ ".number_format($orderpago['devuelto'])."</td>
                                <td>".$orderpago['fecha_pago']."</td>
                                ";
                                ?>
                                
                                <?php  
                                echo"
                                </tr>
                                ";
                            }


                        }


                    }
                    $debe=($total-$acumPago);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.container-fluid -->


</section>
</div>
<?php include ('includes/adminfooter.php');?>



<?php 
/* DATOS TRAIDOS DE LA CONSULTA SQL*/

$empresacon =  "
SELECT *
FROM empresa WHERE id_empresa='$id_empresa'
";

if ($orderResult = $sqlconnection->query($empresacon)) {
                        //if no order
    if ($orderResult->num_rows == 0) {

        echo "<tr><td class='text-center' colspan='7' >Actualmente no hay empresas registradas. </td></tr>";
    }

    else {
        while($fila2 = $orderResult->fetch_array(MYSQLI_ASSOC)) {
            $id_empresa=$fila2['id_empresa'];
            $nit_empresa=$fila2['nit_empresa'];
            $nombre_empresa=$fila2['nombre_empresa'];
            $direccion_empresa=$fila2['direccion_empresa'];
            $telefono_empresa=$fila2['telefono_empresa'];
            $resolucion=$fila2['resolucion'];
            $impresora=$fila2['nombre_impresora'];
        }
    }
}


require __DIR__ . '../staff/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



$nombre_impresora = "$impresora";
$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);


/*Corte el recibo y abra el cajón o monedero */
$printer -> cut();
$printer -> Pulse();
/*Cerramos la sesion de la impresora*/
$printer -> close();


?> 