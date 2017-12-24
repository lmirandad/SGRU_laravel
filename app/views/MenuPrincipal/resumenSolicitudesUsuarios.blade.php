@extends('Templates/menuPrincipalTemplate')
@section('content')
<div class="main">
	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container-fluid">
			<h3 class="page-title"><strong>RESUMEN DE TICKETS POR SECTORES Y USUARIOS</strong> </h3>
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
							{{ Form::open(array('url'=>'/principal/resumen_usuarios_mes','method'=>'get' ,'role'=>'form')) }}
							<div class="row">
								<div class="form-group col-md-5">	
									{{ Form::label('search_fecha','Seleccionar Mes:') }}
									<div id="datetimepicker_resumen" class="form-group input-group date @if($errors->first('fecha_nacimiento')) has-error has-feedback @endif">
										{{ Form::text('search_fecha',$search_fecha,array('class'=>'form-control','placeholder'=>'Mes Solicitud','id'=>'search_fecha_resumen')) }}
										<span class="input-group-addon">
					                        <span class="glyphicon glyphicon-calendar"></span>
					                    </span>
									</div>	
								</div>	
								<div class="form-group col-md-3">
									{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
								</div>
								<div class="col-md-3">
									<div class="btn btn-default btn-block" id="btnLimpiarFecha" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
								</div>	
							</div>
							{{Form::close()}}
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
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
									@if(is_array($resumen_usuario) == true)
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
									@endif
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
										<table class="table table-hover" style="border-collapse: separate;">
											<thead>
												<tr>
													<th class="text-nowrap text-center">Sector</th>
													<th class="text-nowrap text-center">Nombre Usuario</th>
													<th class="text-nowrap text-center">Tickets Pendientes</th>
													<th class="text-nowrap text-center">Tickets Procesando</th>
													<th class="text-nowrap text-center">Total Tickets</th>
												</tr>
											</thead>	
											@if(is_array($resumen_sector) == true && is_array($resumen_sector_total) == true)									
												@for ($i = 0; $i< count($resumen_sector); $i++)
												<tbody>	
													@if($i>0)
														@if($i< count($resumen_sector)-1)
															@if(strcmp($resumen_sector[$i]->nombre_sector,$resumen_sector[$i-1]->nombre_sector)!=0)
																<tr style="background-color:#e3e7e7">	
																	<td class="text-nowrap text-center"  colspan="2">
																		<strong>TOTAL {{$resumen_sector[$i-1]->nombre_sector}}</strong>
																	</td>
																	<td class="text-nowrap text-center">
																		<strong>{{$resumen_sector_total[$contador_sector_total]->cantidad_pendientes}}</strong>
																	</td>	
																	<td class="text-nowrap text-center">
																		<strong>{{$resumen_sector_total[$contador_sector_total]->cantidad_procesando}}</strong>
																	</td>	
																	<td class="text-nowrap text-center">
																		<strong>{{$resumen_sector_total[$contador_sector_total]->cantidad_total}}</strong>
																	</td>	
																	<div style="display:none">{{++$contador_sector_total}}</div>
																</tr>
																<tr>	
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->nombre_sector}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->nombre_usuario}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->cantidad_pendientes}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->cantidad_procesando}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->cantidad_total}}
																	</td>
																</tr>
															@else
																<tr>
																	<td style="border:0px"></td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->nombre_usuario}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->cantidad_pendientes}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->cantidad_procesando}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->cantidad_total}}
																	</td>
																</tr>
															@endif									
														@elseif ($i == count($resumen_sector)-1) <!-- OSEA EL ULTIMO -->
															@if(strcmp($resumen_sector[$i]->nombre_sector,$resumen_sector[$i-1]->nombre_sector)!=0)
																<tr style="background-color:#e3e7e7">		
																	<td class="text-nowrap text-center" colspan="2">
																		<strong>TOTAL {{$resumen_sector[$i-1]->nombre_sector}}</strong>
																	</td>
																	<td class="text-nowrap text-center">
																		<strong>{{$resumen_sector_total[$contador_sector_total]->cantidad_pendientes}}</strong>
																	</td>	
																	<td class="text-nowrap text-center">
																		<strong>{{$resumen_sector_total[$contador_sector_total]->cantidad_procesando}}</strong>
																	</td>	
																	<td class="text-nowrap text-center">
																		<strong>{{$resumen_sector_total[$contador_sector_total]->cantidad_total}}</strong>
																	</td>	
																	<div style="display:none">{{++$contador_sector_total}}</div>			
																</tr>
																<tr>	
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->nombre_sector}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->nombre_usuario}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->cantidad_pendientes}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->cantidad_procesando}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->cantidad_total}}
																	</td>
																</tr>
															@else
																<tr>
																	<td style="border:0px"></td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->nombre_usuario}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->cantidad_pendientes}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->cantidad_procesando}}
																	</td>
																	<td class="text-nowrap text-center">
																		{{$resumen_sector[$i]->cantidad_total}}
																	</td>
																</tr>
																<tr style="background-color:#e3e7e7">	
																	<td class="text-nowrap text-center"  colspan="2">
																		<strong>TOTAL {{$resumen_sector[$i]->nombre_sector}}</strong>
																	</td>
																	<td class="text-nowrap text-center">
																		<strong>{{$resumen_sector_total[$contador_sector_total]->cantidad_pendientes}}</strong>
																	</td>	
																	<td class="text-nowrap text-center">
																		<strong>{{$resumen_sector_total[$contador_sector_total]->cantidad_procesando}}</strong>
																	</td>	
																	<td class="text-nowrap text-center">
																		<strong>{{$resumen_sector_total[$contador_sector_total]->cantidad_total}}</strong>
																	</td>	
																	<div style="display:none">{{++$contador_sector_total}}</div>
																</tr>
															@endif
														@else
															<tr>
																<td class="text-nowrap text-center">
																	{{$resumen_sector[$i]->nombre_sector}}
																</td>
																<td class="text-nowrap text-center">
																	{{$resumen_sector[$i]->nombre_usuario}}
																</td>
																<td class="text-nowrap text-center">
																	{{$resumen_sector[$i]->cantidad_pendientes}}
																</td>
																<td class="text-nowrap text-center">
																	{{$resumen_sector[$i]->cantidad_procesando}}
																</td>
																<td class="text-nowrap text-center">
																	{{$resumen_sector[$i]->cantidad_total}}
																</td>
															</tr>
														@endif
													@else
														<tr>
															<td class="text-nowrap text-center">{{$resumen_sector[$i]->nombre_sector}}</td>
															<td class="text-nowrap text-center">
																{{$resumen_sector[$i]->nombre_usuario}}
															</td>
															<td class="text-nowrap text-center">
																{{$resumen_sector[$i]->cantidad_pendientes}}
															</td>
															<td class="text-nowrap text-center">
																{{$resumen_sector[$i]->cantidad_procesando}}
															</td>
															<td class="text-nowrap text-center">
																{{$resumen_sector[$i]->cantidad_total}}
															</td>
														</tr>
														<tr style="background-color:#e3e7e7">	
															<td class="text-nowrap text-center"  colspan="2">
																<strong>TOTAL {{$resumen_sector[$i]->nombre_sector}}</strong>
															</td>
															<td class="text-nowrap text-center">
																<strong>{{$resumen_sector_total[$contador_sector_total]->cantidad_pendientes}}</strong>
															</td>	
															<td class="text-nowrap text-center">
																<strong>{{$resumen_sector_total[$contador_sector_total]->cantidad_procesando}}</strong>
															</td>	
															<td class="text-nowrap text-center">
																<strong>{{$resumen_sector_total[$contador_sector_total]->cantidad_total}}</strong>
															</td>	
															<div style="display:none">{{++$contador_sector_total}}</div>
														</tr>
													@endif
												</tbody>
												@endfor
											@endif
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