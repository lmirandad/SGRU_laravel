$( document ).ready(function(){
	
	$("#datetimepicker1").datetimepicker({
		ignoreReadonly:true,
		format:'DD-MM-YYYY',
		locale:'es',
	});



	$('#btnCrear').click(function(){
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

	$('#btnEditar').click(function(){
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

	$('#btnReestablecer').click(function(){
		usuario_id = $('#usuario_id').val();
		documento_identidad = $('#documento_identidad').val();

		$.ajax({
            url: inside_url+'usuarios/reestablecer_contrasena/'+usuario_id,
            type: 'POST',
            data: { 'usuario_id' : usuario_id,
            		'documento_identidad' : documento_identidad,
                },
            beforeSend: function(){
                $(".loader_container").show();
            },
            complete: function(){
                $(".loader_container").hide();
            },
            success: function(response){
                if(response.success){
                	if(response["usuario"] != null)
	                    dialog = BootstrapDialog.show({
	                            title: 'Mensaje',
	                            type: BootstrapDialog.TYPE_SUCCESS,
	                            message: 'Se ha reestablecido la contraseña del usuario '+response["usuario"].nombre+' '+response["usuario"].apellido_paterno+' '+response["usuario"].apellido_materno,
	                            closable: false,
	                            buttons: [{
	                                label: 'Aceptar',
	                                cssClass: 'btn-default',
	                                action: function() {
	                                    dialog.close();
	                                }
	                            }]
	                        });
	               	else{
	               		if(response["resultado"] == 1){
	               			dialog = BootstrapDialog.show({
		                            title: 'Mensaje',
		                            type: BootstrapDialog.TYPE_WARNING,
		                            message: 'Usuario no encontrado',
		                            closable: false,
		                            buttons: [{
		                                label: 'Aceptar',
		                                cssClass: 'btn-default',
		                                action: function() {
		                                    dialog.close();
	                                }
	                            }]
                        	});	
	               		}else if(response["resultado"] == 2){
	               			dialog = BootstrapDialog.show({
		                            title: 'Mensaje',
		                            type: BootstrapDialog.TYPE_WARNING,
		                            message: 'El usuario de esta sesión no es administrador.',
		                            closable: false,
		                            buttons: [{
		                                label: 'Aceptar',
		                                cssClass: 'btn-default',
		                                action: function() {
		                                    dialog.close();
	                                }
	                            }]
                        	});
	               		}
	               		
	               	}
                }else{
                	
                    alert('La petición no se pudo completar, inténtelo de nuevo.');
                }
            },
            error: function(){
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        });
    });



	$('#submit-habilitar-usuario').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("habilitar_usuario").submit();
				}
			}
		});
	});

	$('#submit-inhabilitar-usuario').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("inhabilitar_usuario").submit();
				}
			}
		});
	});

	$('#btnLimpiar').click(function(){
		$('#search').val(null);
		$('#search_tipo_doc_identidad').val(0);
		$('#search_documento_identidad').val(null);
		$('#search_herramienta').val(0);
		$('#search_documento_identidad').prop('disabled', true);
	});

	$('#search_tipo_doc_identidad').on("change",function(){
		var tipo_doc_identidad = $('#search_tipo_doc_identidad').val();
		
		if(tipo_doc_identidad==1){
			//es DNI (maximo 7 digitos)
			$('#search_documento_identidad').prop('disabled', false);
			$('#search_documento_identidad').prop('maxLength', '8');

		}else if(tipo_doc_identidad==2){
			//es RUC (maximo 10 digitos)
			$('#search_documento_identidad').prop('disabled', false);
			$('#search_documento_identidad').prop('maxLength', '10');
		}else{
			$('#search_documento_identidad').prop('disabled', true);
			$('#search_documento_identidad').val(null);
		}
	});

	$('#create_tipo_doc_identidad').on("change",function(){
		var tipo_doc_identidad = $('#create_tipo_doc_identidad').val();
		if(tipo_doc_identidad==1){
			//es DNI (maximo 7 digitos)
			$('#documento_identidad').prop('disabled', false);
			$('#documento_identidad').prop('maxLength', '8');

		}else if(tipo_doc_identidad==2){
			//es RUC (maximo 10 digitos)
			$('#documento_identidad').prop('disabled', false);
			$('#documento_identidad').prop('maxLength', '10');
		}else{
			$('#documento_identidad').prop('disabled', true);
			$('#documento_identidad').val(null);
		}
	});

	
	$('#btnAgregarHerramientaSubmit').click(function(){
		agregarNuevasHerramientas();
	});

	$('#btnAgregarSectorSubmit').click(function(){
		agregarNuevosSectores();
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

function agregarNuevosSectores(){
	BootstrapDialog.confirm({
		title: 'Mensaje de Confirmación',
		message: '¿Está seguro que desea realizar esta acción?', 
		type: BootstrapDialog.TYPE_INFO,
		btnCancelLabel: 'Cancelar', 
    	btnOKLabel: 'Aceptar', 
		callback: function(result){
            if(result) {
				document.getElementById("submit-agregar-sectores").submit();
            }
        }
    });					
}

function eliminar_herramienta(e,id){
	usuario_id = $('#usuario_id').val();
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
					url: inside_url+'herramientas/submit_eliminar_herramienta_usuario',
					type: 'POST',
					data: { 
						'idherramientaxusers' : id,
					},
					beforeSend: function(){
						//$(this).prop('disabled',true);
					},
					complete: function(){
						//$(this).prop('disabled',false);
					},
					success: function(response){
						dialog = BootstrapDialog.show({
				            title: 'Mensaje',
				            message: 'Se eliminó la herramienta '+response["nombre_herramienta"]+ ' del usuario',
				            type : BootstrapDialog.TYPE_SUCCESS,
				            buttons: [{
				                label: 'Entendido',
				                action: function(dialog) {
				                    var url = inside_url + "usuarios/mostrar_herramientas_usuario/"+usuario_id;
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

function eliminar_sector(e,id){
	usuario_id = $('#usuario_id').val();
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
					url: inside_url+'sectores/submit_eliminar_sector_usuario',
					type: 'POST',
					data: { 
						'idusersxsector' : id,
					},
					beforeSend: function(){
						//$(this).prop('disabled',true);
					},
					complete: function(){
						//$(this).prop('disabled',false);
					},
					success: function(response){
						dialog = BootstrapDialog.show({
				            title: 'Mensaje',
				            message: 'Se eliminó el sector '+response["nombre_sector"]+ ' del usuario',
				            type : BootstrapDialog.TYPE_SUCCESS,
				            buttons: [{
				                label: 'Entendido',
				                action: function(dialog) {
				                    var url = inside_url + "usuarios/mostrar_sectores_usuario/"+usuario_id;
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

function ver_acciones(e,id){
	usuario_id = $('#usuario_id').val();
	$("#tabla_acciones_disponibles tbody").remove();
	e.preventDefault();	
	$.ajax({
		url: inside_url+'tipos_solicitudes/ver_acciones_herramienta_usuario/'+usuario_id,
		type: 'GET',
		data: { 
			'idherramientaxusers' : id,
		},
		beforeSend: function(){
			//$(this).prop('disabled',true);
		},
		complete: function(){
			//$(this).prop('disabled',false);
		},
		success: function(response){
			if(response.success){
				tamano_arr = response["acciones"].length;
				for (i=0;i<tamano_arr;i++){
					data = "<tr>"
			                +"<td class=\"text-nowrap text-center\">"+(i+1)+"</td>"
			                +"<td class=\"text-nowrap text-center\" style=\"display:none\" id=\"idherramientaxtipo_solicitudxuser"+i+"\">"+response["acciones"][i].idherramientaxtipo_solicitudxuser+"</td>"	
			                +"<td class=\"text-nowrap text-center\">"+response["acciones"][i].nombre_solicitud+"</td>";
            		if(response["acciones"][i].eliminado == null){
                		data += "<td class=\"text-nowrap text-center\"><div class=\"form-check\"><label class=\"form-check-label\"><input id=\"checkbox"+i+"\" type=\"checkbox\" class=\"form-check-input\" checked=\"false\"><label></div></td></tr>"; 
                	}else{
                		"<td class=\"text-nowrap text-center\"><div class=\"form-check\"><label class=\"form-check-label\"><input id=\"checkbox"+i+"\" type=\"checkbox\" class=\"form-check-input\" ><label></div></td></tr>";
                	}	

            		$('#tabla_acciones_disponibles').append(data);
            			
            		                		
            	}            	
	    		$('#modal_acciones').modal('show');
				$('#modal_header_acciones').removeClass();
				$('#modal_header_acciones').addClass("modal-header ");
				$('#modal_header_acciones').addClass("bg-primary");
			}
			
		},
		error: function(){
		}
	});
			
}