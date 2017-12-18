@extends('Templates/Mantenimientos/feriadosTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10">
				<h3 class="page-title">MANTENIMIENTO/<strong>FERIADOS LABORALES</strong></h3>
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
						<p>{{ $errors->first('fecha_feriado') }}</p>		
						<p>{{ $errors->first('nombre_motivo') }}</p>
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
		<!-- OVERVIEW -->
		
		<div class="row">
			<div class="col-md-6">
				<div class="panel">
					<div class="panel-heading">
						<h3 class="panel-title">Criterios de Búsqueda</h3>
					</div>
					<div class="panel-body">
						{{ Form::open(array('url'=>'/feriados/buscar_feriados','method'=>'get' ,'role'=>'form', 'id'=>'search-form')) }}
						<div class="row">
							<div class="form-group col-md-6">					
								{{ Form::label('Anho','Año') }}
								<div id="datetimepicker1" class="form-group input-group date">
									{{ Form::text('search_anho',$search_anho,array('class'=>'form-control','placeholder'=>'Ingresar Año','id'=>'search_anho')) }}
									<span class="input-group-addon">
				                        <span class="glyphicon glyphicon-calendar"></span>
				                    </span>
								</div>
							</div>	
							<div class="form-group col-md-3">
								{{ Form::button('<span class="fa fa-search""></span> Buscar', array('id'=>'submit-search-form','type' => 'submit', 'class' => 'btn btn-info btn-block','style'=>'margin-top:25px;')) }}	
							</div>
							<div class="col-md-3">
								<div class="btn btn-default btn-block" id="btnLimpiar" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
							</div>				
						</div>
						{{ Form::close() }}					
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel">	
					<div class="panel-body" >
						<div class="row"  style="height:150px;overflow-y:auto;">
							<div class="col-md-12" >
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Fecha</th>
												<th class="text-nowrap text-center">Motivo Feriado</th>
												<th class="text-nowrap text-center">Eliminar</th>
											</tr>
										</thead>
										<tbody >	
											
												@foreach($feriados_data as $index  => $feriado)
												<tr>
													<td class="text-nowrap text-center">
														{{$index+1}}
													</td>
													<td class="text-nowrap text-center">
														{{date('d-m-Y',strtotime($feriado->valor_fecha))}}
													</td>
													<td class="text-nowrap text-center">
														{{$feriado->motivo_feriado}}
													</td>

													<td class="text-nowrap">
														<div style="text-align:center">
															<button class="btn btn-danger btn-sm" onclick="eliminar_feriado(event,{{$feriado->idfecha_feriado}})" type="button"><span class="lnr lnr-trash"></span></button>
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
			<a class="btn btn-warning btn-block" id="btnAgregarFeriado"> <i class="fa fa-plus"></i>&nbsp Agregar Nuevo Feriado</a>
		</div>
		
	</div>
</div>

<div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_feriados"  role="dialog">
    <div class="modal-dialog modal-md">    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header bg-primary">
	          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Nuevo Feriado</h4>
	        </div>
	        <div class="modal-body" id="modal_text_equivalencias">
	         	<div class="container-fluid">	        
	         	{{ Form::open(array('url'=>'/feriados/submit_crear_feriado' ,'role'=>'form','id'=>'submit-crear')) }} 		
					<div class="row">
	         			<div class="col-md-6">
							{{ Form::label('fecha_feriado','Fecha Feriado:') }}
							<div id="datetimepicker2" class="form-group input-group date">
								{{ Form::text('fecha_feriado',Input::old('fecha_feriado'),array('class'=>'form-control','placeholder'=>'Ingresar Fecha Feriado')) }}
								<span class="input-group-addon">
			                        <span class="glyphicon glyphicon-calendar"></span>
			                    </span>
							</div>
						</div>	
						<div class="col-md-6">
							{{ Form::label('nombre_motivo','Motivo Feriado:')}}
							{{ Form::text('nombre_motivo',Input::old('nombre_motivo'),array('class'=>'form-control','placeholder'=>'Ingrese motivo del feriado','id'=>'nombre_motivo')) }}
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