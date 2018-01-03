@extends('Templates/solicitudesTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title">SOLICITUD <strong>N° {{$solicitud->codigo_solicitud}}</strong></h3>
		@if ($errors->has())
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<p>{{ $errors->first('usuario_disponible') }}</p>		
				<p>{{ $errors->first('motivo_reasignacion') }}</p>
				<p>{{ $errors->first('motivo_anulacion') }}</p>
			</div>
		@endif

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
		<!-- OVERVIEW -->
		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Datos Generales</h3>
			</div>
			<div class="panel-body">				
				<div class="row">
					<div class="col-md-3">
						{{ Form::label('codigo_solicitud','Código de Solicitud')}}
						{{ Form::text('codigo_solicitud',$solicitud->codigo_solicitud,array('class'=>'form-control','placeholder'=>'Ingrese nombre del usuario','id'=>'codigo_solicitud','disabled'=>'disabled')) }}
					</div>
					<div class="col-md-3">
						{{ Form::label('fecha_solicitud','Fecha de solicitud') }}
						<div id="search_datetimepicker1" class="form-group input-group date @if($errors->first('fecha_solicitud')) has-error has-feedback @endif">
							{{ Form::text('fecha_solicitud',date('d-m-Y',strtotime($solicitud->fecha_solicitud)),array('class'=>'form-control','placeholder'=>'Fecha de Solicitud Desde','disabled'=>'disabled')) }}
							<span class="input-group-addon">
		                        <span class="glyphicon glyphicon-calendar"></span>
		                    </span>
						</div>	
					</div>
					<div class="col-md-3">
						{{ Form::label('tipo_solicitud','Tipo de Accion')}}
						@if($solicitud->idtipo_solicitud != null)
							{{ Form::select('tipo_solicitud',array('0'=>'Seleccione')+$tipos_solicitud,$solicitud->idtipo_solicitud,array('class'=>'form-control','disabled'=>'disabled')) }}
						@else
							{{ Form::text('tipo_solicitud','NO DETECTADO',array('class'=>'form-control','placeholder'=>'Ingrese nombre del usuario','disabled'=>'disabled')) }}
						@endif
					</div>
					<div class="col-md-3">
						{{ Form::label('estado_solicitud','Estado Solicitud')}}
						{{ Form::select('estado_solicitud',array('0'=>'Seleccione')+$estados_solicitud,$solicitud->idestado_solicitud,array('class'=>'form-control','disabled'=>'disabled')) }}
					</div>
				</div>
				<div class="row">					
					<div class="col-md-3">
						{{ Form::label('tipo_solicitud_general','Tipo Solicitud')}}
						{{ Form::select('tipo_solicitud_general',array('0'=>'Seleccione')+$tipos_solicitud_general,$solicitud->idtipo_solicitud_general,array('class'=>'form-control','disabled'=>'disabled')) }}
					</div>					
					<div class="col-md-3">
						{{ Form::label('entidad','Entidad (Socio)')}}
						{{ Form::text('entidad',$entidad->nombre,array('class'=>'form-control','disabled'=>'disabled')) }}
					</div>
					<div class="col-md-3">
						{{ Form::label('usuario_asignado','Usuario Asignado')}}
						@if($usuario_asignado != null)
							{{ Form::text('usuario_asignado',$usuario_asignado->nombre.' '.$usuario_asignado->apellido_paterno.' '.$usuario_asignado->apellido_materno,array('class'=>'form-control','disabled'=>'disabled')) }}
						@else
							{{ Form::text('usuario_asignado','SIN asignacion',array('class'=>'form-control','disabled'=>'disabled')) }}
						@endif
					</div>
				</div>				
			</div>
		</div>
		<!-- OVERVIEW -->
		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Asunto del Ticket y Herramientas Detectadas</h3>
			</div>
			<div class="panel-body">				
				<div class="row">
					<div class="col-md-6">
						{{ Form::label('asunto','Asunto')}}
						{{ Form::text('asunto',$solicitud->asunto,array('class'=>'form-control','placeholder'=>'Ingrese nombre del usuario','id'=>'asunto_detectar','disabled'=>'disabled')) }}
					</div>
					<div class="col-md-6">						
						{{ Form::label('herramienta','Aplicativo:')}}
						@if($solicitud->idherramienta != null)
							{{ Form::text('herramienta',$herramienta->nombre,array('class'=>'form-control','disabled'=>'disabled')) }}
						@else
							{{ Form::text('herramienta_no_detectada','NO DETECTADO',array('class'=>'form-control','disabled'=>'disabled')) }}
						@endif
					</div>
				</div>
			</div>
		</div>
		@if($solicitud->idestado_solicitud == 6)
			<!-- OVERVIEW -->
		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Motivo de Anulación</h3>
			</div>
			<div class="panel-body">				
				<div class="row">
					<div class="row">
	         			<div class="col-md-12">
							{{Form::textarea('motivo_anulacion',$solicitud->motivo_anulacion,array('class'=>'form-control','placeholder'=>'Ingrese motivo','rows'=>3,'style'=>'resize:none','disabled'=>'disabled'))}}
						</div>					
					</div>
				</div>
			</div>
		</div>	
		@endif	
		<div class="panel  panel-headline">	
			<div class="panel-heading">
				<h3 class="panel-title">Requerimientos Asociados</h3>
			</div>	
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-hover">
								@if(count($transacciones)>0)
								<thead>
									<tr>
										<th class="text-nowrap text-center">N°</th>
										<th class="text-nowrap text-center">Código Requerimiento</th>
										<th class="text-nowrap text-center">Acción</th>
										<th class="text-nowrap text-center">Aplicativo</th>
										<th class="text-nowrap text-center">Aplicativo Agrupado</th>
										<th class="text-nowrap text-center">Tipo Gestión</th>
										<th class="text-nowrap text-center">Canal</th>
										<th class="text-nowrap text-center">Entidad</th>
										<th class="text-nowrap text-center">Punto de Venta</th>
										<th class="text-nowrap text-center">Cargo</th>
										<th class="text-nowrap text-center">Perfil</th>
										<th class="text-nowrap text-center">DNI Usuario</th>
										<th class="text-nowrap text-center">Estado</th>
										<th class="text-nowrap text-center">Observaciones</th>
									</tr>
								</thead>
								<tbody>
									
										@foreach($transacciones as $index => $transaccion)
										<tr>	
											<td class="text-nowrap text-center">
												{{$index+1}}
											</td>
											<td class="text-nowrap text-center">
												@if($transaccion->codigo_requerimiento != null)
													{{$transaccion->codigo_requerimiento}}
												@else
													-
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($transaccion->accion_requerimiento != null)
													{{$transaccion->accion_requerimiento}}
												@else
													-
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($transaccion->nombre_herramienta != null)
													{{$transaccion->nombre_herramienta}}
												@else
													-
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($transaccion->nombre_denominacion != null)
													{{$transaccion->nombre_denominacion}}
												@else
													-
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($transaccion->nombre_tipo_requerimiento != null)
													{{$transaccion->nombre_tipo_requerimiento}}
												@else
													-
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($transaccion->nombre_canal != null)
													{{$transaccion->nombre_canal}}
												@else
													-
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($transaccion->nombre_entidad != null)
													{{$transaccion->nombre_entidad}}
												@else
													-
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($transaccion->nombre_punto_venta != null)
													{{$transaccion->nombre_punto_venta}}
												@else
													-
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($transaccion->cargo_canal != null)
													{{$transaccion->cargo_canal}}
												@else
													-
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($transaccion->perfil_aplicativo != null)
													{{$transaccion->perfil_aplicativo}}
												@else
													-
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($transaccion->numero_documento != null)
													{{$transaccion->numero_documento}}
												@else
													-
												@endif
											</td>
											<td class="text-nowrap text-center">
												@if($transaccion->nombre_estado_transaccion != null)
													{{$transaccion->nombre_estado_transaccion}}
												@else
													-
												@endif
											</td>
											 <td class="text-nowrap">
											 	<div style="text-align:center">
											 		<button class="btn btn-info btn-sm" onclick="mostrar_observaciones(event,{{$transaccion->idtransaccion}})" type="button"><span class="fa fa-search"></span>
											 		</button>
											 	</div>
											 </td>
										</tr>
										@endforeach
									
								</tbody>
							@else
									<h3 style="text-align:center;height:150px;overflow-y:auto;">SIN REGISTROS</h3>
							@endif						
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			@if($user->idrol == 1)
				@if( $usuario_asignado != null && $usuario_asignado->deleted_at != null )
					@if(($solicitud->idestado_solicitud == 3 || $solicitud->idestado_solicitud == 4) )
						<div class="col-md-2">
							<button class="btn btn-success btn-block" onclick="mostrar_usuarios_disponibles(event,{{$solicitud->idsolicitud}})"> <i class="lnr lnr-redo"></i> Reasignación</button>
						</div>		
						<div class="form-group col-md-2 col-md-offset-8">
							<a class="btn btn-default btn-block" href="{{URL::to('solicitudes/listar_solicitudes')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
						</div>
					@else
						<div class="form-group col-md-2 col-md-offset-10">
							<a class="btn btn-default btn-block" href="{{URL::to('solicitudes/listar_solicitudes')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
						</div>
					@endif
				@else		
					<div class="form-group col-md-2 col-md-offset-10">
						<a class="btn btn-default btn-block" href="{{URL::to('solicitudes/listar_solicitudes')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
					</div>
				@endif
			@else
				@if(($solicitud->idestado_solicitud == 3 || $solicitud->idestado_solicitud == 4))
					<div class="col-md-2">
						<button class="btn btn-danger btn-block" onclick="mostrar_modal_anular(event,{{$solicitud->idsolicitud}})"> <i class="lnr lnr-thumbs-down"></i> Anular Solicitud</button>
					</div>		
					<div class="form-group col-md-2 col-md-offset-8">
						<a class="btn btn-default btn-block" href="{{URL::to('solicitudes/listar_solicitudes')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
					</div>
				@else
					<div class="form-group col-md-2 col-md-offset-10">
						<a class="btn btn-default btn-block" href="{{URL::to('solicitudes/listar_solicitudes')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
					</div>
				@endif
			@endif
		</div>
		
	</div>
</div>
@if($asignacion != null)
<div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_reasignacion"  role="dialog">
    <div class="modal-dialog modal-lg">    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header bg-primary">
	          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Tipo de Acción Solicitud - Equivalencias</h4>
	        </div>
	        <div class="modal-body" id="modal_text_equivalencias">
	         	<div class="container-fluid">	        
	         	{{ Form::open(array('url'=>'/solicitudes/submit_reasignar_solicitud' ,'role'=>'form','id'=>'submit-crear')) }}
	         	{{ Form::hidden('asignacion_id', $asignacion[0]->idasignacion, array('id'=>'asignacion_id')) }} 		
	         	{{ Form::hidden('solicitud_id', $solicitud->idsolicitud, array('id'=>'solicitud_id')) }} 		
					<div class="row">
	         			<div class="col-md-6">
							{{ Form::label('usuario_disponible','Usuarios Disponibles:')}}
							{{ Form::select('usuario_disponible', [''=>'Seleccione'],Input::old('usuario_disponible'),['class' => 'form-control','id'=>'slcUsuarios']) }}
						</div>	
						<div class="col-md-6">
							{{ Form::label('motivo_reasignacion','Motivo Reasignacion:')}}
							{{ Form::text('motivo_reasignacion',Input::old('motivo_reasignacion'),array('class'=>'form-control','placeholder'=>'Ingrese motivo','id'=>'motivo_reasignacion')) }}
						</div>					
					</div>
					<div class="row">
						<div class="col-md-6" style="margin-top:26px">
							<button type="submit" class="btn btn-info"><i class="fa fa-floppy-o "></i>&nbsp&nbspReasignar</button>
						</div>
					</div>
				</div>
				{{ Form::close() }}
			</div>
	        <div class="modal-footer">
	          
	        </div>
      </div>      
    </div>
  </div>
 </div>
 @endif
 <div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_anulacion"  role="dialog">
    <div class="modal-dialog modal-md">    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header bg-primary">
	          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Anular Solicitud - {{$solicitud->codigo_solicitud}}</h4>
	        </div>
	        <div class="modal-body" id="modal_text_equivalencias">
	         	<div class="container-fluid">	        
	         	{{ Form::open(array('url'=>'/solicitudes/submit_anular_solicitud' ,'role'=>'form','id'=>'submit-anular')) }}	
	         	{{ Form::hidden('solicitud_id', $solicitud->idsolicitud, array('id'=>'solicitud_id')) }} 		
					<div class="row">
	         			<div class="col-md-12">
							{{ Form::label('motivo_anulacion','Motivo Anulación:')}}
							{{Form::textarea('motivo_anulacion',Input::old('motivo_anulacion'),array('class'=>'form-control','placeholder'=>'Ingrese motivo','rows'=>3,'style'=>'resize:none'))}}
						</div>					
					</div>
					<div class="row">
						<div class="col-md-6" style="margin-top:26px">
							<button type="submit" class="btn btn-info"><i class="lnr lnr-location "></i>&nbsp&nbspAnular</button>
						</div>
					</div>
				</div>
				{{ Form::close() }}
			</div>
	        <div class="modal-footer">
	          
	        </div>
      </div>      
    </div>
  </div>
 </div>  
@stop