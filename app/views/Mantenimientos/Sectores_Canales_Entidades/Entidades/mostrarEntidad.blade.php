@extends('Templates/Mantenimientos/entidadTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>ENTIDAD: </strong> {{$entidad->nombre}}</h3>

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
		{{ Form::hidden('entidad_id', $entidad->identidad, array('id'=>'entidad_id')) }}	
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
								<p>{{ $errors->first('nombre_entidad') }}</p>	
								<p>{{ $errors->first('codigo_enve') }}</p>	
								<p>{{ $errors->first('sector') }}</p>
								<p>{{ $errors->first('canal') }}</p>
								<p>{{ $errors->first('descripcion') }}</p>
							</div>
							@endif
						</div>
						<div class="row">
							<div class="form-group col-md-3 @if($errors->first('nombre_entidad')) has-error has-feedback @endif"">
								{{ Form::label('nombre_entidad','Nombre de la Entidad')}}
								{{ Form::text('nombre_entidad',$entidad->nombre,array('class'=>'form-control','placeholder'=>'Ingrese de la entidad','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('nombre_entidad')) has-error has-feedback @endif"">
								{{ Form::label('codigo_enve','Código Entidad')}}
								{{ Form::text('codigo_enve',$entidad->codigo_enve,array('class'=>'form-control','placeholder'=>'Ingrese código de entidad','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('sector')) has-error has-feedback @endif"">
								{{ Form::label('sector','Sector:')}}
								{{ Form::select('sector',array(''=>'Seleccione')+$sectores,$canal_entidad->idsector,array('class'=>'form-control','id'=>'slcSector','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('canal')) has-error has-feedback @endif"">
								{{ Form::label('canal','Canal:')}}
								{{ Form::select('canal',array(''=>'Seleccione')+$canales,$entidad->idcanal,array('class'=>'form-control','id'=>'slcCanal','disabled'=>'disabled')) }}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12 @if($errors->first('descripcion')) has-error has-feedback @endif"">
								{{ Form::label('descripcion','Descripción:')}}
								{{ Form::textarea('descripcion',$entidad->descripcion,array('class'=>'form-control','placeholder'=>'Ingrese una descripción','rows'=>5,'style'=>'resize:none','disabled'=>'disabled')) }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
		
		<div class="row">
			@if($user->idrol == 1)
				@if($entidad->deleted_at)
					{{ Form::open(array('url'=>'entidades/submit_habilitar_entidad', 'role'=>'form','id'=>'habilitar_entidad')) }}
						{{ Form::hidden('entidad_id', $entidad->identidad) }}
						<div class="form-group col-md-2">
							{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-up"></span> Habilitar', array('id'=>'submit-habilitar-entidad', 'class' => 'btn btn-success btn-block')) }}
						</div>
					{{ Form::close() }}
				@else
					{{ Form::open(array('url'=>'entidades/submit_inhabilitar_entidad', 'role'=>'form','id'=>'inhabilitar_entidad')) }}
						{{ Form::hidden('entidad_id', $entidad->identidad) }}
						<div class="form-group col-md-2">
							{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-down"></span> Inhabilitar', array('id'=>'submit-inhabilitar-entidad', 'class' => 'btn btn-danger btn-block')) }}
						</div>
					{{ Form::close() }}
				@endif
			@endif
			<div class="form-group col-md-2 @if($user->idrol==1) col-md-offset-8 @else col-md-offset-10 @endif">
				<a class="btn btn-default btn-block" href="{{URL::to('entidades_canales_sectores/listar/3')}}"><i class="lnr lnr-arrow-left"></i>&nbspSalir</a>				
			</div>
		</div>	
	</div>
		
				
	</div>
</div>
@stop