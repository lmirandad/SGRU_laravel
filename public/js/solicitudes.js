var start,end;
$( document ).ready(function(){	
	
	if($('#input-file').length){
		$("#input-file").fileinput({
		    language: "es",
		    allowedFileExtensions: ["csv"],
		    showPreview: false,
		    showUpload: false,
		});

	 	$('#input-file').attr('name', 'file');
	}

	


	 if($('#search_datetimepicker1').length && $('#search_datetimepicker2').length){
        $('#search_datetimepicker1').datetimepicker({
     		ignoreReadonly: true,
     		format:'DD-MM-YYYY'
     	});
        $('#search_datetimepicker2').datetimepicker({
            ignoreReadonly: true,
            format:'DD-MM-YYYY'
        });
        $("#search_datetimepicker1").on("dp.change", function (e) {
            $('#search_datetimepicker2').data("DateTimePicker").minDate(e.date);
        });
        $("#search_datetimepicker2").on("dp.change", function (e) {
            $('#search_datetimepicker1').data("DateTimePicker").maxDate(e.date);
        });
    }    
	

    


	$('#btnCargar').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("submit-cargar").submit();
				}
			}
		});
	});

	$('#btnDescargarLogs').click(function(){
		document.getElementById("submit-descargar-logs").submit();				
	});

	$('#btnAsignar').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar la asignación? Esto puede tardar unos minutos, dependiendo del volumen de solicitudes por asignar.', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("submit-asignar").submit();
				}
			}
		});
						
	});

	$('#btnLimpiar').click(function(){
		$('#search_solicitud').val(null);
		$('#fecha_solicitud_desde').val(null);
		$('#fecha_solicitud_hasta').val(null);
		$('#search_tipo_solicitud').val(0);
		$('#search_estado_solicitud').val(0);
		$('#search_sector').val(0);
	});

});

