@extends('Templates/loginTemplate')
@section('content')


<div class="vertical-align-wrap">
	<div class="vertical-align-middle">
		<div class="auth-box ">
			<div class="left">
				<div class="content">
					<div id="message-container">
						@if (Session::has('error'))			
							<div class="alert alert-danger alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<i class="fa fa-warning"></i>
								{{ Session::get('error') }}
							</div>
						@endif
						@if (Session::has('message'))		
							<div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<i class="fa fa-check-circle"></i>
								{{ Session::get('message') }}
							</div>
						@endif
					</div>
					<div class="header">
						<div class="logo text-center"><img src="{{asset('assets/img/telefonica-logo.jpg')}}" alt="Klorofil Logo" width="50%" height="50%"></div>
						<p class="lead"><strong>LOGIN</strong></p>
					</div>
					{{ Form::open(array('url'=>'login', 'role'=>'form', 'class'=>'form-auth-small')) }}
					<div class="form-group">
							<label class="control-label sr-only">Nombre de Usuario</label>
							{{ Form::text('usuario',Input::old('usuario'),array('class'=>'form-control','placeholder'=>'Ingrese nombre de usuario')) }}
					</div>
					<div class="form-group">
						<label for="signin-password" class="control-label sr-only">Contraseña</label>
						{{ Form::password('password',array('class'=>'form-control','placeholder'=>'Ingrese contraseña','id'=>'signin-password')) }}
					</div>														
					{{ Form::submit('ACCEDER',array('id'=>'submit-login', 'class'=>'btn btn-lg btn-info btn-block')) }}
					{{ Form::close() }}
				</div>
			</div>
			<div class="right">
				<div class="overlay"></div>
				<div class="content text">
					<h2 class="heading" style="text-align:center" ><strong>SISTEMA DE GESTIÓN DE REGISTRO DE USUARIOS</strong></h2>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
@stop