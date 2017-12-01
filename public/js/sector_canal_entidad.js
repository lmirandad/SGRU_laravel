$( document ).ready(function(){
	
	
	$('#btnLimpiarSector').click(function(){
		$('#sector_search').val(null);
	});

	$('#btnLimpiarCanal').click(function(){
		$('#canal_search').val(null);
		$('#canal_search_sector').val(null);
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
	                	size_canales = canales.length;
	                	$('#slcCanal')[0].options.add(new Option("Seleccione",""));
	                	for(i=0;i<size_canales;i++){
	                		$('#slcCanal')[0].options.add(new Option(canales[i].nombre,canales[i].idcanal));
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
	
});


