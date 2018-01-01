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
		alert("hola");
		$('#search_codigo_solicitud').val(null);
	});

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
			if(response["tiene_requerimientos"])
			{
				arr_requerimientos = response["requerimientos"];
				cantidad_requerimientos = arr_requerimientos.length;
				for (i=0;i<cantidad_requerimientos;i++){
					
					nombre_herramienta = (arr_requerimientos[i].nombre_herramienta == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_herramienta ;  
					nombre_denominacion = (arr_requerimientos[i].nombre_denominacion == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_denominacion ;  
					nombre_tipo_requerimiento = (arr_requerimientos[i].nombre_tipo_requerimiento == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_tipo_requerimiento ;  
					nombre_canal = (arr_requerimientos[i].nombre_canal == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_canal ;  
					nombre_entidad = (arr_requerimientos[i].nombre_entidad == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_entidad ;  
					nombre_punto_venta = (arr_requerimientos[i].nombre_punto_venta == null) ? 'NO ENCONTRADO' : arr_requerimientos[i].nombre_punto_venta ;  
					
					if(arr_requerimientos[i].numero_documento == null)
						numero_documento = "BLOQUEADO";
					else
						numero_documento = arr_requerimientos[i].numero_documento;


					boton_atender = "<td class=\"text-nowrap\"><div style=\"text-align:center\"><button class=\"btn btn-success btn-sm\" onclick=\"finalizar_requerimiento(event,"+arr_requerimientos[i].idrequerimiento+")\" type=\"button\"><span class=\"lnr lnr-thumbs-up\"></span></button></div></td>";
					boton_rechazar = "<td class=\"text-nowrap\"><div style=\"text-align:center\"><button class=\"btn btn-danger btn-sm\" onclick=\"rechazar_requerimiento(event,"+arr_requerimientos[i].idrequerimiento+")\" type=\"button\"><span class=\"lnr lnr-thumbs-down\"></span></button></div></td></tr>";

					data = "<tr>"
			                +"<td class=\"text-nowrap text-center\">"+(i+1)+"</td>"
			                +"<td class=\"text-nowrap text-center\" style=\"display:none\" id=\"idrequerimiento"+i+"\">"+arr_requerimientos[i].idrequerimiento+"</td>"	
			                +"<td class=\"text-nowrap text-center\" style=\"display:none\"><input type=\"text\" class=\"form-control\" name=\"idrequerimientos[]\"  value = "+arr_requerimientos[i].idrequerimiento+"></td>";
			                

			        if(arr_requerimientos[i].idestado_requerimiento == 3)
			        	data = data +  "<td class=\"text-nowrap text-center\"><input type=\"text\" class=\"form-control\" name=\"codigos[]\"  id=\"codigo_requerimiento"+arr_requerimientos[i].idrequerimiento+"\" value = "+arr_requerimientos[i].codigo_requerimiento+"></td>";       
			        else
			        	data = data + "<td class=\"text-nowrap text-center\"><input type=\"text\" class=\"form-control\" name=\"codigos[]\" readonly id=\"codigo_requerimiento"+arr_requerimientos[i].idrequerimiento+"\" value = "+arr_requerimientos[i].codigo_requerimiento+"></td>";       
			                
			         data = data +"<td class=\"text-nowrap text-center\">"+arr_requerimientos[i].accion_requerimiento+"</td>"
			                +"<td class=\"text-nowrap text-center\">"+nombre_herramienta+"</td>"
			                +"<td class=\"text-nowrap text-center\">"+nombre_denominacion+"</td>"
			                +"<td class=\"text-nowrap text-center\">"+nombre_tipo_requerimiento+"</td>"
			                +"<td class=\"text-nowrap text-center\">"+nombre_canal+"</td>"
			                +"<td class=\"text-nowrap text-center\">"+nombre_entidad+"</td>"
			                +"<td class=\"text-nowrap text-center\">"+nombre_punto_venta+"</td>"
			                +"<td class=\"text-nowrap text-center\">"+arr_requerimientos[i].cargo_canal+"</td>"
			                +"<td class=\"text-nowrap text-center\">"+arr_requerimientos[i].perfil_aplicativo+"</td>"
			                +"<td class=\"text-nowrap text-center\">"+numero_documento+"</td>"
			                +"<td class=\"text-nowrap text-center\"><strong>"+arr_requerimientos[i].nombre_estado_requerimiento+"</strong></td>"
			                +"<td class=\"text-nowrap\"><div style=\"text-align:center\"><button class=\"btn btn-info btn-sm\" onclick=\"mostrar_observaciones(event,"+arr_requerimientos[i].idrequerimiento+")\" type=\"button\"><span class=\"fa fa-search\"></span></button></div></td>";

			        if(arr_requerimientos[i].idestado_requerimiento == 3)
			        	data = data + boton_atender + boton_rechazar;
			        else
			        	data = data 
			           			+ "<td class=\"text-nowrap text-center\">-</td>"
			        			+ "<td class=\"text-nowrap text-center\">-</td></tr>";       
			                


            		$('#table_requerimientos').append(data);	            		                		
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

function mostrar_observaciones(e,id)
{
	e.preventDefault();
	$('#modal_requerimientos_mostrar').modal('hide');
	$.ajax({
		url: inside_url+'requerimientos/ver_observacion',
		type: 'POST',
		data: { 
			'idrequerimiento' : id,
		},
		beforeSend: function(){
			$(".loader_container").show();
		},
		complete: function(){
			//$(this).prop('disabled',false);
		},
		success: function(response){
			if(response["requerimiento"] != null)
			{
				dialog = BootstrapDialog.show({
			        title: 'Mensaje',
			        message: response["requerimiento"].observaciones,
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
				dialog = BootstrapDialog.show({
			        title: 'Mensaje',
			        message: 'Requerimiento no existe.',
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

function rechazar_requerimiento(e,idrequerimiento)
{
	e.preventDefault();
	$('#modal_requerimientos_mostrar').modal('hide');
	$.ajax({
		url: inside_url+'requerimientos/rechazar_requerimiento',
		type: 'POST',
		data: { 
			'idrequerimiento' : idrequerimiento,
		},
		beforeSend: function(){
			$(".loader_container").show();
		},
		complete: function(){
			//$(this).prop('disabled',false);
		},
		success: function(response){
			if(response["requerimiento"] != null)
			{	
				$('#requerimiento_id_rechazar').val(response["requerimiento"].idrequerimiento);
				$('#modal_requerimientos_rechazar').modal('show');
				$('#modal_header_requerimientos_rechazar').removeClass();
				$('#modal_header_requerimientos_rechazar').addClass("modal-header ");
				$('#modal_header_requerimientos_rechazar').addClass("bg-primary");
				$('#observacion_rechazo').val(response["requerimiento"].observaciones);
			}else
			{
				dialog = BootstrapDialog.show({
			        title: 'Mensaje',
			        message: 'Requerimiento no existe.',
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

function finalizar_requerimiento(e,idrequerimiento)
{
	$('#requerimiento_id_finalizar').val(idrequerimiento);
	BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
		        	document.getElementById("submit-finalizar").submit();
				}
			}
		});
}