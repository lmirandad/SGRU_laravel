@extends('Templates/solicitudesTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title">SOLICITUDES</h3>
		@if (Session::has('message'))
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				{{ Session::get('message') }}
			</div>
		@endif
		@if (Session::has('error'))
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
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
						{{ Form::text('search_solicitud',$search_solicitud,array('class'=>'form-control','placeholder'=>'Ingrese nombre del usuario','id'=>'search_solicitud')) }}
					</div>
					<div class="col-md-3">
						{{ Form::label('fecha_solicitud','Fecha de solicitud') }}
						<div id="datetimepicker1" class="form-group input-group date @if($errors->first('fecha_solicitud')) has-error has-feedback @endif">
							{{ Form::text('fecha_solicitud',Input::old('fecha_solicitud'),array('class'=>'form-control','placeholder'=>'Fecha de Solicitud')) }}
							<span class="input-group-addon">
		                        <span class="glyphicon glyphicon-calendar"></span>
		                    </span>
						</div>	
					</div>
					<div class="col-md-3">
						{{ Form::label('search_tipo_solicitud','Tipo de Solicitud')}}
						{{ Form::select('search_tipo_solicitud',array('0'=>'Seleccione')+$tipos_solicitud,$search_tipo_solicitud,array('class'=>'form-control')) }}
					</div>
					<div class="col-md-3">
						{{ Form::label('search_estado_solicitud','Estado Solicitud')}}
						{{ Form::select('search_estado_solicitud',array('0'=>'Seleccione')+$estados_solicitud,$search_estado_solicitud,array('class'=>'form-control')) }}
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-2 col-md-offset-8">
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
										<th class="text-nowrap text-center">Tipo de Solicitud</th>
										<th class="text-nowrap text-center">Estado Solicitud</th>
										<th class="text-nowrap text-center">Ver bitácora</th>
									</tr>
								</thead>
								<tbody>	
								
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
@stop