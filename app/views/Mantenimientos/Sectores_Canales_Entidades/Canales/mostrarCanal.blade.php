@extends('Templates/Mantenimientos/canalTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>CANAL: </strong> {{$canal->nombre}}</h3>

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
		<!-- OVERVIEW -->		
		<div class="row">
			<div class="col-md-6">
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
							</div>
							@endif
						</div>
						<div class="row">
							<div class="form-group col-md-4 @if($errors->first('nombre_canal')) has-error has-feedback @endif"">
								{{ Form::label('nombre_canal','Nombre del Canal')}}
								{{ Form::text('nombre_canal',$canal->nombre,array('class'=>'form-control','placeholder'=>'Ingrese nombre del canal','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-4 @if($errors->first('nombre_canal_agrupado')) has-error has-feedback @endif"">
								{{ Form::label('nombre_canal_agrupado','Nombre del Canal Agrupado:')}}
								{{ Form::select('canal_agrupado',array(''=>'Seleccione')+$canales_agrupados,$canal->idcanal_agrupado,array('class'=>'form-control','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-4 @if($errors->first('sector')) has-error has-feedback @endif"">
								{{ Form::label('sector','Sector:')}}
								{{ Form::select('sector',array(''=>'Seleccione')+$sectores,$canal->idsector,array('class'=>'form-control','disabled'=>'disabled')) }}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12 @if($errors->first('descripcion')) has-error has-feedback @endif"">
								{{ Form::label('descripcion','Descripción:')}}
								{{ Form::textarea('descripcion',$canal->descripcion,array('class'=>'form-control','placeholder'=>'Ingrese una descripción','rows'=>5,'style'=>'resize:none','disabled'=>'disabled')) }}
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">Entidades</h3>
					</div>
					<div class="panel-body">
						<div class="table-responsive" style="height:230px;overflow-y:auto; ">
							<table class="table table-hover"  >
								<thead>
									<tr>
										<th class="text-nowrap text-center">N°</th>
										<th class="text-nowrap text-center">Nombre Entidad</th>
										<th class="text-nowrap text-center">Estado</th>
									</tr>
								</thead>
								
									<tbody >	
										@foreach($entidades as $index  => $entidad)
										<tr class="">
											<td class="text-nowrap text-center">
												{{$index+1}}
											</td>
											<td class="text-nowrap text-center">
												{{$entidad->nombre}}
											</td>
											<td class="text-nowrap text-center">
												@if($entidad->deleted_at == null)
													Activo
												@else
													Inactivo
												@endif
											</td>
										</tr>
										@endforeach
									</tbody>										
							</table>
						</div>	
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			@if($user->idrol == 1)
				@if($canal->deleted_at)
					{{ Form::open(array('url'=>'canales/submit_habilitar_canal', 'role'=>'form','id'=>'habilitar_canal')) }}
						{{ Form::hidden('canal_id', $canal->idcanal) }}
						<div class="form-group col-md-2">
							{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-up"></span> Habilitar', array('id'=>'submit-habilitar-canal', 'class' => 'btn btn-success btn-block')) }}
						</div>
					{{ Form::close() }}
				@else
					{{ Form::open(array('url'=>'canales/submit_inhabilitar_canal', 'role'=>'form','id'=>'inhabilitar_canal')) }}
						{{ Form::hidden('canal_id', $canal->idcanal) }}
						<div class="form-group col-md-2">
							{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-down"></span> Inhabilitar', array('id'=>'submit-inhabilitar-canal', 'class' => 'btn btn-danger btn-block')) }}
						</div>
					{{ Form::close() }}
				@endif
			@endif
			<div class="form-group col-md-2 @if($user->idrol==1) col-md-offset-8 @else col-md-offset-10 @endif">
				<a class="btn btn-default btn-block" href="{{URL::to('entidades_canales_sectores/listar/2')}}"><i class="lnr lnr-arrow-left"></i>&nbspSalir</a>				
			</div>
		</div>		
	</div>
		
				
	</div>
</div>
@stop