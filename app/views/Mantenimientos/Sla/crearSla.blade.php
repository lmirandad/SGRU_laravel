@extends('Templates/Mantenimientos/slaTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>CREACIÓN DE NUEVO SLA</strong></h3>

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
								<p>{{ $errors->first('fecha_inicio') }}</p>				
								<p>{{ $errors->first('fecha_fin') }}</p>
							</div>
							@endif
						</div>
						<div class="row">
							<div class="form-group col-md-4 @if($errors->first('nombre_sector')) has-error has-feedback @endif"">
								{{ Form::label('nombre_sector','Nombre del Sector')}}
								{{ Form::text('nombre_sector',$sector->nombre,array('class'=>'form-control','placeholder'=>'Ingrese nombre del aplicativo','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-4 @if($errors->first('nombre_herramienta')) has-error has-feedback @endif"">
								{{ Form::label('nombre_herramienta','Nombre del Aplicativo')}}
								{{ Form::text('nombre_herramienta',$herramienta->nombre,array('class'=>'form-control','placeholder'=>'Ingrese nombre del aplicativo','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-4 @if($errors->first('fecha_inicio_vigencia')) has-error has-feedback @endif"">
								{{ Form::label('fecha_inicio_vigencia','Fecha inicio vigencia:') }}
								{{ Form::text('fecha_inicio_vigencia', date('d-m-Y'),array('class'=>'form-control','placeholder'=>'Fecha de Nacimiento','disabled'=>'disabled')) }}
							</div>							
						</div>					
					</div>
				</div>
			</div>
		</div>
		{{ Form::open(array('url'=>'/slas/submit_crear_sla' ,'role'=>'form','id'=>'submit-crear')) }}
		{{ Form::hidden('idherramientaxsector',$herramientaxsector->idherramientaxsector)}}
		<div class="row">
			<div class="col-md-6">
				<div class="panel">	
					<div class="panel-heading">
						<h4 class="page-title">SLA - <strong>SOLICITUDES PENDIENTES</strong></h4>
					</div>	
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover"  >
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Acción</th>
												<th class="text-nowrap text-center">N° Días</th>
											</tr>
										</thead>
										
											<tbody >	
												@foreach($tipos_solicitud as $index  => $accion)
												<tr class="">
													<td class="text-nowrap text-center">
														<input style="display:none" name='ids_acciones[]' value='{{ $accion->idtipo_solicitud }}' readonly/>
														{{$index+1}}
													</td>
													<td class="text-nowrap text-center">
														{{$accion->nombre}}
													</td>
													<td class="text-nowrap" style=";width:20%">
														<input class="form-control"  name='valor_sla_pendiente[]'/>
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
			</div>
			<div class="col-md-6">
				<div class="panel">	
					<div class="panel-heading">
						<h4 class="page-title">SLA - <strong>SOLICITUDES PROCESANDO</strong></h4>
					</div>	
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Acción</th>
												<th class="text-nowrap text-center">N° Días</th>
											</tr>
										</thead>
										
											<tbody >	
												@foreach($tipos_solicitud as $index  => $accion)
												<tr class="">
													<td class="text-nowrap text-center">
														{{$index+1}}
													</td>
													<td class="text-nowrap text-center">
														{{$accion->nombre}}
													</td>
													<td class="text-nowrap" style=";width:20%">
														<input class="form-control" name='valor_sla_procesando[]'/>
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
			</div>
		</div>		
		
		<div class="row">
			<div class="form-group col-md-2  col-md-offset-8">				
				<a class="btn btn-info btn-block" id="btnCrearSla"> <i class="lnr lnr-plus-circle"></i> Crear</a>
			</div>
			<div class="form-group col-md-2">
				<a class="btn btn-default btn-block" href="{{URL::to('sectores/mostrar_herramientas_sector')}}/{{$herramientaxsector->idsector}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
			</div>
		</div>
		{{ Form::close() }}		
	</div>
		
				
	</div>
</div>
@stop