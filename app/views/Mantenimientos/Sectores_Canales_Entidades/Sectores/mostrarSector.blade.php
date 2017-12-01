@extends('Templates/Mantenimientos/sectorTemplate')
@section('content')
<!-- MAIN CONTENT -->
<div class="main-content">
	<div class="container-fluid">
		<h3 class="page-title"><strong>SECTOR:</strong> {{$sector->nombre}}</h3>

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
		{{ Form::hidden('sector_id', $sector->idsector, array('id'=>'sector_id')) }}	
		<!-- OVERVIEW -->		
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">Datos Generales</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="form-group col-md-4 @if($errors->first('nombre_sector')) has-error has-feedback @endif"">
								{{ Form::label('nombre_sector','Nombre del Sector')}}
								{{ Form::text('nombre_sector',$sector->nombre,array('class'=>'form-control','placeholder'=>'Ingrese nombre del sector','disabled'=>'disabled')) }}
							</div>
							<div class="form-group col-md-8 @if($errors->first('descripcion')) has-error has-feedback @endif"">
								{{ Form::label('descripcion','Descripción:')}}
								{{ Form::textarea('descripcion',$sector->descripcion,array('class'=>'form-control','placeholder'=>'Ingrese una descripción','rows'=>5,'style'=>'resize:none','disabled'=>'disabled')) }}
							</div>
						</div>						
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">Canales</h3>
					</div>
					<div class="panel-body">
						<div class="table-responsive" style="height:155px;overflow-y:auto; " >
							<table class="table table-hover"  >
								<thead>
									<tr>
										<th class="text-nowrap text-center">N°</th>
										<th class="text-nowrap text-center">Nombre Canal</th>
										<th class="text-nowrap text-center">Estado</th>
									</tr>
								</thead>
								
									<tbody >	
										@foreach($canales as $index  => $canal)
										<tr class="">
											<td class="text-nowrap text-center">
												{{$index+1}}
											</td>
											<td class="text-nowrap text-center">
												{{$canal->nombre}}
											</td>
											<td class="text-nowrap text-center">
												@if($canal->deleted_at == null)
													Activo
												@else
													Inactivo
												@endif
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
		

		<div class="row">
			<div class="form-group col-md-2 col-md-offset-10">
				<a class="btn btn-default btn-block" href="{{URL::to('entidades_canales_sectores/listar/1')}}"><i class="lnr lnr-arrow-left"></i>&nbspSalir</a>				
			</div>
		</div>
	</div>
		
				
	</div>
</div>
@stop