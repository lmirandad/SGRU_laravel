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
				<div class="col-md-8 col-md-offset-2">
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">Criterios de Búsqueda</h3>
						</div>
						<div class="panel-body">
							{{ Form::open(array('url'=>'/sectores/buscar_sectores','method'=>'get' ,'role'=>'form', 'id'=>'search-form')) }}
							<div class="row">
								<div class="col-md-5">
									{{ Form::label('sector_search','Nombre del Sector')}}
									{{ Form::text('sector_search',$sector_search,array('class'=>'form-control','placeholder'=>'Ingrese nombre del sector','id'=>'search')) }}
								</div>
								<div class="form-group col-md-3 col-md-offset-1">
								{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
								</div>
								<div class="col-md-3">
									<div class="btn btn-default btn-block" id="btnLimpiarSector" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
								</div>				
							</div>
							{{ Form::close() }}					
						</div>
					</div>
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
											<th class="text-nowrap text-center">Nombre del Sector</th>
											<th class="text-nowrap text-center">Descripcion</th>
											<th class="text-nowrap text-center">Editar</th>
										</tr>
									</thead>
									<tbody>	
										@foreach($sectores_data as $sector_data)
										<tr class="@if($sector_data->deleted_at) bg-danger @endif">
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/sectores/mostrar_sector/')}}/{{$sector_data->idsector}}">{{$sector_data->nombre}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{$sector_data->descripcion}}
											</td>
											<td class="text-nowrap">
												<a class="btn btn-warning btn-block btn-sm" href="{{URL::to('/sectores/editar_sector')}}/{{$sector_data->idsector}}">
												<span class="lnr lnr-pencil"></span></a>
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
			@if($sector_search)
				{{ $sectores_data->appends(array('sector_search' => $sector_search))->links() }}
			@else
				{{ $sectores_data->links() }}
			@endif
			<div class="col-md-2 col-md-offset-10" style="margin-top:-30px;margin-bottom: 10px">
				<a class="btn btn-info btn-block" style='margin-top:25px;'><span class="lnr lnr-plus-circle"></span> Crear Sector</a>	
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
							{{ Form::text('canal_search',$canal_search,array('class'=>'form-control','placeholder'=>'Ingrese nombre del canal','id'=>'search')) }}
						</div>
						<div class="col-md-4">
							{{ Form::label('canal_search_sector','Sector')}}
							{{ Form::select('canal_search_sector',array('0'=>'Seleccione')+$sectores,$canal_search_sector,array('class'=>'form-control')) }}
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
											<th class="text-nowrap text-center">Nombre del Canal</th>
											<th class="text-nowrap text-center">Sector</th>
											<th class="text-nowrap text-center">Editar</th>
										</tr>
									</thead>
									<tbody>	
										@foreach($canales_data as $canal_data)
										<tr class="@if($canal_data->deleted_at) bg-danger @endif">
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/canales/mostrar_canal/')}}/{{$canal_data->idcanal}}">{{$canal_data->nombre}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{$canal_data->nombre_sector}}
											</td>
											<td class="text-nowrap">
												<a class="btn btn-warning btn-block btn-sm" href="{{URL::to('/canales/editar_canal')}}/{{$canal_data->idcanal}}">
												<span class="lnr lnr-pencil"></span></a>
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
			<div class="col-md-2 col-md-offset-10" style="margin-top:-30px">
				<a class="btn btn-info btn-block" style='margin-top:25px;'><span class="lnr lnr-plus-circle"></span> Crear Canal</a>	
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
						<div class="col-md-4">
							{{ Form::label('entidad_search','Nombre de la Entidad')}}
							{{ Form::text('entidad_search',$entidad_search,array('class'=>'form-control','placeholder'=>'Ingrese nombre de la entidad','id'=>'search')) }}
						</div>
						<div class="col-md-4">
							{{ Form::label('entidad_search_canal','Canal')}}
							{{ Form::select('entidad_search_canal',array('0'=>'Seleccione')+$canales,$entidad_search_canal,array('class'=>'form-control')) }}
						</div>
						<div class="col-md-4">
							{{ Form::label('entidad_search_sector','Sector')}}
							{{ Form::select('entidad_search_sector',array('0'=>'Seleccione')+$sectores,$entidad_search_sector,array('class'=>'form-control')) }}
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-2 col-md-offset-8">
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
											<th class="text-nowrap text-center">Entidad</th>
											<th class="text-nowrap text-center">Canal</th>
											<th class="text-nowrap text-center">Sector</th>
											<th class="text-nowrap text-center">Editar</th>
										</tr>
									</thead>
									<tbody>	
										@foreach($entidades_data as $entidad_data)
										<tr class="@if($entidad_data->deleted_at) bg-danger @endif">
											<td class="text-nowrap text-center">
												<a href="{{URL::to('/entidades/mostrar_entidad/')}}/{{$entidad_data->identidad}}">{{$entidad_data->nombre}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{$entidad_data->nombre_canal}}
											</td>
											<td class="text-nowrap text-center">
												{{$entidad_data->nombre_sector}}
											</td>
											<td class="text-nowrap">
												<a class="btn btn-warning btn-block btn-sm" href="{{URL::to('/entidades/editar_entidad')}}/{{$entidad_data->identidad}}">
												<span class="lnr lnr-pencil"></span></a>
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
				<a class="btn btn-info btn-block" style='margin-top:25px;'><span class="lnr lnr-plus-circle"></span> Crear Entidad</a>	
			</div>	
		</div>
	@endif
</div>
@stop