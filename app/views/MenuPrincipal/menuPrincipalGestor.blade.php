@extends('Templates/menuPrincipalTemplate')
@section('content')
<div class="main">
	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container-fluid">
			<h3 class="page-title"><strong>BANDEJA SOLICITUDES</strong> </h3>
			@if (Session::has('info'))
				<div class="alert alert-info alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<i class="fa fa-times-circle"></i> {{ Session::get('info') }}
				</div>
			@endif
			@if (Session::has('message'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-check-circle"></i> 
					{{ Session::get('message') }}
				</div>
			@endif
			@if (Session::has('error'))
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<i class="fa fa-times-circle"></i> {{ Session::get('error') }}
				</div>
			@endif
			@if($solicitud_id_precargar == null)
				{{ Form::hidden('solicitud_id_precargar', null,array('id'=>'solicitud_id_precargar')) }}	
			@else
				{{ Form::hidden('solicitud_id_precargar', $solicitud_id,array('id'=>'solicitud_id_precargar')) }}	
			@endif

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-headline">
						<div class="panel-body">	
							{{ Form::open(array('url'=>'/principal/buscar_solicitud_codigo','method'=>'get' ,'role'=>'form')) }}
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('search_codigo_solicitud','Código Solicitud:')}}
									{{ Form::text('search_codigo_solicitud',$search_codigo_solicitud,array('class'=>'form-control','placeholder'=>'Ingresar cod. solicitud','id'=>'search_codigo_solicitud')) }}
								</div>	
								<div class="form-group col-md-2">
									{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
								</div>
							{{Form::close()}}
								<div class="col-md-2">
									<div class="btn btn-default btn-block" id="btnLimpiarCodigo" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
								</div>							
							{{ Form::open(array('url'=>'/descargar_plantilla_gestor','method'=>'get' ,'role'=>'form')) }}
								<div class="form-group col-md-3">
									{{ Form::button('<span class="lnr lnr-text-align-center""></span> Descargar Plantilla FUR', array('type' => 'submit', 'class' => 'btn btn-success btn-block','style'=>'margin-top:25px;')) }}	
								</div>
							{{ Form::close() }}		
							{{ Form::open(array('url'=>'/generar_reporte_gestor','method'=>'get' ,'role'=>'form')) }}
								<div class="form-group col-md-2">
									{{ Form::button('<span class="fa fa-download""></span> Exportar Base', array('type' => 'submit', 'class' => 'btn btn-success btn-block','style'=>'margin-top:25px;')) }}	
								</div>
							{{ Form::close() }}
							</div>
							
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-headline">
				<div class="panel-heading">
					<h3 class="panel-title">Tickets Pendientes</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-2">
							<div class="metric">
								<span class="icon" style="background-color:#ffeb3b;"><i class="lnr lnr-warning"></i></span>
								<p>
									<span class="number">{{$solicitudes_pendientes}}</span>
									<span class="title">Tickets Pendientes</span>
								</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-nowrap text-center">Código<br>Solicitud</th>
											<th class="text-nowrap text-center">Requerimientos</th>
											<th class="text-nowrap text-center">Fecha de<br>Solicitud</th>
											<!--
											<th class="text-nowrap text-center">Fecha de Asignación</th>
											<th class="text-nowrap text-center">Número de Corte</th>
											<th class="text-nowrap text-center">Días de Asignación</th>
											-->
											<th class="text-nowrap text-center">Estado</th>
											<th class="text-nowrap text-center">Acción</th>
											<th class="text-nowrap text-center">Herramienta</th>
											<th class="text-nowrap text-center">SLA</th>
											<th class="text-nowrap text-center">Días<br>Laborales</th>
											<th class="text-nowrap text-center">SEMÁFORO</th>
											<th class="text-nowrap text-center">Rechazar<br>Solicitud</th>
										</tr>
									</thead>
									@foreach($solicitudes_pendiente_data as $index => $solicitud_data)
									<tbody>	
										<tr>
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/solicitudes/mostrar_solicitud')}}/{{$solicitud_data->idsolicitud}}">{{$solicitud_data->codigo_solicitud}}</a>
											</td>
											<td class="text-nowrap text-center">
												<div style="text-align:center">
													<button class="btn btn-success btn-sm" onclick="cargar_base(event,{{$solicitud_data->idsolicitud}})" type="button"><i class="fa fa-download"></i> Cargar Base</button>
												</div>
											</td>
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_solicitud))}}
											</td>
											<!--
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_asignacion))}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->numero_corte != null)
													CORTE N° {{$solicitud_data->numero_corte}}
												@else
													SOLICITUD MANUAL
												@endif
											</td>
											<td class="text-nowrap text-center">
												{{$diferencia_fechas_pendiente[$index]}}
											</td>
											-->
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_estado_solicitud}}
												@if($solicitud_data->ticket_reasignado == 1)
													<font color="red"><strong><br>(reasignado)</strong></font>
												@endif
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_tipo_solicitud}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idherramienta != null)
													{{$solicitud_data->nombre_herramienta}}
												@else
													NO DETECTADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												{{$slas_data_pendiente[$index]->sla_pendiente}}									
											</td>
											<td class="text-nowrap text-center">
												{{$diferencia_fechas_trabajo_pendiente[$index]}}									
											</td>
											<td class="text-nowrap text-center">
												@if( $diferencia_fechas_trabajo_pendiente[$index] < $slas_data_pendiente[$index]->sla_pendiente - 1 )
												 	<!-- VERDE -->
													<span style=" padding: 2px 11px; border-radius: 100%;background-color: #00C851;"></span>
												@elseif( $slas_data_pendiente[$index]->sla_pendiente - $diferencia_fechas_trabajo_pendiente[$index] <= 1 &&  $slas_data_pendiente[$index]->sla_pendiente - $diferencia_fechas_trabajo_pendiente[$index] >= 0 )
													<!-- AMBAR -->
													<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ffb74d;"></span>
												@else
													<!-- ROJO -->
													<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ff4444;"></span>
												@endif
											</td>
											<td class="text-nowrap text-center">
													<div style="text-align:center">
													<button class="btn btn-danger btn-sm" onclick="rechazar_solicitud(event,{{$solicitud_data->idsolicitud}})" type="button"><i class="fa fa-close"></i></button>
												</div>
											</td>
										</tr>
									</tbody>
									@endforeach
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-headline">
				<div class="panel-heading">
					<h3 class="panel-title">Tickets Procesando</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-2">
							<div class="metric">
								<span class="icon" style="background-color:#0088CC;"><i class="lnr lnr-history"></i></span>
								<p>
									<span class="number">{{$solicitudes_procesando}}</span>
									<span class="title">Tickets Procesando</span>
								</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-nowrap text-center">Código<br>Solicitud</th>
											<th class="text-nowrap text-center">Requerimientos</th>
											<th class="text-nowrap text-center">Fecha de<br>Solicitud</th>
											<!--
											<th class="text-nowrap text-center">Fecha de Asignación</th>
											<th class="text-nowrap text-center">Número de Corte</th>
											<th class="text-nowrap text-center">Días de Asignación</th>
											-->
											<th class="text-nowrap text-center">Estado</th>
											<th class="text-nowrap text-center">Tipo</th>
											<th class="text-nowrap text-center">Herramienta</th>
											<th class="text-nowrap text-center">SLA</th>
											<th class="text-nowrap text-center">Días<br>Laborales</th>
											<th class="text-nowrap text-center">SEMÁFORO</th>
											
										</tr>
									</thead>
									@foreach($solicitudes_procesando_data as $index => $solicitud_data)
									<tbody>	
										<tr>
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/solicitudes/mostrar_solicitud')}}/{{$solicitud_data->idsolicitud}}">{{$solicitud_data->codigo_solicitud}}</a>
											</td>
											<td class="text-nowrap text-center">
												<div style="text-align:center">
													<button class="btn btn-success btn-sm" onclick="mostrar_datos_req(event,{{$solicitud_data->idsolicitud}})" type="button"><i class="fa fa-search"></i> Ver Transacciones</button>
												</div>
											</td>
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_solicitud))}}
											</td>
											<!--
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_asignacion))}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->numero_corte != null)
													CORTE N° {{$solicitud_data->numero_corte}}
												@else
													SOLICITUD MANUAL
												@endif
											</td>
											<td class="text-nowrap text-center">
												{{$diferencia_fechas_procesando[$index]}}
											</td>
											-->
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_estado_solicitud}}
												@if($solicitud_data->ticket_reasignado == 1)
													<font color="red"><strong><br>(reasignado)</strong></font>
												@endif
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_tipo_solicitud}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idherramienta != null)
													{{$solicitud_data->nombre_herramienta}}
												@else
													NO DETECTADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												{{$slas_data_procesando[$index]->sla_procesando}}
											</td>
											<td class="text-nowrap text-center">
												{{$diferencia_fechas_trabajo_procesando[$index]}}
											</td>
											<td class="text-nowrap text-center">												
												@if( $diferencia_fechas_trabajo_procesando[$index] < $slas_data_procesando[$index]->sla_procesando - 1 )
												 	<!-- VERDE -->
													<span style=" padding: 2px 11px; border-radius: 100%;background-color: #00C851;"></span>
												@elseif( $slas_data_procesando[$index]->sla_procesando - $diferencia_fechas_trabajo_procesando[$index] <= 1 &&  $slas_data_procesando[$index]->sla_procesando - $diferencia_fechas_trabajo_procesando[$index] >= 0)
													<!-- AMBAR -->
													<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ffb74d;"></span>
												@else
													<!-- ROJO -->
													<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ff4444;"></span>
												@endif
											</td>
											
										</tr>
									</tbody>
									@endforeach
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END MAIN CONTENT -->
</div>
{{ Form::open(array('url'=>'/requerimientos/cargar_requerimientos' ,'role'=>'form','id'=>'submit-cargar','enctype'=>'multipart/form-data')) }}
<div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_requerimientos_carga"  role="dialog">
    <div class="modal-dialog modal-lg">    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header" id="modal_header_requerimientos_carga">
	          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Cargar Base FUR Estandarizado</h4>
	          {{ Form::hidden('solicitud_id', null,array('id'=>'solicitud_id')) }}	
	        </div>
	        <div class="modal-body" id="modal_text_acciones">
	         	<div class="container-fluid">
	         		<div class="row">
						<div class="col-md-7">
							<label class="control-label">Seleccione Archivo .XLSX/.XLS</label>
							<input id="input-file-fur" type="file" name="file">
						</div>
						<div class="col-md-5" style="padding-top:26px">				
							<a class="btn btn-info btn-block" id="btnCargar" type="submit"> <i class="fa fa-upload"></i> Cargar Requerimientos</a>
						</div>
					</div>
				</div>
			</div>
      </div>      
    </div>
  </div>
 </div>  
 {{ Form::close() }}

 <div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_requerimientos_mostrar"  role="dialog" style="overflow-y:scroll;">
    <div class="modal-dialog modal-lg" style="width:100%;" >    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header" id="modal_header_requerimientos_mostrar">
	          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="solicitud-title"></h4>
	         
	        </div>
	        <div class="modal-body" id="modal_text_acciones">
	         	<div class="container-fluid">
	         		<div id="message-in-modal" style="display:block;">
		         		@if (Session::has('message'))
							<div class="alert alert-success">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<i class="fa fa-check-circle"></i> 
								{{ Session::get('message') }}
							</div>
						@endif
						@if (Session::has('error'))
							<div class="alert alert-danger alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
								<i class="fa fa-times-circle"></i> {{ Session::get('error') }}
							</div>
						@endif
					</div>

					<!-- OVERVIEW -->
					{{ Form::open(array('url'=>'/requerimientos/submit_crear_transaccion' ,'role'=>'form','id'=>'submit-crear-transaccion')) }}
					{{ Form::hidden('solicitud_id_nueva_transaccion', null,array('id'=>'solicitud_id_nueva_transaccion')) }}	
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-headline">
								<div class="panel-heading">
									<h3 class="panel-title">AGREGAR NUEVA TRANSACCION</h3>
								</div>
								<div class="panel-body">
									<div class="row">
										@if ($errors->has())
										<div class="alert alert-danger" role="alert">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<p>{{ $errors->first('accion') }}</p>				
											<p>{{ $errors->first('nombre_usuario') }}</p>
											<p>{{ $errors->first('numero_documento') }}</p>
											<p>{{ $errors->first('cargo') }}</p>
											<p>{{ $errors->first('aplicativo') }}</p>
											<p>{{ $errors->first('punto_venta') }}</p>
											<p>{{ $errors->first('observacion') }}</p>	
										</div>
										@endif
									</div>
									<div class="row">
										<div class="form-group col-md-3 @if($errors->first('accion')) has-error has-feedback @endif"">
											{{ Form::label('accion','Accion')}}
											{{ Form::select('accion',
											[''=>'Seleccione',
												'BLOQUEAR'=>'BLOQUEAR',
												'CREAR'=>'CREAR',
												'DESBLOQUEAR'=>'DESBLOQUEAR',
												'ELIMINAR'=>'ELIMINAR',
												'MODIFICAR'=>'MODIFICAR',
												'REINSTALAR'=>'REINSTALAR',
												'RESETEAR'=>'RESETEAR',
												'TRASLADAR'=>'TRASLADAR',
												],Input::old('accion'),array('class'=>'form-control','id'=>'trAccion')) }}
										</div>
										<div class="form-group col-md-3 @if($errors->first('nombre_usuario')) has-error has-feedback @endif"">
											{{ Form::label('nombre_usuario','Nombre Usuario (Completo):')}}
											{{ Form::text('nombre_usuario',Input::old('nombre_usuario'),array('class'=>'form-control','placeholder'=>'Ingrese nombre completo del usuario','id'=>'trNombreUsuario')) }}
										</div>
										<div class="form-group col-md-3 @if($errors->first('numero_documento')) has-error has-feedback @endif"">
											{{ Form::label('numero_documento','Número de Documento:')}}
											{{ Form::text('numero_documento',Input::old('numero_documento'),array('class'=>'form-control','placeholder'=>'Ingrese número de documento','minlength'=>'8','id'=>'trNumeroDocumento')) }}
										</div>
										<div class="form-group col-md-3 @if($errors->first('cargo')) has-error has-feedback @endif"">
											{{ Form::label('cargo','Cargo Usuario:')}}
											{{ Form::text('cargo',Input::old('cargo'),array('class'=>'form-control','placeholder'=>'Ingrese cargo','id'=>'trCargo')) }}
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-3 @if($errors->first('aplicativo')) has-error has-feedback @endif"">
											{{ Form::label('aplicativo','Aplicativo:')}}
											{{ Form::select('aplicativo',array(''=>'Seleccione')+$aplicativos,Input::old('aplicativo'),array('class'=>'form-control','id'=>'trAplicativo')) }}
										</div>
										<div class="form-group col-md-3 @if($errors->first('punto_venta')) has-error has-feedback @endif"">
											{{ Form::label('punto_venta','Punto Venta:')}}
											{{ Form::select('punto_venta',array(''=>'Seleccione'),Input::old('punto_venta'),array('class'=>'form-control','id'=>'slcPuntoVenta')) }}
										</div>										
									</div>
									<div class="row">
										<div class="form-group col-md-2  col-md-offset-8">				
											<a class="btn btn-info btn-block" id="btnCrearTransaccion"> <i class="lnr lnr-plus-circle"></i> AGREGAR TRANSACCION</a>
										</div>
										<div class="form-group col-md-2">
											<a class="btn btn-default btn-block" id="btnLimpiarTransaccion"><i class="lnr lnr-arrow-left"></i>LIMPIAR</a>				
										</div>
									</div>				
								</div>
							</div>
						</div>
					</div>
					
					{{Form::close()}}

					{{ Form::open(array('url'=>'/requerimientos/submit_actualizar_codigos' ,'role'=>'form','id'=>'submit-actualizar','enctype'=>'multipart/form-data')) }}
					 {{ Form::hidden('solicitud_id_mostrar', null,array('id'=>'solicitud_id_mostrar')) }}	
	         		<div class="row">	
	         			<div class="table-responsive" >
							<table class="table table-hover" id="table_requerimientos" >
								<thead>
									<tr>
										<th class="text-nowrap text-center">ID Único<br>Transacción</th>
										<th class="text-nowrap text-center">Código<br>Requerimiento</th>
										<th class="text-nowrap text-center">Acción</th>
										<th class="text-nowrap text-center">Aplicativo</th>
										<th class="text-nowrap text-center">Tipo<br>Gestión</th>
										<!--
										<th class="text-nowrap text-center">Canal</th>
										<th class="text-nowrap text-center">Entidad</th>
										<th class="text-nowrap text-center">Punto de Venta</th>
										-->
										<th class="text-nowrap text-center">Cargo</th>
										<th class="text-nowrap text-center">Nombre<br>Completo</th>
										<th class="text-nowrap text-center">DNI<br>Usuario</th>
										<th class="text-nowrap text-center">Estado</th>
										<th class="text-nowrap text-center">Observaciones/<br>Trazabilidad</th>
										<th class="text-nowrap text-center">Trabajar<br>Req. <input type="checkbox" id="checkboxAllTrabajar" class="form-check-input"> </th>
										<th class="text-nowrap text-center">Finalizar<br>Req. <input type="checkbox" id="checkboxAllFinalizar" class="form-check-input"> </th>
										<th class="text-nowrap text-center">Rechazar<br>Req.</th>
										<th class="text-nowrap text-center">Eliminar<br>Req.</th>
									</tr>
								</thead>
								<tbody >	
									
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
	        <div class="modal-footer">
	        	 {{ Form::close() }} 
	        	<div class="form-group col-md-3">
					<a class="btn btn-primary btn-block" id="btnActualizarCodigos"><i class="fa fa-floppy-o"></i>&nbsp ACTUALIZAR CODIGOS REQUERIMIENTOS</a>				
				</div>
				<div class="form-group col-md-3 col-md-offset-3">
					<a class="btn btn-info btn-block" id="btnProcesarCodigos"><i class="lnr lnr-cog"></i>&nbsp PROCESAR TRANSACCIONES</a>
				</div>
				<div class="form-group col-md-3">
					<a class="btn btn-success btn-block" id="btnFinalizarCodigos"><i class="fa fa-thumbs-up"></i>&nbsp ATENDER TRANSACCIONES</a>
				</div>
	        </div>
      </div>      
    </div>
  </div>
 </div> 


 {{ Form::open(array('url'=>'/requerimientos/submit_rechazar_requerimiento' ,'role'=>'form','id'=>'submit-rechazar','enctype'=>'multipart/form-data')) }}
 <div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_requerimientos_rechazar"  data-backdrop="static"  role="dialog">
    <div class="modal-dialog modal-md" >    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header modal-open" id="modal_header_requerimientos_rechazar">
	          
	          <h4 class="modal-title">Rechazar Transacción: </h4> 
	          {{ Form::hidden('requerimiento_id_rechazar', null,array('id'=>'requerimiento_id_rechazar')) }}	
	        </div>
	        <div class="modal-body" id="modal_text_acciones">
	         	<div class="container-fluid">
	         		<div class="row">
						<div class="form-group col-md-9 col-md-offset-1 @if($errors->first('observacion')) has-error has-feedback @endif"">
							{{ Form::label('observacion','Observaciones:')}}
							{{ Form::textarea('observacion',null,array('class'=>'form-control','placeholder'=>'Ingrese las observaciones','rows'=>5,'style'=>'resize:none','id'=>'observacion_rechazo')) }}
						</div>
					</div>
				</div>
			</div>
	        <div class="modal-footer">
	        	<div class="form-group col-md-5 col-md-offset-3">
					<a class="btn btn-primary btn-block" id="btnRechazarRequerimiento"><i class="lnr lnr-cross"></i>&nbsp Rechazar Transacción</a>				
				</div>
				<div class="form-group col-md-3">
					<a class="btn btn-default btn-block" id="btnCancelarRechazarRequerimiento">&nbsp Cancelar</a>				
				</div>
	        </div>
      </div>      
    </div>
  </div>
 </div> 
 {{ Form::close() }} 

<div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_requerimientos_trazabilidad"  role="dialog" style="overflow-y:scroll;">
    <div class="modal-dialog modal-md" style="width:80%">    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header" id="modal_header_requerimientos_trazabilidad">
	          
	          <h4 class="modal-title" id="transaccion-title"></h4>
	         
	        </div>
	        <div class="modal-body" id="modal_text_acciones_trazabilidad">
	         	<div class="container-fluid">
	         		<div id="message-in-modal-2" style="display:block;">
		         		@if (Session::has('message'))
							<div class="alert alert-success">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<i class="fa fa-check-circle"></i> 
								{{ Session::get('message') }}
							</div>
						@endif
						@if (Session::has('error'))
							<div class="alert alert-danger alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
								<i class="fa fa-times-circle"></i> {{ Session::get('error') }}
							</div>
						@endif
					</div>

					<!-- OVERVIEW -->
					{{ Form::open(array('url'=>'/requerimientos/submit_crear_trazabilidad' ,'role'=>'form','id'=>'submit-crear-trazabilidad')) }}
					{{ Form::hidden('transaccion_id_trazabilidad', null,array('id'=>'transaccion_id_trazabilidad')) }}
					{{ Form::hidden('trazabilidad_id_editar', null,array('id'=>'trazabilidad_id_editar')) }}	
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-headline">
								<div class="panel-heading">
									<h3 class="panel-title">AGREGAR OBSERVACION</h3>
								</div>
								<div class="panel-body">
									<div class="row">
										@if ($errors->has())
										<div class="alert alert-danger" role="alert">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<p>{{ $errors->first('observacion') }}</p>
										</div>
										@endif
									</div>
									<div class="row">
										<div class="form-group col-md-12 @if($errors->first('observacion')) has-error has-feedback @endif"">
											{{ Form::label('observacion','Observacion:')}}
											{{ Form::textarea('observacion',Input::old('observacion'),array('class'=>'form-control','placeholder'=>'Ingrese observacion','id'=>'trObservacion','rows'=>'5','style'=>'resize:none')) }}
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-4  col-md-offset-4">				
											<a class="btn btn-info btn-block" id="btnCrearTrazabilidad"> <i class="lnr lnr-plus-circle"></i> GUARDAR</a>
										</div>
										<div class="form-group col-md-4">
											<a class="btn btn-default btn-block" id="btnLimpiarTrazabilidad"><i class="lnr lnr-arrow-left"></i>LIMPIAR</a>				
										</div>
									</div>				
								</div>
							</div>
						</div>
					</div>
					
					{{Form::close()}}

					
	         		<div class="row">	         			
						<div class="table-responsive" >
							<table class="table table-hover" id="table_trazabilidad" >
								<thead>
									<tr>
										<th class="text-nowrap text-center">N°</th>
										<th class="text-nowrap text-center">Observacion</th>
										<th class="text-nowrap text-center">Fecha</th>
										<th class="text-nowrap text-center">Editar<br>Observacion</th>
										<th class="text-nowrap text-center">Eliminar<br>Observacion</th>
									</tr>
								</thead>
								<tbody >	
									
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
	        <div class="modal-footer">
	        	 <div class="row">
					<div class="form-group col-md-4 col-md-offset-8">
						<a class="btn btn-default btn-block" id="btnSalirTrazabilidad"><i class="lnr lnr-arrow-left"></i>SALIR</a>				
					</div>
				</div>			
	        </div>
      </div>      
    </div>
  </div>
 </div> 

 {{ Form::open(array('url'=>'/solicitudes/submit_rechazar_solicitud' ,'role'=>'form','id'=>'submit-rechazar-solicitud','enctype'=>'multipart/form-data')) }}
 <div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_solicitud_rechazar"  data-backdrop="static"  role="dialog">
    <div class="modal-dialog modal-md" >    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header modal-open" id="modal_header_solicitud_rechazar">
	          
	          <h4 class="modal-title">Rechazar Solicitud: </h4> 
	          {{ Form::hidden('solicitud_id_rechazar', null,array('id'=>'solicitud_id_rechazar')) }}	
	        </div>
	        <div class="modal-body" id="modal_text_acciones">
	         	<div class="container-fluid">
	         		<div class="row">
						<div class="form-group col-md-9 col-md-offset-1 @if($errors->first('observacion')) has-error has-feedback @endif"">
							{{ Form::label('observacion','Observaciones:')}}
							{{ Form::textarea('observacion',null,array('class'=>'form-control','placeholder'=>'Ingrese las observaciones','rows'=>5,'style'=>'resize:none','id'=>'observacion_rechazo_solicitud')) }}
						</div>
					</div>
				</div>
			</div>
	        <div class="modal-footer">
	        	<div class="form-group col-md-5 col-md-offset-3">
					<a class="btn btn-primary btn-block" id="btnRechazarSolicitud"><i class="lnr lnr-cross"></i>&nbsp Rechazar Solicitud</a>				
				</div>
				<div class="form-group col-md-3">
					<a class="btn btn-default btn-block" id="btnCancelarRechazarSolicitud">&nbsp Cancelar</a>				
				</div>
	        </div>
      </div>      
    </div>
  </div>
 </div> 
 {{ Form::close() }} 


  {{ Form::open(array('url'=>'/requerimientos/submit_finalizar_requerimiento' ,'role'=>'form','id'=>'submit-finalizar','enctype'=>'multipart/form-data')) }}
	   
	   <div style="display:none" id="div_ids_checkbox_finalizar">
	   	
	   </div>

	   {{ Form::hidden('solicitud_id_finalizar', null,array('id'=>'solicitud_id_finalizar')) }}	
   
  {{ Form::close() }} 
   
  {{ Form::open(array('url'=>'/requerimientos/submit_procesar_requerimiento','role'=>'form','id'=>'submit-procesar','enctype'=>'multipart/form-data'))}}
	   
	   <div style="display:none" id="div_ids_checkbox">
	   	
	   </div>

	   {{ Form::hidden('solicitud_id_procesar', null,array('id'=>'solicitud_id_procesar')) }}	

  {{ Form::close() }} 

  {{ Form::open(array('url'=>'/requerimientos/submit_eliminar_transaccion','role'=>'form','id'=>'submit-eliminar-transaccion','enctype'=>'multipart/form-data'))}}
	   
	   {{ Form::hidden('transaccion_id_eliminar', null,array('id'=>'transaccion_id_eliminar')) }}	

  {{ Form::close() }}

  {{ Form::open(array('url'=>'/requerimientos/submit_eliminar_observacion','role'=>'form','id'=>'submit-eliminar-observacion','enctype'=>'multipart/form-data'))}}
	   
	   {{ Form::hidden('trazabilidad_id_eliminar', null,array('id'=>'trazabilidad_id_eliminar')) }}	

  {{ Form::close() }}

@stop