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
							@if($user->idrol == 1)
								<li><a href="{{URL::to('solicitudes/cargar_solicitudes')}}" class="">Cargar/Asignar Solicitudes</a></li>	
								<li><a href="{{URL::to('solicitudes/crear_solicitud')}}" class="">Creación Manual de Solicitud</a></li>	
							@endif						
						</ul>
					</div>
				</li>
				@if($user->idrol == 1)
				<li> <!-- SUBMENÚ MANTENIMIENTOS -->
					<a href="#subPagesMantenimientos" data-toggle="collapse" class="collapsed"><i class="lnr lnr-construction"></i> <span>Mantenimientos</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
					<div id="subPagesMantenimientos" class="collapse ">
						<ul class="nav"> 
							<li><a href="{{URL::to('entidades_canales_sectores/listar/0')}}"><i class="lnr lnr-store"></i><span>Sectores, Canales y Entidades</span></a></li> 
							<li><a href="{{URL::to('herramientas/listar_herramientas')}}"><i class="lnr lnr-laptop-phone"></i><span>Aplicativos</span></a></li>
							<li><a href="{{URL::to('usuarios/listar_usuarios')}}"><i class="lnr lnr-user"></i><span>Usuarios Sistema</span> </a>
							</li>
							<li><a href="{{URL::to('equivalencias_tipo_solicitud/listar_equivalencias')}}"><i class="fa fa-exchange"></i><span>Equivalencias Acción Solicitud</span> </a>
							</li>
							<li><a href="{{URL::to('feriados/listar_feriados')}}"><i class="fa fa-calendar"></i><span>Feriados</span> </a>
							<li><a href="{{URL::to('usuarios_observados_vena/cargar_usuarios_observados_vena')}}"><i class="lnr lnr-cross-circle"></i><span>Lista Vena / Observados</span> </a>
							</li>
						</ul>
					</div>
				</li>
				@endif
				@if($user->idrol == 1)
				<li>
					<a href="#subPagesReporteria" data-toggle="collapse" class="collapsed"><i class="lnr lnr-chart-bars"></i> <span>Reportería</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
					<div id="subPagesReporteria" class="collapse ">
						<ul class="nav"> 
							<li><a href="{{URL::to('/reportes')}}"><i class="fa fa-file-text-o "></i><span>Reportes</span></a></li> 
							<!--
								<li><a href="{{URL::to('/dashboard/0')}}"><i class="lnr lnr-pie-chart"></i><span>Dashboard</span></a></li>
							-->
						</ul>
					</div>
				</li>
				@endif
			</ul>
		</nav>
	</div>
</div>
<!-- END LEFT SIDEBAR -->