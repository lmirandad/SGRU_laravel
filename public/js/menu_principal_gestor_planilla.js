
$( document ).ready(function(){	
	
	if($('#input-file-planilla').length){
		$("#input-file-planilla").fileinput({
		    language: "es",
		    allowedFileExtensions: ["xls","xlsx","xlsm"],
		    showPreview: false,
		    showUpload: false,
		});

	 	$('#input-file-planilla').attr('name', 'file');
	}

	$('#btnProbarCarga').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("submit-probar-planilla").submit();
				}
			}
		});
	});

	$('#btnDescargarLogs').click(function()
	{
		document.getElementById("submit-descargar-logs-planilla").submit();
	});
	
	$('#btnCargar').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("submit-cargar-planilla").submit();
				}
			}
		});
	});

	
});



