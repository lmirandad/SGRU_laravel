$( document ).ready(function(){
		
	if($('#btnLimpiarNombre').length){
		$("#btnLimpiarNombre").click(function (){
			$('#search_usuario').val(null);
			$('#search_fecha').val(null);
		});
	}


	$("#datetimepicker1").datetimepicker({
		ignoreReadonly:true,
		format:'MM-YYYY',
		locale:'es',
	});

	$("#datetimepicker_resumen").datetimepicker({
		ignoreReadonly:true,
		format:'MM-YYYY',
		locale:'es',
	});

	if($('#btnLimpiarFecha').length){
		$("#btnLimpiarFecha").click(function (){
			$('#search_fecha_resumen').val(null);
		});
	}


});

function mostrar_solicitudes(e,estado)
{
	e.preventDefault();
	for (i=1;i<7;i++)
	{
		$('#estado'+i).removeAttr('style');
		$('#estado'+i).css('display','none');

	}
	$('#estado'+estado).removeAttr("style");
}

