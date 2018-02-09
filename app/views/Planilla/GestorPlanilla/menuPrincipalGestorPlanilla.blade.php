@extends('Templates/Planilla/menuPrincipalGestorPlanillaTemplate')
@section('content')
<div class="main">
	<!-- MAIN CONTENT -->
	<div class="main-content">
		<div class="container-fluid">
			<h3 class="page-title"><strong>CONTROL PLANILLA</strong> &nbsp&nbsp&nbsp&nbsp<a class="btn btn-success btn-sm" href="{{URL::to('/principal_gestor_planilla/descargar_plantilla_gestor')}}"><span class="fa fa-download"></span>Descargar Plantilla Base Planilla</a> </h3>
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
			
			{{ Form::open(array('url'=>'/principal_gestor_planilla/probar_carga' ,'role'=>'form','id'=>'submit-probar-planilla','enctype'=>'multipart/form-data')) }}
			<!-- OVERVIEW -->	
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><strong>CARGAR PLANILLA</strong></h3>
						</div>
						<div class="panel-body">
							<div class="row">
								@if($base_cargada == false)
									@if($archivo_subido == false)
										<div class="col-md-4">
											<label class="control-label">Seleccione Archivo .XLS/.XLSX</label>
											<input id="input-file-planilla" type="file" name="file">
										</div>
									@else
										<div class="col-md-4">
											<label class="control-label">Seleccione Archivo .XLS/.XLSX</label>
											<input id="input-file-planilla" type="file" name="file" disabled="disabled">
										</div>
									@endif


									@if($archivo_subido == false)
										<div class="col-md-2" style="padding-top:26px">				
											<a class="btn btn-info btn-block" id="btnProbarCarga" type="submit"> <i class="fa fa-upload"></i> Probar</a>
										</div>
									@else
										<div class="col-md-2" style="padding-top:26px">				
											<a class="btn btn-info btn-block" id="btnCargar" type="submit"> <i class="fa fa-upload"></i> Cargar</a>
										</div>
									@endif
								@else
									<div class="col-md-4">
										<label class="control-label">Seleccione Archivo .XLS/.XLSX</label>
										<input id="input-file-planilla" type="file" name="file" disabled="disabled">
									</div>
									<div class="col-md-2" style="padding-top:26px">				
										<a class="btn btn-info btn-block" id="#" type="submit" disabled="disabled"> <i class="fa fa-upload"></i> Probar</a>
									</div>
								@endif
								
			{{ Form::close() }}	
														
								<div class="col-md-2">
								<label class="control-label">Registros Totales:</label>
									{{ Form::text('numero_registros',$cantidad_registros,array('class'=>'form-control','disabled'=>'disabled','style'=>'text-align:center')) }}							
								</div>
								<div class="col-md-2">
									<label class="control-label">Registros Procesados:</label>
									{{ Form::text('numero_registros',$cantidad_registros_procesados,array('class'=>'form-control','disabled'=>'disabled','style'=>'text-align:center')) }}							
								</div>	
								@if($logs != null )
								<div class="form-group col-md-2" style="margin-top:25px">
									<a class="btn btn-success btn-block" id="btnDescargarLogs"><i class="fa fa-download"></i>&nbspDescargar Logs</a>				
								</div>
								@endif
							</div>	
						</div>
					</div>
				</div>
			</div>
			{{ Form::open(array('url'=>'/principal_gestor_planilla/submit_carga' ,'role'=>'form','id'=>'submit-cargar-planilla', 'enctype'=>'multipart/form-data')) }}
			@if (is_array($registros) || is_object($registros))
				@foreach($registros as $resultado)
					<input style="display:none" name='tipos_documento[]' value='{{ $resultado["tipo_documento"]}}' readonly/>
					<input style="display:none" name='numeros_documento[]' value='{{ $resultado["numero_documento"]}}' readonly/>
					<input style="display:none" name='nombres[]' value='{{ $resultado["nombre"]}}' readonly/>
					<input style="display:none" name='apellidos_paterno[]' value='{{ $resultado["apellido_paterno"]}}' readonly/>
					<input style="display:none" name='apellidos_materno[]' value='{{$resultado["apellido_materno"]}}' readonly/>
					<input style="display:none" name='canales[]' value='{{ $resultado["canal"]}}' readonly/>
					<input style="display:none" name='detalle_canales[]' value='{{ $resultado["detalle_canal"]}}' readonly/>
					<input style="display:none" name='subdetalle_canales[]' value='{{ $resultado["subdetalle_canal"]}}' readonly/>					
					<input style="display:none" name='socios[]' value='{{$resultado["socio"]}}' readonly/>
					<input style="display:none" name='rucs[]' value='{{$resultado["ruc"]}}' readonly/>
					<input style="display:none" name='entidades[]' value='{{$resultado["entidad"]}}' readonly/>
					<input style="display:none" name='puntos_venta[]' value='{{$resultado["punto_venta"]}}' readonly/>
					<input style="display:none" name='roles[]' value='{{$resultado["rol"]}}' readonly/>
				@endforeach
			@endif
			{{Form::close()}}
			{{ Form::open(array('url'=>'/principal_gestor_planilla/descargar_logs' ,'role'=>'form','id'=>'submit-descargar-logs-planilla', 'enctype'=>'multipart/form-data')) }}
				<input style="display:none" name='logs' value='{{ $logs }}' readonly/>			
			{{Form::close()}}
		</div>
		
	</div>
	<!-- END MAIN CONTENT -->
</div>
@stop