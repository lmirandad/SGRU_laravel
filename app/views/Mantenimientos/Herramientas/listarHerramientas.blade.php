@extends('Templates/Mantenimientos/herramientasTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title">MANTENIMIENTOS/<strong>APLICATIVOS</strong></h3>
		@if (Session::has('message'))
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				{{ Session::get('message') }}
			</div>
		@endif
		@if (Session::has('error'))
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
				<i class="fa fa-times-circle"></i> {{ Session::get('error') }}
			</div>
		@endif
		<!-- OVERVIEW -->
		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Criterios de Búsqueda</h3>
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/herramientas/buscar_herramientas','method'=>'get' ,'role'=>'form', 'id'=>'search-form')) }}
				<div class="row">
					<div class="col-md-4">
						{{ Form::label('search','Nombre del Aplicativo')}}
						{{ Form::text('search',$search,array('class'=>'form-control','placeholder'=>'Ingrese nombre del aplicativo','id'=>'search')) }}
					</div>
					<div class="col-md-4">
						{{ Form::label('search_denominacion_herramienta','Categoría Herramienta')}}
						{{ Form::select('search_denominacion_herramienta',array('0'=>'Seleccione')+$denominacion_herramienta,$search_denominacion_herramienta,array('class'=>'form-control')) }}
					</div>
					<div class="form-group col-md-2">
					{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
					</div>
					<div class="col-md-2">
						<div class="btn btn-default btn-block" id="btnLimpiar" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
					</div>				
				</div>
				{{ Form::close() }}					
			</div>
		</div>
		
		<div class="panel">		
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-nowrap text-center">Nombre de Aplicativo</th>
										<th class="text-nowrap text-center">Denominación Aplicativo</th>
										<th class="text-nowrap text-center">¿Valida Seguridad?</th>
										<th class="text-nowrap text-center">Tipo Atención Requerimiento</th>
										<th class="text-nowrap text-center">Editar</th>
										<!--<th class="text-nowrap text-center">Ver Perfiles</th>
										-->
										<th class="text-nowrap text-center">Estado</th>
									</tr>
								</thead>
								<tbody>	
									@foreach($herramientas_data as $herramienta_data)
									<tr>
										<td class="text-nowrap text-center">
											<a href="{{URL::to('/herramientas/mostrar_herramienta/')}}/{{$herramienta_data->idherramienta}}">{{$herramienta_data->nombre}}</a>
										</td>
										<td class="text-nowrap text-center">
											{{$herramienta_data->nombre_denominacion}}
										</td>
										
										<td class="text-nowrap text-center">
											@if($herramienta_data->flag_seguridad == 1)SI
											@elseif($herramienta_data->flag_seguridad == 0)NO
											@else POR VALIDAR
											@endif
										</td>
										<td class="text-nowrap text-center">
											{{$herramienta_data->nombre_tipo}}
										</td>
										<td class="text-nowrap text-center">
											@if($user->idrol == 1)
												@if($herramienta_data->deleted_at != null)
													-
												@else
													<div style="text-align:center">
														<a class="btn btn-warning btn-sm" href="{{URL::to('/herramientas/editar_herramienta')}}/{{$herramienta_data->idherramienta}}"><span class="lnr lnr-pencil"></span></a>
													</div>
												@endif
											@else
												-
											@endif											
										</td>
										<!--
										<td class="text-nowrap text-center">
												@if($herramienta_data->deleted_at == null)	
													<a class="btn btn-info btn-sm" href="{{URL::to('/perfiles_aplicativos/listar_perfiles_aplicativos')}}/{{$herramienta_data->idherramienta}}">
													<span class="fa fa-search"></span></a>
												@else
													-
												@endif
											</td>
										-->
										<td class="text-nowrap text-center">
												@if($herramienta_data->deleted_at != null)
													Inactivo
												@else
													Activo
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
		@if($search || $search_denominacion_herramienta)
			{{ $herramientas_data->appends(array('search' => $search,'search_denominacion_herramienta'=>$search_denominacion_herramienta))->links() }}
		@else
			{{ $herramientas_data->links() }}
		@endif
		<div class="form-group col-md-2 col-md-offset-10">
			<a href="{{URL::to('herramientas/crear_herramienta')}}" class="btn btn-info"><span class="lnr lnr-plus-circle"></span>&nbsp Crear Nuevo Aplicativo</a>
		</div>
		
	</div>
</div>
@stop