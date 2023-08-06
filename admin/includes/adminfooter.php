  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.content-wrapper -->
  <?php $year = date("Y"); ?>
  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo $year; ?>     <a href="http://hexadotsoftware.com" target="_blank">Hexadot Software a la medida </a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 4.0
    </div>
  </footer>
  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">¿Listo para salir?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está listo para finalizar su sesión actual.</div>
      <div class="modal-footer">
        <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
        <a class="btn btn-primary" href="../logout.php">Cerrar sesión</a>
      </div>
    </div>
  </div>
</div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>

<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Ekko Lightbox -->
<script src="plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- fullCalendar 2.2.5 -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/fullcalendar/main.js"></script>
<script src="plugins/fullcalendar/locales/es.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>

<!-- <script src="dist/js/swwtalert.min.js"></script> -->
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>



<script>

  $(function () {
  //$("#tabla_historial").hide();
      $("#tabla_salidas").hide();
      $("#historial").on("click",mostrar_historial);
      $("#salidas").on("click",mostrar_salidas);
   


//FUNCION PARA BUSCAR LAS FACTURAS
$("#buscar_fact").on("keyup",buscar_fact);
$.ajax({
  type:'POST',
  url:'alerta_consecutivo.php',

  success: function  (gato2) {
    $(".aviso").html(gato2);          
  }
});

$.ajax({
  type:'POST',
  url:'base_dia.php',

  success: function  (gato2) {

  }
});

   //  setInterval(function(){
   //     $.ajax({
   //    type:'POST',
   //    url:'imprimir_comanda.php',

   //    success: function  (gato22) {
   //     //alert(gato22);
   //   }
   // });
   //  },1000*3);

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
  };

    /* initialize the external events

    -----------------------------------------------------------------*/

    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (https://fullcalendar.io/docs/event-object)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    ini_events($('#external-events div.external-event'))

    /* initialize the calendar
    -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
    m    = date.getMonth(),
    y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendar.Draggable;

    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');

    // initialize the external events
    // -----------------------------------------------------------------

    new Draggable(containerEl, {

      itemSelector: '.external-event',
      eventData: function(eventEl) {
        return {
          title: eventEl.innerText,
          backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
        };
      }
    });

    var calendar = new Calendar(calendarEl, {

      headerToolbar: {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      themeSystem: 'bootstrap',
      //Random default events

      events: [
      {
        title          : 'All Day Event',
        start          : new Date(y, m, 1),
          backgroundColor: '#f56954', //red
          borderColor    : '#f56954', //red
          allDay         : true
        },
        {
          title          : 'Long Event',
          start          : new Date(y, m, d - 5),
          end            : new Date(y, m, d - 2),
          backgroundColor: '#f39c12', //yellow
          borderColor    : '#f39c12' //yellow
        },
        {
          title          : 'Meeting',
          start          : new Date(y, m, d, 10, 30),
          allDay         : false,
          backgroundColor: '#0073b7', //Blue
          borderColor    : '#0073b7' //Blue
        },
        {
          title          : 'Lunch',
          start          : new Date(y, m, d, 12, 0),
          end            : new Date(y, m, d, 14, 0),
          allDay         : false,
          backgroundColor: '#00c0ef', //Info (aqua)
          borderColor    : '#00c0ef' //Info (aqua)
        },
        {
          title          : 'Birthday Party',
          start          : new Date(y, m, d + 1, 19, 0),
          end            : new Date(y, m, d + 1, 22, 30),
          allDay         : false,
          backgroundColor: '#00a65a', //Success (green)
          borderColor    : '#00a65a' //Success (green)
        },
        {
          title          : 'Click for Google',
          start          : new Date(y, m, 28),
          end            : new Date(y, m, 29),
          url            : 'https://www.google.com/',
          backgroundColor: '#3c8dbc', //Primary (light-blue)
          borderColor    : '#3c8dbc' //Primary (light-blue)
        }
        ],
        editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function(info) {
        // is the "remove after drop" checkbox checked?
        if (checkbox.checked) {
          // if so, remove the element from the "Draggable Events" list
          info.draggedEl.parentNode.removeChild(info.draggedEl);
        }
      }
    });

    calendar.render();
    // $('#calendar').fullCalendar()

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    // Color chooser button
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      // Save color
      currColor = $(this).css('color')
      // Add color effect to button
      $('#add-new-event').css({
        'background-color': currColor,
        'border-color'    : currColor
      })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      // Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      // Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.text(val)
      $('#external-events').prepend(event)

      // Add draggable funtionality
      ini_events(event)

      // Remove event from text input
      $('#new-event').val('')
    })
  })
</script>
<script>
  $(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });

    $('.filter-container').filterizr({gutterPixels: 3});
    $('.btn[data-filter]').on('click', function() {
      $('.btn[data-filter]').removeClass('active');
      $(this).addClass('active');
    });
  })
</script>
<script>
  $(document).ready(function() {
    $('.product-image-thumb').on('click', function () {
      var $image_element = $(this).find('img')
      $('.product-image').prop('src', $image_element.attr('src'))
      $('.product-image-thumb.active').removeClass('active')
      $(this).addClass('active')
    })
  })
</script>
<script>
  $(function () {
    $("table.display").DataTable({

      "language":{
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sSearch":         "Buscar:",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
        },
        "oAria": {
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        "buttons": {
          "copy": "Copiar",
          "colvis": "Visibilidad"
        }
      },
      responsive:"true",
      dom:"Bfrtilp",
      

      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],



      "order":[[0,'desc']]

    });




  });
  

    
    
    function mostrar_historial(){
      //alert("hola historial");
      $("#tabla_salidas").hide();
      $("#tabla_historial").show();
    }
    function mostrar_salidas(){
      //alert("hola salidas");
      $("#tabla_historial").hide();
      $("#tabla_salidas").show();
    }
    function PorPagar(){
      var totalFinal = $("#total_final").val();
      var Pagado = $("#nuevoTotalVenta").val();
      var Pendiente = (totalFinal-Pagado);
      $("#TotalPagar").val(Pendiente);
      if(totalFinal<=Pagado){
        $("#TotalPagar").val(Pendiente);
      }

    }
    function validarPago(){
      var debe = $("#totalVentaReal").val();
      var pagar= $("#nuevoTotalVenta").val();

      if(debe<pagar){

        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          html: 'Verifica que el los pagos<br> <B>Total a Pagar</B>: $'+debe+'<br><B>Pagos Realizados </B>: $'+pagar+''

        })
        //$("#botonPagar").attr("disabled", true);
      }else{

        //$("#botonPagar").attr("disabled", false);
      }
    }
    /*=============================================
     Suma todos los valores de la tabla
     =============================================*/
     function sumarTotalPrecios() {

      var precioItem = $(".pago");

      var arraySumaPrecio = [];

      for (var i = 0; i < precioItem.length; i++) {

        arraySumaPrecio.push(Number($(precioItem[i]).val()));


      }

      function sumaArrayPrecios(total, numero) {

        return total + numero;

      }

      var sumaTotalPrecio = arraySumaPrecio.reduce(sumaArrayPrecios);

      $("#nuevoTotalVenta").val(sumaTotalPrecio);
      $("#totalVenta").val(sumaTotalPrecio);
      $("#nuevoTotalVenta").attr("total", sumaTotalPrecio);

    }

    $(function(){
    /*=============================================
     Clona la fila oculta que tiene los campos base, y la agrega al final de la tabla
     =============================================*/
     $("#adicional").on('click', function(){
      $("#tabla tbody tr:eq(0)").clone().removeClass('fila-fija').appendTo("#tabla");
      sumarTotalPrecios()
      PorPagar()
      validarPago()
      $("#nuevoTotalVenta").number(true);
    });
    /*=============================================
    Evento que selecciona la fila y la elimina 
    =============================================*/
    $(document).on("click",".eliminar",function(){
      var parent = $(this).parents().get(0);
      $(parent).remove();
      sumarTotalPrecios()
      PorPagar()
      validarPago()
      $("#nuevoTotalVenta").number(true);
      
    });
    /*=============================================
     MODIFICAR EL TOTAL PAGADO
     =============================================*/
     $(".formularioVenta").on("change", "input.pago", function () {

      var nombreDiv = $(this).parent().parent().parent().children();
      sumarTotalPrecios()
      PorPagar()
      validarPago()

      $("#nuevoTotalVenta").number(true);
      
    })
     $("#FormularioPropinas").hide();

     $(".formularioVenta").on("change", "input.propinas", function () {
       if( $(this).is(':checked') ){
        // Hacer algo si el checkbox ha sido seleccionado
        alert("La propina ha sido seleccionada");
        var porcentaje = $("#porcentaje").val();
        var total_venta = $("#total_venta").val();
        var monto_propina = (total_venta*porcentaje)/100;
        $("#total_propina").val(monto_propina);
        $("#FormularioPropinas").show();
        var venta2 = parseInt($("#total_venta").val());
        var total_propina2 = parseInt($("#total_propina").val());


        $("#Pagos").val(venta2+total_propina2);
        $("#TotalPagar").val(venta2+total_propina2);
        $("#total_final").val(venta2+total_propina2);
        $("#totalFinal").val(venta2+total_propina2);
        $("#totalVentaReal").val(venta2+total_propina2);
        $("#totalVenta").val(venta2+total_propina2);
         //$("#nuevoTotalVenta").val(venta2+total_propina2);
         //$("#nuevoTotalVenta").attr("total", venta2+total_propina2);


       } else {
        // Hacer algo si el checkbox ha sido deseleccionado
        alert("La propina ha sido deseleccionada");
        $("#FormularioPropinas").hide();
        $("#total_propina").val(0);
        var total_propina3 = parseInt($("#total_propina").val());
        var venta3 = parseInt($("#total_venta").val());
        $("#total_final").val(venta3+total_propina3);
        $("#Pagos").val(venta3+total_propina3);
        $("#TotalPagar").val(venta3+total_propina3);
        $("#totalFinal").val(venta3+total_propina3);
        $("#totalVentaReal").val(venta3+total_propina3);
        $("#totalVenta").val(venta3+total_propina3);
        //$("#nuevoTotalVenta").val(venta3+total_propina3);
        //$("#nuevoTotalVenta").attr("total", venta3+total_propina3);
        
      }
    })
    /*=============================================
     MODIFICAR LA PROPINA
     =============================================*/
     $(".formularioVenta").on("change", "input.propina", function () {
      var total_propina3 = parseInt($("#total_propina").val());
      var venta3 = parseInt($("#total_venta").val());
      $("#total_final").val(venta3+total_propina3);
      $("#Pagos").val(venta3+total_propina3);
      $("#TotalPagar").val(venta3+total_propina3);
      $("#totalFinal").val(venta3+total_propina3);
      $("#totalVentaReal").val(venta3+total_propina3);
      $("#totalVenta").val(venta3+total_propina3);    })





   });
 </script>
<!-- TEXTAREA -->
<script>
  $(function () {
    //Add text editor
    $('#compose-textarea').summernote()
  })
</script>

<script type="text/javascript">
  $(document).ready(function(){
  if (window.history.replaceState) { // verificamos disponibilidad
    window.history.replaceState(null, null, window.location.href);
  }
});
</script>


</body>

</html>
