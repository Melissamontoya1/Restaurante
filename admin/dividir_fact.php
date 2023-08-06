  <?php
  include("../functions.php");
  include('includes/adminheader.php');
  include ('includes/adminnav.php');

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
            <h1>Dividir Cuenta</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Division</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <br>
          <div class="col-lg-12">
            <div class="card mb-3">
              <div class="card-header bg-navy" id="headingOne">
                <h5 class="mb-0">
                  Arrastre los elementos a cada de la factura a cliente 
                </h5>
              </div>
              <div class="card-header">
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
           </div>
         </div>
       </section>
     </div>


     <?php include ('includes/adminfooter.php');?>


