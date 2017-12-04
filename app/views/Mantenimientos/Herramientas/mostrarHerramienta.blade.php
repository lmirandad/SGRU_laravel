@extends('Templates/Mantenimientos/herramientasTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>APLICATIVO: </strong>{{$herramienta->nombre}}</h3>

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
								{{ Form::text('nombre_herramienta',$herramienta->nombre,array('class'=>'form-control','placeholder'=>'Ingrese nombre del aplicativo','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('flag_seguridad')) has-error has-feedback @endif"">
								{{ Form::label('flag_seguridad','Valida Seguridad:')}}
								{{ Form::select('flag_seguridad', [''=>'Seleccione','0'=>'No','1'=>'Si','2'=>'Por Validar'],$herramienta->flag_seguridad,['class' => 'form-control','disabled'=>'disabled']) }}
							</div>
							<div class="form-group col-md-3 @if($errors->first('denominacion_herramienta')) has-error has-feedback @endif">
								{{ Form::label('denominacion_herramienta','Categoría Aplicativo:')}}
								{{ Form::select('denominacion_herramienta',array(''=>'Seleccione')+$denominaciones,$herramienta->iddenominacion_herramienta,array('class'=>'form-control','disabled'=>'disabled')) }}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-9 col-md-offset-1 @if($errors->first('descripcion')) has-error has-feedback @endif"">
								{{ Form::label('descripcion','Descripción:')}}
								{{ Form::textarea('descripcion',$herramienta->descripcion,array('class'=>'form-control','placeholder'=>'Ingrese una descripción','rows'=>5,'style'=>'resize:none','disabled'=>'disabled')) }}
							</div>
						</div>						
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">SECTORES ASIGNADOS AL APLICATIVO</h3>
						</div>
						<div class="panel-body">
							@if(count($sectores) > 0)
								<div class="table-responsive"  style="height:150px;overflow-y:auto;">
									<table class="table table-hover"  >
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Nombre del Sector</th>
												<th class="text-nowrap text-center">Estado</th>
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
													<td class="text-nowrap text-center">
														@if($sector->delete_at == null) Activo
														@else Inactivo
														@endif
													</td>
												</tr>
												@endforeach
											</tbody>										
									</table>
								</div>
							@else
							<h3 style="text-align:center;height:150px;overflow-y:auto;">SIN REGISTROS</h3>
							@endif					
						</div>
					</div>
				
			</div>
			<div class="col-md-6">
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">USUARIOS ESPECIALIZADOS EN EL APLICATIVO</h3>
						</div>
						<div class="panel-body">
							@if(count($usuarios) > 0)
								<div class="table-responsive"  style="height:150px;overflow-y:auto;">
									<table class="table table-hover"  >
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Nombre del Usuario</th>
												<th class="text-nowrap text-center">Estado</th>
											</tr>
										</thead>
										
											<tbody >	
												@foreach($usuarios as $index  => $usuario)
												<tr class="">
													<td class="text-nowrap text-center">
														{{$index+1}}
													</td>
													<td class="text-nowrap text-center">
														{{$usuario->nombre}} {{$usuario->apellido_paterno}} {{$usuario->apellido_materno}}
													</td>
													<td class="text-nowrap text-center">
														@if($usuario->delete_at == null) Activo
														@else Inactivo
														@endif
													</td>
												</tr>
												@endforeach
											</tbody>										
									</table>
								</div>
							@else
							<h3 style="text-align:center;height:120px;overflow-y:auto;">SIN REGISTROS</h3>
							@endif					
						</div>
					</div>
				</div>
		</div>			
		
		<div class="row">
			@if($user->idrol == 1)
				@if($herramienta->deleted_at)
					{{ Form::open(array('url'=>'herramientas/submit_habilitar_herramienta', 'role'=>'form','id'=>'habilitar_herramienta')) }}
						{{ Form::hidden('herramienta_id', $herramienta->idherramienta) }}
						<div class="form-group col-md-2">
							{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-up"></span> Habilitar', array('id'=>'submit-habilitar-herramienta', 'class' => 'btn btn-success btn-block')) }}
						</div>
					{{ Form::close() }}
				@else
					{{ Form::open(array('url'=>'herramientas/submit_inhabilitar_herramienta', 'role'=>'form','id'=>'inhabilitar_herramienta')) }}
						{{ Form::hidden('herramienta_id', $herramienta->idherramienta) }}
						<div class="form-group col-md-2">
							{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-down"></span> Inhabilitar', array('id'=>'submit-inhabilitar-herramienta', 'class' => 'btn btn-danger btn-block')) }}
						</div>
					{{ Form::close() }}
				@endif
			@endif
			@if($user->idrol == 1)
				<div class="form-group col-md-2 col-md-offset-8">
					<a class="btn btn-default btn-block" href="{{URL::to('/herramientas/listar_herramientas')}}"><i class="lnr lnr-arrow-left"></i>&nbspSalir</a>
				</div>
			@else
				<div class="form-group col-md-2 col-md-offset-8">
					<a class="btn btn-default btn-block" href="{{URL::to('/principal')}}"><i class="lnr lnr-arrow-left"></i>&nbspSalir</a>
				</div>
			@endif
		</div>
			
	</div>
		
				
	</div>
</div>
@stop