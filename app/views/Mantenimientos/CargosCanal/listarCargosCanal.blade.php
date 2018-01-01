@extends('Templates/Mantenimientos/cargoCanalTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<h3 class="page-title"><strong>TIPOS DE CARGOS</strong> (CANAL {{$nombre_canal}} )</h3>
			</div>
			<div class="form-group col-md-2 col-md-offset-4">
				<a class="btn btn-default btn-block" href="{{URL::to('entidades_canales_sectores/listar/2')}}"><i class="lnr lnr-arrow-left"></i>&nbspSalir</a>				
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
		{{ Form::open(array('url'=>'/cargos_canal/submit_crear_cargo_canal' ,'role'=>'form','id'=>'submit-crear')) }}
		<!-- OVERVIEW -->	
		{{ Form::hidden('canal_id', $canal_id) }}	
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">Crear Tipo de Cargo</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							@if ($errors->has())
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<p>{{ $errors->first('nombre_cargo_canal') }}</p>
								<p>{{ $errors->first('nombre_edicion_cargo_canal') }}</p>
							</div>
							@endif
						</div>
						<div class="row">
							<div class="form-group col-md-6 @if($errors->first('nombre_cargo_canal')) has-error has-feedback @endif"">
								{{ Form::label('nombre_cargo_canal','Nombre Tipo de Cargo')}}
								{{ Form::text('nombre_cargo_canal',Input::old('nombre_cargo_canal'),array('class'=>'form-control','placeholder'=>'Ingrese nombre del tipo de cargo','id'=>'nombre_cargo_canal')) }}
							</div>
							<div class="form-group col-md-3">				
								<a class="btn btn-info btn-block" id="btnCrearCargoCanal" style='margin-top:25px;'> <i class="lnr lnr-plus-circle"></i> Crear</a>
							</div>
							<div class="form-group col-md-3">
								<div class="btn btn-default btn-block" id="btnLimpiarCargoCanal" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
							</div>
						</div>		
					</div>
				</div>
			</div>	
		</div>
		{{ Form::close() }}
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel">	
					<div class="panel-heading">
						<h3 class="panel-title">Tipos de Cargos</h3>
					</div>	
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<th class="text-nowrap text-center">Nombre del Tipo de Cargo</th>
												<th class="text-nowrap text-center">Editar Nombre</th>
												<!--
												<th class="text-nowrap text-center">Inhabilitar/Habilitar</th>
											-->
											</tr>
										</thead>
										<tbody>	
											@foreach($cargos_canal as $cargo_canal)
											<tr>
												<td class="text-nowrap text-center">
													{{$cargo_canal->nombre}}
												</td>
												<td class="text-nowrap text-center">
													@if($cargo_canal->deleted_at)
														-
													@else
														<button class="btn btn-warning btn-sm" onclick="editar_datos(event,'{{$cargo_canal->nombre}}',{{$cargo_canal->idcargo_canal}})" ><span class="lnr lnr-pencil"></span></button>
													@endif	
												</td>
												<!--
												<td class="text-nowrap text-center">
													@if($cargo_canal->deleted_at)
														{{ Form::open(array('url'=>'cargos_canal/submit_habilitar_cargo_canal', 'role'=>'form','id'=>'habilitar_cargo_canal')) }}
															{{ Form::hidden('cargo_canal_id', $cargo_canal->idcargo_canal) }}
															<div class="form-group col-md-12">
																{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-up"></span> Habilitar', array('id'=>'submit-habilitar-cargo-canal', 'class' => 'btn btn-success btn-block')) }}
															</div>
														{{ Form::close() }}
													@else
														{{ Form::open(array('url'=>'cargos_canal/submit_inhabilitar_cargo_canal', 'role'=>'form','id'=>'inhabilitar_cargo_canal')) }}
															{{ Form::hidden('cargo_canal_id', $cargo_canal->idcargo_canal) }}
															<div class="form-group col-md-12">
																{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-down"></span> Inhabilitar', array('id'=>'submit-inhabilitar-cargo-canal', 'class' => 'btn btn-danger btn-block')) }}
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
  <div class="modal fade" id="modal_editar_cargo_canal"  role="dialog">
    <div class="modal-dialog modal-md">    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header bg-primary">
	          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Editar Nombre Tipo de Cargo</h4>
	        </div>
	        <div class="modal-body" id="modal_text_equivalencias">
	         	<div class="container-fluid">
	         		{{ Form::open(array('url'=>'/cargos_canal/submit_editar_cargo_canal' ,'role'=>'form')) }}
					{{ Form::hidden('cargo_canal_id', null, array('id'=>'cargo_canal_id')) }}
					{{ Form::hidden('canal_id_edicion', $canal_id) }}		
	         		<div class="row">
						<div class="col-md-5 col-md-offset-1">
							{{ Form::label('nombre_edicion_cargo_canal','Nombre Tipo de Cargo:')}}
							{{ Form::text('nombre_edicion_cargo_canal',Input::old('nombre_edicion_cargo_canal'),array('class'=>'form-control','placeholder'=>'Ingresa nuevo nombre','id'=>'nombre_edicion_cargo_canal')) }}
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