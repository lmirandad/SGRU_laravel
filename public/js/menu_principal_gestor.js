$( document ).ready(function(){
		
	if($('#input-file-fur').length){
		
		$("#input-file-fur").fileinput({

		    language: "es",
		    allowedFileExtensions: ["csv","txt","xls","xlsx"],
		    showPreview: false,
		    showUpload: false,
		    
		});

	 	$('#input-file-fur').attr('name', 'file');
	}

	$('#btnLimpiarCodigo').click(function(){
		$('#search_codigo_solicitud').val(null);
	});

	$('#btnSalirTrazabilidad').click(function(){
		$('#modal_requerimientos_mostrar').modal('show');
		$('#modal_requerimientos_trazabilidad').modal('hide');

	});

	//alert($('#solicitud_id_precargar').val());

	if($('#solicitud_id_precargar').val() != null)
	{
		mostrar_datos_req_ready($('#solicitud_id_precargar').val());
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
		        	document.getElementById("submit-cargar").submit();
				}
			}
		});
	});

	$('#btnCrearTransaccion').click(function(){
		
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
		        	document.getElementById("submit-crear-transaccion").submit();
				}
			}
		});
	});

	$('#btnLimpiarTransaccion').click(function(){
		$('#trAccion').val(null);
		$('#trNombreUsuario').val(null);
		$('#trNumeroDocumento').val(null);
		$('#trCargo').val(null);
		$('#trAplicativo').val(null);
		$('#slcPuntoVenta').val(null);
	});

	$('#btnCrearTrazabilidad').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
		        	
		        	document.getElementById("submit-crear-trazabilidad").submit();
				}
			}
		});
	});

	$('#btnLimpiarTrazabilidad').click(function(){
		$('#trObservacion').val(null);
	});

	$('#btnRechazarRequerimiento').click(function(){
		
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
		        	document.getElementById("submit-rechazar").submit();
				}
			}
		});
	});

	$('#btnActualizarCodigos').click(function(){
		document.getElementById("submit-actualizar").submit();		
	});

	$('#checkboxAllTrabajar').change(function() {
       if(this.checked){
       		size_table = document.getElementById("table_requerimientos").rows.length-1;
       		for(i=0;i<size_table;i++){
				$('#checkboxTrabajar'+i).prop('checked', true);
			}
       }else{
       		size_table = document.getElementById("table_requerimientos").rows.length-1;
       		for(i=0;i<size_table;i++){
				$('#checkboxTrabajar'+i).prop('checked', false);
			}
       }
    });

    $('#checkboxAllFinalizar').change(function() {
       if(this.checked){
       		size_table = document.getElementById("table_requerimientos").rows.length-1;
       		for(i=0;i<size_table;i++){
				$('#checkboxAtender'+i).prop('checked', true);
			}
       }else{
       		size_table = document.getElementById("table_requerimientos").rows.length-1;
       		for(i=0;i<size_table;i++){
				$('#checkboxAtender'+i).prop('checked', false);
			}
       }
    });

    $('#btnProcesarCodigos').click(function(){
    	procesar_requerimiento();
    })

    $('#btnFinalizarCodigos').click(function(){
    	finalizar_requerimiento();
    })

    $('#btnCancelarRechazarRequerimiento').click(function()
    {
    	$('#requerimiento_id_rechazar').val(null);
		$('#modal_requerimientos_rechazar').modal('hide');
		$('#observacion_rechazo').val(null);
    	$('#modal_requerimientos_mostrar').modal('show');
    });
	
    $('#btnRechazarSolicitud').click(function()
    {
    	BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
		        	document.getElementById("submit-rechazar-solicitud").submit();
				}
			}
		});
    });

});




function cargar_base(e,id)
{
	e.preventDefault();	
	$('#solicitud_id').val(id);
	$('#modal_requerimientos_carga').modal({
	    backdrop: 'static',
	    keyboard: false
	});
	$('#modal_requerimientos_carga').modal('show');
	$('#modal_header_requerimientos_carga').removeClass();
	$('#modal_header_requerimientos_carga').addClass("modal-header ");
	$('#modal_header_requerimientos_carga').addClass("bg-primary");
}


function mostrar_datos_req(e,id)
{
	e.preventDefault();
	$("#table_requerimientos tbody").remove();
	$('#solicitud_id_mostrar').val(id);
	$('#solicitud_id_eliminar_base').val(id);
	$('#solicitud_id_finalizar').val(id);
	$('#solicitud_id_procesar').val(id);
	$('#solicitud_id_nueva_transaccion').val(id);	

	if($('#solicitud_id_mostrar').val().localeCompare('')!=0)
	{
		if(id == $('#solicitud_id_precargar').val())
		{
			//si son iguales, ocultar el <div> de los mensajes
			$('#message-in-modal').removeAttr("style");
		}else
		{
			$('#message-in-modal').removeAttr('style');
			$('#message-in-modal').css('display','none');
		}

		$.ajax({
			url: inside_url+'requerimientos/mostrar_lista_requerimientos',
			type: 'POST',
			data: { 
				'idsolicitud' : id,
			},
			beforeSend: function(){
				$(".loader_container").show();
			},
			complete: function(){
				//$(this).prop('disabled',false);
			},
			success: function(response){
				if(response["tiene_transacciones"])
				{
					arr_requerimientos = response["transacciones"];
					solicitud = response["solicitud"];

					$('#solicitud-title').text('SOLICITUD N° '+solicitud.codigo_solicitud+' - REQUERIMIENTOS REGISTRADOS' + ' - ENTIDAD: '+ response["entidad"].nombre);

					cantidad_requerimientos = arr_requerimientos.length;
					for (i=0;i<cantidad_requerimientos;i++){
						
						nombre_herramienta = (arr_requerimientos[i].nombre_herramienta == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_herramienta ;  
						nombre_tipo_requerimiento = (arr_requerimientos[i].nombre_tipo_requerimiento == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_tipo_requerimiento ;  
						nombre_usuario = (arr_requerimientos[i].nombre_usuario == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_usuario ;  
						//nombre_canal = (arr_requerimientos[i].nombre_canal == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_canal ;  
						//nombre_entidad = (arr_requerimientos[i].nombre_entidad == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_entidad ;  
						//nombre_punto_venta = (arr_requerimientos[i].nombre_punto_venta == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_punto_venta ;  
						codigo_requerimiento = "";

						if(arr_requerimientos[i].numero_documento == null)
							numero_documento = "BLOQUEADO";
						else
							numero_documento = arr_requerimientos[i].numero_documento;

						if(arr_requerimientos[i].codigo_requerimiento == null)
							codigo_requerimiento = "SIN_REQ";
						else
							codigo_requerimiento = arr_requerimientos[i].codigo_requerimiento;


						boton_procesar = "<td class=\"text-nowrap text-center\"><div class=\"form-check\"><label class=\"form-check-label\"><input id=\"checkboxTrabajar"+i+"\" name=\"checkboxTrabajar"+i+"\" type=\"checkbox\" class=\"form-check-input\" value = 1><label></div></td>";
						boton_atender = "<td class=\"text-nowrap text-center\"><div class=\"form-check\"><label class=\"form-check-label\"><input id=\"checkboxAtender"+i+"\" name=\"checkboxAtender"+i+"\" type=\"checkbox\" class=\"form-check-input\" value=0><label></div></td>";
						boton_rechazar = "<td class=\"text-nowrap\"><div style=\"text-align:center\"><button class=\"btn btn-danger btn-sm\" onclick=\"rechazar_requerimiento(event,"+arr_requerimientos[i].idtransaccion+")\" type=\"button\"><span class=\"lnr lnr-thumbs-down\"></span></button></div></td>";

						data = "<tr>"
				                +"<td class=\"text-nowrap text-center\">"+arr_requerimientos[i].idtransaccion+"</td>"
				                +"<td class=\"text-nowrap text-center\" style=\"display:none\"><input type=\"text\" class=\"form-control\" name=\"idtransacciones[]\"  value = "+arr_requerimientos[i].idtransaccion+"></td>";
				                +"<td class=\"text-nowrap text-center\" style=\"display:none\" id=\"idtransaccion"+i+"\">"+arr_requerimientos[i].idtransaccion+"</td>";
				                

				        if(arr_requerimientos[i].idestado_transaccion == 3 || arr_requerimientos[i].idestado_transaccion == 4)
				        	data = data +  "<td class=\"text-nowrap text-center\"><input type=\"text\" class=\"form-control\" name=\"codigos[]\"  id=\"codigo_requerimiento"+arr_requerimientos[i].idtransaccion+"\" value = "+codigo_requerimiento+"></td>";       
				        else
				        	data = data + "<td class=\"text-nowrap text-center\"><input type=\"text\" class=\"form-control\" name=\"codigos[]\" readonly id=\"codigo_requerimiento"+arr_requerimientos[i].idtransaccion+"\" value = "+codigo_requerimiento+"></td>";       
				                
				         data = data +"<td class=\"text-nowrap text-center\">"+arr_requerimientos[i].accion_requerimiento+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+nombre_herramienta+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+nombre_tipo_requerimiento+"</td>"
				                /*+"<td class=\"text-nowrap text-center\">"+nombre_canal+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+nombre_entidad+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+nombre_punto_venta+"</td>"*/
				                +"<td class=\"text-nowrap text-center\">"+arr_requerimientos[i].cargo_canal+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+nombre_usuario+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+numero_documento+"</td>"
				                +"<td class=\"text-nowrap text-center\"><strong>"+arr_requerimientos[i].nombre_estado_transaccion+"</strong></td>"
				                +"<td class=\"text-nowrap\"><div style=\"text-align:center\"><button class=\"btn btn-info btn-sm\" onclick=\"mostrar_observaciones(event,"+arr_requerimientos[i].idtransaccion+")\" type=\"button\"><span class=\"fa fa-search\"></span></button></div></td>";

				        if(arr_requerimientos[i].idestado_transaccion == 3){ //pendiente
				        	data = data + boton_procesar + "<td class=\"text-nowrap text-center\">-</td>" + boton_rechazar;
				        	data = data + "<td class=\"text-nowrap\"><div style=\"text-align:center\"><button class=\"btn btn-danger btn-sm\" onclick=\"eliminar_transaccion(event,"+arr_requerimientos[i].idtransaccion+")\" type=\"button\"><span class=\"fa fa-times\"></span></button></div></td></tr>";
				        }
				        else if (arr_requerimientos[i].idestado_transaccion == 4){//procesando
				        	data = data + "<td class=\"text-nowrap text-center\">-</td>" + boton_atender + boton_rechazar;
				        	data = data + "<td class=\"text-nowrap\"><div style=\"text-align:center\"><button class=\"btn btn-danger btn-sm\" onclick=\"eliminar_transaccion(event,"+arr_requerimientos[i].idtransaccion+")\" type=\"button\"><span class=\"fa fa-times\"></span></button></div></td></tr>";
				        }
				        else 
				        	data = data
				        			+ "<td class=\"text-nowrap text-center\">-</td>" 
				           			+ "<td class=\"text-nowrap text-center\">-</td>"
				           			+ "<td class=\"text-nowrap text-center\">-</td>"
				        			+ "<td class=\"text-nowrap text-center\">-</td></tr>";  

				       

	            		$('#table_requerimientos').append(data);	            		                		
	            	}

	            	$('#slcPuntoVenta')[0].options.length = 0;
	            	//POBLAR SELECTBOX DE PUNTOS DE VENTA
	            	if(response["puntos_venta"].length > 0 )
	            	{
	            		$('#slcPuntoVenta')[0].options.add(new Option("Seleccione",""));	
	            		arr_puntos_venta = response["puntos_venta"];
	            		cantidad_puntos_venta = arr_puntos_venta.length;
	            		for(j=0;j<cantidad_puntos_venta;j++)
	            		{
	            			//$('#slcPuntoVenta')[0].options.add(new Option(arr_puntos_venta[j].nombre,arr_puntos_venta[j].idpunto_venta));
	            			$('#slcPuntoVenta')[0].options.add(new Option(arr_puntos_venta[j].nombre,arr_puntos_venta[j].nombre));
	            		}
	            	}
	            	

	            	$('#modal_requerimientos_mostrar').modal({
					    backdrop: 'static',
					    keyboard: false
					});
	            	$('#modal_requerimientos_mostrar').modal('show');
					$('#modal_header_requerimientos_mostrar').removeClass();
					$('#modal_header_requerimientos_mostrar').addClass("modal-header ");
					$('#modal_header_requerimientos_mostrar').addClass("bg-primary");
				}else
				{
					dialog = BootstrapDialog.show({
			            title: 'Mensaje',
			            message: 'No existen requerimientos registrados.',
			            type : BootstrapDialog.TYPE_DANGER,
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


function rechazar_requerimiento(e,idtransaccion)
{
	e.preventDefault();
	$('#modal_requerimientos_mostrar').modal('hide');
	$.ajax({
		url: inside_url+'requerimientos/rechazar_requerimiento',
		type: 'POST',
		data: { 
			'idtransaccion' : idtransaccion,
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
				$('#requerimiento_id_rechazar').val(response["transaccion"].idtransaccion);
				$('#modal_requerimientos_rechazar').modal('show');
				$('#modal_header_requerimientos_rechazar').removeClass();
				$('#modal_header_requerimientos_rechazar').addClass("modal-header ");
				$('#modal_header_requerimientos_rechazar').addClass("bg-primary");
				$('#observacion_rechazo').val(response["transaccion"].observaciones);



			}else
			{
				dialog = BootstrapDialog.show({
			        title: 'Mensaje',
			        message: 'Transacción no existe.',
			        type : BootstrapDialog.TYPE_DANGER,
			        buttons: [{
			            label: 'Entendido',
			            action: function(dialog) {
			            	$('#modal_requerimientos_mostrar').modal('show');
			             	dialog.close();   
			            },
			   		 }]
			    });
			}		
			
		},
		error: function(){
		}
	});
}

function finalizar_requerimiento()
{
	$('#div_ids_checkbox_finalizar').empty();
	BootstrapDialog.confirm({
		title: 'Mensaje de Confirmación',
		message: '¿Está seguro que desea realizar esta acción?', 
		type: BootstrapDialog.TYPE_INFO,
		btnCancelLabel: 'Cancelar', 
		btnOKLabel: 'Aceptar', 
		callback: function(result){
	        if(result) {
	        	size_table = document.getElementById("table_requerimientos").rows.length-1;

				cantidad_seleccionados = 0;
				cantidad_errores = 0;
				for(i=0;i<size_table;i++){

					if($('#checkboxAtender'+i).is(':checked') == true)
					{
						html = "<input style=\"display:none\" name='ids_checkbox_finalizar[]' id=\"ids_checkbox_finalizar\" value=\""+1+"\" readonly/>";
						cantidad_seleccionados++;
						//como se encuentra activo se valida si es REMEDY y tiene codigo.
						idtransaccion = document.getElementById("table_requerimientos").rows[i+1].cells[0].innerHTML;
						codigo_transaccion = $('#codigo_requerimiento'+idtransaccion).val();
						tipo_atencion = document.getElementById("table_requerimientos").rows[i+1].cells[6].innerHTML;

						valor_comparacion = tipo_atencion.localeCompare('REMEDY');
						if(valor_comparacion == 0)
						{
							//los datos son iguales entonces validamos que el codigo de requerimiento sea tipo REQ
							comparacion_SIN_REQ = codigo_transaccion.localeCompare('SIN_REQ');
							comparacion_vacio = codigo_transaccion.localeCompare('');
							if(comparacion_vacio == 0 || comparacion_SIN_REQ == 0)
							{
								
								cantidad_errores++;
								break;
								
							}
								
						}

						$('#div_ids_checkbox_finalizar').append(html);

					}else
					{
						html = "<input style=\"display:none\" name='ids_checkbox_finalizar[]' id=\"ids_checkbox_finalizar\" value=\""+0+"\" readonly/>";
						$('#div_ids_checkbox_finalizar').append(html);
					}
				}

				
				if(cantidad_seleccionados == 0)
				{
					$('#modal_requerimientos_mostrar').modal('hide');
					dialog = BootstrapDialog.show({
				        title: 'Mensaje',
				        message: 'No se han seleccionado transacciones para procesar.',
				        type : BootstrapDialog.TYPE_DANGER,
				        buttons: [{
				            label: 'Entendido',
				            action: function(dialog) {
				            	$('#modal_requerimientos_mostrar').modal('show');
				             	dialog.close();  
				            }
				   		 }]
				    });
				}else
				{
					if(cantidad_errores == 0)
						document.getElementById("submit-finalizar").submit();	
					else
					{
						$('#modal_requerimientos_mostrar').modal('hide');
							dialog = BootstrapDialog.show({
						        title: 'Mensaje',
						        message: 'La transacción N° '+idtransaccion + " no cuenta con un código de requerimiento válido (REMEDY)",
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
				}
	        	
			}
		}
	});
}

function procesar_requerimiento()
{
	$('#div_ids_checkbox').empty();
	BootstrapDialog.confirm({
		title: 'Mensaje de Confirmación',
		message: '¿Está seguro que desea realizar esta acción?', 
		type: BootstrapDialog.TYPE_INFO,
		btnCancelLabel: 'Cancelar', 
		btnOKLabel: 'Aceptar', 
		callback: function(result){
	        if(result) {
	        	size_table = document.getElementById("table_requerimientos").rows.length-1;

				cantidad_seleccionados = 0;
				cantidad_errores = 0;
				
				for(i=0;i<size_table;i++){

					if($('#checkboxTrabajar'+i).is(':checked') == true)
					{
						html = "<input style=\"display:none\" name='ids_checkbox[]' id=\"ids_checkbox\" value=\""+1+"\" readonly/>";

						cantidad_seleccionados++;
						//como se encuentra activo se valida si es REMEDY y tiene codigo.
						idtransaccion = document.getElementById("table_requerimientos").rows[i+1].cells[0].innerHTML;
						codigo_transaccion = $('#codigo_requerimiento'+idtransaccion).val();
						tipo_atencion = document.getElementById("table_requerimientos").rows[i+1].cells[6].innerHTML;

						valor_comparacion = tipo_atencion.localeCompare('REMEDY');
						if(valor_comparacion == 0)
						{
							//los datos son iguales entonces validamos que el codigo de requerimiento sea tipo REQ
							comparacion_SIN_REQ = codigo_transaccion.localeCompare('SIN_REQ');
							comparacion_vacio = codigo_transaccion.localeCompare('');
							if(comparacion_vacio == 0 || comparacion_SIN_REQ == 0)
							{
								
								cantidad_errores++;
								break;
													    						    
							}
						}

						$('#div_ids_checkbox').append(html);

					}else
					{
						html = "<input style=\"display:none\" name='ids_checkbox[]' id=\"ids_checkbox\" value=\""+0+"\" readonly/>";
						$('#div_ids_checkbox').append(html);
					}
				}

				if(cantidad_seleccionados == 0)
				{
					$('#modal_requerimientos_mostrar').modal('hide');
					dialog = BootstrapDialog.show({
				        title: 'Mensaje',
				        message: 'No se han seleccionado transacciones para procesar.',
				        type : BootstrapDialog.TYPE_DANGER,
				        buttons: [{
				            label: 'Entendido',
				            action: function(dialog) {
				            	$('#modal_requerimientos_mostrar').modal('show');
				             	dialog.close();  
				            }
				   		 }]
				    });
				}else
				{
					if(cantidad_errores == 0)
						document.getElementById("submit-procesar").submit();	
					else
					{
						$('#modal_requerimientos_mostrar').modal('hide');
							dialog = BootstrapDialog.show({
						        title: 'Mensaje',
						        message: 'La transacción N° '+idtransaccion + " no cuenta con un código de requerimiento válido (REMEDY)",
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
				}
	        	
			}
		}
	});

}

function mostrar_datos_req_ready(id)
{
	$("#table_requerimientos tbody").remove();
	$('#solicitud_id_mostrar').val(id);
	$('#solicitud_id_eliminar_base').val(id);
	$('#solicitud_id_finalizar').val(id);
	$('#solicitud_id_procesar').val(id);
	$('#solicitud_id_nueva_transaccion').val(id);
	


	if($('#solicitud_id_precargar').val().localeCompare('')!=0)
	{
		if(id == $('#solicitud_id_precargar').val())
		{
			//si son iguales, ocultar el <div> de los mensajes
			$('#message-in-modal').removeAttr("style");
			$('#message-in-modal-2').removeAttr("style");
		}else
		{
			$('#message-in-modal').removeAttr('style');
			$('#message-in-modal').css('display','none');
			$('#message-in-modal-2').removeAttr('style');
			$('#message-in-modal-2').css('display','none');
			
		}
		
		


		$.ajax({
			url: inside_url+'requerimientos/mostrar_lista_requerimientos',
			type: 'POST',
			data: { 
				'idsolicitud' : id,
			},
			beforeSend: function(){
				$(".loader_container").show();
			},
			complete: function(){
				//$(this).prop('disabled',false);
			},
			success: function(response){
				if(response["tiene_transacciones"])
				{
					 
					arr_requerimientos = response["transacciones"];
					solicitud = response["solicitud"];
					$('#solicitud-title').text('SOLICITUD N° '+solicitud.codigo_solicitud+' - REQUERIMIENTOS REGISTRADOS' + ' - ENTIDAD: '+ response["entidad"].nombre);
					cantidad_requerimientos = arr_requerimientos.length;
					for (i=0;i<cantidad_requerimientos;i++){
						
						nombre_herramienta = (arr_requerimientos[i].nombre_herramienta == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_herramienta ;  
						//nombre_denominacion = (arr_requerimientos[i].nombre_denominacion == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_denominacion ;  
						nombre_usuario = (arr_requerimientos[i].nombre_usuario == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_usuario ;  
						nombre_tipo_requerimiento = (arr_requerimientos[i].nombre_tipo_requerimiento == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_tipo_requerimiento ;  
						//nombre_canal = (arr_requerimientos[i].nombre_canal == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_canal ;  
						//nombre_entidad = (arr_requerimientos[i].nombre_entidad == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_entidad ;  
						//nombre_punto_venta = (arr_requerimientos[i].nombre_punto_venta == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_punto_venta ;  
						codigo_requerimiento = "";

						if(arr_requerimientos[i].numero_documento == null)
							numero_documento = "BLOQUEADO";
						else
							numero_documento = arr_requerimientos[i].numero_documento;

						if(arr_requerimientos[i].codigo_requerimiento == null)
							codigo_requerimiento = "SIN_REQ";
						else
							codigo_requerimiento = arr_requerimientos[i].codigo_requerimiento;


						boton_procesar = "<td class=\"text-nowrap text-center\"><div class=\"form-check\"><label class=\"form-check-label\"><input id=\"checkboxTrabajar"+i+"\" name=\"checkboxTrabajar"+i+"\" type=\"checkbox\" class=\"form-check-input\" value=1><label></div></td>";
						boton_atender = "<td class=\"text-nowrap text-center\"><div class=\"form-check\"><label class=\"form-check-label\"><input id=\"checkboxAtender"+i+"\" name=\"checkboxAtender"+i+"\" type=\"checkbox\" class=\"form-check-input\" value=0><label></div></td>";
						boton_rechazar = "<td class=\"text-nowrap\"><div style=\"text-align:center\"><button class=\"btn btn-danger btn-sm\" onclick=\"rechazar_requerimiento(event,"+arr_requerimientos[i].idtransaccion+")\" type=\"button\"><span class=\"lnr lnr-thumbs-down\"></span></button></div></td>";

						data = "<tr>"
				                +"<td class=\"text-nowrap text-center\">"+arr_requerimientos[i].idtransaccion+"</td>"
				                +"<td class=\"text-nowrap text-center\" style=\"display:none\"><input type=\"text\" class=\"form-control\" name=\"idtransacciones[]\"  value = "+arr_requerimientos[i].idtransaccion+"></td>";
				                +"<td class=\"text-nowrap text-center\" style=\"display:none\" id=\"idtransaccion"+i+"\">"+arr_requerimientos[i].idtransaccion+"</td>";
				                

				        if(arr_requerimientos[i].idestado_transaccion == 3 || arr_requerimientos[i].idestado_transaccion == 4)
				        	data = data +  "<td class=\"text-nowrap text-center\"><input type=\"text\" class=\"form-control\" name=\"codigos[]\"  id=\"codigo_requerimiento"+arr_requerimientos[i].idtransaccion+"\" value = "+codigo_requerimiento+"></td>";       
				        else
				        	data = data + "<td class=\"text-nowrap text-center\"><input type=\"text\" class=\"form-control\" name=\"codigos[]\" readonly id=\"codigo_requerimiento"+arr_requerimientos[i].idtransaccion+"\" value = "+codigo_requerimiento+"></td>";       
				                
				         data = data +"<td class=\"text-nowrap text-center\">"+arr_requerimientos[i].accion_requerimiento+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+nombre_herramienta+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+nombre_tipo_requerimiento+"</td>"
				                /*+"<td class=\"text-nowrap text-center\">"+nombre_canal+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+nombre_entidad+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+nombre_punto_venta+"</td>"*/
				                +"<td class=\"text-nowrap text-center\">"+arr_requerimientos[i].cargo_canal+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+nombre_usuario+"</td>"
				                +"<td class=\"text-nowrap text-center\">"+numero_documento+"</td>"
				                +"<td class=\"text-nowrap text-center\"><strong>"+arr_requerimientos[i].nombre_estado_transaccion+"</strong></td>"
				                +"<td class=\"text-nowrap\"><div style=\"text-align:center\"><button class=\"btn btn-info btn-sm\" onclick=\"mostrar_observaciones(event,"+arr_requerimientos[i].idtransaccion+")\" type=\"button\"><span class=\"fa fa-search\"></span></button></div></td>";

				        if(arr_requerimientos[i].idestado_transaccion == 3){ //pendiente
				        	data = data + boton_procesar + "<td class=\"text-nowrap text-center\">-</td>" + boton_rechazar;
				        	data = data + "<td class=\"text-nowrap\"><div style=\"text-align:center\"><button class=\"btn btn-danger btn-sm\" onclick=\"eliminar_transaccion(event,"+arr_requerimientos[i].idtransaccion+")\" type=\"button\"><span class=\"fa fa-times\"></span></button></div></td></tr>";
				        }
				        else if (arr_requerimientos[i].idestado_transaccion == 4){//procesando
				        	data = data + "<td class=\"text-nowrap text-center\">-</td>" + boton_atender + boton_rechazar;
				        	data = data + "<td class=\"text-nowrap\"><div style=\"text-align:center\"><button class=\"btn btn-danger btn-sm\" onclick=\"eliminar_transaccion(event,"+arr_requerimientos[i].idtransaccion+")\" type=\"button\"><span class=\"fa fa-times\"></span></button></div></td></tr>";
				        }
				        else 
				        	data = data
				        			+ "<td class=\"text-nowrap text-center\">-</td>" 
				           			+ "<td class=\"text-nowrap text-center\">-</td>"
				           			+ "<td class=\"text-nowrap text-center\">-</td>"
				        			+ "<td class=\"text-nowrap text-center\">-</td></tr>";  

				        

	            		$('#table_requerimientos').append(data);
	            	}

	            	$('#slcPuntoVenta')[0].options.length = 0;
	            	//POBLAR SELECTBOX DE PUNTOS DE VENTA
	            	if(response["puntos_venta"].length > 0 )
	            	{
	            		$('#slcPuntoVenta')[0].options.add(new Option("Seleccione",""));	
	            		arr_puntos_venta = response["puntos_venta"];
	            		cantidad_puntos_venta = arr_puntos_venta.length;
	            		for(j=0;j<cantidad_puntos_venta;j++)
	            		{
	            			//$('#slcPuntoVenta')[0].options.add(new Option(arr_puntos_venta[j].nombre,arr_puntos_venta[j].idpunto_venta));
	            			$('#slcPuntoVenta')[0].options.add(new Option(arr_puntos_venta[j].nombre,arr_puntos_venta[j].nombre));
	            		}
	            	}

	            	$('#modal_requerimientos_mostrar').modal({
					    backdrop: 'static',
					    keyboard: false
					});
	            	$('#modal_requerimientos_mostrar').modal('show');
					$('#modal_header_requerimientos_mostrar').removeClass();
					$('#modal_header_requerimientos_mostrar').addClass("modal-header ");
					$('#modal_header_requerimientos_mostrar').addClass("bg-primary");
					$('#modal_header_requerimientos_mostrar').addClass('modal-open');
				}
				
			},
			error: function(){
			}
		});
	}

	

}


function eliminar_transaccion(e,idtransaccion)
{
	$('#transaccion_id_eliminar').val(idtransaccion);
	BootstrapDialog.confirm({
		title: 'Mensaje de Confirmación',
		message: '¿Está seguro que desea realizar esta acción?', 
		type: BootstrapDialog.TYPE_DANGER,
		btnCancelLabel: 'Cancelar', 
    	btnOKLabel: 'Aceptar', 
		callback: function(result){
	        if(result) {
	        	document.getElementById("submit-eliminar-transaccion").submit();
			}
		}
	});

}

function mostrar_observaciones(e,id)
{

	e.preventDefault();
	$("#table_trazabilidad tbody").remove();
	$('#modal_requerimientos_mostrar').modal('hide');
	$('#transaccion_id_trazabilidad').val(id);
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

				        if(arr_trazabilidad[i].iduser_created_by == null){
				        	data = data + "<td class=\"text-nowrap text-center\">-</td>";
				        	data = data + "<td class=\"text-nowrap text-center\">-</td></tr>";	
				        }else
				        {
				        	data = data + "<td class=\"text-nowrap text-center\"><div style=\"text-align:center\"><button class=\"btn btn-warning btn-sm\" onclick=\"editar_observacion(event,"+arr_trazabilidad[i].idtrazabilidad_transaccion+")\" type=\"button\"><span class=\"lnr lnr-pencil\"></span></button></div></td>";
				        	data = data + "<td class=\"text-nowrap text-center\"><div style=\"text-align:center\"><button class=\"btn btn-danger btn-sm\" onclick=\"eliminar_observacion(event,"+arr_trazabilidad[i].idtrazabilidad_transaccion+")\" type=\"button\"><span class=\"fa fa-times\"></span></button></div></td></tr>";
				        }
				        


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

function editar_observacion(e,id)
{
	e.preventDefault();
	$.ajax({
		url: inside_url+'requerimientos/ver_observacion_transaccion',
		type: 'POST',
		data: { 
			'idobservacion' : id,
		},
		beforeSend: function(){
			$(".loader_container").show();
		},
		complete: function(){
			//$(this).prop('disabled',false);
		},
		success: function(response){

			if(response["trazabilidad"] != null)
			{
				$('#trObservacion').val(response["trazabilidad"].descripcion);
				$('#trazabilidad_id_editar').val(response["trazabilidad"].idtrazabilidad_transaccion);
			}
			
		},
		error: function(){
		}
	});
}

function eliminar_observacion(e,id)
{
	e.preventDefault();
	$('#trazabilidad_id_eliminar').val(id);
	BootstrapDialog.confirm({
		title: 'Mensaje de Confirmación',
		message: '¿Está seguro que desea realizar esta acción?', 
		type: BootstrapDialog.TYPE_INFO,
		btnCancelLabel: 'Cancelar', 
    	btnOKLabel: 'Aceptar', 
		callback: function(result){
	        if(result) {
	        	document.getElementById("submit-eliminar-observacion").submit();
			}
		}
	});
}

function rechazar_solicitud(e,id)
{
	e.preventDefault();
	$('#solicitud_id_rechazar').val(id);

	$('#modal_solicitud_rechazar').modal({
		backdrop: 'static',
		keyboard: false
	});
    $('#modal_solicitud_rechazar').modal('show');
	$('#modal_header_solicitud_rechazar').removeClass();
	$('#modal_header_solicitud_rechazar').addClass("modal-header ");
	$('#modal_header_solicitud_rechazar').addClass("bg-primary");
	$('#modal_header_solicitud_rechazar').addClass('modal-open');
}
