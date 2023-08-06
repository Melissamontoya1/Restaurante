$(document).on("ready",iniciar);

function iniciar(){
	
	
	$.ajax({
		type:'POST',
		url:'../alerta_consecutivo.php',

		success: function  (gato2) {
			$(".aviso").html(gato2);					
		}
	});

	$.ajax({
		type:'POST',
		url:'../base_dia.php',

		success: function  (gato2) {
								
		}
	});
	$("#buscar_fact").on("keyup",buscar_fact);
	
}


 //esta funcion se activa al escribir en el input de busqueda y busca las facturas
 function buscar_fact() {
 	if ($("#buscar_fact").val().length > 0) {

 		var dato= $("#buscar_fact").val();
 		var eljson= {'dato':dato};

 		$.ajax({
 			type:'POST',
 			url:'buscar_fact.php',
 			data: eljson,
 			success: function  (gato) {
 				$("#tblBodyCurrentOrder").html(gato);					
 			}
 		});
 		
 	}else{


 		location.reload("#ventas");
 	}
 }