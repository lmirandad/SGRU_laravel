@extends('Templates/Mantenimientos/equivalenciasTipoSolicitudTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10">
				<h3 class="page-title">MANTENIMIENTO/<strong>EQUIVALENCIAS TIPO DE ACCIÓN DE SOLICITUD</strong></h3>
			</div>

			<div class="col-md-2">			
				@if($user->idrol == 1)
					<a class="btn btn-default btn-block" href="{{URL::to('/principal_admin')}}"><i class="lnr lnr-arrow-left"></i> Cancelar</a>
				@else
					<a class="btn btn-default btn-block" href="{{URL::to('/principal_gestor')}}"><i class="lnr lnr-arrow-left"></i> Cancelar</a>
				@endif
			</div> 
		</div>
		<div class="row">
			<div class="col-md-12">
				@if ($errors->has())
					<div class="alert alert-danger" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<p>{{ $errors->first('nombre_equivalencia') }}</p>		
						<p>{{ $errors->first('tipo_solicitud') }}</p>
					</div>
				@endif
				@if (Session::has('message'))
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						{{ Session::get('message') }}
					</div>
				@endif
				@if (Session::has('error'))
					<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						<i class="fa fa-times-circle"></i> {{ Session::get('error') }}
					</div>
				@endif
			</div>
		</div> 
		<div class="row">
			<div class="col-md-6">
				<div class="panel">	
					<div class="panel-heading">
						<h4 class="page-title"><strong>TIPOS DE ACCIÓN</strong></h4>
					</div>	
					<div class="panel-body" >
						<div class="row" >
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover">
										@if(count($tipos_solicitud)>0)
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Nombre</th>
												<th class="text-nowrap text-center">Ver Equivalencias</th>
											</tr>
										</thead>
										<tbody >	
											
												@foreach($tipos_solicitud as $index  => $tipo)
												<tr>
													<td class="text-nowrap text-center">
														{{$index+1}}
													</td>
													<td class="text-nowrap text-center">
														{{$tipo->nombre}}
													</td>
													<td class="text-nowrap">
														<div style="text-align:center">
															<button class="btn btn-info btn-sm" onclick="mostrar_datos(event,{{$tipo->idtipo_solicitud}})" type="button"><span class="fa fa-search"></span></button>
														</div>
													</td>
												</tr>
												@endforeach											
										</tbody>
										@else												
											<h4 style="text-align:center">No hay tipos de acciones disponibles</h4>
										@endif
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel">	
					<div class="panel-heading">
						<h4 class="page-title"><strong>EQUIVALENCIAS DE LA ACCIÓN: </strong></h4>
					</div>	
					<div class="panel-body">
						<div class="row"   style="height:380px;overflow-y:auto;" >
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover" id="table_equivalencias">
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Nombre</th>
												<th class="text-nowrap text-center">Eliminar</th>
											</tr>
										</thead>
										<tbody>	
											@foreach($equivalencias_data as $index  => $equivalencia)
											<tr class="">
												<td class="text-nowrap text-center">
													{{$index+1}}
												</td>
												<td class="text-nowrap text-center">
													{{$equivalencia->nombre_equivalencia}}
												</td>
												<td class="text-nowrap">
													<div style="text-align:center">
														<button class="btn btn-danger boton-eliminar-herramienta" onclick="eliminar_herramienta(event,{{$equivalencia->idequivalencia_tipo_solicitud}})" type="button"><span class="lnr lnr-trash"></span></button>
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
		<div class="row">
			<div class="form-group col-md-3">				
			<a class="btn btn-warning btn-block" id="btnAgregarTipoSolicitudEquivalencia"> <i class="fa fa-plus"></i>&nbsp Agregar Equivalencias</a>
		</div>
		
	</div>
</div>

<div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_equivalencias"  role="dialog">
    <div class="modal-dialog modal-md">    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header bg-primary">
	          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Tipo de Acción Solicitud - Equivalencias</h4>
	        </div>
	        <div class="modal-body" id="modal_text_equivalencias">
	         	<div class="container-fluid">	        
	         	{{ Form::open(array('url'=>'/equivalencias_tipo_solicitud/submit_crear_equivalencia_tipo_solicitud' ,'role'=>'form','id'=>'submit-crear')) }} 		
					<div class="row">
	         			<div class="col-md-6">
							{{ Form::label('tipo_solicitud','Tipo Solicitud:')}}
							{{ Form::select('tipo_solicitud', [''=>'Seleccione']+$tipos_solicitud_lista,Input::old('tipo_solicitud'),['class' => 'form-control','id'=>'tipo_solicitud_id']) }}
						</div>	
						<div class="col-md-6">
							{{ Form::label('nombre_equivalencia','Nombre Equivalente:')}}
							{{ Form::text('nombre_equivalencia',Input::old('nombre_equivalencia'),array('class'=>'form-control','placeholder'=>'Ingrese nombre del aplicativo','id'=>'nombre_equivalencia')) }}
						</div>					
					</div>
					<div class="row">
						<div class="col-md-6" style="margin-top:26px">
							<a id="btnSubmitAgregar" class="btn btn-info"><i class="fa fa-floppy-o "></i>&nbsp&nbspAgregar</a>
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