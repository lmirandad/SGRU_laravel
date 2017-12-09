@extends('Templates/Mantenimientos/herramientasTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>APLICATIVO: </strong>{{$herramienta->nombre}}</h3>

		<div class="row">
			<div class="col-md-12">
				@if ($errors->has())
					<div class="alert alert-danger" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<p>{{ $errors->first('nombre_herramienta') }}</p>		
						<p>{{ $errors->first('descripcion') }}</p>
						<p>{{ $errors->first('flag_seguridad') }}</p>
						<p>{{ $errors->first('denominacion_herramienta') }}</p>
						<p>{{ $errors->first('nombre_equivalencia') }}</p>
					</div>
				@endif
			</div>
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
		{{ Form::open(array('url'=>'/herramientas/submit_editar_herramienta' ,'role'=>'form','id'=>'submit-editar')) }}
		{{ Form::hidden('herramienta_id', $herramienta->idherramienta, array('id'=>'herramienta_id')) }}	
		<!-- OVERVIEW -->		
		<div class="row">
			<div class="col-md-7">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">Datos del Aplicativo</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							
						</div>
						<div class="row">
							<div class="form-group col-md-4 @if($errors->first('nombre_herramienta')) has-error has-feedback @endif"">
								{{ Form::label('nombre_herramienta','Nombre del Aplicativo')}}
								{{ Form::text('nombre_herramienta',$herramienta->nombre,array('class'=>'form-control','placeholder'=>'Ingrese nombre del aplicativo')) }}
							</div>
							<div class="form-group col-md-4 @if($errors->first('flag_seguridad')) has-error has-feedback @endif"">
								{{ Form::label('flag_seguridad','Valida Seguridad:')}}
								{{ Form::select('flag_seguridad', [''=>'Seleccione','0'=>'No','1'=>'Si','2'=>'Por Validar'],$herramienta->flag_seguridad,['class' => 'form-control']) }}
							</div>
							<div class="form-group col-md-4 @if($errors->first('denominacion_herramienta')) has-error has-feedback @endif">
								{{ Form::label('denominacion_herramienta','Categoría Aplicativo:')}}
								{{ Form::select('denominacion_herramienta',array(''=>'Seleccione')+$denominaciones,$herramienta->iddenominacion_herramienta,array('class'=>'form-control')) }}
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-12 @if($errors->first('descripcion')) has-error has-feedback @endif"">
								{{ Form::label('descripcion','Descripción:')}}
								{{ Form::textarea('descripcion',$herramienta->descripcion,array('class'=>'form-control','placeholder'=>'Ingrese una descripción','rows'=>5,'style'=>'resize:none')) }}
							</div>
						</div>						
					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">Equivalencias</h3>
					</div>
					<div class="panel-body">
						<div class="row" style="height:224	px;overflow-y:auto; ">
							
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover">
										@if(count($equivalencias)>0)
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Nombre</th>
												<th class="text-nowrap text-center">Retirar</th>
											</tr>
										</thead>
										<tbody>	
												@foreach($equivalencias as $index  => $equivalencia)
												<tr class="">
													<td class="text-nowrap text-center">
														
														{{$index+1}}
													</td>
													<td class="text-nowrap text-center">
														{{$equivalencia->nombre_equivalencia}}
													</td>
													<td class="text-nowrap">
														<div style="text-align:center">
															<button class="btn btn-danger btn-sm" onclick="eliminar_equivalencia(event,{{$equivalencia->idherramienta_equivalencia}})" type="button"><span class="lnr lnr-trash"></span></button>
														</div>	
													</td>
												</tr>
												@endforeach											
										</tbody>
										@else
											<h4 style="text-align:center">Sin nombres equivalentes</h4>
										@endif
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
				<a class="btn btn-warning btn-block" id="btnAgregarHerramientaEquivalencia"> <i class="fa fa-plus"></i>&nbsp Agregar Equivalencias</a>
			</div>
			<div class="form-group col-md-2  col-md-offset-5">				
				<a class="btn btn-info btn-block" id="btnEditarHerramienta"> <i class="fa fa-floppy-o"></i> Editar</a>
			</div>
			<div class="form-group col-md-2">
				<a class="btn btn-default btn-block" href="{{URL::to('herramientas/listar_herramientas')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
			</div>
		</div>
		{{ Form::close() }}		
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
	          <h4 class="modal-title">Herramienta Equivalencias</h4>
	        </div>
	        <div class="modal-body" id="modal_text_equivalencias">
	         	<div class="container-fluid">
	         		{{ Form::open(array('url'=>'/herramientas/submit_agregar_equivalencia' ,'role'=>'form')) }}
					{{ Form::hidden('herramienta_id', $herramienta->idherramienta, array('id'=>'herramienta_id')) }}	
	         		<div class="row">
						<div class="col-md-6">
							{{ Form::label('nombre_equivalencia','Nombre Equivalente:')}}
							{{ Form::text('nombre_equivalencia',Input::old('nombre_equivalencia'),array('class'=>'form-control','placeholder'=>'Ingrese nombre del aplicativo','id'=>'nombre_equivalencia')) }}
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