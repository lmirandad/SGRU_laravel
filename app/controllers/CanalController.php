<?php

class CanalController extends BaseController {

	public function crear_canal()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$data["sectores"] = Sector::lists('nombre','idsector');
				$data["canales_agrupados"] = null;
				return View::make('Mantenimientos/Sectores_Canales_Entidades/Canales/crearCanal',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_canal()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$attributes = array(
					'nombre_canal' => 'Nombre del Canal',
					'descripcion' => 'Descripcion',
					'sector' => 'Sector',
					'canal_agrupado' => 'Canal Agrupado'
				);

				$messages = array();

				$rules = array(
					'nombre_canal' => 'required|max:100|alpha_num_spaces_slash_dash|unique:canal,nombre',
					'descripcion' => 'alpha_num_spaces_slash_dash_enter|max:200',
					'sector' => 'required',
					'canal_agrupado' => 'required'
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('canales/crear_canal')->withErrors($validator)->withInput(Input::all());
				}else{
					$nombre_canal = Input::get('nombre_canal');
					$descripcion = Input::get('descripcion');
					$idsector = Input::get('sector');
					$idcanal_agrupado = Input::get('canal_agrupado'); 
					
					$canal = new Canal;
					$canal->nombre = $nombre_canal;
					$canal->descripcion = $descripcion;
					$canal->idsector = $idsector;
					$canal->idcanal_agrupado = $idcanal_agrupado;
					$canal->iduser_created_by = $data["user"]->id;

					$canal->save();						

					Session::flash('message', 'Se registró correctamente el canal.');
					
					return Redirect::to('entidades_canales_sectores/listar/2');
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function editar_canal($idcanal=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idcanal)
			{	
				$data["canal"] = Canal::withTrashed()->find($idcanal);
				$data["canales_agrupados"] = CanalAgrupado::buscarCanalAgrupadoPorIdSector($data["canal"]->idsector)->lists('nombre','idcanal_agrupado');
				$data["sectores"] = Sector::lists('nombre','idsector');

				if($data["canal"]==null){
					return Redirect::to('entidades_canales_sectores/listar/2');
				}

				return View::make('Mantenimientos/Sectores_Canales_Entidades/Canales/editarCanal',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_editar_canal()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$user = User::find(Input::get('usuario_id'));
				$canal_id = Input::get('canal_id');
				$attributes = array(
					'nombre_canal' => 'Nombre del Canal',
					'descripcion' => 'Descripcion',
					'sector' => 'Sector',
					'canal_agrupado' => 'Canal Agrupado'
				);

				$messages = array();

				$rules = array(
					'nombre_canal' => 'required|max:100|alpha_num_spaces_slash_dash|unique:canal,nombre,'.$canal_id.',idcanal',
					'descripcion' => 'alpha_num_spaces_slash_dash_enter|max:200',
					'sector' => 'required',
					'canal_agrupado' => 'required'
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					$canal_id = Input::get('canal_id');
					$url = "canales/editar_canal"."/".$canal_id;
					return Redirect::to($url)->withErrors($validator)->withInput(Input::all());
				}else{	
					$canal_id = Input::get('canal_id');
					

					$nombre_canal = Input::get('nombre_canal');
					$descripcion = Input::get('descripcion');
					$idsector = Input::get('sector');
					$idcanal_agrupado = Input::get('canal_agrupado');
					$canal = Canal::find($canal_id);

					if($idsector != $canal->idsector)
					{
						//validar si este canal ya tenia solicitudes creadas
						$solicitudes = Solicitud::buscarSolicitudesPorCanal($canal->idcanal)->get();
						if($solicitudes != null && !$solicitudes->isEmpty() && count($solicitudes) > 0 )
						{
							return Redirect::to('canales/editar_canal'.'/'.$canal_id)->with('error', 'No se puede cambiar la información del canal . El canal ya cuenta con solicitudes asociadas');
						} 

					}
					
					$canal->nombre = $nombre_canal;
					$canal->descripcion = $descripcion;
					$canal->idsector = $idsector;
					$canal->idcanal_agrupado = $idcanal_agrupado;
					$canal->iduser_updated_by = $data["user"]->id;

					$canal->save();					
					
					return Redirect::to('entidades_canales_sectores/listar/2')->with('message', 'Se editó correctamente el canal: '.$canal->nombre);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	
	public function buscar_canales()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1){
				$data["canal_search"] = Input::get('canal_search');
				$data["canal_search_sector"] = Input::get('canal_search_sector');
				$data["sectores"] = Sector::lists('nombre','idsector');
				$data["flag_seleccion"] = 2;
				if($data["canal_search"] == null && strcmp($data["canal_search_sector"],"") ==0 ){
					$data["canales_data"] = Canal::listarCanales()->paginate(10);					
				}else{
					$data["canales_data"] = Canal::buscarCanales($data["canal_search"],$data["canal_search_sector"])->paginate(10);		
				}
				return View::make('Mantenimientos/Sectores_Canales_Entidades/listarSectoresCanalesEntidades',$data);
				
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_canal($idcanal=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idcanal)
			{	
				$data["canal"] = Canal::withTrashed()->find($idcanal);
				$data["canales_agrupados"] = CanalAgrupado::lists('nombre','idcanal_agrupado');
				$data["sectores"] = Sector::lists('nombre','idsector');


				if($data["canal"]==null){						
					return Redirect::to('entidades_canales_sectores/listar/2')->with('error','Canal no encontrado');
				}
				$data["entidades"] = Entidad::buscarEntidadesPorIdCanal($data["canal"]->idcanal)->get();
				return View::make('Mantenimientos/Sectores_Canales_Entidades/Canales/mostrarCanal',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}
	public function submit_inhabilitar_canal()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$canal_id = Input::get('canal_id');
				$url = "canales/mostrar_canal"."/".$canal_id;
				$canal = Canal::find($canal_id);
				
				//Validar si el canal posee entidades activas
				$entidades = Entidad::buscarEntidadesPorIdCanal($canal->idcanal)->get();

				if($entidades == null || $entidades->isEmpty()){
					//Esta vacio, se puede eliminar el canal
					$cargos_canal = CargoCanal::buscarCargosPorCanal($canal->idcanal)->get();
						
					if($cargos_canal == null || $cargos_canal->isEmpty())
					{
						$canal->delete();
					}else{
						if(count($cargos_canal) > 0){
							Session::flash('error', 'No se puede inhabilitar el canal. El canal cuenta con tipos de cargos activo.');
							return Redirect::to($url);
						}else{
							$canal->delete();
						}	
					}

				}else
				{
					//Por seguridad, se vuelve a revalidar si el sector posee canales.
					$size_canales = count($entidades);
					if($size_canales>0){
						Session::flash('error', 'No se puede inhabilitar el canal. El canal posee entidades activas.');
						return Redirect::to($url);
					}
					else
						$canal->delete();						
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

	public function submit_habilitar_canal()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$canal_id = Input::get('canal_id');
				$url = "canales/mostrar_canal"."/".$canal_id;
				$canal = Canal::withTrashed()->find($canal_id);
				$canal->restore();
				Session::flash('message', 'Se habilitó correctamente el canal.');
				return Redirect::to($url);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

}
