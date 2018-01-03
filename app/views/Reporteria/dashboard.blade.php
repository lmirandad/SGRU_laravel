@extends('Templates/dashboardTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>DASHBOARD SOLICITUDES y REQUERIMIENTOS</strong></h3>
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
		<div class="row">
			<div class="col-md-9 col-md-offset-2">
				<div class="container-fluid">				
					<div class="panel panel-headline">
						<div class="panel-heading">
							<h4 class="panel-title" >Seleccione una Opción</h4>
						</div>	
						<div class="panel-body" style="margin-top:-40px">
							<div class="row">
								<div class="col-md-3">
									<a class="btn btn-default btn-block" href="{{URL::to('dashboard/1')}}" style='margin-top:25px;'></span>1. Dashboard Anual</a>
								</div>
								<div class="col-md-3">
									<a class="btn btn-default btn-block" href="{{URL::to('dashboard/2')}}" style='margin-top:25px;'>2. Dashboard Mensual</a>
								</div>
								<div class="col-md-3">
									<a class="btn btn-default btn-block" href="{{URL::to('dashboard/1')}}" style='margin-top:25px;'></span>3. Dashboard Semanal</a>
								</div>
								<div class="col-md-3">
									<a class="btn btn-default btn-block" href="{{URL::to('dashboard/2')}}" style='margin-top:25px;'>4. Dashboard Gestor</a>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@if($flag_seleccion == 1)
			<h3 class="page-title"><strong>DASHBOARD ANUAL</strong></h3>
			<div class="row">
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title"><strong>SELECCIONAR AÑO y/o USUARIO</strong></h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-3 col-md-offset-1">
									{{ Form::label('fecha_anho','Año:') }}
									<div id="search_datetimepicker1" class="form-group input-group date @if($errors->first('fecha_anho')) has-error has-feedback @endif">
										{{ Form::text('fecha_anho',Input::old('fecha_anho'),array('class'=>'form-control','placeholder'=>'Año','id'=>'anho')) }}
										<span class="input-group-addon">
					                        <span class="glyphicon glyphicon-calendar"></span>
					                    </span>
									</div>	
								</div>	
								<div class="col-md-3 @if($errors->first('usuario')) has-error has-feedback @endif"">
									{{ Form::label('usuario','Gestor:')}}
									{{ Form::select('usuario',array(''=>'Seleccione') + $usuarios,Input::old('usuario'),array('class'=>'form-control','id'=>'usuario')) }}
								</div>
								<div class="form-group col-md-2">
									{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form-1','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
								</div>
								<div class="col-md-2">
									<div class="btn btn-default btn-block" id="btnLimpiar" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title"><strong>CANTIDAD DE TICKETS POR ESTADO DE SOLICITUD</strong></h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12" id="divEvolutivoPorEstado">
									<canvas id="evolutivoPorEstado"></canvas>
								</div>	
							</div>	
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title"><strong>CANTIDAD DE TICKETS POR SECTOR</strong></h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12"  id="divEvolutivoPorSector">
									<canvas id="evolutivoPorSector"></canvas>
								</div>	
							</div>	
						</div>
					</div>
				</div>
			</div>
		@else
			<h3 class="page-title"><strong>DASHBOARD MENSUAL</strong></h3>
			<div class="row">
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title"><strong>SELECCIONAR MES y/o USUARIO</strong></h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-3 col-md-offset-1">
									{{ Form::label('fecha_mes_anho','Mes:') }}
									<div id="search_datetimepicker2" class="form-group input-group date @if($errors->first('fecha_mes_anho')) has-error has-feedback @endif">
										{{ Form::text('fecha_mes_anho',Input::old('fecha_mes_anho'),array('class'=>'form-control','placeholder'=>'Mes','id'=>'mes')) }}
										<span class="input-group-addon">
					                        <span class="glyphicon glyphicon-calendar"></span>
					                    </span>
									</div>	
								</div>	
								<div class="col-md-3 @if($errors->first('usuario')) has-error has-feedback @endif"">
									{{ Form::label('usuario','Gestor:')}}
									{{ Form::select('usuario',array(''=>'Seleccione') + $usuarios,Input::old('usuario'),array('class'=>'form-control','id'=>'usuario_2')) }}
								</div>
								<div class="form-group col-md-2">
									{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form-2','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
								</div>
								<div class="col-md-2">
									<div class="btn btn-default btn-block" id="btnLimpiar" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title"><strong>CANTIDAD DE TICKETS POR ESTADO DE SOLICITUD</strong></h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12" id="divMesEstado">
									<canvas id="mesEstado"></canvas>
								</div>	
							</div>	
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title"><strong>CANTIDAD DE TICKETS POR SECTOR</strong></h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12" id="divMesSector">
									<canvas id="mesSector"></canvas>
								</div>	
							</div>	
						</div>
					</div>
				</div>
			</div>
		@endif
	</div>		
</div>
@stop