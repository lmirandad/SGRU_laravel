<?php

class HerramientaController extends BaseController {

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
			$herramientaUsuario = HerramientaXUser::find($idherramientaxusers);
			if($herramientaUsuario==null)
				return Response::json(array( 'success' => false),200);

			$herramienta =Herramienta::find($herramientaUsuario->idherramienta);

			if($herramienta==null)
				return Response::json(array( 'success' => false),200);

			//validar si tiene solicitudes asignadas
			$solicitudes = Solicitud::buscarSolicitudesPendientesProcesandoPorHerramientaUsuario($herramientaUsuario->idherramienta,$herramientaUsuario->iduser)->get();
			
			if($solicitudes != null && !$solicitudes->isEmpty())
				return Response::json(array( 'success' => true,'tiene_solicitudes'=>true,'idherramientaxusers' => null,'nombre_herramienta'=>null),200);

			if(count($solicitudes) > 0 )
				return Response::json(array( 'success' => true,'tiene_solicitudes'=>true,'idherramientaxusers' => null,'nombre_herramienta'=>null),200);


			$accionesHerramientaUsuario = HerramientaXTipoSolicitudXUser::listarTipoSolicitudUsuario($usuario_id,$herramienta->idherramienta)->get();
			
			if($accionesHerramientaUsuario == null || $accionesHerramientaUsuario->isEmpty()){
				return Response::json(array( 'success' => false),200);				
			}

			$size_acciones = count($accionesHerramientaUsuario);
			for($i=0;$i<$size_acciones;$i++){
				$accionesHerramientaUsuario[$i]->delete();
			}

			$herramientaUsuario->delete();
			

			return Response::json(array( 'success' => true,'tiene_solicitudes'=>false,'idherramientaxusers' => $idherramientaxusers,'nombre_herramienta'=>$herramienta->nombre),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function submit_eliminar_herramienta_sector(){
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$sector_id = Input::get('sector_id');
			$idherramientaxsector = Input::get('idherramientaxsector');
			
			//buscamos al idherramientaxuser
			$herramientaSector = HerramientaXSector::find($idherramientaxsector);
			if($herramientaSector==null)
				return Response::json(array( 'success' => false),200);

			$herramienta =Herramienta::find($herramientaSector->idherramienta);

			if($herramienta==null)
				return Response::json(array( 'success' => false),200);

			//Validar si tiene SLA's vigentes en el sector
			$sla = Sla::buscarSlaVigentePorIdHerramientaXSector($idherramientaxsector)->get();

			if($sla!=null && !$sla->isEmpty() && count($sla)>0)
				return Response::json(array( 'success' => true,'tiene_sla'=>true,'tiene_solicitudes'=>false,'idherramientaxsector' => $idherramientaxsector,'nombre_herramienta'=>$herramienta->nombre),200);

			//Validar si tiene solicitudes pendientes o procesando
			$solicitudes = Solicitud::buscarSolicitudesPendientesProcesandoPorHerramientaSector($herramientaSector->idherramienta,$herramientaSector->idsector)->get();

			if($solicitudes!=null && !$solicitudes->isEmpty() && count($solicitudes)>0)
				return Response::json(array( 'success' => true,'tiene_sla'=>false,'tiene_solicitudes'=>true,'idherramientaxsector' => $idherramientaxsector,'nombre_herramienta'=>$herramienta->nombre),200);


			$accionesHerramientaSector = HerramientaXSectorXTipoSolicitud::listarTipoSolicitudSector($sector_id,$herramienta->idherramienta)->get();
			
			if($accionesHerramientaSector == null || $accionesHerramientaSector->isEmpty()){
				return Response::json(array( 'success' => false),200);				
			}

			$size_acciones = count($accionesHerramientaSector);
			for($i=0;$i<$size_acciones;$i++){
				$accionesHerramientaSector[$i]->delete();
			}

			$herramientaSector->delete();
			

			return Response::json(array( 'success' => true,'tiene_sla'=>false,'tiene_solicitudes'=>false,'idherramientaxsector' => $idherramientaxsector,'nombre_herramienta'=>$herramienta->nombre),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function listar_herramientas()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2 ){
				$data["search"] = null;
				$data["search_denominacion_herramienta"] = null;
				$data["denominacion_herramienta"] = DenominacionHerramienta::lists('nombre','iddenominacion_herramienta');
				$data["herramientas_data"] = Herramienta::withTrashed()->listarHerramientas()->paginate(10);
				return View::make('Mantenimientos/Herramientas/listarHerramientas',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function buscar_herramientas()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2){
				$data["search"] = Input::get('search');
				$data["search_denominacion_herramienta"] = Input::get('search_denominacion_herramienta');;
				$data["denominacion_herramienta"] = DenominacionHerramienta::lists('nombre','iddenominacion_herramienta');
				if($data["search"] == null && $data["search_denominacion_herramienta"]== 0){
					$data["herramientas_data"] = Herramienta::withTrashed()->listarHerramientas()->paginate(10);
					return View::make('Mantenimientos/Herramientas/listarHerramientas',$data);

				}else{
					$data["herramientas_data"] = Herramienta::withTrashed()->buscarHerramientas($data["search"],$data["search_denominacion_herramienta"])->paginate(10);
					return View::make('Mantenimientos/Herramientas/listarHerramientas',$data);	
				}				
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function crear_herramienta()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){

				$data["denominaciones"] = DenominacionHerramienta::lists('nombre','iddenominacion_herramienta');
				$data["tipos_requerimiento"] = TipoRequerimiento::lists('nombre','idtipo_requerimiento');
				
				
				return View::make('Mantenimientos/Herramientas/crearHerramienta',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_herramienta()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$attributes = array(
					'nombre_herramienta' => 'Nombre de Aplicativo',
					'descripcion' => 'Descripcion',
					'flag_seguridad' => 'Valida Seguridad',
					'denominacion_herramienta' => 'Categoría Aplicativo',
					'tipo_requerimiento' => 'Tipo Atención Requerimientos',
				);

				$messages = array();

				$rules = array(
							'nombre_herramienta' => 'required|max:100|alpha_num_spaces_slash_dash|unique:herramienta,nombre',
							'descripcion' => 'alpha_num_spaces_slash_dash_enter|max:200',
							'flag_seguridad' => 'required',
							'denominacion_herramienta' => 'required',
							'tipo_requerimiento' => 'required',
						);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('herramientas/crear_herramienta')->withErrors($validator)->withInput(Input::all());
				}else{
					$nombre_herramienta = Input::get('nombre_herramienta');
					$descripcion = Input::get('descripcion');
					$flag_seguridad = Input::get('flag_seguridad');
					$denominacion_herramienta = Input::get('denominacion_herramienta');
					$tipo_requerimiento = Input::get('tipo_requerimiento');


					$tipos_solicitud = TipoSolicitud::listarTiposSolicitud()->get();
					
					if($tipos_solicitud == null || $tipos_solicitud->isEmpty())
					{
						Session::flash('error', 'Error al realizar el registro. No existe tipo de acciones en el sistema.');
						return Redirect::to('herramientas/crear_herramienta');					
					}

					$herramienta = new Herramienta;
					$herramienta->nombre = $nombre_herramienta;
					if(strcmp($descripcion,"") != 0)
						$herramienta->descripcion = $descripcion;
					$herramienta->flag_seguridad = $flag_seguridad;
					$herramienta->iddenominacion_herramienta = $denominacion_herramienta;
					$herramienta->idtipo_requerimiento = $tipo_requerimiento;
					$herramienta->iduser_created_by = $data["user"]->id;
					


					$herramienta->save();	

					
					$size_tipos = count($tipos_solicitud);
					for($i=0;$i<$size_tipos;$i++){
						$herramientaxtipo_solicitud = new HerramientaXTipoSolicitud;
						$herramientaxtipo_solicitud->idherramienta = $herramienta->idherramienta;
						$herramientaxtipo_solicitud->idtipo_solicitud = $tipos_solicitud[$i]->idtipo_solicitud;
						$herramientaxtipo_solicitud->save();
					}

					return Redirect::to('herramientas/listar_herramientas')->with('message', 'Se registró correctamente la herramienta.');
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function editar_herramienta($idherramienta=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idherramienta)
			{	
				$data["denominaciones"] = DenominacionHerramienta::lists('nombre','iddenominacion_herramienta');
				$data["herramienta"] = Herramienta::withTrashed()->find($idherramienta);
				$data["equivalencias"] = HerramientaEquivalencia::buscarEquivalenciasPorIdHerramienta($idherramienta)->get();
				$data["tipos_requerimiento"] = TipoRequerimiento::lists('nombre','idtipo_requerimiento');

				if($data["herramienta"]==null){
					return Redirect::to('herramientas/listar_herramientas');
				}

				return View::make('Mantenimientos/Herramientas/editarHerramienta',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_editar_herramienta()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$user = User::find(Input::get('usuario_id'));
				$herramienta_id = Input::get('herramienta_id');
				$attributes = array(
					'nombre_herramienta' => 'Nombre de Aplicativo',
					'descripcion' => 'Descripcion',
					'flag_seguridad' => 'Valida Seguridad',
					'denominacion_herramienta' => 'Categoría Aplicativo',
					'tipo_requerimiento' => 'Tipo Atención Requerimientos',
				);

				$messages = array();

				$rules = array(
					'nombre_herramienta' => 'required|max:100|alpha_num_spaces_slash_dash|unique:herramienta,nombre,'.$herramienta_id.',idherramienta',
					'descripcion' => 'alpha_num_spaces_slash_dash_enter|max:200',
					'flag_seguridad' => 'required',
					'denominacion_herramienta' => 'required',
					'tipo_requerimiento' => 'required',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					$herramienta_id = Input::get('herramienta_id');
					$url = "herramientas/editar_herramienta"."/".$herramienta_id;
					return Redirect::to($url)->withErrors($validator)->withInput(Input::all());
				}else{	
					$herramienta_id = Input::get('herramienta_id');
					$url = "herramientas/editar_herramienta"."/".$herramienta_id;		
					
					$nombre_herramienta = Input::get('nombre_herramienta');
					$descripcion = Input::get('descripcion');
					$flag_seguridad = Input::get('flag_seguridad');
					$denominacion_herramienta = Input::get('denominacion_herramienta');
					$tipo_requerimiento = Input::get('tipo_requerimiento');
					
					$tipos_solicitud = TipoSolicitud::listarTiposSolicitud()->get();
					
					if($tipos_solicitud == null || $tipos_solicitud->isEmpty())
					{
						Session::flash('error', 'Error al realizar el registro. No existe tipo de acciones en el sistema.');
						return Redirect::to('herramientas/crear_herramienta');					
					}

					$herramienta = Herramienta::find($herramienta_id);
					$herramienta->nombre = $nombre_herramienta;
					if(strcmp($descripcion,"") != 0)
						$herramienta->descripcion = $descripcion;
					$herramienta->flag_seguridad = $flag_seguridad;
					$herramienta->iddenominacion_herramienta = $denominacion_herramienta;
					$herramienta->idtipo_requerimiento = $tipo_requerimiento;
					$herramienta->iduser_updated_by = $data["user"]->id;
					
					$herramienta->save();	

					return Redirect::to('herramientas/listar_herramientas')->with('message', 'Se editó correctamente el aplicativo: '.$herramienta->nombre);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_agregar_equivalencia(){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$herramienta_id = Input::get('herramienta_id');
				$attributes = array(
					'nombre_equivalencia' => 'Nombre de Aplicativo (Equivalencia)',
				);

				$messages = array();

				$rules = array(
					'nombre_equivalencia' => 'required|max:100|alpha_num_spaces_slash_dash|unique:herramienta_equivalencia,nombre_equivalencia',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					$herramienta_id = Input::get('herramienta_id');
					$url = "herramientas/editar_herramienta"."/".$herramienta_id;
					return Redirect::to($url)->withErrors($validator)->withInput(Input::all());
				}else{	
					$herramienta_id = Input::get('herramienta_id');
					$url = "herramientas/editar_herramienta"."/".$herramienta_id;		
					
					$equivalencia = new HerramientaEquivalencia;
					$equivalencia->nombre_equivalencia = Input::get('nombre_equivalencia');
					$equivalencia->idherramienta = $herramienta_id;
					$equivalencia->iduser_created_by = $data["user"]->id;
					
					$equivalencia->save();	

					$herramienta = Herramienta::find($herramienta_id);

					return Redirect::to($url)->with('message', 'Se editó correctamente el aplicativo: '.$herramienta->nombre);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_herramienta($idherramienta=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idherramienta)
			{	
				$data["herramienta"] = Herramienta::withTrashed()->find($idherramienta);
				
				if($data["herramienta"]==null){
					return Redirect::to('herramientas/listar_herramientas');
				}

				$data["sectores"] = HerramientaXSector::buscarSectorPorIdHerramienta($data["herramienta"]->idherramienta)->get();
				$data["usuarios"] = HerramientaXUser::buscarUsuariosPorIdHerramienta($data["herramienta"]->idherramienta)->get();
				$data["denominaciones"] = DenominacionHerramienta::lists('nombre','iddenominacion_herramienta');
				$data["equivalencias"] = HerramientaEquivalencia::buscarEquivalenciasPorIdHerramienta($idherramienta)->get();
				$data["tipos_requerimiento"] = TipoRequerimiento::lists('nombre','idtipo_requerimiento');
				return View::make('Mantenimientos/Herramientas/mostrarHerramienta',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_inhabilitar_herramienta()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$herramienta_id = Input::get('herramienta_id');
				$url = "herramientas/mostrar_herramienta"."/".$herramienta_id;
				$herramienta = Herramienta::find($herramienta_id);
				
				//Validar si la herramienta posee solicitudes pendientes o en proceso

				$sectores = HerramientaXSector::buscarSectorPorIdHerramienta($herramienta_id)->get();
				$usuarios = HerramientaXUser::buscarUsuariosPorIdHerramienta($herramienta_id)->get();
				

				if( ($sectores == null || $sectores->isEmpty()) && ($usuarios == null || $usuarios->isEmpty()) ){
					//Esta vacio, se puede eliminar la herramienta					
					$perfiles = PerfilAplicativo::buscarPerfilesPorHerramienta($herramienta->idherramienta)->get();
						
					if($perfiles == null || $perfiles->isEmpty())
					{
						$herramienta->delete();
					}else{
						if(count($perfiles) > 0){
							Session::flash('error', 'No se puede inhabilitar la herramienta. La herramienta cuenta con perfiles activo.');
							return Redirect::to($url);
						}else{
							$herramienta->delete();
						}	
					}

				}else
				{
					//Por seguridad, se vuelve a revalidar la cantidad de solicitudes pendientes o procesando
					$size_sectores = count($sectores);
					$size_usuarios = count($usuarios);
					if($size_sectores>0 || $size_usuarios>0){
						Session::flash('error', 'No se puede inhabilitar el aplicativo. El aplicativo cuenta con usuarios y/o sectores asignados.');

						return Redirect::to($url);
					}
					else
						$herramienta->delete();						
				}
				
				Session::flash('message', 'Se inhabilitó correctamente el aplicativo.');
				return Redirect::to($url);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_habilitar_herramienta()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$herramienta_id = Input::get('herramienta_id');
				$url = "herramientas/mostrar_herramienta"."/".$herramienta_id;
				$herramienta = Herramienta::withTrashed()->find($herramienta_id);
				$herramienta->restore();
				Session::flash('message', 'Se habilitó correctamente el aplicativo.');
				return Redirect::to($url);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function eliminar_equivalencia()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		// Check if the current user is the "System Admin"
		$idherramienta_equivalencia = Input::get('idherramienta_equivalencia');
		$herramienta_equivalencia = HerramientaEquivalencia::find($idherramienta_equivalencia);

		if($herramienta_equivalencia == null)
			return Response::json(array( 'success' => true,'herramienta_equivalencia'=>null),200);

		$idherramienta = Input::get('idherramienta');
		$herramienta = Herramienta::find($idherramienta);

		if(strcmp($herramienta->nombre,$herramienta_equivalencia->nombre_equivalencia) == 0)
			return Response::json(array( 'success' => true,'herramienta_equivalencia'=>1),200);

		$herramienta_equivalencia->forceDelete();

		return Response::json(array( 'success' => true, 'herramienta_equivalencia' => $herramienta_equivalencia),200);		
	}

}
