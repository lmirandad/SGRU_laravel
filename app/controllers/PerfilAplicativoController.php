<?php

class PerfilAplicativoController extends BaseController {

	public function listar_perfiles_aplicativos($idherramienta = null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1 || $idherramienta)
			{	
				
				$data["perfiles_aplicativos"] = PerfilAplicativo::buscarPerfilesPorHerramienta($idherramienta)->get();
				$data["herramienta_id"] = $idherramienta;
				$herramienta = Herramienta::find($idherramienta);
				$data["nombre_herramienta"] = $herramienta->nombre;

				return View::make('Mantenimientos/PerfilesAplicativos/listarPerfilesAplicativos',$data);

			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_perfil_aplicativo()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs


				$herramienta_id = Input::get('herramienta_id');

				$attributes = array(
					'nombre_perfil_aplicativo' => 'Nombre del Perfil del Aplicativo',
				);

				$messages = array();

				$rules = array(
					'nombre_perfil_aplicativo' => 'required|max:200|alpha_num_spaces_slash_dash|unique:perfil_aplicativo,nombre',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('perfiles_aplicativos/listar_perfiles_aplicativos/'.$herramienta_id)->withErrors($validator)->withInput(Input::all());
				}else{

					$nombre_perfil_aplicativo = Input::get('nombre_perfil_aplicativo');
					$perfil_aplicativo = new PerfilAplicativo;
					$perfil_aplicativo->nombre = $nombre_perfil_aplicativo;
					$perfil_aplicativo->idherramienta = $herramienta_id;
					$perfil_aplicativo->iduser_created_by = $data["user"]->id;
					
					$perfil_aplicativo->save();						

					Session::flash('message', 'Se registró correctamente el perfil.');
					
					return Redirect::to('perfiles_aplicativos/listar_perfiles_aplicativos/'.$herramienta_id);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}


	public function submit_editar_perfil_aplicativo()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$perfil_aplicativo_id = Input::get('perfil_aplicativo_id');

				$herramienta_id = Input::get('herramienta_id_edicion');
				
				
				$attributes = array(
					'nombre_edicion_perfil_aplicativo' => 'Nombre del Tipo de Cargo',
				);

				$messages = array();

				$rules = array(
					'nombre_edicion_perfil_aplicativo' => 'required|max:200|alpha_num_spaces_slash_dash|unique:perfil_aplicativo,nombre,'.$perfil_aplicativo_id.',idperfil_aplicativo',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					
					$perfil_aplicativo_id = Input::get('perfil_aplicativo_id');
					$url = "perfiles_aplicativos/listar_perfiles_aplicativos"."/".$herramienta_id;
					return Redirect::to($url)->withErrors($validator)->withInput(Input::all());
				}else{	

					$perfil_aplicativo_id = Input::get('perfil_aplicativo_id');
					$url = "perfiles_aplicativos/listar_perfiles_aplicativos"."/".$herramienta_id;		
					
					$nombre = Input::get('nombre_edicion_perfil_aplicativo');

					$perfil_aplicativo = PerfilAplicativo::find($perfil_aplicativo_id);
					$perfil_aplicativo->nombre = $nombre;
					$perfil_aplicativo->iduser_updated_by = $data["user"]->id;
					
					$perfil_aplicativo->save();	

					return Redirect::to($url)->with('message', 'Se editó correctamente el perfil: '.$perfil_aplicativo->nombre);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

}
