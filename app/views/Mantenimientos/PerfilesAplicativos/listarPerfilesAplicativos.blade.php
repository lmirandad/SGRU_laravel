@extends('Templates/Mantenimientos/perfilesAplicativosTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		
		<div class="row">
			<div class="col-md-6">
				<h3 class="page-title"><strong>PERFILES </strong>(APLICATIVO {{$nombre_herramienta}} )</h3>
			</div>
			<div class="form-group col-md-2 col-md-offset-4">
				<a class="btn btn-default btn-block" href="{{URL::to('/herramientas/listar_herramientas')}}"><i class="lnr lnr-arrow-left"></i>&nbspSalir</a>				
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
		{{ Form::open(array('url'=>'/perfiles_aplicativos/submit_crear_perfil_aplicativo' ,'role'=>'form','id'=>'submit-crear')) }}
		<!-- OVERVIEW -->	
		{{ Form::hidden('herramienta_id', $herramienta_id) }}	
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">Crear Perfil de Aplicativo</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							@if ($errors->has())
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<p>{{ $errors->first('nombre_perfil_aplicativo') }}</p>
								<p>{{ $errors->first('nombre_edicion_perfil_aplicativo') }}</p>
							</div>
							@endif
						</div>
						<div class="row">
							<div class="form-group col-md-6 @if($errors->first('nombre_perfil_aplicativo')) has-error has-feedback @endif"">
								{{ Form::label('nombre_perfil_aplicativo','Nombre Perfil')}}
								{{ Form::text('nombre_perfil_aplicativo',Input::old('nombre_perfil_aplicativo'),array('class'=>'form-control','placeholder'=>'Ingrese nombre del perfil','id'=>'nombre_perfil_aplicativo')) }}
							</div>
							<div class="form-group col-md-3">				
								<a class="btn btn-info btn-block" id="btnCrearPerfil" style='margin-top:25px;'> <i class="lnr lnr-plus-circle"></i> Crear</a>
							</div>
							<div class="form-group col-md-3">
								<div class="btn btn-default btn-block" id="btnLimpiarPerfil" style='margin-top:25px;'><span class="lnr lnr-sync""></span> Limpiar</div>				
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
						<h3 class="panel-title">Perfiles</h3>
					</div>	
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<th class="text-nowrap text-center">Nombre del Perfil</th>
												<th class="text-nowrap text-center">Editar Nombre</th>
												<!--
												<th class="text-nowrap text-center">Inhabilitar/Habilitar</th>
											-->
											</tr>
										</thead>
										<tbody>	
											@foreach($perfiles_aplicativos as $perfil_aplicativo)
											<tr>
												<td class="text-nowrap text-center">
													{{$perfil_aplicativo->nombre}}
												</td>
												<td class="text-nowrap text-center">
													@if($perfil_aplicativo->deleted_at)
														-
													@else
														<button class="btn btn-warning btn-sm" onclick="editar_datos(event,'{{$perfil_aplicativo->nombre}}',{{$perfil_aplicativo->idperfil_aplicativo}})" type="button"><span class="lnr lnr-pencil"></span></button>
													@endif	
												</td>
												<!--
												<td class="text-nowrap text-center">
													@if($perfil_aplicativo->deleted_at)
														{{ Form::open(array('url'=>'perfiles_aplicativos/submit_habilitar_perfil_aplicativo', 'role'=>'form','id'=>'habilitar_perfil_aplicativo')) }}
															{{ Form::hidden('perfil_aplicativo_id', $perfil_aplicativo->idperfil_aplicativo) }}
															<div class="form-group col-md-12">
																{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-up"></span> Habilitar', array('id'=>'submit-habilitar-perfil-aplicativo', 'class' => 'btn btn-success btn-block')) }}
															</div>
														{{ Form::close() }}
													@else
														{{ Form::open(array('url'=>'perfiles_aplicativos/submit_inhabilitar_perfil_aplicativo', 'role'=>'form','id'=>'inhabilitar_perfil_aplicativo')) }}
															{{ Form::hidden('perfil_aplicativo_id', $perfil_aplicativo->idperfil_aplicativo) }}
															<div class="form-group col-md-12">
																{{ Form::button('<span class="glyphicon glyphicon-circle-arrow-down"></span> Inhabilitar', array('id'=>'submit-inhabilitar-perfil-aplicativo', 'class' => 'btn btn-danger btn-block')) }}
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
  <div class="modal fade" id="modal_editar_perfil"  role="dialog">
    <div class="modal-dialog modal-md">    
      <!-- Modal content-->
      <div class="modal-content" >
	        <div class="modal-header bg-primary">
	          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Editar Nombre Perfil</h4>
	        </div>
	        <div class="modal-body" id="modal_text_equivalencias">
	         	<div class="container-fluid">
	         		{{ Form::open(array('url'=>'/perfiles_aplicativos/submit_editar_perfil_aplicativo' ,'role'=>'form')) }}
					{{ Form::hidden('perfil_aplicativo_id', null, array('id'=>'perfil_aplicativo_id')) }}
					{{ Form::hidden('herramienta_id_edicion', $herramienta_id) }}		
	         		<div class="row">
						<div class="col-md-5 col-md-offset-1">
							{{ Form::label('nombre_edicion_perfil_aplicativo','Nombre Perfil de Aplicativo')}}
							{{ Form::text('nombre_edicion_perfil_aplicativo',Input::old('nombre_edicion_perfil_aplicativo'),array('class'=>'form-control','placeholder'=>'Ingresa nuevo nombre','id'=>'nombre_edicion_perfil_aplicativo')) }}
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