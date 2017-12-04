$( document ).ready(function(){
	

	$('#btnLimpiar').click(function(){
		$('#search').val(null);
		$('#search_denominacion_herramienta').val(0);
	});

	$('#btnCrearSla').click(function(){
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

	$('#btnEditarSla').click(function(){
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

	$('#btnCrearSlaNuevo').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?\nAl crear un nuevo SLA, se asignará como el vigente.', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					var url = inside_url + "slas/crear_sla/"+$('#idherramientaxsector').val();
					window.location = url;
				}
			}
		});
	});

	
	$('#btnMostrarInformacion').click(function(){
		dialog = BootstrapDialog.show({
            title: 'Informativo',
            message: 'CONSIDERACIONES:\n1. Solo se puede actualizar el SLA vigente siempre y cuando no tenga solicitudes pendientes ni en proceso.\n2. Al crear un nuevo SLA, el SLA anterior dejará de ser el vigente y solo se aplicará para aquellas solicitudes que ingresen a partir de la nueva fecha de vigencia.',
            type : BootstrapDialog.TYPE_PRIMARY,
            buttons: [{
                label: 'Entendido',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
	});

});


function mostrar_datos(e,id){

	idsla = id;

	e.preventDefault();
   	$.ajax({
		url: inside_url+'slas/mostrar_datos',
		type: 'POST',
		data: { 
			'idsla' : idsla,
		},
		beforeSend: function(){
			//$(this).prop('disabled',true);
		},
		complete: function(){
			//$(this).prop('disabled',false);
		},
		success: function(response){
			if(response.success){
				datos_sla = response["sla"];
				size_datos = datos_sla.length;
				for(i=0;i<size_datos;i++){
					$('#valor_sla_pendiente'+i).val(datos_sla[i].sla_pendiente);
					$('#valor_sla_procesando'+i).val(datos_sla[i].sla_procesando);
				}
			}			
		},
		error: function(){
			alert('La petición no se pudo completar, inténtelo de nuevo.');
		}
	});
			
}

function editar_sla(e,id) {
	idsla = id;
	
	e.preventDefault();
   	$.ajax({
		url: inside_url+'slas/validar_slas',
		type: 'POST',
		data: { 
			'idsla' : idsla,
		},
		beforeSend: function(){
			//$(this).prop('disabled',true);
		},
		complete: function(){
			//$(this).prop('disabled',false);
		},
		success: function(response){
			if(response.success){
				cantidad_solicitudes = response["cantidad_solicitudes"];
				if(cantidad_solicitudes > 0)
				{
					dialog = BootstrapDialog.show({
			            title: 'Mensaje',
			            message: 'No es posible editar la información del SLA. Ya existen solicitudes que aplican bajo este SLA.',
			            type : BootstrapDialog.TYPE_DANGER,
			            buttons: [{
			                label: 'Entendido',
			                action: function(dialog) {
			                    dialog.close();
			                }
			            }]
			        });
					
				}else{
					var url = inside_url + "slas/editar_sla/"+idsla;
					window.location = url;
				}
			}			
		},
		error: function(){
			alert('La petición no se pudo completar, inténtelo de nuevo.');
		}
	});
}