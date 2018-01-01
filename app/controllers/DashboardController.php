<?php

class DashboardController extends BaseController {

	public function mostrar_dashboard($flag_seleccion=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1)
			{	
				$data["fecha_anho"] = null;
				$data["usuarios"] = User::withTrashed()->where('users.idrol','=',2)->select(DB::raw('CONCAT(nombre,\' \',apellido_paterno,\' \',apellido_materno) as nombre_completo'),'users.id')->lists('nombre_completo','id');
				
				$data["flag_seleccion"] = $flag_seleccion;


				//echo '<pre>';var_dump(Requerimiento::mostrarRequerimientoEstadoUsuarioMes(1,12,2017,2)); echo '</pre>';

				return View::make('Reporteria/dashboard',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_dashboard_prueba()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			
			$solicitudes_atendidos = Solicitud::mostrarSolicitudPorEstado(1);
			$solicitudes_cerrados = Solicitud::mostrarSolicitudPorEstado(2);
			$solicitudes_pendientes = Solicitud::mostrarSolicitudPorEstado(3);
			$solicitudes_procesando = Solicitud::mostrarSolicitudPorEstado(4);
			$solicitudes_rechazados = Solicitud::mostrarSolicitudPorEstado(5);
			$solicitudes_anulados = Solicitud::mostrarSolicitudPorEstado(6);
			$mes = Meses::listarMeses()->get();


			return Response::json(array( 'success' => true,'atendidos'=>$solicitudes_atendidos,'cerrados'=>$solicitudes_cerrados,'pendientes'=>$solicitudes_pendientes,'procesando'=>$solicitudes_procesando,'rechazados'=>$solicitudes_rechazados,'anulados'=>$solicitudes_anulados,
				'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_estados()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$anho = Input::get('anho');
			$solicitudes_atendidos = Solicitud::mostrarSolicitudPorEstadoAnual(1,$anho);
			$solicitudes_cerrados = Solicitud::mostrarSolicitudPorEstadoAnual(2,$anho);
			$solicitudes_pendientes = Solicitud::mostrarSolicitudPorEstadoAnual(3,$anho);
			$solicitudes_procesando = Solicitud::mostrarSolicitudPorEstadoAnual(4,$anho);
			$solicitudes_rechazados = Solicitud::mostrarSolicitudPorEstadoAnual(5,$anho);
			$solicitudes_anulados = Solicitud::mostrarSolicitudPorEstadoAnual(6,$anho);
			$mes = Meses::listarMeses()->get();


			return Response::json(array( 'success' => true,'atendidos'=>$solicitudes_atendidos,'cerrados'=>$solicitudes_cerrados,'pendientes'=>$solicitudes_pendientes,'procesando'=>$solicitudes_procesando,'rechazados'=>$solicitudes_rechazados,'anulados'=>$solicitudes_anulados,
				'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_sectores()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$anho = Input::get('anho');
			$sectores = Sector::listarSectores()->get();
			$mes = Meses::listarMeses()->get();

			if($sectores == null || $sectores->isEmpty())
			{
				return Response::json(array( 'success' => true,'tiene_sectores'=>false),200);				
			}

			$cantidad_sectores = count($sectores);
			$array_cantidades_sectores = array();
			for($i = 0;$i<$cantidad_sectores;$i++)
			{
				$cantidad_sector = Solicitud::mostrarSolicitudPorSectorAnual($sectores[$i]->idsector,$anho);
				array_push($array_cantidades_sectores,$cantidad_sector);
			}

			


			return Response::json(array( 'success' => true,'tiene_sectores' => true,'resumen'=>$array_cantidades_sectores, 'sectores'=>$sectores,
				'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_usuario_estados()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$anho = Input::get('anho');
			$usuario = Input::get('usuario');
			$solicitudes_atendidos = Solicitud::mostrarSolicitudPorEstadoAnualUsuario(1,$anho,$usuario);
			$solicitudes_cerrados = Solicitud::mostrarSolicitudPorEstadoAnualUsuario(2,$anho,$usuario);
			$solicitudes_pendientes = Solicitud::mostrarSolicitudPorEstadoAnualUsuario(3,$anho,$usuario);
			$solicitudes_procesando = Solicitud::mostrarSolicitudPorEstadoAnualUsuario(4,$anho,$usuario);
			$solicitudes_rechazados = Solicitud::mostrarSolicitudPorEstadoAnualUsuario(5,$anho,$usuario);
			$solicitudes_anulados = Solicitud::mostrarSolicitudPorEstadoAnualUsuario(6,$anho,$usuario);
			$mes = Meses::listarMeses()->get();


			return Response::json(array( 'success' => true,'atendidos'=>$solicitudes_atendidos,'cerrados'=>$solicitudes_cerrados,'pendientes'=>$solicitudes_pendientes,'procesando'=>$solicitudes_procesando,'rechazados'=>$solicitudes_rechazados,'anulados'=>$solicitudes_anulados,
				'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_usuario_sectores()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$anho = Input::get('anho');
			$usuario = Input::get('usuario');

			$sectores = Sector::listarSectores()->get();
			$mes = Meses::listarMeses()->get();

			if($sectores == null || $sectores->isEmpty())
			{
				return Response::json(array( 'success' => true,'tiene_sectores'=>false),200);				
			}

			$cantidad_sectores = count($sectores);
			$array_cantidades_sectores = array();
			for($i = 0;$i<$cantidad_sectores;$i++)
			{
				$cantidad_sector = Solicitud::mostrarSolicitudPorSectorAnualUsuario($sectores[$i]->idsector,$anho,$usuario);
				array_push($array_cantidades_sectores,$cantidad_sector);
			}

			


			return Response::json(array( 'success' => true,'tiene_sectores' => true,'resumen'=>$array_cantidades_sectores, 'sectores'=>$sectores,
				'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_aplicativos()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$anho = Input::get('anho');

			$herramientas = Solicitud::listarHerramientasEnSolicitudes()->get();

			if($herramientas == null || $herramientas->isEmpty())
				return Response::json(array( 'success' => true, 'herramientas' => null ),200);				

			$mes = Meses::listarMeses()->get();
			$cantidad_aplicativos = count($herramientas);
			$resumen_aplicativos = array();
			
			for($i=0;$i<$cantidad_aplicativos;$i++)
			{
				$resumen_aplicativo = Solicitud::mostrarSolicitudPorAplicativoAnual($herramientas[0]->idherramienta,$anho);
				array_push($resumen_aplicativos, $resumen_aplicativo);
			}

			//PENDIENTE PARA LOS NO DETECTADOS
			$resumen_aplicativo = Solicitud::mostrarSolicitudPorAplicativoNoDetectadoAnual($anho);
			array_push($resumen_aplicativos,$resumen_aplicativo);

			return Response::json(array( 'success' => true,'herramientas'=>$herramientas,'resumen' => $resumen_aplicativos,'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_usuario_aplicativos()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$anho = Input::get('anho');
			$usuario = Input::get('usuario');

			$herramientas = Solicitud::listarHerramientasEnSolicitudes()->get();

			if($herramientas == null || $herramientas->isEmpty())
				return Response::json(array( 'success' => true, 'herramientas' => null ),200);				

			$mes = Meses::listarMeses()->get();
			$cantidad_aplicativos = count($herramientas);
			$resumen_aplicativos = array();
			
			for($i=0;$i<$cantidad_aplicativos;$i++)
			{
				$resumen_aplicativo = Solicitud::mostrarSolicitudPorAplicativoAnualUsuario($herramientas[0]->idherramienta,$anho,$usuario);
				array_push($resumen_aplicativos, $resumen_aplicativo);
			}

			//PENDIENTE PARA LOS NO DETECTADOS
			$resumen_aplicativo = Solicitud::mostrarSolicitudPorAplicativoNoDetectadoAnualUsuario($anho,$usuario);
			array_push($resumen_aplicativos,$resumen_aplicativo);

			return Response::json(array( 'success' => true,'herramientas'=>$herramientas,'resumen' => $resumen_aplicativos,'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_requerimientos()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$anho = Input::get('anho');

			$mes = Meses::listarMeses()->get();
			$resumen_atendidos = Requerimiento::mostrarRequerimientoEstadoAnual(1,$anho);
			$resumen_rechazados = Requerimiento::mostrarRequerimientoEstadoAnual(2,$anho);
			$resumen_pendientes = Requerimiento::mostrarRequerimientoEstadoAnual(3,$anho);

			return Response::json(array( 'success' => true,'atendidos' => $resumen_atendidos,'rechazados' => $resumen_rechazados,'pendientes' => $resumen_pendientes,'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_usuario_requerimientos()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$anho = Input::get('anho');
			$usuario = Input::get('usuario');

			$mes = Meses::listarMeses()->get();
			$resumen_atendidos = Requerimiento::mostrarRequerimientoEstadoUsuarioAnual(1,$anho,$usuario);
			$resumen_rechazados = Requerimiento::mostrarRequerimientoEstadoUsuarioAnual(2,$anho,$usuario);
			$resumen_pendientes = Requerimiento::mostrarRequerimientoEstadoUsuarioAnual(3,$anho,$usuario);

			return Response::json(array( 'success' => true,'atendidos' => $resumen_atendidos,'rechazados' => $resumen_rechazados,'pendientes' => $resumen_pendientes,'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	/****************************************************************************************************************************************************/
	/*****************************************DASHBOARDO MENSUAL ****************************************************************************************/

	public function mostrar_dashboard_mes_estados()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$mes = Input::get('mes');
			$anho = Input::get('anho');
			$solicitudes_atendidos = Solicitud::mostrarSolicitudPorEstadoMes(1,$mes,$anho);
			$solicitudes_cerrados = Solicitud::mostrarSolicitudPorEstadoMes(2,$mes,$anho);
			$solicitudes_pendientes = Solicitud::mostrarSolicitudPorEstadoMes(3,$mes,$anho);
			$solicitudes_procesando = Solicitud::mostrarSolicitudPorEstadoMes(4,$mes,$anho);
			$solicitudes_rechazados = Solicitud::mostrarSolicitudPorEstadoMes(5,$mes,$anho);
			$solicitudes_anulados = Solicitud::mostrarSolicitudPorEstadoMes(6,$mes,$anho);


			return Response::json(array( 'success' => true,'atendidos'=>$solicitudes_atendidos,'cerrados'=>$solicitudes_cerrados,'pendientes'=>$solicitudes_pendientes,'procesando'=>$solicitudes_procesando,'rechazados'=>$solicitudes_rechazados,'anulados'=>$solicitudes_anulados),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_mes_sectores()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$mes = Input::get('mes');
			$anho = Input::get('anho');

			$sectores = Sector::listarSectores()->get();
			
			
			if($sectores == null || $sectores->isEmpty())
			{
				return Response::json(array( 'success' => true,'tiene_sectores'=>false),200);				
			}

			$solicitudes_atendidos = Solicitud::mostrarSolicitudPorSectorMes(1,$mes,$anho);
			$solicitudes_cerrados = Solicitud::mostrarSolicitudPorSectorMes(2,$mes,$anho);
			$solicitudes_pendientes = Solicitud::mostrarSolicitudPorSectorMes(3,$mes,$anho);
			$solicitudes_procesando = Solicitud::mostrarSolicitudPorSectorMes(4,$mes,$anho);
			$solicitudes_rechazados = Solicitud::mostrarSolicitudPorSectorMes(5,$mes,$anho);
			$solicitudes_anulados = Solicitud::mostrarSolicitudPorSectorMes(6,$mes,$anho);
			

			return Response::json(array( 'success' => true,'tiene_sectores'=>true,'sectores'=>$sectores,'atendidos'=>$solicitudes_atendidos,'cerrados'=>$solicitudes_cerrados,'pendientes'=>$solicitudes_pendientes,'procesando'=>$solicitudes_procesando,'rechazados'=>$solicitudes_rechazados,'anulados'=>$solicitudes_anulados),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_mes_aplicativos()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$mes = Input::get('mes');
			$anho = Input::get('anho');

			$herramientas = Solicitud::listarHerramientasEnSolicitudesMes($mes,$anho)->get();
			
			
			if($herramientas == null || $herramientas->isEmpty())
			{
				return Response::json(array( 'success' => true,'tiene_herramientas'=>false),200);				
			}	

			$cantidad_aplicativos = count($herramientas);
			$array_aplicativos = array();
			for($i=0;$i<$cantidad_aplicativos;$i++)
			{
				$cantidad_herramienta = Solicitud::mostrarCantidadSolicitudMesAplicativo($mes,$anho,$herramientas[$i]->idherramienta);
				array_push($array_aplicativos,$cantidad_herramienta);
			}

			//PENDIENTE PARA LOS NO DETECTADOS
			$resumen_aplicativo = Solicitud::mostrarSolicitudPorAplicativoNoDetectadoMes($mes,$anho);
			array_push($array_aplicativos,$resumen_aplicativo);


			return Response::json(array( 'success' => true,'tiene_herramientas'=>true,'herramientas'=>$herramientas,'resumen'=>$array_aplicativos),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_mes_requerimientos()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$anho = Input::get('anho');
			$mes = Input::get('mes');

			
			$resumen_atendidos = Requerimiento::mostrarRequerimientoEstadoMes(1,$mes,$anho);
			$resumen_rechazados = Requerimiento::mostrarRequerimientoEstadoMes(2,$mes,$anho);
			$resumen_pendientes = Requerimiento::mostrarRequerimientoEstadoMes(3,$mes,$anho);


			return Response::json(array( 'success' => true,'atendidos'=>$resumen_atendidos,'rechazados'=>$resumen_rechazados,'pendientes'=>$resumen_pendientes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_mes_estados_usuario()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$mes = Input::get('mes');
			$anho = Input::get('anho');
			$usuario = Input::get('usuario');
			$solicitudes_atendidos = Solicitud::mostrarSolicitudPorEstadoMesUsuario(1,$mes,$anho,$usuario);
			$solicitudes_cerrados = Solicitud::mostrarSolicitudPorEstadoMesUsuario(2,$mes,$anho,$usuario);
			$solicitudes_pendientes = Solicitud::mostrarSolicitudPorEstadoMesUsuario(3,$mes,$anho,$usuario);
			$solicitudes_procesando = Solicitud::mostrarSolicitudPorEstadoMesUsuario(4,$mes,$anho,$usuario);
			$solicitudes_rechazados = Solicitud::mostrarSolicitudPorEstadoMesUsuario(5,$mes,$anho,$usuario);
			$solicitudes_anulados = Solicitud::mostrarSolicitudPorEstadoMesUsuario(6,$mes,$anho,$usuario);


			return Response::json(array( 'success' => true,'atendidos'=>$solicitudes_atendidos,'cerrados'=>$solicitudes_cerrados,'pendientes'=>$solicitudes_pendientes,'procesando'=>$solicitudes_procesando,'rechazados'=>$solicitudes_rechazados,'anulados'=>$solicitudes_anulados),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_mes_sectores_usuario()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$mes = Input::get('mes');
			$anho = Input::get('anho');
			$usuario = Input::get('usuario');

			$sectores = Sector::listarSectores()->get();
			
			
			if($sectores == null || $sectores->isEmpty())
			{
				return Response::json(array( 'success' => true,'tiene_sectores'=>false),200);				
			}

			$solicitudes_atendidos = Solicitud::mostrarSolicitudPorSectorMesUsuario(1,$mes,$anho,$usuario);
			$solicitudes_cerrados = Solicitud::mostrarSolicitudPorSectorMesUsuario(2,$mes,$anho,$usuario);
			$solicitudes_pendientes = Solicitud::mostrarSolicitudPorSectorMesUsuario(3,$mes,$anho,$usuario);
			$solicitudes_procesando = Solicitud::mostrarSolicitudPorSectorMesUsuario(4,$mes,$anho,$usuario);
			$solicitudes_rechazados = Solicitud::mostrarSolicitudPorSectorMesUsuario(5,$mes,$anho,$usuario);
			$solicitudes_anulados = Solicitud::mostrarSolicitudPorSectorMesUsuario(6,$mes,$anho,$usuario);
			

			return Response::json(array( 'success' => true,'tiene_sectores'=>true,'sectores'=>$sectores,'atendidos'=>$solicitudes_atendidos,'cerrados'=>$solicitudes_cerrados,'pendientes'=>$solicitudes_pendientes,'procesando'=>$solicitudes_procesando,'rechazados'=>$solicitudes_rechazados,'anulados'=>$solicitudes_anulados),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_mes_aplicativos_usuario()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$mes = Input::get('mes');
			$anho = Input::get('anho');
			$usuario = Input::get('usuario');

			$herramientas = Solicitud::listarHerramientasEnSolicitudesMes($mes,$anho)->get();
			
			
			if($herramientas == null || $herramientas->isEmpty())
			{
				return Response::json(array( 'success' => true,'tiene_herramientas'=>false),200);				
			}	

			$cantidad_aplicativos = count($herramientas);
			$array_aplicativos = array();
			for($i=0;$i<$cantidad_aplicativos;$i++)
			{
				$cantidad_herramienta = Solicitud::mostrarCantidadSolicitudMesAplicativoUsuario($mes,$anho,$herramientas[$i]->idherramienta,$usuario);
				array_push($array_aplicativos,$cantidad_herramienta);
			}

			//PENDIENTE PARA LOS NO DETECTADOS
			$resumen_aplicativo = Solicitud::mostrarSolicitudPorAplicativoNoDetectadoMesUsuario($mes,$anho,$usuario);
			array_push($array_aplicativos,$resumen_aplicativo);


			return Response::json(array( 'success' => true,'tiene_herramientas'=>true,'herramientas'=>$herramientas,'resumen'=>$array_aplicativos),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_mes_requerimientos_usuario()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$anho = Input::get('anho');
			$mes = Input::get('mes');
			$usuario = Input::get('usuario');

			
			$resumen_atendidos = Requerimiento::mostrarRequerimientoEstadoUsuarioMes(1,$mes,$anho,$usuario);
			$resumen_rechazados = Requerimiento::mostrarRequerimientoEstadoUsuarioMes(2,$mes,$anho,$usuario);
			$resumen_pendientes = Requerimiento::mostrarRequerimientoEstadoUsuarioMes(3,$mes,$anho,$usuario);


			return Response::json(array( 'success' => true,'atendidos'=>$resumen_atendidos,'rechazados'=>$resumen_rechazados,'pendientes'=>$resumen_pendientes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

}
