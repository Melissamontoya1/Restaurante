<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');


?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Tomar Pedido</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Orden</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

        <!-- Page Content 
        <h1>Administración de Órdenes</h1>
        <hr>
        <p>Administración de nuevas órdenes en esta página.</p>
      -->
      <div class="row">


        <div class="col-lg-12">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-chart-bar"></i>
            Pedido</div>
            <div class="card-body">
              <form action="insertorder.php" method="POST" >
                <?php if(isset($_GET['id_mesa'])){ 
                  $id_mesa=$_GET['id_mesa']; ?>
                  <label for="">Seleccionar Mesa</label>

                  <select class="form-control input-sm id_mesa_fk" name="id_mesa_fk" >

                    <?php
                    $sql_mesa=mysqli_query($sqlconnection,"SELECT m.id_mesa,m.numero_mesa,m.estado_mesa,m.id_area_fk,m.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
                      FROM mesa m
                      INNER JOIN area_mesa a
                      ON m.id_area_fk=a.id_area
                      INNER JOIN tipo_mesa t 
                      ON m.id_tipo_fk= t.id_tipo WHERE m.id_mesa='{$id_mesa}'");
                    while ($rw=mysqli_fetch_array($sql_mesa)){
                      $id_mesa=$rw["id_mesa"];
                      $id_tipo=$rw["id_tipo"];
                      $nombre_tipo=$rw["nombre_tipo"];
                      $nombre_area=$rw["nombre_area"];
                      $numero_mesa=$rw["numero_mesa"];

                      ?>

                      <option value="<?php echo $id_mesa?>" name="id_mesa_fk" class="id_mesa_fk"><?php echo "Tipo : ".$nombre_tipo." # ".$numero_mesa."  / Area: ".$nombre_area ?></option>
                      <?php
                    }
                    ?>
                  </select>
                <?php   }else{ ?>
                  <?php if($pago_directo=="No"){ ?>
                    <label for="">Seleccionar Mesa</label>

                    <select class="form-control input-sm id_mesa_fk" name="id_mesa_fk" >

                      <?php
                      $sql_mesa=mysqli_query($sqlconnection,"SELECT m.id_mesa,m.numero_mesa,m.estado_mesa,m.id_area_fk,m.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
                        FROM mesa m
                        INNER JOIN area_mesa a
                        ON m.id_area_fk=a.id_area
                        INNER JOIN tipo_mesa t 
                        ON m.id_tipo_fk= t.id_tipo WHERE m.estado_mesa='Habilitada'");
                      while ($rw=mysqli_fetch_array($sql_mesa)){
                        $id_mesa=$rw["id_mesa"];
                        $id_tipo=$rw["id_tipo"];
                        $nombre_tipo=$rw["nombre_tipo"];
                        $nombre_area=$rw["nombre_area"];
                        $numero_mesa=$rw["numero_mesa"];
                         if($nombre_area=="Mesa Dividida"){

                            }else{
                        ?>

                        <option value="<?php echo $id_mesa?>" name="id_mesa_fk" class="id_mesa_fk"><?php echo "Tipo : ".$nombre_tipo." # ".$numero_mesa."  / Area: ".$nombre_area ?></option>
                        <?php
                      }
                    }
                      ?>
                    </select>
                  <?php }else{ ?>

                   <select class="form-control input-sm id_mesa_fk" name="id_mesa_fk" hidden>

                    <?php
                    $sql_mesa=mysqli_query($sqlconnection,"SELECT m.id_mesa,m.numero_mesa,m.estado_mesa,m.id_area_fk,m.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
                      FROM mesa m
                      INNER JOIN area_mesa a
                      ON m.id_area_fk=a.id_area
                      INNER JOIN tipo_mesa t 
                      ON m.id_tipo_fk= t.id_tipo ");
                    while ($rw=mysqli_fetch_array($sql_mesa)){
                      $id_mesa=$rw["id_mesa"];
                      $id_tipo=$rw["id_tipo"];
                      $nombre_tipo=$rw["nombre_tipo"];
                      $nombre_area=$rw["nombre_area"];
                      $numero_mesa=$rw["numero_mesa"];
                      if($nombre_area=="Mesa Dividida"){

                      }else{
                        ?>

                        <option value="<?php echo $id_mesa?>" name="id_mesa_fk" class="id_mesa_fk"><?php echo $nombre_tipo." - ".$numero_mesa."Area: ".$nombre_area ?></option>
                        <?php
                      }
                    }
                      ?>
                    </select>

                  <?php } } ?>

                  <label for="">Seleccionar Cliente</label>
                  <select class="form-control input-sm id_cliente_fk" name="id_cliente_fk">
                    <option value="10" selected>NO REGISTRA</option>
                    <?php
                    $sql_vendedor=mysqli_query($sqlconnection,"select * from cliente order by nombres");
                    while ($rw=mysqli_fetch_array($sql_vendedor)){
                      $id_cliente=$rw["id_cliente"];
                      $nombre_cliente=$rw["nombres"];
                      $identificacion=$rw["identificacion"];

                      ?>

                      <option value="<?php echo $id_cliente?>" name="id_cliente_fk" class="id_cliente_fk"><?php echo $identificacion." - ".$nombre_cliente?></option>
                      <?php
                    }
                    ?>
                  </select>
                  <?php if ($tipo_negocio=="Restaurante"){ ?>
                    <label for="">Nombre Opcional (Apodo) </label>
                <input type="text" value="" class="form-control" placeholder="Nombre opcional" name="nombre_opcional">
                  <?php } ?>
                  

                  <table id="tblOrderList" class="table table-bordered text-center  " width="100%" cellspacing="0">
                    <thead class="bg-teal"> 
                     <tr>
                      <th>Nombre</th>
                      <th>Precio</th>
                      <th>Cant</th>
                      <th>Total (COP)</th>
                      <th>X</th>
                    </tr>
                  </thead>
                  <tbody id="tbody">

                  </tbody>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>Total</th>
                    </tr>
                  </tfoot>
                </table>

                <center>
                  <label for=""><B>Ingrese Descuento Ej 50% - Solo Empleados</B></label>
                </center>
                <input type="number" name="descuento" class="form-control" placeholder="" value="0">


                <br>
                <label for="">Observación / Domicilio</label>
                <textarea name="observacion_order" id="compose-textarea" cols="30" rows="10" class="form-control">
                </textarea>
                <input class="btn btn-success btn-block" type="submit" name="sentorder" value="Enviar">
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card mb-3">
            <div class="card-header bg-navy">
              <i class="fas fa-utensils"></i>
            Inventario</div>
            <div class="card-body">
             <div id="qtypanel" hidden="" class="d-none d-xl-block d-lg-block d-md-block">
              <center>            
                Cantidad: <input id="qty" required="required" type="number"  name="qty" min="1" value="1"  />
                <button class="btn btn-info enviar" onclick = "insertItem()">Enviar</button>
                <br><br>
              </center>
            </div>
            <table class="col-md-12 table table-bordered text-center" width="100%" cellspacing="0">
             <tr>
               <?php 
               $menuQuery = "SELECT * FROM tbl_menu";

               if ($menuResult = $sqlconnection->query($menuQuery)) {
                 $counter = 0;
                 while($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC)) { 
                  if ($counter >=2) {
                   echo "</tr>";
                   $counter = 0;
                 }

                 if($counter == 0) {
                   echo "<tr>";
                 } 
                 ?>
                 <td class="col-md-6"><button style="margin-bottom:4px;white-space: normal;" class="btn btn-primary btn-block" onclick="displayItem(<?php echo $menuRow['menuID']?>)"> <B><?php echo $menuRow['menuName']?></B></button>
                 </td>
                 <?php

                 $counter++;
               }
             }
             ?>
           </tr>
         </table>
       </div>
     </div>
   </div>
   <div class="col-lg-6">
    <div class="card mb-3">
      <div class="card-header bg-navy">
        <i class="fas fa-plus"></i>
      Productos</div>
      <table id="tblItem" class="table table-bordered text-center" width="100%" cellspacing="0">
      </table>
      <div id="qtypanel" hidden="" class="d-block d-sm-block d-xl-none d-md-none d-lg-none">
        <center>            
          Cantidad: <input id="qty" required="required" type="number"  name="qty" min="1" value="1"  />
          <button class="btn btn-info enviar" onclick = "insertItem()">Enviar</button>
          <br><br>
        </center>
      </div>
    </div>
  </div>
</div>
</div>

</div>
</div>
</div>

<!-- /.container-fluid -->



</div>
<!-- /.content-wrapper -->
</section>

</div>
<!-- /#wrapper -->





<script>

 $(function() {
  var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });

  $('.enviar').click(function() {
    Toast.fire({
      icon: 'success',
      title: 'Producto agregado con éxito'
    })
  });


});


      if (window.history.replaceState) { // verificamos disponibilidad
        window.history.replaceState(null, null, window.location.href);
      }
      var currentItemID = null;

      function displayItem (id) {
       $.ajax({
        url : "displayitem.php",
        type : 'POST',
        data : { btnMenuID : id },

        success : function(output) {
          $("#tblItem").html(output);
        }
      });
     }

     function insertItem () {
       var id = currentItemID;
       var quantity = $("#qty").val();
       $.ajax({
        url : "displayitem.php",
        type : 'POST',
        data : { 
          btnMenuItemID : id,
          qty : quantity 
        },

        success : function(output) {
          $("#tbody").append(output);
          $("#qtypanel").prop('hidden',true);
          const formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 0
          })
      //Defino los totales de mis 2 columnas en 0
      var total_col1 = 0;
      var total_col2 = 0;
      var total_col3 = 0;
      var total_col4 = 0;
  //Recorro todos los tr ubicados en el tbody
  $('#tblOrderList #tbody').find('tr').each(function (i, el) {

        //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
        total_col1 += parseFloat($(this).find('td').eq(0).text());
        total_col2 += parseFloat($(this).find('td').eq(1).text());
        total_col3 += parseFloat($(this).find('td').eq(2).text());
        total_col4 += parseFloat($(this).find('td').eq(3).text());

      });
    //Muestro el resultado en el th correspondiente a la columna
    $('#tblOrderList tfoot tr th').eq(0).text("-");
    $('#tblOrderList tfoot tr th').eq(1).text("-");
    $('#tblOrderList tfoot tr th').eq(2).text("-");
    $('#tblOrderList tfoot tr th').eq(3).text("Total " + formatter.format(total_col4));
  }
});

       $("#qty").val(1);
     }

     function setQty (id) {
       currentItemID = id;
       $("#qtypanel").prop('hidden',false);
     }

     $(document).on('click','.deleteBtn', function(event){

      var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });


      Toast.fire({
        icon: 'error',
        title: 'Producto eliminado del pedido.'
      })



      event.preventDefault();
      $(this).closest('tr').remove();

   //Defino los totales de mis 2 columnas en 0
   var total_col1 = 0;
   var total_col2 = 0;
   var total_col3 = 0;
   var total_col4 = 0;
  //Recorro todos los tr ubicados en el tbody
  $('#tblOrderList #tbody').find('tr').each(function (i, el) {

        //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
        total_col1 += parseFloat($(this).find('td').eq(0).text());
        total_col2 += parseFloat($(this).find('td').eq(1).text());
        total_col3 += parseFloat($(this).find('td').eq(2).text());
        total_col4 += parseFloat($(this).find('td').eq(3).text());

      });
    //Muestro el resultado en el th correspondiente a la columna
    $('#tblOrderList tfoot tr th').eq(0).text("-");
    $('#tblOrderList tfoot tr th').eq(1).text("-");
    $('#tblOrderList tfoot tr th').eq(2).text("-");
    $('#tblOrderList tfoot tr th').eq(3).text("Total $" + total_col4);
    return false;
  });


</script>

<?php include ('includes/adminfooter.php');?>