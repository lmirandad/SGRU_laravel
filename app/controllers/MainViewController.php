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



				$data["solicitudes_atendidos"] = count(Solicitud::buscarPorIdEstado(1,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_cerrados"] = count(Solicitud::buscarPorIdEstado(2,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_pendientes"] = count(Solicitud::buscarPorIdEstado(3,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_procesando"] = count(Solicitud::buscarPorIdEstado(4,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_rechazadas"] = count(Solicitud::buscarPorIdEstado(5,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_anuladas"] = count(Solicitud::buscarPorIdEstado(6,$mes_actual,$anho_actual)->get()); 

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

				$data["solicitudes_atendidos"] = count(Solicitud::buscarPorIdEstadoPorUsuario(1,$data["user"]->id,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_cerrados"] = count(Solicitud::buscarPorIdEstadoPorUsuario(2,$data["user"]->id,$mes_actual,$anho_actual)->get()); 
				$data["solicitudes_pendientes"] = count($data["solicitudes_pendiente_data"]);
				$data["solicitudes_procesando"] = count($data["solicitudes_procesando_data"]);  
				$data["solicitudes_rechazadas"] = count(Solicitud::buscarPorIdEstadoPorUsuario(5,$data["user"]->id,$mes_actual,$anho_actual)->get());
				$data["solicitudes_anuladas"] = count(Solicitud::buscarPorIdEstadoPorUsuario(6,$data["user"]->id,$mes_actual,$anho_actual)->get());


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
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInDays($fecha_actual);
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
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInDays($fecha_actual);
					array_push($data["diferencia_fechas_trabajo_procesando"],$diferencia_dias_fecha_trabajo);
				}

				return View::make('MenuPrincipal/menuPrincipalGestor',$data);

			}else
				return View::make('error/error',$data);

		}else{
			return View::make('error/error',$data);
		}
	}


	public function mostrar_solicitudes_estado($idestado=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1 || $data["user"]->idrol == 2) && $idestado){
				
				$data["search_usuario"] = null;
				$data["search_fecha"] = null;
				
				$data["idusuario"] = null;

				$mes_actual = date('m');
				$anho_actual = date('Y');

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
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);

					array_push($data["diferencia_fechas"],$diferencia_dias);

					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInDays($fecha_actual);
					array_push($data["diferencia_fechas_trabajo"],$diferencia_dias_fecha_trabajo);
				}

				
				$data["origen"] = 1; //1: sin usuario //2: con usuario

				
				
				return View::make('MenuPrincipal/menuPrincipal',$data);

			}else
				return View::make('error/error',$data);

		}else{
			return View::make('error/error',$data);
		}
		
	}

	public function mostrar_solicitudes_estado_usuario($idestado=null,$idusuario=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1 || $data["user"]->idrol == 2) && $idestado && $idusuario){
				
				$data["search_usuario"] = Input::get('search_usuario');
				
				$mes_actual = date('m');
				$anho_actual = date('Y');


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
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInDays($fecha_actual);
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
		
	}

	public function buscar_solicitudes_usuario()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) ){
				
				$data["search_usuario"] = Input::get('search_usuario');
				$data["search_fecha"] = Input::get('search_fecha');

				

				if(Input::get('search_usuario') == null && $data["search_fecha"] == null){
					return Redirect::to('/principal');
				}

				$mes_busqueda = date('m',strtotime($data["search_fecha"]));
				$anho_busqueda = date('Y',strtotime($data["search_fecha"]));

				$usuario = User::buscarPorNombre($data["search_usuario"])->get();

				if($usuario == null || $usuario->isEmpty()){
					return Redirect::to('/principal');
				}

				$idusuario = $usuario[0]->id;

				$data["solicitudes_atendidos"] = count(Solicitud::buscarPorIdEstadoPorUsuario(1, $idusuario,$mes_busqueda,$anho_busqueda)->get()); 
				$data["solicitudes_cerrados"] = count(Solicitud::buscarPorIdEstadoPorUsuario(2, $idusuario,$mes_busqueda,$anho_busqueda)->get()); 
				$data["solicitudes_pendientes"] = count(Solicitud::buscarPorIdEstadoPorUsuario(3, $idusuario,$mes_busqueda,$anho_busqueda)->get()); 
				$data["solicitudes_procesando"] = count(Solicitud::buscarPorIdEstadoPorUsuario(4, $idusuario,$mes_busqueda,$anho_busqueda)->get()); 
				$data["solicitudes_rechazadas"] = count(Solicitud::buscarPorIdEstadoPorUsuario(5, $idusuario,$mes_busqueda,$anho_busqueda)->get()); 
				$data["solicitudes_anuladas"] = count(Solicitud::buscarPorIdEstadoPorUsuario(6, $idusuario,$mes_busqueda,$anho_busqueda)->get()); 

				$data["solicitudes_data"] = array();

				$data["idusuario"] = $idusuario;

				$data["origen"] = 2; //1: sin usuario //2: con usuario
				
				return View::make('MenuPrincipal/menuPrincipal',$data);

			}else
				return View::make('error/error',$data);

		}else{
			return View::make('error/error',$data);
		}
		
	}



}
