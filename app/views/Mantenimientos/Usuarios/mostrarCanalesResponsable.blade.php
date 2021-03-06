@extends('Templates/Mantenimientos/usuariosTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10">
				<h3 class="page-title"><strong>USUARIO:</strong> {{$usuario->nombre}} {{$usuario->apellido_paterno}} {{$usuario->apellido_materno}} ({{$usuario->username}})</h3>			
			</div>
			<div class="col-md-2">			
				<a class="btn btn-default btn-block" href="{{URL::to('/usuarios/listar_usuarios')}}"><i class="lnr lnr-arrow-left"></i> Cancelar</a>
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
		{{ Form::open(array('url'=>'/usuarios/submit_agregar_canales' ,'role'=>'form','id'=>'submit-agregar-canales')) }}
		{{ Form::hidden('usuario_id', $usuario->id, array('id'=>'usuario_id')) }}
		<br>
		<div class="row">
			<div class="col-md-6">
				<div class="panel">	
					<div class="panel-heading">
						<h4 class="page-title">CANALES DISPONIBLES PARA EL USUARIO</h4>
					</div>	
					<div class="panel-body">
						<div class="row" style="height:150px;overflow-y:auto; ">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover" id="tabla_canales_disponibles">
										@if(count($canales_disponibles)>0)
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Canal</th>
												<th class="text-nowrap text-center">Agregar <input type="checkbox" id="checkboxAllCanal" class="form-check-input"></th>
											</tr>
										</thead>
										<tbody>	
												@foreach($canales_disponibles as $index  => $canal)
												<tr class="">
													<td class="text-nowrap text-center">
														<input style="display:none" name='ids_canales[]' value='{{ $canal->idcanal }}' readonly/>
														{{$index+1}}
													</td>
													<td class="text-nowrap text-center">
														{{$canal->nombre}}
													</td>
													<td class="text-nowrap">
														<div style="text-align:center">
															{{ Form::checkbox('checkbox'.$index, 1, Input::old('checkbox'.$index), ['class' => 'form-check-input','id'=>'checkboxC'.$index]) }}
														</div>
													</td>
												</tr>
												@endforeach
											
										</tbody>
										@else
											<h4 style="text-align:center">Sin canales disponibles</h4>
										@endif
									</table>
								</div>
							</div>
						</div>
						@if(count($canales_disponibles)>0)
						<br><br>
						<div class="row">
							<div class="form-group col-md-5  col-md-offset-7">
								{{ Form::button('<span class="lnr lnr-plus-circle""></span> Agregar Canales', array('id'=>'btnAgregarCanalSubmit', 'class' => 'btn btn-info btn-block')) }}	
							</div>
						</div>
						@else
						<br><br>
						<div class="row">
							<div class="form-group col-md-5  col-md-offset-7">
								{{ Form::button('<span class="lnr lnr-plus-circle""></span> Agregar Canales', array('id'=>'btnAgregarCanalSubmit', 'class' => 'btn btn-info btn-block','disabled'=>'disabled')) }}
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel">	
					<div class="panel-heading">
						<h4 class="page-title">CANALES RESPONSABLES POR EL USUARIO</h4>
					</div>	
					<div class="panel-body">
						<div class="row"  style="height:240px;overflow-y:auto;" >
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<th class="text-nowrap text-center">N°</th>
												<th class="text-nowrap text-center">Canal</th>
												<th class="text-nowrap text-center">Retirar</th>
											</tr>
										</thead>
										<tbody>	
											@foreach($canales as $index  => $canal)
											<tr class="">
												<td class="text-nowrap text-center">
													{{$index+1}}
												</td>
												<td class="text-nowrap text-center">
													{{$canal->nombre}}
												</td>
												<td class="text-nowrap">
													<div style="margin-left:37%">
														<button class="btn btn-danger boton-eliminar-canal" onclick="eliminar_canal(event,{{$canal->idcanal}})" type="button"><span class="lnr lnr-trash"></span></button>
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


@stop