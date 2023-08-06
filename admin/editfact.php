<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
$idfact= $_GET['orderID'];
//$id_mesa= $_GET['id_mesa'];
$sqldetalles=mysqli_query($sqlconnection,"SELECT o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
  FROM tbl_order o
  INNER JOIN tbl_orderdetail od
  ON o.orderID = od.orderID
  INNER JOIN tbl_menuitem mi
  ON od.itemID = mi.itemID
  INNER JOIN tbl_menu m
  ON mi.menuID = m.menuID
  INNER JOIN cliente c
  ON o.id_cliente_fk = c.id_cliente
  INNER JOIN mesa me
  ON o.id_mesa_fk = me.id_mesa
  INNER JOIN area_mesa a
  ON me.id_area_fk=a.id_area
  INNER JOIN tipo_mesa t 
  ON me.id_tipo_fk= t.id_tipo
  WHERE o.orderID='{$idfact}'");
while ($fila=mysqli_fetch_array($sqldetalles)){ 
 $idfactu=$fila["orderID"];
 $id_mesa1=$fila["id_mesa"];
 $id_tipo1=$fila["id_tipo"];
 $tipo_mesa1=$fila["nombre_tipo"];
 $nombre_area1=$fila["nombre_area"];
 $numero_mesa1=$fila["numero_mesa"];
 $id_cliente1=$fila["id_cliente"];
 $nombre_cliente1=$fila["nombres"];
 $identificacion1=$fila["identificacion"];
}
?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Información Del Pedido</h1>
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


      <!-- Page Content -->
      <h3>Orden # <?php echo $idfactu ?> <?php echo "Area : ".$nombre_area1." / ".$tipo_mesa1." # ".$numero_mesa1 ?></h3>
      <hr>
      <p>Puedes eliminar los productos que han sido devueltos y agregar mas</p>

      <div class="row">
        <div class="col-lg-12">
          <div class="card mb-3">
            <div class="card-header bg-navy">
              <i class="fas fa-chart-bar"></i>
              Pedido
            </div>
            <div class="card-body">
              <form action="editar_factura.php" method="POST" >
                <input type="hidden" name="mesa_anterior" value="<?php echo $id_mesa1 ?>">
                <label for="">Cambiar Cliente</label>
                <select class="form-control input-sm id_cliente_fk" name="id_cliente_fk">
                  <option value="<?php echo $id_cliente1 ?>" selected><?php echo $nombre_cliente1 ?></option>
                  <?php
                  $sql_vendedor=mysqli_query($sqlconnection,"select * from cliente order by nombres");
                  while ($rw=mysqli_fetch_array($sql_vendedor)){
                    $id_cliente=$rw["id_cliente"];
                    $nombre_cliente=$rw["nombres"];
                    $identificacion=$rw["identificacion"];

                    ?>

                    <option value="<?php echo $id_cliente?>" name="id_cliente_fk" class="id_cliente_fk" <?php echo $selected;?>><?php echo $identificacion." - ".$nombre_cliente?></option>
                    <?php
                  }
                  ?>
                </select>
                <label for="">Cambiar Mesa</label>
                <select class="form-control input-sm id_mesa_fk" name="id_mesa_fk" >
                  <option value="<?php echo $id_mesa1?>" name="id_mesa_fk" class="id_mesa_fk"><?php echo $tipo_mesa1." - ".$numero_mesa1." Area: ".$nombre_area1 ?></option>
                  <?php
                  $sql_mesa=mysqli_query($sqlconnection,"SELECT m.id_mesa,m.numero_mesa,m.estado_mesa,m.id_area_fk,m.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
                    FROM mesa m
                    INNER JOIN area_mesa a
                    ON m.id_area_fk=a.id_area
                    INNER JOIN tipo_mesa t 
                    ON m.id_tipo_fk= t.id_tipo ");
                  while ($rw2=mysqli_fetch_array($sql_mesa)){
                    $id_mesa=$rw2["id_mesa"];
                    $id_tipo=$rw2["id_tipo"];
                    $nombre_tipo=$rw2["nombre_tipo"];
                    $nombre_area=$rw2["nombre_area"];
                    $numero_mesa=$rw2["numero_mesa"];

                    ?>

                    <option value="<?php echo $id_mesa?>" name="id_mesa_fk" class="id_mesa_fk"><?php echo $nombre_tipo." - ".$numero_mesa."  Area: ".$nombre_area ?></option>
                    <?php
                  }
                  ?>
                </select>
                <table id="tblOrderList" class="table table-bordered text-center" width="100%" cellspacing="0">
                  <thead>
                   <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cant</th>
                    <th>Total (COP)</th>
                    <th>Remover</th>
                  </tr>
                </thead>
                <tbody id="tbody">
                 <?php
                 
                 $sql_vendedor=mysqli_query($sqlconnection,"SELECT o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date
                   FROM tbl_order o
                   LEFT JOIN tbl_orderdetail od
                   ON o.orderID = od.orderID
                   LEFT JOIN tbl_menuitem mi
                   ON od.itemID = mi.itemID
                   LEFT JOIN tbl_menu m
                   ON mi.menuID = m.menuID
                   WHERE o.orderID='$idfactu'");
                 while ($rw=mysqli_fetch_array($sql_vendedor)){    

                  echo "<tr>
                  <input type='hidden' value='".$rw['orderID']."' name='orderID'>

                  <input type = 'hidden' name = 'itemID[]' value ='".$rw['itemID']."'/>
                  <td>".$rw['menuName']." : ".$rw['menuItemName']."</td>
                  <td><input type = 'number' required='required' name = 'precio[]' width='10px' class='form-control' value ='".$rw['price']."' readonly /></td>
                  <td><input type = 'number' required='required' name = 'itemqty[]' width='10px' class='form-control' value ='".$rw['quantity']."'/></td>
                  <td>".$rw['totalv']."</td>
                  <td><button class='btn btn-danger deleteBtn' type='button' onclick='deleteRow()'><i class='fas fa-times'></i></button></td>
                  </tr>
                  ";
                }
                ?>

              </tbody>
              <tfoot>
                <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th ><B>Total</B></th>
                </tr>
              </tfoot>
            </table>
            <!-- <input type="number" name="pago" class="pago form-control" placeholder="Dinero Recibido" required=""> <br> -->
            <input class="btn btn-success btn-block" type="submit" name="editorder" value="Enviar">
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
         <div id="qtypanel" hidden="">
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
    <table id="tblItem" class="table table-bordered text-center" width="100%" cellspacing="0"></table>

  </div>
</div>
</div>

</div><!-- /.container-fluid -->

</section>

</div><!-- /.content-wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>



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
<script type="text/javascript">
  $(document).ready(function(){
  if (window.history.replaceState) { // verificamos disponibilidad
    window.history.replaceState(null, null, window.location.href);
  }
}
</script>
<?php include ('includes/adminfooter.php');?>