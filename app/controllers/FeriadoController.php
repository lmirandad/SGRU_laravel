<?php

class FeriadoController extends BaseController {

	public function listar_feriados()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1)
			{	
				$data["feriados_data"] = Feriado::listarFeriados()->get();
				$data["search_anho"] = null;
				
				return View::make('Mantenimientos/Feriados/listarFeriados',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_feriado()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$attributes = array(
					'fecha_feriado' => 'Fecha Feriado',
					'nombre_motivo' => 'Motivo'
				);

				$messages = array();

				$rules = array(
					'fecha_feriado' => 'date|required|unique:fecha_feriado,valor_fecha',
					'nombre_motivo' => 'required|max:100|alpha_num_spaces_slash_dash',
					
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('feriados/listar_feriados')->withErrors($validator)->withInput(Input::all());
				}else{
					$fecha_feriado = Input::get('fecha_feriado');
					$nombre_motivo = Input::get('nombre_motivo');
					
					$feriado = new Feriado;
					$feriado->valor_fecha = date('Y-m-d H:i:s',strtotime($fecha_feriado));
					$feriado->motivo_feriado = $nombre_motivo;
					$feriado->save();
						
					Session::flash('message', 'Se registó correctamente la fecha como feriado.');
					
					return Redirect::to('feriados/listar_feriados');	
					
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_eliminar_feriado()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$idferiado = Input::get('idferiado');
			//buscamos las acciones
			$feriado = Feriado::find($idferiado);
			if($feriado==null)
				return Response::json(array( 'success' => false),200);

			$fecha = date("d-m-Y",strtotime($feriado->valor_fecha));

			$feriado->forceDelete();

			return Response::json(array( 'success' => true,'fecha' => $fecha),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function buscar_feriados()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 ){
				$data["search_anho"] = Input::get('search_anho');
				if($data["search_anho"] == null){
					$data["feriados_data"] = Feriado::listarFeriados()->get();
					return View::make('Mantenimientos/Feriados/listarFeriados',$data);

				}else{
					$data["feriados_data"] = Feriado::buscarFeriados($data["search_anho"])->get(0);
					return View::make('Mantenimientos/Feriados/listarFeriados',$data);	
				}				
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

}
