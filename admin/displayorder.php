
<?php
include("../functions.php");

	//display none when open /displayorder.php
if(empty($_GET['cmd'])) 
	die(); 

	//display current order list for kitchen management
if ($_GET['cmd'] == 'currentorder')	{

	$displayOrderQuery =  "
	SELECT o.orderID,o.prefijo, m.menuName, od.itemID,mi.menuItemName,od.quantity,od.precio,od.totalv,o.status,mi.price ,o.order_date,o.motivo_anulacion,o.id_cliente_fk,o.id_mesa_fk,o.observacion_order,c.id_cliente,c.nombres, c.identificacion,c.id_tipo_cliente,c.direccion,c.telefono,c.correo,me.id_mesa,me.numero_mesa,me.estado_mesa,me.id_area_fk,me.id_tipo_fk,a.id_area,a.nombre_area,t.id_tipo,t.nombre_tipo
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
	WHERE o.status 
	IN ( 'Esperando','Preparando','Listo') ORDER BY o.orderID DESC
	";

	if ($orderResult = $sqlconnection->query($displayOrderQuery)) {

		$currentspan = 0;

				//if no order
		if ($orderResult->num_rows == 0) {

					//SI NO HAY PEDIDOS

		}

		else {
			while($orderRow = $orderResult->fetch_array(MYSQLI_ASSOC)) {

				$rowspan = getCountID($orderRow["orderID"],"orderID","tbl_orderdetail"); 

				if ($currentspan == 0)
					$currentspan = $rowspan;

				echo "<tr>";

				if ($currentspan == $rowspan) {
					echo "<td rowspan=".$rowspan. " class='bg-navy'>
					<B> #".$orderRow['prefijo']."-".$orderRow['orderID']."</B> <hr>
					<B>Area :".$orderRow['nombre_area']."</B><hr>
					<B>".$orderRow['nombre_tipo']." # ".$orderRow['numero_mesa']."</B><hr>

					<B>Cliente : ".$orderRow['nombres']."</B>
					</td>";
				}

				echo "
				<td>".$orderRow['menuName']."</td>
				<td>".$orderRow['menuItemName']."</td>
				<td class='text-center'>".$orderRow['quantity']."</td>
				<td>$".number_format($orderRow['precio'])."</td>
				<td>$".number_format($orderRow['totalv'])."</td>
				";

				if ($currentspan == $rowspan) {

					$color = "badge badge-warning";
					switch ($orderRow['status']) {
						case 'Esperando':
						$color = "badge badge-warning";
						break;

						case 'Preparando':
						$color = "badge badge-primary";
						break;

						case 'Listo':
						$color = "badge badge-success";
						break;
					}

					echo "<td class='text-center' rowspan=".$rowspan."><span class='{$color}'>".$orderRow['status']."</span></td>";
					
					echo "<td rowspan=".$rowspan." class='text-center'><B>$".number_format(getSalesTotal($orderRow['orderID']))."</B></td>";

					echo "<td class='text-center' rowspan=".$rowspan.">";

							//options based on status of the order
					switch ($orderRow['status']) {
						case 'Esperando':

						echo "<button onclick='editStatus(this,".$orderRow['orderID'].")' class='btn btn-outline-primary' value = 'Preparando'>Preparando</button>";
						echo "<button onclick='editStatus(this,".$orderRow['orderID'].")' class='btn btn-outline-success' value = 'Listo'>Listo</button>";

						break;

						case 'Preparando':

						echo "<button onclick='editStatus(this,".$orderRow['orderID'].")' class='btn btn-outline-success' value = 'Listo'>Listo</button>";

						break;

						case 'Listo':

						echo "<button onclick='editStatus(this,".$orderRow['orderID'].")' class='btn btn-outline-warning' value = 'Pendiente de Pago'>Limpiar</button>
						";


						break;
					}

					echo "<button onclick='editStatus(this,".$orderRow['orderID'].")' class='btn btn-outline-danger' value = 'Cancelado'>Cancelar</button>
					</td>";


							/*
							echo "<td rowspan=".$rowspan."><button class='btn btn-danger'>".$orderRow['status']."</button>";
							//temporary
							echo "<button class='btn btn-primary'>preparando</button>";
							echo "<button class='btn btn-success'>listo</button></td>";
							*/
						}

						echo "</tr>";

						$currentspan--;
					}
				}	
			}
		}

	//display current ready order list in staff index
		if ($_GET['cmd'] == 'currentready') {

			$latestReadyQuery = "SELECT * FROM tbl_order WHERE status IN ( 'Pendiente de Pago','Preparando') ";

			if ($result = $sqlconnection->query($latestReadyQuery)) {

				if ($result->num_rows == 0) {
					echo "<tr><td class='text-center'>Sin órdenes listas para servir. </td></tr>";
				}

				while($latestOrder = $result->fetch_array(MYSQLI_ASSOC)) {
					echo "<tr><td><i class='fas fa-bullhorn' style='color:green;'></i><b> Orden #".$latestOrder['orderID']."</b> de la mesa ".$latestOrder['id_mesa_fk']." lista para servir.<a href='editstatus.php?orderID=".$latestOrder['orderID']."'><i class='fas fa-check float-right'></i></a></td></tr>";

				}
			}
		}

	?>