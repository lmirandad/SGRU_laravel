@extends('Templates/Mantenimientos/slaTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>SLA's ASOCIADOS</strong>&nbsp&nbsp&nbsp <a class="btn btn-info btn-xs" id="btnMostrarInformacion"> <i class="fa fa-info-circle"></i></a></h3>
		

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
						</div>					
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">SLA's Asociados</h3>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-nowrap text-center">Nº</th>
										<th class="text-nowrap text-center">Fecha Inicio</th>
										<th class="text-nowrap text-center">Fecha Fin</th>
										<th class="text-nowrap text-center">Estado</th>
										<th class="text-nowrap text-center">Ver Datos</th>
										<th class="text-nowrap text-center">Editar</th>
									</tr>
								</thead>
								<tbody>	
									@foreach($slas_data as $index  => $sla)
									<tr>
										<td class="text-nowrap text-center">
											{{$index+1}}
										</td>
										<td class="text-nowrap text-center">
											{{date('d-m-Y',strtotime($sla->fecha_inicio))}}
										</td>
										
										<td class="text-nowrap text-center">
											@if($sla->fecha_fin == null) -
											@else
											{{date('d-m-Y',strtotime($sla->fecha_fin))}}
											@endif
										</td>
										<td class="text-nowrap text-center">
											@if($sla->fecha_fin == null) Vigente
											@else No Vigente
											@endif 
										</td>
										<td class="text-nowrap text-center">
											<div style="text-align:center">
												<button class="btn btn-info btn-sm" onclick="mostrar_datos(event,{{$sla->idsla}})" type="button"><span class="fa fa-search"></span></button>
											</div>										
										</td>
										<td class="text-nowrap text-center">
											<!-- poner aqui el boton de editar -->
											@if($sla->fecha_fin == null)
												<div style="text-align:center">
													<button class="btn btn-warning btn-sm" onclick="editar_sla(event,{{$sla->idsla}})" type="button"><span class="lnr lnr-pencil"></span></button>
												</div>	
											@else -
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

		{{ Form::hidden('idherramientaxsector',$herramientaxsector->idherramientaxsector,array('id'=>'idherramientaxsector'))}}
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
													<input class="form-control" id="valor_sla_pendiente{{$index}}"  name='valor_sla_pendiente[]' disabled="disabled" />
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
													<input style="display:none" name='ids_acciones[]' value='{{ $accion->idtipo_solicitud }}' readonly/>
													{{$index+1}}
												</td>
												<td class="text-nowrap text-center">
													{{$accion->nombre}}
												</td>
												<td class="text-nowrap" style=";width:20%">
													<input class="form-control" id="valor_sla_procesando{{$index}}" name='valor_sla_procesando[]' disabled="disabled"/>
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
				<a class="btn btn-info btn-block" id="btnCrearSlaNuevo"> <i class="lnr lnr-plus-circle"></i> Crear Nuevo SLA</a>
			</div>
			<div class="form-group col-md-2">
				<a class="btn btn-default btn-block" href="{{URL::to('sectores/mostrar_herramientas_sector')}}/{{$herramientaxsector->idsector}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
			</div>
		</div>
	</div>
		
				
	</div>
</div>
@stop