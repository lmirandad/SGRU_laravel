<?php

class SlaController extends BaseController {

	public function crear_sla($idherramientaxsector=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idherramientaxsector)
			{	
				$data["tipos_solicitud"] = TipoSolicitud::listarTiposSolicitud()->get();
				$data["herramientaxsector"] = HerramientaXSector::find($idherramientaxsector);
				$data["sector"] = Sector::find($data["herramientaxsector"]->idsector);
				$data["herramienta"] = Herramienta::find($data["herramientaxsector"]->idherramienta);

				if($data["sector"]==null){
					return Redirect::to('sectores/listar_sectores');
				}

				return View::make('Mantenimientos/Sla/crearSla',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_sla()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				
				$arr_ids_acciones = Input::get('ids_acciones');
				$arr_slas_pendiente = Input::get('valor_sla_pendiente');
				$arr_slas_procesando = Input::get('valor_sla_procesando');
				$idherramientaxsector = Input::get('idherramientaxsector');


				

				$size_acciones = count($arr_ids_acciones);
				$flag_datos_completos = true;
				for($i=0;$i<$size_acciones;$i++){
					if(strcmp($arr_slas_pendiente[$i],'')==0 || !ctype_digit($arr_slas_pendiente[$i])){
						$flag_datos_completos = false;
						break;
					}
				}

				if($flag_datos_completos == true)
				{
					for($i=0;$i<$size_acciones;$i++){
						if(strcmp($arr_slas_procesando[$i],'')==0 || !ctype_digit($arr_slas_procesando[$i]) ){
							$flag_datos_completos = false;
							break;
						}
					}					
				}

				if($flag_datos_completos == false){
					Session::flash('error', 'Error en el registro. Datos incompletos/incorrectos.');
					return Redirect::to('slas/crear_sla/'.$idherramientaxsector);
				}

				// En caso que los datos están completos, se valida si los dias en pendiente son menores a los procesando:
				$flag_datos_consistentes = true;
				for($i=0;$i<$size_acciones;$i++){
					if((int)$arr_slas_pendiente[$i] >= (int)$arr_slas_procesando[$i])
					{
						$flag_datos_consistentes = false;
						break;
					}
				}

				echo '<pre>';
				var_dump($flag_datos_consistentes);
				echo '</pre>';

				if($flag_datos_consistentes==false){
					Session::flash('error', 'Error en el registro. Existen Sla\'s de solicitudes pendientes que son mayores a los de solicitudes en proceso .');
					return Redirect::to('slas/crear_sla/'.$idherramientaxsector);
				}


				//Se debe validar primero si se tiene un SLA vigente:
				$sla_vigente = Sla::buscarSlaVigentePorIdHerramientaXSector($idherramientaxsector)->get();
				
				if($sla_vigente != null && !$sla_vigente->isEmpty()){
					$sla_vigente = $sla_vigente->first();
					$sla_vigente->fecha_fin = date('Y-m-d H:i:s');
					$sla_vigente->save(); 
				}

				// En caso que los datos están completos, se procede a registar los SLA's:

				$sla = new Sla;
				$sla->fecha_inicio =  date('Y-m-d H:i:s');
				$sla->idherramientaxsector = $idherramientaxsector;
				$sla->save();

				// se crean los valores por accion
				$herramientaxsectorxtipo_solicitud = HerramientaXSectorXTipoSolicitud::buscarPorId($idherramientaxsector)->get();
				$size_hsts = count($herramientaxsectorxtipo_solicitud);

				for($i=0;$i<$size_hsts;$i++){
					$tipos_solicitudxsla = new TipoSolicitudXSla;
					$tipos_solicitudxsla->idherramientaxsectorxtipo_solicitud = $herramientaxsectorxtipo_solicitud[$i]->idherramientaxsectorxtipo_solicitud;
					$tipos_solicitudxsla->idsla = $sla->idsla;
					$tipos_solicitudxsla->sla_pendiente = $arr_slas_pendiente[$i];
					$tipos_solicitudxsla->sla_procesando = $arr_slas_procesando[$i];
					$tipos_solicitudxsla->save();
				}				

				Session::flash('message', 'Se registraron los SLA\'s con éxito');
				
				return Redirect::to('slas/mostrar_slas/'.$idherramientaxsector);
				
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_slas($idherramientaxsector=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idherramientaxsector)
			{	
				
				$data["slas_data"] = Sla::buscarSlasPorIdHerramientaXSector($idherramientaxsector)->get();
				$data["herramientaxsector"] = HerramientaXSector::find($idherramientaxsector);
				$data["sector"] = Sector::find($data["herramientaxsector"]->idsector);
				$data["herramienta"] = Herramienta::find($data["herramientaxsector"]->idherramienta);
				$data["tipos_solicitud"] = TipoSolicitud::listarTiposSolicitud()->get();
				

				return View::make('Mantenimientos/Sla/mostrarSlaSector',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}


	public function obtener_slas()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		// Check if the current user is the "System Admin"
		$idherramientaxsector = Input::get('idherramientaxsector');
		$slas = Sla::buscarSlasPorIdHerramientaXSector($idherramientaxsector)->get();

		if($slas==null || $slas->isEmpty())
			return Response::json(array( 'success' => true,'slas'=>null),200);	
		
		return Response::json(array( 'success' => true, 'slas' => count($slas)),200);			
		
	}

	public function mostrar_datos(){
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		// Check if the current user is the "System Admin"
		$idsla = Input::get('idsla');
		$tipo_solicitudxsla = TipoSolicitudXSla::listarAccionesPorSla($idsla)->get();
		if($tipo_solicitudxsla == null || $tipo_solicitudxsla->isEmpty())
			return Response::json(array( 'success' => false),200);	
		
		
		return Response::json(array( 'success' => true,'sla'=>$tipo_solicitudxsla),200);
	}


	public function validar_slas()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		// Check if the current user is the "System Admin"
		$idsla = Input::get('idsla');
		$solicitudes = Solicitud::buscarSolicitudesPendientesProcesandoPorIdSla($idsla)->get();
		if($solicitudes == null || $solicitudes->isEmpty())
			return Response::json(array( 'success' => true, 'cantidad_solicitudes' => 0),200);	
		
		
		return Response::json(array( 'success' => true, 'cantidad_solicitudes' => count($solicitudes)),200);
	}

	public function editar_sla($idsla=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idsla)
			{	
				
				$data["tipos_solicitudxsla"] = TipoSolicitudXSla::listarAccionesPorSla($idsla)->get();
				$data["sla"] = Sla::find($idsla);
				$data["herramientaxsector"] = HerramientaXSector::find($data["sla"]->idherramientaxsector);
				$data["sector"] = Sector::find($data["herramientaxsector"]->idsector);
				$data["herramienta"] = Herramienta::find($data["herramientaxsector"]->idherramienta);
				$data["tipos_solicitud"] = TipoSolicitud::listarTiposSolicitud()->get();
				

				return View::make('Mantenimientos/Sla/editarSlaSector',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_editar_sla()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$sla = Sla::find(Input::get('idsla'));
				$idherramientaxsector = Input::get('idherramientaxsector');
				
				$arr_ids_acciones = Input::get('ids_acciones');
				$arr_slas_pendiente = Input::get('valor_sla_pendiente');
				$arr_slas_procesando = Input::get('valor_sla_procesando');
				

				$size_acciones = count($arr_ids_acciones);
				$flag_datos_completos = true;
				for($i=0;$i<$size_acciones;$i++){
					if(strcmp($arr_slas_pendiente[$i],'')==0 || !ctype_digit($arr_slas_pendiente[$i])){
						$flag_datos_completos = false;
						break;
					}
				}

				if($flag_datos_completos == true)
				{
					for($i=0;$i<$size_acciones;$i++){
						if(strcmp($arr_slas_procesando[$i],'')==0 || !ctype_digit($arr_slas_procesando[$i]) ){
							$flag_datos_completos = false;
							break;
						}
					}					
				}
				if($flag_datos_completos == false){
					Session::flash('error', 'Error en el registro. Datos incompletos/incorrectos.');
					return Redirect::to('slas/editar_sla/'.$sla->idsla);
				}

				// En caso que los datos están completos, se valida si los dias en pendiente son menores a los procesando:
				$flag_datos_consistentes = true;
				for($i=0;$i<$size_acciones;$i++){
					if((int)$arr_slas_pendiente[$i] >= (int)$arr_slas_procesando[$i])
					{
						$flag_datos_consistentes = false;
						break;
					}
				}

				if($flag_datos_consistentes==false){
					Session::flash('error', 'Error en el registro. Existen Sla\'s de solicitudes pendientes que son mayores a los de solicitudes en proceso .');
					return Redirect::to('slas/editar_sla/'.$sla->idsla);
				}

				
				$sla->iduser_updated_by = $data["user"]->id;
				$sla->save();
				// se crean los valores por accion
				$herramientaxsectorxtipo_solicitud = HerramientaXSectorXTipoSolicitud::buscarPorId($idherramientaxsector)->get();
				$size_hsts = count($herramientaxsectorxtipo_solicitud);

				for($i=0;$i<$size_hsts;$i++){
					$tipos_solicitudxsla = TipoSolicitudXSla::buscarPorSlaPorHerramientaXSectorXTipoSolicitud($herramientaxsectorxtipo_solicitud[$i]->idherramientaxsectorxtipo_solicitud,$sla->idsla)->get();
					if($tipos_solicitudxsla == null || $tipos_solicitudxsla->isEmpty())
						return View::make('error/error',$data);

					$tipos_solicitudxsla[0]->sla_pendiente = $arr_slas_pendiente[$i];
					$tipos_solicitudxsla[0]->sla_procesando = $arr_slas_procesando[$i];
					$tipos_solicitudxsla[0]->save();
				}				

				Session::flash('message', 'Se actualizaron los SLA\'s con éxito');
				
				return Redirect::to('slas/mostrar_slas/'.$idherramientaxsector);
				
				


				
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

}
