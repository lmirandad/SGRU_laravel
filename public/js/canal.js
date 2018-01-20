$( document ).ready(function(){
	
	

	$('#btnCrearCanal').click(function(){
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

	$('#btnEditarCanal').click(function(){
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

	$('#submit-habilitar-canal').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("habilitar_canal").submit();
				}
			}
		});
	});

	$('#submit-inhabilitar-canal').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("inhabilitar_canal").submit();
				}
			}
		});
	});

	$('#slcSector').on('change',function(){
		$('#slcCanalAgrupado')[0].options.length = 0;
		idsector = $('#slcSector').val(); 
		if( idsector != ''){
			$.ajax({
	            url: inside_url+'sectores/mostrar_canales_agrupados',
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
	                	canales = response["canales_agrupados"];
	                	if (canales != null)
	                	{
		                	size_canales = canales.length;
		                	$('#slcCanalAgrupado')[0].options.add(new Option("Seleccione",""));
		                	for(i=0;i<size_canales;i++){
		                		$('#slcCanalAgrupado')[0].options.add(new Option(canales[i].nombre,canales[i].idcanal_agrupado));
		                	}
		                }else{
		                	$('#slcCanalAgrupado')[0].options.add(new Option("Seleccione",""));
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
			$('#slcCanalAgrupado')[0].options.add(new Option("Seleccione",""));
		}
		
	});
});


