<!-- LEFT SIDEBAR -->
<div id="sidebar-nav" class="sidebar">
	<div class="sidebar-scroll">
		<nav>
			<ul class="nav">
				<li>
					<a href="#subPagesSolicitudes" data-toggle="collapse" class="collapsed"><i class="lnr lnr-file-add"></i> <span>Solicitudes</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
					<div id="subPagesSolicitudes" class="collapse ">
						<ul class="nav">
							<li><a href="page-login.html" class="">Buscar Solicitudes</a></li>
							<li><a href="{{URL::to('solicitudes/cargar_solicitudes')}}" class="">Cargar/Asignar Solicitudes</a></li>							
						</ul>
					</div>
				</li>
				<li>
					<a href="#subPagesRequerimientos" data-toggle="collapse" class="collapsed"><i class="lnr lnr-list"></i> <span>Requerimientos</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
					<div id="subPagesRequerimientos" class="collapse">
						<ul class="nav">
							<li><a href="page-profile.html" class="">Buscar Requerimientos</a></li>
							<li><a href="page-login.html" class="">Registrar Requerimientos</a></li>
						</ul>
					</div>
				</li>
				<li><a href="tables.html" class=""><i class="lnr lnr-chart-bars"></i> <span>Indicadores</span></a></li>
				<li>
					<a href="#subPagesMantenimientos" data-toggle="collapse" class="collapsed"><i class="lnr lnr-construction"></i> <span>Mantenimientos</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
					<div id="subPagesMantenimientos" class="collapse ">
						<ul class="nav">
							<li><a href="page-profile.html" class="">Entidades, Canales y Sectores</a></li>
							<li><a href="page-login.html" class=""><i class="lnr lnr-cog"></i>Herramientas</a></li>
							<li><a href="#subPagesUsuarios" data-toggle="collapse" class="collapsed"><i class="lnr lnr-users"></i><span>Usuarios</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
								<div id="subPagesUsuarios" class="collapse">
									<ul class="nav">
										<li><a href="{{URL::to('usuarios/crear_usuario')}}" class=""><i class="lnr lnr-plus-circle"></i>Crear Usuario</a></li>
										<li><a href="{{URL::to('usuarios/listar_usuarios')}}" class=""><i class="fa fa-search"></i>Buscar Usuarios</a></li>
									</ul>
								</div>
							</li>
						</ul>
					</div>
				</li>
			</ul>
		</nav>
	</div>
</div>
<!-- END LEFT SIDEBAR -->