<?php

class SolicitudController extends BaseController {

	public function cargar_solicitudes()
	{
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"]= Session::get('user');
		return View::make('Solicitudes/cargarSolicitudes',$data);
	}

	public function listar_solicitudes()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2){
				$data["search_solicitud"] = null;
				$data["fecha_solicitud"] = null;
				$data["search_tipo_solicitud"] = null;
				$data["search_estado_solicitud"] = null;
				$data["tipos_solicitud"] = TipoSolicitud::lists('nombre','idtipo_solicitud');
				$data["estados_solicitud"] = EstadoSolicitud::lists('nombre','idestado_solicitud');
				//$data["solicitudes_data"] = Solicitud::listarSolicitudes()->paginate(10);
				return View::make('Solicitudes/listarSolicitudes',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}



}
