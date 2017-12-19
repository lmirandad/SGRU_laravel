@extends('Templates/menuPrincipalTemplate')
@section('content')
<div class="main">
	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container-fluid">
			<h3 class="page-title"><strong>RESUMEN DE TICKETS POR USUARIOS</strong> </h3>
			@if (Session::has('message'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-check-circle"></i> 
					{{ Session::get('message') }}
				</div>
			@endif
			@if (Session::has('error'))
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<i class="fa fa-times-circle"></i> {{ Session::get('error') }}
				</div>
			@endif
			<div class="row">
				<div class="col-md-7 col-md-offset-3">
					<div class="panel panel-headline">
						<div class="panel-body">	
							{{ Form::open(array('url'=>'/principal/buscar_solicitud_codigo','method'=>'get' ,'role'=>'form')) }}
							<div class="row">
								<div class="col-md-5">
									{{ Form::label('search_codigo_solicitud','Código Solicitud:')}}
									{{ Form::text('search_codigo_solicitud',$search_codigo_solicitud,array('class'=>'form-control','placeholder'=>'Ingresar código solicitud','id'=>'search_codigo_solicitud')) }}
								</div>	
								<div class="form-group col-md-3">
									{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
								</div>
								<div class="col-md-3">
									<div class="btn btn-default btn-block" id="btnLimpiarCodigo" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
								</div>	
							</div>
							{{Form::close()}}
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">Resumen Tickets Por Usuario</h3>
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>

										<tr>
											<th class="text-nowrap text-center">Nombre Usuario</th>
											<th class="text-nowrap text-center">Tickets Pendientes</th>
											<th class="text-nowrap text-center">Tickets Procesando</th>
											<th class="text-nowrap text-center">Total Tickets</th>
										</tr>
									</thead>
									@foreach($resumen_usuario as $index => $usuario)
									<tbody>	
										<tr>
											<td class="text-nowrap text-center">
												{{$usuario->nombre_usuario}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{$usuario->cantidad_pendientes}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{$usuario->cantidad_procesando}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{$usuario->cantidad_total}}</a>
											</td>
										</tr>
									</tbody>
									@endforeach
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">Resumen Tickets Por Sector</h3>
						</div>
						<div class="panel-body">
							<div class="table-responsive" style="margin-bottom: 55px" >
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-nowrap text-center">Nombre Sector</th>
											<th class="text-nowrap text-center">Tickets Pendientes</th>
											<th class="text-nowrap text-center">Tickets Procesando</th>
											<th class="text-nowrap text-center">Total Tickets</th>
										</tr>
									</thead>
									@foreach($resumen_sector_total as $index => $sector)
									<tbody>	
										<tr>
											<td class="text-nowrap text-center">
												{{$sector->nombre_sector}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{$sector->cantidad_pendientes}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{$sector->cantidad_procesando}}</a>
											</td>
											<td class="text-nowrap text-center">
												{{$sector->cantidad_total}}</a>
											</td>
										</tr>
									</tbody>
									@endforeach
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">Resumen Tickets por Sector y Usuario</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-hover">
											<thead>
												<tr>
													<th class="text-nowrap text-center">Sector</th>
													<th class="text-nowrap text-center">Nombre Usuario</th>
													<th class="text-nowrap text-center">Tickets Pendientes</th>
													<th class="text-nowrap text-center">Tickets Procesando</th>
													<th class="text-nowrap text-center">Total Tickets</th>
												</tr>
											</thead>
											@for ($i = 0; $i< count($resumen_sector); $i++)
											<tbody>	
												<tr>
													<td class="text-nowrap text-center">
														@if($i>0)
															@if(strcmp($resumen_sector[$i]->nombre_sector,$resumen_sector[$i-1]->nombre_sector)!=0)
																{{$resumen_sector[$i]->nombre_sector}}
															@endif
														@else
															{{$resumen_sector[$i]->nombre_sector}}
														@endif
													</td>
													<td class="text-nowrap text-center">
														{{$resumen_sector[$i]->nombre_usuario}}</a>
													</td>
													<td class="text-nowrap text-center">
														{{$resumen_sector[$i]->cantidad_pendientes}}</a>
													</td>
													<td class="text-nowrap text-center">
														{{$resumen_sector[$i]->cantidad_procesando}}</a>
													</td>
													<td class="text-nowrap text-center">
														{{$resumen_sector[$i]->cantidad_total}}</a>
													</td>
												</tr>
											</tbody>
											@endfor
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
		</div>
	</div>
	<!-- END MAIN CONTENT -->
</div>

@stop