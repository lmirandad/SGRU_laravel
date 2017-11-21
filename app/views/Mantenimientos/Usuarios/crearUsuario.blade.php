@extends('Templates/Mantenimientos/usuariosTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>CREACIÓN DE NUEVO USUARIO</strong></h3>

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
		{{ Form::open(array('url'=>'/usuarios/submit_crear_usuario' ,'role'=>'form','id'=>'submit-crear')) }}
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
								<p>{{ $errors->first('username') }}</p>				
								<p>{{ $errors->first('nombre') }}</p>
								<p>{{ $errors->first('apellido_paterno') }}</p>
								<p>{{ $errors->first('apellido_materno') }}</p>
								<p>{{ $errors->first('tipo_doc_identidad') }}</p>
								<p>{{ $errors->first('documento_identidad') }}</p>
								<p>{{ $errors->first('genero') }}</p>
								<p>{{ $errors->first('fecha_nacimiento') }}</p>
								<p>{{ $errors->first('idrol') }}</p>
								<p>{{ $errors->first('genero') }}</p>
							</div>
							@endif
						</div>
						<div class="row">
							<div class="form-group col-md-3 @if($errors->first('username')) has-error has-feedback @endif"">
								{{ Form::label('username','Nombre del Usuario')}}
								{{ Form::text('username',Input::old('username'),array('class'=>'form-control','placeholder'=>'Ingrese nombre del usuario')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('nombre')) has-error has-feedback @endif"">
								{{ Form::label('nombre','Nombres')}}
								{{ Form::text('nombre',Input::old('nombre'),array('class'=>'form-control','placeholder'=>'Ingrese nombres')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('apellido_paterno')) has-error has-feedback @endif"">
								{{ Form::label('apellido_paterno','Apellido Paterno')}}
								{{ Form::text('apellido_paterno',Input::old('apellido_paterno'),array('class'=>'form-control','placeholder'=>'Ingrese apellido paterno')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('apellido_materno')) has-error has-feedback @endif"">
								{{ Form::label('apellido_materno','Apellido Materno')}}
								{{ Form::text('apellido_materno',Input::old('apellido_materno'),array('class'=>'form-control','placeholder'=>'Ingrese apellido materno')) }}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-3 @if($errors->first('tipo_doc_identidad')) has-error has-feedback @endif">
								{{ Form::label('tipo_doc_identidad','Tipo Documento de Identidad')}}
								{{ Form::select('tipo_doc_identidad',array(''=>'Seleccione')+$tipo_doc_identidades,Input::old('tipo_doc_identidad'),array('class'=>'form-control','id'=>'create_tipo_doc_identidad')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('documento_identidad')) has-error has-feedback @endif">
								{{ Form::label('documento_identidad','Número de Identidad')}}
								{{ Form::text('documento_identidad',Input::old('documento_identidad'),array('class'=>'form-control','placeholder'=>'Ingrese número de identidad','id'=>'documento_identidad','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('genero')) has-error has-feedback @endif">
								{{ Form::label('genero','Género')}}
								{{ Form::select('genero', [''=>'Seleccione','M'=>'Masculino','F'=>'Femenino'],Input::old('genero'),['class' => 'form-control']) }}
							</div>	
							<div class="form-group col-md-3">	
								{{ Form::label('fecha_nacimiento','Fecha de nacimiento') }}
								<div id="datetimepicker1" class="form-group input-group date @if($errors->first('fecha_nacimiento')) has-error has-feedback @endif">
									{{ Form::text('fecha_nacimiento',Input::old('fecha_nacimiento'),array('class'=>'form-control','placeholder'=>'Fecha de Nacimiento')) }}
									<span class="input-group-addon">
				                        <span class="glyphicon glyphicon-calendar"></span>
				                    </span>
								</div>	
							</div>		
						</div>							
					</div>
				</div>
			</div>
		</div>		
		
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-headline ">
					<div class="panel-heading">
						<h3 class="panel-title">Datos de Contacto</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							@if ($errors->has())
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<p>{{ $errors->first('email') }}</p>
								<p>{{ $errors->first('telefono') }}</p>
							</div>
							@endif
						</div>					
						<div class="row">
							<div class="form-group col-md-6 @if($errors->first('email')) has-error has-feedback @endif">
								{{ Form::label('email','Correo Electrónico')}}
								{{ Form::text('email',Input::old('email'),array('class'=>'form-control','placeholder'=>'Ingrese correo electrónico','type'=>'email')) }}
							</div>
							<div class="form-group col-md-6 @if($errors->first('telefono')) has-error has-feedback @endif">
								{{ Form::label('telefono','Telefono')}}
								{{ Form::text('telefono',Input::old('telefono'),array('class'=>'form-control','placeholder'=>'Ingrese número de teléfono')) }}
							</div>
						</div>				
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">Perfil del Usuario</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							@if ($errors->has())
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>								
								<p>{{ $errors->first('rol') }}</p>
							</div>
							@endif
						</div>		
						<div class="row">
							<div class="form-group col-md-6 @if($errors->first('rol')) has-error has-feedback @endif">
								{{ Form::label('rol','Perfil de Usuario (Rol)')}}
								{{ Form::select('rol',array(''=>'Seleccione')+$roles,Input::old('rol'),array('class'=>'form-control','id'=>'rol')) }}
							</div>
						</div>				
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-md-2  col-md-offset-8">				
				<a class="btn btn-info btn-block" id="btnCrear" type="submit"> <i class="lnr lnr-plus-circle"></i> Crear</a>
			</div>
			<div class="form-group col-md-2">
				<a class="btn btn-default btn-block" href="{{URL::to('/principal')}}">Cancelar</a>				
			</div>
		</div>

	</div>
		
		{{ Form::close() }}					
	</div>
</div>
@stop