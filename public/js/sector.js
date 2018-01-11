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

	$('#checkboxAll').change(function() {
       if(this.checked){
       		size_table = document.getElementById("tabla_herramientas_disponibles").rows.length-1;
       		for(i=0;i<size_table;i++){
				$('#checkbox'+i).prop('checked', true);
			}
       }else{
       		size_table = document.getElementById("tabla_herramientas_disponibles").rows.length-1;
       		for(i=0;i<size_table;i++){
				$('#checkbox'+i).prop('checked', false);
			}
       }
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
						if(response["tiene_sla"] == true)
						{
							dialog = BootstrapDialog.show({
					            title: 'Mensaje',
					            message: 'Error al eliminar el aplicativo del sector. El aplicativo cuenta con Sla\'s vigentes.' ,
					            type : BootstrapDialog.TYPE_DANGER,
					            buttons: [{
					                label: 'Entendido',
					                action: function(dialog) {
					                    var url = inside_url + "sectores/mostrar_herramientas_sector/"+sector_id;
										window.location = url;
					                }
					            }]
					        });
						}
						else if(response["tiene_solicitudes"] == true)
						{
							dialog = BootstrapDialog.show({
					            title: 'Mensaje',
					            message: 'Error al eliminar el aplicativo del sector. El aplicativo cuenta con solicitudes pendientes y/o procesando.',
					            type : BootstrapDialog.TYPE_DANGER,
					            buttons: [{
					                label: 'Entendido',
					                action: function(dialog) {
					                    var url = inside_url + "sectores/mostrar_herramientas_sector/"+sector_id;
										window.location = url;
					                }
					            }]
					        });
						}else
						{
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
						}
						
						
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