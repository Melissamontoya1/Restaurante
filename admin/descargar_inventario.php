
<?php
include("../functions.php");
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= inventario.xls"); 
?>
<?php 
$menuQuery = "SELECT * FROM tbl_menu";

if ($menuResult = $sqlconnection->query($menuQuery)) {

	if ($menuResult->num_rows == 0) {
		echo "<center><label>Sin menús agregados por el momento.</label></center>";
	}

	while($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC)) {?>

		<table class="" id="" width="100%" cellspacing="0" border="1">
			<tr>
				<th colspan="6"><?php echo $menuRow["menuName"]; ?></th>
			</tr>
			<tr>
				<th>#</th>
				<th>Nombre de Item</th>
				<th>Precio Compra (COP)</th>
				<th>Precio Venta (COP)</th>
				<th>Stock</th>
				<th>Iva</th>
				<th>Estado</th>

			</tr>
			<?php
			$menuItemQuery = "SELECT * FROM tbl_menuitem WHERE menuID = " . $menuRow["menuID"];

								//No item in menu
			if ($menuItemResult = $sqlconnection->query($menuItemQuery)) {

				if ($menuItemResult->num_rows == 0) {
					echo "<td colspan='6' class='text-center'>Sin productos agregados.</td>";
				}

				$itemno = 1;
									//Fetch and display all item based on their category 
				while($menuItemRow = $menuItemResult->fetch_array(MYSQLI_ASSOC)) {	?>

					<tr>
						<td><?php echo $itemno++; ?></td>
						<td><?php echo $menuItemRow["menuItemName"] ?></td>
						<td><?php echo"$"; echo number_format($menuItemRow["precio_costo"]); ?></td>
						<td><?php echo"$"; echo number_format($menuItemRow["price"]); ?></td>
						<td><?php echo $menuItemRow["stock"] ?></td>
						<td><?php echo $menuItemRow["iva"] ?> %</td>
						<td><?php echo $menuItemRow["estado"] ?></td>

					</tr>
					<br>
					<br>
					<br>

					<?php
				}
			}

			else {
				die("Algo malo paso");
			}
			?>


			<?php
		}
	}

	else {
		die("Algo malo paso");
	}
?>