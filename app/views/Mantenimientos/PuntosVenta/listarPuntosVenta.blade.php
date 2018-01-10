@extends('Templates/Mantenimientos/puntosVentaTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		
		<div class="row">
			<div class="col-md-6">
				<h3 class="page-title"><strong>PUNTOS DE VENTA </strong>(ENTIDAD {{$nombre_entidad}} )</h3>
			</div>
			<div class="form-group col-md-2 col-md-offset-4">
				<a class="btn btn-default btn-block" href="{{URL::to('entidades_canales_sectores/listar/3')}}"><i class="lnr lnr-arrow-left"></i>&nbspSalir</a>				
			</div>
		</div>
		<div class="row">
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
		{{ Form::open(array('url'=>'/puntos_venta/submit_crear_punto_venta' ,'role'=>'form','id'=>'submit-crear')) }}
		<!-- OVERVIEW -->	
		{{ Form::hidden('entidad_id', $entidad_id) }}	
		<div class="row">
			<div class="col-md-9 col-md-offset-1">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">Crear Punto de Venta</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							@if ($errors->has())
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<p>{{ $errors->first('nombre_punto_venta') }}</p>
								<p>{{ $errors->first('nombre_edicion_punto_venta') }}</p>
								<p>{{ $errors->first('codigo_punto_venta') }}</p>
								<p>{{ $errors->first('codigo_edicion_punto_venta') }}</p>
							</div>
							@endif
						</div>
						<div class="row">
							<div class="form-group col-md-4 @if($errors->first('nombre_punto_venta')) has-error has-feedback @endif"">
								{{ Form::label('codigo_punto_venta','Código Punto de Venta')}}
								{{ Form::text('codigo_punto_venta',Input::old('codigo_punto_venta'),array('class'=>'form-control','placeholder'=>'Ingrese código del punto de venta','id'=>'codigo_punto_venta')) }}
							</div>
							<div class="form-group col-md-4 @if($errors->first('nombre_punto_venta')) has-error has-feedback @endif"">
								{{ Form::label('nombre_punto_venta','Nombre Punto de Venta')}}
								{{ Form::text('nombre_punto_venta',Input::old('nombre_punto_venta'),array('class'=>'form-control','placeholder'=>'Ingrese nombre del punto de venta','id'=>'nombre_punto_venta')) }}
							</div>
							<div class="form-group col-md-2">				
								<a class="btn btn-info btn-block" id="btnCrearPuntoVenta" style='margin-top:25px;'> <i class="lnr lnr-plus-circle"></i> Crear</a>
							</div>
							<div class="form-group col-md-2">
								<div class="btn btn-default btn-block" id="btnLimpiarPuntoVenta" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
							</div>
						</div>		
					</div>
				</div>
			</div>	
		</div>
		{{ Form::close() }}
		<div class="row">
			<div class="col-md-9 col-md-offset-1">
				<div class="panel">	
					<div class="panel-heading">
						<h3 class="panel-title">Puntos de Venta</h3>
					</div>	
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<th class="text-nowrap text-center">Código del Punto de Venta</th>
												<th class="text-nowrap text-center">Nombre del Punto de Venta</th>
												<th class="text-nowrap text-center">Editar Nombre</th>
												<!--
												<th class="text-nowrap text-center">Inhabilitar/Habilitar</th>
											-->
											</tr>
										</thead>
										<tbody>	
											@foreach($puntos_venta as $punto_venta)
											<tr>
												<td class="text-nowrap text-center">
													{{$punto_venta->codigo_punto_venta}}
												</td>
												<td class="text-nowrap text-center">
													{{$punto_venta->nombre}}
												</td>
												<td class="text-nowrap text-center">
													@if($punto_venta->deleted_at)
														-
													@else
														<button class="btn btn-warning btn-sm" onclick="editar_datos(event,'{{$punto_venta->codigo_punto_venta}}','{{$punto_venta->nombre}}',{{$punto_venta->idpunto_venta}})" type="button"><span class="lnr lnr-pencil"></span></button>
													@endif	
												</td>
												<!--
												<td class="text-nowrap text-center">
													@if($punto_venta->deleted_at)
														{{ Form::open(array('url'=>'puntos_venta/submit_habilitar_punto_venta', 'role'=>'form','id'=>'habilitar_punto_venta')) }}
															{{ Form::hidden('punto_venta_id', $punto_venta->idpunto_venta) }}
															<div class="form-group col-md-12">
																{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-up"></span> Habilitar', array('id'=>'submit-habilitar-punto-venta', 'class' => 'btn btn-success btn-block')) }}
															</div>
														{{ Form::close() }}
													@else
														{{ Form::open(array('url'=>'puntos_venta/submit_inhabilitar_punto_venta', 'role'=>'form','id'=>'inhabilitar_punto_venta')) }}
															{{ Form::hidden('punto_venta_id', $punto_venta->idpunto_venta) }}
															<div class="form-group col-md-12">
																{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-down"></span> Inhabilitar', array('id'=>'submit-inhabilitar-punto-venta', 'class' => 'btn btn-danger btn-block')) }}
															</div>
														{{ Form::close() }}
													@endif	
												</td>
											-->
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
	</div>
</div>
<div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_editar_pto_venta"  role="dialog">
    <div class="modal-dialog modal-md">    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header bg-primary">
	          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Editar Nombre Punto de Venta</h4>
	        </div>
	        <div class="modal-body" id="modal_text_equivalencias">
	         	<div class="container-fluid">
	         		{{ Form::open(array('url'=>'/puntos_venta/submit_editar_punto_venta' ,'role'=>'form')) }}
					{{ Form::hidden('punto_venta_id', null, array('id'=>'punto_venta_id')) }}
					{{ Form::hidden('entidad_id_edicion', $entidad_id) }}		
	         		<div class="row">
	         			<div class="col-md-5 col-md-offset-1">
							{{ Form::label('codigo_edicion_punto_venta','Código Punto de Venta:')}}
							{{ Form::text('codigo_edicion_punto_venta',Input::old('codigo_edicion_punto_venta'),array('class'=>'form-control','placeholder'=>'Ingresa nuevo código','id'=>'codigo_edicion_punto_venta')) }}
						</div>
						<div class="col-md-5">
							{{ Form::label('nombre_edicion_punto_venta','Nombre Punto de Venta:')}}
							{{ Form::text('nombre_edicion_punto_venta',Input::old('nombre_edicion_punto_venta'),array('class'=>'form-control','placeholder'=>'Ingresa nuevo nombre','id'=>'nombre_edicion_punto_venta')) }}
						</div>
						<div class="col-md-6" style="margin-top:26px">
							<button type="submit" class="btn btn-info" ><i class="fa fa-floppy-o "></i>&nbsp&nbspActualizar</button>
						</div>						
					</div>
					{{Form::close()}}
				</div>
			</div>
	        <div class="modal-footer">
	          
	        </div>
      </div>      
    </div>
  </div>
 </div> 
@stop