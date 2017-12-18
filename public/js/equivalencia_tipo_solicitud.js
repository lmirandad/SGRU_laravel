$( document ).ready(function(){
	
	$('#btnAgregarTipoSolicitudEquivalencia').click(function(){
		mostrar_modal_agregar_equivalencia();
	});

	$('#btnSubmitAgregar').click(function(){
		agregar_equivalencia();
	});

});


function mostrar_modal_agregar_equivalencia(){
	$('#modal_equivalencias').modal('show');
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


function mostrar_datos(e,id){
	idtipo_solicitud = id;
	$("#table_equivalencias tbody").remove();
	e.preventDefault();
 	$.ajax({
		url: inside_url+'equivalencias_tipo_solicitud/mostrar_datos',
		type: 'POST',
		data: { 
			'idtipo_solicitud' : idtipo_solicitud,
		},
		beforeSend: function(){
			//$(this).prop('disabled',true);
		},
		complete: function(){
			//$(this).prop('disabled',false);
		},
		success: function(response){
			if(response.success){
				equivalencias = response["equivalencias"];
				cantidad_equivalencias = equivalencias.length;
				if(cantidad_equivalencias == 0)
				{
					dialog = BootstrapDialog.show({
			            title: 'Mensaje',
			            message: 'La acción no tiene equivalencias',
			            type : BootstrapDialog.TYPE_DANGER,
			            buttons: [{
			                label: 'Entendido',
			                action: function(dialog) {
			                    dialog.close();
			                }
			            }]
			        });
				}else{
					
					for (i=0;i<cantidad_equivalencias;i++){
						data = "<tr>"
				                +"<td class=\"text-nowrap text-center\">"+(i+1)+"</td>"
				                +"<td class=\"text-nowrap text-center\" style=\"display:none\" id=\"idequivalencia_tipo_solicitud"+i+"\">"+equivalencias[i].idequivalencia_tipo_solicitud+"</td>"	
				                +"<td class=\"text-nowrap text-center\">"+equivalencias[i].nombre_equivalencia+"</td>"
				                +"<td class=\"text-nowrap\"><div style=\"text-align:center\"><button class=\"btn btn-danger btn-sm\" onclick=\"eliminar_equivalencia(event,"+equivalencias[i].idequivalencia_tipo_solicitud+")\" type=\"button\"><span class=\"fa fa-trash\"></span></button></div></td>";
	            		$('#table_equivalencias').append(data);	            		                		
	            	}
				}
			}
			
		},
		error: function(){
		}
	});
		
}

function eliminar_equivalencia(e,id){
	idequivalencia_tipo_solicitud = id;

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
					url: inside_url+'equivalencias_tipo_solicitud/submit_eliminar_equivalencia_tipo_solicitud',
					type: 'POST',
					data: { 
						'idequivalencia_tipo_solicitud' : idequivalencia_tipo_solicitud,
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
							if(response["resultado"] == 1){
								dialog = BootstrapDialog.show({
						            title: 'Mensaje',
						            message: 'Se eliminó la equivalencia <strong>' + response["equivalencias"].nombre_equivalencia+'</strong> con éxito',
						            type : BootstrapDialog.TYPE_SUCCESS,
						            buttons: [{
						                label: 'Entendido',
						                action: function(dialog) {
						                	dialog.close();
						                    mostrar_datos(e,response["equivalencias"].idtipo_solicitud);
						                }
						            }]
						        });
						    }else{
						    	dialog = BootstrapDialog.show({
						            title: 'Mensaje',
						            message: 'No se puede eliminar una equivalencia que tenga el mismo nombre que el original.',	
						            type : BootstrapDialog.TYPE_SUCCESS,
						            buttons: [{
						                label: 'Entendido',
						                action: function(dialog) {
						                    dialog.close();
						                }
						            }]
						        });
						    }	
						}
						
						
					},
					error: function(){
					}
				});
			}
		}
	});	
}

