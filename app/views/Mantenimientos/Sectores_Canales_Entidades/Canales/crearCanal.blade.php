@extends('Templates/Mantenimientos/canalTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>CREACIÓN DE UN NUEVO CANAL</strong></h3>

		<div class="row">

			@if (Session::has('message'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					{{ Session::get('message') }}
				</div>
			@endif
			@if (Session::has('error'))
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					{{ Session::get('error') }}
				</div>
			@endif
		</div>
		{{ Form::open(array('url'=>'/canales/submit_crear_canal' ,'role'=>'form','id'=>'submit-crear')) }}
		<!-- OVERVIEW -->		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">Datos Generales</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							@if ($errors->has())
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<p>{{ $errors->first('nombre_canal') }}</p>				
								<p>{{ $errors->first('descripcion') }}</p>
								<p>{{ $errors->first('sector') }}</p>
								<p>{{ $errors->first('canal_agrupado') }}</p>
							</div>
							@endif
						</div>
						<div class="row">
							<div class="form-group col-md-3 col-md-offset-2 @if($errors->first('nombre_canal')) has-error has-feedback @endif"">
								{{ Form::label('nombre_canal','Nombre del Canal')}}
								{{ Form::text('nombre_canal',Input::old('nombre_canal'),array('class'=>'form-control','placeholder'=>'Ingrese nombre del canal')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('sector')) has-error has-feedback @endif"">
								{{ Form::label('sector','Sector:')}}
								{{ Form::select('sector',array(''=>'Seleccione')+$sectores,Input::old('sector'),array('class'=>'form-control','id'=>'slcSector')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('canal_agrupado')) has-error has-feedback @endif"">
								{{ Form::label('canal_agrupado','Canal Agrupado:')}}
								{{ Form::select('canal_agrupado',array(''=>'Seleccione'),Input::old('canal_agrupado'),array('class'=>'form-control','id'=>'slcCanalAgrupado')) }}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-9 col-md-offset-2 @if($errors->first('descripcion')) has-error has-feedback @endif"">
								{{ Form::label('descripcion','Descripción:')}}
								{{ Form::textarea('descripcion',Input::old('descripcion'),array('class'=>'form-control','placeholder'=>'Ingrese una descripción','rows'=>5,'style'=>'resize:none','id'=>'slcCanalAgrupado')) }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
		
		<div class="row">
			<div class="form-group col-md-2  col-md-offset-8">				
				<a class="btn btn-info btn-block" id="btnCrearCanal"> <i class="lnr lnr-plus-circle"></i> Crear</a>
			</div>
			<div class="form-group col-md-2">
				<a class="btn btn-default btn-block" href="{{URL::to('entidades_canales_sectores/listar/1')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
			</div>
		</div>
		{{ Form::close() }}		
	</div>
		
				
	</div>
</div>
@stop