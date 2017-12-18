<?php

class EquivalenciaTipoSolicitudController extends BaseController {

	public function mostrar_equivalencias_tipo_solicitud()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1)
			{	
				$data["tipos_solicitud"] = TipoSolicitud::listarTiposSolicitud()->get();
				$data["tipos_solicitud_lista"] = TipoSolicitud::lists('nombre','idtipo_solicitud');
				$data["equivalencias_data"] = array();

				return View::make('Mantenimientos/EquivalenciasTipoSolicitud/listarEquivalencias',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_equivalencias_ajax()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$idtipo_solicitud = Input::get('idtipo_solicitud');
			//buscamos las acciones
			$equivalencias = EquivalenciaTipoSolicitud::buscarEquivalenciasPorIdTipoSolicitud($idtipo_solicitud)->get();
			if($equivalencias==null)
				return Response::json(array( 'success' => false),200);

			return Response::json(array( 'success' => true,'equivalencias' => $equivalencias),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function submit_eliminar_equivalencia_tipo_solicitud()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$idequivalencia_tipo_solicitud = Input::get('idequivalencia_tipo_solicitud');
			//buscamos las acciones
			$equivalencias = EquivalenciaTipoSolicitud::find($idequivalencia_tipo_solicitud);
			if($equivalencias==null)
				return Response::json(array( 'success' => false),200);

			$tipo_solicitud = TipoSolicitud::find($equivalencias->idtipo_solicitud);

			if($tipo_solicitud==null)
				return Response::json(array( 'success' => false),200);

			if(strcmp(strtolower($equivalencias->nombre_equivalencia),strtolower($tipo_solicitud->nombre))!=0){
				$equivalencia_data = $equivalencias;
				$equivalencias->forceDelete();
				return Response::json(array( 'success' => true,'equivalencias' => $equivalencia_data, 'resultado' => 1),200);
			}

			return Response::json(array( 'success' => true,'equivalencias' => $equivalencias, 'resultado' => 0),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function submit_crear_equivalencia_tipo_solicitud()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$attributes = array(
					'tipo_solicitud' => 'Tipo Solicitud',
					'nombre_equivalencia' => 'Nombre Equivalente'
				);

				$messages = array();

				$rules = array(
					'tipo_solicitud' => 'required',
					'nombre_equivalencia' => 'required|max:100|alpha_num_spaces_slash_dash|unique:equivalencia_tipo_solicitud,nombre_equivalencia',
					
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('equivalencias_tipo_solicitud/listar_equivalencias')->withErrors($validator)->withInput(Input::all());
				}else{
					$nombre_equivalencia = Input::get('nombre_equivalencia');
					$tipo_solicitud_id = Input::get('tipo_solicitud');
					
					$equivalencia_repetida = EquivalenciaTipoSolicitud::buscarPorTipoSolicitudPorNombre($nombre_equivalencia,$tipo_solicitud_id)->get();

					if($equivalencia_repetida == null || $equivalencia_repetida->isEmpty()){

						$equivalencia = new EquivalenciaTipoSolicitud;
						$equivalencia->nombre_equivalencia = $nombre_equivalencia;
						$equivalencia->idtipo_solicitud = $tipo_solicitud_id;
						$equivalencia->iduser_created_by = $data["user"]->id;

						$equivalencia->save();

						Session::flash('message', 'Se ha registrado la equivalencia con éxito.');
					
						return Redirect::to('equivalencias_tipo_solicitud/listar_equivalencias');	

					}else{
						
						Session::flash('error', 'El nombre ya se encuentra registrado.');
					
						return Redirect::to('equivalencias_tipo_solicitud/listar_equivalencias');	
					}
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}
	

}
