<?php 
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
			$correo_empresa=$fila2['correo_empresa'];
			$base=$fila2['base'];
			$caja_general=$fila2['caja_general'];
			$inventario_licores=$fila2['inventario_licores'];
			$logotipo=$fila2['logo'];
			$nombreimpresorabd=$fila2['nombre_impresora'];
			$pago_directo=$fila2['pago_directo'];
			$aplicar_descuentos=$fila2['aplicar_descuentos'];
			$fecha_inicio=$fila2['fecha_inicio'];
			$fecha_fin=$fila2['fecha_fin'];
			$prefijo=$fila2['prefijo'];
			$tipo_negocio=$fila2['tipo_negocio'];
			$pago_directo=$fila2['pago_directo'];
			$porcentaje_propinas=$fila2['porcentaje_propinas'];

		}
	}
}

?>