@extends('Templates/Mantenimientos/sectorTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10">
				<h3 class="page-title"><strong>SECTOR:</strong> {{$sector->nombre}}</h3>			
			</div>
			<div class="col-md-2">			
				<a class="btn btn-default btn-block" href="{{URL::to('entidades_canales_sectores/listar/1')}}"><i class="lnr lnr-arrow-left"></i> Cancelar</a>
			</div> 
		</div>
		<div class="row">
			<div class="col-md-12">
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
		{{ Form::open(array('url'=>'/sectores/submit_agregar_herramientas' ,'role'=>'form','id'=>'submit-agregar-herramientas')) }}
		{{ Form::hidden('sector_id', $sector->idsector, array('id'=>'sector_id')) }}
		<br>
		<div class="row">
			<div class="col-md-6">
				<div class="panel">	
					<div class="panel-heading">
						<h4 class="page-title"><strong>HERRAMIENTAS DISPONIBLES</strong></h4>
					</div>	
					<div class="panel-body" >
						<div class="row" style="height:150px;overflow-y:auto; " >
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover">
										@if(count($herramientas_disponibles)>0)
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Nombre Aplicativo</th>
												<th class="text-nowrap text-center">Tipo Aplicativo</th>
												<th class="text-nowrap text-center">Agregar</th>
											</tr>
										</thead>
										<tbody >	
											
												@foreach($herramientas_disponibles as $index  => $herramienta)
												<tr>
													<td class="text-nowrap text-center">
														<input style="display:none" name='ids_herramientas[]' value='{{ $herramienta->idherramienta }}' readonly/>
														{{$index+1}}
													</td>
													<td class="text-nowrap text-center">
														{{$herramienta->nombre}}
													</td>
													<td class="text-nowrap text-center">
														{{$herramienta->nombre_denominacion}}
													</td>
													<td class="text-nowrap">
														<div style="text-align:center">
															{{ Form::checkbox('checkbox'.$index, 1, Input::old('checkbox'.$index), ['class' => 'form-check-input']) }}
														</div>
													</td>
												</tr>
												@endforeach											
										</tbody>
										@else												
											<h4 style="text-align:center">No hay aplicativos disponibles</h4>
										@endif
									</table>
								</div>
							</div>
						</div>
						@if(count($herramientas_disponibles)>0)
						<br><br>
						<div class=" form-group row">
							<div class="col-md-5  col-md-offset-7">
								{{ Form::button(' Agregar Herramientas &nbsp&nbsp <span class="fa fa-arrow-right"></span>', array('id'=>'btnAgregarHerramientaSubmit', 'class' => 'btn btn-info btn-block')) }}	
							</div>
						</div>
						@else
						<br><br>
						<div class=" form-group row">
							<div class="col-md-5  col-md-offset-7">
								{{ Form::button(' Agregar Herramientas &nbsp&nbsp <span class="fa fa-arrow-right"></span>', array('id'=>'btnAgregarHerramientaSubmit', 'class' => 'btn btn-info btn-block','disabled'=>'disabled')) }}	
							</div>
						</div>
						@endif

					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel">	
					<div class="panel-heading">
						<h4 class="page-title"><strong>HERRAMIENTAS ASIGNADAS AL SECTOR</strong></h4>
					</div>	
					<div class="panel-body">
						<div class="row"  style="height:240px;overflow-y:auto;" >
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Nombre Aplicativo</th>
												<th class="text-nowrap text-center">Tipo Aplicativo</th>
												<th class="text-nowrap text-center">Retirar</th>
												<th class="text-nowrap text-center">Ver SLA</th>
											</tr>
										</thead>
										<tbody>	
											@foreach($herramientas as $index  => $herramienta)
											<tr class="">
												<td class="text-nowrap text-center">
													{{$index+1}}
												</td>
												<td class="text-nowrap text-center">
													{{$herramienta->nombre}}
												</td>
												<td class="text-nowrap text-center">
													{{$herramienta->nombre_denominacion}}
												</td>
												<td class="text-nowrap">
													<div style="text-align:center">
														<button class="btn btn-danger boton-eliminar-herramienta" onclick="eliminar_herramienta(event,{{$herramienta->idherramientaxsector}})" type="button"><span class="lnr lnr-trash"></span></button>
													</div>
												</td>												
												<td class="text-nowrap">
													<div style="text-align:center">
														<button class="btn btn-info boton-eliminar-herramienta" onclick="mostrar_sla(event,{{$herramienta->idherramientaxsector}})" type="button"><span class="fa fa-search"></span></button>
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
		{{Form::close()}}
		
	</div>
</div>
<div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_acciones"  role="dialog">
    <div class="modal-dialog modal-md">    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header" id="modal_header_acciones">
	          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Acciones Disponibles</h4>
	        </div>
	        <div class="modal-body" id="modal_text_acciones">
	         	<div class="container-fluid">
	         		<div class="row">
						<div class="table-responsive">
							<table class="table table-hover" id="tabla_acciones_disponibles">
								<thead>
									<tr>
										<th class="text-nowrap text-center">N°</th>
										<th class="text-nowrap text-center">Acción</th>
										<th class="text-nowrap text-center">Activo <input type="checkbox" id="checkboxAll" class="form-check-input"></th>
									</tr>
								</thead>
								<tbody >	
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-info" id="btnAgregarAccionSubmit" data-dismiss="modal"><i class="fa fa-floppy-o "></i>&nbsp&nbspActualizar</button>
	        </div>
      </div>      
    </div>
  </div>
 </div>  

@stop