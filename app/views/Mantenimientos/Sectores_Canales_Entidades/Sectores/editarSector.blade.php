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
		{{ Form::open(array('url'=>'/sectores/submit_editar_sector' ,'role'=>'form','id'=>'submit-editar')) }}
		{{ Form::hidden('sector_id', $sector->idsector, array('id'=>'sector_id')) }}	
		<!-- OVERVIEW -->		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-headline">
					<div class="panel-heading">
						<h3 class="panel-title">Datos Generales</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							@if ($errors->has())
							<div class="alert alert-danger" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<p>{{ $errors->first('nombre_sector') }}</p>				
								<p>{{ $errors->first('descripcion') }}</p>
							</div>
							@endif
						</div>
						<div class="row">
							<div class="form-group col-md-3 @if($errors->first('nombre_sector')) has-error has-feedback @endif"">
								{{ Form::label('nombre_sector','Nombre del Sector')}}
								{{ Form::text('nombre_sector',$sector->nombre,array('class'=>'form-control','placeholder'=>'Ingrese nombre del sector')) }}
							</div>
							<div class="form-group col-md-9 @if($errors->first('descripcion')) has-error has-feedback @endif"">
								{{ Form::label('descripcion','Descripción:')}}
								{{ Form::textarea('descripcion',$sector->descripcion,array('class'=>'form-control','placeholder'=>'Ingrese una descripción','rows'=>5,'style'=>'resize:none')) }}
							</div>
						</div>						
					</div>
				</div>
			</div>
		</div>		
		
		<div class="row">
			<div class="form-group col-md-2  col-md-offset-8">				
				<a class="btn btn-info btn-block" id="btnEditarSector"> <i class="fa fa-floppy-o"></i> Editar</a>
			</div>
			<div class="form-group col-md-2">
				<a class="btn btn-default btn-block" href="{{URL::to('entidades_canales_sectores/listar/1')}}"><i class="lnr lnr-arrow-left"></i>&nbspCancelar</a>				
			</div>
		</div>
		{{ Form::close() }}		
	</div>
		
				
	</div>
</div>
@stop