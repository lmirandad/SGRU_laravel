$( document ).ready(function(){
	
	if($('#search_datetimepicker1').length ){
	    $('#search_datetimepicker1').datetimepicker({
	 		ignoreReadonly: true,
	 		format:'YYYY',
	 		locale:'es',
	 	});
	 }

	 if($('#search_datetimepicker2').length ){
	    $('#search_datetimepicker2').datetimepicker({
	 		ignoreReadonly: true,
	 		format:'MM-YYYY',
	 		locale:'es',
	 	});
	 }

	 $('#btnLimpiar').click(function(){
	 	$('#anho').val(null);
	 	$('#usuario').val(null);
	 });

	 $('#submit-search-form-1').click(function(){
	 	anho = $('#anho').val();
	 	usuario = $('#usuario').val();

	 	if(anho.length == 0)
	 	{
	 		dialog = BootstrapDialog.show({
		            title: 'Mensaje',
		            message: 'Seleccionar el año',
		            type : BootstrapDialog.TYPE_DANGER,
		            buttons: [{
		                label: 'Entendido',
		                action: function(dialog) {
		                 	dialog.close();   
		                }
	            }]
	        });
	 	}else
	 	{
	 		if(usuario.length == 0)
	 		{
	 			$('#evolutivoPorEstado').remove();
	 			$('#divEvolutivoPorEstado').append('<canvas id="evolutivoPorEstado"></canvas>');
				$('#evolutivoPorCanal').remove();
				$('#divEvolutivoPorCanal').append('<canvas id="evolutivoPorCanal"></canvas>');
				$('#evolutivoAplicativo').remove();
				$('#divEvolutivoAplicativo').append('<canvas id="evolutivoAplicativo"></canvas>');
				$('#evolutivoGestionSeguridad').remove();
				$('#divEvolutivoGestionSeguridad').append('<canvas id="evolutivoGestionSeguridad"></canvas>');
				$('#evolutivoSolicitudesDia').remove();
				$('#divEvolutivoSolicitudesDia').append('<canvas id="evolutivoSolicitudesDia"></canvas>');
				poblar_charts_anual(anho);
	 		}else{
	 			$('#evolutivoPorEstado').remove();
				$('#divEvolutivoPorEstado').append('<canvas id="evolutivoPorEstado"></canvas>');
				$('#evolutivoPorCanal').remove();
				$('#divEvolutivoPorCanal').append('<canvas id="evolutivoPorCanal"></canvas>');
				$('#evolutivoAplicativo').remove();
				$('#divEvolutivoAplicativo').append('<canvas id="evolutivoAplicativo"></canvas>');
				$('#evolutivoGestionSeguridad').remove();
				$('#divEvolutivoGestionSeguridad').append('<canvas id="evolutivoGestionSeguridad"></canvas>');
				$('#evolutivoSolicitudesDia').remove();
				$('#divEvolutivoSolicitudesDia').append('<canvas id="evolutivoSolicitudesDia"></canvas>');
				poblar_charts_anual_usuario(anho,usuario);
	 		}
	 	}

	 });

	 $('#submit-search-form-2').click(function(){
	 	anho_mes = $('#mes').val();
	 	
	 	usuario = $('#usuario_2').val();

	 	if(anho_mes.length == 0)
	 	{
	 		dialog = BootstrapDialog.show({
		            title: 'Mensaje',
		            message: 'Seleccionar el mes',
		            type : BootstrapDialog.TYPE_DANGER,
		            buttons: [{
		                label: 'Entendido',
		                action: function(dialog) {
		                 	dialog.close();   
		                }
	            }]
	        });
	 	}else
	 	{
	 		partes = anho_mes.split('-');
		 	mes = partes[0];
		 	anho = partes[1];

	 		if(usuario.length == 0)
	 		{
	 			$('#mesEstado').remove();
	        	$('#divMesEstado').append('<canvas id="mesEstado"></canvas>');
	        	$('#evolutivoPorCanal').remove();
				$('#divEvolutivoPorCanal').append('<canvas id="evolutivoPorCanal"></canvas>');
				$('#evolutivoAplicativo').remove();
				$('#divEvolutivoAplicativo').append('<canvas id="evolutivoAplicativo"></canvas>');
				$('#evolutivoGestionSeguridad').remove();
				$('#divEvolutivoGestionSeguridad').append('<canvas id="evolutivoGestionSeguridad"></canvas>');
				$('#evolutivoSolicitudesDia').remove();
				$('#divEvolutivoSolicitudesDia').append('<canvas id="evolutivoSolicitudesDia"></canvas>');
	        	poblar_charts_mes(mes,anho);
	 		}else{
	 			$('#mesEstado').remove();
	        	$('#divMesEstado').append('<canvas id="mesEstado"></canvas>');
	        	$('#evolutivoPorCanal').remove();
				$('#divEvolutivoPorCanal').append('<canvas id="evolutivoPorCanal"></canvas>');
				$('#evolutivoAplicativo').remove();
				$('#divEvolutivoAplicativo').append('<canvas id="evolutivoAplicativo"></canvas>');
				$('#evolutivoSolicitudesDia').remove();
				$('#divEvolutivoGestionSeguridad').append('<canvas id="evolutivoSolicitudesDia"></canvas>');
				$('#evolutivoSolicitudesDia').remove();
				$('#divEvolutivoSolicitudesDia').append('<canvas id="evolutivoSolicitudesDia"></canvas>');
	        	poblar_charts_mes_usuario(mes,anho,usuario);
	 		}
	 	}

	 });
});

function poblar_charts_anual(anho)
{
	//GRAFICO 1: EVOLUTIVO POR ESTADOS
	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_estados',
        type: 'POST',
        data: {'anho':anho},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];

            	solicitudes_atendidos = response["atendidos"];
            	solicitudes_cerrados = response["cerrados"];
            	solicitudes_pendientes = response["pendientes"];
            	solicitudes_procesando = response["procesando"];
            	solicitudes_rechazados = response["rechazados"];
            	solicitudes_anulados = response["anulados"];
            	meses = response["meses"];

            	
            	cantidad_meses = meses.length;
            	
            	var cantidades_atendidos = new Array();
            	var cantidades_cerrados = new Array();
            	var cantidades_pendientes = new Array();
            	var cantidades_procesando = new Array();
            	var cantidades_rechazados = new Array();
            	var cantidades_anulados = new Array();
            	var nombre_meses = new Array();
            	

            	for(i=0; i<cantidad_meses; i++)
            	{            		
            		cantidades_atendidos.push(solicitudes_atendidos[i].cantidad);
            		cantidades_cerrados.push(solicitudes_cerrados[i].cantidad);
            		cantidades_pendientes.push(solicitudes_pendientes[i].cantidad);
            		cantidades_procesando.push(solicitudes_procesando[i].cantidad);
            		cantidades_rechazados.push(solicitudes_rechazados[i].cantidad);
            		cantidades_anulados.push(solicitudes_anulados[i].cantidad);
            		nombre_meses.push(meses[i].mes);
            	}

            	var ctx = document.getElementById("evolutivoPorEstado").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: nombre_meses,
				        datasets: [{
				        	label: 'atendidos',			            
				            data: cantidades_atendidos,
				            backgroundColor: colors[2],
				            borderWidth: 1
				        },
				        {
				        	label: 'cerrados con observaciones',			            
				            data: cantidades_cerrados,
				            backgroundColor:colors[1],
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: cantidades_pendientes,
				            backgroundColor:colors[4],
				            borderWidth: 1
				        },
				        {
				        	label: 'procesando',			            
				            data: cantidades_procesando,
				            backgroundColor:colors[5],
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: cantidades_rechazados,
				            backgroundColor:colors[0],
				            borderWidth: 1
				        },
				        {
				        	label: 'anulados',			            
				            data: cantidades_anulados,
				            backgroundColor:colors[3],
				            borderWidth: 1
				        }]
				    },
				    options: {
				        scales: {
				            yAxes: [{
				                ticks: {
				                    beginAtZero:true
				                }
				            }]
				        }
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

    //GRAFICO 2: EVOLUTIVO POR CANAL ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_canales',
        type: 'POST',
        data: {'anho':anho},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	resumen = response["resumen"];
            	canales = response["canales"];
            	meses = response["meses"];

            	cantidad_canales = canales.length;
            	cantidad_meses = meses.length;
            	array_datasets = new Array();
            	array_nombres = new Array();
            	nombre_meses = new Array();

            	contador_colores = 0;
            	cantidad_colores = colors.length;

            	for(i = 0;i<cantidad_canales;i++)
            	{
            		array_nombres.push(canales[i].nombre);
            		//creamos el dataset
            		array_cantidades = new Array();
            		for(j=0; j<cantidad_meses; j++)
            		{
            			//alert(resumen[1][j].cantidad);
            			array_cantidades.push(resumen[i][j].cantidad);
            			if(i==0)
            			{
            				nombre_meses.push(meses[j].mes);
            			}
            		}

            		if(contador_colores == cantidad_colores)
            			contador_colores = 0;

            		color = colors[contador_colores];

            		contador_colores++;

            		object_dataset = {
            			//agarro la data de cada mes
            			label: canales[i].nombre,
            			data: array_cantidades,
            			backgroundColor: color,
            			borderColor: color,
            			borderWidth: 3,
            			fill: false
            		};	

            		array_datasets.push(object_dataset);
            	}

            	
            	var ctx = document.getElementById("evolutivoPorCanal").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'line',
				    data: {
				        labels: nombre_meses,
				        datasets: array_datasets,
				    },
				    options: {
				        spanGaps: false,
						elements: {
							line: {
								tension: 0.000001
							}
						},
						plugins: {
							filler: {
								propagate: false
							}
						},
						scales: {
							xAxes: [{	
								ticks: {
									autoSkip: false,
									maxRotation: 0
								}
							}]
						}
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

    //GRAFICO 3: EVOLUTIVO POR APLICATIVO ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_aplicativos',
        type: 'POST',
        data: {'anho':anho
    			},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	resumen = response["resumen"];
            	array_nombres = new Array();
            	array_atendidos = new Array();
            	array_rechazados = new Array();
            	array_pendientes = new Array();
            	
            	if(response["tiene_herramientas"] == true)
            	{
            		cantidad_herramientas = resumen.length;
	            	
	            	for(i = 0;i<cantidad_herramientas;i++)
	            	{
	            		array_nombres.push(resumen[i].nombre_herramienta);
	            		//creamos el dataset
	            		array_atendidos.push(resumen[i].cantidad_atendidos);
	            		array_rechazados.push(resumen[i].cantidad_rechazados);
	            		array_pendientes.push(resumen[i].cantidad_pendientes);

	            	}	
            	}else{
            		array_nombres.push('SIN REQUERIMIENTOS REGISTRADOS');
            		array_atendidos.push(0);
            		array_rechazados.push(0);
            		array_pendientes.push(0);
            	}

            	

            	
            	var ctx = document.getElementById("evolutivoAplicativo").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: array_nombres,
				        datasets: [{
				        	label: 'atendidos',			            
				            data: array_atendidos,
				            backgroundColor:colors[2],
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: array_rechazados,
				            backgroundColor:colors[1],
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: array_pendientes,
				            backgroundColor:colors[0],
				            borderWidth: 1
				        }]
				    },
				    options: {
				        scales: {
				            yAxes: [{
				                ticks: {
				                    beginAtZero:true
				                }
				            }]
				        }
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

	//GRAFICO 4: EVOLUTIVO POR GESTION SEGURIDAD ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_gestion_seguridad',
        type: 'POST',
        data: {'anho':anho},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	
            	cantidad_si = response["flag_si"];
            	cantidad_no = response["flag_no"];
            	cantidad_validar = response["flag_validar"];
            	meses = response["meses"];

            	array_si = new Array();
            	array_no = new Array();
            	array_validar = new Array();
            	array_nombres = new Array();
            	
            	cantidad_meses = meses.length;

            	for(i = 0;i<cantidad_meses;i++)
            	{
            		array_nombres.push(meses[i].mes);
            		//llenamos los datasets
            		array_si.push(cantidad_si[i].cantidad*100);
            		array_no.push(cantidad_no[i].cantidad*100);
            		array_validar.push(cantidad_validar[i].cantidad*100);
            	}

            	
            	var ctx = document.getElementById("evolutivoGestionSeguridad").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: array_nombres,
				        datasets: [{
				        	label: 'con gestion de seguridad',			            
				            data: array_si,
				            backgroundColor:colors[0],
				            borderWidth: 1
				        },
				        {
				        	label: 'sin gestion de seguridad',			            
				            data: array_no,
				            backgroundColor:colors[2],
				            borderWidth: 1
				        },
				        {
				        	label: 'gestion de seguridad por validar',			            
				            data: array_validar,
				            backgroundColor:colors[1],
				            borderWidth: 1
				        }]
				    },
				    options: {
	                    tooltips: {
	                        mode: 'index',
	                        intersect: false
	                    },
	                    responsive: true,
	                    scales: {
	                        xAxes: [{
	                            stacked: true,
	                        }],
	                        yAxes: [{
	                            stacked: true
	                        }]
	                    }
	                }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

    //GRAFICO 5: EVOLUTIVO POR DIAS
	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_dias',
        type: 'POST',
        data: {'anho':anho},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	resumen = response["resumen"];
            	dias = response["dias"];

            	cantidad_dias = dias.length;
            	nombre_dias = new Array();

            	contador_colores = 0;
            	cantidad_colores = colors.length;

            	array_cantidades = new Array();
        		for(i=0; i<cantidad_dias; i++)
        		{
        			//alert(resumen[1][j].cantidad);
        			array_cantidades.push(resumen[i].cantidad);
        			nombre_dias.push(dias[i].dia);
        		}

            	var ctx = document.getElementById("evolutivoSolicitudesDia").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'line',
				    data: {
				        labels: nombre_dias,
				        datasets: [{
				        	label: 'Solicitudes',			            
				            data: array_cantidades,
				            backgroundColor: colors[2],
				            borderColor: colors[2],
				            borderWidth: 3,
				            fill: false,
				        }]
				    },
				    options: {
				        spanGaps: false,
						elements: {
							line: {
								tension: 0.000001
							}
						},
						plugins: {
							filler: {
								propagate: false
							}
						},
						scales: {
							xAxes: [{	
								ticks: {
									autoSkip: false,
									maxRotation: 0
								}
							}]
						}
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

}

function poblar_charts_anual_usuario(anho,usuario)
{
	
	//GRAFICO 1: EVOLUTIVO POR ESTADOS

	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_usuario_estados',
        type: 'POST',
        data: {'anho':anho,
    		'usuario':usuario},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	solicitudes_atendidos = response["atendidos"];
            	solicitudes_cerrados = response["cerrados"];
            	solicitudes_pendientes = response["pendientes"];
            	solicitudes_procesando = response["procesando"];
            	solicitudes_rechazados = response["rechazados"];
            	solicitudes_anulados = response["anulados"];
            	meses = response["meses"];

            	
            	cantidad_meses = meses.length;
            	
            	var cantidades_atendidos = new Array();
            	var cantidades_cerrados = new Array();
            	var cantidades_pendientes = new Array();
            	var cantidades_procesando = new Array();
            	var cantidades_rechazados = new Array();
            	var cantidades_anulados = new Array();
            	var nombre_meses = new Array();
            	

            	for(i=0; i<cantidad_meses; i++)
            	{            		
            		cantidades_atendidos.push(solicitudes_atendidos[i].cantidad);
            		cantidades_cerrados.push(solicitudes_cerrados[i].cantidad);
            		cantidades_pendientes.push(solicitudes_pendientes[i].cantidad);
            		cantidades_procesando.push(solicitudes_procesando[i].cantidad);
            		cantidades_rechazados.push(solicitudes_rechazados[i].cantidad);
            		cantidades_anulados.push(solicitudes_anulados[i].cantidad);
            		nombre_meses.push(meses[i].mes);
            	}

            	

            	var ctx = document.getElementById("evolutivoPorEstado").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: nombre_meses,
				        datasets: [{
				        	label: 'atendidos',			            
				            data: cantidades_atendidos,
				            backgroundColor: colors[2],
				            borderWidth: 1
				        },
				        {
				        	label: 'cerrados con observaciones',			            
				            data: cantidades_cerrados,
				            backgroundColor:colors[1],
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: cantidades_pendientes,
				            backgroundColor:colors[4],
				            borderWidth: 1
				        },
				        {
				        	label: 'procesando',			            
				            data: cantidades_procesando,
				            backgroundColor:colors[5],
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: cantidades_rechazados,
				            backgroundColor:colors[0],
				            borderWidth: 1
				        },
				        {
				        	label: 'anulados',			            
				            data: cantidades_anulados,
				            backgroundColor:colors[3],
				            borderWidth: 1
				        }]
				    },
				    options: {
				        scales: {
				            yAxes: [{
				                ticks: {
				                    beginAtZero:true
				                }
				            }]
				        }
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

	//GRAFICO 2: EVOLUTIVO POR CANAL ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_usuarios_canales',
        type: 'POST',
        data: {'anho':anho, 'usuario':usuario},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	resumen = response["resumen"];
            	canales = response["canales"];
            	meses = response["meses"];

            	cantidad_canales = canales.length;
            	cantidad_meses = meses.length;
            	array_datasets = new Array();
            	array_nombres = new Array();
            	nombre_meses = new Array();

            	contador_colores = 0;
            	cantidad_colores = colors.length;
            	

            	for(i = 0;i<cantidad_canales;i++)
            	{
            		array_nombres.push(canales[i].nombre);
            		//creamos el dataset
            		array_cantidades = new Array();
            		for(j=0; j<cantidad_meses; j++)
            		{
            			//alert(resumen[1][j].cantidad);
            			array_cantidades.push(resumen[i][j].cantidad);
            			if(i==0)
            			{
            				nombre_meses.push(meses[j].mes);
            			}
            		}

            		if(contador_colores == cantidad_colores)
            			contador_colores = 0;

            		color = colors[contador_colores];

            		contador_colores++;

            		object_dataset = {
            			//agarro la data de cada mes
            			label: canales[i].nombre,
            			data: array_cantidades,
            			backgroundColor: color,
            			borderColor: color,
            			borderWidth: 3,
            			fill : false
            		};	

            		array_datasets.push(object_dataset);
            	}

            	
            	var ctx = document.getElementById("evolutivoPorCanal").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'line',
				    data: {
				        labels: nombre_meses,
				        datasets: array_datasets,
				    },
				    options: {
				        spanGaps: false,
						elements: {
							line: {
								tension: 0.000001
							}
						},
						plugins: {
							filler: {
								propagate: false
							}
						},
						scales: {
							xAxes: [{	
								ticks: {
									autoSkip: false,
									maxRotation: 0
								}
							}]
						}
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

    
    //GRAFICO 3: EVOLUTIVO POR APLICATIVO ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_usuarios_aplicativos',
        type: 'POST',
        data: {'anho':anho, 'usuario':usuario
    			},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	resumen = response["resumen"];

            	array_nombres = new Array();
            	array_atendidos = new Array();
            	array_rechazados = new Array();
            	array_pendientes = new Array();
            	
            	if(response["tiene_herramientas"] == true)
            	{
            		cantidad_herramientas = resumen.length;
	            	
	            	for(i = 0;i<cantidad_herramientas;i++)
	            	{
	            		array_nombres.push(resumen[i].nombre_herramienta);
	            		//creamos el dataset
	            		array_atendidos.push(resumen[i].cantidad_atendidos);
	            		array_rechazados.push(resumen[i].cantidad_rechazados);
	            		array_pendientes.push(resumen[i].cantidad_pendientes);

	            	}	
            	}else{
            		array_nombres.push('SIN REQUERIMIENTOS REGISTRADOS');
            		array_atendidos.push(0);
            		array_rechazados.push(0);
            		array_pendientes.push(0);
            	}

            	

            	
            	var ctx = document.getElementById("evolutivoAplicativo").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: array_nombres,
				        datasets: [{
				        	label: 'atendidos',			            
				            data: array_atendidos,
				            backgroundColor:colors[2],
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: array_rechazados,
				            backgroundColor:colors[1],
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: array_pendientes,
				            backgroundColor:colors[0],
				            borderWidth: 1
				        }]
				    },
				    options: {
				        scales: {
				            yAxes: [{
				                ticks: {
				                    beginAtZero:true
				                }
				            }]
				        }
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

	//GRAFICO 4: EVOLUTIVO POR GESTION SEGURIDAD ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_usuarios_gestion_seguridad',
        type: 'POST',
        data: {'anho':anho,'usuario':usuario},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	
            	cantidad_si = response["flag_si"];
            	cantidad_no = response["flag_no"];
            	cantidad_validar = response["flag_validar"];
            	meses = response["meses"];

            	array_si = new Array();
            	array_no = new Array();
            	array_validar = new Array();
            	array_nombres = new Array();
            	
            	cantidad_meses = meses.length;

            	for(i = 0;i<cantidad_meses;i++)
            	{
            		array_nombres.push(meses[i].mes);
            		//llenamos los datasets
            		array_si.push(cantidad_si[i].cantidad*100);
            		array_no.push(cantidad_no[i].cantidad*100);
            		array_validar.push(cantidad_validar[i].cantidad*100);
            	}

            	
            	var ctx = document.getElementById("evolutivoGestionSeguridad").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: array_nombres,
				        datasets: [{
				        	label: 'con gestion de seguridad',			            
				            data: array_si,
				            backgroundColor:colors[0],
				            borderWidth: 1
				        },
				        {
				        	label: 'sin gestion de seguridad',			            
				            data: array_no,
				            backgroundColor:colors[2],
				            borderWidth: 1
				        },
				        {
				        	label: 'gestion de seguridad por validar',			            
				            data: array_validar,
				            backgroundColor:colors[1],
				            borderWidth: 1
				        }]
				    },
				    options: {
	                    tooltips: {
	                        mode: 'index',
	                        intersect: false
	                    },
	                    responsive: true,
	                    scales: {
	                        xAxes: [{
	                            stacked: true,
	                        }],
	                        yAxes: [{
	                            stacked: true
	                        }]
	                    }
	                }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

	//GRAFICO 5: EVOLUTIVO POR DIAS
	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_dias_usuarios',
        type: 'POST',
        data: {'anho':anho,'usuario':usuario},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	resumen = response["resumen"];
            	dias = response["dias"];

            	cantidad_dias = dias.length;
            	nombre_dias = new Array();

            	contador_colores = 0;
            	cantidad_colores = colors.length;

            	array_cantidades = new Array();
        		for(i=0; i<cantidad_dias; i++)
        		{
        			//alert(resumen[1][j].cantidad);
        			array_cantidades.push(resumen[i].cantidad);
        			nombre_dias.push(dias[i].dia);
        		}

            	var ctx = document.getElementById("evolutivoSolicitudesDia").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'line',
				    data: {
				        labels: nombre_dias,
				        datasets: [{
				        	label: 'Solicitudes',			            
				            data: array_cantidades,
				            backgroundColor: colors[2],
				            borderColor: colors[2],
				            borderWidth: 3,
				            fill: false,
				        }]
				    },
				    options: {
				        spanGaps: false,
						elements: {
							line: {
								tension: 0.000001
							}
						},
						plugins: {
							filler: {
								propagate: false
							}
						},
						scales: {
							xAxes: [{	
								ticks: {
									autoSkip: false,
									maxRotation: 0
								}
							}]
						}
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

}

function poblar_charts_mes(mes,anho)
{
	//GRAFICO 1: EVOLUTIVO POR ESTADOS
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_estados',
        type: 'POST',
        data: {'mes':mes,'anho':anho},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){
            	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	solicitudes_atendidos = response["atendidos"];
            	solicitudes_cerrados = response["cerrados"];
            	solicitudes_pendientes = response["pendientes"];
            	solicitudes_procesando = response["procesando"];
            	solicitudes_rechazados = response["rechazados"];
            	solicitudes_anulados = response["anulados"];

            	

            	var ctx = document.getElementById("mesEstado").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'pie',
				    data: {
				        labels: ['atendidos','cerrados con observaciones','pendientes','procesando','rechazados','anulados'],
				        datasets: [{
				        	label: 'solicitudes',			            
				            data: [solicitudes_atendidos[0].cantidad,
						            solicitudes_cerrados[0].cantidad,
						            solicitudes_pendientes[0].cantidad,
						            solicitudes_procesando[0].cantidad,
						            solicitudes_rechazados[0].cantidad,
						            solicitudes_anulados[0].cantidad
				            ],				            
				            backgroundColor:[
				            		colors[2],colors[1],colors[3],colors[4],colors[0],colors[5]

				            ],
				            borderColor: [
				                colors[2],colors[1],colors[3],colors[4],colors[0],colors[5]
				            ],
				            borderWidth: 1
				        }]
				    },
				    options: {
				        responsive: true
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

	//GRAFICO 2: EVOLUTIVO POR CANAL ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_canales',
        type: 'POST',
        data: {'anho':anho,'mes':mes},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20','#428bca','#2962ff'];
            	
            	resumen = response["resumen"];
            	canales = response["canales"];
            	
            	cantidad_canales_original = canales.length;
            	cantidad_canales = cantidad_canales_original;
            	array_cantidades = new Array();
            	array_nombres = new Array();
            	array_colores = new Array();



            	if(cantidad_canales > 10)
            		cantidad_canales = 10;

            	for(i=0;i<cantidad_canales;i++)
            	{
            		//por cada canal leer sus datos
            		array_cantidades.push(resumen[i].cantidad);
            		array_nombres.push(canales[i].nombre);
            		array_colores.push(colors[i]);
            		
            	}

            	if(cantidad_canales_original > 10 )
            	{
            		array_nombres.push("OTROS");
            		cantidad_otros = 0;
            		for(i=10;i<cantidad_canales_original;i++)
            		{
            			cantidad_otros += parseInt(resumen[i].cantidad);
            		}
            		array_colores.push(colors[10]);

            		array_cantidades.push(cantidad_otros);
            	}

            	
            	var ctx = document.getElementById("mesCanal").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'pie',
				    data: {
				        labels: array_nombres,
				        datasets: [{
				        	label: 'solicitudes',			            
				            data: array_cantidades,				            
				            backgroundColor:array_colores,
				            borderColor: array_colores,
				            borderWidth: 1
				        }]
				    },
				    options: {
				        responsive: true
				    }
				});
            	
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

    //GRAFICO 3: EVOLUTIVO POR APLICATIVO ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_aplicativos',
        type: 'POST',
        data: {'anho':anho, 'mes':mes
    			},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	resumen = response["resumen"];

            	array_nombres = new Array();
            	array_atendidos = new Array();
            	array_rechazados = new Array();
            	array_pendientes = new Array();
            	
            	if(response["tiene_herramientas"] == true)
            	{
            		cantidad_herramientas = resumen.length;
	            	
	            	for(i = 0;i<cantidad_herramientas;i++)
	            	{
	            		array_nombres.push(resumen[i].nombre_herramienta);
	            		//creamos el dataset
	            		array_atendidos.push(resumen[i].cantidad_atendidos);
	            		array_rechazados.push(resumen[i].cantidad_rechazados);
	            		array_pendientes.push(resumen[i].cantidad_pendientes);

	            	}	
            	}else{
            		array_nombres.push('SIN REQUERIMIENTOS REGISTRADOS');
            		array_atendidos.push(0);
            		array_rechazados.push(0);
            		array_pendientes.push(0);
            	}

            	
            	var ctx = document.getElementById("evolutivoAplicativo").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: array_nombres,
				        datasets: [{
				        	label: 'atendidos',			            
				            data: array_atendidos,
				            backgroundColor:colors[2],
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: array_rechazados,
				            backgroundColor:colors[1],
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: array_pendientes,
				            backgroundColor:colors[0],
				            borderWidth: 1
				        }]
				    },
				    options: {
				        scales: {
				            yAxes: [{
				                ticks: {
				                    beginAtZero:true
				                }
				            }]
				        }
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

	//GRAFICO 4: EVOLUTIVO POR GESTION SEGURIDAD ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_gestion_seguridad',
        type: 'POST',
        data: {'anho':anho,'mes':mes},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	
            	cantidad_si = response["flag_si"];
            	cantidad_no = response["flag_no"];
            	cantidad_validar = response["flag_validar"];
            	
            	
            	
            	var ctx = document.getElementById("evolutivoGestionSeguridad").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'pie',
				    data: {
				        labels: ['con gestion de seguridad','sin gestion de seguridad','gestion de seguridad por validar'],
				        datasets: [{
				        	label: 'Transacciones',			            
				            data: [cantidad_si[0].cantidad*100,cantidad_no[0].cantidad*100,cantidad_validar[0].cantidad*100],
				            backgroundColor:[colors[0],colors[2],colors[1]],
				            borderWidth: 1
				        }]
				    },
				    options: {
				        responsive: true
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

    //GRAFICO 5: EVOLUTIVO POR DIAS
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_dias',
        type: 'POST',
        data: {'anho':anho,'mes':mes},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	resumen = response["resumen"];
            	dias = response["dias"];

            	cantidad_dias = dias.length;
            	nombre_dias = new Array();

            	contador_colores = 0;
            	cantidad_colores = colors.length;

            	array_cantidades = new Array();
        		for(i=0; i<cantidad_dias; i++)
        		{
        			//alert(resumen[1][j].cantidad);
        			array_cantidades.push(resumen[i].cantidad);
        			nombre_dias.push(dias[i].dia);
        		}

        		array_cantidades = [10,12,5,15,20,0,0];

            	var ctx = document.getElementById("evolutivoSolicitudesDia").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'line',
				    data: {
				        labels: nombre_dias,
				        datasets: [{
				        	label: 'Solicitudes',			            
				            data: array_cantidades,
				            backgroundColor: colors[2],
				            borderColor: colors[2],
				            borderWidth: 3,
				            fill: false,
				        }]
				    },
				    options: {
				        spanGaps: false,
						elements: {
							line: {
								tension: 0.000001
							}
						},
						plugins: {
							filler: {
								propagate: false
							}
						},
						scales: {
							xAxes: [{	
								ticks: {
									autoSkip: false,
									maxRotation: 0
								}
							}]
						}
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

}


function poblar_charts_mes_usuario(mes,anho,usuario)
{
	//GRAFICO 1: EVOLUTIVO POR ESTADOS
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_estados_usuario',
        type: 'POST',
        data: {'mes':mes,'anho':anho,'usuario':usuario},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){
            	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	solicitudes_atendidos = response["atendidos"];
            	solicitudes_cerrados = response["cerrados"];
            	solicitudes_pendientes = response["pendientes"];
            	solicitudes_procesando = response["procesando"];
            	solicitudes_rechazados = response["rechazados"];
            	solicitudes_anulados = response["anulados"];

            	

            	var ctx = document.getElementById("mesEstado").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'pie',
				    data: {
				        labels: ['atendidos','cerrados con observaciones','pendientes','procesando','rechazados','anulados'],
				        datasets: [{
				        	label: 'solicitudes',			            
				            data: [solicitudes_atendidos[0].cantidad,
						            solicitudes_cerrados[0].cantidad,
						            solicitudes_pendientes[0].cantidad,
						            solicitudes_procesando[0].cantidad,
						            solicitudes_rechazados[0].cantidad,
						            solicitudes_anulados[0].cantidad
				            ],				            
				            backgroundColor:[
				            		colors[2],colors[1],colors[3],colors[4],colors[0],colors[5]
				            ],
				            borderColor: [
				                colors[2],colors[1],colors[3],colors[4],colors[0],colors[5]
				            ],
				            borderWidth: 1
				        }]
				    },
				    options: {
				        responsive: true
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

	//GRAFICO 2: EVOLUTIVO POR CANAL ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_usuarios_canales',
        type: 'POST',
        data: {'anho':anho,'mes':mes,'usuario':usuario},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20','#428bca','#2962ff'];
            	
            	resumen = response["resumen"];
            	canales = response["canales"];
            	
            	cantidad_canales_original = canales.length;
            	cantidad_canales = cantidad_canales_original;
            	array_cantidades = new Array();
            	array_nombres = new Array();
            	array_colores = new Array();



            	if(cantidad_canales > 10)
            		cantidad_canales = 10;

            	for(i=0;i<cantidad_canales;i++)
            	{
            		//por cada canal leer sus datos
            		array_cantidades.push(resumen[i].cantidad);
            		array_nombres.push(canales[i].nombre);
            		array_colores.push(colors[i]);
            		
            	}

            	if(cantidad_canales_original > 10 )
            	{
            		array_nombres.push("OTROS");
            		cantidad_otros = 0;
            		for(i=10;i<cantidad_canales_original;i++)
            		{
            			cantidad_otros += parseInt(resumen[i].cantidad);
            		}
            		array_colores.push(colors[10]);

            		array_cantidades.push(cantidad_otros);
            	}

            	
            	var ctx = document.getElementById("mesCanal").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'pie',
				    data: {
				        labels: array_nombres,
				        datasets: [{
				        	label: 'solicitudes',			            
				            data: array_cantidades,				            
				            backgroundColor:array_colores,
				            borderColor: array_colores,
				            borderWidth: 1
				        }]
				    },
				    options: {
				        responsive: true
				    }
				});
            	
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

	 //GRAFICO 3: EVOLUTIVO POR APLICATIVO ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_usuarios_aplicativos',
        type: 'POST',
        data: {'anho':anho, 'mes':mes ,'usuario':usuario
    			},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	resumen = response["resumen"];

            	array_nombres = new Array();
            	array_atendidos = new Array();
            	array_rechazados = new Array();
            	array_pendientes = new Array();
            	
            	if(response["tiene_herramientas"] == true)
            	{
            		cantidad_herramientas = resumen.length;
	            	
	            	for(i = 0;i<cantidad_herramientas;i++)
	            	{
	            		array_nombres.push(resumen[i].nombre_herramienta);
	            		//creamos el dataset
	            		array_atendidos.push(resumen[i].cantidad_atendidos);
	            		array_rechazados.push(resumen[i].cantidad_rechazados);
	            		array_pendientes.push(resumen[i].cantidad_pendientes);

	            	}	
            	}else{
            		array_nombres.push('SIN REQUERIMIENTOS REGISTRADOS POR EL GESTOR');
            		array_atendidos.push(0);
            		array_rechazados.push(0);
            		array_pendientes.push(0);
            	}

            	
            	var ctx = document.getElementById("evolutivoAplicativo").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: array_nombres,
				        datasets: [{
				        	label: 'atendidos',			            
				            data: array_atendidos,
				            backgroundColor:colors[2],
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: array_rechazados,
				            backgroundColor:colors[1],
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: array_pendientes,
				            backgroundColor:colors[0],
				            borderWidth: 1
				        }]
				    },
				    options: {
				        scales: {
				            yAxes: [{
				                ticks: {
				                    beginAtZero:true
				                }
				            }]
				        }
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

	//GRAFICO 4: EVOLUTIVO POR GESTION SEGURIDAD ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_usuarios_gestion_seguridad',
        type: 'POST',
        data: {'anho':anho,'mes':mes,'usuario':usuario},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	
            	cantidad_si = response["flag_si"];
            	cantidad_no = response["flag_no"];
            	cantidad_validar = response["flag_validar"];

            	var ctx = document.getElementById("evolutivoGestionSeguridad").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'pie',
				    data: {
				        labels: ['con gestion de seguridad','sin gestion de seguridad','gestion de seguridad por validar'],
				        datasets: [{
				        	label: 'Transacciones',			            
				            data: [cantidad_si[0].cantidad*100,cantidad_no[0].cantidad*100,cantidad_validar[0].cantidad*100],
				            backgroundColor:[colors[0],colors[2],colors[1]],
				            borderWidth: 1
				        }]
				    },
				    options: {
				        responsive: true
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

    //GRAFICO 5: EVOLUTIVO POR DIAS
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_dias_usuarios',
        type: 'POST',
        data: {'anho':anho,'mes':mes,'usuario':usuario},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#ff4444', '#ffbb33', '#00C851', '#33b5e5', '#2BBBAD', '#aa66cc', '#81d4fa', '#eeff41', '#1b5e20'];
            	resumen = response["resumen"];
            	dias = response["dias"];

            	cantidad_dias = dias.length;
            	nombre_dias = new Array();

            	contador_colores = 0;
            	cantidad_colores = colors.length;

            	array_cantidades = new Array();
        		for(i=0; i<cantidad_dias; i++)
        		{
        			//alert(resumen[1][j].cantidad);
        			array_cantidades.push(resumen[i].cantidad);
        			nombre_dias.push(dias[i].dia);
        		}

            	var ctx = document.getElementById("evolutivoSolicitudesDia").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'line',
				    data: {
				        labels: nombre_dias,
				        datasets: [{
				        	label: 'Solicitudes',			            
				            data: array_cantidades,
				            backgroundColor: colors[2],
				            borderColor: colors[2],
				            borderWidth: 3,
				            fill: false,
				        }]
				    },
				    options: {
				        spanGaps: false,
						elements: {
							line: {
								tension: 0.000001
							}
						},
						plugins: {
							filler: {
								propagate: false
							}
						},
						scales: {
							xAxes: [{	
								ticks: {
									autoSkip: false,
									maxRotation: 0
								}
							}]
						}
				    }
				});
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });
    
}	