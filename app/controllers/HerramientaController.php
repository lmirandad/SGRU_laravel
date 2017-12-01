<?php

class HerramientaController extends BaseController {

	public function submit_eliminar_herramienta_usuario(){
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$usuario_id = Input::get('usuario_id');
			$idherramientaxusers = Input::get('idherramientaxusers');
			
			//buscamos al idherramientaxuser
			$herramientaUsuario = HerramientaXUser::find($idherramientaxusers);
			if($herramientaUsuario==null)
				return Response::json(array( 'success' => false),200);

			$herramienta =Herramienta::find($herramientaUsuario->idherramienta);

			if($herramienta==null)
				return Response::json(array( 'success' => false),200);

			
			$accionesHerramientaUsuario = HerramientaXTipoSolicitudXUser::listarTipoSolicitudUsuario($usuario_id,$herramienta->idherramienta)->get();
			
			if($accionesHerramientaUsuario == null || $accionesHerramientaUsuario->isEmpty()){
				return Response::json(array( 'success' => false),200);				
			}

			$size_acciones = count($accionesHerramientaUsuario);
			for($i=0;$i<$size_acciones;$i++){
				$accionesHerramientaUsuario[$i]->delete();
			}

			$herramientaUsuario->delete();
			

			return Response::json(array( 'success' => true,'idherramientaxusers' => $idherramientaxusers,'nombre_herramienta'=>$herramienta->nombre),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function listar_herramientas()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2 ){
				$data["search"] = null;
				$data["search_denominacion_herramienta"] = null;
				$data["denominacion_herramienta"] = DenominacionHerramienta::lists('nombre','iddenominacion_herramienta');
				$data["herramientas_data"] = Herramienta::listarHerramientas()->paginate(10);
				return View::make('Mantenimientos/Herramientas/listarHerramientas',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function buscar_herramientas()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2){
				$data["search"] = Input::get('search');
				$data["search_denominacion_herramienta"] = Input::get('search_denominacion_herramienta');;
				$data["denominacion_herramienta"] = DenominacionHerramienta::lists('nombre','iddenominacion_herramienta');
				if($data["search"] == null && $data["search_denominacion_herramienta"]== 0){
					$data["herramientas_data"] = Herramienta::listarHerramientas()->paginate(10);
					return View::make('Mantenimientos/Herramientas/listarHerramientas',$data);

				}else{
					$data["herramientas_data"] = Herramienta::buscarHerramientas($data["search"],$data["search_denominacion_herramienta"])->paginate(10);
					return View::make('Mantenimientos/Herramientas/listarHerramientas',$data);	
				}				
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function crear_herramienta()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){

				$data["denominaciones"] = DenominacionHerramienta::lists('nombre','iddenominacion_herramienta');
				
				
				return View::make('Mantenimientos/Herramientas/crearHerramienta',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_herramienta()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$attributes = array(
					'nombre_herramienta' => 'Nombre de Aplicativo',
					'descripcion' => 'Descripcion',
					'flag_seguridad' => 'Valida Seguridad',
					'denominacion_herramienta' => 'Categoría Aplicativo',
				);

				$messages = array();

				$rules = array(
							'nombre_herramienta' => 'required|max:100|alpha_num_spaces_slash_dash|unique:herramienta,nombre',
							'descripcion' => 'alpha_num_spaces_slash_dash_enter|max:200',
							'flag_seguridad' => 'required',
							'denominacion_herramienta' => 'required',
						);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('herramientas/crear_herramienta')->withErrors($validator)->withInput(Input::all());
				}else{
					$nombre_herramienta = Input::get('nombre_herramienta');
					$descripcion = Input::get('descripcion');
					$flag_seguridad = Input::get('flag_seguridad');
					$denominacion_herramienta = Input::get('denominacion_herramienta');

					$tipos_solicitud = TipoSolicitud::listarTiposSolicitud()->get();
					
					if($tipos_solicitud == null || $tipos_solicitud->isEmpty())
					{
						Session::flash('error', 'Error al realizar el registro. No existe tipo de acciones en el sistema.');
						return Redirect::to('herramientas/crear_herramienta');					
					}

					$herramienta = new Herramienta;
					$herramienta->nombre = $nombre_herramienta;
					if(strcmp($descripcion,"") != 0)
						$herramienta->descripcion = $descripcion;
					$herramienta->flag_seguridad = $flag_seguridad;
					$herramienta->iddenominacion_herramienta = $denominacion_herramienta;
					$herramienta->iduser_created_by = $data["user"]->id;
					


					$herramienta->save();	

					
					$size_tipos = count($tipos_solicitud);
					for($i=0;$i<$size_tipos;$i++){
						$herramientaxtipo_solicitud = new HerramientaXTipoSolicitud;
						$herramientaxtipo_solicitud->idherramienta = $herramienta->idherramienta;
						$herramientaxtipo_solicitud->idtipo_solicitud = $tipos_solicitud[$i]->idtipo_solicitud;
						$herramientaxtipo_solicitud->save();
					}

					Session::flash('message', 'Se registró correctamente la herramienta.');
					
					return Redirect::to('herramientas/crear_herramienta');
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function editar_herramienta($idherramienta=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idherramienta)
			{	
				$data["denominaciones"] = DenominacionHerramienta::lists('nombre','iddenominacion_herramienta');
				$data["herramienta"] = Herramienta::withTrashed()->find($idherramienta);

				if($data["herramienta"]==null){
					return Redirect::to('herramientas/listar_herramientas');
				}

				return View::make('Mantenimientos/Herramientas/editarHerramienta',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_editar_herramienta()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$user = User::find(Input::get('usuario_id'));
				$herramienta_id = Input::get('herramienta_id');
				$attributes = array(
					'nombre_herramienta' => 'Nombre de Aplicativo',
					'descripcion' => 'Descripcion',
					'flag_seguridad' => 'Valida Seguridad',
					'denominacion_herramienta' => 'Categoría Aplicativo',
				);

				$messages = array();

				$rules = array(
					'nombre_herramienta' => 'required|max:100|alpha_num_spaces_slash_dash|unique:herramienta,nombre,'.$herramienta_id.',idherramienta',
					'descripcion' => 'alpha_num_spaces_slash_dash_enter|max:200',
					'flag_seguridad' => 'required',
					'denominacion_herramienta' => 'required',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					$herramienta_id = Input::get('herramienta_id');
					$url = "herramientas/editar_herramienta"."/".$herramienta_id;
					return Redirect::to($url)->withErrors($validator)->withInput(Input::all());
				}else{	
					$herramienta_id = Input::get('herramienta_id');
					$url = "herramientas/editar_herramienta"."/".$herramienta_id;		
					
					$nombre_herramienta = Input::get('nombre_herramienta');
					$descripcion = Input::get('descripcion');
					$flag_seguridad = Input::get('flag_seguridad');
					$denominacion_herramienta = Input::get('denominacion_herramienta');
					
					$tipos_solicitud = TipoSolicitud::listarTiposSolicitud()->get();
					
					if($tipos_solicitud == null || $tipos_solicitud->isEmpty())
					{
						Session::flash('error', 'Error al realizar el registro. No existe tipo de acciones en el sistema.');
						return Redirect::to('herramientas/crear_herramienta');					
					}

					$herramienta = Herramienta::find($herramienta_id);
					$herramienta->nombre = $nombre_herramienta;
					if(strcmp($descripcion,"") != 0)
						$herramienta->descripcion = $descripcion;
					$herramienta->flag_seguridad = $flag_seguridad;
					$herramienta->iddenominacion_herramienta = $denominacion_herramienta;
					$herramienta->iduser_updated_by = $data["user"]->id;
					
					$herramienta->save();	

					return Redirect::to('herramientas/listar_herramientas')->with('message', 'Se editó correctamente el aplicativo: '.$herramienta->nombre);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

}
