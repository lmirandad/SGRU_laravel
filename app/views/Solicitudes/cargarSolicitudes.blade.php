@extends('Templates/solicitudesTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title">SOLICITUDES/<strong>CARGA DE SOLICITUDES</strong></h3>

		<div class="row">
			<div class="col-md-12">
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
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<i class="fa fa-times-circle"></i> 
						{{ Session::get('error') }}
					</div>
				@endif
			</div>
		</div>
		{{ Form::open(array('url'=>'/solicitudes/cargar_archivo_solicitudes' ,'role'=>'form','id'=>'submit-cargar','enctype'=>'multipart/form-data')) }}
		<div class="row">
			@if ($errors->has())
				<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
			@endif
		</div>
		<!-- OVERVIEW -->	
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><strong>ADJUNTAR ARCHIVO</strong></h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-9">
								<label class="control-label">Seleccione Archivo .XSLX/.XLS</label>
								<input id="input-file" type="file" name="file">
							</div>
							<div class="col-md-3" style="padding-top:26px">				
								<a class="btn btn-info btn-block" id="btnCargar" type="submit"> <i class="fa fa-upload"></i> Cargar</a>
							</div>
						</div>	
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><strong>RESULTADO DE CARGA</strong></h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-4">
								<label class="control-label">Registros Totales:</label>
								{{ Form::text('numero_registros',$cantidad_total,array('class'=>'form-control','disabled'=>'disabled','style'=>'text-align:center')) }}							
							</div>
							<div class="col-md-4">
								<label class="control-label">Registros Procesados:</label>
								{{ Form::text('numero_registros',$cantidad_procesados,array('class'=>'form-control','disabled'=>'disabled','style'=>'text-align:center')) }}							
							</div>	
							@if($logs != null )
							<div class="form-group col-md-4" style="margin-top:25px">
								<a class="btn btn-success btn-block" id="btnDescargarLogs"><i class="fa fa-download"></i>&nbspDescargar Logs</a>				
							</div>
							@endif
						</div>	
					</div>
				</div>
			</div>
		</div>
		{{ Form::close() }}
		{{ Form::open(array('url'=>'/asignaciones/submit_asignacion' ,'role'=>'form','id'=>'submit-asignar','enctype'=>'multipart/form-data')) }}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><strong>VISTA PREVIA SOLICITUDES POR ASIGNAR</strong></h3>
			</div>		
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive" id="tabla_solicitudes">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-nowrap text-center">Código Solicitud</th>
										<th class="text-nowrap text-center">Entidad</th>
										<th class="text-nowrap text-center">Tipo Solicitud</th>
										<th class="text-nowrap text-center">Tipo Acción</th>
										<th class="text-nowrap text-center">Fecha Solicitud</th>
										<th class="text-nowrap text-center">Estado Ticket</th>
										<th class="text-nowrap text-center">Aplicación</th>
									</tr>
								</thead>
								<tbody>
								@if (is_array($resultados) || is_object($resultados))
									@foreach($resultados as $resultado)
									<tr>
										<td class="text-nowrap text-center">
											{{$resultado["codigo"]}}
											<input style="display:none" name='codigos_solicitud[]' value='{{ $resultado["codigo"]}}' readonly/>
										</td>										
										<td class="text-nowrap text-center">
											{{$resultado["entidad"]}}
											<input style="display:none" name='ids_entidad[]' value='{{ $resultado["identidad"]}}' readonly/>
										</td>
										<td class="text-nowrap text-center">
											{{$resultado["tipo_solicitud"]}}
											<input style="display:none" name='idstipo_solicitud_general[]' value='{{ $resultado["idtipo_solicitud_general"]}}' readonly/>
										</td>
										<td class="text-nowrap text-center">
											{{$resultado["tipo_accion"]}}
											<input style="display:none" name='idstipo_solicitud[]' value='{{ $resultado["idtipo_solicitud"]}}' readonly/>
										</td>
										<td class="text-nowrap text-center">
											{{date('d-m-Y',strtotime($resultado["fecha_solicitud"]))}}
											
											<input style="display:none" name='fechas_solicitud[]' value="{{date('d-m-Y',strtotime($resultado["fecha_solicitud"]))}}" readonly/>
										</td>
										<td class="text-nowrap text-center">
											{{$resultado["estado_solicitud"]}}
											<input style="display:none" name='idsestado_solicitud[]' value='{{ $resultado["idestado_solicitud"]}}' readonly/>
										</td>
										<td class="text-nowrap text-center">
											{{$resultado["nombre_herramienta"]}}
											<input style="display:none" name='asuntos[]' value='{{ $resultado["asunto"]}}' readonly/>
											<input style="display:none" name='idherramientas[]' value='{{ $resultado["idherramienta"]}}' readonly/>
										</td>
										
											<input style="display:none" name='fechas_estado[]' value="{{date('d-m-Y',strtotime($resultado["fecha_estado"]))}}" readonly/>
										
									</tr>
									@endforeach
								@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><strong>VISTA PREVIA SOLICITUDES POR RECHAZAR</strong></h3>
			</div>		
			<div class="panel-body">
				<div class="row" >
					<div class="col-md-12">
						<div class="table-responsive" id="tabla_solicitudes">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-nowrap text-center">Código Solicitud</th>
										<th class="text-nowrap text-center">Nombre Entidad</th>
										<th class="text-nowrap text-center">Tipo Solicitud</th>
										<th class="text-nowrap text-center">Fecha Solicitud</th>
										<th class="text-nowrap text-center">Aplicativo Detectado</th>
									</tr>
								</thead>
								<tbody>
								@if (is_array($solicitudes_por_rechazar) || is_object($solicitudes_por_rechazar))
									@foreach($solicitudes_por_rechazar as $solicitudes)
									<tr>
										<td class="text-nowrap text-center">
											<input style="display:none" name='codigos_solicitud_rechazo[]' value='{{ $solicitudes["codigo"]}}' readonly/>
											{{$solicitudes["codigo"]}}
										</td>										
										<td class="text-nowrap text-center">
											<input style="display:none" name='ids_entidad_rechazo[]' value='{{ $solicitudes["identidad"]}}' readonly/>
											{{ $solicitudes["nombre_entidad"]}}
										</td>
										<td class="text-nowrap text-center">
											<input style="display:none" name='idstipo_solicitud_general_rechazo[]' value='{{ $solicitudes["idtipo_solicitud_general"]}}' readonly/>
											@if($solicitudes["nombre_tipo"] == null)
												NO DETECTADO
											@else
												{{$solicitudes["nombre_tipo"]}}
											@endif
										</td>
										<td class="text-nowrap text-center">
											<input style="display:none" name='fechas_solicitud_rechazo[]' value="{{date('d-m-Y',strtotime($solicitudes["fecha_solicitud"]))}}" readonly/>
											{{date('d-m-Y',strtotime($solicitudes["fecha_solicitud"]))}}
										</td>

										
											<input style="display:none" name='fechas_estado_rechazo[]' value="{{date('d-m-Y',strtotime($solicitudes["fecha_estado"]))}}" readonly/>
											
										
										<td class="text-nowrap text-center">
											<input style="display:none" name='ids_herramienta[]' value="{{$solicitudes["idherramienta"]}}" readonly/>
											@if($solicitudes["nombre_herramienta"] == null)
												NO DETECTADO
											@else
												{{$solicitudes["nombre_herramienta"]}}
											@endif
										</td>
									</tr>
									@endforeach
								@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="form-group col-md-2">				
				<a class="btn btn-info btn-block" id="btnAsignar" > <i class="lnr lnr-location"></i>&nbsp&nbspAsignar</a>
			</div>
			<div class="form-group col-md-2 col-md-offset-8">
				<a class="btn btn-default btn-block" href="{{URL::to('/principal_admin')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>		
			</div>
		{{Form::close()}}
		
		{{ Form::open(array('url'=>'/solicitudes/descargar_logs' ,'role'=>'form','id'=>'submit-descargar-logs', 'enctype'=>'multipart/form-data')) }}
			<input style="display:none" name='logs' value='{{ $logs }}' readonly/>			
		{{Form::close()}}
			
		</div>
	</div>
</div>


@stop