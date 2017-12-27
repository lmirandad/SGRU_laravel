<?php

class SectorController extends BaseController {

	public function submit_eliminar_sector_usuario(){
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$usuario_id = Input::get('usuario_id');
			$idusersxsector = Input::get('idusersxsector');
			
			//buscamos al idherramientaxuser
			$hu = UsersXSector::find($idusersxsector);
			if($hu==null)
				return Response::json(array( 'success' => false),200);

			$sector=Sector::find($hu->idsector);

			if($sector==null)
				return Response::json(array( 'success' => false),200);

			//validar si tiene solicitudes asignadas
			$solicitudes = Solicitud::buscarSolicitudesPendientesProcesandoPorSectorUsuario($hu->idsector,$hu->iduser)->get();
			
			if($solicitudes != null && !$solicitudes->isEmpty())
				return Response::json(array( 'success' => true,'tiene_solicitudes'=>true,'idusersxsector' => null,'nombre_sector'=>null),200);

			if(count($solicitudes) > 0 )
				return Response::json(array( 'success' => true,'tiene_solicitudes'=>true,'idusersxsector' => null,'nombre_sector'=>null),200);

			$hu->delete();
			

			return Response::json(array( 'success' => true,'tiene_solicitudes'=>false,'idusersxsector' => $idusersxsector,'nombre_sector'=>$sector->nombre),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function crear_sector()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				return View::make('Mantenimientos/Sectores_Canales_Entidades/Sectores/crearSector',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_sector()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$attributes = array(
					'nombre_sector' => 'Nombre del Sector',
					'descripcion' => 'Descripcion',
				);

				$messages = array();

				$rules = array(
					'nombre_sector' => 'required|max:100|alpha_num_spaces_slash_dash|unique:sector,nombre',
					'descripcion' => 'alpha_num_spaces_slash_dash_enter|max:200',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('sectores/crear_sector')->withErrors($validator)->withInput(Input::all());
				}else{
					$nombre_sector = Input::get('nombre_sector');
					$descripcion = Input::get('descripcion');
					
					$sector = new Sector;
					$sector->nombre = $nombre_sector;
					if(strcmp($descripcion,"") != 0)
						$sector->descripcion = $descripcion;
					$sector->iduser_created_by = $data["user"]->id;

					$sector->save();						

					Session::flash('message', 'Se registró correctamente el sector.');
					
					return Redirect::to('entidades_canales_sectores/listar/1');
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function editar_sector($idsector=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idsector)
			{	
				$data["sector"] = Sector::withTrashed()->find($idsector);

				if($data["sector"]==null){
					return Redirect::to('sectores/listar_sectores');
				}

				return View::make('Mantenimientos/Sectores_Canales_Entidades/Sectores/editarSector',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_editar_sector()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$user = User::find(Input::get('usuario_id'));
				$sector_id = Input::get('sector_id');
				$attributes = array(
					'nombre_sector' => 'Nombre del Sector',
					'descripcion' => 'Descripcion',
				);

				$messages = array();

				$rules = array(
					'nombre_sector' => 'required|max:100|alpha_num_spaces_slash_dash|unique:sector,nombre,'.$sector_id.',idsector',
					'descripcion' => 'alpha_num_spaces_slash_dash_enter|max:200',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					$sector_id = Input::get('sector_id');
					$url = "sectores/editar_sector"."/".$sector_id;
					return Redirect::to($url)->withErrors($validator)->withInput(Input::all());
				}else{	
					$sector_id = Input::get('sector_id');
					$url = "sectores/editar_sector"."/".$sector_id;		
					
					$nombre_sector = Input::get('nombre_sector');
					$descripcion = Input::get('descripcion');
					
					$sector = Sector::find($sector_id);
					$sector->nombre = $nombre_sector;
					$sector->descripcion = $descripcion;
					$sector->iduser_updated_by = $data["user"]->id;

					$sector->save();					
					
					return Redirect::to('entidades_canales_sectores/listar/1')->with('message', 'Se editó correctamente el sector: '.$sector->nombre);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	
	public function buscar_sectores()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2){
				$data["sector_search"] = Input::get('sector_search');
				$data["flag_seleccion"] = 1;
				if($data["sector_search"] == null){
					$data["sectores_data"] = Sector::listarSectores()->paginate(10);					
					return View::make('Mantenimientos/Sectores_Canales_Entidades/listarSectoresCanalesEntidades',$data);

				}else{
					$data["sectores_data"] = Sector::buscarSectores($data["sector_search"])->paginate(10);
					return View::make('Mantenimientos/Sectores_Canales_Entidades/listarSectoresCanalesEntidades',$data);
				}
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_sector($idsector=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idsector)
			{	
				$data["sector"] = Sector::withTrashed()->find($idsector);

				if($data["sector"]==null){						
					return Redirect::to('entidades_canales_sectores/listar/1')->with('error','Sector no encontrado');
				}
				$data["canales"] = Canal::buscarCanalesPorIdSector($data["sector"]->idsector)->get();
				$data["herramientas"] = HerramientaXSector::buscarHerramientasPorIdSector($data["sector"]->idsector)->get();
				return View::make('Mantenimientos/Sectores_Canales_Entidades/Sectores/mostrarSector',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_inhabilitar_sector()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$sector_id = Input::get('sector_id');
				$url = "sectores/mostrar_sector"."/".$sector_id;
				$sector = Sector::find($sector_id);
				
				//Validar si el sector posee canales activos
				$canales = Canal::buscarCanalesPorIdSector($sector->idsector)->get();

				if($canales == null || $canales->isEmpty()){
					//Esta vacio, se puede eliminar el sector
					$sector->delete();
				}else
				{
					//Por seguridad, se vuelve a revalidar la cantidad de canales asociados al sector
					$size_canales = count($canales);
					if($size_canales>0){
						Session::flash('error', 'No se puede inhabilitar el sector. El sector cuenta con canales activos.');
						return Redirect::to($url);
					}
					else
					{

						$solicitudes = Solicitud::buscarSolicitudesPendientesProcesandoPorSector($sector_id)->get();
						if($solicitudes != null && !$solicitudes->isEmpty() && count($solicitudes)>0)
						{
							Session::flash('error', 'No se puede inhabilitar el sector. El sector cuenta con solicitudes pendientes y/o procesando.');
							return Redirect::to($url);
						}
						$sector->delete();						
					}
				}


				Session::flash('message', 'Se inhabilitó correctamente el sector.');
				return Redirect::to($url);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_habilitar_sector()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$sector_id = Input::get('sector_id');
				$url = "sectores/mostrar_sector"."/".$sector_id;
				$sector = Sector::withTrashed()->find($sector_id);
				$sector->restore();
				Session::flash('message', 'Se habilitó correctamente el sector.');
				return Redirect::to($url);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_herramientas_sector($idsector=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) && $idsector)
			{	
				$data["sector"] = Sector::find($idsector);
				if($data["sector"]==null){						
					return Redirect::to('sectores/listar_sectores')->with('error','Sector no encontrado.');
				}
				$data["herramientas"] = HerramientaXSector::buscarHerramientasPorIdSector($data["sector"]->idsector)->get();
				$data["herramientas_disponibles"] = Herramienta::listarHerramientasDisponiblesSector($data["sector"]->idsector)->get();
				
				return View::make('Mantenimientos/Sectores_Canales_Entidades/Sectores/mostrarHerramientasSector',$data);			
				
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_agregar_herramientas(){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$sector = Sector::find(Input::get('sector_id'));				
				$sector_id = Input::get('sector_id');
				$arr_idherramienta = Input::get('ids_herramientas');
				/*echo '<pre>';
			    var_dump(count($arr_idherramienta));
			    echo '</pre>';*/
			    $size_ids = count($arr_idherramienta);
			    $flag_seleccion = false;
			    //por cada uno validar si el checkbox asociado fue tecleado
			    for($i=0;$i<$size_ids;$i++){
			    	$res_checkbox = Input::get('checkbox'.$i);
			    	
			    	if($res_checkbox == 1)
				    {
				    	//validar si ya existe el objeto
				    	$herramientaxsector = HerramientaXSector::buscarHerramientasPorIdSectorIdHerramienta($sector_id,$arr_idherramienta[$i])->get();
				    	
				    	if($herramientaxsector==null || $herramientaxsector->isEmpty()){
				    		//quiere decir que es una herramienta nueva a crear
				    		$herramientaxsector = new HerramientaXSector;
					    	$herramientaxsector->idherramienta = $arr_idherramienta[$i];
					    	$herramientaxsector->idsector = $sector_id;
					    	$herramientaxsector->save();
					    	
					    	//Agregar todos los tipos de acciones:
					    	$tipos_solicitud = TipoSolicitud::listarTiposSolicitud()->get();
					    	if(!$tipos_solicitud->isEmpty()){
					    		$size_tipos = count($tipos_solicitud);
					    		for($j=0;$j<$size_tipos;$j++){
					    			$herramientaxsectorxtipo_solicitud = new HerramientaXSectorXTipoSolicitud;
					    			$herramientaxsectorxtipo_solicitud->idtipo_solicitud = $tipos_solicitud[$j]->idtipo_solicitud;
					    			$herramientaxsectorxtipo_solicitud->idherramientaxsector = $herramientaxsector->idherramientaxsector;
					    			$herramientaxsectorxtipo_solicitud->save();
					    		}
					    	}
				    	}else{
				    		//quiere decir que solo se requiere restaurar
				    		$herramientaxsector = $herramientaxsector->first();
				    		$herramientaxsector->restore();
				    		$accionesHerramientaSector = HerramientaXSectorXTipoSolicitud::listarTipoSolicitudSector($sector_id,$herramientaxsector->idherramienta)->get();


				    		$size_acciones = count($accionesHerramientaSector);
				    		for($j=0;$j<$size_acciones;$j++){
				    			$accionesHerramientaSector[$j]->restore();
				    		}
				    	}

				    	$flag_seleccion = true;
				    }
			    }

			  	if($size_ids > 0)
			    {
			    	if($flag_seleccion)
			    		return Redirect::to('sectores/mostrar_herramientas_sector/'.$sector_id)->with('message', 'Se actualizaron correctamente los aplicativos al sector. <strong> Para hacer posible la asignación a estas herramientas, agregar los SLA\'s a cada herramienta agregada. </strong>');	
			    	else
			    		return Redirect::to('sectores/mostrar_herramientas_sector/'.$sector_id)->with('error', 'No se seleccionó ningún aplicativo.');	
			    }else
			    {
			    	return Redirect::to('sectores/mostrar_herramientas_sector/'.$sector_id)->with('error', 'No se agregaron los aplicativos al sector');	
			   	}
				
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_canales_herramientas(){
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		// Check if the current user is the "System Admin"
		$sector_id = Input::get('sector_id');
		$canales = Canal::buscarCanalesPorIdSector($sector_id)->get();
		$herramientas = HerramientaXSector::buscarHerramientasPorIdSector($sector_id)->get();
		
		if($canales==null || $canales->isEmpty())
			$canales = null;

		if($herramientas==null || $herramientas->isEmpty())
			$herramientas = null;

		return Response::json(array( 'success' => true,'canales' => $canales, 'herramientas' => $herramientas),200);			
		
	}
	

}
