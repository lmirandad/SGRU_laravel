$( document ).ready(function(){
	
	$('#btnLimpiar').click(function(){
		$('#search').val(null);
		$('#search_denominacion_herramienta').val(0);
	});

	$('#btnCrearHerramienta').click(function(){
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

	$('#btnEditarHerramienta').click(function(){
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

	$('#submit-habilitar-herramienta').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("habilitar_herramienta").submit();
				}
			}
		});
	});

	$('#submit-inhabilitar-herramienta').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("inhabilitar_herramienta").submit();
				}
			}
		});
	});

	$('#btnAgregarHerramientaEquivalencia').click(function(){

		$('#modal_equivalencias').modal('show');

	});

});

function eliminar_equivalencia(e,id)
{
	e.preventDefault();
	idherramienta_equivalencia = id;
	idherramienta = $('#herramienta_id').val();
	$.ajax({
		url: inside_url+'herramienta_equivalencia/eliminar_equivalencia',
		type: 'POST',
		data: { 
			'idherramienta_equivalencia' : idherramienta_equivalencia,
			'idherramienta' : idherramienta,
		},
		beforeSend: function(){
			//$(this).prop('disabled',true);
		},
		complete: function(){
			//$(this).prop('disabled',false);
		},
		success: function(response){
			if(response.success){
				if(response["herramienta_equivalencia"] == null)
				{
					dialog = BootstrapDialog.show({
				            title: 'Mensaje',
				            message: 'Error en el proceso.',
				            type : BootstrapDialog.TYPE_DANGER,
				            buttons: [{
				                label: 'Entendido',
				                action: function(dialog) {
				                 	dialog.close();   
				                }
			            }]
			        });
				}else if(response["herramienta_equivalencia"] == 1){
					dialog = BootstrapDialog.show({
				            title: 'Mensaje',
				            message: 'No se puede eliminar el nombre que coincide con el nombre del aplicativo registrado.',
				            type : BootstrapDialog.TYPE_DANGER,
				            buttons: [{
				                label: 'Entendido',
				                action: function(dialog) {
				                 	dialog.close();   
				                }
			            }]
			        });
				}
				else
				{
					dialog = BootstrapDialog.show({
				            title: 'Mensaje',
				            message: 'Se ha eliminado el nombre: '+response["herramienta_equivalencia"].nombre_equivalencia,
				            type : BootstrapDialog.TYPE_DANGER,
				            buttons: [{
				                label: 'Entendido',
				                action: function(dialog) {
				                 	var url = inside_url + "herramientas/editar_herramienta/"+idherramienta;
									window.location = url;   
				                }
			            }]
			        });
				}
				
			}			
		},
		error: function(){
			alert('La petición no se pudo completar, inténtelo de nuevo.');
		}
	});
}


