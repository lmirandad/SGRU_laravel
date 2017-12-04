@extends('Templates/Mantenimientos/herramientasTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>APLICATIVO: </strong>{{$herramienta->nombre}}</h3>

		<div class="row">

			@if (Session::has('message'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-check-circle"></i> 
					{{ Session::get('message') }}
				</div>
			@endif
			@if (Session::has('error'))
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-times-circle"></i> 
					{{ Session::get('error') }}
				</div>
			@endif
		</div>
		{{ Form::open(array('url'=>'/herramientas/submit_editar_herramienta' ,'role'=>'form','id'=>'submit-editar')) }}
		{{ Form::hidden('herramienta_id', $herramienta->idherramienta, array('id'=>'herramienta_id')) }}	
		<!-- OVERVIEW -->		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">Datos del Aplicativo</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							@if ($errors->has())
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<p>{{ $errors->first('nombre_herramienta') }}</p>		
								<p>{{ $errors->first('descripcion') }}</p>
								<p>{{ $errors->first('flag_seguridad') }}</p>
								<p>{{ $errors->first('denominacion_herramienta') }}</p>
							</div>
							@endif
						</div>
						<div class="row">
							<div class="form-group col-md-3 col-md-offset-1 @if($errors->first('nombre_herramienta')) has-error has-feedback @endif"">
								{{ Form::label('nombre_herramienta','Nombre del Aplicativo')}}
								{{ Form::text('nombre_herramienta',$herramienta->nombre,array('class'=>'form-control','placeholder'=>'Ingrese nombre del aplicativo')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('flag_seguridad')) has-error has-feedback @endif"">
								{{ Form::label('flag_seguridad','Valida Seguridad:')}}
								{{ Form::select('flag_seguridad', [''=>'Seleccione','0'=>'No','1'=>'Si','2'=>'Por Validar'],$herramienta->flag_seguridad,['class' => 'form-control']) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('denominacion_herramienta')) has-error has-feedback @endif">
								{{ Form::label('denominacion_herramienta','Categoría Aplicativo:')}}
								{{ Form::select('denominacion_herramienta',array(''=>'Seleccione')+$denominaciones,$herramienta->iddenominacion_herramienta,array('class'=>'form-control')) }}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-9 col-md-offset-1 @if($errors->first('descripcion')) has-error has-feedback @endif"">
								{{ Form::label('descripcion','Descripción:')}}
								{{ Form::textarea('descripcion',$herramienta->descripcion,array('class'=>'form-control','placeholder'=>'Ingrese una descripción','rows'=>5,'style'=>'resize:none')) }}
							</div>
						</div>						
					</div>
				</div>
			</div>
		</div>		
		
		<div class="row">
			<div class="form-group col-md-2  col-md-offset-8">				
				<a class="btn btn-info btn-block" id="btnEditarHerramienta"> <i class="fa fa-floppy-o"></i> Editar</a>
			</div>
			<div class="form-group col-md-2">
				<a class="btn btn-default btn-block" href="{{URL::to('herramientas/listar_herramientas')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
			</div>
		</div>
		{{ Form::close() }}		
	</div>
		
				
	</div>
</div>
@stop