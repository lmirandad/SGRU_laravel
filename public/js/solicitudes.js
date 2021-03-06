
$( document ).ready(function(){

	if($('#input-file').length){
		$("#input-file").fileinput({
		    language: "es",
		    allowedFileExtensions: ["csv","txt","xls","xlsx"],
		    showPreview: false,
		    showUpload: false,
		});

	 	$('#input-file').attr('name', 'file');
	}


	 if($('#search_datetimepicker1').length && $('#search_datetimepicker2').length){
        $('#search_datetimepicker1').datetimepicker({
     		ignoreReadonly: true,
     		format:'DD-MM-YYYY',
     		locale:'es',
     	});
        $('#search_datetimepicker2').datetimepicker({
            ignoreReadonly: true,
            format:'DD-MM-YYYY',
            locale:'es',
        });
        $("#search_datetimepicker1").on("dp.change", function (e) {
            $('#search_datetimepicker2').data("DateTimePicker").minDate(e.date);
        });
        $("#search_datetimepicker2").on("dp.change", function (e) {
            $('#search_datetimepicker1').data("DateTimePicker").maxDate(e.date);
        });
     }

     $('#btnSalirTrazabilidad').click(function(){
     	$('#modal_requerimientos_trazabilidad').modal('hide');
     });

    $('#btnMostrarFormularioReasingacion').click(function(){
    	mostrar_usuarios_disponibles();
    });
  
	if($('#datetimepicker_creacion_solicitud').length){
        $('#datetimepicker_creacion_solicitud').datetimepicker({
     		ignoreReadonly: true,
     		format:'DD-MM-YYYY',
     		locale:'es',
     		maxDate: 'now',
     	});

     }

	$('#btnCargar').click(function(){
		
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
		        	$('body').loading({
			    		message: 'Cargando..',
			      	});
					document.getElementById("submit-cargar").submit();
				}
			}
		});
	});

	$('#btnDescargarLogs').click(function(){
		document.getElementById("submit-descargar-logs").submit();				
	});

	$('#btnAsignar').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar la asignación? Esto puede tardar unos minutos, dependiendo del volumen de solicitudes por asignar.\n Considerar que para las solicitudes que no tienen aplicativo detectado, se procederán a asignar por usuarios del sector.', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
		        	document.getElementById("submit-asignar").submit();
				}
			}
		});
						
	});

	

	$('#btnLimpiar').click(function(){
		$('#search_solicitud').val(null);
		$('#fecha_asignacion_desde').val(null);
		$('#fecha_asignacion_hasta').val(null);
		$('#search_tipo_solicitud').val(0);
		$('#search_estado_solicitud').val(0);
		$('#search_sector').val(0);
	});

	$('#slcSector').on('change',function(){
		$('#slcCanal')[0].options.length = 0;
		$('#slcEntidad')[0].options.length = 0;
		$('#slcEntidad')[0].options.add(new Option("Seleccione",""));		
		$('#slcHerramienta')[0].options.length = 0;
		idsector = $('#slcSector').val(); 
		if( idsector != ''){
			$.ajax({
	            url: inside_url+'sectores/mostrar_canales_herramientas',
	            type: 'POST',
	            data: { 'sector_id' : idsector,	            		
	                },
	            beforeSend: function(){
	                $(".loader_container").show();
	            },
	            complete: function(){
	                $(".loader_container").hide();
	            },
	            success: function(response){
	                if(response.success){
	                	canales = response["canales"];
	                	if (canales != null)
	                	{
		                	size_canales = canales.length;
		                	$('#slcCanal')[0].options.add(new Option("Seleccione",""));
		                	for(i=0;i<size_canales;i++){
		                		$('#slcCanal')[0].options.add(new Option(canales[i].nombre,canales[i].idcanal));
		                	}
		                }else{
		                	$('#slcCanal')[0].options.add(new Option("Seleccione",""));
		                }

		                herramientas = response["herramientas"];

		                if (herramientas != null)
	                	{
		                	size_herramientas = herramientas.length;
		                	$('#slcHerramienta')[0].options.add(new Option("Seleccione",""));
		                	for(i=0;i<size_herramientas;i++){
		                		$('#slcHerramienta')[0].options.add(new Option(herramientas[i].nombre,herramientas[i].idherramienta));
		                	}
		                }else{
		                	$('#slcHerramienta')[0].options.add(new Option("Seleccione",""));
		                }
	                	
	                }else{
	                	
	                    alert('La petición no se pudo completar, inténtelo de nuevo.');
	                }
	            },
	            error: function(){
	                alert('La petición no se pudo completar, inténtelo de nuevo.');
	            }
	        });
		}else{
			$('#slcCanal')[0].options.add(new Option("Seleccione",""));
			$('#slcHerramienta')[0].options.add(new Option("Seleccione",""));
		}
		
	});

	$('#slcCanal').on('change',function(){
		$('#slcEntidad')[0].options.length = 0;
		idcanal = $('#slcCanal').val(); 
		if( idsector != ''){
			$.ajax({
	            url: inside_url+'entidades/mostrar_entidades',
	            type: 'POST',
	            data: { 'canal_id' : idcanal,	            		
	                },
	            beforeSend: function(){
	                $(".loader_container").show();
	            },
	            complete: function(){
	                $(".loader_container").hide();
	            },
	            success: function(response){
	                if(response.success){
	                	entidades = response["entidades"];
	                	if (entidades != null)
	                	{
		                	size_entidades = entidades.length;
		                	$('#slcEntidad')[0].options.add(new Option("Seleccione",""));
		                	for(i=0;i<size_entidades;i++){
		                		$('#slcEntidad')[0].options.add(new Option(entidades[i].nombre,entidades[i].identidad));
		                	}
		                }else{
		                	$('#slcEntidad')[0].options.add(new Option("Seleccione",""));
		                }
	                	
	                }else{
	                	
	                    alert('La petición no se pudo completar, inténtelo de nuevo.');
	                }
	            },
	            error: function(){
	                alert('La petición no se pudo completar, inténtelo de nuevo.');
	            }
	        });
		}else{
			$('#slcEntidad')[0].options.add(new Option("Seleccione",""));
		}
		
	});

	if($('#codigo_solicitud').length)
		$('#codigo_solicitud').prop('maxLength', '6');

	$('#btnCrearSolicitud').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("submit-crear-solicitud").submit();
				}
			}
		});
	});

});

function mostrar_usuarios_disponibles(e,idsolicitud){
	e.preventDefault();
	$('#slcUsuarios')[0].options.length = 0;
	$('#slcUsuarios')[0].options.add(new Option("Seleccione",""));
	$.ajax({
		url: inside_url+'solicitudes/mostrar_usuarios_disponibles_reasignacion',
		type: 'POST',
		data: { 
			'idsolicitud' : idsolicitud,
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
				usuarios = response["usuarios"];
				if(usuarios == null){
					dialog = BootstrapDialog.show({
			            title: 'Mensaje',
			            message: 'No hay usuarios disponibles para esta solicitud.',
			            type : BootstrapDialog.TYPE_DANGER,
			            buttons: [{
			                label: 'Entendido',
			                action: function(dialog) {
			                	dialog.close();
			                }
			            }]
			        });
				}else
				{
					$('#modal_reasignacion').modal('show');
					tamano_usuarios = usuarios.length;
					for(i=0;i<tamano_usuarios;i++)
					{
						$('#slcUsuarios')[0].options.add(new Option(usuarios[i].nombre_usuario+' '+usuarios[i].apellido_paterno+' '+usuarios[i].apellido_materno+': '+
							usuarios[i].cantidad_solicitudes+' solicitudes asignadas',usuarios[i].id_usuario));
					}
				}
			}
			
			
		},
		error: function(){
		}
	});
			
}

function mostrar_modal_anular(e,idsolicitud)
{
	e.preventDefault();
	BootstrapDialog.confirm({
		title: 'Mensaje de Confirmación',
		message: '¿Está seguro que desea realizar esta acción?', 
		type: BootstrapDialog.TYPE_INFO,
		btnCancelLabel: 'Cancelar', 
    	btnOKLabel: 'Aceptar', 
		callback: function(result){
	        if(result) {
				$('#modal_anulacion').modal('show');
			}
		}
	});

}


function reactivar_transaccion(e,id)
{

	e.preventDefault();
	BootstrapDialog.confirm({
		title: 'Mensaje de Confirmación',
		message: '¿Está seguro que desea realizar esta acción?', 
		type: BootstrapDialog.TYPE_INFO,
		btnCancelLabel: 'Cancelar', 
    	btnOKLabel: 'Aceptar', 
		callback: function(result){
	        if(result) {
				$.ajax({
					url: inside_url+'requerimientos/reactivar_transaccion',
					type: 'POST',
					data: { 
						'idtransaccion' : id,
					},
					beforeSend: function(){
						$(".loader_container").show();
					},
					complete: function(){
						//$(this).prop('disabled',false);
					},
					success: function(response){
						if(response["transaccion"] != null)
						{
							dialog = BootstrapDialog.show({
						        title: 'Mensaje',
						        message: 'La transaccion ID: '+response["transaccion"].idtransaccion+' ha sido reactivada a estado PENDIENTE.',
						        type : BootstrapDialog.TYPE_PRIMARY,
						        buttons: [{
						            label: 'Entendido',
						            action: function(dialog) {
						            	location.reload();   
						            }
						   		 }]
						    });
						}				
						else{
							dialog = BootstrapDialog.show({
						        title: 'Mensaje',
						        message: 'Transacción no encontrada.',
						        type : BootstrapDialog.TYPE_PRIMARY,
						        buttons: [{
						            label: 'Entendido',
						            action: function(dialog) {
						            	dialog.close();   
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

function eliminar_requerimientos(e,id)
{
	e.preventDefault();
	$('#solicitud_id_eliminar_base').val(id);
	BootstrapDialog.confirm({
		title: 'Mensaje de Confirmación',
		message: '¿Está seguro que desea realizar esta acción?<br>Se borrarán todos los requerimientos y transacciones asociados a la solicitud.', 
		type: BootstrapDialog.TYPE_DANGER,
		btnCancelLabel: 'Cancelar', 
    	btnOKLabel: 'Aceptar', 
		callback: function(result){
	        if(result) {
	        	document.getElementById("submit-eliminar").submit();
			}
		}
	});
}

function mostrar_observaciones(e,id)
{

	e.preventDefault();
	$("#table_trazabilidad tbody").remove();
	$.ajax({
		url: inside_url+'requerimientos/ver_observacion',
		type: 'POST',
		data: { 
			'idtransaccion' : id,
		},
		beforeSend: function(){
			$(".loader_container").show();
		},
		complete: function(){
			//$(this).prop('disabled',false);
		},
		success: function(response){

			if(response["transaccion"] != null)
			{
				$('#transaccion-title').text('OBSERVACIONES TRANSACCIÓN ID '+ response["transaccion"].idtransaccion +' - SOLICITUD N° '+response["solicitud"].codigo_solicitud);
				if(response["trazabilidad"] != null && response["trazabilidad"].length>0)
				{
					
					arr_trazabilidad = response["trazabilidad"];
					cantidad_observaciones = arr_trazabilidad.length;

					for(i=0;i<cantidad_observaciones;i++)
					{
						data = "<tr>"
				                +"<td class=\"text-nowrap text-center\">"+(i+1)+"</td>"
				                +"<td class=\"text-nowrap text-center\" style=\"display:none\"><input type=\"text\" class=\"form-control\" name=\"idtrazabilidad[]\"  value = "+arr_trazabilidad[i].idtrazabilidad_transaccion+"></td>";
				                +"<td class=\"text-nowrap text-center\" style=\"display:none\" id=\"idtrazabilidad"+i+"\">"+arr_trazabilidad[i].idtrazabilidad_transaccion+"</td>";
				                

				                 
				         data = data +"<td>"+arr_trazabilidad[i].descripcion+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+arr_trazabilidad[i].fecha_registro+"</td>";

				       
				        


	            		$('#table_trazabilidad').append(data);
					}

					

				}
				$('#modal_requerimientos_trazabilidad').modal({
					    backdrop: 'static',
					    keyboard: false
					});
	            	$('#modal_requerimientos_trazabilidad').modal('show');
					$('#modal_header_requerimientos_trazabilidad').removeClass();
					$('#modal_header_requerimientos_trazabilidad').addClass("modal-header ");
					$('#modal_header_requerimientos_trazabilidad').addClass("bg-primary");
					$('#modal_header_requerimientos_trazabilidad').addClass('modal-open');
			}else
			{
				dialog = BootstrapDialog.show({
			        title: 'Mensaje',
			        message: 'Transaccion no existe.',
			        type : BootstrapDialog.TYPE_DANGER,
			        buttons: [{
			            label: 'Entendido',
			            action: function(dialog) {
			            	$('#modal_requerimientos_mostrar').modal('show');
			             	dialog.close();   
			            }
			   		 }]
			    });
			}		
			
		},
		error: function(){
		}
	});
}