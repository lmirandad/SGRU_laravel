$( document ).ready(function(){
		
	if($('#btnLimpiarCriterios').length){
		$("#btnLimpiarCriterios").click(function (){
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


