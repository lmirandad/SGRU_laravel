@extends('Templates/Mantenimientos/usuariosTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<div class="col-md-10 col-md-offset-1">
			<h3 class="page-title"><strong>USUARIO:</strong> {{$usuario->nombre}} {{$usuario->apellido_paterno}} {{$usuario->apellido_materno}} ({{$usuario->username}})</h3>
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
		{{ Form::hidden('usuario_id', $usuario->id, array('id'=>'usuario_id')) }}
		<div class="col-md-10 col-md-offset-1">
			<div class="panel">	
				<div class="panel-heading">
					<h4 class="page-title">HERRAMIENTAS GESTIONADAS POR EL USUARIO</h4>
				</div>	
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-nowrap text-center">N°</th>
											<th class="text-nowrap text-center">Nombre Aplicativo</th>
											<th class="text-nowrap text-center">Tipo Aplicativo</th>
											<th class="text-nowrap text-center">Retirar</th>
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
												{{$herramienta->nombre_tipo_herramienta}}
											</td>
											<td class="text-nowrap">
												<div style="margin-left:37%">
													<button class="btn btn-danger boton-eliminar-herramienta" onclick="eliminar_herramienta(event,{{$herramienta->idherramientaxusers}})" type="button"><span class="lnr lnr-trash"></span></button>
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

		<div class="form-group col-md-2  col-md-offset-7">
			{{ Form::button('<i class="lnr lnr-plus-circle"></i> Agregar Herramienta', array('id'=>'btnAgregarHerramienta', 'class' => 'btn btn-info btn-block')) }}
		</div>
		<div class="form-group col-md-2">
			<a class="btn btn-default btn-block" href="{{URL::to('/usuarios/listar_usuarios')}}">Cancelar</a>			
		</div>
	</div>
</div>

<div class="container" >
  <!-- Modal -->
  <div class="modal fade" id="modal_herramientas"  role="dialog">
    <div class="modal-dialog modal-md">    
      <!-- Modal content-->
      <div class="modal-content" >
        <div class="modal-header" id="modal_header_herramientas">
          <button type="button" class="close" id="btnCerrarModal" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Aplicativos Disponibles</h4>
        </div>
        <div class="modal-body" id="modal_text_herramientas" style="height:150px; overflow: auto;">
         	<div class="container-fluid">
         		<div class="row">
					<div class="table-responsive">
						<table class="table table-hover" id="tabla_herramientas_disponibles">
							<thead>
								<tr>
									<th class="text-nowrap text-center">N°</th>
									<th class="text-nowrap text-center">Nombre Aplicativo</th>
									<th class="text-nowrap text-center">Tipo Aplicativo</th>
									<th class="text-nowrap text-center">Adicionar</th>
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
          <button type="button" class="btn btn-default" id="btnAgregarHerramientaSubmit" data-dismiss="modal">Agregar</button>
        </div>
      </div>      
    </div>
  </div>  
</div>
@stop