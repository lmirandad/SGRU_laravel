<!-- NAVBAR -->
<nav class="navbar navbar-default navbar-fixed-top" style="background-color:#015C63;color:white">
	<div class="brand" style="background-color:#015C63 ;width:25%" >
		@if($user->idrol == 1)
		<a href="{{URL::to('/principal_admin')}}"><img src="{{asset('assets/img/nuevo_logo.png')}}" alt="SGRU" class="img-responsive logo" style="margin-bottom: -30px;margin-top:-20px;padding-left:-20px"></a>
		@elseif($user->idrol == 2)		
		<a href="{{URL::to('/principal_gestor')}}"><img src="{{asset('assets/img/nuevo_logo.png')}}" alt="SGRU" class="img-responsive logo" style="margin-bottom: -30px;margin-top:-20px;padding-left:-20px"></a>
		@elseif($user->idrol == 5)		
		<a href="{{URL::to('/principal_admin_planilla')}}"><img src="{{asset('assets/img/nuevo_logo.png')}}" alt="SGRU" class="img-responsive logo" style="margin-bottom: -30px;margin-top:-20px;padding-left:-20px"></a>
		@elseif($user->idrol == 6)		
		<a href="{{URL::to('/principal_gestor_planilla')}}"><img src="{{asset('assets/img/nuevo_logo.png')}}" alt="SGRU" class="img-responsive logo" style="margin-bottom: -30px;margin-top:-20px;padding-left:-20px"></a>
		@endif
	</div>
	<div class="container-fluid">
		<div class="navbar-btn">
			<button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
		</div>
		<div id="navbar-menu">
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="background-color:#015C63;color:white"><i class="fa fa-id-badge" style="color:white"></i>  <span>
						@if(date('H') >= 0 && date('H') <= 11)
							<font style="color:white">Buenos días,</font>
						@elseif(date('H') >= 12 && date('H') <= 17)
							<font style="color:white">Buenas tardes,</font>
						@else(date('H') >= 18 && date('H') <= 23)
							<font style="color:white">Buenas noches,</font>
						@endif
						<font style="color:white"><strong>&nbsp{{$user->nombre}}</strong></font> </span> <i class="icon-submenu lnr lnr-chevron-down" style="color:white"></i></a>
					<ul class="dropdown-menu" style="background-color:#00272E;color:white">
						<li><a href="{{URL::to('/usuarios/mostrar_usuario_sesion')}}/{{$user->id}}" style="background-color:#015C63;color:white"><i class="lnr lnr-user"></i> <span>Ver Usuario</span></a></li>
						<li><a href="{{ URL::to('logout') }}" style="background-color:#015C63;color:white"><i class="lnr lnr-exit"></i> <span>Cerrar Sesión</span></a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
<!-- END NAVBAR -->