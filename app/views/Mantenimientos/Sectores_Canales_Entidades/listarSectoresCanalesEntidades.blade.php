@extends('Templates/Mantenimientos/SectorCanalEntidadTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="col-md-8 col-md-offset-2">
		<div class="container-fluid">				
			<div class="panel panel-headline">
				<div class="panel-heading">
					<h4 class="panel-title" >Seleccione una Opción</h4>
				</div>	
				<div class="panel-body" style="margin-top:-40px">
					<div class="row">
						<div class="col-md-4 ">
							<a class="btn btn-default btn-block" href="{{URL::to('entidades_canales_sectores/listar/1')}}" style='margin-top:25px;'><span class="lnr lnr-apartment""></span>&nbsp&nbsp Ver Sectores</a>
						</div>
						<div class="col-md-4">
							<a class="btn btn-default btn-block" href="{{URL::to('entidades_canales_sectores/listar/2')}}" style='margin-top:25px;'><span class="lnr lnr-phone-handset""></span>&nbsp&nbsp Ver Canales</a>
						</div>
						<div class="col-md-4">
							<a class="btn btn-default btn-block" href="{{URL::to('entidades_canales_sectores/listar/3')}}" style='margin-top:25px;'><span class="lnr lnr-users""></span>&nbsp&nbsp Ver Entidades</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{ Form::hidden('flag_seleccion', $flag_seleccion,array('id'=>'flag_seleccion')) }}	
	@if($flag_seleccion == 1)
		<!--SECTORES-->
		<div class="container-fluid">
			<h3 class="page-title">MANTENIMIENTOS/<strong>SECTORES</strong></h3>
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
			<div class="row">
				<div class="col-md-4">
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">Criterios de Búsqueda</h3>
						</div>
						<div class="panel-body">
							{{ Form::open(array('url'=>'/sectores/buscar_sectores','method'=>'get' ,'role'=>'form', 'id'=>'search-form')) }}
							<div class="row">
								<div class="col-md-12">
									{{ Form::label('sector_search','Nombre del Sector')}}
									{{ Form::text('sector_search',$sector_search,array('class'=>'form-control','placeholder'=>'Ingrese nombre del sector','id'=>'sector_search')) }}
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-6">
								{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
								</div>
								<div class="col-md-6">
									<div class="btn btn-default btn-block" id="btnLimpiarSector" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
								</div>				
							</div>
							{{ Form::close() }}					
						</div>
					</div>
				</div>
					<div class="col-md-8">
						<div class="panel">		
							<div class="panel-body">
								<div class="row" style="height:206px;overflow-y:auto; " >
									<div class="col-md-12">
										<div class="table-responsive">
											<table class="table table-hover">
												<thead>
													<tr>
														<th class="text-nowrap text-center">Nombre del Sector</th>
														<th class="text-nowrap text-center">Editar</th>
														<th class="text-nowrap text-center">Aplicativos Asignados</th>					
														<th class="text-nowrap text-center">Estado</th>
													</tr>
												</thead>
												<tbody>	
													@foreach($sectores_data as $sector_data)
													<tr>
														<td class="text-nowrap text-center">
															<a href="{{URL::to('/sectores/mostrar_sector/')}}/{{$sector_data->idsector}}">{{$sector_data->nombre}}</a>
														</td>
														<td class="text-nowrap text-center">
															@if($sector_data->deleted_at == null)
																<div style="text-align:center">
																	<a class="btn btn-warning btn-sm" href="{{URL::to('/sectores/editar_sector')}}/{{$sector_data->idsector}}">
																	<span class="lnr lnr-pencil"></span></a>
																</div>
															@else
																-
															@endif															
														</td>														
														<td class="text-nowrap text-center">
															@if($sector_data->deleted_at == null)
																<div style="text-align:center">
																	<a class="btn btn-info btn-sm" href="{{URL::to('/sectores/mostrar_herramientas_sector')}}/{{$sector_data->idsector}}">
																	<span class="fa fa-search"></span></a>
																</div>
															@else
																-
															@endif															
														</td>
														<td class="text-nowrap text-center">
															@if($sector_data->deleted_at == null)	
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
					</div>
				</div>	
			</div>
			

							
			@if($sector_search)
				{{ $sectores_data->appends(array('sector_search' => $sector_search))->links() }}
			@else
				{{ $sectores_data->links() }}
			@endif
			<div class="col-md-2 col-md-offset-10" style="margin-top:-30px;margin-bottom: 10px">
				<a class="btn btn-info btn-block" style='margin-top:25px;' href="{{URL::to('sectores/crear_sector')}}"><span class="lnr lnr-plus-circle"></span> Crear Sector</a>	
			</div>	
		</div>
	@elseif($flag_seleccion == 2)
		<!--CANALES-->
		<div class="container-fluid">
			<h3 class="page-title">MANTENIMIENTOS/<strong>CANALES</strong></h3>
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
					{{ Form::open(array('url'=>'/canales/buscar_canales','method'=>'get' ,'role'=>'form', 'id'=>'search-form')) }}
					<div class="row">
						<div class="col-md-4">
							{{ Form::label('canal_search','Nombre del Canal')}}
							{{ Form::text('canal_search',$canal_search,array('class'=>'form-control','placeholder'=>'Ingrese nombre del canal','id'=>'canal_search')) }}
						</div>
						<div class="col-md-4">
							{{ Form::label('canal_search_sector','Sector')}}
							{{ Form::select('canal_search_sector',array(''=>'Seleccione')+$sectores,$canal_search_sector,array('class'=>'form-control','id'=>'canal_search_sector')) }}
						</div>
						<div class="form-group col-md-2">
						{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
						</div>
						<div class="col-md-2">
							<div class="btn btn-default btn-block" id="btnLimpiarCanal" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
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
											<th class="text-nowrap text-center">Nombre del Canal</th>
											<th class="text-nowrap text-center">Sector</th>
											<th class="text-nowrap text-center">Editar</th>
											<!--
												<th class="text-nowrap text-center">Ver Cargos</th>
											-->
											<th class="text-nowrap text-center">Estado</th>
										</tr>
									</thead>
									<tbody>	
										@foreach($canales_data as $canal_data)
										<tr>
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/canales/mostrar_canal/')}}/{{$canal_data->idcanal}}">{{$canal_data->nombre}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{$canal_data->nombre_sector}}
											</td>
											<td class="text-nowrap text-center">
												@if($canal_data->deleted_at == null)
													<div style="text-align:center">
														<a class="btn btn-warning btn-sm" href="{{URL::to('/canales/editar_canal')}}/{{$canal_data->idcanal}}">
														<span class="lnr lnr-pencil"></span></a>
													</div>
												@else
													-
												@endif												
											</td>
											<!--
											<td class="text-nowrap text-center">
												@if($canal_data->deleted_at == null)	
													<a class="btn btn-info btn-sm" href="{{URL::to('/cargos_canal/listar_cargos_canal')}}/{{$canal_data->idcanal}}">
													<span class="fa fa-search"></span></a>
												@else
													-
												@endif
											</td>
											-->
											<td class="text-nowrap text-center">
												@if($canal_data->deleted_at == null)	
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
			@if($canal_search || $canal_search_sector)
				{{ $canales_data->appends(array('canal_search' => $canal_search,'canal_search_sector'=>$canal_search_sector))->links() }}
			@else
				{{ $canales_data->links() }}
			@endif
			<div class="col-md-2 col-md-offset-10" style="margin-top:-30px;margin-bottom: 10px">
				<a class="btn btn-info btn-block" style='margin-top:25px;' href="{{URL::to('canales/crear_canal')}}"><span class="lnr lnr-plus-circle" ></span> Crear Canal</a>	
			</div>	
		</div>
	@elseif($flag_seleccion==3)
		<!--ENTIDADES-->
		<div class="container-fluid">
			<h3 class="page-title">MANTENIMIENTOS/<strong>SOCIOS (ENTIDADES)</strong></h3>
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
					{{ Form::open(array('url'=>'/entidades/buscar_entidades','method'=>'get' ,'role'=>'form', 'id'=>'search-form')) }}
					<div class="row">
						<div class="col-md-3">
							{{ Form::label('codigo_entidad_search','Código de la Entidad')}}
							{{ Form::text('codigo_entidad_search',$codigo_entidad_search,array('class'=>'form-control','placeholder'=>'Ingrese código de la entidad','id'=>'codigo_entidad_search')) }}
						</div>
						<div class="col-md-3">
							{{ Form::label('entidad_search','Nombre de la Entidad')}}
							{{ Form::text('entidad_search',$entidad_search,array('class'=>'form-control','placeholder'=>'Ingrese nombre de la entidad','id'=>'entidad_search')) }}
						</div>
						<div class="col-md-3">
							{{ Form::label('entidad_search_sector','Sector')}}
							{{ Form::select('entidad_search_sector',array(''=>'Seleccione')+$sectores,$entidad_search_sector,array('class'=>'form-control','id'=>'slcSector')) }}
						</div>
						<div class="col-md-3">
							{{ Form::label('entidad_search_canal','Canal')}}
							{{ Form::select('entidad_search_canal',array(''=>'Seleccione'),$entidad_search_canal,array('class'=>'form-control','id'=>'slcCanal')) }}
						</div>						
					</div>
					<div class="row">
						<div class="form-group col-md-2 col-md-offset-8">
							{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
						</div>
						<div class="col-md-2">
							<div class="btn btn-default btn-block" id="btnLimpiarEntidad" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
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
											<th class="text-nowrap text-center">Entidad</th>
											<th class="text-nowrap text-center">Código Entidad</th>
											<th class="text-nowrap text-center">Canal</th>
											<th class="text-nowrap text-center">Sector</th>
											<th class="text-nowrap text-center">Editar</th>
											<th class="text-nowrap text-center">Ver Puntos de Venta</th>
											<th class="text-nowrap text-center">Estado</th>
										</tr>
									</thead>
									<tbody>	
										@foreach($entidades_data as $entidad_data)
										<tr>
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/entidades/mostrar_entidad/')}}/{{$entidad_data->identidad}}">{{$entidad_data->nombre}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{$entidad_data->codigo_enve}}
											</td>
											<td class="text-nowrap text-center">
												{{$entidad_data->nombre_canal}}
											</td>
											<td class="text-nowrap text-center">
												{{$entidad_data->nombre_sector}}
											</td>
											<td class="text-nowrap text-center">
												@if($entidad_data->deleted_at == null)	
													<a class="btn btn-warning btn-sm" href="{{URL::to('/entidades/editar_entidad')}}/{{$entidad_data->identidad}}">
													<span class="lnr lnr-pencil"></span></a>
												@else
													-
												@endif

											</td>
											<td class="text-nowrap text-center">
												@if($entidad_data->deleted_at == null)	
													<a class="btn btn-info btn-sm" href="{{URL::to('/puntos_venta/listar_puntos_venta')}}/{{$entidad_data->identidad}}">
													<span class="fa fa-search"></span></a>
												@else
													-
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($entidad_data->deleted_at == null)	
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
			@if($entidad_search || $entidad_search_canal || $entidad_search_sector)
				{{ $entidades_data->appends(array('entidad_search' => $entidad_search,'entidad_search_canal'=>$entidad_search_canal,'entidad_search_sector'=>$entidad_search_sector))->links() }}
			@else
				{{ $entidades_data->links() }}
			@endif
			<div class="col-md-2 col-md-offset-10" style="margin-top:-30px;margin-bottom: 10px">
				<a class="btn btn-info btn-block" style='margin-top:25px;' href="{{URL::to('entidades/crear_entidad')}}"><span class="lnr lnr-plus-circle"></span> Crear Entidad</a>	
			</div>	
		</div>
	@endif
</div>
@stop