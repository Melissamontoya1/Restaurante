<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
include("editarEntrada.php");
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Administrar Entradas </h1>
           </div>
           <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                  <li class="breadcrumb-item active">Stock</li>
              </ol>
          </div>
      </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
     <div class="row">
        <div class="col md-12" >
           <div class="card-body ">
              <table class="display table table-hover text-center " width="100%" cellspacing="0">
                 <thead>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Fecha </th>
                    <th>Cantidad Stock</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                    <th>Responsable</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </thead>
                <tbody >
                    <?php 

                    $Entradas =  "
                    SELECT i.menuItemName,i.itemID,e.id_stock,e.fecha_stock,e.cantidad_stock,e.precio_compra,e.precio_venta,e.itemID_fk,e.vendedor
                    FROM entrada_stock e 
                    INNER JOIN tbl_menuitem i 
                    ON e.itemID_fk=i.itemID
                    ORDER BY e.id_stock DESC";
                    if ($orderResultPro = $sqlconnection->query($Entradas)) {
                       if ($orderResultPro->num_rows == 0) {
                          echo "ERROR";
                      }else {

                          while($filaPro = $orderResultPro->fetch_array(MYSQLI_ASSOC)) {
                             $id_entrada=$filaPro['id_stock'];
                             $menuItemName=$filaPro['menuItemName'];
                             $fecha_stock=$filaPro['fecha_stock'];
                             $cantidad_stock=$filaPro['cantidad_stock'];
                             $precio_compra=$filaPro['precio_compra'];
                             $precio_venta=$filaPro['precio_venta'];
                             $vendedor=$filaPro['vendedor'];
                             $itemID_fk=$filaPro['itemID_fk'];
                             ?>  
                             <tr>
                                <td><?php echo $id_entrada; ?></td>
                                <td><?php echo $menuItemName; ?></td>
                                <td><?php echo $fecha_stock; ?></td>
                                <td><?php echo $cantidad_stock; ?></td>
                                <td><?php echo number_format($precio_compra); ?></td>
                                <td><?php echo number_format($precio_venta); ?></td>
                                <td><?php echo $vendedor; ?></td>
                                <td class="text-center" >
                                   <a class="btn btn-warning" href="#editarEntrada" data-toggle="modal" data-id_entrada="<?php echo $id_entrada ?>" data-cantidad_stock="<?php echo $cantidad_stock ?>" data-precio_venta="<?php echo $precio_venta ?>" data-precio_compra="<?php echo $precio_compra ?>" 
                                    data-fecha_stock="<?php echo $fecha_stock ?>" data-itemname="<?php echo $menuItemName ?>" data-vendedor="<?php echo $vendedor ?>"><i class="fas fa-edit"></i> </a>
                               </td>
                               <td class="text-center" >
                                   <a class="btn btn-danger" href="eliminarentrada.php?id_entradas=<?php echo $id_entrada ?>"> <i class="fas fa-trash" readonly></i></a>
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
<!-- /#NUEVO STOCK AL INVENTARIO -->
<div class="modal fade" id="editarEntrada" tabindex="-1" role="dialog" aria-labelledby="addStock" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStock">Añadir Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editarFentrada" method="POST">
                    <div class="row">
                        <div class="form-group col-md-6">
                            
                            <input type="number" id="id_stock" name="id_stock">
                            <label class="col-form-label ">Fecha Ingreso</label>
                            <input type="date" class="form-control" name="fecha_stock" required id="fecha_stock">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Cantidad </label>
                            <input type="number"  class="form-control cantidad_stock" name="cantidad_stock" placeholder="Ingrese la cantidad del producto" required >
                           <!--  STOCK ACTUAL PARA PODER SER RESTADO DEL STOCK DEL PRODUCTO -->
                            <input type="hidden"  class="form-control cantidad_stock" name="cantidad_stock_anterior" required readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Precio Compra</label>
                            <input type="number"  class="form-control" name="precio_compra" placeholder="$2000" id="precio_compra">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Precio Venta</label>
                            <input type="number"  class="form-control" name="precio_venta" placeholder="$5000" id="precio_venta" >
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="editarFentrada" class="btn btn-success" name="Editentrada">Editar</button>
            </div>
        </div>
    </div>
</div>
<?php include ('includes/adminfooter.php');?>
<script>
            //MODAL DE AGRAGAR STOCK
            $('#editarEntrada').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget); // Button that triggered the modal
              var itemname = button.data('itemname'); // Extract info from data-* attributes
              var id_stock = button.data('id_entrada');
              
              var fecha_stock = button.data('fecha_stock');
              var cantidad_stock = button.data('cantidad_stock');
              var precio_venta = button.data('precio_venta');
              var precio_compra = button.data('precio_compra');
             

              // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
              var modal = $(this);
              modal.find('.modal-title').text('Editar la entrada de - ' + itemname );
              modal.find('.modal-body #id_stock').val(id_stock);
              modal.find('.modal-body #fecha_stock').val(fecha_stock);
              modal.find('.modal-body .cantidad_stock').val(cantidad_stock);
              modal.find('.modal-body #precio_venta').val(precio_venta);
              modal.find('.modal-body #precio_compra').val(precio_compra);
              
          });


      </script>