<?php
include("../functions.php");
$idfact= $_GET['orderID'];
include('includes/adminheader.php');
include ('includes/adminnav.php');
$id=0;
$id_mesa=0;
$id= $_GET['orderID'];
$id_mesa= $_GET['id_mesa'];
$descuento=0;
$sql_vendedor=mysqli_query($sqlconnection,"SELECT  o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,o.descuento,o.total,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
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
  WHERE o.orderID='{$id}' OR o.id_mesa_fk='{$id_mesa}'");
while ($rw1=mysqli_fetch_array($sql_vendedor)){ 
  $id=$rw1['orderID'];
  $tipo_mesa=$rw1['nombre_tipo'];
  $numero_mesa=$rw1['numero_mesa'];
  $nombre_cliente=$rw1['nombres'];
  $identificacion=$rw1['identificacion'];
  $id_cliente_fk=$rw1['id_cliente'];
  $total=$rw1['total'];
  $descuento=$rw1['descuento'];
  $fecha=$rw1['order_date'];
}

$descripcion_ingreso=$prefijo."-".$id;
$estado_ingreso="venta";
$addStaffQuery = "INSERT INTO ingresos_caja  (fecha_ingreso,descripcion_ingreso,estado_ingreso,valor_ingreso,orderID_fk) VALUES ('{$fecha}','{$descripcion_ingreso}','{$estado_ingreso}','{$total}','{$id}') ";

if ($sqlconnection->query($addStaffQuery) === TRUE) {
  echo 'insertado';


}else {
          //handle
  echo "someting wong";
  echo $sqlconnection->error;
}
$des=0;
if($descuento==="0"){
  $total_desc1=$total;
}else{
  $des=(($descuento*$total)/100);
  $total_desc1=($total-$des);
}


$editfact = "UPDATE tbl_order SET total = '{$total_desc1}',total_desc = '{$des}' WHERE orderID = '{$id}'";  

if ($sqlconnection->query($editfact) === TRUE) {
  //echo "inserted.";
} 

else {
        //handle
  echo "someting wong";
  echo $sqlconnection->error;

}
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Pagar Pedido</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Cuenta por pagar</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
       <div class="col-lg-2"></div>
       <div class="col-lg-8" >
        <center>
          
          <div class="card mb-3" >
            <?php if ($tipo_negocio=="Restaurante"){ ?>
              <a href="dividir_mesa.php?id=<?php echo $id; ?>"><button class="btn btn-primary btn-block" title="Tomar orden"><i class="fas fa-cut"></i> Dividir Cuenta</button></a>
          <?php } ?>
         
           <div class="card-header bg-navy">
            <h3>Orden # <?php echo $idfact ?> | <?php echo $tipo_mesa."-".$numero_mesa; ?> </h3>

          </div>
          <div class="card-body">

            <form action="finalizar_factura.php" method="POST" class="formularioVenta">
              <select class="form-control input-sm id_cliente_fk" name="id_cliente_fk">
                <option value="<?php echo $id_cliente_fk; ?>" selected><?php echo $identificacion." - ".$nombre_cliente?></option>
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
              
              <table id="tblOrderList" class="table table-bordered text-center" width="100%" cellspacing="0">
                <thead>
                 <tr>
                  <th>Nombre</th>
                  <th>Precio</th>
                  <th>Cant</th>
                  <th>Total (COP)</th>
                </tr>
              </thead>
              <tbody style="overflow: scroll;" >
               <?php

               $sql_vendedor2=mysqli_query($sqlconnection,"SELECT  o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.total,o.descuento,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo,o.total_desc
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
                WHERE o.orderID='{$id}' ");
               while ($rw=mysqli_fetch_array($sql_vendedor2)){    
                $descuento=$rw['descuento'];
                $total_final=$rw['total'];
                $total_desc=$rw['total_desc'];
                $orderID=$rw['orderID'];
                $subtotal=$total_final+$total_desc;

                echo "<tr>

                
                <input type = 'hidden' name = 'itemID[]' value ='".$rw['itemID']."'/>
                <td>".$rw['menuName']." : ".$rw['menuItemName']."</td>
                <td><input type = 'number' required='required' name = 'precio[]' width='10px' class='form-control' value ='".$rw['price']."' readonly/></td>
                <td><input type = 'number' required='required' name = 'itemqty[]' width='10px' class='form-control' value ='".$rw['quantity']."' readonly/></td>
                <td>".$rw['totalv']."</td>

                </tr>
                ";
              }
              ?>

            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th></th>
                <th>Subtotal</th>
                <th>$ <?php echo number_format($subtotal);?></th>
              </tr>
            </tfoot>
          </table>
          <p><B>Descuento: <?php echo $descuento; ?>% - <?php echo number_format($total_desc); ?> </B></p><br>
          <?php if ($tipo_negocio=="Restaurante"){?>
           <div class="icheck-primary">
            <input type="checkbox" id="propinas" class="propinas" />
            <label for="propinas">Propina</label>
          </div>
        <?php }?>

        <div id="FormularioPropinas">
          <input type="hidden" id="porcentaje" value="<?php echo $porcentaje_propinas ?>">
          <input type="hidden" id="total_venta" value="<?php echo $total_final ?>">
          <label for="valor_propina1"> Valor de la Propina</label>
          <input type="number" value="" id="total_propina" class="propina" name="total_propina">
        </div>

        <input type="hidden" value="<?php echo $total_final;?>" id="total_final">
        <p><h4><B>Total a Pagar: <input type="number" id="totalFinal" value="<?php echo $total_final ?>" readonly></B></h4></p><br>

        <div class="form-group ">
          <label class="col-form-label ">Seleccione un método de pago</label>
          <table class="table pt-2"  id="tabla">
            <thead>
              <tr>
                <th>Cuenta</th>
                <th>Vlr Recibido</th>
                <th>Pago</th>

                <th>X</th>
              </tr>
            </thead>
            <tr class="fila-fija ">

              <td class="col-md-3">
                <select class="form-control input-sm " name="id_caja_fk[]">

                  <?php
                  $sql_caja=mysqli_query($sqlconnection,"select * from tipo_caja WHERE estado_caja='Activo' order by id_caja");
                  while ($rw=mysqli_fetch_array($sql_caja)){
                    $id_caja=$rw["id_caja"];
                    $nombre_caja=$rw["nombre_caja"];

                    ?>

                    <option value="<?php echo $id_caja?>" name="id_caja_fk[]" class="id_caja"><?php echo $id_caja." - ".$nombre_caja?></option>
                    <?php
                  }
                  ?>
                </select>
              </td> 
              <td class=" col-md-3">
                <input type='hidden' value='<?php echo $orderID ?>' name='orderID[]'>
                <input type='hidden' value='<?php echo $orderID ?>' name='orderIDMesa'>
                <input type="number" name="monto_recibido[]" class="recibido form-control" placeholder="Dinero Recibido" required step="any">

              </td>
              <td class=" col-md-3">
                <input type="number" class="pago form-control" placeholder="Pago" pago="<?php echo $total_final; ?>" value="<?php echo $total_final; ?>" name="pago[]" step="any" required id="Pagos"></td>

                <td class="eliminar col-md-1">
                  <input type="button" class="btn btn-danger"  value="X"/>
                </td>
              </tr>

            </table>
            <div class="row">

              <div class="col-md-6">
               <label for=""><B>TOTAL PAGOS</B></label>
               <input type="text" class="form-control input-lg" id="nuevoTotalVenta" name="nuevoTotalVenta" total="0" value="0" placeholder="0" readonly required>

             </div>
             <div class="col-md-6">
               <label for=""><B>POR PAGAR</B></label>
               <input type="text" class="form-control input-lg" id="TotalPagar" name="TotalPagar" totalP="<?php echo $total_final; ?>" value="<?php echo $total_final; ?>" placeholder="0" readonly required>

             </div>
           </div>
           <input type="hidden" name="totalVenta" id="totalVenta" value="<?php echo $total_final; ?>">
           <input type="hidden" name="totalVenta" id="totalVentaReal" value="<?php echo $total_final; ?>"><br>
           <button id="adicional" name="adicional" type="button" class="adicional btn btn-warning col-md-6"> <i class="fas fa-plus"></i> Agregar mas Pagos</button>

           <div class="pt-2">
            <div class="form-group ">


              <button class="btn btn-success btn-block" type="submit" name="pagar"  id="botonPagar">Pagar </button>
            </form>
          </div>
        </div>
        <div class="col-lg-2"></div>
      </center>
    </div>
  </div>

</div>


<!-- /.container-fluid -->

</section>

</div>
<!-- /.content-wrapper -->




<?php include ('includes/adminfooter.php');?>






