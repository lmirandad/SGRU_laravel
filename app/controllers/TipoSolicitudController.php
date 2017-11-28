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

			$accionesxherramientaxusuario = HerramientaXTipoSolicitudXUser::listarTipoSolicitudUsuario($herramientasxusuario->iduser,$herramientasxusuario->idherramienta)->get();

			$herramienta = Herramienta::find($herramientasxusuario->idherramienta);

			if($accionesxherramientaxusuario==null || $accionesxherramientaxusuario->isEmpty() )
				return Response::json(array( 'success' => false),200);

			return Response::json(array( 'success' => true,'acciones' => $accionesxherramientaxusuario,'nombre_herramienta'=>$herramienta->nombre),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function eliminar_acciones_herramienta(){
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$usuario_id = Input::get('usuario_id');
			
			$arr_idherramientaxtipo_solicitudxuser = Input::get('arr_idherramientaxtipo_solicitudxuser');
			$arr_checkbox = Input::get('arr_checkbox');

			$size_idhxtipoxuser = count($arr_idherramientaxtipo_solicitudxuser);
			$size_checkbox = count($arr_checkbox);

			if($size_idhxtipoxuser != $size_checkbox)
				return Response::json(array( 'success' => true),200);

			for($i = 0 ; $i<$size_idhxtipoxuser ; $i++){
				$accion = HerramientaXTipoSolicitudXUser::withTrashed()->find($arr_idherramientaxtipo_solicitudxuser[$i]);
				$habilitado = 0; //fue deshabilitado
				
				if($accion->deleted_at == null){
					$habilitado = 1; //no fue deshabilitado, está activo actualmente
				}

				if($habilitado == 1 && $arr_checkbox[$i] == 0){
					//debo deshabilitar
					$accion->delete();
				}else if($habilitado == 0 && $arr_checkbox[$i] == 1){
					//debo habilitar
					$accion->restore();
				}
			}		
			return Response::json(array( 'success' => true),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

}
