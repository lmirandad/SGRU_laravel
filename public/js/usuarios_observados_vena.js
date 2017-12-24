
$( document ).ready(function(){	
	
	if($('#input-file-observados').length){
		$("#input-file-observados").fileinput({
		    language: "es",
		    allowedFileExtensions: ["csv","txt"],
		    showPreview: false,
		    showUpload: false,
		});

	 	$('#input-file-observados').attr('name', 'file');
	}

	if($('#input-file-vena').length){
		$("#input-file-vena").fileinput({
		    language: "es",
		    allowedFileExtensions: ["csv","txt"],
		    showPreview: false,
		    showUpload: false,
		});

	 	$('#input-file-vena').attr('name', 'file');
	}

	$('#btnVistaPreviaObservados').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("submit-cargar-observados").submit();
				}
			}
		});
	});

	$('#btnVistaPreviaVena').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("submit-cargar-vena").submit();
				}
			}
		});
	});

	$('#btnCargarObservados').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("submit-upload-observados").submit();
				}
			}
		});
	});

	$('#btnCargarVena').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("submit-upload-vena").submit();
				}
			}
		});
	});


	
});



