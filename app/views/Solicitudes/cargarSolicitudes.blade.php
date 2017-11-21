@extends('Templates/solicitudesTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title">SOLICITUDES/<strong>CARGA SOLICITUDES</strong></h3>

		<div class="row">

			@if (Session::has('message'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					{{ Session::get('message') }}
				</div>
			@endif
			@if (Session::has('error'))
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					{{ Session::get('error') }}
				</div>
			@endif
		</div>
		{{ Form::open(array('url'=>'/solicitudes/submit_carga_solicitud' ,'role'=>'form','id'=>'submit-cargar')) }}
		<div class="row">
			@if ($errors->has())
				<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<p>{{ $errors->first('username') }}</p>				
					<p>{{ $errors->first('nombre') }}</p>
					<p>{{ $errors->first('apellido_paterno') }}</p>
					<p>{{ $errors->first('apellido_materno') }}</p>
					<p>{{ $errors->first('tipo_doc_identidad') }}</p>
					<p>{{ $errors->first('documento_identidad') }}</p>
					<p>{{ $errors->first('genero') }}</p>
					<p>{{ $errors->first('fecha_nacimiento') }}</p>
					<p>{{ $errors->first('idrol') }}</p>
					<p>{{ $errors->first('genero') }}</p>
				</div>
			@endif
		</div>
		<!-- OVERVIEW -->	
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><strong>ADJUNTAR ARCHIVO</strong></h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-9">
								<label class="control-label">Seleccione Archivo .CSV</label>
								<input id="input-file" type="file">							
							</div>
							<div class="col-md-3" style="padding-top:26px">				
								<a class="btn btn-info btn-block" id="btnVistaPrevia" type="submit"> <i class="fa fa-upload"></i> Cargar</a>
							</div>
						</div>	
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><strong>RESULTADO DE CARGA</strong></h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6 col-md-offset-3">
								<label class="control-label">Registros Procesados:</label>
								{{ Form::text('numero_registros',Input::old('numero_registros'),array('class'=>'form-control','disabled'=>'disabled')) }}							
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
		{{ Form::close() }}
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><strong>VISTA PREVIA</strong></h3>
			</div>		
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th class="text-nowrap text-center">Código Ticket</th>
										<th class="text-nowrap text-center">Asunto</th>
										<th class="text-nowrap text-center">Entidad</th>
										<th class="text-nowrap text-center">Aplicativo</th>
										<th class="text-nowrap text-center">Fecha Solicitud</th>
									</tr>
								</thead>
								<tbody>	
									<tr >
										<td class="text-nowrap text-center">
											<a href="#"></a>
										</td>
										<td class="text-nowrap text-center">
											
										</td>
										<td class="text-nowrap text-center">
											
										</td>
										<td class="text-nowrap text-center">
											
										</td>
										<td class="text-nowrap text-center">
											
										</td>
									</tr>
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="form-group col-md-2  col-md-offset-8">				
				<a class="btn btn-info btn-block" id="btnCargar" type="submit"> <i class="fa fa-floppy-o"></i>  Guardar</a>
			</div>
			<div class="form-group col-md-2">
				<a class="btn btn-default btn-block" href="{{URL::to('/principal')}}">Cancelar</a>				
			</div>
		</div>

	</div>
</div>

@stop