$( document ).ready(function(){
		
	

	$('#btnCrearEntidad').click(function(){
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

	$('#btnEditarEntidad').click(function(){
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

	$('#slcSector').on('change',function(){
		$('#slcCanal')[0].options.length = 0;
		idsector = $('#slcSector').val(); 
		if( idsector != ''){
			$.ajax({
	            url: inside_url+'entidades/mostrar_canales',
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
		}
		
	});

	$('#submit-habilitar-entidad').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("habilitar_entidad").submit();
				}
			}
		});
	});

	$('#submit-inhabilitar-entidad').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("inhabilitar_entidad").submit();
				}
			}
		});
	});

});