<?php

class TipoSolicitudController extends BaseController {

	public function ver_acciones_herramienta(){
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
			//buscamos las acciones
			$herramientasxusuario = HerramientaXUser::find($idherramientaxusers);
			if($herramientasxusuario==null)
				return Response::json(array( 'success' => false),200);

			$accionesxherramientaxusuario = HerramientaXTipoSolicitudXUser::listarTipoSolicitudUsuario($herramientasxusuario->iduser)->get();

			$herramienta = Herramienta::find($herramientasxusuario->idherramienta);

			if($accionesxherramientaxusuario==null || $accionesxherramientaxusuario->isEmpty() )
				return Response::json(array( 'success' => false),200);

			return Response::json(array( 'success' => true,'acciones' => $accionesxherramientaxusuario,'nombre_herramienta'=>$herramienta->nombre),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

}
