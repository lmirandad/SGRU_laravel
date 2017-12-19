$( document ).ready(function(){
		
	

	$('#btnLimpiarCodigo').click(function(){
		$('#search_codigo_solicitud').val(null);
	});

	
});

function mostrar_datos_req(e,id)
{
	e.preventDefault();	
	$('#modal_requerimientos_mostrar').modal('show');
	$('#modal_header_requerimientos_mostrar').removeClass();
	$('#modal_header_requerimientos_mostrar').addClass("modal-header ");
	$('#modal_header_requerimientos_mostrar').addClass("bg-primary");
}

function cargar_base(e,id)
{
	e.preventDefault();	
	$('#modal_requerimientos_carga').modal('show');
	$('#modal_header_requerimientos_carga').removeClass();
	$('#modal_header_requerimientos_carga').addClass("modal-header ");
	$('#modal_header_requerimientos_carga').addClass("bg-primary");
}
