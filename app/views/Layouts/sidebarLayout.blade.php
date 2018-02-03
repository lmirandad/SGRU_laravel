<!-- LEFT SIDEBAR -->
<div id="sidebar-nav" class="sidebar" style="background-color:#F7F9F1;border-right:solid 3px #015C63;">
	<div class="sidebar-scroll">
		<nav>
			<ul class="nav">
				<li style="border-bottom:solid 2px #015C63">
					<a href="#subPagesSolicitudes" data-toggle="collapse" class="collapsed" style="background-color:#F7F9F1"> <span><font style="color:#00272E"><strong><i class="lnr lnr-file-add"></i>Solicitudes</font></strong></span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
					<div id="subPagesSolicitudes" class="collapse"  >
						<ul class="nav" style="background-color:#F7F9F1;">
							<li><a href="{{URL::to('solicitudes/listar_solicitudes')}}" class=""><font style="color:#00272E"><i class="fa fa-search"></i>Buscar Solicitudes</font></a></li>
							@if($user->idrol == 1)
								<li><a href="{{URL::to('solicitudes/cargar_solicitudes')}}" class=""><font style="color:#00272E"><i class="fa fa-paper-plane-o"></i>Cargar/Asignar Solicitudes</font></a></li>	
								<li><a href="{{URL::to('solicitudes/crear_solicitud')}}" class=""><font style="color:#00272E"><i class="lnr lnr-text-align-left"></i>Creación Manual de Solicitud</font></a></li>	
							@endif						
						</ul>
					</div>
				</li>
				@if($user->idrol == 1)
				<li style="border-bottom:solid 3px #015C63"> <!-- SUBMENÚ MANTENIMIENTOS -->
					<a href="#subPagesMantenimientos" data-toggle="collapse" class="collapsed" style="background-color:#F7F9F1"><span><font style="color:#00272E"><i class="lnr lnr-construction"></i><strong>Mantenimientos</strong></font></span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
					<div id="subPagesMantenimientos" class="collapse ">
						<ul class="nav" style="background-color:#F7F9F1"> 
							<li><a href="{{URL::to('entidades_canales_sectores/listar/0')}}"><font style="color:#00272E"><i class="lnr lnr-store"></i><span>Sectores, Canales y Entidades</font></span></a></li> 
							<li><a href="{{URL::to('herramientas/listar_herramientas')}}"><font style="color:#00272E"><i class="lnr lnr-laptop-phone"></i><span>Aplicativos</font></span></a></li>
							<li><a href="{{URL::to('usuarios/listar_usuarios')}}"><font style="color:#00272E"><i class="lnr lnr-user"></i><span>Usuarios Sistema</font></span> </a>
							</li>
							<li><a href="{{URL::to('equivalencias_tipo_solicitud/listar_equivalencias')}}"><font style="color:#00272E"><i class="fa fa-exchange"></i><span>Equivalencias Acción Solicitud</font></span> </a>
							</li>
							<li><a href="{{URL::to('feriados/listar_feriados')}}"><font style="color:#00272E"><i class="fa fa-calendar"></i><span>Feriados</font></span> </a>
							</li>
							<!-- COMING SOON
							<li><a href="{{URL::to('rango_dias/listar_rangos')}}"><font style="color:#00272E"><i class="lnr lnr-code"></i><span>Rango Dias Reporteria</font></span> </a>
							</li>
							-->
							<li><a href="{{URL::to('usuarios_observados_vena/cargar_usuarios_observados_vena')}}"><font style="color:#00272E"><i class="lnr lnr-cross-circle"></i><span>Lista Vena / Observados</font></span> </a>
							</li>
						</ul>
					</div>
				</li>
				@endif
				@if($user->idrol == 1)
				<li style="border-bottom:solid 3px #015C63">
					<a href="#subPagesReporteria" data-toggle="collapse" class="collapsed" style="background-color:#F7F9F1"><span><font style="color:#00272E"><i class="lnr lnr-chart-bars"></i><strong>Reporteria</strong></font></span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
					<div id="subPagesReporteria" class="collapse ">
						<ul class="nav" style="background-color:#F7F9F1"> 
							<li><a href="{{URL::to('/reportes')}}"><font style="color:#00272E"><i class="fa fa-file-text-o "></i><span>Reportes</font></span></a></li>						
							<li><a href="{{URL::to('/dashboardBI')}}"><font style="color:#00272E"><i class="lnr lnr-pie-chart"></i><span>Dashboard</font></span></a></li>
							
						</ul>
					</div>
				</li>
				@endif
			</ul>
		</nav>
	</div>
</div>
<!-- END LEFT SIDEBAR -->