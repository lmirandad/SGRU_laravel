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
						{{ Form::select('tipo_solicitud',array('0'=>'Seleccione')+$tipos_solicitud,$solicitud->idtipo_solicitud,array('class'=>'form-control','disabled'=>'disabled')) }}
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
						{{ Form::text('usuario_asignado',$usuario_asignado->nombre.' '.$usuario_asignado->apellido_paterno.' '.$usuario_asignado->apellido_materno,array('class'=>'form-control','disabled'=>'disabled')) }}
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
						{{ Form::text('herramienta',$herramienta->nombre,array('class'=>'form-control','disabled'=>'disabled')) }}
					</div>
				</div>
			</div>
		</div>	
		<div class="panel  panel-headline">	
			<div class="panel-heading">
				<h3 class="panel-title">Requerimientos Asociados</h3>
			</div>	
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-nowrap text-center">Código Solicitud</th>
										<th class="text-nowrap text-center">Fecha de Solicitud</th>
										<th class="text-nowrap text-center">Tipo de Solicitud</th>
										<th class="text-nowrap text-center">Estado Solicitud</th>
										<th class="text-nowrap text-center">Ver bitácora</th>
									</tr>
								</thead>
														
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			@if($solicitud->idestado_solicitud == 3 || $solicitud->idestado_solicitud == 4 )
				<div class="col-md-2">
					<button class="btn btn-success btn-block" onclick="mostrar_usuarios_disponibles(event,{{$solicitud->idsolicitud}})"> <i class="lnr lnr-redo"></i> Reasignación</button>
				</div>	
				<div class="col-md-2 col-md-offset-6">
					<a class="btn btn-info btn-block" href="#"> <i class="lnr lnr-plus-circle"></i> Crear Requerimiento</a>
				</div>			
				<div class="form-group col-md-2">
					<a class="btn btn-default btn-block" href="{{URL::to('solicitudes/listar_solicitudes')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
				</div>
			@else
				<div class="col-md-2">
					<button class="btn btn-success btn-block" onclick="mostrar_usuarios_disponibles(event,{{$solicitud->idsolicitud}})"> <i class="lnr lnr-redo"></i> Reasignación</button>
				</div>	
				<div class="col-md-2 col-md-offset-6">
					<a class="btn btn-info btn-block" href="#"> <i class="lnr lnr-plus-circle"></i> Crear Requerimiento</a>
				</div>			
				<div class="form-group col-md-2">
					<a class="btn btn-default btn-block" href="{{URL::to('solicitudes/listar_solicitudes')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
				</div>
			@endif
		</div>
		
	</div>
</div>
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
@stop