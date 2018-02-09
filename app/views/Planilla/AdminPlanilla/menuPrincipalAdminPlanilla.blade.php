@extends('Templates/Planilla/menuPrincipalAdminPlanillaTemplate')
@section('content')
<div class="main">
	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container-fluid">
			<h3 class="page-title"><strong>CONTROL PLANILLA</strong> </h3>
			@if (Session::has('message'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-check-circle"></i> 
					{{ Session::get('message') }}
				</div>
			@endif
			@if (Session::has('info'))
				<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-check-circle"></i> 
					{{ Session::get('info') }}
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
				<div class="col-md-8">
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title">Buscar por Usuario</h3>
						</div>
						<div class="panel-body">	
							{{ Form::open(array('url'=>'/principal_admin_planilla/buscar_cargas_usuario','method'=>'get' ,'role'=>'form')) }}
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('search_usuario','Usuario:')}}
									{{ Form::select('search_usuario',array(''=>'Seleccione')+$usuarios,$search_usuario,array('class'=>'form-control','id'=>'search_usuario')) }}
								</div>
								<div class="form-group col-md-3">	
									{{ Form::label('search_fecha','Mes') }}
									<div id="datetimepicker1" class="form-group input-group date @if($errors->first('fecha_mes')) has-error has-feedback @endif">
										{{ Form::text('search_fecha',$search_fecha,array('class'=>'form-control','placeholder'=>'Mes Solicitud','id'=>'search_fecha')) }}
										<span class="input-group-addon">
					                        <span class="glyphicon glyphicon-calendar"></span>
					                    </span>
									</div>	
								</div>		
								<div class="form-group col-md-3">
								{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
								</div>
								<div class="col-md-3">
									<div class="btn btn-default btn-block" id="btnLimpiarCriterios" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
								</div>	
							</div>
							{{Form::close()}}
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h3 class="panel-title" style="text-align:center">Descargar Consolidado</h3>
						</div>
						<div class="panel-body">
							<div class="row" >														
								<div class="form-group col-md-12" style="margin-top:-20px">									
									<a class="btn btn-success btn-block"  href="{{URL::to('principal_admin_planilla/descargar_planilla')}}"><i class="fa fa-users"></i>&nbspConsolidado</a>		
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-headline">
				<div class="panel-heading">
					<h3 class="panel-title">Listado de Cargas de Planilla</h3>
				</div>
				<div class="panel-body">	
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-nowrap text-center">N°</th>
											<th class="text-nowrap text-center">Fecha de<br>Carga</th>
											<th class="text-nowrap text-center">Usuario</th>
											<th class="text-nowrap text-center">Descargar Planilla Cargada</th>
										</tr>
									</thead>
									<tbody>
									@foreach($cargas as $index => $carga_data)
										<tr>
											<td class="text-nowrap text-center">
												{{$index+1}}
											</td>
											<td class="text-nowrap text-center">
												{{date('d-m-Y H:i:s',strtotime($carga_data->fecha_carga_archivo))}}
											</td>
											
											<td class="text-nowrap text-center">
												{{$carga_data->nombre}} {{$carga_data->apellido_paterno}} {{$carga_data->apellido_materno}}
											</td>
											<td class="text-nowrap text-center">
												<div style="text-align:center">
													<a class="btn btn-success btn-sm" href="{{URL::to('/principal_admin_planilla/descargar_base')}}/{{$carga_data->idcarga_archivo_planilla}}"><span class="lfa fa-download"></span></a>
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
		</div>
		
	</div>
	<!-- END MAIN CONTENT -->
</div>
@stop