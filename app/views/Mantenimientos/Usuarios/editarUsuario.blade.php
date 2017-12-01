@extends('Templates/Mantenimientos/usuariosTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10">
				<h3 class="page-title"><strong>USUARIO:</strong> {{$usuario->nombre}} {{$usuario->apellido_paterno}} {{$usuario->apellido_materno}} ({{$usuario->username}})</h3>
			</div>
			<div class="col-md-2">
				<a class="btn btn-default btn-block" href="{{URL::to('/usuarios/listar_usuarios')}}"><i class="lnr lnr-arrow-left"></i> Cancelar</a>		
			</div>	
		</div>
		<div class="row">
			@if (Session::has('error'))
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<i class="fa fa-times-circle"></i> {{ Session::get('message') }}
				</div>
			@endif
			@if (Session::has('message'))		
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-check-circle"></i>
					{{ Session::get('message') }}
				</div>
			@endif
		</div>		
		
		{{ Form::open(array('url'=>'/usuarios/submit_editar_usuario' ,'role'=>'form','id'=>'submit-editar')) }}
		{{ Form::hidden('usuario_id', $usuario->id, array('id'=>'usuario_id')) }}	
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
								{{ Form::text('username',$usuario->username,array('class'=>'form-control','placeholder'=>'Ingrese nombre del usuario','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('nombre')) has-error has-feedback @endif"">
								{{ Form::label('nombre','Nombres')}}
								{{ Form::text('nombre',$usuario->nombre,array('class'=>'form-control','placeholder'=>'Ingrese nombres')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('apellido_paterno')) has-error has-feedback @endif"">
								{{ Form::label('apellido_paterno','Apellido Paterno')}}
								{{ Form::text('apellido_paterno',$usuario->apellido_paterno,array('class'=>'form-control','placeholder'=>'Ingrese apellido paterno')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('apellido_materno')) has-error has-feedback @endif"">
								{{ Form::label('apellido_materno','Apellido Materno')}}
								{{ Form::text('apellido_materno',$usuario->apellido_materno,array('class'=>'form-control','placeholder'=>'Ingrese apellido materno')) }}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-3 @if($errors->first('tipo_doc_identidad')) has-error has-feedback @endif">
								{{ Form::label('tipo_doc_identidad','Tipo Documento de Identidad')}}
								{{ Form::select('tipo_doc_identidad',array(''=>'Seleccione')+$tipo_doc_identidades,$usuario->idtipo_doc_identidad,array('class'=>'form-control','id'=>'create_tipo_doc_identidad')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('documento_identidad')) has-error has-feedback @endif">
								{{ Form::label('documento_identidad','Número de Identidad')}}
								{{ Form::text('documento_identidad',$usuario->numero_doc_identidad,array('class'=>'form-control','placeholder'=>'Ingrese número de identidad','id'=>'documento_identidad')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('genero')) has-error has-feedback @endif">
								{{ Form::label('genero','Género')}}
								{{ Form::select('genero', [''=>'Seleccione','M'=>'Masculino','F'=>'Femenino'],$usuario->genero,['class' => 'form-control']) }}
							</div>	
							<div class="form-group col-md-3">	
								{{ Form::label('fecha_nacimiento','Fecha de nacimiento') }}
								<div id="datetimepicker1" class="form-group input-group date @if($errors->first('fecha_nacimiento')) has-error has-feedback @endif">
									{{ Form::text('fecha_nacimiento',date('d-m-Y',strtotime($usuario->fecha_nacimiento)),array('class'=>'form-control','placeholder'=>'Fecha de Nacimiento')) }}
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
								{{ Form::text('email',$usuario->email,array('class'=>'form-control','placeholder'=>'Ingrese correo electrónico','type'=>'email')) }}
							</div>
							<div class="form-group col-md-6 @if($errors->first('telefono')) has-error has-feedback @endif">
								{{ Form::label('telefono','Telefono')}}
								{{ Form::text('telefono',$usuario->telefono,array('class'=>'form-control','placeholder'=>'Ingrese número de teléfono')) }}
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
								{{ Form::select('rol',array(''=>'Seleccione')+$roles,$usuario->idrol,array('class'=>'form-control','id'=>'rol')) }}
							</div>
						</div>				
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-md-3 ">
				<a class="btn btn-success btn-block" id="btnReestablecer"><i class="fa fa-eraser"></i> Reestablecer Contraseña </a>
			</div>			
			
			<div class="form-group col-md-2  col-md-offset-5">
				{{ Form::button('<i class="fa fa-floppy-o"></i> Guardar', array('id'=>'btnEditar', 'class' => 'btn btn-info btn-block')) }}
			</div>
			{{ Form::close() }}	
			@if($usuario->deleted_at)
				{{ Form::open(array('url'=>'usuarios/submit_habilitar_usuario', 'role'=>'form','id'=>'habilitar_usuario')) }}
					{{ Form::hidden('user_id', $usuario->id) }}
					<div class="form-group col-md-2">
						{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-up"></span> Habilitar', array('id'=>'submit-habilitar-usuario', 'class' => 'btn btn-success btn-block')) }}
					</div>
				{{ Form::close() }}
			@else
				{{ Form::open(array('url'=>'usuarios/submit_inhabilitar_usuario', 'role'=>'form','id'=>'inhabilitar_usuario')) }}
					{{ Form::hidden('user_id', $usuario->id) }}
					<div class="form-group col-md-2">
						{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-down"></span> Inhabilitar', array('id'=>'submit-inhabilitar-usuario', 'class' => 'btn btn-danger btn-block')) }}
					</div>
				{{ Form::close() }}
			@endif
		</div>

	</div>
		
						
	</div>
</div>
@stop