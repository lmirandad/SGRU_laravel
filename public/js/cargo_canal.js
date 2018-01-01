$( document ).ready(function(){
	
	$('#btnLimpiarCargoCanal').click(function(){
		$('#nombre_cargo_canal').val(null);
	});

	$('#btnCrearCargoCanal').click(function(){
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

	$('#submit-habilitar-cargo-canal').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("habilitar_cargo_canal").submit();
				}
			}
		});
	});

	$('#submit-inhabilitar-cargo-canal').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("inhabilitar_cargo_canal").submit();
				}
			}
		});
	});

	

});


function editar_datos(e,nombre,idcargo_canal)
{
	e.preventDefault();

	$('#modal_editar_cargo_canal').modal('show');

	$('#nombre_edicion_cargo_canal').val(nombre);
	
	$('#cargo_canal_id').val(idcargo_canal);

}


