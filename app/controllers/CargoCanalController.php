<?php

class CargoCanalController extends BaseController {

	public function listar_cargos_canal($idcanal = null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1 || $idcanal)
			{	
				
				$data["cargos_canal"] = CargoCanal::buscarCargosPorCanal($idcanal)->get();
				$data["canal_id"] = $idcanal;
				$canal = Canal::find($idcanal);
				$data["nombre_canal"] = $canal->nombre;

				return View::make('Mantenimientos/CargosCanal/listarCargosCanal',$data);

			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_cargo_canal()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs


				$canal_id = Input::get('canal_id');

				$attributes = array(
					'nombre_cargo_canal' => 'Nombre del Tipo de Cargo',
				);

				$messages = array();

				$rules = array(
					'nombre_cargo_canal' => 'required|max:200|alpha_num_spaces_slash_dash|unique:cargo_canal,nombre',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('cargos_canal/listar_cargos_canal/'.$canal_id)->withErrors($validator)->withInput(Input::all());
				}else{

					$nombre_cargo_canal = Input::get('nombre_cargo_canal');
					$cargo_canal = new CargoCanal;
					$cargo_canal->nombre = $nombre_cargo_canal;
					$cargo_canal->idcanal = $canal_id;
					$cargo_canal->iduser_created_by = $data["user"]->id;
					
					$cargo_canal->save();						

					Session::flash('message', 'Se registró correctamente el tipo de cargo.');
					
					return Redirect::to('cargos_canal/listar_cargos_canal/'.$canal_id);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}


	public function submit_editar_cargo_canal()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$cargo_canal_id = Input::get('cargo_canal_id');

				$canal_id = Input::get('canal_id_edicion');
				
				
				$attributes = array(
					'nombre_edicion_cargo_canal' => 'Nombre del Tipo de Cargo',
				);

				$messages = array();

				$rules = array(
					'nombre_edicion_cargo_canal' => 'required|max:200|alpha_num_spaces_slash_dash|unique:cargo_canal,nombre,'.$cargo_canal_id.',idcargo_canal',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					
					$cargo_canal_id = Input::get('cargo_canal_id');
					$url = "cargos_canal/listar_cargos_canal"."/".$canal_id;
					return Redirect::to($url)->withErrors($validator)->withInput(Input::all());
				}else{	

					$cargo_canal_id = Input::get('cargo_canal_id');
					$url = "cargos_canal/listar_cargos_canal"."/".$canal_id;		
					
					$nombre = Input::get('nombre_edicion_cargo_canal');

					$cargo_canal = CargoCanal::find($cargo_canal_id);
					$cargo_canal->nombre = $nombre;
					$cargo_canal->iduser_updated_by = $data["user"]->id;
					
					$cargo_canal->save();	

					return Redirect::to($url)->with('message', 'Se editó correctamente el tipo de cargo: '.$cargo_canal->nombre);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

}
