@extends('templates/errorTemplate')
@section('content')
	<div style="width:270px;
				height:150px;
				position:absolute;
				left:50%;
				top:20%;
				margin:-75px 0 0 ">	
		<div class="col-md-12">
			<img style="width:100%;align:center;" src="{{ asset('img') }}/warning.png" ></img>
		</div>
		<div class="col-md-12">
			<h3 style="text-align:center;">Acceso Denegado</h3>
			<h5 style="text-align:center;"><strong>(No cuenta con permisos para acceder a esta funcionalidad)</strong></h5>
		</div>
	</div>
@stop