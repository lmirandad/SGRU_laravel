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


function eliminar_base(e,id)
{
	e.preventDefault();
	$('#carga_archivo_id_eliminar').val(id);
	BootstrapDialog.confirm({
		title: 'Mensaje de Confirmación',
		message: '¿Está seguro que desea realizar esta acción?', 
		type: BootstrapDialog.TYPE_INFO,
		btnCancelLabel: 'Cancelar', 
    	btnOKLabel: 'Aceptar', 
		callback: function(result){
	        if(result) {
				document.getElementById("submit-eliminar-base-carga").submit();
			}
		}
	});
}