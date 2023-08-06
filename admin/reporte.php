<?php


$host = "localhost";
$usuario = "root";
$contraseña = "";
$base = "fosdb";

$conexion= new mysqli($host, $usuario, $contraseña, $base);

if ($conexion -> connect_errno)
{
	die("Fallo la conexion:(".$conexion -> mysqli_connect_errno().")".$conexion-> mysqli_connect_error());
}



$fecha1=$_POST['fecha1'];
$fecha2=$_POST['fecha2'];
$menu=$_POST['menu'];
$item=$_POST['item'];
$estado=$_POST['estado'];
?>

<?php  
if(isset($_POST['generar_reporte']))
{
	if ($menu=="todo" AND $item=="todoitem") {
	// NOMBRE DEL ARCHIVO Y CHARSET
		header("Content-type: application/vnd.ms-excel; charset=iso-8859-1");
		header('Content-Disposition: attachment; filename="Reporte_General_Ventas"'.$fecha1.'"-"'.$fecha2.'".xls"');
		?>
		<P><B>Fecha de Consulta</B> <?php echo "Desde  ".$fecha1."  Hasta  ".$fecha2 ?> </P>
		<p></p>

		<br><br>

		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" border="1">
			<tr>
				<th colspan="11"><h2>Tabla de Ventas / Creditos</h2></th>
			</tr>
			<tr>
				<th>Fecha</th>
				<th>Factura #</th>
				<th>Tipo Factura </th>
				<th>Categoria</th>
				<th>Nombre</th>
				<th>Estado Producto</th>
				<th>Precio Costo</th>
				<th>Precio Venta</th>
				<th>Cantidad</th>
				<th>Estado Factura</th>
				<th>Motivo Anulacion</th>
				<th>Subtotal Venta</th>
				<th>Descuento</th>
				<th>Total Venta</th>
				<th>Utilidad</th>
			</tr>
			<?php
					//CONSULTA GASTOS
			$reporteCsv=$conexion->query("SELECT o.orderID,o.descuento, m.menuName, od.itemID,mi.menuItemName,mi.estado,od.quantity,o.status,mi.price,mi.precio_costo,o.order_date,od.utilidad_venta,o.motivo_anulacion
				FROM tbl_order o
				LEFT JOIN tbl_orderdetail od
				ON o.orderID = od.orderID
				LEFT JOIN tbl_menuitem mi
				ON od.itemID = mi.itemID
				LEFT JOIN tbl_menu m
				ON mi.menuID = m.menuID
				WHERE o.status='Vendido' OR o.status='Credito' AND mi.estado='$estado' AND o.order_date BETWEEN '$fecha1' AND '$fecha2'
				ORDER BY o.orderID DESC");
			$acumtotal=0;
			$acumsub=0;
			$acumutilidad=0;
			while($filaR= $reporteCsv->fetch_array(MYSQLI_ASSOC)){
				$estado=$filaR['status'];
				$descuento=$filaR['descuento'];
				$total=($filaR['quantity']*$filaR['price']);
				$des=(($descuento*$total)/100);
				$desfinal=$total-$des;
				$acumsub+=$total;
				$acumtotal+=$desfinal;
				$utilidad=$desfinal-$filaR['utilidad_venta'];
				
				if ($estado=="Anulada") {
					$utilidad_final=0;
				}else{
					$utilidad_final=$utilidad;
				}
				$acumutilidad+=$utilidad_final;
				?>
				<tr>

					<td><?php echo $filaR['order_date'] ?></td>
					<td><?php echo $filaR['orderID'] ?></td>
					<td><?php echo $filaR['status'] ?></td>
					<td><?php echo $filaR['menuName'] ?></td>
					<td><?php echo $filaR['menuItemName'] ?></td>
					<td><?php echo $filaR['estado'] ?></td>
					<td><?php echo $filaR['precio_costo'] ?></td>
					<td><?php echo $filaR['price'] ?></td>
					<td><?php echo $filaR['quantity'] ?></td>
					<td><?php echo $filaR['status'] ?></td>
					<td class="bg-danger"><?php echo $filaR['motivo_anulacion'] ?></td>
					<td><?php echo $total ?></td>
					<td><?php echo $descuento ?>%</td>
					<td><?php echo $desfinal ?></td>
					<td><?php echo  $utilidad_final ?></td>
					<br>
					<br>
					<br>

				</tr>
				<?php 
			}

			?>
			<tr>
				<td colspan="8"><center><h4>Total Ventas</h4></center></td>
				<td></td>
				<td></td>
				<td></td>
				<td><B><?php echo $acumsub ?></B></td>
				<td></td>
				<td><B><?php echo $acumtotal ?></B></td>
				<td><B><?php echo $acumutilidad ?></B></td>
			</tr>

		</table>
		<br><br>
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" border="1">
			<tr>
				<th colspan="4"><h2>Tabla de Gastos</h2></th>
			</tr>
			<tr>
				<th>Gasto#</th>
				<th>Fecha Gasto</th>
				<th>Descripcion</th>
				<th>Valor Gasto</th>
			</tr>
			<?php
					//CONSULTA GASTOS
			$gastoReporte=$conexion->query("SELECT * FROM gastos
				WHERE  fecha_gasto BETWEEN '$fecha1' AND '$fecha2'
				ORDER BY fecha_gasto DESC");
			$acumgastos=0;
			while($filaR= $gastoReporte->fetch_array(MYSQLI_ASSOC)){
				
				$total_gas=$filaR['valor_gasto'];
				$acumgastos+=$total_gas;
				?>
				<tr>

					<td><?php echo $filaR['id_gasto'] ?></td>
					<td><?php echo $filaR['fecha_gasto'] ?></td>
					<td><?php echo $filaR['descripcion_gasto'] ?></td>
					<td><?php echo $filaR['valor_gasto'] ?></td>
				</tr>

				<?php 
			}

			?>
			<tr>
				<td colspan="3"><center><h4>Total Gastos</h4></center></td>
				<td><B><?php echo $acumgastos ?></B></td>
			</tr>

		</table>
		<br><br>
		<!-- TABLA CUENTAS -->
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" border="1">
			<tr>
				<th colspan="4"><h2>Tabla de Cuentas</h2></th>
			</tr>
			<tr>
				
				<th class='text-center'># Cuenta</th>
				<th class='text-center'>Fecha Cuenta</th>
				<th class='text-center'>Nombre Cuenta</th>
				<th class='text-center'>Valor</th>
				
			</tr>
			<?php
					//CONSULTA GASTOS
			$cuentas=$conexion->query("SELECT c.id_caja, c.nombre_caja, c.estado_caja,p.id_pago, p.orderID, p.id_caja_fk, p.monto_recibido, SUM(p.pago_orden) AS totalc, p.devuelto, p.fecha_pago
				FROM pagos p
				INNER JOIN tipo_caja c
				ON p.id_caja_fk=c.id_caja
				WHERE  p.fecha_pago BETWEEN '$fecha1' AND '$fecha2'
				GROUP BY p.id_caja_fk
				ORDER BY p.id_caja_fk  ASC ");
			
			while($cta= $cuentas->fetch_array(MYSQLI_ASSOC)){
				
				
				?>
				<tr>
					
					<td><?php echo $cta['id_caja_fk'] ?></td>
					<td><?php echo $cta['order_date'] ?></td>
					<td><?php echo $cta['nombre_caja'] ?></td>
					<td><?php  echo $cta['totalc'] ?></td>
					

				</tr>
				<?php 
			}
			
			?>


		</table>
		<br><br>
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" border="1">
			<tr>
				<th colspan="3"><h2>Tabla de Propinas</h2></th>
			</tr>
			<tr>
				<th class='text-center'># Factura</th>
				<th class='text-center'>Fecha Propina</th>
				<th class='text-center'>Valor Propina</th>
			</tr>
			<?php
					//CONSULTA GASTOS
			$saldoPropina=$conexion->query("SELECT *
				FROM propinas
				WHERE  fecha_propina BETWEEN '$fecha1' AND '$fecha2'
				ORDER BY fecha_propina DESC");
			$acumpropina=0;
			while($filaP= $saldoPropina->fetch_array(MYSQLI_ASSOC)){
				$valor_propina=$filaP['valor_propina'];
				$acumpropina+=$valor_propina;
				if($valor_propina>0){
					?>
					<tr>

						<td><?php echo $filaP['cod_venta'] ?></td>
						<td><?php echo $filaP['fecha_propina'] ?></td>
						<td><?php echo $filaP['valor_propina'] ?></td>
						<br>
						<br>
						<br>

					</tr>
					<?php 
				}
			}
			
			?>
			<tr>
				<td colspan="2"><center><h4>Total Propinas</h4></center></td>
				<td><B><?php echo $acumpropina ?></B></td>
				
			</tr>

		</table>
		<br><br>
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" border="1">
			<tr>
				<th colspan="6"><h2>Tabla de Cierres</h2></th>
			</tr>
			<tr>
				<th class='text-center'># Cierre</th>
				<th class='text-center'>Fecha Cierre</th>
				<th class='text-center'>Base</th>
				<th class='text-center'>Total Ventas</th>
				<th class='text-center'>Total Gastos</th>
				<th class='text-center'>Total Cierre</th>
			</tr>
			<?php
					//CONSULTA GASTOS
			$basedia=$conexion->query("SELECT *
				FROM base
				WHERE  fecha_base BETWEEN '$fecha1' AND '$fecha2'
				ORDER BY fecha_base DESC");
			$acumbase=0;
			while($filaR= $basedia->fetch_array(MYSQLI_ASSOC)){
				$basetotal=$filaR['valor_base'];
				$acumbase+=$basetotal;
				?>
				<tr>

					<td><?php echo $filaR['id_base'] ?></td>
					<td><?php echo $filaR['fecha_base'] ?></td>
					<td><?php echo $filaR['valor_base'] ?></td>
					<td><?php echo $filaR['ventas_cierre'] ?></td>
					<td><?php echo $filaR['gastos_cierre'] ?></td>
					<td><?php echo $filaR['utilidad_cierre'] ?></td>
					<br>
					<br>
					<br>

				</tr>
				<?php 
			}
			$sub=$acumtotal+$acumbase;
			$ganancia=$sub-$acumgastos;
			?>
			<tr>
				<td colspan="2"><center><h4>Total General</h4></center></td>
				<td><B><?php echo $acumbase ?></B></td>
				<td><B><?php echo $acumtotal ?></B></td>
				<td><B><?php echo $acumgastos ?></B></td>
				<td><B><?php echo $ganancia ?></B></td>
			</tr>

		</table>
		
		
		<?php  
	}else{
		// NOMBRE DEL ARCHIVO Y CHARSET
		header("Content-type: application/vnd.ms-excel; charset=iso-8859-1");
		header('Content-Disposition: attachment; filename="Reporte_Ventas"'.$fecha1.'"-"'.$fecha2.'".xls"');?>

		<P><B>Fecha de Consulta</B> <?php echo "Desde  ".$fecha1."  Hasta  ".$fecha2 ?> </P>
		<p><h2>Tabla de ventas Segun Categoria </h2></p>
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" border="1">
			<tr>

				<td>Fecha</td>
				<td>Factura #</td>
				<td>Categoria</td>
				<td>Nombre</td>
				<td>Estado Producto</td>
				<td>Precio</td>
				<td>Cantidad</td>
				<td>Valor Venta</td>

			</tr>
			<?php
							//CONSULTA GASTOS
			$reporteCsv2=$conexion->query("SELECT o.orderID, m.menuName, od.itemID,mi.menuItemName,mi.estado,od.quantity,o.status,mi.price,o.order_date
				FROM tbl_order o
				LEFT JOIN tbl_orderdetail od
				ON o.orderID = od.orderID
				LEFT JOIN tbl_menuitem mi
				ON od.itemID = mi.itemID
				LEFT JOIN tbl_menu m
				ON mi.menuID = m.menuID
				WHERE order_date BETWEEN '$fecha1' AND '$fecha2' AND m.menuName='$menu' AND mi.estado='$estado' AND o.status='Vendido' AND
				ORDER BY o.orderID DESC");

			while($filaR= $reporteCsv2->fetch_array(MYSQLI_ASSOC)){
				$total=($filaR['quantity']*$filaR['price']);?>
				<tr>

					<td><?php echo $filaR['order_date'] ?></td>
					<td><?php echo $filaR['orderID'] ?></td>
					<td><?php echo $filaR['menuName'] ?></td>
					<td><?php echo $filaR['menuItemName'] ?></td>
					<td><?php echo $filaR['estado'] ?></td>
					<td><?php echo $filaR['price'] ?></td>
					<td><?php echo $filaR['quantity'] ?></td>
					<td><?php echo $total ?></td>
					<br>
					<br>
					<br>

				</tr>
				<?php 
			}
			?>
		</table>
	<?php  }
}
?>




