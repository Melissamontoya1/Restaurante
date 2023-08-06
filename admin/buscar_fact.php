<?php 

include("../functions.php");
// VARIABLE PARA REALIZAR LA CONSULTA 
$dato= $_POST['dato'];
// BUSQUEDA DE PREDICTIVA DE CLIENTES 
$displayOrderQuery = "SELECT o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo,o.nombre_opcional
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
WHERE  o.orderID LIKE '%{$dato}%'  OR c.nombres LIKE '%{$dato}%' OR o.nombre_opcional LIKE '%{$dato}%' ORDER BY o.orderID DESC" ;


if ($orderResult = $sqlconnection->query($displayOrderQuery)) {

	$currentspan = 0;
	$total = 0;
	if ($orderResult->num_rows > 0) {
		while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {
			$estado=$orderRow['status'];
			$motivo_anulacion=$orderRow['motivo_anulacion'];
			

                            //basically count rowspan so no repetitive display id in each table row
			$rowspan = getCountID($orderRow["orderID"],"orderID","tbl_orderdetail"); 

			if ($currentspan == 0) {
				$currentspan = $rowspan;
				$total = 0;
			}

                            //get total for each order id
			$total += ($orderRow['price']*$orderRow['quantity']);


			
			echo "<tr class='text-center ' >";

			if ($currentspan == $rowspan) {
				echo "<td rowspan=".$rowspan. " class='bg-navy'>
				<B> #".$orderRow['prefijo']."-".$orderRow['orderID']."</B> <hr>
				<B>Area :".$orderRow['nombre_area']."</B><hr>
				<B>".$orderRow['nombre_tipo']." # ".$orderRow['numero_mesa']."</B><hr>

				<B>Cliente : ".$orderRow['nombres']."</B>
				<hr>
				<B>Apodo : ".$orderRow['nombre_opcional']."</B>
				</td>";
			}

			
			echo "
			<td>".$orderRow['menuName']."</td>
			<td>".$orderRow['menuItemName']."</td>
			<td class='text-center'>".$orderRow['quantity']."</td>
			<td class='text-center'>$".number_format($orderRow['precio'])."</td>
			<td class='text-center'>$".number_format($orderRow['totalv'])."</td>

			";

			if ($currentspan == $rowspan) {

				$color = "badge";

				switch ($orderRow['status']) {
					case 'Esperando':
					$color = "badge badge-warning";
					break;

					case 'En blanco':
					$color = "badge badge-primary";
					break;

					case 'Pendiente de Pago':
					$color = "badge badge-danger";
					break;

					case 'Factura Anulada':
					$color = "badge badge-danger";
					break;

					case 'Vendido':
					$color = "badge badge-success";
					break;
					case 'Anulada':
					$color = "badge badge-secondary";
					break;
					case 'Credito':
					$color = "badge bg-purple";
					break;
				}

				echo "<td class='text-center' rowspan=".$rowspan."><span class='{$color}'>".$orderRow['status']."</span></td>";

				echo "<td rowspan=".$rowspan." class='text-center'>$".number_format(getSalesTotal($orderRow['orderID']))."</td>";

				echo "<td rowspan=".$rowspan." class='text-center'>".$orderRow['order_date']."</td>";
				?>
				<td class='text-center' <?php echo 'rowspan='.$rowspan?> >

					<?php 
					if ($estado=="Pendiente de Pago") {?>
						<a href="editfact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn btn-block btn-warning"><i class="fas fa-concierge-bell"></i></a>
						
						<a href="consultar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn btn-block btn-info"><i class="fas fa-search"></i></a>
						<a href="pagar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn btn-block btn-success"><i class="fas fa-cash"></i>Pagar</a>

						<a href="eliminar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn btn-block btn-danger"><i class="fas fa-trash"></i></a>
						<?php  
					}else{?>
						<?php if ($motivo_anulacion==""){
							echo $motivo_anulacion;
							?>

							<button type="button" class="btn btn-secondary btn-block" data-toggle="modal" data-target="#modal-secondary" data-id="<?php echo $orderRow["orderID"] ?>">
								Anular 

							</button> 
						<?php }else{ ?>
							<button type="button" class="btn bg-navy btn-block" data-toggle="modal" data-target="#modal-anulacion" data-motivo="<?php echo $orderRow["motivo_anulacion"]  ?> " data-id="<?php echo $orderRow["orderID"] ?>">
								Motivo Anulacion
							</button> 
						<?php } 

						?>
						<a href="editfact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn btn-warning btn-block"><i class="fa fa-edit"></i></a>

						<a href="consultar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn btn-info btn-block"><i class="fas fa-search"></i></a>

						<a href="eliminar_fact.php?orderID=<?php echo $orderRow['orderID']; ?>" class="btn  btn-danger btn-block"><i class="fas fa-trash"></i></a>


						<?php  
					}

					?>
				</td>


				<?php  
			}

			echo "</tr>";

			$currentspan--;
		}
	}
} 

else {
	echo $sqlconnection->error;
	echo "ERROR.";
}

?>