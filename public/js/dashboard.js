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
				$('#evolutivoPorSector').remove();
				$('#divEvolutivoPorSector').append('<canvas id="evolutivoPorSector"></canvas>');
				$('#evolutivoPorAplicativo').remove();
				$('#divEvolutivoPorAplicativo').append('<canvas id="evolutivoPorAplicativo" height="400"></canvas>');
				$('#evolutivoPorEstadoRequerimiento').remove();
				$('#divEvolutivoPorEstadoRequerimiento').append('<canvas id="evolutivoPorEstadoRequerimiento"></canvas>');
	 			poblar_charts_anual(anho);
	 		}else{
	 			$('#evolutivoPorEstado').remove();
				$('#divEvolutivoPorEstado').append('<canvas id="evolutivoPorEstado"></canvas>');
				$('#evolutivoPorSector').remove();
				$('#divEvolutivoPorSector').append('<canvas id="evolutivoPorSector"></canvas>');
				$('#evolutivoPorAplicativo').remove();
				$('#divEvolutivoPorAplicativo').append('<canvas id="evolutivoPorAplicativo" height="400"></canvas>');
				$('#evolutivoPorEstadoRequerimiento').remove();
				$('#divEvolutivoPorEstadoRequerimiento').append('<canvas id="evolutivoPorEstadoRequerimiento"></canvas>');
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
	        	$('#mesAplicativo').remove();
	        	$('#divMesAplicativo').append('<canvas id="mesAplicativo"></canvas>');
	        	$('#mesSector').remove();
	        	$('#divMesSector').append('<canvas id="mesSector"></canvas>');
	        	$('#mesEstadoRequerimiento').remove();
	        	$('#divMesEstadoRequerimiento').append('<canvas id="mesEstadoRequerimiento"></canvas>');
	 			poblar_charts_mes(mes,anho);
	 		}else{
	 			$('#mesEstado').remove();
	        	$('#divMesEstado').append('<canvas id="mesEstado"></canvas>');
	        	$('#mesAplicativo').remove();
	        	$('#divMesAplicativo').append('<canvas id="mesAplicativo"></canvas>');
	        	$('#mesSector').remove();
	        	$('#divMesSector').append('<canvas id="mesSector"></canvas>');
	        	$('#mesEstadoRequerimiento').remove();
	        	$('#divMesEstadoRequerimiento').append('<canvas id="mesEstadoRequerimiento"></canvas>');
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
				            backgroundColor:'rgba(255, 99, 132, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'cerrados con observaciones',			            
				            data: cantidades_cerrados,
				            backgroundColor:'rgba(54, 162, 235, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: cantidades_pendientes,
				            backgroundColor:'rgba(255, 206, 86, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'procesando',			            
				            data: cantidades_procesando,
				            backgroundColor:'rgba(75, 192, 192, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: cantidades_rechazados,
				            backgroundColor:'rgba(153, 102, 255, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'anulados',			            
				            data: cantidades_anulados,
				            backgroundColor:'rgba(255, 159, 64, 0.2)',
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

	//GRAFICO 2: EVOLUTIVO POR SECTOR ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_sectores',
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
            	var colors = ['#D9E3F0', '#F47373', '#697689', '#37D67A', '#2CCCE4', '#555555', '#dce775', '#ff8a65', '#ba68c8'];
            	resumen = response["resumen"];
            	sectores = response["sectores"];
            	meses = response["meses"];

            	cantidad_sectores = sectores.length;
            	cantidad_meses = meses.length;
            	array_datasets = new Array();
            	array_nombres = new Array();
            	nombre_meses = new Array();

            	

            	for(i = 0;i<cantidad_sectores;i++)
            	{
            		array_nombres.push(sectores[i].nombre);
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

            		object_dataset = {
            			//agarro la data de cada mes
            			label: sectores[i].nombre,
            			data: array_cantidades,
            			backgroundColor: colors[Math.floor(Math.random() * colors.length)],
            			borderWidth: 1
            		};	

            		array_datasets.push(object_dataset);
            	}

            	
            	var ctx = document.getElementById("evolutivoPorSector").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: nombre_meses,
				        datasets: array_datasets,
				    },
				    options: {
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

    //GRAFICO 3: EVOLUTIVO POR APLICATIVO
    $.ajax({
        url: inside_url+'mostrar_dashboard_anual_aplicativos',
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
            	var colors = ['#D9E3F0', '#F47373', '#697689', '#37D67A', '#2CCCE4', '#555555', '#dce775', '#ff8a65', '#ba68c8'];
            	resumen = response["resumen"];
            	aplicativos = response["herramientas"];
            	meses = response["meses"];

            	cantidad_resumen = resumen.length;
            	cantidad_aplicativos = aplicativos.length;
            	cantidad_meses = meses.length;
            	array_datasets = new Array();
            	array_nombres = new Array();
            	nombre_meses = new Array();

            	

            	for(i = 0;i<cantidad_aplicativos;i++)
            	{
            		array_nombres.push(aplicativos[i].nombre_herramienta);
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

            		color =  colors[Math.floor(Math.random() * colors.length)];

            		object_dataset = {
            			//agarro la data de cada mes
            			label: aplicativos[i].nombre_herramienta,
            			data: array_cantidades,
            			borderColor: color,
            			backgroundColor: color,
            			borderWidth: 1,
            			fill: -1
            		};	

            		array_datasets.push(object_dataset);
            	}

            	//PARA LOS NO DETECTADOS
            	array_nombres.push('NO DETECTADO');
            	array_cantidades = new Array();
        		for(j=0; j<cantidad_meses; j++)
        		{
        			//alert(resumen[1][j].cantidad);
        			array_cantidades.push(resumen[cantidad_resumen-1][j].cantidad);        			
        		}

        		color =  colors[Math.floor(Math.random() * colors.length)];

        		object_dataset = {
        			//agarro la data de cada mes
        			label: 'NO DETECTADO',
        			data: array_cantidades,
        			borderColor: color,
        			backgroundColor: color,
        			borderWidth: 1,
        			fill: -1
        		};	

        		array_datasets.push(object_dataset);
            	
            	var ctx = document.getElementById("evolutivoPorAplicativo").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'line',
				    data: {
				        labels: nombre_meses,
				        datasets: array_datasets,
				    },
				    options: {
						maintainAspectRatio: false,
						spanGaps: false,
						elements: {
							line: {
								tension: 0.000001
							}
						},
						scales: {
							yAxes: [{
								stacked: true
							}]
						},
						plugins: {
							filler: {
								propagate: false
							},
							samples_filler_analyser: {
								target: 'chart-analyser'
							}
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

    //GRAFICO 4: EVOLUTIVO POR REQUERIMIENTOS

	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_requerimientos',
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
            	solicitudes_atendidos = response["atendidos"];
            	solicitudes_rechazados = response["rechazados"];
            	solicitudes_pendientes = response["pendientes"];
            	
            	meses = response["meses"];

            	
            	cantidad_meses = meses.length;
            	
            	var cantidades_atendidos = new Array();
            	var cantidades_rechazados = new Array();
            	var cantidades_pendientes = new Array();
            	var nombre_meses = new Array();
            	

            	for(i=0; i<cantidad_meses; i++)
            	{            		
            		cantidades_atendidos.push(solicitudes_atendidos[i].cantidad);
            		cantidades_rechazados.push(solicitudes_rechazados[i].cantidad);
            		cantidades_pendientes.push(solicitudes_pendientes[i].cantidad);
            		nombre_meses.push(meses[i].mes);
            	}

            	var ctx = document.getElementById("evolutivoPorEstadoRequerimiento").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: nombre_meses,
				        datasets: [{
				        	label: 'atendidos',			            
				            data: cantidades_atendidos,
				            backgroundColor:'rgba(255, 99, 132, 0.2)',
				            borderColor: 'rgba(255,99,132,1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: cantidades_rechazados,
				            backgroundColor:'rgba(54, 162, 235, 0.2)',
				            borderColor: 'rgba(54, 162, 235, 1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: cantidades_pendientes,
				            backgroundColor:'rgba(255, 206, 86, 0.2)',
				            borderColor:'rgba(255, 206, 86, 1)',
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
				            backgroundColor:'rgba(255, 99, 132, 0.2)',
				            borderColor: 'rgba(255,99,132,1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'cerrados con observaciones',			            
				            data: cantidades_cerrados,
				            backgroundColor:'rgba(54, 162, 235, 0.2)',
				            borderColor: 'rgba(54, 162, 235, 1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: cantidades_pendientes,
				            backgroundColor:'rgba(255, 206, 86, 0.2)',
				            borderColor:'rgba(255, 206, 86, 1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'procesando',			            
				            data: cantidades_procesando,
				            backgroundColor:'rgba(75, 192, 192, 0.2)',
				            borderColor: 'rgba(75, 192, 192, 1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: cantidades_rechazados,
				            backgroundColor:'rgba(153, 102, 255, 0.2)',
				            borderColor:'rgba(153, 102, 255, 1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'anulados',			            
				            data: cantidades_anulados,
				            backgroundColor:'rgba(255, 159, 64, 0.2)',
				            borderColor:'rgba(255, 159, 64, 1)',
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

	//GRAFICO 2: EVOLUTIVO POR SECTOR ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_usuario_sectores',
        type: 'POST',
        data: {'anho':anho,
        		'usuario': usuario
    			},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#D9E3F0', '#F47373', '#697689', '#37D67A', '#2CCCE4', '#555555', '#dce775', '#ff8a65', '#ba68c8'];
            	resumen = response["resumen"];
            	sectores = response["sectores"];
            	meses = response["meses"];

            	cantidad_sectores = sectores.length;
            	cantidad_meses = meses.length;
            	array_datasets = new Array();
            	array_nombres = new Array();
            	nombre_meses = new Array();

            	

            	for(i = 0;i<cantidad_sectores;i++)
            	{
            		array_nombres.push(sectores[i].nombre);
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

            		object_dataset = {
            			//agarro la data de cada mes
            			label: sectores[i].nombre,
            			data: array_cantidades,
            			backgroundColor: colors[Math.floor(Math.random() * colors.length)],
            			borderWidth: 1
            		};	

            		array_datasets.push(object_dataset);
            	}

            	
            	
            	var ctx = document.getElementById("evolutivoPorSector").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: nombre_meses,
				        datasets: array_datasets,
				    },
				    options: {
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

    //GRAFICO 3: EVOLUTIVO POR APLICATIVO
    $.ajax({
        url: inside_url+'mostrar_dashboard_anual_usuario_aplicativos',
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
            	var colors = ['#D9E3F0', '#F47373', '#697689', '#37D67A', '#2CCCE4', '#555555', '#dce775', '#ff8a65', '#ba68c8'];
            	resumen = response["resumen"];
            	aplicativos = response["herramientas"];
            	meses = response["meses"];

            	cantidad_resumen = resumen.length;
            	cantidad_aplicativos = aplicativos.length;
            	cantidad_meses = meses.length;
            	array_datasets = new Array();
            	array_nombres = new Array();
            	nombre_meses = new Array();

            	

            	for(i = 0;i<cantidad_aplicativos;i++)
            	{
            		array_nombres.push(aplicativos[i].nombre_herramienta);
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

            		color =  colors[Math.floor(Math.random() * colors.length)];

            		object_dataset = {
            			//agarro la data de cada mes
            			label: aplicativos[i].nombre_herramienta,
            			data: array_cantidades,
            			borderColor: color,
            			backgroundColor: color,
            			borderWidth: 1,
            			fill: -1
            		};	

            		array_datasets.push(object_dataset);
            	}

            	//PARA LOS NO DETECTADOS
            	array_nombres.push('NO DETECTADO');
            	array_cantidades = new Array();
        		for(j=0; j<cantidad_meses; j++)
        		{
        			//alert(resumen[1][j].cantidad);
        			array_cantidades.push(resumen[cantidad_resumen-1][j].cantidad);        			
        		}

        		color =  colors[Math.floor(Math.random() * colors.length)];

        		object_dataset = {
        			//agarro la data de cada mes
        			label: 'NO DETECTADO',
        			data: array_cantidades,
        			borderColor: color,
        			backgroundColor: color,
        			borderWidth: 1,
        			fill: -1
        		};	

        		array_datasets.push(object_dataset);
            	
            	
            	var ctx = document.getElementById("evolutivoPorAplicativo").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'line',
				    data: {
				        labels: nombre_meses,
				        datasets: array_datasets,
				    },
				    options: {
						maintainAspectRatio: false,
						spanGaps: false,
						elements: {
							line: {
								tension: 0.000001
							}
						},
						scales: {
							yAxes: [{
								stacked: true
							}]
						},
						plugins: {
							filler: {
								propagate: false
							},
							samples_filler_analyser: {
								target: 'chart-analyser'
							}
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

    //GRAFICO 4: EVOLUTIVO POR REQUERIMIENTOS

	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_usuario_requerimientos',
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
            	solicitudes_atendidos = response["atendidos"];
            	solicitudes_rechazados = response["rechazados"];
            	solicitudes_pendientes = response["pendientes"];
            	
            	meses = response["meses"];

            	
            	cantidad_meses = meses.length;
            	
            	var cantidades_atendidos = new Array();
            	var cantidades_rechazados = new Array();
            	var cantidades_pendientes = new Array();
            	var nombre_meses = new Array();
            	

            	for(i=0; i<cantidad_meses; i++)
            	{            		
            		cantidades_atendidos.push(solicitudes_atendidos[i].cantidad);
            		cantidades_rechazados.push(solicitudes_rechazados[i].cantidad);
            		cantidades_pendientes.push(solicitudes_pendientes[i].cantidad);
            		nombre_meses.push(meses[i].mes);
            	}

            	
            	
            	var ctx = document.getElementById("evolutivoPorEstadoRequerimiento").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: nombre_meses,
				        datasets: [{
				        	label: 'atendidos',			            
				            data: cantidades_atendidos,
				            backgroundColor:'rgba(255, 99, 132, 0.2)',
				            borderColor: 'rgba(255,99,132,1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: cantidades_rechazados,
				            backgroundColor:'rgba(54, 162, 235, 0.2)',
				            borderColor: 'rgba(54, 162, 235, 1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: cantidades_pendientes,
				            backgroundColor:'rgba(255, 206, 86, 0.2)',
				            borderColor:'rgba(255, 206, 86, 1)',
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
				            		'rgba(255, 99, 132, 0.2)',
				            		'rgba(54, 162, 235, 0.2)',
				            		'rgba(255, 206, 86, 0.2)',
				            		'rgba(75, 192, 192, 0.2)',
				            		'rgba(153, 102, 255, 0.2)',
				            		'rgba(255, 159, 64, 0.2)'
				            ],
				            borderColor: [
				                'rgba(255,99,132,1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(255, 206, 86, 1)',
				                'rgba(75, 192, 192, 1)',
				                'rgba(153, 102, 255, 1)',
				                'rgba(255, 159, 64, 1)'
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

	//GRAFICO 2: EVOLUTIVO POR SECTOR ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_sectores',
        type: 'POST',
        data: {'anho':anho, 'mes':mes},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	
            	solicitudes_atendidos = response["atendidos"];
            	solicitudes_cerrados = response["cerrados"];
            	solicitudes_pendientes = response["pendientes"];
            	solicitudes_procesando = response["procesando"];
            	solicitudes_rechazados = response["rechazados"];
            	solicitudes_anulados = response["anulados"];
            	sectores = response["sectores"];

            	
            	cantidad_sectores = sectores.length;
            	
            	var cantidades_atendidos = new Array();
            	var cantidades_cerrados = new Array();
            	var cantidades_pendientes = new Array();
            	var cantidades_procesando = new Array();
            	var cantidades_rechazados = new Array();
            	var cantidades_anulados = new Array();
            	var nombre_sectores = new Array();
            	

            	for(i=0; i<cantidad_sectores; i++)
            	{            		
            		cantidades_atendidos.push(solicitudes_atendidos[i].cantidad);
            		cantidades_cerrados.push(solicitudes_cerrados[i].cantidad);
            		cantidades_pendientes.push(solicitudes_pendientes[i].cantidad);
            		cantidades_procesando.push(solicitudes_procesando[i].cantidad);
            		cantidades_rechazados.push(solicitudes_rechazados[i].cantidad);
            		cantidades_anulados.push(solicitudes_anulados[i].cantidad);
            		nombre_sectores.push(sectores[i].nombre);
            	}

            	

            	var ctx = document.getElementById("mesSector").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: nombre_sectores,
				        datasets: [{
				        	label: 'atendidos',			            
				            data: cantidades_atendidos,
				            backgroundColor:'rgba(255, 99, 132, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'cerrados con observaciones',			            
				            data: cantidades_cerrados,
				            backgroundColor:'rgba(54, 162, 235, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: cantidades_pendientes,
				            backgroundColor:'rgba(255, 206, 86, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'procesando',			            
				            data: cantidades_procesando,
				            backgroundColor:'rgba(75, 192, 192, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: cantidades_rechazados,
				            backgroundColor:'rgba(153, 102, 255, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'anulados',			            
				            data: cantidades_anulados,
				            backgroundColor:'rgba(255, 159, 64, 0.2)',
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

    //GRAFICO 3: EVOLUTIVO POR APLICATIVO
    $.ajax({
        url: inside_url+'mostrar_dashboard_mes_aplicativos',
        type: 'POST',
        data: {'anho':anho, 'mes':mes},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#D9E3F0', '#F47373', '#697689', '#37D67A', '#2CCCE4', '#555555', '#dce775', '#ff8a65', '#ba68c8'];
            	
            	if(response["tiene_herramientas"] == true){

	            	resumen = response["resumen"];
	            	aplicativos = response["herramientas"];
	            	
	            	cantidad_resumen = resumen.length;
	            	cantidad_aplicativos = aplicativos.length;
	            	array_datasets = new Array();
	            	array_nombres = new Array();
	            	array_color = new Array();
	            	
	            	array_cantidades = new Array();


	            	for(i = 0;i<cantidad_aplicativos;i++)
	            	{
	            		array_nombres.push(aplicativos[i].nombre_herramienta);
	            		//creamos el dataset
	            		
	            		array_cantidades.push(resumen[i][0].cantidad);
	            			
	            		color =  colors[Math.floor(Math.random() * colors.length)];
	            		array_color.push(color);
	            		
	            		
	            	}

	            	//PARA LOS NO DETECTADOS
	            	array_nombres.push('NO DETECTADO');
	            	
	        		array_cantidades.push(resumen[cantidad_resumen-1][0].cantidad);       			
	        		color =  colors[Math.floor(Math.random() * colors.length)];
	        		array_color.push(color);
	            	
	        		

	            	var ctx = document.getElementById("mesAplicativo").getContext('2d');

					var myChart = new Chart(ctx, {
					    type: 'pie',
					    data: {
					        labels: array_nombres,
					        datasets: [{
					        	label: 'Solicitudes',
				                backgroundColor: array_color,
				                borderColor:array_color,
				                data: array_cantidades
					        }]
					    },
					    options: {
		                    // Elements options apply to all of the options unless overridden in a dataset
		                    // In this case, we are setting the border of each horizontal bar to be 2px wide
		                    
		                }
					});
				}
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

    //GRAFICO 4: EVOLUTIVO POR REQUERIMIENTOS

	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_requerimientos',
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
            	solicitudes_atendidos = response["atendidos"];
            	solicitudes_rechazados = response["rechazados"];
            	solicitudes_pendientes = response["pendientes"];
            	
            	  	var ctx = document.getElementById("mesEstadoRequerimiento").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: ['atendidos','rechazados','pendientes'],
				        datasets: [{
				        	label: 'solicitudes',			            
				            data: [solicitudes_atendidos[0].cantidad,solicitudes_rechazados[0].cantidad,solicitudes_pendientes[0].cantidad],
				            backgroundColor:['rgba(255, 99, 132, 0.2)','rgba(54, 162, 235, 0.2)','rgba(255, 206, 86, 0.2)'],
				            borderColor: ['rgba(255, 99, 132, 0.2)','rgba(54, 162, 235, 0.2)','rgba(255, 206, 86, 0.2)'],
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
            		cantidades_rechazados.push(solicitudes_cerrados[i].cantidad);
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
				            backgroundColor:'rgba(255, 99, 132, 0.2)',
				            borderColor: 'rgba(255,99,132,1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'cerrados con observaciones',			            
				            data: cantidades_cerrados,
				            backgroundColor:'rgba(54, 162, 235, 0.2)',
				            borderColor: 'rgba(54, 162, 235, 1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: cantidades_pendientes,
				            backgroundColor:'rgba(255, 206, 86, 0.2)',
				            borderColor:'rgba(255, 206, 86, 1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'procesando',			            
				            data: cantidades_procesando,
				            backgroundColor:'rgba(75, 192, 192, 0.2)',
				            borderColor: 'rgba(75, 192, 192, 1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: cantidades_rechazados,
				            backgroundColor:'rgba(153, 102, 255, 0.2)',
				            borderColor:'rgba(153, 102, 255, 1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'anulados',			            
				            data: cantidades_anulados,
				            backgroundColor:'rgba(255, 159, 64, 0.2)',
				            borderColor:'rgba(255, 159, 64, 1)',
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

	//GRAFICO 2: EVOLUTIVO POR SECTOR ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_usuario_sectores',
        type: 'POST',
        data: {'anho':anho,
        		'usuario': usuario
    			},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#D9E3F0', '#F47373', '#697689', '#37D67A', '#2CCCE4', '#555555', '#dce775', '#ff8a65', '#ba68c8'];
            	resumen = response["resumen"];
            	sectores = response["sectores"];
            	meses = response["meses"];

            	cantidad_sectores = sectores.length;
            	cantidad_meses = meses.length;
            	array_datasets = new Array();
            	array_nombres = new Array();
            	nombre_meses = new Array();

            	

            	for(i = 0;i<cantidad_sectores;i++)
            	{
            		array_nombres.push(sectores[i].nombre);
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

            		object_dataset = {
            			//agarro la data de cada mes
            			label: sectores[i].nombre,
            			data: array_cantidades,
            			backgroundColor: colors[Math.floor(Math.random() * colors.length)],
            			borderWidth: 1
            		};	

            		array_datasets.push(object_dataset);
            	}

            	
            	
            	var ctx = document.getElementById("evolutivoPorSector").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: nombre_meses,
				        datasets: array_datasets,
				    },
				    options: {
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

    //GRAFICO 3: EVOLUTIVO POR APLICATIVO
    $.ajax({
        url: inside_url+'mostrar_dashboard_anual_usuario_aplicativos',
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
            	var colors = ['#D9E3F0', '#F47373', '#697689', '#37D67A', '#2CCCE4', '#555555', '#dce775', '#ff8a65', '#ba68c8'];
            	resumen = response["resumen"];
            	aplicativos = response["herramientas"];
            	meses = response["meses"];

            	cantidad_resumen = resumen.length;
            	cantidad_aplicativos = aplicativos.length;
            	cantidad_meses = meses.length;
            	array_datasets = new Array();
            	array_nombres = new Array();
            	nombre_meses = new Array();

            	

            	for(i = 0;i<cantidad_aplicativos;i++)
            	{
            		array_nombres.push(aplicativos[i].nombre_herramienta);
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

            		color =  colors[Math.floor(Math.random() * colors.length)];

            		object_dataset = {
            			//agarro la data de cada mes
            			label: aplicativos[i].nombre_herramienta,
            			data: array_cantidades,
            			borderColor: color,
            			backgroundColor: color,
            			borderWidth: 1,
            			fill: -1
            		};	

            		array_datasets.push(object_dataset);
            	}

            	//PARA LOS NO DETECTADOS
            	array_nombres.push('NO DETECTADO');
            	array_cantidades = new Array();
        		for(j=0; j<cantidad_meses; j++)
        		{
        			//alert(resumen[1][j].cantidad);
        			array_cantidades.push(resumen[cantidad_resumen-1][j].cantidad);        			
        		}

        		color =  colors[Math.floor(Math.random() * colors.length)];

        		object_dataset = {
        			//agarro la data de cada mes
        			label: 'NO DETECTADO',
        			data: array_cantidades,
        			borderColor: color,
        			backgroundColor: color,
        			borderWidth: 1,
        			fill: -1
        		};	

        		array_datasets.push(object_dataset);
            	
            	
            	var ctx = document.getElementById("evolutivoPorAplicativo").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'line',
				    data: {
				        labels: nombre_meses,
				        datasets: array_datasets,
				    },
				    options: {
						maintainAspectRatio: false,
						spanGaps: false,
						elements: {
							line: {
								tension: 0.000001
							}
						},
						scales: {
							yAxes: [{
								stacked: true
							}]
						},
						plugins: {
							filler: {
								propagate: false
							},
							samples_filler_analyser: {
								target: 'chart-analyser'
							}
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

    //GRAFICO 4: EVOLUTIVO POR REQUERIMIENTOS

	$.ajax({
        url: inside_url+'mostrar_dashboard_anual_usuario_requerimientos',
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
            	solicitudes_atendidos = response["atendidos"];
            	solicitudes_rechazados = response["rechazados"];
            	solicitudes_pendientes = response["pendientes"];
            	
            	meses = response["meses"];

            	
            	cantidad_meses = meses.length;
            	
            	var cantidades_atendidos = new Array();
            	var cantidades_rechazados = new Array();
            	var cantidades_pendientes = new Array();
            	var nombre_meses = new Array();
            	

            	for(i=0; i<cantidad_meses; i++)
            	{            		
            		cantidades_atendidos.push(solicitudes_atendidos[i].cantidad);
            		cantidades_rechazados.push(solicitudes_rechazados[i].cantidad);
            		cantidades_pendientes.push(solicitudes_pendientes[i].cantidad);
            		nombre_meses.push(meses[i].mes);
            	}

            	
            	var ctx = document.getElementById("evolutivoPorEstadoRequerimiento").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: nombre_meses,
				        datasets: [{
				        	label: 'atendidos',			            
				            data: cantidades_atendidos,
				            backgroundColor:'rgba(255, 99, 132, 0.2)',
				            borderColor: 'rgba(255,99,132,1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: cantidades_rechazados,
				            backgroundColor:'rgba(54, 162, 235, 0.2)',
				            borderColor: 'rgba(54, 162, 235, 1)',
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: cantidades_pendientes,
				            backgroundColor:'rgba(255, 206, 86, 0.2)',
				            borderColor:'rgba(255, 206, 86, 1)',
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
				            		'rgba(255, 99, 132, 0.2)',
				            		'rgba(54, 162, 235, 0.2)',
				            		'rgba(255, 206, 86, 0.2)',
				            		'rgba(75, 192, 192, 0.2)',
				            		'rgba(153, 102, 255, 0.2)',
				            		'rgba(255, 159, 64, 0.2)'
				            ],
				            borderColor: [
				                'rgba(255,99,132,1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(255, 206, 86, 1)',
				                'rgba(75, 192, 192, 1)',
				                'rgba(153, 102, 255, 1)',
				                'rgba(255, 159, 64, 1)'
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

	//GRAFICO 2: EVOLUTIVO POR SECTOR ASOCIADO
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_sectores_usuario',
        type: 'POST',
        data: {'anho':anho, 'mes':mes,'usuario':usuario},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	
            	solicitudes_atendidos = response["atendidos"];
            	solicitudes_cerrados = response["cerrados"];
            	solicitudes_pendientes = response["pendientes"];
            	solicitudes_procesando = response["procesando"];
            	solicitudes_rechazados = response["rechazados"];
            	solicitudes_anulados = response["anulados"];
            	sectores = response["sectores"];

            	
            	cantidad_sectores = sectores.length;
            	
            	var cantidades_atendidos = new Array();
            	var cantidades_cerrados = new Array();
            	var cantidades_pendientes = new Array();
            	var cantidades_procesando = new Array();
            	var cantidades_rechazados = new Array();
            	var cantidades_anulados = new Array();
            	var nombre_sectores = new Array();
            	

            	for(i=0; i<cantidad_sectores; i++)
            	{            		
            		cantidades_atendidos.push(solicitudes_atendidos[i].cantidad);
            		cantidades_cerrados.push(solicitudes_cerrados[i].cantidad);
            		cantidades_pendientes.push(solicitudes_pendientes[i].cantidad);
            		cantidades_procesando.push(solicitudes_procesando[i].cantidad);
            		cantidades_rechazados.push(solicitudes_rechazados[i].cantidad);
            		cantidades_anulados.push(solicitudes_anulados[i].cantidad);
            		nombre_sectores.push(sectores[i].nombre);
            	}

            	

            	var ctx = document.getElementById("mesSector").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: nombre_sectores,
				        datasets: [{
				        	label: 'atendidos',			            
				            data: cantidades_atendidos,
				            backgroundColor:'rgba(255, 99, 132, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'cerrados con observaciones',			            
				            data: cantidades_cerrados,
				            backgroundColor:'rgba(54, 162, 235, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'pendientes',			            
				            data: cantidades_pendientes,
				            backgroundColor:'rgba(255, 206, 86, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'procesando',			            
				            data: cantidades_procesando,
				            backgroundColor:'rgba(75, 192, 192, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'rechazados',			            
				            data: cantidades_rechazados,
				            backgroundColor:'rgba(153, 102, 255, 0.2)',
				            borderWidth: 1
				        },
				        {
				        	label: 'anulados',			            
				            data: cantidades_anulados,
				            backgroundColor:'rgba(255, 159, 64, 0.2)',
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

    //GRAFICO 3: EVOLUTIVO POR APLICATIVO
    $.ajax({
        url: inside_url+'mostrar_dashboard_mes_aplicativos_usuario',
        type: 'POST',
        data: {'anho':anho, 'mes':mes, 'usuario':usuario},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){    	
            	var colors = ['#D9E3F0', '#F47373', '#697689', '#37D67A', '#2CCCE4', '#555555', '#dce775', '#ff8a65', '#ba68c8'];
            	
            	if(response["tiene_herramientas"] == true){

	            	resumen = response["resumen"];
	            	aplicativos = response["herramientas"];
	            	
	            	cantidad_resumen = resumen.length;
	            	cantidad_aplicativos = aplicativos.length;
	            	array_datasets = new Array();
	            	array_nombres = new Array();
	            	array_color = new Array();
	            	
	            	array_cantidades = new Array();


	            	for(i = 0;i<cantidad_aplicativos;i++)
	            	{
	            		array_nombres.push(aplicativos[i].nombre_herramienta);
	            		//creamos el dataset
	            		
	            		array_cantidades.push(resumen[i][0].cantidad);
	            			
	            		color =  colors[Math.floor(Math.random() * colors.length)];
	            		array_color.push(color);
	            		
	            		
	            	}

	            	//PARA LOS NO DETECTADOS
	            	array_nombres.push('NO DETECTADO');
	            	
	        		array_cantidades.push(resumen[cantidad_resumen-1][0].cantidad);       			
	        		color =  colors[Math.floor(Math.random() * colors.length)];
	        		array_color.push(color);
	            	
	        		

	            	var ctx = document.getElementById("mesAplicativo").getContext('2d');

					var myChart = new Chart(ctx, {
					    type: 'pie',
					    data: {
					        labels: array_nombres,
					        datasets: [{
					        	label: 'Solicitudes',
				                backgroundColor: array_color,
				                borderColor:array_color,
				                data: array_cantidades
					        }]
					    },
					    options: {
		                    // Elements options apply to all of the options unless overridden in a dataset
		                    // In this case, we are setting the border of each horizontal bar to be 2px wide
		                    
		                }
					});
				}
            	
            }else{
            	
                alert('La petición no se pudo completar, inténtelo de nuevo.');
            }
        },
        error: function(){
            alert('La petición no se pudo completar, inténtelo de nuevo.');
        }
    });

    //GRAFICO 4: EVOLUTIVO POR REQUERIMIENTOS
	$.ajax({
        url: inside_url+'mostrar_dashboard_mes_requerimientos_usuario',
        type: 'POST',
        data: {'anho':anho,'mes':mes, 'usuario':usuario},
        beforeSend: function(){
            $(".loader_container").show();
        },
        complete: function(){
            $(".loader_container").hide();
        },
        success: function(response){
            if(response.success){
            	solicitudes_atendidos = response["atendidos"];
            	solicitudes_rechazados = response["rechazados"];
            	solicitudes_pendientes = response["pendientes"];
            	
            	 //alert(solicitudes_atendidos[0].cantidad);

            	 var ctx = document.getElementById("mesEstadoRequerimiento").getContext('2d');

				var myChart = new Chart(ctx, {
				    type: 'bar',
				    data: {
				        labels: ['atendidos','rechazados','pendientes'],
				        datasets: [{
				        	label: 'solicitudes',			            
				            data: [solicitudes_atendidos[0].cantidad,solicitudes_rechazados[0].cantidad,solicitudes_pendientes[0].cantidad],
				            backgroundColor:['rgba(255, 99, 132, 0.2)','rgba(54, 162, 235, 0.2)','rgba(255, 206, 86, 0.2)'],
				            borderColor: ['rgba(255, 99, 132, 0.2)','rgba(54, 162, 235, 0.2)','rgba(255, 206, 86, 0.2)'],
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

}	