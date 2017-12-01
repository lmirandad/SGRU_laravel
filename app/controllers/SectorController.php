<?php

class SectorController extends BaseController {

	public function submit_eliminar_sector_usuario(){
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$usuario_id = Input::get('usuario_id');
			$idusersxsector = Input::get('idusersxsector');
			
			//buscamos al idherramientaxuser
			$hu = UsersXSector::find($idusersxsector);
			if($hu==null)
				return Response::json(array( 'success' => false),200);

			$sector=Sector::find($hu->idsector);

			if($sector==null)
				return Response::json(array( 'success' => false),200);

			$hu->forceDelete();
			

			return Response::json(array( 'success' => true,'idusersxsector' => $idusersxsector,'nombre_sector'=>$sector->nombre),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function crear_sector()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				return View::make('Mantenimientos/Sectores_Canales_Entidades/Sectores/crearSector',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_sector()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$attributes = array(
					'nombre_sector' => 'Nombre del Sector',
					'descripcion' => 'Descripcion',
				);

				$messages = array();

				$rules = array(
					'nombre_sector' => 'required|max:100|alpha_num_spaces_slash_dash|unique:sector,nombre',
					'descripcion' => 'alpha_num_spaces_slash_dash_enter|max:200',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('sectores/crear_sector')->withErrors($validator)->withInput(Input::all());
				}else{
					$nombre_sector = Input::get('nombre_sector');
					$descripcion = Input::get('descripcion');
					
					$sector = new Sector;
					$sector->nombre = $nombre_sector;
					if(strcmp($descripcion,"") != 0)
						$sector->descripcion = $descripcion;
					$sector->iduser_created_by = $data["user"]->id;

					$sector->save();						

					Session::flash('message', 'Se registró correctamente el sector.');
					
					return Redirect::to('sectores/crear_sector');
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function editar_sector($idsector=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idsector)
			{	
				$data["sector"] = Sector::withTrashed()->find($idsector);

				if($data["sector"]==null){
					return Redirect::to('sectores/listar_sectores');
				}

				return View::make('Mantenimientos/Sectores_Canales_Entidades/Sectores/editarSector',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_editar_sector()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$user = User::find(Input::get('usuario_id'));
				$sector_id = Input::get('sector_id');
				$attributes = array(
					'nombre_sector' => 'Nombre del Sector',
					'descripcion' => 'Descripcion',
				);

				$messages = array();

				$rules = array(
					'nombre_sector' => 'required|max:100|alpha_num_spaces_slash_dash|unique:sector,nombre,'.$sector_id.',idsector',
					'descripcion' => 'alpha_num_spaces_slash_dash_enter|max:200',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					$sector_id = Input::get('sector_id');
					$url = "sectores/editar_sector"."/".$sector_id;
					return Redirect::to($url)->withErrors($validator)->withInput(Input::all());
				}else{	
					$sector_id = Input::get('sector_id');
					$url = "sectores/editar_sector"."/".$sector_id;		
					
					$nombre_sector = Input::get('nombre_sector');
					$descripcion = Input::get('descripcion');
					
					$sector = Sector::find($sector_id);
					$sector->nombre = $nombre_sector;
					$sector->descripcion = $descripcion;
					$sector->iduser_updated_by = $data["user"]->id;

					$sector->save();					
					
					return Redirect::to('entidades_canales_sectores/listar/1')->with('message', 'Se editó correctamente el sector: '.$sector->nombre);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	
	public function buscar_sectores()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2){
				$data["sector_search"] = Input::get('sector_search');
				$data["flag_seleccion"] = 1;
				if($data["sector_search"] == null){
					$data["sectores_data"] = Sector::listarSectores()->paginate(10);					
					return View::make('Mantenimientos/Sectores_Canales_Entidades/listarSectoresCanalesEntidades',$data);

				}else{
					$data["sectores_data"] = Sector::buscarSectores($data["sector_search"])->paginate(10);
					return View::make('Mantenimientos/Sectores_Canales_Entidades/listarSectoresCanalesEntidades',$data);
				}
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_sector($idsector=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idsector)
			{	
				$data["sector"] = Sector::find($idsector);

				if($data["sector"]==null){						
					return Redirect::to('entidades_canales_sectores/listar/1')->with('error','Sector no encontrado');
				}
				$data["canales"] = Canal::buscarCanalesPorIdSector($data["sector"]->idsector)->get();
				return View::make('Mantenimientos/Sectores_Canales_Entidades/Sectores/mostrarSector',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

}
