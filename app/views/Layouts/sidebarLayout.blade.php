<!-- LEFT SIDEBAR -->
<div id="sidebar-nav" class="sidebar">
	<div class="sidebar-scroll">
		<nav>
			<ul class="nav">
				<li>
					<a href="#subPagesSolicitudes" data-toggle="collapse" class="collapsed"><i class="lnr lnr-file-add"></i> <span>Solicitudes</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
					<div id="subPagesSolicitudes" class="collapse ">
						<ul class="nav">
							<li><a href="{{URL::to('solicitudes/listar_solicitudes')}}" class="">Buscar Solicitudes</a></li>
							<li><a href="{{URL::to('solicitudes/cargar_solicitudes')}}" class="">Cargar/Asignar Solicitudes</a></li>							
						</ul>
					</div>
				</li>
				<li>
					<a href="#subPagesRequerimientos" data-toggle="collapse" class="collapsed"><i class="lnr lnr-list"></i> <span>Requerimientos</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
					<div id="subPagesRequerimientos" class="collapse">
						<ul class="nav">
							<li><a href="#" class="">Buscar Requerimientos</a></li>
							<li><a href="#" class="">Registrar Requerimientos</a></li>
						</ul>
					</div>
				</li>
				@if($user->idrol == 1)
				<li> <!-- SUBMENÚ MANTENIMIENTOS -->
					<a href="#subPagesMantenimientos" data-toggle="collapse" class="collapsed"><i class="lnr lnr-construction"></i> <span>Mantenimientos</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
					<div id="subPagesMantenimientos" class="collapse ">
						<ul class="nav"> 
							<li><a href="{{URL::to('entidades_canales_sectores/listar/0')}}"><i class="lnr lnr-store"></i><span>Sectores, Canales y Entidades</span></a></li> 
							<li><a href="#subPagesHerramientas" data-toggle="collapse" class="collapsed"><i class="lnr lnr-laptop-phone"></i><span>Aplicativos</span><i class="icon-submenu lnr lnr-chevron-left"></i></a> <!-- SUBMENÚ HERRAMIENTAS-->
								<div id="subPagesHerramientas" class="collapse">
									<ul class="nav"> 
										<li><a href="#" class=""><i class="lnr lnr-plus-circle"></i>Crear Aplicativo</a></li>
										<li><a href="{{URL::to('herramientas/listar_herramientas')}}" class=""><i class="fa fa-search"></i>Buscar Aplicativos</a></li>
									</ul>
								</div>
							</li>
							<li><a href="#subPagesUsuarios" data-toggle="collapse" class="collapsed"><i class="lnr lnr-user"></i><span>Usuarios Sistema</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a><!-- SUBMENÚ USUARIOS-->
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
				@endif
				<li><a href="#" class=""><i class="lnr lnr-chart-bars"></i> <span>Indicadores</span></a></li>
			</ul>
		</nav>
	</div>
</div>
<!-- END LEFT SIDEBAR -->