<?php

	//established the connection between databse

require("dbconnection.php");

session_start();

	//insert user defined function here
	// TODO - dynamic query
function getNumRowsQuery($query) {
	global $sqlconnection;
	if ($result = $sqlconnection->query($query))
		return $result->num_rows;
	else
		echo "Something wrong the query!";
}

function getFetchAssocQuery($query) {
	global $sqlconnection;
	if ($result = $sqlconnection->query($query)) {

		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			echo "\n", $row["itemID"], $row["menuID"], $row["menuItemName"], $row["price"];
		}

    		//print_r($result);

		return ($result);
	}
	else
		echo "Something wrong the query!";
	echo $sqlconnection->error;
}

function getLastID($id,$table) {
	global $sqlconnection;

	$query = "SELECT MAX({$id}) AS {$id} from {$table} ";

	if ($result = $sqlconnection->query($query)) {

		$res = $result->fetch_array();

			//if currently no id in table
		if ($res[$id] == NULL)
			return 0;

		return $res[$id];
	}
	else {
		echo $sqlconnection->error;
		return null;
	}
}

function getCountID($idnum,$id,$table) {
	global $sqlconnection;

	$query = "SELECT COUNT({$id}) AS {$id} from {$table} WHERE {$id}={$idnum}";

	if ($result = $sqlconnection->query($query)) {

		$res = $result->fetch_array();

			//if currently no id in table
		if ($res[$id] == NULL)
			return 0;

		return $res[$id];
	}
	else {
		echo $sqlconnection->error;
		return null;
	}
}

function getSalesTotal($orderID) {
	global $sqlconnection;
	$total = null;

	$query = "SELECT total FROM tbl_order WHERE orderID = ".$orderID;

	if ($result = $sqlconnection->query($query)) {
		
		if ($res = $result->fetch_array()) {
			$total = $res[0];
			return $total;
		}

		return $total;
	}

	else {

		echo $sqlconnection->error;
		return null;

	}
}

function getSalesGrandTotal($duration) {
	global $sqlconnection;
	$total = 0;

	if ($duration == "ALLTIME") {
		$query = "
		SELECT SUM(pago_orden) as grandtotal
		FROM pagos 



		";
	}

	else if ($duration == ("DAY" || "MONTH" || "WEEK")) {

		$query = "
		SELECT SUM(pago_orden) as grandtotal
		FROM pagos

		WHERE  fecha_pago > DATE_SUB(NOW(), INTERVAL 1 ".$duration." )
		";
	}

	else 
		return null;

	if ($result = $sqlconnection->query($query)) {
		
		while ($res = $result->fetch_array(MYSQLI_ASSOC)) {
			$total+=$res['grandtotal'];
		}

		return number_format($total);
	}

	else {

		echo $sqlconnection->error;
		return null;

	}
}

function updateTotal($orderID) {
	global $sqlconnection;

	$query = "
	UPDATE tbl_order o
	INNER JOIN (
	SELECT SUM(od.quantity*od.precio) AS total
	FROM tbl_order o
	LEFT JOIN tbl_orderdetail od
	ON o.orderID = od.orderID
	LEFT JOIN tbl_menuitem mi
	ON od.itemID = mi.itemID
	LEFT JOIN tbl_menu m
	ON mi.menuID = m.menuID

	WHERE o.orderID = ".$orderID."
	) x
	SET o.total = x.total
	WHERE o.orderID = ".$orderID."
	";



	if ($sqlconnection->query($query) === TRUE) {
		//echo "updated.";
		$consultarimpuesto = "SELECT * FROM tbl_order WHERE orderID='{$orderID}'";

		if ($menuResult = $sqlconnection->query($consultarimpuesto)) {
			while($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC)) { 
				$impo=$menuRow['impo'];
				$total=$menuRow['total'];
			}
		}

		$impoconsumo=($total*$impo/100);
		
		$editimpo = "UPDATE tbl_order SET impoconsumo = '{$impoconsumo}' WHERE orderID = '{$orderID}'";  

		if ($sqlconnection->query($editimpo) === TRUE) {
			//echo "inserted.";

		} 

		else {
				//handle
			echo "someting wong";
			echo $sqlconnection->error;

		}
	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}

}

function updateIva($orderID) {
	global $sqlconnection;

	$query = "
	UPDATE tbl_order o
	INNER JOIN (
	SELECT SUM(od.iva) AS total_iva
	FROM tbl_order o
	LEFT JOIN tbl_orderdetail od
	ON o.orderID = od.orderID
	LEFT JOIN tbl_menuitem mi
	ON od.itemID = mi.itemID
	LEFT JOIN tbl_menu m
	ON mi.menuID = m.menuID

	WHERE o.orderID = ".$orderID."
	) x
	SET o.total_iva = x.total_iva
	WHERE o.orderID = ".$orderID."
	";

	if ($sqlconnection->query($query) === TRUE) {
		echo "updated.";
	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}

}

function updateSub($orderID){	
	global $sqlconnection;
	$total=0;
	$total_iva=0;
	$impoconsumo=0;
	$subtotal=0;
	$consultarimpuesto = "SELECT * FROM tbl_order WHERE orderID='{$orderID}'";

	if ($menuResult = $sqlconnection->query($consultarimpuesto)) {
		while($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC)) { 
			$impoconsumo=$menuRow['impoconsumo'];
			$total=$menuRow['total'];
			$total_iva=$menuRow['total_iva'];
		}
	}

	$subtotal=($total-$impoconsumo)-$total_iva;

	$editimpo = "UPDATE tbl_order SET subtotal = '{$subtotal}' WHERE orderID = '{$orderID}'";  

	if ($sqlconnection->query($editimpo) === TRUE) {
		echo "inserted.";

	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}

}
/* Un contenedor para organizar los nombres y precios de artículos en columnas */
class item
{
	private $name;
	private $price;
	private $dollarSign;

	public function __construct($name = '', $price = '', $dollarSign = false)
	{
		$this -> name = $name;
		$this -> price = $price;
		$this -> dollarSign = $dollarSign;
	}

	public function __toString()
	{
		$rightCols = 10;
		$leftCols = 38;
		if ($this -> dollarSign) {
			$leftCols = $leftCols / 2 - $rightCols / 2;
		}
		$left = str_pad($this -> name, $leftCols) ;

		$sign = ($this -> dollarSign ? '$ ' : '');
		$right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
		return "$left$right\n";
	}
}
function descontar_licores($itemID,$quantity){
	global $sqlconnection;
	$menuQuery = "SELECT * FROM detalle_preparacion WHERE itemID_fk='{$itemID}'";

	if ($menuResult = $sqlconnection->query($menuQuery)) {
		$counter = 0;
		while($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC)) { 
			$id_preparacion=$menuRow['id_preparacion_fk'];
			$cantidad_detalle=$menuRow['cantidad_detalle'];
			
		}
	}
	$cantidad_total=$cantidad_detalle*$quantity;
	$menuQuery2 = "SELECT * FROM stock_preparacion WHERE id_preparacion='{$id_preparacion}'";

	if ($menuResult = $sqlconnection->query($menuQuery2)) {
		$counter = 0;
		while($menuRow = $menuResult->fetch_array(MYSQLI_ASSOC)) { 
			$stockP=$menuRow['stock_preparacion'];
			
			
		}
	}

	$nuevoStockP=$stockP-$cantidad_total;
	$editStock = "UPDATE stock_preparacion SET stock_preparacion = '{$nuevoStockP}' WHERE id_preparacion = '{$id_preparacion}'";  

	if ($sqlconnection->query($editStock) === TRUE) {
		//echo "inserted.";
	} 

	else {
				//handle
		echo "someting wong";
		echo $sqlconnection->error;

	}
	
}
//eliminar cantidades en cero
		$cerocant = "SELECT * FROM tbl_orderdetail WHERE quantity<='0' ";

		if ($ceroCanrR = $sqlconnection->query($cerocant)) {
			
			while($ceroRow = $ceroCanrR->fetch_array(MYSQLI_ASSOC)) { 
				$orderDetailID2=$ceroRow['orderDetailID'];

				$deleteCero = "DELETE FROM tbl_orderdetail WHERE orderDetailID = '{$orderDetailID2}'";

				if ($sqlconnection->query($deleteCero) === TRUE) {		
					echo '<script>
					swal("Buen Trabajo!", "Se Elimino con éxito", "success").then(function() {
						window.location.replace("index.php");
						});

						</script>';
						exit();
					}else {
						echo '<script>swal("ERROR!", "Lo sentimos ocurrió un error ", "error");</script>';
						echo $sqlconnection->error;
					}
				}
			}

	

?>