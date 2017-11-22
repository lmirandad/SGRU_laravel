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
	                            message: 'Se ha reestablecido la contraseña del usuario'+response["usuario"].nombre,
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



	/*$('#submit-enable-user').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("enable_user").submit();
				}
			}
		});
	});*/

	/*$('#submit-disable-user').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("disable_user").submit();
				}
			}
		});
	});*/

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

	$('#btnAgregarHerramienta').click(function(){
		mostrar_herramientas_disponibles();
	});

	$('#btnCerrarModal').click(function(){
		$("#tabla_herramientas_disponibles tbody").remove(); 
	});

	$('#btnAgregarHerramientaSubmit').click(function(){
		agregarNuevasHerramientas();
	});
});

function mostrar_herramientas_disponibles(){
	usuario_id = $('#usuario_id').val();
	$.ajax({
            url: inside_url+'herramientas/listar_herramientas_disponibles',
            type: 'POST',
            data: {'usuario_id':usuario_id},
            beforeSend: function(){
                $(".loader_container").show();
            },
            complete: function(){
                $(".loader_container").hide();
            },
            success: function(response){
                if(response.success){
                	arr_herramientas = response["herramientas"];
                	tamano_arr_herramientas = arr_herramientas.length;
                	contador = 0;
                	for (i=0;i<tamano_arr_herramientas;i++){
                		if(arr_herramientas[i].idherramientaxusers == null){
	                		$('#tabla_herramientas_disponibles').append("<tr>"
				                +"<td class=\"text-nowrap text-center\">"+(contador+1)+"</td>"
				                +"<td class=\"text-nowrap text-center\" style=\"display:none\" id=\"idherramienta"+contador+"\">"+arr_herramientas[i].idherramienta+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+arr_herramientas[i].nombre+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+arr_herramientas[i].nombre_tipo_herramienta+"</td>"
				                +"<td class=\"text-nowrap text-center\"><div class=\"form-check\"><label class=\"form-check-label\"><input id=\"checkbox"+contador+"\" type=\"checkbox\" class=\"form-check-input\"><label></div></td></tr>");
                			contador++;
                		}                		
                	}
                	if(contador==0){
                		dialog = BootstrapDialog.show({
				            title: 'Mensaje',
				            message: 'El usuario posee todos los aplicativos para registrar.',
				            type : BootstrapDialog.TYPE_INFO,
				            buttons: [{
				                label: 'Aceptar',
				                action: function(dialog) {
				                    dialog.close();
				                }
				            }]
				        });		
                	}else{
                		$('#modal_herramientas').modal('show');
	   					$('#modal_header_herramientas').removeClass();
	    				$('#modal_header_herramientas').addClass("modal-header ");
	    				$('#modal_header_herramientas').addClass("bg-primary");
                	}
                   	
                }else{                	
                    alert('La petición no se pudo completar, inténtelo de nuevo.');
                }
            },
            error: function(){
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        });
}

function agregarNuevasHerramientas(){
	tamano_tabla = document.getElementById("tabla_herramientas_disponibles").rows.length - 1; //tamaño de la tabla total
	var arr_idherramientas = [];
	indice = 0;
	for(i=0;i<tamano_tabla;i++){
		if($('#checkbox'+i).is(":checked")){ //si fue elegido se agrega al arreglo
			arr_idherramientas[indice] = $('#idherramienta'+i).text();
			indice++;
		}	
	}
	var tamano_arr_idherramientas = arr_idherramientas.length;
	if(tamano_arr_idherramientas == 0){
		//no se seleccionó ningún aplicativo.
		$("#tabla_herramientas_disponibles tbody").remove();
		
	}else{
		//se procede a realizar la carga de herramientas al sistema
		usuario_id = $('#usuario_id').val();
		$.ajax({
            url: inside_url+'herramientas/submit_agregar_herramientas',
            type: 'POST',
            data: {'usuario_id':usuario_id,
        		   'herramientas':arr_idherramientas},
            beforeSend: function(){
                $(".loader_container").show();
            },
            complete: function(){
                $(".loader_container").hide();
            },
            success: function(response){
                if(response.success){
                	dialog = BootstrapDialog.show({
			            title: 'Mensaje',
			            message: 'Se agregaron las herramientas al usuario',
			            type : BootstrapDialog.TYPE_SUCCESS,
			            buttons: [{
			                label: 'Aceptar',
			                action: function(dialog) {
			                    var url = inside_url + "usuarios/mostrar_herramientas_usuario/"+usuario_id;
                                window.location = url;
			                }
			            }]
			        });
                }else{                	
                    alert('La petición no se pudo completar, inténtelo de nuevo.');
                }
            },
            error: function(){
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        });
	}
	
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
				                label: 'Aceptar',
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