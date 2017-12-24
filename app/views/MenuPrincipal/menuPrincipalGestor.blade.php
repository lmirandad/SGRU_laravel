@extends('Templates/menuPrincipalTemplate')
@section('content')
<div class="main">
	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container-fluid">
			<h3 class="page-title"><strong>BANDEJA SOLICITUDES</strong> </h3>
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
			<div class="row">
				<div class="col-md-7 col-md-offset-3">
					<div class="panel panel-headline">
						<div class="panel-body">	
							{{ Form::open(array('url'=>'/principal/buscar_solicitud_codigo','method'=>'get' ,'role'=>'form')) }}
							<div class="row">
								<div class="col-md-5">
									{{ Form::label('search_codigo_solicitud','Código Solicitud:')}}
									{{ Form::text('search_codigo_solicitud',$search_codigo_solicitud,array('class'=>'form-control','placeholder'=>'Ingresar código solicitud','id'=>'search_codigo_solicitud')) }}
								</div>	
								<div class="form-group col-md-3">
									{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
								</div>
								<div class="col-md-3">
									<div class="btn btn-default btn-block" id="btnLimpiarCodigo" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
								</div>	
							</div>
							{{Form::close()}}
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-headline">
				<div class="panel-heading">
					<h3 class="panel-title">Tickets Por Usuarios</h3>
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
											<th class="text-nowrap text-center">Código Solicitud</th>
											<th class="text-nowrap text-center">Fecha de Solicitud</th>
											<th class="text-nowrap text-center">Fecha de Asignación</th>
											<th class="text-nowrap text-center">Días de Asignación</th>
											<th class="text-nowrap text-center">Tipo</th>
											<th class="text-nowrap text-center">Herramienta</th>
											<th class="text-nowrap text-center">SLA</th>
											<th class="text-nowrap text-center">SEMÁFORO</th>
											<th class="text-nowrap text-center">Requerimientos</th>
										</tr>
									</thead>
									@foreach($solicitudes_pendiente_data as $index => $solicitud_data)
									<tbody>	
										<tr>
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/solicitudes/mostrar_solicitud')}}/{{$solicitud_data->idsolicitud}}">{{$solicitud_data->codigo_solicitud}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_solicitud))}}
											</td>
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_asignacion))}}
											</td>
											<td class="text-nowrap text-center">
												{{$diferencia_fechas_pendiente[$index]}}
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_tipo_solicitud}}
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_herramienta}}
											</td>
											<td class="text-nowrap text-center">
												{{$slas_data_pendiente[$index]->sla_pendiente}}									
											</td>
											<td class="text-nowrap text-center">
												{{$diferencia_fechas_trabajo_pendiente[$index]}}									
											</td>
											<td class="text-nowrap text-center">
												@if( $diferencia_fechas_trabajo_pendiente[$index] < $slas_data_pendiente[$index]->sla_pendiente )
												 	<!-- VERDE -->
													<span style=" padding: 2px 11px; border-radius: 100%;background-color: #00C851;"></span>
												@elseif( $diferencia_fechas_trabajo_pendiente[$index] == $slas_data_pendiente[$index]->sla_pendiente )
													<!-- AMBAR -->
													<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ffb74d;"></span>
												@else
													<!-- ROJO -->
													<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ff4444;"></span>
												@endif
											</td>
											<td class="text-nowrap text-center">
												<div style="text-align:center">
													<button class="btn btn-success btn-sm" onclick="cargar_base(event,{{$solicitud_data->idsolicitud}})" type="button"></button>
													<button class="btn btn-info btn-sm" onclick="mostrar_datos_req(event,{{$solicitud_data->idsolicitud}})" type="button"></button>
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
											<th class="text-nowrap text-center">Código Solicitud</th>
											<th class="text-nowrap text-center">Fecha de Solicitud</th>
											<th class="text-nowrap text-center">Fecha de Asignación</th>
											<th class="text-nowrap text-center">Días de Asignación</th>
											<th class="text-nowrap text-center">Tipo</th>
											<th class="text-nowrap text-center">Herramienta</th>
											<th class="text-nowrap text-center">SLA</th>
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
												{{date('d-m-Y',strtotime($solicitud_data->fecha_solicitud))}}
											</td>
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_asignacion))}}
											</td>
											<td class="text-nowrap text-center">
												{{$diferencia_fechas_procesando[$index]}}
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_tipo_solicitud}}
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_herramienta}}
											</td>
											<td class="text-nowrap text-center">
												{{$slas_data_procesando[$index]->sla_procesando}}
											</td>
											<td class="text-nowrap text-center">
												{{$diferencia_fechas_trabajo_procesando[$index]}}
											</td>
											<td class="text-nowrap text-center">												
												@if( $diferencia_fechas_trabajo_procesando[$index] < $slas_data_procesando[$index]->sla_procesando )
												 	<!-- VERDE -->
													<span style=" padding: 2px 11px; border-radius: 100%;background-color: #00C851;"></span>
												@elseif( $diferencia_fechas_trabajo_procesando[$index] == $slas_data_procesando[$index]->sla_procesando )
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
<div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_requerimientos_carga"  role="dialog">
    <div class="modal-dialog modal-md">    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header" id="modal_header_requerimientos_carga">
	          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Cargar Base FUR</h4>
	        </div>
	        <div class="modal-body" id="modal_text_acciones">
	         	<div class="container-fluid">
	         		<div class="row">
						<div class="col-md-8">
							<label class="control-label">Seleccione Archivo .CSV</label>
							<input id="input-file" type="file" name="file">
						</div>
						<div class="col-md-4" style="padding-top:26px">				
							<a class="btn btn-info btn-block" id="btnCargar" type="submit"> <i class="fa fa-upload"></i> Cargar FUR</a>
						</div>
					</div>
				</div>
			</div>
      </div>      
    </div>
  </div>
 </div>  
 <div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_requerimientos_mostrar"  role="dialog">
    <div class="modal-dialog modal-lg" style="width:85%">    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header" id="modal_header_requerimientos_mostrar">
	          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Requerimientos Registrados</h4>
	        </div>
	        <div class="modal-body" id="modal_text_acciones">
	         	<div class="container-fluid">
	         		<div class="row">
						<div class="table-responsive" >
							<table class="table table-hover" id="tabla_acciones_disponibles">
								<thead>
									<tr>
										<th class="text-nowrap text-center">N°</th>
										<th class="text-nowrap text-center">Código Requerimiento</th>
										<th class="text-nowrap text-center">Acción</th>
										<th class="text-nowrap text-center">Tipo</th>
										<th class="text-nowrap text-center">Cantidad de Usuarios</th>
										<th class="text-nowrap text-center">DNI Usuario</th>
										<th class="text-nowrap text-center">Fecha Ingreso Req.</th>
										<th class="text-nowrap text-center">Fecha Aprobación Req.</th>
										<th class="text-nowrap text-center">Responsable</th>
										<th class="text-nowrap text-center">Estado</th>
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
	          <button type="button" class="btn btn-danger" id="btnAgregarAccionSubmit" data-dismiss="modal"><i class="lnr lnr-users "></i>&nbsp&nbspValidarUsuarios</button>
	        </div>
      </div>      
    </div>
  </div>
 </div>  
@stop