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
							'telefono' => 'required|numeric',
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
					$user->save();					
					return Redirect::to('usuarios/listar_usuarios')->with('message', 'Se creó correctamente el usuario');
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

				if($data["user"]->flag_cambiar_contrasena == 1)
					return View::make('Mantenimientos/Usuarios/cambiarContrasena',$data);
				else
				{
					return Redirect::to('/')->with('error','Administrador no ha reestablecido la contraseña para el cambio');
				}
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
					$user->flag_cambiar_contrasena = 0;					
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
				$data["usuario"] = User::withTrashed()->find($idusuario);

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
					$usuario->flag_cambiar_contrasena = 1;
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
							'telefono' => 'required|numeric',
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
					$user->idrol = Input::get('rol');
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
				$data["usuario"] = User::withTrashed()->find($idusuario);

				if($data["usuario"]==null){						
					return Redirect::to('usuarios/listar_usuarios')->with('error','Usuario no encontrado');
				}
				$data["herramientas"] = HerramientaXUser::buscarHerramientasPorIdUsuario($data["usuario"]->id)->get();
				$data["sectores"] = UsersXSector::buscarSectoresPorIdUsuario($data["usuario"]->id)->get();
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
					if($data["user"]->idrol == 1)
						return Redirect::to('/principal_admin')->with('error','Se ha intentado acceder a la información de otro usuario que no corresponde al usuario logueado');
					else
						return Redirect::to('/principal_gestor')->with('error','Se ha intentado acceder a la información de otro usuario que no corresponde al usuario logueado');
				}

				$data["herramientas"] = HerramientaXUser::buscarHerramientasPorIdUsuario($data["usuario"]->id)->get();

				$data["sectores"] = UsersXSector::buscarSectoresPorIdUsuario($data["usuario"]->id)->get();

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
				$data["herramientas_disponibles"] = Herramienta::listarHerramientasDisponibles($data["usuario"]->id)->get();
				/*echo '<pre>';
			    var_dump(count($data["herramientas_disponibles"]));
			    echo '</pre>';*/
				
				return View::make('Mantenimientos/usuarios/mostrarHerramientasUsuario',$data);			
				
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_sectores_usuario($idusuario=null){
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
				$data["sectores"] = UsersXSector::buscarSectoresPorIdUsuario($data["usuario"]->id)->get();
				$data["sectores_disponibles"] = Sector::listarSectoresDisponibles($data["usuario"]->id)->get();
				/*echo '<pre>';
			    var_dump(count($data["sectores_disponibles"]));
			    echo '</pre>';*/
				
				return View::make('Mantenimientos/usuarios/mostrarSectoresUsuario',$data);			
				
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_canales_usuario($idusuario=null){
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
				$data["canales"] = Canal::buscarCanalesPorIdUsuarioResponsable($data["usuario"]->id)->get();
				$data["canales_disponibles"] = Canal::listarCanalesSinResponsable()->get();
				/*echo '<pre>';
			    var_dump(count($data["sectores_disponibles"]));
			    echo '</pre>';*/
				
				return View::make('Mantenimientos/usuarios/mostrarCanalesResponsable',$data);			
				
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_agregar_herramientas(){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$user = User::find(Input::get('usuario_id'));				
				$usuario_id = Input::get('usuario_id');
				$arr_idherramienta = Input::get('ids_herramientas');
				/*echo '<pre>';
			    var_dump(count($arr_idherramienta));
			    echo '</pre>';*/
			    $size_ids = count($arr_idherramienta);
			    $flag_seleccion = false;
			    //por cada uno validar si el checkbox asociado fue tecleado
			    for($i=0;$i<$size_ids;$i++){
			    	$res_checkbox = Input::get('checkbox'.$i);
			    	/*echo '<pre>';
				    var_dump('seleccionado ' .$res_checkbox.': id-> '.$arr_idherramienta[$i] );	
				    echo '</pre>';*/
				    if($res_checkbox == 1)
				    {
				    	//validar si ya existe el objeto
				    	$herramientaxuser = HerramientaXUser::buscarHerramientasPorIdUsuarioIdHerramienta($usuario_id,$arr_idherramienta[$i])->get();
				    	/*echo '<pre>';
					    var_dump($herramientaxuser[0]->deleted_at);	
					    echo '</pre>';*/
				    	
				    	if($herramientaxuser==null || $herramientaxuser->isEmpty()){
				    		//quiere decir que es una herramienta nueva a crear
				    		$herramientaxuser = new HerramientaXUser;
					    	$herramientaxuser->idherramienta = $arr_idherramienta[$i];
					    	$herramientaxuser->iduser = $usuario_id;
					    	$herramientaxuser->estado = 1;
					    	$herramientaxuser->save();
					    	//Agregar todos los tipos de acciones:
					    	$herramientaxtipo_solicitud = HerramientaXTipoSolicitud::listarTipoSolicitudHerramienta($herramientaxuser->idherramienta)->get();
					    	if(!$herramientaxtipo_solicitud->isEmpty()){
					    		$size_tipos = count($herramientaxtipo_solicitud);
					    		for($j=0;$j<$size_tipos;$j++){
					    			$herramientaxtipo_solicitudxuser = new HerramientaXTipoSolicitudXUser;
					    			$herramientaxtipo_solicitudxuser->idherramientaxtipo_solicitud = $herramientaxtipo_solicitud[$j]->idherramientaxtipo_solicitud;
					    			$herramientaxtipo_solicitudxuser->iduser = $usuario_id;
					    			$herramientaxtipo_solicitudxuser->save();
					    		}
					    	}
				    	}else{
				    		//quiere decir que solo se requiere restaurar
				    		$herramientaxuser = $herramientaxuser->first();
				    		$herramientaxuser->restore();
				    		$accionesHerramientaUsuario = HerramientaXTipoSolicitudXUser::listarTipoSolicitudUsuario($usuario_id,$herramientaxuser->idherramienta)->get();


				    		$size_acciones = count($accionesHerramientaUsuario);
				    		for($j=0;$j<$size_acciones;$j++){
				    			$accionesHerramientaUsuario[$j]->restore();
				    		}
				    	}
				    	$flag_seleccion = true;
				    }
			    }

			  	if($size_ids > 0)
			    {
			    	if($flag_seleccion)
			    		return Redirect::to('usuarios/mostrar_herramientas_usuario/'.$usuario_id)->with('message', 'Se actualizaron correctamente los aplicativos al usuario');	
			    	else
			    		return Redirect::to('usuarios/mostrar_herramientas_usuario/'.$usuario_id)->with('error', 'No se seleccionó ningún aplicativo.');	
			    }else
			    {
			    	return Redirect::to('usuarios/mostrar_herramientas_usuario/'.$usuario_id)->with('error', 'No se agregaron los aplicativos al usuario');	
			   	}
				
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_agregar_sectores(){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$user = User::find(Input::get('usuario_id'));				
				$usuario_id = Input::get('usuario_id');
				$arr_idsectores = Input::get('ids_sectores');
				/*echo '<pre>';
			    var_dump(count($arr_idsectores));
			    echo '</pre>';*/
			    $size_ids = count($arr_idsectores);
			    $flag_seleccion = false;
			    //por cada uno validar si el checkbox asociado fue tecleado
			    for($i=0;$i<$size_ids;$i++){
			    	$res_checkbox = Input::get('checkbox'.$i);
			    	/*echo '<pre>';
				    var_dump('seleccionado ' .$res_checkbox.': id-> '.$arr_idsectores[$i] );	
				    echo '</pre>';*/
				    if($res_checkbox == 1)
				    {
				    	//validar si ya existe (en caso exista se hace un restore)
				    	$us = UsersXSector::buscarPorIdSectorIdUsuario($arr_idsectores[$i],$usuario_id)->get();
				    	if($us!= null && !$us->isEmpty() && count($us)>0)
				    		$us[0]->restore();
				    	else
				    	{
				    		$usersxsector = new UsersXSector;
					    	$usersxsector->idsector = $arr_idsectores[$i];
					    	$usersxsector->iduser = $usuario_id;
					    	$usersxsector->save();
				    	}				    	
				    	$flag_seleccion = true;
				    }
			    }

			    if($size_ids > 0)
			    {
			    	if($flag_seleccion)
			    		return Redirect::to('usuarios/mostrar_sectores_usuario/'.$usuario_id)->with('message', 'Se actualizaron correctamente los sectores al usuario');	
			    	else
			    		return Redirect::to('usuarios/mostrar_sectores_usuario/'.$usuario_id)->with('error', 'No se seleccionó ningún sector.');	
			    }else
			    {
			    	return Redirect::to('usuarios/mostrar_sectores_usuario/'.$usuario_id)->with('error', 'No se agregaron los sectores al usuario');	
			    }	
				
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_agregar_canales(){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$user = User::find(Input::get('usuario_id'));				
				$usuario_id = Input::get('usuario_id');
				$arr_idcanales = Input::get('ids_canales');
				/*echo '<pre>';
			    var_dump(count($arr_idsectores));
			    echo '</pre>';*/
			    $size_ids = count($arr_idcanales);
			    $flag_seleccion = false;
			    //por cada uno validar si el checkbox asociado fue tecleado
			    for($i=0;$i<$size_ids;$i++){
			    	$res_checkbox = Input::get('checkbox'.$i);
			    	/*echo '<pre>';
				    var_dump('seleccionado ' .$res_checkbox.': id-> '.$arr_idcanales[$i] );	
				    echo '</pre>';*/
				    if($res_checkbox == 1)
				    {
				    	
				    	$canal = Canal::find($arr_idcanales[$i]);
				    	$canal->idusuario_responsable = $usuario_id;
				    	$canal->save();
				    	$flag_seleccion = true;
				    }
			    }

			    if($size_ids > 0)
			    {
			    	if($flag_seleccion)
			    		return Redirect::to('usuarios/mostrar_canales_usuario/'.$usuario_id)->with('message', 'Se actualizaron correctamente los canales al usuario como responsable');	
			    	else
			    		return Redirect::to('usuarios/mostrar_canales_usuario/'.$usuario_id)->with('error', 'No se seleccionó ningún canal.');	
			    }else
			    {
			    	return Redirect::to('usuarios/mostrar_canales_usuario/'.$usuario_id)->with('error', 'No se agregaron los canales al usuario');	
			    }	
				
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_inhabilitar_usuario()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$user_id = Input::get('user_id');
				$url = "usuarios/mostrar_usuario"."/".$user_id;
				$user = User::find($user_id);
				
				//No importa que tenga solicitudes pendientes o procesando
				$user->delete();
				
				
				Session::flash('message', 'Se inhabilitó correctamente al usuario.');
				return Redirect::to($url);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_habilitar_usuario()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$user_id = Input::get('user_id');
				$url = "usuarios/mostrar_usuario"."/".$user_id;
				$user = User::withTrashed()->find($user_id);
				$user->restore();
				Session::flash('message', 'Se habilitó correctamente al usuario.');
				return Redirect::to($url);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

}