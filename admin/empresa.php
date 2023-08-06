<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
include ('editempresa.php');
include ('eliminar_correo.php');

if (isset($_POST['logo'])) {
  $nombre_archivo=$_FILES['archivo']['name'];
  $guardar_img=$_FILES['archivo']['tmp_name'];
  
  if (move_uploaded_file($guardar_img,'img/'.$nombre_archivo )) {
    $updateItemQuery = "UPDATE empresa SET logo = '{$nombre_archivo}'  WHERE id_empresa = '1'";

    if ($sqlconnection->query($updateItemQuery) === TRUE) {
      echo '<script>
      swal("Buen Trabajo!", "Logo actualizado correctamente", "success").then(function() {
        window.location.replace("empresa.php");
        });

        </script>';

      } 

      else {
        //handle
        echo "someting wong";
        echo $sqlconnection->error;
        echo $updateItemQuery;
      }
    }

  }

  $empresa =  "
  SELECT MAX(id_empresa) AS id_empresa
  FROM empresa 
  ";

  if ($orderResult = $sqlconnection->query($empresa)) {
                        //if no order
    if ($orderResult->num_rows == 0) {

      echo "<tr><td class='text-center' colspan='7' >Actualmente no hay empresas registradas. </td></tr>";
    }

    else {
      while($fila = $orderResult->fetch_array(MYSQLI_ASSOC)) {
        $id_empresa=$fila['id_empresa'];
      }
    }
  }
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
        $prefijo=$fila2['prefijo'];
        $factura_max=$fila2['factura_max'];
        $fecha_fin=$fila2['fecha_fin'];
        $fecha_inicio=$fila2['fecha_inicio'];
        $impuesto=$fila2['impuesto'];
        $correo_empresa=$fila2['correo_empresa'];
        $base=$fila2['base'];
        $logo=$fila2['logo'];
        $nombre_impresora=$fila2['nombre_impresora'];
        $pago_directo=$fila2['pago_directo'];
        $porcentaje_propinas=$fila2["porcentaje_propinas"];
      }
    }
  }
  //GUARDAR CORREOS ELECTRONICOS

  if (isset($_POST['correos'])) {
    $items1 = ($_POST['correos_empresa']);
    $items2 = ($_POST['id_empresa_fk']);


    while(true) {

            //// RECUPERAR LOS VALORES DE LOS ARREGLOS ////////
      $item1 = current($items1);
      $item2 = current($items2);



            ////// ASIGNARLOS A VARIABLES ///////////////////
      $correo_empresa= (($item1 !== false) ? $item1 : ", &nbsp;");
      $id_empresa_fk= $item2;


            //// CONCATENAR LOS VALORES EN ORDEN PARA SU FUTURA INSERCIÓN ////////
      $valores='("'.$correo_empresa.'","'.$id_empresa_fk.'"),';

            //////// YA QUE TERMINA CON COMA CADA FILA, SE RESTA CON LA FUNCIÓN SUBSTR EN LA ULTIMA FILA /////////////////////
      $valoresQ= substr($valores, 0, -1);

            ///////// QUERY DE INSERCIÓN ////////////////////////////
      $sql = "INSERT INTO correos (correo_empresa, id_empresa_fk) 
      VALUES $valoresQ";

      $sqlconnection->query($sql);
        // Up! Next Value
      $item1 = next( $items1 );
      
      //$item3 = next( $items3 );


            // Check terminator
      if($item1 === false ) break;

    }

  }

  ?>

  <script>

    $(function(){
        // Clona la fila oculta que tiene los campos base, y la agrega al final de la tabla
        $("#adicional").on('click', function(){
          $("#tabla tbody tr:eq(0)").clone().removeClass('fila-fija').appendTo("#tabla");
        });

        // Evento que selecciona la fila y la elimina 
        $(document).on("click",".eliminar",function(){
          var parent = $(this).parents().get(0);
          $(parent).remove();
        });
      });
    </script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header ">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Administración de Empresa</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active">Empresa</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">

          <div id="accordion">
            <div class="card">
              <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                  <button class="btn btn-primary btn-block text-left" data-toggle="collapse" data-target="#collapseOne"  aria-controls="collapseOne">
                    <i class="fas fa-2x fa-building"></i>
                    Datos de la Empresa
                  </button>
                </h5>
              </div>

              <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                  <form  id="edititemform" action="" method="POST">
                    <div class="form-row border-bottom-info">
                      <div class="form-group col-md-6">
                        <input type="hidden" name="id_empresa" value="<?php echo $id_empresa ?>" class="form-control ">
                        <label for="inputEmail4">Nombre Empresa</label>
                        <input type="text" name="nombre_empresa" value="<?php echo $nombre_empresa ?>" class="form-control ">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputPassword4">Nit Empresa</label>
                        <input type="text" name="nit_empresa" value="<?php echo $nit_empresa ?>" class="form-control">
                      </div>

                      <div class="form-group col-md-6">
                        <label for="inputAddress">Dirección</label>
                        <input type="text" name="direccion_empresa" value="<?php echo $direccion_empresa ?>" class="form-control ">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputAddress2">Telefono </label>
                        <input type="text" name="telefono_empresa" value="<?php echo $telefono_empresa ?>" class="form-control">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputAddress2">Correo </label>
                        <input type="text" name="correo_empresa" value="<?php echo $correo_empresa ?>" class="form-control">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputAddress2">Pago Directo </label>
                        <select name="pago_directo" id="" class="form-control">
                          <option value="<?php echo $pago_directo; ?>"><?php echo $pago_directo; ?>  </option>
                          <option value="Si">Si</option>
                          <option value="No">No</option>
                        </select>
                        </div>

                    </div>
                    <h4 class="m-2 font-weight-bold text-warning text-center">Configuración Facturación</h4>
                    <div class="form-row ">
                      <div class="form-group col-md-6">
                        <label for="inputEmail4">Prefijo Factura</label>
                        <input type="text" name="prefijo" value="<?php echo $prefijo ?>" class="form-control ">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputEmail4">Factura Máxima</label>
                        <input type="text" name="factura_max" value="<?php echo $factura_max ?>" class="form-control ">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputEmail4">Fecha Inicial de Facturación</label>
                        <input type="date" name="fecha_inicio" value="<?php echo $fecha_inicio ?>" class="form-control ">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputEmail4">Fecha Final de Facturación</label>
                        <input type="date" name="fecha_fin" value="<?php echo $fecha_fin ?>" class="form-control ">
                      </div>

                    </div>
                    <h4 class="m-2 font-weight-bold text-warning text-center">Configuración General</h4>
                    <div class="form-row ">

                      <div class="form-group col-md-6">
                        <label for="inputEmail4">Base del Dia</label>
                        <input type="number" name="base" value="<?php echo $base ?>" class="form-control ">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="inputAddress2">Nombre Impresora POS </label>
                        <input type="text" name="nombre_impresora" value="<?php echo $nombre_impresora ?>" class="form-control">
                      </div>
                      <div class="form-group col-md-12">
                        <label for="inputAddress2">Pie de Pagina </label>
                        <textarea type="text" name="resolucion" value="<?php echo $resolucion ?>" class="form-control" cols="30" rows="5"><?php echo $resolucion ?>
                      </textarea>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="inputEmail4">Impuesto al Consumo (Impoconsumo)</label>
                      <input type="number" name="impuesto" value="<?php echo $impuesto ?>" class="form-control ">
                    </div>
                        <div class="form-group col-md-6">
                      <label for="inputEmail4">Porcentaje Propinas</label>
                      <input type="number" name="porcentaje_propinas" value="<?php echo $porcentaje_propinas ?>" class="form-control ">
                    </div>
                  </div>
                  <button type="submit" form="edititemform" name="btnedit" class="btn btn-success btn-block">Guardar Cambios</button>
                </form>
              </div>
            </div>
          </div>
          <!--  CAMBIAR LOGOTIPO -->
          <div class="card">
            <div class="card-header" id="headingTwo">
              <h5 class="mb-0">
                <button class="btn btn-primary btn-block collapsed text-left" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  <i class="fas fa-2x fa-image"></i>
                  Cambiar Logotipo
                </button>
              </h5>

            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                <?php
                if ($logo=="") {
                  echo '
                  <form action="" class="form-inline"  method="post" enctype="multipart/form-data">
                  <input type="file" name="archivo" class="form-control">
                  <input type="submit" name="logo" value="Enviar" class="btn btn-info form-inline">
                  </form>
                  ';
                }else{
                  echo '
                  <p>Cambiar el logotipo</p>
                  <form action="" class="form-inline"  method="post" enctype="multipart/form-data">
                  <center>
                  <div class="form-group ">

                  <input type="file" name="archivo" class="form-control">
                  <input type="submit" name="logo" value="Enviar" class="btn btn-info ">

                  </div>
                  </center>
                  </form>
                  ';

                } 
                ?>
              </div> 
            </div>
          </div>
          <!--  CONFIGURAR CORREOS ELECTRONICOS -->
          <div class="card">
            <div class="card-header" id="headingThree">
              <h5 class="mb-0">
                <button class="btn btn-primary btn-block collapsed text-left" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  <i class="fas fa-2x fa-envelope"></i>
                  Configurar Correos Electronicos
                </button>
              </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
              <div class="card-body">
                <center>
                  <form id="enviarcorreos" action="" method="POST">
                    <div class="form-group col-md-8">
                      <label for="inputAddress2"><B>Correo (Agrega los correos donde deseas que lleguen los cierres del dia)</B></label>
                      <table class="table pt-2"  id="tabla">
                        <tr>
                          <input type="hidden" name="id_empresa_fk[]"  class="form-control" value="<?php echo $id_empresa; ?>">
                          <td class="col-md-12"><input type="text" name="correos_empresa[]"  class="form-control"></td>                  
                          <td class="eliminar col-md-4"><input type="button" class="btn btn-danger"  value="Menos -"/></td>
                        </tr>
                      </table>
                      <center>
                        <div class="btn-der ">
                          <button id="adicional" name="adicional" type="button" class="btn btn-warning col-md-6"> <i class="fas fa-plus"></i> Más</button>

                        </div>
                      </center>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" form="enviarcorreos" name="correos">Guardar</button>
                  </form>
                </center>
                <hr>
                <!-- TABLA PARA CONSULTAR LOS CORREOS -->
                <table class="display table table-bordered text-center " width="100%" cellspacing="0">
                  <thead>
                    <th>Correo Electronico</th>
                    <th>Eliminar</th>
                  </thead>
                  <tbody id="caja_gastos">
                    <?php 
                    $displayStaffQuery = "SELECT *
                    FROM correos
                    WHERE id_empresa_fk='{$id_empresa}'";

                    if ($result = $sqlconnection->query($displayStaffQuery)) {

                      if ($result->num_rows == 0) {

                      }
                      while($fila = $result->fetch_array(MYSQLI_ASSOC)) {
                        $id_correo=$fila['id_correos'];
                        ?>  
                        <tr> 
                          <td><?php echo $fila['correo_empresa']; ?></td>

                          <td>
                            <form action="" method="POST">
                              <input type="hidden" value="<?php echo $id_correo ?>" name="id_correo">
                              <button class="btn btn-danger" type="submit" name="eliminarC"><i class="fas fa-trash"></i></button>
                            </form>

                          </td>
                        </tr>
                        <?php 
                      }

                    }
                    else {
                      echo $sqlconnection->error;
                      echo "ERROR.";
                    }

                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!--  INICIALIZAR FACTURA -->
          <div class="card">
            <div class="card-header" id="nueva">
              <h5 class="mb-0">
                <button class="btn btn-primary btn-block collapsed text-left" data-toggle="collapse" data-target="#factura" aria-expanded="false" aria-controls="factura">
                  <i class="fas fa-2x fa-list-ol"></i>
                  Inicializar Factura
                </button>
              </h5>
            </div>
            <div id="factura" class="collapse" aria-labelledby="nueva" data-parent="#accordion">
              <div class="card-body">
                <center>
                  <form id="idorder" action="autoincrement.php" method="POST">
                    <h4>Inicializar Factura</h4>
                    <input type="number" name="id_auto" value="" placeholder="Numero de facturacion">
                    <button type="submit" class="btn btn-info" form="idorder" name="btnauto">Enviar</button>
                  </form>
                </center>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>


    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <?php include ('includes/adminfooter.php');?>