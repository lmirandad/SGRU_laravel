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
			@if (Session::has('error'))
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<i class="fa fa-times-circle"></i> {{ Session::get('error') }}
				</div>
			@endif
			@if($user->idrol == 1)
			<!-- OVERVIEW -->
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">Buscar por Usuario</h3>
						</div>
						<div class="panel-body">	
							{{ Form::open(array('url'=>'/principal/buscar_solicitudes_usuario','method'=>'get' ,'role'=>'form')) }}
							<div class="row">
								<div class="col-md-6">
									{{ Form::label('search_usuario','Nombre del Usuario')}}
									{{ Form::text('search_usuario',$search_usuario,array('class'=>'form-control','placeholder'=>'Ingrese nombre del usuario','id'=>'search_usuario')) }}
								</div>
								<div class="form-group col-md-3">
								{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
								</div>
								<div class="col-md-3">
									<div class="btn btn-default btn-block" id="btnLimpiarSector" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
								</div>	
							</div>
							{{Form::close()}}
						</div>
					</div>
				</div>
			</div>
			@endif
			<div class="panel panel-headline">
				<div class="panel-heading">
					<h3 class="panel-title">Número de Solicitudes</h3>
					<p class="panel-subtitle">Fecha Actual: {{date('d-m-Y')}}</p>
				</div>
				<div class="panel-body">	
					<div class="row">
						<div class="col-md-2">
							<div class="metric">
								@if($user->idrol == 1)
									@if($origen == 1)
										<a href="{{URL::to('principal/mostrar_solicitudes_estado')}}/1"><span class="icon" style="background-color:#5cb85c;"><i class="lnr lnr-thumbs-up"></i></span></a>
									@else
										<a href="{{URL::to('principal/mostrar_solicitudes_estado_usuario')}}/1/{{$idusuario}}"><span class="icon" style="background-color:#5cb85c;"><i class="lnr lnr-thumbs-up"></i></span></a>
									@endif
								@else
									<a href="{{URL::to('principal/mostrar_solicitudes_estado_usuario')}}/1/{{$idusuario}}"><span class="icon" style="background-color:#5cb85c;"><i class="lnr lnr-thumbs-up"></i></span></a>
								@endif
								<p>
									<span class="number">{{$solicitudes_atendidos}}</span>
									<span class="title">Tickets Atendidos</span>
								</p>
							</div>
						</div>
						<div class="col-md-2">
							<div class="metric">
								@if($user->idrol == 1)
									@if($origen == 1)
										<a href="{{URL::to('principal/mostrar_solicitudes_estado')}}/2"><span class="icon" style="background-color:#F89406;"><i class="fa fa-eye"></i></span></a>
									@else
										<a href="{{URL::to('principal/mostrar_solicitudes_estado_usuario')}}/2/{{$idusuario}}"><span class="icon" style="background-color:#F89406;"><i class="fa fa-eye"></i></span></a>
									@endif
								@else
									<a href="{{URL::to('principal/mostrar_solicitudes_estado_usuario')}}/2/{{$idusuario}}"><span class="icon" style="background-color:#F89406;"><i class="fa fa-eye"></i></span></a>
								@endif
								<p>
									<span class="number">{{$solicitudes_cerrados}}</span>
									<span class="title">Tickets Cerrados con Obs.</span>
								</p>
							</div>
						</div>
						<div class="col-md-2">
							<div class="metric">
								@if($user->idrol == 1)
									@if($origen == 1)
										<a href="{{URL::to('/principal/mostrar_solicitudes_estado')}}/3"><span class="icon" style="background-color:#0088CC;"><i class="lnr lnr-warning"></i></span></a>
									@else
										<a href="{{URL::to('/principal/mostrar_solicitudes_estado_usuario')}}/3/{{$idusuario}}"><span class="icon" style="background-color:#0088CC;"><i class="lnr lnr-warning"></i></span></a>
									@endif
								@else
									<a href="{{URL::to('/principal/mostrar_solicitudes_estado_usuario')}}/3/{{$idusuario}}"><span class="icon" style="background-color:#0088CC;"><i class="lnr lnr-warning"></i></span></a>
								@endif
								<p>
									<span class="number">{{$solicitudes_pendientes}}</span>
									<span class="title">Tickets Pendientes</span>
								</p>
							</div>
						</div>
						<div class="col-md-2">
							<div class="metric">
								@if($user->idrol == 1)
									@if($origen == 1)
										<a href="{{URL::to('principal/mostrar_solicitudes_estado')}}/4"><span class="icon" style="background-color:#0088CC;"><i class="lnr lnr-history"></i></span></a>
									@else
										<a href="{{URL::to('principal/mostrar_solicitudes_estado_usuario')}}/4/{{$idusuario}}"><span class="icon" style="background-color:#0088CC;"><i class="lnr lnr-history"></i></span></a>
									@endif
								@else
									<a href="{{URL::to('principal/mostrar_solicitudes_estado_usuario')}}/4/{{$idusuario}}"><span class="icon" style="background-color:#0088CC;"><i class="lnr lnr-history"></i></span></a>
								@endif
								<p>
									<span class="number">{{$solicitudes_procesando}}</span>
									<span class="title">Tickets Procesando</span>
								</p>
							</div>
						</div>
						<div class="col-md-2">
							<div class="metric">
								@if($user->idrol == 1)
									@if($origen == 1)
										<a href="{{URL::to('principal/mostrar_solicitudes_estado')}}/5"><span class="icon" style="background-color:#d9534f;"><i class="fa fa-hand-paper-o"></i></span></a>
									@else
										<a href="{{URL::to('principal/mostrar_solicitudes_estado_usuario')}}/5/{{$idusuario}}"><span class="icon" style="background-color:#d9534f;"><i class="fa fa-hand-paper-o"></i></span></a>
									@endif
								@else
									<a href="{{URL::to('principal/mostrar_solicitudes_estado_usuario')}}/5/{{$idusuario}}"><span class="icon" style="background-color:#d9534f;"><i class="fa fa-hand-paper-o"></i></span></a>
								@endif
								<p>
									<span class="number">{{$solicitudes_rechazadas}}</span>
									<span class="title">Tickets Rechazados</span>
								</p>
							</div>
						</div>
						<div class="col-md-2">
							<div class="metric">
								@if($user->idrol == 1)
									@if($origen == 1)
										<a href="{{URL::to('principal/mostrar_solicitudes_estado')}}/6"><span class="icon" style="background-color:#d9534f;"><i class="lnr lnr-thumbs-down"></i></span></a>
									@else
										<a href="{{URL::to('principal/mostrar_solicitudes_estado_usuario')}}/6/{{$idusuario}}"><span class="icon" style="background-color:#d9534f;"><i class="lnr lnr-thumbs-down"></i></span></a>
									@endif
								@else
									<a href="{{URL::to('principal/mostrar_solicitudes_estado_usuario')}}/6/{{$idusuario}}"><span class="icon" style="background-color:#d9534f;"><i class="lnr lnr-thumbs-down"></i></span></a>
								@endif
								<p>
									<span class="number">{{$solicitudes_anuladas}}</span>
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
											<th class="text-nowrap text-center">Código Solicitud</th>
											<th class="text-nowrap text-center">Fecha de Solicitud</th>
											<th class="text-nowrap text-center">Fecha de Asignación</th>
											<th class="text-nowrap text-center">Días de Asignación</th>
											<th class="text-nowrap text-center">Tipo de Solicitud</th>
											<th class="text-nowrap text-center">Estado Solicitud</th>
											<th class="text-nowrap text-center">Herramienta</th>
											<th class="text-nowrap text-center">SLA (N° Días)</th>
											<th class="text-nowrap text-center">SEMÁFORO</th>
										</tr>
									</thead>
									@foreach($solicitudes_data as $index => $solicitud_data)
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
												{{$diferencia_fechas[$index]}}
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_tipo_solicitud}}
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_estado_solicitud}}
											</td>
											<td class="text-nowrap text-center">
												{{$solicitud_data->nombre_herramienta}}
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idestado_solicitud == 3) <!-- PENDIENTE -->
													{{$slas_data[$index]->sla_pendiente}}
												@elseif ($solicitud_data->idestado_solicitud == 4) <!-- PROCESANDO -->
													{{$slas_data[$index]->sla_procesando}}
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($solicitud_data->idestado_solicitud == 3) <!-- PENDIENTE -->
													@if( $diferencia_fechas_trabajo[$index] < $slas_data[$index]->sla_pendiente )
													 	<!-- VERDE -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #00C851;"></span>
													@elseif( $diferencia_fechas_trabajo[$index] == $slas_data[$index]->sla_pendiente )
														<!-- AMBAR -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ffb74d;"></span>
													@else
														<!-- ROJO -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ff4444;"></span>
													@endif
												@elseif ($solicitud_data->idestado_solicitud == 4) <!-- PROCESANDO -->
													@if( $diferencia_fechas_trabajo[$index] < $slas_data[$index]->sla_procesando )
													 	<!-- VERDE -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #00C851;"></span>
													@elseif( $diferencia_fechas_trabajo[$index] == $slas_data[$index]->sla_procesando )
														<!-- AMBAR -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ffb74d;"></span>
													@else
														<!-- ROJO -->
														<span style=" padding: 2px 11px; border-radius: 100%;background-color: #ff4444;"></span>
													@endif
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
@stop