<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
include ('eliminarPagoPropina.php');
/*====================================
HACER SALIDA DE PROPINA
====================================*/
if (isset($_POST['salida_propinas'])) {
  $fecha_salida = $sqlconnection->real_escape_string($_POST['fecha_salida']);
  $descripcion_salida = $sqlconnection->real_escape_string($_POST['descripcion_salida']);
  $monto_salida = $sqlconnection->real_escape_string($_POST['monto_salida']);

  $addStaffQuery = "INSERT INTO salidas_propinas (fecha_salida, descripcion_salida, monto_salida) VALUES ('{$fecha_salida}','{$descripcion_salida}','{$monto_salida}') ";

  if ($sqlconnection->query($addStaffQuery) === TRUE) {
    echo '
      <script>
      swal("Buen Trabajo!", "Se registro con éxito", "success");
      </script>';

  }else {
          //handle
    echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error al guardar el archivo", "error");</script>';
    echo $sqlconnection->error;
  }
}

$acumPropinas =  "
SELECT *
FROM propinas";
if ($orderResultPropina = $sqlconnection->query($acumPropinas)) {
  if ($orderResultPropina->num_rows == 0) {
    echo "ERROR";
  }else {
    $acumPropina=0;
    while($filaP = $orderResultPropina->fetch_array(MYSQLI_ASSOC)) {
      $valor_propina=$filaP['valor_propina'];
      $acumPropina+=$valor_propina;
    }
  }
}
$salidaPropinas =  "
SELECT *
FROM salidas_propinas ";
if ($orderResultSalidas = $sqlconnection->query($salidaPropinas)) {
  if ($orderResultSalidas->num_rows == 0) {
    echo "ERROR";
  }else {
    $acumSalida=0;
    while($filaS = $orderResultSalidas->fetch_array(MYSQLI_ASSOC)) {
      $monto_salida=$filaS['monto_salida'];
      $acumSalida+=$monto_salida;
    }
  }
}
$propinas_pendientes= ($acumPropina-$acumSalida);
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Administrar Propinas</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
						<li class="breadcrumb-item active">Propinas</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
      <div class="row">
        <div class="col-lg-4 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>$ <?php echo number_format($propinas_pendientes); ?></h3>

              <p>Propinas Pendientes X Cobrar</p>
            </div>
            <div class="icon">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <a href="#" class="small-box-footer"> <i class="fas fa-arrow-circle-top"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>$<?php echo number_format($acumPropina); ?></h3>

              <p>Historial De Propinas</p>
            </div>
            <div class="icon">
              <i class="fas fa-hand-holding-usd"></i>
            </div>
            <button class="small-box-footer btn btn-block" id="historial"> <i class="fas fa-arrow-circle-right"></i> Ver más</button>
            
          </div>
        </div>
        <div class="col-lg-4 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>$<?php echo number_format($acumSalida); ?></h3>

              <p>Propinas Cobradas</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <button class="small-box-footer btn btn-block" id="salidas"> <i class="fas fa-arrow-circle-right"></i> Ver más</button>
          </div>
        </div>
        <div class="col md-12" id="tabla_historial">
          <div class="card-body ">
            <table class="display table table-bordered text-center " width="100%" cellspacing="0">
              <thead>
               <th>#</th>
               <th>Fecha Propina</th>
               <th>Valor Propina </th>
               <th>Factura</th>
             </thead>
             <tbody >
              <?php 

              $Propinas2 =  "
              SELECT *
              FROM propinas ORDER BY id_propina DESC";
              if ($orderResultPro = $sqlconnection->query($Propinas2)) {
                if ($orderResultPro->num_rows == 0) {
                  echo "ERROR";
                }else {
                  $acumSalida=0;
                  while($filaPro = $orderResultPro->fetch_array(MYSQLI_ASSOC)) {
                    $id_propina=$filaPro['id_propina'];
                    $fecha_propina=$filaPro['fecha_propina'];
                    $valor_propina=$filaPro['valor_propina'];
                    $cod_venta=$filaPro['cod_venta'];
                    ?>  
                    <tr>
                     <td><?php echo $id_propina; ?></td>
                     <td><?php echo $fecha_propina; ?></td>
                     <td><?php echo number_format($valor_propina); ?></td>
                     <td><?php echo $cod_venta; ?></td>
                   </tr>
                   <?php 
                 }
               }
             }
             ?>
           </tbody>
         </table>
       </div>

     </div>
     <div class="col md-12" id="tabla_salidas">
      <div class="card-body ">
        <div class="card-body">
          <form action="" class="form-inline" method="POST">
           <div class="form-group  mb-2">
              <label for="">Fecha</label>
            <input type="date" class="form-control" name="fecha_salida">
           </div>
           <div class="form-group  mb-2">
             <label for="">Descripción</label>
            <input type="text" class="form-control" name="descripcion_salida">
           </div>
           <div class="form-group  mb-2">
            <label for="">Valor Salida</label>
            <input type="number" class="form-control" placeholder="$50000" name="monto_salida">
           </div>
            <div class="form-group  mb-2">
            
            <button class="btn btn-success" name="salida_propinas" type="submit">Guardar</button>
           </div>
          </form>
        </div>
        <table class="display table table-bordered text-center " width="100%" cellspacing="0">
          <thead>
           <th>#</th>
           <th>Fecha Salida</th>
           <th>Descripción </th>
           <th>Monto Salida</th>
           <th>Eliminar</th>
         </thead>
         <tbody >
          <?php 

          $salidaPropinas2 =  "
          SELECT *
          FROM salidas_propinas ORDER BY id_salida DESC";
          if ($orderResultSalidas = $sqlconnection->query($salidaPropinas2)) {
            if ($orderResultSalidas->num_rows == 0) {
              echo "ERROR";
            }else {
              $acumSalida=0;
              while($filaS = $orderResultSalidas->fetch_array(MYSQLI_ASSOC)) {
                $id_salida=$filaS['id_salida'];
                $fecha_salida=$filaS['fecha_salida'];
                $descripcion_salida=$filaS['descripcion_salida'];
                $monto_salida=$filaS['monto_salida'];
                ?>  
                <tr>
                 <td><?php echo $id_salida; ?></td>
                 <td><?php echo $fecha_salida; ?></td>
                 <td><?php echo $descripcion_salida; ?></td>
                 <td><?php echo number_format($monto_salida); ?></td>
               
                <td>

                  <button class="btn btn-danger" data-toggle="modal" data-target="#eliminarPropina"  data-id_salida_propina="<?php echo $filaS["id_salida"];?>"><i class="fas fa-trash"></i></button>
                </td>

              </tr>
              <?php 
            }
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

</div>
</div>
</section>
</div>
<!-- Delete Modal-->
<div class="modal fade" id="eliminarPropina" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Estás seguro de eliminar este menú?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Seleccione "Eliminar" a continuación se eliminará <strong>el pago </strong> de las propinas.</div>
      <div class="modal-footer">
        <form id="deletemenuform" method="POST">
          <input type="hidden" name="id_salida_propina" id="id_salida_propina">
        </form>
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
        <button type="submit" form="deletemenuform" class="btn btn-danger" name="eliminarPago">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<?php include ('includes/adminfooter.php');?>
<script>
$('#eliminarPropina').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id_salida_propina = button.data('id_salida_propina'); // Extract info from data-* attributes
        

        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modal-body').html('Selecciona "Eliminar" y a continuación se borrará ');
        modal.find('.modal-footer #id_salida_propina').val(id_salida_propina);
      });

</script>