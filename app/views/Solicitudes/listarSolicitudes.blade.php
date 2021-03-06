@extends('Templates/solicitudesTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title">SOLICITUDES/<strong>BUSCAR SOLICITUDES</strong></h3>
		@if (Session::has('message'))
			<div class="alert alert-success">
				
				<i class="fa fa-check-circle"></i> 
				{{ Session::get('message') }}
			</div>
		@endif
		@if (Session::has('error'))
			<div class="alert alert-danger alert-dismissible" role="alert">
				
				<i class="fa fa-times-circle"></i> {{ Session::get('error') }}
			</div>
		@endif
		<!-- OVERVIEW -->
		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Criterios de Búsqueda</h3>
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/solicitudes/buscar_solicitudes','method'=>'get' ,'role'=>'form', 'id'=>'search-form')) }}
				<div class="row">
					<div class="col-md-3">
						{{ Form::label('search_solicitud','Código de Solicitud')}}
						{{ Form::text('search_solicitud',$search_solicitud,array('class'=>'form-control','placeholder'=>'Ingrese número de solicitud','id'=>'search_solicitud')) }}
					</div>
					<div class="col-md-3">
						{{ Form::label('fecha_asignacion_desde','Fecha de asignación desde') }}
						<div id="search_datetimepicker1" class="form-group input-group date @if($errors->first('fecha_asignacion_desde')) has-error has-feedback @endif">
							{{ Form::text('fecha_asignacion_desde',$fecha_asignacion_desde,array('class'=>'form-control','placeholder'=>'Fecha de Asignación Desde')) }}
							<span class="input-group-addon">
		                        <span class="glyphicon glyphicon-calendar"></span>
		                    </span>
						</div>	
					</div>
					<div class="col-md-3">
						{{ Form::label('fecha_asignacion_hasta','Fecha de asignacion hasta') }}
						<div id="search_datetimepicker2" class="form-group input-group date @if($errors->first('fecha_asignacion_hasta')) has-error has-feedback @endif">
							{{ Form::text('fecha_asignacion_hasta',$fecha_asignacion_hasta,array('class'=>'form-control','placeholder'=>'Fecha de Asignación Hasta')) }}
							<span class="input-group-addon">
		                        <span class="glyphicon glyphicon-calendar"></span>
		                    </span>
						</div>	
					</div>
					<div class="col-md-3">
						{{ Form::label('search_tipo_solicitud','Acción')}}
						{{ Form::select('search_tipo_solicitud',array('0'=>'Seleccione')+$tipos_solicitud,$search_tipo_solicitud,array('class'=>'form-control')) }}
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						{{ Form::label('search_estado_solicitud','Estado Solicitud')}}
						{{ Form::select('search_estado_solicitud',array('0'=>'Seleccione')+$estados_solicitud,$search_estado_solicitud,array('class'=>'form-control')) }}
					</div>					
					<div class="col-md-3">
						{{ Form::label('search_sector','Sector')}}
						{{ Form::select('search_sector',array('0'=>'Seleccione')+$sectores,$search_sector,array('class'=>'form-control')) }}
					</div>
					<div class="form-group col-md-2 col-md-offset-2">
						{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
					</div>
					<div class="col-md-2">
						<div class="btn btn-default btn-block" id="btnLimpiar" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
					</div>
				</div>
				{{ Form::close() }}					
			</div>
		</div>
		
		<div class="panel">		
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
										<th class="text-nowrap text-center">Número de Corte</th>
										<th class="text-nowrap text-center">Acción</th>
										<th class="text-nowrap text-center">Estado Solicitud</th>
									</tr>
								</thead>
								@foreach($solicitudes_data as $solicitud_data)
								<tbody>	
									<tr>
										<td class="text-nowrap text-center">
											<a href="{{URL::to('/solicitudes/mostrar_solicitud')}}/{{$solicitud_data->idsolicitud}}">{{$solicitud_data->codigo_solicitud}}</a>
										</td>
										<td class="text-nowrap text-center">
											{{date('d-m-Y',strtotime($solicitud_data->fecha_solicitud))}}
										</td>
										<td class="text-nowrap text-center">
											@if($solicitud_data->fecha_asignacion != null)
												{{date('d-m-Y',strtotime($solicitud_data->fecha_asignacion 	))}}
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
											@if($solicitud_data->nombre_tipo_solicitud != null)
												{{$solicitud_data->nombre_tipo_solicitud }}
											@else
												-
											@endif
										</td>
										<td class="text-nowrap text-center">
											{{$solicitud_data->nombre_estado_solicitud}}
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
		@if($search_solicitud || $fecha_asignacion_desde || $fecha_asignacion_hasta || $search_tipo_solicitud || $search_estado_solicitud || $search_sector)
			{{ $solicitudes_data->appends(array('search_solicitud' => $search_solicitud,'fecha_asignacion_desde'=>$fecha_asignacion_desde,
			'fecha_asignacion_hasta'=>$fecha_asignacion_hasta,'search_tipo_solicitud'=>$search_tipo_solicitud,'search_estado_solicitud' => $search_estado_solicitud,'search_sector' => $search_sector))->links() }}
		@else
			{{ $solicitudes_data->links() }}
		@endif
	</div>
</div>
@stop