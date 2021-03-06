<?php

class EntidadController extends BaseController {

	public function crear_entidad()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$data["sectores"] = Sector::lists('nombre','idsector');
				return View::make('Mantenimientos/Sectores_Canales_Entidades/Entidades/crearEntidad',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_entidad()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$attributes = array(
					'nombre_entidad' => 'Nombre de la Entidad',
					'codigo_enve' => 'Codigo asociado a la Entidad',
					'descripcion' => 'Descripcion',
					'sector' => 'Sector',
					'canal' => 'Canal',
				);

				$messages = array();

				$rules = array(
					'nombre_entidad' => 'required|max:100|alpha_num_spaces_slash_dash|unique:entidad,nombre',
					'codigo_enve' => 'numeric|unique:entidad,codigo_enve',
					'descripcion' => 'alpha_num_spaces_slash_dash_enter|max:200',
					'sector' => 'required',
					'canal' => 'required',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('entidades/crear_entidad')->withErrors($validator)->withInput(Input::all());
				}else{
					$nombre_entidad = Input::get('nombre_entidad');
					$descripcion = Input::get('descripcion');
					$codigo_enve = Input::get('codigo_enve');
					$idcanal = Input::get('canal');
					
					$entidad = new Entidad;
					$entidad->nombre = $nombre_entidad;
					$entidad->codigo_enve = $codigo_enve;
					$entidad->descripcion = $descripcion;
					$entidad->idcanal = $idcanal;
					$entidad->iduser_created_by = $data["user"]->id;

					$entidad->save();						

					Session::flash('message', 'Se registró correctamente la entidad.');
					
					return Redirect::to('entidades_canales_sectores/listar/3');
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function editar_entidad($identidad=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $identidad)
			{	
				$data["entidad"] = Entidad::withTrashed()->find($identidad);
				$data["sectores"] = Sector::lists('nombre','idsector');
				$data["canal_entidad"] = Canal::withTrashed()->find($data["entidad"]->idcanal); 
				$data["canales"] = Canal::buscarCanalesPorIdSector($data["canal_entidad"]->idsector)->lists('nombre','idcanal');
				
				if($data["entidad"]==null){
					return Redirect::to('entidades_canales_sectores/listar/3');
				}

				return View::make('Mantenimientos/Sectores_Canales_Entidades/Entidades/editarEntidad',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_editar_entidad()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$user = User::find(Input::get('usuario_id'));
				$entidad_id = Input::get('entidad_id');
				$attributes = array(
					'nombre_entidad' => 'Nombre de la Entidad',
					'codigo_enve' => 'Codigo asociado a la Entidad',
					'descripcion' => 'Descripcion',
					'sector' => 'Sector',
					'canal' => 'Canal',
				);

				$messages = array();

				$rules = array(
					'nombre_entidad' => 'required|max:100|alpha_num_spaces_slash_dash|unique:entidad,nombre,'.$entidad_id.',identidad',
					'descripcion' => 'alpha_num_spaces_slash_dash_enter|max:200',
					'codigo_enve' => 'numeric|unique:entidad,codigo_enve,'.$entidad_id.',identidad',
					'sector' => 'required',
					'canal' => 'required',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					$entidad_id = Input::get('entidad_id');
					$url = "entidades/editar_entidad"."/".$entidad_id;
					return Redirect::to($url)->withErrors($validator)->withInput(Input::all());
				}else{	
					$entidad_id = Input::get('entidad_id');
					
					/*echo '<pre>';
					var_dump($entidad_id);
					echo '</pre>';*/

					$nombre_entidad = Input::get('nombre_entidad');
					$codigo_enve = Input::get('codigo_enve');
					$descripcion = Input::get('descripcion');
					$idcanal = Input::get('canal');
					
					$entidad = Entidad::find($entidad_id);

					if($entidad->idcanal != $idcanal)
					{
						$solicitudes = Solicitud::buscarSolicitudesPorEntidad($entidad->identidad)->get();
						if($solicitudes!=null && !$solicitudes->isEmpty() && count($solicitudes) > 0 )
						{
							return Redirect::to('entidades/editar_entidad'.'/'.$entidad_id)->with('error', 'No se puede cambiar la información de la entidad. La entidad ya cuenta con solicitudes asociadas');
						}
					}
					
					$entidad->nombre = $nombre_entidad;
					$entidad->codigo_enve = $codigo_enve;
					$entidad->descripcion = $descripcion;
					$entidad->idcanal = $idcanal;
					$entidad->iduser_updated_by = $data["user"]->id;

					$entidad->save();					
					
					return Redirect::to('entidades_canales_sectores/listar/3')->with('message', 'Se editó correctamente la entidad: '.$entidad->nombre);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	
	public function buscar_entidades()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1){
				$data["codigo_entidad_search"] = Input::get('codigo_entidad_search');
				$data["entidad_search"] = Input::get('entidad_search');
				$data["entidad_search_canal"] = Input::get('entidad_search_canal');
				$data["entidad_search_sector"] = Input::get('entidad_search_sector');
				$data["sectores"] = Sector::lists('nombre','idsector');
				$data["flag_seleccion"] = 3;
				if($data["codigo_entidad_search"] == null && $data["entidad_search"] == null && strcmp($data["entidad_search_canal"],"") == 0  && strcmp($data["entidad_search_sector"],"") == 0 ){
					$data["entidades_data"] = Entidad::withTrashed()->listarEntidades()->paginate(10);					
				}else{
					$data["entidades_data"] = Entidad::withTrashed()->buscarEntidades($data["codigo_entidad_search"],$data["entidad_search"],$data["entidad_search_canal"],$data["entidad_search_sector"])->paginate(10);					

				}
				return View::make('Mantenimientos/Sectores_Canales_Entidades/listarSectoresCanalesEntidades',$data);
				
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_entidad($identidad=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $identidad)
			{	
				$data["entidad"] = Entidad::withTrashed()->find($identidad);
				$data["sectores"] = Sector::lists('nombre','idsector');
				$data["canal_entidad"] = Canal::withTrashed()->find($data["entidad"]->idcanal); 
				$data["canales"] = Canal::buscarCanalesPorIdSector($data["canal_entidad"]->idsector)->lists('nombre','idcanal');
				

				if($data["entidad"]==null){						
					return Redirect::to('entidades_canales_sectores/listar/3')->with('error','Entidad no encontrado');
				}
				return View::make('Mantenimientos/Sectores_Canales_Entidades/Entidades/mostrarEntidad',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_canales(){
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		// Check if the current user is the "System Admin"
		$sector_id = Input::get('sector_id');
		$canales = Canal::buscarCanalesPorIdSector($sector_id)->get();
		
		
		if($canales==null || $canales->isEmpty())
			return Response::json(array( 'success' => true,'canales'=>null),200);		

		return Response::json(array( 'success' => true,'canales' => $canales),200);			
		
	}

	public function mostrar_entidades(){
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		// Check if the current user is the "System Admin"
		$canal_id = Input::get('canal_id');
		$entidades = Entidad::buscarEntidadesPorIdCanal($canal_id)->get();
		
		
		if($entidades==null || $entidades->isEmpty())
			return Response::json(array( 'success' => true,'entidades'=>null),200);		

		return Response::json(array( 'success' => true,'entidades' => $entidades),200);			
		
	}

	public function submit_inhabilitar_entidad()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$entidad_id = Input::get('entidad_id');
				$url = "entidades/mostrar_entidad"."/".$entidad_id;
				$entidad = Entidad::find($entidad_id);
				//Validar si la entidad posee solicitudes pendientes o en proceso
				$solicitudes = Solicitud::buscarSolicitudesPendientesProcesando($entidad->identidad)->get();

				if($solicitudes == null || $solicitudes->isEmpty()){
					//Esta vacio, se puede eliminar la entidad
					$puntos_venta = PuntoVenta::buscarPuntosVentaPorEntidad($entidad->identidad)->get();
						
					if($puntos_venta == null || $puntos_venta->isEmpty())
					{
						$entidad->delete();
					}else{
						if(count($puntos_venta) > 0){
							Session::flash('error', 'No se puede inhabilitar la entidad. La entidad cuenta con puntos de venta activo.');
							return Redirect::to($url);
						}else{
							$entidad->delete();
						}	
					}
				}else
				{
					//Por seguridad, se vuelve a revalidar la cantidad de solicitudes pendientes o procesando
					$size_solicitudes = count($solicitudes);
					if($size_solicitudes>0){
						Session::flash('error', 'No se puede inhabilitar la entidad. La entidad cuenta con solicitudes pendientes y en proceso.');
						return Redirect::to($url);
					}
				}
				Session::flash('message', 'Se inhabilitó correctamente la entidad.');
				return Redirect::to($url);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_habilitar_entidad()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$entidad_id = Input::get('entidad_id');
				$url = "entidades/mostrar_entidad"."/".$entidad_id;
				$entidad = Entidad::withTrashed()->find($entidad_id);
				$entidad->restore();
				Session::flash('message', 'Se habilitó correctamente la entidad.');
				return Redirect::to($url);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

}
