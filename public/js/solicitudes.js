var start,end;
$( document ).ready(function(){	
	
	$("#input-file").fileinput({
	    language: "es",
	    allowedFileExtensions: ["csv"],
	    showPreview: false,
	    showUpload: false,
	});

	 $('#input-file').attr('name', 'file');

	

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

});

