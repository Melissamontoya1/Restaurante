<?php
include("../functions.php");
include('includes/adminheader.php');
include ('includes/adminnav.php');
include('addstaff.php');
if (!empty($_POST['role'])) {
  $role = $sqlconnection->real_escape_string($_POST['role']);
  $staffID = $sqlconnection->real_escape_string($_POST['staffID']);

  $updateRoleQuery = "UPDATE tbl_staff SET role = '{$role}'  WHERE staffID = {$staffID}  ";

  if ($sqlconnection->query($updateRoleQuery) === TRUE) {
    echo "";
  } 

  else {
        //handle
    echo "someting wong";
    echo $sqlconnection->error;
  }
}
$id_empresa="1";
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
    }
  }
}

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header ">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Administración de Empleados</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Empleados</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <div class="card mb-3">
            <div class="card-header bg-primary">
              <i class="fas fa-user" ></i>
            Agregar Nuevo Empleado</div>
            <div class="card-body">
              <center>
                <form action="" method="POST" class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                  <div class="input-group">
                    <select name="staffrole" class="form-control">
                      <?php

                      $roleQuery = "SELECT role FROM tbl_role";

                      if ($res = $sqlconnection->query($roleQuery)) {

                        if ($res->num_rows == 0) {
                          echo "no role";
                        }

                        while ($role = $res->fetch_array(MYSQLI_ASSOC)) {
                          echo "<option value='".$role['role']."'>".ucfirst($role['role'])."</option>";
                        }
                      }

                      ?>
                    </select>
                    <input type="text" required="required" name="staffname" class="form-control" placeholder="Usuario" aria-label="Add" aria-describedby="basic-addon2">
                    <input type="text" required="required" name="password" class="form-control" placeholder="Contraseña">
                    <div class="input-group-append">
                      <button type="submit" name="addstaff" class="btn btn-success">
                        <i class="fas fa-plus"></i>
                      </button> 
                    </div>
                  </div>
                </form>
              </center>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="card mb-3">
            <div class="card-header bg-navy">
              <i class="fas fa-user-circle"></i>
            Lista Actual de Empleados</div>
            <div class="card-body">
              <table class="display table table-bordered text-center "  width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                    <th>Estado</th>
                    <th>Cargo</th>
                    <th class="text-center">Opción</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 

                  $displayStaffQuery = "SELECT * FROM tbl_staff ORDER BY staffID DESC ";

                  if ($result = $sqlconnection->query($displayStaffQuery)) {

                    if ($result->num_rows == 0) {
                      
                    }

                    $staffno = 1;
                    while($staff = $result->fetch_array(MYSQLI_ASSOC)) {
                      ?>  
                      <tr class="text-center">
                       <td><?php echo $staffno++; ?></td>
                       <td><?php echo $staff['username']; ?></td>
                       <td><?php echo $staff['password']; ?></td>
                       <?php

                       if ($staff['status'] == "Online") {
                        echo "<td><span class=\"badge badge-success\">En línea</span></td>";
                      }

                      if ($staff['status'] == "Offline") {
                        echo "<td><span class=\"badge badge-secondary\">Fuera de línea</span></td>";
                      }

                      ?>

                      <td>
                        <form method="POST">
                          <input type="hidden" name="staffID" value="<?php echo $staff['staffID']; ?>"/>
                          <select name="role" class="form-control" onchange="this.form.submit()">
                            <?php

                            $roleQuery = "SELECT role FROM tbl_role";

                            if ($res = $sqlconnection->query($roleQuery)) {

                              if ($res->num_rows == 0) {
                                echo "no role";
                              }

                              while ($role = $res->fetch_array(MYSQLI_ASSOC)) {

                                if ($role['role'] == $staff['role']) 
                                  echo "<option selected='selected' value='".$staff['role']."'>".ucfirst($staff['role'])."</option>";

                                else
                                  echo "<option value='".$role['role']."'>".ucfirst($role['role'])."</option>";
                              }
                            }

                            ?>
                          </select>
                          <noscript><input type="submit" value="Enviar"></noscript>
                        </form>
                      </td>

                      <td class="text-center"><a href="eliminar_empleado.php?staffID=<?php echo $staff['staffID']; ?>" class="btn btn-sm btn-danger">Eliminar</a></td>
                    </tr>

                    <?php 
                  }

                }
                else {
                  echo $sqlconnection->error;
                  echo "Something wrong.";
                }

                ?>
              </tbody>
            </table>
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

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>



<?php include ('includes/adminfooter.php');?>