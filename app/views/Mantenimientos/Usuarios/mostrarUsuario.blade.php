@extends('Templates/Mantenimientos/usuariosTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>USUARIO:</strong> {{$usuario->nombre}} {{$usuario->apellido_paterno}} {{$usuario->apellido_materno}} ({{$usuario->username}})</h3>

		
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

		{{ Form::hidden('usuario_id', $usuario->id) }}	
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
								{{ Form::text('username',$usuario->username,array('class'=>'form-control','placeholder'=>'Ingrese nombre del usuario','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('nombre')) has-error has-feedback @endif"">
								{{ Form::label('nombre','Nombres')}}
								{{ Form::text('nombre',$usuario->nombre,array('class'=>'form-control','placeholder'=>'Ingrese nombres','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('apellido_paterno')) has-error has-feedback @endif"">
								{{ Form::label('apellido_paterno','Apellido Paterno')}}
								{{ Form::text('apellido_paterno',$usuario->apellido_paterno,array('class'=>'form-control','placeholder'=>'Ingrese apellido paterno','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('apellido_materno')) has-error has-feedback @endif"">
								{{ Form::label('apellido_materno','Apellido Materno')}}
								{{ Form::text('apellido_materno',$usuario->apellido_materno,array('class'=>'form-control','placeholder'=>'Ingrese apellido materno','disabled'=>'disabled')) }}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-3 @if($errors->first('tipo_doc_identidad')) has-error has-feedback @endif">
								{{ Form::label('tipo_doc_identidad','Tipo Documento de Identidad')}}
								{{ Form::select('tipo_doc_identidad',array(''=>'Seleccione')+$tipo_doc_identidades,$usuario->idtipo_doc_identidad,array('class'=>'form-control','id'=>'create_tipo_doc_identidad','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('documento_identidad')) has-error has-feedback @endif">
								{{ Form::label('documento_identidad','Número de Identidad')}}
								{{ Form::text('documento_identidad',$usuario->numero_doc_identidad,array('class'=>'form-control','placeholder'=>'Ingrese número de identidad','id'=>'documento_identidad','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('genero')) has-error has-feedback @endif">
								{{ Form::label('genero','Género')}}
								{{ Form::select('genero', [''=>'Seleccione','M'=>'Masculino','F'=>'Femenino'],$usuario->genero,['class' => 'form-control','disabled'=>'disabled']) }}
							</div>	
							<div class="form-group col-md-3">	
								{{ Form::label('fecha_nacimiento','Fecha de nacimiento') }}
								<div id="datetimepicker1" class="form-group input-group date @if($errors->first('fecha_nacimiento')) has-error has-feedback @endif">
									{{ Form::text('fecha_nacimiento',date('d-m-Y',strtotime($usuario->fecha_nacimiento)),array('class'=>'form-control','placeholder'=>'Fecha de Nacimiento','disabled'=>'disabled')) }}
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
								{{ Form::text('email',$usuario->email,array('class'=>'form-control','placeholder'=>'Ingrese correo electrónico','type'=>'email','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-6 @if($errors->first('telefono')) has-error has-feedback @endif">
								{{ Form::label('telefono','Telefono')}}
								{{ Form::text('telefono',$usuario->telefono,array('class'=>'form-control','placeholder'=>'Ingrese número de teléfono','disabled'=>'disabled')) }}
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
								{{ Form::select('rol',array(''=>'Seleccione')+$roles,$usuario->idrol,array('class'=>'form-control','id'=>'rol','disabled'=>'disabled')) }}
							</div>
						</div>				
					</div>
				</div>
			</div>
		</div>
		@if($usuario->idrol != 1)
		<div class="row">
			<div class="col-md-6">
				<div class="panel">	
					<div class="panel-heading">
						<h4 class="page-title">HERRAMIENTAS GESTIONADAS POR EL USUARIO</h4>
					</div>	
					<div class="panel-body" style="height:175px;overflow-y:auto;" >
						<div class="row">
							<div class="col-md-12">
								@if(count($herramientas) > 0)
								<div class="table-responsive">
									<table class="table table-hover"  >
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Nombre Aplicativo</th>
												<th class="text-nowrap text-center">Tipo Aplicativo</th>
											</tr>
										</thead>
										
											<tbody >	
												@foreach($herramientas as $index  => $herramienta)
												<tr class="">
													<td class="text-nowrap text-center">
														{{$index+1}}
													</td>
													<td class="text-nowrap text-center">
														{{$herramienta->nombre}}
													</td>
													<td class="text-nowrap text-center">
														{{$herramienta->nombre_denominacion}}
													</td>
												</tr>
												@endforeach
											</tbody>										
									</table>
								</div>
								@else
								<h3 style="text-align:center">SIN REGISTROS</h3>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel">	
					<div class="panel-heading">
						<h4 class="page-title">SECTORES ASIGNADOS AL USUARIO</h4>
					</div>	
					<div class="panel-body" style="height:175px;overflow-y:auto;">
						<div class="row">
							<div class="col-md-12">
								@if(count($sectores) > 0)
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Sector</th>
											</tr>
										</thead>
										
											<tbody >	
												@foreach($sectores as $index  => $sector)
												<tr class="">
													<td class="text-nowrap text-center">
														{{$index+1}}
													</td>
													<td class="text-nowrap text-center">
														{{$sector->nombre}}
													</td>
												</tr>
												@endforeach
											</tbody>										
									</table>
								</div>
								@else
								<h3 style="text-align:center">SIN REGISTROS</h3>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
		<div class="row">
			@if($user->idrol == 1)
				<div class="form-group col-md-2 col-md-offset-10">
					<a class="btn btn-default btn-block" href="{{URL::to('/usuarios/listar_usuarios')}}">Cancelar</a>
				</div>
			@else
				<div class="form-group col-md-2 col-md-offset-10">
					<a class="btn btn-default btn-block" href="{{URL::to('/principal')}}">Cancelar</a>
				</div>
			@endif
		</div>
	</div>
		
		{{ Form::close() }}					
	</div>
</div>
@stop