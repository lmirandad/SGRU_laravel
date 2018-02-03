<?php

class DashboardController extends BaseController {

	public function mostrar_dashboard_bi()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1)
			{	
				return View::make('Reporteria/dashboardPowerBI',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	/*public function mostrar_dashboard($flag_seleccion=null)
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


				//echo '<pre>';var_dump(Transaccion::mostrarTransaccionPorEstadoMesAplicativo(1,1,2018,1)); echo '</pre>';

				
				return View::make('Reporteria/dashboard',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}*/
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/* *********************************DASHBOARD ANUAL *****************************************/
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	/*
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

	public function mostrar_dashboard_anual_canales()
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
			$canales = Canal::listarCanales()->get();
			$mes = Meses::listarMeses()->get();

			if($canales == null || $canales->isEmpty())
			{
				return Response::json(array( 'success' => true,'tiene_canales'=>false),200);				
			}

			$cantidad_canales = count($canales);
			$array_cantidades_canales = array();
			for($i = 0;$i<$cantidad_canales;$i++)
			{
				$cantidad_canal = Solicitud::mostrarSolicitudPorCanalAnual($canales[$i]->idcanal,$anho);
				array_push($array_cantidades_canales,$cantidad_canal);
			}
			return Response::json(array( 'success' => true,'tiene_canales' => true,'resumen'=>$array_cantidades_canales, 'canales'=>$canales,
				'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_usuarios_canales()
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
			$canales = Canal::listarCanales()->get();
			$mes = Meses::listarMeses()->get();

			if($canales == null || $canales->isEmpty())
			{
				return Response::json(array( 'success' => true,'tiene_canales'=>false),200);				
			}

			$cantidad_canales = count($canales);
			$array_cantidades_canales = array();
			for($i = 0;$i<$cantidad_canales;$i++)
			{
				$cantidad_canal = Solicitud::mostrarSolicitudPorCanalAnualUsuario($canales[$i]->idcanal,$anho,$usuario);
				array_push($array_cantidades_canales,$cantidad_canal);
			}
			return Response::json(array( 'success' => true,'tiene_canales' => true,'resumen'=>$array_cantidades_canales, 'canales'=>$canales,
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
			$herramientas_con_transacciones = Herramienta::buscarTransaccionesPorAnho($anho)->get();

			if($herramientas_con_transacciones == null || $herramientas_con_transacciones->isEmpty())
				return Response::json(array( 'success' => true, 'tiene_herramientas'=>false),200);

			$array_cantidades_herramientas = array();

			$cantidad_herramientas = count($herramientas_con_transacciones);

			if($cantidad_herramientas > 10)
				$cantidad_herramientas = 10;

			for($i=0;$i<$cantidad_herramientas;$i++)
			{
				//por cada herramienta extraer sus tipos de estado de transaccion
				$transacciones_atendidas = Transaccion::mostrarTransaccionPorEstadoAnualAplicativo(1,$anho,$herramientas_con_transacciones[$i]->idherramienta);
				$transacciones_rechazadas = Transaccion::mostrarTransaccionPorEstadoAnualAplicativo(2,$anho,$herramientas_con_transacciones[$i]->idherramienta);
				$transacciones_pendientes = Transaccion::mostrarTransaccionPorEstadoAnualAplicativo(3,$anho,$herramientas_con_transacciones[$i]->idherramienta);

				$obj_cantidad_herramientas = [
					"nombre_herramienta" => $herramientas_con_transacciones[$i]->nombre,
					"cantidad_atendidos" => $transacciones_atendidas[0]->cantidad,
					"cantidad_rechazados" => $transacciones_rechazadas[0]->cantidad,
					"cantidad_pendientes" => $transacciones_pendientes[0]->cantidad
				];

				array_push($array_cantidades_herramientas,$obj_cantidad_herramientas);
			}


			return Response::json(array( 'success' => true,'tiene_herramientas'=>true,"resumen"=>$array_cantidades_herramientas),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_usuarios_aplicativos()
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
			$herramientas_con_transacciones = Herramienta::buscarTransaccionesPorAnhoPorUsuario($anho,$usuario)->get();

			if($herramientas_con_transacciones == null || $herramientas_con_transacciones->isEmpty())
				return Response::json(array( 'success' => true, 'tiene_herramientas'=>false),200);

			$array_cantidades_herramientas = array();

			$cantidad_herramientas = count($herramientas_con_transacciones);

			if($cantidad_herramientas > 10)
				$cantidad_herramientas = 10;

			for($i=0;$i<$cantidad_herramientas;$i++)
			{
				//por cada herramienta extraer sus tipos de estado de transaccion
				$transacciones_atendidas = Transaccion::mostrarTransaccionPorEstadoAnualAplicativoUsuario(1,$anho,$herramientas_con_transacciones[$i]->idherramienta,$usuario);
				$transacciones_rechazadas = Transaccion::mostrarTransaccionPorEstadoAnualAplicativoUsuario(2,$anho,$herramientas_con_transacciones[$i]->idherramienta,$usuario);
				$transacciones_pendientes = Transaccion::mostrarTransaccionPorEstadoAnualAplicativoUsuario(3,$anho,$herramientas_con_transacciones[$i]->idherramienta,$usuario);

				$obj_cantidad_herramientas = [
					"nombre_herramienta" => $herramientas_con_transacciones[$i]->nombre,
					"cantidad_atendidos" => $transacciones_atendidas[0]->cantidad,
					"cantidad_rechazados" => $transacciones_rechazadas[0]->cantidad,
					"cantidad_pendientes" => $transacciones_pendientes[0]->cantidad
				];

				array_push($array_cantidades_herramientas,$obj_cantidad_herramientas);
			}


			return Response::json(array( 'success' => true,'tiene_herramientas'=>true,"resumen"=>$array_cantidades_herramientas),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_gestion_seguridad()
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
			$transacciones_seg_si = Transaccion::mostrarTransaccionPorEstadoAnualAplicativoGestionSeguridad(1,$anho);
			$transacciones_seg_no = Transaccion::mostrarTransaccionPorEstadoAnualAplicativoGestionSeguridad(0,$anho);
			$transacciones_seg_validar = Transaccion::mostrarTransaccionPorEstadoAnualAplicativoGestionSeguridad(2,$anho);
			$mes = Meses::listarMeses()->get();


			return Response::json(array( 'success' => true,'flag_si'=>$transacciones_seg_si,'flag_no'=>$transacciones_seg_no,'flag_validar'=>$transacciones_seg_validar,'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_usuarios_gestion_seguridad()
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
			$transacciones_seg_si = Transaccion::mostrarTransaccionPorEstadoAnualUsuarioAplicativoGestionSeguridad(1,$anho,$usuario);
			$transacciones_seg_no = Transaccion::mostrarTransaccionPorEstadoAnualUsuarioAplicativoGestionSeguridad(0,$anho,$usuario);
			$transacciones_seg_validar = Transaccion::mostrarTransaccionPorEstadoAnualUsuarioAplicativoGestionSeguridad(2,$anho,$usuario);
			$mes = Meses::listarMeses()->get();


			return Response::json(array( 'success' => true,'flag_si'=>$transacciones_seg_si,'flag_no'=>$transacciones_seg_no,'flag_validar'=>$transacciones_seg_validar,'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_dias()
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
			$resumen = Solicitud::mostrarSolicitudAnualDia($anho);
			$dia = Dia::listarDias()->get();


			return Response::json(array( 'success' => true,'resumen'=>$resumen,'dias'=>$dia),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_anual_dias_usuarios()
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
			$resumen = Solicitud::mostrarSolicitudAnualDiaUsuario($anho,$usuario);
			$dia = Dia::listarDias()->get();


			return Response::json(array( 'success' => true,'resumen'=>$resumen,'dias'=>$dia),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}*/

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/* *********************************DASHBOARD MENSUAL *****************************************/
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

	/*
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

	public function mostrar_dashboard_mes_canales()
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
			$canales = Canal::listarCanalesConSolicitudesMesAnho($mes,$anho)->get();
			

			if($canales == null || $canales->isEmpty())
			{
				return Response::json(array( 'success' => true,'tiene_canales'=>false),200);				
			}

			$cantidad_canales = count($canales);
			$array_cantidades_canales = array();
			for($i = 0;$i<$cantidad_canales;$i++)
			{
				$cantidad_canal = Solicitud::mostrarSolicitudPorCanalMes($canales[$i]->idcanal,$mes,$anho);
				array_push($array_cantidades_canales,$cantidad_canal[0]);
			}
			return Response::json(array( 'success' => true,'tiene_canales' => true,'resumen'=>$array_cantidades_canales, 'canales'=>$canales,
				'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}
	
	public function mostrar_dashboard_mes_usuarios_canales()
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
			$canales = Canal::listarCanalesConSolicitudesMesAnhoUsuario($mes,$anho,$usuario)->get();
			

			if($canales == null || $canales->isEmpty())
			{
				return Response::json(array( 'success' => true,'tiene_canales'=>false),200);				
			}

			$cantidad_canales = count($canales);
			$array_cantidades_canales = array();
			for($i = 0;$i<$cantidad_canales;$i++)
			{
				$cantidad_canal = Solicitud::mostrarSolicitudPorCanalMesUsuario($canales[$i]->idcanal,$mes,$anho,$usuario);
				array_push($array_cantidades_canales,$cantidad_canal[0]);
			}
			return Response::json(array( 'success' => true,'tiene_canales' => true,'resumen'=>$array_cantidades_canales, 'canales'=>$canales),200);
			
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
			$anho = Input::get('anho');
			$mes = Input::get('mes');
			$herramientas_con_transacciones = Herramienta::buscarTransaccionesPorAnhoMes($anho,$mes)->get();

			if($herramientas_con_transacciones == null || $herramientas_con_transacciones->isEmpty())
				return Response::json(array( 'success' => true, 'tiene_herramientas'=>false),200);

			$array_cantidades_herramientas = array();

			$cantidad_herramientas = count($herramientas_con_transacciones);

			if($cantidad_herramientas > 10)
				$cantidad_herramientas = 10;

			for($i=0;$i<$cantidad_herramientas;$i++)
			{
				//por cada herramienta extraer sus tipos de estado de transaccion
				$transacciones_atendidas = Transaccion::mostrarTransaccionPorEstadoMesAplicativo(1,$mes,$anho,$herramientas_con_transacciones[$i]->idherramienta);
				$transacciones_rechazadas = Transaccion::mostrarTransaccionPorEstadoMesAplicativo(2,$mes,$anho,$herramientas_con_transacciones[$i]->idherramienta);
				$transacciones_pendientes = Transaccion::mostrarTransaccionPorEstadoMesAplicativo(3,$mes,$anho,$herramientas_con_transacciones[$i]->idherramienta);

				$obj_cantidad_herramientas = [
					"nombre_herramienta" => $herramientas_con_transacciones[$i]->nombre,
					"cantidad_atendidos" => $transacciones_atendidas[0]->cantidad,
					"cantidad_rechazados" => $transacciones_rechazadas[0]->cantidad,
					"cantidad_pendientes" => $transacciones_pendientes[0]->cantidad
				];

				array_push($array_cantidades_herramientas,$obj_cantidad_herramientas);
			}


			return Response::json(array( 'success' => true,'tiene_herramientas'=>true,"resumen"=>$array_cantidades_herramientas),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_mes_usuarios_aplicativos()
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
			$herramientas_con_transacciones = Herramienta::buscarTransaccionesPorAnhoMesPorUsuario($mes,$anho,$usuario)->get();

			if($herramientas_con_transacciones == null || $herramientas_con_transacciones->isEmpty())
				return Response::json(array( 'success' => true, 'tiene_herramientas'=>false),200);

			$array_cantidades_herramientas = array();

			$cantidad_herramientas = count($herramientas_con_transacciones);

			if($cantidad_herramientas > 10)
				$cantidad_herramientas = 10;

			for($i=0;$i<$cantidad_herramientas;$i++)
			{
				//por cada herramienta extraer sus tipos de estado de transaccion
				$transacciones_atendidas = Transaccion::mostrarTransaccionPorEstadoMesAplicativoUsuario(1,$mes,$anho,$herramientas_con_transacciones[$i]->idherramienta,$usuario);
				$transacciones_rechazadas = Transaccion::mostrarTransaccionPorEstadoMesAplicativoUsuario(2,$mes,$anho,$herramientas_con_transacciones[$i]->idherramienta,$usuario);
				$transacciones_pendientes = Transaccion::mostrarTransaccionPorEstadoMesAplicativoUsuario(3,$mes,$anho,$herramientas_con_transacciones[$i]->idherramienta,$usuario);

				$obj_cantidad_herramientas = [
					"nombre_herramienta" => $herramientas_con_transacciones[$i]->nombre,
					"cantidad_atendidos" => $transacciones_atendidas[0]->cantidad,
					"cantidad_rechazados" => $transacciones_rechazadas[0]->cantidad,
					"cantidad_pendientes" => $transacciones_pendientes[0]->cantidad
				];

				array_push($array_cantidades_herramientas,$obj_cantidad_herramientas);
			}


			return Response::json(array( 'success' => true,'tiene_herramientas'=>true,"resumen"=>$array_cantidades_herramientas),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}
	
	public function mostrar_dashboard_mes_gestion_seguridad()
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
			$transacciones_seg_si = Transaccion::mostrarTransaccionPorEstadoMesAplicativoGestionSeguridad(1,$mes,$anho);
			$transacciones_seg_no = Transaccion::mostrarTransaccionPorEstadoMesAplicativoGestionSeguridad(0,$mes,$anho);
			$transacciones_seg_validar = Transaccion::mostrarTransaccionPorEstadoMesAplicativoGestionSeguridad(2,$mes,$anho);
			

			return Response::json(array( 'success' => true,'flag_si'=>$transacciones_seg_si,'flag_no'=>$transacciones_seg_no,'flag_validar'=>$transacciones_seg_validar),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_mes_usuarios_gestion_seguridad()
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
			$transacciones_seg_si = Transaccion::mostrarTransaccionPorEstadoMesUsuarioAplicativoGestionSeguridad(1,$mes,$anho,$usuario);
			$transacciones_seg_no = Transaccion::mostrarTransaccionPorEstadoMesUsuarioAplicativoGestionSeguridad(0,$mes,$anho,$usuario);
			$transacciones_seg_validar = Transaccion::mostrarTransaccionPorEstadoMesUsuarioAplicativoGestionSeguridad(2,$mes,$anho,$usuario);
			


			return Response::json(array( 'success' => true,'flag_si'=>$transacciones_seg_si,'flag_no'=>$transacciones_seg_no,'flag_validar'=>$transacciones_seg_validar),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_mes_dias()
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
			$resumen = Solicitud::mostrarSolicitudMesDia($mes,$anho);
			$dia = Dia::listarDias()->get();


			return Response::json(array( 'success' => true,'resumen'=>$resumen,'dias'=>$dia),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function mostrar_dashboard_mes_dias_usuarios()
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
			$resumen = Solicitud::mostrarSolicitudMesDiaUsuario($mes,$anho,$usuario);
			$dia = Dia::listarDias()->get();

			return Response::json(array( 'success' => true,'resumen'=>$resumen,'dias'=>$dia),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}*/

}
