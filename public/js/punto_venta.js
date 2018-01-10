$( document ).ready(function(){
	
	$('#btnLimpiarPuntoVenta').click(function(){
		$('#nombre_punto_venta').val(null);
		$('#codigo_punto_venta').val(null);
	});

	$('#btnCrearPuntoVenta').click(function(){
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

	$('#submit-habilitar-punto-venta').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("habilitar_punto_venta").submit();
				}
			}
		});
	});

	$('#submit-inhabilitar-punto-venta').click(function(){
		BootstrapDialog.confirm({
			title: 'Mensaje de Confirmación',
			message: '¿Está seguro que desea realizar esta acción?', 
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: 'Cancelar', 
	    	btnOKLabel: 'Aceptar', 
			callback: function(result){
		        if(result) {
					document.getElementById("inhabilitar_punto_venta").submit();
				}
			}
		});
	});

	

});


function editar_datos(e,codigo_punto_venta,nombre,idpunto_venta)
{
	e.preventDefault();

	$('#modal_editar_pto_venta').modal('show');

	$('#nombre_edicion_punto_venta').val(nombre);

	$('#codigo_edicion_punto_venta').val(codigo_punto_venta);
	
	$('#punto_venta_id').val(idpunto_venta);

}


