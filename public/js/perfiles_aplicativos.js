$( document ).ready(function(){
	
	$('#btnLimpiarPerfil').click(function(){
		$('#nombre_perfil_aplicativo').val(null);
	});

	$('#btnCrearPerfil').click(function(){
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

	$('#submit-habilitar-perfil-aplicativo').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("habilitar_perfil_aplicativo").submit();
				}
			}
		});
	});

	$('#submit-inhabilitar-perfil-aplicativo').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("inhabilitar_perfil_aplicativo").submit();
				}
			}
		});
	});

	

});


function editar_datos(e,nombre,idperfil_aplicativo)
{
	e.preventDefault();

	$('#modal_editar_perfil').modal('show');

	$('#nombre_edicion_perfil_aplicativo').val(nombre);
	
	$('#perfil_aplicativo_id').val(idperfil_aplicativo);

}


