<?php
include("../functions.php");
$suma=0;

if (isset($_POST['btnMenuID'])) {

	$menuID = $sqlconnection->real_escape_string($_POST['btnMenuID']);

	$menuItemQuery = "SELECT itemID,menuItemName,estado FROM tbl_menuitem WHERE estado='Activo' AND menuID = " . $menuID;

	if ($menuItemResult = $sqlconnection->query($menuItemQuery)) {
		if ($menuItemResult->num_rows > 0) {
			$counter = 0;
			while($menuItemRow = $menuItemResult->fetch_array(MYSQLI_ASSOC)) {

				if ($counter >=3) {
					echo "</tr>";
					$counter = 0;
				}

				if($counter == 0) {
					echo "<tr>";
				}

				echo "<td><button  class='btn btn-primary btn-block' onclick = 'setQty({$menuItemRow['itemID']})'>{$menuItemRow['menuItemName']}</button></td>";

				$counter++;
			}
		}

		else {
			echo "<tr><td>No hay nada registrado ó activo aun.</td></tr>";
		}

	}
}

if (isset($_POST['btnMenuItemID']) && isset($_POST['qty'])) {

	$menuItemID = $sqlconnection->real_escape_string($_POST['btnMenuItemID']);
	$quantity = $sqlconnection->real_escape_string($_POST['qty']);

	$menuItemQuery = "SELECT mi.itemID,mi.menuItemName,mi.price,m.menuName FROM tbl_menuitem mi LEFT JOIN tbl_menu m ON mi.menuID = m.menuID WHERE  itemID = " . $menuItemID ;

	if ($menuItemResult = $sqlconnection->query($menuItemQuery)) {
		if ($menuItemResult->num_rows > 0) {
			while($menuItemRow = $menuItemResult->fetch_array(MYSQLI_ASSOC)) {
				$sub=$menuItemRow['price']*$quantity;
				$suma+=$sub+$suma;
				echo "
				<tr>
				<input type = 'hidden' name = 'itemID[]' value ='".$menuItemRow['itemID']."'/>
				<td>".$menuItemRow['menuName']." : ".$menuItemRow['menuItemName']."</td>
				<td><input type = 'number' required='required' name = 'precio[]' width='10px' class='form-control' value ='".$menuItemRow['price']."'  /></td>
				<td><input type = 'number' required='required' name = 'itemqty[]' width='10px' class='form-control multiplicar' value ='".$quantity."' /></td>
				<td> ".$sub."</td>
				<td><button class='btn btn-danger deleteBtn btn-sm' type='button' onclick='deleteRow()'><i class='fas fa-times'></i></button></td>
				</tr>
				
				";
			}

			
		}

		else {
				//no data retrieve
			echo "null";
		}

	}


}


?>