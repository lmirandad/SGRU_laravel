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


				$ddate = "2018-05-20";
				$datem = "2018-05-01";
				$date = new DateTime($ddate);
				$week = $date->format("W");

				$date = new DateTime($datem);
				$weekm = $date->format("W");

				echo 'Weeknummer: '.($week - $weekm+1);
				$fecha_actual = new Datetime();
				$fecha_actual->modify('2018W02');

				//echo '<pre>';var_dump(getWee); echo '</pre>';

				
				//return View::make('Reporteria/dashboard',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	/* ******************** DASHBOARD ANUAL ****************************************************/

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


			return Response::json(array( 'success' => true,'tiene_herramientas'=>false,"resumen"=>$array_cantidades_herramientas),200);
			
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


			return Response::json(array( 'success' => true,'tiene_herramientas'=>false,"resumen"=>$array_cantidades_herramientas),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	/*
	public function mostrar_dashboard_semaforo_anual()
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
			$usuarios = User::buscarUsuariosConSolicitudSemaforo($anho)->get();

			if($usuarios == null || $usuarios->isEmpty())
				return Response::json(array( 'success' => true,'tiene_solicitudes'=>false),200);

			$cantidad_usuarios = count($usuarios);

			for($i=0;$i<$cantidad_usuarios;$i++)
			{
				//POR CADA USUARIO EXTRAER TODOS SUS SOLICITUDES E INICIAR EL CONTEO
				$obj_usuario_solicitud = [
					"nombre_usuario" => $usuarios[$i]->nombre.' '.$usuarios[$i]->apellido_paterno.' '.$usuarios[$i]->apellido_materno,
					"cantidad_semaforo_rojo_pendiente" => 0,
					"cantidad_semaforo_amarillo_pendiente" => 0,
					"cantidad_semaforo_verde_pendiente" => 0,
					"cantidad_semaforo_rojo_procesando" => 0,
					"cantidad_semaforo_amarillo_procesando" => 0,
					"cantidad_semaforo_verde_procesando" => 0
				];

				$solicitudes = Solicitud::buscarSolicitudSemaforo($anho,$usuarios[$i]->id)->get();

				if($solicitudes == null || $solicitudes->isEmpty())
					return Response::json(array( 'success' => true,'tiene_solicitudes'=>false),200);

				$cantidad_solicitudes = count($solicitudes);

				for( $j=0; $j < $cantidad_solicitudes; $j++ )
				{
					$solicitud = $solicitudes[$j];
					$fecha_fin = null; $fecha_inicio = null;
					//ESTADO PENDIENTE
					if($solicitud->idestado_solicitud == 3){

						$fecha_inicio = $solicitud->fecha_asignacion;
						//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
						$fecha_fin = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
						$diferencia_dias_fecha_trabajo= $fecha_inicio->diffInWeekdays($fecha_fin);
						
						//Obtener los dias feriados entre la fecha de hoy y la asignacion
						$feriados = Feriado::buscarDiasFeriados($fecha_inicio,$fecha_fin)->get();
						$cantidad_dias = 0;
						if($feriados != null )
						{
							$tamano = count($feriados);											
							for($j=0;$j<$tamano;$j++)
							{
								$dia = date('N',strtotime($feriados[$j]->valor_fecha));
								//Validar si el feriado coincide con un fin de semana para no contar dos veces
								if($dia == 6 || $dia == 7)
									$cantidad_dias++;							
							}
						}
						$diferencia_dias_fecha_trabajo -= $cantidad_dias;

						if($diferencia_dias_fecha_trabajo < $solicitud->sla_pendiente)
							$obj_usuario_solicitud["cantidad_semaforo_verde_pendiente"]++;
						else if($diferencia_dias_fecha_trabajo == $solicitud->sla_pendiente)
							$obj_usuario_solicitud["cantidad_semaforo_amarillo_pendiente"]++;
						else
							$obj_usuario_solicitud["cantidad_semaforo_rojo_pendiente"]++;

					}else if( $solicitud->idestado_solicitud == 4)
					{
						$fecha_fin = $solicitud->fecha_inicio_procesando;
						$fecha_inicio = $solicitud->fecha_asignacion;


					}else{

					}

				}

			}

			
			return Response::json(array( 'success' => true,'atendidos'=>$solicitudes_atendidos,'cerrados'=>$solicitudes_cerrados,'pendientes'=>$solicitudes_pendientes,'procesando'=>$solicitudes_procesando,'rechazados'=>$solicitudes_rechazados,'anulados'=>$solicitudes_anulados,
				'meses'=>$mes),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}
	*/
	
	/*****************************************DASHBOARDO MENSUAL ************************************************/

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

	

}
