$( document ).ready(function(){
	
	$("#datetimepicker1").datetimepicker({
		ignoreReadonly:true,
		format:'YYYY',
		locale:'es',
	});

	
	$("#datetimepicker2").datetimepicker({
		ignoreReadonly:true,
		format:'YYYY-MM-DD',
		locale:'es',
	});

	$('#btnAgregarFeriado').click(function(){
		mostrar_modal_agregar_feriado();
	});

	$('#btnSubmitAgregar').click(function(){
		agregar_equivalencia();
	});

	$('#btnLimpiar').click(function(){
		$('#search_anho').val(null);
	})

});


function mostrar_modal_agregar_feriado(){
	$('#modal_feriados').modal('show');
}


function agregar_equivalencia(){
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
	
}

function eliminar_feriado(e,id){
	idferiado = id;

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
					url: inside_url+'feriados/submit_eliminar_feriado',
					type: 'POST',
					data: { 
						'idferiado' : idferiado,
					},
					beforeSend: function(){
						//$(this).prop('disabled',true);
					},
					complete: function(){
						//$(this).prop('disabled',false);
					},
					success: function(response){
						if(response.success)
						{
							dialog = BootstrapDialog.show({
					            title: 'Mensaje',
					            message: 'Se eliminó la fecha <strong>' + response["fecha"]+'</strong> con éxito',
					            type : BootstrapDialog.TYPE_SUCCESS,
					            buttons: [{
					                label: 'Entendido',
					                action: function(dialog) {
					                	var url = inside_url + "feriados/listar_feriados";
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

