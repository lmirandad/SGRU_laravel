<?php

class LoginController extends BaseController {

	public function login()
	{
		
		// Implemento la reglas para la validacion de los datos
		$rules = array(
					'usuario' => 'required|min:6',
					'password' => 'required|min:6'
				);
		// Corro la validacion
		$validator = Validator::make(Input::all(), $rules);
		// Si falla la validacion se redirige al login con un mensaje de error
		if($validator->fails()){
			Session::flash('error', 'Ingrese los campos correctamente');
			return Redirect::to('/')->withInput(Input::except('password'));
		}else{
			// Se crea un arreglo de datos para intentar la autenticacion
			$userdata = array(
							'username' => Input::get('usuario'),
							'password' => Input::get('password')
						);
			// Se intenta autenticar al usuario			
			if(Auth::attempt($userdata)){
				// Si la autenticacion es exitosa guardo en la variable de sesión la información del usuario			
				$numero_doc_identidad = User::buscarUsuarioPorUsername($userdata["username"])->get()[0]->numero_doc_identidad;
				Session::put('user',Auth::user());
				if(strcmp ( $numero_doc_identidad , $userdata["password"] ) == 0){
					return Redirect::to('/usuarios/cambiar_contrasena');						
				}				
				$data["user"] = Session::get('user');
				Session::put('user',Auth::user());
				return Redirect::to('/principal');
				
				
			}else{
				// Si falla la autenticacion se lo regresa al login con un mensaje de error
				Session::flush();
				Session::flash('error', 'Usuario y/o contraseña incorrectos');
				return Redirect::to('/')->withInput(Input::except('password'));
			}
		}
	}

	public function login_expires()
	{
		// Llamo a la función para registrar el log de auditoria
		$descripcion_log = "Se cerró sesión por tiempo expirado";
		// Cierro la sesion del usuario
		Auth::logout();
		Session::flush();
		return Redirect::to('/');
	}

	public function logout()
	{
		// Cierro la sesion del usuario
		Auth::logout();
		Session::flush();
		return Redirect::to('/');
	}	

}
