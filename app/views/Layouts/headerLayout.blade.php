<!-- NAVBAR -->
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="brand">
		<a href="index.html"><img src="{{asset('assets/img/logo-dark.png')}}" alt="Klorofil Logo" class="img-responsive logo"></a>
	</div>
	<div class="container-fluid">
		<div class="navbar-btn">
			<button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
		</div>
		<div id="navbar-menu">
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-id-badge"></i>  <span>Bienvenido, <strong>{{$user->nombre}}</strong> </span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
					<ul class="dropdown-menu">
						<li><a href="{{URL::to('/usuarios/mostrar_usuario_sesion')}}/{{$user->id}}"><i class="lnr lnr-user"></i> <span>Ver Usuario</span></a></li>
						<li><a href="{{ URL::to('logout') }}"><i class="lnr lnr-exit"></i> <span>Cerrar Sesión</span></a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
<!-- END NAVBAR -->