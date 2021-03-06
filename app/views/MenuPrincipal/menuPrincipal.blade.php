@extends('Templates/menuPrincipalTemplate')
@section('content')
<div class="main">
	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container-fluid">
			<h3 class="page-title"><strong>ATENCIÓN DE TICKETS (SOLICITUDES)</strong> </h3>
			@if (Session::has('message'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-check-circle"></i> 
					{{ Session::get('message') }}
				</div>
			@endif
			@if (Session::has('info'))
				<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-check-circle"></i> 
					{{ Session::get('info') }}
				</div>
			@endif
			@if (Session::has('error'))
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<i class="fa fa-times-circle"></i> {{ Session::get('error') }}
				</div>
			@endif
			@if($user->idrol == 1)
			<!-- OVERVIEW -->
			<div class="row">
				<div class="col-md-8">
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">Buscar por Usuario</h3>
						</div>
						<div class="panel-body">	
							{{ Form::open(array('url'=>'/principal/buscar_solicitudes_usuario','method'=>'get' ,'role'=>'form')) }}
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('search_usuario','Nombre Usuario')}}
									{{ Form::text('search_usuario',$search_usuario,array('class'=>'form-control','placeholder'=>'Username','id'=>'search_usuario')) }}
								</div>
								<div class="form-group col-md-3">	
									{{ Form::label('search_fecha','Mes') }}
									<div id="datetimepicker1" class="form-group input-group date @if($errors->first('fecha_nacimiento')) has-error has-feedback @endif">
										{{ Form::text('search_fecha',$search_fecha,array('class'=>'form-control','placeholder'=>'Mes Solicitud','id'=>'search_fecha')) }}
										<span class="input-group-addon">
					                        <span class="glyphicon glyphicon-calendar"></span>
					                    </span>
									</div>	
								</div>		
								<div class="form-group col-md-3">
								{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
								</div>
								<div class="col-md-3">
									<div class="btn btn-default btn-block" id="btnLimpiarNombre" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
								</div>	
							</div>
							{{Form::close()}}
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title" style="text-align:center">Vistas Resumen General</h3>
						</div>
						<div class="panel-body">
							<div class="row" >														
								<div class="form-group col-md-12" style="margin-top:-20px">									
									<a class="btn btn-success btn-block"  href="{{URL::to('principal/resumen_usuarios')}}"><i class="fa fa-users"></i>&nbspTickets por Usuarios</a>		
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
			<div class="panel panel-headline">
				<div class="panel-heading">
					<h3 class="panel-title">Número de Solicitudes: 
						@if($usuario_busqueda != null) <i>{{$usuario_busqueda}}</i> @endif
					</h3> 
					
					<p class="panel-subtitle"> 
						@if($fecha_busqueda != null) Mes Búsqueda: <strong>{{$fecha_busqueda}} </strong>
						@else	Mes Actual: <strong>{{date('m-Y')}}</strong>
						@endif
					</p>						
				</div>
				<div class="panel-body">	
					<div class="row">
						<div class="col-md-2">
							<div class="metric">
								@if($user->idrol == 1)
									<a href="#" onclick="return mostrar_solicitudes(event,1)"><span class="icon" style="background-color:#5cb85c;"><i class="lnr lnr-thumbs-up"></i></span></a>
								@endif
								<p>
									<span class="number">{{count($solicitudes_atendidos)}}</span>
									<span class="title">Tickets Atendidos</span>
								</p>
							</div>
						</div>
						<div class="col-md-2">
							<div class="metric">
								@if($user->idrol == 1)
									<a href="#" onclick="return mostrar_solicitudes(event,2)"><span class="icon" style="background-color:#F89406;"><i class="fa fa-eye"></i></span></a>
								@endif
								<p>
									<span class="number">{{count($solicitudes_cerrados)}}</span>
									<span class="title">Tickets Cerrados con Obs.</span>
								</p>
							</div>
						</div>
						<div class="col-md-2">
							<div class="metric">
								@if($user->idrol == 1)
									<a href="#" onclick="return mostrar_solicitudes(event,3)"><span class="icon" style="background-color:#ffeb3b;"><i class="lnr lnr-warning"></i></span></a>
								@endif
								<p>
									<span class="number">{{count($solicitudes_pendientes)}}</span>
									<span class="title">Tickets Pendientes</span>
								</p>
							</div>
						</div>
						<div class="col-md-2">
							<div class="metric">
								@if($user->idrol == 1)
									<a href="#" onclick="return mostrar_solicitudes(event,4)"><span class="icon" style="background-color:#0088CC;"><i class="lnr lnr-history"></i></span></a>
								@endif
								<p>
									<span class="number">{{count($solicitudes_procesando)}}</span>
									<span class="title">Tickets Procesando</span>
								</p>
							</div>
						</div>
						<div class="col-md-2">
							<div class="metric">
								@if($user->idrol == 1)
									<a href="#" onclick="return mostrar_solicitudes(event,5)"><span class="icon" style="background-color:#d9534f;"><i class="fa fa-hand-paper-o"></i></span></a>
								@endif
								<p>
									<span class="number">{{count($solicitudes_rechazadas)}}</span>
									<span class="title">Tickets Rechazados</span>
								</p>
							</div>
						</div>
						<div class="col-md-2">
							<div class="metric">
								@if($user->idrol == 1)
									<a  href="#" onclick="return mostrar_solicitudes(event,6)"><span class="icon" style="background-color:#d9534f;"><i class="lnr lnr-thumbs-down"></i></span></a>
								@endif
								<p>
									<span class="number">{{count($solicitudes_anuladas)}}</span>
									<span class="title">Tickets Anulados</span>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-headline">
				<div class="panel-heading">
					<h3 class="panel-title">Listado de Solicitudes</h3>
				</div>
				<div class="panel-body">	
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-nowrap text-center">Código<br>Solicitud</th>
											<th class="text-nowrap text-center">Fecha de<br>Solicitud</th>
											<th class="text-nowrap text-center">Fecha de<br>Asignación</th>
											<th class="text-nowrap text-center">Número de<br>Corte</th>
											<th class="text-nowrap text-center">Días de<br>Asignación</th>
											<th class="text-nowrap text-center">Acción</th>
											<th class="text-nowrap text-center">Estado</th>
											<th class="text-nowrap text-center">Herramienta</th>
											<th class="text-nowrap text-center">SLA</th>
											<th class="text-nowrap text-center">Días<br>Laborables</th>
											<th class="text-nowrap text-center">SEMÁFORO</th>
										</tr>
									</thead>
									<tbody id="estado1" style="display:none">										
									@foreach($solicitudes_atendidos as $index => $solicitud_data)
										<tr>
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/solicitudes/mostrar_solicitud')}}/{{$solicitud_data->idsolicitud}}">{{$solicitud_data->codigo_solicitud}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_solicitud))}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->fecha_asignacion != null)
													{{date('d-m-Y',strtotime($solicitud_data->fecha_asignacion))}}
												@else
													NO ASIGNADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->numero_corte != null)
													CORTE N° {{$solicitud_data->numero_corte}}
												@else
													SOLICITUD MANUAL
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->fecha_asignacion != null)
													{{$diferencia_fechas_atendidos[$index]}}
												@else
													-
												@endif												
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->nombre_tipo_solicitud != null)
													{{$solicitud_data->nombre_tipo_solicitud}}
												@else
													-
												@endif												
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_estado_solicitud}}
												@if($solicitud_data->ticket_reasignado == 1)
													<font color="red"><strong><br>(reasignado)</strong></font>
												@endif
											</td>											
											<td class="text-nowrap text-center">
												@if($solicitud_data->idherramienta != null)
													{{$solicitud_data->nombre_herramienta}}
												@else
													NO DETECTADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												-
											</td>
											<td class="text-nowrap text-center">
												-
											</td>
											<td class="text-nowrap text-center">
												-
											</td>
										</tr>
									@endforeach
									</tbody>
									<tbody id="estado2" style="display:none">	
									@foreach($solicitudes_cerrados as $index => $solicitud_data)
										<tr>
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/solicitudes/mostrar_solicitud')}}/{{$solicitud_data->idsolicitud}}">{{$solicitud_data->codigo_solicitud}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_solicitud))}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->fecha_asignacion != null)
													{{date('d-m-Y',strtotime($solicitud_data->fecha_asignacion))}}
												@else
													NO ASIGNADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->numero_corte != null)
													CORTE N° {{$solicitud_data->numero_corte}}
												@else
													SOLICITUD MANUAL
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->fecha_asignacion != null)
													{{$diferencia_fechas_cerrados[$index]}}
												@else
													-
												@endif												
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->nombre_tipo_solicitud != null)
													{{$solicitud_data->nombre_tipo_solicitud}}
												@else
													-
												@endif												
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_estado_solicitud}}
												@if($solicitud_data->ticket_reasignado == 1)
													<font color="red"><strong><br>(reasignado)</strong></font>
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idherramienta != null)
													{{$solicitud_data->nombre_herramienta}}
												@else
													NO DETECTADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												-
											</td>
											<td class="text-nowrap text-center">
												-
											</td>
											<td class="text-nowrap text-center">
												-
											</td>
										</tr>
									@endforeach
									</tbody>
									<tbody id="estado3" style="display:none">
									@foreach($solicitudes_pendientes as $index => $solicitud_data)
										<tr>
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/solicitudes/mostrar_solicitud')}}/{{$solicitud_data->idsolicitud}}">{{$solicitud_data->codigo_solicitud}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_solicitud))}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->fecha_asignacion != null)
													{{date('d-m-Y',strtotime($solicitud_data->fecha_asignacion))}}
												@else
													NO ASIGNADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->numero_corte != null)
													CORTE N° {{$solicitud_data->numero_corte}}
												@else
													SOLICITUD MANUAL
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->fecha_asignacion != null)
													{{$diferencia_fechas_pendiente[$index]}}
												@else
													-
												@endif												
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->nombre_tipo_solicitud != null)
													{{$solicitud_data->nombre_tipo_solicitud}}
												@else
													-
												@endif												
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_estado_solicitud}}
												@if($solicitud_data->ticket_reasignado == 1)
													<font color="red"><strong><br>(reasignado)</strong></font>
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idherramienta != null)
													{{$solicitud_data->nombre_herramienta}}
												@else
													NO DETECTADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idestado_solicitud == 3) <!-- PENDIENTE -->
													{{$slas_data_pendiente[$index]->sla_pendiente}}
												@elseif ($solicitud_data->idestado_solicitud == 4) <!-- PROCESANDO -->
													{{$slas_data_pendiente[$index]->sla_procesando}}
												@endif
											</td>
											<td class="text-nowrap text-center">
												{{$diferencia_fechas_trabajo_pendiente[$index]}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idestado_solicitud == 3) <!-- PENDIENTE -->
													@if($diferencia_fechas_trabajo_pendiente[$index] < $slas_data_pendiente[$index]->sla_pendiente - 1 )
													 	<!-- VERDE -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #00C851;"></span>
													@elseif( $slas_data_pendiente[$index]->sla_pendiente - $diferencia_fechas_trabajo_pendiente[$index] <= 1 && $slas_data_pendiente[$index]->sla_pendiente - $diferencia_fechas_trabajo_pendiente[$index] >=0  )
														<!-- AMBAR -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ffb74d;"></span>
													@else
														<!-- ROJO -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ff4444;"></span>
													@endif
												@elseif ($solicitud_data->idestado_solicitud == 4) <!-- PROCESANDO -->
													@if( $diferencia_fechas_trabajo_pendiente[$index] < $slas_data_pendiente[$index]->sla_procesando - 1 )
													 	<!-- VERDE -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #00C851;"></span>
													@elseif( $slas_data_pendiente[$index]->sla_procesando - $diferencia_fechas_trabajo_pendiente[$index] <= 1 && $slas_data_pendiente[$index]->sla_procesando - $diferencia_fechas_trabajo_pendiente[$index] >=0 )
														<!-- AMBAR -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ffb74d;"></span>
													@else
														<!-- ROJO -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ff4444;"></span>
													@endif
												@endif
											</td>
										</tr>
									@endforeach
									</tbody>
									<tbody id="estado4" style="display:none">	
									@foreach($solicitudes_procesando as $index => $solicitud_data)
										<tr>
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/solicitudes/mostrar_solicitud')}}/{{$solicitud_data->idsolicitud}}">{{$solicitud_data->codigo_solicitud}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_solicitud))}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->fecha_asignacion != null)
													{{date('d-m-Y',strtotime($solicitud_data->fecha_asignacion))}}
												@else
													NO ASIGNADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->numero_corte != null)
													CORTE N° {{$solicitud_data->numero_corte}}
												@else
													SOLICITUD MANUAL
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->fecha_asignacion != null)
													{{$diferencia_fechas_procesando[$index]}}
												@else
													-
												@endif												
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->nombre_tipo_solicitud != null)
													{{$solicitud_data->nombre_tipo_solicitud}}
												@else
													-
												@endif												
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_estado_solicitud}}
												@if($solicitud_data->ticket_reasignado == 1)
													<font color="red"><strong><br>(reasignado)</strong></font>
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idherramienta != null)
													{{$solicitud_data->nombre_herramienta}}
												@else
													NO DETECTADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idestado_solicitud == 3) <!-- PENDIENTE -->
													{{$slas_data_procesando[$index]->sla_pendiente}}
												@elseif ($solicitud_data->idestado_solicitud == 4) <!-- PROCESANDO -->
													{{$slas_data_procesando[$index]->sla_procesando}}
												@endif
											</td>
											<td class="text-nowrap text-center">
												{{$diferencia_fechas_trabajo_procesando[$index]}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idestado_solicitud == 3) <!-- PENDIENTE -->
													@if( $diferencia_fechas_trabajo_procesando[$index] < $slas_data_procesando[$index]->sla_pendiente - 1 )														
													 	<!-- VERDE -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #00C851;"></span>
													@elseif( $slas_data_procesando[$index]->sla_pendiente - $diferencia_fechas_trabajo_procesando[$index] <= 1 && $slas_data_procesando[$index]->sla_pendiente - $diferencia_fechas_trabajo_procesando[$index] >=0 )
														<!-- AMBAR -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ffb74d;"></span>
													@else
														<!-- ROJO -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ff4444;"></span>
													@endif
												@elseif ($solicitud_data->idestado_solicitud == 4) <!-- PROCESANDO -->
													@if( $diferencia_fechas_trabajo_procesando[$index] < $slas_data_procesando[$index]->sla_procesando - 1 )
													 	<!-- VERDE -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #00C851;"></span>
													@elseif( $slas_data_procesando[$index]->sla_procesando - $diferencia_fechas_trabajo_procesando[$index] <=1 &&  $slas_data_procesando[$index]->sla_procesando - $diferencia_fechas_trabajo_procesando[$index] >=0 )
														<!-- AMBAR -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ffb74d;"></span>
													@else
														<!-- ROJO -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ff4444;"></span>
													@endif
												@endif
											</td>
										</tr>
									@endforeach										
									</tbody>
									<tbody id="estado5" style="display:none">	
									@foreach($solicitudes_rechazadas as $index => $solicitud_data)
										<tr>
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/solicitudes/mostrar_solicitud')}}/{{$solicitud_data->idsolicitud}}">{{$solicitud_data->codigo_solicitud}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_solicitud))}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->fecha_asignacion != null)
													{{date('d-m-Y',strtotime($solicitud_data->fecha_asignacion))}}
												@else
													NO ASIGNADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->numero_corte != null)
													CORTE N° {{$solicitud_data->numero_corte}}
												@else
													SOLICITUD MANUAL
												@endif
											</td>
											<td class="text-nowrap text-center">
												-												
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->nombre_tipo_solicitud != null)
													{{$solicitud_data->nombre_tipo_solicitud}}
												@else
													-
												@endif												
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_estado_solicitud}}
												@if($solicitud_data->ticket_reasignado == 1)
													<font color="red"><strong><br>(reasignado)</strong></font>
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idherramienta != null)
													{{$solicitud_data->nombre_herramienta}}
												@else
													NO DETECTADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												-
											</td>
											<td class="text-nowrap text-center">
												-
											</td>
											<td class="text-nowrap text-center">
												-
											</td>
										</tr>
									@endforeach
									</tbody>
									<tbody id="estado6" style="display:none">	
									@foreach($solicitudes_anuladas as $index => $solicitud_data)
										<tr>
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/solicitudes/mostrar_solicitud')}}/{{$solicitud_data->idsolicitud}}">{{$solicitud_data->codigo_solicitud}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{date('d-m-Y',strtotime($solicitud_data->fecha_solicitud))}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->fecha_asignacion != null)
													{{date('d-m-Y',strtotime($solicitud_data->fecha_asignacion))}}
												@else
													NO ASIGNADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->numero_corte != null)
													CORTE N° {{$solicitud_data->numero_corte}}
												@else
													SOLICITUD MANUAL
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->fecha_asignacion != null)
													{{$diferencia_fechas_anuladas[$index]}}
												@else
													-
												@endif												
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->nombre_tipo_solicitud != null)
													{{$solicitud_data->nombre_tipo_solicitud}}
												@else
													-
												@endif												
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_estado_solicitud}}
												@if($solicitud_data->ticket_reasignado == 1)
													<font color="red"><strong><br>(reasignado)</strong></font>
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idherramienta != null)
													{{$solicitud_data->nombre_herramienta}}
												@else
													NO DETECTADO
												@endif
											</td>
											<td class="text-nowrap text-center">
												-
											</td>
											<td class="text-nowrap text-center">
												-
											</td>
											<td class="text-nowrap text-center">
												-
											</td>
										</tr>
									@endforeach
									</tbody>									
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
@stop