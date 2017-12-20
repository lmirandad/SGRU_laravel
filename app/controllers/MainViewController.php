<?php

class MenuPrincipalController extends BaseController {

	public function home_admin()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				
				$data["search_usuario"] = null;
				$data["solicitudes_data"] = array();

				$data["idusuario"] = null;

				$data["search_fecha"] = null;

				$mes_actual = date('m');
				$anho_actual = date('Y');

				$data["solicitudes_atendidos"] = Solicitud::buscarPorIdEstado(1,$mes_actual,$anho_actual)->get(); 
				if($data["solicitudes_atendidos"] == null || $data["solicitudes_atendidos"]->isEmpty()){
					$data["solicitudes_atendidos"] = array();
				}else				{

					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_atendidos"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_atendidos"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas_atendidos"],$diferencia_dias);
				}

				$data["solicitudes_cerrados"] = Solicitud::buscarPorIdEstado(2,$mes_actual,$anho_actual)->get(); 
				if($data["solicitudes_cerrados"] == null || $data["solicitudes_cerrados"]->isEmpty())
					$data["solicitudes_cerrados"] = array();
				else
				{
					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_cerrados"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_cerrados"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas_cerrados"],$diferencia_dias);
				}

				$data["solicitudes_pendientes"] = Solicitud::buscarPorIdEstado(3,$mes_actual,$anho_actual)->get();
				$data["slas_data_pendiente"] = array();
				$data["diferencia_fechas_pendiente"] = array();
				$data["diferencia_fechas_trabajo_pendiente"] = array();

				if($data["solicitudes_pendientes"] == null || $data["solicitudes_pendientes"]->isEmpty()){
					$data["solicitudes_pendientes"] = array();					
				}
				else
				{
					$cantidad_solicitudes = count($data["solicitudes_pendientes"]);					

					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$sla = Sla::buscarSlaSolicitud($data["solicitudes_pendientes"][$i]->idsolicitud,$data["solicitudes_pendientes"][$i]->idtipo_solicitud)->get()[0];

						array_push($data["slas_data_pendiente"], $sla);

						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_pendientes"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_pendientes"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

						array_push($data["diferencia_fechas_pendiente"],$diferencia_dias);

						//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
						$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
						$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
						
						//Obtener los dias feriados entre la fecha de hoy y la asignacion
						$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
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

						array_push($data["diferencia_fechas_trabajo_pendiente"],$diferencia_dias_fecha_trabajo);
					}
				}
				

				$data["solicitudes_procesando"] = Solicitud::buscarPorIdEstado(4,$mes_actual,$anho_actual)->get();
				$data["slas_data_procesando"] = array();
				$data["diferencia_fechas_procesando"] = array();
				$data["diferencia_fechas_trabajo_procesando"] = array(); 
				
				if($data["solicitudes_procesando"] == null || $data["solicitudes_procesando"]->isEmpty()){
					$data["solicitudes_procesando"] = array();
				}
				else{
					$cantidad_solicitudes = count($data["solicitudes_procesando"]);
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$sla = Sla::buscarSlaSolicitud($data["solicitudes_procesando"][$i]->idsolicitud,$data["solicitudes_procesando"][$i]->idtipo_solicitud)->get()[0];
						array_push($data["slas_data_procesando"], $sla);

						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_procesando"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_procesando"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

						array_push($data["diferencia_fechas_procesando"],$diferencia_dias);

						//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
						$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
						$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
						
						//Obtener los dias feriados entre la fecha de hoy y la asignacion
						$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
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

						array_push($data["diferencia_fechas_trabajo_procesando"],$diferencia_dias_fecha_trabajo);
					}
				}

				$data["solicitudes_rechazadas"] = Solicitud::buscarPorIdEstado(5,$mes_actual,$anho_actual)->get(); 
				if($data["solicitudes_rechazadas"] == null || $data["solicitudes_rechazadas"]->isEmpty()){
					$data["solicitudes_rechazadas"] = array();
				}else
				{
					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_rechazadas"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_rechazadas"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas_rechazadas"],$diferencia_dias);
				}

				$data["solicitudes_anuladas"] = Solicitud::buscarPorIdEstado(6,$mes_actual,$anho_actual)->get(); 
				if($data["solicitudes_anuladas"] == null || $data["solicitudes_anuladas"]->isEmpty()){
					$data["solicitudes_anuladas"] = array();
				}else{
					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_anuladas"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_anuladas"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas_anuladas"],$diferencia_dias);
				}

				$data["origen"] = 1; //1: sin usuario //2: con usuario
				
				return View::make('MenuPrincipal/menuPrincipal',$data);

			}else
				return View::make('error/error',$data);

		}else{
			return View::make('error/error',$data);
		}
		
	}

	public function home_gestor(){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2)
			{

				$mes_actual = date('m');
				$anho_actual = date('Y');

				$data["solicitudes_pendiente_data"] = Solicitud::buscarPorIdEstadoPorUsuario(3,$data["user"]->id,$mes_actual,$anho_actual)->get();
				
				$data["solicitudes_procesando_data"] = Solicitud::buscarPorIdEstadoPorUsuario(4,$data["user"]->id,$mes_actual,$anho_actual)->get();
				
				$data["idusuario"] = $data["user"]->id;

				$data["solicitudes_pendientes"] = count($data["solicitudes_pendiente_data"]);
				$data["solicitudes_procesando"] = count($data["solicitudes_procesando_data"]);
				
				$data["slas_data_pendiente"] = array();
				$data["diferencia_fechas_pendiente"] = array();
				$data["diferencia_fechas_trabajo_pendiente"] = array();


				$cantidad_solicitudes = count($data["solicitudes_pendiente_data"]);
				for($i=0;$i<$cantidad_solicitudes;$i++)
				{
					$sla = Sla::buscarSlaSolicitud($data["solicitudes_pendiente_data"][$i]->idsolicitud,$data["solicitudes_pendiente_data"][$i]->idtipo_solicitud)->get()[0];
					array_push($data["slas_data_pendiente"], $sla);

					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_pendiente_data"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_pendiente_data"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas_pendiente"],$diferencia_dias);

					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
					
					//Obtener los dias feriados entre la fecha de hoy y la asignacion
					$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
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

					array_push($data["diferencia_fechas_trabajo_pendiente"],$diferencia_dias_fecha_trabajo);
				}

				$data["slas_data_procesando"] = array();
				$data["diferencia_fechas_procesando"] = array();
				$data["diferencia_fechas_trabajo_procesando"] = array();


				$cantidad_solicitudes = count($data["solicitudes_procesando_data"]);
				for($i=0;$i<$cantidad_solicitudes;$i++)
				{
					$sla = Sla::buscarSlaSolicitud($data["solicitudes_procesando_data"][$i]->idsolicitud,$data["solicitudes_procesando_data"][$i]->idtipo_solicitud)->get()[0];
					array_push($data["slas_data_procesando"], $sla);

					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_procesando_data"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_procesando_data"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas_procesando"],$diferencia_dias);

					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
					
					//Obtener los dias feriados entre la fecha de hoy y la asignacion
					$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
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

					array_push($data["diferencia_fechas_trabajo_procesando"],$diferencia_dias_fecha_trabajo);
				}

				$data["search_codigo_solicitud"] = null;

				return View::make('MenuPrincipal/menuPrincipalGestor',$data);

			}else
				return View::make('error/error',$data);

		}else{
			return View::make('error/error',$data);
		}
	}


	/*public function mostrar_solicitudes_estado($idestado=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1 || $data["user"]->idrol == 2) && $idestado){
				
				$data["search_usuario"] = null;
				$data["search_fecha"] = Input::get('search_fecha');
				
				$data["idusuario"] = null;

				if($data["search_fecha"] != null){

					$mes_actual = date('m',strtotime(DateTime::createFromFormat('d-m-Y','01-'.$data["search_fecha"])->format('d-m-Y')));
					$anho_actual = date('Y',strtotime(DateTime::createFromFormat('d-m-Y','01-'.$data["search_fecha"])->format('d-m-Y')));
				}else{
					$mes_actual = date('m');
					$anho_actual = date('Y');
				}

				$data["solicitudes_atendidos"] = count(Solicitud::buscarPorIdEstado(1,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_cerrados"] = count(Solicitud::buscarPorIdEstado(2,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_pendientes"] = count(Solicitud::buscarPorIdEstado(3,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_procesando"] = count(Solicitud::buscarPorIdEstado(4,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_rechazadas"] = count(Solicitud::buscarPorIdEstado(5,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_anuladas"] = count(Solicitud::buscarPorIdEstado(6,$mes_actual,$anho_actual)->get()); 

				$data["solicitudes_data"] = Solicitud::buscarPorIdEstado($idestado,$mes_actual,$anho_actual)->get();
				$data["slas_data"] = array();
				$data["diferencia_fechas"] = array();
				$data["diferencia_fechas_trabajo"] = array();

				$cantidad_solicitudes = count($data["solicitudes_data"]);

				for($i=0;$i<$cantidad_solicitudes;$i++)
				{
					$sla = Sla::buscarSlaSolicitud($data["solicitudes_data"][$i]->idsolicitud,$data["solicitudes_data"][$i]->idtipo_solicitud)->get()[0];
					array_push($data["slas_data"], $sla);

					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_data"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_data"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_asignacion_formateada->diffInDays($fecha_solicitud_formateada);

					array_push($data["diferencia_fechas"],$diferencia_dias);

					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
					
					//Obtener los dias feriados entre la fecha de hoy y la asignacion
					$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
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

					
					array_push($data["diferencia_fechas_trabajo"],$diferencia_dias_fecha_trabajo);

				}

				
				$data["origen"] = 1; //1: sin usuario //2: con usuario
				
				
				return View::make('MenuPrincipal/menuPrincipal',$data);

			}else
				return View::make('error/error',$data);

		}else{
			return View::make('error/error',$data);
		}
		
	}*/

	/*public function mostrar_solicitudes_estado_usuario($idestado=null,$idusuario=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1 || $data["user"]->idrol == 2) && $idestado && $idusuario){
				
				$data["search_usuario"] = Input::get('search_usuario');
				$data["search_fecha"] = Input::get('search_fecha');
				
				if($data["search_fecha"] != null){

					$mes_actual = date('m',strtotime(DateTime::createFromFormat('d-m-Y','01-'.$data["search_fecha"])->format('d-m-Y')));
					$anho_actual = date('Y',strtotime(DateTime::createFromFormat('d-m-Y','01-'.$data["search_fecha"])->format('d-m-Y')));
				}else{
					$mes_actual = date('m');
					$anho_actual = date('Y');
				}



				$data["solicitudes_atendidos"] = count(Solicitud::buscarPorIdEstadoPorUsuario(1, $idusuario,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_cerrados"] = count(Solicitud::buscarPorIdEstadoPorUsuario(2, $idusuario,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_pendientes"] = count(Solicitud::buscarPorIdEstadoPorUsuario(3, $idusuario,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_procesando"] = count(Solicitud::buscarPorIdEstadoPorUsuario(4, $idusuario,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_rechazadas"] = count(Solicitud::buscarPorIdEstadoPorUsuario(5, $idusuario,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_anuladas"] = count(Solicitud::buscarPorIdEstadoPorUsuario(6, $idusuario,$mes_actual,$anho_actual)->get()); 

				$data["solicitudes_data"] = Solicitud::buscarPorIdEstadoPorUsuario($idestado, $idusuario,$mes_actual,$anho_actual)->get();
				$data["slas_data"] = array();
				$data["diferencia_fechas"] = array();
				$data["diferencia_fechas_trabajo"] = array();
				$cantidad_solicitudes = count($data["solicitudes_data"]);

				for($i=0;$i<$cantidad_solicitudes;$i++)
				{
					$sla = Sla::buscarSlaSolicitud($data["solicitudes_data"][$i]->idsolicitud,$data["solicitudes_data"][$i]->idtipo_solicitud)->get()[0];
					array_push($data["slas_data"], $sla);

					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_data"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_data"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas"],$diferencia_dias);

					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
					
					//Obtener los dias feriados entre la fecha de hoy y la asignacion
					$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
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

					
					array_push($data["diferencia_fechas_trabajo"],$diferencia_dias_fecha_trabajo);

				}


				$data["idusuario"] = $idusuario;

				$data["origen"] = 2; //1: sin usuario //2: con usuario
				
				return View::make('MenuPrincipal/menuPrincipal',$data);

			}else
				return View::make('error/error',$data);

		}else{
			return View::make('error/error',$data);
		}
		
	}*/

	public function buscar_solicitudes_usuario()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) ){
				
				$data["search_usuario"] = Input::get('search_usuario');
				$data["search_fecha"] = Input::get('search_fecha');

				$mes_busqueda = null;
				$anho_busqueda = null;

				if(Input::get('search_usuario') == null && $data["search_fecha"] == null){
					return Redirect::to('/principal_admin');
				}

				if($data["search_fecha"] != null){

					$mes_busqueda = date('m',strtotime(DateTime::createFromFormat('d-m-Y','01-'.$data["search_fecha"])->format('d-m-Y')));
					$anho_busqueda = date('Y',strtotime(DateTime::createFromFormat('d-m-Y','01-'.$data["search_fecha"])->format('d-m-Y')));
				}else{
					$mes_busqueda = date('m');
					$anho_busqueda = date('Y');
				}

				

				if($data["search_usuario"] == null){
					$idusuario = null;
				}else{
					$usuario = User::buscarPorNombre($data["search_usuario"])->get();
					$idusuario = $usuario[0]->id;
				}

				
				$data["solicitudes_atendidos"] = Solicitud::buscarPorIdEstadoPorUsuario(1, $idusuario,$mes_busqueda,$anho_busqueda)->get(); 
				if($data["solicitudes_atendidos"] == null || $data["solicitudes_atendidos"]->isEmpty()){
					$data["solicitudes_atendidos"] = array();
				}else				{

					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_atendidos"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_atendidos"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas_atendidos"],$diferencia_dias);
				}

				$data["solicitudes_cerrados"] = Solicitud::buscarPorIdEstadoPorUsuario(2, $idusuario,$mes_busqueda,$anho_busqueda)->get();
				if($data["solicitudes_cerrados"] == null || $data["solicitudes_cerrados"]->isEmpty())
					$data["solicitudes_cerrados"] = array();
				else
				{
					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_cerrados"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_cerrados"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas_cerrados"],$diferencia_dias);
				}

				$data["solicitudes_pendientes"] = Solicitud::buscarPorIdEstadoPorUsuario(3, $idusuario,$mes_busqueda,$anho_busqueda)->get();
				$data["slas_data_pendiente"] = array();
				$data["diferencia_fechas_pendiente"] = array();
				$data["diferencia_fechas_trabajo_pendiente"] = array();

				if($data["solicitudes_pendientes"] == null || $data["solicitudes_pendientes"]->isEmpty()){
					$data["solicitudes_pendientes"] = array();					
				}
				else
				{
					$cantidad_solicitudes = count($data["solicitudes_pendientes"]);					

					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$sla = Sla::buscarSlaSolicitud($data["solicitudes_pendientes"][$i]->idsolicitud,$data["solicitudes_pendientes"][$i]->idtipo_solicitud)->get()[0];

						array_push($data["slas_data_pendiente"], $sla);

						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_pendientes"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_pendientes"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

						array_push($data["diferencia_fechas_pendiente"],$diferencia_dias);

						//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
						$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
						$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
						
						//Obtener los dias feriados entre la fecha de hoy y la asignacion
						$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
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

						array_push($data["diferencia_fechas_trabajo_pendiente"],$diferencia_dias_fecha_trabajo);
					}
				}
				

				$data["solicitudes_procesando"] = Solicitud::buscarPorIdEstadoPorUsuario(4, $idusuario,$mes_busqueda,$anho_busqueda)->get();
				$data["slas_data_procesando"] = array();
				$data["diferencia_fechas_procesando"] = array();
				$data["diferencia_fechas_trabajo_procesando"] = array(); 
				
				if($data["solicitudes_procesando"] == null || $data["solicitudes_procesando"]->isEmpty()){
					$data["solicitudes_procesando"] = array();
				}
				else{
					$cantidad_solicitudes = count($data["solicitudes_procesando"]);
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$sla = Sla::buscarSlaSolicitud($data["solicitudes_procesando"][$i]->idsolicitud,$data["solicitudes_procesando"][$i]->idtipo_solicitud)->get()[0];
						array_push($data["slas_data_procesando"], $sla);

						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_procesando"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_procesando"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

						array_push($data["diferencia_fechas_procesando"],$diferencia_dias);

						//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
						$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
						$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
						
						//Obtener los dias feriados entre la fecha de hoy y la asignacion
						$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
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

						array_push($data["diferencia_fechas_trabajo_procesando"],$diferencia_dias_fecha_trabajo);
					}
				}

				$data["solicitudes_rechazadas"] = Solicitud::buscarPorIdEstadoPorUsuario(5, $idusuario,$mes_busqueda,$anho_busqueda)->get();
				if($data["solicitudes_rechazadas"] == null || $data["solicitudes_rechazadas"]->isEmpty()){
					$data["solicitudes_rechazadas"] = array();
				}else
				{
					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_rechazadas"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_rechazadas"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas_rechazadas"],$diferencia_dias);
				}

				$data["solicitudes_anuladas"] = Solicitud::buscarPorIdEstadoPorUsuario(6, $idusuario,$mes_busqueda,$anho_busqueda)->get();
				if($data["solicitudes_anuladas"] == null || $data["solicitudes_anuladas"]->isEmpty()){
					$data["solicitudes_anuladas"] = array();
				}else{
					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_anuladas"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_anuladas"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas_anuladas"],$diferencia_dias);
				}

				$data["idusuario"] = $idusuario;

				$data["origen"] = 2; //1: sin usuario //2: con usuario
				
				return View::make('MenuPrincipal/menuPrincipal',$data);

			}else
				return View::make('error/error',$data);

		}else{
			return View::make('error/error',$data);
		}
		
	}

	public function buscar_solicitud_codigo()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2)
			{

				$data["search_codigo_solicitud"] = Input::get('search_codigo_solicitud');

				if($data["search_codigo_solicitud"]==null)
					return MenuPrincipalController::home_gestor();


				$solicitud = Solicitud::buscarPorCodigoSolicitud($data["search_codigo_solicitud"] )->get();
				


				if($solicitud == null || $solicitud->isEmpty())
				{
					Session::flash('error','Solicitud no encontrada');
					$data["idusuario"] = $data["user"]->id;
					$data["solicitudes_pendientes"] = 0;
					$data["solicitudes_procesando"] = 0; 
					$data["solicitudes_pendiente_data"] = array();  
					$data["solicitudes_procesando_data"] = array(); 				
					$data["slas_data_pendiente"] = array();
					$data["diferencia_fechas_pendiente"] = array();
					$data["diferencia_fechas_trabajo_pendiente"] = array();
					$data["slas_data_procesando"] = array();
					$data["diferencia_fechas_procesando"] = array();
					$data["diferencia_fechas_trabajo_procesando"] = array();
					return View::make('MenuPrincipal/menuPrincipalGestor',$data);
				}

				if($solicitud[0]->idestado_solicitud == 3)
				{
					$data["idusuario"] = $data["user"]->id;

					$data["solicitudes_pendientes"] = 1;
					$data["solicitudes_procesando"] = 0;  
					
					$data["solicitudes_pendiente_data"] = $solicitud;
					$data["solicitudes_procesando_data"] = array();

					$data["slas_data_pendiente"] = array();
					$data["diferencia_fechas_pendiente"] = array();
					$data["diferencia_fechas_trabajo_pendiente"] = array();

					$data["slas_data_procesando"] = array();
					$data["diferencia_fechas_procesando"] = array();
					$data["diferencia_fechas_trabajo_procesando"] = array();

					$sla = Sla::buscarSlaSolicitud($data["solicitudes_pendiente_data"][0]->idsolicitud,$data["solicitudes_pendiente_data"][0]->idtipo_solicitud)->get()[0];
					array_push($data["slas_data_pendiente"], $sla);

					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_pendiente_data"][0]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_pendiente_data"][0]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas_pendiente"],$diferencia_dias);

					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
					
					//Obtener los dias feriados entre la fecha de hoy y la asignacion
					$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
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

					array_push($data["diferencia_fechas_trabajo_pendiente"],$diferencia_dias_fecha_trabajo);

				}else if($solicitud[0]->idestado_solicitud == 4)
				{
					$data["idusuario"] = $data["user"]->id;

					$data["solicitudes_pendientes"] = 0;
					$data["solicitudes_procesando"] = 1;  
					
					$data["solicitudes_pendiente_data"] = array();
					$data["solicitudes_procesando_data"] = $solicitud; 

					$data["slas_data_pendiente"] = array();
					$data["diferencia_fechas_pendiente"] = array();
					$data["diferencia_fechas_trabajo_pendiente"] = array();

					$data["slas_data_procesando"] = array();
					$data["diferencia_fechas_procesando"] = array();
					$data["diferencia_fechas_trabajo_procesando"] = array();

					$sla = Sla::buscarSlaSolicitud($data["solicitudes_procesando_data"][0]->idsolicitud,$data["solicitudes_procesando_data"][0]->idtipo_solicitud)->get()[0];
					array_push($data["slas_data_procesando"], $sla);

					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_procesando_data"][0]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_procesando_data"][0]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas_procesando"],$diferencia_dias);

					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
					
					//Obtener los dias feriados entre la fecha de hoy y la asignacion
					$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
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

					array_push($data["diferencia_fechas_trabajo_procesando"],$diferencia_dias_fecha_trabajo);

				}else
				{
					Session::flash('error','Solicitud no se encuentra en pendiente ni procesando.');
					$data["idusuario"] = $data["user"]->id;
					$data["solicitudes_pendientes"] = 0;
					$data["solicitudes_procesando"] = 0; 
					$data["solicitudes_pendiente_data"] = array();  
					$data["solicitudes_procesando_data"] = array();   				
					$data["slas_data_pendiente"] = array();
					$data["diferencia_fechas_pendiente"] = array();
					$data["diferencia_fechas_trabajo_pendiente"] = array();
					$data["slas_data_procesando"] = array();
					$data["diferencia_fechas_procesando"] = array();
					$data["diferencia_fechas_trabajo_procesando"] = array();
					return View::make('MenuPrincipal/menuPrincipalGestor',$data);
				}
				
				return View::make('MenuPrincipal/menuPrincipalGestor',$data);

			}else
				return View::make('error/error',$data);

		}else{
			return View::make('error/error',$data);
		}
	}

	public function resumen_usuarios()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1)
			{

				$data["resumen_usuario"] = Solicitud::resumenSolicitudesPorUsuario();
				$data["resumen_sector"] = Solicitud::resumenSolicitudesPorSectorPorUsuario();
				$data["resumen_sector_total"] = Solicitud::resumenSolicitudesPorSector();

				$data["contador_sector_total"] = 0;
				$data["search_fecha"] = null;

				return View::make('MenuPrincipal/resumenSolicitudesUsuarios',$data);

			}else
				return View::make('error/error',$data);

		}else{
			return View::make('error/error',$data);
		}
	}

	public function resumen_usuarios_mes()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1)
			{

				$data["search_fecha"] = Input::get('search_fecha');
				
				if($data["search_fecha"] == null)
					return MenuPrincipalController::resumen_usuarios();


				$fecha = DateTime::createFromFormat('d-m-Y','01-'.$data["search_fecha"]);
				
				$mes = date('m',strtotime($fecha->format('d-m-Y')));
				$anho = date('Y',strtotime($fecha->format('d-m-Y')));

				$data["resumen_usuario"] = Solicitud::buscarResumenSolicitudesPorUsuarioPorFecha($mes,$anho);
				$data["resumen_sector"] = Solicitud::buscarResumenSolicitudesPorSectorPorUsuarioPorFecha($mes,$anho);
				$data["resumen_sector_total"] = Solicitud::buscarResumenSolicitudesPorSectorPorFecha($mes,$anho);

				$data["contador_sector_total"] = 0;

				return View::make('MenuPrincipal/resumenSolicitudesUsuarios',$data);

			}else
				return View::make('error/error',$data);

		}else{
			return View::make('error/error',$data);
		}
	}


}
