@extends('Templates/Mantenimientos/usuariosTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title">MANTENIMIENTOS/<strong>USUARIOS</strong></h3>
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
				{{ Form::open(array('url'=>'/usuarios/buscar_usuarios','method'=>'get' ,'role'=>'form', 'id'=>'search-form')) }}
				<div class="row">
					<div class="col-md-3">
						{{ Form::label('search','Nombre del Usuario')}}
						{{ Form::text('search',$search,array('class'=>'form-control','placeholder'=>'Ingrese nombre del usuario','id'=>'search')) }}
					</div>
					<div class="col-md-3">
						{{ Form::label('search_tipo_doc_identidad','Tipo Documento de Identidad')}}
						{{ Form::select('search_tipo_doc_identidad',array('0'=>'Seleccione')+$tipo_doc_identidad,$search_tipo_doc_identidad,array('class'=>'form-control','id'=>'search_tipo_doc_identidad')) }}
					</div>
					<div class="col-md-3">
						{{ Form::label('search_documento_identidad','Número de Identidad')}}
						{{ Form::text('search_documento_identidad',$search_documento_identidad,array('class'=>'form-control','placeholder'=>'Ingrese número de identidad','id'=>'search_documento_identidad','disabled'=>'disabled')) }}
					</div>
					<div class="col-md-3">
						{{ Form::label('search_herramienta','Herramienta (Aplicativo)')}}
						{{ Form::select('search_herramienta',array('0'=>'Seleccione')+$herramientas,$search_herramienta,array('class'=>'form-control')) }}
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
										<th class="text-nowrap text-center">Usuario</th>
										<th class="text-nowrap text-center">Nombres</th>
										<th class="text-nowrap text-center">Apellidos</th>
										<th class="text-nowrap text-center">Doc. de identidad</th>
										<th class="text-nowrap text-center">Rol</th>
										<th class="text-nowrap text-center">Editar</th>
										<th class="text-nowrap text-center">Aplicativos Especializados</th>
										<th class="text-nowrap text-center">Sectores Asignados</th>
										<th class="text-nowrap text-center">Habilitado/Inhabilitado</th>
									</tr>
								</thead>
								<tbody>	
									@foreach($users_data as $user_data)
									<tr class="">
										<td class="text-nowrap text-center">
											<a href="{{URL::to('/usuarios/mostrar_usuario/')}}/{{$user_data->id}}">{{$user_data->username}}</a>
										</td>
										<td class="text-nowrap text-center">
											{{$user_data->nombre}}
										</td>
										<td class="text-nowrap text-center">
											{{$user_data->apellido_paterno}} {{$user_data->apellido_materno}}
										</td>
										<td class="text-nowrap text-center">
											{{$user_data->numero_doc_identidad}}
										</td>
										<td class="text-nowrap text-center">
											{{$user_data->nombre_rol}}
										</td>
										<td class="text-nowrap">
											<div style="text-align:center">
												<a class="btn btn-warning btn-sm" href="{{URL::to('/usuarios/editar_usuario')}}/{{$user_data->id}}">
												<span class="lnr lnr-pencil"></span></a>
											</div>
										</td>
										<td class="text-nowrap">
											@if($user_data->idrol != 1)
												<div style="text-align:center">
													<a class="btn btn-info  btn-sm" href="{{URL::to('/usuarios/mostrar_herramientas_usuario')}}/{{$user_data->id}}">
													<span class="fa fa-search"></span></a>
												</div>		
											@endif
										</td>
										<td class="text-nowrap">
											@if($user_data->idrol != 1)
												<div style="text-align:center">
													<a class="btn btn-info  btn-sm" href="{{URL::to('/usuarios/mostrar_sectores_usuario')}}/{{$user_data->id}}">
													<span class="fa fa-search"></span></a>
												</div>
											@endif
										</td>
										<td class="text-nowrap">
											<div style="text-align:center">
												@if($user_data->deleted_at)
													Inhabilitado
												@else
													Habilitado
												@endif
											</div>
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
		@if($search || $search_tipo_doc_identidad || $search_documento_identidad || $search_herramienta)
			{{ $users_data->appends(array('search' => $search,'search_tipo_doc_identidad'=>$search_tipo_doc_identidad,
			'search_documento_identidad'=>$search_documento_identidad,'search_herramienta'=>$search_herramienta))->links() }}
		@else
			{{ $users_data->links() }}
		@endif
		<div class="form-group col-md-2 col-md-offset-10">
			<a href="{{URL::to('usuarios/crear_usuario')}}" class="btn btn-info"><span class="lnr lnr-plus-circle"></span>&nbsp Crear Nuevo Usuario</a>
		</div>
	</div>
</div>
@stop