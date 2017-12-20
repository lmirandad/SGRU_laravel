@extends('Templates/solicitudesTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>SOLICITUD</strong>/CREACION MANUAL DE NUEVA SOLICITUD</h3>
		@if ($errors->has())
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<p>{{ $errors->first('codigo_solicitud') }}</p>		
				<p>{{ $errors->first('fecha_solicitud') }}</p>
				<p>{{ $errors->first('tipo_solicitud_general') }}</p>
				<p>{{ $errors->first('estado_solicitud') }}</p>
				<p>{{ $errors->first('tipo_solicitud') }}</p>
				<p>{{ $errors->first('asunto') }}</p>
				<p>{{ $errors->first('sector') }}</p>
				<p>{{ $errors->first('canal') }}</p>
				<p>{{ $errors->first('entidad') }}</p>
				<p>{{ $errors->first('herramienta') }}</p>
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
		<!-- OVERVIEW -->
		{{ Form::open(array('url'=>'/solicitudes/submit_crear_solicitud' ,'role'=>'form','id'=>'submit-crear-solicitud')) }}
		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Datos Generales</h3>
			</div>
			<div class="panel-body">				
				<div class="row">
					<div class="col-md-3 @if($errors->first('codigo_solicitud')) has-error has-feedback @endif">
						{{ Form::label('codigo_solicitud','Código de Solicitud')}}
						{{ Form::text('codigo_solicitud',Input::old('codigo_solicitud'),array('class'=>'form-control','placeholder'=>'Ingrese código de solicitud','id'=>'codigo_solicitud')) }}
					</div>
					<div class="col-md-3">
						{{ Form::label('fecha_solicitud','Fecha de solicitud') }}
						<div id="datetimepicker_creacion_solicitud" class="form-group input-group date @if($errors->first('fecha_solicitud')) has-error has-feedback @endif">
							{{ Form::text('fecha_solicitud',Input::old('fecha_solicitud'),array('class'=>'form-control','placeholder'=>'Fecha de Solicitud')) }}
							<span class="input-group-addon">
		                        <span class="glyphicon glyphicon-calendar"></span>
		                    </span>
						</div>	
					</div>
					<div class="col-md-3 @if($errors->first('tipo_solicitud_general')) has-error has-feedback @endif"">
						{{ Form::label('tipo_solicitud_general','Tipo Solicitud')}}
						{{ Form::select('tipo_solicitud_general',array(''=>'Seleccione')+$tipos_solicitud_general,Input::old('tipo_solicitud_general'),array('class'=>'form-control')) }}
					</div>	
					<div class="col-md-3 @if($errors->first('estado_solicitud')) has-error has-feedback @endif">
						{{ Form::label('estado_solicitud','Estado Solicitud')}}
						{{ Form::select('estado_solicitud',array(''=>'Seleccione')+$estados_solicitud,Input::old('estado_solicitud'),array('class'=>'form-control')) }}
					</div>
				</div>
				<div class="row">
					<div class="col-md-3 @if($errors->first('tipo_solicitud')) has-error has-feedback @endif">
						{{ Form::label('tipo_solicitud','Tipo de Accion')}}
						{{ Form::select('tipo_solicitud',array(''=>'Seleccione')+$tipos_solicitud,Input::old('tipo_solicitud'),array('class'=>'form-control')) }}
					</div>
					<div class="col-md-6 @if($errors->first('asunto')) has-error has-feedback @endif">
						{{ Form::label('asunto','Asunto')}}
						{{ Form::text('asunto',Input::old('asunto'),array('class'=>'form-control','placeholder'=>'Ingrese nombre del usuario','id'=>'asunto_detectar')) }}
					</div>
				</div>
						
			</div>
		</div>
		<!-- OVERVIEW -->
		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Selección del Usuario Asignado</h3>
			</div>
			<div class="panel-body">				
				<div class="row">								
					<div class="form-group col-md-3 @if($errors->first('sector')) has-error has-feedback @endif">
						{{ Form::label('sector','Sector:')}}
						{{ Form::select('sector',array(''=>'Seleccione')+$sectores,Input::old('sector'),array('class'=>'form-control','id'=>'slcSector')) }}
					</div>
					<div class="form-group col-md-3 @if($errors->first('canal')) has-error has-feedback @endif"">
						{{ Form::label('canal','Canal:')}}
						{{ Form::select('canal',array(''=>'Seleccione'),Input::old('canal'),array('class'=>'form-control','id'=>'slcCanal')) }}
					</div>	
					<div class="form-group col-md-3 @if($errors->first('entidad')) has-error has-feedback @endif"">
						{{ Form::label('entidad','Entidad:')}}
						{{ Form::select('entidad',array(''=>'Seleccione'),Input::old('entidad'),array('class'=>'form-control','id'=>'slcEntidad')) }}
					</div>
					<div class="col-md-3 @if($errors->first('herramienta')) has-error has-feedback @endif">						
						{{ Form::label('herramienta','Aplicativo:')}}
						{{ Form::select('herramienta',array(''=>'Seleccione') ,Input::old('herramienta'),array('class'=>'form-control','id'=>'slcHerramienta')) }}
					</div>					
				</div>
			</div>
		</div>	

		<div class="row">
			<div class="form-group col-md-2  col-md-offset-8">				
				<a class="btn btn-info btn-block" id="btnCrearSolicitud"> <i class="lnr lnr-plus-circle"></i> Crear</a>
			</div>
			<div class="form-group col-md-2">
				<a class="btn btn-default btn-block" href="{{URL::to('solicitudes/listar_solicitudes')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
			</div>
		</div>
		{{ Form::close() }}		
	</div>
</div>

@stop