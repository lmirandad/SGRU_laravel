@extends('Templates/loginTemplate')
@section('content')
<div class="vertical-align-wrap">
	<div class="vertical-align-middle">
		<div class="auth-box ">
			<div class="left">
				<div class="content">
					<div class="row">
						<div id="message-container">
							@if ($errors->has())			
								<div class="alert alert-danger alert-dismissible" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<i class="fa fa-warning"></i>
									<p>{{ $errors->first('password_nueva') }}</p>				
									<p>{{ $errors->first('password_nueva_2') }}</p>
								</div>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="header">
							<div class="logo text-center"><img src="{{asset('assets/img/telefonica-logo.jpg')}}" alt="Klorofil Logo" width="50%" height="50%"></div>
							<p class="lead">CAMBIO DE CONTRASEÑA</p>
						</div>
					</div>
					{{ Form::open(array('url'=>'usuarios/submit_cambiar_contrasena', 'role'=>'form', 'class'=>'form-auth-small')) }}
					
						<div class="form-group">
							<label for="signin-password" class="control-label sr-only">Nueva Contraseña </label>
							{{ Form::password('password_nueva',array('class'=>'form-control','placeholder'=>'Ingrese nueva contraseña','id'=>'signin-password')) }}
						</div>	
						<div class="form-group">
							<label for="signin-password" class="control-label sr-only">Repetir Nueva Contraseña</label>
							{{ Form::password('password_nueva_2',array('class'=>'form-control','placeholder'=>'Repita ingreso nueva contraseña','id'=>'signin-password1')) }}
						</div>	
						<div class="form-group">
							{{ Form::button('CAMBIAR CONTRASEÑA', array('id'=>'submit-login','type' => 'submit', 'class' => 'btn btn-lg btn-info btn-block')) }}							
						</div>
					
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