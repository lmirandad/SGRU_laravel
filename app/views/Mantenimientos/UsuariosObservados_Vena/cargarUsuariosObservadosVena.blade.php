@extends('Templates/Mantenimientos/usuariosObservadosVenaTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title">MANTENIMIENTO/<strong>LISTA DE OBSERVADOS - VENA</strong></h3>

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
			@if ($errors->has())
				<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
			@endif
		</div>
		{{ Form::open(array('url'=>'/usuarios_observados_vena/submit_cargar_observados' ,'role'=>'form','id'=>'submit-cargar-observados','enctype'=>'multipart/form-data')) }}
		<!-- OVERVIEW -->	
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><strong>LISTA DE OBSERVADOS</strong></h3>@if($usuarios_observados_ya_cargados == 1)<h3 class="panel-title" style="color:#4dd0e1"><strong>(BASE CARGADA)</strong></h3>@endif
					</div>
					<div class="panel-body">
						<div class="row">
							@if($usuarios_observados_ya_cargados == 0)
								@if($vista_previa_ejecutado_observados == 1)
									<div class="col-md-6">
										<label class="control-label">Seleccione Archivo .CSV</label>
										<input id="input-file-observados" type="file" name="file" disabled="disabled">
									</div>
								@else
									<div class="col-md-6">
										<label class="control-label">Seleccione Archivo .CSV</label>
										<input id="input-file-observados" type="file" name="file">
									</div>
								@endif
							@else
								<div class="col-md-6">
									<label class="control-label">Seleccione Archivo .CSV</label>
									<input id="input-file-observados" type="file" name="file" disabled="disabled">
								</div>
							@endif

							@if($usuarios_observados_ya_cargados == 0)
								@if($vista_previa_ejecutado_observados == 1)
									@if($vista_previa_ejecutado_vena == 0)
										<div class="col-md-2" style="padding-top:26px">				
											<a class="btn btn-info btn-block" id="btnCargarObservados" type="submit"> <i class="fa fa-upload"></i> Cargar</a>
										</div>
									@else
										<div class="col-md-2" style="padding-top:26px">				
											<a class="btn btn-success btn-block" id="btnVistaPreviaObservados" type="submit"> <i class="fa fa-circle-o-notch "></i> Vista Previa</a>
										</div>
									@endif
								@else
									<div class="col-md-2" style="padding-top:26px">				
										<a class="btn btn-success btn-block" id="btnVistaPreviaObservados" type="submit"> <i class="fa fa-circle-o-notch "></i> Vista Previa</a>
									</div>
								@endif
							@else
								<div class="col-md-2" style="padding-top:26px">				
									<a class="btn btn-success btn-block" id="btnVistaPreviaObservados" disabled="disabled"> <i class="fa fa-circle-o-notch" ></i> Vista Previa</a>
								</div>
							@endif
							<div class="col-md-2">
								<label class="control-label">Registros Totales:</label>
								{{ Form::text('numero_registros',$cantidad_total_observados,array('class'=>'form-control','disabled'=>'disabled','style'=>'text-align:center')) }}							
							</div>
							<div class="col-md-2">
								<label class="control-label">Registros Procesados:</label>
								{{ Form::text('numero_registros',$cantidad_procesados_observados,array('class'=>'form-control','disabled'=>'disabled','style'=>'text-align:center')) }}							
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
		{{ Form::close() }}

		{{ Form::open(array('url'=>'/usuarios_observados_vena/submit_base_observados' ,'role'=>'form','id'=>'submit-upload-observados', 'enctype'=>'multipart/form-data')) }}
		@if(is_array($resultados_observados) || is_object($resultados_observados))
			@foreach($resultados_observados as $resultado)
				<input style="display:none" name='datos[]' value='{{ $resultado["fecha_bloqueo"]}}?{{ $resultado["herramienta"]}}?{{ $resultado["numero_documento"]}}' readonly/>
			@endforeach
		@endif
		{{ Form::close() }}

		{{ Form::open(array('url'=>'/usuarios_observados_vena/submit_cargar_vena' ,'role'=>'form','id'=>'submit-cargar-vena','enctype'=>'multipart/form-data')) }}
		<!-- OVERVIEW -->	
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><strong>LISTA VENA</strong></h3>@if($usuarios_vena_ya_cargados == 1)<h3 class="panel-title" style="color:#4dd0e1"><strong>(BASE CARGADA)</strong></h3>@endif
					</div>
					<div class="panel-body">
						<div class="row">
							@if($usuarios_vena_ya_cargados == 0)
								@if($vista_previa_ejecutado_vena == 1)
									<div class="col-md-6">
										<label class="control-label">Seleccione Archivo .CSV</label>
										<input id="input-file-vena" type="file" name="file" disabled="disabled">
									</div>
								@else
									<div class="col-md-6">
										<label class="control-label">Seleccione Archivo .CSV</label>
										<input id="input-file-vena" type="file" name="file">
									</div>
								@endif
							@else
								<div class="col-md-6">
									<label class="control-label">Seleccione Archivo .CSV</label>
									<input id="input-file-vena" type="file" name="file" disabled="disabled">
								</div>
							@endif
							@if($usuarios_vena_ya_cargados == 0)
								@if($vista_previa_ejecutado_vena == 1)
									@if($vista_previa_ejecutado_observados == 0)
										<div class="col-md-2" style="padding-top:26px">				
											<a class="btn btn-info btn-block" id="btnCargarVena" type="submit"> <i class="fa fa-upload"></i> Cargar</a>
										</div>
									@else
										<div class="col-md-2" style="padding-top:26px">				
											<a class="btn btn-success btn-block" id="btnVistaPreviaVena" type="submit"> <i class="fa fa-circle-o-notch "></i> Vista Previa</a>
										</div>
									@endif
								@else
									<div class="col-md-2" style="padding-top:26px">				
										<a class="btn btn-success btn-block" id="btnVistaPreviaVena" type="submit"> <i class="fa fa-circle-o-notch "></i> Vista Previa</a>
									</div>
								@endif
							@else
								<div class="col-md-2" style="padding-top:26px">				
									<a class="btn btn-success btn-block" id="btnVistaPreviaVena" type="submit" disabled="disabled"> <i class="fa fa-circle-o-notch "></i> Vista Previa</a>
								</div>
							@endif
							<div class="col-md-2">
								<label class="control-label">Registros Totales:</label>
								{{ Form::text('numero_registros',$cantidad_total_vena,array('class'=>'form-control','disabled'=>'disabled','style'=>'text-align:center')) }}							
							</div>
							<div class="col-md-2">
								<label class="control-label">Registros Procesados:</label>
								{{ Form::text('numero_registros',$cantidad_procesados_vena,array('class'=>'form-control','disabled'=>'disabled','style'=>'text-align:center')) }}							
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
		{{ Form::close() }}
	</div>
</div>

{{ Form::open(array('url'=>'/usuarios_observados_vena/submit_base_vena' ,'role'=>'form','id'=>'submit-upload-vena', 'enctype'=>'multipart/form-data')) }}
@if(is_array($resultados_vena) || is_object($resultados_vena))
	@foreach($resultados_vena as $resultado)
		<input style="display:none" name='datos[]' value='{{ $resultado["numero_documento"]}}?{{ $resultado["fecha_bloqueo"]}}?{{ $resultado["motivo"]}}' readonly/>
	@endforeach
@endif
{{ Form::close() }}
@stop