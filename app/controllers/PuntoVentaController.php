<?php

class PuntoVentaController extends BaseController {

	public function listar_puntos_venta($identidad = null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1 || $identidad)
			{	
				
				$data["puntos_venta"] = PuntoVenta::buscarPuntosVentaPorEntidad($identidad)->get();
				$data["entidad_id"] = $identidad;
				$entidad = Entidad::find($identidad);
				$data["nombre_entidad"] = $entidad->nombre;

				return View::make('Mantenimientos/PuntosVenta/listarPuntosVenta',$data);

			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_punto_venta()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs


				$entidad_id = Input::get('entidad_id');

				$attributes = array(
					'nombre_punto_venta' => 'Nombre del Punto de Venta',
					'codigo_punto_venta' => 'Código del Punto de Venta'
				);

				$messages = array();

				$rules = array(
					'nombre_punto_venta' => 'required|max:200|alpha_num_spaces_slash_dash|unique:punto_venta,nombre',
					'codigo_punto_venta' => 'required|numeric',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('puntos_venta/listar_puntos_venta/'.$entidad_id)->withErrors($validator)->withInput(Input::all());
				}else{

					$nombre_punto_venta = Input::get('nombre_punto_venta');
					$codigo_punto_venta = INput::get('codigo_punto_venta');
					$punto_venta = new PuntoVenta;
					$punto_venta->nombre = $nombre_punto_venta;
					$punto_venta->codigo_punto_venta = $codigo_punto_venta;
					$punto_venta->identidad = $entidad_id;
					$punto_venta->iduser_created_by = $data["user"]->id;
					
					$punto_venta->save();						

					Session::flash('message', 'Se registró correctamente el punto de venta.');
					
					return Redirect::to('puntos_venta/listar_puntos_venta/'.$entidad_id);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}


	public function submit_editar_punto_venta()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$punto_venta_id = Input::get('punto_venta_id');

				$entidad_id = Input::get('entidad_id_edicion');
				
				
				$attributes = array(
					'nombre_edicion_punto_venta' => 'Nombre de Punto de Venta',
					'codigo_edicion_punto_venta' => 'Código del Punto de Venta'
				);

				$messages = array();

				$rules = array(
					'nombre_edicion_punto_venta' => 'required|max:200|alpha_num_spaces_slash_dash|unique:punto_venta,nombre,'.$punto_venta_id.',idpunto_venta',
					'codigo_edicion_punto_venta' => 'required|numeric',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					
					$punto_venta_id = Input::get('punto_venta_id');
					$url = "puntos_venta/listar_puntos_venta"."/".$entidad_id;
					return Redirect::to($url)->withErrors($validator)->withInput(Input::all());
				}else{	

					$punto_venta_id = Input::get('punto_venta_id');
					$url = "puntos_venta/listar_puntos_venta"."/".$entidad_id;		
					
					$nombre = Input::get('nombre_edicion_punto_venta');
					$codigo_punto_venta = Input::get('codigo_edicion_punto_venta');

					$punto_venta = PuntoVenta::find($punto_venta_id);
					$punto_venta->nombre = $nombre;
					$punto_venta->codigo_punto_venta = $codigo_punto_venta;
					$punto_venta->iduser_updated_by = $data["user"]->id;
					
					$punto_venta->save();	

					return Redirect::to($url)->with('message', 'Se editó correctamente el punto de venta: '.$punto_venta->nombre);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

}
