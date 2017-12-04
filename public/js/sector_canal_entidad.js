$( document ).ready(function(){
	
	flag_seleccion = $('#flag_seleccion').val();
	if(flag_seleccion == 3)
	{
		//para entidades se realiza nuevamente el ajax
		mostrar_canales_ajax();
	}

	$('#btnLimpiarSector').click(function(){
		$('#sector_search').val(null);
	});

	$('#btnLimpiarCanal').click(function(){
		$('#canal_search').val(null);
		$('#canal_search_sector').val(null);
	});

	$('#btnLimpiarEntidad').click(function(){
		$('#entidad_search').val(null);
		$('#slcSector').val(null);
		$('#slcCanal').val(null);
	});

	$('#slcSector').on('change',function(){
		mostrar_canales_ajax();
	});
	
});


function mostrar_canales_ajax()
{
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
		
}