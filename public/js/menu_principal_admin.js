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



	
});

