@extends('Templates/reporteriaTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>REPORTES SOLICITUDES y REQUERIMIENTOS</strong></h3>
		<div class="row">
			<div class="col-md-12">
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
		</div>
			{{ Form::open(array('url'=>'/generar_reporte_solicitudes','method'=>'get' ,'role'=>'form', 'id'=>'search-form')) }}
			<div class="row">
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title"><strong>EXPORTAR BASE DE SOLICITUDES</strong></h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-3 col-md-offset-1">
									{{ Form::label('fecha_desde','Fecha Desde:') }}
									<div id="search_datetimepicker1_1" class="form-group input-group date @if($errors->first('fecha_desde')) has-error has-feedback @endif">
										{{ Form::text('fecha_desde',$fecha_desde,array('class'=>'form-control','placeholder'=>'Ingresar fecha','id'=>'fecha_desde')) }}
										<span class="input-group-addon">
					                        <span class="glyphicon glyphicon-calendar"></span>
					                    </span>
									</div>	
								</div>
								<div class="col-md-3">
									{{ Form::label('fecha_hasta','Fecha Hasta:') }}
									<div id="search_datetimepicker2_1" class="form-group input-group date @if($errors->first('fecha_hasta')) has-error has-feedback @endif">
										{{ Form::text('fecha_hasta',$fecha_hasta,array('class'=>'form-control','placeholder'=>'Ingresar fecha','id'=>'fecha_hasta')) }}
										<span class="input-group-addon">
					                        <span class="glyphicon glyphicon-calendar"></span>
					                    </span>
									</div>	
								</div>
								<div class="form-group col-md-2">
									{{ Form::button('<span class="fa fa-search""></span> Buscar', array('type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
								</div>
								<div class="col-md-2">
									<div class="btn btn-default btn-block" id="btnLimpiar" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>	
			{{ Form::close() }}	
		
	</div>		
</div>
@stop