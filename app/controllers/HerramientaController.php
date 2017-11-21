<?php

class HerramientaController extends BaseController {

	public function listar_herramientas_disponibles()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		$resultado = null;
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$usuario_id = Input::get('usuario_id');
			$herramientas = Herramienta::listarHerramientasDisponibles($usuario_id)->get();
			if($herramientas == null || $herramientas->isEmpty())
				return Response::json(array( 'success' => false),200);
			return Response::json(array( 'success' => true, 'herramientas'=>$herramientas),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function submit_agregar_herramientas_usuario(){
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$usuario_id = Input::get('usuario_id');
			$id_herramientas = Input::get('herramientas');
			
			///procedemos a crear los nuevos herramientaxuser
			$tamano_arr_idherramientas = count($id_herramientas);
			for($i=0;$i<$tamano_arr_idherramientas;$i++){
				$herramientasxuser = new HerramientaXUser;
				$herramientasxuser->idherramienta = $id_herramientas[$i];
				$herramientasxuser->iduser = $usuario_id;
				$herramientasxuser->iduser_created_by = $data["user"]->id;
				$herramientasxuser->estado = 1;
				$herramientasxuser->save();
			}

			return Response::json(array( 'success' => true),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

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
			$hu = HerramientaXUser::find($idherramientaxusers);
			if($hu==null)
				return Response::json(array( 'success' => false),200);

			$herramienta =Herramienta::find($hu->idherramienta);

			if($herramienta==null)
				return Response::json(array( 'success' => false),200);

			$hu->forceDelete();
			

			return Response::json(array( 'success' => true,'idherramientaxusers' => $idherramientaxusers,'nombre_herramienta'=>$herramienta->nombre),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}



}
