$( document ).ready(function(){
	
	$('#btnLimpiar').click(function(){
		$('#search').val(null);
	});

	$('#btnCrearSector').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("submit-crear").submit();
				}
			}
		});
	});

	$('#btnEditarSector').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("submit-editar").submit();
				}
			}
		});
	});

	$('#submit-habilitar-sector').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("habilitar_sector").submit();
				}
			}
		});
	});

	$('#submit-inhabilitar-sector').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("inhabilitar_sector").submit();
				}
			}
		});
	});

	$('#btnAgregarHerramientaSubmit').click(function(){
		agregarNuevasHerramientas();
	});


});

function agregarNuevasHerramientas(){
	BootstrapDialog.confirm({
		title: 'Mensaje de Confirmación',
		message: '¿Está seguro que desea realizar esta acción?', 
		type: BootstrapDialog.TYPE_INFO,
		btnCancelLabel: 'Cancelar', 
    	btnOKLabel: 'Aceptar', 
		callback: function(result){
            if(result) {
				document.getElementById("submit-agregar-herramientas").submit();
            }
        }
    });					
}

function eliminar_herramienta(e,id){
	sector_id = $('#sector_id').val();
	e.preventDefault();
	BootstrapDialog.confirm({
		title: 'Mensaje de Confirmación',
		message: '¿Está seguro que desea realizar esta acción?', 
		type: BootstrapDialog.TYPE_DANGER,
		btnCancelLabel: 'Cancelar', 
    	btnOKLabel: 'Aceptar', 
		callback: function(result){
            if(result) {
            	$.ajax({
					url: inside_url+'herramientas/submit_eliminar_herramienta_sector',
					type: 'POST',
					data: { 
						'idherramientaxsector' : id,
						'sector_id': sector_id,
					},
					beforeSend: function(){
						$(".loader_container").show();
					},
					complete: function(){
						//$(this).prop('disabled',false);
					},
					success: function(response){
						dialog = BootstrapDialog.show({
				            title: 'Mensaje',
				            message: 'Se eliminó la herramienta '+response["nombre_herramienta"]+ ' del sector',
				            type : BootstrapDialog.TYPE_SUCCESS,
				            buttons: [{
				                label: 'Entendido',
				                action: function(dialog) {
				                    var url = inside_url + "sectores/mostrar_herramientas_sector/"+sector_id;
									window.location = url;
				                }
				            }]
				        });
						
					},
					error: function(){
					}
				});
			}
		}
	});	
}

function mostrar_sla(e,id){
	sector_id = $('#sector_id').val();
	idherramientaxsector = id;

	e.preventDefault();
   	$.ajax({
		url: inside_url+'slas/obtener_slas',
		type: 'POST',
		data: { 
			'idherramientaxsector' : idherramientaxsector,
		},
		beforeSend: function(){
			$(".loader_container").show();
		},
		complete: function(){
			$(".loader_container").hide();
		},
		success: function(response){
			if(response.success){
				listado_sla = response["slas"];
				if(listado_sla == null || listado_sla == 0){
					dialog = BootstrapDialog.show({
			            title: 'Mensaje',
			            message: 'El aplicativo no cuenta con SLA\'s. Se debe registrar un nuevo SLA.',
			            type : BootstrapDialog.TYPE_INFO,
			            buttons: [{
			                label: 'Entendido',
			                action: function(dialog) {
			                    var url = inside_url + "slas/crear_sla/"+idherramientaxsector;
								window.location = url;
			                }
			            }]
			        });		
				}else{
					var url = inside_url + "slas/mostrar_slas/"+idherramientaxsector;
					window.location = url;
				}
			}
			
			
		},
		error: function(){
		}
	});
			
}