<?php

class UserController extends BaseController {

	public function listar_usuarios()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1){
				$data["search"] = null;
				$data["search_tipo_doc_identidad"] = null;
				$data["search_documento_identidad"] = null;
				$data["search_herramienta"] = null;
				$data["herramientas"] = Herramienta::lists('nombre','idherramienta');
				$data["tipo_doc_identidad"] = TipoDocIdentidad::lists('nombre','idtipo_doc_identidad');
				$data["users_data"] = User::listarUsuarios()->paginate(10);
				return View::make('Mantenimientos/Usuarios/listarUsuarios',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function buscar_usuarios()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1){
				$data["search"] = Input::get('search');
				$data["search_tipo_doc_identidad"] = Input::get('search_tipo_doc_identidad');;
				$data["search_documento_identidad"] = Input::get('search_documento_identidad');
				$data["search_herramienta"] = Input::get('search_herramienta');
				$data["herramientas"] = Herramienta::lists('nombre','idherramienta');
				$data["tipo_doc_identidad"] = TipoDocIdentidad::lists('nombre','idtipo_doc_identidad');
				if($data["search"] == null && $data["search_tipo_doc_identidad"]== 0 && 
					$data["search_documento_identidad"]== null && $data["herramientas"] == 0){

					$data["users_data"] = User::listarUsuarios()->paginate(10);
					return View::make('Mantenimientos/Usuarios/listarUsuarios',$data);

				}else{
					$data["users_data"] = User::buscarUsuariosVistaMantenimiento($data["search"],$data["search_tipo_doc_identidad"],$data["search_documento_identidad"],$data["search_herramienta"])->paginate(10);
					return View::make('Mantenimientos/Usuarios/listarUsuarios',$data);	
				}				
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function crear_usuario(){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){

				$data["tipo_doc_identidades"] = TipoDocIdentidad::lists('nombre','idtipo_doc_identidad');
				$data["roles"] = Rol::lists('nombre','idrol');
				
				return View::make('Mantenimientos/Usuarios/crearUsuario',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_usuario(){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$attributes = array(
					'username' => 'Nombre de Usuario',
					'nombre' => 'Nombres',
					'apellido_paterno' => 'Apellido Paterno',
					'apellido_materno' => 'Apellido Materno',
					'tipo_doc_identidad' => 'Tipo de Documento',
					'documento_identidad' => 'Número de Documento de Identidad',
					'genero' => 'Género',
					'fecha_nacimiento' => 'Fecha Nacimiento',
					'email' => 'E-mail',
					'telefono' => 'Teléfono',					
					'rol' => 'Perfil de Usuario (Rol)',					
					);

				$messages = array();

				$rules = array(
							'username' => 'required|min:6|max:45|unique:users|alpha_num_dash',
							'nombre' => 'required|alpha_spaces|min:2|max:45|alpha_spaces',
							'apellido_paterno' => 'required|alpha_spaces|min:2|max:45|alpha_num_dash',
							'apellido_materno' => 'required|alpha_spaces|min:2|max:45|alpha_num_dash',
							'tipo_doc_identidad' => 'required',
							'documento_identidad' => 'required|numeric|digits_between:8,16|unique:users,numero_doc_identidad',
							'genero' => 'required',
							'fecha_nacimiento' => 'required',
							'email' => 'required|email|max:45',							
							'telefono' => 'required|min:7|max:20|alpha_num_dash',
							'rol' => 'required',
						);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('usuarios/crear_usuario')->withErrors($validator)->withInput(Input::all());
				}else{
					$documento_identidad = Input::get('documento_identidad');		
					$password = $documento_identidad;
					$user = new User;
					$user->username = Input::get('username');
					$user->password = Hash::make($password);
					$user->email = Input::get('email');
					$user->nombre = Input::get('nombre');
					$user->apellido_paterno = Input::get('apellido_paterno');
					$user->apellido_materno = Input::get('apellido_materno');
					$user->idtipo_doc_identidad = Input::get('tipo_doc_identidad');
					$user->numero_doc_identidad = $documento_identidad;
					$user->idrol = Input::get('rol');
					$user->genero = Input::get('genero');
					$user->telefono = Input::get('telefono');
					$user->fecha_nacimiento = date('Y-m-d H:i:s',strtotime(Input::get('fecha_nacimiento')));
					$user->iduser_created_by = $data["user"]->id;
					$user->usuario_bloqueado = 0; //no está bloqueado
					$user->usuario_vena = 0; //no es vena
					$user->save();					
					Session::flash('message', 'Se registró correctamente al usuario.');
					
					return Redirect::to('usuarios/crear_usuario');
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function cambiar_contrasena(){
		if(Auth::check()){
				$data["inside_url"] = Config::get('app.inside_url');
				$data["user"] = Session::get('user');
				return View::make('Mantenimientos/Usuarios/cambiarContrasena',$data);
		}else{
			return View::make('error/error',$data);
		}		
	}

	public function submit_cambiar_contrasena(){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');

			$user = User::find($data["user"]->id);
			//Validate the info, create rules for the inputs
				$attributes = array(
					'password_nueva' => 'nueva contraseña',
					'password_nueva_2' => 'contraseña reingresada',								
					);

				$messages = array();

				$rules = array(
							'password_nueva' => 'required|min:6',
							'password_nueva_2' => 'required|same:password_nueva'							
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){					
					return Redirect::to('/usuarios/cambiar_contrasena')->withErrors($validator)->withInput(Input::all());
				}else{
					$user->password = Hash::make(Input::get('password_nueva'));
					//$user->password = Crypt::encrypt($password);					
					$user->save();					
					Session::flash('message', 'Se cambió la contraseña con éxito.');
					
					return Redirect::to('/');
				}
			

		}else{
			return View::make('error/error',$data);
		}
	}
	
	public function editar_usuario($idusuario=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idusuario)
			{	
				$data["tipo_doc_identidades"] = TipoDocIdentidad::lists('nombre','idtipo_doc_identidad');
				$data["roles"] = Rol::lists('nombre','idrol');				
				$data["usuario"] = User::find($idusuario);

				if($data["usuario"]==null){
					return Redirect::to('usuarios/listar_usuarios');
				}
				

				return View::make('Mantenimientos/usuarios/editarUsuario',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function reestablecer_contrasena(){		
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		$resultado = null;
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$id_usuario = Input::get('usuario_id');
			if($id_usuario !=0){ //el id es mayor que 0
				$usuario = User::find($id_usuario);
				if($usuario != null){
					$documento_identidad = Input::get('documento_identidad');
					$usuario->password = Hash::make($documento_identidad);
					$usuario->iduser_updated_by = $data["user"]->id;
					$usuario->save();
					return Response::json(array( 'success' => true, 'usuario' => $usuario ),200);	
				}else{
					//si $resultado es igual a 1 quiere decir que no se encontró el usuario
					return Response::json(array( 'success' => true, 'usuario' => null,'resultado' =>1 ),200);	
				}
				
			}else{
				return Response::json(array( 'success' => true,'usuario' => null,'resultado' =>1 ),200);
			}
			//si $resultado es igual a 2, quiere decir que no hay permisos para esta actualización de contraseña
			return Response::json(array( 'success' => true, 'usuario' => null, 'resultado'=>2 ),200);
			
		}else{
			return Response::json(array( 'success' => false, ),200);
		}
	}

	public function submit_editar_usuario()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$user = User::find(Input::get('usuario_id'));

				$attributes = array(
					'nombre' => 'Nombres',
					'apellido_paterno' => 'Apellido Paterno',
					'apellido_materno' => 'Apellido Materno',
					'tipo_doc_identidad' => 'Tipo de Documento',
					'documento_identidad' => 'Número de Documento de Identidad',
					'genero' => 'Género',
					'fecha_nacimiento' => 'Fecha Nacimiento',
					'email' => 'E-mail',
					'telefono' => 'Teléfono',					
					'rol' => 'Perfil de Usuario (Rol)',					
					);

				$messages = array();

				$rules = array(
							'nombre' => 'required|alpha_spaces|min:2|max:45|alpha_spaces',
							'apellido_paterno' => 'required|alpha_spaces|min:2|max:45|alpha_num_dash',
							'apellido_materno' => 'required|alpha_spaces|min:2|max:45|alpha_num_dash',
							'tipo_doc_identidad' => 'required',
							'documento_identidad' => 'required|numeric|digits_between:8,16|unique:users,numero_doc_identidad,'.$user->id.',id',
							'genero' => 'required',
							'fecha_nacimiento' => 'required',
							'email' => 'required|email|max:45',							
							'telefono' => 'required|min:7|max:20|alpha_num_dash',
							'rol' => 'required',
						);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					$usuario_id = Input::get('usuario_id');
					$url = "usuarios/editar_usuario"."/".$usuario_id;
					return Redirect::to($url)->withErrors($validator)->withInput(Input::all());
				}else{	
					$usuario_id = Input::get('usuario_id');
					$url = "usuarios/editar_usuario"."/".$usuario_id;				
					$user->email = Input::get('email');
					$user->nombre = Input::get('nombre');
					$user->apellido_paterno = Input::get('apellido_paterno');
					$user->apellido_materno = Input::get('apellido_materno');
					$user->idtipo_doc_identidad = Input::get('tipo_doc_identidad');
					$user->numero_doc_identidad = Input::get('documento_identidad');
					//$user->idrol = Input::get('rol'); POR DEFINIR (PODRIA SER POSIBLE DARLE DE BAJA Y CREARLE OTRO)
					$user->genero = Input::get('genero');
					$user->telefono = Input::get('telefono');
					$user->fecha_nacimiento = date('Y-m-d H:i:s',strtotime(Input::get('fecha_nacimiento')));
					$user->iduser_updated_by = $data["user"]->id;
					$user->save();						
					return Redirect::to('usuarios/listar_usuarios')->with('message', 'Se editó correctamente el usuario: '.$user->nombre.' '.$user->apellido_paterno.' '.$user->apellido_materno);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_usuario($idusuario=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idusuario)
			{	
				$data["tipo_doc_identidades"] = TipoDocIdentidad::lists('nombre','idtipo_doc_identidad');
				$data["roles"] = Rol::lists('nombre','idrol');				
				$data["usuario"] = User::find($idusuario);

				if($data["usuario"]==null){						
					return Redirect::to('usuarios/listar_usuarios')->with('error','Usuario no encontrado');
				}
				$data["herramientas"] = HerramientaXUser::buscarHerramientasPorIdUsuario($data["usuario"]->id)->get();
				return View::make('Mantenimientos/usuarios/mostrarUsuario',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_usuario_actual($idusuario=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($idusuario && $idusuario == $data["user"]->id )
			{	
				$data["tipo_doc_identidades"] = TipoDocIdentidad::lists('nombre','idtipo_doc_identidad');
				$data["roles"] = Rol::lists('nombre','idrol');				
				$data["usuario"] = User::find($idusuario);

				if($data["usuario"]==null){						
					return Redirect::to('/principal')->with('error','Se ha intentado acceder a la información de otro usuario que no corresponde al usuario logueado');
				}

				$data["herramientas"] = HerramientaXUser::buscarHerramientasPorIdUsuario($data["usuario"]->id)->get();

				return View::make('Mantenimientos/usuarios/mostrarUsuario',$data);

			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_herramientas_usuario($idusuario=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idusuario)
			{	
				$data["usuario"] = User::find($idusuario);
				if($data["usuario"]==null){						
					return Redirect::to('usuarios/listar_usuarios')->with('error','Usuario no encontrado');
				}
				$data["herramientas"] = HerramientaXUser::buscarHerramientasPorIdUsuario($data["usuario"]->id)->get();
				

				return View::make('Mantenimientos/usuarios/mostrarHerramientasUsuario',$data);
				
				
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

}